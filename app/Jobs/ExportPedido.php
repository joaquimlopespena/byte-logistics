<?php

namespace App\Jobs;

use App\Events\ExportarPedidosEvent;
use App\Models\Pedido;
use App\Models\PedidoExport;
use App\Service\FiltroPedidoService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Storage;
use RuntimeException;
use Throwable;

/**
 * Exporta pedidos em CSV por streaming (baixo uso de memória).
 *
 * - Cursor lazyById: lê a BD em blocos sem OFFSET caro nem carregar o dataset inteiro.
 * - fputcsv direto em disco: evita PhpSpreadsheet (inviável para milhões de linhas).
 * - Ficheiro .part + rename: evita download de export a meio da escrita.
 *
 * Ordenação: por id ascendente (requisito técnico para lazyById estável).
 */
class ExportPedido implements ShouldQueue
{
    use Queueable;

    public int $timeout = 3600;

    /** Tamanho do bloco de leitura na BD (ajustar conforme colunas / memória do worker). */
    private const CHUNK_SIZE = 500;

    /** Parâmetros explícitos para fputcsv (PHP 8.4+ deprecia omissão de $escape). */
    private const CSV_SEPARATOR = ';';

    private const CSV_ENCLOSURE = '"';

    private const CSV_ESCAPE = '\\';

    public function __construct(
        protected int $userId,
        protected int $pedidoExportId
    ) {
        $this->onQueue('default');
        $this->onConnection('redis');
    }

    public function handle(FiltroPedidoService $filtroPedidoService): void
    {
        $export = PedidoExport::query()->find($this->pedidoExportId);
        if ($export === null || (int) $export->user_id !== $this->userId) {
            throw new RuntimeException('Exportação inválida ou usuário não autorizado.');
        }

        $relativePath = $this->exportarPedidos($export, $filtroPedidoService);

        $export->refresh();

        event(new ExportarPedidosEvent(
            $this->userId,
            $export->id,
            $relativePath,
            $export->completed_at?->timezone(config('app.timezone'))->format('d/m/Y H:i') ?? now()->format('d/m/Y H:i'),
        ));
    }

    private function exportarPedidos(PedidoExport $export, FiltroPedidoService $filtroPedidoService): string
    {
        $data = is_array($export->filters) ? $export->filters : [];

        $query = $filtroPedidoService->aplicarFiltros(
            Pedido::query()->withoutGlobalScope('user'),
            $data,
            $export->user_id
        )
            ->with(['transportadora:id,nome'])
            ->orderBy('id');

        $export->update([
            'status' => PedidoExport::STATUS_PROCESSING,
            'error_message' => null,
        ]);

        $relativePath = 'exports/pedidos-'.$export->id.'.csv';
        $disk = Storage::disk($export->disk);
        $disk->makeDirectory('exports');

        $fullPath = $disk->path($relativePath);
        $tempPath = $fullPath.'.part';

        $handle = fopen($tempPath, 'wb');
        if ($handle === false) {
            throw new RuntimeException('Não foi possível criar o arquivo de exportação.');
        }

        try {
            fwrite($handle, "\xEF\xBB\xBF");
            fputcsv($handle, [
                'ID', 'Cliente', 'Produto', 'Transportadora', 'Qtd', 'Preço', 'Total', 'Data',
            ], self::CSV_SEPARATOR, self::CSV_ENCLOSURE, self::CSV_ESCAPE);

            foreach ($query->lazyById(self::CHUNK_SIZE, 'id') as $row) {
                fputcsv($handle, [
                    $row->id,
                    $row->cliente_nome,
                    $row->produto,
                    $row->transportadora?->nome ?? '',
                    $row->quantidade,
                    $row->preco,
                    $row->total,
                    $row->created_at?->format('Y-m-d H:i:s') ?? '',
                ], self::CSV_SEPARATOR, self::CSV_ENCLOSURE, self::CSV_ESCAPE);
            }
        } finally {
            fclose($handle);
        }

        if (! rename($tempPath, $fullPath)) {
            throw new RuntimeException('Não foi possível finalizar o arquivo de exportação.');
        }

        $export->update([
            'status' => PedidoExport::STATUS_COMPLETED,
            'path' => $relativePath,
            'completed_at' => now(),
        ]);

        return $relativePath;
    }

    public function failed(?Throwable $exception): void
    {
        $export = PedidoExport::query()->find($this->pedidoExportId);
        if ($export === null) {
            return;
        }

        $partPath = Storage::disk($export->disk)->path('exports/pedidos-'.$export->id.'.csv.part');
        if (is_file($partPath)) {
            @unlink($partPath);
        }

        $export->update([
            'status' => PedidoExport::STATUS_FAILED,
            'error_message' => $exception?->getMessage() ?? 'Falha desconhecida.',
        ]);
    }
}

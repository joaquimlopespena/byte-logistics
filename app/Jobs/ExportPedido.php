<?php

namespace App\Jobs;

use App\Models\Pedido;
use App\Models\PedidoExport;
use App\Service\FiltroPedidoService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Storage;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

use Throwable;

/*
* \App\Jobs\ExportPedido::dispatchSync(1,1);
* @date: 2026-04-06
* @description: Job para exportar pedidos em CSV
*/
class ExportPedido implements ShouldQueue
{
    use Queueable;

    public int $timeout = 3600;

    /**
     * @param int $userId
     * @param int $pedidoExportId
     */
    public function __construct(
        protected int $userId,
        protected int $pedidoExportId
    ) {}

    public function handle(FiltroPedidoService $filtroPedidoService): void
    {
        $export = PedidoExport::find($this->pedidoExportId);
        if ($export === null || (int) $export->user_id !== $this->userId) {
            throw new \RuntimeException('Exportação inválida ou usuário não autorizado.');
        }

        $this->exportarPedidos($export, $filtroPedidoService);
    }

    private function exportarPedidos(PedidoExport $export, FiltroPedidoService $filtroPedidoService): void
    {

        $data = !empty($export->filters) ? json_decode(json_encode($export->filters), true) : [];

        $pedidos = (new FiltroPedidoService)->filtrarPedidos($data, $export->user_id)->limit(1)->get();

        dd($pedidos);

        $export->update([
            'status' => PedidoExport::STATUS_PROCESSING,
            'error_message' => null,
        ]);

        $relativePath = 'exports/pedidos-'.$export->id.'.csv';
        $disk = Storage::disk($export->disk);
        $disk->makeDirectory('exports');

        $fullPath = $disk->path($relativePath);
        $handle = fopen($fullPath, 'w');

        if ($handle === false) {
            throw new \RuntimeException('Não foi possível criar o arquivo CSV.');
        }

        fprintf($handle, chr(0xEF).chr(0xBB).chr(0xBF));
        fputcsv($handle, [
            'id',
            'descricao',
            'cliente_nome',
            'produto',
            'preco',
            'quantidade',
            'total',
            'transportadora',
            'criado_em',
        ], ';');

        //$filters = $export->filters ?? $this->data;

        $query = Pedido::withoutGlobalScope('user')
            ->where('pedidos.user_id', $export->user_id);

        $filtroPedidoService->aplicarFiltros($query, $filters);

        $query->leftJoin('transportadoras', 'pedidos.transportadora_id', '=', 'transportadoras.id')
            ->select([
                'pedidos.id',
                'pedidos.descricao',
                'pedidos.cliente_nome',
                'pedidos.produto',
                'pedidos.preco',
                'pedidos.quantidade',
                'pedidos.total',
                'pedidos.created_at',
                'transportadoras.nome as transportadora_nome',
            ])
            ->orderBy('pedidos.id');

        foreach ($query->cursor() as $row) {
            fputcsv($handle, [
                $row->id,
                $row->descricao,
                $row->cliente_nome,
                $row->produto,
                number_format((float) $row->preco, 2, ',', ''),
                $row->quantidade,
                number_format((float) $row->total, 2, ',', ''),
                $row->transportadora_nome ?? '',
                $row->created_at?->format('Y-m-d H:i:s') ?? '',
            ], ';');
        }

        fclose($handle);

        $export->update([
            'status' => PedidoExport::STATUS_COMPLETED,
            'path' => $relativePath,
            'completed_at' => now(),
        ]);
    }

    public function failed(?Throwable $exception): void
    {
        $export = PedidoExport::query()->find($this->pedidoExportId);
        if ($export === null) {
            return;
        }

        $export->update([
            'status' => PedidoExport::STATUS_FAILED,
            'error_message' => $exception?->getMessage() ?? 'Falha desconhecida.',
        ]);
    }
}

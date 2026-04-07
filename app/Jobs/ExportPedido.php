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
        try {
            $export = PedidoExport::find($this->pedidoExportId);
            if ($export === null || (int) $export->user_id !== $this->userId) {
                throw new \RuntimeException('Exportação inválida ou usuário não autorizado.');
            }

            $this->exportarPedidos($export, $filtroPedidoService);
        } catch (\Exception $th) {
            $this->failed($th);
        }
    }

    private function exportarPedidos(PedidoExport $export, FiltroPedidoService $filtroPedidoService): void
    {

        $data = !empty($export->filters) ? json_decode(json_encode($export->filters), true) : [];

        $pedidos = (new FiltroPedidoService)->filtrarPedidos($data, $export->user_id)->limit(1)->get();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet(); // ID	Cliente	Produto	Transportadora	Qtd	Preço		Data

        $sheet->setCellValue('A1', 'ID');
        $sheet->setCellValue('C1', 'Cliente');
        $sheet->setCellValue('D1', 'Produto');
        $sheet->setCellValue('E1', 'Transportadora');
        $sheet->setCellValue('F1', 'Qtd');
        $sheet->setCellValue('G1', 'Preço');
        $sheet->setCellValue('H1', 'Total');
        $sheet->setCellValue('I1', 'Data');

        $export->update([
            'status' => PedidoExport::STATUS_PROCESSING,
            'error_message' => null,
        ]);

        $rowIndex = 2;
        foreach ($pedidos as $row) {
            $sheet->setCellValue('A'.$rowIndex, $row->id);
            $sheet->setCellValue('C'.$rowIndex, $row->cliente_nome);
            $sheet->setCellValue('D'.$rowIndex, $row->produto);
            $sheet->setCellValue('E'.$rowIndex, $row?->transportadora?->nome ?? '');
            $sheet->setCellValue('F'.$rowIndex, $row->quantidade);
            $sheet->setCellValue('G'.$rowIndex, (float) $row?->preco ?? 0);
            $sheet->setCellValue('H'.$rowIndex, (float) $row?->total ?? 0);
            $sheet->setCellValue('I'.$rowIndex, $row->created_at?->format('Y-m-d H:i:s') ?? '');
            $rowIndex++;
        }

        $relativePath = 'exports/pedidos-'.$export->id.'.xlsx';
        $disk = Storage::disk($export->disk);
        $disk->makeDirectory('exports');

        $fullPath = $disk->path($relativePath);

        (new Xlsx($spreadsheet))->save($fullPath);

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

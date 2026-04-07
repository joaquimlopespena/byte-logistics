<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Jobs\ExportPedido;
use App\Models\PedidoExport;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class PedidoExportController extends Controller
{
    public function create(Request $request): View
    {
        $exports = PedidoExport::query()
            ->where('user_id', $request->user()->id)
            ->latest()
            ->limit(15)
            ->get();

        return view('admin.pedidos.export', compact('exports'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'search' => ['nullable', 'string', 'max:255'],
            'data_inicio' => ['nullable', 'date_format:Y-m-d'],
            'data_fim' => ['nullable', 'date_format:Y-m-d'],
        ]);

        $export = PedidoExport::create([
            'user_id' => Auth::user()->id,
            'status' => PedidoExport::STATUS_PENDING,
            'filters' => ! empty($validated) ? $validated : null,
            'disk' => 'local',
        ]);

        ExportPedido::dispatch(
            Auth::user()->id,
            $export->id
        );

        return redirect()
            ->route('admin.pedidos.export')
            ->with('success', 'Exportação enfileirada. Em instantes o arquivo ficará disponível para download.');
    }

    public function download(Request $request, PedidoExport $pedidoExport): BinaryFileResponse|RedirectResponse
    {
        if ((int) $pedidoExport->user_id !== (int) $request->user()->id) {
            abort(403);
        }

        if ($pedidoExport->status !== PedidoExport::STATUS_COMPLETED || $pedidoExport->path === null) {
            return redirect()
                ->route('admin.pedidos.export')
                ->with('error', 'Exportação ainda não está pronta ou falhou.');
        }

        if (! Storage::disk($pedidoExport->disk)->exists($pedidoExport->path)) {
            return redirect()
                ->route('admin.pedidos.export')
                ->with('error', 'Arquivo não encontrado. Gere uma nova exportação.');
        }

        $name = 'pedidos-'.$pedidoExport->id.'-'.now()->format('Y-m-d').'.csv';
        $disk = Storage::disk($pedidoExport->disk);

        return response()->download(
            $disk->path($pedidoExport->path),
            $name,
            ['Content-Type' => 'text/csv; charset=UTF-8']
        )->deleteFileAfterSend(false);
    }
}

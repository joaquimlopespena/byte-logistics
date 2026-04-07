<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ExportarPedidosEvent implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public int $userId,
        public int $pedidoExportId,
        public string $relativePath,
        public string $completedAtFormatted,
    ) {}

    /**
     * @return array<int, PrivateChannel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('App.Models.User.'.$this->userId),
        ];
    }

    /**
     * Nome do evento no cliente (Echo: .export.pedidos.completed).
     */
    public function broadcastAs(): string
    {
        return 'export.pedidos.completed';
    }

    /**
     * @return array<string, mixed>
     */
    public function broadcastWith(): array
    {
        return [
            'pedido_export_id' => $this->pedidoExportId,
            'relative_path' => $this->relativePath,
            'completed_at_formatted' => $this->completedAtFormatted,
            'download_url' => route('admin.pedidos.export.download', ['pedidoExport' => $this->pedidoExportId]),
        ];
    }
}

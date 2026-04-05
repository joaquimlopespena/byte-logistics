<?php

namespace App\Service;

use App\Models\Pedido;
use App\Models\Transportadora;
use Illuminate\Support\Facades\Cache;

class DashboardStatsService
{
    /** Tempo em segundos para reutilizar agregados pesados (count/sum na tabela de pedidos). */
    private const CACHE_TTL_SECONDS = 60;

    private const CACHE_KEY = 'admin.dashboard.pedido_aggregates';

    public static function forgetCache(): void
    {
        Cache::forget(self::CACHE_KEY);
    }

    /**
     * @return array{
     *     total_pedidos: int,
     *     total_vendido: float,
     *     pedidos_hoje: int,
     *     ticket_medio: float,
     *     transportadoras: int
     * }
     */
    public function stats(): array
    {
        $cached = Cache::remember(self::CACHE_KEY, now()->addSeconds(self::CACHE_TTL_SECONDS), function (): array {
            $row = Pedido::query()
                ->selectRaw('COUNT(*) as total_pedidos')
                ->selectRaw('COALESCE(SUM(total), 0) as total_vendido')
                ->first();

            $totalPedidos = (int) ($row->total_pedidos ?? 0);
            $totalVendido = (float) ($row->total_vendido ?? 0.0);

            return [
                'total_pedidos' => $totalPedidos,
                'total_vendido' => $totalVendido,
                'pedidos_hoje' => Pedido::query()->whereDate('created_at', now()->today())->count(),
                'transportadoras' => Transportadora::query()->count(),
            ];
        });

        $totalPedidos = $cached['total_pedidos'];
        $totalVendido = $cached['total_vendido'];
        $ticketMedio = $totalPedidos > 0 ? $totalVendido / $totalPedidos : 0.0;

        return [
            ...$cached,
            'ticket_medio' => $ticketMedio,
        ];
    }
}

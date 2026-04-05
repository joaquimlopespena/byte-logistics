<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pedido;
use App\Service\DashboardStatsService;
use Illuminate\Support\Number;

class DashboardController extends Controller
{
    public function index(DashboardStatsService $dashboardStats)
    {
        $raw = $dashboardStats->stats();

        $stats = [
            'total_pedidos' => $raw['total_pedidos'],
            'total_pedidos_label' => Number::format($raw['total_pedidos'], locale: 'pt_BR'),
            'total_vendido' => $raw['total_vendido'],
            'total_vendido_label' => Number::currency($raw['total_vendido'], 'BRL', 'pt_BR'),
            'pedidos_hoje' => $raw['pedidos_hoje'],
            'ticket_medio' => round($raw['ticket_medio'], 2),
            'ticket_medio_label' => Number::currency($raw['ticket_medio'], 'BRL', 'pt_BR'),
            'transportadoras' => $raw['transportadoras'],
        ];

        $recentOrders = Pedido::query()
            ->latest('created_at')
            ->limit(5)
            ->get(['cliente_nome', 'produto', 'total', 'created_at']);

        return view('admin.dashboard', compact('stats', 'recentOrders'));
    }
}

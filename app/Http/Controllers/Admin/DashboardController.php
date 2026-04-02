<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pedido;
use App\Models\Transportadora;
use Illuminate\Support\Number;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_pedidos' => Pedido::count(),
            'total_pedidos_label' => Number::format(Pedido::count(), locale: 'pt_BR'),
            'total_vendido' => Pedido::sum('total'),
            'total_vendido_label' => Number::currency(Pedido::sum('total'), 'BRL', 'pt_BR'),
            'pedidos_hoje' => Pedido::whereDate('created_at', now()->today())->count(),
            'ticket_medio' => Pedido::count() > 0 ? round(Pedido::sum('total') / Pedido::count(), 2) : 0.0,
            'ticket_medio_label' => Number::currency(
                Pedido::count() > 0 ? Pedido::sum('total') / Pedido::count() : 0,
                'BRL',
                'pt_BR'
            ),
            'transportadoras' => Transportadora::count(),
        ];

        $recentOrders = Pedido::orderBy('created_at', 'desc')->take(5)->get();
        return view('admin.dashboard', compact('stats', 'recentOrders'));
    }
}

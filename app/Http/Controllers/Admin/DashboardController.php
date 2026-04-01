<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Number;

class DashboardController extends Controller
{
    public function index()
    {
        $totalPedidos = 12_847;
        $totalVendido = 2_847_392.50;

        $stats = [
            'total_pedidos' => $totalPedidos,
            'total_pedidos_label' => Number::format($totalPedidos, locale: 'pt_BR'),
            'total_vendido' => $totalVendido,
            'total_vendido_label' => Number::currency($totalVendido, 'BRL', 'pt_BR'),
            'pedidos_hoje' => 23,
            'ticket_medio' => $totalPedidos > 0 ? round($totalVendido / $totalPedidos, 2) : 0.0,
            'ticket_medio_label' => Number::currency(
                $totalPedidos > 0 ? $totalVendido / $totalPedidos : 0,
                'BRL',
                'pt_BR'
            ),
            'transportadoras' => 12,
        ];

        $recentOrders = [
            ['cliente' => 'Maria Silva', 'produto' => 'iPhone 15', 'total' => 5_499.00, 'status' => 'Entregue'],
            ['cliente' => 'João Santos', 'produto' => 'MacBook Air M3', 'total' => 8_299.00, 'status' => 'Em trânsito'],
            ['cliente' => 'Ana Costa', 'produto' => 'Monitor LG 27"', 'total' => 1_249.90, 'status' => 'Processando'],
            ['cliente' => 'Pedro Oliveira', 'produto' => 'Teclado Mecânico', 'total' => 459.00, 'status' => 'Entregue'],
            ['cliente' => 'Carla Mendes', 'produto' => 'SSD NVMe 1TB', 'total' => 389.90, 'status' => 'Entregue'],
        ];

        foreach ($recentOrders as &$row) {
            $row['total_label'] = Number::currency($row['total'], 'BRL', 'pt_BR');
        }
        unset($row);

        return view('admin.dashboard', compact('stats', 'recentOrders'));
    }
}

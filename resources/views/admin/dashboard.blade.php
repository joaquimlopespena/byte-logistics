@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1>Dashboard</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-lg-3 col-6">
            <x-adminlte-small-box
                title="{{ $stats['total_pedidos_label'] }}"
                text="Total de pedidos"
                icon="fas fa-shopping-cart"
                theme="info"
            />
        </div>
        <div class="col-lg-3 col-6">
            <x-adminlte-small-box
                title="{{ $stats['total_vendido_label'] }}"
                text="Total vendido"
                icon="fas fa-dollar-sign"
                theme="success"
            />
        </div>
        <div class="col-lg-3 col-6">
            <x-adminlte-small-box
                title="{{ $stats['pedidos_hoje'] }}"
                text="Pedidos hoje"
                icon="fas fa-calendar-day"
                theme="warning"
            />
        </div>
        <div class="col-lg-3 col-6">
            <x-adminlte-small-box
                title="{{ $stats['ticket_medio_label'] }}"
                text="Ticket médio"
                icon="fas fa-receipt"
                theme="primary"
            />
        </div>
    </div>

    <div class="row">
        <div class="col-lg-4 col-12 mb-3">
            <x-adminlte-info-box
                title="Transportadoras ativas"
                text="{{ $stats['transportadoras'] }}"
                icon="fas fa-truck"
                theme="gradient-teal"
            />
        </div>
        <div class="col-lg-8 col-12 mb-3">
            <x-adminlte-card title="Últimos pedidos" icon="fas fa-list" theme="light" collapsible>
                <div class="table-responsive p-0">
                    <table class="table table-striped table-hover mb-0">
                        <thead>
                            <tr>
                                <th>Cliente</th>
                                <th>Produto</th>
                                <th class="text-right">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($recentOrders as $order)
                                <tr>
                                    <td>{{ $order->cliente_nome }}</td>
                                    <td>{{ $order->produto }}</td>
                                    <td class="text-right">{{ Number::currency($order->total, 'BRL', 'pt_BR') }}</td>
                                </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </x-adminlte-card>
    </div>
@stop

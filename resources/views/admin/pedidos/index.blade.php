@extends('adminlte::page')

@section('title', 'Pedidos')

@section('content_header')
    <h1>Gerenciamento de Pedidos</h1>
@stop

@section('content')
<div class="container-fluid">
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Fechar">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Lista de Pedidos</h3>
            <div class="card-tools">
                <a href="#" class="btn btn-success btn-sm">
                    <i class="fas fa-file-csv"></i> Download CSV
                </a>
                <a href="{{ route('admin.pedidos.create') }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-plus"></i> Novo Pedido
                </a>
            </div>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.pedidos.index') }}" method="GET" class="mb-3">
                <div class="d-flex flex-wrap align-items-end" style="gap: 0.75rem;">
                    <div class="form-group mb-0">
                        <label for="search" class="small text-muted mb-1 d-block">Busca</label>
                        <div class="input-group input-group-sm" style="width: 260px;">
                            <input type="text" name="search" id="search" class="form-control" placeholder="Cliente, produto ou ID…" value="{{ request('search') }}">
                        </div>
                    </div>
                    <div class="form-group mb-0">
                        <label for="periodo_pedidos" class="small text-muted mb-1 d-block">Período (criação)</label>
                        <input type="text" id="periodo_pedidos" class="form-control form-control-sm" style="width: 240px;" placeholder="Selecione o intervalo" autocomplete="off" value="">
                        <input type="hidden" name="data_inicio" id="data_inicio" value="{{ request('data_inicio') }}">
                        <input type="hidden" name="data_fim" id="data_fim" value="{{ request('data_fim') }}">
                    </div>
                    <div class="form-group mb-0 d-flex" style="gap: 0.35rem;">
                        <button type="submit" class="btn btn-default btn-sm"><i class="fas fa-search"></i> Filtrar</button>
                        <a href="{{ route('admin.pedidos.index') }}" class="btn btn-outline-secondary btn-sm">Limpar</a>
                    </div>
                </div>
            </form>

            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Cliente</th>
                        <th>Produto</th>
                        <th>Transportadora</th>
                        <th>Qtd</th>
                        <th>Preço</th>
                        <th>Total</th>
                        <th>Data</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($pedidos as $pedido)
                        <tr>
                            <td>{{ $pedido->id }}</td>
                            <td>{{ $pedido->cliente_nome }}</td>
                            <td>{{ $pedido->produto }}</td>
                            <td>{{ $pedido->transportadora->nome ?? '-' }}</td>
                            <td>{{ $pedido->quantidade }}</td>
                            <td>R$ {{ number_format($pedido->preco, 2, ',', '.') }}</td>
                            <td><strong>R$ {{ number_format($pedido->total, 2, ',', '.') }}</strong></td>
                            <td>{{ $pedido->created_at->format('d/m/Y à\s H:i:s') }}</td>
                            <td>
                                <div class="d-flex flex-wrap align-items-center" style="gap: 0.25rem;">
                                    <a href="{{ route('admin.pedidos.show', $pedido) }}" class="btn btn-xs btn-info"><i class="fas fa-eye"></i></a>
                                    <a href="{{ route('admin.pedidos.edit', $pedido) }}" class="btn btn-xs btn-warning"><i class="fas fa-edit"></i></a>
                                    <form action="{{ route('admin.pedidos.destroy', $pedido) }}" method="post" class="d-inline">
                                        @csrf
                                        @method('delete')
                                        <button type="submit" class="btn btn-xs btn-danger" onclick="return confirm('Excluir este pedido?')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="card-footer clearfix d-flex justify-content-center">
            {{ $pedidos->appends(array_filter([
                'search' => request('search'),
                'data_inicio' => request('data_inicio'),
                'data_fim' => request('data_fim'),
            ]))->links() }}
        </div>
    </div>
</div>
@stop

@push('css')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr@4.6.13/dist/flatpickr.min.css">
@endpush

@push('js')
    @include('admin.pedidos.include.filtro-data-scripts')
@endpush

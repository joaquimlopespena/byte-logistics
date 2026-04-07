@extends('adminlte::page')

@section('title', 'Exportar pedidos')

@section('content_header')
    <h1>Exportar pedidos</h1>
@stop

@section('content')
<div class="container-fluid">
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Fechar"><span aria-hidden="true">&times;</span></button>
        </div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Fechar"><span aria-hidden="true">&times;</span></button>
        </div>
    @endif

    <div class="row">
        <div class="col-lg-5">
            <x-adminlte-card title="Nova exportação" icon="fas fa-file-csv" theme="primary" class="mb-3">
                <form action="{{ route('admin.pedidos.export.store') }}" method="POST">
                    @csrf
                    <p class="text-muted small">Os filtros são opcionais e seguem a mesma lógica da listagem (apenas os seus pedidos).</p>
                    <div class="form-group">
                        <label for="search">Busca</label>
                        <input type="text" name="search" id="search" class="form-control form-control-sm @error('search') is-invalid @enderror" value="{{ old('search') }}" placeholder="Cliente, produto ou ID">
                        @error('search')<span class="invalid-feedback">{{ $message }}</span>@enderror
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="data_inicio">Data inicial</label>
                            <input type="text" id="periodo_pedidos" class="form-control form-control-sm @error('data_inicio') is-invalid @enderror" value="{{ old('data_inicio') }}" placeholder="Selecione o intervalo" autocomplete="off">
                            <input type="hidden" name="data_inicio" id="data_inicio" value="{{ old('data_inicio') }}">
                            <input type="hidden" name="data_fim" id="data_fim" value="{{ old('data_fim') }}">
                            @error('data_inicio')<span class="invalid-feedback">{{ $message }}</span>@enderror
                        </div>
                    </div>
                    <button type="submit" class="btn btn-success btn-sm">
                        <i class="fas fa-cloud-upload-alt mr-1"></i> Enfileirar exportação
                    </button>
                    <a href="{{ route('admin.pedidos.index') }}" class="btn btn-default btn-sm">Voltar à lista</a>
                </form>
            </x-adminlte-card>
        </div>
        <div class="col-lg-7">
            <x-adminlte-card title="Últimas exportações" icon="fas fa-history" theme="light">
                <div class="table-responsive p-0">
                    <table class="table table-striped table-sm mb-0">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Status</th>
                                <th>Solicitado</th>
                                <th>Concluído</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody id="js-pedidos-exports-tbody">
                            @forelse ($exports as $ex)
                                <tr data-pedido-export-id="{{ $ex->id }}">
                                    <td>{{ $ex->id }}</td>
                                    <td class="js-export-status">
                                        @if ($ex->status === \App\Models\PedidoExport::STATUS_COMPLETED)
                                            <span class="badge badge-success">Pronto</span>
                                        @elseif ($ex->status === \App\Models\PedidoExport::STATUS_FAILED)
                                            <span class="badge badge-danger" title="{{ $ex->error_message }}">Falhou</span>
                                        @elseif ($ex->status === \App\Models\PedidoExport::STATUS_PROCESSING)
                                            <span class="badge badge-warning">Processando</span>
                                        @else
                                            <span class="badge badge-secondary">Na fila</span>
                                        @endif
                                    </td>
                                    <td class="small">{{ $ex->created_at?->format('d/m/Y H:i') }}</td>
                                    <td class="small js-export-completed">{{ $ex->completed_at?->format('d/m/Y H:i') ?? '—' }}</td>
                                    <td class="text-right js-export-actions">
                                        @if ($ex->status === \App\Models\PedidoExport::STATUS_COMPLETED)
                                            <a href="{{ route('admin.pedidos.export.download', $ex) }}" class="btn btn-xs btn-success">
                                                <i class="fas fa-download"></i> CSV
                                            </a>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-muted text-center py-3">Nenhuma exportação ainda.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </x-adminlte-card>
        </div>
    </div>
</div>
@stop

@push('css')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr@4.6.13/dist/flatpickr.min.css">
@endpush

@push('js')
    @include('admin.pedidos.include.filtro-data-scripts')
    @auth
        <script>
            $(function () {
                if (typeof window.Echo === 'undefined') {
                    alert('Echo não está definido');
                    return;
                }
                var userId = {{ auth()->id() }};
                window.Echo.private('App.Models.User.' + userId)
                    .listen('.export.pedidos.completed', function (payload) {
                        var $row = $('tr[data-pedido-export-id="' + payload.pedido_export_id + '"]');
                        if ($row.length) {
                            $row.find('.js-export-status').html(
                                '<span class="badge badge-success">Pronto</span>'
                            );
                            $row.find('.js-export-completed').text(
                                payload.completed_at_formatted || '—'
                            );
                            $row.find('.js-export-actions').html(
                                '<a href="' + payload.download_url + '" class="btn btn-xs btn-success">' +
                                '<i class="fas fa-download"></i> CSV</a>'
                            );
                            return;
                        }
                        var $wrap = $('.container-fluid').first();
                        if (!$wrap.length) {
                            return;
                        }
                        var $alert = $('<div>', { role: 'alert' })
                            .addClass('alert alert-success alert-dismissible fade show')
                            .html(
                                'Exportação <strong>#' + payload.pedido_export_id + '</strong> concluída. ' +
                                '<a href="' + payload.download_url + '" class="alert-link font-weight-bold">Baixar CSV</a>. ' +
                                '<button type="button" class="close" data-dismiss="alert" aria-label="Fechar">' +
                                '<span aria-hidden="true">&times;</span></button>'
                            );
                        $wrap.prepend($alert);
                    });
            });
        </script>
    @endauth
@endpush

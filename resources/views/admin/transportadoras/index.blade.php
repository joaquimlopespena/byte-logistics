@extends('adminlte::page')

@section('title', 'Transportadoras')
@section('content_header')
    <h1>Gerenciamento de Transportadoras</h1>
@stop

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Lista de Transportadoras</h3>
            <div class="card-tools">
                <a href="{{ route('admin.transportadoras.create') }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-plus"></i> Nova Transportadora
                </a>
            </div>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.transportadoras.index') }}" method="GET" class="mb-3">
                <div class="input-group input-group-sm" style="width: 300px;">
                    <input type="text" name="search" class="form-control" placeholder="Buscar por cliente..." value="{{ request('search') }}">
                    <div class="input-group-append">
                        <button type="submit" class="btn btn-default"><i class="fas fa-search"></i></button>
                    </div>
                </div>
            </form>

            <table class="table table-striped table-hover mb-0">
                <thead>
                    <tr>
                        <th>Nome</th>
                        <th>CNPJ</th>
                        <th>Bairro</th>
                        <th>Cidade</th>
                        <th>UF</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($transportadoras as $transportadora)
                        <tr>
                            <td><span class="text-monospace">{{ $transportadora->nome }}</span></td>
                            <td><span class="text-monospace">{{ $transportadora->cnpj_formatado }}</span></td>
                            <td><span class="text-monospace">{{ $transportadora->bairro }}</span></td>
                            <td><span class="text-monospace">{{ $transportadora->cidade }}</span></td>
                            <td><span class="text-monospace">{{ $transportadora->uf }}</span></td>
                            <td>
                                <div class="d-flex justify-content-between">
                                    <a href="{{ route('admin.transportadoras.show', $transportadora->id) }}" class="btn btn-secondary btn-sm px-2 py-1">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.transportadoras.edit', $transportadora->id) }}" class="btn btn-primary btn-sm px-2 py-1">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.transportadoras.destroy', $transportadora->id) }}" method="post">
                                        @csrf
                                        @method('delete')
                                        <button type="submit" class="btn btn-danger btn-sm px-2 py-1" onclick="return confirm('Tem certeza que deseja deletar esta transportadora?')">
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
            {{ $transportadoras->appends(['search' => request('search')])->links() }}
        </div>
    </div>
</div>
@stop

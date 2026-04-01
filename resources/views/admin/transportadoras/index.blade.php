@extends('adminlte::page')

@section('title', 'Transportadoras')
@section('content_header')
@stop

@section('content')
<div class="pt-3">
    <x-adminlte-card title="Transportadoras" icon="fas fa-truck" theme="light">
        <x-slot name="toolsSlot">
            <a href="{{ route('admin.transportadoras.create') }}" class="btn btn-success btn-sm mr-2">
                <i class="fas fa-plus mr-1"></i> Cadastrar transportadora
            </a>
        </x-slot>
        <div class="table-responsive p-0">
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
                            <td>{{ $transportadora->nome }}</td>
                            <td>{{ $transportadora->cnpj }}</td>
                            <td>{{ $transportadora->bairro }}</td>
                            <td>{{ $transportadora->cidade }}</td>
                            <td>{{ $transportadora->uf }}</td>
                            <td>
                                <div class="btn-group">
                                    <x-adminlte-button icon="fas fa-eye" theme="secondary" size="sm" href="{{ route('admin.transportadoras.show', $transportadora->id) }}" />
                                    <x-adminlte-button icon="fas fa-edit" theme="primary" size="sm" href="{{ route('admin.transportadoras.edit', $transportadora->id) }}" />
                                    <x-adminlte-button icon="fas fa-trash" theme="danger" size="sm" href="{{ route('admin.transportadoras.destroy', $transportadora->id) }}" />
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="d-flex justify-content-center mt-3">
                {{ $transportadoras->links() }}
            </div>
        </div>
    </x-adminlte-card>
</div>
@stop

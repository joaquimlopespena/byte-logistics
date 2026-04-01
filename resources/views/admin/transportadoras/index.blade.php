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
            <div class="d-flex justify-content-center mt-3">
                {{ $transportadoras->links() }}
            </div>
        </div>
    </x-adminlte-card>
</div>
@stop

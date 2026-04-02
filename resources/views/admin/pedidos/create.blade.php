@extends('adminlte::page')

@section('content_header')
    <h1>Novo Pedido</h1>
@stop

@section('content')
<div class="card card-primary">
    <form action="{{ route('admin.pedidos.store') }}" method="POST">
        @csrf
        @include('admin.pedidos.include.form')
        <div class="card-footer d-flex justify-content-end">
            <a href="{{ route('admin.pedidos.index') }}" class="btn btn-default btn-sm mr-2 p-2">
                <i class="fas fa-arrow-left mr-1"></i> Voltar
            </a>
            <x-adminlte-button type="submit" label="Salvar Pedido" theme="primary" icon="fas fa-save" />
        </div>
    </form>
</div>

@push('js')
    @include('admin.pedidos.include.scripts')
@endpush
@stop

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
<script>
    // Cálculo automático do total
    const precoInput = document.getElementById('preco');
    const qtdInput = document.getElementById('quantidade');
    const totalDisplay = document.getElementById('total_display');
    const totalHidden = document.getElementById('total_hidden');

    function calcular() {
        const total = (precoInput.value * qtdInput.value) || 0;
        totalDisplay.value = total.toLocaleString('pt-br', { style: 'currency', currency: 'BRL' });
        totalHidden.value = total.toFixed(2);
    }

    precoInput.addEventListener('input', calcular);
    qtdInput.addEventListener('input', calcular);
</script>
@endpush
@stop

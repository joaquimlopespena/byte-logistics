@extends('adminlte::page')

@section('plugins.JQueryMask', true)

@section('title', 'Transportadoras')

@section('content')
    <div class="pt-3">
        <x-adminlte-card title="Editar Transportadora {{ $transportadora->nome }}" icon="fas fa-truck" theme="light">
            <form action="{{ route('admin.transportadoras.update', $transportadora->id) }}" class="form" method="post">
                @csrf
                @method('PUT')
                @include('admin.transportadoras.include.form')
                <x-adminlte-button type="submit" label="Salvar" theme="success" icon="fas fa-save" />
            </form>
        </x-adminlte-card>
    </div>
@stop

@push('js')
    @include('admin.transportadoras.include.form-scripts')
@endpush

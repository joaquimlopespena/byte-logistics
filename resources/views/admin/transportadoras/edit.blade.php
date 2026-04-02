@extends('adminlte::page')

@section('plugins.JQueryMask', true)

@section('title', 'Transportadoras')

@section('content_header')
    <h1>Editar Transportadora</h1>
@stop

@section('content')
    <div class="card card-primary">
        <form action="{{ route('admin.transportadoras.update', $transportadora->id) }}" class="form" method="post">
            @method('PUT')
            @csrf
            @include('admin.transportadoras.include.form')

            <div class="card-footer d-flex justify-content-end">
                <a href="{{ route('admin.transportadoras.index') }}" class="btn btn-default btn-sm mr-2 p-2">
                    <i class="fas fa-arrow-left mr-1"></i> Voltar
                </a>
                <x-adminlte-button type="submit" label="Salvar" theme="primary" icon="fas fa-save" />
            </div>
        </form>
    </div>
@stop

@push('js')
    @include('admin.transportadoras.include.scripts')
@endpush

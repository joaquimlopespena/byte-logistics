@extends('adminlte::page')

@section('title', 'Transportadoras')

@section('content_header')
    <h1>Transportadoras</h1>

    <x-adminlte-card title="Transportadoras" icon="fas fa-truck" theme="light" collapsible>
        <div class="table-responsive p-0">
            <table class="table table-striped table-hover mb-0">
                <thead>
                    <tr>
                        <th>Nome</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($transportadoras as $transportadora)
                        <tr>
                            <td>{{ $transportadora->nome }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </x-adminlte-card>
@stop

@section('content')
    <div class="row">

    </div>
@stop

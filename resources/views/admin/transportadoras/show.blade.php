@extends('adminlte::page')

@section('title', $transportadora->nome)

@section('content_header')
    <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-md-between">
        <div class="mb-2 mb-md-0">
            <h1 class="mb-0 text-break">{{ $transportadora->nome }}</h1>
            <p class="text-muted mb-0 small">
                <i class="fas fa-truck mr-1"></i> Transportadora
                <span class="mx-2">·</span>
                CNPJ {{ $transportadora->cnpj_formatado }}
            </p>
        </div>
    </div>
@stop

@section('content')
    @php
        $enderecoLinha = trim(implode(', ', array_filter([
            $transportadora->logradouro,
            $transportadora->numero ? 'nº '.$transportadora->numero : null,
            $transportadora->complemento,
        ])));
        $cidadeUf = trim(implode(' — ', array_filter([
            $transportadora->bairro,
            $transportadora->cidade.' / '.$transportadora->uf,
            'CEP '.$transportadora->cep_formatado,
        ])));
        $mapsQuery = urlencode(trim($enderecoLinha.', '.$cidadeUf));
    @endphp

    <div class="pt-3">
        <div class="row">
            <div class="col-lg-8">
                <x-adminlte-card title="Dados cadastrais" icon="fas fa-id-card" theme="light">
                    <x-slot name="toolsSlot">
                        <a href="{{ route('admin.transportadoras.edit', $transportadora) }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-edit mr-1"></i> Editar
                        </a>
                    </x-slot>
                    <div class="table-responsive">
                        <table class="table table-hover text-nowrap mb-0">
                            <tbody>
                                <tr>
                                    <th class="text-muted font-weight-normal border-top-0" style="width: 11rem;">Razão social</th>
                                    <td class="border-top-0 font-weight-medium">{{ $transportadora->nome }}</td>
                                </tr>
                                <tr>
                                    <th class="text-muted font-weight-normal">CNPJ</th>
                                    <td><span class="text-monospace">{{ $transportadora->cnpj_formatado }}</span></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </x-adminlte-card>

                <x-adminlte-card title="Endereço" icon="fas fa-map-marker-alt" theme="light" class="mt-3">
                    <address class="mb-3 lead" style="font-size: 1.05rem;">
                        @if ($enderecoLinha !== '')
                            {{ $enderecoLinha }}<br>
                        @endif
                        {{ $cidadeUf }}
                    </address>
                    <div class="d-flex flex-wrap align-items-center">
                        <a href="https://www.google.com/maps/search/?api=1&query={{ $mapsQuery }}"
                           class="btn btn-outline-secondary btn-sm mr-2 mb-2"
                           target="_blank"
                           rel="noopener noreferrer">
                            <i class="fas fa-external-link-alt mr-1"></i> Abrir no Google Maps
                        </a>
                    </div>
                </x-adminlte-card>
            </div>

            <div class="col-lg-4 mt-3 mt-lg-0">
                <x-adminlte-card title="Resumo" icon="fas fa-info-circle" theme="teal" theme-mode="outline">
                    <ul class="list-unstyled mb-0 small">
                        <li class="mb-2 pb-2 border-bottom">
                            <span class="text-muted d-block">Cadastrada em</span>
                            <strong>{{ $transportadora->created_at?->format('d/m/Y H:i') ?? '—' }}</strong>
                        </li>
                        <li class="mb-2 pb-2 border-bottom">
                            <span class="text-muted d-block">Última atualização</span>
                            <strong>{{ $transportadora->updated_at?->format('d/m/Y H:i') ?? '—' }}</strong>
                        </li>
                        <li>
                            <span class="text-muted d-block">ID interno</span>
                            <span class="text-monospace">#{{ $transportadora->id }}</span>
                        </li>
                    </ul>
                    <div class="mt-3 pt-2 border-top">
                        <a href="{{ route('admin.transportadoras.index') }}" class="btn btn-default btn-block btn-sm">
                            <i class="fas fa-list mr-1"></i> Voltar à lista
                        </a>
                    </div>
                </x-adminlte-card>
            </div>
        </div>
    </div>
@stop

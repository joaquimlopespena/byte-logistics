<div class="row">
    <div class="col-md-8">
        <x-adminlte-input name="nome" id="nome" label="Nome" placeholder="Nome" value="{{ old('nome', $transportadora->nome ?? null) }}" />
    </div>
    <div class="col-md-4">
        <x-adminlte-input name="cnpj" id="cnpj" label="CNPJ" placeholder="CNPJ" value="{{ old('cnpj', $transportadora->cnpj ?? null) }}" />
    </div>
</div>
<div class="row">
    <div class="col-md-3">
        <x-adminlte-input name="cep" id="cep" label="CEP" placeholder="CEP" value="{{ old('cep', $transportadora->cep ?? null) }}" />
    </div>
    <div class="col-md-2">
        <x-adminlte-input name="uf" id="uf" label="UF" placeholder="UF" value="{{ old('uf', $transportadora->uf ?? null) }}" />
    </div>
    <div class="col-md-3">
        <x-adminlte-input name="cidade" id="cidade" label="Cidade" placeholder="Cidade" value="{{ old('cidade', $transportadora->cidade ?? null) }}" />
    </div>
    <div class="col-md-4">
        <x-adminlte-input name="bairro" id="bairro" label="Bairro" placeholder="Bairro" value="{{ old('bairro', $transportadora->bairro ?? null) }}" />
    </div>

    <div class="col-md-12">
        <x-adminlte-input name="logradouro" id="logradouro" label="Logradouro" placeholder="Logradouro" value="{{ old('logradouro', $transportadora->logradouro ?? null) }}" />
    </div>
    <div class="col-md-3">
        <x-adminlte-input name="numero" id="numero" label="Número" placeholder="Número" value="{{ old('numero', $transportadora->numero ?? null) }}" />
    </div>
    <div class="col-md-6">
        <x-adminlte-input name="complemento" id="complemento" label="Complemento" placeholder="Complemento" value="{{ old('complemento', $transportadora->complemento ?? null) }}" />
    </div>
</div>

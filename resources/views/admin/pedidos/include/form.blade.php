<div class="card-body">
    <div class="row">
        <div class="col-md-12 form-group">
            <x-adminlte-select name="transportadora_id" id="transportadora_id" label="Transportadora" placeholder="Transportadora" value="{{ old('transportadora_id', null) }}">
                <option value="">Selecione</option>
                @foreach($transportadoras as $transportadora)
                    <option value="{{ $transportadora->id }}" {{ old('transportadora_id', $pedido->transportadora_id ?? null) == $transportadora->id ? 'selected' : '' }}>{{ $transportadora->nome }}</option>
                @endforeach
            </x-adminlte-select>
        </div>
        <div class="col-md-6 form-group">
            <x-adminlte-input name="nome_cliente" id="nome_cliente" label="Nome do Cliente" placeholder="Nome do Cliente" value="{{ old('nome_cliente', $pedido->cliente_nome ?? null) }}" />
        </div>
        <div class="col-md-6 form-group">
            <x-adminlte-input name="produto" id="produto" label="Produto" placeholder="Produto" value="{{ old('produto', $pedido->produto ?? null) }}" />
        </div>
    </div>
    <div class="form-group">
        <x-adminlte-textarea name="descricao" id="descricao" label="Descrição" placeholder="Descrição" value="{{ old('descricao', $pedido->descricao ?? null) }}" />
    </div>
    <div class="row">
        <div class="col-md-4 form-group">
            <x-adminlte-input name="preco" id="preco" label="Preço" placeholder="Preço" value="{{ old('preco', $pedido->preco ?? null) }}" />
        </div>
        <div class="col-md-4 form-group">
            <x-adminlte-input name="quantidade" id="quantidade" label="Quantidade" placeholder="Quantidade" value="{{ old('quantidade', $pedido->quantidade ?? null) }}" />
        </div>
        <div class="col-md-4 form-group">
            <x-adminlte-input name="total" id="total" label="Total" placeholder="Total" value="{{ old('total', $pedido->total ?? null) }}" readonly />
        </div>
    </div>
</div>

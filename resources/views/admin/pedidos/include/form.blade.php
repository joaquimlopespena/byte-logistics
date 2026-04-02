<div class="card-body">
    <div class="row">
        <div class="col-md-6 form-group">
            <label>Nome do Cliente</label>
            <input type="text" name="nome_cliente" class="form-control" required>
        </div>
        <div class="col-md-6 form-group">
            <label>Produto</label>
            <input type="text" name="produto" class="form-control" placeholder="Ex: iPhone 15" required>
        </div>
    </div>
    <div class="form-group">
        <label>Descrição</label>
        <textarea name="descricao" class="form-control" rows="3"></textarea>
    </div>
    <div class="row">
        <div class="col-md-4 form-group">
            <label>Preço (R$)</label>
            <input type="number" step="0.01" name="preco" id="preco" class="form-control" required>
        </div>
        <div class="col-md-4 form-group">
            <label>Quantidade</label>
            <input type="number" name="quantidade" id="quantidade" class="form-control" required>
        </div>
        <div class="col-md-4 form-group">
            <label>Total (Automático)</label>
            <input type="text" id="total_display" class="form-control" readonly>
            <input type="hidden" name="total" id="total_hidden">
        </div>
    </div>
</div>

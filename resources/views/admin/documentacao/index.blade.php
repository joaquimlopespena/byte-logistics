@extends('adminlte::page')

@section('title', 'Documentação')

@section('content_header')
    <h1>Documentação do sistema</h1>
    <p class="text-muted mb-0">Resumo das funcionalidades implementadas no Byte Logistics (desafio técnico — escala e exportação).</p>
@stop

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-8">
            <div class="card card-outline card-primary">
                <div class="card-header">
                    <h3 class="card-title">Visão geral</h3>
                </div>
                <div class="card-body">
                    <p>Aplicação Laravel com painel <strong>AdminLTE</strong> para gestão de pedidos de uma loja de informática, com suporte a <strong>grandes volumes</strong> (listagem paginada, agregados no dashboard com cache, inserção em massa e exportação CSV assíncrona).</p>
                    <ul class="mb-0">
                        <li><strong>Dashboard</strong> — total de pedidos, total vendido (R$), pedidos hoje, ticket médio e últimos pedidos.</li>
                        <li><strong>Pedidos</strong> — CRUD completo; busca por nome do cliente (e produto/ID); paginação de 50 itens; total calculado a partir de preço × quantidade.</li>
                        <li><strong>Exportar CSV</strong> — fila (job) para gerar ficheiro com todos os pedidos que correspondem aos filtros, sem limitação da paginação da lista.</li>
                        <li><strong>Transportadoras</strong> — CRUD com endereço; preenchimento automático via API <a href="https://viacep.com.br" target="_blank" rel="noopener">ViaCEP</a>.</li>
                    </ul>
                </div>
            </div>

            <div class="card card-outline card-info">
                <div class="card-header">
                    <h3 class="card-title">API REST (Sanctum)</h3>
                </div>
                <div class="card-body">
                    <p>Base URL: <code>{{ url('/api') }}</code> — rotas protegidas com <code>Authorization: Bearer {token}</code>.</p>
                    <p class="mb-2">Obter token (JSON):</p>
                    <pre class="bg-dark text-light p-3 rounded small mb-3">POST {{ url('/api/sanctum/token') }}
Content-Type: application/json

{
  "email": "utilizador@exemplo.com",
  "password": "…",
  "device_name": "cli-ou-mcp"
}</pre>
                    <table class="table table-sm table-bordered">
                        <thead>
                            <tr><th>Método</th><th>Rota</th><th>Descrição</th></tr>
                        </thead>
                        <tbody>
                            <tr><td>GET</td><td><code>/api/pedidos</code></td><td>Listar pedidos (paginação; query <code>per_page</code> 1–100)</td></tr>
                            <tr><td>GET</td><td><code>/api/pedidos/{id}</code></td><td>Detalhe de um pedido</td></tr>
                            <tr><td>POST</td><td><code>/api/pedidos</code></td><td>Criar pedido (JSON)</td></tr>
                        </tbody>
                    </table>
                    <p class="mb-0 small text-muted">Coleção Postman: <code>postman/Byte-Logistics-API.postman_collection.json</code> na raiz do repositório.</p>
                </div>
            </div>

            <div class="card card-outline card-secondary">
                <div class="card-header">
                    <h3 class="card-title">Massa de dados (1M+ pedidos)</h3>
                </div>
                <div class="card-body">
                    <p>Comando Artisan que insere pedidos em <strong>lotes</strong> (INSERT em chunk), adequado a volumes na ordem do milhão. Com <strong>Laravel Sail</strong> (este projeto):</p>
                    <pre class="bg-dark text-light p-3 rounded small mb-0">./vendor/bin/sail artisan pedido:gerar_massa --count=1000000</pre>
                    <p class="small text-muted mt-2 mb-0">Requer pelo menos uma transportadora cadastrada. Use <code>--force</code> para não pedir confirmação em volumes grandes. Sem Docker, o equivalente é <code>php artisan pedido:gerar_massa …</code>.</p>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card card-outline card-success">
                <div class="card-header">
                    <h3 class="card-title">MCP (consulta rápida)</h3>
                </div>
                <div class="card-body">
                    <p>Servidor MCP em <code>mcp-server/</code> expõe ferramentas que chamam a API acima (útil no Cursor ou outro cliente MCP).</p>
                    <ol class="pl-3 mb-3 small">
                        <li><code>cd mcp-server && npm install</code></li>
                        <li>Emitir token Sanctum (ver caixa API).</li>
                        <li>Variáveis: <code>BYTE_LOGISTICS_API_BASE_URL</code> (com <strong>Sail</strong>, típico <code>http://localhost/api</code>; ajuste à porta se mudou <code>APP_PORT</code>) e <code>BYTE_LOGISTICS_API_TOKEN</code>.</li>
                        <li>Registar o servidor no cliente MCP (ex.: Cursor) com comando <code>node</code> e argumento o ficheiro <code>mcp-server/src/index.js</code>.</li>
                    </ol>
                    <p class="mb-0 small"><strong>Ferramentas:</strong> <code>pedido_obter</code> (por ID), <code>pedido_listar</code> (página e <code>per_page</code>). Detalhes em <code>mcp-server/README.md</code>.</p>
                </div>
            </div>

            <div class="card card-outline card-warning">
                <div class="card-header">
                    <h3 class="card-title">Rotas web principais</h3>
                </div>
                <div class="card-body p-0">
                    <table class="table table-sm mb-0">
                        <tbody>
                            <tr><td><code>/dashboard</code></td><td>Indicadores</td></tr>
                            <tr><td><code>/admin/pedidos</code></td><td>Lista e CRUD</td></tr>
                            <tr><td><code>/admin/pedidos/exportar</code></td><td>Exportação CSV</td></tr>
                            <tr><td><code>/admin/transportadoras</code></td><td>CRUD transportadoras</td></tr>
                            <tr><td><code>/admin/documentacao</code></td><td>Esta página</td></tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@stop

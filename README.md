# Byte Logistics

Sistema web de **gestão de pedidos e logística** para uma loja de informática: painel administrativo autenticado, relacionamento pedidos ↔ transportadoras e indicadores no dashboard. Projeto pensado para **escala** (grandes volumes de pedidos) e para **evolução contínua** — não só um CRUD isolado.

---

## Tecnologias

- **PHP 8.3** / **Laravel 13**
- **Laravel Sail** (Docker Compose) — ambiente local: app, MySQL, Redis, Horizon e Reverb (`compose.yaml`)
- **MySQL** (serviço Sail; credenciais em `.env`)
- **Laravel AdminLTE** — UI do painel (Bootstrap / AdminLTE)
- **Laravel Breeze** — autenticação, registro e perfil
- **Vite** — pipeline de assets
- **Laravel Sanctum** — API REST com token Bearer
- **Filas / Horizon / Reverb** (opcional) — exportação CSV assíncrona e notificações em tempo real
- **Localização pt_BR** (`lucascudo/laravel-pt-br-localization`)

---

## Funcionalidades

| Área | Estado |
|------|--------|
| Dashboard com métricas (totais, ticket médio, últimos pedidos) | Entregue |
| CRUD de pedidos (validação, transações, máscaras pt-BR, total calculado) | Entregue |
| CRUD de transportadoras + endereço via **ViaCEP** | Entregue |
| Listagem com paginação **50** por página e busca (cliente, produto, ID) | Entregue |
| Exportação **CSV** de todos os pedidos (filtros + job em fila, sem limite da paginação) | Entregue |
| API REST: listar, obter por ID, criar pedidos (`/api/*`, Sanctum) | Entregue |
| Inserção automatizada em massa (`sail artisan pedido:gerar_massa`, default 1M) | Entregue |
| Página **Documentação** no painel (`/admin/documentacao`) | Entregue |
| **MCP** para consulta rápida de pedidos (`mcp-server/`) | Entregue |

**Rotas principais** (utilizador autenticado): `/dashboard`, `/admin/pedidos`, `/admin/pedidos/exportar`, `/admin/transportadoras`, `/admin/documentacao`.

---

## Documentação e MCP

- **No browser:** após login, menu **Documentação** — rota `/admin/documentacao` (resumo de módulos, API, massa de dados e MCP).
- **MCP (Cursor ou outro cliente):** ver [`mcp-server/README.md`](mcp-server/README.md) — `npm install` dentro de `mcp-server`, token Sanctum em `BYTE_LOGISTICS_API_TOKEN`, comando `node` apontando para `mcp-server/src/index.js`.

---

## Decisões técnicas

Decisões explícitas no código e no schema, úteis para revisão em entrevista:

- **`foreignId` em `transportadora_id` com `nullOnDelete()`** — se uma transportadora for removida, pedidos associados não são apagados; o FK passa a `null`, evitando perda de histórico e violações de integridade.
- **Índice composto `(cliente_nome, created_at)`** em `pedidos` — prepara listagens e **buscas por nome de cliente** ordenadas por recência sem varrer a tabela inteira à medida que o volume cresce.
- **Índices em `created_at` e `deleted_at`** — suportam ordenação padrão da listagem e consultas com **soft deletes** sem full scan desnecessário.
- **`FormRequest` por operação** (`Store` / `Update`) — validação e normalização de valores monetários centralizadas, alinhadas ao formato brasileiro antes da persistência.
- **`DB::transaction` no create/update de pedidos** — base consistente caso a lógica de escrita ganhe efeitos colaterais (ex.: auditoria, stock) mais tarde.
- **Soft deletes** em pedidos e transportadoras — recuperação e relatórios históricos sem eliminar registros de imediato.
- **Exportação CSV em job** — streaming por chunk sobre a query filtrada, adequado a volumes grandes sem carregar tudo em memória na resposta HTTP.

---

## Como executar (Laravel Sail)

Requisitos: **Docker** e **Docker Compose**. O projeto usa o ficheiro `compose.yaml` na raiz (serviços `laravel.test`, `mysql`, `redis`, `horizon`, `reverb`).

```bash
git clone https://github.com/joaquimlopespena/byte-logistics
cd byte-logistics

composer install
cp .env.example .env
# Confirme APP_URL (ex.: http://localhost) e DB_* alinhados com o MySQL do Sail

./vendor/bin/sail up -d

./vendor/bin/sail artisan key:generate
./vendor/bin/sail artisan migrate
./vendor/bin/sail npm install
./vendor/bin/sail npm run build
```

**Atalho:** na raiz do projeto, `alias sail='./vendor/bin/sail'` para usar `sail` em vez do caminho completo.

A aplicação fica em **`APP_URL`** (por omissão do Sail: **http://localhost**, porta **80** — veja `APP_PORT` no `.env`). Registe um utilizador em **`/register`** (Breeze) e aceda ao painel.

**Desenvolvimento (Vite):** `./vendor/bin/sail npm run dev` — a porta do Vite é exposta conforme `VITE_PORT` / `FORWARD_VITE_PORT` no `.env`.

**Filas e tempo real:** com `sail up -d`, os contentores **Horizon** e **Reverb** arrancam em paralelo (exportação CSV em fila e notificações WebSocket, se configurado no `.env`).

**Testes:** `./vendor/bin/sail artisan test` (ou `composer run test` no host, se o `.env` de testes apontar para o MySQL acessível).

**MCP (corre no teu computador, não dentro do Docker):** `cd mcp-server && npm install` — usa a API exposta pelo host (ex.: `http://localhost/api`); detalhes em [`mcp-server/README.md`](mcp-server/README.md).

**Nota:** `composer run setup` e `composer run dev` no `composer.json` assumem **PHP/Node no host** (`php artisan serve`, etc.). Para este repositório, o fluxo recomendado é **Sail** como acima.

---

## Organização do código

- `app/Http/Controllers/Admin/` — dashboard, pedidos, exportação, transportadoras, documentação  
- `app/Http/Controllers/Api/` — API REST de pedidos e token Sanctum  
- `app/Http/Requests/Pedido/` — regras de validação dos pedidos  
- `app/Jobs/` — geração de CSV em fila  
- `resources/views/admin/` — views Blade do painel  
- `config/adminlte.php` — menu, tema e plugins (ex.: máscaras)  
- `mcp-server/` — servidor MCP (Node) para consultar pedidos via API  

---

## Convenção de commits

Histórico legível ajuda quem revisa o repositório:

- `feat:` nova funcionalidade  
- `fix:` correção  
- `refactor:` / `chore:` / `docs:` conforme o caso  

Evite mensagens vagas (`teste`, `ajuste`, `init` sem contexto). Prefira o **quê** e o **onde**, por exemplo: `feat(admin): export CSV de pedidos`.

---

## Licença

MIT. Ver [opensource.org/licenses/MIT](https://opensource.org/licenses/MIT).

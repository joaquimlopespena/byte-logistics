# Byte Logistics

Sistema web de **gestão de pedidos e logística** para uma loja de informática: painel administrativo autenticado, relacionamento pedidos ↔ transportadoras e indicadores no dashboard. Projeto pensado para **escala** (grandes volumes de pedidos) e para **evolução contínua** — não só um CRUD isolado.

---

## Tecnologias

- **PHP 8.3** / **Laravel 13**
- **MySQL** (configurável via `.env`)
- **Laravel AdminLTE** — UI do painel (Bootstrap / AdminLTE)
- **Laravel Breeze** — autenticação, registro e perfil
- **Vite** — pipeline de assets
- **Localização pt_BR** (`lucascudo/laravel-pt-br-localization`)

---

## Funcionalidades

| Área | Estado |
|------|--------|
| Dashboard com métricas reais (totais, ticket médio, últimos pedidos) | Entregue |
| CRUD de pedidos (validação dedicada, transações, máscaras pt-BR) | Entregue |
| CRUD de transportadoras + endereço via **ViaCEP** | Entregue |
| Busca por cliente na listagem de pedidos | UI pronta; filtro no controller em evolução |
| Exportação CSV de todos os pedidos | Planeado (requisito de escala) |
| API REST (listar / obter / criar pedidos) | Planeado |

**Rotas principais** (utilizador autenticado): `/dashboard`, `/admin/pedidos`, `/admin/transportadoras`.

---

## Decisões técnicas

Decisões explícitas no código e no schema, úteis para revisão em entrevista:

- **`foreignId` em `transportadora_id` com `nullOnDelete()`** — se uma transportadora for removida, pedidos associados não são apagados; o FK passa a `null`, evitando perda de histórico e violações de integridade.
- **Índice composto `(cliente_nome, created_at)`** em `pedidos` — prepara listagens e **buscas por nome de cliente** ordenadas por recência sem varrer a tabela inteira à medida que o volume cresce.
- **Índices em `created_at` e `deleted_at`** — suportam ordenação padrão da listagem e consultas com **soft deletes** sem full scan desnecessário.
- **`FormRequest` por operação** (`Store` / `Update`) — validação e normalização de valores monetários centralizadas, alinhadas ao formato brasileiro antes da persistência.
- **`DB::transaction` no create/update de pedidos** — base consistente caso a lógica de escrita ganhe efeitos colaterais (ex.: auditoria, stock) mais tarde.
- **Soft deletes** em pedidos e transportadoras — recuperação e relatórios históricos sem eliminar registros de imediato.
- **Plugin jQuery Mask via AdminLTE** — máscaras carregadas pela configuração do pacote (`JQueryMask` ativo), evitando scripts órfãos sem dependência.

---

## Como executar

```bash
git clone https://github.com/joaquimlopespena/byte-logistics
cd byte-logistics

composer install
cp .env.example .env
php artisan key:generate
# Ajuste DB_* no .env e crie a base de dados

php artisan migrate
npm install && npm run build

php artisan serve
```

Registe um utilizador em `/register` (Breeze) e aceda ao painel. Atalho opcional: `composer run setup` (requer `.env` com DB válido).

**Desenvolvimento:** `npm run dev` + `php artisan serve` (ou `composer run dev` conforme `composer.json`).

**Testes:** `composer run test`

---

## Organização do código

- `app/Http/Controllers/Admin/` — dashboard, pedidos, transportadoras  
- `app/Http/Requests/Pedido/` — regras de validação dos pedidos  
- `resources/views/admin/` — views Blade do painel  
- `config/adminlte.php` — menu, tema e plugins (ex.: máscaras)

---

## Convenção de commits

Histórico legível ajuda quem revisa o repositório:

- `feat:` nova funcionalidade  
- `fix:` correção  
- `refactor:` / `chore:` / `docs:` conforme o caso  

Evite mensagens vagas (`teste`, `ajuste`, `init` sem contexto). Prefira o **quê** e o **onde**, por exemplo: `feat(admin): export CSV de pedidos`.

---

## Status

Projeto **em evolução ativa** (base sólida de domínio e persistência; próximos passos: busca no índice, CSV em lote, API e otimizações para milhões de linhas conforme o desafio).

---

## Licença

MIT. Ver [opensource.org/licenses/MIT](https://opensource.org/licenses/MIT).

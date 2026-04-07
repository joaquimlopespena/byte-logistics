# Byte Logistics — MCP server

Servidor [Model Context Protocol](https://modelcontextprotocol.io) que consulta pedidos através da API Laravel (Sanctum).

O projeto **Byte Logistics** corre em **Laravel Sail** (Docker): a API fica exposta no **host** na URL base da app (por omissão **`http://localhost`**, porta **80**). O MCP executa no **teu sistema** (ex.: Cursor) e chama essa URL — não uses o hostname interno do Docker (`laravel.test`) nem `http://mysql`.

## Requisitos

- Node.js 18+
- Stack Sail em execução: `./vendor/bin/sail up -d` na raiz do repositório Laravel
- Migrações aplicadas e utilizador criado (ex.: registo em `/register`)
- Token Bearer obtido em `POST /api/sanctum/token` (corpo JSON: `email`, `password`, `device_name`)

## Instalação

```bash
cd mcp-server
npm install
```

## Variáveis de ambiente

| Variável | Obrigatória | Descrição |
|----------|-------------|-----------|
| `BYTE_LOGISTICS_API_TOKEN` | Sim | Token `plainTextToken` devolvido pelo Sanctum |
| `BYTE_LOGISTICS_API_BASE_URL` | Não | Base da API **no host**. Com Sail por omissão: `http://localhost/api` (sem barra final). Se alteraste `APP_PORT` no `.env`, usa `http://localhost:{APP_PORT}/api`. Deve coincidir com `APP_URL` + `/api`. |

### Obter token com Sail a correr

No host (substitui email, password e URL se necessário):

```bash
curl -s -X POST http://localhost/api/sanctum/token \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{"email":"tu@exemplo.com","password":"a-tua-password","device_name":"mcp-local"}'
```

Copia o valor de `token` da resposta JSON para `BYTE_LOGISTICS_API_TOKEN`.

## Ferramentas expostas

| Nome | Descrição |
|------|-----------|
| `pedido_obter` | Argumento `id` — devolve o JSON de um pedido |
| `pedido_listar` | Argumentos opcionais `page`, `per_page` (1–100) — lista paginada |

## Executar manualmente

```bash
export BYTE_LOGISTICS_API_TOKEN='seu-token-aqui'
# Opcional se não usares o default:
# export BYTE_LOGISTICS_API_BASE_URL='http://localhost/api'

npm start
```

## Cursor (exemplo)

Em **Cursor Settings → MCP**, adicione um servidor com:

- **Command:** `node`
- **Args:** caminho absoluto para `mcp-server/src/index.js` dentro deste repositório
- **Environment:** `BYTE_LOGISTICS_API_TOKEN` e, se necessário, `BYTE_LOGISTICS_API_BASE_URL` (ex.: `http://localhost/api` com Sail)

Exemplo de `args` (macOS/Linux):

```json
["/caminho/absoluto/byte-logistics/mcp-server/src/index.js"]
```

Reinicie o Cursor após alterar a configuração.

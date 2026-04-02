# Byte Logistics

Painel administrativo em **Laravel** para gestão de **pedidos** e **transportadoras** de uma loja de informática, com interface **AdminLTE** e autenticação via **Laravel Breeze**.

## Stack

| Tecnologia | Uso |
|------------|-----|
| PHP 8.3 | Runtime |
| Laravel 13 | Framework web |
| [Laravel AdminLTE](https://github.com/jeroennoten/Laravel-AdminLTE) | Layout do painel |
| Laravel Breeze | Registro, login e perfil |
| MySQL | Banco de dados (padrão no `.env.example`) |
| Vite | Assets front-end |

Localização da aplicação: **pt_BR** (`lucascudo/laravel-pt-br-localization`).

## Funcionalidades

- **Dashboard** — indicadores a partir dos dados reais: total de pedidos, total vendido (R$), pedidos do dia, ticket médio e quantidade de transportadoras; lista dos últimos pedidos.
- **Pedidos** — CRUD com cliente, produto, descrição, preço, quantidade, total (calculado no formulário) e transportadora associada; máscaras e formato monetário em pt-BR.
- **Transportadoras** — CRUD com nome, CNPJ e endereço; preenchimento de endereço a partir do **CEP** via [ViaCEP](https://viacep.com.br/).

Rotas do painel (utilizador autenticado):

- `/dashboard`
- `/admin/pedidos`
- `/admin/transportadoras`

## Requisitos

- PHP **8.3+** com extensões usuais do Laravel (`openssl`, `pdo`, `mbstring`, `tokenizer`, `xml`, `ctype`, `json`, `bcmath`)
- [Composer](https://getcomposer.org/)
- [Node.js](https://nodejs.org/) e npm (para Vite)
- MySQL (ou outro SGBD configurado no `.env`)

## Instalação

1. **Clonar o repositório** e entrar na pasta do projeto.

2. **Dependências e ambiente**

   ```bash
   composer install
   cp .env.example .env
   php artisan key:generate
   ```

3. **Base de dados** — criar a base (ex.: `byte_logistics`), preencher `DB_*` no `.env` e executar:

   ```bash
   php artisan migrate
   ```

   Opcional: utilizadores de teste com Breeze — `php artisan migrate` já inclui as tabelas necessárias; registe um utilizador em `/register` ou use seeders se existirem no projeto.

4. **Assets**

   ```bash
   npm install
   npm run build
   ```

   Em desenvolvimento: `npm run dev` (e noutro terminal `php artisan serve`).

5. **Servidor**

   ```bash
   php artisan serve
   ```

   Aceda a `http://127.0.0.1:8000`, faça login e utilize o menu **Dashboard**, **Pedidos** e **Transportadoras**.

### Script Composer (atalho)

O projeto inclui um script que automatiza parte do setup:

```bash
composer run setup
```

Garanta que o `.env` está com credenciais de base de dados válidas antes de correr as migrações.

## Desenvolvimento

```bash
composer run dev
```

Inicia em paralelo (quando configurado no `composer.json`): servidor PHP, fila, logs e Vite.

Testes:

```bash
composer run test
```

## Estrutura relevante

- `app/Http/Controllers/Admin/` — Dashboard, Pedidos e Transportadoras  
- `app/Http/Requests/Pedido/` — validação de criação e atualização de pedidos  
- `resources/views/admin/` — views Blade do painel  
- `config/adminlte.php` — título, menu e plugins (ex.: jQuery Mask)

## Licença

Este projeto é open-source sob a licença [MIT](https://opensource.org/licenses/MIT).

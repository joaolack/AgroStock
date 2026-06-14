# AgroStock

Sistema web em Laravel para controle de estoque, com cadastro de produtos, categorias, fornecedores, lotes, movimentações, validade, relatórios e análises.

## Requisitos

- PHP 8.2 ou superior
- Composer
- Node.js e npm
- Banco de dados MySQL ou SQLite

## Instalacao

1. Instale as dependências PHP:

```bash
composer install
```

2. Instale as dependências do front-end:

```bash
npm install
```

3. Copie o arquivo de configuração:

```bash
copy .env.example .env
```

No Linux ou macOS:

```bash
cp .env.example .env
```

4. Gere a chave da aplicação:

```bash
php artisan key:generate
```

## Banco de dados

Por padrão, o projeto pode usar SQLite ou MySQL.

### MySQL

Atualize o arquivo `.env` com os dados do seu banco:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=agrostock
DB_USERNAME=root
DB_PASSWORD=
```

Depois crie o banco informado em `DB_DATABASE`.

## Criar tabelas e dados iniciais

Execute as migrations e os seeders:

```bash
php artisan migrate --seed
```

Os seeders criam dados iniciais de categorias e produtos para testes.

## Executar o projeto

Abra dois terminais na raiz do projeto.

No primeiro:

```bash
php artisan serve
```

No segundo:

```bash
npm run build
```

Depois acesse:

```text
http://127.0.0.1:8000
```

## Testes

Para rodar os testes automatizados:

```bash
php artisan test
```

## Comandos uteis

```bash
php artisan optimize:clear
php artisan route:list
npm run build
```


# AgroStock

AgroStock e um sistema web para controle de estoque, produtos, fornecedores, lotes, validade e movimentacoes.

O projeto foi desenvolvido em Laravel e tem foco em organizar a rotina de estoque de forma simples: cadastrar produtos, acompanhar entradas e saidas, visualizar alertas, controlar vencimentos e gerar relatorios.

## Funcionalidades

- Login e cadastro de usuarios.
- Dashboard com indicadores de estoque.
- CRUD de produtos, categorias e fornecedores.
- Controle de entradas e saidas de estoque.
- Controle de lotes e datas de validade.
- Alertas para estoque baixo, falta de estoque e vencimentos.
- Analises com graficos, rankings e curva ABC.
- Exportacao de relatorios em PDF e Excel.
- Historico de exportacoes.
- Logs de auditoria das principais acoes do sistema.

## Tecnologias

- PHP 8.2+
- Laravel 12
- Laravel Breeze
- Livewire / Volt
- Blade
- Tailwind CSS
- Vite
- Chart.js
- SQLite ou MySQL
- DomPDF
- Laravel Excel

## Estrutura

O projeto segue a estrutura padrao do Laravel, com algumas camadas para organizar melhor as regras de negocio:

- `Controllers`: entrada das requisicoes.
- `Requests`: validacoes.
- `Services`: regras de negocio.
- `Repositories`: consultas e montagem de dados.
- `Observers`: registros automaticos de auditoria.
- `Views`: telas em Blade.
- `Migrations` e `Seeders`: estrutura e dados iniciais do banco.

## Como rodar

Instale as dependencias do projeto:

```bash
composer install
npm install
```

Crie o arquivo `.env` e gere a chave da aplicacao:

```bash
cp .env.example .env
php artisan key:generate
```

Configure o MySQL no arquivo `.env`:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=agrostock
DB_USERNAME=root
DB_PASSWORD=
```

Crie o banco de dados no MySQL com o mesmo nome configurado em `DB_DATABASE`. Depois execute as migrations e seeders:

```bash
php artisan migrate --seed
```

Inicie o Vite e o servidor Laravel:

```bash
npm run dev
php artisan serve
```

Acesse:

```text
http://127.0.0.1:8000
```

## Comandos uteis

```bash
php artisan test
php artisan route:list
php artisan optimize:clear
php artisan view:cache
```

## Autor

Joao Gabriel Lack  
GitHub: [https://github.com/joaolack](https://github.com/joaolack)

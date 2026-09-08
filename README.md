# Gestão de Carros

Sistema CRUD em **Laravel** para gestão de inventário de veículos, com autenticação,
papéis de utilizador (admin/utilizador), categorias, upload de imagens, pesquisa,
paginação e dashboard de estatísticas.

## Funcionalidades

- Autenticação (registo, login, logout, "lembrar-me")
- Inventário de carros com foto, categoria, marca, modelo, cor, ano, placa e preço
- Gestão de categorias com contagem de veículos
- Pesquisa por marca, modelo, cor ou placa
- Paginação da lista de veículos (10 por página)
- Dashboard com estatísticas (total de veículos, categorias e valor do parque)
- Painel restrito a administradores (criar/editar/eliminar registos)
- **Tailwind CSS offline** — os assets compilados são versionados em `public/build/`,
  sem depender de CDN ou de `npm run build` para funcionar

## Requisitos

- PHP 8.3+
- Composer
- Node.js (apenas para recompilar assets, não necessário para executar)
- MySQL, SQLite ou outro banco suportado

## Instalação

```bash
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
php artisan serve
```

Para recompilar os assets com Tailwind (opcional):

```bash
npm install
npm run build
```

### Utilizadores padrão (após `--seed`)

| Papel | Email               | Palavra-passe |
|-------|---------------------|---------------|
| Admin | admin@example.com   | `password`    |
| Utilizador | test@example.com | `password`    |

## Testes

```bash
php artisan test
```

A suíte cobre autenticação, CRUD de carros e categorias, restrições de admin,
dashboard e modelos.

## Estrutura

```
app/
  Http/Controllers/   Controladores (Auth, Car, Category, Dashboard)
  Http/Middleware/    AdminMiddleware
  Http/Requests/      Validação via Form Requests
  Models/             Car, Category, User
database/
  factories/          Factories para testes e seed
  migrations/         Esquema do banco
  seeders/            Categorias e utilizadores padrão
resources/views/      Vistas Blade com layouts reutilizáveis
tests/                Testes de feature e unidade
```

## Licença

Licença MIT.
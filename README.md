<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework. You can also check out [Laravel Learn](https://laravel.com/learn), where you will be guided through building a modern Laravel application.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

## Laravel Sponsors

We would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the [Laravel Partners program](https://partners.laravel.com).

### Premium Partners

- **[Vehikl](https://vehikl.com)**
- **[Tighten Co.](https://tighten.co)**
- **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
- **[64 Robots](https://64robots.com)**
- **[Curotec](https://www.curotec.com/services/technologies/laravel)**
- **[DevSquad](https://devsquad.com/hire-laravel-developers)**
- **[Redberry](https://redberry.international/laravel-development)**
- **[Active Logic](https://activelogic.com)**

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).


## API Reference

Base URL: `http://localhost:8000/api` (local) — see Deployment section for production URL.

### Authentication

| Method | Endpoint | Auth required | Description |
|--------|----------|---------------|--------------|
| POST | `/register` | No | Create an account, returns user + token |
| POST | `/login` | No | Authenticate, returns user + token |
| POST | `/logout` | Yes | Revoke the current token |
| GET | `/me` | Yes | Get the current authenticated user |

### Wallets

| Method | Endpoint | Auth required | Description |
|--------|----------|---------------|--------------|
| POST | `/wallets` | Yes | Add a wallet (validates Ethereum address format) |
| DELETE | `/wallets/{id}` | Yes | Soft-delete a wallet |
| GET | `/wallets/trashed` | Yes | List soft-deleted wallets |
| POST | `/wallets/{id}/restore` | Yes | Restore a soft-deleted wallet |

### Projects

| Method | Endpoint | Auth required | Description |
|--------|----------|---------------|--------------|
| POST | `/projects` | Yes | Create a project |
| PUT | `/projects/{id}` | Yes | Update a project |
| DELETE | `/projects/{id}` | Yes | Soft-delete a project |
| GET | `/projects/trashed` | Yes | List soft-deleted projects |
| POST | `/projects/{id}/restore` | Yes | Restore a soft-deleted project |

### Eligibility

| Method | Endpoint | Auth required | Description |
|--------|----------|---------------|--------------|
| POST | `/eligibility/cycle` | Yes | Cycle a wallet's status for a project (unchecked → eligible → not_eligible) |
| GET | `/dashboard` | Yes | Fetch all of the user's wallets, projects, and eligibility in one call |

### Public

| Method | Endpoint | Auth required | Description |
|--------|----------|---------------|--------------|
| GET | `/public/ledger/{shareId}` | No | Read-only view of one project's eligibility, via its unique share link |

All authenticated endpoints expect `Authorization: Bearer <token>` (Laravel Sanctum tokens). Unauthenticated requests to protected routes return `401`.
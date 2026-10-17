# DhakaFin

Laravel 13 foundation for developing the existing DhakaFin frontend and enabling backend features one at a time.

## Current scope

- All 47 existing pages are Laravel Blade views with their original HTML output.
- CSS, JavaScript, images, metadata, animations and `.html` URLs are preserved.
- Existing Coming soon dialogs and email-app enquiries continue to work as before.
- No authentication, server-side submissions, calculators, AI integrations or billing is enabled by this conversion.
- The public frontend uses file sessions/cache and synchronous queues; it does not need a database.

## Local setup

PHP 8.3+ and Composer are required, with Laravel's usual extensions (including mbstring, XML/DOM, cURL, fileinfo, OpenSSL and PDO). PHP 8.4 is supported. No Node build is needed for the existing frontend.

```bash
composer install
cp .env.example .env
php artisan key:generate
php artisan serve
```

Open `http://localhost:8000`. `composer run dev` also starts the development server. Do not regenerate an existing production `APP_KEY`.

## Project map

| Location | Purpose |
| --- | --- |
| `resources/views/home.blade.php` | Existing homepage |
| `resources/views/pages/` | All 46 existing inner pages |
| `resources/views/errors/404.blade.php` | Existing error design, with root-relative links for nested URLs |
| `public/assets/` | Original frontend assets |
| `routes/web.php` | Explicit, named routes preserving existing URLs |
| `app/Http/Controllers/` | Future feature controllers |
| `app/Models/`, `database/migrations/` | Future data-backed features |
| `tests/Fixtures/frontend-manifest.json` | Original page and asset SHA-256 hashes |

Complete views use `@verbatim` to protect the original CSS and JavaScript from Blade interpretation. For a future feature, extract only the relevant area into a Blade partial/component and render validated server data with escaped Blade expressions. Preserve the surrounding DOM, classes, ordering, styles and responsive behavior. Do not place user input inside a raw HTML response or disable CSRF protection.

The standard user/cache/jobs migrations are included as future scaffolding. They are not run during frontend setup. There are no seeded users or production credentials.

## Validation

```bash
composer validate --strict
composer test
vendor/bin/pint --test
php artisan route:cache
php artisan view:cache
php artisan test
php artisan optimize:clear
```

The preservation suite requests every page, compares the complete response against the original HTML hash, verifies all static resources and internal links, checks the home alias and 404s, and prevents database connections during page rendering.

See [cPanel deployment](docs/cpanel-deployment.md) and the [backend roadmap](docs/backend-roadmap.md). The original frontend was preserved from commit `7611d473015607b76406db93b8083d8d2f2fd475`. Laravel application scaffolding is based on the official `laravel/laravel` 13.x skeleton.

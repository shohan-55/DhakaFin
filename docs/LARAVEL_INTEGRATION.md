# DhakaFin Laravel integration and staging validation

This development branch now contains a Laravel 13 application skeleton, the original static source, Blade views for the original pages, admin CMS controllers, database migrations, read-only public API endpoints, and tests.

## Important boundaries

- Do not merge to main, replace production files, or modify live databases without separate approval.
- Original page HTML is wrapped in Blade verbatim blocks to preserve HTML/JS/CSS. Visual equivalence still needs browser-based verification.
- All unfinished public tax, law-search and CMS API features are **disabled by default** using config/dhakafin.php and .env flags.
- Staging verification is required. Passing unit/feature tests alone cannot guarantee pixel-perfect appearance.
- Keep the original source files and public assets intact. No fake legal/tax data should be published.

## Staging environment (separate from live public_html)

Use PHP 8.4, Composer 2 and the isolated development branch.

1. Install dependencies: `composer install --no-interaction --prefer-dist`
2. Create an isolated environment: `cp .env.example .env`
3. Set staging APP_URL and isolated DB credentials in .env; **never copy production credentials or overwrite live .env**.
4. Generate a unique staging key: `php artisan key:generate`
5. Copy static assets verbatim: `php scripts/sync-public-assets.php`
6. Run migrations on the **staging database only**: `php artisan migrate --force`
7. Check routes: `php artisan route:list`
8. Run tests: `php artisan test`
9. Provision a privileged admin *on staging only* if needed: `php artisan dhakafin:admin`. The command prompts for the password securely and requires confirmation.
10. Verify each page, nested navigation, scripts, images, calculator gating, admin login, forms and error pages in desktop/mobile browsers. Confirm no console/network errors.
11. Keep production feature flags disabled unless functionality and Bangladesh legal citations are independently verified.

## Development checks

GitHub Actions workflow in .github/workflows/ci.yml installs dependencies, synchronizes assets, checks PHP syntax and executes the test suite. No successful CI run was verified at the time this document was written.

## Production migration

After review and explicit approval, prepare a staging-to-production deployment procedure with maintenance/rollback and database backup. The existing cPanel Laravel installation may have different files/migrations; compare carefully before moving anything.

## Security rules

- No sample users or demo tax records should be seeded in production.
- Administrator provisioning is interactive; avoid passing passwords as shell arguments.
- Do not enable tax calculation or law-search endpoints just because UI pages render.
- Never expose .env, database files, vendor directories, logs or administrator routes as static files.

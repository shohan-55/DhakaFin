# DhakaFin — Laravel 13 staging and release checklist

**Status:** Development branch only. Never merge or deploy without owner approval. The existing DhakaFin production website is not modified by these commits.

## Scope

- All 46 original `pages/*.html` files are represented by `resources/views/pages/*.blade.php`.
- Original `index.html` and custom `404.html` have branded Blade equivalents.
- A source-parity script compares every one of the 47 home/inner page files to its original static HTML. Only relative asset/page links are normalized; other markup must remain unchanged.
- The original `assets/` directory remains the source of truth and copies without changes to Laravel `public/assets/`.
- Laravel uses original `/pages/<slug>.html` URLs.
- Unreleased tools and services default to **Coming Soon**; marketing pages remain visible.
- Framework feature tests, PHP lint, markup-parity test and read-only preflight are configured in GitHub Actions.

## Isolated staging on cPanel (no live changes)

The cPanel account previously used `/home/dhakafin841/dhakafin-app` for an experimental Laravel skeleton, and Composer is at `/home/dhakafin841/composer.phar`. **Do not overwrite that directory or `public_html`.** Use a new `dhakafin-stage` directory after confirming available storage.

```bash
cd /home/dhakafin841 && git clone --branch audit/laravel-conversion-20261017 --single-branch https://github.com/shohan-55/DhakaFin.git dhakafin-stage
cd /home/dhakafin841/dhakafin-stage && cp .env.example .env && php /home/dhakafin841/composer.phar install --no-interaction --prefer-dist --optimize-autoloader
php artisan key:generate && php scripts/sync-public-assets.php && php scripts/verify-view-parity.php && php scripts/preflight.php && php artisan test
```

Staging requires a **separate password-protected hostname or document root** targeting `/home/dhakafin841/dhakafin-stage/public`. Avoid publishing an unprotected staging website. Exact cPanel domain-root settings must be confirmed before any deployment.

## Release gates

1. Source-parity script reports 47 views checked and zero failures.
2. GitHub Actions completes PHP lint, Laravel boot, 46 page HTTP checks, feature flag checks and custom 404.
3. Desktop/mobile screenshots of static versus Laravel pages show no visual regressions; check navigation, motion effects, forms, header/footer and links.
4. PHP production preflight passes; APP_DEBUG=false and APP_KEY configured. No credentials are committed.
5. Owner explicitly approves merge and production deployment, including a rollback plan.
6. Do not activate TDS/VDS/VAT calculators or legal research based on unverified rates, examples or placeholder data.

## Development roadmap

- Phase 1: Inventory and architecture — inventoried 46 pages and static assets.
- Phase 2: Source-preserving frontend migration — code drafted; staging/browser verification pending.
- Phase 3: Feature controls — implemented, automated test run pending.
- Phase 4: Production foundation — Laravel skeleton, testing pipeline and preflight drafted; deployment pending.
- Phases 5–12: Admin CMS, Bangladesh tax tool verification, notices/deadlines, cited law search, blog, auth, advanced tools, hardening and commercialization — **not claimed as complete**.

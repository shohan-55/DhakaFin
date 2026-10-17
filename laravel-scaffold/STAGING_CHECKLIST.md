# DhakaFin Laravel staging checklist

Do not use these instructions on the live document root until staging tests pass and deployment is approved.

## Source layout

- Existing static files remain on `main`.
- Conversion scaffold is under `laravel-scaffold/` on `audit/laravel-conversion-20261017`.
- Existing Laravel skeleton: `/home/dhakafin841/dhakafin-app` (reported by owner, not independently accessed).
- The importer is designed to be run against a clean staging Laravel project. It refuses to overwrite pre-existing imported views or assets.

## Staging sequence

1. Obtain a working copy of the repository on the server or local machine.
2. Make a separate staging Laravel application or take a verified application backup before changing an existing Laravel skeleton.
3. Review `laravel-scaffold/import.php`, then import static files into staging.
4. Copy `laravel-scaffold/routes/web.php` to staging `routes/web.php` and `laravel-scaffold/tests/Feature/LegacyFrontendRoutesTest.php` to staging tests.
5. Run `php artisan route:list` and `php artisan test --filter=LegacyFrontendRoutesTest`.
6. Check that all CSS, JavaScript, fonts and images load on homepage and nested pages.
7. Compare desktop and mobile screenshots to the current production site.
8. Verify all pending tax calculators and AI forms remain disabled and no fake calculations or submissions occur.
9. Review `APP_DEBUG=false`, `APP_ENV=production`, web root, permissions, storage and log handling before launch.
10. Obtain explicit approval before merge or live deployment.

## Known limitations

- Route smoke tests do not prove pixel-perfect parity.
- Importer does not rewrite all possible URLs; audit links and forms before deployment.
- No complete file-tree audit or end-to-end browser testing has been performed.
- No live cPanel commands have been executed by this work.

# cPanel deployment

Deploy to a staging subdomain first and compare it with the current site before switching the live domain. This repository conversion does not change the live hosting configuration.

## Recommended layout

Keep the application outside the web-accessible directory, for example `/home/CPANEL_USER/dhakafin-app`. Set the domain's document root to `/home/CPANEL_USER/dhakafin-app/public`. Only `public/` may be served. Serving the repository root can expose configuration, dependencies and source files; the root `.htaccess` intentionally denies requests.

Select PHP 8.3 or 8.4 in cPanel and use the same PHP version for terminal Composer/Artisan commands. Enable Laravel's required extensions. Apache/LiteSpeed must process `public/.htaccess` and URL rewrites.

## First deployment

1. Back up the current site and record its document root so you can restore it.
2. Check out the reviewed Laravel branch into the application directory.
3. In that directory run `composer install --no-dev --prefer-dist --no-interaction --optimize-autoloader`.
4. Copy `.env.example` to `.env` once. Set `APP_ENV=production`, `APP_DEBUG=false`, `APP_URL=https://dhakafin.com` (or the staging URL), and `SESSION_SECURE_COOKIE=true` for HTTPS.
5. Run `php artisan key:generate` only for this brand-new environment, then `chmod 600 .env`.
6. Make `storage/` and `bootstrap/cache/` writable by the account running PHP. Use the hosting provider's ownership/permission requirements; do not use world-writable permissions.
7. Run `php artisan optimize`.
8. Point the staging domain to `public/`, enable HTTPS, and check the homepage, inner pages, mobile navigation, Coming soon popup, contact email enquiry, `/up` and a nested missing URL.
9. After review, switch the live domain's document root to the verified `public/` directory. Remove any stale static site files from the served directory only after backing them up.

No database creation, migration, queue worker, scheduler, Node install or frontend build is required in this phase. Contact and consultation forms still open the visitor's email application; they do not save or send a server-side message.

## If cPanel fixes the document root to public_html

Ask the hosting provider to point the domain at the application's `public/` directory. If that cannot be done, keep the full application outside `public_html`, copy only the contents of `public/` (including its `.htaccess`) into `public_html`, and update the three application paths in that copied `index.php`:

```php
if (file_exists($maintenance = '/home/CPANEL_USER/dhakafin-app/storage/framework/maintenance.php')) {
    require $maintenance;
}

require '/home/CPANEL_USER/dhakafin-app/vendor/autoload.php';
$app = require_once '/home/CPANEL_USER/dhakafin-app/bootstrap/app.php';
$app->usePublicPath(__DIR__);
$app->handleRequest(Illuminate\Http\Request::capture());
```

Keep the rest of the original `index.php` initialization intact. Replace `CPANEL_USER` with the actual account name. Keep the copied public assets synchronized on later deployments. Do not copy `.env`, `.git`, `vendor`, `storage`, `config`, or the repository root `.htaccess` into `public_html`.

## Later releases and rollback

Preserve `.env` and the existing `APP_KEY`. Deploy reviewed commits, install from `composer.lock`, and refresh caches with `php artisan optimize:clear` followed by `php artisan optimize`. Database migrations need a separate plan when a data-backed feature is introduced.

For this frontend-only conversion, rollback can restore the previous static document root and backup. Once backend features store data, use a release-specific backup and migration rollback plan.

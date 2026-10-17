# Conversion validation

Source: `shohan-55/DhakaFin` commit `7611d473015607b76406db93b8083d8d2f2fd475`.

## Automated checks

- PHP 8.3.6 with Laravel 13.37.0 and locked Composer dependencies.
- 52 tests passed with 5,603 assertions.
- All 47 HTML responses match the original Git checkout byte for byte.
- All 15 public assets and sitemap/robots resources match the original checkout.
- All HTML internal links and resource paths resolve.
- Page requests and HEAD requests pass without database connections.
- Home alias, nested 404 design, health route and unsupported POST handling pass.
- Tests pass with route/view caches and with the full `artisan optimize` production caches.
- `composer validate --strict`, Laravel Pint, Git whitespace checks and `composer audit` pass.

## Browser checks

Original static checkout and Laravel output were rendered in Chromium at 1440×900 and 390×900. The viewport screenshots match exactly for the homepage, About, Services, Contact and Tools: ten matching comparisons. These page loads produced no JavaScript errors or failed resource responses.

Desktop grouped navigation, mobile menu toggling, the personal tax Coming soon dialog and Escape-to-close were exercised successfully.

The 404's relative resource/home/contact URLs were changed to root-relative paths so the original error design works at nested missing URLs. All 47 regular pages remain byte-identical.

PHP 8.3 and 8.4 are configured in GitHub Actions. The local checks above used PHP 8.3. The live cPanel environment and domain document root have not been modified; staging verification remains part of deployment.

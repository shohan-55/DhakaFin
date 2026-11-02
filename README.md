# DhakaFin — Complete Laravel 12 Project

DhakaFin is a full-featured Laravel 12 web application for **Accounting, Tax, VAT & Financial Advisory in Bangladesh**.

## Project Architecture

- **Framework**: Laravel 12 (`^12.0`), PHP `^8.2`
- **Controllers**:
  - `App\Http\Controllers\PageController`: Serves the homepage (`/`), all 46 advisory, digital tool, tax research, and company pages (`/pages/{slug}`, `/pages/{slug}.html`, `/{slug}`, `/{slug}.html`), legacy portal routes, and SEO endpoints (`/sitemap.xml`, `/sitemap.json`).
  - `App\Http\Controllers\EnquiryController`: Handles `POST /enquiry` submissions with validation and database/log persistence (`App\Models\Enquiry`).
- **Blade Templates (`resources/views/`)**:
  - `layouts/home.blade.php` & `layouts/app.blade.php` — Master Blade layouts with SEO metadata, OpenGraph tags, JSON-LD structured data, and asset helpers (`asset(...)`).
  - `partials/header.blade.php`, `partials/footer.blade.php`, `partials/simple-footer.blade.php`, `partials/home-header.blade.php`, `partials/home-footer.blade.php`, `partials/home-dialogs.blade.php` — Reusable navigation mega-menus, mobile accordions, footers, and modal dialogs.
  - `pages/home.blade.php` & 46 inner Blade views in `pages/*.blade.php` — Cleanly converted from the static templates with Laravel named routes (`route('home')`, `route('pages.show', $slug)`).
  - `errors/404.blade.php` — Custom 404 error page.
- **Static Assets (`public/assets/`)**:
  - `home.css`, `site.css`, `signature.css`, `motion.css`, `production.css`, `grouped-nav.css`
  - `site.js`, `signature.js`, `motion.js`, `production.js`, `grouped-nav.js`
  - `favicon.svg`, `dhakafin-og.png`

## Quick Start (Local Development)

1. Install Composer dependencies:
   ```bash
   composer install
   ```
2. Copy the environment configuration and generate an application key:
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```
3. Run database migrations (uses SQLite by default, or configure MySQL in `.env`):
   ```bash
   touch database/database.sqlite
   php artisan migrate
   ```
4. Start the local development server:
   ```bash
   php artisan serve
   ```
   Open `http://127.0.0.1:8000` in your browser.

## Deployment (cPanel / Shared Hosting / VPS)

- **VPS / Dedicated Server (Nginx / Apache)**: Point your web server's `DocumentRoot` to the `public/` directory.
- **cPanel / Shared Hosting**: The root `.htaccess` file automatically forwards requests to `public/`, so it works out of the box even if extracted into `public_html`. Ensure PHP 8.2+ is selected in cPanel MultiPHP Manager and run `composer install --no-dev --optimize-autoloader`.

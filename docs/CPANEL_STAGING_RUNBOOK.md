# DhakaFin: isolated cPanel staging and cutover runbook

**No commands below have been run on the actual cPanel account.**
The GitHub CI builds a versioned staging ZIP only after PHP, security,
47-view desktop/mobile pixel parity, and enabled-tool browser smoke tests pass.

## 1. Isolate staging from live DhakaFin

The existing cPanel account is \`dhakafin841\` and the independently
installed Laravel skeleton was at \`/home/dhakafin841/dhakafin-app\`.
Do not overwrite that folder or \`public_html\` as part of staging.

Download the **dhakafin-cpanel-staging-zip** artifact from the successful
"Laravel CI" GitHub Actions run. The ZIP contains a \`release/\` directory
and never contains production \`.env\`, SQL backups or credentials.

Upload it through cPanel File Manager into a new folder such as
\`/home/dhakafin841/dhakafin-staging\`, then extract in that folder.
The application root becomes \`/home/dhakafin841/dhakafin-staging/release\`.

Create a dedicated staging subdomain in cPanel with document root:
\`/home/dhakafin841/dhakafin-staging/release/public\`.
**Only the public directory may be web-accessible.**

## 2. Configure a staging-only environment

Inside \`/home/dhakafin841/dhakafin-staging/release\`:

\`\`\`bash
cp .env.example .env && chmod 600 .env
\`\`\`

Set \`APP_ENV=staging\`, \`APP_DEBUG=false\`, \`APP_URL\` to the actual
staging URL, and separate staging database configuration in \`.env\`.
Do not reuse live database credentials or copy the live application key.

For a fresh staging installation, generate the key and run migrations
**only after confirming the staging database target**:

\`\`\`bash
php artisan key:generate && php artisan migrate --force && php artisan dhakafin:preflight
\`\`\`

Create an administrator on staging with an interactive password prompt:

\`\`\`bash
php artisan dhakafin:admin
\`\`\`

Finish with \`php artisan route:list\` and check browser network errors.
Production release ZIP excludes developer test dependencies, so the
\`php artisan test\` suite must be passed in GitHub CI, not this ZIP.

## 3. Acceptance criteria before switching the public domain

- Desktop and mobile layouts visually match all original Blade snapshots.
- Header navigation, grouped menus, anchors and page links work.
- Static CSS/JS/images return HTTP 200, and no JS console errors.
- All original \`/pages/*.html\` routes resolve or intentionally show Coming Soon.
- Admin login, role restrictions, CSRF protection and editor CRUD pass.
- Source-backed calculation requires effective, human-verified rate records.
- VAT inclusive/exclusive arithmetic, transaction-date selection, and
  law search are tested with synthetic **CI-only** fixtures.
- All unreleased features remain disabled in live \`.env\`.
- \`/.env\`, \`/vendor/\`, \`/storage/\`, \`/database/\`, \`/.git/\`
  must not be publicly readable; the domain's document root must be \`public\`.
- Preflight must pass, and the exact Git commit SHA of the staging bundle
  must be recorded for safe rollback.

## 4. Production cutover

Do not automatically repoint the live domain, edit live DB, remove
existing static files, change main branch or deploy through cPanel Git
until a reviewed staging release is accepted.

Keep the current live site intact for immediate rollback. Once a
cutover is decided, use an atomic docroot/release switch where cPanel
supports it, or a scheduled maintenance-and-rollback procedure.
Never overwrite an existing live \`.env\` or SQLite/MySQL database.

#!/usr/bin/env bash
# Direct cPanel deployment from a Git checkout outside public_html.
# Does NOT connect to cPanel remotely or deploy automatically.
set -Eeuo pipefail
umask 027

APP="$(cd "$(dirname "$0")/.." && pwd -P)"
DOCROOT="$HOME/public_html"
if [[ ! -d "$DOCROOT" || -L "$DOCROOT" ]]; then
    echo "ERROR: document root missing or symlinked: $DOCROOT" >&2
    exit 1
fi
DOCROOT="$(cd "$DOCROOT" && pwd -P)"
if [[ "$(dirname "$APP")" != "$(dirname "$DOCROOT")" || "$APP" == "$DOCROOT" ]]; then
    echo "ERROR: Laravel checkout must be a sibling of public_html, not inside it." >&2
    exit 1
fi
APPNAME="$(basename "$APP")"

for file in "$APP/artisan" "$APP/.env" "$APP/vendor/autoload.php" \
    "$APP/public/index.php" "$APP/public/.htaccess" "$APP/composer.lock"; do
    if [[ ! -f "$file" ]]; then
        echo "ERROR: missing $file" >&2
        exit 1
    fi
done

# Do not accidentally serve a directory containing Laravel secrets.
for path in "$DOCROOT/.env" "$DOCROOT/vendor" "$DOCROOT/database" "$DOCROOT/storage"; do
    if [[ -e "$path" ]]; then
        echo "ERROR: suspicious protected application path in webroot: $path" >&2
        exit 1
    fi
done

cd "$APP"
php artisan optimize:clear
php scripts/sync-public-assets.php
php artisan dhakafin:preflight

# Produce a front controller that bootstraps Laravel from its private sibling
# directory, rather than the default ../ path used by Laravel's own public/.
if [[ $(grep -Fc "__DIR__.'/../" "$APP/public/index.php") -lt 3 ]]; then
    echo "ERROR: unexpected Laravel front controller; review paths manually." >&2
    exit 1
fi
INDEX_TMP="$(mktemp "$APP/storage/app/dhakafin-index.XXXXXX")"
trap 'rm -f "$INDEX_TMP"' EXIT
sed "s|__DIR__.'/../|__DIR__.'/../$APPNAME/|g" \
    "$APP/public/index.php" > "$INDEX_TMP"
php -l "$INDEX_TMP" >/dev/null

# Ensure DB and schema are ready before publishing the new HTTP entry point.
php artisan migrate --force
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Minimal rollback snapshot OUTSIDE public_html. Never delete existing files.
STAMP="$(date +%Y%m%d-%H%M%S)"
BACKUP="$(dirname "$DOCROOT")/dhakafin-site-rollback/$STAMP"
mkdir -p "$BACKUP"
chmod 700 "$BACKUP"
for item in index.html index.php .htaccess pages assets robots.txt sitemap.xml sitemap.json 404.html; do
    if [[ -e "$DOCROOT/$item" ]]; then
        cp -a "$DOCROOT/$item" "$BACKUP/$item"
    fi
done
echo "Rollback snapshot: $BACKUP"

# Original /pages/*.html files would bypass Laravel and its feature flags.
# Move them away only after the database, PHP entrypoint and rollback copy
# have been validated.
if [[ -d "$DOCROOT/pages" ]]; then
    mv "$DOCROOT/pages" "$BACKUP/pages-disabled"
fi
if [[ -f "$DOCROOT/index.html" ]]; then
    mv "$DOCROOT/index.html" "$BACKUP/index.html-disabled"
fi

mkdir -p "$DOCROOT/assets"
cp -a "$APP/public/assets/." "$DOCROOT/assets/"
# Copy root-level public metadata too (robots, sitemap, custom 404), but
# never copy the unpatched Laravel front controller into public_html.
for file in "$APP/public/"*; do
    if [[ -f "$file" && "$(basename "$file")" != "index.php" ]]; then
        cp -a "$file" "$DOCROOT/$(basename "$file")"
    fi
done
cp "$APP/public/.htaccess" "$DOCROOT/.htaccess"
cp "$INDEX_TMP" "$DOCROOT/.index.php.new"
chmod 644 "$DOCROOT/.htaccess" "$DOCROOT/.index.php.new"
mv -f "$DOCROOT/.index.php.new" "$DOCROOT/index.php"
echo "DhakaFin Laravel webroot published: $DOCROOT"
echo "Original site snapshot: $BACKUP"
echo "Next: test https://dhakafin.com/ , /pages/about.html , /admin/login"
echo "Do not enable unverified tax/law features without live data review."

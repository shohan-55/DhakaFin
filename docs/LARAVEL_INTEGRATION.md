# DhakaFin Laravel integration (development only)

The existing GitHub repository is a static site. It does **not** contain Laravel's composer.json, artisan, or public/index.php. The Blade files in this branch are therefore **not a standalone deployable Laravel application**.

## Current development changes
- Original homepage preserved as resources/views/home.blade.php.
- Eleven inner pages preserved as resources/views/pages/*.blade.php.
- routes/web.php serves the homepage and the existing /pages/*.html URLs.
- scripts/sync-public-assets.php copies the original assets/ tree unchanged to public/assets.
- tests/Feature/PublicPagesTest.php checks the routes.

## Integration into a separate Laravel skeleton
1. Use a staging clone of this branch and a **separate, already installed Laravel app**.
2. Copy resources/views, routes/web.php, and tests/Feature/PublicPagesTest.php into the Laravel app.
3. Copy the original assets directory and scripts/sync-public-assets.php into the Laravel app.
4. In the Laravel app run: php scripts/sync-public-assets.php
5. Run: php artisan route:list
6. Run: php artisan test --filter=PublicPagesTest
7. Check homepage and every converted page in desktop and mobile browsers; check Network for missing assets.
8. Verify remaining static pages and relative links before approving a production release.

Do **not** overwrite a live Laravel app's routes, files, or .env without reviewing differences and creating a rollback point. Do not merge or deploy this branch until tested and explicitly approved.

## Known gaps
- Not all original static pages have been inventoried or migrated.
- Existing CSS/JS have not been visually regression-tested.
- Tax calculators, forms, legal text, and smart law search have not been verified as working backend features.
- Laravel skeleton and Composer dependencies have not been committed into this branch.

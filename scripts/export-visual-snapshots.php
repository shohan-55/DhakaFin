<?php
/**
 * Export rendered Blade views for screenshot comparison only.
 *
 * Deliberately does NOT publish protected/unreleased pages through HTTP.
 * This runs in isolated CI, not under public_html and not in production.
 */
declare(strict_types=1);

$root = dirname(__DIR__);
require $root . '/vendor/autoload.php';

$app = require $root . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$target = $root . '/build/visual-snapshots';
if (!is_dir($target) && !mkdir($target, 0755, true) && !is_dir($target)) {
    throw new RuntimeException('Could not prepare snapshot directory.');
}

file_put_contents($target . '/index.html', view('home')->render());
$count = 1;
foreach (config('dhakafin.pages', []) as $page) {
    if (!is_string($page) || !preg_match('/\A[a-z0-9-]+\z/D', $page)) {
        throw new InvalidArgumentException('Invalid page identifier.');
    }
    $dir = $target . '/pages';
    if (!is_dir($dir) && !mkdir($dir, 0755, true) && !is_dir($dir)) {
        throw new RuntimeException('Could not prepare pages directory.');
    }
    file_put_contents($dir . '/' . $page . '.html', view('pages.' . $page)->render());
    $count++;
}

echo "Exported {$count} compiled Laravel views for isolated screenshot comparison.\n";

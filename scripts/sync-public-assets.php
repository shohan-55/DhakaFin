<?php
/**
 * Copy the original static assets into Laravel's public directory without
 * changing their contents. Run from the repository root:
 * php scripts/sync-public-assets.php
 */
declare(strict_types=1);

$root = dirname(__DIR__);
$source = $root . '/assets';
$target = $root . '/public/assets';

if (!is_dir($source)) {
    fwrite(STDERR, "Missing source assets directory: {$source}\n");
    exit(1);
}
if (!is_dir($root . '/public')) {
    fwrite(STDERR, "Missing Laravel public directory\n");
    exit(1);
}

$sourceIterator = new RecursiveIteratorIterator(
    new RecursiveDirectoryIterator($source, FilesystemIterator::SKIP_DOTS)
);
$count = 0;
foreach ($sourceIterator as $file) {
    if (!$file->isFile() || $file->isLink()) {
        continue;
    }
    $relative = substr($file->getPathname(), strlen($source) + 1);
    $destination = $target . DIRECTORY_SEPARATOR . $relative;
    $directory = dirname($destination);
    if (!is_dir($directory) && !mkdir($directory, 0755, true) && !is_dir($directory)) {
        throw new RuntimeException("Could not create directory: {$directory}");
    }
    if (!copy($file->getPathname(), $destination)) {
        throw new RuntimeException("Could not copy asset: {$relative}");
    }
    $count++;
}
echo "Synced {$count} original asset files to public/assets.\n";

// Preserve original public SEO and error documents as well.
foreach (['robots.txt', 'sitemap.xml', 'sitemap.json', '404.html'] as $file) {
    $original = $root . DIRECTORY_SEPARATOR . $file;
    if (is_file($original) && !copy($original, $root . '/public/' . $file)) {
        throw new RuntimeException("Could not copy public document: {$file}");
    }
}
echo "Synced original public SEO documents.\n";

<?php
/**
 * One-time non-destructive static-to-Blade importer.
 * Usage: php laravel-scaffold/import.php /path/to/DhakaFin /home/dhakafin841/dhakafin-app
 * Run only in a staging Laravel project; does not touch public_html.
 */
if ($argc !== 3) { fwrite(STDERR, "Usage: php import.php SOURCE_REPO LARAVEL_ROOT\n"); exit(2); }
$src = realpath($argv[1]);
$dst = realpath($argv[2]);
if (!$src || !$dst || !is_file("$src/index.html") || !is_file("$dst/artisan")) {
    fwrite(STDERR, "Invalid source repository or Laravel root\n"); exit(2);
}
$views = "$dst/resources/views/legacy";
if (is_dir($views) || file_exists("$dst/public/assets")) {
    fwrite(STDERR, "Target already contains imported views or assets; refusing to overwrite\n"); exit(2);
}
$pages = glob("$src/pages/*.html") ?: [];
$files = array_merge(["$src/index.html"], $pages);
foreach ($files as $file) {
    $html = file_get_contents($file);
    if ($html === false) { throw new RuntimeException("Cannot read $file"); }
    // Convert only relative asset URLs, not markup or styling.
    $html = preg_replace('~((?:src|href)\s*=\s*["\'])\.\./assets/~i', '$1/assets/', $html);
    $html = preg_replace('~((?:src|href)\s*=\s*["\'])assets/~i', '$1/assets/', $html);
    $subdir = $file === "$src/index.html" ? '' : '/pages';
    $name = $file === "$src/index.html" ? 'index' : pathinfo($file, PATHINFO_FILENAME);
    $targetDir = $views . $subdir;
    if (!is_dir($targetDir) && !mkdir($targetDir, 0755, true) && !is_dir($targetDir)) {
        throw new RuntimeException("Cannot create $targetDir");
    }
    file_put_contents("$targetDir/$name.blade.php", "@verbatim\n".$html."\n@endverbatim\n");
}
$assetSrc = "$src/assets";
if (!is_dir($assetSrc)) { throw new RuntimeException('Missing assets directory'); }
$copy = function ($from, $to) use (&$copy) {
    if (is_link($from)) { throw new RuntimeException("Symlinks not allowed: $from"); }
    if (is_dir($from)) {
        if (!mkdir($to, 0755, true) && !is_dir($to)) { throw new RuntimeException("Cannot create $to"); }
        foreach (new DirectoryIterator($from) as $entry) {
            if ($entry->isDot()) continue;
            $copy($entry->getPathname(), "$to/".$entry->getFilename());
        }
    } elseif (is_file($from)) {
        if (!copy($from, $to)) { throw new RuntimeException("Cannot copy $from"); }
    }
};
$copy($assetSrc, "$dst/public/assets");
echo "Imported ".count($files)." pages and assets. Install scaffold routes and test before deploying.\n";

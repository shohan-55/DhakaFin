<?php
/**
 * CI smoke test for static-to-Blade import. No Laravel installation required.
 * Creates a temporary fake Laravel directory and removes it afterwards.
 */
$source = realpath(__DIR__.'/..');
$tmp = sys_get_temp_dir().'/dhakafin-import-'.bin2hex(random_bytes(6));
if (!mkdir($tmp, 0700, true)) { throw new RuntimeException('Cannot create temp directory'); }
$remove = function (string $path) use (&$remove): void {
    if (is_dir($path) && !is_link($path)) {
        foreach (new DirectoryIterator($path) as $item) {
            if (!$item->isDot()) $remove($item->getPathname());
        }
        rmdir($path);
    } else {
        unlink($path);
    }
};
try {
    file_put_contents($tmp.'/artisan', "<?php // test fixture\n");
    $cmd = escapeshellarg(PHP_BINARY).' '.escapeshellarg(__DIR__.'/import.php').' '
        .escapeshellarg($source).' '.escapeshellarg($tmp).' 2>&1';
    exec($cmd, $output, $code);
    if ($code !== 0) throw new RuntimeException("Importer failed: ".implode("\n", $output));
    $homepage = $tmp.'/resources/views/legacy/index.blade.php';
    $tools = $tmp.'/resources/views/legacy/pages/tools.blade.php';
    if (!is_file($homepage) || !is_file($tools)) throw new RuntimeException('Required views missing');
    if (!is_file($tmp.'/public/assets/site.css')) throw new RuntimeException('CSS asset missing');
    $html = file_get_contents($homepage);
    if (!str_contains($html, '@verbatim') || !str_contains($html, 'DhakaFin')) {
        throw new RuntimeException('Homepage HTML not preserved');
    }
    $output = [];
    exec($cmd, $output, $code);
    if ($code === 0) throw new RuntimeException('Importer must refuse to overwrite existing output');
    echo "PASS: homepage, nested page, assets, markup and overwrite protection\n";
} finally {
    $remove($tmp);
}

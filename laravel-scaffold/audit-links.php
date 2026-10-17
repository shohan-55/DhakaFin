<?php
/**
 * Read-only inventory of local links and assets in DhakaFin static HTML.
 * Usage: php laravel-scaffold/audit-links.php /path/to/DhakaFin
 * No files are modified. Exit code 1 means broken local references were found.
 */
if ($argc !== 2 || !is_dir($argv[1]) || !is_file(rtrim($argv[1], '/').'/index.html')) {
    fwrite(STDERR, "Usage: php audit-links.php SOURCE_REPO\n");
    exit(2);
}
$root = realpath($argv[1]);
$files = array_merge(["$root/index.html"], glob("$root/pages/*.html") ?: []);
$broken = [];
$checked = 0;
foreach ($files as $file) {
    $html = file_get_contents($file);
    if ($html === false) { fwrite(STDERR, "Unreadable: $file\n"); exit(2); }
    preg_match_all('~\\b(?:href|src)\\s*=\\s*(["\\\'])(.*?)\\1~is', $html, $matches, PREG_SET_ORDER);
    foreach ($matches as $match) {
        $url = html_entity_decode(trim($match[2]), ENT_QUOTES | ENT_HTML5, 'UTF-8');
        if ($url === '' || $url[0] === '#' || preg_match('~^(?:[a-z][a-z0-9+.-]*:|//)~i', $url)) continue;
        $path = parse_url($url, PHP_URL_PATH);
        if (!is_string($path) || $path === '') continue;
        if (str_starts_with($path, '/')) {
            $candidate = $root.$path;
        } else {
            $candidate = dirname($file).'/'.$path;
        }
        $checked++;
        if (!file_exists($candidate)) {
            $broken[] = substr($file, strlen($root)+1).' -> '.$url;
        }
    }
}
echo 'Pages inspected: '.count($files)."\n";
echo "Local references checked: $checked\n";
echo 'Missing local references: '.count($broken)."\n";
foreach ($broken as $item) echo "MISSING: $item\n";
exit($broken ? 1 : 0);

<?php
declare(strict_types=1);

/**
 * Read-only preflight for a staging/production Laravel installation.
 * Does not print .env contents, credentials, or customer data.
 */
$root = dirname(__DIR__);
$errors = [];
$checks = [
    'PHP >= 8.3' => PHP_VERSION_ID >= 80300,
    'vendor/autoload.php exists' => is_file($root . '/vendor/autoload.php'),
    '.env exists' => is_file($root . '/.env'),
    'Laravel bootstrap exists' => is_file($root . '/bootstrap/app.php'),
    'Public entry point exists' => is_file($root . '/public/index.php'),
    'Original assets available' => is_file($root . '/public/assets/site.css')
        && is_file($root . '/public/assets/site.js'),
    'Views directory exists' => is_dir($root . '/resources/views/pages'),
    'storage is writable' => is_writable($root . '/storage'),
    'bootstrap/cache is writable' => is_writable($root . '/bootstrap/cache'),
];
foreach (['mbstring', 'openssl', 'pdo', 'tokenizer', 'xml', 'ctype', 'fileinfo'] as $extension) {
    $checks["PHP {$extension} extension"] = extension_loaded($extension);
}

$env = @file_get_contents($root . '/.env') ?: '';
$checks['APP_KEY configured'] = (bool) preg_match('/^APP_KEY=base64:[A-Za-z0-9+\/=]+\s*$/m', $env);
$checks['APP_DEBUG disabled'] = (bool) preg_match('/^APP_DEBUG=false\s*$/mi', $env);

foreach ($checks as $name => $ok) {
    echo ($ok ? '[OK] ' : '[FAIL] ') . $name . PHP_EOL;
    if (!$ok) {
        $errors[] = $name;
    }
}
echo count($errors) === 0 ? "Preflight PASS\n" : "Preflight FAIL: " . count($errors) . " check(s)\n";
exit($errors ? 1 : 0);

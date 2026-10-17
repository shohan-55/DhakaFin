<?php
declare(strict_types=1);

// Framework-independent exact-markup check. Only asset / page links may change
// from relative static-site locations to absolute Laravel locations.
$root = dirname(__DIR__);
$failures = [];
$checked = 0;

$rewrite = static function (string $html, bool $home): string {
    foreach (['href', 'src'] as $attribute) {
        foreach (['"', "'"] as $quote) {
            if ($home) {
                foreach (['assets', 'pages'] as $directory) {
                    $html = str_replace(
                        "{$attribute}={$quote}{$directory}/",
                        "{$attribute}={$quote}/{$directory}/",
                        $html
                    );
                }
            } else {
                foreach (['assets', 'pages'] as $directory) {
                    $html = str_replace(
                        "{$attribute}={$quote}../{$directory}/",
                        "{$attribute}={$quote}/{$directory}/",
                        $html
                    );
                }
                $html = str_replace(
                    "{$attribute}={$quote}../index.html",
                    "{$attribute}={$quote}/",
                    $html
                );
            }
        }
    }

    return $html;
};

$compare = static function (string $source, string $view, bool $home) use (&$failures, &$checked, $rewrite): void {
    $checked++;
    $original = @file_get_contents($source);
    $converted = @file_get_contents($view);
    if ($original === false || $converted === false) {
        $failures[] = "Missing source or Blade view: {$source} / {$view}";
        return;
    }

    if ($rewrite($original, $home) !== $converted) {
        $failures[] = "Markup differs unexpectedly: {$view}";
    }
};

$compare($root . '/index.html', $root . '/resources/views/home.blade.php', true);

foreach (glob($root . '/pages/*.html') ?: [] as $source) {
    $slug = pathinfo($source, PATHINFO_FILENAME);
    $compare($source, $root . '/resources/views/pages/' . $slug . '.blade.php', false);
}

foreach ($failures as $failure) {
    fwrite(STDERR, "FAIL: {$failure}\n");
}
echo "Checked {$checked} HTML-to-Blade mappings; failures: " . count($failures) . "\n";
exit($failures ? 1 : 0);

<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Throwable;

final class Preflight extends Command
{
    protected $signature = 'dhakafin:preflight {--no-database : Skip the database connectivity check}';
    protected $description = 'Read-only deployment preflight for routes, assets, feature flags and configuration.';

    public function handle(): int
    {
        $failed = false;
        $check = function (bool $ok, string $label) use (&$failed): void {
            $this->line(($ok ? '[PASS] ' : '[FAIL] ') . $label);
            $failed = $failed || !$ok;
        };

        $check(
            is_string(config('app.key')) && config('app.key') !== '',
            'Application encryption key configured'
        );
        $check(
            config('app.env') !== 'production' || config('app.debug') === false,
            'Debug mode is disabled in production'
        );
        $check(
            is_file(public_path('index.php')),
            'Laravel public/index.php is present'
        );
        $check(view()->exists('home'), 'Homepage view exists');

        $pages = config('dhakafin.pages', []);
        $check(is_array($pages) && count($pages) > 0, 'Original page manifest is populated');
        foreach (is_array($pages) ? $pages : [] as $page) {
            $check(
                is_string($page) && view()->exists('pages.' . $page),
                'Original page available: ' . (string) $page
            );
        }

        foreach ([
            'site.css', 'site.js', 'grouped-nav.js',
            'motion.js', 'production.js',
        ] as $asset) {
            $check(is_file(public_path('assets/' . $asset)), 'Static asset present: ' . $asset);
        }

        if (!$this->option('no-database')) {
            try {
                DB::connection()->getPdo();
                $check(true, 'Database connection available');
            } catch (Throwable $e) {
                $check(false, 'Database connection available');
            }
        }

        foreach (config('dhakafin.features', []) as $feature => $enabled) {
            if ($enabled) {
                $this->warn('[REVIEW] Feature enabled: ' . $feature . ' — confirm editorial and security approval.');
            }
        }

        $this->newLine();
        $this->line($failed ? 'Preflight failed: do not deploy.' : 'Preflight passed; browser QA and deployment approval are still required.');

        return $failed ? self::FAILURE : self::SUCCESS;
    }
}

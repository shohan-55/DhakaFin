<?php

namespace App\Providers;

use App\Services\CmsManager;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(CmsManager::class, fn () => new CmsManager());
    }

    public function boot(): void
    {
        $sqlitePath = database_path('database.sqlite');
        if (!file_exists($sqlitePath) && is_dir(dirname($sqlitePath))) {
            @touch($sqlitePath);
        }

        // Automatically clear any stale compiled Blade views if upgraded over an older version
        $versionMarker = storage_path('framework/views/.df_v6_capsule');
        if (!file_exists($versionMarker) && is_dir(dirname($versionMarker))) {
            foreach (glob(storage_path('framework/views/*.php')) ?: [] as $compiled) {
                @unlink($compiled);
            }
            @file_put_contents($versionMarker, (string) time());
        }

        // Clean up any leftover static .html files if extracted on top of an old static HTML folder
        foreach (['index.html', '404.html'] as $legacyFile) {
            if (is_file(base_path($legacyFile))) {
                @unlink(base_path($legacyFile));
            }
        }

        View::composer('*', function ($view) {
            $cms = app(CmsManager::class)->all();
            $view->with('cms', $cms);
        });
    }
}

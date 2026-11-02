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

        View::composer('*', function ($view) {
            $cms = app(CmsManager::class)->all();
            $view->with('cms', $cms);
        });
    }
}

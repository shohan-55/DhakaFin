<?php

use App\Http\Controllers\StaticPageController;
use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\AdminNoticeController;
use App\Http\Controllers\AdminDeadlineController;
use App\Http\Controllers\AdminPostController;
use App\Http\Controllers\AdminLegalSourceController;
use App\Http\Controllers\AdminTaxRateRuleController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    $html = view('home')->render();
    $tag = \App\Support\FeatureManifest::tag();

    // Add service state only to the HTTP response; keep original design view
    // byte-for-byte compatible with the reviewed static source.
    return response(str_ireplace('</head>', $tag . '</head>', $html))
        ->header('Content-Type', 'text/html; charset=UTF-8');
})->name('home');

// Preserve links to the original entry point.
Route::redirect('/index.html', '/', 301);

// Explicitly register each original HTML URL rather than resolving arbitrary view names.
foreach (config('dhakafin.pages', []) as $page) {
    Route::get('/pages/' . $page . '.html', [StaticPageController::class, 'show'])
        ->defaults('page', $page)
        ->name('pages.' . $page);
}


// All editorial writes require an authenticated, explicitly privileged administrator.
Route::prefix('admin')->name('admin.')->group(function (): void {
    Route::get('login', [AdminAuthController::class, 'showLogin'])->name('login');
    Route::post('login', [AdminAuthController::class, 'login'])->middleware('throttle:5,1')->name('login.submit');

    Route::middleware('dhakafin.admin')->group(function (): void {
        Route::view('/', 'admin.dashboard')->name('dashboard');
        Route::post('logout', [AdminAuthController::class, 'logout'])->name('logout');
        Route::resource('notices', AdminNoticeController::class)->except('show');
        Route::resource('deadlines', AdminDeadlineController::class)->except('show');
        Route::resource('posts', AdminPostController::class)->except('show');
        Route::resource('legal-sources', AdminLegalSourceController::class)->except('show');
        Route::resource('tax-rates', AdminTaxRateRuleController::class)->except('show');
    });
});

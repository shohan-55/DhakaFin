<?php

// DhakaFin static-first Laravel routes. Deploy only after staging verification.
use Illuminate\Support\Facades\Route;

Route::view('/', 'legacy.index')->name('home');
Route::redirect('/index.html', '/', 301);

// Keep existing /pages/*.html links working without exposing arbitrary files.
Route::get('/pages/{slug}.html', function (string $slug) {
    abort_unless(preg_match('/\\A[a-z0-9-]+\\z/D', $slug) === 1, 404);
    abort_unless(view()->exists('legacy.pages.'.$slug), 404);
    return view('legacy.pages.'.$slug);
})->name('legacy.page');

\Illuminate\Support\Facades\Route::get('/coming-soon/{feature}', \App\Http\Controllers\ComingSoonController::class)
    ->where('feature', '[a-z0-9-]+')
    ->name('coming-soon');

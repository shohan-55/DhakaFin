<?php

use App\Http\Controllers\StaticPageController;
use Illuminate\Support\Facades\Route;

Route::view('/', 'home')->name('home');

// Preserve links to the original entry point.
Route::redirect('/index.html', '/', 301);

// Explicitly register each original HTML URL rather than resolving arbitrary view names.
foreach (config('dhakafin.pages', []) as $page) {
    Route::get('/pages/' . $page . '.html', [StaticPageController::class, 'show'])
        ->defaults('page', $page)
        ->name('pages.' . $page);
}

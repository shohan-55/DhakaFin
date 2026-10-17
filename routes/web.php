<?php

use Illuminate\Support\Facades\Route;

Route::view('/', 'home')->name('home');

// Preserve existing public .html URLs and avoid routing arbitrary file paths.
$pages = [
    'services', 'client-services', 'consultation', 'terms',
    'accessibility', 'personal-tax', 'corporate-tax',
    'ai-tax-search', 'tools', 'tds-calculator', 'privacy',
];

foreach ($pages as $page) {
    Route::get('/pages/' . $page . '.html', function () use ($page) {
        abort_unless(view()->exists('pages.' . $page), 404);
        return view('pages.' . $page);
    })->name('pages.' . $page);
}

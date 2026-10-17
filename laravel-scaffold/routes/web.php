<?php

// DhakaFin Laravel migration route scaffold.
// Copy to Laravel routes/web.php only after static pages have been imported
// into resources/views/legacy and tested. Do not deploy directly.

use Illuminate\Support\Facades\Route;

Route::view('/', 'legacy.index')->name('home');

// Existing public URLs remain stable while the backend is built.
// Only a single safe slug segment is accepted.
Route::get('/pages/{slug}.html', function (string $slug) {
    abort_unless(preg_match('/\\A[a-z0-9-]+\\z/D', $slug) === 1, 404);
    abort_unless(view()->exists('legacy.pages.'.$slug), 404);

    return view('legacy.pages.'.$slug);
})->name('legacy.page');

// Do not add a catch-all route or enable tax forms until reviewed.

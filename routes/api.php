<?php

use App\Http\Controllers\PublicContentController;
use Illuminate\Support\Facades\Route;

// Public feeds are read-only, rate-limited and disabled until editorial approval.
Route::prefix('v1')->middleware('throttle:60,1')->group(function (): void {
    Route::get('notices', [PublicContentController::class, 'notices'])->name('api.notices.index');
    Route::get('deadlines', [PublicContentController::class, 'deadlines'])->name('api.deadlines.index');
    Route::get('posts', [PublicContentController::class, 'posts'])->name('api.posts.index');
});

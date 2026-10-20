<?php

use App\Http\Controllers\PageController;
use Illuminate\Support\Facades\Route;

Route::get('/', [PageController::class, 'home'])->name('home');
Route::get('/index.html', [PageController::class, 'home']);

Route::get('/pages/{slug}.html', [PageController::class, 'page']);
Route::get('/pages/{slug}', [PageController::class, 'page']);
Route::get('/{slug}.html', [PageController::class, 'page']);
Route::get('/{slug}', [PageController::class, 'page']);

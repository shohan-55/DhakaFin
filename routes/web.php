<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\CalculatorController;
use App\Http\Controllers\EnquiryController;
use App\Http\Controllers\PageController;
use Illuminate\Support\Facades\Route;

// Homepage routes
Route::get('/', [PageController::class, 'home'])->name('home');
Route::get('/index.html', [PageController::class, 'home']);

// Static asset fallback routes
Route::get('/assets/{path}', [PageController::class, 'asset'])->where('path', '.*')->name('assets.serve');
Route::get('/pages/assets/{path}', [PageController::class, 'asset'])->where('path', '.*');
Route::get('/public/assets/{path}', [PageController::class, 'asset'])->where('path', '.*');

// Sitemap & SEO routes
Route::get('/sitemap.xml', [PageController::class, 'sitemapXml'])->name('sitemap.xml');
Route::get('/sitemap.json', [PageController::class, 'sitemapJson'])->name('sitemap.json');

// Enquiry form backend endpoint (Contact, Consultation, Homepage Modal)
Route::post('/enquiry', [EnquiryController::class, 'store'])->name('enquiry.store');

// Live Calculators & AI Tax Search API endpoints
Route::post('/api/calculate', [CalculatorController::class, 'calculate'])->name('api.calculate');
Route::match(['get', 'post'], '/api/tax-search', [CalculatorController::class, 'taxSearch'])->name('api.tax-search');

// Admin Studio No-Code CMS endpoints
Route::prefix('admin-action')->name('admin.')->group(function () {
    Route::post('/settings', [AdminController::class, 'saveSettings'])->name('settings.save');
    Route::post('/homepage', [AdminController::class, 'saveHomepage'])->name('homepage.save');
    Route::post('/footer', [AdminController::class, 'saveFooter'])->name('footer.save');
    Route::post('/page-override', [AdminController::class, 'savePageOverride'])->name('page-override.save');
    Route::post('/blog', [AdminController::class, 'saveArticle'])->name('blog.save');
    Route::post('/blog/delete', [AdminController::class, 'deleteArticle'])->name('blog.delete');
    Route::post('/deadlines', [AdminController::class, 'saveDeadline'])->name('deadlines.save');
    Route::post('/deadlines/delete', [AdminController::class, 'deleteDeadline'])->name('deadlines.delete');
    Route::post('/library', [AdminController::class, 'saveLibrary'])->name('library.save');
    Route::post('/library/delete', [AdminController::class, 'deleteLibrary'])->name('library.delete');
    Route::post('/tax-rates', [AdminController::class, 'saveTaxRates'])->name('tax-rates.save');
    Route::post('/portfolio', [AdminController::class, 'saveTeam'])->name('portfolio.save');
    Route::post('/portfolio/delete', [AdminController::class, 'deleteTeam'])->name('portfolio.delete');
    Route::post('/messages/status', [AdminController::class, 'updateMessageStatus'])->name('messages.status');
    Route::post('/messages/delete', [AdminController::class, 'deleteMessage'])->name('messages.delete');
    Route::post('/auth', [AdminController::class, 'saveAuth'])->name('auth.save');
    Route::post('/workflow', [AdminController::class, 'updateWorkflow'])->name('workflow.save');
    Route::post('/reset-defaults', [AdminController::class, 'resetDefaults'])->name('reset');
});

// Client Workspace & Auth endpoints
Route::prefix('portal-action')->name('portal.')->group(function () {
    Route::post('/documents', [AdminController::class, 'addDocument'])->name('documents.add');
    Route::post('/documents/delete', [AdminController::class, 'deleteDocument'])->name('documents.delete');
    Route::post('/submissions', [AdminController::class, 'addSubmission'])->name('submissions.add');
    Route::post('/login', [AdminController::class, 'login'])->name('login');
    Route::post('/register', [AdminController::class, 'register'])->name('register');
    Route::post('/logout', [AdminController::class, 'logout'])->name('logout');
});

// Convenience redirect for /admin -> /pages/admin-dashboard
Route::get('/admin', fn () => redirect()->route('pages.show', 'admin-dashboard'))->name('admin.home');

// Explicit 404 preview route
Route::get('/404.html', fn () => response()->view('errors.404', [], 404))->name('errors.404');

// All pages (supports /pages/{slug}, /pages/{slug}.html, /{slug}, and /{slug}.html)
Route::get('/pages/{slug}.html', [PageController::class, 'page'])->where('slug', '[A-Za-z0-9\-_]+');
Route::get('/pages/{slug}', [PageController::class, 'page'])->where('slug', '[A-Za-z0-9\-_]+')->name('pages.show');
Route::get('/{slug}.html', [PageController::class, 'page'])->where('slug', '[A-Za-z0-9\-_]+');
Route::get('/{slug}', [PageController::class, 'page'])->where('slug', '[A-Za-z0-9\-_]+')->name('pages.direct');

// Fallback 404 handler
Route::fallback(fn () => response()->view('errors.404', [], 404));

<?php

use Illuminate\Support\Facades\Route;

Route::view('/', 'home')->name('home');

// Original .html URLs remain available; no arbitrary view paths accepted.
$pages = [
    'services', 'client-services', 'consultation', 'terms',
    'accessibility', 'personal-tax', 'corporate-tax',
    'ai-tax-search', 'tools', 'tds-calculator', 'privacy',
];
$featurePages = [
    'ai-tax-search' => 'law_search',
    'tds-calculator' => 'tds_calculator',
    'client-services' => 'client_portal',
];

foreach ($pages as $page) {
    Route::get('/pages/' . $page . '.html', function () use ($page, $featurePages) {
        $flag = $featurePages[$page] ?? null;
        if ($flag !== null && !config('dhakafin.features.' . $flag, false)) {
            return response()->view('coming-soon');
        }
        abort_unless(view()->exists('pages.' . $page), 404);
        return view('pages.' . $page);
    })->name('pages.' . $page);
}

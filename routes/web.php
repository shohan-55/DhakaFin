<?php

use Illuminate\Support\Facades\Route;

// Preserve existing URLs: relative links and browser feature notices rely on them.
Route::view('/', 'home')->name('home');
Route::view('/index.html', 'home')->name('home.legacy');

Route::view('/pages/about.html', 'pages.about')->name('pages.about');
Route::view('/pages/accessibility.html', 'pages.accessibility')->name('pages.accessibility');
Route::view('/pages/accounting.html', 'pages.accounting')->name('pages.accounting');
Route::view('/pages/ai-tax-search.html', 'pages.ai-tax-search')->name('pages.ai-tax-search');
Route::view('/pages/audit-assurance.html', 'pages.audit-assurance')->name('pages.audit-assurance');
Route::view('/pages/business-advisory.html', 'pages.business-advisory')->name('pages.business-advisory');
Route::view('/pages/calculator.html', 'pages.calculator')->name('pages.calculator');
Route::view('/pages/circulars.html', 'pages.circulars')->name('pages.circulars');
Route::view('/pages/client-services.html', 'pages.client-services')->name('pages.client-services');
Route::view('/pages/consultation.html', 'pages.consultation')->name('pages.consultation');
Route::view('/pages/contact.html', 'pages.contact')->name('pages.contact');
Route::view('/pages/corporate-companies.html', 'pages.corporate-companies')->name('pages.corporate-companies');
Route::view('/pages/corporate-tax-service.html', 'pages.corporate-tax-service')->name('pages.corporate-tax-service');
Route::view('/pages/corporate-tax.html', 'pages.corporate-tax')->name('pages.corporate-tax');
Route::view('/pages/cost-efficiency.html', 'pages.cost-efficiency')->name('pages.cost-efficiency');
Route::view('/pages/explore.html', 'pages.explore')->name('pages.explore');
Route::view('/pages/financial-reporting.html', 'pages.financial-reporting')->name('pages.financial-reporting');
Route::view('/pages/individual-taxpayers.html', 'pages.individual-taxpayers')->name('pages.individual-taxpayers');
Route::view('/pages/insight-detail.html', 'pages.insight-detail')->name('pages.insight-detail');
Route::view('/pages/insights.html', 'pages.insights')->name('pages.insights');
Route::view('/pages/internal-audit.html', 'pages.internal-audit')->name('pages.internal-audit');
Route::view('/pages/mushak-6-3.html', 'pages.mushak-6-3')->name('pages.mushak-6-3');
Route::view('/pages/ngos-nonprofits.html', 'pages.ngos-nonprofits')->name('pages.ngos-nonprofits');
Route::view('/pages/payroll.html', 'pages.payroll')->name('pages.payroll');
Route::view('/pages/personal-tax-service.html', 'pages.personal-tax-service')->name('pages.personal-tax-service');
Route::view('/pages/personal-tax.html', 'pages.personal-tax')->name('pages.personal-tax');
Route::view('/pages/privacy.html', 'pages.privacy')->name('pages.privacy');
Route::view('/pages/services.html', 'pages.services')->name('pages.services');
Route::view('/pages/startups-smes.html', 'pages.startups-smes')->name('pages.startups-smes');
Route::view('/pages/tax-act-section.html', 'pages.tax-act-section')->name('pages.tax-act-section');
Route::view('/pages/tax-act.html', 'pages.tax-act')->name('pages.tax-act');
Route::view('/pages/tax-deadlines.html', 'pages.tax-deadlines')->name('pages.tax-deadlines');
Route::view('/pages/tax-library.html', 'pages.tax-library')->name('pages.tax-library');
Route::view('/pages/tax-rates.html', 'pages.tax-rates')->name('pages.tax-rates');
Route::view('/pages/tax-research.html', 'pages.tax-research')->name('pages.tax-research');
Route::view('/pages/tax-search-results.html', 'pages.tax-search-results')->name('pages.tax-search-results');
Route::view('/pages/tds-calculator.html', 'pages.tds-calculator')->name('pages.tds-calculator');
Route::view('/pages/team-profile.html', 'pages.team-profile')->name('pages.team-profile');
Route::view('/pages/team.html', 'pages.team')->name('pages.team');
Route::view('/pages/terms.html', 'pages.terms')->name('pages.terms');
Route::view('/pages/tools.html', 'pages.tools')->name('pages.tools');
Route::view('/pages/vat-calculator.html', 'pages.vat-calculator')->name('pages.vat-calculator');
Route::view('/pages/vat-compliance.html', 'pages.vat-compliance')->name('pages.vat-compliance');
Route::view('/pages/vat-return.html', 'pages.vat-return')->name('pages.vat-return');
Route::view('/pages/vcfo.html', 'pages.vcfo')->name('pages.vcfo');
Route::view('/pages/vds-calculator.html', 'pages.vds-calculator')->name('pages.vds-calculator');

Route::get('/404.html', fn () => response()->view('errors.404', [], 404));

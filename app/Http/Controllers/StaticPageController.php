<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;
use Illuminate\Http\Response;

final class StaticPageController
{
    /**
     * Preserve the original public HTML exactly when a service is unreleased.
     * A tiny, additive JavaScript adapter is loaded only for approved services.
     */
    public function show(string $page): View|Response
    {
        abort_unless(in_array($page, config('dhakafin.pages', []), true), 404);

        $feature = config('dhakafin.feature_pages.' . $page);

        if (is_string($feature) && !config('dhakafin.features.' . $feature, false)) {
            return response()->view('coming-soon');
        }

        abort_unless(view()->exists('pages.' . $page), 404);

        $integrations = [
            'tds-calculator' => 'tds_calculator',
            'vds-calculator' => 'vds_calculator',
            'vat-calculator' => 'vat_calculator',
            'ai-tax-search' => 'law_search',
            'circulars' => 'notices',
            'tax-deadlines' => 'deadlines',
            'insights' => 'blog',
        ];

        $integration = $integrations[$page] ?? null;
        if (!is_string($integration) || !config('dhakafin.features.' . $integration, false)) {
            return view('pages.' . $page);
        }

        $html = view('pages.' . $page)->render();
        $script = '<script src="/assets/dhakafin-integration.js" defer></script>';
        $html = preg_replace('~</body\s*>~i', $script . '</body>', $html, 1, $replacements);

        // Fail closed if the underlying view has no valid closing body element.
        if ($html === null || $replacements !== 1) {
            return response('The requested service is unavailable.', 503);
        }

        return response($html)->header('Content-Type', 'text/html; charset=UTF-8');
    }
}

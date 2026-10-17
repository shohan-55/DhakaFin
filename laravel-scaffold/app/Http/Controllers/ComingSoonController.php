<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;

final class ComingSoonController
{
    public function __invoke(Request $request, string $feature): Response
    {
        $features = [
            'tds-calculator' => 'tds_calculator',
            'vds-calculator' => 'vds_calculator',
            'vat-calculator' => 'vat_calculator',
            'tax-deadlines' => 'tax_deadlines',
            'law-search' => 'law_search',
            'client-workspace' => 'client_workspace',
            'consultation-submission' => 'consultation_submission',
        ];
        abort_unless(array_key_exists($feature, $features), 404);

        // This endpoint is intentionally informational only.
        // A feature flag must never imply the feature is implemented.
        return response()
            ->view('system.coming-soon', ['feature' => $feature], 503)
            ->header('Cache-Control', 'no-store, private')
            ->header('X-Robots-Tag', 'noindex, nofollow')
            ->header('Retry-After', '86400');
    }
}

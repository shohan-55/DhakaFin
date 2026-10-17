<?php

return [
    'features' => [
        'tds_calculator' => env('DHAKAFIN_TDS_ENABLED', false),
        'vat_calculator' => env('DHAKAFIN_VAT_ENABLED', false),
        'law_search' => env('DHAKAFIN_LAW_SEARCH_ENABLED', false),
        'client_portal' => env('DHAKAFIN_CLIENT_PORTAL_ENABLED', false),
        'notices' => env('DHAKAFIN_NOTICES_ENABLED', false),
        'deadlines' => env('DHAKAFIN_DEADLINES_ENABLED', false),
    ],
];

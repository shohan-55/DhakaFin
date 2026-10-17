<?php

// DhakaFin feature switches: default OFF until backend and legal rules are verified.
// Place in Laravel config/dhakafin.php during staging integration.
// Changing a flag alone does not activate a UI or implement a feature.
return [
    'tds_calculator' => (bool) env('DHAKAFIN_TDS_ENABLED', false),
    'vds_calculator' => (bool) env('DHAKAFIN_VDS_ENABLED', false),
    'vat_calculator' => (bool) env('DHAKAFIN_VAT_ENABLED', false),
    'tax_deadlines' => (bool) env('DHAKAFIN_DEADLINES_ENABLED', false),
    'law_search' => (bool) env('DHAKAFIN_LAW_SEARCH_ENABLED', false),
    'client_workspace' => (bool) env('DHAKAFIN_CLIENT_WORKSPACE_ENABLED', false),
    'consultation_submission' => (bool) env('DHAKAFIN_CONSULTATION_SUBMISSION_ENABLED', false),
];

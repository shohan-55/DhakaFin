<?php
/**
 * Synthetic browser-test fixtures; never used to populate production.
 */
declare(strict_types=1);

require dirname(__DIR__, 2) . '/vendor/autoload.php';
$app = require dirname(__DIR__, 2) . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

if (!app()->environment('testing') || getenv('CI') !== 'true') {
    throw new RuntimeException('Synthetic fixtures permitted only in CI testing.');
}

$today = today(config('app.timezone'));

foreach ([
    ['code' => 'CI-TDS-5PCT', 'tax_type' => 'tds', 'category' => 'Synthetic services', 'bps' => 500],
    ['code' => 'CI-VDS-5PCT', 'tax_type' => 'vds', 'category' => 'Synthetic services', 'bps' => 500],
    ['code' => 'CI-VAT-15PCT', 'tax_type' => 'vat', 'category' => 'Synthetic VAT category', 'bps' => 1500],
] as $item) {
    \App\Models\TaxRateRule::updateOrCreate(['code' => $item['code']], [
        'tax_type' => $item['tax_type'],
        'category' => $item['category'],
        'description' => 'Synthetic browser fixture, not a real statutory tax rate.',
        'rate_basis_points' => $item['bps'],
        'effective_from' => $today->copy()->subDays(30)->toDateString(),
        'effective_until' => $today->copy()->addDays(30)->toDateString(),
        'reference_code' => 'CI-ONLY',
        'provision_reference' => 'Synthetic provision — no legal authority',
        'source_url' => 'https://nbr.gov.bd',
        'status' => 'verified',
        'verified_at' => now(),
    ]);
}

\App\Models\LegalSource::updateOrCreate(
    ['slug' => 'ci-illustrative-source'],
    [
        'title' => 'CI illustrative legal reference',
        'source_type' => 'act',
        'reference_code' => 'CI-ONLY',
        'provision_reference' => 'Section CI-TEST',
        'page_reference' => 'p. 1 (synthetic)',
        'summary' => 'Testing reference only. Not legal advice.',
        'source_url' => 'https://nbr.gov.bd',
        'document_date' => $today->toDateString(),
        'status' => 'verified',
        'verified_at' => now(),
    ]
);

echo "Seeded synthetic CI-only fixtures.\n";

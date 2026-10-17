<?php

namespace Tests\Feature;

use App\Models\TaxRateRule;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FrontendIntegrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_homepage_manifest_contains_only_approved_services(): void
    {
        config()->set('dhakafin.features.tds_calculator', true);
        config()->set('dhakafin.features.vat_calculator', false);

        $this->get('/')->assertOk()
            ->assertSee('"tds-calculator.html":true', false)
            ->assertDontSee('"vat-calculator.html":true', false);
    }

    public function test_approved_calculator_receives_adapter_without_mutating_blade_source(): void
    {
        config()->set('dhakafin.features.tds_calculator', true);

        $this->get('/pages/tds-calculator.html')
            ->assertOk()
            ->assertSee('/assets/dhakafin-integration.js')
            ->assertSee('"tds-calculator.html":true', false);

        $this->assertStringNotContainsString(
            '/assets/dhakafin-integration.js',
            view('pages.tds-calculator')->render()
        );
    }

    public function test_disabled_services_do_not_load_api_adapter(): void
    {
        config()->set('dhakafin.features.tds_calculator', false);

        $this->get('/pages/tds-calculator.html')
            ->assertOk()
            ->assertSee('Coming Soon')
            ->assertDontSee('/assets/dhakafin-integration.js');
    }

    public function test_vat_inclusive_estimator_uses_only_verified_effective_rule(): void
    {
        config()->set('dhakafin.features.vat_calculator', true);
        config()->set('dhakafin.features.tax_rates', true);

        $rule = TaxRateRule::create([
            'code' => 'SYNTHETIC-VAT-TEST',
            'tax_type' => 'vat',
            'category' => 'Synthetic test category',
            'description' => 'Tests only; not a Bangladesh statutory rate.',
            'rate_basis_points' => 1500,
            'effective_from' => today('Asia/Dhaka')->subDay()->format('Y-m-d'),
            'effective_until' => today('Asia/Dhaka')->addDay()->format('Y-m-d'),
            'reference_code' => 'UNIT-TEST-ONLY',
            'provision_reference' => 'Synthetic demonstration',
            'source_url' => 'https://nbr.gov.bd',
            'status' => 'verified',
            'verified_at' => now(),
        ]);

        $this->postJson('/api/v1/tax/estimate', [
            'tax_type' => 'vat',
            'rate_rule_id' => $rule->id,
            'amount_bdt' => '1150.00',
            'amount_mode' => 'inclusive',
        ])->assertOk()
            ->assertJsonPath('calculation.estimated_amount_bdt', '150.00')
            ->assertJsonPath('calculation.net_amount_bdt', '1000.00')
            ->assertJsonPath('calculation.amount_mode', 'inclusive');

        $this->postJson('/api/v1/tax/estimate', [
            'tax_type' => 'tds',
            'rate_rule_id' => $rule->id,
            'amount_bdt' => '1150.00',
            'amount_mode' => 'inclusive',
        ])->assertStatus(503);
    }

    public function test_inclusive_mode_is_rejected_for_tds_even_if_feature_is_enabled(): void
    {
        config()->set('dhakafin.features.tds_calculator', true);
        $this->postJson('/api/v1/tax/estimate', [
            'tax_type' => 'tds',
            'rate_rule_id' => 1,
            'amount_bdt' => '1150.00',
            'amount_mode' => 'inclusive',
        ])->assertUnprocessable();
    }

    public function test_javascript_adapter_file_exists(): void
    {
        $this->assertFileExists(public_path('assets/dhakafin-integration.js'));
    }
}

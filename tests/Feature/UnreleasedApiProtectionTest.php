<?php

namespace Tests\Feature;

use Tests\TestCase;

class UnreleasedApiProtectionTest extends TestCase
{
    public function test_all_unreleased_public_api_endpoints_fail_closed(): void
    {
        foreach (config('dhakafin.features', []) as $name => $unused) {
            config()->set('dhakafin.features.' . $name, false);
        }

        foreach ([
            '/api/v1/notices',
            '/api/v1/deadlines',
            '/api/v1/posts',
            '/api/v1/law-search?q=income+tax',
            '/api/v1/tax/rates',
        ] as $uri) {
            $this->getJson($uri)
                ->assertStatus(503)
                ->assertExactJson(['status' => 'coming_soon']);
        }

        $this->postJson('/api/v1/tax/estimate', [
            'tax_type' => 'tds',
            'rate_rule_id' => 1,
            'amount_bdt' => '1000.00',
        ])->assertStatus(503)->assertExactJson(['status' => 'coming_soon']);
    }

    public function test_disabling_one_feature_does_not_disable_other_public_routes(): void
    {
        config()->set('dhakafin.features.law_search', false);

        $this->get('/')->assertOk();
        $this->get('/pages/about.html')->assertOk();
        $this->getJson('/api/v1/law-search?q=income+tax')->assertStatus(503);
    }

    public function test_unknown_tax_type_never_triggers_calculation(): void
    {
        config()->set('dhakafin.features.tds_calculator', true);
        config()->set('dhakafin.features.vds_calculator', true);
        config()->set('dhakafin.features.vat_calculator', true);

        $this->postJson('/api/v1/tax/estimate', [
            'tax_type' => 'unknown',
            'rate_rule_id' => 1,
            'amount_bdt' => '1000.00',
        ])->assertStatus(503)->assertExactJson(['status' => 'coming_soon']);
    }
}

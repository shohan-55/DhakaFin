<?php

namespace Tests\Feature;

use Tests\TestCase;

class FeatureFlagsTest extends TestCase
{
    public function test_unreleased_features_show_coming_soon_by_default(): void
    {
        foreach (['ai-tax-search', 'tds-calculator', 'client-services'] as $page) {
            $this->get('/pages/' . $page . '.html')
                ->assertOk()
                ->assertSee('Coming Soon');
        }
    }

    public function test_approved_feature_can_be_enabled_individually(): void
    {
        config()->set('dhakafin.features.tds_calculator', true);

        $this->get('/pages/tds-calculator.html')
            ->assertOk()
            ->assertDontSee('This service is being prepared');
    }
}

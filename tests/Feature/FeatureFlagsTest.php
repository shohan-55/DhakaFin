<?php

namespace Tests\Feature;

use Tests\TestCase;

class FeatureFlagsTest extends TestCase
{
    public function test_unreleased_tools_are_disabled_by_default(): void
    {
        $gated = config('dhakafin.feature_pages');
        $this->assertNotEmpty($gated);

        foreach ($gated as $page => $feature) {
            config()->set('dhakafin.features.' . $feature, false);

            $this->get('/pages/' . $page . '.html')
                ->assertOk()
                ->assertSee('Coming Soon');
        }
    }

    public function test_approved_tool_can_be_enabled_individually(): void
    {
        config()->set('dhakafin.features.tds_calculator', true);

        $this->get('/pages/tds-calculator.html')
            ->assertOk()
            ->assertDontSee('This service is being prepared');
    }

    public function test_public_marketing_pages_remain_accessible(): void
    {
        foreach (['about', 'contact', 'services', 'tools'] as $page) {
            $this->get('/pages/' . $page . '.html')
                ->assertOk()
                ->assertDontSee('This service is being prepared');
        }
    }
}

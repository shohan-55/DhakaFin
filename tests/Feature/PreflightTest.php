<?php

namespace Tests\Feature;

use Tests\TestCase;

class PreflightTest extends TestCase
{
    public function test_preflight_succeeds_for_complete_staging_tree(): void
    {
        config()->set('app.key', 'base64:AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA=');
        config()->set('app.env', 'testing');
        config()->set('app.debug', false);

        $this->artisan('dhakafin:preflight', ['--no-database' => true])
            ->assertExitCode(0);
    }

    public function test_preflight_fails_on_missing_application_key(): void
    {
        config()->set('app.key', '');

        $this->artisan('dhakafin:preflight', ['--no-database' => true])
            ->assertExitCode(1);
    }

    public function test_preflight_fails_on_production_debug_mode(): void
    {
        config()->set('app.key', 'base64:AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA=');
        config()->set('app.env', 'production');
        config()->set('app.debug', true);

        $this->artisan('dhakafin:preflight', ['--no-database' => true])
            ->assertExitCode(1);
    }
}

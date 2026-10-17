<?php

namespace Tests\Feature;

use Tests\TestCase;

final class ComingSoonTest extends TestCase
{
    public function test_known_pending_feature_is_unavailable_without_processing(): void
    {
        $this->get('/coming-soon/tds-calculator')
            ->assertStatus(503)
            ->assertSee('Coming Soon')
            ->assertHeader('X-Robots-Tag', 'noindex, nofollow')
            ->assertHeader('Cache-Control', 'no-store, private');
    }

    public function test_unknown_feature_returns_404(): void
    {
        $this->get('/coming-soon/unknown-feature')->assertNotFound();
    }
}

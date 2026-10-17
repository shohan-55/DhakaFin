<?php

namespace Tests\Feature;

use Tests\TestCase;

class LegacyFrontendRoutesTest extends TestCase
{
    public function test_homepage_renders_imported_markup(): void
    {
        $this->get('/')->assertOk()->assertSee('DhakaFin', false);
    }

    public function test_existing_index_html_redirects_home(): void
    {
        $this->get('/index.html')->assertRedirect('/');
    }

    public function test_tools_page_keeps_original_url(): void
    {
        $this->get('/pages/tools.html')->assertOk()->assertSee('DhakaFin', false);
    }

    public function test_missing_page_is_not_found(): void
    {
        $this->get('/pages/this-page-does-not-exist.html')->assertNotFound();
    }

    public function test_invalid_slug_is_not_found(): void
    {
        $this->get('/pages/INVALID.html')->assertNotFound();
    }
}

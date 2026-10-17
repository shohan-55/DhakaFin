<?php

namespace Tests\Feature;

use Tests\TestCase;

class PublicPagesTest extends TestCase
{
    public function test_homepage_and_original_index_url_are_available(): void
    {
        $this->get('/')->assertOk();
        $this->get('/index.html')->assertRedirect('/');
    }

    public function test_every_original_page_url_is_available(): void
    {
        $pages = config('dhakafin.pages');

        $this->assertCount(46, $pages);

        foreach ($pages as $page) {
            $this->assertTrue(view()->exists('pages.' . $page), $page . ' Blade view missing');
            $this->get('/pages/' . $page . '.html')->assertOk();
        }
    }

    public function test_every_inner_page_can_be_compiled(): void
    {
        foreach (config('dhakafin.pages') as $page) {
            $html = view('pages.' . $page)->render();
            $this->assertStringContainsString('<html', strtolower($html), $page);
        }
    }

    public function test_unknown_page_is_not_found(): void
    {
        $this->get('/pages/not-a-real-page.html')->assertNotFound()->assertSee('This page has moved');
        $this->get('/pages/nonexistent-file.html')->assertNotFound();
    }

    public function test_original_assets_are_present_in_laravel_public(): void
    {
        foreach (['site.css', 'site.js', 'grouped-nav.js', 'motion.js', 'production.js'] as $asset) {
            $this->assertFileExists(public_path('assets/' . $asset));
        }
    }
}

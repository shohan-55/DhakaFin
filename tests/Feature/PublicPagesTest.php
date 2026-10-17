<?php

namespace Tests\Feature;

use Tests\TestCase;

class PublicPagesTest extends TestCase
{
    public function test_homepage_is_available(): void
    {
        $this->get('/')->assertOk();
    }

    public function test_original_page_urls_are_preserved(): void
    {
        $pages = [
            'services', 'client-services', 'consultation', 'terms',
            'accessibility', 'personal-tax', 'corporate-tax',
            'ai-tax-search', 'tools', 'tds-calculator', 'privacy',
            'vat-calculator', 'mushak-6-3', 'tax-act', 'circulars',
            'tax-deadlines', 'vcfo', 'about', 'contact',
        ];

        foreach ($pages as $page) {
            $this->get('/pages/' . $page . '.html')->assertOk();
        }
    }

    public function test_unknown_page_is_not_accepted(): void
    {
        $this->get('/pages/not-a-real-page.html')->assertNotFound();
    }
}

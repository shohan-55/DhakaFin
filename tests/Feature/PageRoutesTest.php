<?php

namespace Tests\Feature;

use Tests\TestCase;

class PageRoutesTest extends TestCase
{
    public function test_homepage_loads_successfully(): void
    {
        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertSee('DhakaFin');
    }

    public function test_all_71_inner_and_portal_pages_load_successfully(): void
    {
        $slugs = array_keys(config('dhakafin.pages', []));

        foreach ($slugs as $slug) {
            $this->get("/pages/{$slug}")->assertStatus(200);
            $this->get("/pages/{$slug}.html")->assertStatus(200);
        }
    }

    public function test_calculator_api_computes_personal_and_vat_taxes(): void
    {
        $res = $this->postJson('/api/calculate', [
            'tool' => 'personal-tax',
            'income' => 1200000,
            'other' => 100000,
            'investment' => 200000,
            'status' => 'General',
        ]);
        $res->assertStatus(200)->assertJson(['status' => 'ok', 'stamp' => 'C O M P U T E D']);
    }

    public function test_unknown_page_returns_custom_404(): void
    {
        $response = $this->get('/non-existent-page-xyz');
        $response->assertStatus(404);
        $response->assertSee('PAGE NOT FOUND');
    }
}

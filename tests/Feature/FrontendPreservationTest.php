<?php

namespace Tests\Feature;

use DOMDocument;
use Illuminate\Support\Facades\DB;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class FrontendPreservationTest extends TestCase
{
    private static function manifest(): array
    {
        return json_decode(file_get_contents(__DIR__.'/../Fixtures/frontend-manifest.json'), true, flags: JSON_THROW_ON_ERROR);
    }

    public static function pages(): iterable
    {
        foreach (self::manifest()['pages'] as $url => $hash) {
            yield $url => [$url, $hash];
        }
    }

    #[DataProvider('pages')]
    public function test_pages_render_the_original_html_without_using_a_database(string $url, string $hash): void
    {
        DB::shouldReceive('connection')->never();
        $response = $this->get($url)->assertOk();
        $this->assertSame($hash, hash('sha256', $response->getContent()), $url.' changed its original HTML');
        $this->assertStringStartsWith('text/html', $response->headers->get('Content-Type'));
        $this->head($url)->assertOk();
    }

    public function test_all_public_assets_are_unchanged_and_no_static_html_bypasses_laravel(): void
    {
        foreach (self::manifest()['assets'] as $path => $hash) {
            $this->assertFileExists(public_path($path));
            $this->assertSame($hash, hash_file('sha256', public_path($path)), $path);
        }
        $this->assertFileDoesNotExist(public_path('index.html'));
        $this->assertDirectoryDoesNotExist(public_path('pages'));
    }

    public function test_original_internal_links_and_resources_resolve(): void
    {
        $knownPages = array_merge(array_keys(self::manifest()['pages']), ['/index.html', '/404.html']);
        foreach (array_keys(self::manifest()['pages']) as $url) {
            $dom = new DOMDocument;
            @$dom->loadHTML($this->get($url)->getContent());
            foreach (['a' => 'href', 'link' => 'href', 'script' => 'src', 'img' => 'src'] as $tag => $attribute) {
                foreach ($dom->getElementsByTagName($tag) as $element) {
                    $value = $element->getAttribute($attribute);
                    if ($value === '' || str_starts_with($value, '#') || str_starts_with($value, '//') || parse_url($value, PHP_URL_SCHEME)) {
                        continue;
                    }
                    $path = parse_url($value, PHP_URL_PATH);
                    if (! $path) {
                        continue;
                    }
                    $path = str_starts_with($path, '/') ? $path : ($url === '/' ? '/' : dirname($url).'/').$path;
                    $segments = [];
                    foreach (explode('/', $path) as $part) {
                        if ($part === '..') {
                            array_pop($segments);
                        } elseif ($part !== '' && $part !== '.') {
                            $segments[] = $part;
                        }
                    }
                    $resolved = '/'.implode('/', $segments);
                    $this->assertTrue(in_array($resolved, $knownPages, true) || is_file(public_path(ltrim($resolved, '/'))), $url.' links to missing '.$value);
                }
            }
        }
    }

    public function test_home_alias_and_frontend_placeholders_are_preserved(): void
    {
        $this->assertSame($this->get('/')->getContent(), $this->get('/index.html')->getContent());
        $this->get('/pages/contact.html')->assertOk()->assertSee('data-email-enquiry="contact"', false);
        $this->get('/pages/consultation.html')->assertOk()->assertSee('data-email-enquiry="consultation"', false);
        $this->assertStringContainsString('This digital service is coming soon.', file_get_contents(public_path('assets/production.js')));
    }

    public function test_unknown_urls_return_the_existing_error_design_with_working_links(): void
    {
        foreach (['/404.html', '/unknown-page', '/pages/unknown.html', '/deep/missing/page'] as $url) {
            $this->get($url)->assertNotFound()->assertSee('Page Not Found | DhakaFin', false)
                ->assertSee('href="/assets/production.css"', false)->assertSee('href="/index.html"', false);
        }
    }

    public function test_health_route_and_unsupported_write_requests(): void
    {
        $this->get('/up')->assertOk();
        $this->post('/pages/contact.html')->assertStatus(405);
        $this->get('/pages/contact.html?name=%3Cscript%3Ealert(1)%3C/script%3E')->assertOk()->assertDontSee('<script>alert(1)</script>', false);
    }
}

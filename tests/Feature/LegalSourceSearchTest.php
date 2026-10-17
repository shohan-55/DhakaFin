<?php

namespace Tests\Feature;

use App\Models\LegalSource;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LegalSourceSearchTest extends TestCase
{
    use RefreshDatabase;

    private function draft(): array
    {
        return [
            'title' => 'Illustrative Tax Act Reference',
            'slug' => 'illustrative-tax-act-reference',
            'source_type' => 'act',
            'reference_code' => 'Example reference only',
            'provision_reference' => '',
            'page_reference' => '',
            'summary' => 'Sample index entry, not legal advice.',
            'source_url' => 'https://nbr.gov.bd',
            'document_date' => '',
            'status' => 'draft',
        ];
    }

    private function admin(): User
    {
        $admin = User::factory()->create();
        $admin->is_admin = true;
        $admin->save();
        return $admin;
    }

    public function test_unprivileged_users_cannot_edit_source_index(): void
    {
        $this->get('/admin/legal-sources')->assertRedirect(route('admin.login'));
        $this->actingAs(User::factory()->create())
            ->get('/admin/legal-sources')->assertForbidden();
    }

    public function test_verification_requires_a_precise_page_or_section_locator(): void
    {
        $this->actingAs($this->admin());
        $this->post(route('admin.legal-sources.store'), $this->draft())
            ->assertRedirect(route('admin.legal-sources.index'));

        $source = LegalSource::firstOrFail();
        $this->assertNull($source->verified_at);

        $this->put(route('admin.legal-sources.update', $source), [
            ...$this->draft(), 'status' => 'verified',
        ])->assertSessionHasErrors('provision_reference');

        $this->put(route('admin.legal-sources.update', $source), [
            ...$this->draft(),
            'provision_reference' => 'Section 1 (illustrative)',
            'status' => 'verified',
        ])->assertRedirect(route('admin.legal-sources.index'));

        $this->assertSame('verified', $source->fresh()->status);
        $this->assertNotNull($source->fresh()->verified_at);
    }

    public function test_law_search_stays_disabled_until_explicit_release(): void
    {
        config()->set('dhakafin.features.law_search', false);
        $this->getJson('/api/v1/law-search?q=Income')
            ->assertStatus(503)->assertJsonPath('status', 'coming_soon');
    }

    public function test_verified_keyword_search_returns_original_citations_only(): void
    {
        config()->set('dhakafin.features.law_search', true);

        LegalSource::create($this->draft());
        LegalSource::create([
            ...$this->draft(),
            'slug' => 'verified-reference',
            'provision_reference' => 'Section 1 (illustrative)',
            'status' => 'verified',
            'verified_at' => now(),
        ]);

        $this->getJson('/api/v1/law-search?q=Illustrative')
            ->assertOk()->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.slug', 'verified-reference')
            ->assertJsonPath('data.0.source_url', 'https://nbr.gov.bd');

        $this->getJson('/api/v1/law-search?q=ab')->assertUnprocessable();
    }
}

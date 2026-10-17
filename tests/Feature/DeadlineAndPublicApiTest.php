<?php

namespace Tests\Feature;

use App\Models\Deadline;
use App\Models\Notice;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DeadlineAndPublicApiTest extends TestCase
{
    use RefreshDatabase;

    private function admin(): User
    {
        $user = User::factory()->create();
        $user->is_admin = true;
        $user->save();

        return $user;
    }

    private function data(): array
    {
        return [
            'title' => 'VAT return',
            'slug' => 'vat-return-next-month',
            'description' => 'Illustrative deadline pending official editorial verification.',
            'category' => 'VAT',
            'due_on' => today('Asia/Dhaka')->addDays(10)->format('Y-m-d'),
            'source_url' => 'https://nbr.gov.bd',
            'status' => 'draft',
        ];
    }

    public function test_deadline_management_is_private(): void
    {
        $this->get('/admin/deadlines')->assertRedirect(route('admin.login'));
        $this->actingAs(User::factory()->create())
            ->get('/admin/deadlines')->assertForbidden();
    }

    public function test_admin_can_create_edit_and_delete_deadline(): void
    {
        $this->actingAs($this->admin());

        $this->post(route('admin.deadlines.store'), $this->data())
            ->assertRedirect(route('admin.deadlines.index'));

        $deadline = Deadline::firstOrFail();
        $this->assertSame('draft', $deadline->status);

        $this->put(route('admin.deadlines.update', $deadline), [
            ...$this->data(),
            'status' => 'published',
        ])->assertRedirect(route('admin.deadlines.index'));

        $this->assertNotNull($deadline->fresh()->published_at);
        $this->delete(route('admin.deadlines.destroy', $deadline))
            ->assertRedirect(route('admin.deadlines.index'));
        $this->assertDatabaseMissing('deadlines', ['id' => $deadline->id]);
    }

    public function test_published_deadline_requires_legal_source_url(): void
    {
        $this->actingAs($this->admin());

        $this->post(route('admin.deadlines.store'), [
            ...$this->data(),
            'source_url' => '',
            'status' => 'published',
        ])->assertSessionHasErrors('source_url');

        $this->assertDatabaseCount('deadlines', 0);
    }

    public function test_public_feeds_are_disabled_by_default(): void
    {
        config()->set('dhakafin.features.notices', false);
        config()->set('dhakafin.features.deadlines', false);

        $this->getJson('/api/v1/notices')->assertStatus(503)->assertJsonPath('status', 'coming_soon');
        $this->getJson('/api/v1/deadlines')->assertStatus(503)->assertJsonPath('status', 'coming_soon');
    }

    public function test_enabled_public_feeds_exclude_drafts_and_expired_deadlines(): void
    {
        config()->set('dhakafin.features.notices', true);
        config()->set('dhakafin.features.deadlines', true);

        Notice::create([
            'title' => 'Draft', 'slug' => 'draft-notice',
            'body' => 'unpublished', 'status' => 'draft',
        ]);
        Notice::create([
            'title' => 'Published', 'slug' => 'published-notice',
            'body' => 'Reviewed', 'status' => 'published', 'published_at' => now(),
        ]);
        $future = $this->data();
        Deadline::create([
            ...$future,
            'status' => 'published', 'published_at' => now(),
        ]);
        Deadline::create([
            ...$future,
            'slug' => 'expired-deadline', 'due_on' => today('Asia/Dhaka')->subDays(1),
            'status' => 'published', 'published_at' => now(),
        ]);

        $this->getJson('/api/v1/notices')
            ->assertOk()->assertJsonCount(1, 'data')->assertJsonPath('data.0.slug', 'published-notice');

        $this->getJson('/api/v1/deadlines')
            ->assertOk()->assertJsonCount(1, 'data')->assertJsonPath('data.0.slug', $future['slug']);
    }
}

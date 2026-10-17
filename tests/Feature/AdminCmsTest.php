<?php

namespace Tests\Feature;

use App\Models\Notice;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminCmsTest extends TestCase
{
    use RefreshDatabase;

    private function administrator(): User
    {
        $admin = User::factory()->create();
        $admin->is_admin = true;
        $admin->save();

        return $admin;
    }

    public function test_guests_are_redirected_from_the_private_workspace(): void
    {
        $this->get('/admin')->assertRedirect(route('admin.login'));
        $this->get('/admin/notices')->assertRedirect(route('admin.login'));
    }

    public function test_regular_users_cannot_access_administration(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)->get('/admin')->assertForbidden();
        $this->actingAs($user)->get('/admin/notices')->assertForbidden();
    }

    public function test_only_administrators_can_sign_in_to_admin_area(): void
    {
        $nonAdmin = User::factory()->create(['email' => 'ordinary@example.com']);
        $this->post('/admin/login', ['email' => $nonAdmin->email, 'password' => 'password'])
            ->assertSessionHasErrors('email');
        $this->assertGuest();

        $admin = $this->administrator();
        $this->post('/admin/login', ['email' => $admin->email, 'password' => 'password'])
            ->assertRedirect(route('admin.dashboard'));
        $this->assertAuthenticatedAs($admin);
        $this->get('/admin')->assertOk();
    }

    public function test_administrator_can_create_update_and_delete_a_draft_notice(): void
    {
        $admin = $this->administrator();
        $this->actingAs($admin);

        $payload = [
            'title' => 'Income Tax Update',
            'slug' => 'income-tax-update',
            'body' => 'Draft awaiting review',
            'source_url' => 'https://nbr.gov.bd',
            'status' => 'draft',
        ];

        $this->post(route('admin.notices.store'), $payload)
            ->assertRedirect(route('admin.notices.index'));

        $notice = Notice::firstOrFail();
        $this->assertSame($admin->id, $notice->author_id);
        $this->assertNull($notice->published_at);

        $this->put(route('admin.notices.update', $notice), [
            ...$payload,
            'body' => 'Reviewed and verified',
            'status' => 'published',
        ])->assertRedirect(route('admin.notices.index'));

        $this->assertSame('published', $notice->fresh()->status);
        $this->assertNotNull($notice->fresh()->published_at);

        $this->delete(route('admin.notices.destroy', $notice))
            ->assertRedirect(route('admin.notices.index'));
        $this->assertDatabaseMissing('notices', ['id' => $notice->id]);
    }

    public function test_notice_validation_rejects_invalid_and_duplicate_slugs(): void
    {
        $admin = $this->administrator();
        $this->actingAs($admin);

        $this->post(route('admin.notices.store'), [
            'title' => 'X', 'slug' => 'not valid!', 'body' => 'Body', 'status' => 'draft',
        ])->assertSessionHasErrors('slug');

        Notice::create(['title' => 'Existing', 'slug' => 'existing', 'body' => 'Body', 'status' => 'draft']);

        $this->post(route('admin.notices.store'), [
            'title' => 'Duplicate', 'slug' => 'existing', 'body' => 'Body', 'status' => 'published',
        ])->assertSessionHasErrors('slug');
    }

    public function test_admin_logout_invalidates_the_session(): void
    {
        $admin = $this->administrator();

        $this->actingAs($admin)->post(route('admin.logout'))
            ->assertRedirect(route('admin.login'));

        $this->assertGuest();
    }
}

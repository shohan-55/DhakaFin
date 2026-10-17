<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminBoundaryTest extends TestCase
{
    use RefreshDatabase;

    public function test_default_database_seeding_never_creates_sample_accounts(): void
    {
        $this->seed(DatabaseSeeder::class);

        $this->assertDatabaseCount('users', 0);
    }

    public function test_guest_cannot_access_editorial_admin_area(): void
    {
        $this->get('/admin')->assertRedirect(route('admin.login'));
        $this->get('/admin/notices')->assertRedirect(route('admin.login'));
        $this->get('/admin/tax-rates')->assertRedirect(route('admin.login'));
    }

    public function test_regular_authenticated_user_cannot_access_editorial_admin_area(): void
    {
        $user = User::factory()->create(['is_admin' => false]);
        $this->actingAs($user);

        $this->get('/admin')->assertForbidden();
        $this->get('/admin/notices')->assertForbidden();
        $this->get('/admin/legal-sources')->assertForbidden();
    }

    public function test_administrator_can_open_dashboard(): void
    {
        $user = User::factory()->create(['is_admin' => true]);
        $this->actingAs($user);

        $this->get('/admin')->assertOk()->assertSee('DhakaFin Administration');
    }
}

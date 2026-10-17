<?php

namespace Tests\Feature;

use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class KnowledgeHubTest extends TestCase
{
    use RefreshDatabase;

    private function postData(): array
    {
        return [
            'title' => 'Understanding Financial Reporting',
            'slug' => 'understanding-financial-reporting',
            'category' => 'Accounting',
            'excerpt' => 'An overview of useful reporting concepts.',
            'body' => 'A draft educational article.',
            'source_url' => 'https://www.ifrs.org',
            'status' => 'draft',
        ];
    }

    private function actingAsAdmin(): User
    {
        $admin = User::factory()->create();
        $admin->is_admin = true;
        $admin->save();
        $this->actingAs($admin);

        return $admin;
    }

    public function test_editorial_authoring_requires_admin(): void
    {
        $this->get('/admin/posts')->assertRedirect(route('admin.login'));
        $this->actingAs(User::factory()->create())->get('/admin/posts')->assertForbidden();
    }

    public function test_admin_can_create_publish_and_delete_articles(): void
    {
        $admin = $this->actingAsAdmin();
        $this->post(route('admin.posts.store'), $this->postData())
            ->assertRedirect(route('admin.posts.index'));

        $post = Post::firstOrFail();
        $this->assertSame($admin->id, $post->author_id);
        $this->assertSame('draft', $post->status);
        $this->assertNull($post->published_at);

        $this->put(route('admin.posts.update', $post), [
            ...$this->postData(),
            'status' => 'published',
        ])->assertRedirect(route('admin.posts.index'));
        $this->assertNotNull($post->fresh()->published_at);

        $this->delete(route('admin.posts.destroy', $post))
            ->assertRedirect(route('admin.posts.index'));
        $this->assertDatabaseCount('posts', 0);
    }

    public function test_unreleased_blog_api_is_closed(): void
    {
        config()->set('dhakafin.features.blog', false);
        $this->getJson('/api/v1/posts')->assertStatus(503)->assertJsonPath('status', 'coming_soon');
    }

    public function test_enabled_blog_api_returns_only_published_entries(): void
    {
        config()->set('dhakafin.features.blog', true);
        Post::create($this->postData());
        Post::create([
            ...$this->postData(),
            'slug' => 'published-entry',
            'status' => 'published',
            'published_at' => now(),
        ]);

        $this->getJson('/api/v1/posts')
            ->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.slug', 'published-entry');
    }
}

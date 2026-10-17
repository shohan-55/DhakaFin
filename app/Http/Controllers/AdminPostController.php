<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

final class AdminPostController
{
    public function index(): View
    {
        return view('admin.posts.index', [
            'posts' => Post::query()->latest('id')->paginate(20),
        ]);
    }

    public function create(): View
    {
        return view('admin.posts.form', ['post' => new Post()]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request);
        $data['published_at'] = $data['status'] === 'published' ? now() : null;

        $post = new Post($data);
        $post->author_id = Auth::id();
        $post->save();

        return redirect()->route('admin.posts.index')->with('status', 'Article saved.');
    }

    public function edit(Post $post): View
    {
        return view('admin.posts.form', compact('post'));
    }

    public function update(Request $request, Post $post): RedirectResponse
    {
        $data = $this->validated($request, $post);
        $data['published_at'] = $data['status'] === 'published'
            ? ($post->published_at ?? now()) : null;

        $post->fill($data)->save();

        return redirect()->route('admin.posts.index')->with('status', 'Article updated.');
    }

    public function destroy(Post $post): RedirectResponse
    {
        $post->delete();

        return redirect()->route('admin.posts.index')->with('status', 'Article deleted.');
    }

    private function validated(Request $request, ?Post $post = null): array
    {
        return $request->validate([
            'title' => ['required', 'string', 'max:180'],
            'slug' => ['required', 'alpha_dash', 'max:200', Rule::unique('posts', 'slug')->ignore($post?->id)],
            'category' => ['required', 'string', 'max:80'],
            'excerpt' => ['required', 'string', 'max:1200'],
            'body' => ['required', 'string', 'max:200000'],
            'source_url' => ['nullable', 'url:http,https', 'max:2048'],
            'status' => ['required', Rule::in(['draft', 'published'])],
        ]);
    }
}

<!doctype html>
<html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><meta name="robots" content="noindex,nofollow"><title>Knowledge Hub | DhakaFin Admin</title>
<style>body{margin:0;padding:2rem;background:#092025;color:#f0faf7;font:16px/1.6 system-ui,sans-serif}main{max-width:1000px;margin:auto}a{color:#8fe4ce}.bar{display:flex;justify-content:space-between;align-items:center;gap:1rem;flex-wrap:wrap}table{width:100%;border-collapse:collapse;background:#123239;margin:1.5rem 0}td,th{padding:.8rem;border-bottom:1px solid #2c5e60;text-align:left}button,.link{background:#78dcc6;color:#062922;border:0;border-radius:8px;padding:.5rem .8rem;text-decoration:none;cursor:pointer}form{display:inline}</style>
</head><body><main><p><a href="{{ route('admin.dashboard') }}">← Dashboard</a></p><div class="bar"><h1>Knowledge Hub articles</h1><a class="link" href="{{ route('admin.posts.create') }}">New article</a></div>
@if (session('status'))<p role="status">{{ session('status') }}</p>@endif
<table><thead><tr><th>Title</th><th>Category</th><th>Status</th><th>Actions</th></tr></thead><tbody>
@forelse ($posts as $post)
<tr><td>{{ $post->title }}</td><td>{{ $post->category }}</td><td>{{ ucfirst($post->status) }}</td><td><a href="{{ route('admin.posts.edit', $post) }}">Edit</a>
<form method="post" action="{{ route('admin.posts.destroy', $post) }}" onsubmit="return confirm('Delete this article?')">@csrf @method('DELETE')<button type="submit">Delete</button></form></td></tr>
@empty<tr><td colspan="4">No articles yet.</td></tr>@endforelse
</tbody></table>{{ $posts->links() }}</main></body></html>

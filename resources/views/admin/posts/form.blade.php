<!doctype html>
<html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><meta name="robots" content="noindex,nofollow"><title>{{ $post->exists ? 'Edit' : 'Create' }} article | DhakaFin Admin</title>
<style>body{margin:0;padding:2rem;background:#092025;color:#f1fcfa;font:16px/1.6 system-ui,sans-serif}main{max-width:800px;margin:auto}label{display:block;margin-top:1rem;font-weight:600}input,textarea,select{display:block;width:100%;box-sizing:border-box;margin-top:.35rem;padding:.8rem;font:inherit;border:1px solid #52807b;border-radius:8px;background:#fff;color:#102b2b}textarea{min-height:120px}textarea.long{min-height:320px}button{margin-top:1.4rem;padding:.8rem 1.5rem;border:0;border-radius:8px;background:#78dcc6;color:#05372f;font-weight:700;cursor:pointer}a{color:#8be0cb}.errors{color:#ffc5c5}</style>
</head><body><main><p><a href="{{ route('admin.posts.index') }}">← Back to articles</a></p><h1>{{ $post->exists ? 'Edit article' : 'Create article' }}</h1>
@if ($errors->any())<div class="errors" role="alert"><strong>Please correct the form:</strong><ul>@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>@endif
<form method="post" action="{{ $post->exists ? route('admin.posts.update', $post) : route('admin.posts.store') }}">@csrf
@if ($post->exists)@method('PUT')@endif
<label for="title">Title</label><input id="title" name="title" required maxlength="180" value="{{ old('title', $post->title) }}">
<label for="slug">URL slug</label><input id="slug" name="slug" required maxlength="200" pattern="[A-Za-z0-9_-]+" value="{{ old('slug', $post->slug) }}">
<label for="category">Category</label><input id="category" name="category" required maxlength="80" value="{{ old('category', $post->category) }}">
<label for="excerpt">Short summary</label><textarea id="excerpt" name="excerpt" required>{{ old('excerpt', $post->excerpt) }}</textarea>
<label for="body">Article body (plain text, escaped on output)</label><textarea class="long" id="body" name="body" required>{{ old('body', $post->body) }}</textarea>
<label for="source_url">Primary source URL (optional)</label><input id="source_url" type="url" name="source_url" value="{{ old('source_url', $post->source_url) }}">
<label for="status">Publication status</label><select id="status" name="status"><option value="draft" @selected(old('status', $post->status ?: 'draft') === 'draft')>Draft</option><option value="published" @selected(old('status', $post->status ?: 'draft') === 'published')>Published</option></select>
<button type="submit">Save article</button></form></main></body></html>

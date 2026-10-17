<!doctype html>
<html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><meta name="robots" content="noindex,nofollow"><title>{{ $notice->exists ? 'Edit' : 'Create' }} notice | DhakaFin Admin</title>
<style>body{margin:0;padding:2rem;background:#092025;color:#f1fcfa;font:16px/1.6 system-ui,sans-serif}main{max-width:760px;margin:auto}label{display:block;margin-top:1rem;font-weight:600}input,textarea,select{display:block;box-sizing:border-box;width:100%;padding:.8rem;margin-top:.35rem;border:1px solid #52807b;border-radius:8px;background:#fff;color:#102b2b;font:inherit}textarea{min-height:220px}button{margin-top:1.4rem;padding:.8rem 1.5rem;border:0;border-radius:8px;background:#78dcc6;color:#05372f;font-weight:700;cursor:pointer}a{color:#8be0cb}.errors{color:#ffc5c5}</style>
</head><body><main><p><a href="{{ route('admin.notices.index') }}">← Back to notices</a></p><h1>{{ $notice->exists ? 'Edit notice' : 'Create notice' }}</h1>
@if ($errors->any())<div class="errors" role="alert"><strong>Please correct the form:</strong><ul>@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>@endif
<form method="post" action="{{ $notice->exists ? route('admin.notices.update', $notice) : route('admin.notices.store') }}">@csrf
@if ($notice->exists)@method('PUT')@endif
<label for="title">Title</label><input id="title" name="title" required maxlength="180" value="{{ old('title', $notice->title) }}">
<label for="slug">URL slug (letters, numbers and hyphens)</label><input id="slug" name="slug" required maxlength="200" value="{{ old('slug', $notice->slug) }}" pattern="[A-Za-z0-9_-]+">
<label for="body">Content (plain text)</label><textarea id="body" name="body" required>{{ old('body', $notice->body) }}</textarea>
<label for="source_url">Source URL (optional)</label><input id="source_url" type="url" name="source_url" value="{{ old('source_url', $notice->source_url) }}">
<label for="status">Publication status</label><select id="status" name="status"><option value="draft" @selected(old('status', $notice->status ?: 'draft') === 'draft')>Draft</option><option value="published" @selected(old('status', $notice->status ?: 'draft') === 'published')>Published</option></select>
<button type="submit">Save notice</button></form></main></body></html>

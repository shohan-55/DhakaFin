<!doctype html>
<html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><meta name="robots" content="noindex,nofollow"><title>{{ $source->exists ? 'Edit' : 'Add' }} legal source | DhakaFin Admin</title>
<style>body{margin:0;padding:2rem;background:#092025;color:#f1fcfa;font:16px/1.6 system-ui,sans-serif}main{max-width:780px;margin:auto}label{display:block;margin-top:1rem;font-weight:600}input,textarea,select{display:block;width:100%;box-sizing:border-box;margin-top:.35rem;padding:.8rem;font:inherit;border:1px solid #52807b;border-radius:8px;background:#fff;color:#102b2b}textarea{min-height:180px}button{margin-top:1.4rem;padding:.8rem 1.5rem;border:0;border-radius:8px;background:#78dcc6;color:#05372f;font-weight:700;cursor:pointer}a{color:#8be0cb}.errors{color:#ffc5c5}</style>
</head><body><main><p><a href="{{ route('admin.legal-sources.index') }}">← Back to sources</a></p><h1>{{ $source->exists ? 'Edit legal source' : 'Add legal source' }}</h1><p>Only verify an entry after checking its original source. Record the section or PDF page so readers can independently confirm it.</p>
@if ($errors->any())<div class="errors" role="alert"><strong>Please correct the form:</strong><ul>@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>@endif
<form method="post" action="{{ $source->exists ? route('admin.legal-sources.update', $source) : route('admin.legal-sources.store') }}">@csrf
@if ($source->exists)@method('PUT')@endif
<label for="title">Document title</label><input id="title" name="title" required maxlength="220" value="{{ old('title', $source->title) }}">
<label for="slug">URL slug</label><input id="slug" name="slug" required maxlength="220" value="{{ old('slug', $source->slug) }}">
<label for="source_type">Source type</label><select id="source_type" name="source_type">
@foreach (['act'=>'Act','rules'=>'Rules','sro'=>'SRO','circular'=>'Circular','guidance'=>'Guidance','other'=>'Other'] as $key => $name)
<option value="{{ $key }}" @selected(old('source_type', $source->source_type ?? 'act') === $key)>{{ $name }}</option>@endforeach
</select>
<label for="reference_code">Reference number/code</label><input id="reference_code" name="reference_code" maxlength="120" value="{{ old('reference_code', $source->reference_code) }}">
<label for="provision_reference">Act section / legal provision</label><input id="provision_reference" name="provision_reference" maxlength="220" value="{{ old('provision_reference', $source->provision_reference) }}">
<label for="page_reference">PDF page reference</label><input id="page_reference" name="page_reference" maxlength="120" value="{{ old('page_reference', $source->page_reference) }}">
<label for="summary">Human-verified summary (no invented citations)</label><textarea id="summary" name="summary" required>{{ old('summary', $source->summary) }}</textarea>
<label for="source_url">Direct document URL</label><input id="source_url" name="source_url" type="url" required value="{{ old('source_url', $source->source_url) }}">
<label for="document_date">Document date (optional)</label><input id="document_date" name="document_date" type="date" value="{{ old('document_date', $source->document_date?->format('Y-m-d')) }}">
<label for="status">Verification status</label><select id="status" name="status"><option value="draft" @selected(old('status', $source->status ?: 'draft') === 'draft')>Draft</option><option value="verified" @selected(old('status', $source->status ?: 'draft') === 'verified')>Verified</option></select>
<button type="submit">Save source</button></form></main></body></html>

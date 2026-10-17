<!doctype html>
<html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><meta name="robots" content="noindex,nofollow"><title>Law sources | DhakaFin Admin</title>
<style>body{margin:0;padding:2rem;background:#092025;color:#f0faf7;font:16px/1.6 system-ui,sans-serif}main{max-width:1000px;margin:auto}a{color:#8fe4ce}.bar{display:flex;justify-content:space-between;align-items:center;gap:1rem;flex-wrap:wrap}table{width:100%;border-collapse:collapse;background:#123239;margin:1.5rem 0}td,th{padding:.8rem;border-bottom:1px solid #2c5e60;text-align:left}button,.link{background:#78dcc6;color:#062922;border:0;border-radius:8px;padding:.5rem .8rem;cursor:pointer;text-decoration:none}form{display:inline}</style>
</head><body><main><p><a href="{{ route('admin.dashboard') }}">← Dashboard</a></p><div class="bar"><h1>Verified law sources</h1><a class="link" href="{{ route('admin.legal-sources.create') }}">Add source</a></div>
@if (session('status'))<p role="status">{{ session('status') }}</p>@endif
<table><thead><tr><th>Document</th><th>Reference</th><th>Status</th><th>Actions</th></tr></thead><tbody>
@forelse ($sources as $source)
<tr><td>{{ $source->title }}</td><td>{{ $source->provision_reference ?: $source->page_reference }}</td><td>{{ ucfirst($source->status) }}</td><td><a href="{{ route('admin.legal-sources.edit', $source) }}">Edit</a>
<form method="post" action="{{ route('admin.legal-sources.destroy', $source) }}" onsubmit="return confirm('Delete this source?')">@csrf @method('DELETE')<button type="submit">Delete</button></form></td></tr>
@empty<tr><td colspan="4">No legal sources indexed yet.</td></tr>@endforelse
</tbody></table>{{ $sources->links() }}</main></body></html>

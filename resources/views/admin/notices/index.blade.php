<!doctype html>
<html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><meta name="robots" content="noindex,nofollow"><title>Notices | DhakaFin Admin</title>
<style>body{background:#092025;color:#f0faf7;font:16px/1.6 system-ui,sans-serif;margin:0;padding:2rem}main{max-width:1000px;margin:auto}a{color:#8fe4ce}h1{margin:.5rem 0 1rem}.bar{display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:1rem}table{width:100%;border-collapse:collapse;margin:1.5rem 0;background:#123239}td,th{text-align:left;padding:.9rem;border-bottom:1px solid #2c5e60;vertical-align:top}button,.link{background:#78dcc6;color:#062922;border:0;border-radius:8px;padding:.5rem .8rem;cursor:pointer;text-decoration:none}.notice{color:#a8ecd9}.error{color:#ffbaba}form{display:inline}</style>
</head><body><main><p><a href="{{ route('admin.dashboard') }}">← Dashboard</a></p><div class="bar"><h1>Editorial notices</h1><a class="link" href="{{ route('admin.notices.create') }}">New notice</a></div>
@if (session('status'))<p class="notice" role="status">{{ session('status') }}</p>@endif
<table><thead><tr><th>Title</th><th>Status</th><th>Published</th><th>Actions</th></tr></thead><tbody>
@forelse ($notices as $notice)
<tr><td>{{ $notice->title }}</td><td>{{ ucfirst($notice->status) }}</td><td>{{ $notice->published_at?->format('Y-m-d H:i') ?? '—' }}</td><td><a href="{{ route('admin.notices.edit', $notice) }}">Edit</a>
<form method="post" action="{{ route('admin.notices.destroy', $notice) }}" onsubmit="return confirm('Delete this notice?')">@csrf @method('DELETE')<button type="submit">Delete</button></form></td></tr>
@empty<tr><td colspan="4">No notices yet.</td></tr>@endforelse
</tbody></table>{{ $notices->links() }}</main></body></html>

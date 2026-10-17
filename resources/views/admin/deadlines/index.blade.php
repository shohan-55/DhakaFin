<!doctype html>
<html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><meta name="robots" content="noindex,nofollow"><title>Deadlines | DhakaFin Admin</title>
<style>body{background:#092025;color:#f0faf7;font:16px/1.6 system-ui,sans-serif;margin:0;padding:2rem}main{max-width:1000px;margin:auto}a{color:#8fe4ce}h1{margin:.5rem 0 1rem}.bar{display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:1rem}table{width:100%;border-collapse:collapse;margin:1.5rem 0;background:#123239}td,th{text-align:left;padding:.9rem;border-bottom:1px solid #2c5e60;vertical-align:top}button,.link{background:#78dcc6;color:#062922;border:0;border-radius:8px;padding:.5rem .8rem;cursor:pointer;text-decoration:none}.notice{color:#a8ecd9}form{display:inline}</style>
</head><body><main><p><a href="{{ route('admin.dashboard') }}">← Dashboard</a></p><div class="bar"><h1>Tax deadlines</h1><a class="link" href="{{ route('admin.deadlines.create') }}">New deadline</a></div>
@if (session('status'))<p class="notice" role="status">{{ session('status') }}</p>@endif
<table><thead><tr><th>Title</th><th>Due date</th><th>Status</th><th>Actions</th></tr></thead><tbody>
@forelse ($deadlines as $deadline)
<tr><td>{{ $deadline->title }}</td><td>{{ $deadline->due_on->format('Y-m-d') }}</td><td>{{ ucfirst($deadline->status) }}</td><td><a href="{{ route('admin.deadlines.edit', $deadline) }}">Edit</a>
<form method="post" action="{{ route('admin.deadlines.destroy', $deadline) }}" onsubmit="return confirm('Delete this deadline?')">@csrf @method('DELETE')<button type="submit">Delete</button></form></td></tr>
@empty<tr><td colspan="4">No deadlines yet.</td></tr>@endforelse
</tbody></table>{{ $deadlines->links() }}</main></body></html>

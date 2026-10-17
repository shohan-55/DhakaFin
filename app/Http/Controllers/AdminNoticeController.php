<?php

namespace App\Http\Controllers;

use App\Models\Notice;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

final class AdminNoticeController
{
    public function index(): View
    {
        return view('admin.notices.index', [
            'notices' => Notice::query()->latest('id')->paginate(20),
        ]);
    }

    public function create(): View
    {
        return view('admin.notices.form', ['notice' => new Notice()]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request);
        $data['published_at'] = $data['status'] === 'published' ? now() : null;

        $notice = new Notice($data);
        $notice->author_id = Auth::id();
        $notice->save();

        return redirect()->route('admin.notices.index')->with('status', 'Notice saved.');
    }

    public function edit(Notice $notice): View
    {
        return view('admin.notices.form', compact('notice'));
    }

    public function update(Request $request, Notice $notice): RedirectResponse
    {
        $data = $this->validated($request, $notice);
        $data['published_at'] = $data['status'] === 'published'
            ? ($notice->published_at ?? now()) : null;

        $notice->fill($data)->save();

        return redirect()->route('admin.notices.index')->with('status', 'Notice updated.');
    }

    public function destroy(Notice $notice): RedirectResponse
    {
        $notice->delete();

        return redirect()->route('admin.notices.index')->with('status', 'Notice deleted.');
    }

    private function validated(Request $request, ?Notice $notice = null): array
    {
        return $request->validate([
            'title' => ['required', 'string', 'max:180'],
            'slug' => ['required', 'alpha_dash', 'max:200', Rule::unique('notices', 'slug')->ignore($notice?->id)],
            'body' => ['required', 'string', 'max:100000'],
            'source_url' => ['nullable', 'url:http,https', 'max:2048'],
            'status' => ['required', Rule::in(['draft', 'published'])],
        ]);
    }
}

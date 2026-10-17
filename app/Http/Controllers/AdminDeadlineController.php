<?php

namespace App\Http\Controllers;

use App\Models\Deadline;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

final class AdminDeadlineController
{
    public function index(): View
    {
        return view('admin.deadlines.index', [
            'deadlines' => Deadline::query()->orderBy('due_on')->paginate(20),
        ]);
    }

    public function create(): View
    {
        return view('admin.deadlines.form', ['deadline' => new Deadline()]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request);
        $data['published_at'] = $data['status'] === 'published' ? now() : null;

        $deadline = new Deadline($data);
        $deadline->author_id = Auth::id();
        $deadline->save();

        return redirect()->route('admin.deadlines.index')->with('status', 'Deadline saved.');
    }

    public function edit(Deadline $deadline): View
    {
        return view('admin.deadlines.form', compact('deadline'));
    }

    public function update(Request $request, Deadline $deadline): RedirectResponse
    {
        $data = $this->validated($request, $deadline);
        $data['published_at'] = $data['status'] === 'published'
            ? ($deadline->published_at ?? now()) : null;

        $deadline->fill($data)->save();

        return redirect()->route('admin.deadlines.index')->with('status', 'Deadline updated.');
    }

    public function destroy(Deadline $deadline): RedirectResponse
    {
        $deadline->delete();

        return redirect()->route('admin.deadlines.index')->with('status', 'Deadline deleted.');
    }

    private function validated(Request $request, ?Deadline $deadline = null): array
    {
        return $request->validate([
            'title' => ['required', 'string', 'max:180'],
            'slug' => ['required', 'alpha_dash', 'max:200', Rule::unique('deadlines', 'slug')->ignore($deadline?->id)],
            'description' => ['required', 'string', 'max:20000'],
            'due_on' => ['required', 'date_format:Y-m-d'],
            'category' => ['required', 'string', 'max:80'],
            'source_url' => [Rule::requiredIf($request->input('status') === 'published'), 'nullable', 'url:http,https', 'max:2048'],
            'status' => ['required', Rule::in(['draft', 'published'])],
        ]);
    }
}

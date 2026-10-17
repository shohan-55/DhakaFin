<?php

namespace App\Http\Controllers;

use App\Models\LegalSource;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

final class AdminLegalSourceController
{
    public function index(): View
    {
        return view('admin.legal-sources.index', [
            'sources' => LegalSource::query()->latest('id')->paginate(20),
        ]);
    }

    public function create(): View
    {
        return view('admin.legal-sources.form', ['source' => new LegalSource()]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request);
        $data['verified_at'] = $data['status'] === 'verified' ? now() : null;

        $source = new LegalSource($data);
        $source->author_id = Auth::id();
        $source->save();

        return redirect()->route('admin.legal-sources.index')->with('status', 'Source saved.');
    }

    public function edit(LegalSource $legal_source): View
    {
        return view('admin.legal-sources.form', ['source' => $legal_source]);
    }

    public function update(Request $request, LegalSource $legal_source): RedirectResponse
    {
        $data = $this->validated($request, $legal_source);
        // Verification timestamp must reflect the current edited content.
        $data['verified_at'] = $data['status'] === 'verified' ? now() : null;
        $legal_source->fill($data)->save();

        return redirect()->route('admin.legal-sources.index')->with('status', 'Source updated.');
    }

    public function destroy(LegalSource $legal_source): RedirectResponse
    {
        $legal_source->delete();

        return redirect()->route('admin.legal-sources.index')->with('status', 'Source deleted.');
    }

    private function validated(Request $request, ?LegalSource $source = null): array
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:220'],
            'slug' => ['required', 'alpha_dash', 'max:220', Rule::unique('legal_sources', 'slug')->ignore($source?->id)],
            'source_type' => ['required', Rule::in(['act', 'rules', 'sro', 'circular', 'guidance', 'other'])],
            'reference_code' => ['nullable', 'string', 'max:120'],
            'provision_reference' => ['nullable', 'string', 'max:220'],
            'page_reference' => ['nullable', 'string', 'max:120'],
            'summary' => ['required', 'string', 'max:20000'],
            'source_url' => ['required', 'url:http,https', 'max:2048'],
            'document_date' => ['nullable', 'date_format:Y-m-d'],
            'status' => ['required', Rule::in(['draft', 'verified'])],
        ]);

        if ($data['status'] === 'verified' && blank($data['provision_reference'] ?? null)
            && blank($data['page_reference'] ?? null)) {
            throw ValidationException::withMessages([
                'provision_reference' => 'Verified legal sources need a specific provision or page reference.',
            ]);
        }

        return $data;
    }
}

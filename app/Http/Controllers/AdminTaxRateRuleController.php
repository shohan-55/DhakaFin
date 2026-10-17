<?php

namespace App\Http\Controllers;

use App\Models\TaxRateRule;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

final class AdminTaxRateRuleController
{
    public function index(): View
    {
        return view('admin.tax-rates.index', [
            'rules' => TaxRateRule::query()->orderBy('tax_type')->orderBy('category')->paginate(20),
        ]);
    }

    public function create(): View
    {
        return view('admin.tax-rates.form', ['rule' => new TaxRateRule()]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request);
        $data['verified_at'] = $data['status'] === 'verified' ? now() : null;

        $rule = new TaxRateRule($data);
        $rule->author_id = Auth::id();
        $rule->save();

        return redirect()->route('admin.tax-rates.index')->with('status', 'Rate rule saved.');
    }

    public function edit(TaxRateRule $tax_rate): View
    {
        return view('admin.tax-rates.form', ['rule' => $tax_rate]);
    }

    public function update(Request $request, TaxRateRule $tax_rate): RedirectResponse
    {
        $data = $this->validated($request, $tax_rate);
        // Verification timestamp must reflect the current edited content.
        $data['verified_at'] = $data['status'] === 'verified' ? now() : null;

        $tax_rate->fill($data)->save();

        return redirect()->route('admin.tax-rates.index')->with('status', 'Rate rule updated.');
    }

    public function destroy(TaxRateRule $tax_rate): RedirectResponse
    {
        $tax_rate->delete();

        return redirect()->route('admin.tax-rates.index')->with('status', 'Rate rule deleted.');
    }

    private function validated(Request $request, ?TaxRateRule $rule = null): array
    {
        return $request->validate([
            'code' => ['required', 'alpha_dash', 'max:120', Rule::unique('tax_rate_rules', 'code')->ignore($rule?->id)],
            'tax_type' => ['required', Rule::in(['tds', 'vds', 'vat'])],
            'category' => ['required', 'string', 'max:120'],
            'description' => ['required', 'string', 'max:20000'],
            'rate_basis_points' => ['required', 'integer', 'between:0,10000'],
            'effective_from' => ['required', 'date_format:Y-m-d'],
            'effective_until' => ['nullable', 'date_format:Y-m-d', 'after_or_equal:effective_from'],
            'reference_code' => ['required', 'string', 'max:160'],
            'provision_reference' => ['required', 'string', 'max:200'],
            'source_url' => ['required', 'url:http,https', 'max:2048'],
            'status' => ['required', Rule::in(['draft', 'verified'])],
        ]);
    }
}

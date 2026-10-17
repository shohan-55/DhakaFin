<?php

namespace App\Http\Controllers;

use App\Models\TaxRateRule;
use App\Services\FixedRateEstimator;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

final class PublicTaxEstimatorController
{
    public function estimate(Request $request, FixedRateEstimator $calculator): JsonResponse
    {
        $type = (string) $request->input('tax_type', '');
        $feature = match ($type) {
            'tds' => 'tds_calculator',
            'vds' => 'vds_calculator',
            'vat' => 'vat_calculator',
            default => null,
        };

        if ($feature === null || !config('dhakafin.features.' . $feature, false)) {
            return response()->json(['status' => 'coming_soon'], 503);
        }

        $data = $request->validate([
            'tax_type' => ['required', Rule::in(['tds', 'vds', 'vat'])],
            'rate_rule_id' => ['required', 'integer', 'min:1'],
            'amount_bdt' => ['required', 'string', 'regex:/\A(0|[1-9][0-9]{0,11})(?:\.[0-9]{1,2})?\z/D'],
        ]);

        $today = today(config('app.timezone'));

        $rule = TaxRateRule::query()
            ->whereKey($data['rate_rule_id'])
            ->where('tax_type', $data['tax_type'])
            ->where('status', 'verified')
            ->whereNotNull('verified_at')
            ->where('verified_at', '<=', now())
            ->whereDate('effective_from', '<=', $today)
            ->where(function ($query) use ($today): void {
                $query->whereNull('effective_until')
                    ->orWhereDate('effective_until', '>=', $today);
            })
            ->firstOrFail();

        $result = $calculator->calculate($data['amount_bdt'], $rule->rate_basis_points);

        return response()->json([
            'tax_type' => $rule->tax_type,
            'category' => $rule->category,
            'rule_code' => $rule->code,
            'calculation' => $result,
            'citation' => [
                'reference_code' => $rule->reference_code,
                'provision_reference' => $rule->provision_reference,
                'source_url' => $rule->source_url,
            ],
            'warning' => 'Illustrative fixed-rate arithmetic only. Verify classification, exemptions, thresholds and statutory application before relying on this figure.',
        ]);
    }

    public function rates(): JsonResponse
    {
        if (!config('dhakafin.features.tax_rates', false)) {
            return response()->json(['status' => 'coming_soon'], 503);
        }

        $today = today(config('app.timezone'));
        $rules = TaxRateRule::query()
            ->where('status', 'verified')
            ->whereNotNull('verified_at')
            ->where('verified_at', '<=', now())
            ->whereDate('effective_from', '<=', $today)
            ->where(function ($query) use ($today): void {
                $query->whereNull('effective_until')
                    ->orWhereDate('effective_until', '>=', $today);
            })
            ->orderBy('tax_type')
            ->orderBy('category')
            ->limit(100)
            ->get([
                'id', 'code', 'tax_type', 'category', 'description',
                'rate_basis_points', 'effective_from', 'effective_until',
                'reference_code', 'provision_reference', 'source_url',
            ]);

        return response()->json(['data' => $rules]);
    }
}

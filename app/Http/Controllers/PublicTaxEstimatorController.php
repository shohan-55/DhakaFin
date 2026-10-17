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
            'amount_mode' => ['sometimes', Rule::in(['exclusive', 'inclusive'])],
            'transaction_date' => ['sometimes', 'date_format:Y-m-d'],
        ]);

        $mode = $data['amount_mode'] ?? 'exclusive';
        if ($mode === 'inclusive' && $type !== 'vat') {
            return response()->json(['message' => 'Inclusive amount mode is supported only for VAT.'], 422);
        }

        $asOf = $data['transaction_date'] ?? today(config('app.timezone'))->toDateString();

        $rule = TaxRateRule::query()
            ->whereKey($data['rate_rule_id'])
            ->where('tax_type', $data['tax_type'])
            ->where('status', 'verified')
            ->whereNotNull('verified_at')
            ->where('verified_at', '<=', now())
            ->whereDate('effective_from', '<=', $asOf)
            ->where(function ($query) use ($asOf): void {
                $query->whereNull('effective_until')
                    ->orWhereDate('effective_until', '>=', $asOf);
            })
            ->firstOrFail();

        $result = $calculator->calculate($data['amount_bdt'], $rule->rate_basis_points, $mode);

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

    public function rates(Request $request): JsonResponse
    {
        if (!config('dhakafin.features.tax_rates', false)) {
            return response()->json(['status' => 'coming_soon'], 503);
        }

        $validated = $request->validate([
            'as_of' => ['sometimes', 'date_format:Y-m-d'],
        ]);
        $asOf = $validated['as_of'] ?? today(config('app.timezone'))->toDateString();
        $rules = TaxRateRule::query()
            ->where('status', 'verified')
            ->whereNotNull('verified_at')
            ->where('verified_at', '<=', now())
            ->whereDate('effective_from', '<=', $asOf)
            ->where(function ($query) use ($asOf): void {
                $query->whereNull('effective_until')
                    ->orWhereDate('effective_until', '>=', $asOf);
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

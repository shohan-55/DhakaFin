<?php

namespace App\Http\Controllers;

use App\Models\LegalSource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

final class PublicLawSearchController
{
    public function search(Request $request): JsonResponse
    {
        if (!config('dhakafin.features.law_search', false)) {
            return response()->json(['status' => 'coming_soon'], 503);
        }

        $data = $request->validate([
            'q' => ['required', 'string', 'min:3', 'max:120'],
        ]);

        $keyword = trim($data['q']);
        if (mb_strlen($keyword) < 3) {
            return response()->json(['message' => 'Search must contain at least 3 characters.'], 422);
        }

        // Bound parameters prevent SQL injection. Only human-verified sources
        // may be returned; this is literal search, NOT AI-generated legal advice.
        $results = LegalSource::query()
            ->where('status', 'verified')
            ->whereNotNull('verified_at')
            ->where('verified_at', '<=', now())
            ->where(function ($builder) use ($keyword): void {
                $builder->where('title', 'like', '%' . $keyword . '%')
                    ->orWhere('reference_code', 'like', '%' . $keyword . '%')
                    ->orWhere('provision_reference', 'like', '%' . $keyword . '%')
                    ->orWhere('summary', 'like', '%' . $keyword . '%');
            })
            ->latest('verified_at')
            ->limit(25)
            ->get([
                'title', 'slug', 'source_type', 'reference_code',
                'provision_reference', 'page_reference', 'summary',
                'source_url', 'document_date', 'verified_at',
            ]);

        return response()->json([
            'query' => $keyword,
            'data' => $results,
            'notice' => 'Keyword matches from human-verified records; check each cited original document.',
        ]);
    }
}

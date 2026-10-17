<?php

namespace App\Http\Controllers;

use App\Models\Deadline;
use App\Models\Notice;
use Illuminate\Http\JsonResponse;

final class PublicContentController
{
    public function notices(): JsonResponse
    {
        if (!config('dhakafin.features.notices', false)) {
            return response()->json(['status' => 'coming_soon'], 503);
        }

        $data = Notice::query()
            ->where('status', 'published')
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now())
            ->latest('published_at')
            ->limit(50)
            ->get(['title', 'slug', 'body', 'source_url', 'published_at']);

        return response()->json(['data' => $data]);
    }

    public function deadlines(): JsonResponse
    {
        if (!config('dhakafin.features.deadlines', false)) {
            return response()->json(['status' => 'coming_soon'], 503);
        }

        $data = Deadline::query()
            ->where('status', 'published')
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now())
            ->whereDate('due_on', '>=', today(config('app.timezone')))
            ->orderBy('due_on')
            ->limit(100)
            ->get(['title', 'slug', 'description', 'category', 'due_on', 'source_url', 'published_at']);

        return response()->json(['data' => $data]);
    }
}

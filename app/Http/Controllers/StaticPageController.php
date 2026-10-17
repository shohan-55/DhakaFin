<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;
use Illuminate\Http\Response;

final class StaticPageController
{
    public function show(string $page): View|Response
    {
        abort_unless(in_array($page, config('dhakafin.pages', []), true), 404);

        $feature = config('dhakafin.feature_pages.' . $page);

        if (is_string($feature) && !config('dhakafin.features.' . $feature, false)) {
            return response()->view('coming-soon');
        }

        abort_unless(view()->exists('pages.' . $page), 404);

        return view('pages.' . $page);
    }
}

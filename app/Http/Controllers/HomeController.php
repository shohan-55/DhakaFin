<?php

namespace App\Http\Controllers;

use App\Support\FeatureManifest;
use Illuminate\Http\Response;

final class HomeController
{
    public function __invoke(): Response
    {
        $html = view('home')->render();

        // Keep the stored Blade view unchanged, preserving original styling
        // and markup parity, while announcing only approved services.
        $html = str_ireplace('</head>', FeatureManifest::tag() . '</head>', $html);

        return response($html)->header('Content-Type', 'text/html; charset=UTF-8');
    }
}

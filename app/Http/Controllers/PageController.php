<?php

namespace App\Http\Controllers;<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;

class PageController
{
    public function home()
    {
        return view('pages.home');
    }

    public function page(string $slug)
    {
        $cleanSlug = preg_replace('/\.html$/i', '', trim($slug, '/'));

        if (View::exists("pages.{$cleanSlug}")) {
            return view("pages.{$cleanSlug}");
        }

        $htmlPath = base_path("pages/{$cleanSlug}.html");
        if (!file_exists($htmlPath)) {
            abort(404);
        }

        $rawHtml = file_get_contents($htmlPath);
        $title = 'DhakaFin — Tax, Audit, VAT & Advisory in Bangladesh';
        if (preg_match('/<title\b[^>]*>(.*?)<\/title>/is', $rawHtml, $tm)) {
            $title = trim( html_entity_decode(strip_tags($tm[1])) );
        }

        $mainContent = $rawHtml;
        if (preg_match('/<main\b[^>]*>(.*?)<\/main>/is', $rawHtml, $mm)) {
            $mainContent = $mm[1];
        }

        // Normalize relative asset and page paths so 100% of styles, images and links work from any route depth
        $mainContent = str_replace(['../assets/', 'assets/'], ['/assets/', '/assets/'], $mainContent);
        $mainContent = str_replace('../index.html', '/', $mainContent);

        return view('pages.subpage', [
            'slug' => $cleanSlug,
            'title' => $title,
            'content' => $mainContent,
        ]);
    }
}

use Illuminate\Http\Request;

class PageController extends Controller
{
    public function home()
    {
        return view('pages.home');
    }

    public function page(string $slug)
    {
        $slug = preg_replace('/\.html$/', '', $slug);

        if (view()->exists("pages.{$slug}")) {
            return view("pages.{$slug}");
        }

        abort(404);
    }
}

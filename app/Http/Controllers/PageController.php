<?php

namespace App\Http\Controllers;

use App\Services\CmsManager;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class PageController extends Controller
{
    public function __construct(protected CmsManager $cms)
    {
    }

    /**
     * Display the DhakaFin homepage with live CMS overrides.
     */
    public function home(): Response
    {
        $html = view('pages.home', [
            'slug' => 'home',
            'cms' => $this->cms->all(),
        ])->render();

        return response($this->cms->applyOverrides($html, 'home'));
    }

    /**
     * Display any DhakaFin inner page, calculator, client workspace, auth, or admin studio view.
     */
    public function page(Request $request, string $slug): Response
    {
        $slug = preg_replace('/\.html$/i', '', trim($slug, '/'));

        if ($slug === 'index' || $slug === 'home') {
            return $this->home();
        }

        if ($slug === '404') {
            abort(404);
        }

        if (view()->exists("pages.{$slug}")) {
            $pageMeta = config("dhakafin.pages.{$slug}", []);
            $html = view("pages.{$slug}", [
                'slug' => $slug,
                'pageMeta' => $pageMeta,
                'cms' => $this->cms->all(),
                'request' => $request,
            ])->render();

            return response($this->cms->applyOverrides($html, $slug));
        }

        abort(404);
    }

    /**
     * Serve the XML sitemap.
     */
    public function sitemapXml(): Response
    {
        $path = public_path('sitemap.xml');
        abort_unless(file_exists($path), 404);

        return response(file_get_contents($path), 200, [
            'Content-Type' => 'application/xml; charset=UTF-8',
        ]);
    }

    /**
     * Serve the JSON sitemap metadata.
     */
    public function sitemapJson()
    {
        return response()->json(array_values(config('dhakafin.pages', [])));
    }

    /**
     * Serve static assets directly if routed through Laravel.
     */
    public function asset(string $path): Response
    {
        $cleanPath = ltrim(str_replace('..', '', $path), '/');
        $fullPath = public_path("assets/{$cleanPath}");
        abort_unless(is_file($fullPath), 404);

        $ext = strtolower(pathinfo($fullPath, PATHINFO_EXTENSION));
        $mimeMap = [
            'css' => 'text/css; charset=UTF-8',
            'js' => 'application/javascript; charset=UTF-8',
            'svg' => 'image/svg+xml',
            'png' => 'image/png',
            'jpg' => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'json' => 'application/json; charset=UTF-8',
        ];

        return response(file_get_contents($fullPath), 200, [
            'Content-Type' => $mimeMap[$ext] ?? 'application/octet-stream',
            'Cache-Control' => 'public, max-age=604800',
        ]);
    }
}

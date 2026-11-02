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
}

<?php

namespace App\Http\Controllers;

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

<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Lawyer;
use App\Models\Service;

class HomeController extends Controller
{
    public function index()
    {
        $lawyers  = Lawyer::active()->get();
        $services = Service::active()->take(4)->get();
        $articles = Article::published()->take(3)->with('lawyer')->get();

        return view('public.home', compact('lawyers', 'services', 'articles'));
    }
}

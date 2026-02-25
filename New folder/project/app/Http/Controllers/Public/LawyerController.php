<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Lawyer;

class LawyerController extends Controller
{
    public function index()
    {
        $lawyers = Lawyer::active()->get();

        return view('public.lawyers.index', compact('lawyers'));
    }

    public function show(string $slug)
    {
        $lawyer = Lawyer::where('slug', $slug)->where('is_active', true)->firstOrFail();

        return view('public.lawyers.show', compact('lawyer'));
    }
}

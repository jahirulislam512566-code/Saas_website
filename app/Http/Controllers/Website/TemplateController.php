<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class TemplateController extends Controller
{
    public function index(): View
    {
        return view('website.templates.index');
    }

    public function show($slug): View
    {
        return view('website.templates.show');
    }
}
<?php
// app/Http/Controllers/Agency/AboutController.php

namespace App\Http\Controllers\Agency;

use App\Http\Controllers\Controller;

class AboutController extends Controller
{
    public function index()
    {
        return view('themes.agency.about');
    }
}
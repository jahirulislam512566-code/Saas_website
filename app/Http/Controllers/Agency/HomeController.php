<?php
// app/Http/Controllers/Agency/HomeController.php

namespace App\Http\Controllers\Agency;

use App\Http\Controllers\Controller;

class HomeController extends Controller
{
    public function index()
    {
        return view('themes.agency.home');
    }
}
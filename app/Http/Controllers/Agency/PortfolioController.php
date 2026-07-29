<?php
// app/Http/Controllers/Agency/PortfolioController.php

namespace App\Http\Controllers\Agency;

use App\Http\Controllers\Controller;

class PortfolioController extends Controller
{
    public function index()
    {
        return view('themes.agency.portfolio');
    }
}
<?php
// app/Http/Controllers/Agency/ServicesController.php

namespace App\Http\Controllers\Agency;

use App\Http\Controllers\Controller;

class ServicesController extends Controller
{
    public function index()
    {
        return view('themes.agency.services');
    }
}
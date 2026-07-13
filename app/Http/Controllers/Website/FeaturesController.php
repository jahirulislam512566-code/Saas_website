<?php
// app/Http/Controllers/Website/FeaturesController.php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;

class FeaturesController extends Controller
{
    public function index()
    {
        return view('website.features');
    }
}
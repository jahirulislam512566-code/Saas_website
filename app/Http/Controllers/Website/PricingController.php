<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use App\Models\Plan;
use Illuminate\Http\Request;

class PricingController extends Controller
{
    /**
     * Display the pricing page.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $plans = Plan::where('is_active', true)
            ->orderBy('sort_order', 'asc')
            ->orderBy('price', 'asc')
            ->get();

        return view('website.pricing', compact('plans'));
    }
}
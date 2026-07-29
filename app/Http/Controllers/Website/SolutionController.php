<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SolutionController extends Controller
{
    public function index()
    {
        $solutions = [
            [
                'name' => 'Small Business',
                'description' => 'Build a professional website for your small business',
                'icon' => '🏢',
                'slug' => 'small-business'
            ],
            [
                'name' => 'Restaurant',
                'description' => 'Online ordering and reservation system for restaurants',
                'icon' => '🍽️',
                'slug' => 'restaurant'
            ],
            [
                'name' => 'Agency',
                'description' => 'Portfolio and client management for agencies',
                'icon' => '🚀',
                'slug' => 'agency'
            ],
            [
                'name' => 'School',
                'description' => 'Course management and student portals',
                'icon' => '📚',
                'slug' => 'school'
            ],
            [
                'name' => 'Doctor',
                'description' => 'Appointment booking and patient management',
                'icon' => '🏥',
                'slug' => 'doctor'
            ],
            [
                'name' => 'Real Estate',
                'description' => 'Property listings and virtual tours',
                'icon' => '🏠',
                'slug' => 'real-estate'
            ],
        ];

        return view('website.solutions', compact('solutions'));
    }

    public function show($slug)
    {
        return view('website.solution-detail', compact('slug'));
    }
}
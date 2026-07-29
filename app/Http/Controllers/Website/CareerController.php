<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CareerController extends Controller
{
    public function index()
    {
        $jobs = [
            [
                'title' => 'Frontend Developer',
                'department' => 'Engineering',
                'location' => 'Remote',
                'type' => 'Full-time',
                'slug' => 'frontend-developer'
            ],
            [
                'title' => 'Laravel Developer',
                'department' => 'Engineering',
                'location' => 'Remote',
                'type' => 'Full-time',
                'slug' => 'laravel-developer'
            ],
            [
                'title' => 'UI Designer',
                'department' => 'Design',
                'location' => 'Remote',
                'type' => 'Full-time',
                'slug' => 'ui-designer'
            ],
            [
                'title' => 'Support Engineer',
                'department' => 'Support',
                'location' => 'Remote',
                'type' => 'Full-time',
                'slug' => 'support-engineer'
            ],
        ];

        return view('website.careers.index', compact('jobs'));
    }

    public function show($slug)
    {
        return view('website.career-detail', compact('slug'));
    }

    public function apply(Request $request, $slug)
    {
        // Validate and process application
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'phone' => 'required|string',
            'resume' => 'required|file|mimes:pdf,doc,docx|max:5120',
            'cover_letter' => 'nullable|string',
        ]);

        // Process application logic here
        // ...

        return redirect()->back()->with('success', 'Application submitted successfully!');
    }
}
<?php
// app/Http/Controllers/Agency/ContactController.php

namespace App\Http\Controllers\Agency;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function index()
    {
        return view('themes.agency.contact');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'subject' => 'required|string|max:255',
            'message' => 'required|string|min:10',
            'budget' => 'nullable|string',
        ]);

        // Process the contact form submission
        // Store in database, send email, etc.

        return redirect()->back()->with('success', 'Thank you for your message! We\'ll get back to you soon.');
    }
}
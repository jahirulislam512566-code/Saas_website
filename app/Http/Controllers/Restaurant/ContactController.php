<?php

namespace App\Http\Controllers\Restaurant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function index()
    {
        $data = [
            'contactInfo' => [
                ['icon' => 'user', 'label' => 'Address', 'value' => '123 Main St, New York, NY 10001'],
                ['icon' => 'cog', 'label' => 'Phone', 'value' => '+1 (555) 123-4567'],
                ['icon' => 'star', 'label' => 'Email', 'value' => 'info@restaurant.com']
            ],
            'hours' => [
                'Monday - Friday' => '11:00 AM - 10:00 PM',
                'Saturday' => '10:00 AM - 11:00 PM',
                'Sunday' => '12:00 PM - 9:00 PM'
            ]
        ];
        
        return view('restaurant.contact', $data);
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'message' => 'required|string'
        ]);
        
        // Process the contact form submission
        // Send email, save to database, etc.
        
        return redirect()->route('themes.restaurant.contact')
            ->with('success', 'Thank you for your message! We\'ll get back to you soon.');
    }
}
<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ThemeController extends Controller
{
    /**
     * Display the about page.
     */
    public function about()
    {
        $theme = config('themes.active', 'default');
        $view = "themes.{$theme}.about";
        
        if (view()->exists($view)) {
            return view($view);
        }
        
        return view('website.about');
    }

    /**
     * Display the contact page.
     */
    public function contact()
    {
        $theme = config('themes.active', 'default');
        $view = "themes.{$theme}.contact";
        
        if (view()->exists($view)) {
            return view($view);
        }
        
        return view('website.contact');
    }

    /**
     * Display the features page.
     */
    public function features()
    {
        $theme = config('themes.active', 'default');
        $view = "themes.{$theme}.features";
        
        if (view()->exists($view)) {
            return view($view);
        }
        
        return view('website.features');
    }

    /**
     * Display the pricing page.
     */
    public function pricing()
    {
        $theme = config('themes.active', 'default');
        $view = "themes.{$theme}.pricing";
        
        if (view()->exists($view)) {
            return view($view);
        }
        
        return view('website.pricing');
    }

    /**
     * Display the menu page (Restaurant theme).
     */
    public function menu()
    {
        return view('themes.restaurant.menu');
    }

    /**
     * Handle reservation request (Restaurant theme).
     */
    public function reserve(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'phone' => 'required|string',
            'date' => 'required|date',
            'time' => 'required',
            'guests' => 'required|integer|min:1|max:20',
        ]);

        // Process reservation logic here
        // ...

        return redirect()->back()->with('success', 'Reservation made successfully!');
    }

    /**
     * Display projects page (Portfolio theme).
     */
    public function projects()
    {
        return view('themes.portfolio.projects');
    }

    /**
     * Display project detail (Portfolio theme).
     */
    public function projectDetail($id)
    {
        return view('themes.portfolio.project-detail', ['projectId' => $id]);
    }

    /**
     * Display services page (Agency theme).
     */
    public function services()
    {
        return view('themes.agency.services');
    }

    /**
     * Display service detail (Agency theme).
     */
    public function serviceDetail($slug)
    {
        return view('themes.agency.service-detail', ['slug' => $slug]);
    }

    /**
     * Display classes page (Gym theme).
     */
    public function classes()
    {
        return view('themes.gym.classes');
    }

    /**
     * Handle membership request (Gym theme).
     */
    public function membership(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'plan' => 'required|string',
        ]);

        // Process membership logic here
        // ...

        return redirect()->back()->with('success', 'Membership application submitted!');
    }

    /**
     * Display courses page (School theme).
     */
    public function courses()
    {
        return view('themes.school.courses');
    }

    /**
     * Display course detail (School theme).
     */
    public function courseDetail($slug)
    {
        return view('themes.school.course-detail', ['slug' => $slug]);
    }

    /**
     * Handle admission request (School theme).
     */
    public function admission(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'phone' => 'required|string',
            'course' => 'required|string',
            'message' => 'nullable|string',
        ]);

        // Process admission logic here
        // ...

        return redirect()->back()->with('success', 'Admission application submitted!');
    }

    /**
     * Business theme - Home page.
     */
    public function business()
    {
        return view('themes.business.home');
    }

    /**
     * Business theme - About page.
     */
    public function businessAbout()
    {
        return view('themes.business.about');
    }

    /**
     * Business theme - Services page.
     */
    public function businessServices()
    {
        return view('themes.business.services');
    }

    /**
     * Business theme - Contact page.
     */
    public function businessContact()
    {
        return view('themes.business.contact');
    }

    /**
     * Modern theme - Home page.
     */
    public function modern()
    {
        return view('themes.modern.home');
    }

    /**
     * Modern theme - About page.
     */
    public function modernAbout()
    {
        return view('themes.modern.about');
    }

    /**
     * Modern theme - Services page.
     */
    public function modernServices()
    {
        return view('themes.modern.services');
    }

    /**
     * Modern theme - Contact page.
     */
    public function modernContact()
    {
        return view('themes.modern.contact');
    }

    /**
     * Creative theme - Home page.
     */
    public function creative()
    {
        return view('themes.creative.home');
    }

    /**
     * Creative theme - About page.
     */
    public function creativeAbout()
    {
        return view('themes.creative.about');
    }

    /**
     * Creative theme - Services page.
     */
    public function creativeServices()
    {
        return view('themes.creative.services');
    }

    /**
     * Creative theme - Contact page.
     */
    public function creativeContact()
    {
        return view('themes.creative.contact');
    }
}
<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use App\Models\Plan;
use App\Models\Testimonial;
use App\Models\Faq;
use App\Models\Contact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class LandingController extends Controller
{
    /**
     * Display the landing page.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $plans = Plan::where('is_active', true)
            ->ordered()
            ->get();
            
        $testimonials = Testimonial::where('is_active', true)
            ->where('is_featured', true)
            ->latest()
            ->limit(6)
            ->get();

        return view('landing.home', compact('plans', 'testimonials'));
    }

    /**
     * Display the pricing page.
     *
     * @return \Illuminate\View\View
     */
    public function pricing()
    {
        $plans = Plan::where('is_active', true)
            ->ordered()
            ->get();

        return view('landing.pricing', compact('plans'));
    }

    /**
     * Display the features page.
     *
     * @return \Illuminate\View\View
     */
    public function features()
    {
        return view('landing.features');
    }

    /**
     * Display the templates page.
     *
     * @return \Illuminate\View\View
     */
    public function templates()
    {
        return view('landing.templates');
    }

    /**
     * Display the about page.
     *
     * @return \Illuminate\View\View
     */
    public function about()
    {
        return view('landing.about');
    }

    /**
     * Display the contact page.
     *
     * @return \Illuminate\View\View
     */
    public function contact()
    {
        return view('landing.contact');
    }

    /**
     * Store a contact message.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function storeContact(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'message' => ['required', 'string', 'max:5000'],
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        Contact::create([
            'name' => $request->name,
            'email' => $request->email,
            'subject' => 'Contact from Landing Page',
            'message' => $request->message,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'status' => 'new',
        ]);

        return redirect()->route('landing.contact')
            ->with('success', 'Thank you for your message. We will get back to you soon.');
    }

    /**
     * Display the FAQ page.
     *
     * @return \Illuminate\View\View
     */
    public function faq()
    {
        $faqs = Faq::where('is_active', true)
            ->ordered()
            ->get();

        return view('landing.faq', compact('faqs'));
    }

    /**
     * Display the testimonials page.
     *
     * @return \Illuminate\View\View
     */
    public function testimonials()
    {
        $testimonials = Testimonial::where('is_active', true)
            ->latest()
            ->paginate(12);

        return view('landing.testimonials', compact('testimonials'));
    }
}
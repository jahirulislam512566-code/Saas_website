<?php

namespace App\Http\Controllers;

use App\Mail\ContactFormMail;
use App\Mail\QuoteRequestMail;
use App\Mail\MeetingBookingMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class ContactController extends Controller
{
    public function index()
    {
        return view('frontend.contact.index');
    }

    public function quote()
    {
        return view('frontend.contact.quote');
    }

    public function bookMeeting()
    {
        return view('frontend.contact.book-meeting');
    }

    public function submit(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'subject' => 'required|string|max:255',
            'message' => 'required|string|min:10',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // Send email notification
        Mail::to(config('mail.admin_email', 'admin@agencypro.com'))
            ->send(new ContactFormMail($request->all()));

        // Optional: Save to database
        // Contact::create($request->all());

        return redirect()->route('contact.success')
            ->with('success', 'Thank you for your message. We will get back to you soon!');
    }

    public function submitQuote(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'project_type' => 'required|string',
            'budget' => 'nullable|string',
            'description' => 'required|string|min:20',
            'timeline' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // Send email notification
        Mail::to(config('mail.admin_email', 'admin@agencypro.com'))
            ->send(new QuoteRequestMail($request->all()));

        return redirect()->route('contact.success')
            ->with('success', 'Your quote request has been received. We will contact you within 24 hours.');
    }

    public function submitMeeting(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'date' => 'required|date|after:today',
            'time' => 'required|string',
            'message' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // Send email notification
        Mail::to(config('mail.admin_email', 'admin@agencypro.com'))
            ->send(new MeetingBookingMail($request->all()));

        return redirect()->route('contact.success')
            ->with('success', 'Your meeting has been booked. We will send you a confirmation shortly.');
    }

    public function success()
    {
        return view('frontend.contact.success');
    }
}
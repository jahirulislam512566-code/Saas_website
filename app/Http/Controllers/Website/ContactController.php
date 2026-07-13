<?php
// app/Http/Controllers/Website/ContactController.php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use App\Models\ContactMessage;
use App\Mail\ContactMail;
use App\Mail\AutoReplyMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class ContactController extends Controller
{
    /**
     * Display the contact page.
     */
    public function index()
    {
        // Contact information
        $contactInfo = [
            'address' => '123 SaaS Street, San Francisco, CA 94105',
            'phone' => '+1 (555) 123-4567',
            'email' => 'support@saashub.com',
            'hours' => 'Mon-Fri: 9:00 AM - 6:00 PM EST',
            'social' => [
                'twitter' => 'https://twitter.com/saashub',
                'linkedin' => 'https://linkedin.com/company/saashub',
                'github' => 'https://github.com/saashub',
                'youtube' => 'https://youtube.com/saashub',
            ]
        ];

        // Contact form fields
        $formFields = [
            'name' => '',
            'email' => '',
            'subject' => '',
            'message' => '',
            'phone' => '',
            'company' => '',
        ];

        return view('website.contact', compact('contactInfo', 'formFields'));
    }

    /**
     * Store a new contact message.
     */
    public function store(Request $request)
    {
        // Validate the request
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'subject' => 'required|string|max:255',
            'message' => 'required|string|min:10',
            'phone' => 'nullable|string|max:20',
            'company' => 'nullable|string|max:255',
            'g-recaptcha-response' => 'sometimes|required|recaptcha',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Store the message
        $contact = ContactMessage::create([
            'name' => $request->name,
            'email' => $request->email,
            'subject' => $request->subject,
            'message' => $request->message,
            'phone' => $request->phone,
            'company' => $request->company,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'status' => 'new',
        ]);

        // Send email notifications
        try {
            // Send to admin
            Mail::to(config('mail.admin_email', 'admin@saashub.com'))
                ->send(new ContactMail($contact));

            // Send auto-reply to user
            Mail::to($contact->email)
                ->send(new AutoReplyMail($contact));

        } catch (\Exception $e) {
            // Log the error but don't fail the request
            \Log::error('Failed to send contact email: ' . $e->getMessage());
        }

        return redirect()->back()
            ->with('success', 'Thank you for your message! We will get back to you soon.');
    }

    /**
     * Display contact messages (for admin).
     */
    public function messages(Request $request)
    {
        $messages = ContactMessage::orderBy('created_at', 'desc')
            ->paginate(20);

        return view('admin.contact.messages', compact('messages'));
    }

    /**
     * Display a specific contact message (for admin).
     */
    public function showMessage($id)
    {
        $message = ContactMessage::findOrFail($id);

        // Mark as read
        if (!$message->is_read) {
            $message->update(['is_read' => true]);
        }

        return view('admin.contact.show', compact('message'));
    }

    /**
     * Update message status (for admin).
     */
    public function updateStatus(Request $request, $id)
    {
        $message = ContactMessage::findOrFail($id);

        $message->update([
            'status' => $request->status,
        ]);

        return redirect()->back()
            ->with('success', 'Message status updated successfully.');
    }

    /**
     * Delete a contact message (for admin).
     */
    public function destroy($id)
    {
        $message = ContactMessage::findOrFail($id);
        $message->delete();

        return redirect()->back()
            ->with('success', 'Message deleted successfully.');
    }
}
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\ContactFormSubmitted;

class ContactController extends Controller
{
    /**
     * Contact form देखाउने method
     */
    public function index()
    {
        return view('contact');
    }

    /**
     * Form submit handle गर्ने method
     */
    public function submit(Request $request)
    {
        // Validation rules apply गर्नुहोस्
        $data = $request->validate([
            'name'    => 'required|string|max:255',
            'email'   => 'required|email',
            'message' => 'required|string',
        ]);

        // Send email using Mailable (logged via MAIL_MAILER=log)
        Mail::to(config('mail.from.address'))
            ->send(new ContactFormSubmitted($data));

        // Success message सँग फर्कनुहोस्
        return back()->with('success', 'Your message has been sent (and logged) successfully!');
    }
}

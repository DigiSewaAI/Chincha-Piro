<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Mail\ContactFormSubmitted;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class ContactController extends Controller
{
    /**
     * Display the contact form and info.
     */
    public function index()
    {
        // ✅ $contactInfo variable with complete details
        $contactInfo = [
            'address' => 'काठमाडौँ-३२, टिन्कुने',
            'phone' => '०१-४११२४४८',
            'mobile' => '९८४६२१६७११',
            'email' => 'info@chinchapiro.com',  // ✅ email field added
            'business_hours' => 'घण्टा: १०:०० - २२:००'
        ];

        // ✅ Pass $contactInfo to view
        return view('contact.index', compact('contactInfo'));
    }

    /**
     * Handle contact form submission.
     */
    public function submit(Request $request)
    {
        try {
            // Validate form inputs including reCAPTCHA
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|max:255',
                'message' => 'required|string|min:10|max:1000',
                'g-recaptcha-response' => 'required|captcha', // Ensure reCAPTCHA is validated
            ], [
                // Custom Nepali validation messages
                'name.required' => 'कृपया आफ्नो नाम प्रविष्ट गर्नुहोस्।',
                'email.required' => 'कृपया आफ्नो इमेल प्रविष्ट गर्नुहोस्।',
                'message.required' => 'कृपया सन्देश प्रविष्ट गर्नुहोस्।',
                'g-recaptcha-response.required' => 'कृपया reCAPTCHA जाँच गर्नुहोस्।',
                'captcha.captcha' => 'reCAPTCHA प्रमाणीकरण असफल भयो।',
            ]);

            // Send email to admin
            Mail::to(config('mail.admin_address'))
                ->send(new ContactFormSubmitted($validated));

            return back()->with('success', 'सन्देश सफलतापूर्वक पठाइएको छ!');
        } catch (ValidationException $e) {
            return back()->withErrors($e->validator)->withInput();
        } catch (\Exception $e) {
            Log::error('Contact Form Error: ' . $e->getMessage());
            return back()->with('error', 'त्रुटि: सन्देश पठाउन सकिएन');
        }
    }
}

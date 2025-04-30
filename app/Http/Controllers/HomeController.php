<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // No middleware — Home page is public
    }

    /**
     * Show the application home page.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        // public/images/dishes/dish*.jpg फोल्डरभित्रका सबै JPG फाइलहरू खोज्छ
        $files = File::glob(public_path('images/dishes/dish*.jpg'));

        // प्रत्येक फाइलबाट आवश्यक जानकारी बनाएर कलेक्शन तयार पार्छ
        $signatureDishes = collect($files)->map(function ($fullPath) {
            $filename = basename($fullPath); // e.g. 'dish1.jpg'
            $name     = pathinfo($filename, PATHINFO_FILENAME); // 'dish1'

            // यहाँ तपाईं Description, Price, Spice_Level आदि DB वा अन्‍य स्रोतबाट लोड गर्न सक्नुहुन्छ।
            return [
                'id'           => $name,
                'name'         => ucfirst($name),     // Dish1 → Dish1
                'desc'         => '',                 // पछि सेट गर्नुहोस्
                'price'        => '',                 // पछि सेट गर्नुहोस्
                'spice_level'  => 0,                  // default
                'image'        => "images/dishes/{$filename}",
            ];
        })->all();

        // View मा पठाउँछ
        return view('home', compact('signatureDishes'));
    }
}

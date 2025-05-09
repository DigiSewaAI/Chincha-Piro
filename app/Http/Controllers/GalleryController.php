<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Gallery; // ✅ Gallery मोडेल आयात गरिएको

class GalleryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // ✅ Pagination थपिएको (10 वटा आइटम प्रति पृष्ठ)
        // ✅ latest() प्रयोग गरेर नयाँ आइटमहरू पहिले देखाउने
        $galleryItems = Gallery::latest()->paginate(10);

        // ✅ Blade Template मा $galleryItems प्रयोग गर्नुहोस्
        return view('gallery.index', compact('galleryItems'));
    }
}

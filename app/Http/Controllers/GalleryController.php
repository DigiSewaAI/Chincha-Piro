<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Gallery;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth; // Authentication import

class GalleryController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // Admin-only methods require authentication
        $this->middleware('auth')->only(['index', 'create', 'store', 'destroy']);
    }

    /**
     * Public gallery view
     *
     * @return \Illuminate\View\View
     */
    public function publicIndex()
    {
        // 12 items per page for public view
        $galleryItems = Gallery::latest()->paginate(12);
        return view('gallery.index', compact('galleryItems'));
    }

    /**
     * Admin gallery management view
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // 20 items per page for admin view
        $galleryItems = Gallery::latest()->paginate(20);
        return view('admin.gallery.index', compact('galleryItems'));
    }

    /**
     * Show upload form for admin
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        // Ensure user is admin before showing form
        if (!Auth::user()->isAdmin()) {
            return redirect()->route('home')->with('error', 'Unauthorized access');
        }

        return view('admin.gallery.create');
    }

    /**
     * Store new image/video
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        // Validate request
        $request->validate([
            'title' => 'required|string|max:255',
            'type' => 'required|in:image,video',
            'file' => [
                'required',
                'file',
                'mimes:jpg,jpeg,png,gif,mp4,mov,avi,wmv,flv,webm',
                'max:20480' // 20MB
            ],
        ]);

        // Upload file to public disk
        $path = $request->file('file')->store('gallery', 'public');

        // Create gallery entry
        Gallery::create([
            'title' => $request->title,
            'type' => $request->type,
            'file_path' => $path,
        ]);

        return redirect()->route('admin.gallery.index')->with('success', 'आइटम सफलतापूर्वक थपियो।');
    }

    /**
     * Delete gallery item
     *
     * @param  \App\Models\Gallery  $gallery
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Gallery $gallery)
    {
        // Delete physical file
        Storage::disk('public')->delete($gallery->file_path);

        // Delete DB record
        $gallery->delete();

        return redirect()->route('admin.gallery.index')->with('success', 'आइटम सफलतापूर्वक हटाइयो।');
    }
}

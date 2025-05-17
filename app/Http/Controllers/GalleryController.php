<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Gallery;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class GalleryController extends Controller
{
    /**
     * Constructor: Apply middleware
     */
    public function __construct()
    {
        // Only authenticated users can access these admin methods
        $this->middleware('auth')->only([
            'index', 'create', 'store', 'edit', 'update', 'destroy'
        ]);
    }

    /**
     * Public gallery page
     */
    public function publicIndex()
    {
        $galleryItems = Gallery::latest()->paginate(12);
        return view('gallery.index', compact('galleryItems'));
    }

    /**
     * Admin gallery management list
     */
    public function index()
    {
        $this->authorizeAdmin();
        $galleryItems = Gallery::latest()->paginate(20);
        return view('admin.gallery.index', compact('galleryItems'));
    }

    /**
     * Show upload form (admin only)
     */
    public function create()
    {
        $this->authorizeAdmin();
        return view('admin.gallery.create');
    }

    /**
     * Store new image or video
     */
    public function store(Request $request)
    {
        $this->authorizeAdmin();

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|in:image,video',
            'category' => 'required|in:dish,restaurant,video,other',
            'file' => [
                'required',
                'file',
                'mimes:jpg,jpeg,png,gif,mp4,mov,avi,wmv,flv,webm',
                'max:20480' // 20MB
            ],
        ]);

        // Store file in public disk
        $path = $request->file('file')->store('gallery', 'public');

        // Save to database
        Gallery::create([
            'title' => $request->title,
            'description' => $request->description,
            'type' => $request->type,
            'category' => $request->category,
            'image_path' => $path,
        ]);

        return redirect()->route('admin.gallery.index')->with('success', 'आइटम सफलतापूर्वक थपियो।');
    }

    /**
     * Edit gallery item
     */
    public function edit(Gallery $gallery)
    {
        $this->authorizeAdmin();
        return view('admin.gallery.edit', compact('gallery'));
    }

    /**
     * Update gallery item
     */
    public function update(Request $request, Gallery $gallery)
    {
        $this->authorizeAdmin();

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|in:image,video',
            'category' => 'required|in:dish,restaurant,video,other',
            'file' => [
                'nullable',
                'file',
                'mimes:jpg,jpeg,png,gif,mp4,mov,avi,wmv,flv,webm',
                'max:20480'
            ],
        ]);

        // Update file if new file is uploaded
        if ($request->hasFile('file')) {
            // Delete old file
            Storage::disk('public')->delete($gallery->image_path);

            // Store new file
            $gallery->image_path = $request->file('file')->store('gallery', 'public');
        }

        // Update other fields
        $gallery->update([
            'title' => $request->title,
            'description' => $request->description,
            'type' => $request->type,
            'category' => $request->category,
            'image_path' => $gallery->image_path,
        ]);

        return redirect()->route('admin.gallery.index')->with('success', 'आइटम सफलतापूर्वक अपडेट भयो।');
    }

    /**
     * Delete gallery item (admin only)
     */
    public function destroy(Gallery $gallery)
    {
        $this->authorizeAdmin();

        // Delete physical file
        Storage::disk('public')->delete($gallery->image_path);

        // Delete DB record
        $gallery->delete();

        return redirect()->route('admin.gallery.index')->with('success', 'आइटम सफलतापूर्वक हटाइयो।');
    }

    /**
     * Internal utility to restrict to admins only
     */
    private function authorizeAdmin()
    {
        if (!Auth::check() || !Auth::user()->isAdmin()) {
            abort(403, 'अनधिकृत पहुँच');
        }
    }
}

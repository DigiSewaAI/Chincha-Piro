<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Gallery;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class GalleryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->only([
            'index', 'create', 'store', 'edit', 'update', 'destroy', 'toggleStatus', 'markFeatured'
        ]);
    }

    /**
     * Public gallery view
     */
    public function publicIndex()
    {
        $galleries = Gallery::where('is_active', true)->latest()->paginate(12);
        return view('gallery.index', compact('galleries'));
    }

    /**
     * Admin gallery management view
     */
    public function index()
    {
        $galleries = Gallery::latest()->paginate(20);
        return view('admin.gallery.index', compact('galleries'));
    }

    /**
     * Show upload form for admin
     */
    public function create()
    {
        if (!Auth::user()->isAdmin()) {
            return redirect()->route('home')->with('error', 'अनधिकृत पहुँच');
        }

        return view('admin.gallery.create', [
            'gallery' => null,
            'categories' => Gallery::getCategoryOptions(),
            'types' => Gallery::getTypeOptions()
        ]);
    }

    /**
     * Show edit form for admin
     */
    public function edit(Gallery $gallery)
    {
        if (!Auth::user()->isAdmin()) {
            return redirect()->route('home')->with('error', 'अनधिकृत पहुँच');
        }

        return view('admin.gallery.create', [
            'gallery' => $gallery,
            'categories' => Gallery::getCategoryOptions(),
            'types' => Gallery::getTypeOptions()
        ]);
    }

    /**
     * Store new image/video
     */
    public function store(Request $request)
    {
        if (!Auth::user()->isAdmin()) {
            return redirect()->route('home')->with('error', 'अनधिकृत पहुँच');
        }

        $validated = $this->validateGallery($request);
        $path = $this->handleFileUpload($request);

        Gallery::create([
            'title' => $validated['title'],
            'category' => $validated['category'],
            'description' => $validated['description'] ?? null,
            'type' => $validated['type'],
            'image_path' => $path,
            'is_active' => $validated['is_active'],
            'featured' => $validated['featured'],
        ]);

        return redirect()->route('admin.gallery.index')->with('success', 'आइटम सफलतापूर्वक थपियो।');
    }

    /**
     * Update existing gallery item
     */
    public function update(Request $request, Gallery $gallery)
    {
        if (!Auth::user()->isAdmin()) {
            return redirect()->route('home')->with('error', 'अनधिकृत पहुँच');
        }

        $validated = $this->validateGallery($request, $gallery);
        $newPath = $this->handleFileUpload($request);

        $data = [
            'title' => $validated['title'],
            'category' => $validated['category'],
            'description' => $validated['description'] ?? null,
            'type' => $validated['type'],
            'is_active' => $validated['is_active'],
            'featured' => $validated['featured'],
        ];

        if ($newPath !== null) {
            if ($gallery->isPhoto() || $gallery->isLocalVideo()) {
                Storage::disk('public')->delete($gallery->image_path);
            }
            $data['image_path'] = $newPath;
        }

        $gallery->update($data);
        return redirect()->route('admin.gallery.index')->with('success', 'आइटम सफलतापूर्वक अपडेट गरियो।');
    }

    /**
     * Toggle gallery item status
     */
    public function toggleStatus(Gallery $gallery)
    {
        if (!Auth::user()->isAdmin()) {
            return redirect()->route('home')->with('error', 'अनधिकृत पहुँच');
        }

        $gallery->update(['is_active' => !$gallery->is_active]);
        return back()->with('success', 'स्टेटस सफलतापूर्वक परिवर्तन गरियो।');
    }

    /**
     * Mark gallery item as featured
     */
    public function markFeatured(Gallery $gallery)
    {
        if (!Auth::user()->isAdmin()) {
            return redirect()->route('home')->with('error', 'अनधिकृत पहुँच');
        }

        Gallery::where('category', $gallery->category)
            ->where('id', '!=', $gallery->id)
            ->update(['featured' => false]);

        $gallery->update(['featured' => true]);

        return back()->with('success', 'आइटमलाई सुविधाजनक रूपमा चिन्ह लगाइयो।');
    }

    /**
     * Delete gallery item
     */
    public function destroy(Gallery $gallery)
    {
        if (!Auth::user()->isAdmin()) {
            return redirect()->route('home')->with('error', 'अनधिकृत पहुँच');
        }

        if (($gallery->isPhoto() || $gallery->isLocalVideo()) && $gallery->image_path) {
            Storage::disk('public')->delete($gallery->image_path);
        }

        $gallery->delete();

        return redirect()->route('admin.gallery.index')->with('success', 'आइटम सफलतापूर्वक हटाइयो।');
    }

    /**
     * Validate gallery form data
     */
    private function validateGallery(Request $request, Gallery $gallery = null): array
    {
        $rules = [
            'title' => 'required|string|max:255',
            'category' => ['required', Rule::in(array_keys(Gallery::getCategoryOptions()))],
            'type' => ['required', Rule::in(array_keys(Gallery::getTypeOptions()))],
            'description' => 'nullable|string',
            'is_active' => 'required|boolean',
            'featured' => 'required|boolean',
        ];

        $type = $request->input('type');

        if ($type === 'photo') {
            $rules['file'] = [$gallery ? 'nullable' : 'required', 'file', 'mimes:jpg,jpeg,png,gif', 'max:20480'];
        } elseif ($type === 'local_video') {
            $rules['file'] = [$gallery ? 'nullable' : 'required', 'file', 'mimes:mp4,mov,ogg,qt', 'max:102400'];
        } elseif ($type === 'external_video') {
            $rules['video_url'] = [$gallery ? 'nullable' : 'required', 'url', 'regex:/^(https?\:\/\/)?(www\.)?(youtube\.com|youtu\.be)\/.+$/'];
        }

        return $request->validate($rules);
    }

    /**
     * Handle file upload or external video URL
     */
    private function handleFileUpload(Request $request): ?string
    {
        $type = $request->input('type');

        if (in_array($type, ['photo', 'local_video']) && $request->hasFile('file')) {
            return $request->file('file')->store('gallery', 'public');
        }

        if ($type === 'external_video') {
            return $request->input('video_url');
        }

        return null;
    }
}

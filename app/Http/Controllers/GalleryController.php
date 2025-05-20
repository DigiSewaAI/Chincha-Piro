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

        $rules = [
            'title' => 'required|string|max:255',
            'category' => ['required', Rule::in(array_keys(Gallery::getCategoryOptions()))],
            'type' => ['required', Rule::in(array_keys(Gallery::getTypeOptions()))],
            'is_active' => 'required|boolean',
            'featured' => 'required|boolean',
        ];

        if ($request->input('type') === 'photo') {
            $rules['file'] = [
                'required', 'file', 'mimes:jpg,jpeg,png,gif', 'max:20480'
            ];
        } else {
            $rules['video_url'] = [
                'required', 'url',
                'regex:/^(https?\:\/\/)?(www\.)?(youtube\.com|youtu\.?be)\/.+$/'
            ];
        }

        $request->validate($rules);

        $path = null;
        if ($request->input('type') === 'photo') {
            $path = $request->file('file')->store('gallery', 'public');
        } elseif ($request->input('type') === 'video') {
            $path = $request->input('video_url');
        }

        Gallery::create([
            'title' => $request->title,
            'category' => $request->category,
            'description' => $request->description,
            'type' => $request->type,
            'image_path' => $path,
            'is_active' => $request->boolean('is_active'),
            'featured' => $request->boolean('featured'),
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

        $rules = [
            'title' => 'required|string|max:255',
            'category' => ['required', Rule::in(array_keys(Gallery::getCategoryOptions()))],
            'type' => ['required', Rule::in(array_keys(Gallery::getTypeOptions()))],
            'is_active' => 'required|boolean',
            'featured' => 'required|boolean',
        ];

        if ($request->input('type') === 'photo') {
            $rules['file'] = [
                'nullable', 'file', 'mimes:jpg,jpeg,png,gif', 'max:20480'
            ];
        } else {
            $rules['video_url'] = [
                'nullable', 'url',
                'regex:/^(https?\:\/\/)?(www\.)?(youtube\.com|youtu\.?be)\/.+$/'
            ];
        }

        $request->validate($rules);

        $data = [
            'title' => $request->title,
            'category' => $request->category,
            'description' => $request->description,
            'type' => $request->type,
            'is_active' => $request->boolean('is_active'),
            'featured' => $request->boolean('featured'),
        ];

        if ($request->input('type') === 'photo' && $request->hasFile('file')) {
            if ($gallery->type === 'photo' && $gallery->image_path) {
                Storage::disk('public')->delete($gallery->image_path);
            }
            $data['image_path'] = $request->file('file')->store('gallery', 'public');
        } elseif ($request->input('type') === 'video') {
            $data['image_path'] = $request->input('video_url');
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

        if ($gallery->type === 'photo' && $gallery->image_path) {
            Storage::disk('public')->delete($gallery->image_path);
        }

        $gallery->delete();

        return redirect()->route('admin.gallery.index')->with('success', 'आइटम सफलतापूर्वक हटाइयो।');
    }
}

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
        // Only authenticated users can access these admin methods
        $this->middleware('auth')->only([
            'index', 'create', 'store', 'edit', 'update', 'destroy', 'toggleStatus', 'markFeatured'
        ]);
    }

    /**
     * Public gallery view
     */
    public function publicIndex()
    {
        $galleryItems = Gallery::where('is_active', true)->latest()->paginate(12);
        return view('gallery.index', compact('galleryItems'));
    }

    /**
     * Admin gallery management list
     */
    public function index()
    {
        $galleryItems = Gallery::latest()->paginate(20);
        return view('admin.gallery.index', compact('galleryItems'));
    }

    /**
     * Show upload form (admin only)
     */
    public function create()
    {
        $this->authorizeAdmin();
        return view('admin.gallery.create', [
            'gallery' => null,
            'categories' => Gallery::getCategoryOptions(),
            'types' => Gallery::getTypeOptions()
        ]);
    }

    /**
     * Store new image or video
     */
    public function store(Request $request)
    {
        $this->authorizeAdmin();

        $validated = $this->validateGallery($request);
        $path = $this->handleFileUpload($request);

        $data = [
            'title' => $validated['title'],
            'category' => $validated['category'],
            'description' => $validated['description'] ?? null,
            'type' => $validated['type'],
            'is_active' => $validated['is_active'],
            'featured' => $validated['featured'],
        ];

        if (in_array($validated['type'], ['photo', 'local_video'])) {
            $data['image_path'] = $path;
        } elseif ($validated['type'] === 'external_video') {
            $data['video_url'] = $path;
        }

        Gallery::create($data);
        return redirect()->route('admin.gallery.index')->with('success', 'आइटम सफलतापूर्वक थपियो।');
    }

    /**
     * Edit existing gallery item
     */
    public function edit(Gallery $gallery)
    {
        $this->authorizeAdmin();
        return view('admin.gallery.create', [
            'gallery' => $gallery,
            'categories' => Gallery::getCategoryOptions(),
            'types' => Gallery::getTypeOptions()
        ]);
    }

    /**
     * Update existing gallery item
     */
    public function update(Request $request, Gallery $gallery)
    {
        $this->authorizeAdmin();

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
            if (($gallery->isPhoto() || $gallery->isLocalVideo()) && $gallery->image_path) {
                Storage::disk('public')->delete($gallery->image_path);
            } elseif ($gallery->isExternalVideo() && $gallery->video_url) {
                // We don't delete external URLs, but we do clear the old one by updating the field
                // No physical file to delete
            }

            if (in_array($validated['type'], ['photo', 'local_video'])) {
                $data['image_path'] = $newPath;
                $data['video_url'] = null;
            } elseif ($validated['type'] === 'external_video') {
                $data['video_url'] = $newPath;
                $data['image_path'] = null;
            }
        }

        $gallery->update($data);
        return redirect()->route('admin.gallery.index')->with('success', 'आइटम सफलतापूर्वक अपडेट गरियो।');
    }

    /**
     * Toggle item status
     */
    public function toggleStatus(Gallery $gallery)
    {
        $this->authorizeAdmin();
        $gallery->update(['is_active' => !$gallery->is_active]);
        return back()->with('success', 'स्टेटस सफलतापूर्वक परिवर्तन गरियो।');
    }

    /**
     * Mark item as featured
     */
    public function markFeatured(Gallery $gallery)
    {
        $this->authorizeAdmin();

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
        $this->authorizeAdmin();

        if (($gallery->isPhoto() || $gallery->isLocalVideo()) && $gallery->image_path) {
            Storage::disk('public')->delete($gallery->image_path);
        }

        $gallery->delete();
        return redirect()->route('admin.gallery.index')->with('success', 'आइटम सफलतापूर्वक हटाइयो।');
    }

    /**
     * Validate gallery input
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
     * Handle file upload or video URL
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

    /**
     * Admin authorization check
     */
    private function authorizeAdmin()
    {
        if (!Auth::check() || !Auth::user()->isAdmin()) {
            abort(403, 'अनधिकृत पहुँच');
        }
    }
}

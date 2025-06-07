namespace App\Http\Controllers\Admin;

use App\Models\Gallery;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Controller;
use Illuminate\Validation\Rule;

class GalleryController extends Controller
{
    // Constructor for Middleware
    public function __construct()
    {
        $this->middleware('auth');
    }

    // Index with Search & Sort
    public function index(Request $request)
    {
        $query = Gallery::query();

        // Search Functionality
        if ($request->filled('search')) {
            $query->where('title', 'like', "%{$request->search}%")
                 ->orWhere('category', 'like', "%{$request->search}%");
        }

        // Sorting Functionality
        if ($request->filled('sort')) {
            $direction = $request->get('order', 'desc');
            $query->orderBy($request->sort, $direction);
        } else {
            $query->latest();
        }

        $galleries = $query->paginate(10)->appends([
            'search' => $request->search,
            'sort' => $request->sort,
            'order' => $request->order
        ]);

        return view('admin.gallery.index', compact('galleries'));
    }

    // Create Form
    public function create()
    {
        return view('admin.gallery.create');
    }

    // Store with Enhanced Validation
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => ['required', Rule::in(['photo', 'video'])],
            'file' => 'required_if:type,photo|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'video_url' => 'required_if:type,video|url'
        ]);

        try {
            $gallery = new Gallery();
            $gallery->title = $validated['title'];
            $gallery->category = $validated['category'];
            $gallery->description = $validated['description'];
            $gallery->type = $validated['type'];

<<<<<<< HEAD
            if ($validated['type'] === 'photo') {
                $path = $request->file('file')->store('gallery/photos', 'public');
                $gallery->file_path = $path;
            } else {
                $gallery->file_path = $validated['video_url'];
            }

            $gallery->save();

            return redirect()->route('admin.gallery.index')
                ->with('success', 'आइटम सफलतापूर्वक थपियो!');

        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'त्रुटि: ' . $e->getMessage());
=======
        if ($request->type === 'photo') {
            $path = $request->file('file')->store('gallery/photos', 'public');
            $gallery->image_path = $path;
        } else {
            $gallery->image_path = $request->video_url;
>>>>>>> bf0c141ad1cd9b5312caa672c6a6e68455c88f46
        }
    }

    // Edit Form
    public function edit(Gallery $gallery)
    {
        return view('admin.gallery.edit', compact('gallery'));
    }

    // Update with File Management
    public function update(Request $request, Gallery $gallery)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => ['required', Rule::in(['photo', 'video'])],
            'file' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048|required_if:type,photo',
            'video_url' => 'nullable|url|required_if:type,video'
        ]);

        try {
            $gallery->title = $validated['title'];
            $gallery->category = $validated['category'];
            $gallery->description = $validated['description'];

<<<<<<< HEAD
            if ($gallery->type === 'photo' && $request->hasFile('file')) {
                // Delete old file
                if ($gallery->file_path && Storage::disk('public')->exists($gallery->file_path)) {
                    Storage::disk('public')->delete($gallery->file_path);
                }

                // Store new file
                $path = $request->file('file')->store('gallery/photos', 'public');
                $gallery->file_path = $path;
            } elseif ($gallery->type === 'video' && $request->filled('video_url')) {
                $gallery->file_path = $validated['video_url'];
            }

            $gallery->save();

            return redirect()->route('admin.gallery.index')
                ->with('success', 'आइटम सफलतापूर्वक अपडेट गरियो!');

        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'त्रुटि: ' . $e->getMessage());
        }
    }

    // Soft Delete with Confirmation
    public function destroy(Gallery $gallery)
    {
        try {
            if ($gallery->type === 'photo' && $gallery->file_path) {
                Storage::disk('public')->delete($gallery->file_path);
            }

            $gallery->delete();

            return back()->with('success', 'आइटम सफलतापूर्वक मेटाइयो!');
=======
        if ($gallery->type === 'photo' && $request->hasFile('file')) {
            // पुरानो फाइल मेटाउनुहोस्
            if ($gallery->image_path && Storage::disk('public')->exists($gallery->image_path)) {
                Storage::disk('public')->delete($gallery->image_path);
            }

            // नयाँ फाइल राख्नुहोस्
            $path = $request->file('file')->store('gallery/photos', 'public');
            $gallery->image_path = $path;
        }

        if ($gallery->type === 'video' && $request->filled('video_url')) {
            $gallery->image_path = $request->video_url;
        }
>>>>>>> bf0c141ad1cd9b5312caa672c6a6e68455c88f46

        } catch (\Exception $e) {
            return back()->with('error', 'त्रुटि: ' . $e->getMessage());
        }
    }

    // Status Toggle
    public function toggleStatus(Gallery $gallery)
    {
        $gallery->status = !$gallery->status;
        $gallery->save();

        return back()->with('success', 'स्टेटस सफलतापूर्वक बदलियो!');
    }

    // Mark as Featured
    public function markFeatured(Gallery $gallery)
    {
<<<<<<< HEAD
        // Unmark all others
        Gallery::where('id', '!=', $gallery->id)
               ->where('featured', true)
               ->update(['featured' => false]);
=======
        if ($gallery->type === 'photo' && $gallery->image_path) {
            Storage::disk('public')->delete($gallery->image_path);
        }
>>>>>>> bf0c141ad1cd9b5312caa672c6a6e68455c88f46

        $gallery->featured = true;
        $gallery->save();

        return back()->with('success', 'फीचर्ड आइटम सफलतापूर्वक चयन गरियो!');
    }
}

namespace App\Http\Controllers\Admin;

use App\Models\Gallery;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Controller;

class GalleryController extends Controller
{
    // सूची देखाउने
    public function index()
    {
        $galleries = Gallery::latest()->paginate(10);
        return view('admin.gallery.index', compact('galleries'));
    }

    // नयाँ फार्म देखाउने
    public function create()
    {
        return view('admin.gallery.create');
    }

    // डाटा सेभ गर्ने
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|in:photo,video',
            'file' => 'required_if:type,photo|image|mimes:jpeg,png,jpg,gif|max:2048',
            'video_url' => 'required_if:type,video|url'
        ]);

        $gallery = new Gallery();
        $gallery->title = $request->title;
        $gallery->category = $request->category;
        $gallery->description = $request->description;
        $gallery->type = $request->type;

        if ($request->type === 'photo') {
            $path = $request->file('file')->store('gallery/photos', 'public');
            $gallery->image_path = $path;
        } else {
            $gallery->image_path = $request->video_url;
        }

        $gallery->save();

        return redirect()->route('admin.gallery.index')->with('success', 'आइटम सफलतापूर्वक थपियो!');
    }

    // सम्पादन फार्म देखाउने
    public function edit(Gallery $gallery)
    {
        return view('admin.gallery.edit', compact('gallery'));
    }

    // आइटम अपडेट गर्ने
    public function update(Request $request, Gallery $gallery)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'description' => 'nullable|string',
            'file' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'video_url' => 'nullable|url'
        ]);

        $gallery->title = $request->title;
        $gallery->category = $request->category;
        $gallery->description = $request->description;

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

        $gallery->save();

        return redirect()->route('admin.gallery.index')->with('success', 'आइटम सफलतापूर्वक अपडेट गरियो!');
    }

    // आइटम मेटाउने
    public function destroy(Gallery $gallery)
    {
        if ($gallery->type === 'photo' && $gallery->image_path) {
            Storage::disk('public')->delete($gallery->image_path);
        }

        $gallery->delete();

        return back()->with('success', 'आइटम सफलतापूर्वक मेटाइयो!');
    }
}

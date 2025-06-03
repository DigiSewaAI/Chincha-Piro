<?php

namespace App\Http\Controllers\Admin; // Fixed namespace

use App\Http\Controllers\Controller;
use App\Models\Menu;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class MenuController extends Controller
{
    public function index()
    {
        $menus = Menu::with('category')->latest()->paginate(15);
        return view('admin.menu.index', compact('menus'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('admin.menu.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'category_id' => 'required|exists:categories,id',
            'image' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'description' => 'nullable|string',
            'is_featured' => 'nullable|boolean',
            'status' => 'nullable|boolean',
        ]);

        $validated['status'] = $request->has('status') ? 1 : 0;
        $validated['is_featured'] = $request->has('is_featured') ? 1 : 0;

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = Str::slug(pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME))
                        . '_' . time() . '.' . $image->getClientOriginalExtension();
            $validated['image'] = $image->storeAs('menu_images', $imageName, 'public');
        }

        Menu::create($validated);

        return redirect()->route('admin.menu.index')->with('success', 'मेनु सफलतापूर्वक थपियो!');
    }

    public function edit(Menu $menu)
    {
        $categories = Category::all();
        return view('admin.menu.edit', compact('menu', 'categories'));
    }

    public function update(Request $request, Menu $menu)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'category_id' => 'required|exists:categories,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'description' => 'nullable|string',
            'is_featured' => 'nullable|boolean',
            'status' => 'nullable|boolean',
        ]);

        $validated['is_featured'] = $request->has('is_featured') ? 1 : 0;
        $validated['status'] = $request->has('status') ? 1 : 0;

        if ($request->hasFile('image')) {
            if ($menu->image && Storage::disk('public')->exists($menu->image)) {
                Storage::disk('public')->delete($menu->image);
            }

            $image = $request->file('image');
            $imageName = Str::slug(pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME))
                        . '_' . time() . '.' . $image->getClientOriginalExtension();
            $validated['image'] = $image->storeAs('menu_images', $imageName, 'public');
        }

        $menu->update($validated);

        return redirect()->route('admin.menu.index')->with('success', 'मेनु सफलतापूर्वक अपडेट भयो!');
    }

    public function destroy(Menu $menu)
    {
        if ($menu->orders()->exists()) {
            return redirect()->back()->with('error', 'असफल: मेनु आइटमले पहिले नै अर्डरहरू छन्, मेटाउन सकिँदैन।');
        }

        if ($menu->image && Storage::disk('public')->exists($menu->image)) {
            Storage::disk('public')->delete($menu->image);
        }

        $menu->delete();

        return redirect()->route('admin.menu.index')->with('success', 'मेनु सफलतापूर्वक मेटियो!');
    }
}

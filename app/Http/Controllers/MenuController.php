<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class MenuController extends Controller
{
    /**
     * Display a listing of the resource (Admin view).
     */
    public function index()
    {
        $menus = Menu::with('category')->latest()->paginate(15);
        return view('admin.menu.index', compact('menus'));
    }

    /**
     * Show the form for creating a new menu.
     */
    public function create()
    {
        $categories = Category::all();
        return view('admin.menu.create', compact('categories'));
    }

    /**
     * Store a newly created menu in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'category_id' => 'required|exists:categories,id',
            'image' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'description' => 'nullable|string',
        ]);

        $validated['is_featured'] = $request->has('is_featured');

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $cleanedName = Str::slug(pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME));
            $imageName = $cleanedName . '_' . time() . '.' . $image->getClientOriginalExtension();
            $validated['image'] = $image->storeAs('menu_images', $imageName, 'public');
        }

        Menu::create($validated);

        return redirect()->route('admin.menu.index')->with('success', 'मेनु सफलतापूर्वक थपियो!');
    }

    /**
     * Display the specified menu (Public detail view).
     */
    public function show(Menu $menu)
    {
        return view('menu.show', compact('menu'));
    }

    /**
     * Show the form for editing the specified menu.
     */
    public function edit(Menu $menu)
    {
        $categories = Category::all();
        return view('admin.menu.edit', compact('menu', 'categories'));
    }

    /**
     * Update the specified menu in storage.
     */
    public function update(Request $request, Menu $menu)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'category_id' => 'required|exists:categories,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'description' => 'nullable|string',
        ]);

        $validated['is_featured'] = $request->has('is_featured');

        if ($request->hasFile('image')) {
            if ($menu->image && Storage::disk('public')->exists($menu->image)) {
                Storage::disk('public')->delete($menu->image);
            }

            $image = $request->file('image');
            $cleanedName = Str::slug(pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME));
            $imageName = $cleanedName . '_' . time() . '.' . $image->getClientOriginalExtension();
            $validated['image'] = $image->storeAs('menu_images', $imageName, 'public');
        }

        $menu->update($validated);

        return redirect()->route('admin.menu.index')->with('success', 'मेनु सफलतापूर्वक अपडेट भयो!');
    }

    /**
     * Remove the specified menu from storage.
     */
    public function destroy(Menu $menu)
    {
        if ($menu->image && Storage::disk('public')->exists($menu->image)) {
            Storage::disk('public')->delete($menu->image);
        }

        $menu->delete();

        return redirect()->route('admin.menu.index')->with('success', 'मेनु सफलतापूर्वक मेटियो!');
    }

    /**
     * Public Menu View (menu listing).
     */
    public function publicMenu()
    {
        $menus = Menu::with('category')->paginate(12);
        $categories = Category::all();

        return view('menu.index', compact('menus', 'categories'));
    }
}

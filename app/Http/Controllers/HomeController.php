<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class HomeController extends Controller
{
    /**
     * Display the homepage with featured and random dishes.
     */
    public function index()
    {
        $featuredMenus = Menu::where('is_featured', true)->take(6)->get();
        $dishes = Menu::inRandomOrder()->take(6)->get();

        return view('home', compact('featuredMenus', 'dishes'));
    }

    /**
     * Show all menu items on the public menu page.
     */
    public function menuIndex()
    {
        $menus = Menu::with('category')->latest()->paginate(12);
        $categories = Category::all();

        return view('menu.index', compact('menus', 'categories'));
    }

    /**
     * Show detail of a single dish.
     */
    public function show(Menu $menu)
    {
        return view('menu.show', compact('menu'));
    }

    /**
     * Store a new dish.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'spice_level' => 'required|integer|min:0|max:5',
            'image' => 'required|string|max:255', // If you're storing path directly as string
            'is_featured' => 'nullable|boolean',
            'category_id' => 'required|exists:categories,id',
        ]);

        $validated['is_featured'] = $request->has('is_featured');

        Menu::create($validated);

        return back()->with('success', 'डिश सफलतापूर्वक थपियो!');
    }

    /**
     * Update an existing dish.
     */
    public function update(Request $request, Menu $menu)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'spice_level' => 'required|integer|min:0|max:5',
            'image' => 'required|string|max:255',
            'is_featured' => 'nullable|boolean',
            'category_id' => 'required|exists:categories,id',
        ]);

        $validated['is_featured'] = $request->has('is_featured');

        $menu->update($validated);

        return back()->with('success', 'डिश सफलतापूर्वक अपडेट भयो!');
    }

    /**
     * Delete a dish.
     */
    public function destroy(Menu $menu)
    {
        if ($menu->image && Storage::disk('public')->exists($menu->image)) {
            Storage::disk('public')->delete($menu->image);
        }

        $menu->delete();

        return back()->with('success', 'डिश सफलतापूर्वक हटाइयो!');
    }
}

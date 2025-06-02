<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Menu;
use App\Models\Category;

class HomeController extends Controller
{
    // Constructor
    public function __construct()
    {
        //
    }

    // होमपेज (Featured Dishes र Random Dishes)
    public function index()
    {
        $featuredMenus = Menu::where('is_featured', true)->take(6)->get();
        $dishes = Menu::inRandomOrder()->take(6)->get();

        return view('home', compact('featuredMenus', 'dishes'));
    }

    // सम्पूर्ण मेनु देखाउने
    public function menuIndex()
    {
        $menus = Menu::with('category')->latest()->paginate(12); // Menus with category
        $categories = Category::all(); // ✅ Fixed: यो लाइन थपिएको छ

        return view('menu.index', compact('menus', 'categories'));
    }

    // डिश विवरण
    public function show(Menu $menu)
    {
        return view('menu.show', compact('menu'));
    }

    // नयाँ डिश स्टोर गर्ने
    public function store(Request $request)
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

        Menu::create($validated);

        return back()->with('success', 'डिश सफलतापूर्वक थपियो!');
    }

    // डिश अपडेट गर्ने
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

        return back()->with('success', 'डिश अपडेट भयो!');
    }

    // डिश मेटाउने
    public function destroy(Menu $menu)
    {
        $menu->delete();

        return back()->with('success', 'डिश हटाइयो!');
    }
}

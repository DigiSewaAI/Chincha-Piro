<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Dish; // Dish मोडल आयात गरिएको

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // No middleware — Home page is public
    }

    /**
     * Show the application home page with dynamic dishes from the database.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        // 1. सबै डिशहरू डाटाबेसबाट लोड गर्नुहोस्, images सम्बन्धित छ भने
        $dishes = Dish::with('images')->get();

        // 2. View मा पठाउनुहोस्
        return view('home', compact('dishes'));
    }

    /**
     * Show a specific dish in detail.
     */
    public function show(Dish $dish)
    {
        return view('dishes.show', compact('dish'));
    }

    /**
     * Store a new dish via API or form submission (if applicable).
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'spice_level' => 'required|integer|min:0|max:5',
            'image' => 'required|string|max:255'
        ]);

        Dish::create($validated);

        return back()->with('success', 'डिश सफलतापूर्वक थपियो!');
    }

    /**
     * Update an existing dish.
     */
    public function update(Request $request, Dish $dish)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'spice_level' => 'required|integer|min:0|max:5',
            'image' => 'required|string|max:255'
        ]);

        $dish->update($validated);

        return back()->with('success', 'डिश अपडेट भयो!');
    }

    /**
     * Delete a dish.
     */
    public function destroy(Dish $dish)
    {
        $dish->delete();
        return back()->with('success', 'डिश हटाइयो!');
    }
}

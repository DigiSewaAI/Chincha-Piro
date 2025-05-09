<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Dish;

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
        // 1. सबै डिशहरू DB बाट लोड (single-image field प्रयोग)
        $dishes = Dish::all();

        // 2. View मा पठाउनुहोस्
        return view('home', compact('dishes'));
    }

    /**
     * Show a specific dish in detail.
     *
     * @param  \App\Models\Dish  $dish
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function show(Dish $dish)
    {
        return view('dishes.show', compact('dish'));
    }

    /**
     * Store a new dish via form submission.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:100',
            'description' => 'nullable|string',
            'price'       => 'required|numeric|min:0',
            'spice_level' => 'required|integer|min:0|max:5',
            'image'       => 'required|string|max:255', // filename only, e.g. "dish1.jpg"
        ]);

        Dish::create($validated);

        return back()->with('success', 'डिश सफलतापूर्वक थपियो!');
    }

    /**
     * Update an existing dish.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Dish  $dish
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Dish $dish)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:100',
            'description' => 'nullable|string',
            'price'       => 'required|numeric|min:0',
            'spice_level' => 'required|integer|min:0|max:5',
            'image'       => 'required|string|max:255',
        ]);

        $dish->update($validated);

        return back()->with('success', 'डिश अपडेट भयो!');
    }

    /**
     * Delete a dish.
     *
     * @param  \App\Models\Dish  $dish
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Dish $dish)
    {
        $dish->delete();
        return back()->with('success', 'डिश हटाइयो!');
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Dish;
use App\Models\Category; // Import Category model
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class DishController extends Controller
{
    /**
     * Show the form for creating a new dish.
     */
    public function create()
    {
        // Fetch all categories for the dropdown
        $categories = Category::all();
        return view('dishes.create', compact('categories'));
    }

    /**
     * Store a newly created dish in storage.
     */
    public function store(Request $request)
    {
        // Validate the request with detailed rules including category_id
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'description' => 'nullable|string|max:1000',
            'spice_level' => 'required|integer|between:1,5',
            'category_id' => 'required|exists:categories,id', // Ensure valid category ID
        ]);

        // Image upload handling
        if ($request->hasFile('image')) {
            try {
                $originalName = pathinfo($request->image->getClientOriginalName(), PATHINFO_FILENAME);
                $extension = strtolower($request->image->extension());
                $imageName = time() . '_' . Str::slug($originalName) . '_' . uniqid() . '.' . $extension;

                $directory = public_path('images/dishes');
                if (!File::exists($directory)) {
                    File::makeDirectory($directory, 0755, true);
                }

                $request->image->move($directory, $imageName);
                $validated['image'] = $imageName;
            } catch (\Exception $e) {
                Log::error('Image upload failed: ' . $e->getMessage());
                return back()->withErrors(['image' => 'तस्बिर अपलोड गर्दा त्रुटि भयो'])
                            ->withInput($request->except('image'));
            }
        } else {
            return back()->withErrors(['image' => 'तस्बिर अपलोड गर्न आवश्यक छ'])
                        ->withInput($request->except('image'));
        }

        // Add category_id to validated data
        $validated['category_id'] = $request->input('category_id');

        // Create dish
        Dish::create($validated);

        return Redirect::route('dishes.index')
               ->with('success', 'नयाँ पकवान सफलतापूर्वक थपियो!');
    }

    /**
     * Show the form for editing the specified dish.
     */
    public function edit(Dish $dish)
    {
        // Fetch all categories for the dropdown
        $categories = Category::all();
        return view('dishes.edit', compact('dish', 'categories'));
    }

    /**
     * Update the specified dish in storage.
     */
    public function update(Request $request, Dish $dish)
    {
        // Validate request including category_id
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'description' => 'nullable|string|max:1000',
            'spice_level' => 'required|integer|between:1,5',
            'category_id' => 'required|exists:categories,id',
        ]);

        // Handle image update if provided
        if ($request->hasFile('image')) {
            try {
                // Delete old image
                if ($dish->image && file_exists(public_path('images/dishes/' . $dish->image))) {
                    unlink(public_path('images/dishes/' . $dish->image));
                }

                // Generate new image name
                $originalName = pathinfo($request->image->getClientOriginalName(), PATHINFO_FILENAME);
                $extension = strtolower($request->image->extension());
                $imageName = time() . '_' . Str::slug($originalName) . '_' . uniqid() . '.' . $extension;

                // Ensure directory exists
                $directory = public_path('images/dishes');
                if (!File::exists($directory)) {
                    File::makeDirectory($directory, 0755, true);
                }

                // Move file
                $request->image->move($directory, $imageName);
                $validated['image'] = $imageName;
            } catch (\Exception $e) {
                Log::error('Image update failed: ' . $e->getMessage());
                return back()->withErrors(['image' => 'तस्बिर अपडेट गर्दा त्रुटि भयो'])
                            ->withInput($request->except('image'));
            }
        }

        // Add category_id to validated data
        $validated['category_id'] = $request->input('category_id');

        // Update dish
        $dish->update($validated);

        return Redirect::route('dishes.index')
               ->with('success', 'पकवान सफलतापूर्वक अपडेट भयो!');
    }

    /**
     * Display a listing of dishes.
     */
    public function index()
    {
        $dishes = Dish::latest()->paginate(10);
        return view('dishes.index', compact('dishes'));
    }

    /**
     * Display the specified dish.
     */
    public function show(Dish $dish)
    {
        return view('dishes.show', compact('dish'));
    }

    /**
     * Remove the specified dish from storage.
     */
    public function destroy(Dish $dish)
    {
        // Delete associated image if exists
        if ($dish->image && file_exists(public_path('images/dishes/' . $dish->image))) {
            try {
                unlink(public_path('images/dishes/' . $dish->image));
            } catch (\Exception $e) {
                Log::warning('Failed to delete image: ' . $e->getMessage());
            }
        }

        // Delete the dish record
        $dish->delete();

        return Redirect::route('dishes.index')
               ->with('success', 'पकवान सफलतापूर्वक मेटाइयो!');
    }
}

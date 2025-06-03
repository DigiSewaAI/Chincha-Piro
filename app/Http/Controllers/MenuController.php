<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\Category;
use Illuminate\Http\Request;

class MenuController extends Controller
{
    /**
     * Display a listing of active menus for the public (Frontend).
     */
    public function publicMenu()
    {
        // Fetch all categories with their active menus
        $categories = Category::with(['menus' => function ($query) {
            $query->where('status', true); // Only active menus
        }])->get();

        // Fetch all active menus with category relationship, ordered by latest
        $menus = Menu::with('category')
            ->where('status', true)
            ->latest()
            ->paginate(15);

        // Return view with data
        return view('menu.index', compact('categories', 'menus'));
    }

    /**
     * Display a single menu item for the public (Frontend).
     */
    public function show($id)
    {
        // Find the menu item by ID and ensure it's active
        $menuItem = Menu::with('category')
            ->where('id', $id)
            ->where('status', true)
            ->firstOrFail();

        // Return view with the menu item
        return view('menu.show', compact('menuItem'));
    }
}

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
        // Fetch all categories that have active menus
        $categories = Category::with(['menus' => function ($query) {
            $query->where('status', true); // Only active menus
        }])->get();

        // Fetch all active menus with their category (paginated)
        $menus = Menu::with('category')
            ->where('status', true)
            ->latest()
            ->paginate(15);

        // View: resources/views/menus/index.blade.php
        return view('menus.index', compact('categories', 'menus'));
    }

    /**
     * Display a single menu item for the public (Frontend).
     */
    public function show($id)
    {
        // Find active menu item by ID, or throw 404
        $menuItem = Menu::with('category')
            ->where('id', $id)
            ->where('status', true)
            ->firstOrFail();

        // View: resources/views/menus/show.blade.php
        return view('menus.show', compact('menuItem'));
    }
}

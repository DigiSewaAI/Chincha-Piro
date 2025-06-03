<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Menu;
use App\Models\Category;

class MenuController extends Controller
{
    /**
     * Display all menu items to the public.
     */
    public function publicMenu()
    {
        // Fetch categories and menus with pagination
        $categories = Category::all();
        $menus = Menu::with('category')->paginate(15); // ← `paginate()` प्रयोग गरिएको

        return view('menu.index', compact('categories', 'menus'));
    }

    /**
     * Display a single menu item.
     */
    public function show($menu)
    {
        $menuItem = Menu::findOrFail($menu);
        return view('menu.show', compact('menuItem'));
    }
}

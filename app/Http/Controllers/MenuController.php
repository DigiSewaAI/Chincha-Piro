<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Menu; // ✅ Model Import (Fix 1)

class MenuController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // ✅ Pagination थपिएको (Blade मा $dishes->links() प्रयोग गर्नुहोस्)
        $dishes = Menu::paginate(10); // $menuItems सट्टा $dishes प्रयोग (Fix 2)

        // ✅ Blade Template Path सही गरिएको (resources/views/menu/index.blade.php)
        return view('menu.index', compact('dishes')); // 'menuItems' सट्टा 'dishes' (Fix 3)
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        // ✅ 404 Error Handling (Item नभेटिएमा)
        $item = Menu::findOrFail($id); // $menuItem सट्टा $item (Fix 4)

        // ✅ Show Page Render गर्ने
        return view('menu.show', compact('item')); // Blade: resources/views/menu/show.blade.php
    }
}

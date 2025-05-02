<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;

class HomeController extends Controller
{
    public function index()
    {
        $categories = Category::all(); // Fetch all categories
        $categoryId = request()->query('category_id'); // Get category_id from query string

        $productsQuery = Product::with('category')
            ->where('status', 'active')
            ->whereNull('deleted_at')
            ->orderBy('created_at', 'desc');
        if ($categoryId && Category::where('id', $categoryId)->exists()) {
            $productsQuery->where('category_id', $categoryId);
        }

        $products = $productsQuery->latest()->paginate(12);
        return view('welcome', compact('categories', 'products'));
    }


}

<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;

class HomeController extends Controller
{
    public function index()
    {
        $categories = Category::all();
        $categoryId = request()->query('category_id');

        $productsQuery = Product::with('category')
            ->where('status', 'active')
            ->whereNull('deleted_at')
            ->orderBy('created_at', 'desc');

        if ($categoryId && Category::where('id', $categoryId)->exists()) {
            $productsQuery->where('category_id', $categoryId);
        }

        $products = $productsQuery->paginate(12);

        $products = $productsQuery->get(); // Removed ->latest() to avoid duplicate ordering
        return view('welcome', compact('categories', 'products'));
    }

}

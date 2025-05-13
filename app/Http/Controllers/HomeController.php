<?php
namespace App\Http\Controllers;
use App\Models\Product;
use App\Models\Category;

class HomeController extends Controller
{
    public function index()
    {
        $categories = Category::where('status', 'active')->get(); // Only fetch active categories
        $categoryId = request()->query('category'); // Changed to 'category' to match React component

        $productsQuery = Product::with('category')
            ->where('status', 'active')
            ->whereNull('deleted_at')
            ->orderBy('created_at', 'desc');

        if ($categoryId && Category::where('id', $categoryId)->where('status', 'active')->exists()) {
            // Include products from the selected category and its subcategories
            $subcategoryIds = Category::where('parent_cat_id', $categoryId)
                ->where('status', 'active')
                ->pluck('id')
                ->toArray();
            $categoryIds = array_merge([$categoryId], $subcategoryIds);
            $productsQuery->whereIn('category_id', $categoryIds);
        }

        $products = $productsQuery->paginate(12); // Keep only this for pagination

        return view('welcome', compact('categories', 'products'));
    }
}

<?php
namespace App\Http\Controllers;
use App\Models\Category;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::with(['children', 'products' => function ($query) {
            $query->where('status', 'active')->whereNull('deleted_at');
        }])
            ->whereNull('parent_cat_id')
            ->where('status', 'active')
            ->get();

        // Debug: Log the fetched data
        \Log::info('Fetched Categories:', $categories->toArray());

        return view('categories.index', ['categoriesJson' => $categories->toJson()]);
    }

    public function show($id)
    {
        $category = Category::with(['children', 'products' => function ($query) {
            $query->where('status', 'active')->whereNull('deleted_at');
        }])
            ->where('status', 'active')
            ->findOrFail($id);
        return view('categories.show', compact('category'));
    }
}

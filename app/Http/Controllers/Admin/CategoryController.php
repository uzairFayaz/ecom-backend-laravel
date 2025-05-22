<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Helpers\CategoryHelper;
use App\Models\Category;
use App\Rules\ValidParentCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class CategoryController extends Controller
{
    public function index(): View
    {
        $categories = Category::with('parent')->orderBy('category_name')->paginate(10); // Changed to 'category_name'
        return view('admin.categories.index', compact('categories'));
    }
    
  public function create()
{
    // Use CategoryHelper to get hierarchical category options
    $categoryOptions = CategoryHelper::getCategoryOptions();
    return view('admin.categories.create', compact('categoryOptions'));
}

      public function store(Request $request): RedirectResponse
{
    if (!Auth::check()) {
        return redirect()->route('login')->with('error', 'Please log in to manage categories.');
    }
     

    
    $request->validate([
        'category_name' => 'required|string|max:100',
        'parent_cat_id' => 'nullable|exists:categories,id',
        'status' => 'nullable',
    ], [
        'parent_cat_id.exists' => 'The selected parent category is invalid or not active.',
    ]);

    // Fallback: If parent_cat_id is invalid, set it to null


    Category::create([
        'category_name' => $request->category_name,
        'parent_cat_id' => $request->parent_cat_id,
        'status' => $request->status ?? 'active', // Default to 'active' if not provided
    ]);
    return redirect()->route('admin.categories.index')->with('success', 'Category created successfully.');
}

    public function edit(Category $category): View
    {
        $categoryOptions = CategoryHelper::getCategoryOptions();
        return view('admin.categories.edit',compact('category','categoryOptions'));
    }

    public function update(Request $request, Category $category): RedirectResponse
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Please log in to manage categories.');
        }
        $request->validate([
            'category_name' => 'required|string|max:100', // Changed to 'category_name'
            'parent_cat_id' => 'nullable|exists:categories,id', // Added for parent category
            'status' => 'nullable|in:active,inactive', // Nullable status
        ]);

        $category->update($request->only(['category_name', 'parent_cat_id', 'status']));
        return redirect()->route('admin.categories.index')->with('success', 'Category updated successfully.');
    }

    public function destroy(Category $category): RedirectResponse
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Please log in to manage categories.');
        }
        try {
            $category->delete();
            return redirect()->route('admin.categories.index')->with('success', 'Category deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->route('admin.categories.index')->with('error', 'Cannot delete category; it may have products or subcategories.');
        }
    }
    public function show(Category $category){

        return view('categories.show', compact('category'));
    }
}

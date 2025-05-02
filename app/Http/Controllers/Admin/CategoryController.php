<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
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

    public function create(): View
    {
        $categories = Category::where('status', 'active')->get(); // For parent category dropdown
        return view('admin.categories.create', compact('categories'));
    }

    public function store(Request $request): RedirectResponse
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Please log in to manage categories.');
        }
        $request->validate([
            'category_name' => 'required|string|max:100', // Changed to 'category_name'
            'parent_cat_id' => 'nullable|exists:categories,id', // Added for parent category
            'status' => 'nullable|in:active,inactive', // Nullable status
        ]);

        Category::create($request->only(['category_name', 'parent_cat_id', 'status']));
        return redirect()->route('admin.categories.index')->with('success', 'Category created successfully.');
    }

    public function edit(Category $category): View
    {
        $categories = Category::where('status', 'active')->where('id', '!=', $category->id)->get(); // Exclude self
        return view('admin.categories.edit', compact('category', 'categories'));
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

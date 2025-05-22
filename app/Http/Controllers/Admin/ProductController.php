<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\CategoryHelper;
use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Models\Cart;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with('category')->paginate(10);
        return view('admin.products.index', compact('products'));
    }

    public function create()
    {
        $categoryOptions = CategoryHelper::getCategoryOptions();
        return view('admin.categories.create',compact('categoryOptions'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|numeric|min:0',
            'status' => 'required|in:active,inactive',
            'stock_quantity' => 'required|integer|min:0',
            'description' => 'nullable|string',
        ]);

        Product::create($validated);
        return redirect()->route('admin.products.index')->with('success', 'Product created.');
    }

    public function edit(Product $product)
    {
       $categoryOptions = CategoryHelper::getCategoryOptions();
       return view('admin.products.edit',compact('product','categoryOptions'));
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'product_name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|numeric|min:0',
            'status' => 'required|in:active,inactive',
            'stock_quantity' => 'required|integer|min:0',
            'description' => 'nullable|string',
        ]);

        $product->update($validated);
        return redirect()->route('admin.products.index')->with('success', 'Product updated.');
    }

    public function destroy(Product $product): RedirectResponse
    {
        try {
            // Delete related cart items
            Cart::where('product_id', $product->id)->delete();
            // Add similar lines for other related tables if needed (e.g., Wishlist)

            // Permanently delete the product
            $product->forceDelete();

            return redirect()->route('admin.products.index')->with('success', 'Product permanently deleted successfully.');
        } catch (\Exception $e) {
            \Log::error('Failed to permanently delete product:', ['product_id' => $product->id, 'error' => $e->getMessage()]);
            return redirect()->route('admin.products.index')->with('error', 'Failed to permanently delete product: ' . $e->getMessage());
        }
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with('category', 'variants')
            ->where('status', 'active')
            ->orderBy('product_name')
            ->paginate(12);
        return view('products.index', compact('products'));
    }
  /*  public function index(Request $request)
    {
        $query = Product::where('status', 'active')->whereNull('deleted_at');

        if ($request->has('category')) {
            $categoryId = $request->query('category');
            // Include products from the selected category and its subcategories
            $subcategoryIds = Category::where('parent_cat_id', $categoryId)
                ->pluck('id')
                ->toArray();
            $categoryIds = array_merge([$categoryId], $subcategoryIds);
            $query->whereIn('category_id', $categoryIds);
        }

        $products = $query->paginate(12);
        return view('products.index', compact('products'));
    }*/

    public function show(Product $product)
    {

        if(Auth::user()){


            $product->load('category', 'variants');
            return view('products.show', compact('product'));
        }
        return view('products.show', compact('product'));

    }
}

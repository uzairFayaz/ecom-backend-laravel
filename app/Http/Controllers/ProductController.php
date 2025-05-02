<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with('category', 'variants')
            ->where('status', 'active')
            ->where('deleted_at')
            ->orderBy('product_name')
            ->paginate(12);
        return view('products.index', compact('products'));
    }

    public function show(Product $product)
    {

        if(Auth::user()){

            $product->load('category', 'variants')->whereNull('deleted_at');
            return view('products.show', compact('product'));
        }
        return view('products.show', compact('product'));

    }
}

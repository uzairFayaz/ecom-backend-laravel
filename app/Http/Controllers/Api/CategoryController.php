<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $categories = Category::with(['children', 'products' => function ($query) {
            $query
                ->where('status', 'active');
        }])->whereNull('parent_cat_id')
            ->where('status', 'active')
            ->get();



        return response()->json($categories);
    }

    public function getProducts(Request $request){
        $products = Product::all();
        return response()->json($products);
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function index()
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Please log in to view your cart.');
        }

        $items = Auth::user()->carts()->with(['product', 'variant'])->get();

        return view('cart.index', compact('items'));
    }

    public function add(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Please log in to add items to the cart.');
        }

        $request->validate([
            'product_id' => 'required|exists:products,id',
            'variant_id' => 'nullable|exists:product_variants,id',
        ]);

        $product = Product::findOrFail($request->product_id);
        $variant = $request->variant_id ? ProductVariant::find($request->variant_id) : null;

        // Validate stock
        if ($product->stock_quantity <= 0 || ($variant && $variant->stock_quantity <= 0)) {
            return redirect()->back()->with('error', 'Product is out of stock.');
        }

        $price = $product->price + ($variant ? $variant->additional_price : 0);

        Auth::user()->carts()->updateOrCreate(
            [
                'product_id' => $product->id,
                'variant_id' => $variant ? $variant->id : null,
            ],
            [
                'quantity' => \DB::raw('quantity + 1'),
                'price' => $price,
            ]
        );

        return redirect()->route('cart.index')->with('success', 'Product added to cart.');
    }

    public function remove($id)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Please log in to manage your cart.');
        }

        $cartItem = Auth::user()->carts()->findOrFail($id);
        $cartItem->delete();

        return redirect()->route('cart.index')->with('success', 'Item removed from cart.');
    }
}

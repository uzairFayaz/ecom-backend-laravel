<?php

namespace App\Http\Controllers;

use App\Models\Wishlist;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WishlistController extends Controller
{
    public function index()
    {
        $wishlistItems = Wishlist::with('products')->where('user_id', Auth::id())->get();
        return view('wishlist.index', compact('wishlistItems'));
    }

    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
        ]);

        Wishlist::firstOrCreate([
            'user_id' => Auth::id(),
            'product_id' => $request->product_id,
        ]);

        return redirect()->route('wishlist.index')->with('success', 'Item added to wishlist.');
    }

    public function remove($id)
    {
        $wishlistItem = Wishlist::where('user_id', Auth::id())->findOrFail($id);
        $wishlistItem->delete();
        return redirect()->route('wishlist.index')->with('success', 'Item removed from wishlist.');
    }
}

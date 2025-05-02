<?php
namespace App\Http\Controllers;
use App\Models\ShippingAddress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
class ShippingAddressController extends Controller {
    public function index() {
        $addresses = ShippingAddress::where('user_id', Auth::id())->get();
        return view('shipping_addresses.index', compact('addresses'));
    }
    public function create() {
        return view('shipping_addresses.create');
    }
    public function store(Request $request) {
        $request->validate([
            'full_address' => 'required|string',
            'state' => 'required|string|max:100',
            'city' => 'required|string|max:100',
            'zip_code' => 'required|string|max:20',
        ]);
        ShippingAddress::create([
            'user_id' => Auth::id(),
            'full_address' => $request->full_address,
            'state' => $request->state,
            'city' => $request->city,
            'zip_code' => $request->zip_code,
        ]);
        return redirect()->route('shipping_addresses.index')->with('success', 'Address added.');
    }
}

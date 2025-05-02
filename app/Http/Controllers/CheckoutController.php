<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Stripe\Checkout\Session;
use Stripe\Stripe;

class CheckoutController extends Controller
{
   /* public function __construct()
    {
        $this->middleware('auth');
    }*/
    protected $middleware = ['auth'];

    public function index()
    {
        $items = Cart::where('user_id', Auth::id())->with(['product', 'variant'])->get();
        if ($items->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }
        $total = $items->sum(fn($item) => $item->price * $item->quantity);
        return view('checkout.index', compact('items', 'total'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'shipping_address' => 'required|string|max:255',
            'billing_address' => 'required|string|max:255',
            'payment_type' => 'required|in:netbanking,upi,cod',
        ]);

        $items = Cart::where('user_id', Auth::id())->with(['product', 'variant'])->get();
        if ($items->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }

        $gross_amount = $items->sum(fn($item) => $item->price * $item->quantity);
        $discount_amount = 0; // Add discount logic if needed
        $shipping_amount = 10; // Example flat rate
        $net_amount = $gross_amount - $discount_amount + $shipping_amount;

        // Create order
        $order = Order::create([
            'order_number' => 'ORD-' . Str::random(10),
            'user_id' => Auth::id(),
            'total_amount' => $net_amount,
            'discount_amount' => $discount_amount,
            'gross_amount' => $gross_amount,
            'shipping_amount' => $shipping_amount,
            'net_amount' => $net_amount,
            'status' => 'placed',
            'payment_status' => $request->payment_type === 'cod' ? 'not paid' : 'pending',
            'payment_type' => $request->payment_type,
            'payment_transaction_id' => null,
        ]);

        // Create order items
        foreach ($items as $item) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $item->product_id,
                'product_variant_id' => $item->variant_id,
                'product_name' => $item->product->product_name,
                'color' => $item->variant ? $item->variant->name : null, // Adjust based on variant data
                'size' => null, // Add size logic if applicable
                'price' => $item->price,
                'quantity' => $item->quantity,
                'total_amount' => $item->price * $item->quantity,
            ]);
        }

        // Handle payment
        if ($request->payment_type === 'cod') {
            Cart::where('user_id', Auth::id())->delete();
            return redirect()->route('checkout.success', ['order' => $order->id]);
        }

        // Stripe payment
        Stripe::setApiKey(config('services.stripe.secret'));

        $lineItems = $items->map(function ($item) {
            return [
                'price_data' => [
                    'currency' => 'usd', // Change to 'inr' for India
                    'product_data' => [
                        'name' => $item->product->product_name . ($item->variant ? ' (' . $item->variant->name . ')' : ''),
                    ],
                    'unit_amount' => $item->price * 100,
                ],
                'quantity' => $item->quantity,
            ];
        })->toArray();

        // Add shipping as a line item
        $lineItems[] = [
            'price_data' => [
                'currency' => 'usd',
                'product_data' => ['name' => 'Shipping'],
                'unit_amount' => $shipping_amount * 100,
            ],
            'quantity' => 1,
        ];

        $session = Session::create([
            'payment_method_types' => ['card'], // Add 'upi' for UPI (Stripe India)
            'line_items' => $lineItems,
            'mode' => 'payment',
            'success_url' => route('checkout.success', ['order' => $order->id], true) . '?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => route('checkout.cancel', [], true),
            'metadata' => ['order_id' => $order->id],
        ]);

        // Clear cart
        Cart::where('user_id', Auth::id())->delete();

        return redirect($session->url);
    }

    public function success(Request $request, Order $order)
    {
        if ($request->has('session_id')) {
            Stripe::setApiKey(config('services.stripe.secret'));
            $session = Session::retrieve($request->get('session_id'));
            $order->update([
                'payment_status' => 'paid',
                'payment_transaction_id' => $session->payment_intent,
                'status' => 'processing',
            ]);
        }
        return view('checkout.success', compact('order'));
    }

    public function cancel()
    {
        return view('checkout.cancel');
    }
}

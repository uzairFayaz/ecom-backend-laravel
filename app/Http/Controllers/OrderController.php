<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderShippingAddress;
use App\Models\ShippingAddress;
use App\Models\Offer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Stripe\Checkout\Session;
use Stripe\Stripe;

class OrderController extends Controller
{
    public function checkout()
    {
        $cartItems = Cart::where('user_id', Auth::id())->with(['products', 'variant'])->get();
        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }
        $addresses = ShippingAddress::where('user_id', Auth::id())->get();
        $total = $cartItems->sum(fn($item) => ($item->variant ? $item->variant->price : $item->product->price) * $item->quantity);
        $shipping_amount = 10.00; // Example fixed shipping cost
        $net_amount = $total + $shipping_amount; // Initial net amount without discount
        return view('orders.checkout', compact('cartItems', 'addresses', 'total', 'shipping_amount', 'net_amount'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'shipping_address_id' => 'required|exists:shipping_addresses,id',
            'coupon_code' => 'nullable|exists:offers,coupon_code',
            'payment_type' => 'required|in:netbanking,upi,cod',
        ]);

        $cartItems = Cart::where('user_id', Auth::id())->with(['products', 'variant'])->get();
        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }

        $total = $cartItems->sum(fn($item) => ($item->variant ? $item->variant->price : $item->product->price) * $item->quantity);
        $shipping_amount = 10.00; // Example fixed shipping cost
        $discount_amount = 0;

        // Apply coupon if provided
        if ($request->coupon_code) {
            $offer = Offer::where('coupon_code', $request->coupon_code)
                ->where('status', 'active')
                ->where('start_date', '<=', now())
                ->where('end_date', '>=', now())
                ->first();
            if ($offer) {
                $discount_amount = $offer->discount_type === 'fixed'
                    ? $offer->discount_value
                    : ($total * $offer->discount_value / 100);
            }
        }

        $gross_amount = $total;
        $net_amount = $gross_amount - $discount_amount + $shipping_amount;

        // Handle payment based on payment_type
        if ($request->payment_type === 'cod') {
            // For Cash on Delivery, create order directly
            $order = $this->createOrder($cartItems, $request->shipping_address_id, $gross_amount, $discount_amount, $shipping_amount, $net_amount, 'cod', null);
            Cart::where('user_id', Auth::id())->delete();
            return redirect()->route('orders.show', $order)->with('success', 'Order placed successfully!');
        } else {
            // For online payments (netbanking, upi), use Stripe
            Stripe::setApiKey(config('services.stripe.secret'));

            $lineItems = [];
            foreach ($cartItems as $item) {
                $lineItems[] = [
                    'price_data' => [
                        'currency' => 'usd',
                        'product_data' => [
                            'name' => $item->product->product_name . ($item->variant ? " ({$item->variant->color} - {$item->variant->size})" : ''),
                        ],
                        'unit_amount' => intval(($item->variant ? $item->variant->price : $item->product->price) * 100),
                    ],
                    'quantity' => $item->quantity,
                ];
            }
            $lineItems[] = [
                'price_data' => [
                    'currency' => 'usd',
                    'product_data' => ['name' => 'Shipping'],
                    'unit_amount' => intval($shipping_amount * 100),
                ],
                'quantity' => 1,
            ];

            $session = Session::create([
                'payment_method_types' => ['card'],
                'line_items' => $lineItems,
                'mode' => 'payment',
                'success_url' => route('orders.complete', [], true) . '?session_id={CHECKOUT_SESSION_ID}',
                'cancel_url' => route('cart.index', [], true),
                'metadata' => [
                    'shipping_address_id' => $request->shipping_address_id,
                    'discount_amount' => $discount_amount,
                    'gross_amount' => $gross_amount,
                    'net_amount' => $net_amount,
                    'shipping_amount' => $shipping_amount,
                    'payment_type' => $request->payment_type,
                ],
            ]);

            return redirect($session->url);
        }
    }

    public function complete(Request $request)
    {
        Stripe::setApiKey(config('services.stripe.secret'));
        $session = Session::retrieve($request->get('session_id'));

        if ($session->payment_status === 'paid') {
            $cartItems = Cart::where('user_id', Auth::id())->with(['products', 'variant'])->get();
            $order = $this->createOrder(
                $cartItems,
                $session->metadata->shipping_address_id,
                $session->metadata->gross_amount,
                $session->metadata->discount_amount,
                $session->metadata->shipping_amount,
                $session->metadata->net_amount,
                $session->metadata->payment_type,
                $session->id
            );
            Cart::where('user_id', Auth::id())->delete();
            return redirect()->route('orders.show', $order)->with('success', 'Order placed successfully!');
        }

        return redirect()->route('cart.index')->with('error', 'Payment failed.');
    }

    protected function createOrder($cartItems, $shipping_address_id, $gross_amount, $discount_amount, $shipping_amount, $net_amount, $payment_type, $transaction_id)
    {
        $address = ShippingAddress::findOrFail($shipping_address_id);
        $order = Order::create([
            'order_number' => 'ORD-' . time(),
            'user_id' => Auth::id(),
            'total_amount' => $gross_amount,
            'discount_amount' => $discount_amount,
            'gross_amount' => $gross_amount,
            'shipping_amount' => $shipping_amount,
            'net_amount' => $net_amount,
            'status' => 'placed',
            'payment_status' => $payment_type === 'cod' ? 'not paid' : 'paid',
            'payment_type' => $payment_type,
            'payment_transaction_id' => $transaction_id,
        ]);

        foreach ($cartItems as $item) {
            $price = $item->variant ? $item->variant->price : $item->product->price;
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $item->product_id,
                'product_variant_id' => $item->product_variant_id,
                'product_name' => $item->product->product_name,
                'color' => $item->variant ? $item->variant->color : null,
                'size' => $item->variant ? $item->variant->size : null,
                'price' => $price,
                'quantity' => $item->quantity,
                'total_amount' => $price * $item->quantity,
            ]);
            $stock = $item->variant ? $item->variant : $item->product;
            $stock->decrement('stock_quantity', $item->quantity);
        }

        OrderShippingAddress::create([
            'order_id' => $order->id,
            'shipping_address_id' => $address->id,
            'full_address' => $address->full_address,
            'state' => $address->state,
            'city' => $address->city,
            'zip_code' => $address->zip_code,
        ]);

        return $order;
    }

    public function show(Order $order)
    {
        $this->authorize('view', $order);
        return view('orders.show', compact('order'));
    }
    public function index(){
        $orders = Auth::user()->orders()->with('items.product')->latest()->get();
        return view('order.index','orders');
    }
}

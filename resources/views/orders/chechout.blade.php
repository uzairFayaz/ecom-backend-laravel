@extends('layouts.app')
@section('content')
    <div class="container">
        <h1>Checkout</h1>
        <div class="row">
            <div class="col-md-6">
                <h3>Cart Summary</h3>
                <table class="table">
                    <thead>
                    <tr>
                        <th>Product</th>
                        <th>Variant</th>
                        <th>Price</th>
                        <th>Quantity</th>
                        <th>Total</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($cartItems as $item)
                        <tr>
                            <td>{{ $item->product->product_name }}</td>
                            <td>{{ $item->variant ? "{$item->variant->color} - {$item->variant->size}" : 'N/A' }}</td>
                            <td>${{ number_format($item->variant ? $item->variant->price : $item->product->price, 2) }}</td>
                            <td>{{ $item->quantity }}</td>
                            <td>${{ number_format(($item->variant ? $item->variant->price : $item->product->price) * $item->quantity, 2) }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
                <h4>Subtotal: ${{ number_format($total, 2) }}</h4>
                <h4>Shipping: ${{ number_format($shipping_amount, 2) }}</h4>
                <h4>Total: ${{ number_format($net_amount, 2) }}</h4>
            </div>
            <div class="col-md-6">
                <h3>Shipping Address</h3>
                <form action="{{ route('orders.store') }}" method="POST">
                    @csrf
                    <div class="form-group mb-3">
                        <label for="shipping_address_id">Select Address</label>
                        <select name="shipping_address_id" class="form-control" required>
                            @foreach ($addresses as $address)
                                <option value="{{ $address->id }}">{{ $address->full_address }}, {{ $address->city }}, {{ $address->state }}</option>
                            @endforeach
                        </select>
                        <a href="{{ route('shipping_addresses.create') }}">Add New Address</a>
                    </div>
                    <div class="form-group mb-3">
                        <label for="coupon_code">Coupon Code</label>
                        <input type="text" name="coupon_code" class="form-control">
                    </div>
                    <button type="submit" class="btn btn-success">Proceed to Payment</button>
                </form>
            </div>
        </div>
    </div>
@endsection

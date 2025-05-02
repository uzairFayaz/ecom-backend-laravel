<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Checkout') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h1>Checkout</h1>
                    <form action="{{ route('checkout.store') }}" method="POST">
                        @csrf
                        <div class="mt-4">
                            <label for="shipping_address" class="block text-sm font-medium text-gray-700">Shipping Address</label>
                            <input type="text" name="shipping_address" id="shipping_address" value="{{ old('shipping_address') }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                            @error('shipping_address')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="mt-4">
                            <label for="billing_address" class="block text-sm font-medium text-gray-700">Billing Address</label>
                            <input type="text" name="billing_address" id="billing_address" value="{{ old('billing_address') }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                            @error('billing_address')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="mt-6">
                            <h2>Order Summary</h2>
                            <table class="w-full table-auto">
                                <thead>
                                <tr>
                                    <th class="px-4 py-2">Product</th>
                                    <th class="px-4 py-2">Variant</th>
                                    <th class="px-4 py-2">Price</th>
                                    <th class="px-4 py-2">Quantity</th>
                                    <th class="px-4 py-2">Total</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach ($items as $item)
                                    <tr>
                                        <td class="border px-4 py-2">{{ $item->product->product_name }}</td>
                                        <td class="border px-4 py-2">{{ $item->variant ? $item->variant->name : 'N/A' }}</td>
                                        <td class="border px-4 py-2">${{ number_format($item->price, 2) }}</td>
                                        <td class="border px-4 py-2">{{ $item->quantity }}</td>
                                        <td class="border px-4 py-2">${{ number_format($item->price * $item->quantity, 2) }}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                            <p class="mt-4">Total: ${{ number_format($total, 2) }}</p>
                        </div>
                        <div class="mt-6">
                            <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded">Proceed to Payment</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

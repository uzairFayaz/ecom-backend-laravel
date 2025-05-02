<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Shopping Cart') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h1>Your Cart</h1>
                    @if (session('success'))
                        <p class="text-green-600">{{ session('success') }}</p>
                    @endif
                    @if (session('error'))
                        <p class="text-red-600">{{ session('error') }}</p>
                    @endif
                    @if ($items->isEmpty())
                        <p>Your cart is empty.</p>
                        <a href="{{ route('products.index') }}" class="text-indigo-600 hover:text-indigo-800">Continue Shopping</a>
                    @else
                        <table class="w-full table-auto">
                            <thead>
                            <tr>
                                <th class="px-4 py-2">Image</th>
                                <th class="px-4 py-2">Product</th>
                                <th class="px-4 py-2">Variant</th>
                                <th class="px-4 py-2">Price</th>
                                <th class="px-4 py-2">Quantity</th>
                                <th class="px-4 py-2">Total</th>
                                <th class="px-4 py-2">Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($items as $item)
                                <tr>
                                    <td class="border px-4 py-2">
                                        <img src="{{ $item->product->image_url }}" alt="{{ $item->product->product_name }}" class="h-16 w-16 object-cover">
                                    </td>
                                    <td class="border px-4 py-2">{{ $item->product->product_name }}</td>
                                    <td class="border px-4 py-2">{{ $item->variant ? $item->variant->name : 'N/A' }}</td>
                                    <td class="border px-4 py-2">${{ number_format($item->price, 2) }}</td>
                                    <td class="border px-4 py-2">{{ $item->quantity }}</td>
                                    <td class="border px-4 py-2">${{ number_format($item->price * $item->quantity, 2) }}</td>
                                    <td class="border px-4 py-2">
                                        <form action="{{ route('cart.remove', $item->id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-800" onclick="return confirm('Remove this item?')">Remove</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                            <div class="mt-6">
                                <p>Total: ${{ number_format($items->sum(fn($item) => $item->price * $item->quantity), 2) }}</p>
                                <a href="{{ route('checkout.index') }}" class="mt-4 inline-block bg-indigo-600 text-white px-4 py-2 rounded">Proceed to Checkout</a>
                                <a href="{{ route('products.index') }}" class="mt-4 inline-block text-indigo-600 hover:text-indigo-800">Continue Shopping</a>
                            </div>
                            </tbody>
                        </table>
                        <div class="mt-6">
                            <p>Total: ${{ number_format($items->sum(fn($item) => $item->price * $item->quantity), 2) }}</p>
                            <a href="{{ route('products.index') }}" class="mt-4 inline-block text-indigo-600 hover:text-indigo-800">Continue Shopping</a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

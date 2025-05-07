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
                    <h1>Shopping Cart</h1>
                    @forelse ($items as $cart)
                        @if ($cart->product)
                            <div class="border p-4 rounded-lg mb-4">
                                <div class="flex items-center">
                                    <img src="{{ $cart->product->image_url }}" alt="{{ $cart->product->product_name }}" class="h-16 w-16 object-cover mr-4">
                                    <div>
                                        <h2 class="text-lg font-semibold">{{ $cart->product->product_name }}</h2>
                                        <p>Quantity: {{ $cart->quantity }}</p>
                                        <p>Price: ${{ number_format($cart->price, 2) }}</p>
                                        @if ($cart->variant_id)
                                            <p>Variant: {{ $cart->variant->name }} (+${{ number_format($cart->variant->additional_price, 2) }})</p>
                                        @endif
                                    </div>
                                </div>
                                <form action="{{ route('cart.remove', $cart->id) }}" method="POST" class="mt-2">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-800">Remove</button>
                                </form>
                            </div>
                        @else
                            <div class="border p-4 rounded-lg mb-4 text-red-600">
                                <p>Product not available (ID: {{ $cart->product_id }}). Please remove this item.</p>
                                <form action="{{ route('cart.remove', $cart->id) }}" method="POST" class="mt-2">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-800">Remove</button>
                                </form>
                            </div>
                        @endif
                    @empty
                        <p class="text-center text-gray-500">Your cart is empty.</p>
                    @endforelse
                    @if ($items->isNotEmpty())
                        <div class="mt-6">
                            <a href="{{ route('checkout.index') }}" class="bg-indigo-600 text-white px-4 py-2 rounded">Proceed to Checkout</a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

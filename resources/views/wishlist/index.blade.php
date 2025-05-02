<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Wishlist') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h1>Your Wishlist</h1>
                    @if ($wishlistItems->isEmpty())
                        <p>Your wishlist is empty.</p>
                        <a href="{{ route('products.index') }}" class="text-indigo-600 hover:text-indigo-800">Browse Products</a>
                    @else
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                            @foreach ($wishlistItems as $item)
                                <div class="border p-4 rounded-lg">
                                    <a href="{{ route('products.show', $item->product) }}">
                                        <h2 class="text-lg font-semibold">{{ $item->product->name }}</h2>
                                    </a>
                                    <p>Price: ${{ number_format($item->product->price, 2) }}</p>
                                    <form action="{{ route('wishlist.remove', $item->id) }}" method="POST" class="mt-2">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-800">Remove</button>
                                    </form>
                                    <form action="{{ route('cart.add') }}" method="POST" class="mt-2">
                                        @csrf
                                        <input type="hidden" name="product_id" value="{{ $item->product->id }}">
                                        <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded">Add to Cart</button>
                                    </form>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

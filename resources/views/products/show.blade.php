<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Product Details') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h1>{{ $product->product_name }}</h1>
                    <p>Category: {{ $product->category?->category_name ?? 'N/A' }}</p>
                    <p>Price: ${{ number_format($product->price, 2) }}</p>
                    <p>{{ $product->description ?? 'No description available' }}</p>
                    <form action="{{ route('cart.add', $product->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="text-indigo-600 hover:text-indigo-800">Add to Cart</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

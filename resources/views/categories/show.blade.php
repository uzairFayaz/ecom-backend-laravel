<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Category: ' . $category->category_name) }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h1>{{ $category->category_name }}</h1>
                    <p>{{ $category->products->count() }} Products</p>
                    @if ($category->products->isEmpty())
                    <p>No products in this category.</p>
                    <a href="{{ route('categories.index') }}" class="text-indigo-600 hover:text-indigo-800">Back to Categories</a>
                    @else
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mt-4">
                        @foreach ($category->products as $product)
                        <div class="border p-4 rounded-lg">
                            <h2 class="text-lg font-semibold">
                                <a href="{{ route('products.show', $product) }}" class="text-indigo-600 hover:text-indigo-800">
                                    {{ $product->product_name }}
                                </a>
                            </h2>
                            <p>${{ number_format($product->price, 2) }}</p>
                            <p>{{ Str::limit($product->description, 100) }}</p>
                            <a href="{{ route('cart.store', $product) }}" class="text-indigo-600 hover:text-indigo-800">Add to Cart</a>
                        </div>
                        @endforeach
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

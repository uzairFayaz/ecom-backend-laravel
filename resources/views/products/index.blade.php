<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Products') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h1>Products</h1>
                    @if ($products->isEmpty())
                        <p>No products available.</p>
                        <a href="{{ route('products.index') }}" class="text-indigo-600 hover:text-indigo-800">Refresh</a>
                    @else
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                            @foreach ($products as $product)
                                <div class="border p-4 rounded-lg">
                                    <a href="{{ route('products.show', $product->id) }}">
                                        @if ($product->image)
                                            <img src="{{ $product->image_url }}" alt="{{ $product->product_name }}" class="h-48 w-full object-cover mb-4">
                                        @else
                                            <img src="{{ asset('images/placeholder.jpg') }}" alt="Placeholder" class="h-48 w-full object-cover mb-4">
                                        @endif
                                        <h2 class="text-lg font-semibold">{{ $product->product_name }}</h2>
                                    </a>
                                    <p>Category: {{ $product->category ? $product->category->category_name : 'Uncategorized' }}</p>
                                    <p>Price: ${{ number_format($product->price, 2) }}</p>
                                    <p>Variants: {{ $product->variants ? $product->variants->count() : 0 }}</p>
                                    @if ($product->variants && $product->variants->isNotEmpty())
                                        <ul class="list-disc pl-5">
                                            @foreach ($product->variants as $variant)
                                                <li>{{ $variant->name }} (SKU: {{ $variant->sku }}, +${{ number_format($variant->additional_price, 2) }})</li>
                                            @endforeach
                                        </ul>
                                    @endif
                                    @if (Auth::check())
                                        <div class="mt-4">
                                            <form action="{{ route('cart.add') }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="product_id" value="{{ $product->id }}">
                                                @if ($product->variants && $product->variants->isNotEmpty())
                                                    <select name="variant_id" class="mt-2 block w-full border-gray-300 rounded-md shadow-sm">
                                                        <option value="">Select Variant</option>
                                                        @foreach ($product->variants as $variant)
                                                            <option value="{{ $variant->id }}">{{ $variant->name }} (+${{ number_format($variant->additional_price, 2) }})</option>
                                                        @endforeach
                                                    </select>
                                                    @error('variant_id')
                                                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                                    @enderror
                                                @endif
                                                <button type="submit" class="mt-2 bg-indigo-600 text-white px-4 py-2 rounded">Add to Cart</button>
                                            </form>
                                            <!-- Separate form for wishlist -->
                                            <form action="{{ route('wishlist.add') }}" method="POST" class="mt-2">
                                                @csrf
                                                <input type="hidden" name="product_id" value="{{ $product->id }}">
                                                <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded">Add to Wishlist</button>
                                            </form>
                                        </div>
                                    @else
                                        <p class="mt-4">
                                            <a href="{{ route('login') }}" class="text-indigo-600 hover:text-indigo-800">Log in</a> to add to cart.
                                        </p>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                        <div class="mt-6">
                            {{ $products->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

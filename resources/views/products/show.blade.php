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
                    @if ($product)
                        <h1 class="text-3xl font-bold mb-4">{{ $product->product_name ?? 'Unnamed Product' }}</h1>
                        <div class="flex flex-col md:flex-row gap-6">
                            <!-- Product Image -->
                            <div class="md:w-1/3">
                                @if ($product->image)
                                    <img src="{{ $product->image_url }}" alt="{{ $product->product_name }}" class="w-full h-64 object-cover rounded-lg">
                                @else
                                    <img src="{{ asset('images/placeholder.jpg') }}" alt="Placeholder" class="w-full h-64 object-cover rounded-lg">
                                @endif
                            </div>
                            <!-- Product Details -->
                            <div class="md:w-2/3">
                                <p class="text-gray-600 mb-2"><strong>Category:</strong> {{ $product->category?->category_name ?? 'N/A' }}</p>
                                <p class="text-gray-600 mb-2"><strong>Price:</strong> ${{ number_format($product->price ?? 0, 2) }}</p>
                                <p class="text-gray-600 mb-2"><strong>Stock Quantity:</strong> {{ $product->stock_quantity ?? 'N/A' }}</p>
                                <p class="text-gray-600 mb-4"><strong>Description:</strong> {{ $product->description ?? 'No description available' }}</p>
                                <!-- Variants -->
                                @if ($product->variants && $product->variants->isNotEmpty())
                                    <div class="mb-4">
                                        <p class="text-gray-600 font-semibold">Variants:</p>
                                        <ul class="list-disc pl-5">
                                            @foreach ($product->variants as $variant)
                                                <li>{{ $variant->name }} (SKU: {{ $variant->sku }}, +${{ number_format($variant->additional_price, 2) }})</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif
                                <!-- Add to Cart and Wishlist -->
                                @if (Auth::check())
                                    <div class="flex gap-4">
                                        <form action="{{ route('cart.add') }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                                            @if ($product->variants && $product->variants->isNotEmpty())
                                                <select name="variant_id" class="mb-2 block w-full border-gray-300 rounded-md shadow-sm">
                                                    <option value="">Select Variant</option>
                                                    @foreach ($product->variants as $variant)
                                                        <option value="{{ $variant->id }}">{{ $variant->name }} (+${{ number_format($variant->additional_price, 2) }})</option>
                                                    @endforeach
                                                </select>
                                                @error('variant_id')
                                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                                @enderror
                                            @endif
                                            <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700">Add to Cart</button>
                                        </form>
                                        <form action="{{ route('wishlist.add') }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                                            <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700">Add to Wishlist</button>
                                        </form>
                                    </div>
                                @else
                                    <p class="mt-4">
                                        <a href="{{ route('login') }}" class="text-indigo-600 hover:text-indigo-800">Log in</a> to add to cart or wishlist.
                                    </p>
                                @endif
                            </div>
                        </div>
                    @else
                        <p class="text-red-600">Product not found.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

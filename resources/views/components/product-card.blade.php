<div class="bg-white rounded-lg shadow-md p-4">
    @if ($product->image)
        <img src="{{ $product->image_url }}" alt="{{ $product->product_name }}" class="h-32 w-full object-cover mb-4 rounded">
    @else
        <img src="{{ asset('images/placeholder.jpg') }}" alt="Placeholder" class="h-32 w-full object-cover mb-4 rounded">
    @endif
    <h2 class="text-lg font-semibold">{{ $product->product_name }}</h2>
    <p class="text-gray-600">Category: {{ $product->category?->category_name ?? 'N/A' }}</p>
    <p class="text-gray-600">${{ number_format($product->price, 2) }}</p>
    @if ($product->variants->isNotEmpty())
        <p class="text-gray-600">Variants: {{ $product->variants->pluck('name')->join(', ') }}</p>
    @endif
    <div class="mt-4 flex space-x-2">
        <a href="{{ route('products.show', $product->id) }}" class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700">View Details</a>
        @auth
            <form action="{{ route('cart.add') }}" method="POST">
                @csrf
                <input type="hidden" name="product_id" value="{{ $product->id }}">
                @if ($product->variants->isNotEmpty())
                    <select name="variant_id" class="border rounded px-2 py-1">
                        @foreach ($product->variants as $variant)
                            <option value="{{ $variant->id }}">{{ $variant->name }} (+${{ number_format($variant->additional_price, 2) }})</option>
                        @endforeach
                    </select>
                @endif
                <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">Add to Cart</button>
            </form>
            <form action="{{ route('wishlist.add') }}" method="POST">
                @csrf
                <input type="hidden" name="product_id" value="{{ $product->id }}">
                <button type="submit" class="bg-gray-600 text-white px-4 py-2 rounded hover:bg-gray-700">Add to Wishlist</button>
            </form>
        @else
            <a href="{{ route('login') }}" class="bg-gray-600 text-white px-4 py-2 rounded hover:bg-gray-700">Log in to Add</a>
        @endauth
    </div>
</div>

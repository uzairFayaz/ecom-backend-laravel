<x-app-layout>
    <x-slot name="header">
        <div class="flex space-x-4 overflow-x-auto mt-0">
            <a href="{{ route('home') }}" class="bg-blue-500 rounded-full text-indigo-600 hover:text-indigo-800 px-3 py-2 {{ !request()->query('category_id') ? 'bg-gray-600' : '' }}">
                All Categories
            </a>
            @foreach ($categories as $category)
                <a href="{{ route('home', ['category_id' => $category->id]) }}" class="text-indigo-600 hover:text-indigo-800 px-3 py-2 rounded {{ request()->query('category_id') == $category->id ? 'bg-indigo-100' : '' }}">
                    {{ $category->category_name }}
                </a>
            @endforeach
        </div>
    </x-slot>

    <div class="py-12 pt-10-8 mt-10">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h1 class="text-2xl font-bold mb-6">Featured Products</h1>
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4">
                        @forelse ($products as $product)
                            <div class="border p-4 rounded shadow">
                                <h3 class="text-lg font-semibold">{{ $product->product_name }}</h3>
                                <p class="text-gray-600">{{ $product->category?->category_name ?? 'N/A' }}</p>
                                <p class="text-green-600 font-bold">${{ number_format($product->price, 2) }}</p>
                                <a href="{{ route('products.show', $product->id) }}" class="text-indigo-600 hover:text-indigo-800">View Details</a>
                            </div>
                        @empty
                            <p class="text-center text-gray-500">No products available.</p>
                        @endforelse
                    </div>
                    <div class="mt-6">
                        {{ $products->appends(request()->query())->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Categories') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h1>Browse Categories</h1>
                    @if ($categories->isEmpty())
                        <p>No categories available.</p>
                        <a href="{{ route('products.index') }}" class="text-indigo-600 hover:text-indigo-800">View All Products</a>
                    @else
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mt-4">
                            @foreach ($categories as $category)
                                <div class="border p-4 rounded-lg">
                                    <h2 class="text-lg font-semibold">
                                        <a href="{{ route('categories.show', $category) }}" class="text-indigo-600 hover:text-indigo-800">
                                            {{ $category->category_name }}
                                        </a>
                                    </h2>
                                    <p>{{ $category->products->count() }} Products</p>
                                    @if ($category->products->isNotEmpty())
                                        <ul class="list-disc pl-5 mt-2">
                                            @foreach ($category->products->take(3) as $product)
                                                <li>
                                                    <a href="{{ route('products.show', $product) }}" class="text-gray-600 hover:text-gray-800">
                                                        {{ $product->product_name }} (${{ number_format($product->price, 2) }})
                                                    </a>
                                                </li>
                                            @endforeach
                                        </ul>
                                        @if ($category->products->count() > 3)
                                            <a href="{{ route('categories.show', $category) }}" class="text-indigo-600 hover:text-indigo-800">View All</a>
                                        @endif
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

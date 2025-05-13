@php use Illuminate\Pagination\LengthAwarePaginator; @endphp

<x-app-layout>


    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="mb-6">
                        <!-- Mount the React component for categories -->
                        <div id="category-app"></div>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                        @forelse ($products as $product)
                            <x-product-card :product="$product" />
                        @empty
                            <p class="text-center text-gray-500 col-span-full">No products found.</p>
                        @endforelse
                    </div>
                    <div class="mt-6">
                        {{ $products->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Include Vite assets (CSS and JS) -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</x-app-layout>

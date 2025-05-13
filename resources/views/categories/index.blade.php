<x-app-layout>
    <div class="py-1">
        <div class="max-w-7xl mx-1 sm:px-2 lg:px-2">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-2 text-gray-900">
                    <h1 class="text-2xl font-semibold mb-4">Categories</h1>
                    <div
                        id="CategoriesApp"
                        data-categories="{{ $categoriesJson }}"
                    ></div>
                </div>
            </div>
        </div>
    </div>

    @viteReactRefresh
    @vite(['resources/css/app.css', 'resources/js/app.jsx'])
</x-app-layout>

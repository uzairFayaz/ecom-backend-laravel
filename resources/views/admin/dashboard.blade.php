<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Admin Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h1>Welcome to the Admin Dashboard</h1>
                    <p>Manage your e-commerce store:</p>
                    <ul class="list-disc pl-5 mt-4">
                        <li><a href="{{ route('admin.categories.index') }}" class="text-indigo-600 hover:text-indigo-800">Manage Categories</a></li>
                        <li><a href="{{ route('admin.products.index') }}" class="text-indigo-600 hover:text-indigo-800">Manage Products</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

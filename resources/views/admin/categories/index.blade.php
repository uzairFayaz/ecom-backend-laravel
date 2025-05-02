<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Manage Categories') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h1>Categories</h1>
                    @if (session('success'))
                        <p class="text-green-600">{{ session('success') }}</p>
                    @endif
                    @if (session('error'))
                        <p class="text-red-600">{{ session('error') }}</p>
                    @endif
                    @if (Auth::check())
                        <a href="{{ route('admin.categories.create') }}" class="mb-4 inline-block bg-indigo-600 text-white px-4 py-2 rounded">Add New Category</a>
                    @else
                        <p class="mb-4">Please <a href="{{ route('login') }}" class="text-indigo-600 hover:text-indigo-800">log in</a> to manage categories.</p>
                    @endif
                    @if ($categories->isEmpty())
                        <p>No categories available.</p>
                    @else
                        <table class="w-full table-auto">
                            <thead>
                            <tr>
                                <th class="px-4 py-2">Category Name</th> <!-- Changed to 'Category Name' -->
                                <th class="px-4 py-2">Parent Category</th> <!-- Added -->
                                <th class="px-4 py-2">Status</th>
                                <th class="px-4 py-2">Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($categories as $category)
                                <tr>
                                    <td class="border px-4 py-2">{{ $category->category_name }}</td> <!-- Changed to 'category_name' -->
                                    <td class="border px-4 py-2">{{ $category->parent ? $category->parent->category_name : 'None' }}</td> <!-- Added -->
                                    <td class="border px-4 py-2">{{ ucfirst($category->status ?? 'N/A') }}</td>
                                    <td class="border px-4 py-2">
                                        @if (Auth::check())
                                            <a href="{{ route('admin.categories.edit', $category) }}" class="text-indigo-600 hover:text-indigo-800">Edit</a>
                                            <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-800" onclick="return confirm('Are you sure?')">Delete</button>
                                            </form>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        <div class="mt-6">
                            {{ $categories->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

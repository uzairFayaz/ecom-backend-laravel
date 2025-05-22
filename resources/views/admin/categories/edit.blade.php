<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Category') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h1>Edit Category</h1>
                    @if (!Auth::check())
                        <p>Please <a href="{{ route('login') }}" class="text-indigo-600 hover:text-indigo-800">log in</a> to edit a category.</p>
                    @else
                        <form action="{{ route('admin.categories.update', $category) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="mb-4">
                                <label for="category_name" class="block text-sm font-medium text-gray-700">Category Name</label>
                                <input type="text" name="category_name" id="category_name" value="{{ old('category_name', $category->category_name) }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                @error('category_name')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                         <div class="mb-4">
                            <label for="parent_cat_id" class="block text-sm font-medium text-gray-700">Parent Category</label>
                            <select name="parent_cat_id" id="parent_cat_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                <option value="" {{ old('parent_cat_id', $category->parent_cat_id) == '' ? 'selected' : '' }}>None</option>
                                @foreach ($categoryOptions as $id => $name)
                                    <option value="{{ $id }}" 
                                            {{ old('parent_cat_id', $category->parent_cat_id) == $id ? 'selected' : '' }} 
                                            {{ $category->id == $id ? 'disabled' : '' }}>
                                        {{ $name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('parent_cat_id')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                                                    <div class="mb-4">
                                <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                                <select name="status" id="status" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                    <option value="" {{ old('status') == '' ? 'selected' : '' }}>Select Status</option>
                                    <option value="active" {{ old('status', $category->status) == 'active' ? 'selected' : '' }}>Active</option>
                                    <option value="inactive" {{ old('status', $category->status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                </select>
                                @error('status')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded">Update Category</button>
                            <a href="{{ route('admin.categories.index') }}" class="ml-2 text-gray-600 hover:text-gray-800">Cancel</a>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
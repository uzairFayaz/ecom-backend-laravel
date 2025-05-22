<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Product') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h1>Edit Product</h1>
                    @if (!Auth::check())
                        <p>Please <a href="{{ route('login') }}" class="text-indigo-600 hover:text-indigo-800">log in</a> to edit a product.</p>
                    @else
                        <form action="{{ route('admin.products.update', $product) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="mb-4">
                                <label for="product_name" class="block text-sm font-medium text-gray-700">Product Name</label>
                                <input type="text" name="product_name" id="product_name" value="{{ old('product_name', $product->product_name) }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                @error('product_name')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="mb-4">
                                <label for="price" class="block text-sm font-medium text-gray-700">Price</label>
                                <input type="number" name="price" id="price" step="0.01" value="{{ old('price', $product->price) }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                @error('price')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="mb-4">
                                <label for="category_id" class="block text-sm font-medium text-gray-700">Category</label>
                                <select name="category_id" id="category_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                    <option value="" {{ old('category_id', $product->category_id) == '' ? 'selected' : '' }}>Select Category</option>
                                    @foreach ($categoryOptions as $id => $name)
                                        <option value="{{ $id }}" {{ old('category_id', $product->category_id) == $id ? 'selected' : '' }}>{{ $name }}</option>
                                    @endforeach
                                </select>
                                @error('category_id')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="mb-4">
                                <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                                <select name="status" id="status" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                    <option value="" {{ old('status') == '' ? 'selected' : '' }}>Select Status</option>
                                    <option value="active" {{ old('status', $product->status) == 'active' ? 'selected' : '' }}>Active</option>
                                    <option value="inactive" {{ old('status', $product->status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                </select>
                                @error('status')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded">Update Product</button>
                            <a href="{{ route('admin.products.index') }}" class="ml-2 text-gray-600 hover:text-gray-800">Cancel</a>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
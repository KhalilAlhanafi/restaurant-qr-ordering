@extends('layouts.admin')

@section('title', 'Add Menu Item')

@section('content')
    <!-- Breadcrumb -->
    <div class="mb-6">
        <div class="flex items-center gap-2 text-sm text-gray-500">
            <a href="{{ route('admin.categories.index') }}" class="hover:text-amber-500 transition-colors">Categories</a>
            @if($preSelectedCategory)
                @php
                    $selectedCategory = $categories->firstWhere('id', $preSelectedCategory);
                @endphp
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
                <a href="{{ route('admin.categories.show', $preSelectedCategory) }}" class="hover:text-amber-500 transition-colors">{{ $selectedCategory ? $selectedCategory->name : 'Category' }}</a>
            @endif
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
            </svg>
            <span class="text-gray-400">Add Item</span>
        </div>
    </div>

    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-2xl text-white tracking-wider mb-1">Add Menu Item</h1>
        <p class="text-xs uppercase tracking-[0.2em] text-gray-500">Create a new menu item</p>
    </div>

    <!-- Form -->
    <div class="bg-[#1a1a1a] border border-gray-800 rounded-xl p-6 max-w-2xl">
        <form action="{{ route('admin.items.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            @if($preSelectedCategory)
                @php
                    $selectedCategory = $categories->firstWhere('id', $preSelectedCategory);
                @endphp
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-400 mb-2">Category</label>
                    <div class="w-full px-4 py-3 bg-[#252525] border border-gray-700 rounded-lg text-gray-300">
                        {{ $selectedCategory ? $selectedCategory->name : 'Unknown Category' }}
                    </div>
                    <input type="hidden" name="category_id" value="{{ $preSelectedCategory }}">
                </div>
            @else
                <div class="mb-6">
                    <label for="category_id" class="block text-sm font-medium text-gray-400 mb-2">Category <span class="text-amber-500">*</span></label>
                    <select name="category_id" id="category_id" required
                        class="w-full px-4 py-3 bg-[#0f0f0f] border border-gray-700 rounded-lg text-white focus:outline-none focus:border-amber-500/50 focus:ring-1 focus:ring-amber-500/50 transition-all">
                        <option value="" class="text-gray-500">Select Category</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }} class="text-white">
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('category_id')
                        <p class="text-red-400 text-sm mt-2">{{ $message }}</p>
                    @enderror
                </div>
            @endif

            <div class="mb-6">
                <label for="name" class="block text-sm font-medium text-gray-400 mb-2">Name <span class="text-amber-500">*</span></label>
                <input type="text" name="name" id="name" value="{{ old('name') }}" required
                    class="w-full px-4 py-3 bg-[#0f0f0f] border border-gray-700 rounded-lg text-white placeholder-gray-600 focus:outline-none focus:border-amber-500/50 focus:ring-1 focus:ring-amber-500/50 transition-all">
                @error('name')
                    <p class="text-red-400 text-sm mt-2">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6">
                <label for="description" class="block text-sm font-medium text-gray-400 mb-2">Description</label>
                <textarea name="description" id="description" rows="4"
                    class="w-full px-4 py-3 bg-[#0f0f0f] border border-gray-700 rounded-lg text-white placeholder-gray-600 focus:outline-none focus:border-amber-500/50 focus:ring-1 focus:ring-amber-500/50 transition-all resize-none">{{ old('description') }}</textarea>
                @error('description')
                    <p class="text-red-400 text-sm mt-2">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid grid-cols-2 gap-4 mb-6">
                <div>
                    <label for="price" class="block text-sm font-medium text-gray-400 mb-2">Price</label>
                    <input type="number" name="price" id="price" value="{{ old('price') }}" step="0.01" min="0"
                        class="w-full px-4 py-3 bg-[#0f0f0f] border border-gray-700 rounded-lg text-white placeholder-gray-600 focus:outline-none focus:border-amber-500/50 focus:ring-1 focus:ring-amber-500/50 transition-all">
                    <p class="text-xs text-gray-600 mt-1">Leave empty if price varies</p>
                    @error('price')
                        <p class="text-red-400 text-sm mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="preparation_time" class="block text-sm font-medium text-gray-400 mb-2">Prep Time (min)</label>
                    <input type="number" name="preparation_time" id="preparation_time" value="{{ old('preparation_time', 15) }}" min="1"
                        class="w-full px-4 py-3 bg-[#0f0f0f] border border-gray-700 rounded-lg text-white placeholder-gray-600 focus:outline-none focus:border-amber-500/50 focus:ring-1 focus:ring-amber-500/50 transition-all">
                    @error('preparation_time')
                        <p class="text-red-400 text-sm mt-2">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="mb-6">
                <label for="image" class="block text-sm font-medium text-gray-400 mb-2">Image</label>
                <input type="file" name="image" id="image" accept="image/*"
                    class="w-full px-4 py-3 bg-[#0f0f0f] border border-gray-700 rounded-lg text-white file:mr-4 file:py-2 file:px-4 file:rounded file:border-0 file:bg-amber-500 file:text-[#0a0a0a] file:font-semibold file:text-sm hover:file:bg-amber-600 transition-all">
                <p class="text-xs text-gray-600 mt-1">Max 2MB. JPEG, PNG, JPG, GIF</p>
                @error('image')
                    <p class="text-red-400 text-sm mt-2">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-8 space-y-3">
                <label class="flex items-center cursor-pointer">
                    <input type="checkbox" name="show_price" value="1" {{ old('show_price', true) ? 'checked' : '' }}
                        class="w-5 h-5 rounded border-gray-700 bg-[#0f0f0f] text-amber-500 focus:ring-amber-500/50 focus:ring-1">
                    <span class="ml-3 text-sm text-gray-300">Show Price to Customers</span>
                </label>
                <label class="flex items-center cursor-pointer">
                    <input type="checkbox" name="is_available" value="1" {{ old('is_available', true) ? 'checked' : '' }}
                        class="w-5 h-5 rounded border-gray-700 bg-[#0f0f0f] text-amber-500 focus:ring-amber-500/50 focus:ring-1">
                    <span class="ml-3 text-sm text-gray-300">Available for Ordering</span>
                </label>
            </div>

            <div class="flex items-center gap-4 pt-4 border-t border-gray-800">
                <button type="submit" class="inline-flex items-center px-6 py-3 bg-amber-500 hover:bg-amber-600 text-[#0a0a0a] font-semibold text-sm rounded-lg transition-all duration-200">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Create Item
                </button>
                <a href="{{ $preSelectedCategory ? route('admin.categories.show', $preSelectedCategory) : route('admin.categories.index') }}" class="text-gray-500 hover:text-gray-300 transition-colors">
                    Cancel
                </a>
            </div>
        </form>
    </div>
@endsection

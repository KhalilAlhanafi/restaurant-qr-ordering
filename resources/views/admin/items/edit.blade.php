@extends('layouts.admin')

@section('title', 'Edit Menu Item')

@section('content')
    <!-- Breadcrumb -->
    <div class="mb-6">
        <div class="flex items-center gap-2 text-sm text-gray-500">
            <a href="{{ route('admin.categories.index') }}" class="hover:text-amber-500 transition-colors">Categories</a>
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
            </svg>
            <a href="{{ route('admin.categories.show', $item->category) }}" class="hover:text-amber-500 transition-colors">{{ $item->category->name }}</a>
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
            </svg>
            <span class="text-gray-400">{{ $item->name }}</span>
        </div>
    </div>

    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-2xl text-white tracking-wider mb-1">Edit Menu Item</h1>
        <p class="text-xs uppercase tracking-[0.2em] text-gray-500">Update item details</p>
    </div>

    <!-- Form -->
    <div class="bg-[#1a1a1a] border border-gray-800 rounded-xl p-6 max-w-2xl">
        <form id="update-form" action="{{ route('admin.items.update', $item) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <!-- Category - Read Only (First Field) -->
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-400 mb-2">Category</label>
                <div class="w-full px-4 py-3 bg-[#252525] border border-gray-700 rounded-lg text-gray-300 flex items-center gap-3">
                    <svg class="w-5 h-5 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                    </svg>
                    {{ $item->category->name }}
                </div>
                <input type="hidden" name="category_id" value="{{ $item->category_id }}">
            </div>

            <div class="mb-6">
                <label for="name" class="block text-sm font-medium text-gray-400 mb-2">Name <span class="text-amber-500">*</span></label>
                <input type="text" name="name" id="name" value="{{ old('name', $item->name) }}" required
                    class="w-full px-4 py-3 bg-[#0f0f0f] border border-gray-700 rounded-lg text-white placeholder-gray-600 focus:outline-none focus:border-amber-500/50 focus:ring-1 focus:ring-amber-500/50 transition-all">
                @error('name')
                    <p class="text-red-400 text-sm mt-2">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6">
                <label for="description" class="block text-sm font-medium text-gray-400 mb-2">Description</label>
                <textarea name="description" id="description" rows="4"
                    class="w-full px-4 py-3 bg-[#0f0f0f] border border-gray-700 rounded-lg text-white placeholder-gray-600 focus:outline-none focus:border-amber-500/50 focus:ring-1 focus:ring-amber-500/50 transition-all resize-none">{{ old('description', $item->description) }}</textarea>
                @error('description')
                    <p class="text-red-400 text-sm mt-2">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid grid-cols-2 gap-4 mb-6">
                <div>
                    <label for="price" class="block text-sm font-medium text-gray-400 mb-2">Price <span class="text-amber-500">*</span></label>
                    <input type="number" name="price" id="price" value="{{ old('price', $item->price) }}" step="0.01" min="0" required
                        class="w-full px-4 py-3 bg-[#0f0f0f] border border-gray-700 rounded-lg text-white placeholder-gray-600 focus:outline-none focus:border-amber-500/50 focus:ring-1 focus:ring-amber-500/50 transition-all">
                    @error('price')
                        <p class="text-red-400 text-sm mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="preparation_time" class="block text-sm font-medium text-gray-400 mb-2">Prep Time (min)</label>
                    <input type="number" name="preparation_time" id="preparation_time" value="{{ old('preparation_time', $item->preparation_time) }}" min="1"
                        class="w-full px-4 py-3 bg-[#0f0f0f] border border-gray-700 rounded-lg text-white placeholder-gray-600 focus:outline-none focus:border-amber-500/50 focus:ring-1 focus:ring-amber-500/50 transition-all">
                    @error('preparation_time')
                        <p class="text-red-400 text-sm mt-2">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-400 mb-3">Current Image</label>
                @if($item->image)
                    <div class="mb-4">
                        <img src="{{ Storage::url($item->image) }}" alt="{{ $item->name }}" class="h-32 w-32 rounded-lg object-cover border border-gray-700">
                    </div>
                @else
                    <div class="h-32 w-32 rounded-lg bg-[#252525] border border-gray-700 flex items-center justify-center text-gray-600 mb-4">
                        <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                @endif
                <label for="image" class="block text-sm font-medium text-gray-400 mb-2">Replace Image</label>
                <input type="file" name="image" id="image" accept="image/*"
                    class="w-full px-4 py-3 bg-[#0f0f0f] border border-gray-700 rounded-lg text-white file:mr-4 file:py-2 file:px-4 file:rounded file:border-0 file:bg-amber-500 file:text-[#0a0a0a] file:font-semibold file:text-sm hover:file:bg-amber-600 transition-all">
                <p class="text-xs text-gray-600 mt-1">Leave empty to keep current image. Max 2MB.</p>
                @error('image')
                    <p class="text-red-400 text-sm mt-2">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-8 space-y-3">
                <label class="flex items-center cursor-pointer">
                    <input type="checkbox" name="show_price" value="1" {{ old('show_price', $item->show_price) ? 'checked' : '' }}
                        class="w-5 h-5 rounded border-gray-700 bg-[#0f0f0f] text-amber-500 focus:ring-amber-500/50 focus:ring-1">
                    <span class="ml-3 text-sm text-gray-300">Show Price to Customers</span>
                </label>
                <label class="flex items-center cursor-pointer">
                    <input type="checkbox" name="is_available" value="1" {{ old('is_available', $item->is_available) ? 'checked' : '' }}
                        class="w-5 h-5 rounded border-gray-700 bg-[#0f0f0f] text-amber-500 focus:ring-amber-500/50 focus:ring-1">
                    <span class="ml-3 text-sm text-gray-300">Available for Ordering</span>
                </label>
            </div>

            <div class="flex items-center justify-between pt-4 border-t border-gray-800">
                <div class="flex items-center gap-4">
                    <button type="submit" form="update-form" class="inline-flex items-center px-6 py-3 bg-amber-500 hover:bg-amber-600 text-[#0a0a0a] font-semibold text-sm rounded-lg transition-all duration-200">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Update Item
                    </button>
                    <a href="{{ route('admin.categories.show', $item->category) }}" class="text-gray-500 hover:text-gray-300 transition-colors">
                        Cancel
                    </a>
                </div>
            </div>
        </form>

        <!-- Delete Form - Separate from update form -->
        <form id="delete-form" action="{{ route('admin.items.destroy', $item) }}" method="POST" class="mt-4 pt-4 border-t border-gray-800 flex justify-end" onsubmit="return confirm('Are you sure you want to delete this item?')">
            @csrf
            @method('DELETE')
            <button type="submit" class="inline-flex items-center px-4 py-3 bg-red-500/10 hover:bg-red-500/20 text-red-400 hover:text-red-300 font-medium text-sm rounded-lg transition-all duration-200">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                </svg>
                Delete Item
            </button>
        </form>
    </div>
@endsection

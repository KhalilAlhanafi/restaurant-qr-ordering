@extends('layouts.admin')

@section('title', $category->name)

@section('content')
    <!-- Breadcrumb & Header -->
    <div class="mb-8">
        <div class="flex items-center gap-2 text-sm text-gray-500 mb-4">
            <a href="{{ route('admin.categories.index') }}" class="hover:text-amber-500 transition-colors">Categories</a>
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
            </svg>
            <span class="text-gray-400">{{ $category->name }}</span>
        </div>

        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
            <div class="flex items-center gap-4">
                <div class="w-14 h-14 bg-amber-500/10 rounded-xl flex items-center justify-center">
                    <svg class="w-7 h-7 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10">
                        </path>
                    </svg>
                </div>
                <div>
                    <h1 class="text-2xl text-white tracking-wider">{{ $category->name }}</h1>
                    <div class="flex items-center gap-3 mt-1">
                        <span class="text-xs uppercase tracking-wider {{ $category->is_active ? 'text-green-400' : 'text-red-400' }}">
                            {{ $category->is_active ? 'Active' : 'Inactive' }}
                        </span>
                        <span class="text-gray-600">•</span>
                        <span class="text-sm text-gray-500">{{ $category->items->count() }} {{ Str::plural('item', $category->items->count()) }}</span>
                    </div>
                </div>
            </div>

            <div class="flex items-center gap-3">
                <a href="{{ route('admin.items.create', ['category_id' => $category->id]) }}"
                    class="inline-flex items-center px-5 py-2.5 bg-amber-500 hover:bg-amber-600 text-[#0a0a0a] font-semibold text-sm rounded-lg transition-all duration-200 group">
                    <svg class="w-5 h-5 mr-2 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Add Item
                </a>
                <a href="{{ route('admin.categories.edit', $category) }}"
                    class="inline-flex items-center px-5 py-2.5 bg-[#1a1a1a] hover:bg-[#252525] border border-gray-800 text-gray-300 hover:text-white font-medium text-sm rounded-lg transition-all duration-200">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                    Edit Category
                </a>
            </div>
        </div>

        @if($category->description)
            <p class="mt-4 text-gray-400 max-w-2xl">{{ $category->description }}</p>
        @endif
    </div>

    <!-- Items Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4">
        @forelse($category->items as $item)
            <div class="group bg-[#1a1a1a] border border-gray-800 hover:border-amber-500/30 rounded-xl p-5 transition-all duration-300 hover:shadow-lg hover:shadow-amber-500/5">
                <div class="flex gap-4">
                    <!-- Image -->
                    <div class="shrink-0">
                        @if($item->image)
                            <img src="{{ Storage::url($item->image) }}" alt="{{ $item->name }}" class="w-20 h-20 rounded-lg object-cover">
                        @else
                            <div class="w-20 h-20 bg-[#252525] rounded-lg flex items-center justify-center">
                                <svg class="w-8 h-8 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                        @endif
                    </div>

                    <!-- Info -->
                    <div class="flex-1 min-w-0">
                        <div class="flex items-start justify-between gap-2">
                            <h3 class="text-base font-semibold text-white group-hover:text-amber-500 transition-colors truncate">
                                {{ $item->name }}
                            </h3>
                            <span class="px-2 py-0.5 text-[10px] uppercase tracking-wider rounded-full shrink-0 {{ $item->is_available ? 'bg-green-500/10 text-green-400 border border-green-500/20' : 'bg-red-500/10 text-red-400 border border-red-500/20' }}">
                                {{ $item->is_available ? 'Available' : 'Hidden' }}
                            </span>
                        </div>

                        @if($item->description)
                            <p class="text-sm text-gray-500 mt-1 line-clamp-2">{{ $item->description }}</p>
                        @endif

                        <div class="flex items-center justify-between mt-3">
                            <div class="flex items-center gap-3">
                                @if($item->show_price && $item->price)
                                    <span class="text-lg font-bold text-amber-500">${{ number_format($item->price, 2) }}</span>
                                @else
                                    <span class="text-sm text-gray-600 italic">Price hidden</span>
                                @endif
                                <span class="text-xs text-gray-500 flex items-center gap-1">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    {{ $item->preparation_time }} min
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="flex items-center gap-2 mt-4 pt-4 border-t border-gray-800">
                    <a href="{{ route('admin.items.edit', $item) }}"
                        class="flex-1 inline-flex items-center justify-center px-4 py-2 bg-[#252525] hover:bg-[#303030] text-gray-300 hover:text-white text-sm font-medium rounded-lg transition-all duration-200">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                        Edit
                    </a>
                    <form action="{{ route('admin.items.destroy', $item) }}" method="POST" class="shrink-0" onsubmit="return confirm('Delete this item?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                            class="inline-flex items-center justify-center w-10 h-10 bg-red-500/10 hover:bg-red-500/20 text-red-400 hover:text-red-300 rounded-lg transition-all duration-200">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                            </svg>
                        </button>
                    </form>
                </div>
            </div>
        @empty
            <div class="col-span-full">
                <div class="bg-[#1a1a1a] border border-gray-800 rounded-xl p-12 text-center">
                    <div class="w-16 h-16 bg-amber-500/10 rounded-2xl flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-white mb-2">No Items Yet</h3>
                    <p class="text-gray-500 mb-6">Add your first item to this category.</p>
                    <a href="{{ route('admin.items.create', ['category_id' => $category->id]) }}"
                        class="inline-flex items-center px-6 py-3 bg-amber-500 hover:bg-amber-600 text-[#0a0a0a] font-semibold text-sm rounded-lg transition-all duration-200">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        Add First Item
                    </a>
                </div>
            </div>
        @endforelse
    </div>
@endsection

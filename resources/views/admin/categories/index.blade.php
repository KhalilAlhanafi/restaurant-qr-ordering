@extends('layouts.admin')

@section('title', 'Categories & Items')

@section('content')
    <!-- Header Section -->
    <div class="mb-8">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl text-amber-500 tracking-wider mb-1">Categories & Items</h1>
                <p class="text-xs uppercase tracking-[0.2em] text-gray-500">Manage your menu structure</p>
            </div>
            <a href="{{ route('admin.categories.create') }}"
                class="inline-flex items-center px-6 py-3 bg-amber-500 hover:bg-amber-600 text-[#0a0a0a] font-semibold text-sm rounded-lg transition-all duration-200 group">
                <svg class="w-5 h-5 mr-2 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Add Category
            </a>
        </div>
    </div>

    <!-- Categories Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
        @forelse($categories as $category)
            <a href="{{ route('admin.categories.show', $category) }}"
                class="group relative bg-[#1a1a1a] border border-gray-800 hover:border-amber-500/50 rounded-xl p-6 transition-all duration-300 hover:shadow-lg hover:shadow-amber-500/10">
                <!-- Status Badge -->
                <div class="absolute top-4 right-4">
                    <span class="px-2 py-1 text-[10px] uppercase tracking-wider rounded-full {{ $category->is_active ? 'bg-green-500/10 text-green-400 border border-green-500/20' : 'bg-red-500/10 text-red-400 border border-red-500/20' }}">
                        {{ $category->is_active ? 'Active' : 'Inactive' }}
                    </span>
                </div>

                <!-- Icon -->
                <div class="w-12 h-12 bg-amber-500/10 rounded-xl flex items-center justify-center mb-4 group-hover:bg-amber-500/20 transition-colors">
                    <svg class="w-6 h-6 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10">
                        </path>
                    </svg>
                </div>

                <!-- Category Info -->
                <h3 class="text-lg font-semibold text-white mb-1 group-hover:text-amber-500 transition-colors">
                    {{ $category->name }}
                </h3>

                @if($category->description)
                    <p class="text-sm text-gray-500 mb-3 line-clamp-2">{{ $category->description }}</p>
                @else
                    <p class="text-sm text-gray-600 mb-3 italic">No description</p>
                @endif

                <!-- Item Count -->
                <div class="flex items-center justify-between pt-4 border-t border-gray-800">
                    <span class="text-sm text-gray-400">
                        {{ $category->items_count }} {{ Str::plural('item', $category->items_count) }}
                    </span>
                    <svg class="w-5 h-5 text-gray-600 group-hover:text-amber-500 group-hover:translate-x-1 transition-all"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7">
                        </path>
                    </svg>
                </div>
            </a>
        @empty
            <div class="col-span-full">
                <div class="bg-[#1a1a1a] border border-gray-800 rounded-xl p-12 text-center">
                    <div class="w-16 h-16 bg-amber-500/10 rounded-2xl flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10">
                            </path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-white mb-2">No Categories Yet</h3>
                    <p class="text-gray-500 mb-6">Create your first category to start organizing your menu items.</p>
                    <a href="{{ route('admin.categories.create') }}"
                        class="inline-flex items-center px-6 py-3 bg-amber-500 hover:bg-amber-600 text-[#0a0a0a] font-semibold text-sm rounded-lg transition-all duration-200">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        Add First Category
                    </a>
                </div>
            </div>
        @endforelse
    </div>
@endsection

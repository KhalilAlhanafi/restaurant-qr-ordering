@extends('layouts.admin')

@section('title', 'Categories')
@section('header', 'Item Categories')

@section('content')
    <div class="flex justify-between items-center mb-6">
        <a href="{{ route('admin.categories.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded">
            + Add Category
        </a>
    </div>

    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
        @forelse($categories as $category)
            <a href="{{ route('admin.categories.show', $category) }}" class="bg-white rounded-xl shadow hover:shadow-lg transition-all duration-300 border-2 border-gray-100 hover:border-blue-400 p-8 text-center group">
                <div class="text-5xl mb-4">🍽️</div>
                <h3 class="text-xl font-semibold text-gray-800 mb-2 group-hover:text-blue-600">{{ $category->name }}</h3>
                <p class="text-sm text-gray-500 mb-3">{{ $category->items_count }} items</p>
                <span class="px-3 py-1 text-xs rounded-full {{ $category->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                    {{ $category->is_active ? 'Active' : 'Inactive' }}
                </span>
            </a>
        @empty
            <div class="col-span-full text-center py-12 text-gray-500">
                No categories found. Create your first category to get started.
            </div>
        @endforelse
    </div>
@endsection

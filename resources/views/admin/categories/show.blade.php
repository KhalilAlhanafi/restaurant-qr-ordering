@extends('layouts.admin')

@section('title', $category->name)
@section('header', $category->name)

@section('content')
    <div class="flex justify-between items-center mb-6">
        <a href="{{ route('admin.categories.index') }}" class="text-blue-600 hover:text-blue-800">
            ← Back to Categories
        </a>
        <div class="flex gap-3">
            <a href="{{ route('admin.items.create', ['category_id' => $category->id]) }}" class="bg-green-600 hover:bg-green-700 text-white font-semibold py-2 px-4 rounded">
                + Add Item
            </a>
            <a href="{{ route('admin.categories.edit', $category) }}" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded">
                Edit Category
            </a>
        </div>
    </div>

    @if($category->description)
        <div class="bg-blue-50 border-l-4 border-blue-400 p-4 mb-6">
            <p class="text-gray-700">{{ $category->description }}</p>
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($category->items as $item)
            <div class="bg-white rounded-xl shadow p-6">
                <div class="flex justify-between items-start mb-4">
                    @if($item->image)
                        <img src="{{ Storage::url($item->image) }}" alt="{{ $item->name }}" class="h-16 w-16 rounded-lg object-cover">
                    @else
                        <div class="text-4xl">🍽️</div>
                    @endif
                    <span class="px-2 py-1 text-xs rounded-full {{ $item->is_available ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                        {{ $item->is_available ? 'Available' : 'Unavailable' }}
                    </span>
                </div>
                <h3 class="text-lg font-semibold text-gray-800 mb-2">{{ $item->name }}</h3>
                <p class="text-sm text-gray-500 mb-3">{{ $item->description }}</p>
                <div class="flex justify-between items-center mb-4">
                    <span class="text-xl font-bold text-green-600">
                        @if($item->show_price)
                            ${{ number_format($item->price, 2) }}
                        @else
                            Price hidden
                        @endif
                    </span>
                    <span class="text-sm text-gray-500">⏱️ {{ $item->preparation_time }} min</span>
                </div>
                <div class="flex gap-2">
                    <a href="{{ route('admin.items.edit', $item) }}" class="flex-1 text-center bg-blue-100 hover:bg-blue-200 text-blue-700 py-2 px-4 rounded text-sm font-medium">
                        Edit
                    </a>
                    <form action="{{ route('admin.items.destroy', $item) }}" method="POST" class="flex-1">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-full bg-red-100 hover:bg-red-200 text-red-700 py-2 px-4 rounded text-sm font-medium" onclick="return confirm('Delete this item?')">
                            Delete
                        </button>
                    </form>
                </div>
            </div>
        @empty
            <div class="col-span-full text-center py-12 bg-white rounded-xl shadow">
                <div class="text-6xl mb-4">🍽️</div>
                <p class="text-gray-500 mb-4">No items in this category yet.</p>
                <a href="{{ route('admin.items.create', ['category_id' => $category->id]) }}" class="text-blue-600 hover:text-blue-800 font-medium">
                    Add your first item →
                </a>
            </div>
        @endforelse
    </div>
@endsection

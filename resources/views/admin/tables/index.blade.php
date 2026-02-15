@extends('layouts.admin')

@section('title', 'Tables')
@section('header', 'Table Management')

@section('content')
    <div class="flex justify-between items-center mb-6">
        <a href="{{ route('admin.tables.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded">
            + Add Table
        </a>
    </div>

    <!-- Visual Floor Plan -->
    <div class="bg-white shadow rounded-lg p-6 mb-8">
        <h3 class="text-lg font-semibold mb-4">Floor Plan View</h3>
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-4">
            @foreach($tables as $table)
                @php
                    $statusColors = [
                        'available' => 'bg-green-100 border-green-400 text-green-800',
                        'occupied' => 'bg-red-100 border-red-400 text-red-800',
                        'reserved' => 'bg-yellow-100 border-yellow-400 text-yellow-800',
                        'cleaning' => 'bg-gray-100 border-gray-400 text-gray-800',
                    ];
                    $color = $statusColors[$table->status] ?? $statusColors['available'];
                @endphp
                <a href="{{ route('admin.tables.edit', $table) }}" class="block">
                    <div class="{{ $color }} border-2 rounded-lg p-4 text-center transition-transform hover:scale-105">
                        <div class="text-2xl font-bold mb-1">{{ $table->table_number }}</div>
                        <div class="text-sm font-medium uppercase">{{ $table->status }}</div>
                        <div class="text-xs mt-1">{{ $table->capacity }} seats</div>
                        @if($table->orders_count > 0)
                            <div class="text-xs font-semibold mt-1">{{ $table->orders_count }} active orders</div>
                        @endif
                    </div>
                </a>
            @endforeach
        </div>
        
        <!-- Legend -->
        <div class="flex flex-wrap gap-4 mt-6 pt-4 border-t">
            <div class="flex items-center"><span class="w-4 h-4 bg-green-100 border border-green-400 rounded mr-2"></span> Available</div>
            <div class="flex items-center"><span class="w-4 h-4 bg-red-100 border border-red-400 rounded mr-2"></span> Occupied</div>
            <div class="flex items-center"><span class="w-4 h-4 bg-yellow-100 border border-yellow-400 rounded mr-2"></span> Reserved</div>
            <div class="flex items-center"><span class="w-4 h-4 bg-gray-100 border border-gray-400 rounded mr-2"></span> Cleaning</div>
        </div>
    </div>

    <!-- Table List -->
    <div class="bg-white shadow rounded-lg overflow-x-auto">
        <h3 class="text-lg font-semibold px-6 py-4 border-b">Table List</h3>
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Table #</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Capacity</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Location</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Active Orders</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($tables as $table)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $table->table_number }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $table->capacity }} seats</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $table->location ?? 'N/A' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $statusColors[$table->status] ?? $statusColors['available'] }}">
                                {{ ucfirst($table->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $table->orders_count }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <a href="{{ route('admin.tables.edit', $table) }}" class="text-blue-600 hover:text-blue-900">Edit</a>
                            <form action="{{ route('admin.tables.destroy', $table) }}" method="POST" class="inline ml-3">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Are you sure?')">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-4 text-center text-gray-500">No tables found</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection

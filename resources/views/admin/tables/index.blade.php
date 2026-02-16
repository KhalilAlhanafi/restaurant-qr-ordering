@extends('layouts.admin')

@section('title', 'Tables')

@section('content')
    <!-- Header -->
    <div class="mb-8">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl text-amber-500 tracking-wider mb-1">Tables</h1>
                <p class="text-xs uppercase tracking-[0.2em] text-gray-500">Manage restaurant tables</p>
            </div>
            <a href="{{ route('admin.tables.create') }}"
                class="inline-flex items-center px-6 py-3 bg-amber-500 hover:bg-amber-600 text-[#0a0a0a] font-semibold text-sm rounded-lg transition-all duration-200 group">
                <svg class="w-5 h-5 mr-2 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Add Table
            </a>
        </div>
    </div>

    <!-- Visual Floor Plan -->
    <div class="bg-[#1a1a1a] border border-gray-800 rounded-xl p-6 mb-6">
        <h3 class="text-lg font-semibold text-white mb-4">Floor Plan View</h3>
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-4">
            @foreach($tables as $table)
                @php
                    $statusColors = [
                        'available' => 'border-green-500/50 text-green-400 bg-green-500/10',
                        'occupied' => 'border-red-500/50 text-red-400 bg-red-500/10',
                        'reserved' => 'border-yellow-500/50 text-yellow-400 bg-yellow-500/10',
                        'cleaning' => 'border-gray-500/50 text-gray-400 bg-gray-500/10',
                    ];
                    $color = $statusColors[$table->status] ?? $statusColors['available'];
                @endphp
                <a href="{{ route('admin.tables.edit', $table) }}" class="block group">
                    <div class="{{ $color }} border rounded-xl p-4 text-center transition-all duration-300 hover:scale-105 hover:shadow-lg">
                        <div class="text-2xl font-bold mb-1">{{ $table->table_number }}</div>
                        <div class="text-xs font-medium uppercase tracking-wider opacity-80">{{ $table->status }}</div>
                        <div class="text-xs mt-1 opacity-70">{{ $table->capacity }} seats</div>
                        @if($table->orders_count > 0)
                            <div class="text-xs font-semibold mt-1 text-amber-500">{{ $table->orders_count }} active orders</div>
                        @endif
                    </div>
                </a>
            @endforeach
        </div>

        <!-- Legend -->
        <div class="flex flex-wrap gap-6 mt-6 pt-6 border-t border-gray-800">
            <div class="flex items-center text-sm text-gray-400"><span class="w-4 h-4 bg-green-500/10 border border-green-500/50 rounded mr-2"></span> Available</div>
            <div class="flex items-center text-sm text-gray-400"><span class="w-4 h-4 bg-red-500/10 border border-red-500/50 rounded mr-2"></span> Occupied</div>
            <div class="flex items-center text-sm text-gray-400"><span class="w-4 h-4 bg-yellow-500/10 border border-yellow-500/50 rounded mr-2"></span> Reserved</div>
            <div class="flex items-center text-sm text-gray-400"><span class="w-4 h-4 bg-gray-500/10 border border-gray-500/50 rounded mr-2"></span> Cleaning</div>
        </div>
    </div>

    <!-- Table List -->
    <div class="bg-[#1a1a1a] border border-gray-800 rounded-xl overflow-x-auto">
        <h3 class="text-lg font-semibold text-white px-6 py-4 border-b border-gray-800">Table List</h3>
        <table class="min-w-full">
            <thead class="bg-[#0f0f0f]">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Table #</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Capacity</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Location</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Active Orders</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-800">
                @forelse($tables as $table)
                    <tr class="hover:bg-[#252525] transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-white">{{ $table->table_number }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-400">{{ $table->capacity }} seats</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-400">{{ $table->location ?? 'N/A' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @php
                                $statusColors = [
                                    'available' => 'bg-green-500/10 text-green-400 border-green-500/20',
                                    'occupied' => 'bg-red-500/10 text-red-400 border-red-500/20',
                                    'reserved' => 'bg-yellow-500/10 text-yellow-400 border-yellow-500/20',
                                    'cleaning' => 'bg-gray-500/10 text-gray-400 border-gray-500/20',
                                ];
                            @endphp
                            <span class="px-2 py-1 text-xs font-medium rounded-full border {{ $statusColors[$table->status] ?? $statusColors['available'] }}">
                                {{ ucfirst($table->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-400">{{ $table->orders_count }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <a href="{{ route('admin.tables.edit', $table) }}" class="text-amber-500 hover:text-amber-400 transition-colors">Edit</a>
                            <form action="{{ route('admin.tables.destroy', $table) }}" method="POST" class="inline ml-3">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-400 hover:text-red-300 transition-colors" onclick="return confirm('Are you sure?')">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center">
                            <div class="w-16 h-16 bg-amber-500/10 rounded-2xl flex items-center justify-center mx-auto mb-4">
                                <svg class="w-8 h-8 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                </svg>
                            </div>
                            <h3 class="text-lg font-semibold text-white mb-2">No Tables Yet</h3>
                            <p class="text-gray-500 mb-4">Add your first table to get started.</p>
                            <a href="{{ route('admin.tables.create') }}" class="text-amber-500 hover:text-amber-400 font-medium">Add First Table →</a>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection

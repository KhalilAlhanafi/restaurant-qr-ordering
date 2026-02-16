@extends('layouts.admin')

@section('title', 'Edit Table')

@section('content')
    <!-- Breadcrumb -->
    <div class="mb-6">
        <div class="flex items-center gap-2 text-sm text-gray-500">
            <a href="{{ route('admin.tables.index') }}" class="hover:text-amber-500 transition-colors">Tables</a>
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
            </svg>
            <span class="text-gray-400">Table {{ $table->table_number }}</span>
        </div>
    </div>

    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-2xl text-white tracking-wider mb-1">Edit Table</h1>
        <p class="text-xs uppercase tracking-[0.2em] text-gray-500">Update table details</p>
    </div>

    <!-- Form -->
    <div class="bg-[#1a1a1a] border border-gray-800 rounded-xl p-6 max-w-2xl">
        <form action="{{ route('admin.tables.update', $table) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-6">
                <label for="table_number" class="block text-sm font-medium text-gray-400 mb-2">Table Number</label>
                <div class="w-full px-4 py-3 bg-[#252525] border border-gray-700 rounded-lg text-gray-300 flex items-center gap-3">
                    <svg class="w-5 h-5 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                    </svg>
                    {{ $table->table_number }}
                </div>
                <input type="hidden" name="table_number" value="{{ $table->table_number }}">
                @error('table_number')
                    <p class="text-red-400 text-sm mt-2">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6">
                <label for="capacity" class="block text-sm font-medium text-gray-400 mb-2">Capacity <span class="text-amber-500">*</span></label>
                <input type="number" name="capacity" id="capacity" value="{{ old('capacity', $table->capacity) }}" min="1" required
                    class="w-full px-4 py-3 bg-[#0f0f0f] border border-gray-700 rounded-lg text-white placeholder-gray-600 focus:outline-none focus:border-amber-500/50 focus:ring-1 focus:ring-amber-500/50 transition-all">
                @error('capacity')
                    <p class="text-red-400 text-sm mt-2">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6">
                <label for="location" class="block text-sm font-medium text-gray-400 mb-2">Location <span class="text-amber-500">*</span></label>
                <select name="location" id="location" required
                    class="w-full px-4 py-3 bg-[#0f0f0f] border border-gray-700 rounded-lg text-white focus:outline-none focus:border-amber-500/50 focus:ring-1 focus:ring-amber-500/50 transition-all">
                    <option value="" class="text-gray-500">Select a location</option>
                    <option value="indoor" {{ old('location', $table->location) == 'indoor' ? 'selected' : '' }} class="text-white">Indoor</option>
                    <option value="outdoor" {{ old('location', $table->location) == 'outdoor' ? 'selected' : '' }} class="text-white">Outdoor</option>
                    <option value="balcony" {{ old('location', $table->location) == 'balcony' ? 'selected' : '' }} class="text-white">Balcony</option>
                    <option value="second floor" {{ old('location', $table->location) == 'second floor' ? 'selected' : '' }} class="text-white">Second Floor</option>
                    <option value="second floor balcony" {{ old('location', $table->location) == 'second floor balcony' ? 'selected' : '' }} class="text-white">Second Floor Balcony</option>
                </select>
                @error('location')
                    <p class="text-red-400 text-sm mt-2">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6">
                <label for="status" class="block text-sm font-medium text-gray-400 mb-2">Status <span class="text-amber-500">*</span></label>
                <select name="status" id="status" required
                    class="w-full px-4 py-3 bg-[#0f0f0f] border border-gray-700 rounded-lg text-white focus:outline-none focus:border-amber-500/50 focus:ring-1 focus:ring-amber-500/50 transition-all">
                    <option value="available" {{ old('status', $table->status) == 'available' ? 'selected' : '' }} class="text-white">Available</option>
                    <option value="occupied" {{ old('status', $table->status) == 'occupied' ? 'selected' : '' }} class="text-white">Occupied</option>
                    <option value="reserved" {{ old('status', $table->status) == 'reserved' ? 'selected' : '' }} class="text-white">Reserved</option>
                    <option value="cleaning" {{ old('status', $table->status) == 'cleaning' ? 'selected' : '' }} class="text-white">Cleaning</option>
                </select>
                @error('status')
                    <p class="text-red-400 text-sm mt-2">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6 p-4 bg-[#252525] border border-gray-700 rounded-lg">
                <label class="block text-sm font-medium text-gray-400 mb-2">QR Token</label>
                <div class="flex items-center">
                    <code class="bg-[#0f0f0f] px-3 py-2 rounded text-sm text-gray-300">{{ $table->qr_token }}</code>
                    <a href="{{ route('admin.qr-code-image', $table->qr_token) }}" target="_blank" class="ml-3 text-amber-500 hover:text-amber-400 text-sm font-medium transition-colors">View QR Code →</a>
                </div>
            </div>

            <div class="flex items-center justify-between pt-4 border-t border-gray-800">
                <div class="flex items-center gap-4">
                    <button type="submit" class="inline-flex items-center px-6 py-3 bg-amber-500 hover:bg-amber-600 text-[#0a0a0a] font-semibold text-sm rounded-lg transition-all duration-200">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Update Table
                    </button>
                    <a href="{{ route('admin.tables.index') }}" class="text-gray-500 hover:text-gray-300 transition-colors">
                        Cancel
                    </a>
                </div>

                <form action="{{ route('admin.tables.destroy', $table) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this table?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="inline-flex items-center px-4 py-3 bg-red-500/10 hover:bg-red-500/20 text-red-400 hover:text-red-300 font-medium text-sm rounded-lg transition-all duration-200">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                        </svg>
                        Delete
                    </button>
                </form>
            </div>
        </form>
    </div>
@endsection

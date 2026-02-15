@extends('layouts.admin')

@section('title', 'Edit Table')
@section('header', 'Edit Table')

@section('content')
    <div class="bg-white shadow rounded-lg p-6 max-w-2xl">
        <form action="{{ route('admin.tables.update', $table) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="mb-4">
                <label for="table_number" class="block text-sm font-medium text-gray-700 mb-2">Table Number *</label>
                <input type="text" name="table_number" id="table_number" value="{{ old('table_number', $table->table_number) }}" required
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                @error('table_number')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            <div class="mb-4">
                <label for="capacity" class="block text-sm font-medium text-gray-700 mb-2">Capacity *</label>
                <input type="number" name="capacity" id="capacity" value="{{ old('capacity', $table->capacity) }}" min="1" required
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                @error('capacity')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            <div class="mb-4">
                <label for="location" class="block text-sm font-medium text-gray-700 mb-2">Location</label>
                <input type="text" name="location" id="location" value="{{ old('location', $table->location) }}" placeholder="e.g., Main Hall, Patio, etc."
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                @error('location')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            <div class="mb-4">
                <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Status *</label>
                <select name="status" id="status" required
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="available" {{ old('status', $table->status) == 'available' ? 'selected' : '' }}>Available</option>
                    <option value="occupied" {{ old('status', $table->status) == 'occupied' ? 'selected' : '' }}>Occupied</option>
                    <option value="reserved" {{ old('status', $table->status) == 'reserved' ? 'selected' : '' }}>Reserved</option>
                    <option value="cleaning" {{ old('status', $table->status) == 'cleaning' ? 'selected' : '' }}>Cleaning</option>
                </select>
                @error('status')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            <div class="mb-4 p-4 bg-gray-50 rounded-lg">
                <label class="block text-sm font-medium text-gray-700 mb-2">QR Token</label>
                <div class="flex items-center">
                    <code class="bg-gray-200 px-2 py-1 rounded text-sm">{{ $table->qr_token }}</code>
                    <a href="{{ route('admin.qr-code-image', $table->qr_token) }}" target="_blank" class="ml-3 text-blue-600 hover:text-blue-800 text-sm">View QR Code</a>
                </div>
            </div>
            
            <div class="flex items-center space-x-4">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded">
                    Update Table
                </button>
                <a href="{{ route('admin.tables.index') }}" class="text-gray-600 hover:text-gray-800">Cancel</a>
            </div>
        </form>
    </div>
@endsection

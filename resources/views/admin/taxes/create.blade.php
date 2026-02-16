@extends('layouts.admin')

@section('title', 'Create Tax - L\'ELITE Administration')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">
    <!-- Header -->
    <div>
        <nav class="flex" aria-label="Breadcrumb">
            <ol class="flex items-center space-x-2">
                <li>
                    <a href="{{ route('admin.taxes.index') }}" class="text-gray-400 hover:text-amber-500">
                        Taxes
                    </a>
                </li>
                <li class="flex items-center">
                    <svg class="w-4 h-4 text-gray-600 mx-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                    </svg>
                    <span class="text-amber-500">Create Tax</span>
                </li>
            </ol>
        </nav>
        <h1 class="text-2xl font-bold text-amber-500 mt-4">Create New Tax</h1>
        <p class="text-gray-400 mt-1">Add a new tax rate to apply to customer orders</p>
    </div>

    <!-- Form -->
    <div class="bg-[#1a1a1a] border border-gray-800 rounded-lg p-6">
        <form action="{{ route('admin.taxes.store') }}" method="POST">
            @csrf
            
            <!-- Title -->
            <div class="mb-6">
                <label for="title" class="block text-sm font-medium text-gray-300 mb-2">
                    Tax Title <span class="text-red-500">*</span>
                </label>
                <input type="text" 
                       id="title" 
                       name="title" 
                       required
                       value="{{ old('title') }}"
                       class="w-full px-4 py-2 bg-[#0f0f0f] border border-gray-700 rounded-lg text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-transparent"
                       placeholder="e.g., VAT, Service Charge">
                @error('title')
                    <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <!-- Description -->
            <div class="mb-6">
                <label for="description" class="block text-sm font-medium text-gray-300 mb-2">
                    Description
                </label>
                <textarea id="description" 
                          name="description" 
                          rows="3"
                          class="w-full px-4 py-2 bg-[#0f0f0f] border border-gray-700 rounded-lg text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-transparent"
                          placeholder="Optional description of this tax">{{ old('description') }}</textarea>
                @error('description')
                    <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <!-- Type -->
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-300 mb-2">
                    Tax Type <span class="text-red-500">*</span>
                </label>
                <div class="space-y-3">
                    <label class="flex items-center">
                        <input type="radio" 
                               name="type" 
                               value="percentage" 
                               {{ old('type', 'percentage') === 'percentage' ? 'checked' : '' }}
                               class="w-4 h-4 text-amber-600 bg-[#0f0f0f] border-gray-600 focus:ring-amber-500">
                        <span class="ml-3 text-sm text-gray-300">Percentage (%)</span>
                    </label>
                    <label class="flex items-center">
                        <input type="radio" 
                               name="type" 
                               value="fixed" 
                               {{ old('type') === 'fixed' ? 'checked' : '' }}
                               class="w-4 h-4 text-amber-600 bg-[#0f0f0f] border-gray-600 focus:ring-amber-500">
                        <span class="ml-3 text-sm text-gray-300">Fixed Amount ($)</span>
                    </label>
                </div>
                @error('type')
                    <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <!-- Value -->
            <div class="mb-6">
                <label for="value" class="block text-sm font-medium text-gray-300 mb-2">
                    Tax Value <span class="text-red-500">*</span>
                </label>
                <div class="relative">
                    <input type="number" 
                           id="value" 
                           name="value" 
                           required
                           step="0.01"
                           min="0"
                           value="{{ old('value') }}"
                           class="w-full px-4 py-2 bg-[#0f0f0f] border border-gray-700 rounded-lg text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-transparent"
                           placeholder="0.00">
                    <span id="value-suffix" class="absolute right-4 top-1/2 transform -translate-y-1/2 text-gray-400">
                        {{ old('type', 'percentage') === 'percentage' ? '%' : '$' }}
                    </span>
                </div>
                @error('value')
                    <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <!-- Active Status -->
            <div class="mb-6">
                <label class="flex items-center">
                    <input type="checkbox" 
                           name="is_active" 
                           value="1"
                           {{ old('is_active', true) ? 'checked' : '' }}
                           class="w-4 h-4 text-amber-600 bg-[#0f0f0f] border-gray-600 rounded focus:ring-amber-500">
                    <span class="ml-3 text-sm text-gray-300">Active (apply this tax to orders)</span>
                </label>
            </div>

            <!-- Actions -->
            <div class="flex items-center justify-end space-x-4">
                <a href="{{ route('admin.taxes.index') }}" 
                   class="px-4 py-2 text-gray-300 hover:text-white transition-colors">
                    Cancel
                </a>
                <button type="submit" 
                        class="px-6 py-2 bg-amber-600 text-white rounded-lg hover:bg-amber-700 transition-colors">
                    Create Tax
                </button>
            </div>
        </form>
    </div>
</div>

<script>
// Update value suffix based on tax type
document.addEventListener('DOMContentLoaded', function() {
    const typeRadios = document.querySelectorAll('input[name="type"]');
    const valueSuffix = document.getElementById('value-suffix');
    
    typeRadios.forEach(radio => {
        radio.addEventListener('change', function() {
            valueSuffix.textContent = this.value === 'percentage' ? '%' : '$';
        });
    });
});
</script>
@endsection

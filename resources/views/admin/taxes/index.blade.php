@extends('layouts.admin')

@section('title', 'Taxes - L\'ELITE Administration')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-amber-500">Taxes</h1>
            <p class="text-gray-400 mt-1">Manage tax rates for orders</p>
        </div>
        <a href="{{ route('admin.taxes.create') }}" 
           class="inline-flex items-center px-4 py-2 bg-amber-600 text-white rounded-lg hover:bg-amber-700 transition-colors">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
            </svg>
            Add Tax
        </a>
    </div>

    <!-- Taxes Table -->
    <div class="bg-[#1a1a1a] border border-gray-800 rounded-lg overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-[#0f0f0f] border-b border-gray-800">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Title</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Type</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Value</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-4 text-right text-xs font-medium text-gray-400 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-800">
                    @forelse($taxes as $tax)
                        <tr class="hover:bg-[#2a2a2a] transition-colors">
                            <td class="px-6 py-4">
                                <div>
                                    <div class="text-sm font-medium text-white">{{ $tax->title }}</div>
                                    @if($tax->description)
                                        <div class="text-sm text-gray-400 mt-1">{{ $tax->description }}</div>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                    {{ $tax->type === 'percentage' ? 'bg-blue-900/30 text-blue-400' : 'bg-purple-900/30 text-purple-400' }}">
                                    {{ ucfirst($tax->type) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-300">
                                @if($tax->type === 'percentage')
                                    {{ $tax->value }}%
                                @else
                                    ${{ number_format($tax->value, 2) }}
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    {{ $tax->is_active ? 'bg-green-900/30 text-green-400' : 'bg-red-900/30 text-red-400' }}">
                                    {{ $tax->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right text-sm font-medium">
                                <a href="{{ route('admin.taxes.edit', $tax) }}" 
                                   class="text-amber-500 hover:text-amber-400 mr-3">Edit</a>
                                <form action="{{ route('admin.taxes.destroy', $tax) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="text-red-500 hover:text-red-400"
                                            onclick="return confirm('Are you sure you want to delete this tax?')">
                                        Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-gray-400">
                                <div class="flex flex-col items-center">
                                    <svg class="w-12 h-12 text-gray-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                              d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2z"></path>
                                    </svg>
                                    <p class="text-lg font-medium mb-2">No taxes found</p>
                                    <p class="text-sm mb-4">Create your first tax to start applying taxes to orders</p>
                                    <a href="{{ route('admin.taxes.create') }}" 
                                       class="inline-flex items-center px-4 py-2 bg-amber-600 text-white rounded-lg hover:bg-amber-700 transition-colors">
                                        Add Tax
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@extends('layouts.admin')

@section('title', 'Reservations')

@section('content')
    <!-- Header -->
    <div class="mb-8">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl text-amber-500 tracking-wider mb-1">Reservations</h1>
                <p class="text-xs uppercase tracking-[0.2em] text-gray-500">Manage table reservations</p>
            </div>
            <div class="flex gap-3">
                <a href="{{ route('admin.reservations.create') }}"
                    class="inline-flex items-center px-6 py-3 bg-amber-500 hover:bg-amber-600 text-[#0a0a0a] font-semibold text-sm rounded-lg transition-all duration-200 group">
                    <svg class="w-5 h-5 mr-2 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Add Reservation
                </a>
                <a href="{{ route('admin.reservations.timeline') }}"
                    class="inline-flex items-center px-6 py-3 bg-[#1a1a1a] hover:bg-[#252525] border border-gray-800 text-gray-300 hover:text-white font-medium text-sm rounded-lg transition-all duration-200">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Timeline View
                </a>
            </div>
        </div>
    </div>

    <div class="bg-[#1a1a1a] border border-gray-800 rounded-xl overflow-x-auto">
        <table class="min-w-full">
            <thead class="bg-[#0f0f0f]">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Table</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Party Size</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date & Time</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-800">
                @forelse($reservations as $reservation)
                    <tr class="hover:bg-[#252525] transition-colors">
                        <td class="px-6 py-4">
                            <div class="text-sm font-medium text-white">{{ $reservation->customer_name }}</div>
                            <div class="text-sm text-gray-500">{{ $reservation->customer_phone }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-400">
                            Table {{ $reservation->table->table_number ?? 'N/A' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-400">
                            {{ $reservation->party_size }} guests
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-400">
                            <div class="text-white">{{ $reservation->start_time->format('M d, Y') }}</div>
                            <div class="text-gray-500">{{ $reservation->start_time->format('H:i') }} - {{ $reservation->end_time?->format('H:i') ?? 'TBD' }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @php
                                $statusColors = [
                                    'pending' => 'bg-yellow-500/10 text-yellow-400 border-yellow-500/20',
                                    'confirmed' => 'bg-green-500/10 text-green-400 border-green-500/20',
                                    'cancelled' => 'bg-red-500/10 text-red-400 border-red-500/20',
                                    'completed' => 'bg-blue-500/10 text-blue-400 border-blue-500/20',
                                ];
                            @endphp
                            <span class="px-2 py-1 text-xs font-medium rounded-full border {{ $statusColors[$reservation->status] ?? 'bg-gray-500/10 text-gray-400 border-gray-500/20' }}">
                                {{ ucfirst($reservation->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <a href="{{ route('admin.reservations.edit', $reservation) }}" class="text-amber-500 hover:text-amber-400 mr-3 transition-colors">Edit</a>
                            <form action="{{ route('admin.reservations.destroy', $reservation) }}" method="POST" class="inline">
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
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path>
                                </svg>
                            </div>
                            <h3 class="text-lg font-semibold text-white mb-2">No Reservations Yet</h3>
                            <p class="text-gray-500 mb-4">Add your first reservation to get started.</p>
                            <a href="{{ route('admin.reservations.create') }}" class="text-amber-500 hover:text-amber-400 font-medium">Add First Reservation →</a>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection

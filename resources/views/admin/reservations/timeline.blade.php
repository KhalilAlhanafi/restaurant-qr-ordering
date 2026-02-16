@extends('layouts.admin')

@section('title', 'Reservation Timeline')
@section('header', 'Reservation Timeline')

@section('content')
    <div class="mb-6">
        <a href="{{ route('admin.reservations.index') }}" class="text-blue-600 hover:text-blue-800">
            ← Back to List View
        </a>
    </div>

    <!-- Filter Controls -->
    <div class="bg-white shadow rounded-lg p-6 mb-6">
        <form method="GET" action="{{ route('admin.reservations.timeline') }}" class="flex flex-wrap gap-4 items-end">
            <div>
                <label for="month" class="block text-sm font-medium text-gray-700 mb-2">Month</label>
                <select name="month" id="month" onchange="this.form.submit()"
                    class="px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    @for($m = 1; $m <= 12; $m++)
                        <option value="{{ $m }}" {{ $currentMonth == $m ? 'selected' : '' }}>
                            {{ date('F', mktime(0, 0, 0, $m, 1)) }}
                        </option>
                    @endfor
                </select>
            </div>
            
            <div>
                <label for="week" class="block text-sm font-medium text-gray-700 mb-2">Week of Month</label>
                <select name="week" id="week" onchange="this.form.submit()"
                    class="px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    @for($w = 1; $w <= 5; $w++)
                        <option value="{{ $w }}" {{ $currentWeek == $w ? 'selected' : '' }}>
                            Week {{ $w }}
                        </option>
                    @endfor
                </select>
            </div>
            
            <div class="text-sm text-gray-600">
                <strong>Showing:</strong> {{ $weekStart->format('M j') }} - {{ $weekEnd->format('M j, Y') }}
            </div>
        </form>
    </div>

    <!-- Timeline Legend -->
    <div class="flex flex-wrap gap-4 mb-6 bg-white p-4 rounded-lg shadow">
        <div class="flex items-center"><span class="w-4 h-4 bg-yellow-100 border border-yellow-400 rounded mr-2"></span> Pending</div>
        <div class="flex items-center"><span class="w-4 h-4 bg-green-100 border border-green-400 rounded mr-2"></span> Confirmed</div>
        <div class="flex items-center"><span class="w-4 h-4 bg-red-100 border border-red-400 rounded mr-2"></span> Cancelled</div>
        <div class="flex items-center"><span class="w-4 h-4 bg-blue-100 border border-blue-400 rounded mr-2"></span> Completed</div>
    </div>

    <!-- Timeline View -->
    <div class="bg-white shadow rounded-lg overflow-x-auto">
        <div class="min-w-max">
            <!-- Header Row with Days -->
            <div class="flex border-b">
                <div class="w-32 p-4 font-semibold border-r bg-gray-50 shrink-0">Table</div>
                @for($i = 0; $i < 7; $i++)
                    @php $date = $weekStart->copy()->addDays($i) @endphp
                    <div class="flex-1 p-4 text-center border-r {{ $date->isToday() ? 'bg-blue-50' : 'bg-gray-50' }} min-w-[150px]">
                        <div class="font-semibold">{{ $date->format('D') }}</div>
                        <div class="text-sm text-gray-500">{{ $date->format('M d') }}</div>
                    </div>
                @endfor
            </div>
            
            <!-- Table Rows -->
            @foreach($tables as $table)
                <div class="flex border-b">
                    <div class="w-32 p-4 font-medium border-r bg-gray-50 shrink-0">
                        Table {{ $table->table_number }}
                        <div class="text-xs text-gray-500">{{ $table->capacity }} seats</div>
                    </div>
                    
                    @for($i = 0; $i < 7; $i++)
                        @php 
                            $date = $weekStart->copy()->addDays($i);
                            $dayReservations = $reservations->where('table_id', $table->id)
                                ->filter(function($r) use ($date) {
                                    return $r->start_time->isSameDay($date);
                                });
                        @endphp
                        
                        <div class="flex-1 p-2 border-r {{ $date->isToday() ? 'bg-blue-50' : '' }} min-w-[150px] min-h-20">
                            @forelse($dayReservations as $reservation)
                                @php
                                    $statusColors = [
                                        'pending' => 'bg-yellow-100 border-yellow-400 text-yellow-800',
                                        'confirmed' => 'bg-green-100 border-green-400 text-green-800',
                                        'cancelled' => 'bg-red-100 border-red-400 text-red-800',
                                        'completed' => 'bg-blue-100 border-blue-400 text-blue-800',
                                    ];
                                    $color = $statusColors[$reservation->status] ?? 'bg-gray-100 border-gray-400';
                                @endphp
                                <a href="{{ route('admin.reservations.edit', $reservation) }}" class="block">
                                    <div class="{{ $color }} border rounded p-2 mb-1 text-xs hover:shadow-md transition-shadow">
                                        <div class="font-semibold truncate">{{ $reservation->customer_name }}</div>
                                        <div>{{ $reservation->start_time->format('H:i') }} - {{ $reservation->end_time?->format('H:i') ?? '?' }}</div>
                                        <div class="text-xs">{{ $reservation->party_size }} guests</div>
                                    </div>
                                </a>
                            @empty
                                <div class="text-gray-300 text-xs text-center py-4">-</div>
                            @endforelse
                        </div>
                    @endfor
                </div>
            @endforeach
        </div>
    </div>
@endsection

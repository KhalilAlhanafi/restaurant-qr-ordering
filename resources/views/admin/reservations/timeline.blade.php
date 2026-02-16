@extends('layouts.admin')

@section('title', 'Reservation Timeline')

@section('content')
    <!-- Breadcrumb -->
    <div class="mb-6">
        <div class="flex items-center gap-2 text-sm text-gray-500">
            <a href="{{ route('admin.reservations.index') }}" class="hover:text-amber-500 transition-colors">Reservations</a>
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
            </svg>
            <span class="text-gray-400">Timeline View</span>
        </div>
    </div>

    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-2xl text-white tracking-wider mb-1">Reservation Timeline</h1>
        <p class="text-xs uppercase tracking-[0.2em] text-gray-500">View reservations by week</p>
    </div>

    <!-- Filter Controls -->
    <div class="bg-[#1a1a1a] border border-gray-800 rounded-xl p-6 mb-6">
        <form method="GET" action="{{ route('admin.reservations.timeline') }}" class="flex flex-wrap gap-4 items-end">
            <div>
                <label for="month" class="block text-sm font-medium text-gray-400 mb-2">Month</label>
                <select name="month" id="month" onchange="this.form.submit()"
                    class="px-4 py-3 bg-[#0f0f0f] border border-gray-700 rounded-lg text-white focus:outline-none focus:border-amber-500/50 focus:ring-1 focus:ring-amber-500/50 transition-all">
                    @for($m = 1; $m <= 12; $m++)
                        <option value="{{ $m }}" {{ $currentMonth == $m ? 'selected' : '' }} class="text-white">
                            {{ date('F', mktime(0, 0, 0, $m, 1)) }}
                        </option>
                    @endfor
                </select>
            </div>

            <div>
                <label for="week" class="block text-sm font-medium text-gray-400 mb-2">Week of Month</label>
                <select name="week" id="week" onchange="this.form.submit()"
                    class="px-4 py-3 bg-[#0f0f0f] border border-gray-700 rounded-lg text-white focus:outline-none focus:border-amber-500/50 focus:ring-1 focus:ring-amber-500/50 transition-all">
                    @for($w = 1; $w <= 5; $w++)
                        <option value="{{ $w }}" {{ $currentWeek == $w ? 'selected' : '' }} class="text-white">
                            Week {{ $w }}
                        </option>
                    @endfor
                </select>
            </div>

            <div class="text-sm text-gray-400">
                <span class="text-gray-500">Showing:</span> {{ $weekStart->format('M j') }} - {{ $weekEnd->format('M j, Y') }}
            </div>
        </form>
    </div>

    <!-- Timeline View -->
    <div class="bg-[#1a1a1a] border border-gray-800 rounded-xl overflow-x-auto">
        <div class="min-w-max">
            <!-- Header Row with Days -->
            <div class="flex border-b border-gray-800">
                <div class="w-32 p-4 font-semibold border-r border-gray-800 bg-[#0f0f0f] shrink-0 text-white">Table</div>
                @for($i = 0; $i < 7; $i++)
                    @php $date = $weekStart->copy()->addDays($i) @endphp
                    <div class="flex-1 p-4 text-center border-r border-gray-800 {{ $date->isToday() ? 'bg-amber-500/10' : 'bg-[#0f0f0f]' }} min-w-[150px]">
                        <div class="font-semibold text-white">{{ $date->format('D') }}</div>
                        <div class="text-sm text-gray-500">{{ $date->format('M d') }}</div>
                    </div>
                @endfor
            </div>

            <!-- Table Rows -->
            @foreach($tables as $table)
                <div class="flex border-b border-gray-800">
                    <div class="w-32 p-4 font-medium border-r border-gray-800 bg-[#0f0f0f] shrink-0">
                        <div class="text-white">Table {{ $table->table_number }}</div>
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

                        <div class="flex-1 p-2 border-r border-gray-800 {{ $date->isToday() ? 'bg-amber-500/5' : '' }} min-w-[150px] min-h-20">
                            @forelse($dayReservations as $reservation)
                                <a href="{{ route('admin.reservations.edit', $reservation) }}" class="block">
                                    <div class="bg-[#252525] border border-gray-700 hover:border-amber-500/50 rounded-lg p-2 mb-1 text-xs transition-all hover:shadow-lg">
                                        <div class="font-semibold text-white truncate">{{ $reservation->customer_name }}</div>
                                        <div class="text-gray-400">{{ $reservation->start_time->format('H:i') }} - {{ $reservation->end_time?->format('H:i') ?? '?' }}</div>
                                        <div class="text-xs text-gray-500">{{ $reservation->party_size }} guests</div>
                                    </div>
                                </a>
                            @empty
                                <div class="text-gray-600 text-xs text-center py-4">-</div>
                            @endforelse
                        </div>
                    @endfor
                </div>
            @endforeach
        </div>
    </div>
@endsection

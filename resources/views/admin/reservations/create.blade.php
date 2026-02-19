@extends('layouts.admin')

@section('title', 'Add Reservation')

@section('content')
    <!-- Breadcrumb -->
    <div class="mb-6">
        <div class="flex items-center gap-2 text-sm text-gray-500">
            <a href="{{ route('admin.reservations.index') }}" class="hover:text-amber-500 transition-colors">Reservations</a>
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
            </svg>
            <span class="text-gray-400">Add Reservation</span>
        </div>
    </div>

    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-2xl text-white tracking-wider mb-1">Add Reservation</h1>
        <p class="text-xs uppercase tracking-[0.2em] text-gray-500">Create a new table reservation</p>
    </div>

    <!-- Form -->
    <div class="bg-[#1a1a1a] border border-gray-800 rounded-xl p-6 max-w-2xl">
        <form action="{{ route('admin.reservations.store') }}" method="POST">
            @csrf

            <div class="mb-6">
                <label for="customer_name" class="block text-sm font-medium text-gray-400 mb-2">Customer Name <span class="text-amber-500">*</span></label>
                <input type="text" name="customer_name" id="customer_name" value="{{ old('customer_name') }}" required
                    class="w-full px-4 py-3 bg-[#0f0f0f] border border-gray-700 rounded-lg text-white placeholder-gray-600 focus:outline-none focus:border-amber-500/50 focus:ring-1 focus:ring-amber-500/50 transition-all">
                @error('customer_name')
                    <p class="text-red-400 text-sm mt-2">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6">
                <label for="customer_phone" class="block text-sm font-medium text-gray-400 mb-2">Phone Number</label>
                <input type="tel" name="customer_phone" id="customer_phone" value="{{ old('customer_phone') }}"
                    class="w-full px-4 py-3 bg-[#0f0f0f] border border-gray-700 rounded-lg text-white placeholder-gray-600 focus:outline-none focus:border-amber-500/50 focus:ring-1 focus:ring-amber-500/50 transition-all">
                @error('customer_phone')
                    <p class="text-red-400 text-sm mt-2">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid grid-cols-2 gap-4 mb-6">
                <div>
                    <label for="table_id" class="block text-sm font-medium text-gray-400 mb-2">Table <span class="text-amber-500">*</span></label>
                    <select name="table_id" id="table_id" required
                        class="w-full px-4 py-3 bg-[#0f0f0f] border border-gray-700 rounded-lg text-white focus:outline-none focus:border-amber-500/50 focus:ring-1 focus:ring-amber-500/50 transition-all">
                        <option value="" class="text-gray-500">Select Table</option>
                        @foreach($tables as $table)
                            <option value="{{ $table->id }}" {{ old('table_id') == $table->id ? 'selected' : '' }} class="text-white">
                                Table {{ $table->table_number }} ({{ $table->capacity }} seats)
                            </option>
                        @endforeach
                    </select>
                    @error('table_id')
                        <p class="text-red-400 text-sm mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="party_size" class="block text-sm font-medium text-gray-400 mb-2">Party Size <span class="text-amber-500">*</span></label>
                    <input type="number" name="party_size" id="party_size" value="{{ old('party_size', 2) }}" min="1" required
                        class="w-full px-4 py-3 bg-[#0f0f0f] border border-gray-700 rounded-lg text-white placeholder-gray-600 focus:outline-none focus:border-amber-500/50 focus:ring-1 focus:ring-amber-500/50 transition-all">
                    @error('party_size')
                        <p class="text-red-400 text-sm mt-2">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="grid grid-cols-3 gap-4 mb-6">
                <div>
                    <label for="month" class="block text-sm font-medium text-gray-400 mb-2">Month <span class="text-amber-500">*</span></label>
                    <select name="month" id="month" required
                        class="w-full px-4 py-3 bg-[#0f0f0f] border border-gray-700 rounded-lg text-white focus:outline-none focus:border-amber-500/50 focus:ring-1 focus:ring-amber-500/50 transition-all">
                        <option value="" class="text-gray-500">Select Month</option>
                        @for($m = 1; $m <= 12; $m++)
                            <option value="{{ $m }}" {{ old('month') == $m ? 'selected' : '' }} class="text-white">
                                {{ date('F', mktime(0, 0, 0, $m, 1)) }}
                            </option>
                        @endfor
                    </select>
                    @error('month')
                        <p class="text-red-400 text-sm mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="day" class="block text-sm font-medium text-gray-400 mb-2">Day <span class="text-amber-500">*</span></label>
                    <select name="day" id="day" required
                        class="w-full px-4 py-3 bg-[#0f0f0f] border border-gray-700 rounded-lg text-white focus:outline-none focus:border-amber-500/50 focus:ring-1 focus:ring-amber-500/50 transition-all">
                        <option value="" class="text-gray-500">Select Day</option>
                        @for($d = 1; $d <= 31; $d++)
                            <option value="{{ $d }}" {{ old('day') == $d ? 'selected' : '' }} class="text-white">
                                {{ $d }}
                            </option>
                        @endfor
                    </select>
                    @error('day')
                        <p class="text-red-400 text-sm mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="start_time" class="block text-sm font-medium text-gray-400 mb-2">Start Time <span class="text-amber-500">*</span></label>
                    <input type="time" name="start_time" id="start_time" value="{{ old('start_time') }}" required
                        class="w-full px-4 py-3 bg-[#0f0f0f] border border-gray-700 rounded-lg text-white focus:outline-none focus:border-amber-500/50 focus:ring-1 focus:ring-amber-500/50 transition-all">
                    @error('start_time')
                        <p class="text-red-400 text-sm mt-2">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="mb-6">
                <label for="end_time" class="block text-sm font-medium text-gray-400 mb-2">End Time <span class="text-amber-500">*</span></label>
                <input type="time" name="end_time" id="end_time" value="{{ old('end_time') }}" required
                    class="w-full px-4 py-3 bg-[#0f0f0f] border border-gray-700 rounded-lg text-white focus:outline-none focus:border-amber-500/50 focus:ring-1 focus:ring-amber-500/50 transition-all">
                @error('end_time')
                    <p class="text-red-400 text-sm mt-2">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-8">
                <label for="special_requests" class="block text-sm font-medium text-gray-400 mb-2">Special Requests</label>
                <textarea name="special_requests" id="special_requests" rows="3"
                    class="w-full px-4 py-3 bg-[#0f0f0f] border border-gray-700 rounded-lg text-white placeholder-gray-600 focus:outline-none focus:border-amber-500/50 focus:ring-1 focus:ring-amber-500/50 transition-all resize-none">{{ old('special_requests') }}</textarea>
                @error('special_requests')
                    <p class="text-red-400 text-sm mt-2">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6">
                <label for="status" class="block text-sm font-medium text-gray-400 mb-2">Status <span class="text-amber-500">*</span></label>
                <select name="status" id="status" required
                    class="w-full px-4 py-3 bg-[#0f0f0f] border border-gray-700 rounded-lg text-white focus:outline-none focus:border-amber-500/50 focus:ring-1 focus:ring-amber-500/50 transition-all">
                    <option value="pending" {{ old('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="confirmed" {{ old('status') == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                    <option value="cancelled" {{ old('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                    <option value="completed" {{ old('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                </select>
                @error('status')
                    <p class="text-red-400 text-sm mt-2">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex items-center gap-4 pt-4 border-t border-gray-800">
                <button type="submit" class="inline-flex items-center px-6 py-3 bg-amber-500 hover:bg-amber-600 text-[#0a0a0a] font-semibold text-sm rounded-lg transition-all duration-200">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Create Reservation
                </button>
                <a href="{{ route('admin.reservations.index') }}" class="text-gray-500 hover:text-gray-300 transition-colors">
                    Cancel
                </a>
            </div>
        </form>
    </div>
@endsection

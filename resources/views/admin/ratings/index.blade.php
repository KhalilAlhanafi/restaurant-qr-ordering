@extends('layouts.admin')

@section('title', 'Customer Ratings')

@section('content')
    <div class="space-y-8">
        <!-- Header -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-end gap-4">
            <div>
                <h1 class="text-4xl md:text-5xl font-serif italic text-white mb-2 tracking-wide">Guest Feedback</h1>
                <p class="text-gray-400 text-sm tracking-wide">Monitor and analyze customer satisfaction</p>
            </div>
            <div class="flex items-center gap-4">
                <div class="bg-[#1a1a1a] border border-white/5 rounded-full px-4 py-2 flex items-center gap-3">
                    <span class="text-[10px] uppercase tracking-widest text-gray-500">Overall Score</span>
                    <span class="text-amber-500 font-bold">{{ number_format($overallAvg, 1) }} / 5.0</span>
                </div>
            </div>
        </div>

        <!-- Rating Stats -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div
                class="bg-[#1a1a1a] rounded-xl border border-white/5 p-6 relative overflow-hidden group hover:border-white/10 transition-all">
                <div class="flex justify-between items-start mb-4">
                    <span class="text-[10px] uppercase tracking-[0.2em] text-gray-500">Food Quality</span>
                    <span class="text-amber-500">🍽️</span>
                </div>
                <div class="flex items-baseline gap-2">
                    <span class="text-4xl font-serif text-white">{{ number_format($avgFood, 1) }}</span>
                    <div class="flex text-amber-500 text-[10px]">
                        @for ($i = 1; $i <= 5; $i++)
                            <span>{{ $i <= round($avgFood) ? '★' : '☆' }}</span>
                        @endfor
                    </div>
                </div>
            </div>

            <div
                class="bg-[#1a1a1a] rounded-xl border border-white/5 p-6 relative overflow-hidden group hover:border-white/10 transition-all">
                <div class="flex justify-between items-start mb-4">
                    <span class="text-[10px] uppercase tracking-[0.2em] text-gray-500">Service</span>
                    <span class="text-amber-500">✨</span>
                </div>
                <div class="flex items-baseline gap-2">
                    <span class="text-4xl font-serif text-white">{{ number_format($avgService, 1) }}</span>
                    <div class="flex text-amber-500 text-[10px]">
                        @for ($i = 1; $i <= 5; $i++)
                            <span>{{ $i <= round($avgService) ? '★' : '☆' }}</span>
                        @endfor
                    </div>
                </div>
            </div>

            <div
                class="bg-[#1a1a1a] rounded-xl border border-white/5 p-6 relative overflow-hidden group hover:border-white/10 transition-all">
                <div class="flex justify-between items-start mb-4">
                    <span class="text-[10px] uppercase tracking-[0.2em] text-gray-500">Ambiance</span>
                    <span class="text-amber-500">🍷</span>
                </div>
                <div class="flex items-baseline gap-2">
                    <span class="text-4xl font-serif text-white">{{ number_format($avgAmbiance, 1) }}</span>
                    <div class="flex text-amber-500 text-[10px]">
                        @for ($i = 1; $i <= 5; $i++)
                            <span>{{ $i <= round($avgAmbiance) ? '★' : '☆' }}</span>
                        @endfor
                    </div>
                </div>
            </div>
        </div>

        <!-- Latest Feedback -->
        <div class="bg-[#1a1a1a] rounded-2xl border border-white/5 overflow-hidden">
            <div class="px-8 py-6 border-b border-white/5 flex justify-between items-center">
                <h2 class="text-xl font-serif text-white italic">Recent Testimonials</h2>
                <span class="text-[10px] uppercase tracking-widest text-gray-500">{{ $ratings->total() }} total
                    entries</span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="text-[10px] uppercase tracking-[0.2em] text-gray-500 border-b border-white/5">
                            <th class="px-8 py-4 font-medium">Order / Table</th>
                            <th class="px-8 py-4 font-medium">Scores</th>
                            <th class="px-8 py-4 font-medium">Comment</th>
                            <th class="px-8 py-4 font-medium text-right">Date</th>
                            <th class="px-8 py-4 font-medium text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/5">
                        @forelse($ratings as $rating)
                            <tr class="group hover:bg-white/[0.02] transition-colors">
                                <td class="px-8 py-6">
                                    <div class="flex flex-col">
                                        <a href="{{ route('admin.orders.show', $rating->order_id) }}"
                                            class="text-white font-medium hover:text-amber-500 transition-colors">
                                            #{{ $rating->order_id }}
                                        </a>
                                        <span class="text-[10px] uppercase tracking-wider text-gray-500 mt-1">
                                            Table {{ $rating->table->table_number ?? 'N/A' }}
                                        </span>
                                    </div>
                                </td>
                                <td class="px-8 py-6">
                                    <div class="space-y-1">
                                        <div class="flex items-center gap-2">
                                            <span
                                                class="text-[9px] uppercase tracking-tighter text-gray-600 w-12">Food</span>
                                            <div class="flex text-amber-500 text-[10px]">
                                                @for ($i = 1; $i <= 5; $i++)
                                                    <span>{{ $i <= $rating->food_rating ? '★' : '☆' }}</span>
                                                @endfor
                                            </div>
                                        </div>
                                        <div class="flex items-center gap-2">
                                            <span
                                                class="text-[9px] uppercase tracking-tighter text-gray-600 w-12">Service</span>
                                            <div class="flex text-amber-500 text-[10px]">
                                                @for ($i = 1; $i <= 5; $i++)
                                                    <span>{{ $i <= $rating->service_rating ? '★' : '☆' }}</span>
                                                @endfor
                                            </div>
                                        </div>
                                        <div class="flex items-center gap-2">
                                            <span
                                                class="text-[9px] uppercase tracking-tighter text-gray-600 w-12">Amb.</span>
                                            <div class="flex text-amber-500 text-[10px]">
                                                @for ($i = 1; $i <= 5; $i++)
                                                    <span>{{ $i <= $rating->ambiance_rating ? '★' : '☆' }}</span>
                                                @endfor
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-8 py-6">
                                    @if ($rating->comment)
                                        <p class="text-gray-400 text-sm italic line-clamp-2 max-w-md">
                                            "{{ $rating->comment }}"</p>
                                    @else
                                        <span class="text-gray-600 text-xs italic">No comment left</span>
                                    @endif
                                </td>
                                <td class="px-8 py-6 text-right">
                                    <div class="flex flex-col items-end">
                                        <span class="text-white text-sm">{{ $rating->created_at->format('M d, Y') }}</span>
                                        <span
                                            class="text-[10px] text-gray-600 uppercase">{{ $rating->created_at->format('H:i') }}</span>
                                    </div>
                                </td>
                                <td class="px-8 py-6 text-right">
                                    <form action="{{ route('admin.ratings.destroy', $rating) }}" method="POST"
                                        onsubmit="return confirm('Delete this feedback?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="text-gray-600 hover:text-red-500 transition-colors p-2">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-8 py-12 text-center text-gray-500 italic">
                                    No ratings received yet. Once guests start sharing their experience, they will appear
                                    here.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($ratings->hasPages())
                <div class="px-8 py-6 border-t border-white/5">
                    {{ $ratings->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Reservation;
use App\Models\RestaurantTable;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ReservationController extends Controller
{
    public function index()
    {
        $reservations = Reservation::with('table')
            ->orderBy('start_time', 'desc')
            ->get();
        return view('admin.reservations.index', compact('reservations'));
    }

    public function create()
    {
        $tables = RestaurantTable::where('status', '!=', 'cleaning')->get();
        return view('admin.reservations.create', compact('tables'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'table_id' => 'required|exists:restaurant_tables,id',
            'customer_name' => 'required|string|max:255',
            'customer_phone' => 'nullable|string|max:50',
            'party_size' => 'required|integer|min:1',
            'month' => 'required|integer|min:1|max:12',
            'day' => 'required|integer|min:1|max:31',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'special_requests' => 'nullable|string',
            'status' => 'required|in:pending,confirmed,cancelled,completed',
        ]);

        // Build datetime objects from month, day, and time
        $currentYear = date('Y');
        $startDateTime = Carbon::create($currentYear, $validated['month'], $validated['day'], 
            explode(':', $validated['start_time'])[0], explode(':', $validated['start_time'])[1], 0);
        $endDateTime = Carbon::create($currentYear, $validated['month'], $validated['day'], 
            explode(':', $validated['end_time'])[0], explode(':', $validated['end_time'])[1], 0);

        // If end time is earlier than start time, assume it's the next day
        if ($endDateTime->lt($startDateTime)) {
            $endDateTime->addDay();
        }

        // Check if the reservation is in the past
        if ($startDateTime->lt(Carbon::now())) {
            return redirect()->back()->withErrors(['start_time' => 'Reservation time cannot be in the past.'])->withInput();
        }

        // Replace the separate fields with datetime objects
        unset($validated['month'], $validated['day']);
        $validated['start_time'] = $startDateTime;
        $validated['end_time'] = $endDateTime;

        // Check for overlapping reservations
        $overlapping = Reservation::where('table_id', $validated['table_id'])
            ->where('status', '!=', 'cancelled')
            ->where(function ($query) use ($validated) {
                $query->whereBetween('start_time', [$validated['start_time'], $validated['end_time']])
                    ->orWhereBetween('end_time', [$validated['start_time'], $validated['end_time']])
                    ->orWhere(function ($q) use ($validated) {
                        $q->where('start_time', '<=', $validated['start_time'])
                            ->where('end_time', '>=', $validated['end_time']);
                    });
            })
            ->first();

        if ($overlapping) {
            return redirect()->back()->withErrors(['start_time' => 'This table is already reserved during the selected time.'])->withInput();
        }

        Reservation::create($validated);
        return redirect()->route('admin.reservations.index')->with('success', 'Reservation created successfully');
    }

    public function edit(Reservation $reservation)
    {
        $tables = RestaurantTable::where('status', '!=', 'cleaning')->get();
        return view('admin.reservations.edit', compact('reservation', 'tables'));
    }

    public function update(Request $request, Reservation $reservation)
    {
        $validated = $request->validate([
            'table_id' => 'required|exists:restaurant_tables,id',
            'customer_name' => 'required|string|max:255',
            'customer_phone' => 'nullable|string|max:50',
            'party_size' => 'required|integer|min:1',
            'month' => 'required|integer|min:1|max:12',
            'day' => 'required|integer|min:1|max:31',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'special_requests' => 'nullable|string',
            'status' => 'required|in:pending,confirmed,cancelled,completed',
        ]);

        // Build datetime objects from month, day, and time
        $currentYear = date('Y');
        $startDateTime = Carbon::create($currentYear, $validated['month'], $validated['day'], 
            explode(':', $validated['start_time'])[0], explode(':', $validated['start_time'])[1], 0);
        $endDateTime = Carbon::create($currentYear, $validated['month'], $validated['day'], 
            explode(':', $validated['end_time'])[0], explode(':', $validated['end_time'])[1], 0);

        // If end time is earlier than start time, assume it's the next day
        if ($endDateTime->lt($startDateTime)) {
            $endDateTime->addDay();
        }

        // Replace the separate fields with datetime objects
        unset($validated['month'], $validated['day']);
        $validated['start_time'] = $startDateTime;
        $validated['end_time'] = $endDateTime;

        // Check for overlapping reservations (excluding current reservation)
        $overlapping = Reservation::where('table_id', $validated['table_id'])
            ->where('id', '!=', $reservation->id)
            ->where('status', '!=', 'cancelled')
            ->where(function ($query) use ($validated) {
                $query->whereBetween('start_time', [$validated['start_time'], $validated['end_time']])
                    ->orWhereBetween('end_time', [$validated['start_time'], $validated['end_time']])
                    ->orWhere(function ($q) use ($validated) {
                        $q->where('start_time', '<=', $validated['start_time'])
                            ->where('end_time', '>=', $validated['end_time']);
                    });
            })
            ->first();

        if ($overlapping) {
            return redirect()->back()->withErrors(['start_time' => 'This table is already reserved during the selected time.'])->withInput();
        }

        $reservation->update($validated);
        return redirect()->route('admin.reservations.index')->with('success', 'Reservation updated successfully');
    }

    public function destroy(Reservation $reservation)
    {
        $reservation->delete();
        return redirect()->route('admin.reservations.index')->with('success', 'Reservation deleted successfully');
    }

    public function timeline(Request $request)
    {
        $request->validate([
            'month' => 'nullable|integer|min:1|max:12',
            'week' => 'nullable|integer|min:1|max:5',
        ]);

        $tables = RestaurantTable::all();
        
        // Default to current month and week if not provided
        $currentMonth = $request->input('month', Carbon::now()->month);
        $currentWeek = $request->input('week', Carbon::now()->weekOfMonth);
        
        // Calculate the start and end dates for the selected week
        $year = Carbon::now()->year;
        $firstDayOfMonth = Carbon::create($year, $currentMonth, 1);
        
        // Calculate the start of the selected week
        $weekStart = $firstDayOfMonth->copy()->addWeeks($currentWeek - 1);
        if ($currentWeek > 1) {
            $weekStart->startOfWeek(Carbon::MONDAY);
        }
        
        // Calculate the end of the selected week (7 days)
        $weekEnd = $weekStart->copy()->addDays(6)->endOfDay();

        $reservations = Reservation::with('table')
            ->where('start_time', '>=', $weekStart)
            ->where('start_time', '<=', $weekEnd)
            ->where('status', '!=', 'cancelled')
            ->orderBy('start_time')
            ->get();

        return view('admin.reservations.timeline', compact('tables', 'reservations', 'weekStart', 'weekEnd', 'currentMonth', 'currentWeek'));
    }
}

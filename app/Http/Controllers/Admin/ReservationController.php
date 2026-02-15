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
            'start_time' => 'required|date|after:now',
            'end_time' => 'required|date|after:start_time',
            'special_requests' => 'nullable|string',
            'status' => 'required|in:pending,confirmed,cancelled,completed',
        ]);

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
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time',
            'special_requests' => 'nullable|string',
            'status' => 'required|in:pending,confirmed,cancelled,completed',
        ]);

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

    public function timeline()
    {
        $tables = RestaurantTable::all();
        $today = Carbon::today();
        $weekEnd = Carbon::today()->addDays(7);

        $reservations = Reservation::with('table')
            ->where('start_time', '>=', $today)
            ->where('start_time', '<', $weekEnd)
            ->where('status', '!=', 'cancelled')
            ->orderBy('start_time')
            ->get();

        return view('admin.reservations.timeline', compact('tables', 'reservations', 'today', 'weekEnd'));
    }
}

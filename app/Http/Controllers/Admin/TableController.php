<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RestaurantTable;
use Illuminate\Http\Request;

class TableController extends Controller
{
    public function index()
    {
        $tables = RestaurantTable::withCount(['orders' => function ($query) {
            $query->whereIn('status', ['pending', 'preparing', 'ready', 'served']);
        }])->get();
        return view('admin.tables.index', compact('tables'));
    }

    public function create()
    {
        // Get the next table number
        $lastTable = RestaurantTable::orderBy('table_number', 'desc')->first();
        $nextTableNumber = $lastTable ? ((int) $lastTable->table_number) + 1 : 1;
        
        return view('admin.tables.create', compact('nextTableNumber'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'table_number' => 'required|string|unique:restaurant_tables',
            'capacity' => 'required|integer|min:1',
            'location' => 'required|in:indoor,outdoor,balcony,second floor,second floor balcony',
        ]);

        // Generate unique QR token
        $validated['qr_token'] = bin2hex(random_bytes(8));
        // Set default status to available
        $validated['status'] = 'available';

        RestaurantTable::create($validated);
        return redirect()->route('admin.tables.index')->with('success', 'Table created successfully');
    }

    public function edit(RestaurantTable $table)
    {
        return view('admin.tables.edit', compact('table'));
    }

    public function update(Request $request, RestaurantTable $table)
    {
        $validated = $request->validate([
            'table_number' => 'required|string|unique:restaurant_tables,table_number,' . $table->id,
            'capacity' => 'required|integer|min:1',
            'location' => 'required|in:indoor,outdoor,balcony,second floor,second floor balcony',
        ]);

        $table->update($validated);
        return redirect()->route('admin.tables.index')->with('success', 'Table updated successfully');
    }

    public function destroy(RestaurantTable $table)
    {
        $table->delete();
        return redirect()->route('admin.tables.index')->with('success', 'Table deleted successfully');
    }
}

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
        return view('admin.tables.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'table_number' => 'required|string|unique:restaurant_tables',
            'capacity' => 'required|integer|min:1',
            'location' => 'nullable|string',
            'status' => 'required|in:available,occupied,reserved,cleaning',
        ]);

        // Generate unique QR token
        $validated['qr_token'] = bin2hex(random_bytes(8));

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
            'location' => 'nullable|string',
            'status' => 'required|in:available,occupied,reserved,cleaning',
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

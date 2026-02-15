<?php

namespace App\Http\Controllers;

use App\Models\ItemCategory;
use App\Models\RestaurantTable;

class MenuController extends Controller
{
    public function index()
    {
        $tableId = session('table_id');

        if (!$tableId) {
            return redirect()->route('qr.required');
        }

        $table = RestaurantTable::find($tableId);
        $categories = ItemCategory::with(['items' => function ($query) {
            $query->where('is_available', true);
        }])
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        return view('menu.index', compact('categories', 'table'));
    }
}

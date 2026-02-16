<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ItemCategory;
use App\Models\Item;
use App\Models\RestaurantTable;
use App\Models\Order;
use App\Models\Reservation;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'categories' => ItemCategory::count(),
            'items' => Item::count(),
            'tables' => RestaurantTable::count(),
            'active_orders' => Order::whereIn('status', ['pending', 'preparing', 'ready'])->count(),
            'today_reservations' => Reservation::whereDate('start_time', today())->count(),
            'total_revenue' => Order::whereDate('created_at', today())->sum('total_amount'),
        ];

        $recentOrders = Order::with(['table', 'orderItems.item'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        $upcomingReservations = Reservation::with('table')
            ->where('start_time', '>=', now())
            ->whereIn('status', ['pending', 'confirmed'])
            ->orderBy('start_time')
            ->limit(5)
            ->get();

        $tables = RestaurantTable::all();

        return view('admin.dashboard', compact('stats', 'recentOrders', 'upcomingReservations', 'tables'));
    }
}

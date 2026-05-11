<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;

class StationController extends Controller
{
    public function index($station)
    {
        if (!in_array($station, ['kitchen', 'bar', 'shisha', 'cash'])) {
            abort(404);
        }

        return view('admin.stations.index', compact('station'));
    }

    public function getOrders($station)
    {
        $orders = Order::with(['table', 'orderItems' => function($query) use ($station) {
            $query->whereHas('item.category', function($q) use ($station) {
                $q->where('station', $station);
            })->with('item');
        }])
        ->whereHas('orderItems.item.category', function($query) use ($station) {
            $query->where('station', $station);
        })
        ->where('is_checked_out', false)
        ->orderBy('created_at', 'desc')
        ->get();

        // Filter out orders that have no items for this station after the join
        $orders = $orders->filter(function($order) {
            return $order->orderItems->count() > 0;
        });

        return response()->json($orders);
    }
}

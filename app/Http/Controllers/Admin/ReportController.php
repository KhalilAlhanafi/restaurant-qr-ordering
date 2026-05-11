<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ItemCategory;
use App\Models\Order;
use App\Models\OrderItem;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $startDate = $request->input('start_date', now()->subDays(30)->format('Y-m-d'));
        $endDate = $request->input('end_date', now()->format('Y-m-d'));

        $start = Carbon::parse($startDate)->startOfDay();
        $end = Carbon::parse($endDate)->endOfDay();

        // Sales Overview: Daily revenue
        $dailyRevenue = Order::whereBetween('created_at', [$start, $end])
            ->whereIn('status', ['completed', 'served'])
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('SUM(total_amount) as revenue'))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Sales Overview: Weekly revenue
        $weeklyRevenue = Order::whereBetween('created_at', [$start, $end])
            ->whereIn('status', ['completed', 'served'])
            ->select(
                DB::raw('YEARWEEK(created_at, 1) as yearweek'),
                DB::raw('MIN(DATE(created_at)) as week_start'),
                DB::raw('SUM(total_amount) as revenue')
            )
            ->groupBy('yearweek')
            ->orderBy('yearweek')
            ->get()
            ->map(function ($row) {
                $row->week_label = Carbon::parse($row->week_start)->format('M d');
                return $row;
            });

        // Sales Overview: Monthly revenue
        $monthlyRevenue = Order::whereBetween('created_at', [$start, $end])
            ->whereIn('status', ['completed', 'served'])
            ->select(
                DB::raw('DATE_FORMAT(created_at, "%Y-%m") as month'),
                DB::raw('SUM(total_amount) as revenue')
            )
            ->groupBy('month')
            ->orderBy('month')
            ->get()
            ->map(function ($row) {
                $row->month_label = Carbon::parse($row->month . '-01')->format('M Y');
                return $row;
            });

        // Peak Hours
        $peakHours = Order::whereBetween('created_at', [$start, $end])
            ->whereIn('status', ['completed', 'served'])
            ->select(DB::raw('HOUR(created_at) as hour'), DB::raw('COUNT(*) as orders_count'))
            ->groupBy('hour')
            ->orderBy('hour')
            ->get()
            ->keyBy('hour');

        $peakHoursData = collect(range(0, 23))->map(function ($hour) use ($peakHours) {
            return [
                'hour' => $hour,
                'label' => sprintf('%02d:00', $hour),
                'orders_count' => $peakHours->get($hour)->orders_count ?? 0,
            ];
        });

        // Top Selling Items by Quantity
        $topItemsByQuantity = OrderItem::join('items', 'order_items.item_id', '=', 'items.id')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->whereBetween('orders.created_at', [$start, $end])
            ->whereIn('orders.status', ['completed', 'served'])
            ->select('items.id', 'items.name', DB::raw('SUM(order_items.quantity) as total_quantity'), DB::raw('SUM(order_items.subtotal) as total_revenue'))
            ->groupBy('items.id', 'items.name')
            ->orderByDesc('total_quantity')
            ->limit(10)
            ->get();

        // Top Selling Items by Revenue
        $topItemsByRevenue = OrderItem::join('items', 'order_items.item_id', '=', 'items.id')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->whereBetween('orders.created_at', [$start, $end])
            ->whereIn('orders.status', ['completed', 'served'])
            ->select('items.id', 'items.name', DB::raw('SUM(order_items.quantity) as total_quantity'), DB::raw('SUM(order_items.subtotal) as total_revenue'))
            ->groupBy('items.id', 'items.name')
            ->orderByDesc('total_revenue')
            ->limit(10)
            ->get();

        // Customer Loyalty
        $loyalCustomers = Order::whereBetween('created_at', [$start, $end])
            ->whereIn('status', ['completed', 'served'])
            ->whereNotNull('customer_phone')
            ->select('customer_phone', 'customer_name', DB::raw('COUNT(*) as order_count'), DB::raw('SUM(total_amount) as total_spent'))
            ->groupBy('customer_phone', 'customer_name')
            ->orderByDesc('order_count')
            ->limit(10)
            ->get();

        // Category Performance
        $categoryPerformance = OrderItem::join('items', 'order_items.item_id', '=', 'items.id')
            ->join('item_categories', 'items.category_id', '=', 'item_categories.id')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->whereBetween('orders.created_at', [$start, $end])
            ->whereIn('orders.status', ['completed', 'served'])
            ->select('item_categories.id', 'item_categories.name', 'item_categories.station', DB::raw('SUM(order_items.subtotal) as total_revenue'), DB::raw('SUM(order_items.quantity) as total_quantity'))
            ->groupBy('item_categories.id', 'item_categories.name', 'item_categories.station')
            ->orderByDesc('total_revenue')
            ->get();

        // Summary stats
        $totalRevenue = Order::whereBetween('created_at', [$start, $end])
            ->whereIn('status', ['completed', 'served'])
            ->sum('total_amount');

        $totalOrders = Order::whereBetween('created_at', [$start, $end])
            ->whereIn('status', ['completed', 'served'])
            ->count();

        $avgOrderValue = $totalOrders > 0 ? $totalRevenue / $totalOrders : 0;

        $totalItemsSold = OrderItem::join('orders', 'order_items.order_id', '=', 'orders.id')
            ->whereBetween('orders.created_at', [$start, $end])
            ->whereIn('orders.status', ['completed', 'served'])
            ->sum('order_items.quantity');

        return view('admin.reports', compact(
            'startDate', 'endDate',
            'dailyRevenue', 'weeklyRevenue', 'monthlyRevenue',
            'peakHoursData',
            'topItemsByQuantity', 'topItemsByRevenue',
            'loyalCustomers',
            'categoryPerformance',
            'totalRevenue', 'totalOrders', 'avgOrderValue', 'totalItemsSold'
        ));
    }

    public function exportCsv(Request $request)
    {
        $type = $request->input('type', 'sales');
        $startDate = $request->input('start_date', now()->subDays(30)->format('Y-m-d'));
        $endDate = $request->input('end_date', now()->format('Y-m-d'));

        $start = Carbon::parse($startDate)->startOfDay();
        $end = Carbon::parse($endDate)->endOfDay();

        $filename = $type . '_report_' . $startDate . '_to_' . $endDate . '.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function () use ($type, $start, $end) {
            $handle = fopen('php://output', 'w');
            fprintf($handle, chr(0xEF) . chr(0xBB) . chr(0xBF));

            switch ($type) {
                case 'sales':
                    fputcsv($handle, ['Date', 'Revenue']);
                    $data = Order::whereBetween('created_at', [$start, $end])
                        ->whereIn('status', ['completed', 'served'])
                        ->select(DB::raw('DATE(created_at) as date'), DB::raw('SUM(total_amount) as revenue'))
                        ->groupBy('date')
                        ->orderBy('date')
                        ->get();
                    foreach ($data as $row) {
                        fputcsv($handle, [$row->date, number_format($row->revenue, 2)]);
                    }
                    break;

                case 'peak_hours':
                    fputcsv($handle, ['Hour', 'Orders Count']);
                    $data = Order::whereBetween('created_at', [$start, $end])
                        ->whereIn('status', ['completed', 'served'])
                        ->select(DB::raw('HOUR(created_at) as hour'), DB::raw('COUNT(*) as orders_count'))
                        ->groupBy('hour')
                        ->orderBy('hour')
                        ->get();
                    foreach ($data as $row) {
                        fputcsv($handle, [sprintf('%02d:00', $row->hour), $row->orders_count]);
                    }
                    break;

                case 'top_items_quantity':
                    fputcsv($handle, ['Item Name', 'Total Quantity', 'Total Revenue']);
                    $data = OrderItem::join('items', 'order_items.item_id', '=', 'items.id')
                        ->join('orders', 'order_items.order_id', '=', 'orders.id')
                        ->whereBetween('orders.created_at', [$start, $end])
                        ->whereIn('orders.status', ['completed', 'served'])
                        ->select('items.name', DB::raw('SUM(order_items.quantity) as total_quantity'), DB::raw('SUM(order_items.subtotal) as total_revenue'))
                        ->groupBy('items.id', 'items.name')
                        ->orderByDesc('total_quantity')
                        ->limit(10)
                        ->get();
                    foreach ($data as $row) {
                        fputcsv($handle, [$row->name, $row->total_quantity, number_format($row->total_revenue, 2)]);
                    }
                    break;

                case 'top_items_revenue':
                    fputcsv($handle, ['Item Name', 'Total Quantity', 'Total Revenue']);
                    $data = OrderItem::join('items', 'order_items.item_id', '=', 'items.id')
                        ->join('orders', 'order_items.order_id', '=', 'orders.id')
                        ->whereBetween('orders.created_at', [$start, $end])
                        ->whereIn('orders.status', ['completed', 'served'])
                        ->select('items.name', DB::raw('SUM(order_items.quantity) as total_quantity'), DB::raw('SUM(order_items.subtotal) as total_revenue'))
                        ->groupBy('items.id', 'items.name')
                        ->orderByDesc('total_revenue')
                        ->limit(10)
                        ->get();
                    foreach ($data as $row) {
                        fputcsv($handle, [$row->name, $row->total_quantity, number_format($row->total_revenue, 2)]);
                    }
                    break;

                case 'customers':
                    fputcsv($handle, ['Customer Phone', 'Customer Name', 'Order Count', 'Total Spent']);
                    $data = Order::whereBetween('created_at', [$start, $end])
                        ->whereIn('status', ['completed', 'served'])
                        ->whereNotNull('customer_phone')
                        ->select('customer_phone', 'customer_name', DB::raw('COUNT(*) as order_count'), DB::raw('SUM(total_amount) as total_spent'))
                        ->groupBy('customer_phone', 'customer_name')
                        ->orderByDesc('order_count')
                        ->limit(10)
                        ->get();
                    foreach ($data as $row) {
                        fputcsv($handle, [$row->customer_phone, $row->customer_name ?? 'N/A', $row->order_count, number_format($row->total_spent, 2)]);
                    }
                    break;

                case 'categories':
                    fputcsv($handle, ['Category', 'Station', 'Total Revenue', 'Total Quantity']);
                    $data = OrderItem::join('items', 'order_items.item_id', '=', 'items.id')
                        ->join('item_categories', 'items.category_id', '=', 'item_categories.id')
                        ->join('orders', 'order_items.order_id', '=', 'orders.id')
                        ->whereBetween('orders.created_at', [$start, $end])
                        ->whereIn('orders.status', ['completed', 'served'])
                        ->select('item_categories.name', 'item_categories.station', DB::raw('SUM(order_items.subtotal) as total_revenue'), DB::raw('SUM(order_items.quantity) as total_quantity'))
                        ->groupBy('item_categories.id', 'item_categories.name', 'item_categories.station')
                        ->orderByDesc('total_revenue')
                        ->get();
                    foreach ($data as $row) {
                        fputcsv($handle, [$row->name, $row->station ?? 'N/A', number_format($row->total_revenue, 2), $row->total_quantity]);
                    }
                    break;
            }

            fclose($handle);
        };

        return new StreamedResponse($callback, 200, $headers);
    }

    public function exportPdf(Request $request)
    {
        $type = $request->input('type', 'sales');
        $startDate = $request->input('start_date', now()->subDays(30)->format('Y-m-d'));
        $endDate = $request->input('end_date', now()->format('Y-m-d'));

        $start = Carbon::parse($startDate)->startOfDay();
        $end = Carbon::parse($endDate)->endOfDay();

        $data = [];
        $reportTitle = '';

        switch ($type) {
            case 'sales':
                $reportTitle = 'Daily Sales Report';
                $data = Order::whereBetween('created_at', [$start, $end])
                    ->whereIn('status', ['completed', 'served'])
                    ->select(DB::raw('DATE(created_at) as date'), DB::raw('SUM(total_amount) as revenue'))
                    ->groupBy('date')
                    ->orderBy('date')
                    ->get();
                break;

            case 'peak_hours':
                $reportTitle = 'Peak Hours Report';
                $data = Order::whereBetween('created_at', [$start, $end])
                    ->whereIn('status', ['completed', 'served'])
                    ->select(DB::raw('HOUR(created_at) as hour'), DB::raw('COUNT(*) as orders_count'))
                    ->groupBy('hour')
                    ->orderBy('hour')
                    ->get();
                break;

            case 'top_items_quantity':
                $reportTitle = 'Top Selling Items by Quantity';
                $data = OrderItem::join('items', 'order_items.item_id', '=', 'items.id')
                    ->join('orders', 'order_items.order_id', '=', 'orders.id')
                    ->whereBetween('orders.created_at', [$start, $end])
                    ->whereIn('orders.status', ['completed', 'served'])
                    ->select('items.name', DB::raw('SUM(order_items.quantity) as total_quantity'), DB::raw('SUM(order_items.subtotal) as total_revenue'))
                    ->groupBy('items.id', 'items.name')
                    ->orderByDesc('total_quantity')
                    ->limit(10)
                    ->get();
                break;

            case 'top_items_revenue':
                $reportTitle = 'Top Selling Items by Revenue';
                $data = OrderItem::join('items', 'order_items.item_id', '=', 'items.id')
                    ->join('orders', 'order_items.order_id', '=', 'orders.id')
                    ->whereBetween('orders.created_at', [$start, $end])
                    ->whereIn('orders.status', ['completed', 'served'])
                    ->select('items.name', DB::raw('SUM(order_items.quantity) as total_quantity'), DB::raw('SUM(order_items.subtotal) as total_revenue'))
                    ->groupBy('items.id', 'items.name')
                    ->orderByDesc('total_revenue')
                    ->limit(10)
                    ->get();
                break;

            case 'customers':
                $reportTitle = 'Customer Loyalty Report';
                $data = Order::whereBetween('created_at', [$start, $end])
                    ->whereIn('status', ['completed', 'served'])
                    ->whereNotNull('customer_phone')
                    ->select('customer_phone', 'customer_name', DB::raw('COUNT(*) as order_count'), DB::raw('SUM(total_amount) as total_spent'))
                    ->groupBy('customer_phone', 'customer_name')
                    ->orderByDesc('order_count')
                    ->limit(10)
                    ->get();
                break;

            case 'categories':
                $reportTitle = 'Category Performance Report';
                $data = OrderItem::join('items', 'order_items.item_id', '=', 'items.id')
                    ->join('item_categories', 'items.category_id', '=', 'item_categories.id')
                    ->join('orders', 'order_items.order_id', '=', 'orders.id')
                    ->whereBetween('orders.created_at', [$start, $end])
                    ->whereIn('orders.status', ['completed', 'served'])
                    ->select('item_categories.name', 'item_categories.station', DB::raw('SUM(order_items.subtotal) as total_revenue'), DB::raw('SUM(order_items.quantity) as total_quantity'))
                    ->groupBy('item_categories.id', 'item_categories.name', 'item_categories.station')
                    ->orderByDesc('total_revenue')
                    ->get();
                break;
        }

        if (app()->bound('dompdf.wrapper')) {
            $pdf = app('dompdf.wrapper');
            $pdf->loadView('admin.reports.pdf', compact('data', 'reportTitle', 'startDate', 'endDate', 'type'));
            return $pdf->download($type . '_report_' . $startDate . '_to_' . $endDate . '.pdf');
        }

        // Fallback: render print-friendly HTML page for browser print-to-PDF
        return view('admin.reports.pdf', compact('data', 'reportTitle', 'startDate', 'endDate', 'type'))->with('printMode', true);
    }
}

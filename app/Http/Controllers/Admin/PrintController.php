<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Services\PrintService;
use Illuminate\Http\Request;

class PrintController extends Controller
{
    protected $printService;

    public function __construct(PrintService $printService)
    {
        $this->printService = $printService;
    }

    public function printReceipt(Order $order)
    {
        $success = $this->printService->printReceipt($order);

        if ($success) {
            return response()->json(['success' => true, 'message' => 'Receipt sent to printer']);
        }

        return response()->json([
            'success' => false,
            'message' => 'Failed to print receipt. Check printer connection or logs.'
        ], 500);
    }

    public function printKitchen(Order $order)
    {
        $success = $this->printService->printKitchenOrder($order);

        if ($success) {
            return response()->json(['success' => true, 'message' => 'Kitchen order sent to printer']);
        }

        return response()->json([
            'success' => false,
            'message' => 'Failed to print kitchen order. Check printer connection or logs.'
        ], 500);
    }
}

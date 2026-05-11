<?php

namespace App\Http\Controllers;

use App\Models\WaiterCall;
use Illuminate\Http\Request;

class WaiterCallController extends Controller
{
    /**
     * Customer calls the waiter from the menu page.
     */
    public function store(Request $request)
    {
        $tableId = session('table_id');

        if (!$tableId) {
            return response()->json(['success' => false, 'message' => 'Table not identified.'], 400);
        }

        // Prevent duplicate pending calls from the same table
        $existing = WaiterCall::where('table_id', $tableId)
            ->where('status', 'pending')
            ->first();

        if ($existing) {
            return response()->json([
                'success' => true,
                'message' => 'already_sent',
                'waiter_call' => $existing,
            ]);
        }

        $call = WaiterCall::create([
            'table_id' => $tableId,
            'status' => 'pending',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'sent',
            'waiter_call' => $call,
        ]);
    }

    /**
     * Admin fetches all pending waiter calls (polled from dashboard).
     */
    public function pending()
    {
        $calls = WaiterCall::with('table')
            ->where('status', 'pending')
            ->orderBy('created_at', 'asc')
            ->get()
            ->map(function ($call) {
                return [
                    'id' => $call->id,
                    'table_number' => $call->table->table_number ?? 'N/A',
                    'created_at' => $call->created_at->diffForHumans(),
                    'created_at_raw' => $call->created_at->toISOString(),
                ];
            });

        return response()->json(['calls' => $calls]);
    }

    /**
     * Admin marks a waiter call as resolved.
     */
    public function resolve(WaiterCall $waiterCall)
    {
        $waiterCall->update(['status' => 'resolved']);

        return response()->json(['success' => true]);
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Rating;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Log;

class RatingController extends Controller
{
    public function store(Request $request)
    {
        // Debug logging
        Log::info('Rating submission attempt', [
            'request_data' => $request->all(),
            'headers' => $request->headers->all()
        ]);

        try {
            $validated = $request->validate([
                'order_id' => 'required|exists:orders,id',
                'food_rating' => 'required|integer|min:1|max:5',
                'service_rating' => 'required|integer|min:1|max:5',
                'ambiance_rating' => 'required|integer|min:1|max:5',
                'comment' => 'nullable|string|max:1000',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Rating validation failed', [
                'errors' => $e->errors(),
                'request_data' => $request->all()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        }

        try {
            $order = Order::findOrFail($validated['order_id']);
            
            Log::info('Order found', [
                'order_id' => $order->id,
                'has_rating' => $order->rating()->exists()
            ]);

            // Check if already rated
            if ($order->rating()->exists()) {
                return response()->json([
                    'success' => false,
                    'message' => 'This order has already been rated.'
                ], 422);
            }

            $rating = Rating::create([
                'order_id' => $order->id,
                'table_id' => $order->table_id,
                'food_rating' => $validated['food_rating'],
                'service_rating' => $validated['service_rating'],
                'ambiance_rating' => $validated['ambiance_rating'],
                'comment' => $validated['comment'] ?? null,
            ]);

            Log::info('Rating created successfully', ['rating_id' => $rating->id]);

            return response()->json([
                'success' => true,
                'message' => 'Thank you for your feedback!'
            ]);
        } catch (ModelNotFoundException $e) {
            Log::error('Order not found', ['order_id' => $validated['order_id']]);
            return response()->json([
                'success' => false,
                'message' => 'Order not found.'
            ], 404);
        } catch (\Exception $e) {
            Log::error('Rating creation failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while saving your rating: ' . $e->getMessage()
            ], 500);
        }
    }
}

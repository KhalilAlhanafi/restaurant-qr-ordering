<?php

namespace App\Http\Middleware;

use App\Models\RestaurantTable;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IdentifyTable
{
    public function handle(Request $request, Closure $next): Response
    {
        // Extract token from URL parameter (e.g., /menu?token=abc123)
        $token = $request->query('token');

        if ($token) {
            // Find table by QR token
            $table = RestaurantTable::where('qr_token', $token)
                ->where('status', '!=', 'cleaning')
                ->first();

            if ($table) {
                // Store table_id in session
                session(['table_id' => $table->id]);
                session(['table_number' => $table->table_number]);
                session(['qr_token' => $token]);
            }
        }

        // If no token provided, check if table_id exists in session
        if (!session('table_id') && !$request->is('api/*')) {
            // Redirect to error page or show QR scanning required message
            if ($request->route() && $request->route()->getName() !== 'qr.required') {
                return redirect()->route('qr.required');
            }
        }

        return $next($request);
    }
}

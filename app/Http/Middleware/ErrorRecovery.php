<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class ErrorRecovery
{
    public function handle(Request $request, Closure $next): Response
    {
        try {
            return $next($request);
        } catch (\Illuminate\Database\QueryException $e) {
            Log::error('Database error in ErrorRecovery middleware', [
                'error' => $e->getMessage(),
                'url' => $request->fullUrl(),
                'method' => $request->method()
            ]);

            // If database is unavailable, show maintenance message
            if (str_contains($e->getMessage(), 'Connection refused') || 
                str_contains($e->getMessage(), 'No such host')) {
                
                if ($request->expectsJson()) {
                    return response()->json([
                        'error' => 'Database temporarily unavailable. Please try again.',
                        'retry_after' => 5
                    ], 503);
                }

                return response()->view('errors.database', [], 503);
            }

            throw $e;
        } catch (\Exception $e) {
            Log::error('Unexpected error in ErrorRecovery middleware', [
                'error' => $e->getMessage(),
                'url' => $request->fullUrl(),
                'trace' => $e->getTraceAsString()
            ]);

            // Return user-friendly error
            if ($request->expectsJson()) {
                return response()->json([
                    'error' => 'An error occurred. Please try again.',
                    'reference' => uniqid('err_')
                ], 500);
            }

            return response()->view('errors.generic', [
                'reference' => uniqid('err_')
            ], 500);
        }
    }
}

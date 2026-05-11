<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class StationAuth
{
    public function handle(Request $request, Closure $next)
    {
        if (!session('admin_authenticated') || !auth()->check()) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Authentication required',
                    'redirect' => route('admin.login')
                ], 401);
            }

            return redirect()->route('admin.login');
        }

        $user = auth()->user();
        $station = $request->route('station');

        // Admin can access everything
        if ($user->role === 'admin') {
            return $next($request);
        }

        // Station users can only access their assigned station
        if ($user->role !== 'station' || $user->station !== $station) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Access denied',
                    'redirect' => route('admin.stations.index', $user->station)
                ], 403);
            }

            return redirect()->route('admin.stations.index', $user->station)
                ->with('error', 'Access denied: you can only access your assigned station.');
        }

        return $next($request);
    }
}

<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AdminAuth
{
    public function handle(Request $request, Closure $next)
    {
        if (!session('admin_authenticated')) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Authentication required',
                    'redirect' => route('admin.login')
                ], 401);
            }
            
            return redirect()->route('admin.login');
        }

        return $next($request);
    }
}

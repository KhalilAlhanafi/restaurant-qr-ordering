<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CustomerRestriction
{
    public function handle(Request $request, Closure $next)
    {
        // Block admin access for anyone who was a customer (even after session clear)
        if (session('was_customer') && $request->is('admin/*')) {
            return redirect()->route('menu.index');
        }

        // If customer has table session, block admin access completely
        if (session('table_id') && $request->is('admin/*')) {
            return redirect()->route('menu.index');
        }

        // If customer has table session, restrict access to customer pages only
        if (session('table_id')) {
            $allowedRoutes = [
                'menu.index',
                'cart.index',
                'cart.add',
                'cart.update',
                'cart.remove',
                'cart.clear',
                'cart.summary',
                'checkout.index',
                'checkout.store',
                'order.confirmation',
                'checkout.finalize',
                'qr.scan',
                'qr.required',
                'language.set'
            ];

            // If trying to access other non-customer routes, redirect to menu
            if ($request->route() && !in_array($request->route()->getName(), $allowedRoutes)) {
                return redirect()->route('menu.index');
            }
        }

        return $next($request);
    }
}

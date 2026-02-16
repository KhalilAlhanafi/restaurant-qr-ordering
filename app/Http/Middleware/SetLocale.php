<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    public function handle(Request $request, Closure $next): Response
    {
        // Check if locale is stored in session
        if (session()->has('locale')) {
            $locale = session('locale');
            
            // Validate locale is supported
            if (in_array($locale, ['en', 'ar'])) {
                app()->setLocale($locale);
            }
        }

        return $next($request);
    }
}

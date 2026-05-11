<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('admin.auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        if (Auth::guard('web')->attempt($credentials)) {
            $request->session()->regenerate();

            // Clear any customer flags
            session()->forget(['was_customer', 'table_id', 'table_number', 'qr_token', 'cart', 'locale']);
            session(['admin_authenticated' => true, 'admin_logged_in' => true]);

            $user = Auth::guard('web')->user();

            if ($user->role === 'admin') {
                $redirect = route('admin.dashboard');
            } else {
                $redirect = route('admin.stations.index', $user->station);
            }

            return response()->json([
                'success' => true,
                'redirect' => $redirect
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Invalid credentials'
        ], 401);
    }

    public function logout(Request $request)
    {
        Auth::guard('web')->logout();
        session()->forget(['admin_authenticated', 'admin_logged_in']);
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login');
    }
}

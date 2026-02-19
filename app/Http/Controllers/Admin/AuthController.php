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
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        // Custom authentication logic
        if ($credentials['username'] === 'admin' && $credentials['password'] === 'Rest2026admin') {
            // Clear any customer flags and create a session for admin
            session()->forget(['was_customer', 'table_id', 'table_number', 'qr_token', 'cart', 'locale']);
            session(['admin_authenticated' => true, 'admin_logged_in' => true]);
            
            return response()->json([
                'success' => true,
                'redirect' => route('admin.dashboard')
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Invalid credentials'
        ], 401);
    }

    public function logout(Request $request)
    {
        session()->forget(['admin_authenticated', 'admin_logged_in']);
        
        return redirect()->route('admin.login');
    }
}

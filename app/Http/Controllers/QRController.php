<?php

namespace App\Http\Controllers;

use App\Models\RestaurantTable;
use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Writer\PngWriter;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel;
use Illuminate\Http\Request;

class QRController extends Controller
{
    public function scan($token)
    {
        $table = RestaurantTable::where('qr_token', $token)
            ->where('status', '!=', 'cleaning')
            ->first();

        if (!$table) {
            return view('qr.invalid');
        }

        // Check if this is a different table than before
        $previousTableId = session('table_id');
        if ($previousTableId && $previousTableId != $table->id) {
            // Clear cart when switching to a new table
            session()->forget('cart');
        }

        // Store in session
        session(['table_id' => $table->id]);
        session(['table_number' => $table->table_number]);
        session(['qr_token' => $token]);

        // Check if language already selected
        if (session()->has('locale')) {
            // Redirect to menu
            return redirect()->route('menu.index');
        }

        // Redirect to language selection
        return view('menu.language-select', compact('table'));
    }

    public function required()
    {
        return view('qr.required');
    }

    public function setLanguage(Request $request)
    {
        $validated = $request->validate([
            'locale' => 'required|in:en,ar',
            'redirect' => 'nullable|string'
        ]);

        // Store locale in session
        session(['locale' => $validated['locale']]);
        app()->setLocale($validated['locale']);

        // Redirect to specified page or menu
        $redirect = $validated['redirect'] ?? 'menu';
        
        if ($redirect === 'menu') {
            return redirect()->route('menu.index');
        }

        return redirect()->back();
    }

    public function generateAll()
    {
        $tables = RestaurantTable::all();
        return view('admin.qr-codes', compact('tables'));
    }

    public function generateQrImage($token)
    {
        // Build URL for QR code
        $url = url('/scan/' . $token);

        // Generate QR code
        $builder = new Builder();
        $result = $builder->build(
            writer: new PngWriter(),
            data: $url,
            encoding: new Encoding('UTF-8'),
            errorCorrectionLevel: ErrorCorrectionLevel::High,
            size: 200,
            margin: 10
        );

        // Return image response
        return response($result->getString())
            ->header('Content-Type', $result->getMimeType());
    }
}

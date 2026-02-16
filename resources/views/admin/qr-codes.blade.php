@extends('layouts.admin')

@section('title', 'QR Codes')

@section('content')
    <!-- Header -->
    <div class="mb-8">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl text-amber-500 tracking-wider mb-1">QR Codes</h1>
                <p class="text-xs uppercase tracking-[0.2em] text-gray-500">Print table QR codes for customers</p>
            </div>
            <button onclick="window.print()" class="inline-flex items-center px-6 py-3 bg-amber-500 hover:bg-amber-600 text-[#0a0a0a] font-semibold text-sm rounded-lg transition-all duration-200 group">
                <svg class="w-5 h-5 mr-2 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                </svg>
                Print QR Codes
            </button>
        </div>
        <p class="text-sm text-gray-400 mt-4 max-w-2xl">
            Print these QR codes and place them on the corresponding tables. Customers can scan these codes to access the menu and place orders.
        </p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
        @foreach($tables as $table)
        <div class="bg-[#1a1a1a] border border-gray-800 rounded-xl p-6 text-center hover:border-amber-500/30 transition-all duration-300">
            <div class="flex items-center justify-center gap-2 mb-4">
                <svg class="w-5 h-5 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"></path>
                </svg>
                <h4 class="text-lg font-semibold text-white">Table {{ $table->table_number }}</h4>
            </div>
            <div class="qr-code mb-4 bg-white p-3 rounded-lg inline-block">
                <img src="{{ route('admin.qr-code-image', $table->qr_token) }}" alt="QR Code for Table {{ $table->table_number }}" class="mx-auto" width="180" height="180">
            </div>
            <div class="table-info text-sm text-gray-400 space-y-1">
                <p><span class="text-gray-500">Capacity:</span> {{ $table->capacity }} people</p>
                <p><span class="text-gray-500">Location:</span> {{ ucfirst($table->location) }}</p>
            </div>
        </div>
        @endforeach
    </div>

    <style>
        @media print {
            body {
                background: white !important;
                print-color-adjust: exact;
                -webkit-print-color-adjust: exact;
            }

            .bg-[#1a1a1a] {
                background: white !important;
                box-shadow: none !important;
                border: 1px solid #ddd !important;
            }

            .qr-code {
                background: white !important;
            }

            .qr-code img {
                box-shadow: none !important;
            }

            /* Hide UI elements for print */
            button, header, aside, .mb-8 p {
                display: none !important;
            }

            /* Ensure proper spacing for print */
            .grid {
                grid-template-columns: repeat(auto-fill, minmax(250px, 1fr)) !important;
                gap: 20px !important;
            }

            .bg-[#1a1a1a] {
                page-break-inside: avoid;
            }

            h4 {
                color: #1a1a1a !important;
            }

            .text-gray-400, .text-gray-500 {
                color: #666 !important;
            }
        }
    </style>
@endsection

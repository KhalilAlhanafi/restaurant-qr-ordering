@extends('layouts.admin')

@section('title', 'QR Codes')
@section('header', 'QR Codes')

@section('content')
    <div class="bg-white rounded-lg shadow">
        <div class="px-6 py-4 border-b border-gray-200">
            <div class="flex justify-between items-center">
                <div>
                    <h3 class="text-lg font-semibold">Restaurant Table QR Codes</h3>
                    <p class="text-sm text-gray-600 mt-1">
                        Print these QR codes and place them on the corresponding tables.<br>
                        Customers can scan these codes to access the menu and place orders.
                    </p>
                </div>
                <button onclick="window.print()" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                    Print QR Codes
                </button>
            </div>
        </div>
        
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                @foreach($tables as $table)
                <div class="border border-gray-200 rounded-lg p-6 text-center hover:shadow-md transition-shadow">
                    <h4 class="text-lg font-semibold text-gray-800 mb-4">Table {{ $table->table_number }}</h4>
                    <div class="qr-code mb-4">
                        <img src="{{ route('admin.qr-code-image', $table->qr_token) }}" alt="QR Code for Table {{ $table->table_number }}" class="mx-auto rounded-lg shadow-sm" width="180" height="180">
                    </div>
                    <div class="table-info text-sm text-gray-600">
                        <p><strong>Capacity:</strong> {{ $table->capacity }} people</p>
                        <p><strong>Location:</strong> {{ ucfirst($table->location) }}</p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    
    <style>
        @media print {
            body { 
                background: white !important; 
                print-color-adjust: exact;
                -webkit-print-color-adjust: exact;
            }
            
            .bg-white {
                box-shadow: none !important;
                border: none !important;
            }
            
            .qr-code img {
                box-shadow: none !important;
                border: 1px solid #ddd !important;
            }
            
            .border-gray-200 {
                border: 1px solid #ddd !important;
                break-inside: avoid;
            }
            
            /* Hide print button and other UI elements */
            button, .bg-gray-100, header, aside {
                display: none !important;
            }
            
            /* Ensure proper spacing for print */
            .grid {
                grid-template-columns: repeat(auto-fill, minmax(250px, 1fr)) !important;
                gap: 20px !important;
            }
            
            .border-gray-200 {
                page-break-inside: avoid;
            }
        }
    </style>
@endsection

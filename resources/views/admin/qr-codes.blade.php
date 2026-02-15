<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Table QR Codes</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 20px;
            background: #f5f5f5;
        }
        h1 {
            text-align: center;
            color: #333;
        }
        .qr-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 30px;
            max-width: 1200px;
            margin: 0 auto;
        }
        .qr-card {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            text-align: center;
        }
        .qr-card h2 {
            margin-top: 0;
            color: #333;
        }
        .qr-code {
            margin: 20px 0;
        }
        .table-info {
            color: #666;
            margin-top: 15px;
        }
        @media print {
            body { background: white; }
            .qr-card {
                break-inside: avoid;
                box-shadow: none;
                border: 1px solid #ddd;
            }
        }
    </style>
</head>
<body>
    <h1>Restaurant Table QR Codes</h1>
    <p style="text-align: center; color: #666; margin-bottom: 30px;">
        Print this page and place the QR codes on the corresponding tables.<br>
        Customers can scan these codes to access the menu and place orders.
    </p>

    <div class="qr-grid">
        @foreach($tables as $table)
        <div class="qr-card">
            <h2>Table {{ $table->table_number }}</h2>
            <div class="qr-code">
                <img src="{{ route('admin.qr-code-image', $table->qr_token) }}" alt="QR Code" width="200" height="200">
            </div>
            <div class="table-info">
                <p><strong>Capacity:</strong> {{ $table->capacity }} people</p>
                <p><strong>Location:</strong> {{ $table->location ?? 'Main floor' }}</p>
            </div>
        </div>
        @endforeach
    </div>
</body>
</html>

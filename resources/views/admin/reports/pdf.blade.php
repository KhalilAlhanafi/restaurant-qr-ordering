<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ $reportTitle }}</title>
    <style>
        body { font-family: 'DejaVu Sans', sans-serif; font-size: 12px; color: #333; }
        h1 { font-size: 18px; margin-bottom: 5px; }
        h2 { font-size: 14px; color: #666; margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th, td { padding: 8px 10px; text-align: left; border-bottom: 1px solid #ddd; }
        th { background: #f5f5f5; font-weight: bold; font-size: 11px; text-transform: uppercase; }
        .text-right { text-align: right; }
        .footer { margin-top: 30px; font-size: 10px; color: #999; text-align: center; }
        .badge { display: inline-block; padding: 2px 6px; border-radius: 3px; font-size: 10px; text-transform: uppercase; }
        .badge-kitchen { background: #fff3e0; color: #e65100; }
        .badge-bar { background: #e3f2fd; color: #1565c0; }
        .badge-shisha { background: #f3e5f5; color: #7b1fa2; }
    </style>
</head>
<body>
    <h1>{{ $reportTitle }}</h1>
    <h2>Period: {{ $startDate }} to {{ $endDate }}</h2>

    <table>
        <thead>
            <tr>
                @if($type === 'sales')
                    <th>Date</th>
                    <th class="text-right">Revenue</th>
                @elseif($type === 'peak_hours')
                    <th>Hour</th>
                    <th class="text-right">Orders Count</th>
                @elseif(in_array($type, ['top_items_quantity', 'top_items_revenue']))
                    <th>Item Name</th>
                    <th class="text-right">Quantity</th>
                    <th class="text-right">Revenue</th>
                @elseif($type === 'customers')
                    <th>Phone</th>
                    <th>Name</th>
                    <th class="text-right">Orders</th>
                    <th class="text-right">Total Spent</th>
                @elseif($type === 'categories')
                    <th>Category</th>
                    <th>Station</th>
                    <th class="text-right">Quantity</th>
                    <th class="text-right">Revenue</th>
                @endif
            </tr>
        </thead>
        <tbody>
            @forelse($data as $row)
                <tr>
                    @if($type === 'sales')
                        <td>{{ $row->date }}</td>
                        <td class="text-right">${{ number_format($row->revenue, 2) }}</td>
                    @elseif($type === 'peak_hours')
                        <td>{{ sprintf('%02d:00', $row->hour) }}</td>
                        <td class="text-right">{{ $row->orders_count }}</td>
                    @elseif(in_array($type, ['top_items_quantity', 'top_items_revenue']))
                        <td>{{ $row->name }}</td>
                        <td class="text-right">{{ $row->total_quantity }}</td>
                        <td class="text-right">${{ number_format($row->total_revenue, 2) }}</td>
                    @elseif($type === 'customers')
                        <td>{{ $row->customer_phone }}</td>
                        <td>{{ $row->customer_name ?? 'N/A' }}</td>
                        <td class="text-right">{{ $row->order_count }}</td>
                        <td class="text-right">${{ number_format($row->total_spent, 2) }}</td>
                    @elseif($type === 'categories')
                        <td>{{ $row->name }}</td>
                        <td>
                            <span class="badge badge-{{ $row->station ?? 'general' }}">{{ $row->station ?? 'general' }}</span>
                        </td>
                        <td class="text-right">{{ $row->total_quantity }}</td>
                        <td class="text-right">${{ number_format($row->total_revenue, 2) }}</td>
                    @endif
                </tr>
            @empty
                <tr>
                    <td colspan="5" style="text-align:center; padding: 20px;">No data available for selected period.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        Generated on {{ now()->format('F d, Y H:i') }} by {{ config('app.name') }}
    </div>
</body>
</html>

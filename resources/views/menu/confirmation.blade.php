<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Confirmation - Table {{ $order->table->table_number }}</title>
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
            background: #f8f9fa;
            min-height: 100vh;
        }
        .success-header {
            background: #28a745;
            color: white;
            padding: 40px 20px;
            text-align: center;
        }
        .success-icon {
            font-size: 60px;
            margin-bottom: 15px;
        }
        .success-title {
            font-size: 24px;
            font-weight: 600;
            margin-bottom: 10px;
        }
        .success-message {
            font-size: 14px;
            opacity: 0.9;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .order-card {
            background: white;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 15px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        }
        .order-number {
            font-size: 18px;
            font-weight: 600;
            color: #333;
            margin-bottom: 5px;
        }
        .order-meta {
            color: #666;
            font-size: 14px;
        }
        .section-title {
            font-size: 16px;
            font-weight: 600;
            color: #333;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 1px solid #f0f0f0;
        }
        .item-list {
            list-style: none;
        }
        .item-list li {
            display: flex;
            justify-content: space-between;
            padding: 12px 0;
            border-bottom: 1px solid #f8f8f8;
        }
        .item-list li:last-child {
            border-bottom: none;
        }
        .item-info {
            display: flex;
            gap: 10px;
        }
        .item-qty {
            color: #666;
            min-width: 30px;
        }
        .item-name {
            color: #333;
        }
        .item-price {
            color: #28a745;
            font-weight: 500;
        }
        .item-price.hidden {
            color: #999;
            font-style: italic;
        }
        .est-time-box {
            background: #e8f4fd;
            border-radius: 8px;
            padding: 20px;
            text-align: center;
            margin-bottom: 20px;
        }
        .est-time-label {
            font-size: 14px;
            color: #666;
            margin-bottom: 8px;
        }
        .est-time-value {
            font-size: 28px;
            font-weight: 700;
            color: #007bff;
        }
        .total-row {
            display: flex;
            justify-content: space-between;
            padding: 15px 0;
            border-top: 2px solid #f0f0f0;
            font-size: 18px;
            font-weight: 600;
        }
        .special-requests {
            background: #fff9e6;
            border-radius: 8px;
            padding: 15px;
            margin-top: 15px;
        }
        .special-requests-label {
            font-size: 12px;
            color: #666;
            text-transform: uppercase;
            margin-bottom: 8px;
        }
        .special-requests-text {
            font-size: 14px;
            color: #333;
        }
        .status-badge {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
        }
        .status-pending {
            background: #fff3cd;
            color: #856404;
        }
        .status-preparing {
            background: #cce5ff;
            color: #004085;
        }
        .actions {
            margin-top: 20px;
            text-align: center;
        }
        .btn {
            display: inline-block;
            padding: 12px 30px;
            border-radius: 25px;
            text-decoration: none;
            font-weight: 600;
            margin: 0 10px;
        }
        .btn-primary {
            background: #007bff;
            color: white;
        }
        .btn-success {
            background: #28a745;
            color: white;
        }
        .btn-success:disabled {
            background: #6c757d;
            cursor: not-allowed;
        }
        .checkout-box {
            background: #d4edda;
            border-radius: 8px;
            padding: 20px;
            text-align: center;
            margin-bottom: 20px;
        }
        .checkout-text {
            font-size: 14px;
            color: #155724;
            margin-bottom: 15px;
        }
        .checked-out-badge {
            background: #28a745;
            color: white;
            padding: 10px 20px;
            border-radius: 25px;
            font-weight: 600;
            display: inline-block;
        }
    </style>
</head>
<body>
    <div class="success-header">
        <div class="success-icon">✓</div>
        <div class="success-title">
            @if($order->is_checked_out)
                Order Completed!
            @else
                Order Placed Successfully!
            @endif
        </div>
        <div class="success-message">
            @if($order->is_checked_out)
                Thank you for dining with us
            @else
                Your order has been sent to the kitchen. You can add more items or checkout when ready.
            @endif
        </div>
    </div>

    <div class="container">
        <div class="order-card">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
                <div>
                    <div class="order-number">Order #{{ $order->id }}</div>
                    <div class="order-meta">Table {{ $order->table->table_number }}</div>
                </div>
                <div style="text-align: right;">
                    <span class="status-badge status-{{ $order->status }}" style="display: block; margin-bottom: 5px;">{{ ucfirst($order->status) }}</span>
                    @if($order->is_checked_out)
                        <span class="checked-out-badge">✓ Checked Out</span>
                    @else
                        <span class="status-badge bg-yellow-100 text-yellow-800" style="background: #fff3cd; color: #856404;">Active Order</span>
                    @endif
                </div>
            </div>

            <div class="est-time-box">
                <div class="est-time-label">Estimated Ready Time</div>
                <div class="est-time-value">{{ $order->estimated_minutes }} min</div>
            </div>
        </div>

        <div class="order-card">
            <h3 class="section-title">Order Details</h3>
            <ul class="item-list">
                @foreach($order->orderItems as $orderItem)
                    <li>
                        <div class="item-info">
                            <span class="item-qty">{{ $orderItem->quantity }}×</span>
                            <span class="item-name">{{ $orderItem->item->name }}</span>
                        </div>
                        <span class="item-price {{ $orderItem->item->show_price ? '' : 'hidden' }}">
                            @if($orderItem->item->show_price)
                                ${{ number_format($orderItem->unit_price * $orderItem->quantity, 2) }}
                            @else
                                Price on request
                            @endif
                        </span>
                    </li>
                @endforeach
            </ul>

            @if($order->special_requests)
                <div class="special-requests">
                    <div class="special-requests-label">Special Requests</div>
                    <div class="special-requests-text">{{ $order->special_requests }}</div>
                </div>
            @endif

            <div class="total-row">
                <span>Total</span>
                <span>${{ number_format($order->total_amount, 2) }}</span>
            </div>
        </div>

        <div class="actions">
            @if(!$order->is_checked_out)
                <a href="/menu" class="btn btn-primary">Order More Items</a>
                <form action="{{ route('checkout.finalize') }}" method="POST" style="display: inline;">
                    @csrf
                    <button type="submit" class="btn btn-success" onclick="return confirm('Are you sure you want to checkout? This will finalize your order.')">✓ Checkout</button>
                </form>
            @else
                <a href="/menu" class="btn btn-primary">Start New Order</a>
            @endif
        </div>
    </div>
</body>
</html>

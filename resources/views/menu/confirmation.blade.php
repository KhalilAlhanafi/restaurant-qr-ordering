<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Order Confirmation - Table {{ $order->table->table_number }}</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            -webkit-tap-highlight-color: transparent;
        }
        
        :root {
            --bg-dark: #1a1a2e;
            --bg-card: #ffffff;
            --accent-gold: #d4af37;
            --accent-gold-light: #f4d03f;
            --text-dark: #1a1a2e;
            --text-muted: #6b7280;
            --text-light: #ffffff;
            --success: #10b981;
            --danger: #ef4444;
            --warning: #f59e0b;
            --shadow: 0 4px 20px rgba(0,0,0,0.15);
            --shadow-soft: 0 2px 10px rgba(0,0,0,0.08);
        }
        
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'SF Pro Display', 'Segoe UI', Roboto, sans-serif;
            background: var(--bg-dark);
            color: var(--text-dark);
            line-height: 1.5;
            min-height: 100vh;
        }
        
        /* Success Header */
        .success-header {
            background: linear-gradient(135deg, #2d2d44 0%, var(--bg-dark) 100%);
            padding: 50px 20px 30px;
            text-align: center;
            position: relative;
            overflow: hidden;
        }
        
        .success-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><circle cx="50" cy="50" r="40" fill="none" stroke="%23d4af37" stroke-width="0.5" opacity="0.1"/></svg>') repeat;
            background-size: 50px;
        }
        
        .success-icon {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, var(--accent-gold), var(--accent-gold-light));
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            font-size: 40px;
            box-shadow: 0 10px 40px rgba(212,175,55,0.4);
            animation: scaleIn 0.5s ease;
            position: relative;
            z-index: 1;
        }
        
        @keyframes scaleIn {
            0% { transform: scale(0); opacity: 0; }
            50% { transform: scale(1.1); }
            100% { transform: scale(1); opacity: 1; }
        }
        
        .success-title {
            font-size: 26px;
            font-weight: 700;
            color: var(--text-light);
            margin-bottom: 8px;
            position: relative;
            z-index: 1;
        }
        
        .success-message {
            font-size: 15px;
            color: rgba(255,255,255,0.7);
            max-width: 280px;
            margin: 0 auto;
            position: relative;
            z-index: 1;
        }
        
        /* Container */
        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            padding-bottom: 100px;
        }
        
        /* Cards */
        .order-card {
            background: var(--bg-card);
            border-radius: 20px;
            padding: 24px;
            margin-bottom: 16px;
            box-shadow: var(--shadow-soft);
        }
        
        /* Order Header */
        .order-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 20px;
            padding-bottom: 20px;
            border-bottom: 1px solid #f3f4f6;
        }
        
        .order-info h2 {
            font-size: 20px;
            font-weight: 700;
            color: var(--text-dark);
            margin-bottom: 4px;
        }
        
        .order-meta {
            font-size: 14px;
            color: var(--text-muted);
        }
        
        .status-wrapper {
            text-align: right;
        }
        
        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .status-pending {
            background: #fef3c7;
            color: #92400e;
        }
        
        .status-preparing {
            background: #dbeafe;
            color: #1e40af;
        }
        
        .status-ready {
            background: #d1fae5;
            color: #065f46;
        }
        
        .status-served {
            background: #e0e7ff;
            color: #3730a3;
        }
        
        .status-completed {
            background: var(--accent-gold);
            color: var(--bg-dark);
        }
        
        .pulse {
            width: 8px;
            height: 8px;
            background: currentColor;
            border-radius: 50%;
            animation: pulse 2s infinite;
        }
        
        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.5; }
        }
        
        /* Progress Tracker */
        .progress-tracker {
            display: flex;
            justify-content: space-between;
            margin: 20px 0;
            position: relative;
        }
        
        .progress-tracker::before {
            content: '';
            position: absolute;
            top: 15px;
            left: 15%;
            right: 15%;
            height: 2px;
            background: #e5e7eb;
            z-index: 0;
        }
        
        .progress-step {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 8px;
            z-index: 1;
        }
        
        .step-icon {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: #e5e7eb;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 14px;
            transition: all 0.3s ease;
        }
        
        .step-icon.active {
            background: linear-gradient(135deg, var(--accent-gold), var(--accent-gold-light));
            box-shadow: 0 4px 12px rgba(212,175,55,0.4);
        }
        
        .step-icon.completed {
            background: var(--success);
            color: white;
        }
        
        .step-label {
            font-size: 11px;
            color: var(--text-muted);
            font-weight: 600;
        }
        
        /* Estimated Time */
        .est-time-box {
            background: linear-gradient(135deg, rgba(212,175,55,0.1), rgba(212,175,55,0.05));
            border-radius: 16px;
            padding: 24px;
            text-align: center;
            border: 1px solid rgba(212,175,55,0.2);
        }
        
        .est-time-label {
            font-size: 13px;
            color: var(--text-muted);
            margin-bottom: 8px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        
        .est-time-value {
            font-size: 36px;
            font-weight: 800;
            color: var(--accent-gold);
        }
        
        .est-time-unit {
            font-size: 16px;
            color: var(--text-muted);
        }
        
        /* Section Title */
        .section-title {
            font-size: 18px;
            font-weight: 700;
            color: var(--text-dark);
            margin-bottom: 16px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .section-title::after {
            content: '';
            flex: 1;
            height: 1px;
            background: linear-gradient(90deg, #e5e7eb, transparent);
        }
        
        /* Item List */
        .item-list {
            list-style: none;
        }
        
        .item-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 16px 0;
            border-bottom: 1px solid #f3f4f6;
        }
        
        .item-row:last-child {
            border-bottom: none;
        }
        
        .item-info {
            display: flex;
            align-items: center;
            gap: 12px;
        }
        
        .item-qty {
            width: 28px;
            height: 28px;
            background: var(--bg-dark);
            color: white;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 13px;
            font-weight: 700;
        }
        
        .item-name {
            font-size: 15px;
            font-weight: 600;
            color: var(--text-dark);
        }
        
        .item-price {
            font-size: 15px;
            font-weight: 700;
            color: var(--accent-gold);
        }
        
        /* Total */
        .total-section {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-top: 20px;
            margin-top: 20px;
            border-top: 2px solid #e5e7eb;
        }
        
        .total-label {
            font-size: 18px;
            font-weight: 700;
            color: var(--text-dark);
        }
        
        .total-value {
            font-size: 28px;
            font-weight: 800;
            color: var(--accent-gold);
        }
        
        /* Special Requests */
        .special-requests {
            background: linear-gradient(135deg, #fef9e7, #fef3c7);
            border-radius: 12px;
            padding: 16px;
            margin-top: 16px;
            border-left: 4px solid var(--accent-gold);
        }
        
        .special-requests-label {
            font-size: 11px;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 6px;
        }
        
        .special-requests-text {
            font-size: 14px;
            color: var(--text-dark);
            font-style: italic;
        }
        
        /* Actions */
        .actions {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            background: var(--bg-card);
            padding: 16px 20px 24px;
            box-shadow: 0 -4px 20px rgba(0,0,0,0.1);
            display: flex;
            gap: 12px;
            z-index: 100;
        }
        
        .btn {
            flex: 1;
            padding: 16px 24px;
            border-radius: 14px;
            font-size: 15px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s ease;
            border: none;
            text-align: center;
            text-decoration: none;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }
        
        .btn-secondary {
            background: #f3f4f6;
            color: var(--text-dark);
        }
        
        .btn-secondary:hover {
            background: #e5e7eb;
        }
        
        .btn-primary {
            background: linear-gradient(135deg, var(--accent-gold), var(--accent-gold-light));
            color: var(--bg-dark);
            box-shadow: 0 4px 15px rgba(212,175,55,0.4);
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(212,175,55,0.5);
        }
        
        /* Status Notification */
        .status-notification {
            position: fixed;
            top: 20px;
            left: 50%;
            transform: translateX(-50%) translateY(-100px);
            background: var(--text-dark);
            color: white;
            padding: 16px 24px;
            border-radius: 12px;
            font-size: 15px;
            font-weight: 600;
            z-index: 3000;
            opacity: 0;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            display: flex;
            align-items: center;
            gap: 12px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.3);
        }
        
        .status-notification.show {
            opacity: 1;
            transform: translateX(-50%) translateY(0);
        }
        
        .status-icon {
            width: 28px;
            height: 28px;
            background: var(--accent-gold);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 14px;
        }
        
        /* Checked Out State */
        .checked-out-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: var(--success);
            color: white;
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 700;
            margin-top: 8px;
        }
        
        /* Responsive */
        @media (min-width: 768px) {
            .container {
                padding: 30px;
            }
        }
    </style>
</head>
<body>
    <!-- Success Header -->
    <div class="success-header">
        <div class="success-icon">✓</div>
        <h1 class="success-title">
            @if($order->is_checked_out)
                Order Completed!
            @else
                Order Confirmed
            @endif
        </h1>
        <p class="success-message">
            @if($order->is_checked_out)
                Thank you for dining with us. We hope to see you again soon!
            @else
                Your order has been sent to the kitchen. You can track its progress below.
            @endif
        </p>
    </div>

    <div class="container">
        <!-- Order Status Card -->
        <div class="order-card">
            <div class="order-header">
                <div class="order-info">
                    <h2>Order #{{ $order->id }}</h2>
                    <p class="order-meta">Table {{ $order->table->table_number }}</p>
                </div>
                <div class="status-wrapper">
                    <span class="status-badge status-{{ $order->status }}">
                        <span class="pulse"></span>
                        {{ ucfirst($order->status) }}
                    </span>
                    @if($order->is_checked_out)
                        <span class="checked-out-badge">✓ Paid</span>
                    @endif
                </div>
            </div>
            
            @if(!$order->is_checked_out)
            <!-- Progress Tracker -->
            <div class="progress-tracker" id="progress-tracker">
                <div class="progress-step">
                    <div class="step-icon {{ in_array($order->status, ['pending', 'preparing', 'ready', 'served', 'completed']) ? 'completed' : 'active' }}">✓</div>
                    <span class="step-label">Received</span>
                </div>
                <div class="progress-step">
                    <div class="step-icon {{ in_array($order->status, ['preparing', 'ready', 'served', 'completed']) ? (in_array($order->status, ['ready', 'served', 'completed']) ? 'completed' : 'active') : '' }}">
                        {{ in_array($order->status, ['ready', 'served', 'completed']) ? '✓' : '👨‍🍳' }}
                    </div>
                    <span class="step-label">Preparing</span>
                </div>
                <div class="progress-step">
                    <div class="step-icon {{ in_array($order->status, ['ready', 'served', 'completed']) ? (in_array($order->status, ['served', 'completed']) ? 'completed' : 'active') : '' }}">
                        {{ in_array($order->status, ['served', 'completed']) ? '✓' : '🍽' }}
                    </div>
                    <span class="step-label">Ready</span>
                </div>
                <div class="progress-step">
                    <div class="step-icon {{ $order->status === 'completed' ? 'completed' : '' }}">
                        {{ $order->status === 'completed' ? '✓' : '✨' }}
                    </div>
                    <span class="step-label">Served</span>
                </div>
            </div>
            
            <!-- Estimated Time -->
            <div class="est-time-box">
                <div class="est-time-label">Estimated Ready In</div>
                <div class="est-time-value">
                    <span id="est-minutes">{{ $order->estimated_minutes }}</span>
                    <span class="est-time-unit">min</span>
                </div>
            </div>
            @endif
        </div>

        <!-- Order Details Card -->
        <div class="order-card">
            <h3 class="section-title">Order Details</h3>
            <ul class="item-list">
                @foreach($order->orderItems as $orderItem)
                    <li class="item-row">
                        <div class="item-info">
                            <span class="item-qty">{{ $orderItem->quantity }}</span>
                            <span class="item-name">{{ $orderItem->item->name }}</span>
                        </div>
                        <span class="item-price">
                            @if($orderItem->item->show_price)
                                ${{ number_format($orderItem->unit_price * $orderItem->quantity, 2) }}
                            @else
                                --
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

            <div class="total-section">
                <span class="total-label">Total Amount</span>
                <span class="total-value">${{ number_format($order->total_amount, 2) }}</span>
            </div>
        </div>
    </div>

    <!-- Fixed Actions -->
    <div class="actions">
        @if(!$order->is_checked_out)
            <a href="/menu" class="btn btn-secondary">
                <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                </svg>
                Add More
            </a>
            <form action="{{ route('checkout.finalize') }}" method="POST" style="flex: 1;">
                @csrf
                <button type="submit" class="btn btn-primary" onclick="return confirm('Are you sure you want to checkout? This will finalize your order.')">
                    <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    Checkout
                </button>
            </form>
        @else
            <a href="/menu" class="btn btn-primary" style="flex: 1;">
                <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                </svg>
                Start New Order
            </a>
        @endif
    </div>

    <!-- Status Notification -->
    <div class="status-notification" id="status-notification">
        <div class="status-icon">🍽</div>
        <span id="notification-text">Your order is being prepared!</span>
    </div>

    <script>
        // Simple polling for order status updates (no WebSocket needed)
        let lastStatus = '{{ $order->status }}';
        
        async function checkOrderStatus() {
            try {
                const response = await fetch('/admin/orders/{{ $order->id }}/data');
                const order = await response.json();
                
                if (order.status !== lastStatus) {
                    lastStatus = order.status;
                    updateStatusUI(order.status);
                    showStatusNotification(order.status);
                }
                
                if (order.status === 'completed' || order.status === 'cancelled') {
                    location.reload();
                }
            } catch (e) {
                console.log('Status check failed:', e);
            }
        }
        
        function updateStatusUI(status) {
            // Update status badge
            const badge = document.querySelector('.status-badge');
            if (badge) {
                badge.className = 'status-badge status-' + status;
                badge.innerHTML = '<span class="pulse"></span>' + status.charAt(0).toUpperCase() + status.slice(1);
            }
            
            // Update progress tracker
            updateProgressTracker(status);
        }
        
        function updateProgressTracker(status) {
            const steps = document.querySelectorAll('.step-icon');
            const stepOrder = ['pending', 'preparing', 'ready', 'served', 'completed'];
            const currentIndex = stepOrder.indexOf(status);
            
            steps.forEach((step, index) => {
                if (index < currentIndex) {
                    step.className = 'step-icon completed';
                    step.textContent = '✓';
                } else if (index === currentIndex) {
                    step.className = 'step-icon active';
                    const icons = ['📋', '👨‍🍳', '🍽', '✨', '✓'];
                    step.textContent = icons[index];
                }
            });
        }
        
        function showStatusNotification(status) {
            const notifications = {
                'pending': 'Order Received! We\'ve got it.',
                'preparing': 'Now Preparing! Chef is cooking.',
                'ready': 'Order Ready! Please collect.',
                'served': 'Order Served! Enjoy your meal.',
                'completed': 'Order Completed! Thank you.'
            };
            
            if (!notifications[status]) return;
            
            const notification = document.getElementById('status-notification');
            const text = document.getElementById('notification-text');
            
            text.textContent = notifications[status];
            notification.classList.add('show');
            
            setTimeout(() => {
                notification.classList.remove('show');
            }, 4000);
        }
        
        // Check for updates every 5 seconds
        @if(!$order->is_checked_out)
        document.addEventListener('DOMContentLoaded', function() {
            setInterval(checkOrderStatus, 5000);
        });
        @endif
    </script>
</body>
</html>

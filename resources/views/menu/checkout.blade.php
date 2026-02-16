<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Checkout - Table {{ $table->table_number }}</title>
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
            --shadow: 0 4px 20px rgba(0,0,0,0.15);
            --shadow-soft: 0 2px 10px rgba(0,0,0,0.08);
        }
        
        body {
            font-family: 'Montserrat', sans-serif;
            background: var(--bg-dark);
            color: var(--text-dark);
            line-height: 1.5;
            min-height: 100vh;
            padding-bottom: 120px;
        }
        
        /* Header */
        .header {
            background: var(--bg-dark);
            padding: 16px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid rgba(212,175,55,0.1);
            position: sticky;
            top: 0;
            z-index: 100;
        }
        
        .back-btn {
            display: flex;
            align-items: center;
            gap: 8px;
            color: var(--text-light);
            text-decoration: none;
            font-size: 15px;
            font-weight: 600;
            padding: 8px 12px;
            border-radius: 10px;
            transition: all 0.3s ease;
        }
        
        .back-btn:hover {
            background: rgba(255,255,255,0.1);
        }
        
        .back-btn svg {
            width: 20px;
            height: 20px;
        }
        
        .logo {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .logo-icon {
            width: 36px;
            height: 36px;
            background: linear-gradient(135deg, var(--accent-gold), var(--accent-gold-light));
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
        }
        
        .table-badge {
            background: rgba(212,175,55,0.15);
            color: var(--accent-gold);
            padding: 6px 14px;
            border-radius: 20px;
            font-size: 13px;
            font-weight: 600;
            border: 1px solid rgba(212,175,55,0.3);
        }
        
        /* Container */
        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        
        /* Cards */
        .card {
            background: var(--bg-card);
            border-radius: 20px;
            padding: 24px;
            margin-bottom: 16px;
            box-shadow: var(--shadow-soft);
        }
        
        .card-title {
            font-size: 18px;
            font-weight: 700;
            color: var(--text-dark);
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .card-title::after {
            content: '';
            flex: 1;
            height: 1px;
            background: linear-gradient(90deg, #e5e7eb, transparent);
        }
        
        /* Cart Items */
        .cart-item {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            padding: 16px 0;
            border-bottom: 1px solid #f3f4f6;
        }
        
        .cart-item:last-child {
            border-bottom: none;
        }
        
        .item-details {
            flex: 1;
        }
        
        .item-name {
            font-size: 16px;
            font-weight: 600;
            color: var(--text-dark);
            margin-bottom: 8px;
        }
        
        .item-controls {
            display: flex;
            align-items: center;
            gap: 12px;
        }
        
        .qty-btn {
            width: 36px;
            height: 36px;
            border-radius: 10px;
            border: 2px solid #e5e7eb;
            background: white;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            font-size: 18px;
            font-weight: 600;
            color: var(--text-dark);
            transition: all 0.2s ease;
        }
        
        .qty-btn:hover {
            border-color: var(--accent-gold);
            background: rgba(212,175,55,0.1);
        }
        
        .qty-value {
            font-size: 16px;
            font-weight: 700;
            color: var(--text-dark);
            min-width: 30px;
            text-align: center;
        }
        
        .remove-btn {
            color: var(--danger);
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            padding: 4px 8px;
            border-radius: 6px;
            transition: all 0.2s ease;
        }
        
        .remove-btn:hover {
            background: rgba(239,68,68,0.1);
        }
        
        .item-price {
            font-size: 16px;
            font-weight: 700;
            color: var(--accent-gold);
            text-align: right;
        }
        
        /* Total Section */
        .total-section {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-top: 20px;
            margin-top: 16px;
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
        
        /* Est Time */
        .est-time-box {
            background: linear-gradient(135deg, rgba(212,175,55,0.1), rgba(212,175,55,0.05));
            border-radius: 16px;
            padding: 20px;
            display: flex;
            align-items: center;
            gap: 16px;
            border: 1px solid rgba(212,175,55,0.2);
        }
        
        .est-time-icon {
            width: 48px;
            height: 48px;
            background: linear-gradient(135deg, var(--accent-gold), var(--accent-gold-light));
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            box-shadow: 0 4px 15px rgba(212,175,55,0.3);
        }
        
        .est-time-content {
            flex: 1;
        }
        
        .est-time-label {
            font-size: 13px;
            color: var(--text-muted);
            margin-bottom: 4px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .est-time-value {
            font-size: 20px;
            font-weight: 700;
            color: var(--accent-gold);
        }
        
        .est-time-note {
            font-size: 12px;
            color: var(--text-muted);
            margin-top: 12px;
        }
        
        /* Special Requests */
        .notes-area {
            width: 100%;
            min-height: 100px;
            padding: 16px;
            border: 2px solid #e5e7eb;
            border-radius: 12px;
            font-family: inherit;
            font-size: 15px;
            resize: vertical;
            transition: all 0.3s ease;
        }
        
        .notes-area:focus {
            outline: none;
            border-color: var(--accent-gold);
            box-shadow: 0 0 0 3px rgba(212,175,55,0.1);
        }
        
        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 60px 20px;
        }
        
        .empty-icon {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, #f3f4f6, #e5e7eb);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            font-size: 40px;
        }
        
        .empty-title {
            font-size: 20px;
            font-weight: 700;
            color: var(--text-dark);
            margin-bottom: 8px;
        }
        
        .empty-text {
            font-size: 15px;
            color: var(--text-muted);
            margin-bottom: 24px;
        }
        
        .browse-btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 14px 28px;
            background: linear-gradient(135deg, var(--accent-gold), var(--accent-gold-light));
            color: var(--bg-dark);
            text-decoration: none;
            border-radius: 12px;
            font-weight: 700;
            font-size: 15px;
            box-shadow: 0 4px 15px rgba(212,175,55,0.4);
            transition: all 0.3s ease;
        }
        
        .browse-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(212,175,55,0.5);
        }
        
        /* Bottom Bar */
        .bottom-bar {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            background: var(--bg-card);
            padding: 16px 20px 24px;
            box-shadow: 0 -4px 20px rgba(0,0,0,0.15);
            display: flex;
            justify-content: space-between;
            align-items: center;
            z-index: 100;
            transform: translateY(100%);
            transition: transform 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        .bottom-bar.visible {
            transform: translateY(0);
        }
        
        .summary-info {
            display: flex;
            flex-direction: column;
        }
        
        .summary-items {
            font-size: 13px;
            color: var(--text-muted);
        }
        
        .summary-total {
            font-size: 24px;
            font-weight: 800;
            color: var(--accent-gold);
        }
        
        .place-order-btn {
            padding: 16px 32px;
            background: linear-gradient(135deg, var(--accent-gold), var(--accent-gold-light));
            color: var(--bg-dark);
            border: none;
            border-radius: 14px;
            font-size: 16px;
            font-weight: 700;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 8px;
            box-shadow: 0 4px 15px rgba(212,175,55,0.4);
            transition: all 0.3s ease;
        }
        
        .place-order-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(212,175,55,0.5);
        }
        
        .place-order-btn:disabled {
            background: #e5e7eb;
            color: #9ca3af;
            cursor: not-allowed;
            box-shadow: none;
        }
        
        /* Toast */
        .toast {
            position: fixed;
            top: 100px;
            left: 50%;
            transform: translateX(-50%) translateY(-100px);
            background: var(--text-dark);
            color: white;
            padding: 16px 24px;
            border-radius: 12px;
            font-size: 15px;
            font-weight: 600;
            z-index: 1000;
            opacity: 0;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            display: flex;
            align-items: center;
            gap: 10px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.3);
        }
        
        .toast.show {
            opacity: 1;
            transform: translateX(-50%) translateY(0);
        }
        
        .toast-icon {
            width: 24px;
            height: 24px;
            background: var(--success);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <header class="header">
        <a href="/menu" class="back-btn">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            {{ __('menu.back') }}
        </a>
        <div class="logo">
            <div class="logo-icon">🍽</div>
        </div>
        <span class="table-badge">{{ __('menu.table') }} {{ $table->table_number }}</span>
    </header>

    <div class="container">
        <!-- Cart Items Card -->
        <div class="card">
            <h2 class="card-title">{{ __('menu.your_order') }}</h2>
            <div id="cart-items">
                <!-- Cart items loaded here -->
            </div>
            <div id="empty-cart" class="empty-state" style="display: none;">
                <div class="empty-icon">🛒</div>
                <div class="empty-title">{{ __('menu.your_cart_is_empty') }}</div>
                <p class="empty-text">{{ __('menu.add_delicious_items') }}</p>
                <a href="/menu" class="browse-btn">
                    <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                    </svg>
                    {{ __('menu.browse_menu') }}
                </a>
            </div>
            <div class="total-section" id="cart-total-section">
                <span class="total-label">{{ __('menu.total') }}</span>
                <span class="total-value" id="cart-total">$0.00</span>
            </div>
        </div>

        <!-- Est Time Card -->
        <div class="card" id="est-time-card">
            <div class="est-time-box">
                <div class="est-time-icon">⏱️</div>
                <div class="est-time-content">
                    <div class="est-time-label">{{ __('menu.estimated_ready_time') }}</div>
                    <div class="est-time-value">{{ $estimatedTime }} {{ __('menu.minutes') }}</div>
                </div>
            </div>
            <p class="est-time-note">{{ __('menu.estimated_time_note') }}</p>
        </div>

        <!-- Special Requests Card -->
        <div class="card" id="notes-card">
            <h2 class="card-title">{{ __('menu.special_requests') }}</h2>
            <textarea id="special-requests" class="notes-area" placeholder="{{ __('menu.any_special_requests') }}"></textarea>
        </div>
    </div>

    <!-- Bottom Bar -->
    <div class="bottom-bar" id="bottom-bar">
        <div class="summary-info">
            <span class="summary-items" id="summary-items">0 {{ __('menu.items') }}</span>
            <span class="summary-total" id="bottom-total">$0.00</span>
        </div>
        <button class="place-order-btn" id="place-order-btn" onclick="placeOrder()">
            <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
            </svg>
            {{ __('menu.place_order') }}
        </button>
    </div>

    <!-- Toast -->
    <div class="toast" id="toast">
        <div class="toast-icon">
            <svg width="14" height="14" fill="none" stroke="white" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/>
            </svg>
        </div>
        <span id="toast-message">{{ __('menu.item_added') }}</span>
    </div>

    <script data-estimated-time="{{ $estimatedTime }}">
        let cart = [];
        let estimatedMinutes = parseInt(document.currentScript.dataset.estimatedTime);

        // Load cart
        async function loadCart() {
            try {
                const response = await fetch('/cart/summary');
                const data = await response.json();
                cart = data.cart || [];
                renderCart();
            } catch (error) {
                console.error('Error loading cart:', error);
                renderCart();
            }
        }

        function renderCart() {
            const cartItemsDiv = document.getElementById('cart-items');
            const emptyCartDiv = document.getElementById('empty-cart');
            const bottomBar = document.getElementById('bottom-bar');
            const cartTotalSection = document.getElementById('cart-total-section');
            const estTimeCard = document.getElementById('est-time-card');
            const notesCard = document.getElementById('notes-card');

            if (cart.length === 0) {
                cartItemsDiv.innerHTML = '';
                cartItemsDiv.style.display = 'none';
                emptyCartDiv.style.display = 'block';
                cartTotalSection.style.display = 'none';
                estTimeCard.style.display = 'none';
                notesCard.style.display = 'none';
                bottomBar.classList.remove('visible');
                return;
            }

            cartItemsDiv.style.display = 'block';
            emptyCartDiv.style.display = 'none';
            cartTotalSection.style.display = 'flex';
            estTimeCard.style.display = 'block';
            notesCard.style.display = 'block';
            bottomBar.classList.add('visible');

            let html = '';
            let totalPrice = 0;
            let totalItems = 0;

            cart.forEach((item, index) => {
                const itemTotal = item.show_price ? item.price * item.quantity : 0;
                totalPrice += itemTotal;
                totalItems += item.quantity;

                html += `
                    <div class="cart-item">
                        <div class="item-details">
                            <div class="item-name">${item.name}</div>
                            <div class="item-controls">
                                <button class="qty-btn" onclick="updateQuantity(${index}, -1)">−</button>
                                <span class="qty-value">${item.quantity}</span>
                                <button class="qty-btn" onclick="updateQuantity(${index}, 1)">+</button>
                                <span class="remove-btn" onclick="removeItem(${index})">{{ __('menu.remove') }}</span>
                            </div>
                        </div>
                        <div class="item-price">
                            ${item.show_price ? '$' + itemTotal.toFixed(2) : '--'}
                        </div>
                    </div>
                `;
            });

            cartItemsDiv.innerHTML = html;
            document.getElementById('cart-total').textContent = '$' + totalPrice.toFixed(2);
            document.getElementById('bottom-total').textContent = '$' + totalPrice.toFixed(2);
            document.getElementById('summary-items').textContent = totalItems + ' item' + (totalItems > 1 ? 's' : '');
        }

        async function updateQuantity(index, change) {
            const newQuantity = cart[index].quantity + change;
            if (newQuantity < 1) return;
            
            try {
                const response = await fetch('/cart/update', {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        item_id: cart[index].id,
                        quantity: newQuantity
                    })
                });

                const data = await response.json();
                if (data.success) {
                    cart = data.cart;
                    renderCart();
                }
            } catch (error) {
                console.error('Error updating cart:', error);
            }
        }

        async function removeItem(index) {
            try {
                const response = await fetch('/cart/remove', {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        item_id: cart[index].id
                    })
                });

                const data = await response.json();
                if (data.success) {
                    cart = data.cart;
                    renderCart();
                    showToast('{{ __('menu.item_removed') }}');
                }
            } catch (error) {
                console.error('Error removing item:', error);
            }
        }

        async function placeOrder() {
            const btn = document.getElementById('place-order-btn');
            btn.disabled = true;
            btn.innerHTML = '<span>{{ __('menu.placing') }}</span>';
            
            const specialRequests = document.getElementById('special-requests').value;
            
            // Debug: log cart data
            console.log('Cart data:', cart);
            console.log('Items to send:', cart.map(item => ({ id: item.id, quantity: item.quantity })));
            
            try {
                const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
                console.log('CSRF Token:', csrfToken ? 'Found' : 'Missing');
                
                const response = await fetch('/checkout', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken || ''
                    },
                    body: JSON.stringify({
                        items: cart.map(item => ({ 
                            id: item.id, 
                            quantity: item.quantity 
                        })),
                        special_requests: specialRequests
                    })
                });

                const data = await response.json();
                console.log('Response:', response.status, data);

                if (response.ok && data.success) {
                    window.location.href = data.redirect || ('/order-confirmation/' + data.order_id);
                } else {
                    const errorMsg = data.message || data.error || '{{ __('menu.error_placing_order') }}';
                    console.error('Order error:', errorMsg);
                    showToast('Error: ' + errorMsg);
                    btn.disabled = false;
                    btn.innerHTML = `
                        <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        {{ __('menu.place_order') }}
                    `;
                }
            } catch (error) {
                console.error('Error placing order:', error);
                showToast('Error: ' + error.message);
                btn.disabled = false;
                btn.innerHTML = `
                    <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    {{ __('menu.place_order') }}
                `;
            }
        }

        function showToast(message) {
            const toast = document.getElementById('toast');
            const msgEl = document.getElementById('toast-message');
            
            msgEl.textContent = message;
            toast.classList.add('show');
            
            setTimeout(() => {
                toast.classList.remove('show');
            }, 2500);
        }

        // Load on page load
        document.addEventListener('DOMContentLoaded', loadCart);
    </script>
</body>
</html>

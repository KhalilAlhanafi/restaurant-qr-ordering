<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout - Table {{ $table->table_number }}</title>
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
            background: #f8f9fa;
            padding-bottom: 100px;
        }
        .header {
            background: #fff;
            padding: 15px 20px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        h1 {
            font-size: 20px;
            color: #333;
        }
        .table-badge {
            background: #007bff;
            color: white;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 14px;
        }
        .back-btn {
            color: #007bff;
            text-decoration: none;
            font-size: 14px;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .section {
            background: white;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 15px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        }
        .section-title {
            font-size: 16px;
            font-weight: 600;
            color: #333;
            margin-bottom: 15px;
        }
        .cart-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 12px 0;
            border-bottom: 1px solid #f0f0f0;
        }
        .cart-item:last-child {
            border-bottom: none;
        }
        .item-info {
            flex: 1;
        }
        .item-name {
            font-weight: 500;
            color: #333;
        }
        .item-quantity {
            color: #666;
            font-size: 14px;
        }
        .item-price {
            font-weight: 600;
            color: #28a745;
        }
        .item-price.hidden {
            color: #999;
            font-style: italic;
        }
        .quantity-control {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-top: 8px;
        }
        .qty-btn {
            width: 32px;
            height: 32px;
            border: 1px solid #ddd;
            background: white;
            border-radius: 6px;
            cursor: pointer;
            font-size: 16px;
        }
        .qty-value {
            font-weight: 600;
            min-width: 24px;
            text-align: center;
        }
        .remove-btn {
            color: #dc3545;
            font-size: 12px;
            cursor: pointer;
            margin-left: 10px;
        }
        .notes-area {
            width: 100%;
            min-height: 80px;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-family: inherit;
            font-size: 14px;
            resize: vertical;
        }
        .est-time {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 15px;
            background: #e8f4fd;
            border-radius: 8px;
            margin-bottom: 15px;
        }
        .est-time-icon {
            font-size: 24px;
        }
        .est-time-text {
            font-size: 14px;
            color: #333;
        }
        .est-time-value {
            font-weight: 600;
            color: #007bff;
        }
        .cart-total {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 0;
            border-top: 2px solid #f0f0f0;
            font-size: 18px;
            font-weight: 600;
        }
        .total-label {
            color: #333;
        }
        .total-value {
            color: #28a745;
        }
        .empty-cart {
            text-align: center;
            padding: 40px;
            color: #666;
        }
        .bottom-bar {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            background: white;
            padding: 15px 20px;
            box-shadow: 0 -2px 10px rgba(0,0,0,0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .total-summary {
            font-size: 16px;
        }
        .total-amount {
            font-weight: 700;
            color: #28a745;
            font-size: 20px;
        }
        .place-order-btn {
            background: #28a745;
            color: white;
            border: none;
            padding: 14px 30px;
            border-radius: 25px;
            cursor: pointer;
            font-size: 16px;
            font-weight: 600;
        }
        .place-order-btn:disabled {
            background: #6c757d;
            cursor: not-allowed;
        }
    </style>
</head>
<body>
    <div class="header">
        <a href="/menu" class="back-btn">← Back to Menu</a>
        <span class="table-badge">Table {{ $table->table_number }}</span>
    </div>

    <div class="container">
        <div class="section">
            <h2 class="section-title">Your Order</h2>
            <div id="cart-items">
                <!-- Cart items will be loaded here -->
            </div>
            <div id="empty-cart" class="empty-cart" style="display: none;">
                Your cart is empty. <a href="/menu">Browse the menu</a>
            </div>
            <div class="cart-total">
                <span class="total-label">Total:</span>
                <span class="total-value" id="cart-total">$0.00</span>
            </div>
        </div>

        <div class="section">
            <h2 class="section-title">Estimated Preparation Time</h2>
            <div class="est-time">
                <span class="est-time-icon">⏱️</span>
                <span class="est-time-text">
                    Your order will be ready in approximately <span class="est-time-value">{{ $estimatedTime }} minutes</span>
                </span>
            </div>
            <p style="font-size: 12px; color: #666;">
                *Estimated time may vary based on current kitchen load
            </p>
        </div>

        <div class="section">
            <h2 class="section-title">Special Requests</h2>
            <textarea id="special-requests" class="notes-area" placeholder="Any special dietary requirements, allergies, or requests?"></textarea>
        </div>
    </div>

    <div class="bottom-bar" id="bottom-bar" style="display: none;">
        <div class="total-summary">
            <div id="summary-items">0 items</div>
            <div class="total-amount" id="bottom-total">$0.00</div>
        </div>
        <button class="place-order-btn" id="place-order-btn" onclick="placeOrder()">Place Order</button>
    </div>

    <div id="page-data" data-estimated-time="{{ $estimatedTime }}" data-csrf="{{ csrf_token() }}"></div>

    <script>
        let cart = [];
        let estimatedMinutes = parseInt(document.getElementById('page-data').dataset.estimatedTime);

        // Load cart from sessionStorage
        function loadCart() {
            const storedCart = sessionStorage.getItem('cart');
            if (storedCart) {
                cart = JSON.parse(storedCart);
            }
            renderCart();
        }

        function renderCart() {
            const cartItemsDiv = document.getElementById('cart-items');
            const emptyCartDiv = document.getElementById('empty-cart');
            const bottomBar = document.getElementById('bottom-bar');
            const placeOrderBtn = document.getElementById('place-order-btn');

            if (cart.length === 0) {
                cartItemsDiv.innerHTML = '';
                emptyCartDiv.style.display = 'block';
                bottomBar.style.display = 'none';
                return;
            }

            emptyCartDiv.style.display = 'none';
            bottomBar.style.display = 'flex';

            let html = '';
            let totalPrice = 0;
            let totalItems = 0;

            cart.forEach((item, index) => {
                const itemTotal = item.showPrice ? item.price * item.quantity : 0;
                totalPrice += itemTotal;
                totalItems += item.quantity;

                html += `
                    <div class="cart-item">
                        <div class="item-info">
                            <div class="item-name">${item.name}</div>
                            <div class="quantity-control">
                                <button class="qty-btn" onclick="updateQuantity(${index}, -1)">−</button>
                                <span class="qty-value">${item.quantity}</span>
                                <button class="qty-btn" onclick="updateQuantity(${index}, 1)">+</button>
                                <span class="remove-btn" onclick="removeItem(${index})">Remove</span>
                            </div>
                        </div>
                        <div class="item-price ${item.showPrice ? '' : 'hidden'}">
                            ${item.showPrice ? '$' + itemTotal.toFixed(2) : 'Price on request'}
                        </div>
                    </div>
                `;
            });

            cartItemsDiv.innerHTML = html;
            document.getElementById('cart-total').textContent = '$' + totalPrice.toFixed(2);
            document.getElementById('bottom-total').textContent = '$' + totalPrice.toFixed(2);
            document.getElementById('summary-items').textContent = totalItems + ' item' + (totalItems > 1 ? 's' : '');
        }

        function updateQuantity(index, change) {
            cart[index].quantity += change;
            if (cart[index].quantity <= 0) {
                cart.splice(index, 1);
            }
            sessionStorage.setItem('cart', JSON.stringify(cart));
            renderCart();
        }

        function removeItem(index) {
            cart.splice(index, 1);
            sessionStorage.setItem('cart', JSON.stringify(cart));
            renderCart();
        }

        async function placeOrder() {
            const btn = document.getElementById('place-order-btn');
            btn.disabled = true;
            btn.textContent = 'Placing Order...';

            const specialRequests = document.getElementById('special-requests').value;
            const csrfToken = document.getElementById('page-data').dataset.csrf;

            try {
                const response = await fetch('/checkout', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        items: cart,
                        special_requests: specialRequests
                    })
                });

                let data;
                try {
                    data = await response.json();
                } catch (e) {
                    // If not JSON, get text
                    const text = await response.text();
                    console.error('Non-JSON response:', text);
                    throw new Error('Invalid response from server');
                }

                if (response.ok && data.success) {
                    sessionStorage.removeItem('cart');
                    window.location.href = data.redirect || ('/order-confirmation/' + data.order_id);
                } else {
                    console.error('Server error:', data);
                    alert('Error: ' + (data.error || data.message || 'Please try again.'));
                    btn.disabled = false;
                    btn.textContent = 'Place Order';
                }
            } catch (err) {
                console.error('Fetch error:', err);
                alert('Error placing order: ' + err.message);
                btn.disabled = false;
                btn.textContent = 'Place Order';
            }
        }

        // Load cart on page load
        loadCart();
    </script>
</body>
</html>

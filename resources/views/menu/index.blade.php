    <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menu - Table {{ $table->table_number }}</title>
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
            background: #f8f9fa;
            padding-bottom: 80px;
        }
        .header {
            background: #fff;
            padding: 15px 20px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            position: sticky;
            top: 0;
            z-index: 100;
        }
        .header-top {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 10px;
        }
        h1 {
            font-size: 24px;
            color: #333;
        }
        .table-badge {
            background: #007bff;
            color: white;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 14px;
        }
        .categories {
            display: flex;
            gap: 10px;
            overflow-x: auto;
            padding-bottom: 5px;
        }
        .category-btn {
            white-space: nowrap;
            padding: 8px 16px;
            border: none;
            background: #e9ecef;
            color: #495057;
            border-radius: 20px;
            cursor: pointer;
            font-size: 14px;
        }
        .category-btn.active {
            background: #007bff;
            color: white;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .category-section {
            margin-bottom: 30px;
        }
        .category-title {
            font-size: 20px;
            color: #333;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 2px solid #007bff;
        }
        .menu-item {
            background: white;
            border-radius: 12px;
            padding: 15px;
            margin-bottom: 15px;
            display: flex;
            gap: 15px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        }
        .item-image {
            width: 80px;
            height: 80px;
            border-radius: 8px;
            background: #e9ecef;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 30px;
        }
        .item-details {
            flex: 1;
        }
        .item-name {
            font-size: 16px;
            font-weight: 600;
            color: #333;
            margin-bottom: 5px;
        }
        .item-description {
            font-size: 13px;
            color: #6c757d;
            margin-bottom: 8px;
        }
        .item-meta {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .item-price {
            font-size: 18px;
            font-weight: 700;
            color: #28a745;
        }
        .item-time {
            font-size: 12px;
            color: #6c757d;
        }
        .add-btn {
            background: #007bff;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 25px;
            cursor: pointer;
            font-size: 14px;
            font-weight: 600;
        }
        .add-btn:hover {
            background: #0056b3;
        }
        .cart-bar {
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
        .cart-info {
            display: flex;
            flex-direction: column;
        }
        .cart-count {
            font-size: 14px;
            color: #6c757d;
        }
        .cart-total {
            font-size: 20px;
            font-weight: 700;
            color: #28a745;
        }
        .checkout-btn {
            background: #28a745;
            color: white;
            border: none;
            padding: 12px 30px;
            border-radius: 25px;
            cursor: pointer;
            font-size: 16px;
            font-weight: 600;
        }
        .checkout-btn:hover {
            background: #218838;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="header-top">
            <h1>Our Menu</h1>
            <span class="table-badge">Table {{ $table->table_number }}</span>
        </div>
        <div class="categories">
            @foreach($categories as $category)
                <button class="category-btn {{ $loop->first ? 'active' : '' }}" onclick="scrollToCategory('cat-{{ $category->id }}')">
                    {{ $category->name }}
                </button>
            @endforeach
        </div>
    </div>

    <div class="container">
        @foreach($categories as $category)
            <div class="category-section" id="cat-{{ $category->id }}">
                <h2 class="category-title">{{ $category->name }}</h2>
                @forelse($category->items as $item)
                    <div class="menu-item" data-item-id="{{ $item->id }}">
                        @if($item->image)
                            <img src="{{ Storage::url($item->image) }}" alt="{{ $item->name }}" class="item-image" style="object-fit: cover;">
                        @else
                            <div class="item-image">🍽️</div>
                        @endif
                        <div class="item-details">
                            <div class="item-name">{{ $item->name }}</div>
                            <div class="item-description">{{ $item->description }}</div>
                            <div class="item-meta">
                                @if($item->show_price)
                                    <span class="item-price">${{ number_format($item->price, 2) }}</span>
                                @else
                                    <span class="item-price">Price on request</span>
                                @endif
                                <span class="item-time">⏱️ {{ $item->preparation_time }} min</span>
                            </div>
                        </div>
                        <button class="add-btn" data-item-id="{{ $item->id }}" data-item-name="{{ $item->name }}" data-item-price="{{ $item->price }}" data-item-show-price="{{ $item->show_price ? 1 : 0 }}">Add</button>
                    </div>
                @empty
                    <p>No items available in this category.</p>
                @endforelse
            </div>
        @endforeach
    </div>

    <div class="cart-bar" id="cart-bar" style="display: none;">
        <div class="cart-info">
            <span class="cart-count"><span id="cart-count">0</span> items</span>
            <span class="cart-total">$<span id="cart-total">0.00</span></span>
        </div>
        <button class="checkout-btn" onclick="goToCheckout()">View Cart</button>
    </div>

    <script>
        let cart = [];

        // Add click handlers to all Add buttons
        document.querySelectorAll('.add-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const id = this.dataset.itemId;
                const name = this.dataset.itemName;
                const price = parseFloat(this.dataset.itemPrice);
                const showPrice = this.dataset.itemShowPrice === '1';
                addToCart(id, name, price, showPrice);
            });
        });

        function scrollToCategory(id) {
            document.getElementById(id).scrollIntoView({ behavior: 'smooth' });
        }

        function addToCart(id, name, price, showPrice) {
            const existingItem = cart.find(item => item.id === id);
            if (existingItem) {
                existingItem.quantity++;
            } else {
                cart.push({ id, name, price, quantity: 1, showPrice });
            }
            updateCartDisplay();
        }

        function updateCartDisplay() {
            const cartBar = document.getElementById('cart-bar');
            const cartCount = document.getElementById('cart-count');
            const cartTotal = document.getElementById('cart-total');

            if (cart.length > 0) {
                cartBar.style.display = 'flex';
                const totalItems = cart.reduce((sum, item) => sum + item.quantity, 0);
                // Only count items with showPrice=true in total
                const totalPrice = cart.reduce((sum, item) => {
                    return item.showPrice ? sum + (item.price * item.quantity) : sum;
                }, 0);
                cartCount.textContent = totalItems;
                cartTotal.textContent = totalPrice.toFixed(2);
            } else {
                cartBar.style.display = 'none';
            }
        }

        function goToCheckout() {
            // Store cart in sessionStorage and redirect to checkout
            sessionStorage.setItem('cart', JSON.stringify(cart));
            window.location.href = '/checkout';
        }
    </script>
</body>
</html>

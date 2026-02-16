    <!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Menu - Table {{ $table->table_number }}</title>
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
            --shadow: 0 4px 20px rgba(0,0,0,0.15);
            --shadow-soft: 0 2px 10px rgba(0,0,0,0.08);
        }
        
        body {
            font-family: 'Montserrat', sans-serif;
            background: var(--bg-dark);
            color: var(--text-dark);
            line-height: 1.5;
            overflow-x: hidden;
        }
        
        /* Sticky Header */
        .header {
            position: sticky;
            top: 0;
            z-index: 1000;
            background: var(--bg-dark);
            padding: 16px 20px;
            border-bottom: 1px solid rgba(212,175,55,0.1);
        }
        
        .header-top {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 12px;
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
        
        .logo-text {
            font-size: 20px;
            font-weight: 700;
            color: var(--text-light);
            letter-spacing: -0.5px;
        }
        
        .header-actions {
            display: flex;
            align-items: center;
            gap: 12px;
            position: relative;
            z-index: 101;
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
        
        .cart-icon-btn {
            width: 40px;
            height: 40px;
            background: rgba(255,255,255,0.1);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            cursor: pointer;
            border: none;
            transition: all 0.3s ease;
        }
        
        .cart-icon-btn:hover {
            background: rgba(255,255,255,0.2);
            transform: scale(1.05);
        }
        
        .cart-icon-btn svg {
            width: 22px;
            height: 22px;
            color: var(--text-light);
        }
        
        .cart-badge {
            position: absolute;
            top: -4px;
            right: -4px;
            background: var(--accent-gold);
            color: var(--bg-dark);
            font-size: 11px;
            font-weight: 700;
            width: 20px;
            height: 20px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            animation: bounce 0.5s ease;
        }
        
        .lang-btn {
            padding: 10px 16px;
            background: rgba(255,255,255,0.1);
            border: 2px solid var(--accent-gold);
            border-radius: 20px;
            color: var(--accent-gold);
            font-size: 14px;
            font-weight: 700;
            cursor: pointer;
        }
        
        /* RTL Support */
        [dir="rtl"] .header-top {
            flex-direction: row-reverse;
        }
        
        [dir="rtl"] .header-actions {
            flex-direction: row-reverse;
        }
        
        [dir="rtl"] .logo {
            flex-direction: row-reverse;
        }
        
        [dir="rtl"] .category-title {
            flex-direction: row-reverse;
        }
        
        [dir="rtl"] .category-title::after {
            background: linear-gradient(90deg, transparent, rgba(212,175,55,0.5));
        }
        
        [dir="rtl"] .item-header {
            flex-direction: row-reverse;
        }
        
        [dir="rtl"] .item-name {
            margin-right: 0;
            margin-left: 10px;
        }
        
        [dir="rtl"] .item-footer {
            flex-direction: row-reverse;
        }
        
        [dir="rtl"] .item-actions {
            flex-direction: row-reverse;
        }
        
        [dir="rtl"] .cart-bar {
            flex-direction: row-reverse;
        }
        
        [dir="rtl"] .sheet-header {
            text-align: right;
        }
        
        [dir="rtl"] .quantity-selector {
            flex-direction: row-reverse;
        }
        
        [dir="rtl"] .cart-summary {
            text-align: right;
        }
        
        [dir="rtl"] .summary-total {
            flex-direction: row-reverse;
        }
        
        [dir="rtl"] .cart-item {
            flex-direction: row-reverse;
        }
        
        [dir="rtl"] .cart-item-info {
            text-align: right;
        }
        
        [dir="rtl"] .hero-title {
            letter-spacing: 0;
        }
        
        /* Hero Section */
        .hero {
            position: relative;
            height: 200px;
            background: linear-gradient(135deg, #2d2d44 0%, var(--bg-dark) 100%);
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .hero::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><circle cx="50" cy="50" r="40" fill="none" stroke="%23d4af37" stroke-width="0.5" opacity="0.1"/></svg>') repeat;
            background-size: 50px;
            opacity: 0.5;
        }
        
        .hero-content {
            text-align: center;
            z-index: 1;
            padding: 20px;
        }
        
        .hero-title {
            font-size: 28px;
            font-weight: 700;
            color: var(--text-light);
            margin-bottom: 8px;
            letter-spacing: -0.5px;
        }
        
        .hero-subtitle {
            font-size: 16px;
            color: var(--accent-gold);
            font-weight: 500;
        }
        
        /* Category Navigation */
        .categories-wrapper {
            position: sticky;
            top: 73px;
            z-index: 100;
            background: var(--bg-dark);
            padding: 12px 0;
            border-bottom: 1px solid rgba(212,175,55,0.1);
        }
        
        .categories {
            display: flex;
            gap: 10px;
            overflow-x: auto;
            padding: 4px 20px;
            scrollbar-width: none;
            -ms-overflow-style: none;
        }
        
        .categories::-webkit-scrollbar {
            display: none;
        }
        
        .category-btn {
            flex-shrink: 0;
            padding: 10px 20px;
            border: none;
            background: rgba(255,255,255,0.08);
            color: var(--text-muted);
            border-radius: 25px;
            cursor: pointer;
            font-size: 14px;
            font-weight: 600;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            white-space: nowrap;
        }
        
        .category-btn:hover {
            background: rgba(255,255,255,0.15);
            transform: translateY(-2px);
        }
        
        .category-btn.active {
            background: var(--accent-gold);
            color: var(--bg-dark);
            box-shadow: 0 4px 15px rgba(212,175,55,0.4);
        }
        
        /* Menu Container */
        .menu-container {
            padding: 20px;
            padding-bottom: 120px;
        }
        
        .category-section {
            margin-bottom: 40px;
        }
        
        .category-title {
            font-size: 22px;
            font-weight: 700;
            color: var(--text-light);
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .category-title::after {
            content: '';
            flex: 1;
            height: 1px;
            background: linear-gradient(90deg, rgba(212,175,55,0.5), transparent);
        }
        
        /* Menu Cards */
        .menu-grid {
            display: grid;
            gap: 16px;
        }
        
        .menu-item {
            background: var(--bg-card);
            border-radius: 20px;
            overflow: hidden;
            box-shadow: var(--shadow-soft);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            cursor: pointer;
        }
        
        .menu-item:hover {
            transform: translateY(-4px);
            box-shadow: var(--shadow);
        }
        
        .menu-item:active {
            transform: scale(0.98);
        }
        
        .item-image-container {
            position: relative;
            height: 160px;
            overflow: hidden;
        }
        
        .item-image {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.5s ease;
        }
        
        .menu-item:hover .item-image {
            transform: scale(1.05);
        }
        
        .item-tag {
            position: absolute;
            top: 12px;
            left: 12px;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .tag-popular { background: var(--accent-gold); color: var(--bg-dark); }
        .tag-chef { background: var(--success); color: white; }
        .tag-spicy { background: #ef4444; color: white; }
        
        .item-content {
            padding: 16px;
        }
        
        .item-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 8px;
        }
        
        .item-name {
            font-size: 17px;
            font-weight: 700;
            color: var(--text-dark);
            flex: 1;
            margin-right: 10px;
        }
        
        .item-price {
            font-size: 18px;
            font-weight: 800;
            color: var(--accent-gold);
        }
        
        .item-description {
            font-size: 13px;
            color: var(--text-muted);
            line-height: 1.5;
            margin-bottom: 12px;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
        
        .item-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .item-actions {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .qty-selector {
            display: flex;
            align-items: center;
            gap: 8px;
            background: #f3f4f6;
            padding: 4px;
            border-radius: 20px;
        }
        
        .qty-btn-small {
            width: 28px;
            height: 28px;
            border-radius: 50%;
            border: none;
            background: white;
            color: var(--text-dark);
            font-size: 16px;
            font-weight: 700;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s ease;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        
        .qty-btn-small:hover {
            background: var(--accent-gold);
            color: var(--bg-dark);
            transform: scale(1.1);
        }
        
        .qty-btn-small:active {
            transform: scale(0.95);
        }
        
        .qty-display {
            font-size: 15px;
            font-weight: 700;
            color: var(--text-dark);
            min-width: 24px;
            text-align: center;
        }
        
        .prep-time {
            display: flex;
            align-items: center;
            gap: 5px;
            font-size: 12px;
            color: var(--text-muted);
        }
        
        .prep-time svg {
            width: 14px;
            height: 14px;
        }
        
        .add-btn {
            width: 44px;
            height: 44px;
            border-radius: 50%;
            border: none;
            background: linear-gradient(135deg, var(--accent-gold), var(--accent-gold-light));
            color: var(--bg-dark);
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(212,175,55,0.4);
            position: relative;
            overflow: hidden;
        }
        
        .add-btn::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 0;
            height: 0;
            background: rgba(255,255,255,0.3);
            border-radius: 50%;
            transform: translate(-50%, -50%);
            transition: width 0.6s, height 0.6s;
        }
        
        .add-btn:active::before {
            width: 100px;
            height: 100px;
        }
        
        .add-btn:hover {
            transform: scale(1.1);
            box-shadow: 0 6px 20px rgba(212,175,55,0.5);
        }
        
        .add-btn:active {
            transform: scale(0.95);
        }
        
        .add-btn svg {
            width: 20px;
            height: 20px;
            stroke-width: 3;
        }
        
        /* Bottom Sheet Modal */
        .modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0,0,0,0.7);
            backdrop-filter: blur(4px);
            z-index: 2000;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
        }
        
        .modal-overlay.active {
            opacity: 1;
            visibility: visible;
        }
        
        .bottom-sheet {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            background: var(--bg-card);
            border-radius: 30px 30px 0 0;
            z-index: 2001;
            transform: translateY(100%);
            transition: transform 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            max-height: 90vh;
            overflow-y: auto;
        }
        
        .bottom-sheet.active {
            transform: translateY(0);
        }
        
        .sheet-handle {
            width: 40px;
            height: 4px;
            background: #e5e7eb;
            border-radius: 2px;
            margin: 12px auto;
        }
        
        .sheet-image {
            width: 100%;
            height: 250px;
            object-fit: cover;
        }
        
        .sheet-content {
            padding: 24px;
        }
        
        .sheet-header {
            margin-bottom: 16px;
        }
        
        .sheet-title {
            font-size: 24px;
            font-weight: 700;
            color: var(--text-dark);
            margin-bottom: 8px;
        }
        
        .sheet-price {
            font-size: 28px;
            font-weight: 800;
            color: var(--accent-gold);
        }
        
        .sheet-description {
            font-size: 15px;
            color: var(--text-muted);
            line-height: 1.7;
            margin-bottom: 24px;
        }
        
        .quantity-selector {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 20px;
            margin-bottom: 24px;
        }
        
        .qty-btn {
            width: 48px;
            height: 48px;
            border-radius: 50%;
            border: 2px solid #e5e7eb;
            background: white;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.2s ease;
        }
        
        .qty-btn:hover {
            border-color: var(--accent-gold);
            background: rgba(212,175,55,0.1);
        }
        
        .qty-btn svg {
            width: 20px;
            height: 20px;
            color: var(--text-dark);
        }
        
        .qty-value {
            font-size: 24px;
            font-weight: 700;
            color: var(--text-dark);
            min-width: 40px;
            text-align: center;
        }
        
        .add-to-cart-btn {
            width: 100%;
            padding: 18px 24px;
            background: linear-gradient(135deg, var(--accent-gold), var(--accent-gold-light));
            border: none;
            border-radius: 16px;
            color: var(--bg-dark);
            font-size: 17px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }
        
        .add-to-cart-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(212,175,55,0.5);
        }
        
        .add-to-cart-btn:active {
            transform: scale(0.98);
        }
        
        /* Floating Cart Bar */
        .cart-bar {
            position: fixed;
            bottom: 20px;
            left: 20px;
            right: 20px;
            background: var(--bg-card);
            border-radius: 20px;
            padding: 16px 20px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.3);
            z-index: 1500;
            display: flex;
            justify-content: space-between;
            align-items: center;
            transform: translateY(150%);
            transition: transform 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        .cart-bar.visible {
            transform: translateY(0);
        }
        
        .cart-info {
            display: flex;
            flex-direction: column;
        }
        
        .cart-count {
            font-size: 13px;
            color: var(--text-muted);
            margin-bottom: 2px;
        }
        
        .cart-total {
            font-size: 22px;
            font-weight: 800;
            color: var(--accent-gold);
        }
        
        .view-cart-btn {
            padding: 14px 24px;
            background: var(--text-dark);
            color: white;
            border: none;
            border-radius: 12px;
            font-size: 15px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .view-cart-btn:hover {
            background: var(--bg-dark);
            transform: scale(1.05);
        }
        
        /* Toast Notification */
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
            z-index: 3000;
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
        
        /* Cart Summary Modal */
        .cart-summary {
            padding: 20px;
        }
        
        .summary-title {
            font-size: 20px;
            font-weight: 700;
            color: var(--text-dark);
            margin-bottom: 20px;
        }
        
        .cart-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 16px 0;
            border-bottom: 1px solid #f3f4f6;
        }
        
        .cart-item-info {
            flex: 1;
        }
        
        .cart-item-name {
            font-size: 15px;
            font-weight: 600;
            color: var(--text-dark);
            margin-bottom: 4px;
        }
        
        .cart-item-qty {
            font-size: 13px;
            color: var(--text-muted);
        }
        
        .cart-item-price {
            font-size: 16px;
            font-weight: 700;
            color: var(--accent-gold);
        }
        
        .summary-total {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px 0;
            margin-top: 10px;
            border-top: 2px solid #e5e7eb;
        }
        
        .total-label {
            font-size: 18px;
            font-weight: 600;
            color: var(--text-dark);
        }
        
        .total-value {
            font-size: 24px;
            font-weight: 800;
            color: var(--accent-gold);
        }
        
        .checkout-btn {
            width: 100%;
            padding: 18px;
            background: linear-gradient(135deg, var(--accent-gold), var(--accent-gold-light));
            border: none;
            border-radius: 16px;
            color: var(--bg-dark);
            font-size: 17px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: 10px;
        }
        
        .checkout-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(212,175,55,0.5);
        }
        
        /* Loading Shimmer */
        @keyframes shimmer {
            0% { background-position: -200% 0; }
            100% { background-position: 200% 0; }
        }
        
        .shimmer {
            background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
            background-size: 200% 100%;
            animation: shimmer 1.5s infinite;
        }
        
        /* Empty State */
        .empty-category {
            text-align: center;
            padding: 40px 20px;
            color: var(--text-muted);
        }
        
        .empty-category svg {
            width: 60px;
            height: 60px;
            margin-bottom: 16px;
            opacity: 0.3;
        }
        
        /* Responsive */
        @media (min-width: 768px) {
            .menu-grid {
                grid-template-columns: repeat(2, 1fr);
            }
            
            .hero {
                height: 280px;
            }
            
            .hero-title {
                font-size: 36px;
            }
        }
        
        /* Animations */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .animate-in {
            animation: fadeInUp 0.5s ease forwards;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <header class="header">
        <div class="header-top">
            <div class="logo">
                <div class="logo-icon">🍽</div>
                <span class="logo-text">Gourmet</span>
            </div>
            <div class="header-actions">
                <button class="lang-btn" id="lang-btn" type="button" onclick="toggleLang()">{{ app()->getLocale() === 'ar' ? 'EN' : 'AR' }}</button>
                <span class="table-badge">{{ __('menu.table') }} {{ $table->table_number }}</span>
                <button class="cart-icon-btn" onclick="showCartSummary()">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                    <span class="cart-badge" id="cart-badge" style="display: none;">0</span>
                </button>
            </div>
        </div>
    </header>
    
    <!-- Hero Section -->
    <section class="hero">
        <div class="hero-content">
            <h1 class="hero-title">{{ __('menu.welcome') }}</h1>
            <p class="hero-subtitle" style="color: var(--accent-gold); font-weight: 500;">{{ __('menu.menu') }}</p>
        </div>
    </section>
    
    <!-- Category Navigation -->
    <div class="categories-wrapper">
        <div class="categories" id="categories">
            @foreach($categories as $category)
                <button class="category-btn {{ $loop->first ? 'active' : '' }}" 
                        data-category="cat-{{ $category->id }}"
                        onclick="filterCategory('cat-{{ $category->id }}', this)">
                    {{ $category->name }}
                </button>
            @endforeach
        </div>
    </div>
    
    <!-- Menu Container -->
    <div class="menu-container">
        @foreach($categories as $category)
            <div class="category-section" id="cat-{{ $category->id }}">
                <h2 class="category-title">{{ $category->name }}</h2>
                <div class="menu-grid">
                    @forelse($category->items as $item)
                        <div class="menu-item" data-item="{{ json_encode($item) }}" data-aos="fade-up">
                            <div class="item-image-container">
                                @if($item->image)
                                    <img src="{{ Storage::url($item->image) }}" alt="{{ $item->name }}" class="item-image">
                                @else
                                    <div class="item-image shimmer" style="display: flex; align-items: center; justify-content: center; background: #f0f0f0;">
                                        <span style="font-size: 40px;">🍽️</span>
                                    </div>
                                @endif
                                @if($item->is_popular)
                                    <span class="item-tag tag-popular">{{ __('menu.popular') }}</span>
                                @elseif($item->is_chef_choice)
                                    <span class="item-tag tag-chef">{{ __('menu.chef_choice') }}</span>
                                @elseif($item->is_spicy)
                                    <span class="item-tag tag-spicy">{{ __('menu.spicy') }}</span>
                                @endif
                            </div>
                            <div class="item-content">
                                <div class="item-header">
                                    <h3 class="item-name">{{ $item->name }}</h3>
                                    @if($item->show_price)
                                        <span class="item-price">${{ number_format($item->price, 2) }}</span>
                                    @else
                                        <span class="item-price">--</span>
                                    @endif
                                </div>
                                <p class="item-description">{{ $item->description }}</p>
                                <div class="item-footer">
                                    <span class="prep-time">
                                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <circle cx="12" cy="12" r="10" stroke-width="2"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6l4 2"/>
                                        </svg>
                                        {{ $item->preparation_time }} {{ __('menu.minutes') }}
                                    </span>
                                    <div class="item-actions" onclick="event.stopPropagation()">
                                        <div class="qty-selector" id="qty-selector-{{ $item->id }}">
                                            <button class="qty-btn-small" onclick="changeItemQty({{ $item->id }}, -1)">−</button>
                                            <span class="qty-display" id="qty-display-{{ $item->id }}">1</span>
                                            <button class="qty-btn-small" onclick="changeItemQty({{ $item->id }}, 1)">+</button>
                                        </div>
                                        <button class="add-btn" onclick="addItemWithQty({{ $item->id }})" data-item-id="{{ $item->id }}">
                                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="empty-category">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                            </svg>
                            <p>{{ __('menu.cart_empty') }}</p>
                        </div>
                    @endforelse
                </div>
            </div>
        @endforeach
    </div>
    
    <!-- Floating Cart Bar -->
    <div class="cart-bar" id="cart-bar">
        <div class="cart-info">
            <span class="cart-count"><span id="cart-count">0</span> {{ __('menu.items') }}</span>
            <span class="cart-total">$<span id="cart-total">0.00</span></span>
        </div>
        <button class="view-cart-btn" onclick="showCartSummary()">{{ __('menu.view_cart') }}</button>
    </div>
    
    <!-- Item Detail Modal -->
    <div class="modal-overlay" id="item-modal-overlay" onclick="closeItemModal()"></div>
    <div class="bottom-sheet" id="item-modal">
        <div class="sheet-handle"></div>
        <img class="sheet-image" id="modal-image" src="" alt="">
        <div class="sheet-content">
            <div class="sheet-header">
                <h2 class="sheet-title" id="modal-title"></h2>
                <div class="sheet-price" id="modal-price"></div>
            </div>
            <p class="sheet-description" id="modal-description"></p>
            
            <div class="quantity-selector">
                <button class="qty-btn" onclick="changeQty(-1)">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"/>
                    </svg>
                </button>
                <span class="qty-value" id="modal-qty">1</span>
                <button class="qty-btn" onclick="changeQty(1)">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                </button>
            </div>
            
            <button class="add-to-cart-btn" onclick="addToCartFromModal()">
                <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                </svg>
                <span id="add-to-cart-text">{{ __('menu.add_to_order') }}</span> - $<span id="modal-total">0.00</span>
            </button>
        </div>
    </div>
    
    <!-- Cart Summary Modal -->
    <div class="modal-overlay" id="cart-modal-overlay" onclick="closeCartModal()"></div>
    <div class="bottom-sheet" id="cart-modal">
        <div class="sheet-handle"></div>
        <div class="cart-summary">
            <h2 class="summary-title">{{ __('menu.your_order') }}</h2>
            <div id="cart-items-container">
                <!-- Cart items will be loaded here -->
            </div>
            <div class="summary-total">
                <span class="total-label">{{ __('menu.total') }}</span>
                <span class="total-value" id="summary-total">$0.00</span>
            </div>
            <button class="checkout-btn" onclick="goToCheckout()">{{ __('menu.confirm_order') }}</button>
        </div>
    </div>
    
    <!-- Toast Notification -->
    <div class="toast" id="toast">
        <div class="toast-icon">
            <svg width="14" height="14" fill="none" stroke="white" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/>
            </svg>
        </div>
        <span id="toast-message">{{ __('menu.item_added_to_cart') }}</span>
    </div>
    
    <script>
        // Translations object passed from Laravel
        const translations = {
            no_description: "{{ __('menu.no_description') }}",
            items_added: "{{ __('menu.items_added') }}",
            item_added_single: "{{ __('menu.item_added_single') }}",
            failed_to_add_item: "{{ __('menu.failed_to_add_item') }}",
            error_adding_item: "{{ __('menu.error_adding_item') }}",
            your_cart_is_empty: "{{ __('menu.your_cart_is_empty') }}",
            qty: "{{ __('menu.qty') }}"
        };
        
        let cart = [];
        let currentItem = null;
        let currentQty = 1;
        
        // Language management
        let currentLang = localStorage.getItem('menu-language') || 'en';
        
        // Initialize language on load
        function initLanguage() {
            if (currentLang === 'ar') {
                const html = document.documentElement;
                const btn = document.getElementById('lang-text');
                
                html.setAttribute('dir', 'rtl');
                html.setAttribute('lang', 'ar');
                btn.textContent = 'EN';
                
                // Update all text to Arabic
                document.querySelectorAll('[data-ar]').forEach(el => {
                    const arText = el.getAttribute('data-ar');
                    if (arText) el.textContent = arText;
                });
            }
        }
        
        // Simple language switch function
        function switchLang() {
            const btn = document.getElementById('lang-text');
            const html = document.documentElement;
            
            if (!btn) return;
            
            // Visual feedback
            btn.parentElement.style.transform = 'scale(0.9)';
            setTimeout(() => btn.parentElement.style.transform = 'scale(1)', 150);
            
            const isRTL = html.getAttribute('dir') === 'rtl';
            
            if (isRTL) {
                // Switch to English
                html.setAttribute('dir', 'ltr');
                html.setAttribute('lang', 'en');
                btn.textContent = 'AR';
                localStorage.setItem('menu-language', 'en');
                
                // Update all text
                document.querySelectorAll('[data-en]').forEach(el => {
                    const enText = el.getAttribute('data-en');
                    if (enText) el.textContent = enText;
                });
            } else {
                // Switch to Arabic
                html.setAttribute('dir', 'rtl');
                html.setAttribute('lang', 'ar');
                btn.textContent = 'EN';
                localStorage.setItem('menu-language', 'ar');
                
                // Update all text
                document.querySelectorAll('[data-ar]').forEach(el => {
                    const arText = el.getAttribute('data-ar');
                    if (arText) el.textContent = arText;
                });
            }
        }
        
        // Language toggle function - calls server to switch locale
        function toggleLang() {
            console.log('toggleLang clicked');
            const currentLocale = document.documentElement.getAttribute('lang');
            console.log('Current locale:', currentLocale);
            const newLocale = currentLocale === 'ar' ? 'en' : 'ar';
            console.log('Switching to:', newLocale);
            
            // Call server to set locale
            const url = "{{ route('language.set') }}?locale=" + newLocale + "&redirect=menu";
            console.log('Redirecting to:', url);
            window.location.href = url;
        }
        
        // Initialize
        document.addEventListener('DOMContentLoaded', function() {
            loadCart();
            initQuantities();
        });
        
        // Load cart from server
        async function loadCart() {
            try {
                const response = await fetch('/cart/summary');
                const data = await response.json();
                cart = data.cart || [];
                updateCartDisplay();
            } catch (error) {
                console.error('Error loading cart:', error);
            }
        }
        
        // Filter category
        function filterCategory(categoryId, btn) {
            // Update active button
            document.querySelectorAll('.category-btn').forEach(b => b.classList.remove('active'));
            btn.classList.add('active');
            
            // Smooth scroll to category
            const section = document.getElementById(categoryId);
            if (section) {
                const offset = 160;
                const top = section.getBoundingClientRect().top + window.pageYOffset - offset;
                window.scrollTo({ top, behavior: 'smooth' });
            }
        }
        
        // Show item detail modal
        function showItemDetail(item) {
            currentItem = item;
            currentQty = 1;
            
            const modal = document.getElementById('item-modal');
            const overlay = document.getElementById('item-modal-overlay');
            
            // Populate modal
            document.getElementById('modal-image').src = item.image ? `/storage/${item.image}` : '';
            document.getElementById('modal-title').textContent = item.name;
            document.getElementById('modal-price').textContent = item.show_price ? `$${parseFloat(item.price).toFixed(2)}` : '';
            document.getElementById('modal-description').textContent = item.description || translations.no_description;
            
            updateModalTotal();
            
            // Show modal
            overlay.classList.add('active');
            modal.classList.add('active');
            document.body.style.overflow = 'hidden';
        }
        
        // Close item modal
        function closeItemModal() {
            const modal = document.getElementById('item-modal');
            const overlay = document.getElementById('item-modal-overlay');
            
            modal.classList.remove('active');
            overlay.classList.remove('active');
            document.body.style.overflow = '';
            
            currentItem = null;
        }
        
        // Change quantity
        function changeQty(delta) {
            currentQty = Math.max(1, currentQty + delta);
            document.getElementById('modal-qty').textContent = currentQty;
            updateModalTotal();
        }
        
        // Update modal total
        function updateModalTotal() {
            if (currentItem && currentItem.show_price) {
                const total = (currentItem.price * currentQty).toFixed(2);
                document.getElementById('modal-total').textContent = total;
            } else {
                document.getElementById('modal-total').textContent = '0.00';
            }
        }
        
        // Add to cart from modal
        async function addToCartFromModal() {
            if (!currentItem) return;
            
            await addToCart(currentItem.id, currentQty);
            closeItemModal();
        }
        
        // Store quantities for each item
        let itemQuantities = {};
        
        // Initialize quantities and menu item click handlers
        function initQuantities() {
            document.querySelectorAll('.menu-item').forEach(item => {
                const itemId = item.querySelector('.add-btn').dataset.itemId;
                itemQuantities[itemId] = 1;
                
                // Add click handler for item detail modal
                item.addEventListener('click', function(e) {
                    // Don't trigger if clicking on action buttons
                    if (e.target.closest('.item-actions') || e.target.closest('.qty-selector')) {
                        return;
                    }
                    const itemData = JSON.parse(this.dataset.item);
                    showItemDetail(itemData);
                });
            });
        }
        
        // Change item quantity on card
        function changeItemQty(itemId, delta) {
            const currentQty = itemQuantities[itemId] || 1;
            const newQty = Math.max(1, currentQty + delta);
            itemQuantities[itemId] = newQty;
            
            const display = document.getElementById('qty-display-' + itemId);
            if (display) {
                display.textContent = newQty;
                display.style.transform = 'scale(1.2)';
                setTimeout(() => {
                    display.style.transform = 'scale(1)';
                }, 150);
            }
        }
        
        // Add item with selected quantity from card
        async function addItemWithQty(itemId) {
            const qty = itemQuantities[itemId] || 1;
            await addToCart(itemId, qty);
            
            // Reset quantity to 1 after adding
            itemQuantities[itemId] = 1;
            const display = document.getElementById('qty-display-' + itemId);
            if (display) {
                display.textContent = '1';
            }
        }
        
        // Quick add (from card button - keeping for compatibility)
        async function quickAdd(itemId) {
            await addToCart(itemId, 1);
        }
        
        // Add to cart API
        async function addToCart(itemId, qty = 1) {
            try {
                const response = await fetch('/cart/add', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        item_id: itemId,
                        quantity: qty
                    })
                });
                
                const data = await response.json();
                if (data.success) {
                    cart = data.cart;
                    updateCartDisplay();
                    const message = qty > 1 ? `${qty} ${translations.items_added}` : `${qty} ${translations.item_added_single}`;
                    showToast(message);
                    animateCartBadge();
                } else {
                    showToast(translations.failed_to_add_item, 'error');
                }
            } catch (error) {
                console.error('Error:', error);
                showToast(translations.error_adding_item, 'error');
            }
        }
        
        // Update cart display
        function updateCartDisplay() {
            const cartBar = document.getElementById('cart-bar');
            const cartCount = document.getElementById('cart-count');
            const cartTotal = document.getElementById('cart-total');
            const cartBadge = document.getElementById('cart-badge');
            
            if (cart.length > 0) {
                const totalItems = cart.reduce((sum, item) => sum + item.quantity, 0);
                const totalPrice = cart.reduce((sum, item) => {
                    return item.show_price ? sum + (item.price * item.quantity) : sum;
                }, 0);
                
                cartCount.textContent = totalItems;
                cartTotal.textContent = totalPrice.toFixed(2);
                cartBadge.textContent = totalItems;
                cartBadge.style.display = 'flex';
                
                cartBar.classList.add('visible');
            } else {
                cartBar.classList.remove('visible');
                cartBadge.style.display = 'none';
            }
        }
        
        // Animate cart badge
        function animateCartBadge() {
            const badge = document.getElementById('cart-badge');
            badge.style.animation = 'none';
            badge.offsetHeight; // Trigger reflow
            badge.style.animation = 'bounce 0.5s ease';
        }
        
        // Show cart summary
        function showCartSummary() {
            const modal = document.getElementById('cart-modal');
            const overlay = document.getElementById('cart-modal-overlay');
            const container = document.getElementById('cart-items-container');
            const totalEl = document.getElementById('summary-total');
            
            // Build cart items HTML
            if (cart.length === 0) {
                container.innerHTML = '<p style="text-align: center; color: #6b7280; padding: 40px;">' + translations.your_cart_is_empty + '</p>';
                totalEl.textContent = '$0.00';
            } else {
                let html = '';
                let total = 0;
                
                cart.forEach(item => {
                    const itemTotal = item.show_price ? item.price * item.quantity : 0;
                    total += itemTotal;
                    
                    html += `
                        <div class="cart-item">
                            <div class="cart-item-info">
                                <div class="cart-item-name">${item.name}</div>
                                <div class="cart-item-qty">${translations.qty}: ${item.quantity}</div>
                            </div>
                            <div class="cart-item-price">$${itemTotal.toFixed(2)}</div>
                        </div>
                    `;
                });
                
                container.innerHTML = html;
                totalEl.textContent = `$${total.toFixed(2)}`;
            }
            
            overlay.classList.add('active');
            modal.classList.add('active');
            document.body.style.overflow = 'hidden';
        }
        
        // Close cart modal
        function closeCartModal() {
            const modal = document.getElementById('cart-modal');
            const overlay = document.getElementById('cart-modal-overlay');
            
            modal.classList.remove('active');
            overlay.classList.remove('active');
            document.body.style.overflow = '';
        }
        
        // Go to checkout
        function goToCheckout() {
            window.location.href = '/checkout';
        }
        
        // Show toast
        function showToast(message, type = 'success') {
            const toast = document.getElementById('toast');
            const msgEl = document.getElementById('toast-message');
            
            msgEl.textContent = message;
            toast.classList.add('show');
            
            setTimeout(() => {
                toast.classList.remove('show');
            }, 2500);
        }
        
        // Handle swipe to close modals
        let touchStartY = 0;
        const bottomSheets = document.querySelectorAll('.bottom-sheet');
        
        bottomSheets.forEach(sheet => {
            sheet.addEventListener('touchstart', (e) => {
                touchStartY = e.touches[0].clientY;
            }, { passive: true });
            
            sheet.addEventListener('touchmove', (e) => {
                const touchY = e.touches[0].clientY;
                const diff = touchY - touchStartY;
                
                if (diff > 100 && sheet.scrollTop === 0) {
                    if (sheet.id === 'item-modal') closeItemModal();
                    if (sheet.id === 'cart-modal') closeCartModal();
                }
            }, { passive: true });
        });
    </script>
</body>
</html>


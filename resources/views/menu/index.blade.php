<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ __('menu.menu') }} — Table {{ $table->table_number }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link
        href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,700;0,900;1,400&family=Montserrat:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">
    <style>
        /* ─── Reset & Base ─────────────────────────────────────── */
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            -webkit-tap-highlight-color: transparent;
        }

        :root {
            --bg: #0d0d0d;
            --surface: #161616;
            --surface2: #1e1e1e;
            --gold: #c9a84c;
            --gold-light: #e5c46a;
            --gold-dim: rgba(201, 168, 76, 0.15);
            --white: #ffffff;
            --gray: rgba(255, 255, 255, 0.45);
            --gray-dim: rgba(255, 255, 255, 0.15);
            --green: #3ec98a;
            --red: #ef4444;

            --header-h: 64px;
            --tabs-h: 58px;
        }

        body {
            font-family: 'Montserrat', sans-serif;
            background: var(--bg);
            color: var(--white);
            min-height: 100vh;
            min-height: 100dvh;
            overflow-x: hidden;
            padding-bottom: 90px;
        }

        /* ─── RTL support ──────────────────────────────────────── */
        [dir="rtl"] .header-left {
            flex-direction: row-reverse;
        }

        [dir="rtl"] .header-right {
            flex-direction: row-reverse;
        }

        [dir="rtl"] .item-footer {
            flex-direction: row-reverse;
        }

        [dir="rtl"] .bottom-bar {
            flex-direction: row-reverse;
        }

        [dir="rtl"] .cart-item {
            flex-direction: row-reverse;
        }

        /* ─── Header ───────────────────────────────────────────── */
        .header {
            position: sticky;
            top: 0;
            z-index: 900;
            height: var(--header-h);
            background: var(--bg);
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 20px;
        }

        .header-left {
            display: flex;
            flex-direction: column;
            gap: 2px;
        }

        .header-brand {
            font-family: 'Playfair Display', serif;
            font-size: 18px;
            font-weight: 700;
            color: var(--white);
            letter-spacing: 2px;
            line-height: 1;
        }

        .header-date {
            font-size: 10px;
            font-weight: 500;
            color: var(--gray);
            letter-spacing: 1.5px;
            text-transform: uppercase;
        }

        .header-right {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        /* Language toggle */
        .lang-toggle {
            padding: 6px 12px;
            background: transparent;
            border: 1px solid var(--gray-dim);
            border-radius: 20px;
            color: var(--gray);
            font-size: 11px;
            font-weight: 600;
            letter-spacing: 1px;
            cursor: pointer;
            font-family: 'Montserrat', sans-serif;
            transition: all 0.25s ease;
        }

        .lang-toggle:hover {
            border-color: var(--gold);
            color: var(--gold);
        }

        /* Cart button */
        .cart-btn {
            position: relative;
            width: 40px;
            height: 40px;
            background: var(--surface2);
            border: 1px solid rgba(255, 255, 255, 0.07);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.25s ease;
        }

        .cart-btn:hover {
            background: var(--surface);
            border-color: var(--gold-dim);
        }

        .cart-btn svg {
            width: 18px;
            height: 18px;
            color: var(--white);
            fill: none;
            stroke: currentColor;
            stroke-width: 1.8;
        }

        .cart-badge {
            position: absolute;
            top: -5px;
            right: -5px;
            background: var(--gold);
            color: #0d0d0d;
            font-size: 10px;
            font-weight: 700;
            width: 18px;
            height: 18px;
            border-radius: 50%;
            display: none;
            align-items: center;
            justify-content: center;
            animation: pop 0.3s ease;
        }

        @keyframes pop {
            0% {
                transform: scale(0.5);
            }

            70% {
                transform: scale(1.25);
            }

            100% {
                transform: scale(1);
            }
        }

        /* ─── Hero / Brand Section ─────────────────────────────── */
        .hero {
            padding: 28px 20px 20px;
            background: var(--bg);
        }

        .season-label {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 10px;
        }

        .season-label span {
            font-size: 10px;
            font-weight: 600;
            letter-spacing: 2.5px;
            text-transform: uppercase;
            color: var(--gold);
        }

        .season-label::before,
        .season-label::after {
            content: '';
            flex: 1;
            height: 1px;
            background: linear-gradient(90deg, transparent, rgba(201, 168, 76, 0.3));
        }

        .season-label::after {
            background: linear-gradient(270deg, transparent, rgba(201, 168, 76, 0.3));
        }

        .brand-title {
            font-family: 'Playfair Display', serif;
            font-size: clamp(38px, 10vw, 50px);
            font-weight: 700;
            color: var(--gold);
            letter-spacing: 4px;
            line-height: 1;
            margin-bottom: 12px;
            text-shadow: 0 0 30px rgba(201, 168, 76, 0.2);
        }

        .brand-desc {
            font-size: 12.5px;
            color: var(--gray);
            line-height: 1.65;
            max-width: 300px;
            font-weight: 300;
        }

        /* ─── Category Tabs ────────────────────────────────────── */
        .tabs-wrapper {
            position: sticky;
            top: var(--header-h);
            z-index: 800;
            height: var(--tabs-h);
            background: var(--bg);
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
        }

        .tabs-scroll {
            display: flex;
            gap: 8px;
            overflow-x: auto;
            padding: 0 20px;
            height: 100%;
            align-items: center;
            scrollbar-width: none;
        }

        .tabs-scroll::-webkit-scrollbar {
            display: none;
        }

        .tab-btn {
            flex-shrink: 0;
            padding: 8px 18px;
            border: 1px solid rgba(255, 255, 255, 0.08);
            background: rgba(255, 255, 255, 0.04);
            color: var(--gray);
            border-radius: 50px;
            font-size: 13px;
            font-weight: 500;
            font-family: 'Montserrat', sans-serif;
            cursor: pointer;
            white-space: nowrap;
            transition: all 0.25s ease;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .tab-btn:hover {
            background: rgba(255, 255, 255, 0.08);
            color: var(--white);
        }

        .tab-btn.active {
            background: var(--gold);
            border-color: var(--gold);
            color: #0d0d0d;
            font-weight: 700;
            box-shadow: 0 4px 16px rgba(201, 168, 76, 0.35);
        }

        .tab-star {
            font-size: 11px;
        }

        /* ─── Menu List ────────────────────────────────────────── */
        .menu-list {
            padding: 16px 16px 8px;
            display: flex;
            flex-direction: column;
            gap: 0;
        }

        .category-section {
            margin-bottom: 32px;
        }

        .category-header {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 0 4px;
            margin-bottom: 16px;
        }

        .category-header h2 {
            font-size: 13px;
            font-weight: 600;
            letter-spacing: 2px;
            text-transform: uppercase;
            color: var(--gray);
        }

        .category-header::before {
            content: '';
            width: 3px;
            height: 14px;
            background: var(--gold);
            border-radius: 2px;
        }

        /* ─── Item Card ────────────────────────────────────────── */
        .item-card {
            background: var(--surface);
            border-radius: 18px;
            overflow: hidden;
            margin-bottom: 14px;
            border: 1px solid rgba(255, 255, 255, 0.04);
            cursor: pointer;
            transition: transform 0.25s ease, box-shadow 0.25s ease;
            position: relative;
        }

        .item-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 35px rgba(0, 0, 0, 0.4);
        }

        .item-card:active {
            transform: scale(0.99);
        }

        /* Image wrapper */
        .item-image-wrap {
            position: relative;
            width: 100%;
            height: 200px;
            overflow: hidden;
            background: var(--surface2);
        }

        .item-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.5s ease;
        }

        .item-card:hover .item-img {
            transform: scale(1.05);
        }

        /* No image placeholder */
        .item-img-placeholder {
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #1e1e1e, #2a2a2a);
            font-size: 52px;
        }

        /* Tag badge on image */
        .item-badge {
            position: absolute;
            top: 12px;
            left: 12px;
            padding: 5px 10px;
            border-radius: 6px;
            font-size: 10px;
            font-weight: 700;
            letter-spacing: 1px;
            text-transform: uppercase;
        }

        [dir="rtl"] .item-badge {
            left: auto;
            right: 12px;
        }

        .badge-popular {
            background: var(--gold);
            color: #0d0d0d;
        }

        .badge-chef {
            background: #10b981;
            color: #fff;
        }

        .badge-spicy {
            background: #ef4444;
            color: #fff;
        }

        .badge-vegetarian {
            background: #22c55e;
            color: #fff;
        }

        /* Image gradient overlay */
        .item-image-wrap::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 60px;
            background: linear-gradient(to top, rgba(22, 22, 22, 0.8), transparent);
            pointer-events: none;
        }

        /* Item body */
        .item-body {
            padding: 14px 16px 16px;
        }

        .item-name {
            font-size: 16px;
            font-weight: 700;
            color: var(--white);
            margin-bottom: 6px;
            line-height: 1.3;
        }

        .item-desc {
            font-size: 12px;
            color: var(--gray);
            font-weight: 300;
            line-height: 1.55;
            margin-bottom: 14px;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        /* Item footer: price + add button */
        .item-footer {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
        }

        .item-price {
            font-size: 20px;
            font-weight: 700;
            color: var(--white);
        }

        .item-price-label {
            font-size: 12px;
            color: var(--gray);
            font-weight: 300;
        }

        .price-on-request {
            font-size: 13px;
            color: var(--gray);
            font-style: italic;
        }

        /* Add button */
        .add-btn {
            display: flex;
            align-items: center;
            gap: 6px;
            padding: 10px 18px;
            background: var(--gold);
            border: none;
            border-radius: 50px;
            color: #0d0d0d;
            font-size: 13px;
            font-weight: 700;
            font-family: 'Montserrat', sans-serif;
            cursor: pointer;
            transition: all 0.25s ease;
            white-space: nowrap;
            flex-shrink: 0;
        }

        .add-btn:hover {
            background: var(--gold-light);
            transform: scale(1.05);
            box-shadow: 0 6px 20px rgba(201, 168, 76, 0.4);
        }

        .add-btn:active {
            transform: scale(0.97);
        }

        .add-btn svg {
            width: 13px;
            height: 13px;
            stroke: #0d0d0d;
            stroke-width: 3;
            fill: none;
        }

        /* Empty state */
        .empty-cat {
            text-align: center;
            padding: 32px 20px;
            color: var(--gray);
            font-size: 13px;
        }

        /* ─── Fixed Bottom Bar ─────────────────────────────────── */
        .bottom-bar {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            z-index: 850;
            background: var(--surface);
            border-top: 1px solid rgba(255, 255, 255, 0.07);
            padding: 14px 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            transform: translateY(100%);
            transition: transform 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .bottom-bar.visible {
            transform: translateY(0);
        }

        .bar-left {}

        .bar-label {
            font-size: 10px;
            font-weight: 600;
            letter-spacing: 2px;
            text-transform: uppercase;
            color: var(--gray);
            margin-bottom: 3px;
        }

        .bar-total {
            font-size: 22px;
            font-weight: 700;
            color: var(--white);
        }

        .bar-btn {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 14px 22px;
            background: var(--gold);
            border: none;
            border-radius: 50px;
            color: #0d0d0d;
            font-size: 14px;
            font-weight: 700;
            font-family: 'Montserrat', sans-serif;
            cursor: pointer;
            transition: all 0.25s ease;
            white-space: nowrap;
            flex-shrink: 0;
        }

        .bar-btn:hover {
            background: var(--gold-light);
            box-shadow: 0 8px 24px rgba(201, 168, 76, 0.4);
        }

        .bar-btn svg {
            width: 16px;
            height: 16px;
            stroke: #0d0d0d;
            stroke-width: 2.5;
            fill: none;
        }

        /* ─── Modals ───────────────────────────────────────────── */
        .overlay {
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.75);
            backdrop-filter: blur(5px);
            z-index: 1000;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
        }

        .overlay.active {
            opacity: 1;
            visibility: visible;
        }

        .drawer {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            background: #1a1a1a;
            border-radius: 28px 28px 0 0;
            border-top: 1px solid rgba(255, 255, 255, 0.07);
            z-index: 1001;
            transform: translateY(100%);
            transition: transform 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            max-height: 88vh;
            overflow-y: auto;
        }

        .drawer.active {
            transform: translateY(0);
        }

        .drawer-handle {
            width: 36px;
            height: 4px;
            background: rgba(255, 255, 255, 0.12);
            border-radius: 2px;
            margin: 14px auto 0;
        }

        /* Item drawer */
        .drawer-img {
            width: 100%;
            height: 240px;
            object-fit: cover;
            background: var(--surface2);
        }

        .drawer-img-placeholder {
            width: 100%;
            height: 200px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #1e1e1e, #2a2a2a);
            font-size: 60px;
        }

        .drawer-body {
            padding: 20px 24px 32px;
        }

        .drawer-title {
            font-size: 22px;
            font-weight: 700;
            color: var(--white);
            margin-bottom: 6px;
        }

        .drawer-price {
            font-size: 26px;
            font-weight: 700;
            color: var(--gold);
            margin-bottom: 14px;
        }

        .drawer-desc {
            font-size: 13.5px;
            color: var(--gray);
            line-height: 1.65;
            margin-bottom: 24px;
        }

        /* Quantity selector */
        .qty-row {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 24px;
            margin-bottom: 24px;
        }

        .qty-btn {
            width: 44px;
            height: 44px;
            border-radius: 50%;
            border: 1.5px solid rgba(255, 255, 255, 0.12);
            background: rgba(255, 255, 255, 0.05);
            color: var(--white);
            font-size: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .qty-btn:hover {
            border-color: var(--gold);
            background: var(--gold-dim);
        }

        .qty-val {
            font-size: 26px;
            font-weight: 700;
            color: var(--white);
            min-width: 36px;
            text-align: center;
        }

        .add-to-order-btn {
            width: 100%;
            padding: 18px;
            background: var(--gold);
            border: none;
            border-radius: 16px;
            color: #0d0d0d;
            font-size: 16px;
            font-weight: 700;
            font-family: 'Montserrat', sans-serif;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }

        .add-to-order-btn:hover {
            background: var(--gold-light);
            box-shadow: 0 10px 28px rgba(201, 168, 76, 0.45);
            transform: translateY(-2px);
        }

        /* Cart drawer */
        .cart-drawer-body {
            padding: 16px 24px 32px;
        }

        .cart-drawer-title {
            font-size: 18px;
            font-weight: 700;
            color: var(--white);
            margin-bottom: 20px;
        }

        .c-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 14px 0;
            border-bottom: 1px solid rgba(255, 255, 255, 0.06);
        }

        .c-item:last-child {
            border-bottom: none;
        }

        .c-name {
            font-size: 14px;
            font-weight: 600;
            color: var(--white);
            margin-bottom: 3px;
        }

        .c-qty {
            font-size: 12px;
            color: var(--gray);
        }

        .c-price {
            font-size: 15px;
            font-weight: 700;
            color: var(--gold);
        }

        .cart-total-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 18px 0 14px;
            border-top: 1.5px solid rgba(255, 255, 255, 0.1);
            margin-top: 4px;
        }

        .cart-total-label {
            font-size: 15px;
            font-weight: 600;
            color: var(--white);
        }

        .cart-total-val {
            font-size: 22px;
            font-weight: 700;
            color: var(--gold);
        }

        .checkout-btn {
            width: 100%;
            padding: 17px;
            background: var(--gold);
            border: none;
            border-radius: 14px;
            color: #0d0d0d;
            font-size: 15px;
            font-weight: 700;
            font-family: 'Montserrat', sans-serif;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: 4px;
        }

        .checkout-btn:hover {
            background: var(--gold-light);
            box-shadow: 0 8px 24px rgba(201, 168, 76, 0.45);
        }

        .cart-empty-msg {
            text-align: center;
            padding: 36px 0;
            color: var(--gray);
            font-size: 14px;
        }

        /* ─── Toast ────────────────────────────────────────────── */
        .toast {
            position: fixed;
            bottom: 100px;
            left: 50%;
            transform: translateX(-50%) translateY(20px);
            background: #1e1e1e;
            border: 1px solid rgba(201, 168, 76, 0.25);
            color: var(--white);
            padding: 12px 22px;
            border-radius: 50px;
            font-size: 13.5px;
            font-weight: 500;
            z-index: 2000;
            opacity: 0;
            white-space: nowrap;
            transition: all 0.35s ease;
            display: flex;
            align-items: center;
            gap: 8px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.4);
        }

        .toast.show {
            opacity: 1;
            transform: translateX(-50%) translateY(0);
        }

        .toast-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: var(--gold);
            flex-shrink: 0;
        }

        /* ─── Animations ───────────────────────────────────────── */
        @keyframes fadeUp {
            from {
                opacity: 0;
                transform: translateY(16px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .item-card {
            animation: fadeUp 0.4s ease both;
        }

        /* Responsive */
        @media (min-width: 640px) {
            .menu-list {
                padding: 20px 24px;
            }
        }
    </style>
</head>

<body>

    <!-- ═══════════════════════════════════════════════════════ -->
    <!-- HEADER                                                  -->
    <!-- ═══════════════════════════════════════════════════════ -->
    <header class="header">
        <div class="header-left">
            <span class="header-brand">{{ strtoupper(config('app.name', 'GOURMET')) }}</span>
            <span class="header-date">{{ now()->translatedFormat('D d M') }}</span>
        </div>
        <div class="header-right">
            <button class="lang-toggle" onclick="toggleLanguage()">
                {{ app()->getLocale() === 'ar' ? 'EN' : 'AR' }}
            </button>
            <button class="cart-btn" id="cart-btn" onclick="showCart()">
                <svg viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M6 2L3 6v14a2 2 0 002 2h14a2 2 0 002-2V6l-3-4zM3 6h18M16 10a4 4 0 01-8 0" />
                </svg>
                <span class="cart-badge" id="cart-badge">0</span>
            </button>
        </div>
    </header>

    <!-- ═══════════════════════════════════════════════════════ -->
    <!-- HERO                                                    -->
    <!-- ═══════════════════════════════════════════════════════ -->
    <section class="hero">
        <div class="season-label">
            <span>{{ __('menu.seasonal_menu') }}</span>
        </div>
        <h1 class="brand-title">GOURMET</h1>
        <p class="brand-desc">{{ __('menu.welcome') }} — {{ __('menu.table') }} {{ $table->table_number }}</p>
    </section>

    <!-- ═══════════════════════════════════════════════════════ -->
    <!-- CATEGORY TABS                                           -->
    <!-- ═══════════════════════════════════════════════════════ -->
    <div class="tabs-wrapper">
        <div class="tabs-scroll" id="tabs-scroll">
            @foreach ($categories as $category)
                <button class="tab-btn {{ $loop->first ? 'active' : '' }}" data-cat="cat-{{ $category->id }}"
                    onclick="selectTab('cat-{{ $category->id }}', this)">
                    @if ($loop->first)
                        <span class="tab-star">★</span>
                    @endif
                    {{ $category->name }}
                </button>
            @endforeach
        </div>
    </div>

    <!-- ═══════════════════════════════════════════════════════ -->
    <!-- MENU LIST                                               -->
    <!-- ═══════════════════════════════════════════════════════ -->
    <main class="menu-list">
        @foreach ($categories as $category)
            <div class="category-section" id="cat-{{ $category->id }}">
                <div class="category-header">
                    <h2>{{ $category->name }}</h2>
                </div>

                @forelse($category->items as $item)
                    <div class="item-card"
                        data-item="{{ json_encode(['id' => $item->id, 'name' => $item->name, 'description' => $item->description, 'price' => $item->price, 'show_price' => $item->show_price, 'image' => $item->image, 'preparation_time' => $item->preparation_time]) }}"
                        onclick="openItem(this)">

                        <!-- Image -->
                        <div class="item-image-wrap">
                            @if ($item->image)
                                <img src="{{ Storage::url($item->image) }}" alt="{{ $item->name }}"
                                    class="item-img">
                            @else
                                <div class="item-img-placeholder">🍽️</div>
                            @endif

                            @if ($item->is_popular)
                                <span class="item-badge badge-popular">★ {{ __('menu.popular') }}</span>
                            @elseif($item->is_chef_choice)
                                <span class="item-badge badge-chef">{{ __('menu.chef_choice') }}</span>
                            @elseif($item->is_spicy)
                                <span class="item-badge badge-spicy">🌶 {{ __('menu.spicy') }}</span>
                            @endif
                        </div>

                        <!-- Body -->
                        <div class="item-body">
                            <h3 class="item-name">{{ $item->name }}</h3>
                            @if ($item->description)
                                <p class="item-desc">{{ $item->description }}</p>
                            @endif

                            <div class="item-footer" onclick="event.stopPropagation()">
                                <div>
                                    @if ($item->show_price)
                                        <div class="item-price">${{ number_format($item->price, 2) }}</div>
                                    @else
                                        <div class="price-on-request">
                                            {{ __('menu.price') }}</div>
                                    @endif
                                </div>
                                <button class="add-btn" data-item-id="{{ $item->id }}"
                                    onclick="quickAdd({{ $item->id }})">
                                    <svg viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                                    </svg>
                                    {{ __('menu.add_to_cart') }}
                                </button>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="empty-cat">{{ __('menu.cart_empty') }}</div>
                @endforelse
            </div>
        @endforeach
    </main>

    <!-- ═══════════════════════════════════════════════════════ -->
    <!-- BOTTOM BAR                                              -->
    <!-- ═══════════════════════════════════════════════════════ -->
    <div class="bottom-bar" id="bottom-bar">
        <div class="bar-left">
            <div class="bar-label">{{ __('menu.total_order') }}</div>
            <div class="bar-total">$<span id="bar-total">0.00</span></div>
        </div>
        <button class="bar-btn" onclick="showCart()">
            {{ __('menu.view_cart_arrow') }}
        </button>
    </div>

    <!-- ═══════════════════════════════════════════════════════ -->
    <!-- ITEM DETAIL DRAWER                                      -->
    <!-- ═══════════════════════════════════════════════════════ -->
    <div class="overlay" id="item-overlay" onclick="closeItem()"></div>
    <div class="drawer" id="item-drawer">
        <div class="drawer-handle"></div>
        <div id="drawer-img-area"></div>
        <div class="drawer-body">
            <h2 class="drawer-title" id="drawer-title"></h2>
            <div class="drawer-price" id="drawer-price"></div>
            <p class="drawer-desc" id="drawer-desc"></p>
            <div class="qty-row">
                <button class="qty-btn" onclick="changeQty(-1)">−</button>
                <span class="qty-val" id="modal-qty">1</span>
                <button class="qty-btn" onclick="changeQty(1)">+</button>
            </div>
            <button class="add-to-order-btn" onclick="addFromDrawer()">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#0d0d0d"
                    stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
                {{ __('menu.add_to_order') }} — $<span id="drawer-total">0.00</span>
            </button>
        </div>
    </div>

    <!-- ═══════════════════════════════════════════════════════ -->
    <!-- CART DRAWER                                             -->
    <!-- ═══════════════════════════════════════════════════════ -->
    <div class="overlay" id="cart-overlay" onclick="closeCart()"></div>
    <div class="drawer" id="cart-drawer">
        <div class="drawer-handle"></div>
        <div class="cart-drawer-body">
            <h2 class="cart-drawer-title">{{ __('menu.your_order') }}</h2>
            <div id="cart-items-list"></div>
            <div class="cart-total-row">
                <span class="cart-total-label">{{ __('menu.total') }}</span>
                <span class="cart-total-val" id="cart-total-display">$0.00</span>
            </div>
            <button class="checkout-btn" onclick="goCheckout()">
                {{ __('menu.confirm_order') }} →
            </button>
        </div>
    </div>

    <!-- Toast -->
    <div class="toast" id="toast">
        <div class="toast-dot"></div>
        <span id="toast-msg">{{ __('menu.item_added_to_cart') }}</span>
    </div>

    <!-- ═══════════════════════════════════════════════════════ -->
    <!-- SCRIPT                                                  -->
    <!-- ═══════════════════════════════════════════════════════ -->
    <script>
        const TRANSLATIONS = {
            noDescription: "{{ __('menu.no_description') }}",
            itemAdded: "{{ __('menu.item_added_to_cart') }}",
            itemsAdded: "{{ __('menu.items_added') }}",
            itemAddedSingle: "{{ __('menu.item_added_single') }}",
            orderEmpty: "{{ __('menu.your_cart_is_empty') }}",
            qtyLabel: "{{ __('menu.qty') }}",
            errorAdding: "{{ __('menu.error_adding_item') }}"
        };
        const CSRF = document.querySelector('meta[name="csrf-token"]').content;
        let cart = [];
        let activeItem = null;
        let modalQty = 1;

        /* ── Init ── */
        document.addEventListener('DOMContentLoaded', () => {
            loadCart();
            initScrollSpy();
        });

        /* ── Language toggle ── */
        function toggleLanguage() {
            const cur = document.documentElement.getAttribute('lang');
            const next = cur === 'ar' ? 'en' : 'ar';
            window.location.href = `{{ route('language.set') }}?locale=${next}&redirect=menu`;
        }

        /* ── Category tabs ── */
        function selectTab(catId, btn) {
            document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
            btn.classList.add('active');

            const el = document.getElementById(catId);
            if (el) {
                const offset = parseInt(getComputedStyle(document.documentElement)
                        .getPropertyValue('--header-h')) +
                    parseInt(getComputedStyle(document.documentElement)
                        .getPropertyValue('--tabs-h')) + 12;
                const top = el.getBoundingClientRect().top + window.pageYOffset - offset;
                window.scrollTo({
                    top,
                    behavior: 'smooth'
                });
            }
        }

        /* ── Scroll spy: highlight tab when section in view ── */
        function initScrollSpy() {
            const header = 64 + 58 + 16;
            const sections = document.querySelectorAll('.category-section');
            const tabs = document.querySelectorAll('.tab-btn');

            const obs = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        const id = entry.target.id;
                        tabs.forEach(t => {
                            t.classList.toggle('active', t.dataset.cat === id);
                        });
                    }
                });
            }, {
                rootMargin: `-${header}px 0px -60% 0px`,
                threshold: 0
            });

            sections.forEach(s => obs.observe(s));
        }

        /* ── Open item drawer ── */
        function openItem(card) {
            activeItem = JSON.parse(card.dataset.item);
            modalQty = 1;

            /* image */
            const imgArea = document.getElementById('drawer-img-area');
            if (activeItem.image) {
                imgArea.innerHTML = `<img src="/storage/${activeItem.image}" alt="${activeItem.name}" class="drawer-img">`;
            } else {
                imgArea.innerHTML = `<div class="drawer-img-placeholder">🍽️</div>`;
            }

            document.getElementById('drawer-title').textContent = activeItem.name;
            document.getElementById('drawer-price').textContent =
                activeItem.show_price ? `$${parseFloat(activeItem.price).toFixed(2)}` : '';
            document.getElementById('drawer-desc').textContent =
                activeItem.description || TRANSLATIONS.noDescription;
            updateDrawerTotal();

            document.getElementById('item-overlay').classList.add('active');
            document.getElementById('item-drawer').classList.add('active');
            document.body.style.overflow = 'hidden';
        }

        function closeItem() {
            document.getElementById('item-overlay').classList.remove('active');
            document.getElementById('item-drawer').classList.remove('active');
            document.body.style.overflow = '';
            activeItem = null;
        }

        function changeQty(d) {
            modalQty = Math.max(1, modalQty + d);
            document.getElementById('modal-qty').textContent = modalQty;
            updateDrawerTotal();
        }

        function updateDrawerTotal() {
            const total = (activeItem && activeItem.show_price) ?
                (parseFloat(activeItem.price) * modalQty).toFixed(2) :
                '0.00';
            document.getElementById('drawer-total').textContent = total;
        }

        async function addFromDrawer() {
            if (!activeItem) return;
            await addToCart(activeItem.id, modalQty);
            closeItem();
        }

        /* ── Quick add (from card button) ── */
        async function quickAdd(itemId) {
            await addToCart(itemId, 1);
        }

        /* ── Cart API ── */
        async function addToCart(itemId, qty = 1) {
            try {
                const res = await fetch('/cart/add', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': CSRF
                    },
                    body: JSON.stringify({
                        item_id: itemId,
                        quantity: qty
                    })
                });
                const data = await res.json();
                if (data.success) {
                    cart = data.cart;
                    updateUI();
                    showToast(qty > 1 ? `${qty} ${TRANSLATIONS.itemsAdded}` : TRANSLATIONS.itemAddedSingle);
                }
            } catch (e) {
                console.error(e);
                showToast(TRANSLATIONS.errorAdding, true);
            }
        }

        async function loadCart() {
            try {
                const res = await fetch('/cart/summary');
                const data = await res.json();
                cart = data.cart || [];
                updateUI();
            } catch (e) {
                console.error(e);
            }
        }

        /* ── Update UI with cart state ── */
        function updateUI() {
            const bar = document.getElementById('bottom-bar');
            const badge = document.getElementById('cart-badge');
            const total = document.getElementById('bar-total');

            if (!cart || cart.length === 0) {
                bar.classList.remove('visible');
                badge.style.display = 'none';
                return;
            }

            const count = cart.reduce((s, i) => s + i.quantity, 0);
            const amount = cart.reduce((s, i) =>
                i.show_price ? s + i.price * i.quantity : s, 0);

            total.textContent = amount.toFixed(2);
            badge.textContent = count;
            badge.style.display = 'flex';
            bar.classList.add('visible');
        }

        /* ── Show cart drawer ── */
        function showCart() {
            const list = document.getElementById('cart-items-list');
            const totEl = document.getElementById('cart-total-display');

            if (!cart || cart.length === 0) {
                list.innerHTML = `<p class="cart-empty-msg">${TRANSLATIONS.orderEmpty}</p>`;
                totEl.textContent = '$0.00';
            } else {
                let html = '',
                    total = 0;
                cart.forEach(item => {
                    const sub = item.show_price ? item.price * item.quantity : 0;
                    total += sub;
                    html += `
                        <div class="c-item">
                            <div>
                                <div class="c-name">${item.name}</div>
                                <div class="c-qty">${TRANSLATIONS.qtyLabel}: ${item.quantity}</div>
                            </div>
                            <div class="c-price">$${sub.toFixed(2)}</div>
                        </div>`;
                });
                list.innerHTML = html;
                totEl.textContent = `$${total.toFixed(2)}`;
            }

            document.getElementById('cart-overlay').classList.add('active');
            document.getElementById('cart-drawer').classList.add('active');
            document.body.style.overflow = 'hidden';
        }

        function closeCart() {
            document.getElementById('cart-overlay').classList.remove('active');
            document.getElementById('cart-drawer').classList.remove('active');
            document.body.style.overflow = '';
        }

        function goCheckout() {
            window.location.href = '/checkout';
        }

        /* ── Toast ── */
        function showToast(msg, isError = false) {
            const t = document.getElementById('toast');
            const m = document.getElementById('toast-msg');
            const d = t.querySelector('.toast-dot');
            m.textContent = msg;
            d.style.background = isError ? '#ef4444' : 'var(--gold)';
            t.classList.add('show');
            setTimeout(() => t.classList.remove('show'), 2500);
        }

        /* ── Swipe to close drawers ── */
        let swipeY = 0;
        document.querySelectorAll('.drawer').forEach(drawer => {
            drawer.addEventListener('touchstart', e => {
                swipeY = e.touches[0].clientY;
            }, {
                passive: true
            });
            drawer.addEventListener('touchmove', e => {
                if (e.touches[0].clientY - swipeY > 90 && drawer.scrollTop === 0) {
                    if (drawer.id === 'item-drawer') closeItem();
                    if (drawer.id === 'cart-drawer') closeCart();
                }
            }, {
                passive: true
            });
        });
    </script>
</body>

</html>

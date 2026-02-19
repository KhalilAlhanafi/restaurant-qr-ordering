<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Order #{{ $order->id }} — Table {{ $order->table->table_number }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link
        href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700;900&family=Montserrat:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">
    <style>
        /* ── Reset ────────────────────────────────────────── */
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
            --border: rgba(255, 255, 255, 0.06);
            --gold: #c9a84c;
            --gold-l: #e5c46a;
            --gold-dim: rgba(201, 168, 76, 0.12);
            --white: #ffffff;
            --gray: rgba(255, 255, 255, 0.45);
            --green: #3ec98a;
            --red: #ef4444;
            --blue: #60a5fa;
        }

        body {
            font-family: 'Montserrat', sans-serif;
            background: var(--bg);
            color: var(--white);
            min-height: 100vh;
            min-height: 100dvh;
            padding-bottom: 110px;
        }

        /* ── Success Hero Section ─────────────────────────── */
        .hero {
            background: var(--surface);
            border-bottom: 1px solid var(--border);
            padding: 48px 20px 40px;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        /* Cross-grid pattern background */
        .hero::before {
            content: '';
            position: absolute;
            inset: 0;
            background-image:
                linear-gradient(rgba(255, 255, 255, 0.03) 1px, transparent 1px),
                linear-gradient(90deg, rgba(255, 255, 255, 0.03) 1px, transparent 1px);
            background-size: 36px 36px;
            pointer-events: none;
        }

        /* Gold glow orb behind icon */
        .hero::after {
            content: '';
            position: absolute;
            top: 20px;
            left: 50%;
            transform: translateX(-50%);
            width: 160px;
            height: 160px;
            background: radial-gradient(circle, rgba(201, 168, 76, 0.15), transparent 70%);
            pointer-events: none;
        }

        .success-ring {
            position: relative;
            z-index: 1;
            width: 84px;
            height: 84px;
            margin: 0 auto 24px;
        }

        .success-circle {
            width: 84px;
            height: 84px;
            border-radius: 50%;
            background: var(--gold-dim);
            border: 2px solid var(--gold);
            display: flex;
            align-items: center;
            justify-content: center;
            animation: popIn 0.6s cubic-bezier(0.4, 0, 0.2, 1) both;
            box-shadow:
                0 0 0 12px rgba(201, 168, 76, 0.06),
                0 0 0 24px rgba(201, 168, 76, 0.03);
        }

        @keyframes popIn {
            0% {
                transform: scale(0.5);
                opacity: 0;
            }

            60% {
                transform: scale(1.1);
            }

            100% {
                transform: scale(1);
                opacity: 1;
            }
        }

        .success-circle svg {
            width: 36px;
            height: 36px;
            stroke: var(--gold);
            fill: none;
            stroke-width: 2.5;
        }

        .hero-title {
            font-family: 'Playfair Display', serif;
            font-size: 26px;
            font-weight: 700;
            color: var(--white);
            margin-bottom: 8px;
            position: relative;
            z-index: 1;
            animation: fadeUp 0.5s 0.2s ease both;
        }

        .hero-sub {
            font-size: 13.5px;
            color: var(--gray);
            max-width: 280px;
            margin: 0 auto;
            line-height: 1.6;
            position: relative;
            z-index: 1;
            animation: fadeUp 0.5s 0.3s ease both;
        }

        @keyframes fadeUp {
            from {
                opacity: 0;
                transform: translateY(12px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* ── Container ────────────────────────────────────── */
        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px 16px;
            display: flex;
            flex-direction: column;
            gap: 14px;
        }

        /* ── Card ─────────────────────────────────────────── */
        .card {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 20px;
            overflow: hidden;
        }

        .card-head {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 18px 20px;
            border-bottom: 1px solid var(--border);
        }

        .card-head-left {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .card-bar {
            width: 3px;
            height: 16px;
            background: var(--gold);
            border-radius: 2px;
        }

        .card-label {
            font-size: 12px;
            font-weight: 600;
            letter-spacing: 2px;
            text-transform: uppercase;
            color: var(--gray);
        }

        /* ── Order meta row ───────────────────────────────── */
        .order-meta {
            padding: 16px 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            border-bottom: 1px solid var(--border);
        }

        .order-num {
            font-size: 20px;
            font-weight: 700;
            color: var(--white);
        }

        .order-table {
            font-size: 12px;
            color: var(--gray);
            margin-top: 3px;
        }

        /* Status badge */
        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 7px 14px;
            border-radius: 50px;
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 0.5px;
            text-transform: uppercase;
            flex-shrink: 0;
        }

        .badge-dot {
            width: 7px;
            height: 7px;
            border-radius: 50%;
            background: currentColor;
            animation: blink 2s infinite;
        }

        @keyframes blink {

            0%,
            100% {
                opacity: 1;
            }

            50% {
                opacity: 0.4;
            }
        }

        .status-pending {
            background: rgba(251, 191, 36, 0.12);
            color: #fbbf24;
            border: 1px solid rgba(251, 191, 36, 0.2);
        }

        .status-preparing {
            background: rgba(96, 165, 250, 0.12);
            color: #60a5fa;
            border: 1px solid rgba(96, 165, 250, 0.2);
        }

        .status-ready {
            background: rgba(62, 201, 138, 0.12);
            color: #3ec98a;
            border: 1px solid rgba(62, 201, 138, 0.2);
        }

        .status-served {
            background: rgba(167, 139, 250, 0.12);
            color: #a78bfa;
            border: 1px solid rgba(167, 139, 250, 0.2);
        }

        .status-completed {
            background: var(--gold-dim);
            color: var(--gold);
            border: 1px solid rgba(201, 168, 76, 0.2);
        }

        /* ── Progress tracker ─────────────────────────────── */
        .progress-wrap {
            padding: 20px 20px 22px;
        }

        .progress-steps {
            display: flex;
            justify-content: space-between;
            position: relative;
        }

        /* Connecting line behind steps */
        .progress-steps::before {
            content: '';
            position: absolute;
            top: 19px;
            left: 10%;
            right: 10%;
            height: 2px;
            background: var(--surface2);
            z-index: 0;
        }

        .p-step {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 8px;
            z-index: 1;
            flex: 1;
        }

        .p-step-dot {
            width: 38px;
            height: 38px;
            border-radius: 50%;
            background: var(--surface2);
            border: 2px solid rgba(255, 255, 255, 0.08);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 15px;
            transition: all 0.4s ease;
            flex-shrink: 0;
        }

        .p-step-dot.active {
            background: var(--gold-dim);
            border-color: var(--gold);
            box-shadow: 0 0 0 6px rgba(201, 168, 76, 0.08);
        }

        .p-step-dot.done {
            background: rgba(62, 201, 138, 0.15);
            border-color: var(--green);
        }

        .p-step-dot.done svg {
            width: 16px;
            height: 16px;
            stroke: var(--green);
            fill: none;
            stroke-width: 2.5;
        }

        .p-step-label {
            font-size: 10px;
            font-weight: 600;
            letter-spacing: 0.5px;
            color: var(--gray);
            text-align: center;
            line-height: 1.3;
        }

        /* ── ETA card ─────────────────────────────────────── */
        .eta-inner {
            display: flex;
            align-items: center;
            gap: 16px;
            padding: 16px 20px 20px;
        }

        .eta-icon {
            width: 52px;
            height: 52px;
            background: var(--gold-dim);
            border: 1px solid rgba(201, 168, 76, 0.2);
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 22px;
            flex-shrink: 0;
        }

        .eta-label {
            font-size: 10px;
            font-weight: 600;
            letter-spacing: 2px;
            text-transform: uppercase;
            color: var(--gray);
            margin-bottom: 5px;
        }

        .eta-val {
            font-size: 28px;
            font-weight: 700;
            color: var(--gold);
            line-height: 1;
        }

        .eta-unit {
            font-size: 14px;
            color: var(--gray);
            font-weight: 400;
        }

        /* ── Items list ───────────────────────────────────── */
        .items-list {
            padding: 0 20px 4px;
        }

        .item-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 14px 0;
            border-bottom: 1px solid var(--border);
            gap: 12px;
            transition: background 0.3s ease;
        }

        .item-row:last-child {
            border-bottom: none;
        }

        .item-row.new-item {
            background: linear-gradient(90deg, rgba(201, 168, 76, 0.06), transparent);
            border-left: 3px solid var(--gold);
            padding-left: 12px;
            animation: slideIn 0.4s ease;
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateX(-16px);
            }

            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        .item-row-info {
            display: flex;
            align-items: center;
            gap: 12px;
            flex: 1;
        }

        .qty-chip {
            width: 28px;
            height: 28px;
            background: var(--surface2);
            border: 1px solid var(--border);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
            font-weight: 700;
            color: var(--gold);
            flex-shrink: 0;
        }

        .item-name {
            font-size: 14px;
            font-weight: 600;
            color: var(--white);
        }

        .item-price-tag {
            font-size: 14px;
            font-weight: 700;
            color: var(--gold);
            flex-shrink: 0;
        }

        /* ── Special requests block ───────────────────────── */
        .special-req-block {
            margin: 8px 20px 16px;
            background: rgba(201, 168, 76, 0.05);
            border-left: 3px solid rgba(201, 168, 76, 0.4);
            border-radius: 0 10px 10px 0;
            padding: 12px 16px;
        }

        .special-req-label {
            font-size: 10px;
            font-weight: 600;
            letter-spacing: 2px;
            text-transform: uppercase;
            color: var(--gray);
            margin-bottom: 4px;
        }

        .special-req-text {
            font-size: 13.5px;
            color: rgba(255, 255, 255, 0.7);
            font-style: italic;
        }

        /* ── Total section ────────────────────────────────── */
        .totals-block {
            padding: 8px 20px 20px;
        }

        .taxes-section {
            background: var(--surface2);
            border: 1px solid var(--border);
            border-radius: 12px;
            padding: 14px 16px;
            margin-bottom: 14px;
        }

        .taxes-title {
            font-size: 10px;
            font-weight: 600;
            letter-spacing: 2px;
            text-transform: uppercase;
            color: var(--gray);
            margin-bottom: 10px;
        }

        .tax-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 6px 0;
            border-bottom: 1px solid rgba(255, 255, 255, 0.04);
            font-size: 13px;
        }

        .tax-row:last-child {
            border-bottom: none;
        }

        .tax-row-label {
            color: var(--gray);
        }

        .tax-row-val {
            color: var(--gold);
            font-weight: 600;
        }

        .subtotal-row,
        .tax-sum-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 7px 0;
            font-size: 13px;
            color: var(--gray);
        }

        .divider-thin {
            height: 1px;
            background: var(--border);
            margin: 10px 0;
        }

        .total-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 12px 0 4px;
        }

        .total-row-label {
            font-size: 14px;
            font-weight: 600;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            color: var(--gray);
        }

        .total-row-val {
            font-size: 28px;
            font-weight: 700;
            color: var(--white);
        }

        /* ── Fixed action bar ─────────────────────────────── */
        .actions-bar {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            z-index: 100;
            background: var(--surface);
            border-top: 1px solid var(--border);
            padding: 14px 20px calc(14px + env(safe-area-inset-bottom));
            display: flex;
            gap: 12px;
        }

        .btn {
            flex: 1;
            padding: 14px 20px;
            border-radius: 50px;
            font-family: 'Montserrat', sans-serif;
            font-size: 13.5px;
            font-weight: 700;
            cursor: pointer;
            border: none;
            text-decoration: none;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            transition: all 0.25s ease;
            text-align: center;
        }

        .btn svg {
            width: 16px;
            height: 16px;
            stroke: currentColor;
            fill: none;
            stroke-width: 2.5;
        }

        .btn-outline {
            background: transparent;
            border: 1px solid rgba(255, 255, 255, 0.1);
            color: var(--gray);
        }

        .btn-outline:hover {
            border-color: rgba(255, 255, 255, 0.2);
            color: var(--white);
        }

        .btn-gold {
            background: var(--gold);
            color: #0d0d0d;
            box-shadow: 0 4px 16px rgba(201, 168, 76, 0.35);
        }

        .btn-gold:hover {
            background: var(--gold-l);
            box-shadow: 0 8px 24px rgba(201, 168, 76, 0.45);
        }

        /* ── Toast/notification ───────────────────────────── */
        .notif {
            position: fixed;
            top: 20px;
            left: 50%;
            transform: translateX(-50%) translateY(-80px);
            background: var(--surface2);
            border: 1px solid rgba(201, 168, 76, 0.25);
            color: var(--white);
            padding: 13px 22px;
            border-radius: 50px;
            font-size: 13.5px;
            font-weight: 500;
            z-index: 3000;
            opacity: 0;
            white-space: nowrap;
            transition: all 0.4s ease;
            display: flex;
            align-items: center;
            gap: 10px;
            box-shadow: 0 12px 32px rgba(0, 0, 0, 0.5);
        }

        .notif.show {
            opacity: 1;
            transform: translateX(-50%) translateY(0);
        }

        .notif-icon {
            font-size: 16px;
        }

        /* ── Checked-out paid badge ───────────────────────── */
        .paid-badge {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            background: rgba(62, 201, 138, 0.12);
            color: var(--green);
            border: 1px solid rgba(62, 201, 138, 0.2);
            font-size: 11px;
            font-weight: 700;
            padding: 5px 12px;
            border-radius: 50px;
            margin-top: 6px;
        }

        /* ── RTL support ──────────────────────────────────── */
        [dir="rtl"] .order-meta {
            flex-direction: row-reverse;
        }

        [dir="rtl"] .card-head {
            flex-direction: row-reverse;
        }

        [dir="rtl"] .item-row {
            flex-direction: row-reverse;
        }

        [dir="rtl"] .item-row-info {
            flex-direction: row-reverse;
        }

        [dir="rtl"] .total-row {
            flex-direction: row-reverse;
        }

        [dir="rtl"] .actions-bar {
            flex-direction: row-reverse;
        }
    </style>
</head>

<body>

    <!-- ═══════════════════════════════════════════════════════ -->
    <!-- HERO                                                    -->
    <!-- ═══════════════════════════════════════════════════════ -->
    <div class="hero">
        <div class="success-ring">
            <div class="success-circle">
                @if ($order->is_checked_out)
                    <svg viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                    </svg>
                @else
                    <svg viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                    </svg>
                @endif
            </div>
        </div>

        <h1 class="hero-title">
            @if ($order->is_checked_out)
                {{ __('menu.order_completed') }}
            @else
                {{ __('menu.order_number') }} #{{ $order->id }}
            @endif
        </h1>
        <p class="hero-sub">
            @if ($order->is_checked_out)
                {{ __('menu.order_completed_msg') }}
            @else
                {{ __('menu.order_sent_msg') }}
            @endif
        </p>
    </div>

    <!-- ═══════════════════════════════════════════════════════ -->
    <!-- MAIN CONTENT                                            -->
    <!-- ═══════════════════════════════════════════════════════ -->
    <div class="container">

        <!-- ── Status & Progress Card ───────────────────────── -->
        <div class="card">
            <!-- Order # / status -->
            <div class="order-meta">
                <div>
                    <div class="order-num">{{ __('menu.order_number') }} #{{ $order->id }}</div>
                    <div class="order-table">{{ __('menu.table') }} {{ $order->table->table_number }}</div>
                </div>
                <div style="text-align:right;">
                    <span class="status-badge status-{{ $order->status }}" id="status-badge">
                        <span class="badge-dot"></span>
                        <span id="status-text">{{ ucfirst($order->status) }}</span>
                    </span>
                    @if ($order->is_checked_out)
                        <div><span class="paid-badge">✓ {{ __('menu.paid') }}</span></div>
                    @endif
                </div>
            </div>

            @if (!$order->is_checked_out)
                <!-- Progress Steps -->
                <div class="progress-wrap">
                    <div class="progress-steps" id="progress-steps">

                        @php
                            $statuses = ['pending', 'preparing', 'ready', 'served'];
                            $labels = [
                                __('menu.status_received'),
                                __('menu.status_preparing'),
                                __('menu.status_ready'),
                                __('menu.status_served'),
                            ];
                            $icons = ['📋', '👨‍🍳', '🍽', '✨'];
                            $cur = array_search($order->status, $statuses);
                        @endphp

                        @foreach ($statuses as $i => $st)
                            <div class="p-step">
                                <div class="p-step-dot {{ $i < $cur ? 'done' : ($i === $cur ? 'active' : '') }}"
                                    id="step-{{ $i }}">
                                    @if ($i < $cur)
                                        <svg viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                        </svg>
                                    @else
                                        {{ $icons[$i] }}
                                    @endif
                                </div>
                                <span class="p-step-label">{{ $labels[$i] }}</span>
                            </div>
                        @endforeach

                    </div>
                </div>
            @endif
        </div>

        @if (!$order->is_checked_out)
            <!-- ── ETA Card ─────────────────────────────────────── -->
            <div class="card">
                <div class="eta-inner">
                    <div class="eta-icon">⏱️</div>
                    <div>
                        <div class="eta-label">{{ __('menu.estimated_time') }}</div>
                        <div class="eta-val">
                            <span id="est-minutes">{{ $order->estimated_minutes }}</span>
                            <span class="eta-unit">{{ __('menu.minutes') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <!-- ── Order Details Card ───────────────────────────── -->
        <div class="card">
            <div class="card-head">
                <div class="card-head-left">
                    <div class="card-bar"></div>
                    <span class="card-label">{{ __('menu.order_details') }}</span>
                </div>
            </div>

            <ul class="items-list" id="order-items-list">
                @foreach ($order->orderItems as $oi)
                    <li class="item-row" data-item-id="{{ $oi->id }}">
                        <div class="item-row-info">
                            <span class="qty-chip">{{ $oi->quantity }}</span>
                            <span class="item-name">{{ $oi->item->name }}</span>
                        </div>
                        <span class="item-price-tag">
                            @if ($oi->item->show_price)
                                ${{ number_format($oi->unit_price * $oi->quantity, 2) }}
                            @else
                                —
                            @endif
                        </span>
                    </li>
                @endforeach
            </ul>

            @if ($order->special_requests)
                <div class="special-req-block">
                    <div class="special-req-label">{{ __('menu.special_requests') }}</div>
                    <div class="special-req-text">{{ $order->special_requests }}</div>
                </div>
            @endif

            <!-- Totals -->
            <div class="totals-block">
                @if ($order->taxes->count() > 0)
                    <div class="taxes-section">
                        <div class="taxes-title">{{ __('menu.taxes_fees') }}</div>
                        @foreach ($order->taxes as $tax)
                            <div class="tax-row">
                                <span class="tax-row-label">
                                    {{ $tax->title }}
                                    @if ($tax->type === 'percentage')
                                        ({{ $tax->value }}%)
                                    @endif
                                </span>
                                <span class="tax-row-val">${{ number_format($tax->pivot->tax_amount, 2) }}</span>
                            </div>
                        @endforeach
                    </div>
                @endif

                <div class="subtotal-row">
                    <span>{{ __('menu.subtotal') }}</span>
                    <span>${{ number_format($order->orderItems->sum('subtotal'), 2) }}</span>
                </div>

                @if ($order->taxes->count() > 0)
                    <div class="tax-sum-row">
                        <span>{{ __('menu.tax') }}</span>
                        <span>${{ number_format($order->taxes->sum('pivot.tax_amount'), 2) }}</span>
                    </div>
                @endif

                <div class="divider-thin"></div>

                <div class="total-row">
                    <span class="total-row-label">{{ __('menu.total') }}</span>
                    <span class="total-row-val" id="order-total">${{ number_format($order->total_amount, 2) }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- ═══════════════════════════════════════════════════════ -->
    <!-- FIXED ACTION BAR                                        -->
    <!-- ═══════════════════════════════════════════════════════ -->
    <div class="actions-bar">
        @if (!$order->is_checked_out)
            <a href="/menu" class="btn btn-outline">
                <svg viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                </svg>
                {{ __('menu.add_more') }}
            </a>
            <form action="{{ route('checkout.finalize') }}" method="POST" style="flex:1; display:flex;">
                @csrf
                <button type="submit" class="btn btn-gold" style="width:100%"
                    onclick="return confirm('{{ __('menu.checkout_confirm') }}')">
                    <svg viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                    </svg>
                    {{ __('menu.checkout') }}
                </button>
            </form>
        @else
            <a href="/menu" class="btn btn-gold" style="font-size:14px;">
                <svg viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                </svg>
                {{ __('menu.start_new_order') }}
            </a>
        @endif
    </div>

    <!-- Status notification toast -->
    <div class="notif" id="notif">
        <span class="notif-icon" id="notif-icon">🍽</span>
        <span id="notif-text">{{ __('menu.your_order_is_being_prepared') }}</span>
    </div>

    <!-- ═══════════════════════════════════════════════════════ -->
    <!-- SCRIPT                                                  -->
    <!-- ═══════════════════════════════════════════════════════ -->
    <script>
        /* ── Translations ──────────────────────────────────── */
        const trans = {
            orderReceived: "{{ __('menu.order_received') }}",
            nowPreparing: "{{ __('menu.now_preparing') }}",
            orderReady: "{{ __('menu.order_ready') }}",
            orderServed: "{{ __('menu.order_served') }}",
            orderCompleted: "{{ __('menu.order_completed') }}",
            statusReceived: "{{ __('menu.status_received') }}",
            statusPreparing: "{{ __('menu.status_preparing') }}",
            statusReady: "{{ __('menu.status_ready') }}",
            statusServed: "{{ __('menu.status_served') }}",
            newItemsMsg: "{{ __('menu.items_added') }}"
        };

        /* ── State ─────────────────────────────────────────── */
        let lastStatus = '{{ $order->status }}';
        let lastItemCount = {{ $order->orderItems->count() }};
        let lastTotal = {{ $order->total_amount }};

        /* ── Poll order status every 2s ─────────────────────  */
        async function checkStatus() {
            try {
                const res = await fetch('/admin/orders/{{ $order->id }}/data');
                const order = await res.json();

                if (order.status !== lastStatus) {
                    lastStatus = order.status;
                    updateStatusBadge(order.status);
                    updateProgressSteps(order.status);
                    showNotif(order.status);
                }

                if (order.total_items > lastItemCount || Math.abs(order.total_amount - lastTotal) > 0.01) {
                    lastItemCount = order.total_items;
                    lastTotal = order.total_amount;
                    addNewItems(order.items);
                    updateTotal(order.total_amount);
                    showRawNotif('🍽', trans.newItemsMsg);
                }

                if (order.status === 'completed' || order.status === 'cancelled') {
                    location.reload();
                }
            } catch (e) {
                console.log('Poll failed:', e);
            }
        }

        /* ── Update status badge ────────────────────────────── */
        function updateStatusBadge(status) {
            const badge = document.getElementById('status-badge');
            const text = document.getElementById('status-text');
            if (!badge) return;

            const classes = ['status-pending', 'status-preparing', 'status-ready', 'status-served', 'status-completed'];
            badge.classList.remove(...classes);
            badge.classList.add('status-' + status);

            const labels = {
                pending: trans.statusReceived,
                preparing: trans.statusPreparing,
                ready: trans.statusReady,
                served: trans.statusServed,
                completed: trans.statusServed,
            };
            if (text) text.textContent = labels[status] || status;
        }

        /* ── Update progress steps ──────────────────────────── */
        function updateProgressSteps(status) {
            const order = ['pending', 'preparing', 'ready', 'served'];
            const icons = ['📋', '👨‍🍳', '🍽', '✨'];
            const cur = order.indexOf(status);

            order.forEach((_, i) => {
                const dot = document.getElementById('step-' + i);
                if (!dot) return;

                if (i < cur) {
                    dot.className = 'p-step-dot done';
                    dot.innerHTML =
                        '<svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="var(--green)" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>';
                } else if (i === cur) {
                    dot.className = 'p-step-dot active';
                    dot.textContent = icons[i];
                }
            });
        }

        /* ── Add new items to list ──────────────────────────── */
        function addNewItems(items) {
            const list = document.getElementById('order-items-list');
            const known = new Set();
            list.querySelectorAll('.item-row').forEach(r => known.add(r.dataset.itemId));

            items.forEach(item => {
                if (!known.has(String(item.id))) {
                    const li = document.createElement('li');
                    li.className = 'item-row new-item';
                    li.dataset.itemId = item.id;
                    li.innerHTML = `
                        <div class="item-row-info">
                            <span class="qty-chip">${item.quantity}</span>
                            <span class="item-name">${item.name}</span>
                        </div>
                        <span class="item-price-tag">${item.unit_price ? '$' + (item.unit_price * item.quantity).toFixed(2) : '—'}</span>
                    `;
                    list.appendChild(li);
                    setTimeout(() => li.classList.remove('new-item'), 3500);
                }
            });
        }

        /* ── Update total display ───────────────────────────── */
        function updateTotal(amt) {
            const el = document.getElementById('order-total');
            if (!el) return;
            el.textContent = '$' + amt.toFixed(2);
            el.style.color = 'var(--gold)';
            setTimeout(() => el.style.color = '', 2000);
        }

        /* ── Show notification ──────────────────────────────── */
        function showNotif(status) {
            const msgs = {
                pending: {
                    icon: '📋',
                    text: trans.orderReceived
                },
                preparing: {
                    icon: '👨‍🍳',
                    text: trans.nowPreparing
                },
                ready: {
                    icon: '🍽',
                    text: trans.orderReady
                },
                served: {
                    icon: '✨',
                    text: trans.orderServed
                },
                completed: {
                    icon: '✓',
                    text: trans.orderCompleted
                },
            };
            if (!msgs[status]) return;
            showRawNotif(msgs[status].icon, msgs[status].text);
        }

        function showRawNotif(icon, text) {
            const n = document.getElementById('notif');
            document.getElementById('notif-icon').textContent = icon;
            document.getElementById('notif-text').textContent = text;
            n.classList.add('show');
            setTimeout(() => n.classList.remove('show'), 4000);
        }

        /* ── Start polling ──────────────────────────────────── */
        @if (!$order->is_checked_out)
            document.addEventListener('DOMContentLoaded', () => {
                setInterval(checkStatus, 2000);
            });
        @endif
    </script>
</body>

</html>

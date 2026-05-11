<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ __('menu.your_order') }} — Table {{ $table->table_number }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link
        href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700;900&family=Montserrat:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">
    <style>
        /* ── Reset ───────────────────────────────────────────── */
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
            --gray-dim: rgba(255, 255, 255, 0.1);
            --red: #ef4444;
            --green: #3ec98a;
            --header-h: 60px;
        }

        body {
            font-family: 'Montserrat', sans-serif;
            background: var(--bg);
            color: var(--white);
            min-height: 100vh;
            min-height: 100dvh;
            padding-bottom: 110px;
        }

        /* ── Header ─────────────────────────────────────────── */
        .header {
            position: sticky;
            top: 0;
            z-index: 200;
            height: var(--header-h);
            background: var(--bg);
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 20px;
        }

        .back-link {
            display: flex;
            align-items: center;
            gap: 8px;
            color: var(--gray);
            text-decoration: none;
            font-size: 13px;
            font-weight: 500;
            letter-spacing: 0.3px;
            transition: color 0.2s ease;
        }

        .back-link:hover {
            color: var(--white);
        }

        .back-link svg {
            width: 18px;
            height: 18px;
        }

        .header-title {
            font-family: 'Playfair Display', serif;
            font-size: 17px;
            font-weight: 700;
            color: var(--white);
            letter-spacing: 1px;
        }

        .table-pill {
            background: var(--gold-dim);
            border: 1px solid rgba(201, 168, 76, 0.25);
            color: var(--gold);
            font-size: 11px;
            font-weight: 600;
            letter-spacing: 1.5px;
            padding: 6px 14px;
            border-radius: 50px;
            text-transform: uppercase;
        }

        /* ── Container ───────────────────────────────────────── */
        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px 16px;
            display: flex;
            flex-direction: column;
            gap: 14px;
        }

        /* ── Section card ────────────────────────────────────── */
        .card {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 20px;
            overflow: hidden;
        }

        .card-head {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 18px 20px 0;
            margin-bottom: 16px;
        }

        .card-head-bar {
            width: 3px;
            height: 16px;
            background: var(--gold);
            border-radius: 2px;
            flex-shrink: 0;
        }

        .card-head-title {
            font-size: 13px;
            font-weight: 600;
            letter-spacing: 2px;
            text-transform: uppercase;
            color: var(--gray);
        }

        /* ── Cart items ──────────────────────────────────────── */
        .items-wrap {
            padding: 0 20px 4px;
        }

        .cart-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 14px 0;
            border-bottom: 1px solid var(--border);
            gap: 12px;
        }

        .cart-row:last-child {
            border-bottom: none;
        }

        .row-left {
            flex: 1;
        }

        .row-name {
            font-size: 15px;
            font-weight: 600;
            color: var(--white);
            margin-bottom: 10px;
        }

        .row-controls {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .qty-btn {
            width: 32px;
            height: 32px;
            border-radius: 10px;
            border: 1px solid rgba(255, 255, 255, 0.1);
            background: var(--surface2);
            color: var(--white);
            font-size: 16px;
            font-weight: 600;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.2s ease;
            font-family: 'Montserrat', sans-serif;
        }

        .qty-btn:hover {
            border-color: var(--gold);
            background: var(--gold-dim);
            color: var(--gold);
        }

        .qty-num {
            font-size: 15px;
            font-weight: 700;
            color: var(--white);
            min-width: 24px;
            text-align: center;
        }

        .remove-btn {
            font-size: 11.5px;
            font-weight: 600;
            color: rgba(239, 68, 68, 0.7);
            cursor: pointer;
            padding: 4px 8px;
            border-radius: 6px;
            transition: all 0.2s ease;
            letter-spacing: 0.3px;
        }

        .remove-btn:hover {
            background: rgba(239, 68, 68, 0.1);
            color: var(--red);
        }

        .row-price {
            font-size: 16px;
            font-weight: 700;
            color: var(--gold);
            flex-shrink: 0;
            text-align: right;
        }

        /* ── Total line ──────────────────────────────────────── */
        .total-line {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 18px 20px 20px;
            border-top: 1px solid var(--border);
            margin-top: 4px;
        }

        .total-label {
            font-size: 14px;
            font-weight: 600;
            color: var(--gray);
            letter-spacing: 1.5px;
            text-transform: uppercase;
        }

        .total-val {
            font-size: 26px;
            font-weight: 700;
            color: var(--white);
        }

        /* ── Empty state ─────────────────────────────────────── */
        .empty-state {
            display: none;
            padding: 52px 20px;
            text-align: center;
        }

        .empty-icon {
            width: 68px;
            height: 68px;
            background: var(--surface2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 30px;
            margin: 0 auto 20px;
            border: 1px solid var(--border);
        }

        .empty-title {
            font-size: 17px;
            font-weight: 700;
            color: var(--white);
            margin-bottom: 8px;
        }

        .empty-sub {
            font-size: 13px;
            color: var(--gray);
            margin-bottom: 24px;
        }

        .browse-link {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 13px 26px;
            background: var(--gold);
            color: #0d0d0d;
            font-size: 13.5px;
            font-weight: 700;
            border-radius: 50px;
            text-decoration: none;
            transition: all 0.25s ease;
        }

        .browse-link:hover {
            background: var(--gold-l);
            box-shadow: 0 8px 24px rgba(201, 168, 76, 0.4);
        }

        /* ── ETA card ────────────────────────────────────────── */
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

        .eta-value {
            font-size: 26px;
            font-weight: 700;
            color: var(--gold);
            line-height: 1;
        }

        .eta-note {
            font-size: 11.5px;
            color: rgba(255, 255, 255, 0.3);
            margin-top: 4px;
        }

        /* ── Notes card ──────────────────────────────────────── */
        .notes-wrap {
            padding: 0 20px 20px;
        }

        .notes-textarea {
            width: 100%;
            min-height: 100px;
            background: var(--surface2);
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 14px;
            padding: 14px 16px;
            color: var(--white);
            font-family: 'Montserrat', sans-serif;
            font-size: 14px;
            font-weight: 400;
            resize: vertical;
            transition: border-color 0.25s ease;
            line-height: 1.6;
        }

        .notes-textarea::placeholder {
            color: rgba(255, 255, 255, 0.2);
        }

        .notes-textarea:focus {
            outline: none;
            border-color: rgba(201, 168, 76, 0.4);
            box-shadow: 0 0 0 3px rgba(201, 168, 76, 0.06);
        }

        /* ── Bottom bar ──────────────────────────────────────── */
        .bottom-bar {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            z-index: 100;
            background: var(--surface);
            border-top: 1px solid var(--border);
            padding: 14px 20px calc(14px + env(safe-area-inset-bottom));
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

        .bar-info {}

        .bar-items-label {
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

        .place-btn {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 14px 24px;
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

        .place-btn:hover {
            background: var(--gold-l);
            box-shadow: 0 8px 24px rgba(201, 168, 76, 0.45);
        }

        .place-btn:disabled {
            background: rgba(255, 255, 255, 0.12);
            color: var(--gray);
            cursor: not-allowed;
            box-shadow: none;
        }

        .place-btn svg {
            width: 16px;
            height: 16px;
            stroke: currentColor;
            fill: none;
            stroke-width: 2.5;
        }

        /* ── Toast ───────────────────────────────────────────── */
        .toast {
            position: fixed;
            bottom: 100px;
            left: 50%;
            transform: translateX(-50%) translateY(20px);
            background: var(--surface2);
            border: 1px solid rgba(201, 168, 76, 0.2);
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
            width: 7px;
            height: 7px;
            border-radius: 50%;
            background: var(--gold);
            flex-shrink: 0;
        }

        /* ── RTL ─────────────────────────────────────────────── */
        [dir="rtl"] .back-link {
            flex-direction: row-reverse;
        }

        [dir="rtl"] .cart-row {
            flex-direction: row-reverse;
        }

        [dir="rtl"] .row-controls {
            flex-direction: row-reverse;
        }

        [dir="rtl"] .bottom-bar {
            flex-direction: row-reverse;
        }

        [dir="rtl"] .total-line {
            flex-direction: row-reverse;
        }
    </style>
</head>

<body>

    <!-- Header -->
    <header class="header">
        <a href="/menu" class="back-link">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
            {{ __('menu.back') }}
        </a>
        <span class="header-title">GOURMET</span>
        <span class="table-pill">{{ __('menu.table') }} {{ $table->table_number }}</span>
    </header>

    <div class="container">

        <!-- ── Your Order Card ──────────────────────────────── -->
        <div class="card">
            <div class="card-head">
                <div class="card-head-bar"></div>
                <span class="card-head-title">{{ __('menu.your_order') }}</span>
            </div>

            <!-- Items list (JS-rendered) -->
            <div class="items-wrap" id="cart-items-wrap"></div>

            <!-- Empty state -->
            <div class="empty-state" id="empty-state">
                <div class="empty-icon">🛒</div>
                <div class="empty-title">{{ __('menu.your_cart_is_empty') }}</div>
                <p class="empty-sub">{{ __('menu.add_delicious_items') }}</p>
                <a href="/menu" class="browse-link">
                    <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4" />
                    </svg>
                    {{ __('menu.browse_menu') }}
                </a>
            </div>

            <!-- Total -->
            <div class="total-line" id="total-line">
                <span class="total-label">{{ __('menu.total') }}</span>
                <span class="total-val" id="cart-total">$0.00</span>
            </div>
        </div>

        <!-- ── ETA Card ─────────────────────────────────────── -->
        <div class="card" id="eta-card">
            <div class="eta-inner">
                <div class="eta-icon">⏱️</div>
                <div>
                    <div class="eta-label">{{ __('menu.estimated_ready_time') }}</div>
                    <div class="eta-value">{{ $estimatedTime }} <span
                            style="font-size:14px;color:var(--gray);font-weight:400;">{{ __('menu.minutes') }}</span>
                    </div>
                    <div class="eta-note">{{ __('menu.estimated_time_note') }}</div>
                </div>
            </div>
        </div>

        <!-- ── Special Requests Card ────────────────────────── -->
        <div class="card" id="notes-card">
            <div class="card-head">
                <div class="card-head-bar"></div>
                <span class="card-head-title">{{ __('menu.special_requests') }}</span>
            </div>
            <div class="notes-wrap">
                <textarea id="special-requests" class="notes-textarea" placeholder="{{ __('menu.any_special_requests') }}"></textarea>
            </div>
        </div>

    </div>

    <!-- Bottom Bar -->
    <div class="bottom-bar" id="bottom-bar">
        <div class="bar-info">
            <div class="bar-items-label"><span id="bar-count">0</span> {{ __('menu.items') }}</div>
            <div class="bar-total" id="bar-total">$0.00</div>
        </div>
        <button class="place-btn" id="place-btn" onclick="placeOrder()">
            {{ __('menu.place_order_arrow') }}
        </button>
    </div>

    <!-- Toast -->
    <div class="toast" id="toast">
        <div class="toast-dot"></div>
        <span id="toast-msg">Done</span>
    </div>

    <script data-eta="{{ $estimatedTime }}">
        const CSRF = document.querySelector('meta[name="csrf-token"]').content;
        let cart = [];

        /* ─────────────── Load cart ─────────────── */
        async function loadCart() {
            try {
                const res = await fetch('/cart/summary');
                const data = await res.json();
                cart = data.cart || [];
                render();
            } catch (e) {
                console.error(e);
                render();
            }
        }

        /* ─────────────── Render ─────────────────  */
        function render() {
            const wrap = document.getElementById('cart-items-wrap');
            const empty = document.getElementById('empty-state');
            const bar = document.getElementById('bottom-bar');
            const etaCard = document.getElementById('eta-card');
            const notesCard = document.getElementById('notes-card');
            const totalLine = document.getElementById('total-line');

            if (cart.length === 0) {
                wrap.innerHTML = '';
                empty.style.display = 'block';
                bar.classList.remove('visible');
                etaCard.style.display = 'none';
                notesCard.style.display = 'none';
                totalLine.style.display = 'none';
                return;
            }

            empty.style.display = 'none';
            etaCard.style.display = 'block';
            notesCard.style.display = 'block';
            totalLine.style.display = 'flex';
            bar.classList.add('visible');

            let html = '',
                total = 0,
                count = 0;

            cart.forEach((item, idx) => {
                const sub = item.show_price ? item.price * item.quantity : 0;
                total += sub;
                count += item.quantity;
                html += `
                    <div class="cart-row" style="flex-direction: column; align-items: stretch; gap: 12px;">
                        <div style="display: flex; justify-content: space-between; align-items: start; width: 100%;">
                            <div class="row-left">
                                <div class="row-name" style="margin-bottom: 8px;">${item.name}</div>
                                <div class="row-controls">
                                    <button class="qty-btn" onclick="updateQty(${idx}, -1)">−</button>
                                    <span class="qty-num">${item.quantity}</span>
                                    <button class="qty-btn" onclick="updateQty(${idx}, 1)">+</button>
                                    <span class="remove-btn" onclick="removeItem(${idx})">{{ __('menu.remove') }}</span>
                                </div>
                            </div>
                            <div class="row-price">${item.show_price ? '$' + sub.toFixed(2) : '—'}</div>
                        </div>
                        <div class="item-note-edit" style="width: 100%;">
                            <textarea 
                                class="notes-textarea" 
                                style="min-height: 44px; font-size: 12px; padding: 10px; border-radius: 10px;" 
                                placeholder="{{ __('menu.edit_item_note') }}"
                                onchange="updateNote(${idx}, this.value)"
                            >${item.note || ''}</textarea>
                        </div>
                    </div>
                `;
            });

            wrap.innerHTML = html;
            document.getElementById('cart-total').textContent = '$' + total.toFixed(2);
            document.getElementById('bar-total').textContent = '$' + total.toFixed(2);
            document.getElementById('bar-count').textContent = count;
        }

        /* ─────────────── Update qty ─────────────── */
        async function updateQty(idx, delta) {
            const newQty = cart[idx].quantity + delta;
            if (newQty < 1) return;
            try {
                const res = await fetch('/cart/update', {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': CSRF
                    },
                    body: JSON.stringify({
                        index: idx,
                        quantity: newQty
                    })
                });
                const data = await res.json();
                if (data.success) {
                    cart = data.cart;
                    render();
                }
            } catch (e) {
                console.error(e);
            }
        }

        /* ─────────────── Update note ─────────────── */
        async function updateNote(idx, note) {
            try {
                const res = await fetch('/cart/update', {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': CSRF
                    },
                    body: JSON.stringify({
                        index: idx,
                        note: note
                    })
                });
                const data = await res.json();
                if (data.success) {
                    cart = data.cart;
                    // No full render here to keep focus/state if needed, but for now render is fine
                    // render();
                }
            } catch (e) {
                console.error(e);
            }
        }
        async function removeItem(idx) {
            try {
                const res = await fetch('/cart/remove', {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': CSRF
                    },
                    body: JSON.stringify({
                        index: idx
                    })
                });
                const data = await res.json();
                if (data.success) {
                    cart = data.cart;
                    render();
                    toast('{{ __('menu.item_removed') }}');
                }
            } catch (e) {
                console.error(e);
            }
        }

        /* ─────────────── Place order ────────────── */
        async function placeOrder() {
            const btn = document.getElementById('place-btn');
            btn.disabled = true;
            btn.innerHTML = '<span>{{ __('menu.placing') }}…</span>';

            const notes = document.getElementById('special-requests').value;

            try {
                const res = await fetch('/checkout', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': CSRF,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        items: cart.map(i => ({
                            id: i.id,
                            quantity: i.quantity,
                            notes: i.note
                        })),
                        special_requests: notes
                    })
                });

                const contentType = res.headers.get('content-type');
                if (!contentType || !contentType.includes('application/json')) {
                    // Server returned HTML (likely redirect to login or error page)
                    const text = await res.text();
                    console.error('Non-JSON response:', text.substring(0, 200));

                    if (res.status === 419 || text.includes('csrf') || text.includes('login')) {
                        toast('Session expired. Please refresh the page and try again.', true);
                    } else if (res.status === 200 && text.includes('<!DOCTYPE')) {
                        toast('Server returned an unexpected page. Please refresh and try again.', true);
                    } else {
                        toast('Server error: ' + res.status + '. Please try again.', true);
                    }
                    btn.disabled = false;
                    btn.innerHTML =
                        '<svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg> {{ __('menu.place_order') }}';
                    return;
                }

                const data = await res.json();

                if (res.ok && data.success) {
                    window.location.href = data.redirect || '/order-confirmation/' + data.order_id;
                } else {
                    toast((data.message || data.error || '{{ __('menu.error_placing_order') }}'), true);
                    btn.disabled = false;
                    btn.innerHTML =
                        '<svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg> {{ __('menu.place_order') }}';
                }
            } catch (e) {
                console.error('Place order error:', e);
                toast('Network error. Please check your connection and try again.', true);
                btn.disabled = false;
                btn.innerHTML =
                    '<svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg> {{ __('menu.place_order') }}';
            }
        }

        /* ─────────────── Toast ──────────────────── */
        function toast(msg, isErr = false) {
            const t = document.getElementById('toast');
            const d = t.querySelector('.toast-dot');
            document.getElementById('toast-msg').textContent = msg;
            d.style.background = isErr ? '#ef4444' : 'var(--gold)';
            t.classList.add('show');
            setTimeout(() => t.classList.remove('show'), 2600);
        }

        document.addEventListener('DOMContentLoaded', loadCart);
    </script>
</body>

</html>

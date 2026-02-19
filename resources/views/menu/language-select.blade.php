<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Select Language — {{ config('app.name', 'Gourmet') }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link
        href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,700;0,900;1,400&family=Montserrat:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            -webkit-tap-highlight-color: transparent;
        }

        body {
            font-family: 'Montserrat', sans-serif;
            background: #0a0a0a;
            min-height: 100vh;
            min-height: 100dvh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow: hidden;
        }

        /* Cross/Plus grid pattern background */
        .bg-pattern {
            position: fixed;
            inset: 0;
            pointer-events: none;
            overflow: hidden;
        }

        .bg-pattern::before {
            content: '';
            position: absolute;
            inset: -50%;
            background-image:
                linear-gradient(rgba(255, 255, 255, 0.045) 1px, transparent 1px),
                linear-gradient(90deg, rgba(255, 255, 255, 0.045) 1px, transparent 1px);
            background-size: 44px 44px;
        }

        /* Subtle radial vignette */
        .bg-pattern::after {
            content: '';
            position: absolute;
            inset: 0;
            background: radial-gradient(ellipse at center, transparent 40%, rgba(0, 0, 0, 0.75) 100%);
        }

        /* Main layout */
        .page-wrapper {
            position: relative;
            z-index: 1;
            width: 100%;
            max-width: 420px;
            padding: 24px 20px;
            display: flex;
            flex-direction: column;
            align-items: center;
            min-height: 100vh;
            min-height: 100dvh;
            justify-content: center;
            gap: 32px;
        }

        /* Card */
        .card {
            width: 100%;
            background: rgba(20, 19, 17, 0.92);
            border: 1px solid rgba(201, 168, 76, 0.12);
            border-radius: 28px;
            padding: 44px 32px 36px;
            box-shadow:
                0 30px 80px rgba(0, 0, 0, 0.6),
                inset 0 1px 0 rgba(255, 255, 255, 0.04);
            backdrop-filter: blur(20px);
        }

        /* Restaurant name */
        .restaurant-name {
            font-family: 'Playfair Display', serif;
            font-size: clamp(42px, 12vw, 56px);
            font-weight: 700;
            color: #c9a84c;
            letter-spacing: 6px;
            text-align: center;
            line-height: 1;
            margin-bottom: 12px;
            text-shadow: 0 0 40px rgba(201, 168, 76, 0.25);
        }

        /* Thin gold divider */
        .gold-divider {
            width: 56px;
            height: 1px;
            background: linear-gradient(90deg, transparent, #c9a84c, transparent);
            margin: 0 auto 20px;
        }

        /* Welcome text */
        .welcome-text {
            font-size: 13.5px;
            font-weight: 400;
            color: rgba(255, 255, 255, 0.42);
            text-align: center;
            letter-spacing: 0.3px;
            line-height: 1.6;
            margin-bottom: 36px;
        }

        /* Language cards grid */
        .lang-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 14px;
            margin-bottom: 32px;
        }

        .lang-card {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 14px;
            padding: 28px 16px 22px;
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid rgba(255, 255, 255, 0.07);
            border-radius: 20px;
            text-decoration: none;
            cursor: pointer;
            transition: all 0.35s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
        }

        .lang-card::before {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, rgba(201, 168, 76, 0.06), transparent 60%);
            opacity: 0;
            transition: opacity 0.35s ease;
            border-radius: 20px;
        }

        .lang-card:hover {
            border-color: rgba(201, 168, 76, 0.28);
            transform: translateY(-5px);
            box-shadow: 0 16px 40px rgba(0, 0, 0, 0.35);
        }

        .lang-card:hover::before {
            opacity: 1;
        }

        .lang-card:active {
            transform: scale(0.97);
        }

        /* Language icon sphere */
        .lang-icon {
            width: 82px;
            height: 82px;
            border-radius: 50%;
            position: relative;
            box-shadow:
                0 8px 24px rgba(0, 0, 0, 0.5),
                inset 0 1px 0 rgba(255, 255, 255, 0.25);
            flex-shrink: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 38px;
            font-weight: 700;
            color: rgba(255, 255, 255, 0.95);
            text-shadow: 0 2px 10px rgba(0, 0, 0, 0.4);
        }

        /* English — cool teal/green planet */
        .en-icon {
            background:
                radial-gradient(circle at 35% 30%,
                    #7ef7d4 0%,
                    #34c99e 20%,
                    #1a9b7c 42%,
                    #0d5c4a 65%,
                    #041a12 100%);
        }

        .en-icon::after {
            content: '';
            position: absolute;
            inset: 0;
            border-radius: 50%;
            background:
                radial-gradient(circle at 38% 28%, rgba(255, 255, 255, 0.35) 0%, transparent 40%);
        }

        /* Arabic — warm golden desert/sand */
        .ar-icon {
            background:
                radial-gradient(circle at 50% 60%,
                    #ffe87a 0%,
                    #f5c518 20%,
                    #e08e0b 45%,
                    #a85500 70%,
                    #3d1100 100%);
        }

        .ar-icon::after {
            content: '';
            position: absolute;
            inset: 0;
            border-radius: 50%;
            background:
                radial-gradient(circle at 38% 28%, rgba(255, 255, 255, 0.32) 0%, transparent 38%);
        }

        /* Language name & label */
        .lang-name {
            font-size: 16.5px;
            font-weight: 600;
            color: rgba(255, 255, 255, 0.9);
            letter-spacing: 0.3px;
            text-align: center;
        }

        .lang-label {
            font-size: 11.5px;
            font-weight: 400;
            color: rgba(255, 255, 255, 0.28);
            letter-spacing: 0.5px;
            text-align: center;
            margin-top: -6px;
        }

        /* Table badge */
        .table-badge {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            background: rgba(255, 255, 255, 0.04);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 50px;
            padding: 13px 24px;
            margin: 0 auto;
        }

        .table-icon {
            color: rgba(255, 255, 255, 0.35);
            font-size: 13px;
            line-height: 1;
            letter-spacing: -2px;
        }

        .table-text {
            font-size: 12px;
            font-weight: 600;
            color: rgba(255, 255, 255, 0.6);
            letter-spacing: 2.5px;
            text-transform: uppercase;
        }

        /* Footer */
        .footer-text {
            font-size: 10px;
            font-weight: 500;
            letter-spacing: 3.5px;
            text-transform: uppercase;
            color: rgba(255, 255, 255, 0.18);
            text-align: center;
        }

        /* Entrance animations */
        @keyframes fadeUp {
            from {
                opacity: 0;
                transform: translateY(24px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .card {
            animation: fadeUp 0.6s ease both;
        }

        .footer-text {
            animation: fadeUp 0.6s 0.2s ease both;
        }
    </style>
</head>

<body>
    <div class="bg-pattern"></div>

    <div class="page-wrapper">
        <div class="card">
            <!-- Restaurant name -->
            <h1 class="restaurant-name">GOURMET</h1>
            <div class="gold-divider"></div>
            <p class="welcome-text">{{ __('menu.welcome_select_language') }}</p>

            <!-- Language options -->
            <div class="lang-grid">
                <!-- English -->
                <a href="{{ route('language.set', ['locale' => 'en', 'redirect' => 'menu']) }}" class="lang-card">
                    <div class="lang-icon en-icon">
                        <span style="position: relative; z-index: 2;">E</span>
                    </div>
                    <span class="lang-name">English</span>
                    <span class="lang-label">{{ __('menu.select') }}</span>
                </a>

                <!-- Arabic -->
                <a href="{{ route('language.set', ['locale' => 'ar', 'redirect' => 'menu']) }}" class="lang-card">
                    <div class="lang-icon ar-icon">
                        <span
                            style="position: relative; z-index: 2; font-family: Arial, sans-serif; font-size: 42px;">ع</span>
                    </div>
                    <span class="lang-name" style="font-family: 'Montserrat', Arial, sans-serif;">العربية</span>
                    <span class="lang-label">{{ __('menu.select') }}</span>
                </a>
            </div>

            <!-- Table number -->
            <div class="table-badge">
                <span class="table-icon">🍴</span>
                <span class="table-text">{{ __('menu.table_no') }} {{ $table->table_number }}</span>
            </div>
        </div>

        <p class="footer-text">{{ __('menu.exquisite_experience') }}</p>
    </div>
</body>

</html>

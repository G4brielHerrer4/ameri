<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-theme="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="color-scheme" content="dark light">
    <meta name="theme-color" content="#05080a">

    <title>{{ config('app.name', 'AMERI') }} - Crear Cuenta</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700|space-grotesk:400,500,600,700" rel="stylesheet" />
    <!-- Material Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@mdi/font@7.2.96/css/materialdesignicons.min.css">

    <script>
        (function () {
            try {
                var saved = localStorage.getItem('ameri-theme');
                if (saved === 'light' || saved === 'dark') {
                    document.documentElement.setAttribute('data-theme', saved);
                }
            } catch (e) {}
        })();
    </script>

    <style>
        /* ==================== DESIGN TOKENS ==================== */
        :root {
            --bg: #05080a;
            --bg-1: #0a1014;
            --bg-2: #0f171c;
            --bg-3: #16222a;
            --ink: #eefcff;
            --ink-muted: #8fa8ae;
            --ink-faint: #566d74;

            --cyan: #00fff0;
            --cyan-2: #00d9cc;
            --cyan-dim: #00a89e;
            --cyan-ink: #00201e;

            --line: rgba(0, 255, 240, 0.14);
            --line-strong: rgba(0, 255, 240, 0.38);
            --glow: rgba(0, 255, 240, 0.45);
            --glow-soft: rgba(0, 255, 240, 0.18);
            --shadow: rgba(0, 0, 0, 0.5);
            --surface-alpha: rgba(255, 255, 255, 0.03);

            --error: #ff5570;
            --error-bg: rgba(255, 85, 112, 0.08);
            --error-line: rgba(255, 85, 112, 0.28);

            --font-display: 'Space Grotesk', 'Instrument Sans', sans-serif;
            --font-body: 'Instrument Sans', sans-serif;

            --r-sm: 10px;
            --r-md: 16px;
            --r-lg: 22px;
            --r-xl: 28px;
            --r-pill: 999px;

            --ease: cubic-bezier(0.16, 1, 0.3, 1);
            --ease-out: cubic-bezier(0.22, 1, 0.36, 1);
        }

        html[data-theme="light"] {
            --bg: #eef4f5;
            --bg-1: #ffffff;
            --bg-2: #e4eef0;
            --bg-3: #d6e4e7;
            --ink: #05161a;
            --ink-muted: #476067;
            --ink-faint: #7a9198;

            --cyan: #00b8ab;
            --cyan-2: #009e93;
            --cyan-dim: #007d74;
            --cyan-ink: #ffffff;

            --line: rgba(0, 140, 130, 0.18);
            --line-strong: rgba(0, 140, 130, 0.42);
            --glow: rgba(0, 160, 150, 0.28);
            --glow-soft: rgba(0, 160, 150, 0.12);
            --shadow: rgba(10, 40, 45, 0.14);
            --surface-alpha: rgba(0, 40, 48, 0.03);

            --error: #d9354f;
            --error-bg: rgba(217, 53, 79, 0.07);
            --error-line: rgba(217, 53, 79, 0.25);
        }

        /* ==================== RESET ==================== */
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: var(--font-body);
            background-color: var(--bg);
            color: var(--ink);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1.25rem;
            position: relative;
            overflow-x: hidden;
            transition: background-color 0.5s var(--ease), color 0.5s var(--ease);
            -webkit-font-smoothing: antialiased;
        }

        body::before {
            content: '';
            position: fixed;
            inset: 0;
            background-image:
                linear-gradient(var(--line) 1px, transparent 1px),
                linear-gradient(90deg, var(--line) 1px, transparent 1px);
            background-size: 64px 64px;
            -webkit-mask-image: radial-gradient(ellipse at 50% 30%, rgba(0,0,0,0.9), transparent 75%);
            mask-image: radial-gradient(ellipse at 50% 30%, rgba(0,0,0,0.9), transparent 75%);
            pointer-events: none;
            z-index: 0;
        }

        body::after {
            content: '';
            position: fixed;
            top: -30%;
            right: -15%;
            width: 640px;
            height: 640px;
            background: radial-gradient(circle, var(--glow-soft) 0%, transparent 70%);
            border-radius: 50%;
            pointer-events: none;
            filter: blur(40px);
            z-index: 0;
        }

        .bg-orb {
            position: fixed;
            bottom: -30%;
            left: -15%;
            width: 560px;
            height: 560px;
            background: radial-gradient(circle, var(--glow-soft) 0%, transparent 70%);
            border-radius: 50%;
            pointer-events: none;
            filter: blur(40px);
            z-index: 0;
        }

        ::selection { background: var(--cyan); color: var(--cyan-ink); }

        :focus-visible {
            outline: 2px solid var(--cyan);
            outline-offset: 3px;
            border-radius: 6px;
        }

        @media (prefers-reduced-motion: reduce) {
            *, *::before, *::after {
                animation-duration: 0.001ms !important;
                animation-iteration-count: 1 !important;
                transition-duration: 0.001ms !important;
            }
        }

        ::-webkit-scrollbar { width: 10px; }
        ::-webkit-scrollbar-track { background: var(--bg-1); }
        ::-webkit-scrollbar-thumb {
            background: linear-gradient(var(--cyan-dim), var(--cyan-2));
            border-radius: 10px;
        }
        ::-webkit-scrollbar-thumb:hover { background: var(--cyan); }

        /* ==================== REGISTER CONTAINER ==================== */
        .register-container {
            width: 100%;
            max-width: 780px; /* Más ancho por las 2 columnas */
            position: relative;
            z-index: 1;
            animation: fadeInUp 0.7s var(--ease-out);
        }

        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(28px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* ==================== REGISTER CARD ==================== */
        .register-card {
            position: relative;
            background: var(--bg-1);
            border: 1px solid var(--line);
            border-radius: var(--r-xl);
            padding: 2.5rem;
            box-shadow: 0 40px 80px -40px var(--shadow);
            overflow: hidden;
        }

        .register-card::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0;
            height: 1px;
            background: linear-gradient(90deg, transparent, var(--cyan), transparent);
            opacity: 0.7;
        }

        .register-card::after {
            content: '';
            position: absolute;
            top: -40%; left: 50%;
            transform: translateX(-50%);
            width: 400px;
            height: 400px;
            background: radial-gradient(circle, var(--glow-soft) 0%, transparent 70%);
            pointer-events: none;
            opacity: 0.6;
        }

        /* ==================== THEME TOGGLE ==================== */
        .theme-toggle {
            position: absolute;
            top: 1.25rem;
            right: 1.25rem;
            width: 42px;
            height: 42px;
            border-radius: var(--r-pill);
            border: 1px solid var(--line);
            background: var(--surface-alpha);
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            color: var(--ink);
            z-index: 5;
            transition: all 0.3s var(--ease);
        }
        .theme-toggle:hover {
            border-color: var(--line-strong);
            transform: rotate(15deg) scale(1.05);
            box-shadow: 0 0 20px -4px var(--glow);
        }
        .theme-toggle svg { width: 17px; height: 17px; }
        .theme-toggle .icon-sun { display: none; }
        html[data-theme="light"] .theme-toggle .icon-moon { display: none; }
        html[data-theme="light"] .theme-toggle .icon-sun { display: block; }

        /* ==================== LOGO / HEADER ==================== */
        .logo-section {
            position: relative;
            z-index: 2;
            text-align: center;
            margin-bottom: 1.9rem;
        }

        .logo {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1rem;
            padding: 0.8rem 1.2rem;
            border: 1px solid var(--line);
            border-radius: var(--r-lg);
            background: var(--surface-alpha);
            transition: all 0.35s var(--ease);
        }
        .logo:hover {
            border-color: var(--line-strong);
            box-shadow: 0 0 26px -8px var(--glow);
        }
        .logo img {
            height: 52px;
            width: auto;
            object-fit: contain;
        }

        .logo-section h2 {
            font-family: var(--font-display);
            font-size: 1.6rem;
            font-weight: 600;
            color: var(--ink);
            margin-bottom: 0.35rem;
            letter-spacing: -0.01em;
        }
        .logo-section h2 span { color: var(--cyan); }

        .logo-section p {
            font-size: 0.875rem;
            color: var(--ink-muted);
        }

        /* ==================== ALERTAS ==================== */
        .validation-errors {
            background: var(--error-bg);
            border: 1px solid var(--error-line);
            border-radius: var(--r-md);
            padding: 0.9rem 1.1rem;
            margin-bottom: 1.25rem;
            position: relative;
            z-index: 2;
            animation: shake 0.5s ease;
        }

        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            20% { transform: translateX(-8px); }
            40% { transform: translateX(8px); }
            60% { transform: translateX(-4px); }
            80% { transform: translateX(4px); }
        }

        .validation-errors p {
            color: var(--error);
            font-size: 0.83rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
            display: flex;
            align-items: center;
            gap: 0.4rem;
        }

        .validation-errors ul { list-style: none; padding-left: 0; }

        .validation-errors li {
            color: var(--error);
            font-size: 0.8rem;
            margin-bottom: 0.3rem;
            display: flex;
            align-items: flex-start;
            gap: 0.5rem;
            line-height: 1.4;
        }
        .validation-errors li:last-child { margin-bottom: 0; }

        /* ==================== FORM EN 2 COLUMNAS ==================== */
        .form-grid {
            position: relative;
            z-index: 2;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1.15rem 1.25rem;
        }

        .form-group { margin-bottom: 0; position: relative; }

        /* Campos que ocupan las 2 columnas */
        .form-group.full { grid-column: 1 / -1; }

        @media (max-width: 640px) {
            .form-grid { grid-template-columns: 1fr; gap: 1.15rem; }
            .form-group.full { grid-column: auto; }
        }

        /* ==================== LABEL ==================== */
        .form-label {
            display: flex;
            align-items: center;
            gap: 0.45rem;
            font-size: 0.76rem;
            font-weight: 600;
            color: var(--ink-muted);
            margin-bottom: 0.5rem;
            font-family: var(--font-display);
            letter-spacing: 0.04em;
            text-transform: uppercase;
        }

        .form-label svg {
            width: 14px;
            height: 14px;
            color: var(--cyan);
            flex-shrink: 0;
        }

        .form-label .required { color: var(--error); margin-left: 0.15rem; }

        /* ==================== INPUTS ==================== */
        .input-wrapper { position: relative; }

        .input-icon {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: var(--ink-faint);
            display: flex;
            align-items: center;
            pointer-events: none;
            transition: color 0.3s ease;
        }

        .input-icon svg { width: 18px; height: 18px; }

        .form-input {
            width: 100%;
            padding: 0.9rem 1rem 0.9rem 2.85rem;
            border: 1px solid var(--line);
            border-radius: var(--r-md);
            font-family: inherit;
            font-size: 0.95rem;
            color: var(--ink);
            background: var(--bg-2);
            transition: all 0.3s var(--ease);
        }

        .form-input::placeholder { color: var(--ink-faint); }
        .form-input:hover { border-color: var(--line-strong); }

        .form-input:focus {
            outline: none;
            border-color: var(--cyan);
            background: var(--bg-3);
            box-shadow: 0 0 0 4px var(--glow-soft), 0 0 22px -6px var(--glow);
        }

        .form-input:focus ~ .input-icon { color: var(--cyan); }

        /* Select */
        select.form-input {
            appearance: none;
            -webkit-appearance: none;
            cursor: pointer;
            padding-right: 2.5rem;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='8' viewBox='0 0 12 8'%3E%3Cpath d='M1 1l5 5 5-5' stroke='%238fa8ae' stroke-width='2' fill='none' stroke-linecap='round'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 1rem center;
        }

        html[data-theme="light"] select.form-input {
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='8' viewBox='0 0 12 8'%3E%3Cpath d='M1 1l5 5 5-5' stroke='%23476067' stroke-width='2' fill='none' stroke-linecap='round'/%3E%3C/svg%3E");
        }

        select.form-input option {
            background: var(--bg-1);
            color: var(--ink);
            padding: 0.5rem;
        }

        /* Error */
        .form-input.input-error {
            border-color: var(--error) !important;
            box-shadow: 0 0 0 4px var(--error-bg) !important;
        }

        .error-message {
            font-size: 0.78rem;
            color: var(--error);
            margin-top: 0.5rem;
            display: flex;
            align-items: center;
            gap: 0.4rem;
        }

        /* ==================== PASSWORD TOGGLE + INFO ==================== */
        .toggle-password {
            position: absolute;
            right: 3rem;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: var(--ink-faint);
            cursor: pointer;
            padding: 0.35rem;
            border-radius: var(--r-sm);
            display: flex;
            align-items: center;
            transition: color 0.25s ease;
            z-index: 3;
        }
        .toggle-password:hover { color: var(--cyan); }
        .toggle-password svg { width: 18px; height: 18px; }

        /* Botón "!" de requisitos */
        .pwd-info {
            position: absolute;
            right: 0.85rem;
            top: 50%;
            transform: translateY(-50%);
            z-index: 4;
        }

        .pwd-info-btn {
            width: 30px;
            height: 30px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: var(--r-pill);
            border: 1px solid var(--line);
            background: var(--surface-alpha);
            color: var(--ink-faint);
            cursor: pointer;
            padding: 0;
            transition: all 0.3s var(--ease);
        }

        .pwd-info-btn .mdi { font-size: 1.05rem; }

        .pwd-info-btn:hover,
        .pwd-info-btn:focus-visible,
        .pwd-info.active .pwd-info-btn {
            color: var(--cyan);
            border-color: var(--line-strong);
            box-shadow: 0 0 16px -4px var(--glow);
            transform: scale(1.06);
        }

        /* Tooltip de requisitos */
        .pwd-tooltip {
            position: absolute;
            top: calc(100% + 12px);
            right: 0;
            width: 250px;
            padding: 0.85rem 1rem;
            background: var(--bg-1);
            border: 1px solid var(--line-strong);
            border-radius: var(--r-md);
            box-shadow: 0 24px 48px -20px var(--shadow), 0 0 0 1px var(--line);
            opacity: 0;
            visibility: hidden;
            transform: translateY(-6px) scale(0.98);
            transform-origin: top right;
            transition: opacity 0.28s var(--ease), transform 0.28s var(--ease), visibility 0.28s var(--ease);
            pointer-events: none;
            z-index: 20;
        }

        .pwd-tooltip::before {
            content: '';
            position: absolute;
            top: -6px;
            right: 12px;
            width: 10px;
            height: 10px;
            background: var(--bg-1);
            border-top: 1px solid var(--line-strong);
            border-left: 1px solid var(--line-strong);
            transform: rotate(45deg);
        }

        .pwd-info:hover .pwd-tooltip,
        .pwd-info:focus-within .pwd-tooltip,
        .pwd-info.active .pwd-tooltip {
            opacity: 1;
            visibility: visible;
            transform: translateY(0) scale(1);
            pointer-events: auto;
        }

        .pwd-tooltip-title {
            font-size: 0.68rem;
            font-weight: 700;
            color: var(--ink-muted);
            margin-bottom: 0.55rem;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            font-family: var(--font-display);
        }

        .requirement {
            font-size: 0.76rem;
            color: var(--ink-faint);
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin-bottom: 0.3rem;
            transition: color 0.3s ease;
        }
        .requirement:last-child { margin-bottom: 0; }

        .requirement .mdi {
            font-size: 0.95rem;
            transition: all 0.3s var(--ease);
        }

        .requirement.met { color: var(--cyan); }
        .requirement.met .mdi {
            color: var(--cyan);
            text-shadow: 0 0 10px var(--glow);
            transform: scale(1.1);
        }

        /* ==================== TERMS ==================== */
        .terms-wrapper {
            display: flex;
            align-items: flex-start;
            gap: 0.75rem;
            cursor: pointer;
            padding: 0.35rem 0;
        }

        .terms-wrapper input[type="checkbox"] {
            width: 18px;
            height: 18px;
            min-width: 18px;
            margin-top: 2px;
            accent-color: var(--cyan);
            cursor: pointer;
        }

        .terms-wrapper span {
            font-size: 0.8rem;
            color: var(--ink-muted);
            line-height: 1.5;
        }

        .terms-wrapper a {
            color: var(--cyan);
            text-decoration: none;
            font-weight: 600;
            transition: all 0.25s ease;
        }

        .terms-wrapper a:hover {
            text-shadow: 0 0 12px var(--glow);
            text-decoration: underline;
        }

        /* ==================== BOTÓN PRINCIPAL ==================== */
        .btn-register {
            position: relative;
            width: 100%;
            padding: 1rem;
            background: linear-gradient(135deg, var(--cyan), var(--cyan-2));
            color: var(--cyan-ink);
            border: none;
            border-radius: var(--r-pill);
            font-family: inherit;
            font-weight: 700;
            font-size: 0.98rem;
            letter-spacing: 0.01em;
            cursor: pointer;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.6rem;
            box-shadow: 0 8px 28px -8px var(--glow);
            transition: all 0.35s var(--ease);
        }

        .btn-register::before {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(120deg, transparent 30%, rgba(255,255,255,0.5) 50%, transparent 70%);
            transform: translateX(-100%);
            transition: transform 0.7s var(--ease);
        }

        .btn-register:hover::before { transform: translateX(100%); }

        .btn-register:hover {
            transform: translateY(-3px);
            box-shadow: 0 16px 44px -8px var(--glow), 0 0 0 1px var(--cyan);
        }

        .btn-register:active { transform: translateY(-1px); }

        .btn-register .mdi { font-size: 1.15rem; }

        /* ==================== LOGIN LINK ==================== */
        .divider {
            text-align: center;
            margin: 1.5rem 0 1.1rem;
            position: relative;
        }

        .divider::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 0;
            right: 0;
            height: 1px;
            background: var(--line);
        }

        .divider span {
            background: var(--bg-1);
            padding: 0 1rem;
            position: relative;
            font-size: 0.75rem;
            color: var(--ink-faint);
            letter-spacing: 0.04em;
            text-transform: uppercase;
        }

        .login-link { text-align: center; position: relative; z-index: 2; }

        .login-link a {
            color: var(--cyan);
            text-decoration: none;
            font-weight: 600;
            font-size: 0.9rem;
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            transition: all 0.3s var(--ease);
        }

        .login-link a:hover {
            gap: 0.65rem;
            text-shadow: 0 0 14px var(--glow);
        }

        .login-link a svg { width: 15px; height: 15px; transition: transform 0.3s var(--ease); }
        .login-link a:hover svg { transform: translateX(3px); }

        /* ==================== BACK HOME ==================== */
        .back-home {
            text-align: center;
            margin-top: 1.4rem;
            animation: fadeInUp 0.7s var(--ease-out) 0.2s backwards;
        }

        .back-home a {
            color: var(--ink-muted);
            text-decoration: none;
            font-size: 0.85rem;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem 1rem;
            border-radius: var(--r-pill);
            border: 1px solid transparent;
            transition: all 0.3s var(--ease);
        }

        .back-home a:hover {
            color: var(--cyan);
            border-color: var(--line);
            background: var(--surface-alpha);
            box-shadow: 0 0 20px -6px var(--glow);
        }

        .back-home a svg { width: 15px; height: 15px; transition: transform 0.3s var(--ease); }
        .back-home a:hover svg { transform: translateX(-4px); }

        /* ==================== RESPONSIVE ==================== */
        @media (max-width: 640px) {
            body { padding: 1rem; align-items: flex-start; }

            .register-card { padding: 1.75rem 1.5rem; border-radius: var(--r-lg); }

            .theme-toggle {
                top: 1rem;
                right: 1rem;
                width: 38px;
                height: 38px;
            }

            .logo { padding: 0.7rem 1rem; }
            .logo img { height: 46px; }

            .logo-section h2 { font-size: 1.4rem; }
            .logo-section p { font-size: 0.82rem; }

            .form-input { padding: 0.85rem 0.9rem 0.85rem 2.7rem; font-size: 0.92rem; }
            .btn-register { padding: 0.9rem; font-size: 0.93rem; }

            .pwd-tooltip {
                width: 230px;
                right: -4px;
            }
            .pwd-tooltip::before { right: 16px; }
        }

        @media (max-width: 400px) {
            .register-card { padding: 1.5rem 1.15rem; }
            .logo-section h2 { font-size: 1.25rem; }
            .form-label { font-size: 0.7rem; }
            .form-input { font-size: 0.88rem; padding: 0.75rem 0.85rem 0.75rem 2.6rem; }
        }
    </style>
</head>
<body>
    <div class="bg-orb"></div>

    <div class="register-container">
        <div class="register-card">
            <!-- Theme toggle -->
            <button id="theme-toggle" class="theme-toggle" aria-label="Cambiar tema" type="button">
                <svg class="icon-moon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 12.79A9 9 0 1111.21 3 7 7 0 0021 12.79z"/></svg>
                <svg class="icon-sun" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="4"/><path d="M12 2v2M12 20v2M4.93 4.93l1.41 1.41M17.66 17.66l1.41 1.41M2 12h2M20 12h2M4.93 19.07l1.41-1.41M17.66 6.34l1.41-1.41"/></svg>
            </button>

            <!-- Logo -->
            <div class="logo-section">
                <div class="logo">
                    <img src="{{ asset('img/logo.png') }}" alt="AMERI">
                </div>
                <h2>Crear <span>Cuenta</span></h2>
                <p>Regístrate y comienza a construir</p>
            </div>

            <!-- Validation Errors -->
            @if ($errors->any())
                <div class="validation-errors">
                    <p>
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="12" cy="12" r="10"/>
                            <line x1="12" y1="8" x2="12" y2="12"/>
                            <line x1="12" y1="16" x2="12.01" y2="16"/>
                        </svg>
                        Por favor corrige los siguientes errores:
                    </p>
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>✕ {{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Register Form -->
            <form method="POST" action="{{ route('register') }}">
                @csrf

                <div class="form-grid">

                    <!-- Name -->
                    <div class="form-group">
                        <label class="form-label" for="name">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                                <circle cx="12" cy="7" r="4"/>
                            </svg>
                            Nombre Completo
                            <span class="required">*</span>
                        </label>
                        <div class="input-wrapper">
                            <input type="text" id="name" name="name"
                                   class="form-input @error('name') input-error @enderror"
                                   value="{{ old('name') }}" required autofocus autocomplete="name"
                                   placeholder="Ej. Juan Pérez">
                            <span class="input-icon">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                                    <circle cx="12" cy="7" r="4"/>
                                </svg>
                            </span>
                        </div>
                        @error('name')
                            <div class="error-message">
                                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <circle cx="12" cy="12" r="10"/>
                                    <line x1="12" y1="8" x2="12" y2="12"/>
                                    <line x1="12" y1="16" x2="12.01" y2="16"/>
                                </svg>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div class="form-group">
                        <label class="form-label" for="email">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/>
                                <polyline points="22,6 12,13 2,6"/>
                            </svg>
                            Correo Electrónico
                            <span class="required">*</span>
                        </label>
                        <div class="input-wrapper">
                            <input type="email" id="email" name="email"
                                   class="form-input @error('email') input-error @enderror"
                                   value="{{ old('email') }}" required autocomplete="username"
                                   placeholder="ejemplo@empresa.com">
                            <span class="input-icon">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/>
                                    <polyline points="22,6 12,13 2,6"/>
                                </svg>
                            </span>
                        </div>
                        @error('email')
                            <div class="error-message">
                                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <circle cx="12" cy="12" r="10"/>
                                    <line x1="12" y1="8" x2="12" y2="12"/>
                                    <line x1="12" y1="16" x2="12.01" y2="16"/>
                                </svg>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <!-- Role (full width) -->
                    <div class="form-group full">
                        <label class="form-label" for="role_id">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                                <circle cx="9" cy="7" r="4"/>
                                <path d="M23 21v-2a4 4 0 0 0-3-3.87"/>
                                <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                            </svg>
                            Tipo de Usuario
                            <span class="required">*</span>
                        </label>
                        <div class="input-wrapper">
                            <select id="role_id" name="role_id"
                                    class="form-input @error('role_id') input-error @enderror"
                                    required>
                                <option value="">Selecciona un rol...</option>
                                @foreach($roles as $role)
                                    <option value="{{ $role->id }}" {{ old('role_id') == $role->id ? 'selected' : '' }}>
                                        {{ $role->name }}
                                    </option>
                                @endforeach
                            </select>
                            <span class="input-icon">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                                    <circle cx="12" cy="7" r="4"/>
                                </svg>
                            </span>
                        </div>
                        @error('role_id')
                            <div class="error-message">
                                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <circle cx="12" cy="12" r="10"/>
                                    <line x1="12" y1="8" x2="12" y2="12"/>
                                    <line x1="12" y1="16" x2="12.01" y2="16"/>
                                </svg>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <!-- Password -->
                    <div class="form-group">
                        <label class="form-label" for="password">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/>
                                <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                            </svg>
                            Contraseña
                            <span class="required">*</span>
                        </label>
                        <div class="input-wrapper">
                            <input type="password" id="password" name="password"
                                   class="form-input @error('password') input-error @enderror"
                                   required autocomplete="new-password" placeholder="••••••••">
                            <span class="input-icon">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/>
                                    <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                                </svg>
                            </span>

                            <button type="button" class="toggle-password" data-target="password" aria-label="Mostrar contraseña">
                                <svg class="icon-eye" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                                    <circle cx="12" cy="12" r="3"/>
                                </svg>
                                <svg class="icon-eye-off" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="display:none">
                                    <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"/>
                                    <line x1="1" y1="1" x2="23" y2="23"/>
                                </svg>
                            </button>

                            <!-- Tooltip de requisitos -->
                            <div class="pwd-info" id="pwd-info">
                                <button type="button" class="pwd-info-btn" aria-label="Requisitos de contraseña" aria-describedby="pwd-tooltip">
                                    <span class="mdi mdi-alert-circle-outline"></span>
                                </button>
                                <div class="pwd-tooltip" id="pwd-tooltip" role="tooltip">
                                    <div class="pwd-tooltip-title">La contraseña debe contener:</div>
                                    <div class="requirement" id="req-length">
                                        <span class="mdi mdi-circle-outline"></span>
                                        Al menos 8 caracteres
                                    </div>
                                    <div class="requirement" id="req-upper">
                                        <span class="mdi mdi-circle-outline"></span>
                                        Una mayúscula
                                    </div>
                                    <div class="requirement" id="req-lower">
                                        <span class="mdi mdi-circle-outline"></span>
                                        Una minúscula
                                    </div>
                                    <div class="requirement" id="req-number">
                                        <span class="mdi mdi-circle-outline"></span>
                                        Un número
                                    </div>
                                    <div class="requirement" id="req-special">
                                        <span class="mdi mdi-circle-outline"></span>
                                        Un carácter especial
                                    </div>
                                </div>
                            </div>
                        </div>
                        @error('password')
                            <div class="error-message">
                                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <circle cx="12" cy="12" r="10"/>
                                    <line x1="12" y1="8" x2="12" y2="12"/>
                                    <line x1="12" y1="16" x2="12.01" y2="16"/>
                                </svg>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <!-- Confirm Password -->
                    <div class="form-group">
                        <label class="form-label" for="password_confirmation">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M9 12l2 2 4-4"/>
                                <circle cx="12" cy="12" r="10"/>
                            </svg>
                            Confirmar Contraseña
                            <span class="required">*</span>
                        </label>
                        <div class="input-wrapper">
                            <input type="password" id="password_confirmation" name="password_confirmation"
                                   class="form-input"
                                   required autocomplete="new-password" placeholder="••••••••">
                            <span class="input-icon">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M9 12l2 2 4-4"/>
                                    <circle cx="12" cy="12" r="10"/>
                                </svg>
                            </span>
                            <button type="button" class="toggle-password" data-target="password_confirmation" aria-label="Mostrar contraseña">
                                <svg class="icon-eye" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                                    <circle cx="12" cy="12" r="3"/>
                                </svg>
                                <svg class="icon-eye-off" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="display:none">
                                    <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"/>
                                    <line x1="1" y1="1" x2="23" y2="23"/>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <!-- Terms (full width, si aplica) -->
                    @if (Laravel\Jetstream\Jetstream::hasTermsAndPrivacyPolicyFeature())
                        <div class="form-group full">
                            <label class="terms-wrapper">
                                <input type="checkbox" name="terms" id="terms" required>
                                <span>
                                    Acepto los
                                    <a href="{{ route('terms.show') }}" target="_blank">Términos de Servicio</a>
                                    y la
                                    <a href="{{ route('policy.show') }}" target="_blank">Política de Privacidad</a>
                                </span>
                            </label>
                        </div>
                    @endif

                    <!-- Submit (full width) -->
                    <div class="form-group full">
                        <button type="submit" class="btn-register">
                            <span class="mdi mdi-account-plus"></span>
                            Registrarse
                        </button>
                    </div>

                </div>
            </form>

            <div class="divider">
                <span>¿Ya tienes cuenta?</span>
            </div>

            <div class="login-link">
                <a href="{{ route('login') }}">
                    Iniciar Sesión
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M5 12h14M13 6l6 6-6 6"/>
                    </svg>
                </a>
            </div>
        </div>

        <div class="back-home">
            <a href="{{ url('/') }}">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M19 12H5"/>
                    <path d="M12 19l-7-7 7-7"/>
                </svg>
                Volver al inicio
            </a>
        </div>
    </div>

    <!-- ==================== JAVASCRIPT ==================== -->
    <script>
        (function () {
            // ==================== THEME TOGGLE ====================
            var themeBtn = document.getElementById('theme-toggle');
            var root = document.documentElement;

            function setTheme(theme) {
                root.setAttribute('data-theme', theme);
                try { localStorage.setItem('ameri-theme', theme); } catch (e) {}
            }

            if (themeBtn) {
                themeBtn.addEventListener('click', function () {
                    var current = root.getAttribute('data-theme') === 'light' ? 'light' : 'dark';
                    setTheme(current === 'light' ? 'dark' : 'light');
                });
            }

            // ==================== TOGGLE PASSWORD (múltiples) ====================
            document.querySelectorAll('.toggle-password').forEach(function (btn) {
                btn.addEventListener('click', function () {
                    var targetId = this.getAttribute('data-target');
                    var input = document.getElementById(targetId);
                    if (!input) return;

                    var isPassword = input.type === 'password';
                    input.type = isPassword ? 'text' : 'password';

                    var eye = this.querySelector('.icon-eye');
                    var eyeOff = this.querySelector('.icon-eye-off');
                    if (eye && eyeOff) {
                        eye.style.display = isPassword ? 'none' : 'block';
                        eyeOff.style.display = isPassword ? 'block' : 'none';
                    }

                    this.setAttribute('aria-label', isPassword ? 'Ocultar contraseña' : 'Mostrar contraseña');
                });
            });

            // ==================== PASSWORD REQUIREMENTS ====================
            var password = document.getElementById('password');
            var pwdInfo = document.getElementById('pwd-info');

            function checkRequirements() {
                if (!password) return;
                var val = password.value || '';

                var checks = {
                    'req-length': val.length >= 8,
                    'req-upper': /[A-Z]/.test(val),
                    'req-lower': /[a-z]/.test(val),
                    'req-number': /\d/.test(val),
                    'req-special': /[\W_]/.test(val)
                };

                Object.entries(checks).forEach(function (entry) {
                    var id = entry[0], met = entry[1];
                    var el = document.getElementById(id);
                    if (!el) return;
                    var icon = el.querySelector('.mdi');
                    if (met) {
                        el.classList.add('met');
                        if (icon) icon.className = 'mdi mdi-check-circle';
                    } else {
                        el.classList.remove('met');
                        if (icon) icon.className = 'mdi mdi-circle-outline';
                    }
                });
            }

            if (password) {
                password.addEventListener('focus', function () {
                    if (pwdInfo) pwdInfo.classList.add('active');
                });

                password.addEventListener('blur', function () {
                    setTimeout(function () {
                        if (pwdInfo && !pwdInfo.matches(':hover')) {
                            pwdInfo.classList.remove('active');
                        }
                    }, 150);
                });

                if (pwdInfo) {
                    pwdInfo.addEventListener('mouseenter', function () {
                        pwdInfo.classList.add('active');
                    });
                    pwdInfo.addEventListener('mouseleave', function () {
                        pwdInfo.classList.remove('active');
                    });
                }

                password.addEventListener('input', checkRequirements);
                checkRequirements();
            }
        })();
    </script>
</body>
</html>
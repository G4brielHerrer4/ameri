<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-theme="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="color-scheme" content="dark light">
    <meta name="theme-color" content="#05080a">

    <title>{{ config('app.name', 'AMERI') }} - Iniciar Sesión</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700|space-grotesk:400,500,600,700" rel="stylesheet" />

    <script>
        /* Sincroniza el tema con el guardado en el index antes del paint */
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

            /* Cyan neón real */
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

            --success: #00fff0;
            --success-bg: rgba(0, 255, 240, 0.08);

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

            --success: #008a80;
            --success-bg: rgba(0, 160, 150, 0.08);
        }

        /* ==================== RESET ==================== */
        * { margin: 0; padding: 0; box-sizing: border-box; }

        html, body {
            height: 100%;
            overflow: hidden; /* Sin scroll */
        }

        body {
            font-family: var(--font-body);
            background-color: var(--bg);
            color: var(--ink);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1.25rem;
            position: relative;
            transition: background-color 0.5s var(--ease), color 0.5s var(--ease);
            -webkit-font-smoothing: antialiased;
        }

        /* Grid de grout sutil de fondo, igual que el index */
        body::before {
            content: '';
            position: absolute;
            inset: 0;
            background-image:
                linear-gradient(var(--line) 1px, transparent 1px),
                linear-gradient(90deg, var(--line) 1px, transparent 1px);
            background-size: 64px 64px;
            -webkit-mask-image: radial-gradient(ellipse at 50% 50%, rgba(0,0,0,0.9), transparent 75%);
            mask-image: radial-gradient(ellipse at 50% 50%, rgba(0,0,0,0.9), transparent 75%);
            pointer-events: none;
        }

        /* Halo cyan radial */
        body::after {
            content: '';
            position: absolute;
            top: -30%;
            right: -15%;
            width: 640px;
            height: 640px;
            background: radial-gradient(circle, var(--glow-soft) 0%, transparent 70%);
            border-radius: 50%;
            pointer-events: none;
            filter: blur(40px);
        }

        /* Segundo halo, abajo a la izquierda */
        .bg-orb {
            position: absolute;
            bottom: -30%;
            left: -15%;
            width: 560px;
            height: 560px;
            background: radial-gradient(circle, var(--glow-soft) 0%, transparent 70%);
            border-radius: 50%;
            pointer-events: none;
            filter: blur(40px);
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

        /* ==================== LOGIN CONTAINER ==================== */
        .login-container {
            width: 100%;
            max-width: 440px;
            position: relative;
            z-index: 1;
            animation: fadeInUp 0.7s var(--ease-out);
        }

        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(28px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* ==================== LOGIN CARD ==================== */
        .login-card {
            position: relative;
            background: var(--bg-1);
            border: 1px solid var(--line);
            border-radius: var(--r-xl);
            padding: 2.5rem;
            box-shadow: 0 40px 80px -40px var(--shadow);
            overflow: hidden;
        }

        /* Línea superior con gradiente cyan */
        .login-card::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0;
            height: 1px;
            background: linear-gradient(90deg, transparent, var(--cyan), transparent);
            opacity: 0.7;
        }

        /* Resplandor interno sutil */
        .login-card::after {
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
            z-index: 3;
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
            margin-bottom: 1.1rem;
            padding: 0.85rem 1.25rem;
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
            height: 54px;
            width: auto;
            object-fit: contain;
        }

        .logo-section h2 {
            font-family: var(--font-display);
            font-size: 1.65rem;
            font-weight: 600;
            color: var(--ink);
            margin-bottom: 0.4rem;
            letter-spacing: -0.01em;
        }

        .logo-section p {
            font-size: 0.875rem;
            color: var(--ink-muted);
        }

        /* ==================== FORM ==================== */
        .form-group { margin-bottom: 1.25rem; }

        .form-label {
            display: flex;
            align-items: center;
            gap: 0.45rem;
            font-size: 0.78rem;
            font-weight: 600;
            color: var(--ink-muted);
            margin-bottom: 0.55rem;
            font-family: var(--font-display);
            letter-spacing: 0.04em;
            text-transform: uppercase;
        }

        .form-label svg {
            width: 14px;
            height: 14px;
            color: var(--cyan);
        }

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

        .form-input:focus + .input-icon { color: var(--cyan); }

        /* Password toggle */
        .toggle-password {
            position: absolute;
            right: 1rem;
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
        }
        .toggle-password:hover { color: var(--cyan); }
        .toggle-password svg { width: 18px; height: 18px; }

        /* Error input */
        .input-error {
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

        /* ==================== ALERTAS ==================== */
        .validation-errors {
            background: var(--error-bg);
            border: 1px solid var(--error-line);
            border-radius: var(--r-md);
            padding: 0.9rem 1.1rem;
            margin-bottom: 1.25rem;
            position: relative;
            z-index: 2;
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

        .success-message {
            background: var(--success-bg);
            border: 1px solid var(--line-strong);
            border-radius: var(--r-md);
            padding: 0.9rem 1.1rem;
            margin-bottom: 1.25rem;
            display: flex;
            align-items: center;
            gap: 0.6rem;
            color: var(--success);
            font-size: 0.85rem;
            position: relative;
            z-index: 2;
        }

        /* ==================== CHECKBOX ==================== */
        .checkbox-wrapper {
            display: flex;
            align-items: center;
            gap: 0.55rem;
            cursor: pointer;
            user-select: none;
        }

        .checkbox-wrapper input {
            width: 18px;
            height: 18px;
            accent-color: var(--cyan);
            cursor: pointer;
        }

        .checkbox-wrapper span {
            font-size: 0.85rem;
            color: var(--ink-muted);
        }

        /* ==================== BOTÓN PRINCIPAL ==================== */
        .btn-login {
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

        .btn-login::before {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(120deg, transparent 30%, rgba(255,255,255,0.5) 50%, transparent 70%);
            transform: translateX(-100%);
            transition: transform 0.7s var(--ease);
        }

        .btn-login:hover::before { transform: translateX(100%); }

        .btn-login:hover {
            transform: translateY(-3px);
            box-shadow: 0 16px 44px -8px var(--glow), 0 0 0 1px var(--cyan);
        }

        .btn-login:active { transform: translateY(-1px); }

        .btn-login svg { width: 18px; height: 18px; }

        /* ==================== LINKS ==================== */
        .forgot-link {
            font-size: 0.83rem;
            color: var(--cyan);
            text-decoration: none;
            font-weight: 500;
            transition: all 0.25s ease;
        }

        .forgot-link:hover {
            text-shadow: 0 0 12px var(--glow);
            text-decoration: underline;
        }

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

        .register-link { text-align: center; }

        .register-link a {
            color: var(--cyan);
            text-decoration: none;
            font-weight: 600;
            font-size: 0.9rem;
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            transition: all 0.3s var(--ease);
        }

        .register-link a:hover {
            gap: 0.65rem;
            text-shadow: 0 0 14px var(--glow);
        }

        .register-link a svg { width: 15px; height: 15px; transition: transform 0.3s var(--ease); }
        .register-link a:hover svg { transform: translateX(3px); }

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
            body { padding: 1rem; }

            .login-card { padding: 1.75rem 1.5rem; border-radius: var(--r-lg); }

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
            .btn-login { padding: 0.9rem; font-size: 0.93rem; }
        }

        @media (max-height: 720px) {
            .logo-section { margin-bottom: 1.4rem; }
            .logo { margin-bottom: 0.8rem; padding: 0.65rem 1rem; }
            .logo img { height: 44px; }
            .logo-section h2 { font-size: 1.4rem; }
            .form-group { margin-bottom: 1rem; }
            .form-input { padding: 0.8rem 0.9rem 0.8rem 2.7rem; }
            .btn-login { padding: 0.85rem; }
            .divider { margin: 1.1rem 0 0.9rem; }
            .login-card { padding: 2rem 1.75rem; }
        }
    </style>
</head>
<body>
    <div class="bg-orb"></div>

    <div class="login-container">
        <div class="login-card">
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
                <h2>Bienvenido</h2>
                <p>Ingresa tus credenciales para continuar</p>
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

            <!-- Session Status -->
            @if (session('status'))
                <div class="success-message">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/>
                        <polyline points="22 4 12 14.01 9 11.01"/>
                    </svg>
                    {{ session('status') }}
                </div>
            @endif

            <!-- Login Form -->
            <form method="POST" action="{{ route('login') }}">
                @csrf

                <div class="form-group">
                    <label class="form-label" for="email">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/>
                            <polyline points="22,6 12,13 2,6"/>
                        </svg>
                        Correo Electrónico
                    </label>
                    <div class="input-wrapper">
                        <input type="email" id="email" name="email"
                               class="form-input @error('email') input-error @enderror"
                               value="{{ old('email') }}" required autofocus autocomplete="username"
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

                <div class="form-group">
                    <label class="form-label" for="password">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/>
                            <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                        </svg>
                        Contraseña
                    </label>
                    <div class="input-wrapper">
                        <input type="password" id="password" name="password"
                               class="form-input @error('password') input-error @enderror"
                               required autocomplete="current-password" placeholder="••••••••">
                        <span class="input-icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/>
                                <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                            </svg>
                        </span>
                        <button type="button" class="toggle-password" id="toggle-password" aria-label="Mostrar contraseña">
                            <svg id="icon-eye" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                                <circle cx="12" cy="12" r="3"/>
                            </svg>
                            <svg id="icon-eye-off" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="display:none">
                                <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"/>
                                <line x1="1" y1="1" x2="23" y2="23"/>
                            </svg>
                        </button>
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

                <div class="form-group">
                    <div style="display: flex; justify-content: space-between; align-items: center; gap: 1rem; flex-wrap: wrap;">
                        <label class="checkbox-wrapper">
                            <input type="checkbox" name="remember" id="remember_me">
                            <span>Recordarme</span>
                        </label>
                        @if (Route::has('password.request'))
                            <a class="forgot-link" href="{{ route('password.request') }}">
                                ¿Olvidaste tu contraseña?
                            </a>
                        @endif
                    </div>
                </div>

                <button type="submit" class="btn-login">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M15 3h6v6"/>
                        <path d="M21 3l-9 9"/>
                        <path d="M3 13V5a2 2 0 0 1 2-2h6"/>
                        <path d="M21 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V9"/>
                    </svg>
                    Iniciar Sesión
                </button>
            </form>

            <div class="divider">
                <span>¿Nuevo en AMERI?</span>
            </div>

            <div class="register-link">
                <a href="{{ route('register') }}">
                    Crear una cuenta nueva
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

            // ==================== TOGGLE PASSWORD ====================
            var toggleBtn = document.getElementById('toggle-password');
            var pwdInput = document.getElementById('password');
            var iconEye = document.getElementById('icon-eye');
            var iconEyeOff = document.getElementById('icon-eye-off');

            if (toggleBtn && pwdInput) {
                toggleBtn.addEventListener('click', function () {
                    var isPassword = pwdInput.type === 'password';
                    pwdInput.type = isPassword ? 'text' : 'password';
                    iconEye.style.display = isPassword ? 'none' : 'block';
                    iconEyeOff.style.display = isPassword ? 'block' : 'none';
                    toggleBtn.setAttribute('aria-label', isPassword ? 'Ocultar contraseña' : 'Mostrar contraseña');
                });
            }
        })();
    </script>
</body>
</html>
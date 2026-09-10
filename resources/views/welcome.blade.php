<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-theme="dark">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="color-scheme" content="dark light">
        <meta name="theme-color" content="#05080a">

        <title>{{ config('app.name', 'AMERI') }} - Cerámicos y Cemento Cola</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700|space-grotesk:400,500,600,700" rel="stylesheet" />
        <!-- Material Icons -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@mdi/font@7.2.96/css/materialdesignicons.min.css">

        <!-- Styles / Scripts Laravel -->
        @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
            @vite(['resources/css/app.css', 'resources/js/app.js'])
        @endif

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
                /* Dark theme — graphite profundo + cyan neón real */
                --bg: #05080a;
                --bg-1: #0a1014;
                --bg-2: #0f171c;
                --bg-3: #16222a;
                --ink: #eefcff;
                --ink-muted: #8fa8ae;
                --ink-faint: #566d74;

                /* Cyan neón REAL (no azul) */
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

                --font-display: 'Space Grotesk', 'Instrument Sans', sans-serif;
                --font-body: 'Instrument Sans', sans-serif;

                /* Radios profesionales deep */
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
            }

            /* ==================== RESET ==================== */
            * { margin: 0; padding: 0; box-sizing: border-box; }
            html { scroll-behavior: smooth; }

            body {
                font-family: var(--font-body);
                background-color: var(--bg);
                color: var(--ink);
                overflow-x: hidden;
                transition: background-color 0.5s var(--ease), color 0.5s var(--ease);
                line-height: 1.55;
                -webkit-font-smoothing: antialiased;
            }

            a { color: inherit; }
            ::selection { background: var(--cyan); color: var(--cyan-ink); }

            @media (prefers-reduced-motion: reduce) {
                *, *::before, *::after {
                    animation-duration: 0.001ms !important;
                    animation-iteration-count: 1 !important;
                    transition-duration: 0.001ms !important;
                    scroll-behavior: auto !important;
                }
            }

            :focus-visible {
                outline: 2px solid var(--cyan);
                outline-offset: 3px;
                border-radius: 6px;
            }

            /* ==================== SCROLLBAR ==================== */
            ::-webkit-scrollbar { width: 10px; }
            ::-webkit-scrollbar-track { background: var(--bg-1); }
            ::-webkit-scrollbar-thumb {
                background: linear-gradient(var(--cyan-dim), var(--cyan-2));
                border-radius: 10px;
            }
            ::-webkit-scrollbar-thumb:hover { background: var(--cyan); }

            /* ==================== TILE GROUT TEXTURE ==================== */
            .grout-field {
                position: absolute;
                inset: 0;
                background-image:
                    linear-gradient(var(--line) 1px, transparent 1px),
                    linear-gradient(90deg, var(--line) 1px, transparent 1px);
                background-size: 64px 64px;
                mask-image: radial-gradient(ellipse at 50% 0%, rgba(0,0,0,0.85), transparent 75%);
                pointer-events: none;
            }

            /* ==================== CONTAINER ==================== */
            .container { max-width: 1280px; margin: 0 auto; padding: 0 2rem; }
            @media (max-width: 1024px) { .container { padding: 0 1.5rem; } }
            @media (max-width: 640px) { .container { padding: 0 1.25rem; } }

            /* ==================== REVEAL ANIMATION ==================== */
            .reveal {
                opacity: 0;
                transform: translateY(28px);
                transition: opacity 0.8s var(--ease-out), transform 0.8s var(--ease-out);
                will-change: opacity, transform;
            }
            .reveal.visible { opacity: 1; transform: translateY(0); }
            .reveal[data-delay="1"] { transition-delay: 0.08s; }
            .reveal[data-delay="2"] { transition-delay: 0.16s; }
            .reveal[data-delay="3"] { transition-delay: 0.24s; }
            .reveal[data-delay="4"] { transition-delay: 0.32s; }

            /* ==================== NAVBAR ==================== */
            .navbar {
                position: fixed;
                top: 0; left: 0; width: 100%;
                background: color-mix(in srgb, var(--bg) 72%, transparent);
                backdrop-filter: blur(18px) saturate(140%);
                -webkit-backdrop-filter: blur(18px) saturate(140%);
                border-bottom: 1px solid transparent;
                z-index: 1000;
                padding: 0.65rem 0;
                transition: padding 0.35s var(--ease), border-color 0.35s var(--ease),
                            background 0.35s var(--ease), box-shadow 0.35s var(--ease);
            }

            .navbar.scrolled {
                border-bottom-color: var(--line);
                background: color-mix(in srgb, var(--bg) 90%, transparent);
                box-shadow: 0 8px 32px -12px var(--shadow);
            }

            .navbar .container {
                display: flex;
                justify-content: space-between;
                align-items: center;
                gap: 1.5rem;
            }

            .logo { flex-shrink: 0; display: flex; align-items: center; }
            .logo img {
                height: 56px;
                width: auto;
                object-fit: contain;
                transition: height 0.35s var(--ease), filter 0.3s ease;
            }
            .logo img:hover { filter: drop-shadow(0 0 12px var(--glow)); }
            @media (min-width: 768px) { .logo img { height: 86px; } }

            .nav-links { display: none; gap: 2.1rem; align-items: center; }
            @media (min-width: 1024px) { .nav-links { display: flex; } }

            .nav-links a {
                text-decoration: none;
                font-weight: 500;
                font-size: 0.9rem;
                color: var(--ink-muted);
                position: relative;
                padding: 0.35rem 0;
                transition: color 0.25s ease;
            }
            .nav-links a::after {
                content: '';
                position: absolute;
                bottom: -3px; left: 0;
                width: 0; height: 2px;
                border-radius: var(--r-pill);
                background: var(--cyan);
                box-shadow: 0 0 10px var(--glow);
                transition: width 0.32s var(--ease);
            }
            .nav-links a:hover::after, .nav-links a.active::after { width: 100%; }
            .nav-links a:hover, .nav-links a.active { color: var(--ink); }

            .nav-right { display: flex; align-items: center; gap: 0.7rem; }

            .auth-buttons { display: none; gap: 0.7rem; align-items: center; }
            @media (min-width: 1024px) { .auth-buttons { display: flex; } }

            /* ---------- BOTONES PROFESIONALES DEEP ---------- */
            .btn-login {
                padding: 0.6rem 1.25rem;
                text-decoration: none;
                font-weight: 600;
                font-size: 0.88rem;
                color: var(--ink);
                border-radius: var(--r-pill);
                border: 1px solid var(--line);
                background: var(--surface-alpha);
                transition: all 0.3s var(--ease);
            }
            .btn-login:hover {
                border-color: var(--line-strong);
                color: var(--cyan);
                transform: translateY(-2px);
                box-shadow: 0 8px 24px -8px var(--glow);
            }

            .btn-register, .btn-dashboard {
                position: relative;
                padding: 0.65rem 1.5rem;
                background: linear-gradient(135deg, var(--cyan), var(--cyan-2));
                color: var(--cyan-ink);
                text-decoration: none;
                font-weight: 700;
                font-size: 0.88rem;
                border-radius: var(--r-pill);
                overflow: hidden;
                transition: all 0.3s var(--ease);
                box-shadow: 0 4px 16px -6px var(--glow);
            }
            .btn-register::before, .btn-dashboard::before {
                content: '';
                position: absolute;
                inset: 0;
                background: linear-gradient(120deg, transparent 30%, rgba(255,255,255,0.45) 50%, transparent 70%);
                transform: translateX(-100%);
                transition: transform 0.6s var(--ease);
            }
            .btn-register:hover::before, .btn-dashboard:hover::before { transform: translateX(100%); }
            .btn-register:hover, .btn-dashboard:hover {
                transform: translateY(-2px);
                box-shadow: 0 10px 32px -6px var(--glow), 0 0 0 1px var(--cyan);
            }

            /* Theme toggle */
            .theme-toggle {
                width: 46px; height: 46px;
                border-radius: var(--r-pill);
                border: 1px solid var(--line);
                background: var(--surface-alpha);
                display: flex; align-items: center; justify-content: center;
                cursor: pointer;
                color: var(--ink);
                flex-shrink: 0;
                transition: all 0.3s var(--ease);
            }
            .theme-toggle:hover {
                border-color: var(--line-strong);
                transform: rotate(15deg) scale(1.05);
                box-shadow: 0 0 20px -4px var(--glow);
            }
            .theme-toggle svg { width: 19px; height: 19px; }
            .theme-toggle .icon-sun { display: none; }
            html[data-theme="light"] .theme-toggle .icon-moon { display: none; }
            html[data-theme="light"] .theme-toggle .icon-sun { display: block; }

            /* Carrito */
            .carrito-icon {
                display: inline-flex; align-items: center; justify-content: center;
                width: 46px; height: 46px;
                position: relative;
                color: var(--ink);
                text-decoration: none;
                border: 1px solid var(--line);
                border-radius: var(--r-pill);
                background: var(--surface-alpha);
                transition: all 0.3s var(--ease);
                flex-shrink: 0;
            }
            .carrito-icon:hover {
                border-color: var(--line-strong);
                color: var(--cyan);
                transform: translateY(-2px);
                box-shadow: 0 0 20px -4px var(--glow);
            }
            .carrito-icon .mdi { font-size: 21px; }

            #carrito-contador {
                position: absolute; top: -6px; right: -6px;
                background: var(--cyan);
                color: var(--cyan-ink);
                font-size: 10px; font-weight: 800;
                min-width: 19px; height: 19px;
                border-radius: 50%;
                display: none; align-items: center; justify-content: center;
                box-shadow: 0 0 10px var(--glow);
            }

            /* Mobile Menu Button */
            .mobile-menu-btn {
                display: flex;
                background: var(--surface-alpha);
                border: 1px solid var(--line);
                border-radius: var(--r-pill);
                color: var(--ink);
                cursor: pointer;
                padding: 0.55rem;
                width: 46px; height: 46px;
                align-items: center; justify-content: center;
                transition: all 0.3s var(--ease);
            }
            .mobile-menu-btn:hover { border-color: var(--line-strong); color: var(--cyan); }
            @media (min-width: 1024px) { .mobile-menu-btn { display: none; } }

            /* Mobile Menu */
            .mobile-menu {
                position: fixed; top: 0; right: -100%;
                width: 84%; max-width: 340px; height: 100vh;
                background: var(--bg-1);
                border-left: 1px solid var(--line);
                z-index: 1001;
                padding: 1.75rem;
                transition: right 0.4s var(--ease);
                box-shadow: -20px 0 60px var(--shadow);
            }
            .mobile-menu.active { right: 0; }

            .mobile-menu-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 2.5rem; }
            .mobile-menu-header .logo img { height: 48px; }
            .mobile-menu-close {
                background: var(--surface-alpha);
                border: 1px solid var(--line);
                border-radius: var(--r-pill);
                width: 40px; height: 40px;
                color: var(--ink); cursor: pointer; font-size: 1.1rem;
                transition: all 0.3s var(--ease);
            }
            .mobile-menu-close:hover { border-color: var(--line-strong); color: var(--cyan); }

            .mobile-menu-links { display: flex; flex-direction: column; }
            .mobile-menu-links a {
                color: var(--ink);
                text-decoration: none;
                font-family: var(--font-display);
                font-weight: 500;
                font-size: 1.15rem;
                padding: 0.95rem 0;
                border-bottom: 1px solid var(--line);
                transition: color 0.25s ease, padding-left 0.25s ease;
            }
            .mobile-menu-links a:hover { color: var(--cyan); padding-left: 8px; }

            .mobile-auth {
                margin-top: 1.75rem; padding-top: 1.25rem;
                border-top: 1px solid var(--line);
                display: flex; flex-direction: column; gap: 0.75rem;
            }
            .mobile-auth a {
                text-decoration: none; padding: 0.9rem; text-align: center;
                border-radius: var(--r-pill); font-weight: 700;
                transition: all 0.3s var(--ease);
            }
            .mobile-auth .btn-login-mobile { border: 1px solid var(--line); color: var(--ink); }
            .mobile-auth .btn-login-mobile:hover { border-color: var(--cyan); color: var(--cyan); }
            .mobile-auth .btn-register-mobile, .mobile-auth .btn-dashboard {
                background: linear-gradient(135deg, var(--cyan), var(--cyan-2));
                color: var(--cyan-ink);
            }

            .mobile-overlay {
                position: fixed; inset: 0;
                background: rgba(0,0,0,0.6);
                z-index: 1000; display: none;
                backdrop-filter: blur(3px);
                opacity: 0;
                transition: opacity 0.3s ease;
            }
            .mobile-overlay.active { display: block; opacity: 1; }

            /* ==================== CARRUSEL ==================== */
            .carousel-section {
                position: relative;
                width: 100%; height: 88vh; min-height: 520px;
                overflow: hidden;
                margin-top: 92px;
                border-bottom: 1px solid var(--line);
            }

            .carousel-container { position: relative; width: 100%; height: 100%; }
            .carousel-slides { position: relative; width: 100%; height: 100%; }

            .carousel-slide {
                position: absolute; inset: 0;
                opacity: 0; visibility: hidden;
                transition: opacity 1.1s var(--ease), visibility 1.1s var(--ease);
            }
            .carousel-slide.active { opacity: 1; visibility: visible; }
            .carousel-slide img {
                width: 100%; height: 100%; object-fit: cover;
                filter: saturate(0.92) contrast(1.05);
                transform: scale(1.04);
                transition: transform 6s var(--ease-out);
            }
            .carousel-slide.active img { transform: scale(1); }

            .carousel-overlay {
                position: absolute; inset: 0;
                background:
                    linear-gradient(180deg, rgba(5,8,10,0.5) 0%, rgba(5,8,10,0.75) 60%, var(--bg) 100%),
                    linear-gradient(100deg, rgba(5,8,10,0.88) 0%, rgba(5,8,10,0.3) 55%);
            }
            html[data-theme="light"] .carousel-overlay {
                background:
                    linear-gradient(180deg, rgba(5,22,26,0.35) 0%, rgba(5,22,26,0.55) 60%, var(--bg) 100%),
                    linear-gradient(100deg, rgba(5,22,26,0.6) 0%, rgba(5,22,26,0.12) 55%);
            }

            .carousel-content {
                position: absolute; left: 0; bottom: 14%;
                z-index: 2; width: 100%; padding: 0 2rem;
            }
            .carousel-content .inner { max-width: 1280px; margin: 0 auto; }

            .carousel-tag {
                display: inline-flex; align-items: center; gap: 0.55rem;
                font-family: var(--font-display);
                font-size: 0.78rem; letter-spacing: 0.06em;
                text-transform: uppercase;
                color: var(--cyan);
                margin-bottom: 1.1rem;
                padding: 0.4rem 0.9rem;
                border: 1px solid var(--line-strong);
                border-radius: var(--r-pill);
                background: rgba(0, 255, 240, 0.06);
                backdrop-filter: blur(8px);
            }
            .carousel-tag::before {
                content: '';
                width: 6px; height: 6px; border-radius: 50%;
                background: var(--cyan);
                box-shadow: 0 0 10px var(--glow);
                animation: pulseDot 2s infinite;
            }
            @keyframes pulseDot {
                0%, 100% { opacity: 1; transform: scale(1); }
                50% { opacity: 0.5; transform: scale(0.8); }
            }

            .carousel-content h2 {
                font-family: var(--font-display);
                font-size: clamp(2rem, 5vw, 3.4rem);
                font-weight: 600;
                line-height: 1.08;
                margin-bottom: 1rem;
                color: #fff;
                max-width: 15ch;
                text-shadow: 0 4px 30px rgba(0,0,0,0.4);
            }
            .carousel-content h2 em {
                color: var(--cyan);
                font-style: normal;
                text-shadow: 0 0 30px var(--glow);
            }

            .carousel-content p {
                font-size: 1.05rem;
                margin-bottom: 1.9rem;
                color: rgba(255,255,255,0.8);
                max-width: 44ch;
            }

            /* ---------- BOTÓN PRINCIPAL DEL CARRUSEL ---------- */
            .carousel-btn {
                display: inline-flex; align-items: center; gap: 0.65rem;
                padding: 1rem 2.1rem;
                background: linear-gradient(135deg, var(--cyan), var(--cyan-2));
                color: var(--cyan-ink);
                text-decoration: none;
                font-weight: 700;
                border-radius: var(--r-pill);
                position: relative;
                overflow: hidden;
                box-shadow: 0 6px 24px -6px var(--glow);
                transition: all 0.35s var(--ease);
            }
            .carousel-btn::before {
                content: '';
                position: absolute; inset: 0;
                background: linear-gradient(120deg, transparent 30%, rgba(255,255,255,0.5) 50%, transparent 70%);
                transform: translateX(-100%);
                transition: transform 0.7s var(--ease);
            }
            .carousel-btn:hover::before { transform: translateX(100%); }
            .carousel-btn:hover {
                transform: translateY(-3px);
                box-shadow: 0 16px 40px -8px var(--glow), 0 0 0 1px var(--cyan);
            }

            .carousel-prev, .carousel-next {
                position: absolute; top: 50%; transform: translateY(-50%);
                background: rgba(5,8,10,0.35);
                backdrop-filter: blur(10px);
                border: 1px solid rgba(255,255,255,0.15);
                width: 52px; height: 52px;
                border-radius: var(--r-pill);
                display: flex; align-items: center; justify-content: center;
                cursor: pointer; z-index: 10; color: #fff;
                transition: all 0.35s var(--ease);
            }
            .carousel-prev { left: 24px; }
            .carousel-next { right: 24px; }
            .carousel-prev:hover, .carousel-next:hover {
                border-color: var(--cyan);
                background: rgba(0, 255, 240, 0.12);
                box-shadow: 0 0 26px -4px var(--glow);
                transform: translateY(-50%) scale(1.08);
            }

            .carousel-dots {
                position: absolute; bottom: 34px; right: 2rem;
                display: flex; gap: 10px; z-index: 10;
            }
            .carousel-dot {
                width: 9px; height: 9px; border-radius: var(--r-pill);
                background: rgba(255,255,255,0.35);
                border: none; cursor: pointer;
                transition: all 0.4s var(--ease);
            }
            .carousel-dot:hover { background: rgba(255,255,255,0.6); }
            .carousel-dot.active {
                background: var(--cyan);
                width: 30px;
                box-shadow: 0 0 12px var(--glow);
            }

            @media (max-width: 768px) {
                .carousel-section { height: 72vh; min-height: 460px; margin-top: 82px; }
                .carousel-content { bottom: 12%; }
                .carousel-prev, .carousel-next { width: 42px; height: 42px; }
                .carousel-prev { left: 12px; }
                .carousel-next { right: 12px; }
                .carousel-dots { right: 1.25rem; bottom: 20px; }
            }

            /* ==================== HERO ==================== */
            .hero {
                position: relative;
                background: var(--bg);
                padding: 6.5rem 0 5.5rem;
                overflow: hidden;
            }

            .hero .container {
                position: relative; z-index: 1;
                display: grid; grid-template-columns: 1fr; gap: 3rem; align-items: center;
            }
            @media (min-width: 1024px) {
                .hero .container { grid-template-columns: 1.05fr 0.95fr; gap: 4rem; }
            }

            .hero-eyebrow-line { display: flex; align-items: center; gap: 0.75rem; margin-bottom: 1.5rem; }
            .hero-eyebrow-line .dot {
                width: 8px; height: 8px; border-radius: 50%;
                background: var(--cyan);
                box-shadow: 0 0 14px var(--glow);
                animation: pulseDot 2s infinite;
            }
            .hero-eyebrow-line span {
                font-family: var(--font-display); font-size: 0.85rem;
                color: var(--ink-muted); letter-spacing: 0.04em;
            }

            .hero-content h1 {
                font-family: var(--font-display);
                font-size: clamp(2.1rem, 4.6vw, 3.6rem);
                font-weight: 600;
                line-height: 1.1;
                margin-bottom: 1.4rem;
                max-width: 15ch;
            }
            .hero-content h1 .accent {
                color: var(--cyan);
                text-shadow: 0 0 40px var(--glow-soft);
            }

            .hero-content p {
                font-size: 1.08rem;
                color: var(--ink-muted);
                line-height: 1.7;
                margin-bottom: 2.2rem;
                max-width: 46ch;
            }

            .hero-buttons { display: flex; gap: 1rem; flex-wrap: wrap; margin-bottom: 3rem; }

            /* ---------- BOTONES PRINCIPALES ---------- */
            .btn-primary {
                position: relative;
                display: inline-flex; align-items: center; gap: 0.6rem;
                padding: 1rem 2.1rem;
                background: linear-gradient(135deg, var(--cyan), var(--cyan-2));
                color: var(--cyan-ink);
                text-decoration: none;
                font-weight: 700;
                border: none; cursor: pointer;
                border-radius: var(--r-pill);
                overflow: hidden;
                box-shadow: 0 6px 24px -6px var(--glow);
                transition: all 0.35s var(--ease);
            }
            .btn-primary::before {
                content: '';
                position: absolute; inset: 0;
                background: linear-gradient(120deg, transparent 30%, rgba(255,255,255,0.5) 50%, transparent 70%);
                transform: translateX(-100%);
                transition: transform 0.7s var(--ease);
            }
            .btn-primary:hover::before { transform: translateX(100%); }
            .btn-primary:hover {
                transform: translateY(-3px);
                box-shadow: 0 16px 40px -8px var(--glow), 0 0 0 1px var(--cyan);
            }

            .btn-outline {
                display: inline-flex; align-items: center; gap: 0.5rem;
                padding: 1rem 2.1rem;
                background: transparent;
                color: var(--ink);
                text-decoration: none;
                font-weight: 600;
                border: 1px solid var(--line-strong);
                border-radius: var(--r-pill);
                transition: all 0.35s var(--ease);
            }
            .btn-outline:hover {
                border-color: var(--cyan);
                color: var(--cyan);
                background: rgba(0, 255, 240, 0.05);
                transform: translateY(-3px);
                box-shadow: 0 10px 30px -10px var(--glow);
            }

            .hero-stats {
                display: grid; grid-template-columns: repeat(3, 1fr);
                border-top: 1px solid var(--line);
                padding-top: 1.75rem;
                max-width: 500px;
            }
            .hero-stat { padding-right: 1rem; border-right: 1px solid var(--line); }
            .hero-stat:last-child { border-right: none; }
            .hero-stat h3 {
                font-family: var(--font-display); font-size: 1.9rem; font-weight: 600;
                color: var(--cyan);
                text-shadow: 0 0 20px var(--glow-soft);
            }
            .hero-stat p { font-size: 0.8rem; color: var(--ink-faint); margin-top: 0.2rem; }

            /* ---------- SPEC PANEL ---------- */
            .spec-panel {
                position: relative;
                background: var(--bg-1);
                border: 1px solid var(--line);
                border-radius: var(--r-lg);
                padding: 2rem;
                box-shadow: 0 30px 60px -30px var(--shadow);
                overflow: hidden;
            }
            .spec-panel::before {
                content: '';
                position: absolute; top: 0; left: 0; right: 0; height: 1px;
                background: linear-gradient(90deg, transparent, var(--cyan), transparent);
                opacity: 0.6;
            }

            .spec-panel-header {
                display: flex; justify-content: space-between; align-items: baseline;
                margin-bottom: 1.5rem; padding-bottom: 1.25rem;
                border-bottom: 1px solid var(--line);
            }
            .spec-panel-header h4 { font-family: var(--font-display); font-size: 1.05rem; font-weight: 600; }
            .spec-panel-header span { font-size: 0.75rem; color: var(--ink-faint); }

            .spec-visual {
                position: relative;
                aspect-ratio: 4 / 3;
                margin-bottom: 1.5rem;
                display: grid;
                grid-template-columns: repeat(4, 1fr);
                grid-template-rows: repeat(3, 1fr);
                gap: 4px;
                background: var(--line);
                border: 1px solid var(--line);
                border-radius: var(--r-md);
                overflow: hidden;
                padding: 4px;
            }
            .spec-visual .tile {
                background: var(--bg-2);
                border-radius: 6px;
                opacity: 0;
                transform: scale(0.86);
                animation: tileIn 0.55s var(--ease) forwards;
            }
            .spec-visual .tile.hi {
                background: linear-gradient(135deg, var(--cyan), var(--cyan-2));
                box-shadow: 0 0 16px var(--glow-soft);
            }
            @keyframes tileIn { to { opacity: 1; transform: scale(1); } }

            .spec-rows { display: flex; flex-direction: column; }
            .spec-row {
                display: flex; justify-content: space-between; align-items: center;
                padding: 0.75rem 0;
                border-bottom: 1px solid var(--line);
                font-size: 0.88rem;
            }
            .spec-row:last-child { border-bottom: none; }
            .spec-row .label { color: var(--ink-faint); }
            .spec-row .value { font-family: var(--font-display); font-weight: 500; color: var(--ink); }

            /* ==================== SECTION HEADER ==================== */
            .section-header {
                display: flex; flex-direction: column; gap: 0.9rem;
                margin-bottom: 3.5rem;
                max-width: 640px;
            }
            .section-header .kicker { display: flex; align-items: center; gap: 0.7rem; }
            .section-header .kicker .dot {
                width: 7px; height: 7px; border-radius: 50%;
                background: var(--cyan); box-shadow: 0 0 12px var(--glow);
            }
            .section-header .kicker span {
                font-family: var(--font-display); font-size: 0.8rem;
                color: var(--ink-muted); letter-spacing: 0.06em; text-transform: uppercase;
            }
            .section-header h2 {
                font-family: var(--font-display);
                font-size: clamp(1.7rem, 3.4vw, 2.5rem);
                font-weight: 600;
                line-height: 1.15;
            }
            .section-header h2 .accent { color: var(--cyan); }
            .section-header p { color: var(--ink-muted); font-size: 1rem; line-height: 1.6; }

            /* ==================== FEATURES ==================== */
            .features {
                position: relative; padding: 6.5rem 0;
                background: var(--bg-1);
                border-top: 1px solid var(--line);
                border-bottom: 1px solid var(--line);
            }

            .features-grid {
                display: grid; grid-template-columns: 1fr; gap: 1.25rem;
            }
            @media (min-width: 768px) { .features-grid { grid-template-columns: repeat(2, 1fr); } }
            @media (min-width: 1024px) { .features-grid { grid-template-columns: repeat(4, 1fr); } }

            .feature-card {
                background: var(--bg);
                border: 1px solid var(--line);
                border-radius: var(--r-lg);
                padding: 2.25rem 1.75rem;
                transition: all 0.45s var(--ease);
                position: relative;
                overflow: hidden;
            }
            .feature-card::before {
                content: '';
                position: absolute; inset: 0;
                background: radial-gradient(circle at 50% 0%, var(--glow-soft), transparent 70%);
                opacity: 0;
                transition: opacity 0.45s var(--ease);
                pointer-events: none;
            }
            .feature-card:hover {
                border-color: var(--line-strong);
                transform: translateY(-8px);
                box-shadow: 0 30px 50px -25px var(--shadow), 0 0 0 1px var(--line-strong);
            }
            .feature-card:hover::before { opacity: 1; }

            .feature-icon {
                width: 52px; height: 52px;
                border: 1px solid var(--line-strong);
                border-radius: var(--r-md);
                display: flex; align-items: center; justify-content: center;
                margin-bottom: 1.5rem;
                color: var(--cyan);
                background: rgba(0, 255, 240, 0.05);
                transition: all 0.45s var(--ease);
            }
            .feature-card:hover .feature-icon {
                background: var(--cyan);
                color: var(--cyan-ink);
                box-shadow: 0 0 26px var(--glow);
                transform: rotate(-6deg) scale(1.08);
            }
            .feature-icon svg { width: 24px; height: 24px; }

            .feature-card h3 {
                font-family: var(--font-display); font-size: 1.05rem;
                font-weight: 600; margin-bottom: 0.6rem;
            }
            .feature-card p { font-size: 0.88rem; color: var(--ink-muted); line-height: 1.6; }

            /* ==================== ABOUT ==================== */
            .about { padding: 7rem 0; background: var(--bg); position: relative; }

            .about-grid { display: grid; grid-template-columns: 1fr; gap: 3.5rem; align-items: center; }
            @media (min-width: 1024px) { .about-grid { grid-template-columns: 0.85fr 1.15fr; } }

            .about-visual {
                position: relative;
                aspect-ratio: 1 / 1;
                border: 1px solid var(--line);
                border-radius: var(--r-xl);
                background: var(--bg-1);
                display: grid;
                grid-template-columns: repeat(3, 1fr);
                grid-template-rows: repeat(3, 1fr);
                gap: 6px;
                padding: 10px;
                box-shadow: 0 30px 60px -30px var(--shadow);
                overflow: hidden;
            }
            .about-visual .cell {
                background: var(--bg-2);
                border-radius: var(--r-md);
                display: flex; align-items: center; justify-content: center;
                transition: all 0.45s var(--ease);
            }
            .about-visual .cell:hover { background: var(--bg-3); transform: scale(1.04); }
            .about-visual .cell.mark {
                background: linear-gradient(135deg, var(--cyan), var(--cyan-2));
                color: var(--cyan-ink);
                font-family: var(--font-display);
                font-weight: 700;
                font-size: 1.5rem;
                box-shadow: 0 0 24px var(--glow-soft);
            }
            .about-visual .cell.mark:hover { box-shadow: 0 0 36px var(--glow); }
            .about-visual .cell svg { width: 26px; height: 26px; color: var(--ink-faint); }

            .about-content p {
                font-size: 1rem; color: var(--ink-muted);
                line-height: 1.75; margin-bottom: 1.1rem; max-width: 56ch;
            }
            .about-content p strong { color: var(--ink); font-weight: 600; }

            .stats-grid {
                display: grid; grid-template-columns: repeat(3, 1fr);
                gap: 1.5rem; margin-top: 2.25rem;
                padding-top: 1.75rem; border-top: 1px solid var(--line);
            }
            .stat-item h4 {
                font-family: var(--font-display); font-size: 1.9rem;
                font-weight: 600; color: var(--cyan);
                text-shadow: 0 0 20px var(--glow-soft);
            }
            .stat-item p { font-size: 0.82rem; color: var(--ink-faint); margin-top: 0.2rem; }

            /* ==================== PRODUCTS ==================== */
            .products {
                padding: 7rem 0;
                background: var(--bg-1);
                border-top: 1px solid var(--line);
            }

            .products-grid { display: grid; grid-template-columns: 1fr; gap: 1.75rem; }
            @media (min-width: 768px) { .products-grid { grid-template-columns: repeat(2, 1fr); } }
            @media (min-width: 1024px) { .products-grid { grid-template-columns: repeat(3, 1fr); } }

            .product-card {
                background: var(--bg);
                border: 1px solid var(--line);
                border-radius: var(--r-lg);
                overflow: hidden;
                transition: all 0.45s var(--ease);
            }
            .product-card:hover {
                border-color: var(--line-strong);
                transform: translateY(-8px);
                box-shadow: 0 30px 55px -25px var(--shadow), 0 0 0 1px var(--line-strong);
            }

            .product-image {
                height: 210px;
                position: relative;
                background: var(--bg-2);
                display: flex; align-items: center; justify-content: center;
                overflow: hidden;
                border-bottom: 1px solid var(--line);
            }
            .product-image::before {
                content: '';
                position: absolute; inset: 0;
                background-image:
                    linear-gradient(var(--line) 1px, transparent 1px),
                    linear-gradient(90deg, var(--line) 1px, transparent 1px);
                background-size: 26px 26px;
                opacity: 0.7;
                transition: opacity 0.45s ease;
            }
            .product-card:hover .product-image::before { opacity: 1; }
            .product-image svg {
                width: 60px; height: 60px;
                color: var(--cyan);
                position: relative; z-index: 1;
                transition: all 0.45s var(--ease);
                filter: drop-shadow(0 0 16px var(--glow-soft));
            }
            .product-card:hover .product-image svg {
                transform: scale(1.12) rotate(-4deg);
                filter: drop-shadow(0 0 26px var(--glow));
            }

            .product-info { padding: 1.7rem; }
            .product-info h3 {
                font-family: var(--font-display); font-size: 1.1rem;
                font-weight: 600; margin-bottom: 0.5rem;
            }
            .product-info p { font-size: 0.88rem; color: var(--ink-muted); margin-bottom: 1.3rem; line-height: 1.6; }

            .product-link {
                color: var(--cyan); text-decoration: none; font-weight: 600; font-size: 0.9rem;
                display: inline-flex; align-items: center; gap: 0.5rem;
                transition: gap 0.3s var(--ease);
            }
            .product-link svg { width: 15px; height: 15px; transition: transform 0.3s var(--ease); }
            .product-link:hover { gap: 0.75rem; }
            .product-link:hover svg { transform: translateX(4px); }

            /* ==================== CTA ==================== */
            .cta {
                padding: 6rem 0;
                background: var(--bg);
                position: relative;
                border-top: 1px solid var(--line);
                overflow: hidden;
            }
            .cta .container {
                position: relative; z-index: 1;
                display: flex; flex-wrap: wrap; justify-content: space-between;
                align-items: center; gap: 2rem;
            }
            .cta h2 {
                font-family: var(--font-display);
                font-size: clamp(1.6rem, 3vw, 2.2rem);
                font-weight: 600; max-width: 20ch;
            }
            .cta h2 .accent { color: var(--cyan); }
            .cta p { color: var(--ink-muted); margin-top: 0.75rem; max-width: 44ch; }

            /* ==================== CONTACT ==================== */
            .contact { padding: 7rem 0; background: var(--bg-1); border-top: 1px solid var(--line); }

            .contact-grid { display: grid; grid-template-columns: 1fr; gap: 3rem; }
            @media (min-width: 1024px) { .contact-grid { grid-template-columns: 0.85fr 1.15fr; } }

            .contact-info { display: flex; flex-direction: column; gap: 1.75rem; }
            .contact-item { display: flex; align-items: flex-start; gap: 1rem; }

            .contact-icon {
                width: 48px; height: 48px; flex-shrink: 0;
                border: 1px solid var(--line-strong);
                border-radius: var(--r-md);
                display: flex; align-items: center; justify-content: center;
                color: var(--cyan);
                background: rgba(0, 255, 240, 0.05);
                transition: all 0.4s var(--ease);
            }
            .contact-item:hover .contact-icon {
                background: var(--cyan);
                color: var(--cyan-ink);
                box-shadow: 0 0 22px var(--glow);
                transform: translateY(-3px);
            }
            .contact-icon svg { width: 21px; height: 21px; }

            .contact-text h4 {
                font-family: var(--font-display); font-size: 0.95rem;
                font-weight: 600; margin-bottom: 0.3rem;
            }
            .contact-text p { font-size: 0.88rem; color: var(--ink-muted); }

            .contact-form {
                background: var(--bg);
                padding: 2.5rem;
                border: 1px solid var(--line);
                border-radius: var(--r-xl);
                box-shadow: 0 30px 60px -30px var(--shadow);
            }

            .form-group { margin-bottom: 1.2rem; }
            .form-group label {
                display: block; font-size: 0.78rem; color: var(--ink-faint);
                margin-bottom: 0.45rem; font-family: var(--font-display);
                letter-spacing: 0.04em; text-transform: uppercase;
            }
            .form-group input, .form-group textarea {
                width: 100%;
                padding: 0.9rem 1.1rem;
                border: 1px solid var(--line);
                border-radius: var(--r-md);
                background: var(--bg-1);
                color: var(--ink);
                font-family: inherit;
                font-size: 0.95rem;
                transition: all 0.3s var(--ease);
            }
            .form-group input::placeholder, .form-group textarea::placeholder { color: var(--ink-faint); }
            .form-group input:focus, .form-group textarea:focus {
                outline: none;
                border-color: var(--cyan);
                background: var(--bg-2);
                box-shadow: 0 0 0 4px var(--glow-soft), 0 0 20px -6px var(--glow);
            }

            .contact-form .btn-primary { width: 100%; justify-content: center; margin-top: 0.5rem; }

            /* ==================== FOOTER ==================== */
            .footer {
                background: var(--bg);
                color: var(--ink);
                padding: 4.5rem 0 2rem;
                border-top: 1px solid var(--line);
            }

            .footer-grid { display: grid; grid-template-columns: 1fr; gap: 2.5rem; margin-bottom: 2.5rem; }
            @media (min-width: 768px) { .footer-grid { grid-template-columns: repeat(2, 1fr); } }
            @media (min-width: 1024px) { .footer-grid { grid-template-columns: 1.3fr 1fr 1fr 1fr; } }

            .footer-col .logo img { height: 48px; margin-bottom: 1rem; }
            .footer-col h3 {
                font-family: var(--font-display); font-size: 0.95rem; font-weight: 600;
                margin-bottom: 1.1rem; color: var(--ink);
            }
            .footer-col p { font-size: 0.85rem; color: var(--ink-faint); line-height: 1.65; margin-bottom: 0.35rem; }

            .footer-links { list-style: none; display: flex; flex-direction: column; gap: 0.7rem; }
            .footer-links a {
                color: var(--ink-faint); text-decoration: none; font-size: 0.88rem;
                transition: all 0.3s var(--ease);
                display: inline-block;
            }
            .footer-links a:hover { color: var(--cyan); transform: translateX(4px); }

            .social-links { display: flex; gap: 0.75rem; }
            .social-links a {
                width: 42px; height: 42px;
                border: 1px solid var(--line);
                border-radius: var(--r-md);
                display: flex; align-items: center; justify-content: center;
                transition: all 0.35s var(--ease);
                color: var(--ink-muted);
            }
            .social-links a:hover {
                border-color: var(--cyan);
                color: var(--cyan);
                background: rgba(0, 255, 240, 0.06);
                box-shadow: 0 0 20px -4px var(--glow);
                transform: translateY(-4px);
            }
            .social-links svg { width: 17px; height: 17px; }

            .footer-bottom {
                display: flex; flex-wrap: wrap; justify-content: space-between; gap: 0.75rem;
                padding-top: 1.75rem; border-top: 1px solid var(--line);
                color: var(--ink-faint); font-size: 0.78rem;
            }

            /* ==================== SCROLL TOP ==================== */
            .scroll-top {
                position: fixed; bottom: 28px; right: 28px;
                width: 50px; height: 50px;
                background: linear-gradient(135deg, var(--cyan), var(--cyan-2));
                color: var(--cyan-ink);
                border: none;
                border-radius: var(--r-pill);
                display: flex; align-items: center; justify-content: center;
                cursor: pointer;
                opacity: 0; visibility: hidden;
                transform: translateY(12px);
                transition: all 0.4s var(--ease);
                z-index: 999;
                box-shadow: 0 8px 26px -6px var(--glow);
            }
            .scroll-top.show { opacity: 1; visibility: visible; transform: translateY(0); }
            .scroll-top:hover { transform: translateY(-5px); box-shadow: 0 14px 36px -6px var(--glow); }

            /* Fade-in hero */
            .hero-fade { opacity: 0; transform: translateY(16px); animation: heroFade 0.8s var(--ease-out) forwards; }
            @keyframes heroFade { to { opacity: 1; transform: translateY(0); } }
        </style>
    </head>
    <body>
        <!-- ==================== NAVBAR ==================== -->
        <nav id="navbar" class="navbar">
            <div class="container">
                <div class="logo">
                    <img src="{{ asset('img/logo.png') }}" alt="AMERI">
                </div>

                <div class="nav-links">
                    <a href="#home" class="active">Inicio</a>
                    <a href="#about">Nosotros</a>
                    <a href="#products">Productos</a>
                    <a href="#contact">Contacto</a>
                </div>

                <div class="nav-right">
                    <div class="auth-buttons">
                        @auth
                            <a href="{{ url('/dashboard') }}" class="btn-dashboard">Mi Cuenta</a>
                        @else
                            <a href="{{ route('login') }}" class="btn-login">Iniciar Sesión</a>
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="btn-register">Registrarse</a>
                            @endif
                        @endauth
                    </div>

                    <button id="theme-toggle" class="theme-toggle" aria-label="Cambiar tema" type="button">
                        <svg class="icon-moon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 12.79A9 9 0 1111.21 3 7 7 0 0021 12.79z"/></svg>
                        <svg class="icon-sun" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="4"/><path d="M12 2v2M12 20v2M4.93 4.93l1.41 1.41M17.66 17.66l1.41 1.41M2 12h2M20 12h2M4.93 19.07l1.41-1.41M17.66 6.34l1.41-1.41"/></svg>
                    </button>

                    <a href="" class="carrito-icon" aria-label="Carrito de compras">
                        <i class="mdi mdi-cart-outline"></i>
                        <span id="carrito-contador">0</span>
                    </a>

                    <button id="mobile-menu-btn" class="mobile-menu-btn" aria-label="Abrir menú" type="button">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M3 12h18M3 6h18M3 18h18"/>
                        </svg>
                    </button>
                </div>
            </div>
        </nav>

        <!-- Mobile Menu -->
        <div id="mobile-menu" class="mobile-menu">
            <div class="mobile-menu-header">
                <div class="logo"><img src="{{ asset('img/logo.png') }}" alt="AMERI"></div>
                <button id="mobile-menu-close" class="mobile-menu-close" aria-label="Cerrar menú" type="button">✕</button>
            </div>
            <div class="mobile-menu-links">
                <a href="#home">Inicio</a>
                <a href="#about">Nosotros</a>
                <a href="#products">Productos</a>
                <a href="#contact">Contacto</a>
            </div>
            <div class="mobile-auth">
                @if (Route::has('login'))
                    @auth
                        <a href="{{ url('/dashboard') }}" class="btn-dashboard">Mi Cuenta</a>
                    @else
                        <a href="{{ route('login') }}" class="btn-login-mobile">Iniciar Sesión</a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="btn-register-mobile">Registrarse</a>
                        @endif
                    @endauth
                @endif
            </div>
        </div>
        <div id="mobile-overlay" class="mobile-overlay"></div>

        <!-- ==================== CARRUSEL ==================== -->
        <section class="carousel-section">
            <div class="carousel-container">
                <div class="carousel-slides">
                    <div class="carousel-slide active" data-slide="0">
                        <img src="{{ asset('img/fondo1.jpg') }}" alt="Cerámicos AMERI">
                        <div class="carousel-overlay"></div>
                        <div class="carousel-content">
                            <div class="inner">
                                <div class="carousel-tag">Línea Cerámicos</div>
                                <h2>Superficies que definen <em>cada</em> espacio</h2>
                                <p>Porcelanatos y cerámicos con acabados de precisión, listos para proyectos residenciales y comerciales.</p>
                                <a href="#products" class="carousel-btn btn-scroll">Descubrir productos</a>
                            </div>
                        </div>
                    </div>
                    <div class="carousel-slide" data-slide="1">
                        <img src="{{ asset('img/fondo2.jpg') }}" alt="Cemento Cola AMERI">
                        <div class="carousel-overlay"></div>
                        <div class="carousel-content">
                            <div class="inner">
                                <div class="carousel-tag">Línea Adhesivos</div>
                                <h2>Cemento cola de <em>alta</em> resistencia</h2>
                                <p>Fórmulas profesionales de adherencia certificada para todo tipo de instalaciones.</p>
                                <a href="#products" class="carousel-btn btn-scroll">Ver ficha técnica</a>
                            </div>
                        </div>
                    </div>
                </div>

                <button class="carousel-prev" id="carousel-prev" aria-label="Anterior">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M15 18l-6-6 6-6"/></svg>
                </button>
                <button class="carousel-next" id="carousel-next" aria-label="Siguiente">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 18l6-6-6-6"/></svg>
                </button>

                <div class="carousel-dots">
                    <button class="carousel-dot active" data-slide="0" aria-label="Diapositiva 1"></button>
                    <button class="carousel-dot" data-slide="1" aria-label="Diapositiva 2"></button>
                </div>
            </div>
        </section>

        <!-- ==================== HERO ==================== -->
        <section id="home" class="hero">
            <div class="grout-field"></div>
            <div class="container">
                <div class="hero-content">
                    <div class="hero-eyebrow-line hero-fade" style="animation-delay:0.05s">
                        <span class="dot"></span>
                        <span>AMERI · Bolivia</span>
                    </div>
                    <h1 class="hero-fade" style="animation-delay:0.12s">Cerámicos y cemento cola <span class="accent">de calidad superior</span></h1>
                    <p class="hero-fade" style="animation-delay:0.2s">Tu aliado en construcción y acabados. Porcelanatos, cerámicos y adhesivos profesionales que garantizan durabilidad y estética en cada metro cuadrado.</p>
                    <div class="hero-buttons hero-fade" style="animation-delay:0.28s">
                        <a href="#contact" class="btn-primary">Solicitar cotización</a>
                        <a href="#products" class="btn-outline">Ver productos</a>
                    </div>
                    <div class="hero-stats hero-fade" style="animation-delay:0.36s">
                        <div class="hero-stat"><h3>500+</h3><p>Proyectos realizados</p></div>
                        <div class="hero-stat"><h3>20+</h3><p>Años de experiencia</p></div>
                        <div class="hero-stat"><h3>98%</h3><p>Satisfacción del cliente</p></div>
                    </div>
                </div>

                <div class="spec-panel hero-fade" style="animation-delay:0.2s">
                    <div class="spec-panel-header">
                        <h4>Ficha técnica</h4>
                        <span>Serie Estándar</span>
                    </div>
                    <div class="spec-visual" id="spec-visual"></div>
                    <div class="spec-rows">
                        <div class="spec-row"><span class="label">Formato</span><span class="value">60 × 60 cm</span></div>
                        <div class="spec-row"><span class="label">Absorción de agua</span><span class="value">&lt; 0.5%</span></div>
                        <div class="spec-row"><span class="label">Resistencia a flexión</span><span class="value">Alta</span></div>
                        <div class="spec-row"><span class="label">Uso recomendado</span><span class="value">Piso y pared</span></div>
                    </div>
                </div>
            </div>
        </section>

        <!-- ==================== FEATURES ==================== -->
        <section class="features">
            <div class="container">
                <div class="section-header reveal">
                    <div class="kicker"><span class="dot"></span><span>Ventajas AMERI</span></div>
                    <h2>Calidad, respaldo técnico y <span class="accent">compromiso</span> en cada entrega</h2>
                </div>
                <div class="features-grid">
                    <div class="feature-card reveal" data-delay="1">
                        <div class="feature-icon">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <h3>Calidad certificada</h3>
                        <p>Productos fabricados bajo los más altos estándares de control.</p>
                    </div>
                    <div class="feature-card reveal" data-delay="2">
                        <div class="feature-icon">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21l-7-5-7 5V5a2 2 0 012-2h10a2 2 0 012 2v16z"/></svg>
                        </div>
                        <h3>Durabilidad probada</h3>
                        <p>Materiales resistentes que garantizan larga vida útil.</p>
                    </div>
                    <div class="feature-card reveal" data-delay="3">
                        <div class="feature-icon">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"/></svg>
                        </div>
                        <h3>Catálogo amplio</h3>
                        <p>Variedad de estilos y acabados para cada tipo de proyecto.</p>
                    </div>
                    <div class="feature-card reveal" data-delay="4">
                        <div class="feature-icon">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        </div>
                        <h3>Asesoría técnica</h3>
                        <p>Acompañamiento especializado para elegir el producto ideal.</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- ==================== ABOUT ==================== -->
        <section id="about" class="about">
            <div class="grout-field"></div>
            <div class="container">
                <div class="about-grid">
                    <div class="about-visual reveal">
                        <div class="cell"><svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 6a2 2 0 012-2h12a2 2 0 012 2v12a2 2 0 01-2 2H6a2 2 0 01-2-2V6z"/></svg></div>
                        <div class="cell mark">A</div>
                        <div class="cell"><svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6V4m0 16v-2m8-6h2M2 12h2m13.66-5.66l1.42-1.42M4.92 19.08l1.42-1.42M19.08 19.08l-1.42-1.42M4.92 4.92l1.42 1.42"/></svg></div>
                        <div class="cell mark">M</div>
                        <div class="cell"><svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2"/></svg></div>
                        <div class="cell mark">E</div>
                        <div class="cell"><svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 3v18M15 3v18M3 9h18M3 15h18"/></svg></div>
                        <div class="cell mark">R</div>
                        <div class="cell"><svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 2l3 7h7l-5.5 4.5L18.5 21 12 16.5 5.5 21l2-7.5L2 9h7z"/></svg></div>
                    </div>
                    <div class="about-content reveal" data-delay="2">
                        <div class="section-header" style="margin-bottom:1.5rem;">
                            <div class="kicker"><span class="dot"></span><span>Quiénes somos</span></div>
                            <h2>20 años construyendo <span class="accent">confianza</span></h2>
                        </div>
                        <p><strong>AMERI</strong> es una empresa boliviana con más de 20 años de trayectoria en el sector de la construcción y los acabados. Nos especializamos en la comercialización de <strong>cerámicos, porcelanatos y cemento cola</strong> de alta calidad.</p>
                        <p>Trabajamos con las mejores marcas del mercado y ofrecemos productos que cumplen con estándares internacionales, aportando soluciones duraderas y estéticas para cada proyecto, desde viviendas hasta grandes obras comerciales.</p>
                        <p>Contamos con un equipo de expertos que te asesora en la elección del producto adecuado para tus necesidades.</p>
                        <div class="stats-grid">
                            <div class="stat-item"><h4>20+</h4><p>Años de trayectoria</p></div>
                            <div class="stat-item"><h4>500+</h4><p>Proyectos</p></div>
                            <div class="stat-item"><h4>50+</h4><p>Marcas aliadas</p></div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- ==================== PRODUCTS ==================== -->
        <section id="products" class="products">
            <div class="container">
                <div class="section-header reveal">
                    <div class="kicker"><span class="dot"></span><span>Catálogo</span></div>
                    <h2>Soluciones para <span class="accent">piso, pared</span> y fijación</h2>
                    <p>Todo lo que necesitas para revestir y fijar, en un solo proveedor.</p>
                </div>
                <div class="products-grid">
                    <div class="product-card reveal" data-delay="1">
                        <div class="product-image">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 6a2 2 0 012-2h12a2 2 0 012 2v12a2 2 0 01-2 2H6a2 2 0 01-2-2V6z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 10h8M8 14h4"/></svg>
                        </div>
                        <div class="product-info">
                            <h3>Cerámicos y porcelanatos</h3>
                            <p>Amplia variedad de diseños, texturas y formatos para pisos y paredes.</p>
                            <a href="" class="product-link">Ver productos <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M13 6l6 6-6 6"/></svg></a>
                        </div>
                    </div>
                    <div class="product-card reveal" data-delay="2">
                        <div class="product-image">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                        </div>
                        <div class="product-info">
                            <h3>Cemento cola</h3>
                            <p>Adhesivos de alta resistencia para todo tipo de instalaciones.</p>
                            <a href="" class="product-link">Ver productos <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M13 6l6 6-6 6"/></svg></a>
                        </div>
                    </div>
                    <div class="product-card reveal" data-delay="3">
                        <div class="product-image">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 2l3 7h7l-5.5 4.5L18.5 21 12 16.5 5.5 21l2-7.5L2 9h7z"/></svg>
                        </div>
                        <div class="product-info">
                            <h3>Adhesivos especiales</h3>
                            <p>Soluciones específicas para cerámica, porcelanato y piedra.</p>
                            <a href="" class="product-link">Ver productos <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M13 6l6 6-6 6"/></svg></a>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- ==================== CTA ==================== -->
        <section class="cta">
            <div class="grout-field"></div>
            <div class="container">
                <div class="reveal">
                    <h2>¿Listo para <span class="accent">transformar</span> tu espacio?</h2>
                    <p>Contáctanos y descubre la calidad y el diseño que AMERI tiene para tu proyecto.</p>
                </div>
                <a href="#contact" class="btn-primary reveal" data-delay="2">Solicitar información</a>
            </div>
        </section>

        <!-- ==================== CONTACT ==================== -->
        <section id="contact" class="contact">
            <div class="container">
                <div class="section-header reveal">
                    <div class="kicker"><span class="dot"></span><span>Contacto</span></div>
                    <h2>Hablemos de tu <span class="accent">próximo proyecto</span></h2>
                </div>
                <div class="contact-grid">
                    <div class="contact-info reveal">
                        <div class="contact-item">
                            <div class="contact-icon">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            </div>
                            <div class="contact-text">
                                <h4>Dirección</h4>
                                <p>Av. Principal #123, Zona Industrial, La Paz - Bolivia</p>
                            </div>
                        </div>
                        <div class="contact-item">
                            <div class="contact-icon">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                            </div>
                            <div class="contact-text">
                                <h4>Teléfono</h4>
                                <p>+591 77743260</p>
                            </div>
                        </div>
                        <div class="contact-item">
                            <div class="contact-icon">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                            </div>
                            <div class="contact-text">
                                <h4>Email</h4>
                                <p>info@ameri.com.bo</p>
                            </div>
                        </div>
                    </div>
                    <form class="contact-form reveal" data-delay="2">
                        <div class="form-group">
                            <label for="c-nombre">Nombre completo</label>
                            <input id="c-nombre" type="text" placeholder="Escribe tu nombre">
                        </div>
                        <div class="form-group">
                            <label for="c-email">Correo electrónico</label>
                            <input id="c-email" type="email" placeholder="tu@correo.com">
                        </div>
                        <div class="form-group">
                            <label for="c-tel">Teléfono</label>
                            <input id="c-tel" type="tel" placeholder="+591 ...">
                        </div>
                        <div class="form-group">
                            <label for="c-msg">Mensaje</label>
                            <textarea id="c-msg" rows="4" placeholder="Cuéntanos sobre tu proyecto"></textarea>
                        </div>
                        <button type="submit" class="btn-primary">Enviar mensaje</button>
                    </form>
                </div>
            </div>
        </section>

        <!-- ==================== FOOTER ==================== -->
        <footer class="footer">
            <div class="container">
                <div class="footer-grid">
                    <div class="footer-col reveal">
                        <div class="logo"><img src="{{ asset('img/logo.png') }}" alt="AMERI"></div>
                        <p>Cerámicos y cemento cola de calidad superior. Transformamos espacios con durabilidad y diseño.</p>
                    </div>
                    <div class="footer-col reveal" data-delay="1">
                        <h3>Enlaces rápidos</h3>
                        <ul class="footer-links">
                            <li><a href="#home">Inicio</a></li>
                            <li><a href="#about">Nosotros</a></li>
                            <li><a href="#products">Productos</a></li>
                            <li><a href="#contact">Contacto</a></li>
                        </ul>
                    </div>
                    <div class="footer-col reveal" data-delay="2">
                        <h3>Horario</h3>
                        <p>Lunes a viernes: 8:00 - 18:00</p>
                        <p>Sábados: 9:00 - 13:00</p>
                        <p>Domingos: cerrado</p>
                    </div>
                    <div class="footer-col reveal" data-delay="3">
                        <h3>Síguenos</h3>
                        <div class="social-links">
                            <a href="#" aria-label="Facebook"><svg fill="currentColor" viewBox="0 0 24 24"><path d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.879v-6.99h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.99C18.343 21.128 22 16.991 22 12z"/></svg></a>
                            <a href="#" aria-label="Twitter"><svg fill="currentColor" viewBox="0 0 24 24"><path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 0021.968-11.446c0-.278-.016-.553-.045-.827a10.056 10.056 0 002.46-2.55z"/></svg></a>
                            <a href="#" aria-label="Instagram"><svg fill="currentColor" viewBox="0 0 24 24"><path d="M12.48 10.92v3.28h7.84c-.24 1.84-.853 3.187-1.787 4.133-1.147 1.147-2.933 2.4-6.053 2.4-4.827 0-8.6-3.893-8.6-8.72s3.773-8.72 8.6-8.72c2.6 0 4.507 1.027 5.907 2.347l2.307-2.307C18.747 1.44 16.133 0 12.48 0 5.867 0 .307 5.387.307 12s5.56 12 12.173 12c3.573 0 6.267-1.173 8.373-3.36 2.16-2.16 2.84-5.213 2.84-7.667 0-.76-.053-1.467-.173-2.053H12.48z"/></svg></a>
                        </div>
                    </div>
                </div>
                <div class="footer-bottom">
                    <p>&copy; {{ date('Y') }} AMERI - Cerámicos y Cemento Cola. Todos los derechos reservados.</p>
                    <p>Hecho en Bolivia</p>
                </div>
            </div>
        </footer>

        <button id="scroll-top" class="scroll-top" aria-label="Volver arriba">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 10l7-7m0 0l7 7m-7-7v18"/></svg>
        </button>

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

                // ==================== HERO SPEC-VISUAL TILE GRID ====================
                var specVisual = document.getElementById('spec-visual');
                if (specVisual) {
                    var highlightIndexes = [2, 7, 9];
                    for (var i = 0; i < 12; i++) {
                        var tile = document.createElement('div');
                        tile.className = 'tile' + (highlightIndexes.indexOf(i) > -1 ? ' hi' : '');
                        tile.style.animationDelay = (0.35 + i * 0.045) + 's';
                        specVisual.appendChild(tile);
                    }
                }

                // ==================== CARRUSEL ====================
                var slides = document.querySelectorAll('.carousel-section .carousel-slide');
                var dots = document.querySelectorAll('.carousel-section .carousel-dot');
                var prevBtn = document.getElementById('carousel-prev');
                var nextBtn = document.getElementById('carousel-next');

                var currentSlide = 0;
                var slideInterval;

                function showSlide(index) {
                    if (index < 0) index = slides.length - 1;
                    if (index >= slides.length) index = 0;

                    slides.forEach(function (slide) { slide.classList.remove('active'); });
                    dots.forEach(function (dot) { dot.classList.remove('active'); });

                    slides[index].classList.add('active');
                    dots[index].classList.add('active');
                    currentSlide = index;
                }

                function nextSlide() { showSlide(currentSlide + 1); }
                function prevSlide() { showSlide(currentSlide - 1); }

                function startAutoPlay() {
                    if (slideInterval) clearInterval(slideInterval);
                    slideInterval = setInterval(nextSlide, 5500);
                }

                if (prevBtn && nextBtn) {
                    prevBtn.addEventListener('click', function () { prevSlide(); startAutoPlay(); });
                    nextBtn.addEventListener('click', function () { nextSlide(); startAutoPlay(); });
                }

                dots.forEach(function (dot, index) {
                    dot.addEventListener('click', function () { showSlide(index); startAutoPlay(); });
                });

                if (slides.length > 0) {
                    showSlide(0);
                    startAutoPlay();
                }

                var carouselContainer = document.querySelector('.carousel-container');
                if (carouselContainer) {
                    carouselContainer.addEventListener('mouseenter', function () { if (slideInterval) clearInterval(slideInterval); });
                    carouselContainer.addEventListener('mouseleave', function () { startAutoPlay(); });
                }

                document.querySelectorAll('.btn-scroll').forEach(function (btn) {
                    btn.addEventListener('click', function (e) {
                        e.preventDefault();
                        var target = document.querySelector(this.getAttribute('href'));
                        if (target) target.scrollIntoView({ behavior: 'smooth' });
                    });
                });

                // ==================== MOBILE MENU ====================
                var menuBtn = document.getElementById('mobile-menu-btn');
                var mobileMenu = document.getElementById('mobile-menu');
                var closeBtn = document.getElementById('mobile-menu-close');
                var overlay = document.getElementById('mobile-overlay');

                function openMenu() {
                    mobileMenu.classList.add('active');
                    overlay.classList.add('active');
                    document.body.style.overflow = 'hidden';
                }

                function closeMenu() {
                    mobileMenu.classList.remove('active');
                    overlay.classList.remove('active');
                    document.body.style.overflow = '';
                }

                if (menuBtn) menuBtn.addEventListener('click', openMenu);
                if (closeBtn) closeBtn.addEventListener('click', closeMenu);
                if (overlay) overlay.addEventListener('click', closeMenu);

                document.querySelectorAll('.mobile-menu-links a, .mobile-auth a').forEach(function (link) {
                    link.addEventListener('click', closeMenu);
                });

                // ==================== NAVBAR SCROLL ====================
                var navbar = document.getElementById('navbar');
                window.addEventListener('scroll', function () {
                    if (window.scrollY > 50) navbar.classList.add('scrolled');
                    else navbar.classList.remove('scrolled');
                });

                // ==================== ACTIVE NAV LINK ====================
                var sections = document.querySelectorAll('section[id]');
                var navLinks = document.querySelectorAll('.nav-links a');

                function updateActiveLink() {
                    var current = '';
                    var scrollPos = window.scrollY + 120;

                    sections.forEach(function (section) {
                        var sectionTop = section.offsetTop;
                        var sectionHeight = section.clientHeight;
                        if (scrollPos >= sectionTop && scrollPos < sectionTop + sectionHeight) {
                            current = section.getAttribute('id');
                        }
                    });

                    navLinks.forEach(function (link) {
                        link.classList.remove('active');
                        if (link.getAttribute('href') === '#' + current) link.classList.add('active');
                    });
                }

                window.addEventListener('scroll', updateActiveLink);
                window.addEventListener('load', updateActiveLink);

                // ==================== SCROLL TO TOP ====================
                var scrollTop = document.getElementById('scroll-top');
                window.addEventListener('scroll', function () {
                    if (window.scrollY > 300) scrollTop.classList.add('show');
                    else scrollTop.classList.remove('show');
                });

                scrollTop.addEventListener('click', function () {
                    window.scrollTo({ top: 0, behavior: 'smooth' });
                });

                // ==================== SMOOTH SCROLL ====================
                document.querySelectorAll('a[href^="#"]').forEach(function (anchor) {
                    anchor.addEventListener('click', function (e) {
                        var href = this.getAttribute('href');
                        if (href === '#') return;
                        var target = document.querySelector(href);
                        if (target) {
                            e.preventDefault();
                            target.scrollIntoView({ behavior: 'smooth', block: 'start' });
                        }
                    });
                });

                // ==================== REVEAL ON SCROLL ====================
                var revealEls = document.querySelectorAll('.reveal');
                if ('IntersectionObserver' in window) {
                    var observer = new IntersectionObserver(function (entries) {
                        entries.forEach(function (entry) {
                            if (entry.isIntersecting) {
                                entry.target.classList.add('visible');
                                observer.unobserve(entry.target);
                            }
                        });
                    }, { threshold: 0.12, rootMargin: '0px 0px -60px 0px' });

                    revealEls.forEach(function (el) { observer.observe(el); });
                } else {
                    revealEls.forEach(function (el) { el.classList.add('visible'); });
                }
            })();

            // ==================== CARRITO CONTADOR ====================
            function actualizarContadorCarrito() {
                var carrito = JSON.parse(localStorage.getItem('carrito')) || [];
                var totalItems = carrito.reduce(function (sum, item) { return sum + item.cantidad; }, 0);
                var contador = document.getElementById('carrito-contador');
                if (contador) {
                    if (totalItems > 0) {
                        contador.style.display = 'flex';
                        contador.innerText = totalItems;
                    } else {
                        contador.style.display = 'none';
                    }
                }
            }

            document.addEventListener('DOMContentLoaded', function () {
                actualizarContadorCarrito();
            });
        </script>
    </body>
</html>
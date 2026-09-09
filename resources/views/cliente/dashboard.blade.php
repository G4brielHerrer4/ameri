<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'AMERI') }} - Mi Cuenta</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700,800" rel="stylesheet" />
    
    <!-- Material Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@mdi/font@7.2.96/css/materialdesignicons.min.css">

    <style>
        /* ==================== VARIABLES ==================== */
        :root {
            --primary-blue: #0A2647;
            --primary-green: #10B981;
            --primary-black: #1F2937;
            --primary-white: #FFFFFF;
            --secondary-green: #34D399;
            --secondary-blue: #3B82F6;
            --gray-light: #F9FAFB;
            --gray-medium: #6B7280;
            --gray-dark: #374151;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Instrument Sans', sans-serif;
            background-color: var(--gray-light);
            color: var(--primary-black);
            overflow-x: hidden;
        }

        ::-webkit-scrollbar { width: 8px; }
        ::-webkit-scrollbar-track { background: var(--gray-light); }
        ::-webkit-scrollbar-thumb { background: var(--primary-green); border-radius: 10px; }

        html { scroll-behavior: smooth; }

        .container {
            max-width: 1280px;
            margin: 0 auto;
            padding: 0 2rem;
        }

        @media (max-width: 1024px) { .container { padding: 0 1.5rem; } }
        @media (max-width: 640px) { .container { padding: 0 1rem; } }

        /* ==================== NAVBAR ==================== */
        .navbar {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            background: rgba(10, 38, 71, 0.95);
            backdrop-filter: blur(10px);
            z-index: 1000;
            padding: 1rem 0;
            transition: all 0.3s ease;
        }

        .navbar.scrolled {
            padding: 0.5rem 0;
            background: var(--primary-blue);
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        }

        .navbar .container {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo img {
            height: 55px;
            width: auto;
            object-fit: contain;
        }

        @media (min-width: 768px) { .logo img { height: 65px; } }

        .nav-links {
            display: none;
            gap: 2rem;
            align-items: center;
        }

        @media (min-width: 1024px) { .nav-links { display: flex; } }

        .nav-links a {
            color: var(--primary-white);
            text-decoration: none;
            font-weight: 500;
            font-size: 0.95rem;
            transition: all 0.3s ease;
            position: relative;
        }

        .nav-links a::after {
            content: '';
            position: absolute;
            bottom: -5px;
            left: 0;
            width: 0;
            height: 2px;
            background: var(--primary-green);
            transition: width 0.3s ease;
        }

        .nav-links a:hover::after,
        .nav-links a.active::after {
            width: 100%;
        }

        .nav-links a:hover,
        .nav-links a.active {
            color: var(--primary-green);
        }

        .auth-buttons {
            display: none;
            gap: 1rem;
            align-items: center;
        }

        @media (min-width: 1024px) { .auth-buttons { display: flex; } }

        .btn-login, .btn-dashboard {
            padding: 0.5rem 1.25rem;
            color: var(--primary-white);
            text-decoration: none;
            font-weight: 500;
            border-radius: 8px;
            transition: all 0.3s ease;
        }

        .btn-dashboard {
            background: var(--primary-green);
            font-weight: 600;
        }

        .btn-dashboard:hover {
            background: var(--secondary-green);
            transform: translateY(-2px);
        }

        .btn-login:hover {
            color: var(--primary-green);
            background: rgba(16, 185, 129, 0.1);
        }

        .btn-register {
            padding: 0.5rem 1.25rem;
            background: var(--primary-green);
            color: var(--primary-white);
            text-decoration: none;
            font-weight: 600;
            border-radius: 8px;
            transition: all 0.3s ease;
        }

        .btn-register:hover {
            background: var(--secondary-green);
            transform: translateY(-2px);
        }

        .carrito-icon {
            display: inline-flex;
            align-items: center;
            transition: all 0.3s ease;
            position: relative;
            color: white;
            text-decoration: none;
            margin-left: 1rem;
        }
        .carrito-icon:hover {
            color: var(--primary-green);
            transform: scale(1.05);
        }

        .mobile-menu-btn {
            display: block;
            background: none;
            border: none;
            color: var(--primary-white);
            cursor: pointer;
        }

        @media (min-width: 1024px) { .mobile-menu-btn { display: none; } }

        .mobile-menu-btn svg { width: 24px; height: 24px; }

        .mobile-menu {
            position: fixed;
            top: 0;
            right: -100%;
            width: 80%;
            max-width: 320px;
            height: 100vh;
            background: var(--primary-blue);
            z-index: 1001;
            padding: 2rem;
            transition: right 0.3s ease;
            box-shadow: -5px 0 20px rgba(0, 0, 0, 0.2);
        }

        .mobile-menu.active { right: 0; }

        .mobile-menu-header {
            display: flex;
            justify-content: flex-end;
            margin-bottom: 2rem;
        }

        .mobile-menu-close {
            background: none;
            border: none;
            color: var(--primary-white);
            cursor: pointer;
            font-size: 1.5rem;
        }

        .mobile-menu-links {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        .mobile-menu-links a {
            color: var(--primary-white);
            text-decoration: none;
            padding: 0.75rem 0;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .mobile-menu-links a:hover {
            color: var(--primary-green);
            padding-left: 0.5rem;
        }

        .mobile-auth {
            margin-top: 2rem;
            padding-top: 1rem;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
        }

        .mobile-auth a {
            color: var(--primary-white);
            text-decoration: none;
            padding: 0.75rem;
            text-align: center;
            border-radius: 8px;
        }

        .mobile-auth .btn-login-mobile { background: rgba(255, 255, 255, 0.1); }
        .mobile-auth .btn-register-mobile { background: var(--primary-green); }

        .mobile-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 1000;
            display: none;
        }

        .mobile-overlay.active { display: block; }

        /* ==================== DASHBOARD CLIENTE ==================== */
        .cliente-dashboard {
            padding: 120px 0 3rem;
            min-height: 100vh;
        }

        .dashboard-wrapper {
            display: flex;
            gap: 2rem;
            flex-wrap: wrap;
        }

        /* Sidebar */
        .cliente-sidebar {
            flex: 0 0 280px;
            background: white;
            border-radius: 24px;
            padding: 1.5rem;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
            height: fit-content;
            position: sticky;
            top: 100px;
        }

        .cliente-perfil {
            text-align: center;
            padding-bottom: 1.5rem;
            border-bottom: 2px solid var(--gray-light);
            margin-bottom: 1.5rem;
        }

        .cliente-avatar {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, var(--primary-green), var(--secondary-green));
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1rem;
            box-shadow: 0 8px 20px rgba(16, 185, 129, 0.3);
        }

        .cliente-avatar i {
            font-size: 40px;
            color: white;
        }

        .cliente-perfil h4 {
            font-size: 1.1rem;
            font-weight: 700;
            color: var(--primary-black);
            margin-bottom: 0.25rem;
        }

        .cliente-perfil .cliente-rol {
            font-size: 0.75rem;
            background: rgba(16, 185, 129, 0.1);
            color: var(--primary-green);
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            display: inline-block;
        }

        .cliente-perfil .cliente-email {
            font-size: 0.75rem;
            color: var(--gray-medium);
            margin-top: 0.5rem;
        }

        .cliente-menu {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .cliente-menu li {
            margin-bottom: 0.5rem;
        }

        .cliente-menu a {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.75rem 1rem;
            color: var(--gray-dark);
            text-decoration: none;
            border-radius: 12px;
            transition: all 0.3s ease;
            font-weight: 500;
        }

        .cliente-menu a i {
            font-size: 1.1rem;
            width: 24px;
            color: var(--gray-medium);
            transition: all 0.3s ease;
        }

        .cliente-menu a:hover {
            background: rgba(16, 185, 129, 0.1);
            color: var(--primary-green);
        }

        .cliente-menu a:hover i {
            color: var(--primary-green);
        }

        .cliente-menu a.active {
            background: var(--primary-green);
            color: white;
        }

        .cliente-menu a.active i {
            color: white;
        }

        .logout-btn {
            margin-top: 1.5rem;
            padding-top: 1.5rem;
            border-top: 2px solid var(--gray-light);
        }

        .logout-btn button {
            width: 100%;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.75rem 1rem;
            color: #EF4444;
            background: none;
            border: none;
            border-radius: 12px;
            transition: all 0.3s ease;
            font-weight: 500;
            cursor: pointer;
        }

        .logout-btn button:hover {
            background: rgba(239, 68, 68, 0.1);
        }

        /* Contenido principal */
        .cliente-contenido {
            flex: 1;
            min-width: 0;
        }

        .welcome-card {
            background: linear-gradient(135deg, var(--primary-blue) 0%, #1E3A5F 100%);
            border-radius: 24px;
            padding: 1.5rem;
            color: white;
            margin-bottom: 1.5rem;
            position: relative;
            overflow: hidden;
        }

        .welcome-card::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -20%;
            width: 200px;
            height: 200px;
            background: rgba(16, 185, 129, 0.1);
            border-radius: 50%;
        }

        .welcome-card h2 {
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }

        .welcome-card p {
            color: rgba(255, 255, 255, 0.8);
            margin-bottom: 0;
        }

        .motivacional-card {
            background: white;
            border-radius: 16px;
            padding: 1rem;
            text-align: center;
            margin-bottom: 1.5rem;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        }

        .motivacional-card p {
            font-size: 0.85rem;
            color: var(--gray-dark);
            margin-bottom: 0;
        }

        .motivacional-card i {
            color: var(--primary-green);
            margin-right: 0.5rem;
        }

        /* ==================== PEDIDOS ==================== */
        .pedidos-section {
            background: white;
            border-radius: 24px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
        }

        .pedidos-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
            padding-bottom: 1rem;
            border-bottom: 2px solid var(--gray-light);
        }

        .pedidos-header h3 {
            font-size: 1.1rem;
            font-weight: 700;
            margin: 0;
        }

        .pedidos-header h3 i {
            color: var(--primary-green);
            margin-right: 0.5rem;
        }

        .pedidos-header .ver-todos {
            color: var(--primary-green);
            text-decoration: none;
            font-size: 0.8rem;
            font-weight: 500;
        }

        .pedidos-header .ver-todos:hover {
            text-decoration: underline;
        }

        .pedido-card {
            background: var(--gray-light);
            border-radius: 16px;
            padding: 1rem;
            margin-bottom: 1rem;
            transition: all 0.3s ease;
        }

        .pedido-card:hover {
            transform: translateX(5px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        }

        .pedido-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 0.5rem;
            margin-bottom: 0.75rem;
        }

        .pedido-codigo {
            font-weight: 700;
            color: var(--primary-black);
            font-size: 0.9rem;
        }

        .pedido-estado {
            font-size: 0.7rem;
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-weight: 600;
        }

        .estado-pendiente { background: #FEF3C7; color: #D97706; }
        .estado-listo { background: #D1FAE5; color: #059669; }
        .estado-entregado { background: #DBEAFE; color: #2563EB; }

        .pedido-info {
            display: flex;
            flex-wrap: wrap;
            gap: 1rem;
            margin-bottom: 0.75rem;
        }

        .pedido-info-item {
            display: flex;
            align-items: center;
            gap: 0.25rem;
            font-size: 0.75rem;
            color: var(--gray-medium);
        }

        .pedido-info-item i {
            font-size: 0.8rem;
        }

        .pedido-total {
            font-weight: 700;
            color: var(--primary-green);
            font-size: 0.9rem;
        }

        .pedido-footer {
            display: flex;
            justify-content: flex-end;
            padding-top: 0.75rem;
            border-top: 1px solid #E5E7EB;
        }

        .btn-ver-detalle {
            background: none;
            border: none;
            color: var(--primary-green);
            font-size: 0.75rem;
            font-weight: 500;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 0.25rem;
            transition: all 0.3s ease;
        }

        .btn-ver-detalle:hover {
            gap: 0.5rem;
        }

        .sin-pedidos {
            text-align: center;
            padding: 2rem;
            color: var(--gray-medium);
        }

        .sin-pedidos i {
            font-size: 48px;
            margin-bottom: 1rem;
            display: inline-block;
        }

        /* Módulos grid */
        .modulos-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));
            gap: 1.5rem;
        }

        .modulo-card {
            background: white;
            border-radius: 20px;
            padding: 1.5rem;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
            text-decoration: none;
            display: block;
        }

        .modulo-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 30px rgba(16, 185, 129, 0.15);
        }

        .modulo-icon {
            width: 55px;
            height: 55px;
            background: rgba(16, 185, 129, 0.1);
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1rem;
        }

        .modulo-icon i {
            font-size: 28px;
            color: var(--primary-green);
        }

        .modulo-card h3 {
            font-size: 1rem;
            font-weight: 700;
            color: var(--primary-black);
            margin-bottom: 0.5rem;
        }

        .modulo-card p {
            font-size: 0.8rem;
            color: var(--gray-medium);
            margin-bottom: 0;
        }

        /* Estadísticas */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 1rem;
            margin-top: 1.5rem;
        }

        .stat-card {
            background: white;
            border-radius: 16px;
            padding: 1rem;
            text-align: center;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        }

        .stat-card h3 {
            font-size: 1.5rem;
            font-weight: 800;
            color: var(--primary-green);
        }

        .stat-card p {
            font-size: 0.7rem;
            color: var(--gray-medium);
            margin-top: 0.25rem;
        }

        /* Footer */
        .footer {
            background: var(--primary-black);
            color: var(--primary-white);
            padding: 3rem 0 1.5rem;
            margin-top: 3rem;
        }

        .footer-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 2rem;
            margin-bottom: 2rem;
        }

        .footer-col h3 {
            font-size: 1.125rem;
            font-weight: 700;
            margin-bottom: 1rem;
            color: var(--primary-green);
        }

        .footer-col p, .footer-col a {
            font-size: 0.875rem;
            color: #9CA3AF;
            line-height: 1.5;
            text-decoration: none;
        }

        .footer-links { list-style: none; }
        .footer-links li { margin-bottom: 0.5rem; }
        .footer-links a:hover { color: var(--primary-green); }

        .social-links {
            display: flex;
            gap: 1rem;
        }

        .social-links a {
            width: 36px;
            height: 36px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
        }

        .social-links a:hover {
            background: var(--primary-green);
            transform: translateY(-3px);
        }

        .social-links svg { width: 18px; height: 18px; color: white; }

        .footer-bottom {
            text-align: center;
            padding-top: 1.5rem;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            color: #9CA3AF;
            font-size: 0.75rem;
        }

        .scroll-top {
            position: fixed;
            bottom: 30px;
            right: 30px;
            width: 45px;
            height: 45px;
            background: var(--primary-green);
            color: white;
            border: none;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
            z-index: 999;
        }

        .scroll-top.show {
            opacity: 1;
            visibility: visible;
        }

        .scroll-top:hover {
            background: var(--secondary-green);
            transform: translateY(-5px);
        }

        @media (max-width: 992px) {
            .dashboard-wrapper {
                flex-direction: column;
            }
            .cliente-sidebar {
                flex: auto;
                position: static;
            }
        }
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
                <a href="{{ url('/') }}">Inicio</a>
                <a href="{{ url('/') }}#about">Nosotros</a>
                <a href="{{ url('/') }}#products">Productos</a>
                <a href="{{ url('/') }}#contact">Contacto</a>
            </div>
            <div class="auth-buttons">
                @auth
                    <a href="{{ url('/dashboard') }}" class="btn-dashboard">Mi Cuenta</a>
                @else
                    <a href="{{ route('login') }}" class="btn-login">Iniciar Sesión</a>
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="btn-register">Registrarse</a>
                    @endif
                @endauth
                <a href="" class="carrito-icon">
                    <i class="mdi mdi-cart-outline" style="font-size: 24px;"></i>
                    <span id="carrito-contador" style="position: absolute; top: -8px; right: -12px; background: #10B981; color: white; font-size: 10px; font-weight: bold; min-width: 18px; height: 18px; border-radius: 50%; display: none; align-items: center; justify-content: center;">0</span>
                </a>
            </div>
            <button id="mobile-menu-btn" class="mobile-menu-btn">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M3 12h18M3 6h18M3 18h18"/>
                </svg>
            </button>
        </div>
    </nav>

    <!-- Mobile Menu -->
    <div id="mobile-menu" class="mobile-menu">
        <div class="mobile-menu-header">
            <button id="mobile-menu-close" class="mobile-menu-close">✕</button>
        </div>
        <div class="mobile-menu-links">
            <a href="{{ url('/') }}">Inicio</a>
            <a href="{{ url('/') }}#about">Nosotros</a>
            <a href="{{ url('/') }}#products">Productos</a>
            <a href="{{ url('/') }}#contact">Contacto</a>
        </div>
        <div class="mobile-auth">
            @auth
                <a href="{{ url('/dashboard') }}" style="background: var(--primary-green);">Mi Cuenta</a>
            @else
                <a href="{{ route('login') }}" class="btn-login-mobile">Iniciar Sesión</a>
                @if (Route::has('register'))
                    <a href="{{ route('register') }}" class="btn-register-mobile">Registrarse</a>
                @endif
            @endauth
        </div>
    </div>
    <div id="mobile-overlay" class="mobile-overlay"></div>

    <!-- ==================== DASHBOARD CLIENTE ==================== -->
    <section class="cliente-dashboard">
        <div class="container">
            <div class="dashboard-wrapper">
                
                <!-- Sidebar -->
                <aside class="cliente-sidebar">
                    <div class="cliente-perfil">
                        <div class="cliente-avatar">
                            <i class="mdi mdi-account-circle"></i>
                        </div>
                        <h4>{{ Auth::user()->name }}</h4>
                        <span class="cliente-rol">
                            <i class="mdi mdi-shield-account"></i> {{ Auth::user()->role->name ?? 'Cliente' }}
                        </span>
                        <p class="cliente-email">
                            <i class="mdi mdi-email-outline"></i> {{ Auth::user()->email }}
                        </p>
                    </div>

                    <ul class="cliente-menu">
                        <li><a href="#" class="active"><i class="mdi mdi-view-dashboard"></i> Dashboard</a></li>
                        <li><a href=""><i class="mdi mdi-package-variant"></i> Mis Pedidos</a></li>
                        <li><a href=""><i class="mdi mdi-store"></i> Tienda</a></li>
                        <li><a href="#"><i class="mdi mdi-heart"></i> Lista de Deseos</a></li>
                        <li><a href="#"><i class="mdi mdi-account-settings"></i> Mi Perfil</a></li>
                        <li><a href="#"><i class="mdi mdi-headset"></i> Soporte</a></li>
                    </ul>

                    <div class="logout-btn">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit">
                                <i class="mdi mdi-logout"></i>
                                <span>Cerrar Sesión</span>
                            </button>
                        </form>
                    </div>
                </aside>

                <!-- Contenido Principal -->
                <main class="cliente-contenido">
                    
                    <!-- Bienvenida -->
                    <div class="welcome-card">
                        <h2>¡Bienvenido, {{ Auth::user()->name }}!</h2>
                        <p>Nos alegra tenerte de vuelta. Aquí podrás gestionar tus pedidos y preferencias.</p>
                    </div>

                    <!-- Mensaje Motivacional -->
                    <div class="motivacional-card">
                        <p>
                            <i class="mdi mdi-lightbulb-on-outline"></i>
                            "La calidad no es un acto, es un hábito" - Aristóteles
                        </p>
                    </div>

                    <!-- Estadísticas rápidas -->
                    <div class="stats-grid">
                        <div class="stat-card">
                            <h3 id="total-pedidos">0</h3>
                            <p>Pedidos realizados</p>
                        </div>
                        <div class="stat-card">
                            <h3 id="total-entregados">0</h3>
                            <p>Entregas completadas</p>
                        </div>
                        <div class="stat-card">
                            <h3 id="total-pendientes">0</h3>
                            <p>En proceso</p>
                        </div>
                    </div>

                    <!-- Últimos Pedidos -->
                    <div class="pedidos-section">
                        <div class="pedidos-header">
                            <h3><i class="mdi mdi-package-variant"></i> Mis Últimos Pedidos</h3>
                            <a href="" class="ver-todos">Ver todos →</a>
                        </div>
                        <div id="pedidos-container">
                            <div class="text-center py-3">
                                <div class="spinner-border text-success" style="width: 30px; height: 30px;"></div>
                                <p class="mt-2 text-muted">Cargando pedidos...</p>
                            </div>
                        </div>
                    </div>

                    <!-- Módulos -->
                    <h5 class="mt-4 mb-3">
                        <i class="mdi mdi-apps me-2" style="color: #10B981;"></i>
                        Accesos Rápidos
                    </h5>

                    <div class="modulos-grid">
                        <a href="" class="modulo-card">
                            <div class="modulo-icon"><i class="mdi mdi-package-variant"></i></div>
                            <h3>Mis Pedidos</h3>
                            <p>Consulta el estado de tus pedidos</p>
                        </a>
                        <a href="" class="modulo-card">
                            <div class="modulo-icon"><i class="mdi mdi-store"></i></div>
                            <h3>Tienda</h3>
                            <p>Explora nuestro catálogo de productos</p>
                        </a>
                        <a href="#" class="modulo-card">
                            <div class="modulo-icon"><i class="mdi mdi-heart"></i></div>
                            <h3>Lista de Deseos</h3>
                            <p>Productos que te interesan</p>
                        </a>
                        <a href="#" class="modulo-card">
                            <div class="modulo-icon"><i class="mdi mdi-account-settings"></i></div>
                            <h3>Mi Perfil</h3>
                            <p>Actualiza tus datos personales</p>
                        </a>
                    </div>
                </main>
            </div>
        </div>
    </section>

    <!-- ==================== FOOTER ==================== -->
    <footer class="footer">
        <div class="container">
            <div class="footer-grid">
                <div class="footer-col">
                    <h3>AMERI</h3>
                    <p>Soluciones innovadoras en plástico, comprometidos con la calidad y la sostenibilidad.</p>
                </div>
                <div class="footer-col">
                    <h3>Enlaces Rápidos</h3>
                    <ul class="footer-links">
                        <li><a href="{{ url('/') }}">Inicio</a></li>
                        <li><a href="{{ url('/') }}#about">Nosotros</a></li>
                        <li><a href="{{ url('/') }}#products">Productos</a></li>
                        <li><a href="{{ url('/') }}#contact">Contacto</a></li>
                    </ul>
                </div>
                <div class="footer-col">
                    <h3>Horario</h3>
                    <p>Lunes a Viernes: 8:00 - 17:30</p>
                    <p>Sábados: Cerrado</p>
                    <p>Domingos: Cerrado</p>
                </div>
                <div class="footer-col">
                    <h3>Síguenos</h3>
                    <div class="social-links">
                        <a href="#"><svg fill="currentColor" viewBox="0 0 24 24"><path d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.879v-6.99h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.99C18.343 21.128 22 16.991 22 12z"/></svg></a>
                        <a href="#"><svg fill="currentColor" viewBox="0 0 24 24"><path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 0021.968-11.446c0-.278-.016-.553-.045-.827a10.056 10.056 0 002.46-2.55z"/></svg></a>
                        <a href="#"><svg fill="currentColor" viewBox="0 0 24 24"><path d="M12.48 10.92v3.28h7.84c-.24 1.84-.853 3.187-1.787 4.133-1.147 1.147-2.933 2.4-6.053 2.4-4.827 0-8.6-3.893-8.6-8.72s3.773-8.72 8.6-8.72c2.6 0 4.507 1.027 5.907 2.347l2.307-2.307C18.747 1.44 16.133 0 12.48 0 5.867 0 .307 5.387.307 12s5.56 12 12.173 12c3.573 0 6.267-1.173 8.373-3.36 2.16-2.16 2.84-5.213 2.84-7.667 0-.76-.053-1.467-.173-2.053H12.48z"/></svg></a>
                    </div>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; {{ date('Y') }} AMERI - Soluciones en Plástico. Todos los derechos reservados.</p>
            </div>
        </div>
    </footer>

    <!-- Scroll to Top -->
    <button id="scroll-top" class="scroll-top">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M5 10l7-7m0 0l7 7m-7-7v18"/>
        </svg>
    </button>

    <script>
        (function() {
            // Mobile Menu
            const menuBtn = document.getElementById('mobile-menu-btn');
            const mobileMenu = document.getElementById('mobile-menu');
            const closeBtn = document.getElementById('mobile-menu-close');
            const overlay = document.getElementById('mobile-overlay');

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
            document.querySelectorAll('.mobile-menu-links a, .mobile-auth a').forEach(link => {
                link.addEventListener('click', closeMenu);
            });

            // Función para actualizar el contador del carrito
            function actualizarContadorCarrito() {
                const carrito = JSON.parse(localStorage.getItem('carrito')) || [];
                const totalItems = carrito.reduce((sum, item) => sum + item.cantidad, 0);
                const contador = document.getElementById('carrito-contador');
                if (contador) {
                    if (totalItems > 0) {
                        contador.style.display = 'flex';
                        contador.innerText = totalItems;
                    } else {
                        contador.style.display = 'none';
                    }
                }
            }

            // Navbar Scroll Effect
            const navbar = document.getElementById('navbar');
            window.addEventListener('scroll', () => {
                if (window.scrollY > 50) {
                    navbar.classList.add('scrolled');
                } else {
                    navbar.classList.remove('scrolled');
                }
            });

            // Scroll to Top
            const scrollTop = document.getElementById('scroll-top');
            window.addEventListener('scroll', () => {
                if (window.scrollY > 300) {
                    scrollTop.classList.add('show');
                } else {
                    scrollTop.classList.remove('show');
                }
            });
            scrollTop.addEventListener('click', () => {
                window.scrollTo({ top: 0, behavior: 'smooth' });
            });

            // Inicializar
            actualizarContadorCarrito();
            cargarPedidos();
        })();
    </script>
</body>
</html>
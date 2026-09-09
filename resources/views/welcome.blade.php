<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'AMERI') }} - Cerámicos y Cemento Cola</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700,800" rel="stylesheet" />
        <!-- Material Icons -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@mdi/font@7.2.96/css/materialdesignicons.min.css">

        <!-- Styles / Scripts Laravel -->
        @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
            @vite(['resources/css/app.css', 'resources/js/app.js'])
        @endif

        <style>
            /* ==================== VARIABLES - PALETA AMERI ==================== */
            :root {
                --primary-orange: #E85D04;
                --primary-dark: #1A1A1A;
                --primary-brown: #5C3A21;
                --primary-white: #FFFFFF;
                --secondary-orange: #F48C06;
                --tertiary-orange: #FFBA08;
                --gray-light: #F8F6F3;
                --gray-medium: #6B6B6B;
                --gray-dark: #2D2D2D;
                --gradient-primary: linear-gradient(135deg, #E85D04 0%, #DC2F02 100%);
                --shadow-orange: rgba(232, 93, 4, 0.3);
            }

            /* ==================== RESET & BASE ==================== */
            * {
                margin: 0;
                padding: 0;
                box-sizing: border-box;
            }

            body {
                font-family: 'Instrument Sans', sans-serif;
                background-color: var(--primary-white);
                color: var(--primary-dark);
                overflow-x: hidden;
            }

            /* ==================== SCROLLBAR ==================== */
            ::-webkit-scrollbar {
                width: 8px;
            }
            
            ::-webkit-scrollbar-track {
                background: var(--gray-light);
            }
            
            ::-webkit-scrollbar-thumb {
                background: var(--primary-orange);
                border-radius: 10px;
            }
            
            ::-webkit-scrollbar-thumb:hover {
                background: var(--secondary-orange);
            }

            /* ==================== SMOOTH SCROLL ==================== */
            html {
                scroll-behavior: smooth;
            }

            /* ==================== CONTAINER ==================== */
            .container {
                max-width: 1280px;
                margin: 0 auto;
                padding: 0 2rem;
            }

            @media (max-width: 1024px) {
                .container {
                    padding: 0 1.5rem;
                }
            }

            @media (max-width: 640px) {
                .container {
                    padding: 0 1rem;
                }
            }

            /* ==================== NAVBAR ==================== */
            .navbar {
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                background: rgba(26, 26, 26, 0.95);
                backdrop-filter: blur(10px);
                z-index: 1000;
                padding: 1rem 0;
                transition: all 0.3s ease;
            }

            .navbar.scrolled {
                padding: 0.5rem 0;
                background: var(--primary-dark);
                box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
            }

            .navbar .container {
                display: flex;
                justify-content: space-between;
                align-items: center;
            }

            /* Logo */
            .logo {
                flex-shrink: 0;
            }

            .logo img {
                height: 55px;
                width: auto;
                object-fit: contain;
                transition: all 0.3s ease;
            }

            @media (min-width: 768px) {
                .logo img {
                    height: 65px;
                }
            }

            /* Desktop Navigation */
            .nav-links {
                display: none;
                gap: 2rem;
                align-items: center;
            }

            @media (min-width: 1024px) {
                .nav-links {
                    display: flex;
                }
            }

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
                background: var(--primary-orange);
                transition: width 0.3s ease;
            }

            .nav-links a:hover::after,
            .nav-links a.active::after {
                width: 100%;
            }

            .nav-links a:hover,
            .nav-links a.active {
                color: var(--primary-orange);
            }

            /* Auth Buttons */
            .auth-buttons {
                display: none;
                gap: 1rem;
                align-items: center;
            }

            @media (min-width: 1024px) {
                .auth-buttons {
                    display: flex;
                }
            }

            .btn-login {
                padding: 0.5rem 1.25rem;
                color: var(--primary-white);
                text-decoration: none;
                font-weight: 500;
                border-radius: 8px;
                transition: all 0.3s ease;
            }

            .btn-login:hover {
                color: var(--primary-orange);
                background: rgba(232, 93, 4, 0.1);
            }

            .btn-register {
                padding: 0.5rem 1.25rem;
                background: var(--gradient-primary);
                color: var(--primary-white);
                text-decoration: none;
                font-weight: 600;
                border-radius: 8px;
                transition: all 0.3s ease;
            }

            .btn-register:hover {
                background: var(--secondary-orange);
                transform: translateY(-2px);
                box-shadow: 0 8px 25px var(--shadow-orange);
            }

            .btn-dashboard {
                padding: 0.5rem 1.25rem;
                background: var(--gradient-primary);
                color: var(--primary-white);
                text-decoration: none;
                font-weight: 600;
                border-radius: 8px;
                transition: all 0.3s ease;
            }

            .btn-dashboard:hover {
                background: var(--secondary-orange);
                transform: translateY(-2px);
                box-shadow: 0 8px 25px var(--shadow-orange);
            }

            /* Mobile Menu Button */
            .mobile-menu-btn {
                display: block;
                background: none;
                border: none;
                color: var(--primary-white);
                cursor: pointer;
                padding: 0.5rem;
                transition: all 0.3s ease;
            }

            .mobile-menu-btn:hover {
                color: var(--primary-orange);
            }

            @media (min-width: 1024px) {
                .mobile-menu-btn {
                    display: none;
                }
            }

            /* Mobile Menu */
            .mobile-menu {
                position: fixed;
                top: 0;
                right: -100%;
                width: 80%;
                max-width: 320px;
                height: 100vh;
                background: var(--primary-dark);
                z-index: 1001;
                padding: 2rem;
                transition: right 0.3s ease;
                box-shadow: -5px 0 20px rgba(0, 0, 0, 0.4);
            }

            .mobile-menu.active {
                right: 0;
            }

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
                font-weight: 500;
                font-size: 1rem;
                padding: 0.75rem 0;
                border-bottom: 1px solid rgba(255, 255, 255, 0.05);
                transition: all 0.3s ease;
            }

            .mobile-menu-links a:hover {
                color: var(--primary-orange);
                padding-left: 0.5rem;
            }

            .mobile-auth {
                margin-top: 2rem;
                padding-top: 1rem;
                border-top: 1px solid rgba(255, 255, 255, 0.05);
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
                transition: all 0.3s ease;
            }

            .mobile-auth .btn-login-mobile {
                background: rgba(255, 255, 255, 0.05);
            }

            .mobile-auth .btn-login-mobile:hover {
                background: rgba(232, 93, 4, 0.2);
                color: var(--primary-orange);
            }

            .mobile-auth .btn-register-mobile {
                background: var(--gradient-primary);
            }

            .mobile-auth .btn-register-mobile:hover {
                background: var(--secondary-orange);
                transform: scale(1.02);
            }

            /* Overlay */
            .mobile-overlay {
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background: rgba(0, 0, 0, 0.6);
                z-index: 1000;
                display: none;
            }

            .mobile-overlay.active {
                display: block;
            }

            /* ==================== CARRUSEL SECTION ==================== */
            .carousel-section {
                position: relative;
                width: 100%;
                height: 90vh;
                min-height: 500px;
                overflow: hidden;
                margin-top: 80px;
            }

            .carousel-container {
                position: relative;
                width: 100%;
                height: 100%;
            }

            .carousel-section .carousel-slides {
                position: relative;
                width: 100%;
                height: 100%;
            }

            .carousel-section .carousel-slide {
                position: absolute;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                opacity: 0;
                visibility: hidden;
                transition: opacity 0.8s ease-in-out, visibility 0.8s ease-in-out;
            }

            .carousel-section .carousel-slide.active {
                opacity: 1;
                visibility: visible;
            }

            .carousel-section .carousel-slide img {
                width: 100%;
                height: 100%;
                object-fit: cover;
            }

            .carousel-section .carousel-overlay {
                position: absolute;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background: linear-gradient(135deg, rgba(26, 26, 26, 0.85) 0%, rgba(0, 0, 0, 0.7) 100%);
            }

            .carousel-section .carousel-content {
                position: absolute;
                top: 50%;
                left: 50%;
                transform: translate(-50%, -50%);
                text-align: center;
                color: white;
                z-index: 2;
                width: 100%;
                padding: 0 20px;
            }

            .carousel-section .carousel-content h2 {
                font-size: 2.5rem;
                font-weight: 700;
                margin-bottom: 1rem;
                animation: fadeInUp 0.8s ease;
            }

            .carousel-section .carousel-content h2 span {
                color: var(--primary-orange);
            }

            .carousel-section .carousel-content p {
                font-size: 1.125rem;
                margin-bottom: 1.5rem;
                animation: fadeInUp 0.8s ease 0.2s both;
                color: rgba(255,255,255,0.85);
            }

            .carousel-section .carousel-btn {
                display: inline-block;
                padding: 0.875rem 2rem;
                background: var(--gradient-primary);
                color: white;
                text-decoration: none;
                font-weight: 600;
                border-radius: 12px;
                transition: all 0.3s ease;
                animation: fadeInUp 0.8s ease 0.4s both;
            }

            .carousel-section .carousel-btn:hover {
                background: var(--secondary-orange);
                transform: translateY(-3px);
                box-shadow: 0 10px 25px var(--shadow-orange);
            }

            /* Controles del carrusel */
            .carousel-section .carousel-prev,
            .carousel-section .carousel-next {
                position: absolute;
                top: 50%;
                transform: translateY(-50%);
                background: rgba(255, 255, 255, 0.1);
                backdrop-filter: blur(5px);
                border: none;
                width: 48px;
                height: 48px;
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
                cursor: pointer;
                transition: all 0.3s ease;
                z-index: 10;
                color: white;
            }

            .carousel-section .carousel-prev {
                left: 20px;
            }

            .carousel-section .carousel-next {
                right: 20px;
            }

            .carousel-section .carousel-prev:hover,
            .carousel-section .carousel-next:hover {
                background: var(--gradient-primary);
                transform: translateY(-50%) scale(1.1);
            }

            /* Dots */
            .carousel-section .carousel-dots {
                position: absolute;
                bottom: 30px;
                left: 50%;
                transform: translateX(-50%);
                display: flex;
                gap: 12px;
                z-index: 10;
            }

            .carousel-section .carousel-dot {
                width: 10px;
                height: 10px;
                border-radius: 50%;
                background: rgba(255, 255, 255, 0.4);
                border: none;
                cursor: pointer;
                transition: all 0.3s ease;
            }

            .carousel-section .carousel-dot.active {
                background: var(--primary-orange);
                width: 28px;
                border-radius: 5px;
            }

            .carousel-section .carousel-dot:hover {
                background: var(--primary-orange);
            }

            @media (max-width: 768px) {
                .carousel-section {
                    height: 60vh;
                    min-height: 400px;
                    margin-top: 70px;
                }
                .carousel-section .carousel-content h2 {
                    font-size: 1.5rem;
                }
                .carousel-section .carousel-content p {
                    font-size: 0.875rem;
                }
                .carousel-section .carousel-prev,
                .carousel-section .carousel-next {
                    width: 36px;
                    height: 36px;
                }
            }

            @media (max-width: 480px) {
                .carousel-section {
                    height: 50vh;
                    min-height: 350px;
                }
                .carousel-section .carousel-content h2 {
                    font-size: 1.2rem;
                }
                .carousel-section .carousel-btn {
                    padding: 0.6rem 1.2rem;
                    font-size: 0.8rem;
                }
            }

            /* ==================== HERO SECTION ==================== */
            .hero {
                min-height: 100vh;
                background: linear-gradient(135deg, var(--primary-dark) 0%, #2D2D2D 100%);
                position: relative;
                overflow: hidden;
                display: flex;
                align-items: center;
                padding-top: 80px;
            }

            .hero::before {
                content: '';
                position: absolute;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 200 200"><path fill="%23E85D04" fill-opacity="0.03" d="M0 0h200v200H0z"/><circle cx="100" cy="100" r="80" fill="%23E85D04" fill-opacity="0.03"/></svg>');
                background-size: 60px 60px;
                opacity: 0.3;
            }

            .hero .container {
                position: relative;
                z-index: 1;
                display: grid;
                grid-template-columns: 1fr;
                gap: 3rem;
                align-items: center;
            }

            @media (min-width: 1024px) {
                .hero .container {
                    grid-template-columns: 1fr 1fr;
                }
            }

            .hero-content h1 {
                font-size: 3rem;
                font-weight: 800;
                color: var(--primary-white);
                line-height: 1.2;
                margin-bottom: 1.5rem;
            }

            .hero-content h1 span {
                color: var(--primary-orange);
            }

            .hero-content p {
                font-size: 1.125rem;
                color: rgba(255, 255, 255, 0.8);
                line-height: 1.6;
                margin-bottom: 2rem;
            }

            @media (min-width: 768px) {
                .hero-content h1 {
                    font-size: 3.5rem;
                }
                .hero-content p {
                    font-size: 1.25rem;
                }
            }

            @media (min-width: 1024px) {
                .hero-content h1 {
                    font-size: 4rem;
                }
            }

            .hero-buttons {
                display: flex;
                gap: 1rem;
                flex-wrap: wrap;
            }

            .btn-primary {
                display: inline-block;
                padding: 0.875rem 2rem;
                background: var(--gradient-primary);
                color: var(--primary-white);
                text-decoration: none;
                font-weight: 600;
                border-radius: 12px;
                transition: all 0.3s ease;
                border: none;
                cursor: pointer;
            }

            .btn-primary:hover {
                background: var(--secondary-orange);
                transform: translateY(-3px);
                box-shadow: 0 10px 25px var(--shadow-orange);
            }

            .btn-outline {
                display: inline-block;
                padding: 0.875rem 2rem;
                background: transparent;
                color: var(--primary-white);
                text-decoration: none;
                font-weight: 600;
                border-radius: 12px;
                transition: all 0.3s ease;
                border: 2px solid var(--primary-orange);
            }

            .btn-outline:hover {
                background: var(--primary-orange);
                color: var(--primary-white);
                transform: translateY(-3px);
                box-shadow: 0 10px 25px var(--shadow-orange);
            }

            .hero-stats {
                display: grid;
                grid-template-columns: repeat(3, 1fr);
                gap: 1rem;
                margin-top: 2rem;
            }

            .hero-stat {
                text-align: center;
                padding: 1rem;
            }

            .hero-stat h3 {
                font-size: 1.875rem;
                font-weight: 800;
                color: var(--primary-orange);
            }

            .hero-stat p {
                font-size: 0.875rem;
                color: rgba(255, 255, 255, 0.7);
                margin-bottom: 0;
            }

            .hero-image {
                position: relative;
                display: flex;
                justify-content: center;
                align-items: center;
            }

            .hero-image svg {
                width: 100%;
                max-width: 450px;
                animation: float 3s ease-in-out infinite;
            }

            @keyframes float {
                0%, 100% { transform: translateY(0); }
                50% { transform: translateY(-20px); }
            }

            /* ==================== FEATURES SECTION ==================== */
            .features {
                padding: 5rem 0;
                background: var(--gray-light);
            }

            .section-header {
                text-align: center;
                margin-bottom: 3rem;
            }

            .section-header h2 {
                font-size: 2.25rem;
                font-weight: 700;
                color: var(--primary-dark);
                margin-bottom: 1rem;
            }

            .section-header h2 span {
                color: var(--primary-orange);
            }

            .section-header p {
                font-size: 1rem;
                color: var(--gray-medium);
                max-width: 600px;
                margin: 0 auto;
            }

            .features-grid {
                display: grid;
                grid-template-columns: 1fr;
                gap: 2rem;
            }

            @media (min-width: 768px) {
                .features-grid {
                    grid-template-columns: repeat(2, 1fr);
                }
            }

            @media (min-width: 1024px) {
                .features-grid {
                    grid-template-columns: repeat(4, 1fr);
                }
            }

            .feature-card {
                background: var(--primary-white);
                padding: 2rem;
                border-radius: 20px;
                text-align: center;
                transition: all 0.3s ease;
                box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
                border: 1px solid rgba(232, 93, 4, 0.05);
            }

            .feature-card:hover {
                transform: translateY(-10px);
                box-shadow: 0 20px 30px var(--shadow-orange);
                border-color: var(--primary-orange);
            }

            .feature-icon {
                width: 70px;
                height: 70px;
                background: rgba(232, 93, 4, 0.1);
                border-radius: 20px;
                display: flex;
                align-items: center;
                justify-content: center;
                margin: 0 auto 1.5rem;
            }

            .feature-icon svg {
                width: 35px;
                height: 35px;
                color: var(--primary-orange);
            }

            .feature-card h3 {
                font-size: 1.25rem;
                font-weight: 600;
                margin-bottom: 0.75rem;
                color: var(--primary-dark);
            }

            .feature-card p {
                font-size: 0.875rem;
                color: var(--gray-medium);
                line-height: 1.5;
            }

            /* ==================== ABOUT SECTION ==================== */
            .about {
                padding: 5rem 0;
                background: var(--primary-white);
            }

            .about-grid {
                display: grid;
                grid-template-columns: 1fr;
                gap: 3rem;
                align-items: center;
            }

            @media (min-width: 1024px) {
                .about-grid {
                    grid-template-columns: 1fr 1fr;
                }
            }

            .about-image {
                position: relative;
            }

            .about-image svg {
                width: 100%;
                border-radius: 30px;
                box-shadow: 0 20px 30px rgba(0, 0, 0, 0.1);
            }

            .about-image::before {
                content: '';
                position: absolute;
                top: -20px;
                left: -20px;
                width: 100px;
                height: 100px;
                background: var(--gradient-primary);
                border-radius: 30px;
                z-index: -1;
                opacity: 0.2;
            }

            .about-content h2 {
                font-size: 2rem;
                font-weight: 700;
                margin-bottom: 1.5rem;
                color: var(--primary-dark);
            }

            .about-content h2 span {
                color: var(--primary-orange);
            }

            .about-content p {
                font-size: 1rem;
                color: var(--gray-medium);
                line-height: 1.6;
                margin-bottom: 1rem;
            }

            .stats-grid {
                display: grid;
                grid-template-columns: repeat(3, 1fr);
                gap: 1rem;
                margin-top: 2rem;
            }

            .stat-item {
                text-align: center;
            }

            .stat-item h4 {
                font-size: 2rem;
                font-weight: 800;
                color: var(--primary-orange);
            }

            .stat-item p {
                font-size: 0.875rem;
                color: var(--gray-dark);
                margin-bottom: 0;
            }

            /* ==================== PRODUCTS SECTION ==================== */
            .products {
                padding: 5rem 0;
                background: var(--gray-light);
            }

            .products-grid {
                display: grid;
                grid-template-columns: 1fr;
                gap: 2rem;
            }

            @media (min-width: 768px) {
                .products-grid {
                    grid-template-columns: repeat(2, 1fr);
                }
            }

            @media (min-width: 1024px) {
                .products-grid {
                    grid-template-columns: repeat(3, 1fr);
                }
            }

            .product-card {
                background: var(--primary-white);
                border-radius: 20px;
                overflow: hidden;
                transition: all 0.3s ease;
                box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
                border: 1px solid rgba(232, 93, 4, 0.05);
            }

            .product-card:hover {
                transform: translateY(-5px);
                box-shadow: 0 20px 30px var(--shadow-orange);
                border-color: var(--primary-orange);
            }

            .product-image {
                height: 220px;
                background: linear-gradient(135deg, var(--primary-dark), #3D3D3D);
                display: flex;
                align-items: center;
                justify-content: center;
            }

            .product-image svg {
                width: 80px;
                height: 80px;
                color: var(--primary-orange);
            }

            .product-info {
                padding: 1.5rem;
            }

            .product-info h3 {
                font-size: 1.25rem;
                font-weight: 600;
                margin-bottom: 0.5rem;
                color: var(--primary-dark);
            }

            .product-info p {
                font-size: 0.875rem;
                color: var(--gray-medium);
                margin-bottom: 1rem;
            }

            .product-link {
                color: var(--primary-orange);
                text-decoration: none;
                font-weight: 600;
                display: inline-flex;
                align-items: center;
                gap: 0.5rem;
                transition: gap 0.3s ease;
            }

            .product-link:hover {
                gap: 0.75rem;
                color: var(--secondary-orange);
            }

            /* ==================== CTA SECTION ==================== */
            .cta {
                padding: 5rem 0;
                background: linear-gradient(135deg, var(--primary-dark) 0%, #2D2D2D 100%);
                text-align: center;
                position: relative;
                overflow: hidden;
            }

            .cta::before {
                content: '';
                position: absolute;
                top: -50%;
                right: -50%;
                width: 100%;
                height: 100%;
                background: radial-gradient(circle, rgba(232, 93, 4, 0.05) 0%, transparent 70%);
            }

            .cta h2 {
                font-size: 2rem;
                font-weight: 700;
                color: var(--primary-white);
                margin-bottom: 1rem;
                position: relative;
            }

            .cta h2 span {
                color: var(--primary-orange);
            }

            .cta p {
                font-size: 1.125rem;
                color: rgba(255, 255, 255, 0.8);
                margin-bottom: 2rem;
                max-width: 600px;
                margin-left: auto;
                margin-right: auto;
                position: relative;
            }

            .cta .btn-primary {
                position: relative;
            }

            /* ==================== CONTACT SECTION ==================== */
            .contact {
                padding: 5rem 0;
                background: var(--primary-white);
            }

            .contact-grid {
                display: grid;
                grid-template-columns: 1fr;
                gap: 3rem;
            }

            @media (min-width: 1024px) {
                .contact-grid {
                    grid-template-columns: 1fr 1fr;
                }
            }

            .contact-info {
                display: flex;
                flex-direction: column;
                gap: 1.5rem;
            }

            .contact-item {
                display: flex;
                align-items: center;
                gap: 1rem;
            }

            .contact-icon {
                width: 50px;
                height: 50px;
                background: rgba(232, 93, 4, 0.1);
                border-radius: 12px;
                display: flex;
                align-items: center;
                justify-content: center;
            }

            .contact-icon svg {
                width: 24px;
                height: 24px;
                color: var(--primary-orange);
            }

            .contact-text h4 {
                font-size: 1rem;
                font-weight: 600;
                margin-bottom: 0.25rem;
                color: var(--primary-dark);
            }

            .contact-text p {
                font-size: 0.875rem;
                color: var(--gray-medium);
            }

            .contact-form {
                background: var(--gray-light);
                padding: 2rem;
                border-radius: 24px;
                border: 1px solid rgba(232, 93, 4, 0.05);
            }

            .form-group {
                margin-bottom: 1.25rem;
            }

            .form-group input,
            .form-group textarea {
                width: 100%;
                padding: 0.875rem 1rem;
                border: 1px solid #E5E7EB;
                border-radius: 12px;
                font-family: inherit;
                transition: all 0.3s ease;
                background: var(--primary-white);
            }

            .form-group input:focus,
            .form-group textarea:focus {
                outline: none;
                border-color: var(--primary-orange);
                box-shadow: 0 0 0 3px rgba(232, 93, 4, 0.1);
            }

            .contact-form .btn-primary {
                width: 100%;
            }

            /* ==================== FOOTER ==================== */
            .footer {
                background: var(--primary-dark);
                color: var(--primary-white);
                padding: 3rem 0 1.5rem;
            }

            .footer-grid {
                display: grid;
                grid-template-columns: 1fr;
                gap: 2rem;
                margin-bottom: 2rem;
            }

            @media (min-width: 768px) {
                .footer-grid {
                    grid-template-columns: repeat(2, 1fr);
                }
            }

            @media (min-width: 1024px) {
                .footer-grid {
                    grid-template-columns: repeat(4, 1fr);
                }
            }

            .footer-col h3 {
                font-size: 1.125rem;
                font-weight: 700;
                margin-bottom: 1rem;
                color: var(--primary-orange);
            }

            .footer-col p {
                font-size: 0.875rem;
                color: #9CA3AF;
                line-height: 1.5;
            }

            .footer-links {
                list-style: none;
            }

            .footer-links li {
                margin-bottom: 0.5rem;
            }

            .footer-links a {
                color: #9CA3AF;
                text-decoration: none;
                font-size: 0.875rem;
                transition: color 0.3s ease;
            }

            .footer-links a:hover {
                color: var(--primary-orange);
            }

            .social-links {
                display: flex;
                gap: 1rem;
            }

            .social-links a {
                width: 36px;
                height: 36px;
                background: rgba(255, 255, 255, 0.05);
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
                transition: all 0.3s ease;
            }

            .social-links a:hover {
                background: var(--gradient-primary);
                transform: translateY(-3px);
                box-shadow: 0 8px 25px var(--shadow-orange);
            }

            .social-links svg {
                width: 18px;
                height: 18px;
                color: var(--primary-white);
            }

            .footer-bottom {
                text-align: center;
                padding-top: 1.5rem;
                border-top: 1px solid rgba(255, 255, 255, 0.05);
                color: #9CA3AF;
                font-size: 0.75rem;
            }

            /* ==================== SCROLL TO TOP ==================== */
            .scroll-top {
                position: fixed;
                bottom: 30px;
                right: 30px;
                width: 45px;
                height: 45px;
                background: var(--gradient-primary);
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
                box-shadow: 0 4px 15px var(--shadow-orange);
            }

            .scroll-top.show {
                opacity: 1;
                visibility: visible;
            }

            .scroll-top:hover {
                background: var(--secondary-orange);
                transform: translateY(-5px);
                box-shadow: 0 8px 25px var(--shadow-orange);
            }

            /* ==================== ANIMATIONS ==================== */
            @keyframes fadeInUp {
                from {
                    opacity: 0;
                    transform: translateY(30px);
                }
                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }

            .fade-in {
                animation: fadeInUp 0.6s ease forwards;
            }

            /* Carrito icono */
            .carrito-icon {
                display: inline-flex;
                align-items: center;
                transition: all 0.3s ease;
                position: relative;
                color: var(--primary-white);
                text-decoration: none;
                margin-left: 1rem;
            }

            .carrito-icon:hover {
                color: var(--primary-orange);
                transform: scale(1.05);
            }

            .carrito-icon .mdi {
                font-size: 24px;
            }

            #carrito-contador {
                position: absolute;
                top: -8px;
                right: -12px;
                background: var(--primary-orange);
                color: white;
                font-size: 10px;
                font-weight: bold;
                min-width: 18px;
                height: 18px;
                border-radius: 50%;
                display: none;
                align-items: center;
                justify-content: center;
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
                    <a href="#home" class="active">Inicio</a>
                    <a href="#about">Nosotros</a>
                    <a href="#products">Productos</a>
                    <a href="#contact">Contacto</a>
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
                    
                    <!-- Icono del carrito -->
                    <a href="" class="carrito-icon">
                        <i class="mdi mdi-cart-outline"></i>
                        <span id="carrito-contador">0</span>
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
                <a href="#home">Inicio</a>
                <a href="#about">Nosotros</a>
                <a href="#products">Productos</a>
                <a href="#contact">Contacto</a>
            </div>
            <div class="mobile-auth">
                @if (Route::has('login'))
                    @auth
                        <a href="{{ url('/dashboard') }}" class="btn-dashboard" style="background: var(--gradient-primary);">Dashboard</a>
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

        <!-- ==================== CARRUSEL SECTION ==================== -->
        <section class="carousel-section">
            <div class="carousel-container">
                <div class="carousel-slides">
                    <!-- Slide 1 -->
                    <div class="carousel-slide active" data-slide="0">
                        <img src="{{ asset('img/fondo1.jpg') }}" alt="Cerámicos AMERI">
                        <div class="carousel-overlay"></div>
                        <div class="carousel-content">
                            <h2>Excelencia en <span>Cerámicos</span></h2>
                            <p>Calidad y diseño para tus espacios</p>
                            <a href="#products" class="carousel-btn btn-scroll">Descubrir productos</a>
                        </div>
                    </div>
                    <!-- Slide 2 -->
                    <div class="carousel-slide" data-slide="1">
                        <img src="{{ asset('img/fondo2.png') }}" alt="Cemento Cola AMERI">
                        <div class="carousel-overlay"></div>
                        <div class="carousel-content">
                            <h2><span>Cemento Cola</span> de Alta Resistencia</h2>
                            <p>Adhesivos profesionales para todo tipo de instalaciones</p>
                            <a href="#products" class="carousel-btn btn-scroll">Ver más</a>
                        </div>
                    </div>
                </div>

                <!-- Controles -->
                <button class="carousel-prev" id="carousel-prev">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M15 18l-6-6 6-6"/>
                    </svg>
                </button>
                <button class="carousel-next" id="carousel-next">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M9 18l6-6-6-6"/>
                    </svg>
                </button>

                <!-- Dots -->
                <div class="carousel-dots">
                    <button class="carousel-dot active" data-slide="0"></button>
                    <button class="carousel-dot" data-slide="1"></button>
                </div>
            </div>
        </section>

        <!-- ==================== HERO SECTION ==================== -->
        <section id="home" class="hero">
            <div class="container">
                <div class="hero-content">
                    <h1>Cerámicos y <span>Cemento Cola</span> de Calidad Superior</h1>
                    <p>AMERI es tu aliado en construcción y acabados. Ofrecemos porcelanatos, cerámicos y adhesivos profesionales que garantizan durabilidad y estética.</p>
                    <div class="hero-buttons">
                        <a href="#contact" class="btn-primary">Solicitar Cotización</a>
                        <a href="#products" class="btn-outline">Ver Productos</a>
                    </div>
                    <div class="hero-stats">
                        <div class="hero-stat">
                            <h3>500+</h3>
                            <p>Proyectos Realizados</p>
                        </div>
                        <div class="hero-stat">
                            <h3>20+</h3>
                            <p>Años de Experiencia</p>
                        </div>
                        <div class="hero-stat">
                            <h3>98%</h3>
                            <p>Satisfacción del Cliente</p>
                        </div>
                    </div>
                </div>
                <div class="hero-image">
                    <svg viewBox="0 0 400 400" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <rect x="60" y="60" width="280" height="280" rx="30" fill="rgba(232,93,4,0.08)"/>
                        <rect x="100" y="100" width="200" height="200" rx="20" stroke="#E85D04" stroke-width="2" fill="rgba(232,93,4,0.05)"/>
                        <rect x="140" y="140" width="120" height="120" rx="12" fill="rgba(232,93,4,0.1)"/>
                        <path d="M200 170L230 200L200 230L170 200L200 170Z" fill="#E85D04" opacity="0.3"/>
                        <circle cx="200" cy="200" r="50" stroke="#E85D04" stroke-width="2" stroke-dasharray="8 8" fill="none"/>
                        <text x="200" y="205" text-anchor="middle" fill="#E85D04" font-size="18" font-weight="bold">AMERI</text>
                    </svg>
                </div>
            </div>
        </section>

        <!-- ==================== FEATURES SECTION ==================== -->
        <section class="features">
            <div class="container">
                <div class="section-header">
                    <h2>¿Por qué <span>AMERI</span>?</h2>
                    <p>Calidad, innovación y compromiso en cada producto</p>
                </div>
                <div class="features-grid">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <h3>Calidad Premium</h3>
                        <p>Productos fabricados bajo los más altos estándares de calidad.</p>
                    </div>
                    <div class="feature-card">
                        <div class="feature-icon">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21l-7-5-7 5V5a2 2 0 012-2h10a2 2 0 012 2v16z"/>
                            </svg>
                        </div>
                        <h3>Durabilidad</h3>
                        <p>Materiales resistentes que garantizan larga vida útil.</p>
                    </div>
                    <div class="feature-card">
                        <div class="feature-icon">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/>
                            </svg>
                        </div>
                        <h3>Diseño Moderno</h3>
                        <p>Amplia variedad de estilos y acabados para cada proyecto.</p>
                    </div>
                    <div class="feature-card">
                        <div class="feature-icon">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                        </div>
                        <h3>Atención Personalizada</h3>
                        <p>Asesoramiento técnico para elegir el producto ideal.</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- ==================== ABOUT SECTION ==================== -->
        <section id="about" class="about">
            <div class="container">
                <div class="about-grid">
                    <div class="about-image">
                        <svg width="100%" height="320" viewBox="0 0 400 320" fill="none" style="background: linear-gradient(135deg, #1A1A1A, #2D2D2D); border-radius: 30px;">
                            <rect width="400" height="320" rx="30" fill="#1A1A1A" opacity="0.95"/>
                            <rect x="40" y="50" width="320" height="220" rx="20" fill="rgba(232,93,4,0.05)"/>
                            <text x="200" y="130" text-anchor="middle" fill="white" font-size="32" font-weight="bold">AMERI</text>
                            <text x="200" y="165" text-anchor="middle" fill="#E85D04" font-size="16">Cerámicos &amp; Cemento Cola</text>
                            <line x1="120" y1="185" x2="280" y2="185" stroke="#E85D04" stroke-width="1" opacity="0.3"/>
                            <text x="200" y="215" text-anchor="middle" fill="#9CA3AF" font-size="13">Calidad que construye futuro</text>
                            <rect x="160" y="235" width="80" height="4" rx="2" fill="#E85D04" opacity="0.6"/>
                            <defs>
                                <linearGradient id="grad" x1="0" y1="0" x2="400" y2="320" gradientUnits="userSpaceOnUse">
                                    <stop stop-color="#1A1A1A"/>
                                    <stop offset="1" stop-color="#2D2D2D"/>
                                </linearGradient>
                            </defs>
                        </svg>
                    </div>
                    <div class="about-content">
                        <h2>Sobre <span>AMERI</span></h2>
                        <p><strong>AMERI</strong> es una empresa boliviana con más de 20 años de trayectoria en el sector de la construcción y acabados. Nos especializamos en la comercialización de <strong>cerámicos, porcelanatos y cemento cola</strong> de alta calidad.</p>
                        <p>Trabajamos con las mejores marcas del mercado y ofrecemos productos que cumplen con estándares internacionales. Nuestro compromiso es brindar soluciones duraderas y estéticas para cada proyecto, desde viviendas hasta grandes obras comerciales.</p>
                        <p>Contamos con un equipo de expertos que te asesorarán en la elección del producto adecuado para tus necesidades.</p>
                        <div class="stats-grid">
                            <div class="stat-item">
                                <h4>20+</h4>
                                <p>Años de Trayectoria</p>
                            </div>
                            <div class="stat-item">
                                <h4>500+</h4>
                                <p>Proyectos</p>
                            </div>
                            <div class="stat-item">
                                <h4>50+</h4>
                                <p>Marcas Aliadas</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- ==================== PRODUCTS SECTION ==================== -->
        <section id="products" class="products">
            <div class="container">
                <div class="section-header">
                    <h2>Nuestros <span>Productos</span></h2>
                    <p>Soluciones en cerámicos y adhesivos para cada necesidad</p>
                </div>
                <div class="products-grid">
                    <div class="product-card">
                        <div class="product-image">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h12a2 2 0 012 2v12a2 2 0 01-2 2H6a2 2 0 01-2-2V6z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h8M8 14h4"/>
                            </svg>
                        </div>
                        <div class="product-info">
                            <h3>Cerámicos y Porcelanatos</h3>
                            <p>Amplia variedad de diseños, texturas y formatos para pisos y paredes.</p>
                            <a href="" class="product-link">Ver productos →</a>
                        </div>
                    </div>
                    <div class="product-card">
                        <div class="product-image">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                            </svg>
                        </div>
                        <div class="product-info">
                            <h3>Cemento Cola</h3>
                            <p>Adhesivos de alta resistencia para todo tipo de instalaciones.</p>
                            <a href="" class="product-link">Ver productos →</a>
                        </div>
                    </div>
                    <div class="product-card">
                        <div class="product-image">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/>
                            </svg>
                        </div>
                        <div class="product-info">
                            <h3>Adhesivos Especiales</h3>
                            <p>Soluciones específicas para cerámica, porcelanato y piedra.</p>
                            <a href="" class="product-link">Ver productos →</a>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- ==================== CTA SECTION ==================== -->
        <section class="cta">
            <div class="container">
                <h2>¿Listo para <span>transformar</span> tu espacio?</h2>
                <p>Contáctanos y descubre la calidad y el diseño que AMERI tiene para tu proyecto.</p>
                <a href="#contact" class="btn-primary">Solicitar Información</a>
            </div>
        </section>

        <!-- ==================== CONTACT SECTION ==================== -->
        <section id="contact" class="contact">
            <div class="container">
                <div class="section-header">
                    <h2>Contáctanos</h2>
                    <p>Estamos aquí para ayudarte</p>
                </div>
                <div class="contact-grid">
                    <div class="contact-info">
                        <div class="contact-item">
                            <div class="contact-icon">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                            </div>
                            <div class="contact-text">
                                <h4>Dirección</h4>
                                <p>Av. Principal #123, Zona Industrial, La Paz - Bolivia</p>
                            </div>
                        </div>
                        <div class="contact-item">
                            <div class="contact-icon">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                </svg>
                            </div>
                            <div class="contact-text">
                                <h4>Teléfono</h4>
                                <p>+591 77743260</p>
                            </div>
                        </div>
                        <div class="contact-item">
                            <div class="contact-icon">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                </svg>
                            </div>
                            <div class="contact-text">
                                <h4>Email</h4>
                                <p>info@ameri.com.bo</p>
                            </div>
                        </div>
                    </div>
                    <form class="contact-form">
                        <div class="form-group">
                            <input type="text" placeholder="Nombre completo">
                        </div>
                        <div class="form-group">
                            <input type="email" placeholder="Correo electrónico">
                        </div>
                        <div class="form-group">
                            <input type="tel" placeholder="Teléfono">
                        </div>
                        <div class="form-group">
                            <textarea rows="4" placeholder="Mensaje"></textarea>
                        </div>
                        <button type="submit" class="btn-primary">Enviar Mensaje</button>
                    </form>
                </div>
            </div>
        </section>

        <!-- ==================== FOOTER ==================== -->
        <footer class="footer">
            <div class="container">
                <div class="footer-grid">
                    <div class="footer-col">
                        <h3>AMERI</h3>
                        <p>Cerámicos y Cemento Cola de calidad superior. Transformamos espacios con durabilidad y diseño.</p>
                    </div>
                    <div class="footer-col">
                        <h3>Enlaces Rápidos</h3>
                        <ul class="footer-links">
                            <li><a href="#home">Inicio</a></li>
                            <li><a href="#about">Nosotros</a></li>
                            <li><a href="#products">Productos</a></li>
                            <li><a href="#contact">Contacto</a></li>
                        </ul>
                    </div>
                    <div class="footer-col">
                        <h3>Horario</h3>
                        <p>Lunes a Viernes: 8:00 - 18:00</p>
                        <p>Sábados: 9:00 - 13:00</p>
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
                    <p>&copy; {{ date('Y') }} AMERI - Cerámicos y Cemento Cola. Todos los derechos reservados.</p>
                </div>
            </div>
        </footer>

        <!-- ==================== SCROLL TO TOP ==================== -->
        <button id="scroll-top" class="scroll-top">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M5 10l7-7m0 0l7 7m-7-7v18"/>
            </svg>
        </button>

        <!-- ==================== JAVASCRIPT ==================== -->
        <script>
            (function() {
                // ==================== CARRUSEL ====================
                const slides = document.querySelectorAll('.carousel-section .carousel-slide');
                const dots = document.querySelectorAll('.carousel-section .carousel-dot');
                const prevBtn = document.getElementById('carousel-prev');
                const nextBtn = document.getElementById('carousel-next');
                
                let currentSlide = 0;
                let slideInterval;

                function showSlide(index) {
                    if (index < 0) index = slides.length - 1;
                    if (index >= slides.length) index = 0;
                    
                    slides.forEach(slide => slide.classList.remove('active'));
                    dots.forEach(dot => dot.classList.remove('active'));
                    
                    slides[index].classList.add('active');
                    dots[index].classList.add('active');
                    currentSlide = index;
                }

                function nextSlide() {
                    showSlide(currentSlide + 1);
                }

                function prevSlide() {
                    showSlide(currentSlide - 1);
                }

                function startAutoPlay() {
                    if (slideInterval) clearInterval(slideInterval);
                    slideInterval = setInterval(nextSlide, 5000);
                }

                if (prevBtn && nextBtn) {
                    prevBtn.addEventListener('click', () => {
                        prevSlide();
                        startAutoPlay();
                    });

                    nextBtn.addEventListener('click', () => {
                        nextSlide();
                        startAutoPlay();
                    });
                }

                dots.forEach((dot, index) => {
                    dot.addEventListener('click', () => {
                        showSlide(index);
                        startAutoPlay();
                    });
                });

                if (slides.length > 0) {
                    showSlide(0);
                    startAutoPlay();
                }

                const carouselContainer = document.querySelector('.carousel-container');
                if (carouselContainer) {
                    carouselContainer.addEventListener('mouseenter', () => {
                        if (slideInterval) clearInterval(slideInterval);
                    });
                    carouselContainer.addEventListener('mouseleave', () => {
                        startAutoPlay();
                    });
                }

                document.querySelectorAll('.btn-scroll').forEach(btn => {
                    btn.addEventListener('click', function(e) {
                        e.preventDefault();
                        const target = document.querySelector(this.getAttribute('href'));
                        if (target) {
                            target.scrollIntoView({ behavior: 'smooth' });
                        }
                    });
                });

                // ==================== MOBILE MENU ====================
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

                // ==================== NAVBAR SCROLL EFFECT ====================
                const navbar = document.getElementById('navbar');
                window.addEventListener('scroll', () => {
                    if (window.scrollY > 50) {
                        navbar.classList.add('scrolled');
                    } else {
                        navbar.classList.remove('scrolled');
                    }
                });

                // ==================== ACTIVE NAV LINK ====================
                const sections = document.querySelectorAll('section[id]');
                const navLinks = document.querySelectorAll('.nav-links a');

                function updateActiveLink() {
                    let current = '';
                    const scrollPos = window.scrollY + 100;

                    sections.forEach(section => {
                        const sectionTop = section.offsetTop;
                        const sectionHeight = section.clientHeight;
                        if (scrollPos >= sectionTop && scrollPos < sectionTop + sectionHeight) {
                            current = section.getAttribute('id');
                        }
                    });

                    navLinks.forEach(link => {
                        link.classList.remove('active');
                        if (link.getAttribute('href') === `#${current}`) {
                            link.classList.add('active');
                        }
                    });
                }

                window.addEventListener('scroll', updateActiveLink);
                window.addEventListener('load', updateActiveLink);

                // ==================== SCROLL TO TOP ====================
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

                // ==================== SMOOTH SCROLL ====================
                document.querySelectorAll('a[href^="#"]').forEach(anchor => {
                    anchor.addEventListener('click', function(e) {
                        const target = document.querySelector(this.getAttribute('href'));
                        if (target) {
                            e.preventDefault();
                            target.scrollIntoView({ behavior: 'smooth', block: 'start' });
                        }
                    });
                });
            })();

            // ==================== CARRITO CONTADOR ====================
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

            document.addEventListener('DOMContentLoaded', function() {
                actualizarContadorCarrito();
            });
        </script>
    </body>
</html>
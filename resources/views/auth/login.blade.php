<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'AMERI') }} - Iniciar Sesión</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700,800" rel="stylesheet" />

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
            --error-red: #EF4444;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Instrument Sans', sans-serif;
            background: linear-gradient(135deg, var(--primary-blue) 0%, #1E3A5F 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1.5rem;
            position: relative;
            overflow-x: hidden;
        }

        /* Background decoration */
        body::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 200 200"><circle cx="100" cy="100" r="80" fill="%2310B981" fill-opacity="0.03"/></svg>');
            background-size: 60px 60px;
            pointer-events: none;
        }

        body::after {
            content: '';
            position: absolute;
            top: -50%;
            right: -20%;
            width: 500px;
            height: 500px;
            background: radial-gradient(circle, rgba(16, 185, 129, 0.1) 0%, transparent 70%);
            border-radius: 50%;
            pointer-events: none;
        }

        /* ==================== LOGIN CARD ==================== */
        .login-container {
            width: 100%;
            max-width: 460px;
            position: relative;
            z-index: 1;
            animation: fadeInUp 0.6s ease-out;
        }

        .login-card {
            background: var(--primary-white);
            border-radius: 32px;
            padding: 2.5rem;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
            backdrop-filter: blur(10px);
        }

        /* Logo Section */
        .logo-section {
            text-align: center;
            margin-bottom: 2rem;
        }

        .logo {
            display: inline-block;
            margin-bottom: 1rem;
        }

        .logo img {
            height: 60px;
            width: auto;
            object-fit: contain;
        }

        .logo-section h2 {
            font-size: 1.75rem;
            font-weight: 700;
            color: var(--primary-black);
            margin-bottom: 0.5rem;
        }

        .logo-section p {
            font-size: 0.875rem;
            color: var(--gray-medium);
        }

        /* Form Styles */
        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-label {
            display: block;
            font-size: 0.875rem;
            font-weight: 600;
            color: var(--primary-black);
            margin-bottom: 0.5rem;
        }

        .form-label i {
            color: var(--primary-green);
            margin-right: 0.5rem;
        }

        .input-wrapper {
            position: relative;
        }

        .input-icon {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: var(--gray-medium);
            transition: color 0.3s ease;
        }

        .form-input {
            width: 100%;
            padding: 0.875rem 1rem 0.875rem 2.75rem;
            border: 1.5px solid #E5E7EB;
            border-radius: 16px;
            font-family: inherit;
            font-size: 0.95rem;
            transition: all 0.3s ease;
            background: var(--primary-white);
        }

        .form-input:focus {
            outline: none;
            border-color: var(--primary-green);
            box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.1);
        }

        .form-input:focus + .input-icon {
            color: var(--primary-green);
        }

        /* Error Styles */
        .input-error {
            border-color: var(--error-red) !important;
        }

        .error-message {
            font-size: 0.75rem;
            color: var(--error-red);
            margin-top: 0.5rem;
            display: flex;
            align-items: center;
            gap: 0.25rem;
        }

        /* Validation Errors Container */
        .validation-errors {
            background: #FEF2F2;
            border: 1px solid #FEE2E2;
            border-radius: 16px;
            padding: 1rem;
            margin-bottom: 1.5rem;
        }

        .validation-errors p {
            color: var(--error-red);
            font-size: 0.875rem;
            font-weight: 500;
            margin-bottom: 0.5rem;
        }

        .validation-errors ul {
            list-style: none;
            padding-left: 0;
        }

        .validation-errors li {
            color: var(--error-red);
            font-size: 0.8rem;
            margin-bottom: 0.25rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        /* Success Message */
        .success-message {
            background: #ECFDF5;
            border: 1px solid #D1FAE5;
            border-radius: 16px;
            padding: 1rem;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            color: var(--primary-green);
            font-size: 0.875rem;
        }

        /* Checkbox */
        .checkbox-wrapper {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            cursor: pointer;
        }

        .checkbox-wrapper input {
            width: 18px;
            height: 18px;
            accent-color: var(--primary-green);
            cursor: pointer;
        }

        .checkbox-wrapper span {
            font-size: 0.875rem;
            color: var(--gray-dark);
        }

        /* Buttons */
        .btn-login {
            width: 100%;
            padding: 0.875rem;
            background: var(--primary-green);
            color: var(--primary-white);
            border: none;
            border-radius: 16px;
            font-weight: 700;
            font-size: 1rem;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }

        .btn-login:hover {
            background: var(--secondary-green);
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(16, 185, 129, 0.3);
        }

        /* Links */
        .forgot-link {
            font-size: 0.875rem;
            color: var(--primary-green);
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .forgot-link:hover {
            color: var(--secondary-green);
            text-decoration: underline;
        }

        .register-link {
            text-align: center;
            margin-top: 1.5rem;
            padding-top: 1.5rem;
            border-top: 1px solid #E5E7EB;
        }

        .register-link p {
            font-size: 0.875rem;
            color: var(--gray-medium);
        }

        .register-link a {
            color: var(--primary-green);
            text-decoration: none;
            font-weight: 600;
            transition: color 0.3s ease;
        }

        .register-link a:hover {
            color: var(--secondary-green);
            text-decoration: underline;
        }

        /* Divider */
        .divider {
            text-align: center;
            margin: 1.5rem 0;
            position: relative;
        }

        .divider::before,
        .divider::after {
            content: '';
            position: absolute;
            top: 50%;
            width: calc(50% - 30px);
            height: 1px;
            background: #E5E7EB;
        }

        .divider::before {
            left: 0;
        }

        .divider::after {
            right: 0;
        }

        .divider span {
            background: var(--primary-white);
            padding: 0 1rem;
            font-size: 0.75rem;
            color: var(--gray-medium);
        }

        /* Back to home */
        .back-home {
            text-align: center;
            margin-top: 1.5rem;
        }

        .back-home a {
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
            font-size: 0.875rem;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.3s ease;
        }

        .back-home a:hover {
            color: var(--primary-green);
        }

        /* Animation */
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

        /* Responsive */
        @media (max-width: 640px) {
            .login-card {
                padding: 1.75rem;
            }
            .logo img {
                height: 45px;
            }
            .logo-section h2 {
                font-size: 1.5rem;
            }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-card">
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
                    <p>Por favor corrige los siguientes errores:</p>
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
                        <i>📧</i> Correo Electrónico
                    </label>
                    <div class="input-wrapper">
                        <span class="input-icon">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/>
                                <polyline points="22,6 12,13 2,6"/>
                            </svg>
                        </span>
                        <input type="email" id="email" name="email" class="form-input @error('email') input-error @enderror" 
                               value="{{ old('email') }}" required autofocus autocomplete="username" placeholder="ejemplo@empresa.com">
                    </div>
                    @error('email')
                        <div class="error-message">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
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
                        <i>🔒</i> Contraseña
                    </label>
                    <div class="input-wrapper">
                        <span class="input-icon">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/>
                                <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                            </svg>
                        </span>
                        <input type="password" id="password" name="password" class="form-input @error('password') input-error @enderror" 
                               required autocomplete="current-password" placeholder="••••••••">
                    </div>
                    @error('password')
                        <div class="error-message">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <circle cx="12" cy="12" r="10"/>
                                <line x1="12" y1="8" x2="12" y2="12"/>
                                <line x1="12" y1="16" x2="12.01" y2="16"/>
                            </svg>
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <div class="form-group">
                    <div style="display: flex; justify-content: space-between; align-items: center;">
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
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
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
                <p>
                    <a href="{{ route('register') }}">
                        Crear una cuenta nueva
                    </a>
                </p>
            </div>
        </div>

        <div class="back-home">
            <a href="{{ url('/') }}">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M3 12h18"/>
                    <path d="M3 12l6-6"/>
                    <path d="M3 12l6 6"/>
                </svg>
                Volver al inicio
            </a>
        </div>
    </div>
</body>
</html>
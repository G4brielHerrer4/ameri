<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'AMERI') }} - Crear Cuenta</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700,800" rel="stylesheet" />
    <!-- Material Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@mdi/font@7.2.96/css/materialdesignicons.min.css">

    <style>
        /* ==================== VARIABLES - AMERI ==================== */
        :root {
            --primary-orange: #E85D04;
            --primary-dark: #1A1A1A;
            --primary-brown: #5C3A21;
            --primary-white: #FFFFFF;
            --secondary-orange: #F48C06;
            --tertiary-orange: #FFBA08;
            --gray-light: #F8F6F3;
            --gray-medium: #8B8B8B;
            --gray-dark: #2D2D2D;
            --error-red: #EF4444;
            --success-green: #22C55E;
            --gradient-primary: linear-gradient(135deg, #E85D04 0%, #DC2F02 100%);
            --gradient-hover: linear-gradient(135deg, #F48C06 0%, #E85D04 100%);
            --shadow-orange: rgba(232, 93, 4, 0.3);
            --shadow-dark: rgba(0, 0, 0, 0.25);
            --border-radius: 20px;
            --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        /* ==================== RESET & BASE ==================== */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Instrument Sans', sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1.5rem;
            position: relative;
            overflow-x: hidden;
            background: var(--primary-dark);
        }

        /* ==================== ANIMATED BACKGROUND ==================== */
        .bg-pattern {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            z-index: 0;
            overflow: hidden;
        }

        .bg-pattern .gradient-1 {
            position: absolute;
            top: -30%;
            right: -20%;
            width: 600px;
            height: 600px;
            background: radial-gradient(circle, rgba(232, 93, 4, 0.12) 0%, transparent 70%);
            border-radius: 50%;
            animation: floatBg 15s ease-in-out infinite;
        }

        .bg-pattern .gradient-2 {
            position: absolute;
            bottom: -30%;
            left: -20%;
            width: 500px;
            height: 500px;
            background: radial-gradient(circle, rgba(232, 93, 4, 0.08) 0%, transparent 70%);
            border-radius: 50%;
            animation: floatBg 20s ease-in-out infinite reverse;
        }

        .bg-pattern .dots {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-image: radial-gradient(rgba(232, 93, 4, 0.04) 1px, transparent 1px);
            background-size: 30px 30px;
        }

        @keyframes floatBg {
            0%, 100% { transform: translate(0, 0) scale(1); }
            50% { transform: translate(30px, -30px) scale(1.1); }
        }

        /* ==================== REGISTER CONTAINER ==================== */
        .register-container {
            width: 100%;
            max-width: 540px;
            position: relative;
            z-index: 1;
            animation: fadeInUp 0.8s cubic-bezier(0.4, 0, 0.2, 1) both;
        }

        /* ==================== REGISTER CARD ==================== */
        .register-card {
            background: var(--primary-white);
            border-radius: var(--border-radius);
            padding: 2.5rem 2.5rem 2rem;
            box-shadow: 
                0 25px 60px -12px rgba(0, 0, 0, 0.5),
                inset 0 1px 0 rgba(255, 255, 255, 0.1);
            position: relative;
            overflow: hidden;
        }

        .register-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: var(--gradient-primary);
        }

        /* ==================== LOGO SECTION ==================== */
        .logo-section {
            text-align: center;
            margin-bottom: 2rem;
        }

        .logo {
            display: inline-block;
            margin-bottom: 0.75rem;
            position: relative;
        }

        .logo img {
            height: 60px;
            width: auto;
            object-fit: contain;
            transition: var(--transition);
        }

        .logo img:hover {
            transform: scale(1.05);
        }

        .logo-section h2 {
            font-size: 1.75rem;
            font-weight: 800;
            color: var(--primary-dark);
            letter-spacing: -0.5px;
        }

        .logo-section h2 span {
            background: var(--gradient-primary);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .logo-section p {
            font-size: 0.9rem;
            color: var(--gray-medium);
            margin-top: 0.25rem;
        }

        .logo-section .divider {
            width: 60px;
            height: 3px;
            background: var(--gradient-primary);
            border-radius: 10px;
            margin: 0.75rem auto 0;
        }

        /* ==================== VALIDATION ERRORS ==================== */
        .validation-errors {
            background: #FEF2F2;
            border: 1px solid #FEE2E2;
            border-radius: 16px;
            padding: 1rem 1.25rem;
            margin-bottom: 1.5rem;
            animation: shake 0.5s ease;
        }

        .validation-errors p {
            color: var(--error-red);
            font-size: 0.8rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .validation-errors ul {
            list-style: none;
            padding-left: 0;
        }

        .validation-errors li {
            color: var(--error-red);
            font-size: 0.75rem;
            margin-bottom: 0.25rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.25rem 0;
        }

        .validation-errors li::before {
            content: '✕';
            font-weight: 700;
            color: var(--error-red);
        }

        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            20% { transform: translateX(-8px); }
            40% { transform: translateX(8px); }
            60% { transform: translateX(-4px); }
            80% { transform: translateX(4px); }
        }

        /* ==================== FORM ==================== */
        .form-group {
            margin-bottom: 1.25rem;
        }

        .form-label {
            display: block;
            font-size: 0.8rem;
            font-weight: 600;
            color: var(--primary-dark);
            margin-bottom: 0.5rem;
            letter-spacing: 0.3px;
            text-transform: uppercase;
        }

        .form-label .mdi {
            color: var(--primary-orange);
            margin-right: 0.5rem;
            font-size: 1rem;
        }

        .form-label .required {
            color: var(--error-red);
            margin-left: 0.25rem;
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
            transition: var(--transition);
            pointer-events: none;
            font-size: 1.2rem;
        }

        .form-input {
            width: 100%;
            padding: 0.875rem 1rem 0.875rem 3rem;
            border: 2px solid #E5E7EB;
            border-radius: 14px;
            font-family: inherit;
            font-size: 0.95rem;
            transition: var(--transition);
            background: var(--primary-white);
            color: var(--primary-dark);
        }

        .form-input::placeholder {
            color: #B0B0B0;
        }

        .form-input:focus {
            outline: none;
            border-color: var(--primary-orange);
            box-shadow: 0 0 0 4px rgba(232, 93, 4, 0.1);
        }

        .form-input:focus + .input-icon,
        .form-input:focus ~ .input-icon {
            color: var(--primary-orange);
        }

        .form-input.input-error {
            border-color: var(--error-red);
        }

        .form-input.input-error:focus {
            box-shadow: 0 0 0 4px rgba(239, 68, 68, 0.1);
        }

        .form-input:disabled {
            opacity: 0.6;
            cursor: not-allowed;
        }

        /* ==================== SELECT ==================== */
        select.form-input {
            appearance: none;
            padding-right: 2.5rem;
            cursor: pointer;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='8' viewBox='0 0 12 8'%3E%3Cpath d='M1 1l5 5 5-5' stroke='%238B8B8B' stroke-width='2' fill='none' stroke-linecap='round'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 1rem center;
        }

        select.form-input:focus {
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='8' viewBox='0 0 12 8'%3E%3Cpath d='M1 1l5 5 5-5' stroke='%23E85D04' stroke-width='2' fill='none' stroke-linecap='round'/%3E%3C/svg%3E");
        }

        select.form-input option {
            padding: 0.5rem;
        }

        /* ==================== ERROR MESSAGE ==================== */
        .error-message {
            font-size: 0.75rem;
            color: var(--error-red);
            margin-top: 0.5rem;
            display: flex;
            align-items: center;
            gap: 0.4rem;
        }

        .error-message .mdi {
            font-size: 1rem;
        }

        /* ==================== PASSWORD REQUIREMENTS ==================== */
        .password-requirements {
            margin-top: 0.75rem;
            padding: 0.75rem 1rem;
            background: var(--gray-light);
            border-radius: 12px;
            border: 1px solid rgba(232, 93, 4, 0.06);
        }

        .password-requirements .req-title {
            font-size: 0.7rem;
            font-weight: 700;
            color: var(--gray-dark);
            margin-bottom: 0.5rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .requirement {
            font-size: 0.7rem;
            color: var(--gray-medium);
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin-bottom: 0.2rem;
            transition: var(--transition);
        }

        .requirement:last-child {
            margin-bottom: 0;
        }

        .requirement .mdi {
            font-size: 0.9rem;
            transition: var(--transition);
        }

        .requirement.met {
            color: var(--success-green);
        }

        .requirement.met .mdi {
            color: var(--success-green);
        }

        /* ==================== TERMS ==================== */
        .terms-wrapper {
            display: flex;
            align-items: flex-start;
            gap: 0.75rem;
            cursor: pointer;
            margin-bottom: 1.5rem;
            padding: 0.5rem 0;
        }

        .terms-wrapper input[type="checkbox"] {
            width: 18px;
            height: 18px;
            min-width: 18px;
            margin-top: 2px;
            accent-color: var(--primary-orange);
            cursor: pointer;
            border-radius: 4px;
            border: 2px solid #D1D5DB;
            transition: var(--transition);
        }

        .terms-wrapper input[type="checkbox"]:checked {
            border-color: var(--primary-orange);
        }

        .terms-wrapper span {
            font-size: 0.8rem;
            color: var(--gray-dark);
            line-height: 1.5;
        }

        .terms-wrapper a {
            color: var(--primary-orange);
            text-decoration: none;
            font-weight: 600;
            transition: var(--transition);
        }

        .terms-wrapper a:hover {
            color: var(--secondary-orange);
            text-decoration: underline;
        }

        /* ==================== BUTTON ==================== */
        .btn-register {
            width: 100%;
            padding: 0.875rem;
            background: var(--gradient-primary);
            color: var(--primary-white);
            border: none;
            border-radius: 14px;
            font-weight: 700;
            font-size: 1rem;
            cursor: pointer;
            transition: var(--transition);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.75rem;
            position: relative;
            overflow: hidden;
        }

        .btn-register::after {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.15), transparent);
            transition: left 0.5s ease;
        }

        .btn-register:hover::after {
            left: 100%;
        }

        .btn-register:hover {
            background: var(--gradient-hover);
            transform: translateY(-2px);
            box-shadow: 0 10px 30px var(--shadow-orange);
        }

        .btn-register:active {
            transform: translateY(0);
        }

        .btn-register .mdi {
            font-size: 1.2rem;
        }

        /* ==================== LOGIN LINK ==================== */
        .login-link {
            text-align: center;
            margin-top: 1.5rem;
            padding-top: 1.5rem;
            border-top: 1px solid #E5E7EB;
        }

        .login-link p {
            font-size: 0.85rem;
            color: var(--gray-medium);
        }

        .login-link a {
            color: var(--primary-orange);
            text-decoration: none;
            font-weight: 600;
            transition: var(--transition);
        }

        .login-link a:hover {
            color: var(--secondary-orange);
            text-decoration: underline;
        }

        /* ==================== BACK HOME ==================== */
        .back-home {
            text-align: center;
            margin-top: 1.5rem;
        }

        .back-home a {
            color: rgba(255, 255, 255, 0.5);
            text-decoration: none;
            font-size: 0.85rem;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            transition: var(--transition);
            padding: 0.5rem 1rem;
            border-radius: 10px;
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid rgba(255, 255, 255, 0.05);
        }

        .back-home a:hover {
            color: var(--primary-orange);
            background: rgba(232, 93, 4, 0.1);
            border-color: rgba(232, 93, 4, 0.2);
            transform: translateY(-2px);
        }

        .back-home a .mdi {
            font-size: 1.1rem;
        }

        /* ==================== RESPONSIVE ==================== */
        @media (max-width: 640px) {
            .register-card {
                padding: 1.75rem 1.5rem 1.5rem;
                border-radius: 16px;
            }

            .logo img {
                height: 45px;
            }

            .logo-section h2 {
                font-size: 1.5rem;
            }

            .logo-section p {
                font-size: 0.8rem;
            }

            .form-input {
                font-size: 0.9rem;
                padding: 0.75rem 1rem 0.75rem 2.75rem;
            }

            .btn-register {
                font-size: 0.9rem;
                padding: 0.75rem;
            }

            .bg-pattern .gradient-1,
            .bg-pattern .gradient-2 {
                width: 300px;
                height: 300px;
            }

            .validation-errors {
                padding: 0.75rem 1rem;
            }
        }

        @media (max-width: 400px) {
            .register-card {
                padding: 1.25rem 1rem 1.25rem;
            }

            .logo-section h2 {
                font-size: 1.25rem;
            }

            .form-label {
                font-size: 0.7rem;
            }

            .form-input {
                font-size: 0.85rem;
                padding: 0.65rem 0.85rem 0.65rem 2.5rem;
            }
        }

        @media (min-width: 768px) {
            .register-card {
                padding: 3rem 3rem 2.5rem;
            }

            .logo img {
                height: 70px;
            }

            .logo-section h2 {
                font-size: 2rem;
            }
        }

        /* ==================== ANIMATIONS ==================== */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(40px) scale(0.96);
            }
            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }

        /* ==================== FOCUS VISIBLE ==================== */
        .form-input:focus-visible {
            outline: 2px solid var(--primary-orange);
            outline-offset: 2px;
        }

        .btn-register:focus-visible {
            outline: 2px solid var(--primary-orange);
            outline-offset: 2px;
        }

        /* ==================== SCROLLBAR ==================== */
        ::-webkit-scrollbar {
            width: 6px;
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
    </style>
</head>
<body>
    <!-- ==================== BACKGROUND PATTERN ==================== -->
    <div class="bg-pattern">
        <div class="gradient-1"></div>
        <div class="gradient-2"></div>
        <div class="dots"></div>
    </div>

    <!-- ==================== REGISTER CONTAINER ==================== -->
    <div class="register-container">
        <div class="register-card">
            <!-- ===== LOGO ===== -->
            <div class="logo-section">
                <div class="logo">
                    <img src="{{ asset('img/logo.png') }}" alt="AMERI">
                </div>
                <h2>Crear <span>Cuenta</span></h2>
                <p>Regístrate y comienza a construir</p>
                <div class="divider"></div>
            </div>

            <!-- ===== VALIDATION ERRORS ===== -->
            @if ($errors->any())
                <div class="validation-errors">
                    <p>
                        <span class="mdi mdi-alert-circle"></span>
                        Por favor corrige los siguientes errores:
                    </p>
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- ===== REGISTER FORM ===== -->
            <form method="POST" action="{{ route('register') }}">
                @csrf

                <!-- ===== NAME ===== -->
                <div class="form-group">
                    <label class="form-label" for="name">
                        <span class="mdi mdi-account"></span>
                        Nombre Completo
                        <span class="required">*</span>
                    </label>
                    <div class="input-wrapper">
                        <span class="input-icon mdi mdi-account-outline"></span>
                        <input 
                            type="text" 
                            id="name" 
                            name="name" 
                            class="form-input @error('name') input-error @enderror" 
                            value="{{ old('name') }}" 
                            required 
                            autofocus 
                            autocomplete="name" 
                            placeholder="Ej. Juan Pérez"
                        >
                    </div>
                    @error('name')
                        <div class="error-message">
                            <span class="mdi mdi-alert-circle"></span>
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <!-- ===== EMAIL ===== -->
                <div class="form-group">
                    <label class="form-label" for="email">
                        <span class="mdi mdi-email"></span>
                        Correo Electrónico
                        <span class="required">*</span>
                    </label>
                    <div class="input-wrapper">
                        <span class="input-icon mdi mdi-email-outline"></span>
                        <input 
                            type="email" 
                            id="email" 
                            name="email" 
                            class="form-input @error('email') input-error @enderror" 
                            value="{{ old('email') }}" 
                            required 
                            autocomplete="username" 
                            placeholder="ejemplo@empresa.com"
                        >
                    </div>
                    @error('email')
                        <div class="error-message">
                            <span class="mdi mdi-alert-circle"></span>
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <!-- ===== ROLE SELECTOR ===== -->
                <div class="form-group">
                    <label class="form-label" for="role_id">
                        <span class="mdi mdi-account-group"></span>
                        Tipo de Usuario
                        <span class="required">*</span>
                    </label>
                    <div class="input-wrapper">
                        <span class="input-icon mdi mdi-account-switch"></span>
                        <select 
                            id="role_id" 
                            name="role_id" 
                            class="form-input @error('role_id') input-error @enderror"
                            required
                        >
                            <option value="">Selecciona un rol...</option>
                            @foreach($roles as $role)
                                <option value="{{ $role->id }}" {{ old('role_id') == $role->id ? 'selected' : '' }}>
                                    {{ $role->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    @error('role_id')
                        <div class="error-message">
                            <span class="mdi mdi-alert-circle"></span>
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <!-- ===== PASSWORD ===== -->
                <div class="form-group">
                    <label class="form-label" for="password">
                        <span class="mdi mdi-lock"></span>
                        Contraseña
                        <span class="required">*</span>
                    </label>
                    <div class="input-wrapper">
                        <span class="input-icon mdi mdi-lock-outline"></span>
                        <input 
                            type="password" 
                            id="password" 
                            name="password" 
                            class="form-input @error('password') input-error @enderror" 
                            required 
                            autocomplete="new-password" 
                            placeholder="••••••••"
                        >
                    </div>

                    <!-- Password Requirements -->
                    <div class="password-requirements">
                        <div class="req-title">La contraseña debe contener:</div>
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

                    @error('password')
                        <div class="error-message">
                            <span class="mdi mdi-alert-circle"></span>
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <!-- ===== CONFIRM PASSWORD ===== -->
                <div class="form-group">
                    <label class="form-label" for="password_confirmation">
                        <span class="mdi mdi-check-circle"></span>
                        Confirmar Contraseña
                        <span class="required">*</span>
                    </label>
                    <div class="input-wrapper">
                        <span class="input-icon mdi mdi-check-circle-outline"></span>
                        <input 
                            type="password" 
                            id="password_confirmation" 
                            name="password_confirmation" 
                            class="form-input" 
                            required 
                            autocomplete="new-password" 
                            placeholder="••••••••"
                        >
                    </div>
                </div>

                <!-- ===== TERMS ===== -->
                @if (Laravel\Jetstream\Jetstream::hasTermsAndPrivacyPolicyFeature())
                    <div class="terms-wrapper">
                        <input type="checkbox" name="terms" id="terms" required>
                        <span>
                            Acepto los 
                            <a href="{{ route('terms.show') }}" target="_blank">Términos de Servicio</a> 
                            y la 
                            <a href="{{ route('policy.show') }}" target="_blank">Política de Privacidad</a>
                        </span>
                    </div>
                @endif

                <!-- ===== SUBMIT ===== -->
                <button type="submit" class="btn-register">
                    <span class="mdi mdi-account-plus"></span>
                    Registrarse
                </button>
            </form>

            <!-- ===== LOGIN LINK ===== -->
            <div class="login-link">
                <p>
                    ¿Ya tienes una cuenta? 
                    <a href="{{ route('login') }}">
                        Iniciar Sesión
                    </a>
                </p>
            </div>
        </div>

        <!-- ===== BACK HOME ===== -->
        <div class="back-home">
            <a href="{{ url('/') }}">
                <span class="mdi mdi-arrow-left"></span>
                Volver al inicio
            </a>
        </div>
    </div>

    <!-- ==================== PASSWORD REQUIREMENTS SCRIPT ==================== -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const password = document.getElementById('password');
            
            function checkRequirements() {
                const val = password.value || '';
                
                const checks = {
                    'req-length': val.length >= 8,
                    'req-upper': /[A-Z]/.test(val),
                    'req-lower': /[a-z]/.test(val),
                    'req-number': /\d/.test(val),
                    'req-special': /[\W_]/.test(val)
                };
                
                Object.entries(checks).forEach(([id, met]) => {
                    const el = document.getElementById(id);
                    if (el) {
                        if (met) {
                            el.classList.add('met');
                            el.querySelector('.mdi').className = 'mdi mdi-check-circle';
                        } else {
                            el.classList.remove('met');
                            el.querySelector('.mdi').className = 'mdi mdi-circle-outline';
                        }
                    }
                });
            }
            
            if (password) {
                password.addEventListener('input', checkRequirements);
                checkRequirements();
            }
        });
    </script>
</body>
</html>
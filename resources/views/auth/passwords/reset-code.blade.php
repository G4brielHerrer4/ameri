
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nueva contraseña | AMERI</title>

    <link rel="icon" href="{{ asset('img/logo.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Instrument+Sans:wght@400;500;600;700&family=Space+Grotesk:wght@400;500;600;700&display=swap" rel="stylesheet">

    <script>
        (() => {
            const saved = localStorage.getItem('ameri-theme');
            document.documentElement.dataset.theme = saved || 'dark';
        })();
    </script>

    <style>
        :root {
            color-scheme: dark;
            --bg: #05080a;
            --card: rgba(10, 16, 20, .94);
            --text: #edfafa;
            --muted: #8da3a8;
            --border: rgba(0, 255, 240, .16);
            --cyan: #00fff0;
            --input: #080e12;
            --danger: #ff7777;
            --success-bg: rgba(0, 255, 170, .08);
        }

        :root[data-theme="light"] {
            color-scheme: light;
            --bg: #f1f8f9;
            --card: rgba(255, 255, 255, .96);
            --text: #12282d;
            --muted: #63777b;
            --border: rgba(0, 135, 135, .20);
            --cyan: #008f8a;
            --input: #f8fcfc;
            --danger: #c62828;
            --success-bg: rgba(0, 143, 138, .08);
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px 16px;
            background-color: var(--bg);
            background-image:
                linear-gradient(rgba(0, 255, 240, .035) 1px, transparent 1px),
                linear-gradient(90deg, rgba(0, 255, 240, .035) 1px, transparent 1px);
            background-size: 38px 38px;
            color: var(--text);
            font-family: 'Instrument Sans', sans-serif;
            transition: background-color .2s, color .2s;
        }

        .orb {
            position: fixed;
            width: 280px;
            height: 280px;
            border-radius: 50%;
            background: rgba(0, 255, 240, .08);
            filter: blur(85px);
            top: 8%;
            left: 50%;
            transform: translateX(-50%);
            pointer-events: none;
        }

        .card {
            position: relative;
            width: 100%;
            max-width: 440px;
            padding: 36px;
            border: 1px solid var(--border);
            border-radius: 22px;
            background: var(--card);
            box-shadow: 0 20px 70px rgba(0, 0, 0, .20);
            backdrop-filter: blur(14px);
        }

        .theme-toggle {
            position: absolute;
            top: 16px;
            right: 16px;
            border: 1px solid var(--border);
            border-radius: 999px;
            padding: 8px 12px;
            background: transparent;
            color: var(--text);
            cursor: pointer;
            font-size: 12px;
        }

        .brand {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
            margin: 12px 0 28px;
        }

        .brand img {
            width: 48px;
            height: 48px;
            object-fit: contain;
        }

        .brand-name {
            font-family: 'Space Grotesk', sans-serif;
            font-size: 25px;
            font-weight: 700;
            letter-spacing: 2px;
        }

        .brand-name span {
            color: var(--cyan);
        }

        .eyebrow {
            color: var(--cyan);
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 2px;
            text-transform: uppercase;
            text-align: center;
            margin-bottom: 10px;
        }

        h1 {
            font-family: 'Space Grotesk', sans-serif;
            text-align: center;
            font-size: clamp(24px, 5vw, 30px);
            margin: 0 0 12px;
            letter-spacing: -.8px;
        }

        .description {
            color: var(--muted);
            font-size: 14px;
            line-height: 1.7;
            text-align: center;
            margin: 0 0 25px;
        }

        .message {
            padding: 12px 14px;
            margin-bottom: 18px;
            border-radius: 10px;
            font-size: 13px;
            line-height: 1.5;
        }

        .message.error {
            background: rgba(255, 70, 70, .07);
            border: 1px solid rgba(255, 100, 100, .24);
            color: var(--danger);
        }

        .field {
            margin-bottom: 18px;
        }

        .field-label {
            display: block;
            margin-bottom: 9px;
            font-size: 13px;
            font-weight: 600;
        }

        .password-wrap {
            position: relative;
        }

        .password-input {
            display: block;
            width: 100%;
            height: 48px;
            border: 1px solid var(--border);
            border-radius: 11px;
            outline: none;
            background: var(--input);
            color: var(--text);
            font-family: 'Instrument Sans', sans-serif;
            font-size: 14px;
            padding: 12px 76px 12px 14px;
            transition: border-color .2s, box-shadow .2s;
        }

        .password-input:focus {
            border-color: var(--cyan);
            box-shadow: 0 0 0 3px rgba(0, 255, 240, .08);
        }

        .toggle-password {
            position: absolute;
            top: 50%;
            right: 12px;
            transform: translateY(-50%);
            background: transparent;
            border: none;
            color: var(--cyan);
            cursor: pointer;
            font-size: 12px;
            font-weight: 600;
        }

        .hint {
            color: var(--muted);
            font-size: 12px;
            line-height: 1.6;
            margin: 8px 0 0;
        }

        .submit-btn {
            width: 100%;
            min-height: 48px;
            margin-top: 6px;
            border: none;
            border-radius: 11px;
            background: var(--cyan);
            color: #031011;
            font-family: 'Space Grotesk', sans-serif;
            font-size: 14px;
            font-weight: 700;
            cursor: pointer;
            transition: transform .2s, opacity .2s;
        }

        .submit-btn:hover {
            transform: translateY(-1px);
            opacity: .9;
        }

        .links {
            display: flex;
            justify-content: center;
            margin-top: 22px;
            font-size: 13px;
        }

        .links a {
            color: var(--cyan);
            text-decoration: none;
        }

        .links a:hover {
            text-decoration: underline;
        }

        .footer {
            color: var(--muted);
            font-size: 11px;
            text-align: center;
            margin-top: 28px;
        }

        @media (max-width: 480px) {
            .card {
                padding: 28px 22px;
            }
        }
    </style>
</head>
<body>
    <div class="orb"></div>

    <main class="card">
        <button class="theme-toggle" type="button" id="themeToggle">
            ☀ / ☾
        </button>

        <div class="brand">
            <img src="{{ asset('img/logo.png') }}" alt="Logo AMERI">
            <div class="brand-name">AMERI<span>.</span></div>
        </div>

        <div class="eyebrow">Último paso</div>
        <h1>Crea una contraseña nueva</h1>

        <p class="description">
            El código fue verificado correctamente. Establece una contraseña
            nueva para volver a acceder a tu cuenta.
        </p>

        @if ($errors->any())
            <div class="message error" role="alert">
                @foreach ($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('password.code.update') }}">
            @csrf

            <div class="field">
                <label class="field-label" for="password">
                    Nueva contraseña
                </label>

                <div class="password-wrap">
                    <input
                        id="password"
                        name="password"
                        type="password"
                        class="password-input"
                        placeholder="Mínimo 8 caracteres"
                        autocomplete="new-password"
                        minlength="8"
                        required
                    >

                    <button
                        type="button"
                        class="toggle-password"
                        data-target="password"
                    >
                        Mostrar
                    </button>
                </div>

                <p class="hint">
                    Utiliza al menos 8 caracteres y evita contraseñas fáciles de adivinar.
                </p>
            </div>

            <div class="field">
                <label class="field-label" for="password_confirmation">
                    Confirmar nueva contraseña
                </label>

                <div class="password-wrap">
                    <input
                        id="password_confirmation"
                        name="password_confirmation"
                        type="password"
                        class="password-input"
                        placeholder="Repite la contraseña"
                        autocomplete="new-password"
                        minlength="8"
                        required
                    >

                    <button
                        type="button"
                        class="toggle-password"
                        data-target="password_confirmation"
                    >
                        Mostrar
                    </button>
                </div>
            </div>

            <button type="submit" class="submit-btn">
                Guardar nueva contraseña
            </button>
        </form>

        <div class="links">
            <a href="{{ route('login') }}">← Volver al inicio de sesión</a>
        </div>

        <div class="footer">
            AMERI · Recuperación segura de cuenta
        </div>
    </main>

    <script>
        const themeToggle = document.getElementById('themeToggle');

        themeToggle.addEventListener('click', () => {
            const root = document.documentElement;
            const nextTheme =
                root.dataset.theme === 'dark' ? 'light' : 'dark';

            root.dataset.theme = nextTheme;
            localStorage.setItem('ameri-theme', nextTheme);
        });

        document.querySelectorAll('.toggle-password').forEach(button => {
            button.addEventListener('click', () => {
                const input = document.getElementById(button.dataset.target);
                const showing = input.type === 'text';

                input.type = showing ? 'password' : 'text';
                button.textContent = showing ? 'Mostrar' : 'Ocultar';
            });
        });
    </script>
</body>
</html>

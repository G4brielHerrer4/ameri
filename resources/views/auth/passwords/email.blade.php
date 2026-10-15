
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-theme="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'AMERI') }} - Recuperar contraseña</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700|space-grotesk:400,500,600,700" rel="stylesheet">

    <script>
        (function () {
            try {
                const saved = localStorage.getItem('ameri-theme');
                if (saved === 'light' || saved === 'dark') {
                    document.documentElement.setAttribute('data-theme', saved);
                }
            } catch (e) {}
        })();
    </script>

    <style>
        :root {
            --bg:#05080a; --bg-1:#0a1014; --bg-2:#0f171c; --bg-3:#16222a;
            --ink:#eefcff; --muted:#8fa8ae; --faint:#566d74;
            --cyan:#00fff0; --cyan-2:#00d9cc; --cyan-ink:#00201e;
            --line:rgba(0,255,240,.14); --strong:rgba(0,255,240,.38);
            --glow:rgba(0,255,240,.18); --shadow:rgba(0,0,0,.5);
            --error:#ff5570; --error-bg:rgba(255,85,112,.08);
            --success:#00fff0; --success-bg:rgba(0,255,240,.08);
            --display:'Space Grotesk','Instrument Sans',sans-serif;
            --body:'Instrument Sans',sans-serif;
        }

        html[data-theme="light"] {
            --bg:#eef4f5; --bg-1:#fff; --bg-2:#e4eef0; --bg-3:#d6e4e7;
            --ink:#05161a; --muted:#476067; --faint:#7a9198;
            --cyan:#00b8ab; --cyan-2:#009e93; --cyan-ink:#fff;
            --line:rgba(0,140,130,.18); --strong:rgba(0,140,130,.42);
            --glow:rgba(0,160,150,.12); --shadow:rgba(10,40,45,.14);
            --error:#d9354f; --error-bg:rgba(217,53,79,.07);
            --success:#008a80; --success-bg:rgba(0,160,150,.08);
        }

        * { box-sizing:border-box; margin:0; padding:0; }

        body {
            min-height:100vh; display:flex; align-items:center; justify-content:center;
            padding:24px; position:relative; overflow-x:hidden;
            font-family:var(--body); color:var(--ink); background:var(--bg);
            -webkit-font-smoothing:antialiased;
        }

        body::before {
            content:""; position:fixed; inset:0; pointer-events:none;
            background-image:linear-gradient(var(--line) 1px,transparent 1px),
                linear-gradient(90deg,var(--line) 1px,transparent 1px);
            background-size:64px 64px;
            mask-image:radial-gradient(ellipse at center,#000,transparent 75%);
        }

        .orb {
            position:fixed; width:520px; height:520px; right:-180px; top:-220px;
            border-radius:50%; background:radial-gradient(circle,var(--glow),transparent 70%);
            filter:blur(35px); pointer-events:none;
        }

        .container { width:100%; max-width:440px; position:relative; z-index:1; }

        .card {
            position:relative; background:var(--bg-1); border:1px solid var(--line);
            border-radius:28px; padding:38px; overflow:hidden;
            box-shadow:0 40px 80px -40px var(--shadow);
        }

        .card::before {
            content:""; position:absolute; top:0; left:0; right:0; height:1px;
            background:linear-gradient(90deg,transparent,var(--cyan),transparent);
        }

        .theme-toggle {
            position:absolute; right:18px; top:18px; width:40px; height:40px;
            border:1px solid var(--line); border-radius:50%; color:var(--ink);
            background:var(--bg-2); cursor:pointer; font-size:18px;
        }

        .header { text-align:center; margin:12px 0 28px; }

        .logo {
            display:inline-flex; padding:12px 20px; border:1px solid var(--line);
            border-radius:16px; background:var(--bg-2); margin-bottom:22px;
        }

        .logo img { height:48px; width:auto; object-fit:contain; }

        h1 {
            font:600 26px var(--display); letter-spacing:-.5px;
            margin-bottom:9px; color:var(--ink);
        }

        .subtitle { font-size:14px; line-height:1.65; color:var(--muted); }

        .info {
            padding:13px 15px; border-radius:14px; margin-bottom:20px;
            background:var(--success-bg); border:1px solid var(--strong);
            color:var(--success); font-size:13px; line-height:1.55;
        }

        .errors {
            padding:13px 15px; border-radius:14px; margin-bottom:20px;
            background:var(--error-bg); border:1px solid var(--error);
            color:var(--error); font-size:13px; line-height:1.6;
        }

        .group { margin-bottom:22px; }

        label {
            display:block; margin-bottom:9px; color:var(--muted);
            font:600 12px var(--display); letter-spacing:1px;
            text-transform:uppercase;
        }

        input {
            display:block; width:100%; padding:15px 16px;
            border:1px solid var(--line); border-radius:14px;
            background:var(--bg-2); color:var(--ink); font:15px var(--body);
            outline:none; transition:.25s;
        }

        input::placeholder { color:var(--faint); }

        input:focus {
            border-color:var(--cyan); background:var(--bg-3);
            box-shadow:0 0 0 4px var(--glow);
        }

        .error-field { border-color:var(--error); }

        .field-error { color:var(--error); font-size:12px; margin-top:8px; }

        .btn {
            width:100%; padding:15px 18px; border:0; border-radius:999px;
            background:linear-gradient(135deg,var(--cyan),var(--cyan-2));
            color:var(--cyan-ink); font:700 15px var(--body); cursor:pointer;
            box-shadow:0 8px 28px -8px var(--glow); transition:.25s;
        }

        .btn:hover { transform:translateY(-2px); filter:brightness(1.04); }

        .back { text-align:center; margin-top:23px; }

        .back a {
            color:var(--muted); text-decoration:none; font-size:13px;
            transition:.2s;
        }

        .back a:hover { color:var(--cyan); }

        @media(max-width:480px) {
            body { padding:15px; }
            .card { padding:30px 23px; border-radius:22px; }
            h1 { font-size:23px; }
            .logo img { height:42px; }
        }
    </style>
</head>
<body>
    <div class="orb"></div>

    <main class="container">
        <section class="card">
            <button type="button" class="theme-toggle" id="theme-toggle"
                aria-label="Cambiar tema">☼</button>

            <header class="header">
                <div class="logo">
                    <img src="{{ asset('img/logo.png') }}" alt="AMERI">
                </div>
                <h1>Recuperar contraseña</h1>
                <p class="subtitle">
                    Ingresa el correo asociado a tu cuenta.
                    Te enviaremos un código de seguridad de seis dígitos.
                </p>
            </header>

            @if ($errors->any())
                <div class="errors" role="alert">
                    @foreach ($errors->all() as $error)
                        <div>• {{ $error }}</div>
                    @endforeach
                </div>
            @endif

            @if (session('status'))
                <div class="info" role="status">{{ session('status') }}</div>
            @endif

            <form method="POST" action="{{ route('password.email') }}">
                @csrf

                <div class="group">
                    <label for="email">Correo electrónico</label>
                    <input
                        type="email"
                        id="email"
                        name="email"
                        value="{{ old('email') }}"
                        placeholder="ejemplo@empresa.com"
                        autocomplete="email"
                        maxlength="255"
                        required
                        autofocus
                        class="@error('email') error-field @enderror"
                    >
                    @error('email')
                        <p class="field-error">{{ $message }}</p>
                    @enderror
                </div>

                <button type="submit" class="btn">Enviar código de recuperación</button>
            </form>

            <div class="back">
                <a href="{{ route('login') }}">← Volver al inicio de sesión</a>
            </div>
        </section>
    </main>

    <script>
        (function () {
            const button = document.getElementById('theme-toggle');
            const root = document.documentElement;

            function setTheme(theme) {
                root.setAttribute('data-theme', theme);
                try { localStorage.setItem('ameri-theme', theme); } catch (e) {}
                button.textContent = theme === 'light' ? '☾' : '☼';
            }

            setTheme(root.getAttribute('data-theme') || 'dark');

            button.addEventListener('click', function () {
                const current = root.getAttribute('data-theme') === 'light' ? 'light' : 'dark';
                setTheme(current === 'light' ? 'dark' : 'light');
            });
        })();
    </script>
</body>
</html>

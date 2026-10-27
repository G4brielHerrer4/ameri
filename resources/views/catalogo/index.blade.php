<!DOCTYPE html>
<html lang="es" data-theme="dark">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="color-scheme" content="dark light">

    <title>Catálogo de Productos - AMERI</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700|space-grotesk:400,500,600,700" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@mdi/font@7.2.96/css/materialdesignicons.min.css">

    <script>
        (function () {
            try {
                const tema = localStorage.getItem('ameri-theme');

                if (tema === 'light' || tema === 'dark') {
                    document.documentElement.setAttribute('data-theme', tema);
                }
            } catch (e) {}
        })();
    </script>

    <style>
        :root {
            --bg: #05080a;
            --bg-1: #0a1014;
            --bg-2: #0f171c;
            --ink: #eefcff;
            --ink-muted: #8fa8ae;
            --ink-faint: #566d74;
            --cyan: #00fff0;
            --cyan-2: #00d9cc;
            --cyan-ink: #00201e;
            --line: rgba(0,255,240,.14);
            --line-strong: rgba(0,255,240,.38);
            --glow: rgba(0,255,240,.45);
            --shadow: rgba(0,0,0,.5);
            --surface-alpha: rgba(255,255,255,.03);
            --font-display: 'Space Grotesk',sans-serif;
            --font-body: 'Instrument Sans',sans-serif;
            --ease: cubic-bezier(.16,1,.3,1);
        }

        html[data-theme="light"] {
            --bg: #eef4f5;
            --bg-1: #ffffff;
            --bg-2: #e4eef0;
            --ink: #05161a;
            --ink-muted: #476067;
            --ink-faint: #7a9198;
            --cyan: #00b8ab;
            --cyan-2: #009e93;
            --cyan-ink: #ffffff;
            --line: rgba(0,140,130,.18);
            --line-strong: rgba(0,140,130,.42);
            --glow: rgba(0,160,150,.28);
            --shadow: rgba(10,40,45,.14);
            --surface-alpha: rgba(0,40,48,.03);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: var(--font-body);
            background: var(--bg);
            color: var(--ink);
            line-height: 1.6;
            min-height: 100vh;
            transition: background .3s, color .3s;
        }

        a {
            color: inherit;
            text-decoration: none;
        }

        .container {
            width: 100%;
            max-width: 1280px;
            margin: auto;
            padding: 0 2rem;
        }

        /* NAVBAR */
        .navbar {
            position: sticky;
            top: 0;
            z-index: 100;
            background: color-mix(in srgb, var(--bg) 90%, transparent);
            backdrop-filter: blur(18px);
            border-bottom: 1px solid var(--line);
        }

        .nav-inner {
            min-height: 94px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
        }

        .logo img {
            height: 76px;
            width: auto;
            object-fit: contain;
        }

        .nav-actions {
            display: flex;
            align-items: center;
            gap: .8rem;
        }

        .back-link {
            display: inline-flex;
            align-items: center;
            gap: .5rem;
            color: var(--ink-muted);
            font-size: .88rem;
            font-weight: 600;
            transition: color .2s;
        }

        .back-link:hover {
            color: var(--cyan);
        }

        .theme-toggle {
            height: 44px;
            width: 44px;
            border-radius: 50%;
            background: var(--surface-alpha);
            border: 1px solid var(--line);
            color: var(--ink);
            cursor: pointer;
            font-size: 1.2rem;
            transition: all .3s var(--ease);
        }

        .theme-toggle:hover {
            color: var(--cyan);
            border-color: var(--line-strong);
            transform: rotate(12deg);
        }

        .icon-sun {
            display: none;
        }

        html[data-theme="light"] .icon-sun {
            display: inline;
        }

        html[data-theme="light"] .icon-moon {
            display: none;
        }

        /* HERO */
        .catalogo-hero {
            position: relative;
            overflow: hidden;
            padding: 5rem 0 4rem;
            border-bottom: 1px solid var(--line);
            background:
                radial-gradient(
                    ellipse at 80% 20%,
                    color-mix(in srgb, var(--cyan) 10%, transparent),
                    transparent 55%
                ),
                var(--bg);
        }

        .catalogo-hero::before {
            content: '';
            position: absolute;
            inset: 0;
            background-image:
                linear-gradient(var(--line) 1px, transparent 1px),
                linear-gradient(90deg, var(--line) 1px, transparent 1px);
            background-size: 65px 65px;
            opacity: .35;
            pointer-events: none;
        }

        .hero-content {
            position: relative;
            z-index: 1;
        }

        .eyebrow {
            display: inline-flex;
            align-items: center;
            gap: .6rem;
            color: var(--cyan);
            font-family: var(--font-display);
            font-size: .78rem;
            letter-spacing: .1em;
            text-transform: uppercase;
            margin-bottom: 1.2rem;
        }

        .eyebrow::before {
            content: '';
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: var(--cyan);
            box-shadow: 0 0 14px var(--glow);
        }

        .catalogo-hero h1 {
            font-family: var(--font-display);
            font-size: clamp(2.2rem, 5vw, 3.8rem);
            font-weight: 600;
            line-height: 1.12;
            margin-bottom: 1.2rem;
            letter-spacing: -.035em;
        }

        .accent {
            color: var(--cyan);
        }

        .catalogo-hero p {
            max-width: 600px;
            color: var(--ink-muted);
            font-size: 1.05rem;
        }

        /* MAIN */
        .catalogo-main {
            padding: 3rem 0 6rem;
        }

        .catalogo-toolbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 1.5rem;
            margin-bottom: 2.5rem;
        }

        .categorias {
            display: flex;
            flex-wrap: wrap;
            gap: .65rem;
        }

        .categoria-btn {
            display: inline-flex;
            align-items: center;
            gap: .45rem;
            border: 1px solid var(--line);
            border-radius: 999px;
            padding: .75rem 1.25rem;
            font-size: .85rem;
            font-weight: 600;
            color: var(--ink-muted);
            background: var(--bg-1);
            transition: all .3s var(--ease);
        }

        .categoria-btn:hover {
            border-color: var(--line-strong);
            color: var(--cyan);
            transform: translateY(-2px);
        }

        .categoria-btn.active {
            color: var(--cyan-ink);
            border-color: var(--cyan);
            background: linear-gradient(135deg, var(--cyan), var(--cyan-2));
            box-shadow: 0 6px 20px -8px var(--glow);
        }

        .resultados {
            font-size: .85rem;
            color: var(--ink-faint);
            white-space: nowrap;
        }

        .resultados strong {
            color: var(--cyan);
        }

        /* GRID */
        .catalogo-grid {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 1.5rem;
        }

        .catalogo-card {
            background: var(--bg-1);
            border: 1px solid var(--line);
            border-radius: 22px;
            overflow: hidden;
            transition: all .4s var(--ease);
            min-width: 0;
        }

        .catalogo-card:hover {
            border-color: var(--line-strong);
            transform: translateY(-7px);
            box-shadow: 0 25px 50px -25px var(--shadow);
        }

        .card-image {
            position: relative;
            height: 235px;
            background: var(--bg-2);
            overflow: hidden;
            border-bottom: 1px solid var(--line);
        }

        .card-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform .6s var(--ease);
        }

        .catalogo-card:hover .card-image img {
            transform: scale(1.07);
        }

        .sin-imagen {
            width: 100%;
            height: 100%;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: .5rem;
            color: var(--ink-faint);
            background: var(--bg-2);
            font-size: .8rem;
        }

        .sin-imagen i {
            font-size: 2.5rem;
            color: var(--cyan);
        }

        .card-category {
            position: absolute;
            top: 14px;
            left: 14px;
            padding: .4rem .75rem;
            background: color-mix(in srgb, var(--bg) 90%, transparent);
            border: 1px solid var(--line-strong);
            backdrop-filter: blur(10px);
            border-radius: 999px;
            color: var(--cyan);
            font-size: .68rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .04em;
        }

        .card-body {
            padding: 1.35rem;
        }

        .card-body h3 {
            font-family: var(--font-display);
            font-size: 1.05rem;
            font-weight: 600;
            margin-bottom: .85rem;
            line-height: 1.4;
            overflow-wrap: anywhere;
        }

        .card-detail {
            display: flex;
            align-items: flex-start;
            gap: .5rem;
            color: var(--ink-muted);
            font-size: .83rem;
            margin-bottom: .55rem;
            overflow-wrap: anywhere;
        }

        .card-detail i {
            color: var(--cyan);
            font-size: 1rem;
            margin-top: .1rem;
            flex-shrink: 0;
        }

        .card-footer {
            border-top: 1px solid var(--line);
            margin-top: 1rem;
            padding-top: 1rem;
            color: var(--ink-faint);
            font-size: .78rem;
            display: flex;
            align-items: center;
            gap: .5rem;
        }

        .card-footer i {
            color: var(--cyan);
            font-size: 1.1rem;
        }

        /* EMPTY STATE */
        .empty-state {
            text-align: center;
            background: var(--bg-1);
            border: 1px solid var(--line);
            border-radius: 22px;
            padding: 5rem 2rem;
        }

        .empty-state i {
            display: block;
            font-size: 3.5rem;
            color: var(--cyan);
            margin-bottom: 1rem;
        }

        .empty-state h3 {
            font-family: var(--font-display);
            font-size: 1.3rem;
            margin-bottom: .5rem;
        }

        .empty-state p {
            color: var(--ink-muted);
            margin-bottom: 1.5rem;
        }

        /* FOOTER */
        .footer {
            border-top: 1px solid var(--line);
            background: var(--bg-1);
            padding: 2.5rem 0;
        }

        .footer-inner {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 1rem;
            flex-wrap: wrap;
            color: var(--ink-faint);
            font-size: .8rem;
        }

        .footer-inner a {
            color: var(--cyan);
        }

        /* RESPONSIVE */
        @media(max-width:1100px) {
            .catalogo-grid {
                grid-template-columns: repeat(3, minmax(0, 1fr));
            }
        }

        @media(max-width:850px) {
            .catalogo-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
        }

        @media(max-width:640px) {
            .container {
                padding: 0 1.25rem;
            }

            .nav-inner {
                min-height: 78px;
            }

            .logo img {
                height: 55px;
            }

            .back-link span {
                display: none;
            }

            .catalogo-hero {
                padding: 3.5rem 0;
            }

            .catalogo-main {
                padding: 2rem 0 4rem;
            }

            .catalogo-grid {
                gap: .85rem;
            }

            .card-image {
                height: 165px;
            }

            .card-body {
                padding: .9rem;
            }

            .card-body h3 {
                font-size: .9rem;
            }

            .card-detail {
                font-size: .75rem;
            }

            .card-category {
                font-size: .58rem;
                padding: .3rem .5rem;
            }

            .catalogo-toolbar {
                align-items: flex-start;
            }

            .categoria-btn {
                padding: .6rem .85rem;
                font-size: .76rem;
            }
        }

        @media(max-width:370px) {
            .catalogo-grid {
                grid-template-columns: 1fr;
            }

            .card-image {
                height: 240px;
            }
        }
    </style>
</head>

<body>

    <!-- NAVBAR -->
    <nav class="navbar">
        <div class="container nav-inner">

            <a href="{{ url('/') }}" class="logo" aria-label="Inicio AMERI">
                <img src="{{ asset('img/logo.png') }}" alt="AMERI">
            </a>

            <div class="nav-actions">

                <a href="{{ url('/#products') }}" class="back-link">
                    <i class="mdi mdi-arrow-left"></i>
                    <span>Volver al inicio</span>
                </a>

                <button
                    type="button"
                    class="theme-toggle"
                    id="theme-toggle"
                    aria-label="Cambiar tema"
                >
                    <i class="mdi mdi-weather-night icon-moon"></i>
                    <i class="mdi mdi-white-balance-sunny icon-sun"></i>
                </button>

            </div>
        </div>
    </nav>

    <!-- HERO -->
    <section class="catalogo-hero">
        <div class="container hero-content">

            <div class="eyebrow">Catálogo oficial AMERI</div>

            <h1>
                Descubre nuestro
                <span class="accent">catálogo</span>
            </h1>

            <p>
                Explora nuestra selección de cerámicas, porcelanatos,
                cementos y productos decorativos.
                Encuentra opciones para tu próximo proyecto.
            </p>

        </div>
    </section>

    <!-- PRODUCTS -->
    <main class="catalogo-main">
        <div class="container">

            
            @php
                $categoriaActual = isset($categoria) ? (int) $categoria : null;
            @endphp

            <div class="catalogo-toolbar">

                <div class="categorias">

                    <a
                        href="{{ route('catalogo.index') }}"
                        class="categoria-btn {{ $categoriaActual === null ? 'active' : '' }}"
                    >
                        <i class="mdi mdi-view-grid-outline"></i>
                        Todos
                    </a>

                    @foreach($categorias as $cat)
                        <a
                            href="{{ route('catalogo.categoria', ['categoria' => $cat->id]) }}"
                            class="categoria-btn {{ $categoriaActual === (int) $cat->id ? 'active' : '' }}"
                        >
                            {{ $cat->nombre }}
                        </a>
                    @endforeach

                </div>


                <div class="resultados">
                    <strong>{{ $productos->count() }}</strong>
                    productos encontrados
                </div>

            </div>

            @if($productos->isEmpty())

                <div class="empty-state">
                    <i class="mdi mdi-package-variant"></i>

                    <h3>No hay productos disponibles</h3>

                    <p>
                        Próximamente tendremos nuevos productos
                        en esta categoría.
                    </p>

                    <a href="{{ route('catalogo.index') }}" class="categoria-btn">
                        Ver todo el catálogo
                    </a>
                </div>

            @else

                <div class="catalogo-grid">

                    @foreach($productos as $producto)

                        <article class="catalogo-card">

                            <div class="card-image">

                                @if($producto->imagen_catalogo)
                                    <img
                                        src="{{ asset('storage/' . $producto->imagen_catalogo) }}"
                                        alt="{{ $producto->nombre }}"
                                        loading="lazy"
                                    >
                                @else
                                    <div class="sin-imagen">
                                        <i class="mdi mdi-image-outline"></i>
                                        <span>Imagen no disponible</span>
                                    </div>
                                @endif

                                @if($producto->tipoProducto?->categoria)
                                    <span class="card-category">
                                        {{ $producto->tipoProducto->categoria->nombre }}
                                    </span>
                                @endif

                            </div>

                            <div class="card-body">

                                <h3>{{ $producto->nombre }}</h3>

                                @if($producto->marca)
                                    <div class="card-detail">
                                        <i class="mdi mdi-tag-outline"></i>
                                        <span>Marca: {{ $producto->marca }}</span>
                                    </div>
                                @endif

                                @if($producto->modelo)
                                    <div class="card-detail">
                                        <i class="mdi mdi-shape-outline"></i>
                                        <span>Modelo: {{ $producto->modelo }}</span>
                                    </div>
                                @endif

                                @if($producto->medida)
                                    <div class="card-detail">
                                        <i class="mdi mdi-ruler"></i>
                                        <span>Medida: {{ $producto->medida }}</span>
                                    </div>
                                @endif

                                @if($producto->tipoProducto?->categoria?->nombre === 'Cementos / Adhesivos'
                                    && !is_null($producto->peso))
                                    <div class="card-detail">
                                        <i class="mdi mdi-weight"></i>
                                        <span>Peso: {{ $producto->peso }} kg</span>
                                    </div>
                                @endif

                                @if($producto->color)
                                    <div class="card-detail">
                                        <i class="mdi mdi-palette-outline"></i>
                                        <span>Color: {{ $producto->color }}</span>
                                    </div>
                                @endif

                                @if($producto->acabado)
                                    <div class="card-detail">
                                        <i class="mdi mdi-texture-box"></i>
                                        <span>Acabado: {{ $producto->acabado }}</span>
                                    </div>
                                @endif

                                <div class="card-footer">
                                    <i class="mdi mdi-information-outline"></i>
                                    <span>Consulta con nosotros para más información.</span>
                                </div>

                            </div>

                        </article>

                    @endforeach

                </div>

            @endif

        </div>
    </main>

    <!-- FOOTER -->
    <footer class="footer">
        <div class="container footer-inner">

            <span>
                &copy; {{ date('Y') }} AMERI.
                Todos los derechos reservados.
            </span>

            <span>Hecho en Bolivia</span>

            <a href="{{ url('/') }}">
                <i class="mdi mdi-arrow-left"></i>
                Volver al inicio
            </a>

        </div>
    </footer>

    <script>
        document.getElementById('theme-toggle').addEventListener('click', function () {
            const root = document.documentElement;
            const actual = root.getAttribute('data-theme');
            const nuevo = actual === 'dark' ? 'light' : 'dark';

            root.setAttribute('data-theme', nuevo);

            try {
                localStorage.setItem('ameri-theme', nuevo);
            } catch (e) {}
        });
    </script>

</body>
</html>

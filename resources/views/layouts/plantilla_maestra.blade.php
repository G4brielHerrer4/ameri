{{-- resources/views/layouts/plantilla_maestra.blade.php --}}
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-theme="dark">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <meta name="color-scheme" content="dark light">
  <meta name="theme-color" content="#05080a">

  <title>@yield('title', config('app.name', 'AMERI'))</title>

  <!-- Fonts -->
  <link rel="preconnect" href="https://fonts.bunny.net">
  <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700|space-grotesk:400,500,600,700" rel="stylesheet" />

  <!-- Iconos del template -->
  <link rel="stylesheet" href="{{ asset('template/vendors/feather/feather.css') }}">
  <link rel="stylesheet" href="{{ asset('template/vendors/mdi/css/materialdesignicons.min.css') }}">
  <link rel="stylesheet" href="{{ asset('template/vendors/ti-icons/css/themify-icons.css') }}">
  <link rel="stylesheet" href="{{ asset('template/vendors/typicons/typicons.css') }}">
  <link rel="stylesheet" href="{{ asset('template/vendors/simple-line-icons/css/simple-line-icons.css') }}">
  <link rel="stylesheet" href="{{ asset('template/vendors/css/vendor.bundle.base.css') }}">

  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@mdi/font@7.2.96/css/materialdesignicons.min.css">

  <!-- DataTables CSS -->
  <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
  <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css">

  <!-- Toastr CSS -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
  <!-- SweetAlert2 -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

  <!-- Template base -->
  <link rel="stylesheet" href="{{ asset('template/css/vertical-layout-light/style.css') }}">

  <link rel="shortcut icon" href="{{ asset('template/images/favicon.png') }}" />

  <script>
    /* Sincroniza el tema con el guardado en las vistas públicas, ANTES de pintar (evita flash) */
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
      --success: #00fff0;
      --warning: #ffb547;

      --font-display: 'Space Grotesk', 'Instrument Sans', sans-serif;
      --font-body: 'Instrument Sans', sans-serif;

      --r-sm: 10px;
      --r-md: 14px;
      --r-lg: 20px;
      --r-xl: 26px;
      --r-pill: 999px;

      --navbar-h: 68px;
      --sidebar-w: 250px;
      --sidebar-w-min: 76px;

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
      --success: #008a80;
      --warning: #d98600;
    }

    /* ==================== RESET / BASE ==================== */
    * { box-sizing: border-box; }

    html, body { height: 100%; }

    body {
      font-family: var(--font-body);
      background-color: var(--bg);
      color: var(--ink);
      transition: background-color 0.4s var(--ease), color 0.4s var(--ease);
      -webkit-font-smoothing: antialiased;
      overflow-x: hidden;
    }

    a { color: inherit; text-decoration: none; }
    ::selection { background: var(--cyan); color: var(--cyan-ink); }

    :focus-visible {
      outline: 2px solid var(--cyan);
      outline-offset: 2px;
      border-radius: 6px;
    }

    @media (prefers-reduced-motion: reduce) {
      *, *::before, *::after {
        animation-duration: 0.001ms !important;
        transition-duration: 0.001ms !important;
      }
    }

    .container-scroller,
    .page-body-wrapper,
    .main-panel,
    .content-wrapper {
      background: var(--bg) !important;
      color: var(--ink);
    }

    /* Compensa el navbar fijo con precisión (evita saltos de layout) */
    .page-body-wrapper {
      padding-top: var(--navbar-h);
    }

    .main-panel {
      display: flex;
      flex-direction: column;
      min-height: calc(100vh - var(--navbar-h));
    }

    .content-wrapper { flex: 1 1 auto; }

    /* ==================== HELPERS DE VISIBILIDAD (sin pisar flex) ==================== */
    /* Sustituyen a las utilidades d-none/d-lg-block de Bootstrap para los ítems
       del navbar cuyo centrado vertical depende de display:flex; así evitamos
       que un !important de Bootstrap rompa el alineado en pantallas grandes. */
    .navbar-nav .nav-item.only-desktop {
      display: none;
    }
    @media (min-width: 992px) {
      .navbar-nav .nav-item.only-desktop {
        display: flex;
      }
    }

    /* ==================== NAVBAR ==================== */
    .navbar.default-layout {
      height: var(--navbar-h) !important;
      min-height: var(--navbar-h) !important;
      background: color-mix(in srgb, var(--bg) 85%, transparent) !important;
      backdrop-filter: blur(18px) saturate(140%);
      -webkit-backdrop-filter: blur(18px) saturate(140%);
      border-bottom: 1px solid var(--line) !important;
      box-shadow: 0 8px 30px -18px var(--shadow);
      padding: 0 !important;
      margin: 0 !important;
      z-index: 1040;
      display: flex !important;
      flex-wrap: nowrap !important;
      align-items: stretch !important;
      transition: background 0.3s var(--ease), border-color 0.3s var(--ease);
    }

    /* Marca (logo + hamburguesa) */
    .navbar.default-layout .navbar-brand-wrapper {
      background: transparent !important;
      border-right: 1px solid var(--line) !important;
      width: var(--sidebar-w);
      height: var(--navbar-h) !important;
      padding: 0 1rem !important;
      display: flex !important;
      align-items: center !important;
      justify-content: flex-start !important;
      gap: 0.85rem;
      flex-shrink: 0;
      transition: width 0.3s var(--ease);
    }

    .navbar.default-layout .navbar-brand-wrapper .navbar-brand {
      display: inline-flex;
      align-items: center;
      line-height: 0;
    }

    .navbar.default-layout .navbar-brand-wrapper .navbar-brand img {
      height: 40px;
      width: auto;
      max-width: 100%;
      object-fit: contain;
      filter: drop-shadow(0 0 10px var(--glow-soft));
      display: block;
    }

    .navbar.default-layout .navbar-brand-wrapper .navbar-brand-mini img {
      height: 30px;
      width: auto;
      display: block;
    }

    /* Menú derecho */
    .navbar.default-layout .navbar-menu-wrapper {
      background: transparent !important;
      height: var(--navbar-h) !important;
      padding: 0 1.25rem !important;
      color: var(--ink);
      display: flex !important;
      align-items: center !important;
      flex: 1 1 auto;
      min-width: 0;
    }

    .navbar.default-layout .navbar-menu-wrapper > ul.navbar-nav {
      display: flex !important;
      align-items: center !important;
      margin: 0;
      padding: 0;
      list-style: none;
      height: 100%;
    }

    .navbar.default-layout .navbar-menu-wrapper > ul.navbar-nav.ms-auto {
      margin-left: auto !important;
    }

    /* Botón hamburguesa */
    .navbar.default-layout .navbar-toggler {
      border: 1px solid var(--line);
      background: var(--surface-alpha);
      color: var(--ink);
      border-radius: var(--r-md);
      width: 40px;
      height: 40px;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      transition: all 0.3s var(--ease);
      padding: 0;
      margin: 0;
    }
    .navbar.default-layout .navbar-toggler:hover {
      border-color: var(--line-strong);
      color: var(--cyan);
      box-shadow: 0 0 18px -6px var(--glow);
    }

    /* ==================== TEXTO DE BIENVENIDA ==================== */
    .navbar-welcome {
      display: flex;
      flex-direction: column;
      justify-content: center;
      padding: 0 0.75rem;
      height: var(--navbar-h);
      max-width: 340px;
      overflow: hidden;
    }

    .navbar-welcome .welcome-text {
      font-family: var(--font-display);
      font-size: 1rem;
      font-weight: 600;
      color: var(--ink);
      margin: 0;
      line-height: 1.15;
      letter-spacing: -0.01em;
      white-space: nowrap;
      overflow: hidden;
      text-overflow: ellipsis;
    }
    .navbar-welcome .welcome-text .highlight {
      color: var(--cyan);
    }
    .navbar-welcome .welcome-sub-text {
      font-size: 0.72rem;
      font-weight: 400;
      color: var(--ink-muted);
      margin: 0;
      line-height: 1.2;
      white-space: nowrap;
      overflow: hidden;
      text-overflow: ellipsis;
    }

    /* ==================== INPUTS NAVBAR ==================== */
    .navbar .datepicker input.form-control,
    .navbar .search-form .form-control {
      background: var(--bg-2) !important;
      border: 1px solid var(--line) !important;
      color: var(--ink) !important;
      border-radius: var(--r-md);
      height: 38px;
      font-size: 0.83rem;
      transition: all 0.25s var(--ease);
    }
    .navbar .datepicker input.form-control:focus,
    .navbar .search-form .form-control:focus {
      border-color: var(--cyan) !important;
      box-shadow: 0 0 0 3px var(--glow-soft) !important;
      outline: none;
    }
    .navbar .datepicker .input-group-text.calendar-icon {
      background: var(--bg-2) !important;
      border: 1px solid var(--line) !important;
      border-right: none !important;
      color: var(--cyan);
      border-radius: var(--r-md) 0 0 var(--r-md);
      height: 38px;
      display: inline-flex;
      align-items: center;
    }
    .navbar .datepicker .form-control {
      border-left: none !important;
      border-radius: 0 var(--r-md) var(--r-md) 0 !important;
    }
    .navbar .datepicker .input-group-addon {
      border-radius: var(--r-md) 0 0 var(--r-md);
      display: flex;
      align-items: center;
    }
    .navbar .datepicker .input-group {
      display: flex;
      align-items: center;
      height: 38px;
    }

    /* Search */
    .navbar .search-form {
      position: relative;
      display: flex;
      align-items: center;
      height: 38px;
      margin: 0;
    }
    .navbar .search-form .icon-search {
      color: var(--ink-faint);
      z-index: 2;
      position: absolute;
      left: 12px;
      top: 50%;
      transform: translateY(-50%);
      font-size: 1rem;
      line-height: 1;
    }
    .navbar .search-form .form-control {
      padding-left: 2.3rem;
      width: 180px;
      transition: width 0.3s var(--ease);
    }
    .navbar .search-form .form-control:focus {
      width: 220px;
    }

    /* Dropdown "Categorías" */
    .navbar .dropdown-toggle.dropdown-bordered {
      background: var(--surface-alpha);
      border: 1px solid var(--line) !important;
      color: var(--ink) !important;
      border-radius: var(--r-pill);
      padding: 0.42rem 1rem;
      font-size: 0.82rem;
      font-weight: 500;
      height: 38px;
      display: inline-flex;
      align-items: center;
      gap: 0.4rem;
      transition: all 0.25s var(--ease);
      line-height: 1;
    }
    .navbar .dropdown-toggle.dropdown-bordered:hover {
      border-color: var(--line-strong) !important;
      color: var(--cyan) !important;
    }
    .navbar .dropdown-toggle.dropdown-bordered::after {
      margin-left: 0.25rem;
      vertical-align: middle;
    }

    /* ==================== ÍTEMS / ICONOS NAVBAR ==================== */
    .navbar .navbar-nav .nav-item {
      display: flex;
      align-items: center;
      height: var(--navbar-h);
      margin: 0 0.15rem;
    }

    .navbar .nav-link {
      display: inline-flex !important;
      align-items: center !important;
      justify-content: center !important;
      padding: 0.4rem !important;
      color: var(--ink-muted);
      line-height: 1;
      transition: color 0.25s ease;
    }

    .navbar .nav-link.count-indicator {
      position: relative;
      width: 40px;
      height: 40px;
      border-radius: var(--r-pill);
      transition: all 0.25s var(--ease);
    }
    .navbar .nav-link.count-indicator:hover {
      background: var(--surface-alpha);
    }
    .navbar .nav-link.count-indicator .icon-mail {
      color: var(--ink-muted);
      font-size: 1.15rem;
      transition: color 0.25s ease;
    }
    .navbar .nav-link.count-indicator:hover .icon-mail { color: var(--cyan); }

    /* Avatar */
    .navbar .user-dropdown .nav-link {
      padding: 0 !important;
      width: 40px;
      height: 40px;
      border-radius: 50%;
    }
    .navbar .user-dropdown .nav-link img.img-xs {
      width: 38px;
      height: 38px;
      border-radius: 50%;
      border: 2px solid var(--line-strong);
      transition: all 0.3s var(--ease);
      object-fit: cover;
      display: block;
    }
    .navbar .user-dropdown .nav-link:hover img.img-xs {
      border-color: var(--cyan);
      box-shadow: 0 0 16px -2px var(--glow);
      transform: scale(1.05);
    }

    /* Dropdown menus */
    .navbar .dropdown-menu.navbar-dropdown {
      background: var(--bg-1) !important;
      border: 1px solid var(--line-strong) !important;
      border-radius: var(--r-md);
      box-shadow: 0 24px 48px -20px var(--shadow);
      padding: 0.5rem;
      min-width: 260px;
      overflow: hidden;
      margin-top: 0.5rem;
    }
    .navbar .dropdown-menu.navbar-dropdown .dropdown-item {
      color: var(--ink) !important;
      border-radius: var(--r-sm);
      padding: 0.65rem 0.85rem;
      transition: all 0.2s var(--ease);
      font-size: 0.86rem;
      display: flex;
      align-items: center;
    }
    .navbar .dropdown-menu.navbar-dropdown .dropdown-item:hover,
    .navbar .dropdown-menu.navbar-dropdown .dropdown-item:focus {
      background: var(--surface-alpha) !important;
      color: var(--cyan) !important;
    }
    .navbar .dropdown-menu.navbar-dropdown .dropdown-item .mdi { color: var(--cyan) !important; }
    .navbar .dropdown-menu.navbar-dropdown .dropdown-divider { border-color: var(--line); }
    .navbar .dropdown-menu.navbar-dropdown .dropdown-header {
      color: var(--ink);
      border-bottom: 1px solid var(--line);
      padding-bottom: 0.75rem;
    }
    .navbar .dropdown-menu.navbar-dropdown .dropdown-header p { color: var(--ink-muted); }
    .navbar .dropdown-menu.navbar-dropdown .preview-subject { color: var(--ink) !important; }
    .navbar .dropdown-menu.navbar-dropdown .small-text { color: var(--ink-faint) !important; }
    .navbar .dropdown-menu.navbar-dropdown .preview-thumbnail {
      display: inline-flex;
      align-items: center;
      justify-content: center;
      width: 38px;
      height: 38px;
      border-radius: 50%;
      background: var(--surface-alpha);
      margin-right: 0.75rem;
      flex-shrink: 0;
    }

    /* ==================== THEME TOGGLE ==================== */
    .ameri-theme-toggle {
      width: 40px;
      height: 40px;
      border-radius: var(--r-pill);
      border: 1px solid var(--line);
      background: var(--surface-alpha);
      display: inline-flex;
      align-items: center;
      justify-content: center;
      cursor: pointer;
      color: var(--ink);
      transition: all 0.3s var(--ease);
      padding: 0;
    }
    .ameri-theme-toggle:hover {
      border-color: var(--line-strong);
      transform: rotate(15deg) scale(1.06);
      color: var(--cyan);
      box-shadow: 0 0 18px -6px var(--glow);
    }
    .ameri-theme-toggle svg { width: 17px; height: 17px; display: block; }
    .ameri-theme-toggle .icon-sun { display: none; }
    html[data-theme="light"] .ameri-theme-toggle .icon-moon { display: none; }
    html[data-theme="light"] .ameri-theme-toggle .icon-sun { display: block; }

    /* ==================== SIDEBAR ==================== */
    .sidebar {
      background: var(--bg-1) !important;
      border-right: 1px solid var(--line) !important;
      width: var(--sidebar-w);
      transition: width 0.3s var(--ease), background 0.3s var(--ease), border-color 0.3s var(--ease);
      padding-top: 0.75rem;
      min-height: calc(100vh - var(--navbar-h));
    }

    .sidebar .nav { padding: 0 0.75rem; }

    .sidebar .nav .nav-item { margin-bottom: 2px; }

    .sidebar .nav .nav-item.nav-category {
      font-family: var(--font-display);
      font-size: 0.68rem;
      font-weight: 700;
      letter-spacing: 0.1em;
      text-transform: uppercase;
      color: var(--ink-faint) !important;
      padding: 1.1rem 0.85rem 0.5rem;
      margin: 0;
      background: transparent !important;
      border: none !important;
    }

    .sidebar .nav .nav-item .nav-link {
      color: var(--ink-muted) !important;
      font-size: 0.86rem;
      font-weight: 500;
      padding: 0.68rem 0.85rem;
      border-radius: var(--r-md);
      display: flex;
      align-items: center;
      gap: 0.75rem;
      transition: all 0.25s var(--ease);
      border: 1px solid transparent;
      position: relative;
    }

    .sidebar .nav .nav-item .nav-link .menu-icon {
      font-size: 1.15rem;
      color: var(--ink-faint);
      transition: color 0.25s ease, transform 0.25s var(--ease);
      min-width: 22px;
      text-align: center;
      line-height: 1;
    }

    .sidebar .nav .nav-item .nav-link .menu-title {
      flex: 1;
      transition: color 0.25s ease;
    }

    .sidebar .nav .nav-item .nav-link .menu-arrow {
      color: var(--ink-faint);
      font-size: 0.75rem;
      transition: transform 0.3s var(--ease), color 0.25s ease;
    }

    .sidebar .nav .nav-item .nav-link:hover {
      background: var(--surface-alpha) !important;
      color: var(--ink) !important;
      border-color: var(--line);
    }
    .sidebar .nav .nav-item .nav-link:hover .menu-icon {
      color: var(--cyan);
      transform: scale(1.1);
    }
    .sidebar .nav .nav-item .nav-link:hover .menu-arrow { color: var(--cyan); }

    .sidebar .nav .nav-item .nav-link.active,
    .sidebar .nav .nav-item .nav-link[aria-expanded="true"] {
      background: linear-gradient(135deg,
        color-mix(in srgb, var(--cyan) 14%, transparent),
        color-mix(in srgb, var(--cyan-2) 8%, transparent)) !important;
      color: var(--ink) !important;
      border-color: var(--line-strong);
      box-shadow: inset 0 0 0 1px var(--line), 0 6px 20px -12px var(--glow);
    }
    .sidebar .nav .nav-item .nav-link.active .menu-icon,
    .sidebar .nav .nav-item .nav-link[aria-expanded="true"] .menu-icon {
      color: var(--cyan);
      text-shadow: 0 0 12px var(--glow);
    }
    .sidebar .nav .nav-item .nav-link.active::before,
    .sidebar .nav .nav-item .nav-link[aria-expanded="true"]::before {
      content: '';
      position: absolute;
      left: 0;
      top: 20%;
      bottom: 20%;
      width: 3px;
      background: var(--cyan);
      border-radius: 3px;
      box-shadow: 0 0 12px var(--glow);
    }

    .sidebar .nav .sub-menu {
      padding: 0.35rem 0 0.35rem 0.9rem;
      margin: 0;
      border-left: 1px dashed var(--line);
      margin-left: 1rem;
    }
    .sidebar .nav .sub-menu .nav-item .nav-link {
      padding: 0.5rem 0.85rem;
      font-size: 0.82rem;
      color: var(--ink-muted) !important;
    }
    .sidebar .nav .sub-menu .nav-item .nav-link:hover {
      color: var(--cyan) !important;
      background: transparent !important;
      padding-left: 1.1rem;
    }

    .sidebar-icon-only .sidebar { width: var(--sidebar-w-min); }
    .sidebar-icon-only .navbar .navbar-brand-wrapper { width: var(--sidebar-w-min); }
    .sidebar-icon-only .sidebar .nav .nav-item .nav-link .menu-title,
    .sidebar-icon-only .sidebar .nav .nav-item .nav-link .menu-arrow { display: none; }
    .sidebar-icon-only .sidebar .nav .nav-item.nav-category {
      font-size: 0;
      padding: 0.5rem 0;
      border-top: 1px solid var(--line);
      margin: 0.5rem 0.75rem;
    }
    .sidebar-icon-only .sidebar .nav .nav-item .nav-link {
      justify-content: center;
      padding: 0.75rem;
    }

    /* ==================== MAIN PANEL ==================== */
    .content-wrapper {
      padding: 1.75rem 1.5rem 2rem;
    }

    /* ==================== CARDS ==================== */
    .card {
      background: var(--bg-1) !important;
      border: 1px solid var(--line) !important;
      border-radius: var(--r-lg);
      box-shadow: 0 20px 40px -28px var(--shadow);
      color: var(--ink);
      overflow: hidden;
      transition: all 0.35s var(--ease);
    }
    .card:hover {
      border-color: var(--line-strong) !important;
      box-shadow: 0 28px 50px -28px var(--shadow), 0 0 0 1px var(--line);
    }
    .card .card-title {
      font-family: var(--font-display);
      font-weight: 600;
      color: var(--ink);
    }
    .card .card-body { color: var(--ink); }
    .card .card-subtitle,
    .card .text-muted { color: var(--ink-muted) !important; }

    /* ==================== TABLAS ==================== */
    .table {
      color: var(--ink) !important;
      background: transparent;
    }
    .table thead th {
      border-bottom: 1px solid var(--line) !important;
      color: var(--ink-muted) !important;
      font-family: var(--font-display);
      font-size: 0.72rem;
      font-weight: 700;
      letter-spacing: 0.06em;
      text-transform: uppercase;
      padding: 0.9rem 0.75rem;
      background: transparent !important;
      vertical-align: middle;
    }
    .table tbody td {
      border-top: 1px solid var(--line) !important;
      color: var(--ink);
      padding: 0.85rem 0.75rem;
      font-size: 0.88rem;
      vertical-align: middle;
    }
    .table tbody tr { transition: background 0.2s ease; }
    .table tbody tr:hover { background: var(--surface-alpha); }
    .table-striped tbody tr:nth-of-type(odd) {
      background: color-mix(in srgb, var(--bg-2) 60%, transparent);
    }

    /* ==================== BOTONES ==================== */
    .btn {
      font-family: inherit;
      font-weight: 600;
      border-radius: var(--r-md);
      transition: all 0.3s var(--ease);
      padding: 0.6rem 1.2rem;
      font-size: 0.85rem;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      gap: 0.4rem;
    }

    .btn-primary,
    .btn-gradient-primary {
      background: linear-gradient(135deg, var(--cyan), var(--cyan-2)) !important;
      border: none !important;
      color: var(--cyan-ink) !important;
      box-shadow: 0 6px 20px -8px var(--glow);
    }
    .btn-primary:hover,
    .btn-gradient-primary:hover {
      transform: translateY(-2px);
      box-shadow: 0 12px 28px -8px var(--glow), 0 0 0 1px var(--cyan) !important;
    }

    .btn-secondary {
      background: var(--bg-2) !important;
      border: 1px solid var(--line) !important;
      color: var(--ink) !important;
    }
    .btn-secondary:hover {
      border-color: var(--line-strong) !important;
      color: var(--cyan) !important;
    }

    .btn-outline-primary {
      background: transparent !important;
      border: 1px solid var(--line-strong) !important;
      color: var(--cyan) !important;
    }
    .btn-outline-primary:hover {
      background: var(--glow-soft) !important;
      border-color: var(--cyan) !important;
      box-shadow: 0 0 20px -6px var(--glow);
    }

    .btn-danger {
      background: var(--error) !important;
      border: none !important;
      color: #fff !important;
    }
    .btn-danger:hover {
      filter: brightness(1.1);
      box-shadow: 0 10px 24px -8px rgba(255, 85, 112, 0.5);
      transform: translateY(-2px);
    }

    .btn-icon {
      width: 36px;
      height: 36px;
      padding: 0;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      border-radius: var(--r-md);
    }

    /* ==================== FORMS ==================== */
    .form-control,
    .form-select,
    textarea.form-control {
      background: var(--bg-2) !important;
      border: 1px solid var(--line) !important;
      color: var(--ink) !important;
      border-radius: var(--r-md);
      padding: 0.65rem 0.9rem;
      font-size: 0.9rem;
      transition: all 0.25s var(--ease);
    }
    .form-control::placeholder { color: var(--ink-faint) !important; }
    .form-control:focus,
    .form-select:focus,
    textarea.form-control:focus {
      background: var(--bg-3) !important;
      border-color: var(--cyan) !important;
      box-shadow: 0 0 0 3px var(--glow-soft), 0 0 18px -8px var(--glow) !important;
      outline: none;
    }
    .form-label {
      display: block;
      color: var(--ink-muted);
      font-size: 0.78rem;
      font-weight: 600;
      font-family: var(--font-display);
      letter-spacing: 0.04em;
      text-transform: uppercase;
      margin-bottom: 0.45rem;
    }
    .input-group-text {
      background: var(--bg-2) !important;
      border: 1px solid var(--line) !important;
      color: var(--ink-muted) !important;
      border-radius: var(--r-md) 0 0 var(--r-md);
      display: flex;
      align-items: center;
    }

    /* ==================== BADGES ==================== */
    .badge {
      font-weight: 600;
      font-size: 0.7rem;
      letter-spacing: 0.03em;
      padding: 0.35rem 0.65rem;
      border-radius: var(--r-pill);
      display: inline-flex;
      align-items: center;
    }
    .badge-primary,
    .badge-gradient-primary {
      background: linear-gradient(135deg, var(--cyan), var(--cyan-2)) !important;
      color: var(--cyan-ink) !important;
    }
    .badge-success { background: var(--success) !important; color: var(--cyan-ink) !important; }
    .badge-warning { background: var(--warning) !important; color: #1a1200 !important; }
    .badge-danger { background: var(--error) !important; color: #fff !important; }

    /* ==================== ALERTAS ==================== */
    .alert {
      border-radius: var(--r-md);
      border: 1px solid var(--line);
      background: var(--bg-1);
      color: var(--ink);
      display: flex;
      align-items: center;
    }
    .alert-success {
      background: color-mix(in srgb, var(--cyan) 8%, var(--bg-1)) !important;
      border-color: var(--line-strong) !important;
      color: var(--cyan) !important;
    }
    .alert-danger {
      background: color-mix(in srgb, var(--error) 8%, var(--bg-1)) !important;
      border-color: color-mix(in srgb, var(--error) 40%, transparent) !important;
      color: var(--error) !important;
    }
    .alert-warning {
      background: color-mix(in srgb, var(--warning) 10%, var(--bg-1)) !important;
      border-color: color-mix(in srgb, var(--warning) 40%, transparent) !important;
      color: var(--warning) !important;
    }

    /* ==================== PAGINACIÓN ==================== */
    .pagination {
      display: flex;
      align-items: center;
    }
    .pagination .page-item .page-link {
      background: var(--bg-1) !important;
      border: 1px solid var(--line) !important;
      color: var(--ink-muted) !important;
      border-radius: var(--r-sm) !important;
      margin: 0 3px;
      transition: all 0.25s var(--ease);
      font-size: 0.85rem;
      display: flex;
      align-items: center;
      justify-content: center;
    }
    .pagination .page-item .page-link:hover {
      border-color: var(--line-strong) !important;
      color: var(--cyan) !important;
    }
    .pagination .page-item.active .page-link {
      background: linear-gradient(135deg, var(--cyan), var(--cyan-2)) !important;
      color: var(--cyan-ink) !important;
      border-color: transparent !important;
      box-shadow: 0 6px 16px -6px var(--glow);
    }

    /* ==================== FOOTER ==================== */
    .footer {
      background: var(--bg-1) !important;
      border-top: 1px solid var(--line) !important;
      color: var(--ink-faint) !important;
      padding: 1rem 1.5rem;
      font-size: 0.8rem;
    }
    .footer a { color: var(--cyan) !important; }
    .footer .d-sm-flex {
      display: flex;
      flex-wrap: wrap;
      align-items: center;
      gap: 0.35rem 1rem;
    }

    /* ==================== SCROLLBAR ==================== */
    ::-webkit-scrollbar { width: 10px; height: 10px; }
    ::-webkit-scrollbar-track { background: var(--bg-1); }
    ::-webkit-scrollbar-thumb {
      background: linear-gradient(var(--cyan-dim), var(--cyan-2));
      border-radius: 10px;
    }
    ::-webkit-scrollbar-thumb:hover { background: var(--cyan); }

    /* ==================== UTILIDADES ==================== */
    .text-primary { color: var(--cyan) !important; }
    .text-muted { color: var(--ink-muted) !important; }
    .border { border-color: var(--line) !important; }
    hr { border-color: var(--line); opacity: 1; }
    .bg-light { background: var(--bg-2) !important; }
    .bg-white { background: var(--bg-1) !important; color: var(--ink) !important; }

    /* DataTables */
    .dataTables_wrapper .dataTables_length select,
    .dataTables_wrapper .dataTables_filter input {
      background: var(--bg-2) !important;
      border: 1px solid var(--line) !important;
      color: var(--ink) !important;
      border-radius: var(--r-md);
    }
    .dataTables_wrapper .dataTables_info,
    .dataTables_wrapper .dataTables_length,
    .dataTables_wrapper .dataTables_filter {
      color: var(--ink-muted) !important;
      font-size: 0.85rem;
    }
    .dataTables_wrapper .dataTables_paginate .paginate_button {
      background: var(--bg-1) !important;
      border: 1px solid var(--line) !important;
      color: var(--ink-muted) !important;
      border-radius: var(--r-sm) !important;
    }
    .dataTables_wrapper .dataTables_paginate .paginate_button.current {
      background: linear-gradient(135deg, var(--cyan), var(--cyan-2)) !important;
      color: var(--cyan-ink) !important;
      border-color: transparent !important;
    }

    /* ==================== RESPONSIVE ==================== */
    @media (max-width: 1199.98px) {
      .navbar .search-form .form-control { width: 140px; }
      .navbar .search-form .form-control:focus { width: 180px; }
    }

    @media (max-width: 991.98px) {
      .navbar.default-layout .navbar-brand-wrapper {
        width: auto;
        min-width: 160px;
      }
      .content-wrapper { padding: 1.25rem 1rem 1.5rem; }
    }

    @media (max-width: 767.98px) {
      .navbar .search-form { display: none; }
    }

    @media (max-width: 575.98px) {
      .navbar-welcome .welcome-text { font-size: 0.85rem; }
      .navbar-welcome .welcome-sub-text { display: none; }
      .content-wrapper { padding: 1rem 0.75rem 1.25rem; }
      .card { border-radius: var(--r-md); }
      .navbar.default-layout .navbar-brand-wrapper { min-width: 120px; }
    }
  </style>

  @stack('styles')
  @yield('css')
</head>
<body>
  <div class="container-scroller">

    <!-- ========== NAVBAR ========== -->
    <nav class="navbar default-layout col-lg-12 col-12 p-0 fixed-top">
      <div class="navbar-brand-wrapper">
        <button class="navbar-toggler" type="button" data-bs-toggle="minimize" aria-label="Alternar menú">
          <span class="icon-menu"></span>
        </button>

        <a class="navbar-brand brand-logo" href="{{ url('/dashboard') }}">
          <img src="{{ asset('img/logo.png') }}" alt="AMERI">
        </a>
      </div>

      <div class="navbar-menu-wrapper">
        <ul class="navbar-nav">
          <li class="nav-item only-desktop ms-0">
            <div class="navbar-welcome">
              <h1 class="welcome-text">Bienvenido, <span class="highlight">{{ Auth::user()->name ?? 'AMERI SRL' }}</span></h1>
            </div>
          </li>
        </ul>

        <ul class="navbar-nav ms-auto">
          <li class="nav-item dropdown only-desktop">
            <a class="nav-link dropdown-bordered dropdown-toggle dropdown-toggle-split" id="messageDropdown" href="#" data-bs-toggle="dropdown" aria-expanded="false">
              Categorías
            </a>
            <div class="dropdown-menu dropdown-menu-right navbar-dropdown preview-list pb-0" aria-labelledby="messageDropdown">
              <a class="dropdown-item py-3"><p class="mb-0 font-weight-medium float-left">Seleccionar categoría</p></a>
              <div class="dropdown-divider"></div>
              <a class="dropdown-item preview-item" href="#">
                <div class="preview-item-content flex-grow py-2">
                  <p class="preview-subject ellipsis font-weight-medium mb-0">Cerámicos</p>
                  <p class="fw-light small-text mb-0">Pisos y paredes</p>
                </div>
              </a>
              <a class="dropdown-item preview-item" href="#">
                <div class="preview-item-content flex-grow py-2">
                  <p class="preview-subject ellipsis font-weight-medium mb-0">Porcelanatos</p>
                  <p class="fw-light small-text mb-0">Alta resistencia</p>
                </div>
              </a>
              <a class="dropdown-item preview-item" href="#">
                <div class="preview-item-content flex-grow py-2">
                  <p class="preview-subject ellipsis font-weight-medium mb-0">Cemento Cola</p>
                  <p class="fw-light small-text mb-0">Adhesivos profesionales</p>
                </div>
              </a>
            </div>
          </li>

          <li class="nav-item only-desktop">
            <div class="input-group date datepicker navbar-date-picker">
              <span class="input-group-addon input-group-prepend border-right">
                <span class="icon-calendar input-group-text calendar-icon"></span>
              </span>
              <input type="text" class="form-control" placeholder="Fecha">
            </div>
          </li>

          <li class="nav-item">
            <form class="search-form" action="#" onsubmit="return false;">
              <i class="icon-search"></i>
              <input type="search" class="form-control" placeholder="Buscar...">
            </form>
          </li>

          <li class="nav-item dropdown">
            <a class="nav-link count-indicator" id="notificationDropdown" href="#" data-bs-toggle="dropdown" aria-expanded="false" aria-label="Notificaciones">
              <i class="icon-mail icon-lg"></i>
            </a>
            <div class="dropdown-menu dropdown-menu-right navbar-dropdown preview-list pb-0" aria-labelledby="notificationDropdown">
              <a class="dropdown-item py-3 border-bottom" href="#">
                <p class="mb-0 font-weight-medium float-left">Tienes 4 notificaciones</p>
                <span class="badge badge-pill badge-primary float-right">Ver todo</span>
              </a>
              <a class="dropdown-item preview-item py-3" href="#">
                <div class="preview-thumbnail"><i class="mdi mdi-alert text-primary"></i></div>
                <div class="preview-item-content">
                  <h6 class="preview-subject fw-normal mb-1">Error de aplicación</h6>
                  <p class="fw-light small-text mb-0">Hace un momento</p>
                </div>
              </a>
              <a class="dropdown-item preview-item py-3" href="#">
                <div class="preview-thumbnail"><i class="mdi mdi-settings text-primary"></i></div>
                <div class="preview-item-content">
                  <h6 class="preview-subject fw-normal mb-1">Configuración</h6>
                  <p class="fw-light small-text mb-0">Mensaje privado</p>
                </div>
              </a>
            </div>
          </li>

          <!-- Theme toggle -->
          <li class="nav-item">
            <button id="ameri-theme-toggle" class="ameri-theme-toggle" aria-label="Cambiar tema" type="button">
              <svg class="icon-moon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M21 12.79A9 9 0 1111.21 3 7 7 0 0021 12.79z"/>
              </svg>
              <svg class="icon-sun" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <circle cx="12" cy="12" r="4"/>
                <path d="M12 2v2M12 20v2M4.93 4.93l1.41 1.41M17.66 17.66l1.41 1.41M2 12h2M20 12h2M4.93 19.07l1.41-1.41M17.66 6.34l1.41-1.41"/>
              </svg>
            </button>
          </li>

          <li class="nav-item dropdown only-desktop user-dropdown">
            <a class="nav-link" id="UserDropdown" href="#" data-bs-toggle="dropdown" aria-expanded="false">
              <img class="img-xs rounded-circle" src="{{ asset('template/images/faces/face8.jpg') }}" alt="Avatar">
            </a>
            <div class="dropdown-menu dropdown-menu-right navbar-dropdown" aria-labelledby="UserDropdown">
              <div class="dropdown-header text-center">
                <img class="img-md rounded-circle" src="{{ asset('template/images/faces/face8.jpg') }}" alt="Avatar">
                <p class="mb-1 mt-3 font-weight-semibold">{{ Auth::user()->name ?? 'Usuario AMERI' }}</p>
                <p class="fw-light text-muted mb-0">{{ Auth::user()->email ?? 'usuario@ameri.com' }}</p>
              </div>
              <a class="dropdown-item" href="#"><i class="dropdown-item-icon mdi mdi-account-outline text-primary me-2"></i> Mi Perfil</a>
              <a class="dropdown-item" href="#"><i class="dropdown-item-icon mdi mdi-message-text-outline text-primary me-2"></i> Mensajes</a>
              <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                <i class="dropdown-item-icon mdi mdi-power text-primary me-2"></i> Cerrar Sesión
              </a>
              <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">@csrf</form>
            </div>
          </li>
        </ul>

        <button class="navbar-toggler navbar-toggler-right d-lg-none" type="button" data-bs-toggle="offcanvas" aria-label="Abrir menú">
          <span class="mdi mdi-menu"></span>
        </button>
      </div>
    </nav>

    <div class="container-fluid page-body-wrapper">

      <!-- ========== SIDEBAR ========== -->
      <nav class="sidebar sidebar-offcanvas" id="sidebar">
        <ul class="nav">
          <li class="nav-item">
            <a class="nav-link" href="{{ url('/dashboard') }}">
              <i class="mdi mdi-grid-large menu-icon"></i>
              <span class="menu-title">Inicio</span>
            </a>
          </li>

          <li class="nav-item nav-category">Usuarios</li>
          <li class="nav-item">
            <a class="nav-link" data-bs-toggle="collapse" href="#ui-basic" aria-expanded="false">
              <i class="menu-icon mdi mdi-account-multiple-outline"></i>
              <span class="menu-title">Usuarios</span>
              <i class="menu-arrow"></i>
            </a>
            <div class="collapse" id="ui-basic">
              <ul class="nav flex-column sub-menu">
                <li class="nav-item"><a class="nav-link" href="{{ route('admin.users.index') }}">Administración</a></li>
                <li class="nav-item"><a class="nav-link" href="#">Clientes</a></li>
              </ul>
            </div>
          </li>

          <li class="nav-item nav-category">Administración</li>
          <li class="nav-item">
            <a class="nav-link" data-bs-toggle="collapse" href="#form-elements" aria-expanded="false">
              <i class="menu-icon mdi mdi-package-variant-closed"></i>
              <span class="menu-title">Catálogo</span>
              <i class="menu-arrow"></i>
            </a>
            <div class="collapse" id="form-elements">
              <ul class="nav flex-column sub-menu">
                <li class="nav-item"><a class="nav-link" href="#">Agregar producto</a></li>
              </ul>
            </div>
          </li>

          <li class="nav-item">
            <a class="nav-link" href="{{ route('admin.suministros.index') }}">
              <i class="menu-icon mdi mdi-truck-delivery-outline"></i>
              <span class="menu-title">Suministros</span>
            </a>
          </li>

          <li class="nav-item">
            <a class="nav-link" data-bs-toggle="collapse" href="#tables" aria-expanded="false">
              <i class="menu-icon mdi mdi-table"></i>
              <span class="menu-title">Reportes</span>
              <i class="menu-arrow"></i>
            </a>
            <div class="collapse" id="tables">
              <ul class="nav flex-column sub-menu">
                <li class="nav-item"><a class="nav-link" href="#">Ver reportes</a></li>
              </ul>
            </div>
          </li>

          <li class="nav-item">
            <a class="nav-link" data-bs-toggle="collapse" href="#icons" aria-expanded="false">
              <i class="menu-icon mdi mdi-cog-outline"></i>
              <span class="menu-title">Configuración</span>
              <i class="menu-arrow"></i>
            </a>
            <div class="collapse" id="icons">
              <ul class="nav flex-column sub-menu">
                <li class="nav-item"><a class="nav-link" href="#">Ajustes generales</a></li>
              </ul>
            </div>
          </li>
        </ul>
      </nav>

      <div class="main-panel">
        <div class="content-wrapper">
          @yield('content')
        </div>

        <footer class="footer">
          <div class="d-sm-flex justify-content-center justify-content-sm-between">
            <span class="text-muted text-center text-sm-left d-block d-sm-inline-block">
              © {{ date('Y') }} AMERI · Cerámicos y Cemento Cola
            </span>
            <span class="float-none float-sm-right d-block mt-1 mt-sm-0 text-center">
              Hecho en Bolivia
            </span>
          </div>
        </footer>
      </div>
    </div>
  </div>

  <!-- ========== SCRIPTS ========== -->
  <script src="{{ asset('template/vendors/js/vendor.bundle.base.js') }}"></script>
  <script src="{{ asset('template/vendors/chart.js/Chart.min.js') }}"></script>
  <script src="{{ asset('template/vendors/bootstrap-datepicker/bootstrap-datepicker.min.js') }}"></script>
  <script src="{{ asset('template/vendors/progressbar.js/progressbar.min.js') }}"></script>
  <script src="{{ asset('template/js/off-canvas.js') }}"></script>
  <script src="{{ asset('template/js/hoverable-collapse.js') }}"></script>
  <script src="{{ asset('template/js/template.js') }}"></script>
  <script src="{{ asset('template/js/settings.js') }}"></script>
  <script src="{{ asset('template/js/todolist.js') }}"></script>
  <script src="{{ asset('template/js/dashboard.js') }}"></script>
  <script src="{{ asset('template/js/Chart.roundedBarCharts.js') }}"></script>

  <!-- jQuery (necesario para DataTables) -->
  <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

  <!-- DataTables JS -->
  <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
  <script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
  <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
  <script src="https://cdn.datatables.net/responsive/2.5.0/js/responsive.bootstrap5.min.js"></script>

  <!-- Toastr JS -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
  <!-- SweetAlert2 -->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

  <script>
    (function () {
      var btn = document.getElementById('ameri-theme-toggle');
      var root = document.documentElement;

      function setTheme(theme) {
        root.setAttribute('data-theme', theme);
        try { localStorage.setItem('ameri-theme', theme); } catch (e) {}
      }

      if (btn) {
        btn.addEventListener('click', function () {
          var current = root.getAttribute('data-theme') === 'light' ? 'light' : 'dark';
          setTheme(current === 'light' ? 'dark' : 'light');
        });
      }

      /* Link activo automático en el sidebar */
      try {
        var path = window.location.pathname;
        document.querySelectorAll('.sidebar .nav-link').forEach(function (link) {
          var href = link.getAttribute('href');
          if (!href || href === '#' || href.indexOf('javascript') === 0) return;
          try {
            var linkPath = new URL(link.href, window.location.origin).pathname;
            if (linkPath === path) {
              link.classList.add('active');
              var parentCollapse = link.closest('.collapse');
              if (parentCollapse) {
                parentCollapse.classList.add('show');
                var toggler = document.querySelector('[href="#' + parentCollapse.id + '"]');
                if (toggler) toggler.setAttribute('aria-expanded', 'true');
              }
            }
          } catch (e) {}
        });
      } catch (e) {}
    })();
  </script>

  @stack('scripts')
  @yield('js')
</body>
</html>
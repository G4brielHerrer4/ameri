{{-- resources/views/layouts/plantilla_maestra.blade.php --}}
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-theme="dark">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <meta name="color-scheme" content="dark light">
  <meta name="theme-color" content="#0a1013">

  <title>@yield('title', config('app.name', 'AMERI'))</title>

  <link rel="preconnect" href="https://fonts.bunny.net">
  <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700|space-grotesk:500,600,700" rel="stylesheet" />

  <!-- Template base CSS -->
  <link rel="stylesheet" href="{{ asset('template/vendors/feather/feather.css') }}">
  <link rel="stylesheet" href="{{ asset('template/vendors/ti-icons/css/themify-icons.css') }}">
  <link rel="stylesheet" href="{{ asset('template/vendors/typicons/typicons.css') }}">
  <link rel="stylesheet" href="{{ asset('template/vendors/simple-line-icons/css/simple-line-icons.css') }}">
  <link rel="stylesheet" href="{{ asset('template/vendors/css/vendor.bundle.base.css') }}">

  <!-- Bootstrap 5 CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

  <!-- MDI -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@mdi/font@7.2.96/css/materialdesignicons.min.css">

  <!-- DataTables -->
  <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
  <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css">

  <!-- Toastr / SweetAlert2 -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

  <!-- ⚠️ TEMPLATE CSS — VA PRIMERO para que tu CSS pueda pisarlo -->
  <link rel="stylesheet" href="{{ asset('template/css/vertical-layout-light/style.css') }}">

  <link rel="shortcut icon" href="{{ asset('template/images/favicon.png') }}" />

  <script>
    (function () {
      try {
        var saved = localStorage.getItem('ameri-theme');
        var theme = (saved === 'light' || saved === 'dark')
          ? saved
          : (window.matchMedia && window.matchMedia('(prefers-color-scheme: light)').matches ? 'light' : 'dark');
        document.documentElement.setAttribute('data-theme', theme);
        var meta = document.querySelector('meta[name="theme-color"]');
        if (meta) meta.setAttribute('content', theme === 'light' ? '#f3f6f7' : '#0a1013');
      } catch (e) {}
    })();
  </script>

  <style>
    /* =====================================================
       1. TOKENS
       ===================================================== */
    :root {
      --bg: #0a1013;
      --bg-1: #0f171b;
      --bg-2: #141e23;
      --bg-3: #1a2830;
      --ink: #e6eff1;
      --ink-muted: #92a7ad;
      --ink-faint: #5f767d;

      --cyan: #00fff0;
      --cyan-2: #00d9cc;
      --cyan-dim: #00a89e;
      --cyan-ink: #00201e;

      --line: rgba(255, 255, 255, 0.07);
      --line-strong: rgba(0, 255, 240, 0.35);
      --glow: rgba(0, 255, 240, 0.30);
      --glow-soft: rgba(0, 255, 240, 0.14);
      --shadow: rgba(0, 0, 0, 0.45);
      --surface-alpha: rgba(255, 255, 255, 0.04);

      --error: #f2667b;
      --success: #2dd4c4;
      --warning: #f5b04c;

      --font-display: 'Space Grotesk', 'Instrument Sans', system-ui, sans-serif;
      --font-body: 'Instrument Sans', system-ui, sans-serif;

      --r-sm: 8px;
      --r-md: 10px;
      --r-lg: 14px;
      --r-pill: 999px;

      --navbar-h: 64px;
      --sidebar-w: 248px;
      --sidebar-w-min: 72px;

      --ease: cubic-bezier(0.4, 0, 0.2, 1);
      --ease-out: cubic-bezier(0.2, 0.8, 0.2, 1);
      --t: 0.18s;
    }

    html[data-theme="light"] {
      --bg: #f3f6f7;
      --bg-1: #ffffff;
      --bg-2: #eef2f4;
      --bg-3: #e3eaed;
      --ink: #0f1f24;
      --ink-muted: #52666d;
      --ink-faint: #81959b;

      --cyan: #0d9488;
      --cyan-2: #0f766e;
      --cyan-dim: #115e59;
      --cyan-ink: #ffffff;

      --line: rgba(15, 40, 48, 0.10);
      --line-strong: rgba(13, 148, 136, 0.40);
      --glow: rgba(13, 148, 136, 0.25);
      --glow-soft: rgba(13, 148, 136, 0.12);
      --shadow: rgba(15, 40, 48, 0.10);
      --surface-alpha: rgba(15, 40, 48, 0.04);

      --error: #d63a52;
      --success: #0d9488;
      --warning: #c77700;
    }

    /* =====================================================
       1.5 OVERRIDE TOTAL DEL TEMPLATE
       (Rompe el CSS del template que fuerza fondo blanco)
       ===================================================== */
    html,
    body,
    .container-scroller,
    .page-body-wrapper,
    .main-panel,
    .content-wrapper {
      background: var(--bg) !important;
      background-color: var(--bg) !important;
      color: var(--ink) !important;
    }

    .card,
    .navbar,
    .navbar.default-layout,
    .sidebar,
    .footer {
      background: var(--bg-1) !important;
      background-color: var(--bg-1) !important;
      color: var(--ink) !important;
    }

    /* El navbar con transparencia */
    .app-navbar,
    .navbar.default-layout {
      background: color-mix(in srgb, var(--bg) 88%, transparent) !important;
      background-color: color-mix(in srgb, var(--bg) 88%, transparent) !important;
    }

    /* Cualquier wrapper interno que pinte blanco */
    .container-scroller > *,
    .page-body-wrapper > *,
    .main-panel > * {
      background-color: transparent !important;
    }

    /* Textos */
    h1, h2, h3, h4, h5, h6 {
      color: var(--ink) !important;
    }

    p, span, small, label, div, li, a {
      color: inherit;
    }

    .text-muted {
      color: var(--ink-muted) !important;
    }

    /* Inputs por si el template los fuerza */
    input,
    select,
    textarea,
    .form-control,
    .form-select {
      background-color: var(--bg-2) !important;
      color: var(--ink) !important;
      border-color: var(--line) !important;
    }

    /* Tablas */
    .table {
      color: var(--ink) !important;
      background-color: transparent !important;
    }

    .table thead th,
    .table tbody td {
      background-color: transparent !important;
      color: var(--ink) !important;
      border-color: var(--line) !important;
    }

    /* Dropdowns */
    .dropdown-menu {
      background-color: var(--bg-1) !important;
      border-color: var(--line) !important;
      color: var(--ink) !important;
    }

    .dropdown-item {
      color: var(--ink) !important;
    }

    /* Mata los pseudo-elementos del template */
    .page-body-wrapper::before,
    .page-body-wrapper::after,
    .main-panel::before,
    .main-panel::after,
    .container-scroller::before,
    .container-scroller::after {
      display: none !important;
    }

    /* =====================================================
       2. BASE
       ===================================================== */
    * { box-sizing: border-box; }
    html, body { height: 100%; }

    body {
      font-family: var(--font-body);
      background-color: var(--bg) !important;
      color: var(--ink) !important;
      transition: background-color var(--t) var(--ease), color var(--t) var(--ease);
      -webkit-font-smoothing: antialiased;
      overflow-x: hidden;
      margin: 0;
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

    /* =====================================================
       3. LAYOUT
       ===================================================== */
    .app-shell {
      display: grid;
      grid-template-columns: var(--sidebar-w) minmax(0, 1fr);
      grid-template-rows: var(--navbar-h) 1fr;
      grid-template-areas:
        "navbar navbar"
        "sidebar main";
      min-height: 100vh;
      transition: grid-template-columns var(--t) var(--ease);
      background-color: var(--bg) !important;
    }

    .app-shell.sidebar-collapsed {
      grid-template-columns: var(--sidebar-w-min) minmax(0, 1fr);
    }

    .app-navbar  { grid-area: navbar; }
    .app-sidebar { grid-area: sidebar; }
    .app-main    { grid-area: main; min-width: 0; display: flex; flex-direction: column; background-color: transparent !important; }

    /* =====================================================
       4. NAVBAR
       ===================================================== */
    .app-navbar {
      position: sticky;
      top: 0;
      height: var(--navbar-h);
      background: color-mix(in srgb, var(--bg) 88%, transparent) !important;
      backdrop-filter: blur(12px);
      -webkit-backdrop-filter: blur(12px);
      border-bottom: 1px solid var(--line);
      display: flex;
      align-items: stretch;
      z-index: 1040;
      min-width: 0;
    }

    .navbar-brand-wrapper {
      width: var(--sidebar-w);
      flex-shrink: 0;
      display: flex;
      align-items: center;
      gap: 0.75rem;
      padding: 0 1rem;
      border-right: 1px solid var(--line);
      transition: width var(--t) var(--ease);
      min-width: 0;
      background-color: transparent !important;
    }
    .app-shell.sidebar-collapsed .navbar-brand-wrapper {
      width: var(--sidebar-w-min);
      justify-content: center;
      padding: 0 0.5rem;
    }

    .navbar-brand {
      display: inline-flex;
      align-items: center;
      line-height: 0;
      overflow: hidden;
    }
    .navbar-brand img {
      height: 36px;
      width: auto;
      max-width: 100%;
      object-fit: contain;
      display: block;
    }

    .navbar-menu-wrapper {
      flex: 1 1 auto;
      min-width: 0;
      display: flex;
      align-items: center;
      gap: 0.75rem;
      padding: 0 clamp(0.75rem, 2vw, 1.25rem);
      overflow: visible;
      background-color: transparent !important;
    }

    .navbar-toggler,
    .ameri-theme-toggle,
    .nav-link.count-indicator {
      width: 38px;
      height: 38px;
      padding: 0;
      margin: 0;
      border-radius: var(--r-md);
      border: 1px solid transparent;
      background: transparent;
      color: var(--ink-muted);
      display: inline-flex;
      align-items: center;
      justify-content: center;
      cursor: pointer;
      flex-shrink: 0;
      transition: background var(--t) var(--ease), color var(--t) var(--ease), border-color var(--t) var(--ease);
    }
    .navbar-toggler:hover,
    .ameri-theme-toggle:hover,
    .nav-link.count-indicator:hover {
      background: var(--surface-alpha);
      color: var(--ink);
      border-color: var(--line);
    }
    .navbar-toggler .mdi { font-size: 1.4rem; line-height: 1; }

    .navbar-welcome {
      display: flex;
      flex-direction: column;
      justify-content: center;
      min-width: 0;
      max-width: clamp(160px, 26vw, 420px);
      flex: 1 1 auto;
      overflow: hidden;
    }
    .navbar-welcome .welcome-text {
      font-family: var(--font-display);
      font-size: 0.98rem;
      font-weight: 600;
      color: var(--ink) !important;
      margin: 0;
      line-height: 1.2;
      white-space: nowrap;
      overflow: hidden;
      text-overflow: ellipsis;
    }
    .navbar-welcome .welcome-text .highlight { color: var(--cyan); }

    .navbar-menu-wrapper > .navbar-nav {
      display: flex;
      flex-direction: row;
      align-items: center;
      margin: 0;
      padding: 0;
      list-style: none;
      height: 100%;
      gap: 0.15rem;
    }
    .navbar-menu-wrapper > .navbar-nav.ms-auto { margin-left: auto; }

    .app-navbar .nav-item {
      display: flex;
      align-items: center;
      height: var(--navbar-h);
      margin: 0;
      position: relative;
    }

    .app-navbar .nav-link {
      display: inline-flex;
      align-items: center;
      justify-content: center;
      padding: 0.4rem;
      color: var(--ink-muted);
      line-height: 1;
      transition: color var(--t) var(--ease);
      cursor: pointer;
    }
    .app-navbar .nav-link::after { display: none !important; }

    .app-navbar .dropdown-menu {
      position: absolute !important;
      top: 100% !important;
      right: 0 !important;
      left: auto !important;
      margin-top: 0.25rem !important;
    }

    /* Fecha */
    .navbar-date-wrapper { position: relative; display: inline-flex; align-items: center; }
    .navbar-date-btn {
      display: inline-flex;
      align-items: center;
      gap: 0.5rem;
      height: 38px;
      padding: 0 0.85rem;
      background: var(--bg-2) !important;
      border: 1px solid var(--line);
      border-radius: var(--r-md);
      color: var(--ink) !important;
      font-size: 0.83rem;
      font-weight: 500;
      cursor: pointer;
      white-space: nowrap;
      transition: border-color var(--t) var(--ease), background var(--t) var(--ease);
    }
    .navbar-date-btn:hover,
    .navbar-date-btn[aria-expanded="true"] { border-color: var(--line-strong); }
    .navbar-date-btn .mdi { color: var(--cyan); font-size: 1.05rem; }

    /* Paneles flotantes */
    .custom-calendar,
    .search-results {
      position: absolute;
      top: calc(100% + 8px);
      right: 0;
      background: var(--bg-1) !important;
      border: 1px solid var(--line);
      border-radius: var(--r-lg);
      box-shadow: 0 16px 40px -12px var(--shadow);
      z-index: 1050;
      opacity: 0;
      visibility: hidden;
      transform: translateY(-4px);
      pointer-events: none;
      transition: opacity var(--t) var(--ease), transform var(--t) var(--ease), visibility var(--t);
    }
    .custom-calendar.open,
    .search-results.open {
      opacity: 1;
      visibility: visible;
      transform: translateY(0);
      pointer-events: auto;
    }

    /* Calendario */
    .custom-calendar { padding: 0.85rem; width: 292px; }
    .cal-header {
      display: flex;
      align-items: center;
      justify-content: space-between;
      gap: 0.5rem;
      margin-bottom: 0.65rem;
    }
    .cal-nav-btn {
      width: 30px;
      height: 30px;
      border-radius: var(--r-sm);
      border: 1px solid transparent;
      background: transparent;
      color: var(--ink-muted);
      cursor: pointer;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      padding: 0;
      font-size: 1.1rem;
      transition: all var(--t) var(--ease);
    }
    .cal-nav-btn:hover { background: var(--surface-alpha); color: var(--ink); border-color: var(--line); }
    .cal-month-year {
      font-family: var(--font-display);
      font-weight: 600;
      color: var(--ink) !important;
      font-size: 0.9rem;
      text-align: center;
      flex: 1;
    }
    .cal-weekdays,
    .cal-days { display: grid; grid-template-columns: repeat(7, 1fr); gap: 2px; }
    .cal-weekdays span {
      text-align: center;
      font-size: 0.72rem;
      font-weight: 600;
      color: var(--ink-faint);
      padding: 0.3rem 0;
    }
    .cal-day {
      aspect-ratio: 1;
      border: 1px solid transparent;
      background: transparent;
      color: var(--ink) !important;
      font-size: 0.8rem;
      border-radius: var(--r-sm);
      cursor: pointer;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 0;
      transition: background var(--t) var(--ease);
    }
    .cal-day:hover { background: var(--surface-alpha); }
    .cal-day.otro-mes { color: var(--ink-faint) !important; opacity: 0.5; }
    .cal-day.hoy { background: var(--cyan); color: var(--cyan-ink) !important; font-weight: 700; }
    .cal-day.seleccionado:not(.hoy) { border-color: var(--line-strong); color: var(--cyan) !important; font-weight: 600; }
    .cal-footer {
      display: flex;
      justify-content: center;
      margin-top: 0.65rem;
      padding-top: 0.65rem;
      border-top: 1px solid var(--line);
    }
    .cal-today-btn {
      background: transparent;
      border: none;
      color: var(--cyan);
      font-size: 0.8rem;
      font-weight: 600;
      padding: 0.3rem 0.75rem;
      border-radius: var(--r-sm);
      cursor: pointer;
      transition: background var(--t) var(--ease);
    }
    .cal-today-btn:hover { background: var(--glow-soft); }

    /* Buscador */
    .navbar-search-wrapper { position: relative; display: flex; align-items: center; height: 38px; }
    .navbar-search-wrapper .search-form { position: relative; display: flex; align-items: center; margin: 0; }
    .navbar-search-wrapper .icon-search {
      position: absolute;
      left: 12px;
      top: 50%;
      transform: translateY(-50%);
      color: var(--ink-faint);
      font-size: 0.95rem;
      pointer-events: none;
      z-index: 2;
    }
    .navbar-search-wrapper .form-control {
      background: var(--bg-2) !important;
      border: 1px solid var(--line) !important;
      color: var(--ink) !important;
      border-radius: var(--r-md);
      height: 38px;
      font-size: 0.85rem;
      padding: 0 3.4rem 0 2.35rem;
      width: 230px;
      transition: width 0.25s var(--ease), border-color var(--t) var(--ease), box-shadow var(--t) var(--ease);
    }
    .navbar-search-wrapper .form-control:focus {
      width: 290px;
      border-color: var(--cyan) !important;
      box-shadow: 0 0 0 3px var(--glow-soft) !important;
      outline: none;
    }
    .search-kbd {
      position: absolute;
      right: 10px;
      top: 50%;
      transform: translateY(-50%);
      font-family: var(--font-body);
      font-size: 0.68rem;
      color: var(--ink-faint);
      border: 1px solid var(--line);
      border-radius: 5px;
      padding: 1px 5px;
      pointer-events: none;
      transition: opacity var(--t);
    }
    .navbar-search-wrapper .form-control:focus ~ .search-kbd { opacity: 0; }

    .search-results { width: 360px; max-height: 400px; overflow-y: auto; padding: 0.4rem; }
    .search-results-header {
      padding: 0.4rem 0.65rem;
      font-size: 0.75rem;
      font-weight: 600;
      color: var(--ink-faint);
    }
    .search-result-item {
      display: flex;
      align-items: center;
      gap: 0.7rem;
      padding: 0.55rem 0.65rem;
      border-radius: var(--r-sm);
      color: var(--ink);
      transition: background var(--t) var(--ease);
    }
    .search-result-item:hover,
    .search-result-item.active { background: var(--surface-alpha); color: var(--ink); }
    .search-result-item.active { box-shadow: inset 2px 0 0 var(--cyan); }
    .search-result-item .result-icon {
      width: 32px;
      height: 32px;
      border-radius: var(--r-sm);
      background: var(--glow-soft);
      color: var(--cyan);
      display: inline-flex;
      align-items: center;
      justify-content: center;
      font-size: 1rem;
      flex-shrink: 0;
    }
    .search-result-item .result-info { flex: 1; min-width: 0; }
    .search-result-item .result-title {
      font-weight: 600;
      font-size: 0.85rem;
      margin: 0;
      white-space: nowrap;
      overflow: hidden;
      text-overflow: ellipsis;
    }
    .search-result-item .result-path {
      font-size: 0.72rem;
      color: var(--ink-faint);
      margin: 0;
      white-space: nowrap;
      overflow: hidden;
      text-overflow: ellipsis;
    }
    .search-empty { padding: 1.5rem 1rem; text-align: center; color: var(--ink-muted); font-size: 0.85rem; }
    .search-empty .mdi { display: block; font-size: 1.8rem; color: var(--ink-faint); margin-bottom: 0.25rem; }

    /* =====================================================
       5. NOTIFICACIONES
       ===================================================== */
    .notif-bell { position: relative; }
    .notif-bell .mdi { font-size: 1.3rem; line-height: 1; display: block; }
    .notif-badge {
      position: absolute;
      top: 1px;
      right: 1px;
      min-width: 17px;
      height: 17px;
      padding: 0 4px;
      border-radius: var(--r-pill);
      background: var(--error);
      color: #fff;
      font-size: 0.65rem;
      font-weight: 700;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      line-height: 1;
      box-shadow: 0 0 0 2px var(--bg);
    }

    .navbar-dropdown {
      background: var(--bg-1) !important;
      border: 1px solid var(--line) !important;
      border-radius: var(--r-lg) !important;
      box-shadow: 0 16px 40px -12px var(--shadow) !important;
      padding: 0.4rem !important;
      min-width: 250px;
      z-index: 1060;
    }
    .navbar-dropdown .dropdown-item {
      color: var(--ink) !important;
      border-radius: var(--r-sm);
      padding: 0.6rem 0.8rem;
      font-size: 0.86rem;
      display: flex;
      align-items: center;
      transition: background var(--t) var(--ease);
    }
    .navbar-dropdown .dropdown-item:hover {
      background: var(--surface-alpha) !important;
      color: var(--ink) !important;
    }
    .navbar-dropdown .dropdown-item .mdi { color: var(--cyan) !important; }
    .navbar-dropdown .dropdown-header {
      color: var(--ink);
      border-bottom: 1px solid var(--line);
      padding: 0.5rem 0.5rem 0.9rem;
      margin-bottom: 0.4rem;
    }
    .navbar-dropdown .dropdown-header p { color: var(--ink-muted); margin: 0; }
    .navbar-dropdown .dropdown-header .img-md {
      width: 60px;
      height: 60px;
      border-radius: 50%;
      object-fit: cover;
      border: 2px solid var(--line-strong);
    }

    .notif-dropdown { min-width: 370px !important; padding: 0 !important; overflow: hidden; }
    .notif-dropdown-header {
      display: flex;
      align-items: center;
      justify-content: space-between;
      gap: 0.75rem;
      padding: 0.9rem 1.1rem;
      border-bottom: 1px solid var(--line);
    }
    .notif-header-left { display: flex; align-items: center; gap: 0.65rem; }
    .notif-header-left .mdi { color: var(--cyan) !important; font-size: 1.3rem; }
    .notif-header-left h6 {
      font-family: var(--font-display);
      font-weight: 600;
      color: var(--ink) !important;
      font-size: 0.92rem;
      margin: 0;
      line-height: 1.2;
    }
    .notif-header-left span { font-size: 0.75rem; color: var(--ink-muted); }
    .notif-header-left span strong { color: var(--cyan); font-weight: 600; }
    .notif-mark-all {
      width: 30px;
      height: 30px;
      border-radius: var(--r-sm);
      border: 1px solid var(--line);
      background: transparent;
      color: var(--ink-muted);
      cursor: pointer;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      padding: 0;
      font-size: 1rem;
      transition: all var(--t) var(--ease);
    }
    .notif-mark-all:hover { color: var(--cyan); border-color: var(--line-strong); }
    .notif-dropdown-list { max-height: 340px; overflow-y: auto; }

    .notif-dropdown-item {
      display: flex;
      align-items: flex-start;
      gap: 0.75rem;
      padding: 0.8rem 1.1rem;
      border-bottom: 1px solid var(--line);
      color: inherit;
      transition: background var(--t) var(--ease);
    }
    .notif-dropdown-item:last-child { border-bottom: none; }
    .notif-dropdown-item:hover { background: var(--surface-alpha); color: inherit; }
    .notif-dropdown-item.is-unread { background: var(--glow-soft); }

    .notif-icon-wrap {
      width: 34px;
      height: 34px;
      border-radius: var(--r-sm);
      flex-shrink: 0;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      font-size: 1.05rem;
      background: var(--glow-soft);
      color: var(--cyan);
    }
    .notif-icon-wrap.tipo-prestado {
      background: color-mix(in srgb, var(--warning) 14%, transparent);
      color: var(--warning);
    }
    .notif-content { flex: 1; min-width: 0; }
    .notif-title {
      font-weight: 600;
      color: var(--ink) !important;
      font-size: 0.84rem;
      margin: 0 0 0.15rem;
      white-space: nowrap;
      overflow: hidden;
      text-overflow: ellipsis;
    }
    .notif-msg {
      font-size: 0.78rem;
      color: var(--ink-muted) !important;
      margin: 0 0 0.3rem;
      line-height: 1.4;
      display: -webkit-box;
      -webkit-line-clamp: 2;
      -webkit-box-orient: vertical;
      overflow: hidden;
    }
    .notif-meta-line {
      display: flex;
      align-items: center;
      gap: 0.75rem;
      flex-wrap: wrap;
      font-size: 0.72rem;
      color: var(--ink-faint);
    }
    .notif-meta-line .mdi { font-size: 0.8rem; }
    .notif-from, .notif-time { display: inline-flex; align-items: center; gap: 0.25rem; }
    .notif-from { color: var(--cyan); font-weight: 500; }
    .notif-dot { width: 8px; height: 8px; border-radius: 50%; background: var(--cyan); flex-shrink: 0; margin-top: 0.4rem; }

    .notif-empty { padding: 2.25rem 1.5rem; text-align: center; }
    .notif-empty .mdi { font-size: 2.6rem; color: var(--ink-faint); opacity: 0.5; display: block; margin-bottom: 0.4rem; }
    .notif-empty-title { font-weight: 600; color: var(--ink); font-size: 0.9rem; margin: 0 0 0.2rem; }
    .notif-empty-sub { font-size: 0.78rem; color: var(--ink-muted); display: block; line-height: 1.4; }

    .notif-dropdown-footer {
      display: flex !important;
      align-items: center !important;
      justify-content: center !important;
      gap: 0.35rem;
      padding: 0.75rem 1.1rem !important;
      border-top: 1px solid var(--line);
      color: var(--cyan) !important;
      font-weight: 600;
      font-size: 0.82rem;
      border-radius: 0 !important;
      transition: background var(--t) var(--ease);
    }
    .notif-dropdown-footer:hover { background: var(--surface-alpha) !important; }
    .notif-dropdown-footer .mdi { color: var(--cyan) !important; }

    /* =====================================================
       6. TEMA + USUARIO
       ===================================================== */
    .ameri-theme-toggle svg { width: 18px; height: 18px; display: block; }
    .ameri-theme-toggle .icon-sun { display: none; }
    html[data-theme="light"] .ameri-theme-toggle .icon-moon { display: none; }
    html[data-theme="light"] .ameri-theme-toggle .icon-sun { display: block; }

    .user-dropdown .nav-link {
      padding: 0 !important;
      width: 38px;
      height: 38px;
      border-radius: 50%;
    }
    .user-dropdown .nav-link img.img-xs {
      width: 36px;
      height: 36px;
      border-radius: 50%;
      border: 2px solid var(--line);
      object-fit: cover;
      display: block;
      transition: border-color var(--t) var(--ease);
    }
    .user-dropdown .nav-link:hover img.img-xs,
    .user-dropdown .nav-link[aria-expanded="true"] img.img-xs { border-color: var(--cyan); }

    .user-dropdown .dropdown-header {
      padding: 0.5rem 0.5rem 1rem !important;
      margin-bottom: 0.35rem;
    }
    .user-dropdown .dropdown-header .font-weight-semibold {
      font-family: var(--font-display);
      font-weight: 600 !important;
      color: var(--ink);
      font-size: 0.9rem;
    }

    /* =====================================================
       7. SIDEBAR
       ===================================================== */
    .app-sidebar {
      background: var(--bg-1) !important;
      border-right: 1px solid var(--line);
      overflow-y: auto;
      overflow-x: hidden;
      padding: 0.75rem 0 1.5rem;
      height: calc(100vh - var(--navbar-h));
      position: sticky;
      top: var(--navbar-h);
      scrollbar-width: thin;
      scrollbar-color: var(--bg-3) transparent;
      transition: width var(--t) var(--ease);
    }
    .app-sidebar::-webkit-scrollbar { width: 6px; }
    .app-sidebar::-webkit-scrollbar-thumb { background: var(--bg-3); border-radius: 6px; }

    .app-sidebar .nav {
      padding: 0 0.75rem;
      display: flex;
      flex-direction: column;
      gap: 0.15rem;
    }

    .app-sidebar .nav-category {
      position: relative;
      display: flex;
      align-items: center;
      gap: 0.5rem;
      font-family: var(--font-display);
      font-size: 0.68rem;
      font-weight: 700;
      letter-spacing: 0.12em;
      text-transform: uppercase;
      color: var(--ink-faint) !important;
      margin: 1.1rem 0 0.35rem;
      padding: 0 0.85rem 0 0.85rem;
      background: transparent !important;
      border: none;
      list-style: none;
      user-select: none;
    }
    .app-sidebar .nav-category::after {
      content: '';
      flex: 1;
      height: 1px;
      background: linear-gradient(to right, var(--line), transparent 90%);
      margin-left: 0.25rem;
    }
    .app-sidebar .nav > .nav-category:first-child { margin-top: 0.25rem; }

    .app-sidebar .nav-item {
      list-style: none;
      margin: 0;
    }
    .app-sidebar .nav-item .nav-link {
      color: var(--ink-muted) !important;
      font-size: 0.87rem;
      font-weight: 500;
      padding: 0.6rem 0.85rem;
      border-radius: var(--r-md);
      display: flex;
      align-items: center;
      gap: 0.7rem;
      border: 1px solid transparent;
      position: relative;
      transition: background var(--t) var(--ease), color var(--t) var(--ease);
      min-width: 0;
      cursor: pointer;
    }
    .app-sidebar .nav-item .nav-link .menu-icon {
      font-size: 1.15rem;
      color: var(--ink-faint);
      min-width: 22px;
      text-align: center;
      line-height: 1;
      flex-shrink: 0;
      transition: color var(--t) var(--ease);
    }
    .app-sidebar .nav-item .nav-link .menu-title {
      flex: 1;
      white-space: nowrap;
      overflow: hidden;
      text-overflow: ellipsis;
    }
    .app-sidebar .nav-item .nav-link .menu-arrow {
      color: var(--ink-faint);
      font-size: 0.85rem;
      flex-shrink: 0;
      transition: transform var(--t) var(--ease), color var(--t) var(--ease);
    }
    .app-sidebar .nav-item .nav-link[aria-expanded="true"] .menu-arrow {
      transform: rotate(90deg);
      color: var(--cyan);
    }
    .app-sidebar .nav-item .nav-link:hover {
      background: var(--surface-alpha) !important;
      color: var(--ink) !important;
    }
    .app-sidebar .nav-item .nav-link:hover .menu-icon { color: var(--cyan); }
    .app-sidebar .nav-item .nav-link.active {
      background: var(--glow-soft) !important;
      color: var(--ink) !important;
      font-weight: 600;
    }
    .app-sidebar .nav-item .nav-link.active .menu-icon { color: var(--cyan); }
    .app-sidebar .nav-item .nav-link.active::before {
      content: '';
      position: absolute;
      left: -0.75rem;
      top: 22%;
      bottom: 22%;
      width: 3px;
      background: var(--cyan);
      border-radius: 0 3px 3px 0;
    }
    .app-sidebar .nav-item .nav-link[aria-expanded="true"] { color: var(--ink) !important; }
    .app-sidebar .nav-item .nav-link[aria-expanded="true"] .menu-icon { color: var(--cyan); }

    .app-sidebar .sub-menu {
      list-style: none;
      padding: 0.35rem 0 0.35rem 0;
      margin: 0 0 0.35rem 1.85rem;
      position: relative;
    }
    .app-sidebar .sub-menu::before {
      content: '';
      position: absolute;
      left: 0;
      top: 0.35rem;
      bottom: 0.35rem;
      width: 1px;
      background: var(--line);
    }
    .app-sidebar .sub-menu .nav-item .nav-link {
      padding: 0.45rem 0.85rem;
      font-size: 0.83rem;
      font-weight: 500;
      border-radius: var(--r-sm);
      color: var(--ink-muted) !important;
      gap: 0;
    }
    .app-sidebar .sub-menu .nav-item .nav-link::after {
      content: '';
      position: absolute;
      left: -0.5rem;
      top: 50%;
      transform: translateY(-50%);
      width: 5px;
      height: 5px;
      border-radius: 50%;
      background: var(--line);
      transition: background var(--t) var(--ease), transform var(--t) var(--ease);
    }
    .app-sidebar .sub-menu .nav-item .nav-link:hover {
      color: var(--cyan) !important;
      background: transparent !important;
    }
    .app-sidebar .sub-menu .nav-item .nav-link:hover::after {
      background: var(--cyan);
      transform: translateY(-50%) scale(1.4);
    }
    .app-sidebar .sub-menu .nav-item .nav-link.active {
      background: transparent !important;
      color: var(--cyan) !important;
      font-weight: 600;
    }
    .app-sidebar .sub-menu .nav-item .nav-link.active::before { display: none; }
    .app-sidebar .sub-menu .nav-item .nav-link.active::after {
      background: var(--cyan);
      transform: translateY(-50%) scale(1.4);
    }

    .app-shell.sidebar-collapsed .app-sidebar .menu-title,
    .app-shell.sidebar-collapsed .app-sidebar .menu-arrow { display: none; }
    .app-shell.sidebar-collapsed .app-sidebar .nav-category {
      font-size: 0;
      padding: 0;
      margin: 0.5rem 0.5rem;
      height: 1px;
      background: var(--line) !important;
    }
    .app-shell.sidebar-collapsed .app-sidebar .nav-category::after { display: none; }
    .app-shell.sidebar-collapsed .app-sidebar .nav-item .nav-link {
      justify-content: center;
      padding: 0.7rem;
    }
    .app-shell.sidebar-collapsed .app-sidebar .nav-item .nav-link.active::before { display: none; }
    .app-shell.sidebar-collapsed .app-sidebar .sub-menu { display: none !important; }

    .sidebar-backdrop {
      position: fixed;
      inset: var(--navbar-h) 0 0 0;
      background: rgba(0, 0, 0, 0.5);
      z-index: 1030;
      opacity: 0;
      visibility: hidden;
      transition: opacity var(--t) var(--ease), visibility var(--t);
    }
    .sidebar-backdrop.show { opacity: 1; visibility: visible; }

    /* =====================================================
       8. CONTENIDO + FOOTER
       ===================================================== */
    .content-wrapper {
      flex: 1 1 auto;
      padding: clamp(1rem, 2vw, 1.75rem) clamp(0.75rem, 2vw, 1.75rem) 2rem;
      width: 100%;
      min-width: 0;
      background-color: transparent !important;
    }
    .footer {
      background: var(--bg-1) !important;
      border-top: 1px solid var(--line);
      color: var(--ink-faint) !important;
      padding: 0.9rem clamp(1rem, 2vw, 1.5rem);
      font-size: 0.8rem;
    }
    .footer a { color: var(--cyan); }
    .footer .footer-inner {
      display: flex;
      flex-wrap: wrap;
      gap: 0.25rem 1rem;
      justify-content: space-between;
      align-items: center;
    }

    /* =====================================================
       9. COMPONENTES
       ===================================================== */
    .card {
      background: var(--bg-1) !important;
      border: 1px solid var(--line) !important;
      border-radius: var(--r-lg);
      box-shadow: 0 1px 2px var(--shadow);
      color: var(--ink) !important;
      overflow: hidden;
    }
    .card .card-title { font-family: var(--font-display); font-weight: 600; color: var(--ink) !important; }
    .card .card-body { color: var(--ink) !important; background-color: transparent !important; }
    .card .card-subtitle, .card .text-muted { color: var(--ink-muted) !important; }

    .table { color: var(--ink) !important; background: transparent !important; }
    .table thead th {
      border-bottom: 1px solid var(--line) !important;
      color: var(--ink-muted) !important;
      font-size: 0.8rem;
      font-weight: 600;
      padding: 0.8rem 0.75rem;
      background: transparent !important;
      vertical-align: middle;
    }
    .table tbody td {
      border-top: 1px solid var(--line) !important;
      color: var(--ink) !important;
      padding: 0.8rem 0.75rem;
      font-size: 0.88rem;
      vertical-align: middle;
      background-color: transparent !important;
    }
    .table tbody tr:hover { background: var(--surface-alpha) !important; }
    .table-striped tbody tr:nth-of-type(odd) { background: color-mix(in srgb, var(--bg-2) 55%, transparent) !important; }

    .btn {
      font-family: inherit;
      font-weight: 600;
      border-radius: var(--r-md);
      padding: 0.55rem 1.1rem;
      font-size: 0.86rem;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      gap: 0.4rem;
      transition: background var(--t) var(--ease), border-color var(--t) var(--ease), color var(--t) var(--ease), filter var(--t) var(--ease);
    }
    .btn-primary, .btn-gradient-primary {
      background: var(--cyan) !important;
      border: 1px solid var(--cyan) !important;
      color: var(--cyan-ink) !important;
    }
    .btn-primary:hover, .btn-gradient-primary:hover { filter: brightness(1.08); }
    .btn-secondary {
      background: var(--bg-2) !important;
      border: 1px solid var(--line) !important;
      color: var(--ink) !important;
    }
    .btn-secondary:hover { border-color: var(--line-strong) !important; color: var(--cyan) !important; }
    .btn-outline-primary {
      background: transparent !important;
      border: 1px solid var(--line-strong) !important;
      color: var(--cyan) !important;
    }
    .btn-outline-primary:hover { background: var(--glow-soft) !important; border-color: var(--cyan) !important; }
    .btn-danger { background: var(--error) !important; border: 1px solid var(--error) !important; color: #fff !important; }
    .btn-danger:hover { filter: brightness(1.08); }
    .btn-icon {
      width: 36px;
      height: 36px;
      padding: 0;
      display: inline-flex;
      align-items: center;
      justify-content: center;
    }

    .form-control, .form-select, textarea.form-control {
      background: var(--bg-2) !important;
      border: 1px solid var(--line) !important;
      color: var(--ink) !important;
      border-radius: var(--r-md);
      padding: 0.6rem 0.85rem;
      font-size: 0.9rem;
      transition: border-color var(--t) var(--ease), box-shadow var(--t) var(--ease);
    }
    .form-control::placeholder { color: var(--ink-faint) !important; }
    .form-control:focus, .form-select:focus, textarea.form-control:focus {
      border-color: var(--cyan) !important;
      box-shadow: 0 0 0 3px var(--glow-soft) !important;
      outline: none;
      background: var(--bg-2) !important;
      color: var(--ink) !important;
    }
    .form-label {
      display: block;
      color: var(--ink-muted) !important;
      font-size: 0.85rem;
      font-weight: 600;
      margin-bottom: 0.4rem;
    }
    .input-group-text {
      background: var(--bg-2) !important;
      border: 1px solid var(--line) !important;
      color: var(--ink-muted) !important;
      display: flex;
      align-items: center;
    }

    .badge {
      font-weight: 600;
      font-size: 0.72rem;
      padding: 0.32rem 0.6rem;
      border-radius: var(--r-pill);
      display: inline-flex;
      align-items: center;
    }
    .badge-primary, .badge-gradient-primary { background: var(--cyan) !important; color: var(--cyan-ink) !important; }
    .badge-success { background: var(--success) !important; color: var(--cyan-ink) !important; }
    .badge-warning { background: var(--warning) !important; color: #1a1200 !important; }
    .badge-danger  { background: var(--error) !important; color: #fff !important; }

    .alert {
      border-radius: var(--r-md);
      border: 1px solid var(--line);
      background: var(--bg-1) !important;
      color: var(--ink) !important;
      display: flex;
      align-items: center;
    }
    .alert-success {
      background: color-mix(in srgb, var(--cyan) 9%, var(--bg-1)) !important;
      border-color: var(--line-strong) !important;
      color: var(--cyan) !important;
    }
    .alert-danger {
      background: color-mix(in srgb, var(--error) 9%, var(--bg-1)) !important;
      border-color: color-mix(in srgb, var(--error) 40%, transparent) !important;
      color: var(--error) !important;
    }
    .alert-warning {
      background: color-mix(in srgb, var(--warning) 10%, var(--bg-1)) !important;
      border-color: color-mix(in srgb, var(--warning) 40%, transparent) !important;
      color: var(--warning) !important;
    }

    .pagination { display: flex; align-items: center; }
    .pagination .page-item .page-link {
      background: var(--bg-1) !important;
      border: 1px solid var(--line) !important;
      color: var(--ink-muted) !important;
      border-radius: var(--r-sm) !important;
      margin: 0 2px;
      font-size: 0.85rem;
      display: flex;
      align-items: center;
      justify-content: center;
      transition: all var(--t) var(--ease);
    }
    .pagination .page-item .page-link:hover { border-color: var(--line-strong) !important; color: var(--cyan) !important; }
    .pagination .page-item.active .page-link {
      background: var(--cyan) !important;
      color: var(--cyan-ink) !important;
      border-color: var(--cyan) !important;
    }

    .dataTables_wrapper .dataTables_length select,
    .dataTables_wrapper .dataTables_filter input {
      background: var(--bg-2) !important;
      border: 1px solid var(--line) !important;
      color: var(--ink) !important;
      border-radius: var(--r-md);
    }
    .dataTables_wrapper .dataTables_info,
    .dataTables_wrapper .dataTables_length,
    .dataTables_wrapper .dataTables_filter { color: var(--ink-muted) !important; font-size: 0.85rem; }
    .dataTables_wrapper .dataTables_paginate .paginate_button {
      background: var(--bg-1) !important;
      border: 1px solid var(--line) !important;
      color: var(--ink-muted) !important;
      border-radius: var(--r-sm) !important;
    }
    .dataTables_wrapper .dataTables_paginate .paginate_button.current {
      background: var(--cyan) !important;
      color: var(--cyan-ink) !important;
      border-color: var(--cyan) !important;
    }

    * { scrollbar-width: thin; scrollbar-color: var(--bg-3) transparent; }
    ::-webkit-scrollbar { width: 8px; height: 8px; }
    ::-webkit-scrollbar-track { background: transparent; }
    ::-webkit-scrollbar-thumb { background: var(--bg-3); border-radius: 8px; }
    ::-webkit-scrollbar-thumb:hover { background: var(--ink-faint); }

    .text-primary { color: var(--cyan) !important; }
    .text-muted { color: var(--ink-muted) !important; }
    .border { border-color: var(--line) !important; }
    hr { border-color: var(--line); opacity: 1; }
    .bg-light { background: var(--bg-2) !important; }
    .bg-white { background: var(--bg-1) !important; color: var(--ink) !important; }

    /* =====================================================
       10. RESPONSIVE
       ===================================================== */
    @media (max-width: 1399.98px) {
      .navbar-search-wrapper .form-control { width: 180px; }
      .navbar-search-wrapper .form-control:focus { width: 240px; }
    }

    @media (max-width: 1199.98px) {
      .navbar-welcome { display: none; }
      .navbar-search-wrapper .form-control { width: 150px; }
      .navbar-search-wrapper .form-control:focus { width: 210px; }
      .search-kbd { display: none; }
    }

    @media (max-width: 991.98px) {
      .app-shell {
        grid-template-columns: minmax(0, 1fr);
        grid-template-areas:
          "navbar"
          "main";
      }
      .app-shell.sidebar-collapsed { grid-template-columns: minmax(0, 1fr); }

      .navbar-brand-wrapper {
        width: auto;
        min-width: 0;
        border-right: none;
        padding: 0 0.5rem;
        gap: 0;
      }
      .navbar-brand { display: none !important; }

      .app-sidebar {
        position: fixed;
        top: var(--navbar-h);
        left: 0;
        bottom: 0;
        width: min(280px, 86vw);
        height: auto;
        transform: translateX(-100%);
        transition: transform var(--t) var(--ease-out);
        z-index: 1035;
        box-shadow: 16px 0 40px -16px var(--shadow);
      }
      .app-sidebar.open { transform: translateX(0); }

      .app-shell.sidebar-collapsed .app-sidebar .menu-title,
      .app-shell.sidebar-collapsed .app-sidebar .menu-arrow { display: inline; }
      .app-shell.sidebar-collapsed .app-sidebar .nav-category {
        font-size: 0.68rem;
        padding: 0 0.85rem;
        margin: 1.1rem 0 0.35rem;
        height: auto;
        background: transparent !important;
      }
      .app-shell.sidebar-collapsed .app-sidebar .nav-category::after { display: block; }
      .app-shell.sidebar-collapsed .app-sidebar .nav-item .nav-link {
        justify-content: flex-start;
        padding: 0.6rem 0.85rem;
      }
      .app-shell.sidebar-collapsed .app-sidebar .nav-item .nav-link.active::before { display: block; }
      .app-shell.sidebar-collapsed .app-sidebar .sub-menu { display: block !important; }

      .navbar-date-wrapper { display: none; }
      .navbar-menu-wrapper { padding: 0 0.5rem; gap: 0.25rem; }
    }

    @media (max-width: 767.98px) {
      .navbar-search-wrapper { display: none; }
      .notif-dropdown { min-width: 320px !important; }
    }

    @media (max-width: 575.98px) {
      .navbar-toggler,
      .ameri-theme-toggle,
      .nav-link.count-indicator { width: 34px; height: 34px; }
      .app-navbar .nav-link { padding: 0.25rem; }
      .user-dropdown .nav-link { width: 34px !important; height: 34px !important; }
      .user-dropdown .nav-link img.img-xs { width: 32px; height: 32px; }

      .content-wrapper { padding: 1rem 0.75rem 1.5rem; }
      .card { border-radius: var(--r-md); }
      .footer .footer-inner { justify-content: center; text-align: center; }

      .navbar-dropdown.show {
        position: fixed !important;
        top: var(--navbar-h) !important;
        left: 0.5rem !important;
        right: 0.5rem !important;
        width: auto !important;
        min-width: 0 !important;
        max-width: none !important;
        margin-top: 0.5rem !important;
        transform: none !important;
      }
      .notif-dropdown-list { max-height: 55vh; }
    }
  </style>

  @stack('styles')
  @yield('css')
</head>
<body>
  @php
    $noLeidasNav = \App\Models\Notificacion::where('user_id', auth()->id())->where('leida', false)->count();
    $ultimasNotif = \App\Models\Notificacion::with('fromUser')
        ->where('user_id', auth()->id())
        ->latest()
        ->take(4)
        ->get();
  @endphp

  <div class="app-shell" id="appShell">

    <!-- ==================== NAVBAR ==================== -->
    <nav class="app-navbar">
      <div class="navbar-brand-wrapper">
        <button class="navbar-toggler" id="sidebarToggle" type="button" aria-label="Alternar menú">
          <span class="mdi mdi-menu"></span>
        </button>

        <a class="navbar-brand" href="{{ url('/dashboard') }}">
          <img src="{{ asset('img/logo.png') }}" alt="AMERI">
        </a>
      </div>

      <div class="navbar-menu-wrapper">

        <div class="navbar-welcome">
          <h1 class="welcome-text">
            Bienvenido, <span class="highlight">{{ Auth::user()->name ?? 'AMERI SRL' }}</span>
          </h1>
        </div>

        <ul class="navbar-nav ms-auto">

          {{-- CALENDARIO --}}
          <li class="nav-item">
            <div class="navbar-date-wrapper">
              <button type="button" class="navbar-date-btn" id="navbarDateBtn" aria-label="Ver calendario" aria-expanded="false" aria-controls="customCalendar">
                <i class="mdi mdi-calendar-month-outline"></i>
                <span class="date-text">{{ ucfirst(\Carbon\Carbon::now()->translatedFormat('d M Y')) }}</span>
              </button>

              <div class="custom-calendar" id="customCalendar">
                <div class="cal-header">
                  <button type="button" class="cal-nav-btn" id="calPrev" aria-label="Mes anterior"><i class="mdi mdi-chevron-left"></i></button>
                  <div class="cal-month-year" id="calMonthYear" aria-live="polite"></div>
                  <button type="button" class="cal-nav-btn" id="calNext" aria-label="Mes siguiente"><i class="mdi mdi-chevron-right"></i></button>
                </div>
                <div class="cal-weekdays">
                  <span>Lu</span><span>Ma</span><span>Mi</span><span>Ju</span><span>Vi</span><span>Sá</span><span>Do</span>
                </div>
                <div class="cal-days" id="calDays"></div>
                <div class="cal-footer">
                  <button type="button" class="cal-today-btn" id="calTodayBtn">Ir a hoy</button>
                </div>
              </div>
            </div>
          </li>

          {{-- BUSCADOR --}}
          <li class="nav-item">
            <div class="navbar-search-wrapper">
              <form class="search-form" action="#" onsubmit="return false;" autocomplete="off" role="search">
                <i class="icon-search"></i>
                <input type="search"
                       class="form-control"
                       id="navbarSearch"
                       placeholder="Buscar módulo..."
                       aria-label="Buscar módulo"
                       autocomplete="off">
                <span class="search-kbd">Ctrl K</span>
              </form>

              <div class="search-results" id="searchResults">
                <div class="search-results-header">Resultados</div>
                <div id="searchResultsList"></div>
              </div>
            </div>
          </li>

          {{-- NOTIFICACIONES --}}
          <li class="nav-item dropdown">
            <a class="nav-link count-indicator notif-bell"
               id="notifDropdown"
               href="#"
               role="button"
               data-bs-toggle="dropdown"
               data-bs-display="static"
               aria-expanded="false"
               aria-label="Notificaciones"
               onclick="event.preventDefault(); event.stopPropagation();">
              <i class="mdi mdi-bell-outline"></i>
              @if($noLeidasNav > 0)
                <span class="notif-badge">{{ $noLeidasNav > 99 ? '99+' : $noLeidasNav }}</span>
              @endif
            </a>

            <div class="dropdown-menu dropdown-menu-end navbar-dropdown notif-dropdown"
                 aria-labelledby="notifDropdown"
                 onclick="event.stopPropagation();">
              <div class="notif-dropdown-header">
                <div class="notif-header-left">
                  <i class="mdi mdi-bell-ring-outline"></i>
                  <div>
                    <h6>Notificaciones</h6>
                    <span>
                      @if($noLeidasNav > 0)
                        Tienes <strong>{{ $noLeidasNav }}</strong> sin leer
                      @else
                        Todo al día
                      @endif
                    </span>
                  </div>
                </div>

                @if($noLeidasNav > 0)
                  <form action="{{ route('admin.notificaciones.todas-leidas') }}" method="POST" class="d-inline">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="notif-mark-all" title="Marcar todas como leídas" aria-label="Marcar todas como leídas">
                      <i class="mdi mdi-check-all"></i>
                    </button>
                  </form>
                @endif
              </div>

              <div class="notif-dropdown-list">
                @forelse($ultimasNotif as $n)
                  <a href="{{ route('admin.notificaciones.index') }}"
                     class="notif-dropdown-item {{ $n->leida ? '' : 'is-unread' }}">
                    <div class="notif-icon-wrap tipo-{{ $n->tipo === 'stock_prestado' ? 'prestado' : 'devuelto' }}">
                      <i class="mdi {{ $n->tipo === 'stock_prestado' ? 'mdi-arrow-down-bold-circle-outline' : 'mdi-arrow-up-bold-circle-outline' }}"></i>
                    </div>

                    <div class="notif-content">
                      <p class="notif-title">{{ $n->titulo }}</p>
                      <p class="notif-msg">{{ \Illuminate\Support\Str::limit($n->mensaje, 70) }}</p>
                      <div class="notif-meta-line">
                        @if($n->fromUser)
                          <span class="notif-from">
                            <i class="mdi mdi-account-circle-outline"></i>
                            {{ $n->fromUser->name }}
                          </span>
                        @endif
                        <span class="notif-time">
                          <i class="mdi mdi-clock-outline"></i>
                          {{ $n->created_at->diffForHumans() }}
                        </span>
                      </div>
                    </div>

                    @if(!$n->leida)
                      <span class="notif-dot" aria-label="Sin leer"></span>
                    @endif
                  </a>
                @empty
                  <div class="notif-empty">
                    <i class="mdi mdi-bell-sleep-outline"></i>
                    <p class="notif-empty-title">Sin notificaciones</p>
                    <span class="notif-empty-sub">Cuando otro admin use tu stock, aparecerá aquí.</span>
                  </div>
                @endforelse
              </div>

              <a href="{{ route('admin.notificaciones.index') }}" class="notif-dropdown-footer">
                Ver todas las notificaciones
                <i class="mdi mdi-arrow-right"></i>
              </a>
            </div>
          </li>

          {{-- TEMA --}}
          <li class="nav-item">
            <button id="ameri-theme-toggle" class="ameri-theme-toggle" aria-label="Cambiar tema" type="button">
              <svg class="icon-moon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M21 12.79A9 9 0 1111.21 3 7 7 0 0021 12.79z"/>
              </svg>
              <svg class="icon-sun" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="12" cy="12" r="4"/>
                <path d="M12 2v2M12 20v2M4.93 4.93l1.41 1.41M17.66 17.66l1.41 1.41M2 12h2M20 12h2M4.93 19.07l1.41-1.41M17.66 6.34l1.41-1.41"/>
              </svg>
            </button>
          </li>

          {{-- USUARIO --}}
          <li class="nav-item dropdown user-dropdown">
            <a class="nav-link"
               id="UserDropdown"
               href="#"
               role="button"
               data-bs-toggle="dropdown"
               data-bs-display="static"
               aria-expanded="false"
               aria-label="Menú de usuario"
               onclick="event.preventDefault(); event.stopPropagation();">
              <img class="img-xs rounded-circle" src="{{ asset('template/images/faces/face8.jpg') }}" alt="Avatar">
            </a>

            <div class="dropdown-menu dropdown-menu-end navbar-dropdown"
                 aria-labelledby="UserDropdown"
                 onclick="event.stopPropagation();">
              <div class="dropdown-header text-center">
                <img class="img-md rounded-circle" src="{{ asset('template/images/faces/face8.jpg') }}" alt="Avatar">
                <p class="mb-1 mt-3 font-weight-semibold">{{ Auth::user()->name ?? 'Usuario AMERI' }}</p>
                <p class="fw-light text-muted mb-0">{{ Auth::user()->email ?? 'usuario@ameri.com' }}</p>
              </div>

              <a class="dropdown-item" href="#">
                <i class="dropdown-item-icon mdi mdi-account-outline me-2"></i>
                Mi perfil
              </a>

              <a class="dropdown-item" href="#">
                <i class="dropdown-item-icon mdi mdi-message-text-outline me-2"></i>
                Mensajes
              </a>

              <a class="dropdown-item" href="{{ route('logout') }}"
                 onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                <i class="dropdown-item-icon mdi mdi-power me-2"></i>
                Cerrar sesión
              </a>

              <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">@csrf</form>
            </div>
          </li>
        </ul>
      </div>
    </nav>

    <!-- ==================== SIDEBAR ==================== -->
    <aside class="app-sidebar" id="sidebar" aria-label="Navegación principal">
      <ul class="nav">

        <li class="nav-item">
          <a class="nav-link" href="{{ url('/dashboard') }}">
            <i class="mdi mdi-view-dashboard-outline menu-icon"></i>
            <span class="menu-title">Inicio</span>
          </a>
        </li>

        <li class="nav-category">Gestión</li>

        <li class="nav-item">
          <a class="nav-link" data-bs-toggle="collapse" href="#grp-usuarios" aria-expanded="false">
            <i class="mdi mdi-account-multiple-outline menu-icon"></i>
            <span class="menu-title">Usuarios</span>
            <i class="mdi mdi-chevron-right menu-arrow"></i>
          </a>
          <div class="collapse" id="grp-usuarios">
            <ul class="sub-menu">
              <li class="nav-item">
                <a class="nav-link" href="{{ route('admin.users.index') }}">Administración</a>
              </li>
            </ul>
          </div>
        </li>

        <li class="nav-item">
          <a class="nav-link" href="{{ route('admin.suministros.index') }}">
            <i class="mdi mdi-truck-delivery-outline menu-icon"></i>
            <span class="menu-title">Suministros</span>
          </a>
        </li>

        <li class="nav-category">Operaciones</li>

        <li class="nav-item">
          <a class="nav-link" data-bs-toggle="collapse" href="#grp-compras" aria-expanded="false">
            <i class="mdi mdi-cart-plus menu-icon"></i>
            <span class="menu-title">Compras</span>
            <i class="mdi mdi-chevron-right menu-arrow"></i>
          </a>
          <div class="collapse" id="grp-compras">
            <ul class="sub-menu">
              <li class="nav-item">
                <a class="nav-link" href="{{ route('admin.compras.index') }}">Órdenes de compra</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="{{ route('admin.compras.create') }}">Nueva compra</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="{{ route('admin.stocks.index') }}">Stock por admin</a>
              </li>
            </ul>
          </div>
        </li>

        <li class="nav-item">
          <a class="nav-link" data-bs-toggle="collapse" href="#grp-ventas" aria-expanded="false">
            <i class="mdi mdi-cart-outline menu-icon"></i>
            <span class="menu-title">Ventas</span>
            <i class="mdi mdi-chevron-right menu-arrow"></i>
          </a>
          <div class="collapse" id="grp-ventas">
            <ul class="sub-menu">
              <li class="nav-item">
                <a class="nav-link" href="{{ route('admin.ventas.index') }}">Historial</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="{{ route('admin.ventas.create') }}">Hacer venta</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="{{ route('admin.notificaciones.index') }}">
                  Notificaciones
                  @if($noLeidasNav > 0)
                    <span class="badge badge-danger ms-1">{{ $noLeidasNav }}</span>
                  @endif
                </a>
              </li>
            </ul>
          </div>
        </li>

        <li class="nav-category">Análisis</li>

        <ul class="sub-menu">
          <li class="nav-item">
            <a class="nav-link" href="{{ route('admin.reportes.index') }}">Ventas</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#">Inventario</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#">Exportar datos</a>
          </li>
        </ul>

        <li class="nav-category">Sistema</li>

        <li class="nav-item">
          <a class="nav-link" data-bs-toggle="collapse" href="#grp-config" aria-expanded="false">
            <i class="mdi mdi-cog-outline menu-icon"></i>
            <span class="menu-title">Configuración</span>
            <i class="mdi mdi-chevron-right menu-arrow"></i>
          </a>
          <div class="collapse" id="grp-config">
            <ul class="sub-menu">
              <li class="nav-item"><a class="nav-link" href="#">Ajustes generales</a></li>
              <li class="nav-item"><a class="nav-link" href="#">Roles y permisos</a></li>
            </ul>
          </div>
        </li>

      </ul>
    </aside>

    <div class="sidebar-backdrop" id="sidebarBackdrop"></div>

    <!-- ==================== MAIN ==================== -->
    <main class="app-main">
      <div class="content-wrapper">
        @yield('content')
      </div>

      <footer class="footer">
        <div class="footer-inner">
          <span class="text-muted">© {{ date('Y') }} AMERI · Cerámicos y Cemento Cola</span>
          <span class="text-muted">Hecho en Bolivia</span>
        </div>
      </footer>
    </main>
  </div>

  <!-- ==================== SCRIPTS ==================== -->
  <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
  <script src="{{ asset('template/vendors/js/vendor.bundle.base.js') }}"></script>
  <script src="{{ asset('template/js/off-canvas.js') }}"></script>
  <script src="{{ asset('template/js/hoverable-collapse.js') }}"></script>
  <script src="{{ asset('template/js/template.js') }}"></script>
  <script src="{{ asset('template/js/settings.js') }}"></script>
  <script src="{{ asset('template/js/todolist.js') }}"></script>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

  <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
  <script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
  <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
  <script src="https://cdn.datatables.net/responsive/2.5.0/js/responsive.bootstrap5.min.js"></script>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

  <script>
    /* ============================================================
       FORZAR COLORES — aplicar inline al cargar
       (Por si el template pisa con estilos dinámicos)
       ============================================================ */
    (function forzarColores() {
      function aplicar() {
        var theme = document.documentElement.getAttribute('data-theme');
        var bg = theme === 'light' ? '#f3f6f7' : '#0a1013';
        var bg1 = theme === 'light' ? '#ffffff' : '#0f171b';
        var ink = theme === 'light' ? '#0f1f24' : '#e6eff1';

        document.documentElement.style.background = bg;
        document.body.style.background = bg;
        document.body.style.color = ink;

        ['.container-scroller', '.page-body-wrapper', '.main-panel'].forEach(function(sel) {
          var el = document.querySelector(sel);
          if (el) {
            el.style.background = bg;
            el.style.backgroundColor = bg;
            el.style.color = ink;
          }
        });

        ['.app-sidebar', '.card', '.footer', '.app-navbar'].forEach(function(sel) {
          var el = document.querySelector(sel);
          if (el) {
            el.style.backgroundColor = bg1;
          }
        });
      }

      if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', aplicar);
      } else {
        aplicar();
      }

      // Reaplicar cuando cambia el tema
      window.addEventListener('ameri:theme-change', aplicar);
    })();

    /* ============================================================
       FIX DROPDOWNS
       ============================================================ */
    (function fixDropdowns() {
      function init() {
        if (typeof bootstrap === 'undefined' || !bootstrap.Dropdown) {
          setTimeout(init, 50);
          return;
        }

        var toggles = document.querySelectorAll('[data-bs-toggle="dropdown"]');
        toggles.forEach(function (toggle) {
          var clone = toggle.cloneNode(true);
          toggle.parentNode.replaceChild(clone, toggle);
          new bootstrap.Dropdown(clone);
          clone.addEventListener('click', function (e) {
            e.stopImmediatePropagation();
          }, true);
        });
      }

      if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
      } else {
        init();
      }
    })();

    /* ============================================================
       TEMA
       ============================================================ */
    (function initTheme() {
      var btn = document.getElementById('ameri-theme-toggle');
      var root = document.documentElement;
      var meta = document.querySelector('meta[name="theme-color"]');

      function setTheme(theme) {
        root.setAttribute('data-theme', theme);
        if (meta) meta.setAttribute('content', theme === 'light' ? '#f3f6f7' : '#0a1013');
        try { localStorage.setItem('ameri-theme', theme); } catch (e) {}

        // Disparar evento para reaplicar colores
        window.dispatchEvent(new Event('ameri:theme-change'));
      }

      if (btn) {
        btn.addEventListener('click', function () {
          setTheme(root.getAttribute('data-theme') === 'light' ? 'dark' : 'light');
        });
      }
    })();

    /* ============================================================
       SIDEBAR
       ============================================================ */
    (function initSidebar() {
      var shell    = document.getElementById('appShell');
      var sidebar  = document.getElementById('sidebar');
      var toggle   = document.getElementById('sidebarToggle');
      var backdrop = document.getElementById('sidebarBackdrop');
      var DESKTOP  = 992;

      function isDesktop() { return window.innerWidth >= DESKTOP; }

      if (toggle) {
        toggle.addEventListener('click', function () {
          if (isDesktop()) {
            shell.classList.toggle('sidebar-collapsed');
            try {
              localStorage.setItem('ameri-sidebar',
                shell.classList.contains('sidebar-collapsed') ? '1' : '0');
            } catch (e) {}
          } else {
            var open = sidebar.classList.toggle('open');
            backdrop.classList.toggle('show', open);
          }
        });
      }

      if (backdrop) {
        backdrop.addEventListener('click', function () {
          sidebar.classList.remove('open');
          backdrop.classList.remove('show');
        });
      }

      document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape' && sidebar.classList.contains('open')) {
          sidebar.classList.remove('open');
          backdrop.classList.remove('show');
        }
      });

      var resizeTimer;
      window.addEventListener('resize', function () {
        clearTimeout(resizeTimer);
        resizeTimer = setTimeout(function () {
          if (isDesktop()) {
            sidebar.classList.remove('open');
            backdrop.classList.remove('show');
          }
        }, 100);
      });

      try {
        if (localStorage.getItem('ameri-sidebar') === '1' && isDesktop()) {
          shell.classList.add('sidebar-collapsed');
        }
      } catch (e) {}
    })();

    /* ============================================================
       ENLACE ACTIVO EN SIDEBAR
       ============================================================ */
    (function markActiveLink() {
      var path = window.location.pathname;
      document.querySelectorAll('.app-sidebar .nav-link').forEach(function (link) {
        var href = link.getAttribute('href');
        if (!href || href.charAt(0) === '#' || href.indexOf('javascript') === 0) return;
        try {
          if (new URL(link.href, window.location.origin).pathname !== path) return;
          link.classList.add('active');
          var collapse = link.closest('.collapse');
          if (collapse) {
            collapse.classList.add('show');
            var toggler = document.querySelector('[href="#' + collapse.id + '"]');
            if (toggler) toggler.setAttribute('aria-expanded', 'true');
          }
        } catch (e) {}
      });
    })();

    /* ============================================================
       CALENDARIO
       ============================================================ */
    (function initCalendar() {
      var btnDate = document.getElementById('navbarDateBtn');
      var cal     = document.getElementById('customCalendar');
      var calDays = document.getElementById('calDays');
      var calMY   = document.getElementById('calMonthYear');
      var prev    = document.getElementById('calPrev');
      var next    = document.getElementById('calNext');
      var todayBt = document.getElementById('calTodayBtn');
      if (!btnDate || !cal || !calDays || !calMY) return;

      var meses = ['Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'];
      var hoy = new Date();
      var mes = hoy.getMonth();
      var anio = hoy.getFullYear();

      function celda(texto, clases) {
        var b = document.createElement('button');
        b.type = 'button';
        b.className = 'cal-day' + (clases ? ' ' + clases : '');
        b.textContent = texto;
        return b;
      }

      function render() {
        calMY.textContent = meses[mes] + ' ' + anio;
        calDays.innerHTML = '';

        var offset = (new Date(anio, mes, 1).getDay() + 6) % 7;
        var diasMes = new Date(anio, mes + 1, 0).getDate();
        var diasPrev = new Date(anio, mes, 0).getDate();

        for (var i = offset - 1; i >= 0; i--) calDays.appendChild(celda(diasPrev - i, 'otro-mes'));

        for (var d = 1; d <= diasMes; d++) {
          var esHoy = d === hoy.getDate() && mes === hoy.getMonth() && anio === hoy.getFullYear();
          var c = celda(d, esHoy ? 'hoy' : '');
          c.addEventListener('click', function () {
            calDays.querySelectorAll('.seleccionado').forEach(function (b) { b.classList.remove('seleccionado'); });
            this.classList.add('seleccionado');
          });
          calDays.appendChild(c);
        }

        var faltan = (7 - ((offset + diasMes) % 7)) % 7;
        for (var k = 1; k <= faltan; k++) calDays.appendChild(celda(k, 'otro-mes'));
      }

      function toggle(open) {
        cal.classList.toggle('open', open);
        btnDate.setAttribute('aria-expanded', open ? 'true' : 'false');
      }

      btnDate.addEventListener('click', function (e) {
        e.stopPropagation();
        toggle(!cal.classList.contains('open'));
      });
      if (prev) prev.addEventListener('click', function () { mes--; if (mes < 0) { mes = 11; anio--; } render(); });
      if (next) next.addEventListener('click', function () { mes++; if (mes > 11) { mes = 0; anio++; } render(); });
      if (todayBt) todayBt.addEventListener('click', function () { mes = hoy.getMonth(); anio = hoy.getFullYear(); render(); });

      document.addEventListener('click', function (e) {
        if (cal.contains(e.target)) return;
        if (e.target.closest('.dropdown')) return;
        if (btnDate.contains(e.target)) return;
        toggle(false);
      });

      document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') toggle(false);
      });

      render();
    })();

    /* ============================================================
       BUSCADOR
       ============================================================ */
    (function initSearch() {
      var input   = document.getElementById('navbarSearch');
      var results = document.getElementById('searchResults');
      var list    = document.getElementById('searchResultsList');
      if (!input || !results || !list) return;

      var modulos = [
        { titulo: 'Inicio',                    path: '/dashboard',                                   icono: 'mdi-view-dashboard-outline',  keywords: ['inicio','dashboard','panel'] },
        { titulo: 'Usuarios - Administración', path: '{{ route("admin.users.index") }}',             icono: 'mdi-account-multiple-outline', keywords: ['usuarios','administracion','admin','users'] },
        { titulo: 'Suministros',               path: '{{ route("admin.suministros.index") }}',       icono: 'mdi-truck-delivery-outline',   keywords: ['suministros','proveedores','productos'] },
        { titulo: 'Compras - Órdenes',         path: '{{ route("admin.compras.index") }}',           icono: 'mdi-cart-plus',                keywords: ['compras','ordenes','comprar'] },
        { titulo: 'Compras - Nueva',           path: '{{ route("admin.compras.create") }}',          icono: 'mdi-plus-circle-outline',      keywords: ['nueva compra','crear compra','comprar'] },
        { titulo: 'Stock por admin',           path: '{{ route("admin.stocks.index") }}',            icono: 'mdi-warehouse',                keywords: ['stock','inventario','almacen'] },
        { titulo: 'Ventas - Historial',        path: '{{ route("admin.ventas.index") }}',            icono: 'mdi-cart-outline',             keywords: ['ventas','historial','vender'] },
        { titulo: 'Ventas - Nueva',            path: '{{ route("admin.ventas.create") }}',           icono: 'mdi-plus-circle-outline',      keywords: ['nueva venta','hacer venta','vender'] },
        { titulo: 'Notificaciones',            path: '{{ route("admin.notificaciones.index") }}',    icono: 'mdi-bell-outline',             keywords: ['notificaciones','avisos','mensajes'] }
      ];

      function normalizar(t) {
        return String(t).toLowerCase().normalize('NFD').replace(/[\u0300-\u036f]/g, '');
      }

      function escapar(t) {
        var d = document.createElement('div');
        d.textContent = t;
        return d.innerHTML;
      }

      function buscar(termino) {
        var q = normalizar(termino.trim());
        if (!q) return [];
        return modulos.filter(function (m) {
          return normalizar(m.titulo).indexOf(q) !== -1 ||
                 m.keywords.some(function (k) { return normalizar(k).indexOf(q) !== -1; });
        });
      }

      function items() { return Array.prototype.slice.call(list.querySelectorAll('.search-result-item')); }

      function marcar(idx) {
        var els = items();
        if (!els.length) return;
        idx = (idx + els.length) % els.length;
        els.forEach(function (el, i) { el.classList.toggle('active', i === idx); });
        els[idx].scrollIntoView({ block: 'nearest' });
      }

      function render(encontrados, termino) {
        list.innerHTML = '';
        if (!encontrados.length) {
          list.innerHTML =
            '<div class="search-empty"><i class="mdi mdi-magnify-close"></i>' +
            'No hay módulos que coincidan con "<strong>' + escapar(termino) + '</strong>"</div>';
          return;
        }
        encontrados.forEach(function (m, i) {
          var a = document.createElement('a');
          a.href = m.path;
          a.className = 'search-result-item' + (i === 0 ? ' active' : '');
          a.innerHTML =
            '<span class="result-icon"><i class="mdi ' + m.icono + '"></i></span>' +
            '<div class="result-info"><p class="result-title">' + escapar(m.titulo) + '</p>' +
            '<p class="result-path">' + escapar(m.path.replace(/^https?:\/\/[^/]+/, '')) + '</p></div>';
          list.appendChild(a);
        });
      }

      var timer;
      input.addEventListener('input', function () {
        clearTimeout(timer);
        var val = this.value;
        if (!val.trim()) { results.classList.remove('open'); return; }
        timer = setTimeout(function () {
          render(buscar(val), val.trim());
          results.classList.add('open');
        }, 100);
      });

      input.addEventListener('focus', function () { if (this.value.trim()) results.classList.add('open'); });

      input.addEventListener('keydown', function (e) {
        var els = items();
        var actual = els.findIndex(function (el) { return el.classList.contains('active'); });

        if (e.key === 'ArrowDown') { e.preventDefault(); marcar(actual + 1); }
        else if (e.key === 'ArrowUp') { e.preventDefault(); marcar(actual - 1); }
        else if (e.key === 'Enter') {
          e.preventDefault();
          var sel = els[actual] || els[0];
          if (sel) window.location.href = sel.href;
        } else if (e.key === 'Escape') {
          results.classList.remove('open');
          this.blur();
        }
      });

      document.addEventListener('keydown', function (e) {
        if ((e.ctrlKey || e.metaKey) && e.key.toLowerCase() === 'k') {
          e.preventDefault();
          input.focus();
          input.select();
        }
      });

      document.addEventListener('click', function (e) {
        if (results.contains(e.target)) return;
        if (e.target === input) return;
        if (e.target.closest('.dropdown')) return;
        results.classList.remove('open');
      });
    })();

  </script>

  @stack('scripts')
  @yield('js')
</body>
</html>
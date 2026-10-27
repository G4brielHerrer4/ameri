{{-- resources/views/layouts/plantilla_vendedor.blade.php --}}
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-theme="dark" data-color="cyan">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <meta name="color-scheme" content="dark light">
  <meta name="theme-color" content="#0a1013">

  <title>@yield('title', config('app.name', 'AMERI'))</title>

  <link rel="preconnect" href="https://fonts.bunny.net">
  <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700|space-grotesk:500,600,700" rel="stylesheet" />

  <link rel="stylesheet" href="{{ asset('template/vendors/feather/feather.css') }}">
  <link rel="stylesheet" href="{{ asset('template/vendors/ti-icons/css/themify-icons.css') }}">
  <link rel="stylesheet" href="{{ asset('template/vendors/typicons/typicons.css') }}">
  <link rel="stylesheet" href="{{ asset('template/vendors/simple-line-icons/css/simple-line-icons.css') }}">
  <link rel="stylesheet" href="{{ asset('template/vendors/css/vendor.bundle.base.css') }}">

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@mdi/font@7.2.96/css/materialdesignicons.min.css">
  <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
  <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

  <link rel="stylesheet" href="{{ asset('template/css/vertical-layout-light/style.css') }}">
  <link rel="shortcut icon" href="{{ asset('template/images/favicon.png') }}" />

  <script>
    (function () {
      try {
        var savedTheme = localStorage.getItem('ameri-theme') || 'dark';
        var savedColor = localStorage.getItem('ameri-color') || 'cyan';
        document.documentElement.setAttribute('data-theme', savedTheme);
        document.documentElement.setAttribute('data-color', savedColor);
        var meta = document.querySelector('meta[name="theme-color"]');
        if (meta) meta.setAttribute('content', savedTheme === 'light' ? '#f3f6f7' : '#0a1013');
      } catch (e) {}
    })();
  </script>

  <style>
    /* =====================================================
       TOKENS Y TEMAS
       ===================================================== */
    :root {
      --bg: #0a1013; --bg-1: #0f171b; --bg-2: #141e23; --bg-3: #1a2830;
      --ink: #e6eff1; --ink-muted: #92a7ad; --ink-faint: #5f767d;
      --line: rgba(255, 255, 255, 0.07); --shadow: rgba(0, 0, 0, 0.45);
      --surface-alpha: rgba(255, 255, 255, 0.04);
      --error: #f2667b; --success: #2dd4c4; --warning: #f5b04c;
      --font-display: 'Space Grotesk', 'Instrument Sans', system-ui, sans-serif;
      --font-body: 'Instrument Sans', system-ui, sans-serif;
      --r-sm: 8px; --r-md: 10px; --r-lg: 14px; --r-pill: 999px;
      --navbar-h: 64px; --sidebar-w: 248px; --sidebar-w-min: 72px;
      --ease: cubic-bezier(0.4, 0, 0.2, 1); --ease-out: cubic-bezier(0.2, 0.8, 0.2, 1); --t: 0.18s;
    }

    html[data-color="cyan"] {
      --primary: #00fff0; --primary-2: #00d9cc; --primary-dim: #00a89e;
      --primary-ink: #00201e; --line-strong: rgba(0, 255, 240, 0.35); --glow: rgba(0, 255, 240, 0.30); --glow-soft: rgba(0, 255, 240, 0.14);
    }
    html[data-color="green"] {
      --primary: #10b981; --primary-2: #059669; --primary-dim: #047857;
      --primary-ink: #ffffff; --line-strong: rgba(16, 185, 129, 0.35); --glow: rgba(16, 185, 129, 0.30); --glow-soft: rgba(16, 185, 129, 0.14);
    }
    html[data-color="orange"] {
      --primary: #f97316; --primary-2: #ea580c; --primary-dim: #c2410c;
      --primary-ink: #ffffff; --line-strong: rgba(249, 115, 22, 0.35); --glow: rgba(249, 115, 22, 0.30); --glow-soft: rgba(249, 115, 22, 0.14);
    }
    html[data-color="violet"] {
      --primary: #8b5cf6; --primary-2: #7c3aed; --primary-dim: #6d28d9;
      --primary-ink: #ffffff; --line-strong: rgba(139, 92, 246, 0.35); --glow: rgba(139, 92, 246, 0.30); --glow-soft: rgba(139, 92, 246, 0.14);
    }

    html[data-theme="light"] {
      --bg: #f3f6f7; --bg-1: #ffffff; --bg-2: #eef2f4; --bg-3: #e3eaed;
      --ink: #0f1f24; --ink-muted: #52666d; --ink-faint: #81959b;
      --line: rgba(15, 40, 48, 0.10); --shadow: rgba(15, 40, 48, 0.10); --surface-alpha: rgba(15, 40, 48, 0.04);
      --error: #d63a52; --success: #0d9488; --warning: #c77700;
    }
    html[data-theme="light"][data-color="cyan"] {
      --primary: #0d9488; --primary-2: #0f766e; --primary-dim: #115e59; --primary-ink: #ffffff; --line-strong: rgba(13, 148, 136, 0.40); --glow: rgba(13, 148, 136, 0.25); --glow-soft: rgba(13, 148, 136, 0.12);
    }
    html[data-theme="light"][data-color="green"] {
      --primary: #059669; --primary-2: #047857; --primary-dim: #065f46; --primary-ink: #ffffff; --line-strong: rgba(5, 150, 105, 0.40); --glow: rgba(5, 150, 105, 0.25); --glow-soft: rgba(5, 150, 105, 0.12);
    }
    html[data-theme="light"][data-color="orange"] {
      --primary: #ea580c; --primary-2: #c2410c; --primary-dim: #9a3412; --primary-ink: #ffffff; --line-strong: rgba(234, 88, 12, 0.40); --glow: rgba(234, 88, 12, 0.25); --glow-soft: rgba(234, 88, 12, 0.12);
    }
    html[data-theme="light"][data-color="violet"] {
      --primary: #7c3aed; --primary-2: #6d28d9; --primary-dim: #5b21b6; --primary-ink: #ffffff; --line-strong: rgba(124, 58, 237, 0.40); --glow: rgba(124, 58, 237, 0.25); --glow-soft: rgba(124, 58, 237, 0.12);
    }

    /* OVERRIDE TEMPLATE */
    html, body, .container-scroller, .page-body-wrapper, .main-panel, .content-wrapper { background: var(--bg) !important; color: var(--ink) !important; }
    .card, .navbar, .navbar.default-layout, .sidebar, .footer { background: var(--bg-1) !important; color: var(--ink) !important; }
    .app-navbar, .navbar.default-layout { background: color-mix(in srgb, var(--bg) 88%, transparent) !important; }
    .container-scroller > *, .page-body-wrapper > *, .main-panel > * { background-color: transparent !important; }
    h1, h2, h3, h4, h5, h6 { color: var(--ink) !important; }
    p, span, small, label, div, li, a { color: inherit; }
    .text-muted { color: var(--ink-muted) !important; }
    input, select, textarea, .form-control, .form-select { background-color: var(--bg-2) !important; color: var(--ink) !important; border-color: var(--line) !important; }
    .table { color: var(--ink) !important; background-color: transparent !important; }
    .table thead th, .table tbody td { background-color: transparent !important; color: var(--ink) !important; border-color: var(--line) !important; }
    .dropdown-menu { background-color: var(--bg-1) !important; border-color: var(--line) !important; color: var(--ink) !important; }
    .dropdown-item { color: var(--ink) !important; }
    .page-body-wrapper::before, .page-body-wrapper::after, .main-panel::before, .main-panel::after, .container-scroller::before, .container-scroller::after { display: none !important; }

    /* BASE */
    * { box-sizing: border-box; }
    html, body { height: 100%; }
    body { font-family: var(--font-body); background-color: var(--bg) !important; color: var(--ink) !important; transition: all var(--t) var(--ease); -webkit-font-smoothing: antialiased; overflow-x: hidden; margin: 0; }
    a { color: inherit; text-decoration: none; }
    ::selection { background: var(--primary); color: var(--primary-ink); }
    :focus-visible { outline: 2px solid var(--primary); outline-offset: 2px; border-radius: 6px; }

    /* LAYOUT */
    .app-shell { display: grid; grid-template-columns: var(--sidebar-w) minmax(0, 1fr); grid-template-rows: var(--navbar-h) 1fr; grid-template-areas: "navbar navbar" "sidebar main"; min-height: 100vh; transition: grid-template-columns var(--t) var(--ease); background-color: var(--bg) !important; }
    .app-shell.sidebar-collapsed { grid-template-columns: var(--sidebar-w-min) minmax(0, 1fr); }
    .app-navbar { grid-area: navbar; }
    .app-sidebar { grid-area: sidebar; }
    .app-main { grid-area: main; min-width: 0; display: flex; flex-direction: column; background-color: transparent !important; }

    /* NAVBAR */
    .app-navbar { position: sticky; top: 0; height: var(--navbar-h); background: color-mix(in srgb, var(--bg) 88%, transparent) !important; backdrop-filter: blur(12px); -webkit-backdrop-filter: blur(12px); border-bottom: 1px solid var(--line); display: flex; align-items: stretch; z-index: 1040; min-width: 0; }
    .navbar-brand-wrapper { width: var(--sidebar-w); flex-shrink: 0; display: flex; align-items: center; gap: 0.75rem; padding: 0 1rem; border-right: 1px solid var(--line); transition: width var(--t) var(--ease); min-width: 0; background-color: transparent !important; }
    .app-shell.sidebar-collapsed .navbar-brand-wrapper { width: var(--sidebar-w-min); justify-content: center; padding: 0 0.5rem; }
    .navbar-brand { display: inline-flex; align-items: center; line-height: 0; overflow: hidden; }
    .navbar-brand img { height: 36px; width: auto; max-width: 100%; object-fit: contain; display: block; }

    .navbar-menu-wrapper { flex: 1 1 auto; min-width: 0; display: flex; align-items: center; gap: 0.75rem; padding: 0 clamp(0.75rem, 2vw, 1.25rem); overflow: visible; background-color: transparent !important; }
    .navbar-toggler, .ameri-theme-toggle, .nav-link.count-indicator { width: 38px; height: 38px; padding: 0; margin: 0; border-radius: var(--r-md); border: 1px solid transparent; background: transparent; color: var(--ink-muted); display: inline-flex; align-items: center; justify-content: center; cursor: pointer; flex-shrink: 0; transition: all var(--t) var(--ease); }
    .navbar-toggler:hover, .ameri-theme-toggle:hover, .nav-link.count-indicator:hover { background: var(--surface-alpha); color: var(--ink); border-color: var(--line); }
    .navbar-toggler .mdi { font-size: 1.4rem; line-height: 1; }

    .navbar-welcome { display: flex; flex-direction: column; justify-content: center; min-width: 0; max-width: clamp(160px, 26vw, 420px); flex: 1 1 auto; overflow: hidden; }
    .navbar-welcome .welcome-text { font-family: var(--font-display); font-size: 0.98rem; font-weight: 600; color: var(--ink) !important; margin: 0; line-height: 1.2; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    .navbar-welcome .welcome-text .highlight { color: var(--primary); }
    
    .navbar-menu-wrapper > .navbar-nav { display: flex; flex-direction: row; align-items: center; margin: 0; padding: 0; list-style: none; height: 100%; gap: 0.15rem; }
    .navbar-menu-wrapper > .navbar-nav.ms-auto { margin-left: auto; }
    .app-navbar .nav-item { display: flex; align-items: center; height: var(--navbar-h); margin: 0; position: relative; }
    .app-navbar .nav-link { display: inline-flex; align-items: center; justify-content: center; padding: 0.4rem; color: var(--ink-muted); line-height: 1; transition: color var(--t) var(--ease); cursor: pointer; }
    .app-navbar .nav-link::after { display: none !important; }
    .app-navbar .dropdown-menu { position: absolute !important; top: 100% !important; right: 0 !important; left: auto !important; margin-top: 0.25rem !important; }

    /* CALENDARIO Y BUSCADOR */
    .navbar-date-wrapper { position: relative; display: inline-flex; align-items: center; }
    .navbar-date-btn { display: inline-flex; align-items: center; gap: 0.5rem; height: 38px; padding: 0 0.85rem; background: var(--bg-2) !important; border: 1px solid var(--line); border-radius: var(--r-md); color: var(--ink) !important; font-size: 0.83rem; font-weight: 500; cursor: pointer; white-space: nowrap; transition: all var(--t) var(--ease); }
    .navbar-date-btn:hover, .navbar-date-btn[aria-expanded="true"] { border-color: var(--line-strong); }
    .navbar-date-btn .mdi { color: var(--primary); font-size: 1.05rem; }

    .custom-calendar, .search-results { position: absolute; top: calc(100% + 8px); right: 0; background: var(--bg-1) !important; border: 1px solid var(--line); border-radius: var(--r-lg); box-shadow: 0 16px 40px -12px var(--shadow); z-index: 1050; opacity: 0; visibility: hidden; transform: translateY(-4px); pointer-events: none; transition: opacity var(--t) var(--ease), transform var(--t) var(--ease), visibility var(--t); }
    .custom-calendar.open, .search-results.open { opacity: 1; visibility: visible; transform: translateY(0); pointer-events: auto; }

    .custom-calendar { padding: 0.85rem; width: 292px; }
    .cal-header { display: flex; align-items: center; justify-content: space-between; gap: 0.5rem; margin-bottom: 0.65rem; }
    .cal-nav-btn { width: 30px; height: 30px; border-radius: var(--r-sm); border: 1px solid transparent; background: transparent; color: var(--ink-muted); cursor: pointer; display: inline-flex; align-items: center; justify-content: center; padding: 0; font-size: 1.1rem; transition: all var(--t) var(--ease); }
    .cal-nav-btn:hover { background: var(--surface-alpha); color: var(--ink); border-color: var(--line); }
    .cal-month-year { font-family: var(--font-display); font-weight: 600; color: var(--ink) !important; font-size: 0.9rem; text-align: center; flex: 1; }
    .cal-weekdays, .cal-days { display: grid; grid-template-columns: repeat(7, 1fr); gap: 2px; }
    .cal-weekdays span { text-align: center; font-size: 0.72rem; font-weight: 600; color: var(--ink-faint); padding: 0.3rem 0; }
    .cal-day { aspect-ratio: 1; border: 1px solid transparent; background: transparent; color: var(--ink) !important; font-size: 0.8rem; border-radius: var(--r-sm); cursor: pointer; display: flex; align-items: center; justify-content: center; padding: 0; transition: background var(--t) var(--ease); }
    .cal-day:hover { background: var(--surface-alpha); }
    .cal-day.otro-mes { color: var(--ink-faint) !important; opacity: 0.5; }
    .cal-day.hoy { background: var(--primary); color: var(--primary-ink) !important; font-weight: 700; }
    .cal-day.seleccionado:not(.hoy) { border-color: var(--line-strong); color: var(--primary) !important; font-weight: 600; }
    .cal-footer { display: flex; justify-content: center; margin-top: 0.65rem; padding-top: 0.65rem; border-top: 1px solid var(--line); }
    .cal-today-btn { background: transparent; border: none; color: var(--primary); font-size: 0.8rem; font-weight: 600; padding: 0.3rem 0.75rem; border-radius: var(--r-sm); cursor: pointer; transition: background var(--t) var(--ease); }
    .cal-today-btn:hover { background: var(--glow-soft); }

    .navbar-search-wrapper { position: relative; display: flex; align-items: center; height: 38px; }
    .navbar-search-wrapper .search-form { position: relative; display: flex; align-items: center; margin: 0; }
    .navbar-search-wrapper .icon-search { position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: var(--ink-faint); font-size: 0.95rem; pointer-events: none; z-index: 2; }
    .navbar-search-wrapper .form-control { background: var(--bg-2) !important; border: 1px solid var(--line) !important; color: var(--ink) !important; border-radius: var(--r-md); height: 38px; font-size: 0.85rem; padding: 0 3.4rem 0 2.35rem; width: 230px; transition: width 0.25s var(--ease), border-color var(--t) var(--ease), box-shadow var(--t) var(--ease); }
    .navbar-search-wrapper .form-control:focus { width: 290px; border-color: var(--primary) !important; box-shadow: 0 0 0 3px var(--glow-soft) !important; outline: none; }
    .search-kbd { position: absolute; right: 10px; top: 50%; transform: translateY(-50%); font-family: var(--font-body); font-size: 0.68rem; color: var(--ink-faint); border: 1px solid var(--line); border-radius: 5px; padding: 1px 5px; pointer-events: none; transition: opacity var(--t); }
    .navbar-search-wrapper .form-control:focus ~ .search-kbd { opacity: 0; }

    .search-results { width: 360px; max-height: 400px; overflow-y: auto; padding: 0.4rem; }
    .search-results-header { padding: 0.4rem 0.65rem; font-size: 0.75rem; font-weight: 600; color: var(--ink-faint); }
    .search-result-item { display: flex; align-items: center; gap: 0.7rem; padding: 0.55rem 0.65rem; border-radius: var(--r-sm); color: var(--ink); transition: background var(--t) var(--ease); }
    .search-result-item:hover, .search-result-item.active { background: var(--surface-alpha); color: var(--ink); }
    .search-result-item.active { box-shadow: inset 2px 0 0 var(--primary); }
    .search-result-item .result-icon { width: 32px; height: 32px; border-radius: var(--r-sm); background: var(--glow-soft); color: var(--primary); display: inline-flex; align-items: center; justify-content: center; font-size: 1rem; flex-shrink: 0; }
    .search-result-item .result-info { flex: 1; min-width: 0; }
    .search-result-item .result-title { font-weight: 600; font-size: 0.85rem; margin: 0; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    .search-result-item .result-path { font-size: 0.72rem; color: var(--ink-faint); margin: 0; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    .search-empty { padding: 1.5rem 1rem; text-align: center; color: var(--ink-muted); font-size: 0.85rem; }
    .search-empty .mdi { display: block; font-size: 1.8rem; color: var(--ink-faint); margin-bottom: 0.25rem; }

    /* NOTIFICACIONES Y DROPDOWNS */
    .notif-bell { position: relative; }
    .notif-bell .mdi { font-size: 1.3rem; line-height: 1; display: block; }
    .notif-badge { position: absolute; top: 1px; right: 1px; min-width: 17px; height: 17px; padding: 0 4px; border-radius: var(--r-pill); background: var(--error); color: #fff; font-size: 0.65rem; font-weight: 700; display: inline-flex; align-items: center; justify-content: center; line-height: 1; box-shadow: 0 0 0 2px var(--bg); }
    
    .navbar-dropdown { background: var(--bg-1) !important; border: 1px solid var(--line) !important; border-radius: var(--r-lg) !important; box-shadow: 0 16px 40px -12px var(--shadow) !important; padding: 0.4rem !important; min-width: 250px; z-index: 1060; }
    .navbar-dropdown .dropdown-item { color: var(--ink) !important; border-radius: var(--r-sm); padding: 0.6rem 0.8rem; font-size: 0.86rem; display: flex; align-items: center; transition: background var(--t) var(--ease); }
    .navbar-dropdown .dropdown-item:hover { background: var(--surface-alpha) !important; color: var(--ink) !important; }
    .navbar-dropdown .dropdown-item .mdi { color: var(--primary) !important; }
    .navbar-dropdown .dropdown-header { color: var(--ink); border-bottom: 1px solid var(--line); padding: 0.5rem 0.5rem 0.9rem; margin-bottom: 0.4rem; }
    .navbar-dropdown .dropdown-header p { color: var(--ink-muted); margin: 0; }
    .navbar-dropdown .dropdown-header .img-md { width: 60px; height: 60px; border-radius: 50%; object-fit: cover; border: 2px solid var(--line-strong); }

    .notif-dropdown { min-width: 370px !important; padding: 0 !important; overflow: hidden; }
    .notif-dropdown-header { display: flex; align-items: center; justify-content: space-between; gap: 0.75rem; padding: 0.9rem 1.1rem; border-bottom: 1px solid var(--line); }
    .notif-header-left { display: flex; align-items: center; gap: 0.65rem; }
    .notif-header-left .mdi { color: var(--primary) !important; font-size: 1.3rem; }
    .notif-header-left h6 { font-family: var(--font-display); font-weight: 600; color: var(--ink) !important; font-size: 0.92rem; margin: 0; line-height: 1.2; }
    .notif-header-left span { font-size: 0.75rem; color: var(--ink-muted); }
    .notif-header-left span strong { color: var(--primary); font-weight: 600; }
    .notif-mark-all { width: 30px; height: 30px; border-radius: var(--r-sm); border: 1px solid var(--line); background: transparent; color: var(--ink-muted); cursor: pointer; display: inline-flex; align-items: center; justify-content: center; padding: 0; font-size: 1rem; transition: all var(--t) var(--ease); }
    .notif-mark-all:hover { color: var(--primary); border-color: var(--line-strong); }
    .notif-dropdown-list { max-height: 340px; overflow-y: auto; }

    .notif-dropdown-item { display: flex; align-items: flex-start; gap: 0.75rem; padding: 0.8rem 1.1rem; border-bottom: 1px solid var(--line); color: inherit; transition: background var(--t) var(--ease); }
    .notif-dropdown-item:last-child { border-bottom: none; }
    .notif-dropdown-item:hover { background: var(--surface-alpha); color: inherit; }
    .notif-dropdown-item.is-unread { background: var(--glow-soft); }

    .notif-icon-wrap { width: 34px; height: 34px; border-radius: var(--r-sm); flex-shrink: 0; display: inline-flex; align-items: center; justify-content: center; font-size: 1.05rem; background: var(--glow-soft); color: var(--primary); }
    .notif-icon-wrap.tipo-prestado { background: color-mix(in srgb, var(--warning) 14%, transparent); color: var(--warning); }
    .notif-content { flex: 1; min-width: 0; }
    .notif-title { font-weight: 600; color: var(--ink) !important; font-size: 0.84rem; margin: 0 0 0.15rem; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    .notif-msg { font-size: 0.78rem; color: var(--ink-muted) !important; margin: 0 0 0.3rem; line-height: 1.4; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }
    .notif-meta-line { display: flex; align-items: center; gap: 0.75rem; flex-wrap: wrap; font-size: 0.72rem; color: var(--ink-faint); }
    .notif-meta-line .mdi { font-size: 0.8rem; }
    .notif-from, .notif-time { display: inline-flex; align-items: center; gap: 0.25rem; }
    .notif-from { color: var(--primary); font-weight: 500; }
    .notif-dot { width: 8px; height: 8px; border-radius: 50%; background: var(--primary); flex-shrink: 0; margin-top: 0.4rem; }

    .notif-empty { padding: 2.25rem 1.5rem; text-align: center; }
    .notif-empty .mdi { font-size: 2.6rem; color: var(--ink-faint); opacity: 0.5; display: block; margin-bottom: 0.4rem; }
    .notif-empty-title { font-weight: 600; color: var(--ink); font-size: 0.9rem; margin: 0 0 0.2rem; }
    .notif-empty-sub { font-size: 0.78rem; color: var(--ink-muted); display: block; line-height: 1.4; }

    .notif-dropdown-footer { display: flex !important; align-items: center !important; justify-content: center !important; gap: 0.35rem; padding: 0.75rem 1.1rem !important; border-top: 1px solid var(--line); color: var(--primary) !important; font-weight: 600; font-size: 0.82rem; border-radius: 0 !important; transition: background var(--t) var(--ease); }
    .notif-dropdown-footer:hover { background: var(--surface-alpha) !important; }
    .notif-dropdown-footer .mdi { color: var(--primary) !important; }

    /* SELECTOR DE TEMAS */
    .theme-picker { display: flex; gap: 0.5rem; padding: 0.5rem 0.75rem; justify-content: center; }
    .theme-dot { width: 24px; height: 24px; border-radius: 50%; cursor: pointer; border: 2px solid transparent; transition: transform 0.2s; }
    .theme-dot:hover { transform: scale(1.15); }
    .theme-dot.active { border-color: var(--ink); }
    .theme-dot.cyan { background: #00fff0; }
    .theme-dot.green { background: #10b981; }
    .theme-dot.orange { background: #f97316; }
    .theme-dot.violet { background: #8b5cf6; }

    /* USUARIO */
    .user-dropdown .nav-link { padding: 0 !important; width: 38px; height: 38px; border-radius: 50%; }
    .user-dropdown .nav-link img.img-xs { width: 36px; height: 36px; border-radius: 50%; border: 2px solid var(--line); object-fit: cover; display: block; transition: border-color var(--t) var(--ease); }
    .user-dropdown .nav-link:hover img.img-xs, .user-dropdown .nav-link[aria-expanded="true"] img.img-xs { border-color: var(--primary); }
    .user-dropdown .dropdown-header { padding: 0.5rem 0.5rem 1rem !important; margin-bottom: 0.35rem; }
    .user-dropdown .dropdown-header .font-weight-semibold { font-family: var(--font-display); font-weight: 600 !important; color: var(--ink); font-size: 0.9rem; }

    /* SIDEBAR */
    .app-sidebar { background: var(--bg-1) !important; border-right: 1px solid var(--line); overflow-y: auto; overflow-x: hidden; padding: 0.75rem 0 1.5rem; height: calc(100vh - var(--navbar-h)); position: sticky; top: var(--navbar-h); scrollbar-width: thin; scrollbar-color: var(--bg-3) transparent; transition: width var(--t) var(--ease); }
    .app-sidebar::-webkit-scrollbar { width: 6px; }
    .app-sidebar::-webkit-scrollbar-thumb { background: var(--bg-3); border-radius: 6px; }
    .app-sidebar .nav { padding: 0 0.75rem; display: flex; flex-direction: column; gap: 0.15rem; }
    .app-sidebar .nav-category { position: relative; display: flex; align-items: center; gap: 0.5rem; font-family: var(--font-display); font-size: 0.68rem; font-weight: 700; letter-spacing: 0.12em; text-transform: uppercase; color: var(--ink-faint) !important; margin: 1.1rem 0 0.35rem; padding: 0 0.85rem 0 0.85rem; background: transparent !important; border: none; list-style: none; user-select: none; }
    .app-sidebar .nav-category::after { content: ''; flex: 1; height: 1px; background: linear-gradient(to right, var(--line), transparent 90%); margin-left: 0.25rem; }
    .app-sidebar .nav > .nav-category:first-child { margin-top: 0.25rem; }
    .app-sidebar .nav-item { list-style: none; margin: 0; }
    .app-sidebar .nav-item .nav-link { color: var(--ink-muted) !important; font-size: 0.87rem; font-weight: 500; padding: 0.6rem 0.85rem; border-radius: var(--r-md); display: flex; align-items: center; gap: 0.7rem; border: 1px solid transparent; position: relative; transition: background var(--t) var(--ease), color var(--t) var(--ease); min-width: 0; cursor: pointer; }
    .app-sidebar .nav-item .nav-link .menu-icon { font-size: 1.15rem; color: var(--ink-faint); min-width: 22px; text-align: center; line-height: 1; flex-shrink: 0; transition: color var(--t) var(--ease); }
    .app-sidebar .nav-item .nav-link .menu-title { flex: 1; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    .app-sidebar .nav-item .nav-link:hover { background: var(--surface-alpha) !important; color: var(--ink) !important; }
    .app-sidebar .nav-item .nav-link:hover .menu-icon { color: var(--primary); }
    .app-sidebar .nav-item .nav-link.active { background: var(--glow-soft) !important; color: var(--ink) !important; font-weight: 600; }
    .app-sidebar .nav-item .nav-link.active .menu-icon { color: var(--primary); }
    .app-sidebar .nav-item .nav-link.active::before { content: ''; position: absolute; left: -0.75rem; top: 22%; bottom: 22%; width: 3px; background: var(--primary); border-radius: 0 3px 3px 0; }

    .app-shell.sidebar-collapsed .app-sidebar .menu-title { display: none; }
    .app-shell.sidebar-collapsed .app-sidebar .nav-category { font-size: 0; padding: 0; margin: 0.5rem 0.5rem; height: 1px; background: var(--line) !important; }
    .app-shell.sidebar-collapsed .app-sidebar .nav-category::after { display: none; }
    .app-shell.sidebar-collapsed .app-sidebar .nav-item .nav-link { justify-content: center; padding: 0.7rem; }
    .app-shell.sidebar-collapsed .app-sidebar .nav-item .nav-link.active::before { display: none; }

    .sidebar-backdrop { position: fixed; inset: var(--navbar-h) 0 0 0; background: rgba(0, 0, 0, 0.5); z-index: 1030; opacity: 0; visibility: hidden; transition: opacity var(--t) var(--ease), visibility var(--t); }
    .sidebar-backdrop.show { opacity: 1; visibility: visible; }

    /* CONTENIDO Y FOOTER */
    .content-wrapper { flex: 1 1 auto; padding: clamp(1rem, 2vw, 1.75rem) clamp(0.75rem, 2vw, 1.75rem) 2rem; width: 100%; min-width: 0; background-color: transparent !important; }
    .footer { background: var(--bg-1) !important; border-top: 1px solid var(--line); color: var(--ink-faint) !important; padding: 0.9rem clamp(1rem, 2vw, 1.5rem); font-size: 0.8rem; }
    .footer a { color: var(--primary); }
    .footer .footer-inner { display: flex; flex-wrap: wrap; gap: 0.25rem 1rem; justify-content: space-between; align-items: center; }

    /* COMPONENTES */
    .card { background: var(--bg-1) !important; border: 1px solid var(--line) !important; border-radius: var(--r-lg); box-shadow: 0 1px 2px var(--shadow); color: var(--ink) !important; overflow: hidden; }
    .table { color: var(--ink) !important; background: transparent !important; }
    .table thead th { border-bottom: 1px solid var(--line) !important; color: var(--ink-muted) !important; font-size: 0.8rem; font-weight: 600; padding: 0.8rem 0.75rem; background: transparent !important; }
    .table tbody td { border-top: 1px solid var(--line) !important; color: var(--ink) !important; padding: 0.8rem 0.75rem; font-size: 0.88rem; background-color: transparent !important; }
    .table tbody tr:hover { background: var(--surface-alpha) !important; }

    .btn { font-family: inherit; font-weight: 600; border-radius: var(--r-md); padding: 0.55rem 1.1rem; font-size: 0.86rem; display: inline-flex; align-items: center; justify-content: center; gap: 0.4rem; transition: all var(--t) var(--ease); }
    .btn-primary { background: var(--primary) !important; border: 1px solid var(--primary) !important; color: var(--primary-ink) !important; }
    .btn-secondary { background: var(--bg-2) !important; border: 1px solid var(--line) !important; color: var(--ink) !important; }
    .btn-secondary:hover { border-color: var(--line-strong) !important; color: var(--primary) !important; }
    .btn-outline-primary { background: transparent !important; border: 1px solid var(--line-strong) !important; color: var(--primary) !important; }
    .btn-outline-primary:hover { background: var(--glow-soft) !important; border-color: var(--primary) !important; }
    .btn-danger { background: var(--error) !important; border: 1px solid var(--error) !important; color: #fff !important; }

    .form-control, .form-select { background: var(--bg-2) !important; border: 1px solid var(--line) !important; color: var(--ink) !important; border-radius: var(--r-md); padding: 0.6rem 0.85rem; font-size: 0.9rem; }
    .form-control:focus, .form-select:focus { border-color: var(--primary) !important; box-shadow: 0 0 0 3px var(--glow-soft) !important; outline: none; background: var(--bg-2) !important; color: var(--ink) !important; }

    @media (max-width: 1399.98px) { .navbar-search-wrapper .form-control { width: 180px; } .navbar-search-wrapper .form-control:focus { width: 240px; } }
    @media (max-width: 1199.98px) { .navbar-welcome { display: none; } .navbar-search-wrapper .form-control { width: 150px; } .navbar-search-wrapper .form-control:focus { width: 210px; } .search-kbd { display: none; } }
    @media (max-width: 991.98px) {
      .app-shell { grid-template-columns: minmax(0, 1fr); grid-template-areas: "navbar" "main"; }
      .app-shell.sidebar-collapsed { grid-template-columns: minmax(0, 1fr); }
      .navbar-brand-wrapper { width: auto; border-right: none; padding: 0 0.5rem; }
      .navbar-brand { display: none !important; }
      .app-sidebar { position: fixed; top: var(--navbar-h); left: 0; bottom: 0; width: min(280px, 86vw); height: auto; transform: translateX(-100%); transition: transform var(--t) var(--ease-out); z-index: 1035; box-shadow: 16px 0 40px -16px var(--shadow); }
      .app-sidebar.open { transform: translateX(0); }
      .app-shell.sidebar-collapsed .app-sidebar .menu-title { display: inline; }
      .app-shell.sidebar-collapsed .app-sidebar .nav-category { font-size: 0.68rem; padding: 0 0.85rem; margin: 1.1rem 0 0.35rem; height: auto; background: transparent !important; }
      .app-shell.sidebar-collapsed .app-sidebar .nav-category::after { display: block; }
      .app-shell.sidebar-collapsed .app-sidebar .nav-item .nav-link { justify-content: flex-start; padding: 0.6rem 0.85rem; }
      .app-shell.sidebar-collapsed .app-sidebar .nav-item .nav-link.active::before { display: block; }
      .navbar-date-wrapper { display: none; }
    }
    @media (max-width: 767.98px) { .navbar-search-wrapper { display: none; } .notif-dropdown { min-width: 320px !important; } }
    @media (max-width: 575.98px) {
      .navbar-toggler, .ameri-theme-toggle, .nav-link.count-indicator { width: 34px; height: 34px; }
      .app-navbar .nav-link { padding: 0.25rem; }
      .user-dropdown .nav-link { width: 34px !important; height: 34px !important; }
      .user-dropdown .nav-link img.img-xs { width: 32px; height: 32px; }
      .navbar-dropdown.show { position: fixed !important; top: var(--navbar-h) !important; left: 0.5rem !important; right: 0.5rem !important; width: auto !important; transform: none !important; }
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
        <a class="navbar-brand" href="{{ url('/vendedor/dashboard') }}">
          <img src="{{ asset('img/logo.png') }}" alt="AMERI">
        </a>
      </div>

      <div class="navbar-menu-wrapper">
        <div class="navbar-welcome">
          <h1 class="welcome-text">
            Hola, <span class="highlight">{{ Auth::user()->name ?? 'Vendedor' }}</span>
          </h1>
        </div>

        <ul class="navbar-nav ms-auto">
          <!-- CALENDARIO -->
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

          <!-- BUSCADOR -->
          <li class="nav-item">
            <div class="navbar-search-wrapper">
              <form class="search-form" action="#" onsubmit="return false;" autocomplete="off" role="search">
                <i class="icon-search"></i>
                <input type="search" class="form-control" id="navbarSearch" placeholder="Buscar módulo..." autocomplete="off">
                <span class="search-kbd">Ctrl K</span>
              </form>
              <div class="search-results" id="searchResults">
                <div class="search-results-header">Resultados</div>
                <div id="searchResultsList"></div>
              </div>
            </div>
          </li>

          <!-- NOTIFICACIONES -->
          <li class="nav-item dropdown">
            <a class="nav-link count-indicator notif-bell" id="notifDropdown" href="#" role="button" data-bs-toggle="dropdown" data-bs-display="static" aria-expanded="false" onclick="event.preventDefault(); event.stopPropagation();">
              <i class="mdi mdi-bell-outline"></i>
              @if($noLeidasNav > 0)
                <span class="notif-badge">{{ $noLeidasNav > 99 ? '99+' : $noLeidasNav }}</span>
              @endif
            </a>
            <div class="dropdown-menu dropdown-menu-end navbar-dropdown notif-dropdown" aria-labelledby="notifDropdown" onclick="event.stopPropagation();">
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
              </div>
              <div class="notif-dropdown-list">
                @forelse($ultimasNotif as $n)
                  <a href="{{ route('admin.notificaciones.index') }}" class="notif-dropdown-item {{ $n->leida ? '' : 'is-unread' }}">
                    <div class="notif-icon-wrap tipo-{{ $n->tipo === 'stock_prestado' ? 'prestado' : 'devuelto' }}">
                      <i class="mdi {{ $n->tipo === 'stock_prestado' ? 'mdi-arrow-down-bold-circle-outline' : 'mdi-arrow-up-bold-circle-outline' }}"></i>
                    </div>
                    <div class="notif-content">
                      <p class="notif-title">{{ $n->titulo }}</p>
                      <p class="notif-msg">{{ \Illuminate\Support\Str::limit($n->mensaje, 70) }}</p>
                    </div>
                  </a>
                @empty
                  <div class="notif-empty">
                    <i class="mdi mdi-bell-sleep-outline"></i>
                    <p class="notif-empty-title">Sin notificaciones</p>
                  </div>
                @endforelse
              </div>
            </div>
          </li>

          <!-- SELECTOR DE TEMA / COLOR -->
          <li class="nav-item dropdown">
            <button class="nav-link p-2" id="themeDropdown" data-bs-toggle="dropdown" aria-expanded="false" aria-label="Cambiar tema y color">
              <i class="mdi mdi-palette-outline fs-5"></i>
            </button>
            <div class="dropdown-menu dropdown-menu-end navbar-dropdown">
              <div class="d-flex gap-2 px-2 pb-2 border-bottom mb-2" style="border-color:var(--line)!important">
                <button class="btn btn-sm btn-outline-secondary w-100" onclick="setTheme('dark')"><i class="mdi mdi-weather-night"></i> Oscuro</button>
                <button class="btn btn-sm btn-outline-secondary w-100" onclick="setTheme('light')"><i class="mdi mdi-white-balance-sunny"></i> Claro</button>
              </div>
              <div class="theme-picker">
                <div class="theme-dot cyan" onclick="setColor('cyan')" title="Cyan"></div>
                <div class="theme-dot green" onclick="setColor('green')" title="Verde"></div>
                <div class="theme-dot orange" onclick="setColor('orange')" title="Naranja"></div>
                <div class="theme-dot violet" onclick="setColor('violet')" title="Violeta"></div>
              </div>
            </div>
          </li>

          <!-- USUARIO -->
          <li class="nav-item dropdown user-dropdown">
            <a class="nav-link" id="UserDropdown" href="#" role="button" data-bs-toggle="dropdown" data-bs-display="static" aria-expanded="false" onclick="event.preventDefault(); event.stopPropagation();">
              <img class="img-xs rounded-circle" src="{{ asset('template/images/faces/face8.jpg') }}" alt="Avatar">
            </a>
            <div class="dropdown-menu dropdown-menu-end navbar-dropdown" aria-labelledby="UserDropdown" onclick="event.stopPropagation();">
              <div class="dropdown-header text-center">
                <img class="img-md rounded-circle" src="{{ asset('template/images/faces/face8.jpg') }}" alt="Avatar">
                <p class="mb-1 mt-3 font-weight-semibold">{{ Auth::user()->name ?? 'Vendedor' }}</p>
                <p class="fw-light text-muted mb-0">{{ Auth::user()->email ?? 'vendedor@ameri.com' }}</p>
              </div>
              <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                <i class="mdi mdi-power me-2"></i>Cerrar sesión
              </a>
              <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">@csrf</form>
            </div>
          </li>
        </ul>
      </div>
    </nav>

    <!-- ==================== SIDEBAR (VENDEDOR) ==================== -->
    <aside class="app-sidebar" id="sidebar" aria-label="Navegación principal">
      <ul class="nav">
        <li class="nav-item">
          <a class="nav-link" href="{{ route('vendedor.dashboard') }}">
            <i class="mdi mdi-view-dashboard-outline menu-icon"></i>
            <span class="menu-title">Inicio</span>
          </a>
        </li>

        <li class="nav-category">Operaciones</li>
         <li class="nav-item">
          <a class="nav-link" href="{{ route('vendedor.productos.buscar') }}">
            <i class="mdi mdi-magnify menu-icon"></i>
            <span class="menu-title">Buscar Producto</span>
          </a>
        </li>

        <li class="nav-item">
          <a class="nav-link {{ request()->routeIs('vendedor.ventas.create') ? 'active' : '' }}" href="{{ route('vendedor.ventas.create') }}">
            <i class="mdi mdi-cart-plus menu-icon"></i>
            <span class="menu-title">Hacer Venta</span>
          </a>
        </li>

        <li class="nav-item">
          <a class="nav-link {{ request()->routeIs('vendedor.ventas.index') ? 'active' : '' }}" href="{{ route('vendedor.ventas.index') }}">
            <i class="mdi mdi-format-list-bulleted menu-icon"></i>
            <span class="menu-title">Historial de Ventas</span>
          </a>
        </li>

        <li class="nav-category">Sistema</li>

        <li class="nav-item">
          <a class="nav-link" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
            <i class="mdi mdi-power menu-icon"></i>
            <span class="menu-title">Cerrar Sesión</span>
          </a>
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
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
  <script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
  <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
  <script src="https://cdn.datatables.net/responsive/2.5.0/js/responsive.bootstrap5.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

  <script>
    /* ============================================================
       TEMA Y COLORES
       ============================================================ */
    function setTheme(theme) {
      document.documentElement.setAttribute('data-theme', theme);
      try { localStorage.setItem('ameri-theme', theme); } catch (e) {}
      var meta = document.querySelector('meta[name="theme-color"]');
      if (meta) meta.setAttribute('content', theme === 'light' ? '#f3f6f7' : '#0a1013');
    }

    function setColor(color) {
      document.documentElement.setAttribute('data-color', color);
      try { localStorage.setItem('ameri-color', color); } catch (e) {}
      document.querySelectorAll('.theme-dot').forEach(function(dot) {
        dot.classList.remove('active');
        if(dot.classList.contains(color)) dot.classList.add('active');
      });
    }

    document.addEventListener('DOMContentLoaded', function() {
        var color = document.documentElement.getAttribute('data-color') || 'cyan';
        document.querySelectorAll('.theme-dot').forEach(function(dot) {
            if(dot.classList.contains(color)) dot.classList.add('active');
        });
    });

    /* ============================================================
       FIX DROPDOWNS
       ============================================================ */
    (function fixDropdowns() {
      function init() {
        if (typeof bootstrap === 'undefined' || !bootstrap.Dropdown) { setTimeout(init, 50); return; }
        document.querySelectorAll('[data-bs-toggle="dropdown"]').forEach(function (toggle) {
          var clone = toggle.cloneNode(true);
          toggle.parentNode.replaceChild(clone, toggle);
          new bootstrap.Dropdown(clone);
          clone.addEventListener('click', function (e) { e.stopImmediatePropagation(); }, true);
        });
      }
      if (document.readyState === 'loading') { document.addEventListener('DOMContentLoaded', init); } else { init(); }
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
            try { localStorage.setItem('ameri-sidebar', shell.classList.contains('sidebar-collapsed') ? '1' : '0'); } catch (e) {}
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
        if (localStorage.getItem('ameri-sidebar') === '1' && isDesktop()) { shell.classList.add('sidebar-collapsed'); }
      } catch (e) {}
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

      btnDate.addEventListener('click', function (e) { e.stopPropagation(); toggle(!cal.classList.contains('open')); });
      if (prev) prev.addEventListener('click', function () { mes--; if (mes < 0) { mes = 11; anio--; } render(); });
      if (next) next.addEventListener('click', function () { mes++; if (mes > 11) { mes = 0; anio++; } render(); });
      if (todayBt) todayBt.addEventListener('click', function () { mes = hoy.getMonth(); anio = hoy.getFullYear(); render(); });

      document.addEventListener('click', function (e) {
        if (cal.contains(e.target)) return;
        if (e.target.closest('.dropdown')) return;
        if (btnDate.contains(e.target)) return;
        toggle(false);
      });

      document.addEventListener('keydown', function (e) { if (e.key === 'Escape') toggle(false); });
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
        { titulo: 'Inicio', path: '{{ route("vendedor.dashboard") }}', icono: 'mdi-view-dashboard-outline', keywords: ['inicio','dashboard','panel'] },
        { titulo: 'Hacer Venta', path: '{{ route("vendedor.ventas.create") }}', icono: 'mdi-cart-plus', keywords: ['vender','venta','nueva'] },
        { titulo: 'Historial de Ventas', path: '{{ route("vendedor.ventas.index") }}', icono: 'mdi-format-list-bulleted', keywords: ['historial','ventas','lista'] }
      ];

      function normalizar(t) { return String(t).toLowerCase().normalize('NFD').replace(/[\u0300-\u036f]/g, ''); }
      function escapar(t) { var d = document.createElement('div'); d.textContent = t; return d.innerHTML; }
      function buscar(termino) {
        var q = normalizar(termino.trim());
        if (!q) return [];
        return modulos.filter(function (m) {
          return normalizar(m.titulo).indexOf(q) !== -1 || m.keywords.some(function (k) { return normalizar(k).indexOf(q) !== -1; });
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
          list.innerHTML = '<div class="search-empty"><i class="mdi mdi-magnify-close"></i>No hay resultados para "<strong>' + escapar(termino) + '</strong>"</div>';
          return;
        }
        encontrados.forEach(function (m, i) {
          var a = document.createElement('a');
          a.href = m.path;
          a.className = 'search-result-item' + (i === 0 ? ' active' : '');
          a.innerHTML = '<span class="result-icon"><i class="mdi ' + m.icono + '"></i></span>' +
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
        timer = setTimeout(function () { render(buscar(val), val.trim()); results.classList.add('open'); }, 100);
      });
      input.addEventListener('keydown', function (e) {
        var els = items();
        var actual = els.findIndex(function (el) { return el.classList.contains('active'); });
        if (e.key === 'ArrowDown') { e.preventDefault(); marcar(actual + 1); }
        else if (e.key === 'ArrowUp') { e.preventDefault(); marcar(actual - 1); }
        else if (e.key === 'Enter') { e.preventDefault(); var sel = els[actual] || els[0]; if (sel) window.location.href = sel.href; }
        else if (e.key === 'Escape') { results.classList.remove('open'); this.blur(); }
      });
      document.addEventListener('keydown', function (e) {
        if ((e.ctrlKey || e.metaKey) && e.key.toLowerCase() === 'k') { e.preventDefault(); input.focus(); input.select(); }
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
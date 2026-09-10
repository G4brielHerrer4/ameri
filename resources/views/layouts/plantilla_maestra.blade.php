{{-- resources/views/layouts/plantilla_maestra.blade.php --}}
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  
  <title>@yield('title', config('app.name', 'AMERI'))</title>
  
  <!-- plugins:css -->`
  <link rel="stylesheet" href="{{ asset('template/vendors/feather/feather.css') }}">
  <link rel="stylesheet" href="{{ asset('template/vendors/mdi/css/materialdesignicons.min.css') }}">
  <link rel="stylesheet" href="{{ asset('template/vendors/ti-icons/css/themify-icons.css') }}">
  <link rel="stylesheet" href="{{ asset('template/vendors/typicons/typicons.css') }}">
  <link rel="stylesheet" href="{{ asset('template/vendors/simple-line-icons/css/simple-line-icons.css') }}">
  <link rel="stylesheet" href="{{ asset('template/vendors/css/vendor.bundle.base.css') }}">
  
  <link rel="stylesheet" href="{{ asset('template/vendors/datatables.net-bs4/dataTables.bootstrap4.css') }}">
  <link rel="stylesheet" href="{{ asset('template/js/select.dataTables.min.css') }}">
  
  <link rel="stylesheet" href="{{ asset('template/css/vertical-layout-light/style.css') }}">
  <link rel="shortcut icon" href="{{ asset('template/images/favicon.png') }}" />
  
  @stack('styles')
  @yield('css')
</head>
<body>
  <div class="container-scroller"> 
    
    <!-- ========== NAVBAR (COMPLETO) ========== -->
    <nav class="navbar default-layout col-lg-12 col-12 p-0 fixed-top d-flex align-items-top flex-row">
      <div class="text-center navbar-brand-wrapper d-flex align-items-center justify-content-start">
        <div class="me-3">
          <button class="navbar-toggler navbar-toggler align-self-center" type="button" data-bs-toggle="minimize">
            <span class="icon-menu"></span>
          </button>
        </div>
        <div>
          <a class="navbar-brand brand-logo" href="{{ url('/dashboard') }}">
            <img src="{{ asset('img/logo.png') }}" alt="logo" />
          </a>
          <a class="navbar-brand brand-logo-mini" href="{{ url('/dashboard') }}">
            <img src="{{ asset('img/logo.png') }}" alt="logo" />
          </a>
        </div>
      </div>
      <div class="navbar-menu-wrapper d-flex align-items-top"> 
        <ul class="navbar-nav">
          <li class="nav-item font-weight-semibold d-none d-lg-block ms-0">
            <h1 class="welcome-text">Bienvenido, <span class="text-black fw-bold">{{ Auth::user()->name ?? 'AMERI SRL' }}</span></h1>
            <h3 class="welcome-sub-text">Un nuevo dia para hacer mejor las cosas....</h3>
          </li>
        </ul>
        <ul class="navbar-nav ms-auto">
          <li class="nav-item dropdown d-none d-lg-block">
            <a class="nav-link dropdown-bordered dropdown-toggle dropdown-toggle-split" id="messageDropdown" href="#" data-bs-toggle="dropdown">Select Category</a>
            <div class="dropdown-menu dropdown-menu-right navbar-dropdown preview-list pb-0">
              <a class="dropdown-item py-3"><p class="mb-0 font-weight-medium float-left">Select category</p></a>
              <div class="dropdown-divider"></div>
              <a class="dropdown-item preview-item"><div class="preview-item-content flex-grow py-2"><p class="preview-subject ellipsis font-weight-medium text-dark">Bootstrap Bundle</p><p class="fw-light small-text mb-0">16 unique dashboards</p></div></a>
              <a class="dropdown-item preview-item"><div class="preview-item-content flex-grow py-2"><p class="preview-subject ellipsis font-weight-medium text-dark">Angular Bundle</p><p class="fw-light small-text mb-0">For Angular projects</p></div></a>
              <a class="dropdown-item preview-item"><div class="preview-item-content flex-grow py-2"><p class="preview-subject ellipsis font-weight-medium text-dark">VUE Bundle</p><p class="fw-light small-text mb-0">6 Premium Vue Dashboards</p></div></a>
            </div>
          </li>
          <li class="nav-item d-none d-lg-block">
            <div class="input-group date datepicker navbar-date-picker">
              <span class="input-group-addon input-group-prepend border-right"><span class="icon-calendar input-group-text calendar-icon"></span></span>
              <input type="text" class="form-control">
            </div>
          </li>
          <li class="nav-item">
            <form class="search-form" action="#">
              <i class="icon-search"></i>
              <input type="search" class="form-control" placeholder="Search Here">
            </form>
          </li>
          <li class="nav-item dropdown">
            <a class="nav-link count-indicator" id="notificationDropdown" href="#" data-bs-toggle="dropdown">
              <i class="icon-mail icon-lg"></i>
            </a>
            <div class="dropdown-menu dropdown-menu-right navbar-dropdown preview-list pb-0">
              <a class="dropdown-item py-3 border-bottom"><p class="mb-0 font-weight-medium float-left">You have 4 new notifications</p><span class="badge badge-pill badge-primary float-right">View all</span></a>
              <a class="dropdown-item preview-item py-3"><div class="preview-thumbnail"><i class="mdi mdi-alert m-auto text-primary"></i></div><div class="preview-item-content"><h6 class="preview-subject fw-normal text-dark mb-1">Application Error</h6><p class="fw-light small-text mb-0">Just now</p></div></a>
              <a class="dropdown-item preview-item py-3"><div class="preview-thumbnail"><i class="mdi mdi-settings m-auto text-primary"></i></div><div class="preview-item-content"><h6 class="preview-subject fw-normal text-dark mb-1">Settings</h6><p class="fw-light small-text mb-0">Private message</p></div></a>
            </div>
          </li>
          <li class="nav-item dropdown d-none d-lg-block user-dropdown">
            <a class="nav-link" id="UserDropdown" href="#" data-bs-toggle="dropdown">
              <img class="img-xs rounded-circle" src="{{ asset('template/images/faces/face8.jpg') }}" alt="Profile image">
            </a>
            <div class="dropdown-menu dropdown-menu-right navbar-dropdown">
              <div class="dropdown-header text-center">
                <img class="img-md rounded-circle" src="{{ asset('template/images/faces/face8.jpg') }}" alt="Profile image">
                <p class="mb-1 mt-3 font-weight-semibold">{{ Auth::user()->name ?? 'Allen Moreno' }}</p>
                <p class="fw-light text-muted mb-0">{{ Auth::user()->email ?? 'allenmoreno@gmail.com' }}</p>
              </div>
              <a class="dropdown-item" href="#"><i class="dropdown-item-icon mdi mdi-account-outline text-primary me-2"></i> Mi Perfil</a>
              <a class="dropdown-item" href="#"><i class="dropdown-item-icon mdi mdi-message-text-outline text-primary me-2"></i> Mensajes</a>
              <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><i class="dropdown-item-icon mdi mdi-power text-primary me-2"></i>Cerrar Sesión</a>
              <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">@csrf</form>
            </div>
          </li>
        </ul>
        <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button" data-bs-toggle="offcanvas">
          <span class="mdi mdi-menu"></span>
        </button>
      </div>
    </nav>
    
    <div class="container-fluid page-body-wrapper">
      
      <!-- ========== SIDEBAR (COMPLETO) ========== -->
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
              <i class="menu-icon mdi mdi-floor-plan"></i>
              <span class="menu-title">Usuarios</span>
              <i class="menu-arrow"></i>
            </a>
            <div class="collapse" id="ui-basic">
              <ul class="nav flex-column sub-menu">
                <li class="nav-item"><a class="nav-link" href="{{ route('admin.users.index') }}">Administracion</a></li>
                <li class="nav-item"><a class="nav-link" href="#">Clientes</a></li>
                {{-- <li class="nav-item"><a class="nav-link" href="#">Typography</a></li> --}}
              </ul>
            </div>
          </li>
          <li class="nav-item nav-category">Administracion</li>
          <li class="nav-item">
            <a class="nav-link" data-bs-toggle="collapse" href="#form-elements">
              <i class="menu-icon mdi mdi-card-text-outline"></i>
              <span class="menu-title">Catalogo</span>
              <i class="menu-arrow"></i>
            </a>
            <div class="collapse" id="form-elements">
              <ul class="nav flex-column sub-menu">
                <li class="nav-item"><a class="nav-link" href="">Agregar producto</a></li>
              </ul>
            </div>
          </li>
          <li class="nav-item">
            <a class="nav-link" data-bs-toggle="collapse" href="#charts">
              <i class="menu-icon mdi mdi-chart-line"></i>
              <span class="menu-title">Proveedores</span>
              <i class="menu-arrow"></i>
            </a>
            <div class="collapse" id="charts">
              <ul class="nav flex-column sub-menu">
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('proveedores.index') }}">
                        Ver Proveedores
                    </a>
                </li>
              </ul>
            </div>
          </li>
          <li class="nav-item">
            <a class="nav-link" data-bs-toggle="collapse" href="#tables">
              <i class="menu-icon mdi mdi-table"></i>
              <span class="menu-title">Tablas</span>
              <i class="menu-arrow"></i>
            </a>
            <div class="collapse" id="tables">
              <ul class="nav flex-column sub-menu">
                <li class="nav-item"><a class="nav-link" href="#">abc</a></li>
              </ul>
            </div>
          </li>
          <li class="nav-item">
            <a class="nav-link" data-bs-toggle="collapse" href="#icons">
              <i class="menu-icon mdi mdi-layers-outline"></i>
              <span class="menu-title">Iconos</span>
              <i class="menu-arrow"></i>
            </a>
            <div class="collapse" id="icons">
              <ul class="nav flex-column sub-menu">
                <li class="nav-item"><a class="nav-link" href="#">abc</a></li>
              </ul>
            </div>
          </li>
        </ul>
      </nav>
      
      <div class="main-panel">
        <div class="content-wrapper">
          <!-- ========== AQUÍ VA EL CONTENIDO DE CADA PÁGINA ========== -->
          @yield('content')
        </div>
        
        <!-- ========== FOOTER (COMPLETO) ========== -->
        {{-- <footer class="footer">
          <div class="d-sm-flex justify-content-center justify-content-sm-between">
            <span class="text-muted text-center text-sm-left d-block d-sm-inline-block">Premium <a href="https://www.bootstrapdash.com/" target="_blank">Bootstrap admin template</a> from BootstrapDash.</span>
            <span class="float-none float-sm-right d-block mt-1 mt-sm-0 text-center">Copyright © {{ date('Y') }}. All rights reserved.</span>
          </div>
        </footer> --}}
      </div>
    </div>
  </div>

  <!-- ========== SCRIPTS (COMPLETOS) ========== -->
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
  
  @stack('scripts')
  @yield('js')
</body>
</html>
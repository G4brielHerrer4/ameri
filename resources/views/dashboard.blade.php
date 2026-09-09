{{-- resources/views/dashboard.blade.php --}}
@extends('layouts.plantilla_maestra')

@section('title', 'Dashboard - AMERI')

@section('content')
<div class="row">
  <div class="col-sm-12">
    <div class="home-tab">
      <div class="d-sm-flex align-items-center justify-content-between border-bottom">
        <ul class="nav nav-tabs" role="tablist">
          <li class="nav-item">
            <a class="nav-link active ps-0" id="home-tab" data-bs-toggle="tab" href="#overview" role="tab" aria-controls="overview" aria-selected="true">Overview</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" id="profile-tab" data-bs-toggle="tab" href="#audiences" role="tab" aria-selected="false">Audiences</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" id="contact-tab" data-bs-toggle="tab" href="#demographics" role="tab" aria-selected="false">Demographics</a>
          </li>
        </ul>
        <div>
          <div class="btn-wrapper">
            <a href="#" class="btn btn-otline-dark align-items-center"><i class="icon-share"></i> Share</a>
            <a href="#" class="btn btn-primary text-white me-0"><i class="icon-download"></i> Export</a>
          </div>
        </div>
      </div>
      
      <div class="tab-content tab-content-basic">
        <div class="tab-pane fade show active" id="overview" role="tabpanel" aria-labelledby="overview"> 
          
          <!-- Tarjetas de estadísticas -->
          <div class="row">
            <div class="col-sm-12">
              <div class="statistics-details d-flex align-items-center justify-content-between">
                <div>
                  <p class="statistics-title">Bounce Rate</p>
                  <h3 class="rate-percentage">32.53%</h3>
                  <p class="text-danger d-flex"><i class="mdi mdi-menu-down"></i><span>-0.5%</span></p>
                </div>
                <div>
                  <p class="statistics-title">Page Views</p>
                  <h3 class="rate-percentage">7,682</h3>
                  <p class="text-success d-flex"><i class="mdi mdi-menu-up"></i><span>+0.1%</span></p>
                </div>
                <div>
                  <p class="statistics-title">New Sessions</p>
                  <h3 class="rate-percentage">68.8</h3>
                  <p class="text-danger d-flex"><i class="mdi mdi-menu-down"></i><span>68.8</span></p>
                </div>
                <div class="d-none d-md-block">
                  <p class="statistics-title">Avg. Time on Site</p>
                  <h3 class="rate-percentage">2m:35s</h3>
                  <p class="text-success d-flex"><i class="mdi mdi-menu-down"></i><span>+0.8%</span></p>
                </div>
              </div>
            </div>
          </div>
          
          <!-- Mensaje de bienvenida personalizado -->
          <div class="row mt-4">
            <div class="col-12">
              <div class="card card-rounded">
                <div class="card-body">
                  <h3 class="card-title">Bienvenido, {{ Auth::user()->name }}!</h3>
                  <p class="card-subtitle">Has iniciado sesión correctamente en el panel de administración de AMERI.</p>
                  <hr>
                  <p>Desde aquí puedes gestionar todos los aspectos de tu cuenta y acceder a las diferentes secciones del sistema.</p>
                </div>
              </div>
            </div>
          </div>
          
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

@section('css')
<style>
  /* Estilos adicionales para el dashboard si los necesitas */
  .home-tab {
    background: white;
    border-radius: 8px;
    padding: 20px;
  }
</style>
@endsection
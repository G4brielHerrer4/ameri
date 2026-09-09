
@extends('layouts.plantilla_maestra_ven')

@section('title', 'Dashboard - Vendedor')

@section('content')
<div class="row">
  <div class="col-12">
    <div class="card">
      <div class="card-body">
        <h4>Bienvenido, {{ auth()->user()->name }}</h4>
        <p>Panel de vendedor</p>
      </div>
    </div>
  </div>
</div>
@endsection
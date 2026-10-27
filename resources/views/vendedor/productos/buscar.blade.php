@extends('layouts.plantilla_maestra_ven')
@section('title', 'Buscar Producto')

@push('styles')
<style>
  .search-header {
    text-align: center;
    margin-bottom: 2.5rem;
    padding: 1rem;
  }
  .search-header h2 {
    font-family: var(--font-display);
    font-weight: 700;
    color: var(--ink);
    font-size: 1.8rem;
    margin-bottom: 0.5rem;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.75rem;
  }
  .search-header h2 .mdi {
    color: var(--primary);
    font-size: 2rem;
  }
  .search-header p {
    color: var(--ink-muted);
    font-size: 0.95rem;
  }

  .search-input-wrapper {
    position: relative;
    max-width: 650px;
    margin: 0 auto 3rem;
  }
  .search-input-wrapper .mdi {
    position: absolute;
    left: 1.25rem;
    top: 50%;
    transform: translateY(-50%);
    font-size: 1.4rem;
    color: var(--ink-faint);
    pointer-events: none;
  }
  .search-input-wrapper input {
    width: 100%;
    padding: 1rem 1rem 1rem 3.5rem;
    height: 60px;
    font-size: 1.05rem;
    border-radius: var(--r-pill);
    background: var(--bg-1);
    border: 1px solid var(--line);
    color: var(--ink);
    box-shadow: 0 10px 30px -15px var(--shadow);
    transition: all 0.3s var(--ease);
  }
  .search-input-wrapper input:focus {
    outline: none;
    border-color: var(--primary);
    box-shadow: 0 0 0 4px var(--glow-soft), 0 10px 30px -15px var(--shadow);
    background: var(--bg-1);
  }
  .search-input-wrapper input::placeholder {
    color: var(--ink-faint);
  }

  .producto-card {
    background: var(--bg-1);
    border: 1px solid var(--line);
    border-radius: var(--r-lg);
    padding: 1.5rem;
    margin-bottom: 1.25rem;
    transition: all 0.3s var(--ease);
    display: flex;
    flex-direction: column;
  }
  .producto-card:hover {
    border-color: var(--line-strong);
    box-shadow: 0 10px 30px -15px var(--glow);
    transform: translateY(-2px);
  }
  
  .prod-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 1.25rem;
    gap: 1rem;
    flex-wrap: wrap;
  }
  .prod-title {
    font-family: var(--font-display);
    font-weight: 700;
    color: var(--ink);
    font-size: 1.25rem;
    margin: 0 0 0.25rem 0;
  }
  .prod-tipo {
    font-size: 0.8rem;
    color: var(--primary);
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    display: flex;
    align-items: center;
    gap: 0.25rem;
  }
  .prod-meta {
    display: flex;
    gap: 1rem;
    color: var(--ink-muted);
    font-size: 0.85rem;
    flex-wrap: wrap;
    margin-top: 0.5rem;
  }
  .prod-meta span {
    display: inline-flex;
    align-items: center;
    gap: 0.35rem;
    background: var(--bg-2);
    padding: 0.25rem 0.65rem;
    border-radius: var(--r-sm);
    border: 1px solid var(--line);
  }

  .stock-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(240px, 1fr));
    gap: 1rem;
    margin-top: auto;
    padding-top: 1rem;
    border-top: 1px dashed var(--line);
  }
  .stock-admin-card {
    background: var(--bg-2);
    border: 1px solid var(--line);
    border-radius: var(--r-md);
    padding: 1rem;
    transition: all 0.2s ease;
  }
  .stock-admin-card:hover {
    border-color: var(--line-strong);
    background: var(--surface-alpha);
  }
  .admin-header {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    margin-bottom: 0.75rem;
    padding-bottom: 0.5rem;
    border-bottom: 1px solid var(--line);
  }
  .admin-header .mdi {
    color: var(--primary);
    font-size: 1.1rem;
  }
  .admin-nombre {
    font-weight: 600;
    color: var(--ink);
    font-size: 0.9rem;
  }
  .deposito-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    font-size: 0.82rem;
    padding: 0.35rem 0;
  }
  .deposito-row:last-child { padding-bottom: 0; }
  .deposito-nombre {
    color: var(--ink-muted);
    display: flex;
    align-items: center;
    gap: 0.35rem;
  }
  .deposito-cant {
    font-family: var(--font-display);
    font-weight: 700;
    color: var(--primary);
    background: var(--glow-soft);
    padding: 0.15rem 0.5rem;
    border-radius: var(--r-pill);
    font-size: 0.8rem;
  }
  
  /* ESTADO INICIAL (Invitación a buscar) */
  .empty-state {
    text-align: center;
    padding: 5rem 1rem;
    background: var(--bg-1);
    border: 1px dashed var(--line);
    border-radius: var(--r-lg);
    margin-top: 2rem;
  }
  .empty-state .mdi {
    font-size: 5rem;
    color: var(--primary);
    opacity: 0.3;
    margin-bottom: 1rem;
    animation: pulse 2s infinite;
  }
  .empty-state h5 {
    color: var(--ink);
    font-weight: 700;
    font-family: var(--font-display);
    font-size: 1.3rem;
    margin-bottom: 0.5rem;
  }
  .empty-state p {
    color: var(--ink-muted);
    font-size: 0.95rem;
    max-width: 400px;
    margin: 0 auto;
  }
  @keyframes pulse {
    0% { transform: scale(1); opacity: 0.3; }
    50% { transform: scale(1.05); opacity: 0.5; }
    100% { transform: scale(1); opacity: 0.3; }
  }
</style>
@endpush

@section('content')
<div class="container-fluid py-4">

  <!-- Header -->
  <div class="search-header">
    <h2>
      <i class="mdi mdi-package-variant-closed"></i>
      Buscar Producto Global
    </h2>
    <p>Consulta la disponibilidad exacta de un producto en todas las tiendas y administradores.</p>
  </div>

  <!-- Buscador -->
  <div class="search-input-wrapper">
    <i class="mdi mdi-magnify"></i>
    <input type="text" id="searchInput" class="form-control" placeholder="Escribe el nombre, marca o medida del producto..." autocomplete="off">
  </div>

  <!-- 
    ESTADO INICIAL: Se muestra antes de buscar 
  -->
  <div id="estadoInicial" class="empty-state">
    <i class="mdi mdi-magnify"></i>
    <h5>Empieza a buscar</h5>
    <p>Escribe al menos 2 letras en el buscador para encontrar productos en el inventario de todos los administradores.</p>
  </div>

  <!-- 
    RESULTADOS: Ocultos por defecto. Solo se muestran al buscar. 
  -->
  <div id="resultados" style="display: none;">
    @forelse($productos as $producto)
      @php
        $busquedaStr = Str::lower(
            $producto->nombre . ' ' . 
            ($producto->marca ?? '') . ' ' . 
            ($producto->modelo ?? '') . ' ' . 
            ($producto->medida ?? '') . ' ' .
            ($producto->tipoProducto?->categoria?->nombre ?? '') . ' ' .
            ($producto->tipoProducto?->nombre ?? '')
        );
      @endphp
      
      <div class="producto-card" data-search="{{ $busquedaStr }}">
        <!-- Info del producto -->
        <div class="prod-header">
          <div>
            <h4 class="prod-title nombre-producto">{{ $producto->nombre }}</h4>
            <span class="prod-tipo">
              <i class="mdi mdi-tag-outline"></i> 
              {{ $producto->tipoProducto?->categoria?->nombre }} · {{ $producto->tipoProducto?->nombre }}
            </span>
          </div>
          
          <div class="prod-meta">
            @if($producto->marca) <span><i class="mdi mdi-trademark"></i> {{ $producto->marca }}</span> @endif
            @if($producto->medida) <span><i class="mdi mdi-ruler"></i> {{ $producto->medida }}</span> @endif
            @if($producto->modelo) <span><i class="mdi mdi-barcode"></i> {{ $producto->modelo }}</span> @endif
            @if($producto->color) <span><i class="mdi mdi-palette"></i> {{ $producto->color }}</span> @endif
          </div>
        </div>

        <!-- Desglose de stock -->
        <div class="stock-grid">
          @forelse($producto->stocks->groupBy('user_id') as $userId => $stocks)
            <div class="stock-admin-card">
              <div class="admin-header">
                <i class="mdi mdi-account-circle-outline"></i>
                <span class="admin-nombre">{{ $stocks->first()->user->name ?? 'Admin Desconocido' }}</span>
              </div>
              <div>
                @foreach($stocks as $stock)
                  <div class="deposito-row">
                    <span class="deposito-nombre">
                      <i class="mdi mdi-storefront-outline"></i> 
                      {{ $stock->deposito->nombre ?? 'Tienda Desconocida' }}
                    </span>
                    <span class="deposito-cant">
                      {{ number_format($stock->cantidad, 0) }} pzs
                    </span>
                  </div>
                @endforeach
              </div>
            </div>
          @empty
            <div class="stock-admin-card" style="border-style: dashed; opacity: 0.7;">
              <div class="d-flex align-items-center gap-2 text-muted" style="font-size: 0.85rem;">
                <i class="mdi mdi-information-outline"></i>
                Sin stock en ninguna tienda
              </div>
            </div>
          @endforelse
        </div>
      </div>
    @empty
      <!-- Si no hay productos en la BD -->
      <div class="empty-state">
        <i class="mdi mdi-package-variant"></i>
        <h5>No hay productos registrados</h5>
        <p>Agrega productos desde el módulo de Suministros para verlos aquí.</p>
      </div>
    @endforelse

    <!-- Estado vacío por búsqueda no encontrada -->
    <div id="noResults" class="empty-state" style="display: none;">
      <i class="mdi mdi-magnify-close"></i>
      <h5>No se encontraron productos</h5>
      <p>No hay resultados para tu búsqueda. Intenta con otra palabra.</p>
    </div>
  </div>

</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
  const searchInput = document.getElementById('searchInput');
  const cards = document.querySelectorAll('.producto-card');
  const noResults = document.getElementById('noResults');
  const estadoInicial = document.getElementById('estadoInicial');
  const resultadosContainer = document.getElementById('resultados');

  searchInput.addEventListener('input', function() {
    const query = this.value.toLowerCase().trim();

    // 1. Si tiene menos de 2 letras, volvemos al estado inicial
    if (query.length < 2) {
      estadoInicial.style.display = 'block';
      resultadosContainer.style.display = 'none';
      return;
    }

    // 2. Si tiene 2 o más letras, mostramos el contenedor y filtramos
    estadoInicial.style.display = 'none';
    resultadosContainer.style.display = 'block';
    
    let encontrados = 0;

    cards.forEach(card => {
      const searchData = card.dataset.search;
      
      if (searchData.includes(query)) {
        card.style.display = 'flex';
        encontrados++;
      } else {
        card.style.display = 'none';
      }
    });

    // 3. Manejo del mensaje de "No encontrado"
    if (encontrados === 0) {
      noResults.style.display = 'block';
    } else {
      noResults.style.display = 'none';
    }
  });
});
</script>
@endpush
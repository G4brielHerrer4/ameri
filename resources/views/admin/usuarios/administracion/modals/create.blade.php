{{-- resources/views/admin/usuarios/administracion/modals/create.blade.php --}}

<div class="modal fade" id="modalCreateUser" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content user-modal">

      {{-- ===== HEADER ===== --}}
      <div class="modal-header user-modal-header">
        <div class="user-modal-header-info">
          <div class="user-modal-icon user-modal-icon-create">
            <i class="mdi mdi-account-plus-outline"></i>
          </div>
          <div>
            <h5 class="user-modal-title" id="modalCreateUserLabel">Crear Usuario</h5>
            <p class="user-modal-subtitle">Ingresa los datos del nuevo usuario</p>
          </div>
        </div>
        <button type="button" class="user-modal-close" data-bs-dismiss="modal" aria-label="Close">
          <i class="mdi mdi-close"></i>
        </button>
      </div>

      <form id="formCreateUser" action="{{ route('admin.users.store') }}" method="POST">
        @csrf

        {{-- ===== BODY ===== --}}
        <div class="modal-body user-modal-body">

          {{-- Nombre --}}
          <div class="user-form-group">
            <label for="name" class="user-form-label">
              <i class="mdi mdi-account-outline"></i>
              Nombre Completo
              <span class="user-required">*</span>
            </label>
            <div class="user-input-wrapper">
              <input type="text" class="user-input" id="name" name="name"
                     placeholder="Ej. Juan Pérez" required>
              <span class="user-input-icon">
                <i class="mdi mdi-account-outline"></i>
              </span>
            </div>
            <div class="invalid-feedback"></div>
          </div>

          {{-- Email --}}
          <div class="user-form-group">
            <label for="email" class="user-form-label">
              <i class="mdi mdi-email-outline"></i>
              Correo Electrónico
              <span class="user-required">*</span>
            </label>
            <div class="user-input-wrapper">
              <input type="email" class="user-input" id="email" name="email"
                     placeholder="ejemplo@empresa.com" required>
              <span class="user-input-icon">
                <i class="mdi mdi-email-outline"></i>
              </span>
            </div>
            <div class="invalid-feedback"></div>
          </div>

          {{-- Teléfono --}}
          <div class="user-form-group">
            <label for="telefono" class="user-form-label">
              <i class="mdi mdi-phone-outline"></i>
              Teléfono
            </label>
            <div class="user-input-wrapper">
              <input type="text" class="user-input" id="telefono" name="telefono"
                     placeholder="Ej. 70012345" maxlength="30">
              <span class="user-input-icon">
                <i class="mdi mdi-phone-outline"></i>
              </span>
            </div>
            <div class="invalid-feedback"></div>
          </div>

          {{-- Dirección --}}
          <div class="user-form-group">
            <label for="direccion" class="user-form-label">
              <i class="mdi mdi-map-marker-outline"></i>
              Dirección
            </label>
            <div class="user-input-wrapper">
              <input type="text" class="user-input" id="direccion" name="direccion"
                     placeholder="Ej. Av. principal, zona central" maxlength="255">
              <span class="user-input-icon">
                <i class="mdi mdi-map-marker-outline"></i>
              </span>
            </div>
            <div class="invalid-feedback"></div>
          </div>

          {{-- Contraseñas (grid 2 columnas) --}}
          <div class="user-form-row">
            <div class="user-form-group">
              <label for="password" class="user-form-label">
                <i class="mdi mdi-lock-outline"></i>
                Contraseña
                <span class="user-required">*</span>
              </label>
              <div class="user-input-wrapper">
                <input type="password" class="user-input" id="password" name="password"
                       placeholder="••••••••" required>
                <span class="user-input-icon">
                  <i class="mdi mdi-lock-outline"></i>
                </span>
              </div>
              <div class="invalid-feedback"></div>
            </div>

            <div class="user-form-group">
              <label for="password_confirmation" class="user-form-label">
                <i class="mdi mdi-lock-check-outline"></i>
                Confirmar
                <span class="user-required">*</span>
              </label>
              <div class="user-input-wrapper">
                <input type="password" class="user-input" id="password_confirmation"
                       name="password_confirmation" placeholder="••••••••" required>
                <span class="user-input-icon">
                  <i class="mdi mdi-lock-check-outline"></i>
                </span>
              </div>
              <div class="invalid-feedback"></div>
            </div>
          </div>

          {{-- Rol --}}
          <div class="user-form-group">
            <label for="role_id" class="user-form-label">
              <i class="mdi mdi-shield-account-outline"></i>
              Rol
              <span class="user-required">*</span>
            </label>
            <div class="user-input-wrapper">
              <select class="user-input user-select" id="role_id" name="role_id" required>
                <option value="">Selecciona un rol</option>
                @foreach($roles as $role)
                  <option value="{{ $role->id }}">
                    {{ $role->name }}
                  </option>
                @endforeach
              </select>
              <span class="user-input-icon">
                <i class="mdi mdi-shield-account-outline"></i>
              </span>
            </div>
            <div class="invalid-feedback"></div>
          </div>

        </div>

        {{-- ===== FOOTER ===== --}}
        <div class="modal-footer user-modal-footer">
          <button type="button" class="user-btn user-btn-cancel" data-bs-dismiss="modal">
            <i class="mdi mdi-close"></i>
            Cancelar
          </button>
          <button type="submit" class="user-btn user-btn-primary">
            <i class="mdi mdi-content-save-outline"></i>
            Guardar Usuario
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

<style>
  /* ============================================================
     MODAL CREATE — TEMA AMERI (dark/light)
     ============================================================ */
  .user-modal {
    background: var(--bg-1);
    border: 1px solid var(--line);
    border-radius: var(--r-xl);
    overflow: hidden;
    box-shadow: 0 40px 80px -40px var(--shadow);
    color: var(--ink);
  }

  /* HEADER */
  .user-modal-header {
    position: relative;
    border-bottom: 1px solid var(--line);
    background: var(--bg-1);
    padding: 1.25rem 1.5rem;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 1rem;
  }

  .user-modal-header::before {
    content: '';
    position: absolute;
    top: 0; left: 0; right: 0;
    height: 1px;
    background: linear-gradient(90deg, transparent, var(--cyan), transparent);
    opacity: 0.6;
  }

  .user-modal-header-info {
    display: flex;
    align-items: center;
    gap: 0.9rem;
    min-width: 0;
  }

  .user-modal-icon {
    width: 44px;
    height: 44px;
    border-radius: var(--r-md);
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    background: var(--surface-alpha);
    border: 1px solid var(--line);
    color: var(--cyan);
    font-size: 1.3rem;
    box-shadow: 0 0 22px -10px var(--glow);
  }

  /* Variante create: acento verde cyan más vivo */
  .user-modal-icon-create {
    background: var(--glow-soft);
    border-color: var(--line-strong);
    color: var(--cyan);
  }

  .user-modal-title {
    font-family: var(--font-display);
    font-size: 1.1rem;
    font-weight: 600;
    color: var(--ink);
    margin: 0 0 0.15rem;
    letter-spacing: -0.01em;
  }

  .user-modal-subtitle {
    font-size: 0.8rem;
    color: var(--ink-muted);
    margin: 0;
  }

  .user-modal-close {
    width: 34px;
    height: 34px;
    border-radius: var(--r-sm);
    border: 1px solid var(--line);
    background: transparent;
    color: var(--ink-muted);
    display: inline-flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.25s var(--ease);
    flex-shrink: 0;
  }

  .user-modal-close .mdi { font-size: 1.1rem; }

  .user-modal-close:hover {
    border-color: var(--error);
    color: var(--error);
    background: var(--error-bg);
    box-shadow: 0 0 16px -4px rgba(255, 85, 112, 0.5);
  }

  /* BODY */
  .user-modal-body {
    padding: 1.5rem;
    background: var(--bg-1);
  }

  .user-form-group {
    margin-bottom: 1.1rem;
  }

  .user-form-group:last-child {
    margin-bottom: 0;
  }

  /* Fila de 2 columnas para contraseñas */
  .user-form-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1rem;
    margin-bottom: 1.1rem;
  }

  .user-form-row .user-form-group {
    margin-bottom: 0;
  }

  @media (max-width: 575.98px) {
    .user-form-row {
      grid-template-columns: 1fr;
      gap: 1.1rem;
    }
  }

  .user-form-label {
    display: flex;
    align-items: center;
    gap: 0.4rem;
    font-size: 0.76rem;
    font-weight: 600;
    color: var(--ink-muted);
    margin-bottom: 0.5rem;
    font-family: var(--font-display);
    letter-spacing: 0.04em;
    text-transform: uppercase;
  }

  .user-form-label .mdi {
    color: var(--cyan);
    font-size: 0.95rem;
  }

  .user-required {
    color: var(--error);
    margin-left: 0.15rem;
  }

  .user-input-wrapper { position: relative; }

  .user-input-icon {
    position: absolute;
    left: 1rem;
    top: 50%;
    transform: translateY(-50%);
    color: var(--ink-faint);
    display: flex;
    align-items: center;
    pointer-events: none;
    transition: color 0.3s ease;
  }

  .user-input-icon .mdi { font-size: 1.1rem; }

  .user-input {
    width: 100%;
    padding: 0.8rem 1rem 0.8rem 2.85rem;
    border: 1px solid var(--line);
    border-radius: var(--r-md);
    font-family: inherit;
    font-size: 0.9rem;
    color: var(--ink);
    background: var(--bg-2);
    transition: all 0.3s var(--ease);
  }

  .user-input::placeholder { color: var(--ink-faint); }

  .user-input:hover { border-color: var(--line-strong); }

  .user-input:focus {
    outline: none;
    border-color: var(--cyan);
    background: var(--bg-3);
    box-shadow: 0 0 0 4px var(--glow-soft), 0 0 20px -6px var(--glow);
  }

  .user-input:focus ~ .user-input-icon { color: var(--cyan); }

  /* Select */
  .user-select {
    appearance: none;
    -webkit-appearance: none;
    cursor: pointer;
    padding-right: 2.5rem;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='8' viewBox='0 0 12 8'%3E%3Cpath d='M1 1l5 5 5-5' stroke='%238fa8ae' stroke-width='2' fill='none' stroke-linecap='round'/%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: right 1rem center;
  }

  html[data-theme="light"] .user-select {
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='8' viewBox='0 0 12 8'%3E%3Cpath d='M1 1l5 5 5-5' stroke='%23476067' stroke-width='2' fill='none' stroke-linecap='round'/%3E%3C/svg%3E");
  }

  .user-select option {
    background: var(--bg-1);
    color: var(--ink);
    padding: 0.5rem;
  }

  /* Invalid state */
  .user-input.is-invalid {
    border-color: var(--error) !important;
    box-shadow: 0 0 0 4px var(--error-bg) !important;
  }

  .invalid-feedback {
    font-size: 0.76rem;
    color: var(--error);
    margin-top: 0.4rem;
    display: none;
  }

  /* FOOTER */
  .user-modal-footer {
    border-top: 1px solid var(--line);
    background: var(--bg-1);
    padding: 1rem 1.5rem;
    display: flex;
    justify-content: flex-end;
    gap: 0.6rem;
    flex-wrap: wrap;
  }

  .user-btn {
    display: inline-flex;
    align-items: center;
    gap: 0.45rem;
    padding: 0.65rem 1.3rem;
    border-radius: var(--r-pill);
    font-family: var(--font-display);
    font-weight: 600;
    font-size: 0.85rem;
    letter-spacing: 0.02em;
    cursor: pointer;
    transition: all 0.3s var(--ease);
    border: 1px solid transparent;
    background: transparent;
    position: relative;
    overflow: hidden;
  }

  .user-btn .mdi { font-size: 1rem; }

  /* Cancelar (outline neutro) */
  .user-btn-cancel {
    border-color: var(--line);
    color: var(--ink-muted);
  }

  .user-btn-cancel:hover {
    border-color: var(--line-strong);
    color: var(--ink);
    background: var(--surface-alpha);
  }

  /* Primary (outline cyan → glow) */
  .user-btn-primary {
    border-color: var(--line-strong);
    color: var(--cyan);
    background: var(--surface-alpha);
  }

  .user-btn-primary::before {
    content: '';
    position: absolute;
    inset: 0;
    background: linear-gradient(120deg, transparent 30%, var(--glow-soft) 50%, transparent 70%);
    transform: translateX(-100%);
    transition: transform 0.7s var(--ease);
  }

  .user-btn-primary:hover::before { transform: translateX(100%); }

  .user-btn-primary:hover {
    border-color: var(--cyan);
    background: var(--glow-soft);
    box-shadow: 0 0 24px -6px var(--glow);
    transform: translateY(-2px);
  }

  .user-btn-primary:active { transform: translateY(0); }

  /* RESPONSIVE */
  @media (max-width: 575.98px) {
    .user-modal {
      border-radius: var(--r-lg);
      margin: 0.5rem;
    }

    .user-modal-header { padding: 1rem 1.15rem; }
    .user-modal-body { padding: 1.15rem; }
    .user-modal-footer { padding: 0.9rem 1.15rem; }

    .user-modal-icon { width: 38px; height: 38px; font-size: 1.1rem; }
    .user-modal-title { font-size: 1rem; }
    .user-modal-subtitle { font-size: 0.75rem; }

    .user-btn {
      flex: 1;
      justify-content: center;
      padding: 0.65rem 1rem;
    }

    .user-input { font-size: 0.88rem; padding: 0.75rem 0.9rem 0.75rem 2.6rem; }
  }

  @media (prefers-reduced-motion: reduce) {
    .user-modal,
    .user-btn,
    .user-input,
    .user-modal-close,
    .user-btn-primary::before {
      transition: none !important;
      animation: none !important;
    }
  }
</style>
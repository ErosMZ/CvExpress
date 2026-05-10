@extends('layouts.app')

@section('title', 'Mi Panel — CVPortfolio')
@section('meta_description', 'Gestiona tu portfolio, edita tu perfil y sube tu CV desde tu panel personal.')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('css/panel.css') }}">
@endpush

@section('content')

<div class="panel">
    {{-- ── MOBILE BAR ── --}}
    <div class="panel__mobile-bar">
        <button class="panel__mobile-toggle" id="sidebarToggle" aria-label="Abrir menú lateral">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="3" y1="6" x2="21" y2="6"/><line x1="3" y1="12" x2="21" y2="12"/><line x1="3" y1="18" x2="21" y2="18"/></svg>
            Menú
        </button>
        <span style="font-size: var(--text-sm); font-weight: 600; color: var(--color-text-primary);" id="mobileSectionLabel">Inicio</span>
    </div>

    {{-- ── OVERLAY MOBILE ── --}}
    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <div class="panel__body">

        {{-- ── SIDEBAR ── --}}
        <aside class="sidebar" id="sidebar" aria-label="Navegación del panel">
            {{-- User info --}}
            <div class="sidebar__user">
                <div class="sidebar__avatar">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                </div>
                <div class="sidebar__user-name">{{ auth()->user()->name }}</div>
                <div class="sidebar__user-email">{{ auth()->user()->email }}</div>
                @if(auth()->user()->cv_path)
                    <div class="sidebar__cv-status sidebar__cv-status--ok">
                        <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><polyline points="20 6 9 17 4 12"/></svg>
                        CV subido
                    </div>
                @else
                    <div class="sidebar__cv-status sidebar__cv-status--missing">
                        <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                        Sin CV
                    </div>
                @endif
            </div>

            {{-- Nav links --}}
            <nav class="sidebar__nav">
                <div class="sidebar__section-label">Mi cuenta</div>

                <button class="sidebar__link sidebar__link--active" data-section="overview" onclick="switchSection('overview', 'Inicio')">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/></svg>
                    Inicio
                </button>

                <button class="sidebar__link" data-section="profile" onclick="switchSection('profile', 'Editar perfil')">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                    Editar perfil
                </button>

                <button class="sidebar__link" data-section="cvs" onclick="switchSection('cvs', 'Mis CVs')">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/></svg>
                    Mis CVs
                </button>

                <button class="sidebar__link" data-section="orders" onclick="switchSection('orders', 'Mis pedidos')">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 0 1-8 0"/></svg>
                    CVs Web
                </button>

                @if(auth()->user()->is_admin)
                    <div class="sidebar__section-label" style="margin-top: var(--space-4);">Administración</div>
                    <a href="{{ url('/admin/dashboard') }}" class="sidebar__link sidebar__link--admin">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                        Panel de administración
                    </a>
                @endif
            </nav>

            {{-- Bottom: logout --}}
            <div class="sidebar__bottom">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="sidebar__link sidebar__link--danger">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
                        Cerrar sesión
                    </button>
                </form>
            </div>
        </aside>

        {{-- ── MAIN ── --}}
        <main class="panel__main">

            {{-- Flash messages --}}
            @if(session('success'))
                <div class="alert alert--success" role="alert" style="border-radius: var(--radius-lg); margin-bottom: var(--space-6);">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg>
                    {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="alert alert--error" role="alert" style="border-radius: var(--radius-lg); margin-bottom: var(--space-6);">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                    {{ session('error') }}
                </div>
            @endif

            {{-- ════════════════════════════════
                 SECCIÓN: OVERVIEW / INICIO
            ════════════════════════════════ --}}
            <div class="panel__section panel__section--active" id="section-overview">

                <div class="welcome-banner">
                    <div class="welcome-banner__title">¡Hola, {{ explode(' ', auth()->user()->name)[0] }}! 👋</div>
                    <div class="welcome-banner__sub">Bienvenido a tu panel de control de CVPortfolio.</div>
                </div>

                <div class="panel__stats">
                    <div class="stat-card">
                        <div class="stat-card__label">CV subido</div>
                        <div class="stat-card__value">{{ auth()->user()->cv_path ? '1' : '0' }}</div>
                        <div class="stat-card__sub">{{ auth()->user()->cv_path ? 'Activo' : 'Sin CV todavía' }}</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-card__label">Portfolio</div>
                        <div class="stat-card__value" style="font-size: var(--text-lg); color: var(--color-text-muted);">Próximo</div>
                        <div class="stat-card__sub">En construcción</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-card__label">Pedidos</div>
                        <div class="stat-card__value">0</div>
                        <div class="stat-card__sub">Sin pedidos aún</div>
                    </div>
                </div>

                {{-- Quick actions --}}
                <div class="panel-card">
                    <div class="panel-card__title">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M13 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V9z"/><polyline points="13 2 13 9 20 9"/></svg>
                        Acciones rápidas
                    </div>
                    <div style="display: flex; flex-wrap: wrap; gap: var(--space-3);">
                        <button class="btn btn--primary" onclick="switchSection('profile', 'Editar perfil')">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                            Editar perfil y subir CV
                        </button>
                        <button class="btn btn--ghost" onclick="switchSection('cvs', 'Mis CVs')">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/></svg>
                            Ver mis CVs
                        </button>
                    </div>
                </div>

            </div>

            {{-- ════════════════════════════════
                 SECCIÓN: EDITAR PERFIL + CV
            ════════════════════════════════ --}}
            <div class="panel__section" id="section-profile">

                <div class="panel__header">
                    <h1 class="panel__title">Editar perfil</h1>
                    <p class="panel__subtitle">Actualiza tu información personal y sube tu CV en PDF.</p>
                </div>

                {{-- FORM PERFIL --}}
                <form method="POST" action="{{ route('dashboard.profile.update') }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="panel-card">
                        <div class="panel-card__title">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                            Información personal
                        </div>

                        <div class="form-grid">
                            <div class="form-group">
                                <label class="form-label" for="name">Nombre completo</label>
                                <input type="text" id="name" name="name" class="form-input @error('name') is-invalid @enderror"
                                    value="{{ old('name', auth()->user()->name) }}" required>
                                @error('name')<span class="form-error">{{ $message }}</span>@enderror
                            </div>

                            <div class="form-group">
                                <label class="form-label" for="email">Correo electrónico</label>
                                <input type="email" id="email" name="email" class="form-input @error('email') is-invalid @enderror"
                                    value="{{ old('email', auth()->user()->email) }}" required>
                                @error('email')<span class="form-error">{{ $message }}</span>@enderror
                            </div>

                            <div class="form-group">
                                <label class="form-label" for="job_title">Título profesional</label>
                                <input type="text" id="job_title" name="job_title" class="form-input"
                                    placeholder="ej. Desarrollador Full Stack"
                                    value="{{ old('job_title', auth()->user()->job_title) }}">
                            </div>

                            <div class="form-group">
                                <label class="form-label" for="phone">Teléfono</label>
                                <input type="tel" id="phone" name="phone" class="form-input"
                                    placeholder="+34 600 000 000"
                                    value="{{ old('phone', auth()->user()->phone) }}">
                            </div>

                            <div class="form-group">
                                <label class="form-label" for="location">Ubicación</label>
                                <input type="text" id="location" name="location" class="form-input"
                                    placeholder="ej. Madrid, España"
                                    value="{{ old('location', auth()->user()->location) }}">
                            </div>

                            <div class="form-group">
                                <label class="form-label" for="linkedin_url">LinkedIn</label>
                                <input type="url" id="linkedin_url" name="linkedin_url" class="form-input"
                                    placeholder="https://linkedin.com/in/tu-perfil"
                                    value="{{ old('linkedin_url', auth()->user()->linkedin_url) }}">
                            </div>

                            <div class="form-group">
                                <label class="form-label" for="website_url">Sitio web personal</label>
                                <input type="url" id="website_url" name="website_url" class="form-input"
                                    placeholder="https://tuportfolio.com"
                                    value="{{ old('website_url', auth()->user()->website_url) }}">
                            </div>

                            <div class="form-group form-grid--full">
                                <label class="form-label" for="bio">Sobre mí</label>
                                <textarea id="bio" name="bio" class="form-textarea" rows="4"
                                    placeholder="Una breve descripción profesional sobre ti...">{{ old('bio', auth()->user()->bio) }}</textarea>
                                <span class="form-hint">Aparecerá en la sección "Sobre mí" de tu portfolio.</span>
                            </div>
                        </div>
                    </div>

                    {{-- CV UPLOAD --}}
                    <div class="panel-card">
                        <div class="panel-card__title">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                            Currículum Vitae (PDF)
                        </div>

                        @if(auth()->user()->cv_path)
                            <div class="cv-current">
                                <div class="cv-current__icon">
                                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg>
                                </div>
                                <div class="cv-current__info">
                                    <div class="cv-current__name">{{ auth()->user()->cv_original_name ?? 'curriculum.pdf' }}</div>
                                    <div class="cv-current__date">
                                        Subido el {{ auth()->user()->cv_uploaded_at ? \Carbon\Carbon::parse(auth()->user()->cv_uploaded_at)->format('d/m/Y \a \l\a\s H:i') : '—' }}
                                    </div>
                                </div>
                                <a href="{{ route('dashboard.cv.download') }}" class="btn btn--ghost btn--sm">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                                    Descargar
                                </a>
                            </div>
                        @endif

                        <div class="cv-upload-zone" id="dropZone">
                            <input type="file" name="cv_file" id="cvFile" accept=".pdf" aria-label="Subir CV en PDF">
                            <div class="cv-upload-zone__icon">
                                <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
                            </div>
                            <div class="cv-upload-zone__title" id="dropTitle">
                                {{ auth()->user()->cv_path ? 'Arrastra un nuevo PDF para reemplazar' : 'Arrastra tu CV aquí o haz clic para seleccionar' }}
                            </div>
                            <div class="cv-upload-zone__hint">Solo archivos PDF · Máximo 10 MB</div>
                        </div>
                        @error('cv_file')<span class="form-error" style="display:block; margin-top: var(--space-2);">{{ $message }}</span>@enderror
                        <span class="form-hint" style="display:block; margin-top: var(--space-2);">
                            {{-- Ruta de almacenamiento: storage/app/private/cvs/{user_id}/ --}}
                            El CV se almacena de forma segura y privada. Solo tú puedes descargarlo.
                        </span>
                    </div>

                    {{-- PASSWORD CHANGE --}}
                    <div class="panel-card">
                        <div class="panel-card__title">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                            Cambiar contraseña
                        </div>
                        <div class="form-grid">
                            <div class="form-group">
                                <label class="form-label" for="current_password">Contraseña actual</label>
                                <input type="password" id="current_password" name="current_password" class="form-input" autocomplete="current-password">
                                @error('current_password')<span class="form-error">{{ $message }}</span>@enderror
                            </div>
                            <div></div>
                            <div class="form-group">
                                <label class="form-label" for="password">Nueva contraseña</label>
                                <input type="password" id="password" name="password" class="form-input" autocomplete="new-password">
                                @error('password')<span class="form-error">{{ $message }}</span>@enderror
                            </div>
                            <div class="form-group">
                                <label class="form-label" for="password_confirmation">Confirmar nueva contraseña</label>
                                <input type="password" id="password_confirmation" name="password_confirmation" class="form-input" autocomplete="new-password">
                            </div>
                        </div>
                        <span class="form-hint">Deja estos campos en blanco si no quieres cambiar la contraseña.</span>
                    </div>

                    <div class="form-actions">
                        <button type="button" class="btn btn--ghost" onclick="switchSection('overview', 'Inicio')">Cancelar</button>
                        <button type="submit" class="btn btn--primary">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13"/><polyline points="7 3 7 8 15 8"/></svg>
                            Guardar cambios
                        </button>
                    </div>
                </form>
            </div>

            {{-- ════════════════════════════════
                 SECCIÓN: MIS CVS
            ════════════════════════════════ --}}
            <div class="panel__section" id="section-cvs">

                <div class="panel__header">
                    <h1 class="panel__title">Mis CVs</h1>
                    <p class="panel__subtitle">Aquí aparecerán todos los CVs que hayas subido a la plataforma.</p>
                </div>

                @if(auth()->user()->cv_path)
                    <div class="panel-card">
                        <div class="panel-card__title">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/></svg>
                            CV activo
                        </div>
                        <div class="cv-overview">
                            <div class="cv-item">
                                <div class="cv-item__icon">
                                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>
                                </div>
                                <div class="cv-item__body">
                                    <div class="cv-item__name">{{ auth()->user()->cv_original_name ?? 'curriculum.pdf' }}</div>
                                    <div class="cv-item__meta">
                                        Subido el {{ auth()->user()->cv_uploaded_at ? \Carbon\Carbon::parse(auth()->user()->cv_uploaded_at)->format('d/m/Y \a \l\a\s H:i') : '—' }}
                                        &nbsp;·&nbsp;
                                        <span style="color: #059669; font-weight: 600;">Activo</span>
                                    </div>
                                </div>
                                <div class="cv-item__actions">
                                    <a href="{{ route('dashboard.cv.download') }}" class="btn btn--ghost btn--sm">
                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                                        Descargar
                                    </a>
                                    <button class="btn btn--outline btn--sm" onclick="switchSection('profile', 'Editar perfil')">
                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
                                        Reemplazar
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="panel-card">
                        <div class="orders-empty">
                            <div class="orders-empty__icon">
                                <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                            </div>
                            <div class="orders-empty__title">Aún no has subido ningún CV</div>
                            <p class="orders-empty__desc" style="margin-bottom: var(--space-6);">Sube tu currículum en PDF para que nuestra IA lo analice y genere tu portfolio web.</p>
                            <button class="btn btn--primary" onclick="switchSection('profile', 'Editar perfil')">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
                                Subir mi CV
                            </button>
                        </div>
                    </div>
                @endif

            </div>

            {{-- ════════════════════════════════
                 SECCIÓN: MIS PEDIDOS
            ════════════════════════════════ --}}
            <div class="panel__section" id="section-orders">

                <div class="panel__header">
                    <h1 class="panel__title">CVwebs</h1>
                    <p class="panel__subtitle">Historial de suscripciones y planes contratados.</p>
                </div>

            </div>

        </main>
    </div>
</div>

@push('scripts')
<script>
    // ── Section switcher ──
    function switchSection(sectionId, label) {
        // Hide all sections
        document.querySelectorAll('.panel__section').forEach(s => s.classList.remove('panel__section--active'));
        // Show target
        document.getElementById('section-' + sectionId).classList.add('panel__section--active');
        // Update sidebar active state
        document.querySelectorAll('.sidebar__link[data-section]').forEach(link => {
            link.classList.remove('sidebar__link--active');
            if (link.dataset.section === sectionId) link.classList.add('sidebar__link--active');
        });
        // Mobile label
        document.getElementById('mobileSectionLabel').textContent = label;
        // Close mobile sidebar
        closeSidebar();
        // Scroll to top
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }

    // ── Mobile sidebar ──
    const sidebarToggle = document.getElementById('sidebarToggle');
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('sidebarOverlay');

    sidebarToggle.addEventListener('click', () => {
        sidebar.classList.toggle('sidebar--open');
        overlay.classList.toggle('sidebar-overlay--active');
    });

    overlay.addEventListener('click', closeSidebar);

    function closeSidebar() {
        sidebar.classList.remove('sidebar--open');
        overlay.classList.remove('sidebar-overlay--active');
    }

    // ── Drag & drop CV upload ──
    const dropZone = document.getElementById('dropZone');
    const cvFileInput = document.getElementById('cvFile');
    const dropTitle = document.getElementById('dropTitle');

    if (dropZone) {
        dropZone.addEventListener('dragover', (e) => {
            e.preventDefault();
            dropZone.classList.add('cv-upload-zone--drag');
        });
        dropZone.addEventListener('dragleave', () => {
            dropZone.classList.remove('cv-upload-zone--drag');
        });
        dropZone.addEventListener('drop', (e) => {
            e.preventDefault();
            dropZone.classList.remove('cv-upload-zone--drag');
            const files = e.dataTransfer.files;
            if (files.length > 0 && files[0].type === 'application/pdf') {
                cvFileInput.files = files;
                dropTitle.textContent = '✓ ' + files[0].name;
            }
        });
        cvFileInput.addEventListener('change', () => {
            if (cvFileInput.files.length > 0) {
                dropTitle.textContent = '✓ ' + cvFileInput.files[0].name;
            }
        });
    }

    // ── Open section from URL hash ──
    const hash = window.location.hash.replace('#', '');
    const validSections = ['overview', 'profile', 'cvs', 'orders'];
    if (hash && validSections.includes(hash)) {
        const labels = { overview: 'Inicio', profile: 'Editar perfil', cvs: 'Mis CVs', orders: 'Mis pedidos' };
        switchSection(hash, labels[hash]);
    }

    // ── Open profile section if session says so (after upload error etc.) ──
    @if(session('open_section'))
        switchSection('{{ session('open_section') }}', '{{ session('open_section') === 'profile' ? 'Editar perfil' : 'Inicio' }}');
    @endif
</script>
@endpush

@endsection
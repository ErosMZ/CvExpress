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
                 SECCIÓN: MIS PLANES
            ════════════════════════════════ --}}
            <div class="panel__section" id="section-orders">

                <div class="panel__header">
                    <h1 class="panel__title">Mis Planes</h1>
                    <p class="panel__subtitle">Gestiona tu plan activo y la configuración de hosting.</p>
                </div>

                @php
                    $tierOrder  = ['basic' => 1, 'pro' => 2, 'super_pro' => 3];
                    $tierColors = [
                        'basic'     => ['bg' => '#dcfce7', 'fg' => '#16a34a', 'border' => '#86efac'],
                        'pro'       => ['bg' => '#dbeafe', 'fg' => '#1A56DB', 'border' => '#93c5fd'],
                        'super_pro' => ['bg' => '#ede9fe', 'fg' => '#7c3aed', 'border' => '#c4b5fd'],
                    ];
                    $tierLabels = ['basic' => 'Básico', 'pro' => 'Pro', 'super_pro' => 'Super Pro'];
                @endphp

                {{-- ── PLAN ACTIVO ── --}}
                @if($activePurchase)
                    @php
                        $ap   = $activePurchase;
                        $slug = $ap->plan->slug ?? 'basic';
                        $tc   = $tierColors[$slug] ?? $tierColors['basic'];
                    @endphp

                    <div class="panel-card" style="border-color: {{ $tc['border'] }};">
                        <div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:1rem;margin-bottom:1.25rem;">
                            <div style="display:flex;align-items:center;gap:.85rem;">
                                <div style="width:44px;height:44px;border-radius:12px;background:{{ $tc['bg'] }};color:{{ $tc['fg'] }};display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
                                </div>
                                <div>
                                    <div style="font-size:1rem;font-weight:700;color:var(--color-text-primary);">{{ $ap->plan->name }}</div>
                                    <div style="font-size:.8rem;color:var(--color-text-muted);">
                                        Activado el {{ $ap->purchased_at->format('d/m/Y') }}
                                        &nbsp;·&nbsp; {{ number_format($ap->amount_paid, 2) }}€ pagados
                                    </div>
                                </div>
                            </div>
                            <div style="display:flex;align-items:center;gap:.65rem;">
                                <span style="display:inline-flex;align-items:center;gap:.35rem;background:#dcfce7;color:#15803d;font-size:.75rem;font-weight:700;padding:.3rem .85rem;border-radius:999px;">
                                    <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><polyline points="20 6 9 17 4 12"/></svg>
                                    Activo
                                </span>
                                <form method="POST" action="{{ route('dashboard.purchase.cancel', $ap) }}"
                                      onsubmit="return confirm('¿Cancelar el plan activo?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" style="background:none;border:none;cursor:pointer;font-size:.75rem;color:var(--color-text-muted);padding:.3rem .6rem;border-radius:6px;transition:color .15s;" onmouseover="this.style.color='#ef4444'" onmouseout="this.style.color='var(--color-text-muted)'">
                                        Cancelar plan
                                    </button>
                                </form>
                            </div>
                        </div>

                        {{-- Features del plan activo --}}
                        @if($ap->plan->features)
                        <div style="display:flex;flex-wrap:wrap;gap:.5rem;">
                            @foreach($ap->plan->features as $feat)
                            <span style="display:inline-flex;align-items:center;gap:.35rem;font-size:.78rem;background:{{ $tc['bg'] }};color:{{ $tc['fg'] }};padding:.25rem .7rem;border-radius:999px;font-weight:500;">
                                <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><polyline points="20 6 9 17 4 12"/></svg>
                                {{ $feat }}
                            </span>
                            @endforeach
                        </div>
                        @endif
                    </div>

                    {{-- ── HOSTING SETUP ── --}}
                    @if($ap->hosting_type === 'none')
                    <div class="panel-card" style="border-color:#fbbf24;background:#fffbeb;">
                        <div class="panel-card__title" style="color:#d97706;">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                            Configura tu hosting
                        </div>
                        <p style="font-size:.875rem;color:var(--color-text-secondary);margin-bottom:1.25rem;">
                            Elige cómo quieres publicar tu portfolio web. Puedes cambiarlo después.
                        </p>
                        <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;">

                            {{-- Subdominio gratis --}}
                            <form method="POST" action="{{ route('dashboard.purchase.hosting', $ap) }}">
                                @csrf @method('PATCH')
                                <input type="hidden" name="hosting_type" value="subdomain">
                                <div style="border:1.5px solid #e5e7eb;border-radius:12px;padding:1.25rem;background:#fff;height:100%;display:flex;flex-direction:column;gap:.85rem;">
                                    <div style="display:flex;align-items:center;gap:.6rem;">
                                        <div style="width:38px;height:38px;background:#dcfce7;color:#16a34a;border-radius:10px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="2" y1="12" x2="22" y2="12"/><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/></svg>
                                        </div>
                                        <div>
                                            <div style="font-size:.875rem;font-weight:700;color:var(--color-text-primary);">Subdominio gratis</div>
                                            <div style="font-size:.75rem;color:#16a34a;font-weight:600;">Sin coste adicional</div>
                                        </div>
                                    </div>
                                    <div>
                                        <label style="font-size:.78rem;font-weight:600;color:var(--color-text-secondary);display:block;margin-bottom:.4rem;">Tu subdominio</label>
                                        <div style="display:flex;align-items:center;border:1.5px solid #e5e7eb;border-radius:8px;overflow:hidden;background:#f9fafb;">
                                            <input type="text" name="subdomain"
                                                   value="{{ Str::slug(auth()->user()->name) }}"
                                                   style="flex:1;padding:.5rem .75rem;border:none;background:transparent;font-size:.82rem;outline:none;font-family:monospace;"
                                                   placeholder="{{ Str::slug(auth()->user()->name) }}">
                                            <span style="padding:.5rem .75rem;font-size:.78rem;color:var(--color-text-muted);white-space:nowrap;border-left:1px solid #e5e7eb;background:#f3f4f6;">.expresscv.es</span>
                                        </div>
                                    </div>
                                    <button type="submit" class="btn btn--primary" style="width:100%;justify-content:center;">
                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg>
                                        Usar subdominio gratis
                                    </button>
                                </div>
                            </form>

                            {{-- Hosting de pago --}}
                            <form method="POST" action="{{ route('dashboard.purchase.hosting', $ap) }}">
                                @csrf @method('PATCH')
                                <input type="hidden" name="hosting_type" value="paid_hosting">
                                <div style="border:1.5px solid #e5e7eb;border-radius:12px;padding:1.25rem;background:#fff;height:100%;display:flex;flex-direction:column;gap:.85rem;">
                                    <div style="display:flex;align-items:center;gap:.6rem;">
                                        <div style="width:38px;height:38px;background:#dbeafe;color:#1A56DB;border-radius:10px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="3" width="20" height="14" rx="2"/><line x1="8" y1="21" x2="16" y2="21"/><line x1="12" y1="17" x2="12" y2="21"/></svg>
                                        </div>
                                        <div>
                                            <div style="font-size:.875rem;font-weight:700;color:var(--color-text-primary);">Hosting gestionado</div>
                                            <div style="font-size:.75rem;color:#1A56DB;font-weight:600;">2–3€/mes</div>
                                        </div>
                                    </div>
                                    <ul style="list-style:none;padding:0;margin:0;display:flex;flex-direction:column;gap:.4rem;flex:1;">
                                        @foreach(['Dominio personalizado','SSL incluido','Backups automáticos','CDN global'] as $f)
                                        <li style="font-size:.78rem;color:var(--color-text-secondary);display:flex;align-items:center;gap:.4rem;">
                                            <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="#1A56DB" stroke-width="3"><polyline points="20 6 9 17 4 12"/></svg>
                                            {{ $f }}
                                        </li>
                                        @endforeach
                                    </ul>
                                    <button type="submit" class="btn btn--ghost" style="width:100%;justify-content:center;">
                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg>
                                        Quiero hosting de pago
                                    </button>
                                </div>
                            </form>

                        </div>
                    </div>
                    @else
                    {{-- Hosting ya configurado --}}
                    <div class="panel-card">
                        <div class="panel-card__title">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="3" width="20" height="14" rx="2"/><line x1="8" y1="21" x2="16" y2="21"/><line x1="12" y1="17" x2="12" y2="21"/></svg>
                            Hosting configurado
                        </div>
                        <div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:1rem;">
                            <div>
                                @if($ap->hosting_type === 'subdomain')
                                    <div style="font-size:.95rem;font-weight:600;color:var(--color-text-primary);">
                                        <span style="color:#16a34a;">●</span>
                                        {{ $ap->subdomain }}.expresscv.es
                                    </div>
                                    <div style="font-size:.8rem;color:var(--color-text-muted);margin-top:.2rem;">Subdominio gratuito</div>
                                @else
                                    <div style="font-size:.95rem;font-weight:600;color:var(--color-text-primary);">
                                        <span style="color:#1A56DB;">●</span>
                                        Hosting de pago seleccionado
                                    </div>
                                    <div style="font-size:.8rem;color:var(--color-text-muted);margin-top:.2rem;">2–3€/mes · Nos pondremos en contacto pronto</div>
                                @endif
                            </div>
                            <form method="POST" action="{{ route('dashboard.purchase.hosting', $ap) }}">
                                @csrf @method('PATCH')
                                <input type="hidden" name="hosting_type" value="none">
                                <button type="submit" class="btn btn--ghost btn--sm">Cambiar hosting</button>
                            </form>
                        </div>
                    </div>
                    @endif

                    {{-- ── UPGRADE si no tiene Super Pro ── --}}
                    @if(($tierOrder[$slug] ?? 1) < 3)
                    <div class="panel-card">
                        <div class="panel-card__title">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="17 11 12 6 7 11"/><polyline points="17 18 12 13 7 18"/></svg>
                            Mejorar plan
                        </div>
                        <p style="font-size:.875rem;color:var(--color-text-secondary);margin-bottom:1.25rem;">Actualiza tu plan para desbloquear más funcionalidades.</p>
                        <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(220px,1fr));gap:1rem;">
                            @foreach($plans as $plan)
                                @if(($tierOrder[$plan->slug] ?? 1) > ($tierOrder[$slug] ?? 1))
                                @php $ptc = $tierColors[$plan->slug] ?? $tierColors['basic']; @endphp
                                <div style="border:1.5px solid {{ $ptc['border'] }};border-radius:12px;padding:1.25rem;display:flex;flex-direction:column;gap:.85rem;">
                                    <div style="display:flex;align-items:center;justify-content:space-between;">
                                        <span style="font-size:.875rem;font-weight:700;color:{{ $ptc['fg'] }};">{{ $plan->name }}</span>
                                        <span style="font-size:1.1rem;font-weight:800;color:var(--color-text-primary);">{{ number_format($plan->price, 2) }}€</span>
                                    </div>
                                    <ul style="list-style:none;padding:0;margin:0;display:flex;flex-direction:column;gap:.35rem;flex:1;">
                                        @foreach(array_slice($plan->features, 0, 4) as $feat)
                                        <li style="font-size:.78rem;color:var(--color-text-secondary);display:flex;align-items:center;gap:.4rem;">
                                            <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="{{ $ptc['fg'] }}" stroke-width="3"><polyline points="20 6 9 17 4 12"/></svg>
                                            {{ $feat }}
                                        </li>
                                        @endforeach
                                        @if(count($plan->features) > 4)
                                        <li style="font-size:.75rem;color:var(--color-text-muted);">+{{ count($plan->features) - 4 }} más...</li>
                                        @endif
                                    </ul>
                                    <form method="POST" action="{{ route('dashboard.plan.activate', $plan) }}">
                                        @csrf
                                        <button type="submit" style="width:100%;padding:.55rem;border:none;border-radius:8px;background:{{ $ptc['bg'] }};color:{{ $ptc['fg'] }};font-size:.82rem;font-weight:700;cursor:pointer;transition:opacity .15s;" onmouseover="this.style.opacity='.8'" onmouseout="this.style.opacity='1'">
                                            Actualizar a {{ $plan->name }}
                                        </button>
                                    </form>
                                </div>
                                @endif
                            @endforeach
                        </div>
                    </div>
                    @endif

                @else
                    {{-- ── SIN PLAN ── --}}
                    <div class="panel-card">
                        <div class="orders-empty">
                            <div class="orders-empty__icon">
                                <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 0 1-8 0"/></svg>
                            </div>
                            <div class="orders-empty__title">Aún no tienes ningún plan</div>
                            <p class="orders-empty__desc">Elige el plan que mejor se adapte a ti. Pago único, sin renovaciones.</p>
                        </div>
                    </div>

                    {{-- Planes disponibles --}}
                    <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(260px,1fr));gap:1.25rem;">
                        @foreach($plans as $plan)
                        @php $ptc = $tierColors[$plan->slug] ?? $tierColors['basic']; @endphp
                        <div style="border:1.5px solid {{ $ptc['border'] }};border-radius:16px;padding:1.5rem;background:#fff;display:flex;flex-direction:column;gap:1rem;position:relative;{{ $plan->badge_label ? 'box-shadow:0 4px 20px rgba(0,0,0,.08);' : '' }}">
                            @if($plan->badge_label)
                                <div style="position:absolute;top:-11px;left:50%;transform:translateX(-50%);background:{{ $ptc['fg'] }};color:#fff;font-size:.7rem;font-weight:700;letter-spacing:.06em;padding:.28rem .85rem;border-radius:999px;white-space:nowrap;">{{ $plan->badge_label }}</div>
                            @endif
                            <div style="display:flex;align-items:center;gap:.75rem;">
                                <div style="width:42px;height:42px;border-radius:12px;background:{{ $ptc['bg'] }};color:{{ $ptc['fg'] }};display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                    @if($plan->slug === 'basic')
                                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                                    @elseif($plan->slug === 'pro')
                                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
                                    @else
                                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                                    @endif
                                </div>
                                <div>
                                    <div style="font-size:.95rem;font-weight:700;color:var(--color-text-primary);">{{ $plan->name }}</div>
                                    <div style="font-size:1.35rem;font-weight:800;color:{{ $ptc['fg'] }};">{{ number_format($plan->price, 2) }}€ <span style="font-size:.75rem;font-weight:500;color:var(--color-text-muted);">pago único</span></div>
                                </div>
                            </div>

                            <ul style="list-style:none;padding:0;margin:0;display:flex;flex-direction:column;gap:.55rem;flex:1;">
                                @foreach($plan->features as $feat)
                                <li style="font-size:.82rem;color:var(--color-text-secondary);display:flex;align-items:flex-start;gap:.5rem;line-height:1.4;">
                                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="{{ $ptc['fg'] }}" stroke-width="2.5" style="margin-top:2px;flex-shrink:0;"><polyline points="20 6 9 17 4 12"/></svg>
                                    {{ $feat }}
                                </li>
                                @endforeach
                            </ul>

                            <form method="POST" action="{{ route('dashboard.plan.activate', $plan) }}">
                                @csrf
                                <button type="submit" style="width:100%;padding:.65rem;border:2px solid {{ $ptc['fg'] }};border-radius:10px;background:{{ $ptc['bg'] }};color:{{ $ptc['fg'] }};font-size:.875rem;font-weight:700;cursor:pointer;transition:background .15s,transform .1s;" onmouseover="this.style.transform='translateY(-1px)'" onmouseout="this.style.transform=''">
                                    Activar {{ $plan->name }} — {{ number_format($plan->price, 2) }}€
                                </button>
                            </form>
                        </div>
                        @endforeach
                    </div>

                    {{-- Hosting info --}}
                    <div class="panel-card" style="border-color:#fbbf24;background:#fffbeb;">
                        <div style="display:flex;align-items:center;gap:1rem;flex-wrap:wrap;">
                            <div style="width:44px;height:44px;background:#fef3c7;color:#d97706;border-radius:12px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="3" width="20" height="14" rx="2"/><line x1="8" y1="21" x2="16" y2="21"/><line x1="12" y1="17" x2="12" y2="21"/></svg>
                            </div>
                            <div style="flex:1;">
                                <div style="font-size:.9rem;font-weight:700;color:#92400e;margin-bottom:.2rem;">Hosting opcional — 2–3€/mes</div>
                                <div style="font-size:.8rem;color:#78350f;line-height:1.5;">Incluye hosting, SSL, backups y dominio propio. También puedes usar tu subdominio <strong>gratis</strong>. Sin obligación.</div>
                            </div>
                        </div>
                    </div>

                @endif

                {{-- Historial de compras --}}
                @if($allPurchases->where('status', 'cancelled')->isNotEmpty())
                <div class="panel-card">
                    <div class="panel-card__title">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                        Historial de planes
                    </div>
                    <div style="display:flex;flex-direction:column;gap:.5rem;">
                        @foreach($allPurchases->where('status','cancelled') as $p)
                        <div style="display:flex;align-items:center;justify-content:space-between;padding:.65rem .85rem;background:var(--color-bg-secondary);border-radius:8px;font-size:.82rem;">
                            <span style="font-weight:600;color:var(--color-text-primary);">{{ $p->plan->name ?? 'Plan' }}</span>
                            <span style="color:var(--color-text-muted);">{{ $p->purchased_at->format('d/m/Y') }}</span>
                            <span style="background:#fee2e2;color:#ef4444;padding:.2rem .65rem;border-radius:999px;font-size:.72rem;font-weight:700;">Cancelado</span>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif

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

    // ── Open section from session (after form submit redirects) ──
    @if(session('open_section'))
    @php
        $sectionLabels = ['overview' => 'Inicio', 'profile' => 'Editar perfil', 'cvs' => 'Mis CVs', 'orders' => 'Mis pedidos'];
        $openSection   = session('open_section');
    @endphp
        switchSection('{{ $openSection }}', '{{ $sectionLabels[$openSection] ?? 'Inicio' }}');
    @endif
</script>
@endpush

@endsection
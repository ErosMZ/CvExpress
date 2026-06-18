@extends('layouts.app')

@section('title', 'Mi Panel — CvXpress')
@section('meta_description', 'Gestiona tu portfolio, edita tu perfil y sube tu CV desde tu panel personal.')

@push('styles')
    <style>
        /* ── TABS SCROLL HINT ── */
        .cv-tabs-wrap {
            position: relative;
        }
        /* Degradado derecho: indica que hay más tabs al deslizar */
        .cv-tabs-wrap::after {
            content: '';
            position: absolute;
            right: 0;
            top: 0;
            bottom: 0;
            width: 36px;
            background: linear-gradient(to right, transparent, var(--color-bg-secondary, #f3f4f6));
            pointer-events: none;
            border-radius: 0 10px 10px 0;
        }
        /* Scrollbar fino y visible */
        .cv-tabs-scroll {
            scrollbar-width: thin;
            scrollbar-color: var(--gray-300, #d1d5db) transparent;
        }
        .cv-tabs-scroll::-webkit-scrollbar { height: 4px; }
        .cv-tabs-scroll::-webkit-scrollbar-track { background: transparent; }
        .cv-tabs-scroll::-webkit-scrollbar-thumb {
            background: var(--gray-300, #d1d5db);
            border-radius: 99px;
        }
        /* Ocultar degradado cuando ya se llegó al final (JS lo gestiona) */
        .cv-tabs-wrap.scrolled-end::after { display: none; }

        /* ── RESPONSIVE PANEL (mobile) ── */
        @media (max-width: 900px) {
            html, body { overflow-x: hidden !important; max-width: 100vw; }

            /* Todos los contenedores del panel no desbordan */
            .panel, .panel__body, .panel__main,
            .panel__section, .panel__section--active {
                overflow-x: hidden !important;
                max-width: 100% !important;
                min-width: 0 !important;
                width: 100% !important;
                box-sizing: border-box !important;
            }

            /* Grid editor: columna única + grid items contraíbles */
            .cv-editor-cols {
                display: grid !important;
                grid-template-columns: 1fr !important;
                width: 100% !important;
                max-width: 100% !important;
                min-width: 0 !important;
                overflow: hidden !important;
            }
            /* ↓ CLAVE: grid items por defecto tienen min-width:auto
               lo que les impide contraerse. Forzar 0 los contiene. */
            .cv-editor-cols > * {
                min-width: 0 !important;
                max-width: 100% !important;
                overflow-x: hidden !important;
            }

            /* Hub view: columna 300px → colapsa en móvil */
            .cv-hub-grid {
                grid-template-columns: 1fr !important;
            }

            /* Preview debajo del formulario */
            .cv-editor-preview {
                position: static !important;
                max-width: 100% !important;
                min-width: 0 !important;
            }
            #cv-preview-wrap { height: 260px !important; }

            /* Cards e inputs no desbordan */
            .panel-card {
                max-width: 100% !important;
                min-width: 0 !important;
                box-sizing: border-box !important;
                overflow-x: hidden !important;
                padding: var(--space-6) !important;
            }
            .form-group, .form-input, .form-textarea, .form-select {
                max-width: 100% !important;
                min-width: 0 !important;
                width: 100% !important;
                box-sizing: border-box !important;
            }
        }
    </style>
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
            {{-- Botón cerrar (solo móvil) --}}
            <button class="sidebar__close" id="sidebarClose" aria-label="Cerrar menú">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
            </button>

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

                <button class="sidebar__link" data-section="profile" onclick="switchSection('profile', 'Mi Perfil')">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                    Mi Perfil
                </button>

                <button class="sidebar__link" data-section="templates" onclick="switchSection('templates', 'Mi CV Web')">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><rect x="3" y="3" width="18" height="18" rx="2"/><path d="M3 9h18M9 21V9"/></svg>
                    Mi CV Web
                </button>

                <button class="sidebar__link" data-section="orders" onclick="switchSection('orders', 'Mi Plan')">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 0 1-8 0"/></svg>
                    Mi Plan
                </button>

                @if(auth()->user()->is_admin)
                    <div class="sidebar__section-label" style="margin-top: var(--space-4);">Administración</div>
                    <a href="{{ url('/admin') }}" class="sidebar__link sidebar__link--admin">
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
                    <div class="welcome-banner__sub">Bienvenido a tu panel de control de CvXpress.</div>
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
                        <button class="btn btn--primary" onclick="switchSection('profile', 'Mi Perfil')">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                            Ver mi perfil
                        </button>
                        <button class="btn btn--ghost" onclick="switchSection('templates', 'Mi CV Web')">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="2"/><path d="M3 9h18M9 21V9"/></svg>
                            Mi CV Web
                        </button>
                    </div>
                </div>

            </div>

            {{-- ════════════════════════════════
                 SECCIÓN: MI PERFIL
            ════════════════════════════════ --}}
            <div class="panel__section" id="section-profile">

                <div class="panel__header" style="position:relative;padding-right:3.5rem;">
                    <div>
                        <h1 class="panel__title">Mi Perfil</h1>
                        <p class="panel__subtitle">Tu información personal y currículum.</p>
                    </div>
                    <button type="button" id="btn-edit-profile" onclick="toggleProfileEdit()"
                            title="Editar perfil"
                            style="position:absolute;top:0;right:0;width:40px;height:40px;border-radius:50%;border:1.5px solid var(--color-border);background:var(--color-surface);cursor:pointer;display:flex;align-items:center;justify-content:center;color:var(--color-text-secondary);transition:background .2s,border-color .2s,color .2s;flex-shrink:0;">
                        <svg id="icon-pencil" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                        <svg id="icon-close" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" style="display:none;"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                    </button>
                </div>

                {{-- ── MODO VISTA ── --}}
                <div id="profile-view">
                    @php $u = auth()->user(); @endphp
                    <div class="panel-card">
                        <div class="panel-card__title">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                            Información personal
                        </div>
                        <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(200px,1fr));gap:1.5rem 2rem;">
                            @foreach([
                                'Nombre completo'    => $u->name,
                                'Correo electrónico' => $u->email,
                                'Título profesional' => $u->job_title,
                                'Teléfono'           => $u->phone,
                                'Ubicación'          => $u->location,
                                'LinkedIn'           => $u->linkedin_url,
                                'Sitio web'          => $u->website_url,
                            ] as $label => $value)
                            <div>
                                <div style="font-size:.7rem;font-weight:700;text-transform:uppercase;letter-spacing:.07em;color:var(--color-text-muted);margin-bottom:.3rem;">{{ $label }}</div>
                                @if($value)
                                    @if(str_starts_with($value, 'http'))
                                        <a href="{{ $value }}" target="_blank" rel="noopener" style="font-size:.9rem;color:var(--color-primary);text-decoration:none;word-break:break-all;">{{ $value }}</a>
                                    @else
                                        <div style="font-size:.9rem;color:var(--color-text-primary);">{{ $value }}</div>
                                    @endif
                                @else
                                    <div style="font-size:.88rem;color:var(--color-text-muted);font-style:italic;">Sin rellenar</div>
                                @endif
                            </div>
                            @endforeach
                        </div>
                        @if($u->bio)
                        <div style="margin-top:1.5rem;padding-top:1.25rem;border-top:1px solid var(--color-border);">
                            <div style="font-size:.7rem;font-weight:700;text-transform:uppercase;letter-spacing:.07em;color:var(--color-text-muted);margin-bottom:.5rem;">Sobre mí</div>
                            <p style="font-size:.9rem;color:var(--color-text-primary);line-height:1.6;margin:0;">{{ $u->bio }}</p>
                        </div>
                        @endif
                    </div>

                    {{-- CV actual --}}
                    @if($u->cv_path)
                    <div class="panel-card">
                        <div class="panel-card__title">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                            Currículum Vitae
                        </div>
                        <div class="cv-current">
                            <div class="cv-current__icon">
                                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg>
                            </div>
                            <div class="cv-current__info">
                                <div class="cv-current__name">{{ $u->cv_original_name ?? 'curriculum.pdf' }}</div>
                                <div class="cv-current__date">
                                    Subido el {{ $u->cv_uploaded_at ? \Carbon\Carbon::parse($u->cv_uploaded_at)->format('d/m/Y \a \l\a\s H:i') : '—' }}
                                    &nbsp;·&nbsp;<span style="color:#059669;font-weight:600;">Activo</span>
                                </div>
                            </div>
                            <a href="{{ route('dashboard.cv.download') }}" class="btn btn--ghost btn--sm">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                                Descargar
                            </a>
                        </div>
                    </div>
                    @else
                    <div class="panel-card" style="text-align:center;padding:2rem 1.5rem;">
                        <div style="width:48px;height:48px;border-radius:50%;background:var(--color-bg-secondary);display:flex;align-items:center;justify-content:center;margin:0 auto .875rem;">
                            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="var(--color-text-muted)" stroke-width="1.5"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                        </div>
                        <div style="font-weight:600;color:var(--color-text-primary);margin-bottom:.35rem;">Aún no has subido tu CV</div>
                        <p style="font-size:.85rem;color:var(--color-text-secondary);margin:0;">Pulsa el lápiz para editar tu perfil y subir tu currículum en PDF.</p>
                    </div>
                    @endif
                </div>

                {{-- ── MODO EDICIÓN (oculto por defecto) ── --}}
                <div id="profile-edit" style="display:none;">
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
                            @error('cv_file')<span class="form-error" style="display:block;margin-top:var(--space-2);">{{ $message }}</span>@enderror
                            <span class="form-hint" style="display:block;margin-top:var(--space-2);">El CV se almacena de forma segura y privada. Solo tú puedes descargarlo.</span>
                        </div>

                        {{-- CAMBIAR CONTRASEÑA --}}
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
                            <button type="button" class="btn btn--ghost" onclick="toggleProfileEdit()">Cancelar</button>
                            <button type="submit" class="btn btn--primary">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13"/><polyline points="7 3 7 8 15 8"/></svg>
                                Guardar cambios
                            </button>
                        </div>
                    </form>
                </div>

            </div>

            {{-- ════════════════════════════════
                 SECCIÓN: MI CV WEB — EDITOR
            ════════════════════════════════ --}}
            <div class="panel__section" id="section-templates">

                @if(! $activePurchase)
                    <div class="panel__header">
                        <h1 class="panel__title">Mi CV Web</h1>
                        <p class="panel__subtitle">Elige y personaliza tu portfolio web.</p>
                    </div>
                    <div style="text-align:center;padding:3rem 1rem;">
                        <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="var(--color-text-secondary)" stroke-width="1.2" style="margin-bottom:1rem;opacity:.4"><rect x="3" y="3" width="18" height="18" rx="2"/><path d="M3 9h18M9 21V9"/></svg>
                        <p style="font-weight:600;color:var(--color-text-primary);margin-bottom:.5rem;">Necesitas un plan activo</p>
                        <p style="font-size:.875rem;color:var(--color-text-secondary);margin-bottom:1.5rem;">Adquiere un plan para poder elegir tu plantilla de portfolio.</p>
                        <button onclick="switchSection('orders','Mi Plan')" class="btn btn--primary btn--sm">Ver planes</button>
                    </div>

                @else
                    @php
                        $selected  = $activePurchase->selectedTemplate;
                        $tiers     = \App\Models\Template::PLAN_TIERS;
                        $u         = auth()->user();
                        $showHub   = $selected && session('show_hub');
                    @endphp

                    {{-- ─── FORMULARIOS OCULTOS DE SELECCIÓN ─── --}}
                    @foreach($availableTemplates as $tpl)
                    <form id="sel-tpl-{{ $tpl->id }}" method="POST" action="{{ route('dashboard.template.select', [$activePurchase->id, $tpl->id]) }}" style="display:none;">@csrf</form>
                    @endforeach

                    {{-- ─── VISTA: SELECTOR DE PLANTILLA ─── --}}
                    <div id="cv-selector-view" style="{{ $selected ? 'display:none' : '' }}">
                        <div class="panel__header" style="display:flex;align-items:flex-start;justify-content:space-between;flex-wrap:wrap;gap:1rem;">
                            <div>
                                <h1 class="panel__title">Mi CV Web</h1>
                                <p class="panel__subtitle">Elige tu plantilla para empezar.</p>
                            </div>
                            @if($selected)
                            <button onclick="showCvView('hub')" class="btn btn--ghost btn--sm">
                                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="15 18 9 12 15 6"/></svg>
                                Volver
                            </button>
                            @endif
                        </div>

                        @if($availableTemplates->isEmpty())
                            <div style="text-align:center;padding:2.5rem 1rem;">
                                <p style="color:var(--color-text-secondary);font-size:.9rem;">No hay plantillas disponibles para tu plan todavía.</p>
                            </div>
                        @else
                            <p style="font-size:.82rem;color:var(--color-text-secondary);margin-bottom:1.25rem;">
                                Tu plan <strong>{{ $activePurchase->plan->name }}</strong> incluye {{ $availableTemplates->count() }} plantilla(s). <strong>Haz doble clic</strong> para seleccionar una.
                            </p>
                            <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(210px,1fr));gap:1.25rem;">
                                @foreach($availableTemplates as $tpl)
                                @php $isActive = $selected && $selected->id === $tpl->id; @endphp
                                <div ondblclick="{{ $isActive ? "showCvView('editor')" : "document.getElementById('sel-tpl-{$tpl->id}').submit()" }}"
                                     title="{{ $isActive ? 'Ya está en uso — doble clic para editar' : 'Doble clic para usar esta plantilla' }}"
                                     style="border:2px solid {{ $isActive ? '#16a34a' : 'var(--color-border)' }};border-radius:14px;overflow:hidden;background:var(--color-surface);cursor:pointer;transition:border-color .15s,box-shadow .15s,transform .1s;user-select:none;{{ $isActive ? 'box-shadow:0 0 0 3px #bbf7d0;' : '' }}"
                                     onmouseover="if(!{{ $isActive ? 'true' : 'false' }})this.style.borderColor='var(--color-primary)'"
                                     onmouseout="this.style.borderColor='{{ $isActive ? '#16a34a' : 'var(--color-border)' }}'">
                                    <div style="position:relative;aspect-ratio:4/3;background:#f1f5f9;overflow:hidden;">
                                        @if($tpl->preview_image)
                                            <img src="{{ asset('storage/'.$tpl->preview_image) }}" alt="{{ $tpl->name }}" style="width:100%;height:100%;object-fit:cover;pointer-events:none;">
                                        @else
                                            <div style="width:100%;height:100%;display:flex;align-items:center;justify-content:center;">
                                                <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="#cbd5e1" stroke-width="1.2"><rect x="3" y="3" width="18" height="18" rx="2"/><path d="M3 9h18M9 21V9"/></svg>
                                            </div>
                                        @endif
                                        @if($isActive)
                                            <div style="position:absolute;top:8px;right:8px;background:#16a34a;color:#fff;border-radius:99px;padding:3px 10px;font-size:.68rem;font-weight:700;">✓ En uso</div>
                                        @endif
                                        @php $tierData = $tiers[$tpl->plan_tier] ?? null; @endphp
                                        @if($tierData)
                                            <div style="position:absolute;top:8px;left:8px;background:{{ $tierData['bg'] }};color:{{ $tierData['color'] }};border-radius:99px;padding:2px 8px;font-size:.65rem;font-weight:700;">{{ $tierData['label'] }}</div>
                                        @endif
                                    </div>
                                    <div style="padding:.875rem;">
                                        <div style="font-weight:700;font-size:.9rem;color:var(--color-text-primary);margin-bottom:.15rem;">{{ $tpl->name }}</div>
                                        @if($tpl->category)<div style="font-size:.72rem;color:var(--color-text-secondary);margin-bottom:.5rem;">{{ $tpl->category->name }}</div>@else<div style="margin-bottom:.5rem;"></div>@endif
                                        @if($isActive)
                                            <div style="width:100%;padding:.4rem;text-align:center;border-radius:7px;background:#dcfce7;color:#16a34a;font-size:.75rem;font-weight:700;">✓ En uso</div>
                                        @else
                                            <div style="width:100%;padding:.4rem;text-align:center;border-radius:7px;background:var(--color-bg-secondary);color:var(--color-text-muted);font-size:.72rem;font-weight:600;">Doble clic para usar</div>
                                        @endif
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        @endif
                    </div>

                    {{-- ─── VISTA: HUB — elegir método ─── --}}
                    <div id="cv-hub-view" style="{{ $showHub ? '' : 'display:none' }}">

                        {{-- Cabecera prominente --}}
                        <div style="margin-bottom:1.75rem;">
                            <div style="display:inline-flex;align-items:center;gap:.4rem;background:#eff6ff;border:1px solid #bfdbfe;border-radius:99px;padding:.3rem .875rem;font-size:.72rem;font-weight:700;color:#1d4ed8;margin-bottom:.75rem;letter-spacing:.03em;">
                                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><rect x="3" y="3" width="18" height="18" rx="2"/><path d="M3 9h18M9 21V9"/></svg>
                                Mi CV Web
                            </div>
                            <h1 style="font-size:1.6rem;font-weight:800;color:var(--color-text-primary);margin:0 0 .4rem;line-height:1.2;">¿Cómo quieres crear tu CV?</h1>
                            <p style="font-size:.9rem;color:var(--color-text-secondary);margin:0;">Elige un método para rellenar tu plantilla <strong style="color:var(--color-text-primary);">{{ $selected?->name }}</strong>.</p>
                        </div>

                        <div class="cv-hub-grid">

                            {{-- IZQUIERDA: dos CTAs grandes --}}
                            <div style="display:flex;flex-direction:column;gap:.875rem;">

                                {{-- CTA: IA — opción destacada --}}
                                <div onclick="openAiModal()"
                                     style="position:relative;border:2px solid #3b82f6;border-radius:16px;padding:1.75rem;cursor:pointer;background:linear-gradient(135deg,#eff6ff 0%,#dbeafe 100%);transition:box-shadow .15s,transform .12s;display:flex;gap:1.25rem;align-items:center;"
                                     onmouseover="this.style.boxShadow='0 8px 28px rgba(59,130,246,.25)';this.style.transform='translateY(-3px)'"
                                     onmouseout="this.style.boxShadow='none';this.style.transform=''">
                                    <div style="position:absolute;top:-1px;right:14px;background:#3b82f6;color:#fff;border-radius:0 0 8px 8px;padding:.2rem .75rem;font-size:.65rem;font-weight:800;letter-spacing:.05em;text-transform:uppercase;">Recomendado</div>
                                    <div style="width:56px;height:56px;border-radius:14px;background:#3b82f6;display:flex;align-items:center;justify-content:center;flex-shrink:0;box-shadow:0 4px 12px rgba(59,130,246,.4);">
                                        <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="1.8"><path d="M12 2L2 7l10 5 10-5-10-5z"/><path d="M2 17l10 5 10-5"/><path d="M2 12l10 5 10-5"/></svg>
                                    </div>
                                    <div style="flex:1;">
                                        <div style="font-size:1.15rem;font-weight:800;color:#1e3a8a;margin-bottom:.3rem;">Generar con IA</div>
                                        <div style="font-size:.84rem;color:#1e40af;line-height:1.55;opacity:.85;">Sube tu CV en PDF y la IA rellena todo en segundos automáticamente.</div>
                                        <div style="margin-top:.75rem;display:inline-flex;align-items:center;gap:.35rem;font-size:.8rem;font-weight:700;color:#2563eb;background:#fff;padding:.35rem .875rem;border-radius:99px;box-shadow:0 1px 4px rgba(0,0,0,.08);">
                                            Empezar ahora
                                            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="9 18 15 12 9 6"/></svg>
                                        </div>
                                    </div>
                                </div>

                                {{-- Separador --}}
                                <div style="display:flex;align-items:center;gap:.75rem;">
                                    <div style="flex:1;height:1px;background:var(--color-border);"></div>
                                    <span style="font-size:.72rem;font-weight:600;color:var(--color-text-muted);white-space:nowrap;">o si prefieres</span>
                                    <div style="flex:1;height:1px;background:var(--color-border);"></div>
                                </div>

                                {{-- CTA: Manual --}}
                                <div onclick="showCvView('editor')"
                                     style="border:2px solid var(--color-border);border-radius:16px;padding:1.75rem;cursor:pointer;background:var(--color-surface);transition:box-shadow .15s,transform .12s,border-color .15s;display:flex;gap:1.25rem;align-items:center;"
                                     onmouseover="this.style.boxShadow='0 6px 20px rgba(0,0,0,.08)';this.style.borderColor='var(--color-text-secondary)';this.style.transform='translateY(-2px)'"
                                     onmouseout="this.style.boxShadow='none';this.style.borderColor='var(--color-border)';this.style.transform=''">
                                    <div style="width:56px;height:56px;border-radius:14px;background:var(--color-bg-secondary);border:1.5px solid var(--color-border);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                        <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="var(--color-text-secondary)" stroke-width="1.8"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                                    </div>
                                    <div style="flex:1;">
                                        <div style="font-size:1.05rem;font-weight:700;color:var(--color-text-primary);margin-bottom:.3rem;">Rellenar a mano</div>
                                        <div style="font-size:.84rem;color:var(--color-text-secondary);line-height:1.55;">Escribe cada campo directamente y personaliza tu CV a tu ritmo.</div>
                                        <div style="margin-top:.75rem;display:inline-flex;align-items:center;gap:.35rem;font-size:.78rem;font-weight:600;color:var(--color-text-secondary);">
                                            Ir al editor
                                            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="9 18 15 12 9 6"/></svg>
                                        </div>
                                    </div>
                                </div>

                            </div>

                            {{-- DERECHA: plantilla activa + cambiar --}}
                            <div style="display:flex;flex-direction:column;gap:.875rem;">

                                @if($selected)
                                <div style="border:2px solid #16a34a;border-radius:14px;overflow:hidden;background:var(--color-surface);box-shadow:0 0 0 3px #bbf7d0;">
                                    <div style="position:relative;aspect-ratio:4/3;background:#f1f5f9;overflow:hidden;">
                                        @if($selected->preview_image)
                                            <img src="{{ asset('storage/'.$selected->preview_image) }}" alt="{{ $selected->name }}" style="width:100%;height:100%;object-fit:cover;">
                                        @else
                                            <div style="width:100%;height:100%;display:flex;align-items:center;justify-content:center;"><svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="#cbd5e1" stroke-width="1.2"><rect x="3" y="3" width="18" height="18" rx="2"/><path d="M3 9h18M9 21V9"/></svg></div>
                                        @endif
                                        <div style="position:absolute;top:8px;right:8px;background:#16a34a;color:#fff;border-radius:99px;padding:3px 10px;font-size:.68rem;font-weight:700;">✓ En uso</div>
                                        @php $tierData = $tiers[$selected->plan_tier] ?? null; @endphp
                                        @if($tierData)
                                            <div style="position:absolute;top:8px;left:8px;background:{{ $tierData['bg'] }};color:{{ $tierData['color'] }};border-radius:99px;padding:2px 8px;font-size:.65rem;font-weight:700;">{{ $tierData['label'] }}</div>
                                        @endif
                                    </div>
                                    <div style="padding:.75rem .875rem;">
                                        <div style="font-weight:700;font-size:.9rem;color:var(--color-text-primary);">{{ $selected->name }}</div>
                                        @if($selected->category)<div style="font-size:.72rem;color:var(--color-text-secondary);margin-top:.1rem;">{{ $selected->category->name }}</div>@endif
                                    </div>
                                </div>
                                @endif

                                {{-- Otras plantillas disponibles --}}
                                @php $otrasPlantillas = $availableTemplates->filter(fn($t) => !$selected || $t->id !== $selected->id); @endphp
                                @if($otrasPlantillas->isNotEmpty())
                                <div style="font-size:.67rem;font-weight:700;text-transform:uppercase;letter-spacing:.07em;color:var(--color-text-muted);margin-top:.25rem;">Otras disponibles</div>
                                <div style="display:flex;flex-direction:column;gap:.5rem;">
                                    @foreach($otrasPlantillas as $tpl)
                                    <div ondblclick="document.getElementById('sel-tpl-{{ $tpl->id }}').submit()"
                                         title="Doble clic para usar esta plantilla"
                                         style="display:flex;align-items:center;gap:.75rem;border:1.5px solid var(--color-border);border-radius:10px;overflow:hidden;background:var(--color-surface);cursor:pointer;padding-right:.75rem;transition:border-color .15s;"
                                         onmouseover="this.style.borderColor='var(--color-primary)'"
                                         onmouseout="this.style.borderColor='var(--color-border)'">
                                        <div style="width:64px;height:48px;background:#f1f5f9;flex-shrink:0;overflow:hidden;">
                                            @if($tpl->preview_image)
                                                <img src="{{ asset('storage/'.$tpl->preview_image) }}" alt="{{ $tpl->name }}" style="width:100%;height:100%;object-fit:cover;pointer-events:none;">
                                            @else
                                                <div style="width:100%;height:100%;display:flex;align-items:center;justify-content:center;"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#cbd5e1" stroke-width="1.5"><rect x="3" y="3" width="18" height="18" rx="2"/></svg></div>
                                            @endif
                                        </div>
                                        <div style="flex:1;min-width:0;">
                                            <div style="font-size:.82rem;font-weight:600;color:var(--color-text-primary);white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">{{ $tpl->name }}</div>
                                            <div style="font-size:.7rem;color:var(--color-text-muted);">Doble clic para usar</div>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                                @endif

                                <button onclick="showCvView('selector')" style="width:100%;padding:.5rem;border-radius:8px;border:1.5px solid var(--color-border);background:none;font-size:.78rem;font-weight:600;color:var(--color-text-secondary);cursor:pointer;transition:border-color .15s,color .15s;"
                                        onmouseover="this.style.borderColor='var(--color-primary)';this.style.color='var(--color-primary)'"
                                        onmouseout="this.style.borderColor='var(--color-border)';this.style.color='var(--color-text-secondary)'">
                                    Ver todas las plantillas →
                                </button>

                            </div>
                        </div>
                    </div>

                    {{-- ─── VISTA: EDITOR DE CV ─── --}}
                    <div id="cv-editor-view" style="{{ ($selected && !$showHub) ? '' : 'display:none' }}">

                        {{-- Header editor --}}
                        <div class="panel__header" style="display:flex;align-items:flex-start;justify-content:space-between;flex-wrap:wrap;gap:1rem;">
                            <div>
                                <h1 class="panel__title">Mi CV Web</h1>
                                <p class="panel__subtitle">Edita tu contenido y ve los cambios en tiempo real.</p>
                            </div>
                        </div>

                        {{-- Acciones rápidas --}}
                        <div style="display:flex;gap:.625rem;margin-bottom:1.5rem;">
                            <button type="button" onclick="openAiModal()" class="btn btn--primary btn--sm" style="flex:1;justify-content:center;">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
                                Analizar CV con IA
                            </button>
                            <button type="button" onclick="showCvView('selector')" class="btn btn--ghost btn--sm">
                                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="2"/><path d="M3 9h18M9 21V9"/></svg>
                                Cambiar plantilla
                            </button>
                        </div>

                        {{-- ─── EDITOR DOS COLUMNAS ─── --}}
                        <div class="cv-editor-cols">

                            {{-- COLUMNA IZQUIERDA ─── --}}
                            <div style="display:flex;flex-direction:column;gap:.875rem;">

                                {{-- Tabs de sección --}}
                                <div class="cv-tabs-wrap">
                                <div class="cv-tabs-scroll" style="display:flex;flex-wrap:nowrap;gap:.3rem;background:var(--color-bg-secondary);border:1px solid var(--color-border);padding:.4rem;border-radius:10px;overflow-x:auto;-webkit-overflow-scrolling:touch;">
                                    @foreach([
                                        'presentacion' => 'Presentación',
                                        'contacto'     => 'Contacto',
                                        'experiencia'  => 'Experiencia',
                                        'formacion'    => 'Formación',
                                        'habilidades'  => 'Habilidades',
                                        'proyectos'    => 'Proyectos',
                                        'idiomas'      => 'Idiomas',
                                    ] as $key => $label)
                                    <button type="button" onclick="showCvSection('{{ $key }}')"
                                            id="cvtab-{{ $key }}"
                                            style="font-size:.75rem;padding:.3rem .65rem;border-radius:7px;border:none;cursor:pointer;font-weight:600;white-space:nowrap;transition:all .15s;{{ $loop->first ? 'background:var(--color-brand);color:#fff;' : 'background:transparent;color:var(--color-text-secondary);' }}">
                                        {{ $label }}
                                    </button>
                                    @endforeach
                                </div>{{-- /cv-tabs-scroll --}}
                                </div>{{-- /cv-tabs-wrap --}}

                                {{-- PANEL: Presentación --}}
                                <div id="cvpanel-presentacion" class="panel-card" style="margin:0;">
                                    <div style="font-size:.72rem;font-weight:700;color:var(--color-text-muted);text-transform:uppercase;letter-spacing:.07em;margin-bottom:1rem;">Presentación</div>

                                    {{-- Foto de perfil --}}
                                    <div class="form-group" style="margin-bottom:1.25rem;">
                                        <label class="form-label">Foto de perfil</label>
                                        @if($photoOrientation)
                                        <div style="font-size:.72rem;color:#1e40af;background:#eff6ff;border:1px solid #bfdbfe;border-radius:6px;padding:.35rem .6rem;margin-bottom:.6rem;display:flex;align-items:center;gap:.4rem;">
                                            @if($photoOrientation === 'vertical')
                                            <svg width="9" height="12" viewBox="0 0 9 12" fill="none" stroke="currentColor" stroke-width="2"><rect x="0.5" y="0.5" width="8" height="11" rx="1"/></svg>
                                            Plantilla requiere foto <strong>vertical</strong> (3:4)
                                            @else
                                            <svg width="12" height="9" viewBox="0 0 12 9" fill="none" stroke="currentColor" stroke-width="2"><rect x="0.5" y="0.5" width="11" height="8" rx="1"/></svg>
                                            Plantilla requiere foto <strong>horizontal</strong> (4:3)
                                            @endif
                                        </div>
                                        @endif
                                        <div style="display:flex;align-items:center;gap:.875rem;">
                                            <div id="editor-photo-preview" style="width:56px;height:56px;border-radius:10px;overflow:hidden;background:var(--color-border);flex-shrink:0;display:flex;align-items:center;justify-content:center;">
                                                @if($photoUrl)
                                                <img src="{{ $photoUrl }}" style="width:100%;height:100%;object-fit:cover;" id="editor-photo-img">
                                                @else
                                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="var(--color-text-muted)" stroke-width="1.5" id="editor-photo-placeholder"><circle cx="12" cy="8" r="4"/><path d="M4 20c0-4 3.6-7 8-7s8 3 8 7"/></svg>
                                                @endif
                                            </div>
                                            <div>
                                                <button type="button" onclick="document.getElementById('editor-photo-file').click()"
                                                        class="btn btn--ghost btn--sm" style="margin-bottom:.3rem;">
                                                    {{ $photoUrl ? 'Cambiar foto' : 'Subir foto' }}
                                                </button>
                                                <input type="file" id="editor-photo-file" accept="image/jpeg,image/png,image/webp" style="display:none;" onchange="uploadProfilePhoto(this)">
                                                <div style="font-size:.7rem;color:var(--color-text-muted);">JPG, PNG o WebP · Máx. 3 MB</div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group" style="margin-bottom:.875rem;">
                                        <label class="form-label" for="cv_name">Nombre completo</label>
                                        <input type="text" id="cv_name" class="form-input" value="{{ $cvData['cv_name'] ?? $u->name }}"
                                               oninput="updatePreview('name',this.value)">
                                    </div>
                                    <div class="form-group" style="margin-bottom:.875rem;">
                                        <label class="form-label" for="cv_job_title">Título profesional</label>
                                        <input type="text" id="cv_job_title" class="form-input"
                                               placeholder="ej. Desarrollador Full Stack" value="{{ $cvData['cv_job_title'] ?? $u->job_title ?? '' }}"
                                               oninput="updatePreview('job_title',this.value)">
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label" for="cv_bio">Resumen / Sobre mí</label>
                                        <textarea id="cv_bio" class="form-textarea" rows="5"
                                                  placeholder="Descripción profesional..."
                                                  oninput="updatePreview('bio',this.value)">{{ $cvData['cv_bio'] ?? $u->bio ?? '' }}</textarea>
                                    </div>
                                </div>

                                {{-- PANEL: Contacto --}}
                                <div id="cvpanel-contacto" class="panel-card" style="margin:0;display:none;">
                                    <div style="font-size:.72rem;font-weight:700;color:var(--color-text-muted);text-transform:uppercase;letter-spacing:.07em;margin-bottom:1rem;">Contacto</div>
                                    @foreach([
                                        ['cv_email',    'email',    'Correo electrónico', 'email', $cvData['cv_email']    ?? $u->email ?? '',        'tu@email.com'],
                                        ['cv_phone',    'phone',    'Teléfono',           'tel',   $cvData['cv_phone']    ?? $u->phone ?? '',         '+34 600 000 000'],
                                        ['cv_location', 'location', 'Ubicación',          'text',  $cvData['cv_location'] ?? $u->location ?? '',      'ej. Madrid, España'],
                                        ['cv_linkedin', 'linkedin', 'LinkedIn',           'url',   $cvData['cv_linkedin'] ?? $u->linkedin_url ?? '',  'https://linkedin.com/in/...'],
                                        ['cv_website',  'website',  'Sitio web',          'url',   $cvData['cv_website']  ?? $u->website_url ?? '',   'https://tuportfolio.com'],
                                    ] as [$id, $field, $label, $type, $val, $ph])
                                    <div class="form-group" style="margin-bottom:.75rem;">
                                        <label class="form-label" for="{{ $id }}">{{ $label }}</label>
                                        <input type="{{ $type }}" id="{{ $id }}" class="form-input"
                                               placeholder="{{ $ph }}" value="{{ $val }}"
                                               oninput="updatePreview('{{ $field }}',this.value)">
                                    </div>
                                    @endforeach
                                </div>

                                {{-- PANEL: Experiencia --}}
                                <div id="cvpanel-experiencia" class="panel-card" style="margin:0;display:none;">
                                    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1rem;">
                                        <div style="font-size:.72rem;font-weight:700;color:var(--color-text-muted);text-transform:uppercase;letter-spacing:.07em;">Experiencia laboral</div>
                                        <button type="button" onclick="addEntry('experiencia')" style="font-size:.75rem;font-weight:700;color:var(--color-primary);background:none;border:none;cursor:pointer;display:flex;align-items:center;gap:.3rem;">
                                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg> Añadir
                                        </button>
                                    </div>
                                    <div id="entries-experiencia">
                                        @php $expEntries = !empty($cvData['experiencia']) ? $cvData['experiencia'] : [[]]; @endphp
                                        @foreach($expEntries as $exp)
                                        <div class="cv-entry" style="border:1px solid var(--color-border);border-radius:10px;padding:.875rem;margin-bottom:.75rem;position:relative;">
                                            <button type="button" onclick="removeEntry(this)" title="Eliminar" style="position:absolute;top:.5rem;right:.5rem;background:none;border:none;cursor:pointer;color:var(--color-text-muted);">
                                                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                                            </button>
                                            <div class="form-grid" style="gap:.625rem;">
                                                <div class="form-group" style="margin:0;"><label class="form-label" style="font-size:.72rem;">Empresa</label><input type="text" data-field="empresa" class="form-input" placeholder="Empresa S.L." value="{{ $exp['empresa'] ?? '' }}"></div>
                                                <div class="form-group" style="margin:0;"><label class="form-label" style="font-size:.72rem;">Cargo</label><input type="text" data-field="cargo" class="form-input" placeholder="Desarrollador Web" value="{{ $exp['cargo'] ?? '' }}"></div>
                                                <div class="form-group" style="margin:0;"><label class="form-label" style="font-size:.72rem;">Período</label><input type="text" data-field="periodo" class="form-input" placeholder="2022 – presente" value="{{ $exp['periodo'] ?? '' }}"></div>
                                                <div class="form-group form-grid--full" style="margin:0;"><label class="form-label" style="font-size:.72rem;">Descripción</label><textarea data-field="descripcion" class="form-textarea" rows="3" placeholder="Responsabilidades y logros...">{{ $exp['descripcion'] ?? '' }}</textarea></div>
                                            </div>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>

                                {{-- PANEL: Formación --}}
                                <div id="cvpanel-formacion" class="panel-card" style="margin:0;display:none;">
                                    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1rem;">
                                        <div style="font-size:.72rem;font-weight:700;color:var(--color-text-muted);text-transform:uppercase;letter-spacing:.07em;">Formación académica</div>
                                        <button type="button" onclick="addEntry('formacion')" style="font-size:.75rem;font-weight:700;color:var(--color-primary);background:none;border:none;cursor:pointer;display:flex;align-items:center;gap:.3rem;">
                                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg> Añadir
                                        </button>
                                    </div>
                                    <div id="entries-formacion">
                                        @php $formEntries = !empty($cvData['formacion']) ? $cvData['formacion'] : [[]]; @endphp
                                        @foreach($formEntries as $form)
                                        <div class="cv-entry" style="border:1px solid var(--color-border);border-radius:10px;padding:.875rem;margin-bottom:.75rem;position:relative;">
                                            <button type="button" onclick="removeEntry(this)" title="Eliminar" style="position:absolute;top:.5rem;right:.5rem;background:none;border:none;cursor:pointer;color:var(--color-text-muted);">
                                                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                                            </button>
                                            <div class="form-grid" style="gap:.625rem;">
                                                <div class="form-group" style="margin:0;"><label class="form-label" style="font-size:.72rem;">Institución</label><input type="text" data-field="institucion" class="form-input" placeholder="Universidad / Centro" value="{{ $form['institucion'] ?? '' }}"></div>
                                                <div class="form-group" style="margin:0;"><label class="form-label" style="font-size:.72rem;">Título / Grado</label><input type="text" data-field="titulo" class="form-input" placeholder="Grado en Informática" value="{{ $form['titulo'] ?? '' }}"></div>
                                                <div class="form-group" style="margin:0;"><label class="form-label" style="font-size:.72rem;">Período</label><input type="text" data-field="periodo" class="form-input" placeholder="2018 – 2022" value="{{ $form['periodo'] ?? '' }}"></div>
                                                <div class="form-group form-grid--full" style="margin:0;"><label class="form-label" style="font-size:.72rem;">Descripción (opcional)</label><textarea data-field="descripcion" class="form-textarea" rows="2" placeholder="Especialización, proyectos...">{{ $form['descripcion'] ?? '' }}</textarea></div>
                                            </div>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>

                                {{-- PANEL: Habilidades --}}
                                <div id="cvpanel-habilidades" class="panel-card" style="margin:0;display:none;">
                                    <div style="font-size:.72rem;font-weight:700;color:var(--color-text-muted);text-transform:uppercase;letter-spacing:.07em;margin-bottom:1rem;">Habilidades</div>
                                    @php
                                        $habilidades = $cvData['habilidades'] ?? [];
                                        if (empty($habilidades) && !empty($cvData['cv_skills'])) {
                                            $habilidades = array_values(array_filter(array_map('trim', explode(',', $cvData['cv_skills']))));
                                        }
                                        if (!empty($cvData['cv_soft_skills'])) {
                                            $softSkills = array_values(array_filter(array_map('trim', explode(',', $cvData['cv_soft_skills']))));
                                            $habilidades = array_merge($habilidades, $softSkills);
                                        }
                                    @endphp
                                    <div id="skills-chips" style="display:flex;flex-wrap:wrap;gap:.4rem;margin-bottom:.875rem;min-height:2rem;">
                                        @foreach($habilidades as $skill)
                                            @if(trim($skill))
                                            <span class="skill-chip-item" data-skill="{{ trim($skill) }}" style="display:inline-flex;align-items:center;gap:.25rem;background:#eff6ff;border:1px solid #bfdbfe;color:#1e40af;font-size:.78rem;font-weight:600;padding:.25rem .6rem .25rem .75rem;border-radius:99px;line-height:1.3;">
                                                {{ trim($skill) }}<button type="button" onclick="removeSkill(this)" title="Eliminar" style="background:none;border:none;cursor:pointer;color:#93c5fd;padding:0 0 0 2px;line-height:1;font-size:1.15rem;display:flex;align-items:center;">&times;</button>
                                            </span>
                                            @endif
                                        @endforeach
                                    </div>
                                    <div style="display:flex;gap:.5rem;">
                                        <input type="text" id="skill-input" class="form-input" placeholder="ej. JavaScript, trabajo en equipo..." style="flex:1;" onkeydown="if(event.key==='Enter'){event.preventDefault();addSkill();}">
                                        <button type="button" onclick="addSkill()" class="btn btn--ghost btn--sm">
                                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                                            Añadir
                                        </button>
                                    </div>
                                    <span class="form-hint">Escribe una habilidad y pulsa Enter o el botón para añadirla.</span>
                                </div>

                                {{-- PANEL: Proyectos --}}
                                <div id="cvpanel-proyectos" class="panel-card" style="margin:0;display:none;">
                                    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1rem;">
                                        <div style="font-size:.72rem;font-weight:700;color:var(--color-text-muted);text-transform:uppercase;letter-spacing:.07em;">Proyectos</div>
                                        <button type="button" onclick="addEntry('proyectos')" style="font-size:.75rem;font-weight:700;color:var(--color-primary);background:none;border:none;cursor:pointer;display:flex;align-items:center;gap:.3rem;">
                                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg> Añadir
                                        </button>
                                    </div>
                                    <div id="entries-proyectos">
                                        @php $proyEntries = !empty($cvData['proyectos']) ? $cvData['proyectos'] : [[]]; @endphp
                                        @foreach($proyEntries as $proy)
                                        <div class="cv-entry" style="border:1px solid var(--color-border);border-radius:10px;padding:.875rem;margin-bottom:.75rem;position:relative;">
                                            <button type="button" onclick="removeEntry(this)" title="Eliminar" style="position:absolute;top:.5rem;right:.5rem;background:none;border:none;cursor:pointer;color:var(--color-text-muted);">
                                                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                                            </button>
                                            <div class="form-grid" style="gap:.625rem;">
                                                <div class="form-group form-grid--full" style="margin:0;"><label class="form-label" style="font-size:.72rem;">Nombre del proyecto</label><input type="text" data-field="nombre" class="form-input" placeholder="Mi Proyecto" value="{{ $proy['nombre'] ?? '' }}"></div>
                                                <div class="form-group form-grid--full" style="margin:0;"><label class="form-label" style="font-size:.72rem;">Descripción</label><textarea data-field="descripcion" class="form-textarea" rows="2" placeholder="Descripción breve del proyecto...">{{ $proy['descripcion'] ?? '' }}</textarea></div>
                                                <div class="form-group" style="margin:0;"><label class="form-label" style="font-size:.72rem;">URL / Link</label><input type="url" data-field="url" class="form-input" placeholder="https://github.com/..." value="{{ $proy['url'] ?? '' }}"></div>
                                                <div class="form-group" style="margin:0;"><label class="form-label" style="font-size:.72rem;">Tecnologías</label><input type="text" data-field="tecnologias" class="form-input" placeholder="React, Node.js, PHP..." value="{{ $proy['tecnologias'] ?? '' }}"><span class="form-hint">Separa con comas</span></div>
                                            </div>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>

                                {{-- PANEL: Idiomas --}}
                                <div id="cvpanel-idiomas" class="panel-card" style="margin:0;display:none;">
                                    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1rem;">
                                        <div style="font-size:.72rem;font-weight:700;color:var(--color-text-muted);text-transform:uppercase;letter-spacing:.07em;">Idiomas</div>
                                        <button type="button" onclick="addEntry('idiomas')" style="font-size:.75rem;font-weight:700;color:var(--color-primary);background:none;border:none;cursor:pointer;display:flex;align-items:center;gap:.3rem;">
                                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg> Añadir
                                        </button>
                                    </div>
                                    <div id="entries-idiomas">
                                        @php
                                            $idiomaEntries = !empty($cvData['idiomas']) ? $cvData['idiomas'] : [[]];
                                            $nivelesOpts = [
                                                '── Nivel general ──' => ['Nativo','C2 – Maestría','C1 – Avanzado','B2 – Intermedio alto','B1 – Intermedio','A2 – Básico','A1 – Elemental'],
                                                '── Certificados Inglés ──' => ['Cambridge A2 Key (KET)','Cambridge B1 Preliminary (PET)','Cambridge B2 First (FCE)','Cambridge C1 Advanced (CAE)','Cambridge C2 Proficiency (CPE)','IELTS 4.0–5.0 (B1)','IELTS 5.5–6.0 (B2)','IELTS 6.5–7.0 (C1)','IELTS 8.0+ (C2)','TOEFL 42–71 (B1)','TOEFL 72–94 (B2)','TOEFL 95–110 (C1)','TOEFL 111+ (C2)','TOEIC 550–780','TOEIC 785–900','TOEIC 905+'],
                                                '── Certificados Español ──' => ['DELE A1','DELE A2','DELE B1','DELE B2','DELE C1','DELE C2','SIELE'],
                                                '── Certificados Francés ──' => ['DELF A1','DELF A2','DELF B1','DELF B2','DALF C1','DALF C2','TCF B1','TCF B2+'],
                                                '── Certificados Alemán ──' => ['Goethe A1','Goethe A2','Goethe B1','Goethe B2','Goethe C1','Goethe C2'],
                                                '── Certificados Chino ──' => ['HSK 1–2 (A1-A2)','HSK 3–4 (B1-B2)','HSK 5–6 (C1-C2)'],
                                                '── Otros ──' => ['EOI A2','EOI B1','EOI B2','EOI C1','EOI C2'],
                                            ];
                                        @endphp
                                        @foreach($idiomaEntries as $idioma)
                                        <div class="cv-entry" style="border:1px solid var(--color-border);border-radius:10px;padding:.875rem;margin-bottom:.75rem;position:relative;">
                                            <button type="button" onclick="removeEntry(this)" title="Eliminar" style="position:absolute;top:.5rem;right:.5rem;background:none;border:none;cursor:pointer;color:var(--color-text-muted);">
                                                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                                            </button>
                                            <div class="form-grid" style="gap:.625rem;">
                                                <div class="form-group" style="margin:0;">
                                                    <label class="form-label" style="font-size:.72rem;">Idioma</label>
                                                    <input type="text" data-field="idioma" class="form-input" placeholder="ej. Inglés, Francés..." value="{{ $idioma['idioma'] ?? '' }}">
                                                </div>
                                                <div class="form-group" style="margin:0;">
                                                    <label class="form-label" style="font-size:.72rem;">Nivel / Certificado</label>
                                                    <select data-field="nivel" class="form-input">
                                                        <option value="">Seleccionar nivel o certificado...</option>
                                                        @foreach($nivelesOpts as $grupo => $opciones)
                                                        <optgroup label="{{ $grupo }}">
                                                            @foreach($opciones as $op)
                                                            <option {{ ($idioma['nivel'] ?? '') === $op ? 'selected' : '' }}>{{ $op }}</option>
                                                            @endforeach
                                                        </optgroup>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>

                                {{-- Guardar / Limpiar --}}
                                <div style="display:flex;align-items:center;justify-content:space-between;padding:.25rem 0;gap:.5rem;">
                                    <button type="button" onclick="confirmClearCvData()"
                                            class="btn btn--ghost btn--sm"
                                            style="color:#ef4444;border-color:#fca5a5;"
                                            title="Borrar todos los datos del CV">
                                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/><path d="M10 11v6M14 11v6"/><path d="M9 6V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2"/></svg>
                                        Limpiar datos
                                    </button>
                                    <button type="button" id="btn-save-cv" onclick="saveCvData(event)" class="btn btn--primary btn--sm">
                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13"/><polyline points="7 3 7 8 15 8"/></svg>
                                        Guardar
                                    </button>
                                </div>

                            </div>{{-- / columna izquierda --}}

                            {{-- COLUMNA DERECHA: PREVIEW ─── --}}
                            <div class="cv-editor-preview" style="position:sticky;top:1.5rem;">
                                <div style="border-radius:14px;overflow:hidden;box-shadow:0 4px 24px rgba(0,0,0,.1);border:1.5px solid var(--color-border);">
                                    {{-- Fake browser chrome --}}
                                    <div style="background:#f1f5f9;padding:.45rem .875rem;display:flex;align-items:center;gap:.5rem;border-bottom:1px solid var(--color-border);">
                                        <div style="display:flex;gap:.3rem;">
                                            <div style="width:10px;height:10px;border-radius:50%;background:#ef4444;"></div>
                                            <div style="width:10px;height:10px;border-radius:50%;background:#f59e0b;"></div>
                                            <div style="width:10px;height:10px;border-radius:50%;background:#22c55e;"></div>
                                        </div>
                                        <div style="flex:1;background:#fff;border-radius:5px;padding:.22rem .65rem;font-size:.7rem;color:var(--color-text-muted);font-family:monospace;overflow:hidden;white-space:nowrap;text-overflow:ellipsis;">
                                            {{ $selected ? $selected->slug . '.cvexpress.es' : 'preview' }}
                                        </div>
                                        @if($selected && $selected->preview_html_url)
                                        <button onclick="openPreviewTab('{{ $selected->preview_html_url }}')" title="Abrir en nueva pestaña" style="background:none;border:none;cursor:pointer;color:var(--color-text-muted);display:flex;flex-shrink:0;padding:0;">
                                            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/><polyline points="15 3 21 3 21 9"/><line x1="10" y1="14" x2="21" y2="3"/></svg>
                                        </button>
                                        @endif
                                    </div>
                                    {{-- iframe --}}
                                    <div id="cv-preview-wrap" style="position:relative;height:580px;overflow:hidden;background:#f8fafc;">
                                        @if($selected && $selected->preview_html_url)
                                            <iframe id="cv-preview-iframe"
                                                    src="{{ $selected->preview_html_url }}"
                                                    style="position:absolute;top:0;left:0;width:1280px;height:900px;transform-origin:top left;border:none;"
                                                    scrolling="auto"
                                                    sandbox="allow-same-origin allow-scripts"
                                                    onload="onPreviewLoaded(this)">
                                            </iframe>
                                        @else
                                            <div style="display:flex;align-items:center;justify-content:center;height:100%;flex-direction:column;gap:.75rem;color:var(--color-text-muted);">
                                                <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.2" opacity=".3"><rect x="3" y="3" width="18" height="18" rx="2"/><path d="M3 9h18M9 21V9"/></svg>
                                                <span style="font-size:.85rem;">Sin plantilla seleccionada</span>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                {{-- Barra inferior: plantilla activa --}}
                                @if($selected)
                                <div style="display:flex;align-items:center;justify-content:space-between;margin-top:.75rem;padding:.55rem .875rem;background:var(--color-bg-secondary);border-radius:10px;font-size:.8rem;border:1px solid var(--color-border);">
                                    <span style="color:var(--color-text-secondary);">Plantilla: <strong style="color:var(--color-text-primary);">{{ $selected->name }}</strong></span>
                                    <button onclick="showCvView('selector')" style="background:none;border:none;cursor:pointer;font-size:.78rem;font-weight:700;color:var(--color-primary);">Cambiar →</button>
                                </div>
                                @endif
                            </div>{{-- / columna derecha --}}

                        </div>{{-- / grid dos columnas --}}
                    </div>{{-- / cv-editor-view --}}

                @endif
            </div>

            {{-- ════════════════════════════════
                 SECCIÓN: MIS PLANES
            ════════════════════════════════ --}}
            <div class="panel__section" id="section-orders">

                <div class="panel__header">
                    <h1 class="panel__title">Mi Plan</h1>
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

{{-- ══════════════════════════════════════════════
     MODAL: Analizar CV con IA
     ══════════════════════════════════════════════ --}}
<div id="ai-modal-overlay"
     style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.55);z-index:9999;align-items:center;justify-content:center;padding:1rem;">
    <div style="background:#fff;border-radius:20px;padding:2rem;max-width:520px;width:100%;box-shadow:0 24px 60px rgba(0,0,0,.2);position:relative;">
        {{-- Cerrar --}}
        <button type="button" onclick="closeAiModal()"
                style="position:absolute;top:1rem;right:1rem;background:none;border:none;cursor:pointer;color:var(--color-text-muted);padding:.25rem;">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
        </button>

        {{-- ESTADO: selección --}}
        <div id="ai-modal-select">
            <div style="display:flex;align-items:center;gap:.875rem;margin-bottom:1.5rem;">
                <div style="width:48px;height:48px;border-radius:14px;background:linear-gradient(135deg,#6366f1,#8b5cf6);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2"><path d="M12 2a10 10 0 1 0 10 10"/><path d="M12 6v6l4 2"/><circle cx="18" cy="6" r="3" fill="#fff" stroke="none"/></svg>
                </div>
                <div>
                    <div style="font-size:1.1rem;font-weight:800;color:var(--color-text-primary);">Analizar CV con Inteligencia Artificial</div>
                    <div style="font-size:.82rem;color:var(--color-text-muted);">La IA leerá tu CV y rellenará automáticamente todos los campos</div>
                </div>
            </div>

            @if($u->cv_path)
            {{-- Opción A: usar CV existente --}}
            <div id="ai-option-existing"
                 onclick="selectAiOption('existing')"
                 style="border:2px solid var(--color-border);border-radius:14px;padding:1rem 1.25rem;cursor:pointer;margin-bottom:.75rem;transition:all .15s;display:flex;align-items:center;gap:1rem;">
                <div style="width:40px;height:40px;border-radius:10px;background:#eff6ff;color:#1A56DB;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                </div>
                <div style="flex:1;">
                    <div style="font-size:.9rem;font-weight:700;color:var(--color-text-primary);">Usar CV guardado</div>
                    <div style="font-size:.78rem;color:var(--color-text-muted);">{{ $u->cv_original_name ?? 'curriculum.pdf' }}</div>
                </div>
                <div id="ai-check-existing" style="display:none;color:var(--color-primary);">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><polyline points="20 6 9 17 4 12"/></svg>
                </div>
            </div>
            @endif

            {{-- Opción B: subir nuevo --}}
            <div id="ai-option-new"
                 onclick="selectAiOption('new')"
                 style="border:2px solid var(--color-border);border-radius:14px;padding:1rem 1.25rem;cursor:pointer;margin-bottom:1.25rem;transition:all .15s;display:flex;align-items:center;gap:1rem;">
                <div style="width:40px;height:40px;border-radius:10px;background:#f0fdf4;color:#16a34a;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
                </div>
                <div style="flex:1;">
                    <div style="font-size:.9rem;font-weight:700;color:var(--color-text-primary);">Subir nuevo CV</div>
                    <div style="font-size:.78rem;color:var(--color-text-muted);">PDF hasta 10 MB</div>
                </div>
                <div id="ai-check-new" style="display:none;color:var(--color-primary);">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><polyline points="20 6 9 17 4 12"/></svg>
                </div>
            </div>

            {{-- Zona drop de archivo (solo visible si source=new) --}}
            <div id="ai-drop-zone" style="display:none;border:2px dashed var(--color-border);border-radius:12px;padding:1.5rem;text-align:center;margin-bottom:1.25rem;cursor:pointer;transition:border-color .2s;"
                 ondragover="event.preventDefault();this.style.borderColor='var(--color-primary)'"
                 ondragleave="this.style.borderColor='var(--color-border)'"
                 ondrop="onAiDrop(event)">
                <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="var(--color-text-muted)" stroke-width="1.5" style="margin:0 auto .5rem;display:block;"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
                <div style="font-size:.875rem;font-weight:600;color:var(--color-text-secondary);" id="ai-drop-label">Arrastra tu CV aquí o <span style="color:var(--color-primary);text-decoration:underline;cursor:pointer;" onclick="document.getElementById('ai-file-input').click()">selecciona archivo</span></div>
                <input type="file" id="ai-file-input" accept=".pdf" style="display:none;" onchange="onAiFileSelected(this)">
            </div>

            {{-- Foto de perfil opcional --}}
            <div style="border:1px solid var(--color-border);border-radius:14px;padding:1rem 1.25rem;margin-bottom:1.25rem;background:var(--color-bg-secondary);">
                <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:.75rem;">
                    <div style="font-size:.85rem;font-weight:700;color:var(--color-text-primary);">
                        📷 Foto de perfil
                        <span style="font-size:.72rem;font-weight:400;color:var(--color-text-muted);margin-left:.4rem;">(opcional, puedes añadirla después)</span>
                    </div>
                    @if($photoUrl)
                    <span style="font-size:.72rem;color:#16a34a;font-weight:600;">✓ Ya tienes foto</span>
                    @endif
                </div>

                @if($photoOrientation)
                <div style="display:flex;align-items:center;gap:.5rem;background:#eff6ff;border:1px solid #bfdbfe;border-radius:8px;padding:.5rem .75rem;margin-bottom:.75rem;font-size:.78rem;color:#1e40af;">
                    @if($photoOrientation === 'vertical')
                    <svg width="12" height="16" viewBox="0 0 12 16" fill="none" stroke="currentColor" stroke-width="2"><rect x="1" y="1" width="10" height="14" rx="1.5"/></svg>
                    Esta plantilla recomienda foto <strong>vertical</strong> (formato retrato, ej. 3:4)
                    @else
                    <svg width="16" height="12" viewBox="0 0 16 12" fill="none" stroke="currentColor" stroke-width="2"><rect x="1" y="1" width="14" height="10" rx="1.5"/></svg>
                    Esta plantilla recomienda foto <strong>horizontal</strong> (formato paisaje, ej. 4:3)
                    @endif
                </div>
                @endif

                <div style="display:flex;align-items:center;gap:.875rem;">
                    <div id="ai-photo-preview" style="width:52px;height:52px;border-radius:10px;overflow:hidden;background:var(--color-border);flex-shrink:0;display:flex;align-items:center;justify-content:center;">
                        @if($photoUrl)
                        <img src="{{ $photoUrl }}" style="width:100%;height:100%;object-fit:cover;" id="ai-photo-img">
                        @else
                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="var(--color-text-muted)" stroke-width="1.5" id="ai-photo-placeholder"><circle cx="12" cy="8" r="4"/><path d="M4 20c0-4 3.6-7 8-7s8 3 8 7"/></svg>
                        @endif
                    </div>
                    <div style="flex:1;">
                        <button type="button" onclick="document.getElementById('ai-photo-file').click()"
                                class="btn btn--ghost btn--sm" style="margin-bottom:.3rem;">
                            {{ $photoUrl ? 'Cambiar foto' : 'Subir foto' }}
                        </button>
                        <input type="file" id="ai-photo-file" accept="image/jpeg,image/png,image/webp" style="display:none;" onchange="uploadProfilePhoto(this)">
                        <div style="font-size:.7rem;color:var(--color-text-muted);">JPG, PNG o WebP · Máx. 3 MB</div>
                    </div>
                </div>
            </div>

            <button type="button" id="ai-analyze-btn" onclick="startAiAnalysis()" class="btn btn--primary" style="width:100%;justify-content:center;" disabled>
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"/></svg>
                Analizar con IA
            </button>
        </div>

        {{-- ESTADO: procesando --}}
        <div id="ai-modal-processing" style="display:none;text-align:center;padding:1rem 0;">
            <div style="width:64px;height:64px;border-radius:50%;background:linear-gradient(135deg,#6366f1,#8b5cf6);margin:0 auto 1.25rem;display:flex;align-items:center;justify-content:center;animation:ai-pulse 1.5s ease-in-out infinite;">
                <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2"><path d="M12 2a10 10 0 1 0 10 10"/><path d="M12 6v6l4 2"/></svg>
            </div>
            <div style="font-size:1.1rem;font-weight:800;color:var(--color-text-primary);margin-bottom:.5rem;">Analizando tu CV…</div>
            <div id="ai-processing-step" style="font-size:.85rem;color:var(--color-text-muted);margin-bottom:1.5rem;">Extrayendo texto del PDF…</div>
            <div style="background:var(--color-bg-secondary);border-radius:999px;height:6px;overflow:hidden;">
                <div id="ai-progress-bar" style="height:100%;background:linear-gradient(90deg,#6366f1,#8b5cf6);border-radius:999px;width:15%;transition:width .5s ease;"></div>
            </div>
        </div>

        {{-- ESTADO: error --}}
        <div id="ai-modal-error" style="display:none;text-align:center;padding:1rem 0;">
            <div style="width:56px;height:56px;border-radius:50%;background:#fee2e2;margin:0 auto 1rem;display:flex;align-items:center;justify-content:center;">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#dc2626" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>
            </div>
            <div style="font-size:1rem;font-weight:700;color:var(--color-text-primary);margin-bottom:.5rem;">Error en el análisis</div>
            <div id="ai-error-msg" style="font-size:.83rem;color:#dc2626;margin-bottom:1.25rem;"></div>
            <button type="button" onclick="resetAiModal()" class="btn btn--ghost btn--sm">Intentar de nuevo</button>
        </div>
    </div>
</div>

<style>
@keyframes ai-pulse {
    0%,100% { transform:scale(1); opacity:1; }
    50%      { transform:scale(1.08); opacity:.85; }
}
</style>

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
        // Scale iframe after section becomes visible
        if (sectionId === 'templates') setTimeout(scaleCvPreview, 80);
    }

    // ── Mobile sidebar ──
    const sidebarToggle = document.getElementById('sidebarToggle');
    const sidebarClose  = document.getElementById('sidebarClose');
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('sidebarOverlay');

    sidebarToggle.addEventListener('click', () => {
        sidebar.classList.toggle('sidebar--open');
        overlay.classList.toggle('sidebar-overlay--active');
    });

    overlay.addEventListener('click', closeSidebar);
    if (sidebarClose) sidebarClose.addEventListener('click', closeSidebar);

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

    // ── CV Editor ──
    function showCvView(view) {
        var sel = document.getElementById('cv-selector-view');
        var hub = document.getElementById('cv-hub-view');
        var ed  = document.getElementById('cv-editor-view');
        if (sel) sel.style.display = (view === 'selector') ? '' : 'none';
        if (hub) hub.style.display = (view === 'hub')      ? '' : 'none';
        if (ed)  ed.style.display  = (view === 'editor')   ? '' : 'none';
        if (view === 'editor') scaleCvPreview();
    }

    // Mapa: tab del editor → IDs/selectores de sección en la plantilla
    var _SECTION_ANCHORS = {
        presentacion: ['#perfil','#profile','#about','#inicio','#home','#hero','.hero','header'],
        contacto:     ['#contacto','#contact','#contactame'],
        experiencia:  ['#experiencia','#experience','#trabajo','#work','#exp'],
        formacion:    ['#formacion','#education','#estudios','#educacion'],
        habilidades:  ['#habilidades','#skills','#competencias','#abilities'],
        idiomas:      ['#idiomas','#languages','#lenguajes'],
    };

    // ── CV Section tabs ──
    function showCvSection(section) {
        var savedY = window.scrollY || window.pageYOffset;

        document.querySelectorAll('[id^="cvpanel-"]').forEach(function(p) { p.style.display = 'none'; });
        document.querySelectorAll('[id^="cvtab-"]').forEach(function(t) {
            t.style.background = 'transparent';
            t.style.color = 'var(--color-text-secondary)';
        });
        var panel = document.getElementById('cvpanel-' + section);
        var tab   = document.getElementById('cvtab-' + section);
        if (panel) panel.style.display = '';
        if (tab) {
            tab.style.background = 'var(--color-brand)';
            tab.style.color = '#fff';
            tab.blur();
        }

        // Restaurar scroll: el foco del botón y scrollIntoView del iframe
        // pueden desplazar la página — lo evitamos
        requestAnimationFrame(function() { window.scrollTo(0, savedY); });

        var iframe = document.getElementById('cv-preview-iframe');
        if (!iframe) return;
        var doc; try { doc = iframe.contentDocument; } catch(e) { return; }
        if (!doc) return;
        var anchors = (_SECTION_ANCHORS[section] || []);
        for (var i = 0; i < anchors.length; i++) {
            var el = doc.querySelector(anchors[i]);
            if (el) { el.scrollIntoView({ behavior: 'smooth', block: 'start' }); return; }
        }
        doc.querySelectorAll('a[href]').forEach(function(a) {
            if (anchors.indexOf(a.getAttribute('href')) !== -1) { a.click(); }
        });
    }

    // ── Scale iframe ──
    function scaleCvPreview() {
        var wrap   = document.getElementById('cv-preview-wrap');
        var iframe = document.getElementById('cv-preview-iframe');
        if (!wrap || !iframe) return;
        var scale = wrap.offsetWidth / 1280;
        if (!scale) return;
        var wrapH = wrap.offsetHeight || 580;
        iframe.style.transform = 'scale(' + scale + ')';
        iframe.style.width     = '1280px';
        iframe.style.height    = Math.round(wrapH / scale) + 'px';
    }
    window.addEventListener('resize', scaleCvPreview);

    // ── Tabs scroll: ocultar degradado al llegar al final ──
    (function() {
        var tabsEl = document.querySelector('.cv-tabs-scroll');
        var wrapEl = document.querySelector('.cv-tabs-wrap');
        if (!tabsEl || !wrapEl) return;
        function updateFade() {
            var atEnd = tabsEl.scrollLeft + tabsEl.clientWidth >= tabsEl.scrollWidth - 4;
            wrapEl.classList.toggle('scrolled-end', atEnd);
        }
        tabsEl.addEventListener('scroll', updateFade, { passive: true });
        updateFade();
    })();

    // ── HTML escape helper ──
    function _esc(str) {
        return String(str || '').replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
    }

    // ── Build HTML for dynamic sections ──
    function _buildExperienciaHTML(entries) {
        if (!entries.length) return '<p style="color:var(--color-text-muted,#999);font-style:italic;padding:.5rem 0;">Sin experiencia añadida.</p>';
        return entries.map(function(e) {
            var desc = _esc(e.descripcion || '').replace(/\n/g, '<br>');
            return '<div class="timeline-item">' +
                '<div class="timeline-date">' + _esc(e.periodo) + '</div>' +
                '<div class="timeline-content">' +
                '<h3>' + _esc(e.cargo) + '</h3>' +
                '<h4>' + _esc(e.empresa) + '</h4>' +
                '<p>' + desc + '</p>' +
                '</div></div>';
        }).join('');
    }

    function _buildFormacionHTML(entries) {
        if (!entries.length) return '<p style="color:var(--color-text-muted,#999);font-style:italic;padding:.5rem 0;">Sin formación añadida.</p>';
        return entries.map(function(e) {
            return '<div class="education-item">' +
                '<div class="education-icon">🎓</div>' +
                '<div class="education-content">' +
                '<h3>' + _esc(e.titulo) + '</h3>' +
                '<h4>' + _esc(e.institucion) + '</h4>' +
                '<p class="education-date">' + _esc(e.periodo) + '</p>' +
                '<p class="education-description">' + _esc(e.descripcion) + '</p>' +
                '</div></div>';
        }).join('');
    }

    function _buildProyectosHTML(entries) {
        if (!entries.length) return '';
        return entries.map(function(e) {
            if (!e.nombre && !e.descripcion) return '';
            var nameHtml = (e.url && e.url.trim())
                ? '<a href="' + _esc(e.url) + '" class="cv-project-link" target="_blank" rel="noopener">' + _esc(e.nombre) + '</a>'
                : _esc(e.nombre);
            var tagsHtml = '';
            if (e.tecnologias) {
                var tags = e.tecnologias.split(',').map(function(t){ return t.trim(); }).filter(Boolean);
                if (tags.length) tagsHtml = '<div class="cv-project-tags">' + tags.map(function(t){ return '<span class="cv-project-tag">' + _esc(t) + '</span>'; }).join('') + '</div>';
            }
            var desc = e.descripcion ? '<p class="cv-project-desc">' + _esc(e.descripcion).replace(/\n/g,'<br>') + '</p>' : '';
            return '<div class="cv-project-item"><div class="cv-project-name">' + nameHtml + '</div>' + desc + tagsHtml + '</div>';
        }).filter(Boolean).join('');
    }

    function _buildIdiomasHTML(entries) {
        if (!entries.length) return '';
        return entries.map(function(e) {
            if (!e.idioma) return '';
            return '<div style="display:flex;align-items:center;justify-content:space-between;padding:.5rem 0;border-bottom:1px solid rgba(255,255,255,.08);">' +
                '<span style="font-weight:600;">' + _esc(e.idioma) + '</span>' +
                '<span style="opacity:.75;">' + _esc(e.nivel) + '</span>' +
                '</div>';
        }).join('');
    }

    // ── Serialize entries from DOM ──
    function _serializeEntries(type) {
        var result = [];
        var container = document.getElementById('entries-' + type);
        if (!container) return result;
        container.querySelectorAll('.cv-entry').forEach(function(entry) {
            var obj = {};
            entry.querySelectorAll('[data-field]').forEach(function(el) {
                obj[el.dataset.field] = el.value;
            });
            if (Object.values(obj).some(function(v){ return (v || '').trim(); })) {
                result.push(obj);
            }
        });
        return result;
    }

    function _toggleWrap(doc, type, show) {
        doc.querySelectorAll('[data-cv-section-wrap="' + type + '"]').forEach(function(el) {
            el.style.display = show ? '' : 'none';
        });
    }

    // ── Update entries section in iframe ──
    var _entriesTimers = {};
    function updateEntriesPreview(type) {
        clearTimeout(_entriesTimers[type]);
        _entriesTimers[type] = setTimeout(function() {
            var iframe = document.getElementById('cv-preview-iframe');
            if (!iframe) return;
            var doc; try { doc = iframe.contentDocument; } catch(e) { return; }
            if (!doc) return;
            var entries = _serializeEntries(type);
            var section = doc.querySelector('[data-cv-section="' + type + '"]');
            if (!section) return;
            if (type === 'experiencia') section.innerHTML = _buildExperienciaHTML(entries);
            if (type === 'formacion')   section.innerHTML = _buildFormacionHTML(entries);
            if (type === 'proyectos') {
                var html = _buildProyectosHTML(entries);
                section.innerHTML = html;
                _toggleWrap(doc, 'proyectos', html.trim() !== '');
            }
            if (type === 'idiomas')     section.innerHTML = _buildIdiomasHTML(entries);
        }, 220);
    }

    // ── Event delegation for entry fields ──
    ['experiencia','formacion','proyectos','idiomas'].forEach(function(type) {
        var cont = document.getElementById('entries-' + type);
        if (!cont) return;
        cont.addEventListener('input', function() { updateEntriesPreview(type); });
        cont.addEventListener('change', function() { updateEntriesPreview(type); });
    });

    // ── Simple field update ──
    var _cvTimer = null;
    function updatePreview(field, value) {
        clearTimeout(_cvTimer);
        _cvTimer = setTimeout(function() {
            var iframe = document.getElementById('cv-preview-iframe');
            if (!iframe) return;
            var doc; try { doc = iframe.contentDocument; } catch(e) { return; }
            if (!doc) return;
            doc.querySelectorAll('[data-cv="' + field + '"]').forEach(function(el) {
                if (el.tagName === 'A') {
                    el.textContent = value;
                    if (field === 'email')                           el.href = 'mailto:' + value;
                    else if (field === 'phone')                      el.href = 'tel:' + value;
                    else if (field === 'linkedin' || field === 'website') el.href = value;
                } else {
                    el.textContent = value;
                }
            });
        }, 180);
    }

    // ── Fill ALL fields into iframe ──
    function fillAllPreview() {
        var iframe = document.getElementById('cv-preview-iframe');
        if (!iframe) return;
        var doc; try { doc = iframe.contentDocument; } catch(e) { return; }
        if (!doc || !doc.body) return;

        // Simple fields
        var simpleMap = {
            name: 'cv_name', job_title: 'cv_job_title', bio: 'cv_bio',
            email: 'cv_email', phone: 'cv_phone', location: 'cv_location',
            linkedin: 'cv_linkedin', website: 'cv_website'
        };
        Object.keys(simpleMap).forEach(function(field) {
            var input = document.getElementById(simpleMap[field]);
            if (!input || !input.value.trim()) return;
            var value = input.value;
            doc.querySelectorAll('[data-cv="' + field + '"]').forEach(function(el) {
                if (el.tagName === 'A') {
                    el.textContent = value;
                    if (field === 'email')   el.href = 'mailto:' + value;
                    if (field === 'phone')   el.href = 'tel:'    + value;
                    if (field === 'linkedin' || field === 'website') el.href = value;
                } else {
                    el.textContent = value;
                }
            });
        });

        // Foto de perfil
        if (_currentPhotoUrl) {
            doc.querySelectorAll('[data-cv="photo"]').forEach(function(el) {
                if (el.tagName === 'IMG') el.src = _currentPhotoUrl;
            });
        }

        // Entry sections
        ['experiencia','formacion','proyectos','idiomas'].forEach(function(type) {
            var entries = _serializeEntries(type);
            var section = doc.querySelector('[data-cv-section="' + type + '"]');
            if (!section) return;
            if (type === 'experiencia' && entries.length) section.innerHTML = _buildExperienciaHTML(entries);
            if (type === 'formacion'   && entries.length) section.innerHTML = _buildFormacionHTML(entries);
            if (type === 'proyectos') {
                var html = _buildProyectosHTML(entries);
                section.innerHTML = html;
                _toggleWrap(doc, 'proyectos', html.trim() !== '');
            }
            if (type === 'idiomas'     && entries.length) section.innerHTML = _buildIdiomasHTML(entries);
        });

        // Habilidades
        var habSection = doc.querySelector('[data-cv-section="habilidades"]');
        if (habSection) habSection.innerHTML = _buildHabilidadesHTML(_getSkills());
    }

    // ── Banner ──
    function fillFromProfile() {
        dismissImportBanner();
        fillAllPreview();
    }

    // ── Abrir plantilla en nueva pestaña con datos actuales ──
    function openPreviewTab(baseUrl) {
        var data = { fields: {}, sections: {}, photo: _currentPhotoUrl || null, sectionVisibility: {} };
        var simpleMap = {
            name: 'cv_name', job_title: 'cv_job_title', bio: 'cv_bio',
            email: 'cv_email', phone: 'cv_phone', location: 'cv_location',
            linkedin: 'cv_linkedin', website: 'cv_website'
        };
        Object.keys(simpleMap).forEach(function(field) {
            var el = document.getElementById(simpleMap[field]);
            if (el) data.fields[field] = el.value;
        });
        ['experiencia','formacion','proyectos','idiomas'].forEach(function(type) {
            var entries = _serializeEntries(type);
            if (type === 'experiencia') data.sections[type] = _buildExperienciaHTML(entries);
            if (type === 'formacion')   data.sections[type] = _buildFormacionHTML(entries);
            if (type === 'proyectos') {
                var html = _buildProyectosHTML(entries);
                data.sections[type] = html;
                data.sectionVisibility[type] = html.trim() !== '';
            }
            if (type === 'idiomas')     data.sections[type] = _buildIdiomasHTML(entries);
        });
        data.sections['habilidades'] = _buildHabilidadesHTML(_getSkills());
        localStorage.setItem('cv_preview_live', JSON.stringify(data));
        var url = baseUrl.split('?')[0];
        window.open(url + '?live=1', '_blank');
    }

    function dismissImportBanner() {
        var b = document.getElementById('cv-import-banner');
        if (b) { b.style.opacity = '0'; b.style.transition = 'opacity .3s'; setTimeout(function(){ b.style.display='none'; }, 300); }
    }

    // ── iframe load ──
    function onPreviewLoaded(iframe) {
        scaleCvPreview();
        setTimeout(fillAllPreview, 50);
        try {
            var doc = iframe.contentDocument;
            if (!doc) return;
            doc.addEventListener('click', function(e) {
                var link = e.target.closest ? e.target.closest('a[href]') : (e.target.tagName === 'A' ? e.target : null);
                if (!link) return;
                var href = link.getAttribute('href') || '';
                var isInternal = href.startsWith('#') || href.startsWith('mailto:') || href.startsWith('tel:') || href === '' || href === '/';
                if (!isInternal) e.preventDefault();
            });
        } catch(e) {}
    }

    function onCvFileSelected(input) {
        if (input.files && input.files[0]) {
            dismissImportBanner();
        }
    }

    // ── Add / Remove entries ──
    function addEntry(type) {
        var container = document.getElementById('entries-' + type);
        if (!container) return;
        var tmpl = container.querySelector('.cv-entry');
        if (!tmpl) return;
        var clone = tmpl.cloneNode(true);
        clone.querySelectorAll('input,textarea').forEach(function(el){ el.value = ''; });
        clone.querySelectorAll('select').forEach(function(el){ el.selectedIndex = 0; });
        container.appendChild(clone);
        var first = clone.querySelector('input,textarea,select');
        if (first) { first.focus(); first.scrollIntoView({ behavior:'smooth', block:'nearest' }); }
    }

    function removeEntry(btn) {
        var entry = btn.closest ? btn.closest('.cv-entry') : btn.parentElement;
        if (!entry) return;
        var cont = entry.parentElement;
        var type = cont.id ? cont.id.replace('entries-','') : null;
        if (cont.querySelectorAll('.cv-entry').length <= 1) {
            entry.querySelectorAll('input,textarea').forEach(function(el){ el.value=''; });
            entry.querySelectorAll('select').forEach(function(el){ el.selectedIndex=0; });
            if (type) updateEntriesPreview(type);
            return;
        }
        entry.style.opacity = '0'; entry.style.transition = 'opacity .2s';
        setTimeout(function(){
            entry.remove();
            if (type) updateEntriesPreview(type);
        }, 200);
    }

    // ── Foto de perfil ──
    var _currentPhotoUrl = '{{ $photoUrl ?? '' }}';

    function uploadProfilePhoto(input) {
        var file = input.files[0];
        if (!file) return;
        if (file.size > 3 * 1024 * 1024) { alert('La imagen no puede superar 3 MB.'); return; }
        var fd = new FormData();
        fd.append('photo', file);
        fd.append('_token', '{{ csrf_token() }}');
        fetch('{{ route("dashboard.photo.upload") }}', { method: 'POST', body: fd })
            .then(function(r) { return r.json(); })
            .then(function(data) {
                if (!data.url) return;
                _currentPhotoUrl = data.url;
                // Actualizar ambas vistas previas (modal + editor)
                ['ai-photo-preview','editor-photo-preview'].forEach(function(id) {
                    var wrap = document.getElementById(id);
                    if (!wrap) return;
                    var img = wrap.querySelector('img');
                    if (img) { img.src = data.url; }
                    else {
                        wrap.innerHTML = '<img src="' + data.url + '" style="width:100%;height:100%;object-fit:cover;">';
                    }
                });
                // Actualizar botones
                document.querySelectorAll('#ai-photo-file,#editor-photo-file').forEach(function(inp) {
                    var btn = inp.previousElementSibling;
                    if (btn) btn.textContent = 'Cambiar foto';
                });
                // Inyectar en iframe
                _injectPhotoToIframe(data.url);
            })
            .catch(function() { alert('Error al subir la foto. Inténtalo de nuevo.'); });
    }

    function _injectPhotoToIframe(url) {
        var iframe = document.getElementById('cv-preview-iframe');
        if (!iframe) return;
        var doc; try { doc = iframe.contentDocument; } catch(e) { return; }
        if (!doc) return;
        doc.querySelectorAll('[data-cv="photo"]').forEach(function(el) {
            if (el.tagName === 'IMG') el.src = url;
        });
    }

    // ── Habilidades (chips) ──
    var _skillsTimer = null;

    function _getSkills() {
        var skills = [];
        document.querySelectorAll('#skills-chips .skill-chip-item').forEach(function(chip) {
            var s = chip.dataset.skill;
            if (s && s.trim()) skills.push(s.trim());
        });
        return skills;
    }

    function addSkill() {
        var input = document.getElementById('skill-input');
        if (!input) return;
        var val = input.value.trim();
        if (!val) return;
        var chip = document.createElement('span');
        chip.className = 'skill-chip-item';
        chip.dataset.skill = val;
        chip.style.cssText = 'display:inline-flex;align-items:center;gap:.25rem;background:#eff6ff;border:1px solid #bfdbfe;color:#1e40af;font-size:.78rem;font-weight:600;padding:.25rem .6rem .25rem .75rem;border-radius:99px;line-height:1.3;';
        chip.innerHTML = _esc(val) + '<button type="button" onclick="removeSkill(this)" title="Eliminar" style="background:none;border:none;cursor:pointer;color:#93c5fd;padding:0 0 0 2px;line-height:1;font-size:1.15rem;display:flex;align-items:center;">&times;</button>';
        var container = document.getElementById('skills-chips');
        if (container) container.appendChild(chip);
        input.value = '';
        updateSkillsPreview();
    }

    function removeSkill(btn) {
        var chip = btn.closest ? btn.closest('.skill-chip-item') : btn.parentElement;
        if (!chip) return;
        chip.style.opacity = '0';
        chip.style.transition = 'opacity .15s';
        setTimeout(function() { chip.remove(); updateSkillsPreview(); }, 150);
    }

    function updateSkillsPreview() {
        clearTimeout(_skillsTimer);
        _skillsTimer = setTimeout(function() {
            var iframe = document.getElementById('cv-preview-iframe');
            if (!iframe) return;
            var doc; try { doc = iframe.contentDocument; } catch(e) { return; }
            if (!doc) return;
            var section = doc.querySelector('[data-cv-section="habilidades"]');
            if (!section) return;
            section.innerHTML = _buildHabilidadesHTML(_getSkills());
        }, 220);
    }

    function _buildHabilidadesHTML(skills) {
        if (!skills.length) return '';
        return '<div style="display:flex;flex-wrap:wrap;gap:8px;padding:4px 0;">' +
            skills.map(function(s) {
                return '<span class="cv-skill-chip">' + _esc(s) + '</span>';
            }).join('') +
            '</div>';
    }

    function saveCvData(e) {
        var data = {};
        ['cv_name','cv_job_title','cv_bio','cv_email','cv_phone','cv_location',
         'cv_linkedin','cv_website'].forEach(function(id) {
            var el = document.getElementById(id);
            if (el) data[id] = el.value;
        });
        data.habilidades = _getSkills();
        data.experiencia = _serializeEntries('experiencia');
        data.formacion   = _serializeEntries('formacion');
        data.proyectos   = _serializeEntries('proyectos');
        data.idiomas     = _serializeEntries('idiomas');

        var btn = document.getElementById('btn-save-cv');
        if (btn) { btn.disabled = true; btn.style.opacity = '.6'; }

        fetch('{{ route("dashboard.cv-data.update") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json',
            },
            body: JSON.stringify({ cv_data: data }),
        })
        .then(function(r) { return r.json(); })
        .then(function() {
            if (btn) {
                btn.disabled = false; btn.style.opacity = '';
                var orig = btn.innerHTML;
                btn.innerHTML = '<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg> Guardado';
                btn.style.background = '#16a34a';
                setTimeout(function(){ btn.innerHTML = orig; btn.style.background = ''; }, 2200);
            }
        })
        .catch(function() {
            if (btn) { btn.disabled = false; btn.style.opacity = ''; }
            alert('Error al guardar. Inténtalo de nuevo.');
        });
    }

    // ── Limpiar todos los datos del CV ──
    function confirmClearCvData() {
        if (!confirm('¿Seguro que quieres borrar todos los datos de tu CV?\nEsta acción no se puede deshacer.')) return;

        fetch('{{ route("dashboard.cv-data.clear") }}', {
            method: 'DELETE',
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' },
        })
        .then(function(r) { return r.json(); })
        .then(function() {
            // Limpiar campos simples
            ['cv_name','cv_job_title','cv_bio','cv_email','cv_phone','cv_location',
             'cv_linkedin','cv_website'].forEach(function(id) {
                var el = document.getElementById(id);
                if (el) el.value = '';
            });
            // Limpiar chips de habilidades
            var chipsContainer = document.getElementById('skills-chips');
            if (chipsContainer) chipsContainer.innerHTML = '';

            // Limpiar entradas dinámicas (dejar solo una vacía)
            ['experiencia','formacion','proyectos'].forEach(function(type) {
                var container = document.getElementById('entries-' + type);
                if (!container) return;
                var entries = container.querySelectorAll('.cv-entry');
                for (var i = 1; i < entries.length; i++) entries[i].remove();
                var first = container.querySelector('.cv-entry');
                if (first) {
                    first.querySelectorAll('input,textarea').forEach(function(el) { el.value = ''; });
                    first.querySelectorAll('select').forEach(function(el) { el.selectedIndex = 0; });
                }
            });
            _rebuildIdiomaEntries([]);

            // Limpiar foto de perfil
            _currentPhotoUrl = '';
            ['ai-photo-preview','editor-photo-preview'].forEach(function(id) {
                var wrap = document.getElementById(id);
                if (wrap) wrap.innerHTML = '';
            });
            document.querySelectorAll('#ai-photo-file,#editor-photo-file').forEach(function(inp) {
                inp.value = '';
                var btn = inp.previousElementSibling;
                if (btn) btn.textContent = 'Subir foto';
            });

            // Recargar iframe (quita todos los datos inyectados)
            var iframe = document.getElementById('cv-preview-iframe');
            if (iframe) iframe.src = iframe.src;

            // Feedback
            var btn = document.getElementById('btn-save-cv');
            if (btn) {
                var orig = btn.innerHTML;
                btn.innerHTML = '<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg> Limpiado';
                btn.style.background = '#64748b';
                setTimeout(function(){ btn.innerHTML = orig; btn.style.background = ''; }, 2000);
            }
        })
        .catch(function() { alert('Error al limpiar los datos.'); });
    }

    // ── Profile view/edit toggle ──
    function toggleProfileEdit() {
        var view    = document.getElementById('profile-view');
        var edit    = document.getElementById('profile-edit');
        var btn     = document.getElementById('btn-edit-profile');
        var pencil  = document.getElementById('icon-pencil');
        var closeX  = document.getElementById('icon-close');
        var editing = edit.style.display !== 'none';

        view.style.display  = editing ? '' : 'none';
        edit.style.display  = editing ? 'none' : '';
        pencil.style.display = editing ? '' : 'none';
        closeX.style.display = editing ? 'none' : '';

        if (editing) {
            btn.style.background   = 'var(--color-surface)';
            btn.style.borderColor  = 'var(--color-border)';
            btn.style.color        = 'var(--color-text-secondary)';
        } else {
            btn.style.background   = 'var(--color-primary)';
            btn.style.borderColor  = 'var(--color-primary)';
            btn.style.color        = '#fff';
        }
    }

    // Auto-open edit mode if there are validation errors
    @if($errors->any())
        switchSection('profile', 'Mi Perfil');
        toggleProfileEdit();
    @endif

    // ── Open section from URL hash ──
    const hash = window.location.hash.replace('#', '');
    const validSections = ['overview', 'profile', 'templates', 'orders'];
    if (hash && validSections.includes(hash)) {
        const labels = { overview: 'Inicio', profile: 'Mi Perfil', templates: 'Mi CV Web', orders: 'Mi Plan' };
        switchSection(hash, labels[hash]);
    }

    // ── Open section from session (after form submit redirects) ──
    @if(session('open_section'))
    @php
        $sectionLabels = ['overview' => 'Inicio', 'profile' => 'Mi Perfil', 'templates' => 'Mi CV Web', 'orders' => 'Mi Plan'];
        $openSection   = session('open_section');
    @endphp
        switchSection('{{ $openSection }}', '{{ $sectionLabels[$openSection] ?? 'Inicio' }}');
    @endif

    // ════════════════════════════════════════════════
    //  MODAL AI — Analizar CV con IA
    // ════════════════════════════════════════════════
    var _aiSource   = null;   // 'existing' | 'new'
    var _aiFile     = null;   // File object si es nuevo
    var _aiParseId  = null;
    var _aiPollTimer = null;

    var _aiSteps = [
        [15,  'Extrayendo texto del PDF…'],
        [35,  'Procesando el contenido…'],
        [55,  'Enviando a la IA…'],
        [75,  'La IA está analizando tu CV…'],
        [88,  'Estructurando los datos…'],
        [95,  'Casi listo…'],
    ];
    var _aiStepIdx = 0;

    function openAiModal() {
        resetAiModal();
        var overlay = document.getElementById('ai-modal-overlay');
        overlay.style.display = 'flex';
        document.body.style.overflow = 'hidden';

        @if($u->cv_path)
        // Si ya tiene CV, preseleccionar "existente"
        selectAiOption('existing');
        @endif
    }

    function closeAiModal() {
        clearInterval(_aiPollTimer);
        document.getElementById('ai-modal-overlay').style.display = 'none';
        document.body.style.overflow = '';
    }

    function resetAiModal() {
        _aiSource = null; _aiFile = null; _aiParseId = null; _aiStepIdx = 0;
        clearInterval(_aiPollTimer);
        _showAiState('select');
        // Deselect options
        ['existing','new'].forEach(function(o) {
            var el = document.getElementById('ai-option-' + o);
            var ch = document.getElementById('ai-check-' + o);
            if (el) el.style.borderColor = 'var(--color-border)';
            if (ch) ch.style.display = 'none';
        });
        var drop = document.getElementById('ai-drop-zone');
        if (drop) drop.style.display = 'none';
        var btn = document.getElementById('ai-analyze-btn');
        if (btn) btn.disabled = true;
        _aiFile = null;
        var lbl = document.getElementById('ai-drop-label');
        if (lbl) lbl.innerHTML = 'Arrastra tu CV aquí o <span style="color:var(--color-primary);text-decoration:underline;cursor:pointer;" onclick="document.getElementById(\'ai-file-input\').click()">selecciona archivo</span>';
    }

    function _showAiState(state) {
        ['select','processing','error'].forEach(function(s) {
            var el = document.getElementById('ai-modal-' + s);
            if (el) el.style.display = s === state ? '' : 'none';
        });
    }

    function selectAiOption(option) {
        _aiSource = option;
        ['existing','new'].forEach(function(o) {
            var card  = document.getElementById('ai-option-' + o);
            var check = document.getElementById('ai-check-' + o);
            if (!card) return;
            var active = o === option;
            card.style.borderColor  = active ? 'var(--color-primary)' : 'var(--color-border)';
            card.style.background   = active ? '#f5f3ff' : '';
            if (check) check.style.display = active ? '' : 'none';
        });
        var drop = document.getElementById('ai-drop-zone');
        if (drop) drop.style.display = option === 'new' ? '' : 'none';

        var btn = document.getElementById('ai-analyze-btn');
        if (btn) btn.disabled = option === 'new' ? (_aiFile === null) : false;
    }

    function onAiFileSelected(input) {
        if (input.files && input.files[0]) {
            _aiFile = input.files[0];
            var lbl = document.getElementById('ai-drop-label');
            if (lbl) lbl.textContent = '✓ ' + _aiFile.name;
            var btn = document.getElementById('ai-analyze-btn');
            if (btn) btn.disabled = false;
        }
    }

    function onAiDrop(e) {
        e.preventDefault();
        document.getElementById('ai-drop-zone').style.borderColor = 'var(--color-border)';
        var file = e.dataTransfer.files[0];
        if (file && file.type === 'application/pdf') {
            _aiFile = file;
            var lbl = document.getElementById('ai-drop-label');
            if (lbl) lbl.textContent = '✓ ' + file.name;
            var btn = document.getElementById('ai-analyze-btn');
            if (btn) btn.disabled = false;
        }
    }

    function startAiAnalysis() {
        if (!_aiSource) return;
        if (_aiSource === 'new' && !_aiFile) return;

        _showAiState('processing');
        _aiStepIdx = 0;
        _advanceAiStep();

        var formData = new FormData();
        formData.append('source', _aiSource);
        formData.append('_token', '{{ csrf_token() }}');
        if (_aiSource === 'new') formData.append('cv', _aiFile);

        fetch('{{ route("dashboard.cv.parse") }}', {
            method: 'POST',
            headers: { 'Accept': 'application/json' },
            body: formData,
        })
        .then(function(r) { return r.json(); })
        .then(function(data) {
            if (data.error) { _showAiError(data.error); return; }
            _aiParseId = data.parse_id;
            _startPolling();
        })
        .catch(function(e) { _showAiError('Error de conexión. Inténtalo de nuevo.'); });
    }

    function _advanceAiStep() {
        if (_aiStepIdx >= _aiSteps.length) return;
        var step = _aiSteps[_aiStepIdx++];
        var bar  = document.getElementById('ai-progress-bar');
        var lbl  = document.getElementById('ai-processing-step');
        if (bar) bar.style.width = step[0] + '%';
        if (lbl) lbl.textContent = step[1];
        if (_aiStepIdx < _aiSteps.length) {
            setTimeout(_advanceAiStep, 2500 + Math.random() * 1500);
        }
    }

    function _startPolling() {
        _aiPollTimer = setInterval(function() {
            if (!_aiParseId) return;
            fetch('{{ url("dashboard/cv/parse") }}/' + _aiParseId + '/status', {
                headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
            })
            .then(function(r) { return r.json(); })
            .then(function(data) {
                if (data.status === 'completed') {
                    clearInterval(_aiPollTimer);
                    _onAiCompleted(data.cv_data || {});
                } else if (data.status === 'failed') {
                    clearInterval(_aiPollTimer);
                    _showAiError(data.error || 'El análisis falló. Inténtalo de nuevo.');
                }
                // pending/processing → seguir esperando
            })
            .catch(function() {});
        }, 2500);
    }

    function _onAiCompleted(cvData) {
        // Completar barra al 100%
        var bar = document.getElementById('ai-progress-bar');
        var lbl = document.getElementById('ai-processing-step');
        if (bar) bar.style.width = '100%';
        if (lbl) lbl.textContent = '¡Análisis completado!';

        setTimeout(function() {
            closeAiModal();
            _applyAiData(cvData);
        }, 800);
    }

    function _applyAiData(cvData) {
        // 1. Asegurar que el editor esté visible
        switchSection('templates', 'Mi CV Web');
        showCvView('editor');

        // 2. Rellenar campos simples
        var simpleFields = {
            'cv_name':      cvData.cv_name      || '',
            'cv_job_title': cvData.cv_job_title || '',
            'cv_bio':       cvData.cv_bio       || '',
            'cv_email':     cvData.cv_email     || '',
            'cv_phone':     cvData.cv_phone     || '',
            'cv_location':  cvData.cv_location  || '',
            'cv_linkedin':  cvData.cv_linkedin  || '',
            'cv_website':   cvData.cv_website   || ''
        };
        Object.keys(simpleFields).forEach(function(id) {
            var el = document.getElementById(id);
            if (el) el.value = simpleFields[id];
        });

        // Habilidades (chips)
        var habContainer = document.getElementById('skills-chips');
        if (habContainer) {
            habContainer.innerHTML = '';
            var habs = cvData.habilidades || [];
            // Backward compat: old cv_skills string
            if (!habs.length && cvData.cv_skills) {
                habs = cvData.cv_skills.split(',').map(function(s){ return s.trim(); }).filter(Boolean);
            }
            habs.forEach(function(skill) {
                if (!skill) return;
                var chip = document.createElement('span');
                chip.className = 'skill-chip-item';
                chip.dataset.skill = skill;
                chip.style.cssText = 'display:inline-flex;align-items:center;gap:.25rem;background:#eff6ff;border:1px solid #bfdbfe;color:#1e40af;font-size:.78rem;font-weight:600;padding:.25rem .6rem .25rem .75rem;border-radius:99px;line-height:1.3;';
                chip.innerHTML = _esc(skill) + '<button type="button" onclick="removeSkill(this)" title="Eliminar" style="background:none;border:none;cursor:pointer;color:#93c5fd;padding:0 0 0 2px;line-height:1;font-size:1.15rem;display:flex;align-items:center;">&times;</button>';
                habContainer.appendChild(chip);
            });
        }

        // 3. Reconstruir entradas dinámicas (limpia primero)
        _rebuildEntries('experiencia', cvData.experiencia || [],
            function(e) { return [_nullClean(e.empresa), _nullClean(e.cargo), _nullClean(e.periodo), _nullClean(e.descripcion)]; });
        _rebuildEntries('formacion', cvData.formacion || [],
            function(e) { return [_nullClean(e.institucion), _nullClean(e.titulo), _nullClean(e.periodo), _nullClean(e.descripcion)]; });
        _rebuildEntries('proyectos', cvData.proyectos || [],
            function(e) { return [_nullClean(e.nombre), _nullClean(e.descripcion), _nullClean(e.url), _nullClean(e.tecnologias)]; });
        _rebuildIdiomaEntries(cvData.idiomas || []);

        // 4. Actualizar plantilla en el iframe
        // Esperamos a que el iframe esté listo y el layout visible
        var attempts = 0;
        function tryFillPreview() {
            var iframe = document.getElementById('cv-preview-iframe');
            if (!iframe) return;
            var doc;
            try { doc = iframe.contentDocument; } catch(e) {}
            if (!doc || !doc.body || doc.body.children.length === 0) {
                if (++attempts < 20) setTimeout(tryFillPreview, 200);
                return;
            }
            fillAllPreview();
        }
        setTimeout(tryFillPreview, 150);

        // 5. Feedback visual en botón guardar
        var btn = document.getElementById('btn-save-cv');
        if (btn) {
            var orig = btn.innerHTML;
            btn.innerHTML = '<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg> Datos IA aplicados';
            btn.style.background = '#16a34a';
            setTimeout(function(){ btn.innerHTML = orig; btn.style.background = ''; }, 4000);
        }
    }

    function _nullClean(v) {
        if (!v) return '';
        var t = String(v).trim();
        if (t.toLowerCase() === 'null') return '';
        if (/^null\s*[–\-]\s*null$/i.test(t)) return '';
        return t;
    }

    function _rebuildEntries(type, entries, fieldsFn) {
        var container = document.getElementById('entries-' + type);
        if (!container) return;

        // Eliminar todas las entradas existentes excepto la primera (la usamos de plantilla)
        var existing = container.querySelectorAll('.cv-entry');
        for (var i = 1; i < existing.length; i++) existing[i].remove();

        if (!entries.length) return;

        var tmpl = container.querySelector('.cv-entry');
        if (!tmpl) return;

        // Primera entrada: rellenar con datos
        var fields0 = fieldsFn(entries[0]);
        tmpl.querySelectorAll('[data-field]').forEach(function(inp, idx) {
            if (fields0[idx] !== undefined) inp.value = fields0[idx];
        });

        // Entradas adicionales: clonar y rellenar
        for (var j = 1; j < entries.length; j++) {
            var clone = tmpl.cloneNode(true);
            var f = fieldsFn(entries[j]);
            clone.querySelectorAll('[data-field]').forEach(function(inp, idx) {
                if (f[idx] !== undefined) inp.value = f[idx];
            });
            container.appendChild(clone);
        }
    }

    function _setNivelSelect(sel, nivel) {
        if (!nivel) return;
        var n = nivel.trim();
        var nl = n.toLowerCase();
        // 1. Exact match
        for (var o = 0; o < sel.options.length; o++) {
            if (sel.options[o].text === n) { sel.selectedIndex = o; return; }
        }
        // 2. Case-insensitive exact
        for (var o = 0; o < sel.options.length; o++) {
            if (sel.options[o].text.toLowerCase() === nl) { sel.selectedIndex = o; return; }
        }
        // 3. Option text starts with AI value (e.g. "C2" matches "C2 – Maestría")
        for (var o = 0; o < sel.options.length; o++) {
            if (sel.options[o].text.toLowerCase().startsWith(nl)) { sel.selectedIndex = o; return; }
        }
        // 4. AI value starts with option text (e.g. "Nativo ..." → "Nativo")
        for (var o = 0; o < sel.options.length; o++) {
            var tl = sel.options[o].text.toLowerCase();
            if (tl && nl.startsWith(tl)) { sel.selectedIndex = o; return; }
        }
        // 5. First significant word match (e.g. "B2" in "B2 – Intermedio alto")
        for (var o = 0; o < sel.options.length; o++) {
            var fw = sel.options[o].text.split(/[\s–-]/)[0].toLowerCase();
            if (fw && (nl === fw || nl.startsWith(fw + ' '))) { sel.selectedIndex = o; return; }
        }
    }

    function _rebuildIdiomaEntries(idiomas) {
        if (!idiomas.length) return;
        var container = document.getElementById('entries-idiomas');
        if (!container) return;

        // Clear all entries except the first (template)
        var existing = container.querySelectorAll('.cv-entry');
        for (var k = 1; k < existing.length; k++) existing[k].remove();

        var tmpl = container.querySelector('.cv-entry');
        if (!tmpl) return;

        var idiomaInput = tmpl.querySelector('[data-field="idioma"]');
        var nivelSelect = tmpl.querySelector('[data-field="nivel"]');
        if (idiomaInput) idiomaInput.value = idiomas[0].idioma || '';
        if (nivelSelect) _setNivelSelect(nivelSelect, idiomas[0].nivel || '');

        for (var i = 1; i < idiomas.length; i++) {
            var clone = tmpl.cloneNode(true);
            var inp = clone.querySelector('[data-field="idioma"]');
            var sel = clone.querySelector('[data-field="nivel"]');
            if (inp) inp.value = idiomas[i].idioma || '';
            if (sel) _setNivelSelect(sel, idiomas[i].nivel || '');

            container.appendChild(clone);
        }

        updateEntriesPreview('idiomas');
    }

    function _showAiError(msg) {
        _showAiState('error');
        var el = document.getElementById('ai-error-msg');
        if (el) el.textContent = msg;
    }

    // Cerrar modal al hacer click fuera
    document.getElementById('ai-modal-overlay').addEventListener('click', function(e) {
        if (e.target === this) closeAiModal();
    });
</script>
@endpush

@endsection
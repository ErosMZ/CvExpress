<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Usuarios — Admin CVPortfolio</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:opsz,wght@9..40,300;9..40,400;9..40,500;9..40,600;9..40,700&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
    <style>
        /* ── Modal crear usuario ── */
        .modal-backdrop {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,.45);
            z-index: 200;
            align-items: center;
            justify-content: center;
            padding: 1rem;
        }
        .modal-backdrop.open { display: flex; }
        .modal {
            background: var(--admin-surface);
            border: 1px solid var(--admin-border);
            border-radius: var(--radius-lg);
            width: 100%;
            max-width: 480px;
            box-shadow: 0 24px 48px rgba(0,0,0,.18);
        }
        .modal__header {
            padding: 1.25rem 1.5rem;
            border-bottom: 1px solid var(--admin-border);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .modal__title { font-size: .95rem; font-weight: 700; color: var(--admin-text); }
        .modal__close {
            background: none; border: none; cursor: pointer;
            color: var(--admin-text-muted); padding: .25rem;
            border-radius: var(--radius); line-height: 1;
        }
        .modal__close:hover { background: var(--admin-border); color: var(--admin-text); }
        .modal__body { padding: 1.5rem; display: flex; flex-direction: column; gap: 1rem; }
        .modal__footer { padding: 1rem 1.5rem; border-top: 1px solid var(--admin-border); display: flex; gap: .75rem; justify-content: flex-end; }

        /* Campos del modal */
        .form-field { display: flex; flex-direction: column; gap: .35rem; }
        .form-field label { font-size: .82rem; font-weight: 600; color: var(--admin-text); }
        .form-field label .req { color: #ef4444; margin-left: 2px; }
        .form-input, .form-select-el {
            width: 100%; padding: .6rem .875rem;
            background: var(--admin-bg); border: 1px solid var(--admin-border);
            border-radius: var(--radius); font-size: .875rem; color: var(--admin-text);
            font-family: inherit; outline: none; transition: border-color .15s, box-shadow .15s;
        }
        .form-input:focus, .form-select-el:focus {
            border-color: var(--blue-400);
            box-shadow: 0 0 0 3px rgba(96,165,250,.12);
        }
        .field-error { font-size: .73rem; color: #ef4444; margin-top: .2rem; }

        /* Toggle switches (reutilizados de create) */
        .toggle-row { display: flex; align-items: center; justify-content: space-between; gap: 1rem; }
        .toggle-info { flex: 1; }
        .toggle-label { font-size: .875rem; font-weight: 500; color: var(--admin-text); }
        .toggle-desc  { font-size: .75rem; color: var(--admin-text-muted); margin-top: 2px; }
        .toggle { position: relative; width: 40px; height: 22px; flex-shrink: 0; }
        .toggle input { opacity: 0; width: 0; height: 0; }
        .toggle-track {
            position: absolute; inset: 0; background: var(--admin-border);
            border-radius: 999px; cursor: pointer; transition: background .2s;
        }
        .toggle-track::after {
            content: ''; position: absolute; width: 16px; height: 16px;
            left: 3px; top: 3px; background: white; border-radius: 50%;
            transition: transform .2s; box-shadow: 0 1px 3px rgba(0,0,0,.2);
        }
        .toggle input:checked + .toggle-track { background: var(--blue-500, #1A56DB); }
        .toggle input:checked + .toggle-track::after { transform: translateX(18px); }

        /* Avatar inicial */
        .user-avatar {
            width: 34px; height: 34px; border-radius: 50%;
            background: var(--blue-100, #dbeafe);
            color: var(--blue-700, #1d4ed8);
            font-size: .78rem; font-weight: 700;
            display: flex; align-items: center; justify-content: center;
            flex-shrink: 0;
        }
        .user-avatar--admin {
            background: var(--amber-100, #fef3c7);
            color: var(--amber-700, #b45309);
        }
    </style>
</head>
<body>

{{-- ── TOPBAR MÓVIL ── --}}
<div class="admin-topbar">
    <a href="{{ url('/') }}" class="admin-topbar__logo">
        <div class="admin-topbar__logo-mark">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <rect x="3" y="3" width="18" height="18" rx="3"/><path d="M8 7h8M8 11h5M8 15h6"/>
            </svg>
        </div>
        <span class="admin-topbar__logo-text">CVPortfolio Admin</span>
    </a>
    <button class="admin-hamburger" id="hamburger" aria-label="Menú">
        <span></span><span></span><span></span>
    </button>
</div>

<div class="admin-sidebar-overlay" id="sidebarOverlay"></div>

<div class="admin-layout">

    {{-- ── SIDEBAR ── --}}
    <aside class="admin-sidebar" id="sidebar">
        <div class="admin-sidebar__header">
            <a href="{{ url('/') }}" class="admin-sidebar__logo">
                <div class="admin-sidebar__logo-mark">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="3" y="3" width="18" height="18" rx="3"/><path d="M8 7h8M8 11h5M8 15h6"/>
                    </svg>
                </div>
                <span class="admin-sidebar__logo-text"><strong>CV</strong>Portfolio</span>
            </a>
            <div class="admin-sidebar__user">
                <div class="admin-sidebar__avatar">{{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 2)) }}</div>
                <div class="admin-sidebar__user-info">
                    <div class="admin-sidebar__user-name">{{ auth()->user()->name ?? 'Administrador' }}</div>
                    <div class="admin-sidebar__user-role">Administrador</div>
                </div>
                <div class="admin-sidebar__badge"></div>
            </div>
        </div>

        <nav class="admin-sidebar__nav">
            <div class="admin-sidebar__section-label">Principal</div>
            <a href="{{ route('admin') }}" class="admin-sidebar__link">
                <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg>
                Dashboard
            </a>
            <a href="{{ route('admin.users.index') }}" class="admin-sidebar__link active">
                <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                Usuarios
                <span class="admin-sidebar__link-badge">{{ \App\Models\User::count() }}</span>
            </a>
            <div class="admin-sidebar__section-label">Contenido</div>
            <a href="{{ route('templates.index') }}" class="admin-sidebar__link">
                <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                Plantillas
                <span class="admin-sidebar__link-badge">{{ \App\Models\Template::count() }}</span>
            </a>
            <a href="{{ route('categories.index') }}" class="admin-sidebar__link">
                <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"/></svg>
                Categorías
                <span class="admin-sidebar__link-badge">{{ \App\Models\Category::count() }}</span>
            </a>
            <div class="admin-sidebar__section-label">Crear</div>
            <a href="{{ route('templates.create') }}" class="admin-sidebar__link">
                <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="16"/><line x1="8" y1="12" x2="16" y2="12"/></svg>
                Nueva plantilla
            </a>
            <a href="{{ route('categories.create') }}" class="admin-sidebar__link">
                <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="16"/><line x1="8" y1="12" x2="16" y2="12"/></svg>
                Nueva categoría
            </a>
            <div class="admin-sidebar__section-label">Sitio web</div>
            <a href="{{ url('/') }}" target="_blank" class="admin-sidebar__link">
                <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="2" y1="12" x2="22" y2="12"/><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/></svg>
                Ir al sitio
                <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-left:auto;opacity:.4"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/><polyline points="15 3 21 3 21 9"/><line x1="10" y1="14" x2="21" y2="3"/></svg>
            </a>
        </nav>

        <div class="admin-sidebar__footer">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="admin-sidebar__link" style="width:100%;border:none;background:none;cursor:pointer;text-align:left;">
                    <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
                    Cerrar sesión
                </button>
            </form>
        </div>
    </aside>

    {{-- ── MAIN ── --}}
    <main class="admin-main">

        <div class="admin-header">
            <div class="admin-header__left">
                <nav class="admin-header__breadcrumb">
                    <a href="{{ route('admin') }}">Admin</a>
                    <span>/</span>
                    <span>Usuarios</span>
                </nav>
                <h1 class="admin-header__title">Usuarios</h1>
            </div>
            <div class="admin-header__right">
                <button class="btn-admin btn-admin--primary" onclick="openModal()">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/>
                    </svg>
                    Nuevo usuario
                </button>
            </div>
        </div>

        <div class="admin-content">

            @if(session('success'))
                <div class="admin-alert admin-alert--success">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink:0"><polyline points="20 6 9 17 4 12"/></svg>
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="admin-alert admin-alert--error">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink:0"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                    {{ session('error') }}
                </div>
            @endif

            {{-- Stats --}}
            @php
                $total      = \App\Models\User::count();
                $admins     = \App\Models\User::where('is_admin', true)->count();
                $verified   = \App\Models\User::whereNotNull('email_verified_at')->count();
                $unverified = \App\Models\User::whereNull('email_verified_at')->count();
            @endphp
            <div class="admin-stats" style="grid-template-columns:repeat(4,1fr);margin-bottom:1.5rem;">
                <div class="stat-card stat-card--blue" style="padding:1.1rem 1.25rem;">
                    <div class="stat-card__value" style="font-size:1.75rem;">{{ $total }}</div>
                    <div class="stat-card__label">Total usuarios</div>
                </div>
                <div class="stat-card stat-card--amber" style="padding:1.1rem 1.25rem;">
                    <div class="stat-card__value" style="font-size:1.75rem;">{{ $admins }}</div>
                    <div class="stat-card__label">Administradores</div>
                </div>
                <div class="stat-card stat-card--teal" style="padding:1.1rem 1.25rem;">
                    <div class="stat-card__value" style="font-size:1.75rem;">{{ $verified }}</div>
                    <div class="stat-card__label">Verificados</div>
                </div>
                <div class="stat-card stat-card--purple" style="padding:1.1rem 1.25rem;">
                    <div class="stat-card__value" style="font-size:1.75rem;">{{ $unverified }}</div>
                    <div class="stat-card__label">Sin verificar</div>
                </div>
            </div>

            {{-- Tabla --}}
            <div class="admin-panel">
                <div class="admin-toolbar">
                    <form method="GET" action="{{ route('admin.users.index') }}" style="display:contents;">
                        <div class="admin-toolbar__search">
                            <div class="admin-search">
                                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                                <input type="text" name="search" placeholder="Buscar por nombre o email…" value="{{ request('search') }}" id="searchInput">
                            </div>
                        </div>
                        <div class="admin-toolbar__actions">
                            <select name="filter" class="form-select" style="width:auto;padding:.5rem .875rem;font-size:.85rem;" onchange="this.form.submit()">
                                <option value="">Todos</option>
                                <option value="admin"      {{ request('filter') === 'admin'      ? 'selected' : '' }}>Administradores</option>
                                <option value="verified"   {{ request('filter') === 'verified'   ? 'selected' : '' }}>Verificados</option>
                                <option value="unverified" {{ request('filter') === 'unverified' ? 'selected' : '' }}>Sin verificar</option>
                            </select>
                            <button type="submit" class="btn-admin btn-admin--ghost btn-admin--sm">Filtrar</button>
                        </div>
                    </form>
                </div>

                @if($users->isEmpty())
                    <div class="admin-empty" style="padding:4rem 2rem;">
                        <div class="admin-empty__icon">
                            <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/></svg>
                        </div>
                        <div class="admin-empty__title">No hay usuarios</div>
                        <div class="admin-empty__text">Crea el primer usuario con el botón de arriba</div>
                    </div>
                @else
                <div class="admin-table-wrap">
                    <table class="admin-table">
                        <thead>
                            <tr>
                                <th>Usuario</th>
                                <th>Rol</th>
                                <th>Email verificado</th>
                                <th>Registro</th>
                                <th style="text-align:right;">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($users as $user)
                            <tr>
                                <td>
                                    <div style="display:flex;align-items:center;gap:10px;">
                                        <div class="user-avatar {{ $user->is_admin ? 'user-avatar--admin' : '' }}">
                                            {{ strtoupper(substr($user->name, 0, 2)) }}
                                        </div>
                                        <div>
                                            <div style="font-weight:600;font-size:.9rem;color:var(--admin-text);">
                                                {{ $user->name }}
                                                @if($user->id === auth()->id())
                                                    <span style="font-size:.7rem;color:var(--admin-text-muted);font-weight:400;">(tú)</span>
                                                @endif
                                            </div>
                                            <div style="font-size:.78rem;color:var(--admin-text-muted);font-family:var(--font-mono);">{{ $user->email }}</div>
                                        </div>
                                    </div>
                                </td>

                                <td>
                                    @if($user->is_admin)
                                        <span class="badge-status badge-status--premium">Admin</span>
                                    @else
                                        <span class="badge-status badge-status--inactive">Usuario</span>
                                    @endif
                                </td>

                                <td>
                                    @if($user->hasVerifiedEmail())
                                        <span class="badge-status badge-status--active">Verificado</span>
                                        <div style="font-size:.72rem;color:var(--admin-text-muted);margin-top:3px;">
                                            {{ $user->email_verified_at->format('d M Y') }}
                                        </div>
                                    @else
                                        <span class="badge-status badge-status--inactive">Sin verificar</span>
                                    @endif
                                </td>

                                <td style="font-size:.8rem;color:var(--admin-text-muted);white-space:nowrap;">
                                    {{ $user->created_at->format('d M Y') }}
                                    <div style="font-size:.72rem;">{{ $user->created_at->diffForHumans() }}</div>
                                </td>

                                <td>
                                    <div class="table-actions" style="justify-content:flex-end;flex-wrap:wrap;gap:.35rem;">

                                        {{-- Verificar email --}}
                                        @if(! $user->hasVerifiedEmail())
                                            <form action="{{ route('admin.users.verify', $user) }}" method="POST">
                                                @csrf @method('PATCH')
                                                <button type="submit" class="table-action-btn" title="Verificar email" style="color:var(--teal-600,#0d9488);">
                                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                                                </button>
                                            </form>
                                            <form action="{{ route('admin.users.resend-verification', $user) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="table-action-btn" title="Reenviar email de verificación">
                                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="23 4 23 10 17 10"/><path d="M20.49 15a9 9 0 1 1-2.12-9.36L23 10"/></svg>
                                                </button>
                                            </form>
                                        @endif

                                        {{-- Toggle admin --}}
                                        @if($user->id !== auth()->id())
                                            <form action="{{ route('admin.users.toggle-admin', $user) }}" method="POST"
                                                  onsubmit="return confirm('{{ $user->is_admin ? '¿Quitar permisos de administrador a' : '¿Hacer administrador a' }} {{ $user->name }}?')">
                                                @csrf @method('PATCH')
                                                <button type="submit" class="table-action-btn {{ $user->is_admin ? 'danger' : '' }}"
                                                        title="{{ $user->is_admin ? 'Quitar admin' : 'Hacer admin' }}">
                                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                                                </button>
                                            </form>

                                            {{-- Eliminar --}}
                                            <form action="{{ route('admin.users.destroy', $user) }}" method="POST"
                                                  onsubmit="return confirm('¿Eliminar el usuario «{{ $user->name }}»? Esta acción no se puede deshacer.')">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="table-action-btn danger" title="Eliminar">
                                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/><path d="M10 11v6M14 11v6"/><path d="M9 6V4h6v2"/></svg>
                                                </button>
                                            </form>
                                        @endif

                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- Paginación --}}
                @if($users->hasPages())
                <div class="admin-pagination">
                    <span style="font-size:.82rem;color:var(--admin-text-muted);">
                        Mostrando {{ $users->firstItem() }}–{{ $users->lastItem() }} de {{ $users->total() }} usuarios
                    </span>
                    <div style="display:flex;gap:4px;margin-left:auto;">
                        @if($users->onFirstPage())
                            <span class="page-btn" style="opacity:.4;cursor:default;">←</span>
                        @else
                            <a href="{{ $users->previousPageUrl() }}" class="page-btn">←</a>
                        @endif
                        @foreach($users->getUrlRange(1, $users->lastPage()) as $page => $url)
                            <a href="{{ $url }}" class="page-btn {{ $users->currentPage() === $page ? 'active' : '' }}">{{ $page }}</a>
                        @endforeach
                        @if($users->hasMorePages())
                            <a href="{{ $users->nextPageUrl() }}" class="page-btn">→</a>
                        @else
                            <span class="page-btn" style="opacity:.4;cursor:default;">→</span>
                        @endif
                    </div>
                </div>
                @endif

                @endif
            </div>
        </div>
    </main>
</div>

{{-- ── MODAL: Nuevo usuario ── --}}
<div class="modal-backdrop" id="modalBackdrop" onclick="closeModalOutside(event)">
    <div class="modal">
        <div class="modal__header">
            <span class="modal__title">Nuevo usuario</span>
            <button class="modal__close" onclick="closeModal()">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
            </button>
        </div>

        <form action="{{ route('admin.users.store') }}" method="POST">
            @csrf
            <div class="modal__body">

                @if($errors->any())
                    <div class="admin-alert admin-alert--error" style="margin:0;">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink:0"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                        <div>
                            @foreach($errors->all() as $err)
                                <div style="font-size:.8rem;">{{ $err }}</div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <div class="form-field">
                    <label for="modal_name">Nombre <span class="req">*</span></label>
                    <input type="text" id="modal_name" name="name" class="form-input" value="{{ old('name') }}" required placeholder="Nombre completo">
                    @error('name') <span class="field-error">{{ $message }}</span> @enderror
                </div>

                <div class="form-field">
                    <label for="modal_email">Email <span class="req">*</span></label>
                    <input type="email" id="modal_email" name="email" class="form-input" value="{{ old('email') }}" required placeholder="correo@ejemplo.com">
                    @error('email') <span class="field-error">{{ $message }}</span> @enderror
                </div>

                <div class="form-field">
                    <label for="modal_password">Contraseña <span class="req">*</span></label>
                    <input type="password" id="modal_password" name="password" class="form-input" required placeholder="Mínimo 8 caracteres">
                    @error('password') <span class="field-error">{{ $message }}</span> @enderror
                </div>

                <div style="display:flex;flex-direction:column;gap:.75rem;padding:.25rem 0;">
                    <div class="toggle-row">
                        <div class="toggle-info">
                            <div class="toggle-label">Administrador</div>
                            <div class="toggle-desc">Acceso al panel de administración</div>
                        </div>
                        <label class="toggle">
                            <input type="checkbox" name="is_admin" {{ old('is_admin') ? 'checked' : '' }}>
                            <span class="toggle-track"></span>
                        </label>
                    </div>
                    <div class="toggle-row">
                        <div class="toggle-info">
                            <div class="toggle-label">Email verificado</div>
                            <div class="toggle-desc">Marcar el email como verificado</div>
                        </div>
                        <label class="toggle">
                            <input type="checkbox" name="verified" {{ old('verified') ? 'checked' : '' }}>
                            <span class="toggle-track"></span>
                        </label>
                    </div>
                </div>

            </div>
            <div class="modal__footer">
                <button type="button" class="btn-admin btn-admin--ghost btn-admin--sm" onclick="closeModal()">Cancelar</button>
                <button type="submit" class="btn-admin btn-admin--primary btn-admin--sm">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                    Crear usuario
                </button>
            </div>
        </form>
    </div>
</div>

<script>
(function () {
    // Hamburguesa
    const hamburger = document.getElementById('hamburger');
    const sidebar   = document.getElementById('sidebar');
    const overlay   = document.getElementById('sidebarOverlay');
    function openSidebar()  { sidebar.classList.add('open');    overlay.classList.add('visible');    hamburger.classList.add('open');    document.body.style.overflow='hidden'; }
    function closeSidebar() { sidebar.classList.remove('open'); overlay.classList.remove('visible'); hamburger.classList.remove('open'); document.body.style.overflow=''; }
    hamburger.addEventListener('click', () => sidebar.classList.contains('open') ? closeSidebar() : openSidebar());
    overlay.addEventListener('click', closeSidebar);
})();

function openModal() {
    document.getElementById('modalBackdrop').classList.add('open');
    document.body.style.overflow = 'hidden';
}
function closeModal() {
    document.getElementById('modalBackdrop').classList.remove('open');
    document.body.style.overflow = '';
}
function closeModalOutside(e) {
    if (e.target === document.getElementById('modalBackdrop')) closeModal();
}

// Si hay errores de validación, abrir modal automáticamente
@if($errors->any())
    openModal();
@endif

// Escape para cerrar modal
document.addEventListener('keydown', e => { if (e.key === 'Escape') closeModal(); });
</script>

</body>
</html>

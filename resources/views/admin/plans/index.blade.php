<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Planes — Admin CVPortfolio</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:opsz,wght@9..40,300;9..40,400;9..40,500;9..40,600;9..40,700&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
    <style>
        /* ── PLAN CARDS ── */
        .plans-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 1.25rem;
        }
        .plan-card {
            background: var(--admin-surface);
            border: 1.5px solid var(--admin-border);
            border-radius: 14px;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            position: relative;
            transition: box-shadow .2s, transform .2s;
        }
        .plan-card:hover {
            box-shadow: 0 6px 24px rgba(0,0,0,.09);
            transform: translateY(-2px);
        }
        .plan-card__top {
            padding: 1.25rem 1.25rem .9rem;
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: .75rem;
        }
        .plan-card__color-dot {
            width: 42px;
            height: 42px;
            border-radius: 11px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }
        .plan-card__title-block { flex: 1; min-width: 0; }
        .plan-card__name {
            font-size: 1rem;
            font-weight: 700;
            color: var(--admin-text);
            line-height: 1.2;
        }
        .plan-card__slug {
            font-size: .72rem;
            color: var(--admin-text-muted);
            font-family: var(--font-mono);
            margin-top: 2px;
        }
        .plan-card__price {
            font-size: 1.5rem;
            font-weight: 800;
            color: var(--admin-text);
            letter-spacing: -.03em;
        }
        .plan-card__price-sub {
            font-size: .72rem;
            font-weight: 500;
            color: var(--admin-text-muted);
        }
        .plan-card__body {
            padding: 0 1.25rem 1rem;
            flex: 1;
        }
        .plan-card__features {
            list-style: none;
            padding: 0;
            margin: 0;
            display: flex;
            flex-direction: column;
            gap: .4rem;
        }
        .plan-card__features li {
            font-size: .8rem;
            color: var(--admin-text-secondary);
            display: flex;
            align-items: flex-start;
            gap: .5rem;
            line-height: 1.45;
        }
        .plan-card__features li svg { flex-shrink: 0; margin-top: 2px; }
        .plan-card__footer {
            padding: .9rem 1.25rem;
            border-top: 1px solid var(--admin-border);
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: .5rem;
        }
        .plan-card__badge-label {
            font-size: .7rem;
            font-weight: 700;
            padding: .22rem .7rem;
            border-radius: 999px;
            letter-spacing: .04em;
        }
        /* Delete X button on card */
        .plan-card__delete-btn {
            position: absolute;
            top: .65rem;
            right: .65rem;
            width: 26px;
            height: 26px;
            border-radius: 50%;
            background: rgba(239,68,68,.08);
            border: 1.5px solid rgba(239,68,68,.18);
            color: #ef4444;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 0;
            transition: opacity .15s, background .15s;
            z-index: 2;
        }
        .plan-card:hover .plan-card__delete-btn { opacity: 1; }
        .plan-card__delete-btn:hover { background: rgba(239,68,68,.15); }

        /* ── MODAL ── */
        .pm-backdrop {
            position: fixed;
            inset: 0;
            background: rgba(15,23,42,.45);
            backdrop-filter: blur(2px);
            z-index: 200;
            display: none;
            align-items: center;
            justify-content: center;
            padding: 1rem;
        }
        .pm-backdrop.open { display: flex; }
        .pm-modal {
            background: var(--admin-surface);
            border-radius: 16px;
            width: 100%;
            max-width: 560px;
            max-height: 90vh;
            overflow-y: auto;
            box-shadow: 0 24px 64px rgba(0,0,0,.18);
            animation: pmIn .2s ease;
        }
        @keyframes pmIn {
            from { opacity: 0; transform: translateY(16px) scale(.97); }
            to   { opacity: 1; transform: none; }
        }
        .pm-modal__head {
            padding: 1.4rem 1.5rem 1rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-bottom: 1px solid var(--admin-border);
        }
        .pm-modal__title { font-size: 1.05rem; font-weight: 700; color: var(--admin-text); }
        .pm-modal__close {
            width: 30px;
            height: 30px;
            border-radius: 8px;
            border: 1.5px solid var(--admin-border);
            background: transparent;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--admin-text-muted);
            transition: background .15s;
        }
        .pm-modal__close:hover { background: var(--admin-bg); }
        .pm-modal__body { padding: 1.25rem 1.5rem; }
        .pm-modal__footer {
            padding: 1rem 1.5rem;
            border-top: 1px solid var(--admin-border);
            display: flex;
            justify-content: flex-end;
            gap: .75rem;
        }

        /* Color palette */
        .color-palette {
            display: flex;
            flex-wrap: wrap;
            gap: .5rem;
            margin-top: .4rem;
        }
        .color-swatch {
            width: 28px;
            height: 28px;
            border-radius: 8px;
            cursor: pointer;
            border: 2px solid transparent;
            transition: border-color .15s, transform .1s;
            position: relative;
        }
        .color-swatch:hover { transform: scale(1.15); }
        .color-swatch.selected { border-color: var(--admin-text); }
        .color-swatch input[type="radio"] {
            position: absolute;
            inset: 0;
            opacity: 0;
            width: 100%;
            height: 100%;
            cursor: pointer;
            margin: 0;
        }

        .pm-form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: .85rem; }
        @media (max-width: 480px) { .pm-form-grid { grid-template-columns: 1fr; } }
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
                <span class="admin-sidebar__logo-text"><strong>CV</strong>Xpress</span>
            </a>
            <div class="admin-sidebar__user">
                <div class="admin-sidebar__avatar">{{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 2)) }}</div>
                <div class="admin-sidebar__user-info">
                    <div class="admin-sidebar__user-name">{{ auth()->user()->name ?? 'Administrador' }}</div>
                    <div class="admin-sidebar__user-role">Admin</div>
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
            <a href="{{ route('admin.users.index') }}" class="admin-sidebar__link">
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
            <a href="{{ route('admin.plans.index') }}" class="admin-sidebar__link active">
                <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
                Planes
                <span class="admin-sidebar__link-badge">{{ \App\Models\Plan::count() }}</span>
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
                    <span>Planes</span>
                </nav>
                <h1 class="admin-header__title">Planes de precios</h1>
            </div>
            <div class="admin-header__right">
                <button class="btn-admin btn-admin--primary" onclick="openCreateModal()">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/>
                    </svg>
                    Nuevo plan
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

            {{-- Stats --}}
            @php
                $total    = $plans->count();
                $activos  = $plans->where('is_active', true)->count();
                $inactivos = $plans->where('is_active', false)->count();
            @endphp
            <div class="admin-stats" style="grid-template-columns:repeat(3,1fr);margin-bottom:1.5rem;">
                <div class="stat-card stat-card--blue" style="padding:1.1rem 1.25rem;">
                    <div class="stat-card__value" style="font-size:1.75rem;">{{ $total }}</div>
                    <div class="stat-card__label">Total planes</div>
                </div>
                <div class="stat-card stat-card--teal" style="padding:1.1rem 1.25rem;">
                    <div class="stat-card__value" style="font-size:1.75rem;">{{ $activos }}</div>
                    <div class="stat-card__label">Activos</div>
                </div>
                <div class="stat-card stat-card--purple" style="padding:1.1rem 1.25rem;">
                    <div class="stat-card__value" style="font-size:1.75rem;">{{ $inactivos }}</div>
                    <div class="stat-card__label">Inactivos</div>
                </div>
            </div>

            {{-- Cards grid --}}
            @if($plans->isEmpty())
                <div class="admin-panel">
                    <div class="admin-empty" style="padding:4rem 2rem;">
                        <div class="admin-empty__icon">
                            <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
                        </div>
                        <div class="admin-empty__title">No hay planes todavía</div>
                        <div class="admin-empty__text">Crea tu primer plan de precios</div>
                        <button onclick="openCreateModal()" class="btn-admin btn-admin--primary" style="margin-top:1.25rem;">
                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                            Crear primer plan
                        </button>
                    </div>
                </div>
            @else
            <div class="plans-grid">
                @foreach($plans as $plan)
                @php
                    $colorMap = [
                        'basic'     => ['bg' => '#dcfce7', 'fg' => '#16a34a'],
                        'pro'       => ['bg' => '#dbeafe', 'fg' => '#1A56DB'],
                        'super_pro' => ['bg' => '#ede9fe', 'fg' => '#7c3aed'],
                    ];
                    $tc = $colorMap[$plan->slug] ?? ['bg' => '#f1f5f9', 'fg' => '#475569'];
                @endphp
                <div class="plan-card" id="plan-card-{{ $plan->id }}">

                    {{-- Delete X --}}
                    <form method="POST" action="{{ route('admin.plans.destroy', $plan) }}"
                          onsubmit="return confirm('¿Eliminar el plan «{{ $plan->name }}»? Esta acción no se puede deshacer.')">
                        @csrf @method('DELETE')
                        <button type="submit" class="plan-card__delete-btn" title="Eliminar plan">
                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                <line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
                            </svg>
                        </button>
                    </form>

                    <div class="plan-card__top">
                        <div style="display:flex;align-items:center;gap:.75rem;flex:1;min-width:0;padding-right:1.5rem;">
                            <div class="plan-card__color-dot" style="background:{{ $tc['bg'] }};color:{{ $tc['fg'] }};">
                                @if($plan->slug === 'basic')
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                                @elseif($plan->slug === 'pro')
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
                                @else
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                                @endif
                            </div>
                            <div class="plan-card__title-block">
                                <div class="plan-card__name">{{ $plan->name }}</div>
                                <div class="plan-card__slug">{{ $plan->slug }}</div>
                            </div>
                        </div>
                        <div style="text-align:right;flex-shrink:0;">
                            <div class="plan-card__price" style="color:{{ $tc['fg'] }};">{{ number_format($plan->price, 2) }}€</div>
                            <div class="plan-card__price-sub">pago único</div>
                        </div>
                    </div>

                    <div class="plan-card__body">
                        @if($plan->features && count($plan->features))
                        <ul class="plan-card__features">
                            @foreach($plan->features as $feat)
                            <li>
                                <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="{{ $tc['fg'] }}" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                                {{ $feat }}
                            </li>
                            @endforeach
                        </ul>
                        @else
                            <p style="font-size:.8rem;color:var(--admin-text-muted);font-style:italic;">Sin características definidas</p>
                        @endif
                    </div>

                    <div class="plan-card__footer">
                        <div style="display:flex;align-items:center;gap:.5rem;">
                            @if($plan->is_active)
                                <span class="badge-status badge-status--active">Activo</span>
                            @else
                                <span class="badge-status badge-status--inactive">Inactivo</span>
                            @endif
                            @if($plan->badge_label)
                                <span class="plan-card__badge-label" style="background:{{ $tc['bg'] }};color:{{ $tc['fg'] }};">{{ $plan->badge_label }}</span>
                            @endif
                        </div>
                        <button class="btn-admin btn-admin--ghost btn-admin--sm"
                                onclick="openEditModal({{ $plan->id }}, {{ json_encode($plan->name) }}, {{ $plan->price }}, {{ json_encode($plan->color) }}, {{ json_encode($plan->badge_label) }}, {{ json_encode(implode("\n", $plan->features ?? [])) }}, {{ $plan->is_active ? 'true' : 'false' }}, {{ $plan->sort_order }})">
                            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                            Editar
                        </button>
                    </div>
                </div>
                @endforeach
            </div>
            @endif

        </div>
    </main>
</div>

{{-- ══════════════════════════════════════════
     MODAL: CREAR PLAN
══════════════════════════════════════════ --}}
<div class="pm-backdrop" id="createBackdrop" onclick="handleBackdropClick(event, 'createBackdrop')">
    <div class="pm-modal">
        <div class="pm-modal__head">
            <span class="pm-modal__title">Nuevo plan</span>
            <button class="pm-modal__close" onclick="closeModal('createBackdrop')" type="button">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
            </button>
        </div>
        <form method="POST" action="{{ route('admin.plans.store') }}">
            @csrf
            <div class="pm-modal__body">
                @include('admin.plans._form', ['plan' => null])
            </div>
            <div class="pm-modal__footer">
                <button type="button" class="btn-admin btn-admin--ghost" onclick="closeModal('createBackdrop')">Cancelar</button>
                <button type="submit" class="btn-admin btn-admin--primary">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                    Crear plan
                </button>
            </div>
        </form>
    </div>
</div>

{{-- ══════════════════════════════════════════
     MODAL: EDITAR PLAN
══════════════════════════════════════════ --}}
<div class="pm-backdrop" id="editBackdrop" onclick="handleBackdropClick(event, 'editBackdrop')">
    <div class="pm-modal">
        <div class="pm-modal__head">
            <span class="pm-modal__title">Editar plan</span>
            <button class="pm-modal__close" onclick="closeModal('editBackdrop')" type="button">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
            </button>
        </div>
        <form method="POST" id="editForm" action="">
            @csrf @method('PUT')
            <div class="pm-modal__body">
                @include('admin.plans._form', ['plan' => null, 'edit' => true])
            </div>
            <div class="pm-modal__footer">
                <button type="button" class="btn-admin btn-admin--ghost" onclick="closeModal('editBackdrop')">Cancelar</button>
                <button type="submit" class="btn-admin btn-admin--primary">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                    Guardar cambios
                </button>
            </div>
        </form>
    </div>
</div>

<script>
(function () {
    // Sidebar mobile
    const hamburger = document.getElementById('hamburger');
    const sidebar   = document.getElementById('sidebar');
    const overlay   = document.getElementById('sidebarOverlay');
    function openSidebar()  { sidebar.classList.add('open');    overlay.classList.add('visible');    hamburger.classList.add('open');    document.body.style.overflow='hidden'; }
    function closeSidebar() { sidebar.classList.remove('open'); overlay.classList.remove('visible'); hamburger.classList.remove('open'); document.body.style.overflow=''; }
    hamburger.addEventListener('click', () => sidebar.classList.contains('open') ? closeSidebar() : openSidebar());
    overlay.addEventListener('click', closeSidebar);
})();

function openCreateModal() {
    document.getElementById('createBackdrop').classList.add('open');
    document.body.style.overflow = 'hidden';
}

function openEditModal(id, name, price, color, badgeLabel, features, isActive, sortOrder) {
    const form = document.getElementById('editForm');
    form.action = `/admin/planes/${id}`;

    form.querySelector('[name="name"]').value        = name;
    form.querySelector('[name="price"]').value       = price;
    form.querySelector('[name="badge_label"]').value = badgeLabel || '';
    form.querySelector('[name="features"]').value    = features || '';
    form.querySelector('[name="sort_order"]').value  = sortOrder || 0;
    form.querySelector('[name="is_active"]').checked = isActive;

    // Color swatches
    form.querySelectorAll('.color-swatch input[type="radio"]').forEach(radio => {
        radio.checked = (radio.value === color);
        radio.closest('.color-swatch').classList.toggle('selected', radio.checked);
    });

    document.getElementById('editBackdrop').classList.add('open');
    document.body.style.overflow = 'hidden';
}

function closeModal(id) {
    document.getElementById(id).classList.remove('open');
    document.body.style.overflow = '';
}

function handleBackdropClick(e, id) {
    if (e.target.id === id) closeModal(id);
}

// Highlight selected color swatch
document.querySelectorAll('.color-swatch input[type="radio"]').forEach(radio => {
    radio.addEventListener('change', function() {
        const group = this.closest('.color-palette');
        group.querySelectorAll('.color-swatch').forEach(sw => sw.classList.remove('selected'));
        this.closest('.color-swatch').classList.add('selected');
    });
});

// Close on Escape
document.addEventListener('keydown', e => {
    if (e.key === 'Escape') {
        closeModal('createBackdrop');
        closeModal('editBackdrop');
    }
});
</script>

</body>
</html>

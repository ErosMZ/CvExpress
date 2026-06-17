{{-- resources/views/admin/dashboard.blade.php --}}
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel Admin — CvXpress</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:opsz,wght@9..40,300;9..40,400;9..40,500;9..40,600;9..40,700&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
</head>
<body>

{{-- ── TOPBAR MÓVIL ── --}}
<div class="admin-topbar">
    <a href="/" class="admin-topbar__logo">
        <div class="admin-topbar__logo-mark">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <rect x="3" y="3" width="18" height="18" rx="3"/><path d="M8 7h8M8 11h5M8 15h6"/>
            </svg>
        </div>
        <span class="admin-topbar__logo-text">CvXpress Admin</span>
    </a>
    <button class="admin-hamburger" id="hamburger" aria-label="Menú">
        <span></span><span></span><span></span>
    </button>
</div>

{{-- ── OVERLAY ── --}}
<div class="admin-sidebar-overlay" id="sidebarOverlay"></div>

<div class="admin-layout">

    {{-- ─────────────── SIDEBAR ─────────────── --}}
    <aside class="admin-sidebar" id="sidebar">

        {{-- Logo + usuario --}}
        <div class="admin-sidebar__header">
            <a href="/" class="admin-sidebar__logo">
                <div class="admin-sidebar__logo-mark">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="3" y="3" width="18" height="18" rx="3"/><path d="M8 7h8M8 11h5M8 15h6"/>
                    </svg>
                </div>
                <span class="admin-sidebar__logo-text"><strong>CV</strong>Xpress</span>
            </a>

            {{-- Usuario conectado --}}
            <div class="admin-sidebar__user">
                <div class="admin-sidebar__avatar">
                    {{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 2)) }}
                </div>
                <div class="admin-sidebar__user-info">
                    <div class="admin-sidebar__user-name">{{ auth()->user()->name ?? 'Administrador' }}</div>
                    <div class="admin-sidebar__user-role">Admin</div>
                </div>
                <div class="admin-sidebar__badge"></div>
            </div>
        </div>

        {{-- Navegación --}}
        <nav class="admin-sidebar__nav">

            <div class="admin-sidebar__section-label">Principal</div>

            <a href="{{route('admin') }}" class="admin-sidebar__link active">
                <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/>
                    <rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/>
                </svg>
                Dashboard
            </a>

            <a href="{{ route('admin.users.index') }}" class="admin-sidebar__link">
                <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                    <circle cx="9" cy="7" r="4"/>
                    <path d="M23 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75"/>
                </svg>
                Usuarios
                <span class="admin-sidebar__link-badge">{{ \App\Models\User::count() }}</span>
            </a>

            <div class="admin-sidebar__section-label">Contenido</div>

            <a href="{{ route('templates.index') }}" class="admin-sidebar__link">
                <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                    <polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/>
                    <line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/>
                </svg>
                Plantillas
                <span class="admin-sidebar__link-badge">{{ \App\Models\Template::count() }}</span>
            </a>

            <a href="{{ route('categories.index') }}" class="admin-sidebar__link">
                <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"/>
                </svg>
                Categorías
                <span class="admin-sidebar__link-badge">{{ \App\Models\Category::count() }}</span>
            </a>

            <a href="{{ route('admin.plans.index') }}" class="admin-sidebar__link">
                <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
                Planes
                <span class="admin-sidebar__link-badge">{{ \App\Models\Plan::count() }}</span>
            </a>

            <div class="admin-sidebar__section-label">Accesos rápidos</div>

            <a href="{{ route('templates.create') }}" class="admin-sidebar__link">
                <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="16"/><line x1="8" y1="12" x2="16" y2="12"/>
                </svg>
                Nueva plantilla
            </a>

            <a href="{{ route('categories.create') }}" class="admin-sidebar__link">
                <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="16"/><line x1="8" y1="12" x2="16" y2="12"/>
                </svg>
                Nueva categoría
            </a>

            <div class="admin-sidebar__section-label">Sitio web</div>

            <a href="{{ url('/') }}" target="_blank" class="admin-sidebar__link">
                <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="10"/>
                    <line x1="2" y1="12" x2="22" y2="12"/>
                    <path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/>
                </svg>
                Ir al sitio
            </a>

        </nav>

        {{-- Logout --}}
        <div class="admin-sidebar__footer">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="admin-sidebar__link" style="width:100%;border:none;background:none;cursor:pointer;">
                    <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/>
                        <polyline points="16 17 21 12 16 7"/>
                        <line x1="21" y1="12" x2="9" y2="12"/>
                    </svg>
                    Cerrar sesión
                </button>
            </form>
        </div>
    </aside>

    {{-- ─────────────── CONTENIDO ─────────────── --}}
    <main class="admin-main">

        {{-- Header --}}
        <div class="admin-header">
            <div class="admin-header__left">
                <nav class="admin-header__breadcrumb">
                    <a href="{{ url('/') }}">CvXpress</a>
                    <span>/</span>
                    <span>Admin</span>
                </nav>
                <h1 class="admin-header__title">Dashboard</h1>
            </div>
            <div class="admin-header__right">
                <span style="font-size:.82rem;color:var(--admin-text-muted);">
                    {{ now()->format('d M Y') }}
                </span>
                <a href="{{ route('templates.create') }}" class="btn-admin btn-admin--primary btn-admin--sm">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/>
                    </svg>
                    Nueva plantilla
                </a>
            </div>
        </div>

        <div class="admin-content">

            @if(session('success'))
                <div class="admin-alert admin-alert--success">
                    {{ session('success') }}
                </div>
            @endif

            {{-- ── STATS ── --}}
            @php
                $totalUsers      = \App\Models\User::count();
                $totalTemplates  = \App\Models\Template::count();
                $totalCategories = \App\Models\Category::count();
                $premiumTemplates = \App\Models\Template::where('is_premium', true)->count();
                $newUsersThisMonth = \App\Models\User::whereMonth('created_at', now()->month)->count();
                $templatesByTier = \App\Models\Template::selectRaw('plan_tier, count(*) as total')
                    ->groupBy('plan_tier')->pluck('total', 'plan_tier');
            @endphp

            <div class="admin-stats">

                <div class="stat-card stat-card--blue">
                    <div class="stat-card__header">
                        <div class="stat-card__icon">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                                <circle cx="9" cy="7" r="4"/>
                                <path d="M23 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75"/>
                            </svg>
                        </div>
                        <span class="stat-card__trend stat-card__trend--up">
                            ↑ +{{ $newUsersThisMonth }} este mes
                        </span>
                    </div>
                    <div class="stat-card__value">{{ number_format($totalUsers) }}</div>
                    <div class="stat-card__label">Usuarios registrados</div>
                </div>

                <div class="stat-card stat-card--teal">
                    <div class="stat-card__header">
                        <div class="stat-card__icon">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                                <polyline points="14 2 14 8 20 8"/>
                            </svg>
                        </div>
                        <span class="stat-card__trend stat-card__trend--neu">
                            {{ $premiumTemplates }} premium
                        </span>
                    </div>
                    <div class="stat-card__value">{{ number_format($totalTemplates) }}</div>
                    <div class="stat-card__label">Plantillas</div>
                    <div style="display:flex;gap:6px;margin-top:.6rem;flex-wrap:wrap;">
                        @php
                            $tiers = \App\Models\Template::PLAN_TIERS;
                        @endphp
                        @foreach($tiers as $key => $tier)
                            <span style="display:inline-flex;align-items:center;gap:4px;font-size:.72rem;font-weight:600;padding:2px 8px;border-radius:99px;background:{{ $tier['bg'] }};color:{{ $tier['color'] }};">
                                {{ $tier['label'] }}
                                <span style="font-weight:700;">{{ $templatesByTier[$key] ?? 0 }}</span>
                            </span>
                        @endforeach
                    </div>
                </div>

                <div class="stat-card stat-card--purple">
                    <div class="stat-card__header">
                        <div class="stat-card__icon">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"/>
                            </svg>
                        </div>
                        <span class="stat-card__trend stat-card__trend--neu">—</span>
                    </div>
                    <div class="stat-card__value">{{ number_format($totalCategories) }}</div>
                    <div class="stat-card__label">Categorías</div>
                </div>

                <div class="stat-card stat-card--amber">
                    <div class="stat-card__header">
                        <div class="stat-card__icon">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <line x1="12" y1="1" x2="12" y2="23"/>
                                <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/>
                            </svg>
                        </div>
                        <span class="stat-card__trend stat-card__trend--up">↑ simulado</span>
                    </div>
                    <div class="stat-card__value">€1.240</div>
                    <div class="stat-card__label">Ingresos este mes</div>
                </div>

            </div>{{-- /stats --}}

            {{-- ── FILA 1: Gráfica + Acciones rápidas ── --}}
            <div class="admin-grid" style="margin-bottom:1.5rem;">

                {{-- Gráfica de ingresos (simulada) --}}
                <div class="admin-panel">
                    <div class="admin-panel__head">
                        <span class="admin-panel__title">
                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/>
                            </svg>
                            Ingresos mensuales
                        </span>
                        <span style="font-size:.75rem;background:var(--amber-100);color:var(--amber-600);padding:3px 10px;border-radius:99px;font-weight:600;">Datos simulados</span>
                    </div>
                    <div class="admin-panel__body">
                        <div style="display:flex;align-items:baseline;gap:8px;margin-bottom:1.25rem;">
                            <span style="font-size:2rem;font-weight:700;letter-spacing:-0.04em;color:var(--admin-text);">€7.840</span>
                            <span style="font-size:.82rem;font-weight:600;color:var(--green-600);background:var(--green-100);padding:2px 8px;border-radius:99px;">↑ +18.3%</span>
                        </div>
                        <div class="chart-container">
                            <div class="chart-grid">
                                <div class="chart-grid-line"></div>
                                <div class="chart-grid-line"></div>
                                <div class="chart-grid-line"></div>
                                <div class="chart-grid-line"></div>
                            </div>
                            <div class="chart-bars">
                                @php
                                $chartData = [
                                    ['mes'=>'Nov','val'=>620,'h'=>'52%'],
                                    ['mes'=>'Dic','val'=>890,'h'=>'75%'],
                                    ['mes'=>'Ene','val'=>740,'h'=>'62%'],
                                    ['mes'=>'Feb','val'=>1050,'h'=>'88%'],
                                    ['mes'=>'Mar','val'=>820,'h'=>'69%'],
                                    ['mes'=>'Abr','val'=>960,'h'=>'80%'],
                                    ['mes'=>'May','val'=>1240,'h'=>'100%','highlight'=>true],
                                ];
                                @endphp
                                @foreach($chartData as $d)
                                <div class="chart-bar-wrap">
                                    <div class="chart-bar {{ isset($d['highlight']) ? 'chart-bar--highlight' : '' }}"
                                         style="height:{{ $d['h'] }}"
                                         data-val="€{{ $d['val'] }}">
                                    </div>
                                    <span class="chart-label">{{ $d['mes'] }}</span>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Acciones rápidas + Actividad --}}
                <div style="display:flex;flex-direction:column;gap:1.5rem;">

                    {{-- Quick actions --}}
                    <div class="admin-panel">
                        <div class="admin-panel__head">
                            <span class="admin-panel__title">
                                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"/>
                                </svg>
                                Acciones rápidas
                            </span>
                        </div>
                        <div class="admin-panel__body">
                            <div class="quick-actions">
                                <a href="{{ route('templates.create') }}" class="quick-action">
                                    <div class="quick-action__icon quick-action__icon--blue">
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                                            <polyline points="14 2 14 8 20 8"/>
                                            <line x1="12" y1="18" x2="12" y2="12"/><line x1="9" y1="15" x2="15" y2="15"/>
                                        </svg>
                                    </div>
                                    <span class="quick-action__label">Nueva plantilla</span>
                                </a>
                                <a href="{{ route('categories.create') }}" class="quick-action">
                                    <div class="quick-action__icon quick-action__icon--teal">
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"/>
                                            <line x1="12" y1="18" x2="12" y2="12"/><line x1="9" y1="15" x2="15" y2="15"/>
                                        </svg>
                                    </div>
                                    <span class="quick-action__label">Nueva categoría</span>
                                </a>
                                <a href="{{ route('templates.index') }}" class="quick-action">
                                    <div class="quick-action__icon quick-action__icon--purple">
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                                            <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                                        </svg>
                                    </div>
                                    <span class="quick-action__label">Gestionar plantillas</span>
                                </a>
                                <a href="{{ route('admin.users.index') }}" class="quick-action">
                                    <div class="quick-action__icon quick-action__icon--amber">
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/>
                                            <path d="M23 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75"/>
                                        </svg>
                                    </div>
                                    <span class="quick-action__label">Ver usuarios</span>
                                </a>
                            </div>
                        </div>
                    </div>

                    {{-- Estado del sistema --}}
                    <div class="admin-panel">
                        <div class="admin-panel__head">
                            <span class="admin-panel__title">
                                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M22 12h-4l-3 9L9 3l-3 9H2"/>
                                </svg>
                                Estado del sistema
                            </span>
                        </div>
                        <div class="admin-panel__body">
                            @php
                            $systemItems = [
                                ['label'=>'Plantillas activas',  'val'=> \App\Models\Template::where('is_active',true)->count(),  'total'=>$totalTemplates,  'color'=>'var(--teal-500)'],
                                ['label'=>'Categorías activas', 'val'=> \App\Models\Category::where('is_active',true)->count(), 'total'=>$totalCategories, 'color'=>'var(--blue-500)'],
                                ['label'=>'Emails verificados', 'val'=> \App\Models\User::whereNotNull('email_verified_at')->count(), 'total'=>$totalUsers, 'color'=>'var(--purple-600)'],
                            ];
                            @endphp
                            <div style="display:flex;flex-direction:column;gap:1rem;">
                                @foreach($systemItems as $item)
                                @php $pct = $item['total'] > 0 ? round($item['val']/$item['total']*100) : 0; @endphp
                                <div>
                                    <div style="display:flex;justify-content:space-between;margin-bottom:5px;">
                                        <span style="font-size:.82rem;font-weight:500;color:var(--admin-text);">{{ $item['label'] }}</span>
                                        <span style="font-size:.8rem;color:var(--admin-text-muted);">{{ $item['val'] }}/{{ $item['total'] }} &nbsp;·&nbsp; <strong>{{ $pct }}%</strong></span>
                                    </div>
                                    <div style="height:6px;background:var(--admin-border);border-radius:99px;overflow:hidden;">
                                        <div style="height:100%;width:{{ $pct }}%;background:{{ $item['color'] }};border-radius:99px;transition:width .4s ease;"></div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                </div>{{-- /col derecha --}}
            </div>{{-- /fila 1 --}}

            {{-- ── FILA 2: Usuarios recientes + Plantillas recientes ── --}}
            <div class="admin-grid" style="margin-bottom:1.5rem;">

                {{-- Usuarios recientes --}}
                <div class="admin-panel">
                    <div class="admin-panel__head">
                        <span class="admin-panel__title">
                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/>
                            </svg>
                            Usuarios recientes
                        </span>
                        <a href="{{ route('admin.users.index') }}" class="admin-panel__link">
                            Ver todos
                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M5 12h14M12 5l7 7-7 7"/>
                            </svg>
                        </a>
                    </div>
                    <div class="admin-panel__body--nop">
                        @php
                        $recentUsers = \App\Models\User::latest()->take(5)->get();
                        $avatarColors = ['blue','teal','purple','amber','blue'];
                        @endphp
                        @if($recentUsers->isEmpty())
                            <div class="admin-empty">
                                <div class="admin-empty__icon">
                                    <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/>
                                    </svg>
                                </div>
                                <div class="admin-empty__title">Sin usuarios aún</div>
                                <div class="admin-empty__text">Los usuarios registrados aparecerán aquí</div>
                            </div>
                        @else
                        <div class="admin-table-wrap">
                            <table class="admin-table">
                                <thead>
                                    <tr>
                                        <th>Usuario</th>
                                        <th>Registrado</th>
                                        <th>Estado</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recentUsers as $i => $user)
                                    <tr>
                                        <td>
                                            <div class="admin-user-info">
                                                <div class="admin-user-avatar admin-user-avatar--{{ $avatarColors[$i % count($avatarColors)] }}">
                                                    {{ strtoupper(substr($user->name, 0, 2)) }}
                                                </div>
                                                <div>
                                                    <div class="admin-user-info__name">{{ $user->name }}</div>
                                                    <div class="admin-user-info__email">{{ $user->email }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-muted" style="font-size:.8rem;white-space:nowrap;">
                                            {{ $user->created_at->diffForHumans() }}
                                        </td>
                                        <td>
                                            @if($user->email_verified_at)
                                                <span class="badge-status badge-status--active">Verificado</span>
                                            @else
                                                <span class="badge-status badge-status--inactive">Pendiente</span>
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @endif
                    </div>
                </div>

                {{-- Plantillas recientes --}}
                <div class="admin-panel">
                    <div class="admin-panel__head">
                        <span class="admin-panel__title">
                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                                <polyline points="14 2 14 8 20 8"/>
                            </svg>
                            Plantillas recientes
                        </span>
                        <a href="{{ route('templates.index') }}" class="admin-panel__link">
                            Ver todas
                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M5 12h14M12 5l7 7-7 7"/>
                            </svg>
                        </a>
                    </div>
                    <div class="admin-panel__body--nop">
                        @php $recentTemplates = \App\Models\Template::with('category')->latest()->take(5)->get(); @endphp
                        @if($recentTemplates->isEmpty())
                            <div class="admin-empty">
                                <div class="admin-empty__icon">
                                    <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                                    </svg>
                                </div>
                                <div class="admin-empty__title">Sin plantillas aún</div>
                                <div class="admin-empty__text">Crea tu primera plantilla</div>
                            </div>
                        @else
                        <div class="admin-table-wrap">
                            <table class="admin-table">
                                <thead>
                                    <tr>
                                        <th>Nombre</th>
                                        <th>Precio</th>
                                        <th>Estado</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recentTemplates as $template)
                                    <tr>
                                        <td>
                                            <div style="font-weight:600;font-size:.875rem;">{{ $template->name }}</div>
                                            <div style="font-size:.75rem;color:var(--admin-text-muted);">
                                                {{ $template->category->name ?? '—' }}
                                            </div>
                                        </td>
                                        <td>
                                            @if($template->is_premium)
                                                <span class="badge-status badge-status--premium">€{{ number_format($template->price, 2) }}</span>
                                            @else
                                                <span style="font-size:.82rem;color:var(--admin-text-muted);">Gratis</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($template->is_active)
                                                <span class="badge-status badge-status--active">Activa</span>
                                            @else
                                                <span class="badge-status badge-status--inactive">Inactiva</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="table-actions">
                                                <a href="{{ route('templates.edit', $template) }}" class="table-action-btn" title="Editar">
                                                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                        <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                                                        <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                                                    </svg>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @endif
                    </div>
                </div>

            </div>{{-- /fila 2 --}}

            {{-- ── FILA 3: Actividad reciente ── --}}
            <div class="admin-panel">
                <div class="admin-panel__head">
                    <span class="admin-panel__title">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="10"/>
                            <polyline points="12 6 12 12 16 14"/>
                        </svg>
                        Actividad reciente
                    </span>
                </div>
                <div class="admin-panel__body">
                    <div class="activity-list">
                        @php
                        $activities = collect();
                        \App\Models\User::latest()->take(3)->get()->each(function($u) use (&$activities) {
                            $activities->push(['type'=>'user','text'=>"Nuevo usuario registrado: <strong>{$u->name}</strong>",'time'=>$u->created_at->diffForHumans(),'color'=>'blue']);
                        });
                        \App\Models\Template::latest()->take(3)->get()->each(function($t) use (&$activities) {
                            $activities->push(['type'=>'template','text'=>"Plantilla creada: <strong>{$t->name}</strong>",'time'=>$t->created_at->diffForHumans(),'color'=>'green']);
                        });
                        \App\Models\Category::latest()->take(2)->get()->each(function($c) use (&$activities) {
                            $activities->push(['type'=>'category','text'=>"Categoría añadida: <strong>{$c->name}</strong>",'time'=>$c->created_at->diffForHumans(),'color'=>'purple']);
                        });
                        $activities = $activities->sortByDesc(function($a){ return $a['time']; })->take(8)->values();
                        @endphp

                        @forelse($activities as $act)
                        <div class="activity-item">
                            <div class="activity-dot activity-dot--{{ $act['color'] }}"></div>
                            <div class="activity-text">
                                <span>{!! $act['text'] !!}</span>
                            </div>
                            <span class="activity-time">{{ $act['time'] }}</span>
                        </div>
                        @empty
                        <div class="admin-empty">
                            <div class="admin-empty__text">No hay actividad reciente</div>
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>

        </div>{{-- /admin-content --}}
    </main>

</div>{{-- /admin-layout --}}

<script>
(function() {
    const hamburger = document.getElementById('hamburger');
    const sidebar   = document.getElementById('sidebar');
    const overlay   = document.getElementById('sidebarOverlay');

    function openSidebar() {
        sidebar.classList.add('open');
        overlay.classList.add('visible');
        hamburger.classList.add('open');
        document.body.style.overflow = 'hidden';
    }
    function closeSidebar() {
        sidebar.classList.remove('open');
        overlay.classList.remove('visible');
        hamburger.classList.remove('open');
        document.body.style.overflow = '';
    }

    hamburger.addEventListener('click', function() {
        sidebar.classList.contains('open') ? closeSidebar() : openSidebar();
    });
    overlay.addEventListener('click', closeSidebar);

    // Cerrar al pulsar un link en móvil
    sidebar.querySelectorAll('.admin-sidebar__link').forEach(function(link) {
        link.addEventListener('click', function() {
            if (window.innerWidth <= 1024) closeSidebar();
        });
    });
})();
</script>

<script src="{{ asset('js/admin-alerts.js') }}"></script>
</body>
</html>
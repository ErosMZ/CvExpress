<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Plantillas — Admin CvXpress</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:opsz,wght@9..40,300;9..40,400;9..40,500;9..40,600;9..40,700&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
    <style>
        /* ── Bulk action bar ── */
        .bulk-bar {
            display: none;
            align-items: center;
            gap: .75rem;
            padding: .7rem 1rem;
            background: #1e3a5f;
            color: #fff;
            border-radius: 10px;
            margin-bottom: 1rem;
            font-size: .875rem;
            font-weight: 500;
        }
        .bulk-bar.visible { display: flex; }
        .bulk-bar__count {
            font-weight: 700;
            background: rgba(255,255,255,.15);
            padding: .2rem .65rem;
            border-radius: 999px;
            font-size: .8rem;
        }
        .bulk-bar__spacer { flex: 1; }
        .bulk-bar__btn {
            display: inline-flex;
            align-items: center;
            gap: .4rem;
            padding: .45rem 1rem;
            border-radius: 8px;
            font-size: .8rem;
            font-weight: 600;
            cursor: pointer;
            border: none;
            transition: background .15s;
        }
        .bulk-bar__btn--cancel {
            background: rgba(255,255,255,.12);
            color: #fff;
        }
        .bulk-bar__btn--cancel:hover { background: rgba(255,255,255,.2); }
        .bulk-bar__btn--delete {
            background: #ef4444;
            color: #fff;
        }
        .bulk-bar__btn--delete:hover { background: #dc2626; }

        /* ── Checkbox col ── */
        .col-check { width: 40px; padding-right: 0 !important; }
        .row-checkbox {
            width: 16px;
            height: 16px;
            accent-color: var(--blue-500, #1A56DB);
            cursor: pointer;
        }
        .template-row.selected td { background: #eff6ff; }
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
        <span class="admin-topbar__logo-text">CvXpress Admin</span>
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
            <a href="{{ route('admin.users.index') }}" class="admin-sidebar__link">
                <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                Usuarios
                <span class="admin-sidebar__link-badge">{{ \App\Models\User::count() }}</span>
            </a>
            <div class="admin-sidebar__section-label">Contenido</div>
            <a href="{{ route('templates.index') }}" class="admin-sidebar__link active">
                <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>
                Plantillas
                <span class="admin-sidebar__link-badge">{{ \App\Models\Template::count() }}</span>
            </a>
            <a href="{{ route('categories.index') }}" class="admin-sidebar__link">
                <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"/></svg>
                Categorías
                <span class="admin-sidebar__link-badge">{{ \App\Models\Category::count() }}</span>
            </a>
            <a href="{{ route('admin.plans.index') }}" class="admin-sidebar__link">
                <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
                Planes
                <span class="admin-sidebar__link-badge">{{ \App\Models\Plan::count() }}</span>
            </a>
            <a href="{{ route('admin.sites.index') }}" class="admin-sidebar__link">
                <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="2" y1="12" x2="22" y2="12"/><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/></svg>
                Webs publicadas
                <span class="admin-sidebar__link-badge">{{ \App\Models\UserPurchase::where('hosting_type','subdomain')->whereNotNull('subdomain')->count() }}</span>
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
                    <span>Plantillas</span>
                </nav>
                <h1 class="admin-header__title">Plantillas</h1>
            </div>
            <div class="admin-header__right">
                <a href="{{ route('templates.create') }}" class="btn-admin btn-admin--primary">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
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
            @if(session('error'))
                <div class="admin-alert admin-alert--error">
                    {{ session('error') }}
                </div>
            @endif

            {{-- Stats --}}
            @php
                $total   = $templates->count();
                $activas = $templates->where('is_active', true)->count();
                $premium = $templates->where('price', '>', 0)->count();
                $gratis  = $templates->where('price', '<=', 0)->count();
            @endphp
            <div class="admin-stats" style="grid-template-columns:repeat(4,1fr);margin-bottom:1.5rem;">
                <div class="stat-card stat-card--blue" style="padding:1.1rem 1.25rem;">
                    <div class="stat-card__value" style="font-size:1.75rem;">{{ $total }}</div>
                    <div class="stat-card__label">Total plantillas</div>
                </div>
                <div class="stat-card stat-card--teal" style="padding:1.1rem 1.25rem;">
                    <div class="stat-card__value" style="font-size:1.75rem;">{{ $activas }}</div>
                    <div class="stat-card__label">Activas</div>
                </div>
                <div class="stat-card stat-card--amber" style="padding:1.1rem 1.25rem;">
                    <div class="stat-card__value" style="font-size:1.75rem;">{{ $premium }}</div>
                    <div class="stat-card__label">Premium</div>
                </div>
                <div class="stat-card stat-card--purple" style="padding:1.1rem 1.25rem;">
                    <div class="stat-card__value" style="font-size:1.75rem;">{{ $gratis }}</div>
                    <div class="stat-card__label">Gratuitas</div>
                </div>
            </div>

            {{-- ── BULK ACTION BAR ── --}}
            <div class="bulk-bar" id="bulkBar">
                <span class="bulk-bar__count" id="bulkCount">0</span>
                <span>plantilla(s) seleccionada(s)</span>
                <span class="bulk-bar__spacer"></span>
                <button type="button" class="bulk-bar__btn bulk-bar__btn--cancel" onclick="clearSelection()">
                    Cancelar
                </button>
                <button type="button" class="bulk-bar__btn bulk-bar__btn--delete" onclick="confirmBulkDelete()">
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/></svg>
                    Eliminar seleccionadas
                </button>
            </div>

            {{-- Form oculto para bulk delete --}}
            <form id="bulkForm" method="POST" action="{{ route('templates.bulk-destroy') }}" style="display:none;">
                @csrf
                @method('DELETE')
                <div id="bulkIds"></div>
            </form>

            {{-- Tabla principal --}}
            <div class="admin-panel">
                <div class="admin-toolbar">
                    <div class="admin-toolbar__search">
                        <div class="admin-search">
                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                            <input type="text" id="searchInput" placeholder="Buscar plantilla...">
                        </div>
                    </div>
                    <div class="admin-toolbar__actions">
                        {{-- Filtro por categoría --}}
                        <select id="filterCategory" class="form-select" style="width:auto;padding:.5rem .875rem;font-size:.85rem;">
                            <option value="">Todas las categorías</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                            @endforeach
                        </select>

                        {{-- Filtro por plan --}}
                        <select id="filterTier" class="form-select" style="width:auto;padding:.5rem .875rem;font-size:.85rem;">
                            <option value="">Todos los planes</option>
                            @foreach(\App\Models\Template::PLAN_TIERS as $key => $tier)
                                <option value="{{ $key }}">{{ $tier['label'] }}</option>
                            @endforeach
                        </select>

                        {{-- Filtro por estado --}}
                        <select id="filterStatus" class="form-select" style="width:auto;padding:.5rem .875rem;font-size:.85rem;">
                            <option value="">Todos los estados</option>
                            <option value="active">Activas</option>
                            <option value="inactive">Inactivas</option>
                            <option value="premium">Premium</option>
                            <option value="free">Gratuitas</option>
                        </select>

                        <a href="{{ route('templates.create') }}" class="btn-admin btn-admin--primary btn-admin--sm">
                            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                            Nueva
                        </a>
                    </div>
                </div>

                @if($templates->isEmpty())
                    <div class="admin-empty" style="padding:4rem 2rem;">
                        <div class="admin-empty__icon">
                            <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                        </div>
                        <div class="admin-empty__title">No hay plantillas todavía</div>
                        <div class="admin-empty__text">Crea tu primera plantilla para que aparezca aquí</div>
                        <a href="{{ route('templates.create') }}" class="btn-admin btn-admin--primary" style="margin-top:1.25rem;">
                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                            Crear primera plantilla
                        </a>
                    </div>
                @else
                <div class="admin-table-wrap">
                    <table class="admin-table" id="templatesTable">
                        <thead>
                            <tr>
                                <th class="col-check">
                                    <input type="checkbox" class="row-checkbox" id="selectAll" title="Seleccionar todas">
                                </th>
                                <th>Plantilla</th>
                                <th>Categoría</th>
                                <th>Precio</th>
                                <th>Estado</th>
                                <th>Creada</th>
                                <th style="text-align:right;">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($templates as $template)
                            <tr class="template-row"
                                data-id="{{ $template->id }}"
                                data-name="{{ strtolower($template->name) }}"
                                data-category="{{ $template->category_id ?? '' }}"
                                data-status="{{ $template->is_active ? 'active' : 'inactive' }}"
                                data-premium="{{ $template->price > 0 ? 'premium' : 'free' }}"
                                data-tier="{{ $template->plan_tier }}">

                                <td class="col-check">
                                    <input type="checkbox" class="row-checkbox item-checkbox" value="{{ $template->id }}">
                                </td>

                                <td>
                                    <div style="display:flex;align-items:center;gap:12px;">
                                        @if($template->preview_image)
                                            <img src="{{ asset('storage/' . $template->preview_image) }}"
                                                 alt="{{ $template->name }}"
                                                 style="width:48px;height:36px;object-fit:cover;border-radius:6px;border:1px solid var(--admin-border);flex-shrink:0;">
                                        @else
                                            <div style="width:48px;height:36px;border-radius:6px;background:var(--blue-50);border:1px solid var(--blue-100);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="var(--blue-300)" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                                            </div>
                                        @endif
                                        <div>
                                            <div style="font-weight:600;font-size:.9rem;line-height:1.2;color:var(--admin-text);">{{ $template->name }}</div>
                                            @if($template->description)
                                                <div style="font-size:.75rem;color:var(--admin-text-muted);margin-top:2px;max-width:220px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">
                                                    {{ $template->description }}
                                                </div>
                                            @endif
                                            <div style="font-size:.72rem;color:var(--admin-text-muted);margin-top:2px;font-family:var(--font-mono);">/{{ $template->slug }}</div>
                                        </div>
                                    </div>
                                </td>

                                <td>
                                    @if($template->category)
                                        <span class="admin-tag">{{ $template->category->name }}</span>
                                    @else
                                        <span style="color:var(--admin-text-muted);font-size:.82rem;">—</span>
                                    @endif
                                </td>

                                <td>
                                    @if($template->price > 0)
                                        <div style="font-weight:700;color:var(--amber-600);font-size:.9rem;">€{{ number_format($template->price, 2) }}</div>
                                    @else
                                        <div style="font-weight:600;color:var(--green-600);font-size:.9rem;">Gratis</div>
                                    @endif
                                    @php $tier = \App\Models\Template::PLAN_TIERS[$template->plan_tier] ?? null; @endphp
                                    @if($tier)
                                        <span style="display:inline-block;margin-top:4px;font-size:.7rem;font-weight:700;padding:2px 7px;border-radius:99px;background:{{ $tier['bg'] }};color:{{ $tier['color'] }};">
                                            {{ $tier['label'] }}
                                        </span>
                                    @endif
                                </td>

                                <td>
                                    @if($template->is_active)
                                        <span class="badge-status badge-status--active">Activa</span>
                                    @else
                                        <span class="badge-status badge-status--inactive">Inactiva</span>
                                    @endif
                                </td>

                                <td style="font-size:.8rem;color:var(--admin-text-muted);white-space:nowrap;">
                                    {{ $template->created_at->format('d M Y') }}
                                    <div style="font-size:.72rem;">{{ $template->created_at->diffForHumans() }}</div>
                                </td>

                                <td>
                                    <div class="table-actions" style="justify-content:flex-end;">
                                        <a href="{{ route('templates.edit', $template) }}"
                                           class="table-action-btn" title="Editar">
                                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                                        </a>
                                        <form action="{{ route('templates.destroy', $template) }}"
                                              method="POST"
                                              data-confirm="¿Eliminar «{{ $template->name }}»? Esta acción no se puede deshacer.">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="table-action-btn danger" title="Eliminar">
                                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/><path d="M10 11v6M14 11v6"/><path d="M9 6V4h6v2"/></svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- No results tras filtrar --}}
                <div id="noResults" style="display:none;padding:3rem 2rem;text-align:center;color:var(--admin-text-muted);">
                    <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" style="margin-bottom:.75rem;opacity:.4"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                    <div style="font-size:.9rem;font-weight:600;color:var(--admin-text);">Sin resultados</div>
                    <div style="font-size:.82rem;margin-top:.25rem;">Prueba con otros filtros</div>
                </div>

                @if(method_exists($templates, 'links'))
                <div class="admin-pagination">
                    <span style="font-size:.82rem;color:var(--admin-text-muted);">
                        Mostrando {{ $templates->firstItem() }}–{{ $templates->lastItem() }} de {{ $templates->total() }} plantillas
                    </span>
                    <div style="display:flex;gap:4px;margin-left:auto;">
                        @if($templates->onFirstPage())
                            <span class="page-btn" style="opacity:.4;cursor:default;">←</span>
                        @else
                            <a href="{{ $templates->previousPageUrl() }}" class="page-btn">←</a>
                        @endif
                        @foreach($templates->getUrlRange(1, $templates->lastPage()) as $page => $url)
                            <a href="{{ $url }}" class="page-btn {{ $templates->currentPage() === $page ? 'active' : '' }}">{{ $page }}</a>
                        @endforeach
                        @if($templates->hasMorePages())
                            <a href="{{ $templates->nextPageUrl() }}" class="page-btn">→</a>
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

<script>
(function () {
    // ── Sidebar ──
    const hamburger = document.getElementById('hamburger');
    const sidebar   = document.getElementById('sidebar');
    const overlay   = document.getElementById('sidebarOverlay');
    function openSb()  { sidebar.classList.add('open');    overlay.classList.add('visible');    hamburger.classList.add('open');    document.body.style.overflow='hidden'; }
    function closeSb() { sidebar.classList.remove('open'); overlay.classList.remove('visible'); hamburger.classList.remove('open'); document.body.style.overflow=''; }
    hamburger.addEventListener('click', () => sidebar.classList.contains('open') ? closeSb() : openSb());
    overlay.addEventListener('click', closeSb);
    sidebar.querySelectorAll('a,button[type="submit"]').forEach(el => el.addEventListener('click', () => { if(window.innerWidth<=1024) closeSb(); }));

    // ── Filters ──
    const searchInput    = document.getElementById('searchInput');
    const filterCategory = document.getElementById('filterCategory');
    const filterTier     = document.getElementById('filterTier');
    const filterStatus   = document.getElementById('filterStatus');
    const rows           = document.querySelectorAll('.template-row');
    const noResults      = document.getElementById('noResults');

    function filterTable() {
        const q    = searchInput    ? searchInput.value.toLowerCase() : '';
        const cat  = filterCategory ? filterCategory.value            : '';
        const tier = filterTier     ? filterTier.value                : '';
        const st   = filterStatus   ? filterStatus.value              : '';
        let visible = 0;

        rows.forEach(row => {
            const matchSearch   = (row.dataset.name || '').includes(q);
            const matchCategory = !cat  || row.dataset.category === cat;
            const matchTier     = !tier || row.dataset.tier     === tier;
            const matchStatus   =
                !st ||
                (st === 'active'   && row.dataset.status  === 'active')  ||
                (st === 'inactive' && row.dataset.status  === 'inactive') ||
                (st === 'premium'  && row.dataset.premium === 'premium')  ||
                (st === 'free'     && row.dataset.premium === 'free');

            const show = matchSearch && matchCategory && matchTier && matchStatus;
            row.style.display = show ? '' : 'none';
            if (show) visible++;
        });

        if (noResults) noResults.style.display = visible === 0 ? 'block' : 'none';
    }

    if (searchInput)    searchInput.addEventListener('input', filterTable);
    if (filterCategory) filterCategory.addEventListener('change', filterTable);
    if (filterTier)     filterTier.addEventListener('change', filterTable);
    if (filterStatus)   filterStatus.addEventListener('change', filterTable);

    // ── Bulk select ──
    const selectAll    = document.getElementById('selectAll');
    const bulkBar      = document.getElementById('bulkBar');
    const bulkCount    = document.getElementById('bulkCount');

    function updateBulkBar() {
        const checked = document.querySelectorAll('.item-checkbox:checked');
        const n = checked.length;
        bulkCount.textContent = n;
        bulkBar.classList.toggle('visible', n > 0);

        // Keep select-all in sync
        const total = document.querySelectorAll('.item-checkbox').length;
        selectAll.checked       = n === total && total > 0;
        selectAll.indeterminate = n > 0 && n < total;

        // Highlight selected rows
        document.querySelectorAll('.template-row').forEach(row => {
            const cb = row.querySelector('.item-checkbox');
            row.classList.toggle('selected', cb && cb.checked);
        });
    }

    if (selectAll) {
        selectAll.addEventListener('change', function () {
            document.querySelectorAll('.item-checkbox').forEach(cb => {
                // only check visible rows
                const row = cb.closest('.template-row');
                if (!row || row.style.display !== 'none') {
                    cb.checked = this.checked;
                }
            });
            updateBulkBar();
        });
    }

    document.querySelectorAll('.item-checkbox').forEach(cb => {
        cb.addEventListener('change', updateBulkBar);
    });
})();

function clearSelection() {
    document.querySelectorAll('.item-checkbox').forEach(cb => cb.checked = false);
    const selectAll = document.getElementById('selectAll');
    if (selectAll) { selectAll.checked = false; selectAll.indeterminate = false; }
    document.querySelectorAll('.template-row').forEach(r => r.classList.remove('selected'));
    document.getElementById('bulkBar').classList.remove('visible');
}

function confirmBulkDelete() {
    const checked = document.querySelectorAll('.item-checkbox:checked');
    if (!checked.length) return;

    adminConfirm(`¿Eliminar ${checked.length} plantilla(s) seleccionada(s)? Esta acción no se puede deshacer.`).then(function (ok) {
        if (!ok) return;

        const form      = document.getElementById('bulkForm');
        const container = document.getElementById('bulkIds');
        container.innerHTML = '';

        checked.forEach(cb => {
            const input = document.createElement('input');
            input.type  = 'hidden';
            input.name  = 'ids[]';
            input.value = cb.value;
            container.appendChild(input);
        });

        form.submit();
    });
}
</script>

<script src="{{ asset('js/admin-alerts.js') }}"></script>
</body>
</html>

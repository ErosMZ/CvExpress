<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Categorías — Admin CVPortfolio</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:opsz,wght@9..40,300;9..40,400;9..40,500;9..40,600;9..40,700&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
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
            <a href="{{ route('categories.index') }}" class="admin-sidebar__link active">
                <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"/></svg>
                Categorías
                <span class="admin-sidebar__link-badge">{{ \App\Models\Category::count() }}</span>
            </a>
            <a href="{{ route('admin.plans.index') }}" class="admin-sidebar__link">
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
                    <span>Categorías</span>
                </nav>
                <h1 class="admin-header__title">Categorías</h1>
            </div>
            <div class="admin-header__right">
                <a href="{{ route('categories.create') }}" class="btn-admin btn-admin--primary">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/>
                    </svg>
                    Nueva categoría
                </a>
            </div>
        </div>

        <div class="admin-content">

            @if(session('success'))
                <div class="admin-alert admin-alert--success">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink:0"><polyline points="20 6 9 17 4 12"/></svg>
                    {{ session('success') }}
                </div>
            @endif

            @php
                $total    = $categories->count();
                $activas  = $categories->where('is_active', true)->count();
                $inactivas = $categories->where('is_active', false)->count();
            @endphp
            <div class="admin-stats" style="grid-template-columns:repeat(3,1fr);margin-bottom:1.5rem;">
                <div class="stat-card stat-card--blue" style="padding:1.1rem 1.25rem;">
                    <div class="stat-card__value" style="font-size:1.75rem;">{{ $total }}</div>
                    <div class="stat-card__label">Total categorías</div>
                </div>
                <div class="stat-card stat-card--teal" style="padding:1.1rem 1.25rem;">
                    <div class="stat-card__value" style="font-size:1.75rem;">{{ $activas }}</div>
                    <div class="stat-card__label">Activas</div>
                </div>
                <div class="stat-card stat-card--purple" style="padding:1.1rem 1.25rem;">
                    <div class="stat-card__value" style="font-size:1.75rem;">{{ $inactivas }}</div>
                    <div class="stat-card__label">Inactivas</div>
                </div>
            </div>

            <div class="admin-panel">
                <div class="admin-toolbar">
                    <div class="admin-toolbar__search">
                        <div class="admin-search">
                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                            <input type="text" id="searchInput" placeholder="Buscar categoría...">
                        </div>
                    </div>
                    <div class="admin-toolbar__actions">
                        <select id="filterStatus" class="form-select" style="width:auto;padding:.5rem .875rem;font-size:.85rem;">
                            <option value="">Todos los estados</option>
                            <option value="active">Activas</option>
                            <option value="inactive">Inactivas</option>
                        </select>
                        <a href="{{ route('categories.create') }}" class="btn-admin btn-admin--primary btn-admin--sm">
                            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                            Nueva
                        </a>
                    </div>
                </div>

                @if($categories->isEmpty())
                    <div class="admin-empty" style="padding:4rem 2rem;">
                        <div class="admin-empty__icon">
                            <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"/></svg>
                        </div>
                        <div class="admin-empty__title">No hay categorías todavía</div>
                        <div class="admin-empty__text">Crea tu primera categoría para organizar las plantillas</div>
                        <a href="{{ route('categories.create') }}" class="btn-admin btn-admin--primary" style="margin-top:1.25rem;">
                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                            Crear primera categoría
                        </a>
                    </div>
                @else
                <div class="admin-table-wrap">
                    <table class="admin-table" id="categoriesTable">
                        <thead>
                            <tr>
                                <th>Categoría</th>
                                <th>Plantillas</th>
                                <th>Estado</th>
                                <th>Creada</th>
                                <th style="text-align:right;">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($categories as $category)
                            <tr class="cat-row"
                                data-name="{{ strtolower($category->name) }}"
                                data-status="{{ $category->is_active ? 'active' : 'inactive' }}">

                                <td>
                                    <div>
                                        <div style="font-weight:600;font-size:.9rem;color:var(--admin-text);">{{ $category->name }}</div>
                                        <div style="font-size:.72rem;color:var(--admin-text-muted);margin-top:2px;font-family:var(--font-mono);">/{{ $category->slug }}</div>
                                    </div>
                                </td>

                                <td>
                                    <span class="admin-tag">{{ $category->templates_count ?? $category->templates()->count() }} plantillas</span>
                                </td>

                                <td>
                                    @if($category->is_active)
                                        <span class="badge-status badge-status--active">Activa</span>
                                    @else
                                        <span class="badge-status badge-status--inactive">Inactiva</span>
                                    @endif
                                </td>

                                <td style="font-size:.8rem;color:var(--admin-text-muted);white-space:nowrap;">
                                    {{ $category->created_at->format('d M Y') }}
                                    <div style="font-size:.72rem;">{{ $category->created_at->diffForHumans() }}</div>
                                </td>

                                <td>
                                    <div class="table-actions" style="justify-content:flex-end;">
                                        <a href="{{ route('categories.edit', $category) }}"
                                           class="table-action-btn" title="Editar">
                                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                                        </a>
                                        <form action="{{ route('categories.destroy', $category) }}"
                                              method="POST"
                                              onsubmit="return confirm('¿Eliminar la categoría «{{ $category->name }}»?')">
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
                @endif
            </div>

        </div>
    </main>
</div>

<script>
(function () {
    const hamburger = document.getElementById('hamburger');
    const sidebar   = document.getElementById('sidebar');
    const overlay   = document.getElementById('sidebarOverlay');
    function openSidebar()  { sidebar.classList.add('open');    overlay.classList.add('visible');    hamburger.classList.add('open');    document.body.style.overflow='hidden'; }
    function closeSidebar() { sidebar.classList.remove('open'); overlay.classList.remove('visible'); hamburger.classList.remove('open'); document.body.style.overflow=''; }
    hamburger.addEventListener('click', () => sidebar.classList.contains('open') ? closeSidebar() : openSidebar());
    overlay.addEventListener('click', closeSidebar);

    const searchInput  = document.getElementById('searchInput');
    const filterStatus = document.getElementById('filterStatus');
    const rows         = document.querySelectorAll('.cat-row');

    function filterTable() {
        const q  = searchInput ? searchInput.value.toLowerCase() : '';
        const st = filterStatus ? filterStatus.value : '';
        rows.forEach(row => {
            const matchSearch = (row.dataset.name || '').includes(q);
            const matchStatus = !st || row.dataset.status === st;
            row.style.display = matchSearch && matchStatus ? '' : 'none';
        });
    }

    if (searchInput)  searchInput.addEventListener('input', filterTable);
    if (filterStatus) filterStatus.addEventListener('change', filterTable);
})();
</script>

</body>
</html>

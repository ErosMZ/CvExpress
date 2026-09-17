<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nueva Categoría — Admin CvXpress</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:opsz,wght@9..40,300;9..40,400;9..40,500;9..40,600;9..40,700&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
    <style>
        .form-card { background: var(--admin-surface); border: 1px solid var(--admin-border); border-radius: var(--radius-lg); overflow: hidden; }
        .form-card__header { padding: 1.1rem 1.5rem; border-bottom: 1px solid var(--admin-border); display: flex; align-items: center; gap: .6rem; }
        .form-card__header svg { color: var(--admin-text-muted); flex-shrink: 0; }
        .form-card__title { font-size: .82rem; font-weight: 700; text-transform: uppercase; letter-spacing: .07em; color: var(--admin-text-muted); }
        .form-card__body { padding: 1.5rem; display: flex; flex-direction: column; gap: 1.25rem; }
        .form-field { display: flex; flex-direction: column; gap: .4rem; }
        .form-field label { font-size: .82rem; font-weight: 600; color: var(--admin-text); }
        .form-field label .req { color: #ef4444; margin-left: 2px; }
        .form-field .hint { font-size: .73rem; color: var(--admin-text-muted); margin-top: .15rem; line-height: 1.4; }
        .form-input, .form-textarea {
            width: 100%; padding: .6rem .875rem;
            background: var(--admin-bg); border: 1px solid var(--admin-border);
            border-radius: var(--radius); font-size: .875rem; color: var(--admin-text);
            font-family: inherit; transition: border-color .15s, box-shadow .15s; outline: none;
        }
        .form-input:focus, .form-textarea:focus { border-color: var(--blue-400); box-shadow: 0 0 0 3px rgba(96,165,250,.12); }
        .form-input.is-error { border-color: #ef4444; }
        .form-textarea { resize: vertical; min-height: 80px; }
        .field-error { font-size: .75rem; color: #ef4444; margin-top: .25rem; }
        .toggle-row { display: flex; align-items: center; justify-content: space-between; gap: 1rem; }
        .toggle-info { flex: 1; }
        .toggle-label { font-size: .875rem; font-weight: 500; color: var(--admin-text); }
        .toggle-desc  { font-size: .75rem; color: var(--admin-text-muted); margin-top: 2px; }
        .toggle { position: relative; width: 40px; height: 22px; flex-shrink: 0; }
        .toggle input { opacity: 0; width: 0; height: 0; }
        .toggle-track { position: absolute; inset: 0; background: var(--admin-border); border-radius: 999px; cursor: pointer; transition: background .2s; }
        .toggle-track::after { content: ''; position: absolute; width: 16px; height: 16px; left: 3px; top: 3px; background: white; border-radius: 50%; transition: transform .2s; box-shadow: 0 1px 3px rgba(0,0,0,.2); }
        .toggle input:checked + .toggle-track { background: var(--blue-500, #1A56DB); }
        .toggle input:checked + .toggle-track::after { transform: translateX(18px); }
        .create-grid { display: grid; grid-template-columns: 1fr 300px; gap: 1.5rem; align-items: start; }
        @media (max-width: 860px) { .create-grid { grid-template-columns: 1fr; } }
    </style>
</head>
<body>

<div class="admin-topbar">
    <a href="{{ url('/') }}" class="admin-topbar__logo">
        <div class="admin-topbar__logo-mark">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="18" rx="3"/><path d="M8 7h8M8 11h5M8 15h6"/></svg>
        </div>
        <span class="admin-topbar__logo-text">CvXpress Admin</span>
    </a>
    <button class="admin-hamburger" id="hamburger" aria-label="Menú"><span></span><span></span><span></span></button>
</div>

<div class="admin-sidebar-overlay" id="sidebarOverlay"></div>

<div class="admin-layout">

    <aside class="admin-sidebar" id="sidebar">
        <div class="admin-sidebar__header">
            <a href="{{ url('/') }}" class="admin-sidebar__logo">
                <div class="admin-sidebar__logo-mark">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="18" rx="3"/><path d="M8 7h8M8 11h5M8 15h6"/></svg>
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
            <a href="{{ route('categories.create') }}" class="admin-sidebar__link active">
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

    <main class="admin-main">

        <div class="admin-header">
            <div class="admin-header__left">
                <nav class="admin-header__breadcrumb">
                    <a href="{{ route('admin') }}">Admin</a>
                    <span>/</span>
                    <a href="{{ route('categories.index') }}">Categorías</a>
                    <span>/</span>
                    <span>Nueva</span>
                </nav>
                <h1 class="admin-header__title">Nueva categoría</h1>
            </div>
            <div class="admin-header__right">
                <a href="{{ route('categories.index') }}" class="btn-admin btn-admin--ghost">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 12H5M12 5l-7 7 7 7"/></svg>
                    Volver
                </a>
            </div>
        </div>

        <div class="admin-content">

            @if($errors->any())
                <div class="admin-alert admin-alert--error" style="margin-bottom:1.25rem;">
                    <div>
                        <strong>Corrige los siguientes errores:</strong>
                        <ul style="margin:.35rem 0 0 1rem;padding:0;font-size:.82rem;">
                            @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                        </ul>
                    </div>
                </div>
            @endif

            <form action="{{ route('categories.store') }}" method="POST">
                @csrf
                <div class="create-grid">

                    {{-- Columna izquierda --}}
                    <div class="form-card">
                        <div class="form-card__header">
                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"/></svg>
                            <span class="form-card__title">Información</span>
                        </div>
                        <div class="form-card__body">
                            <div class="form-field">
                                <label for="name">Nombre <span class="req">*</span></label>
                                <input type="text" id="name" name="name"
                                    class="form-input {{ $errors->has('name') ? 'is-error' : '' }}"
                                    value="{{ old('name') }}"
                                    placeholder="Ej: Diseño, Programación, Marketing…"
                                    required autofocus>
                                <span class="hint">El slug se generará automáticamente a partir del nombre.</span>
                                @error('name') <span class="field-error">{{ $message }}</span> @enderror
                            </div>

                            <div class="form-field">
                                <label for="description">Descripción</label>
                                <textarea id="description" name="description"
                                    class="form-textarea"
                                    placeholder="Descripción opcional de la categoría…"
                                    rows="3">{{ old('description') }}</textarea>
                            </div>
                        </div>
                    </div>

                    {{-- Columna derecha --}}
                    <div style="display:flex;flex-direction:column;gap:1.25rem;">

                        <div class="form-card">
                            <div class="form-card__header">
                                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="3"/><path d="M19.07 4.93a10 10 0 0 1 0 14.14M4.93 4.93a10 10 0 0 0 0 14.14"/></svg>
                                <span class="form-card__title">Opciones</span>
                            </div>
                            <div class="form-card__body">
                                <div class="toggle-row">
                                    <div class="toggle-info">
                                        <div class="toggle-label">Activa</div>
                                        <div class="toggle-desc">Visible para los usuarios en el catálogo</div>
                                    </div>
                                    <label class="toggle">
                                        <input type="checkbox" name="is_active" {{ old('is_active', true) ? 'checked' : '' }}>
                                        <span class="toggle-track"></span>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="form-card">
                            <div class="form-card__body">
                                <div style="display:flex;flex-direction:column;gap:.75rem;">
                                    <button type="submit" class="btn-admin btn-admin--primary" style="width:100%;justify-content:center;">
                                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
                                        Guardar categoría
                                    </button>
                                    <a href="{{ route('categories.index') }}" class="btn-admin btn-admin--ghost" style="width:100%;justify-content:center;">Cancelar</a>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </form>

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
})();
</script>

<script src="{{ asset('js/admin-alerts.js') }}"></script>
</body>
</html>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nueva Plantilla — Admin CvXpress</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:opsz,wght@9..40,300;9..40,400;9..40,500;9..40,600;9..40,700&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
    <style>
        /* ── Estilos extra del formulario ── */
        .create-grid {
            display: grid;
            grid-template-columns: 1fr 320px;
            gap: 1.5rem;
            align-items: start;
        }
        .form-card {
            background: var(--admin-surface);
            border: 1px solid var(--admin-border);
            border-radius: var(--radius-lg);
            overflow: hidden;
        }
        .form-card__header {
            padding: 1.1rem 1.5rem;
            border-bottom: 1px solid var(--admin-border);
            display: flex;
            align-items: center;
            gap: .6rem;
        }
        .form-card__header svg { color: var(--admin-text-muted); flex-shrink: 0; }
        .form-card__title {
            font-size: .82rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .07em;
            color: var(--admin-text-muted);
        }
        .form-card__body { padding: 1.5rem; display: flex; flex-direction: column; gap: 1.25rem; }

        /* Campos */
        .form-field { display: flex; flex-direction: column; gap: .4rem; }
        .form-field label {
            font-size: .82rem;
            font-weight: 600;
            color: var(--admin-text);
        }
        .form-field label .req { color: #ef4444; margin-left: 2px; }
        .form-field .hint {
            font-size: .73rem;
            color: var(--admin-text-muted);
            margin-top: .15rem;
            line-height: 1.4;
        }
        .form-input,
        .form-textarea,
        .form-select-el {
            width: 100%;
            padding: .6rem .875rem;
            background: var(--admin-bg);
            border: 1px solid var(--admin-border);
            border-radius: var(--radius);
            font-size: .875rem;
            color: var(--admin-text);
            font-family: inherit;
            transition: border-color .15s, box-shadow .15s;
            outline: none;
        }
        .form-input:focus,
        .form-textarea:focus,
        .form-select-el:focus {
            border-color: var(--blue-400);
            box-shadow: 0 0 0 3px rgba(96,165,250,.12);
        }
        .form-input.is-error,
        .form-textarea.is-error,
        .form-select-el.is-error { border-color: #ef4444; }
        .form-textarea { resize: vertical; min-height: 90px; }

        /* File input */
        .file-input-wrap {
            border: 1.5px dashed var(--admin-border);
            border-radius: var(--radius);
            padding: 1.25rem;
            text-align: center;
            cursor: pointer;
            transition: border-color .15s, background .15s;
            position: relative;
        }
        .file-input-wrap:hover { border-color: var(--blue-400); background: var(--blue-50,#eff6ff); }
        .file-input-wrap input[type="file"] {
            position: absolute;
            inset: 0;
            opacity: 0;
            cursor: pointer;
            width: 100%;
            height: 100%;
        }
        .file-input-wrap__icon { margin-bottom: .5rem; color: var(--admin-text-muted); }
        .file-input-wrap__text { font-size: .82rem; color: var(--admin-text-muted); }
        .file-input-wrap__text strong { color: var(--admin-text); }
        .file-name {
            font-size: .75rem;
            color: var(--blue-600, #2563eb);
            margin-top: .35rem;
            font-family: var(--font-mono);
        }

        /* Toggle switches */
        .toggle-list { display: flex; flex-direction: column; gap: .85rem; }
        .toggle-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
        }
        .toggle-info { flex: 1; }
        .toggle-label { font-size: .875rem; font-weight: 500; color: var(--admin-text); }
        .toggle-desc { font-size: .75rem; color: var(--admin-text-muted); margin-top: 2px; }

        .toggle {
            position: relative;
            width: 40px;
            height: 22px;
            flex-shrink: 0;
        }
        .toggle input { opacity: 0; width: 0; height: 0; }
        .toggle-track {
            position: absolute;
            inset: 0;
            background: var(--admin-border);
            border-radius: 999px;
            cursor: pointer;
            transition: background .2s;
        }
        .toggle-track::after {
            content: '';
            position: absolute;
            width: 16px;
            height: 16px;
            left: 3px;
            top: 3px;
            background: white;
            border-radius: 50%;
            transition: transform .2s;
            box-shadow: 0 1px 3px rgba(0,0,0,.2);
        }
        .toggle input:checked + .toggle-track { background: var(--blue-500, #1A56DB); }
        .toggle input:checked + .toggle-track::after { transform: translateX(18px); }

        /* Price row */
        .price-input-wrap { position: relative; }
        .price-input-wrap .currency {
            position: absolute;
            left: .75rem;
            top: 50%;
            transform: translateY(-50%);
            color: var(--admin-text-muted);
            font-size: .875rem;
            pointer-events: none;
        }
        .price-input-wrap input { padding-left: 1.75rem; }

        /* Error messages */
        .field-error { font-size: .75rem; color: #ef4444; margin-top: .25rem; }

        /* Preview image display */
        .preview-img-wrap { margin-top: .5rem; display: none; }
        .preview-img-wrap img { width: 100%; border-radius: var(--radius); border: 1px solid var(--admin-border); }

        /* Actions */
        .form-actions {
            display: flex;
            gap: .75rem;
            padding-top: .25rem;
        }

        /* Plan Tier Selector */
        .plan-tier-selector {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: .5rem;
        }
        .tier-option { cursor: pointer; }
        .tier-option input { display: none; }
        .tier-option__card {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: .2rem;
            padding: .7rem .5rem;
            border: 2px solid var(--admin-border);
            border-radius: var(--radius);
            background: var(--admin-bg);
            transition: border-color .15s, box-shadow .15s;
            text-align: center;
        }
        .tier-option:hover .tier-option__card,
        .tier-option input:checked + .tier-option__card {
            border-color: var(--tier-color);
            box-shadow: 0 0 0 1px var(--tier-color);
        }
        .tier-option input:checked + .tier-option__card .tier-option__name {
            color: var(--tier-color);
        }
        .tier-option__name { font-size: .8rem; font-weight: 700; color: var(--admin-text); }
        .tier-option__price { font-size: .69rem; color: var(--admin-text-muted); font-family: var(--font-mono); }

        @media (max-width: 900px) {
            .create-grid { grid-template-columns: 1fr; }
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
            <a href="{{ url('/admin/users') }}" class="admin-sidebar__link">
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
                <span class="admin-sidebar__link-badge">{{ \App\Models\UserPurchase::where('status','active')->where('hosting_type','subdomain')->whereNotNull('subdomain')->count() }}</span>
            </a>
            <div class="admin-sidebar__section-label">Crear</div>
            <a href="{{ route('templates.create') }}" class="admin-sidebar__link active">
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
                    <a href="{{ route('templates.index') }}">Plantillas</a>
                    <span>/</span>
                    <span>Nueva</span>
                </nav>
                <h1 class="admin-header__title">Nueva plantilla</h1>
            </div>
            <div class="admin-header__right">
                <a href="{{ route('templates.index') }}" class="btn-admin btn-admin--ghost">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M19 12H5M12 5l-7 7 7 7"/>
                    </svg>
                    Volver
                </a>
            </div>
        </div>

        <div class="admin-content">

            {{-- Errores de validación --}}
            @if($errors->any())
                <div class="admin-alert admin-alert--error" style="margin-bottom:1.25rem;">
                    <div>
                        <strong>Corrige los siguientes errores:</strong>
                        <ul style="margin:.35rem 0 0 1rem;padding:0;font-size:.82rem;">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            <form
                action="{{ route('templates.store') }}"
                method="POST"
                enctype="multipart/form-data"
                id="createForm"
            >
                @csrf

                <div class="create-grid">

                    {{-- ── COLUMNA IZQUIERDA: datos principales ── --}}
                    <div style="display:flex;flex-direction:column;gap:1.25rem;">

                        {{-- Info básica --}}
                        <div class="form-card">
                            <div class="form-card__header">
                                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                                <span class="form-card__title">Información básica</span>
                            </div>
                            <div class="form-card__body">

                                <div class="form-field">
                                    <label for="name">Nombre <span class="req">*</span></label>
                                    <input
                                        type="text"
                                        id="name"
                                        name="name"
                                        class="form-input {{ $errors->has('name') ? 'is-error' : '' }}"
                                        value="{{ old('name') }}"
                                        placeholder="Ej: Modern Developer, Clean Resume…"
                                        required
                                        autofocus
                                    >
                                    @error('name')
                                        <span class="field-error">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-field">
                                    <label for="description">Descripción</label>
                                    <textarea
                                        id="description"
                                        name="description"
                                        class="form-textarea {{ $errors->has('description') ? 'is-error' : '' }}"
                                        placeholder="Describe brevemente el estilo y características de la plantilla…"
                                        rows="3"
                                    >{{ old('description') }}</textarea>
                                    @error('description')
                                        <span class="field-error">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;">
                                    <div class="form-field">
                                        <label for="category_id">Categoría</label>
                                        <select
                                            id="category_id"
                                            name="category_id"
                                            class="form-select-el {{ $errors->has('category_id') ? 'is-error' : '' }}"
                                        >
                                            <option value="">Sin categoría</option>
                                            @foreach($categories as $cat)
                                                <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>
                                                    {{ $cat->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('category_id')
                                            <span class="field-error">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="form-field">
                                        <label for="price">Precio (€)</label>
                                        <div class="price-input-wrap">
                                            <span class="currency">€</span>
                                            <input
                                                type="number"
                                                id="price"
                                                name="price"
                                                class="form-input {{ $errors->has('price') ? 'is-error' : '' }}"
                                                value="{{ old('price', '0') }}"
                                                step="0.01"
                                                min="0"
                                                placeholder="0.00"
                                            >
                                        </div>
                                        @error('price')
                                            <span class="field-error">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                            </div>
                        </div>

                        {{-- Archivos --}}
                        <div class="form-card">
                            <div class="form-card__header">
                                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
                                <span class="form-card__title">Archivos</span>
                            </div>
                            <div class="form-card__body">

                                {{-- Preview image --}}
                                <div class="form-field">
                                    <label>Imagen de vista previa</label>
                                    <div class="file-input-wrap" id="imgWrap">
                                        <input
                                            type="file"
                                            name="preview_image"
                                            accept="image/*"
                                            id="previewImageInput"
                                        >
                                        <div class="file-input-wrap__icon">
                                            <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>
                                        </div>
                                        <div class="file-input-wrap__text">
                                            <strong>Haz click o arrastra</strong> una imagen<br>
                                            PNG, JPG, WEBP — Recomendado 1280×800
                                        </div>
                                        <div class="file-name" id="imgName"></div>
                                    </div>
                                    <div class="preview-img-wrap" id="previewImgWrap">
                                        <img id="previewImgEl" src="" alt="Vista previa">
                                    </div>
                                    @error('preview_image')
                                        <span class="field-error">{{ $message }}</span>
                                    @enderror
                                </div>

                                {{-- ZIP --}}
                                <div class="form-field">
                                    <label>Archivo ZIP de la plantilla <span class="req">*</span></label>
                                    <div class="file-input-wrap {{ $errors->has('template_zip') ? 'is-error' : '' }}" id="zipWrap" style="{{ $errors->has('template_zip') ? 'border-color:#ef4444;' : '' }}">
                                        <input
                                            type="file"
                                            name="template_zip"
                                            accept=".zip"
                                            id="zipInput"
                                            required
                                        >
                                        <div class="file-input-wrap__icon">
                                            <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
                                        </div>
                                        <div class="file-input-wrap__text">
                                            <strong>Haz click o arrastra</strong> el ZIP<br>
                                            Debe contener <code style="font-size:.75rem;background:var(--admin-border);padding:.1rem .3rem;border-radius:4px;">index.html</code> en la raíz
                                        </div>
                                        <div class="file-name" id="zipName"></div>
                                    </div>
                                    @error('template_zip')
                                        <span class="field-error">{{ $message }}</span>
                                    @enderror
                                    <span class="hint">Estructura esperada: <code>index.html</code> + carpetas <code>css/</code>, <code>js/</code>, <code>img/</code> con rutas relativas.</span>
                                </div>

                            </div>
                        </div>

                    </div>

                    {{-- ── COLUMNA DERECHA: opciones y acciones ── --}}
                    <div style="display:flex;flex-direction:column;gap:1.25rem;">

                        {{-- Opciones --}}
                        <div class="form-card">
                            <div class="form-card__header">
                                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="3"/><path d="M19.07 4.93a10 10 0 0 1 0 14.14M4.93 4.93a10 10 0 0 0 0 14.14"/></svg>
                                <span class="form-card__title">Opciones</span>
                            </div>
                            <div class="form-card__body">

                                <div class="form-field">
                                    <label for="plan_tier">Plan requerido <span class="req">*</span></label>
                                    <div class="plan-tier-selector">
                                        @foreach(\App\Models\Template::PLAN_TIERS as $value => $tier)
                                        <label class="tier-option">
                                            <input type="radio" name="plan_tier" value="{{ $value }}"
                                                {{ old('plan_tier', 'basic') === $value ? 'checked' : '' }}>
                                            <span class="tier-option__card" style="--tier-color: {{ $tier['color'] }}">
                                                <span class="tier-option__name">{{ $tier['label'] }}</span>
                                                <span class="tier-option__price">
                                                    {{ isset($plansByTier[$value]) ? number_format($plansByTier[$value]->price, 2, ',', '.').'€' : '—' }}
                                                </span>
                                            </span>
                                        </label>
                                        @endforeach
                                    </div>
                                    <span class="hint">Determina en qué plan de pago aparece esta plantilla.</span>
                                </div>

                                {{-- Orientación de la foto --}}
                                <div class="form-field">
                                    <label>Orientación de la foto de perfil</label>
                                    <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:.5rem;margin-top:.25rem;">
                                        @foreach([
                                            '' => ['icon' => '⬜', 'label' => 'Sin preferencia', 'desc' => 'Cualquier formato'],
                                            'vertical' => ['icon' => '🖼️', 'label' => 'Vertical', 'desc' => 'Retrato (3:4)'],
                                            'horizontal' => ['icon' => '🖼️', 'label' => 'Horizontal', 'desc' => 'Paisaje (4:3)'],
                                        ] as $val => $opt)
                                        <label style="cursor:pointer;">
                                            <input type="radio" name="photo_orientation" value="{{ $val }}"
                                                   {{ old('photo_orientation', '') === $val ? 'checked' : '' }}
                                                   style="display:none;" class="orientation-radio">
                                            <div class="orientation-card" style="border:2px solid var(--admin-border);border-radius:var(--radius);padding:.6rem .4rem;text-align:center;transition:all .15s;background:var(--admin-bg);">
                                                <div style="font-size:{{ $val === '' ? '1.4rem' : '1rem' }};margin-bottom:.2rem;">
                                                    @if($val === 'vertical')
                                                        <svg width="18" height="24" viewBox="0 0 18 24" fill="none" stroke="currentColor" stroke-width="2" style="display:inline-block;vertical-align:middle;"><rect x="1" y="1" width="16" height="22" rx="2"/></svg>
                                                    @elseif($val === 'horizontal')
                                                        <svg width="24" height="18" viewBox="0 0 24 18" fill="none" stroke="currentColor" stroke-width="2" style="display:inline-block;vertical-align:middle;"><rect x="1" y="1" width="22" height="16" rx="2"/></svg>
                                                    @else
                                                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="8" y1="12" x2="16" y2="12"/></svg>
                                                    @endif
                                                </div>
                                                <div style="font-size:.75rem;font-weight:700;color:var(--admin-text);">{{ $opt['label'] }}</div>
                                                <div style="font-size:.67rem;color:var(--admin-text-muted);">{{ $opt['desc'] }}</div>
                                            </div>
                                        </label>
                                        @endforeach
                                    </div>
                                    <span class="hint">El usuario verá esta recomendación al subir su foto de perfil.</span>
                                </div>

                                <div class="toggle-list">

                                    <div class="toggle-row">
                                        <div class="toggle-info">
                                            <div class="toggle-label">Activa</div>
                                            <div class="toggle-desc">Visible para los usuarios</div>
                                        </div>
                                        <label class="toggle">
                                            <input type="checkbox" name="is_active" {{ old('is_active', true) ? 'checked' : '' }}>
                                            <span class="toggle-track"></span>
                                        </label>
                                    </div>

                                    <div class="toggle-row">
                                        <div class="toggle-info">
                                            <div class="toggle-label">Destacada</div>
                                            <div class="toggle-desc">Aparece en el carrusel de inicio</div>
                                        </div>
                                        <label class="toggle">
                                            <input type="checkbox" name="is_featured" {{ old('is_featured') ? 'checked' : '' }}>
                                            <span class="toggle-track"></span>
                                        </label>
                                    </div>

                                </div>
                            </div>
                        </div>

                        {{-- Botones --}}
                        <div class="form-card">
                            <div class="form-card__body">
                                <div class="form-actions" style="flex-direction:column;">
                                    <button type="submit" class="btn-admin btn-admin--primary" style="width:100%;justify-content:center;">
                                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
                                        Guardar plantilla
                                    </button>
                                    <a href="{{ route('templates.index') }}" class="btn-admin btn-admin--ghost" style="width:100%;justify-content:center;">
                                        Cancelar
                                    </a>
                                </div>
                                <p style="font-size:.73rem;color:var(--admin-text-muted);text-align:center;margin-top:.25rem;line-height:1.4;">
                                    El ZIP se extraerá automáticamente para generar la vista previa.
                                </p>
                            </div>
                        </div>

                        {{-- Guía rápida --}}
                        <div class="form-card">
                            <div class="form-card__header">
                                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                                <span class="form-card__title">Guía del ZIP</span>
                            </div>
                            <div class="form-card__body" style="gap:.6rem;">
                                <div style="font-size:.78rem;color:var(--admin-text-muted);font-family:var(--font-mono);background:var(--admin-bg);border:1px solid var(--admin-border);border-radius:var(--radius);padding:.85rem 1rem;line-height:1.8;">
                                    plantilla.zip<br>
                                    ├── index.html<br>
                                    ├── css/<br>
                                    │&nbsp;&nbsp; └── style.css<br>
                                    ├── js/<br>
                                    │&nbsp;&nbsp; └── main.js<br>
                                    └── img/<br>
                                    &nbsp;&nbsp;&nbsp;&nbsp; └── foto.jpg
                                </div>
                                <p style="font-size:.75rem;color:var(--admin-text-muted);line-height:1.5;">
                                    Usa rutas <strong>relativas</strong> en el HTML: <code style="font-size:.72rem;background:var(--admin-border);padding:.1rem .25rem;border-radius:3px;">href="css/style.css"</code> y en el CSS: <code style="font-size:.72rem;background:var(--admin-border);padding:.1rem .25rem;border-radius:3px;">url('../img/x.jpg')</code>
                                </p>
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
    // Hamburger
    const hamburger = document.getElementById('hamburger');
    const sidebar   = document.getElementById('sidebar');
    const overlay   = document.getElementById('sidebarOverlay');
    function openSidebar()  { sidebar.classList.add('open');    overlay.classList.add('visible');    hamburger.classList.add('open');    document.body.style.overflow='hidden'; }
    function closeSidebar() { sidebar.classList.remove('open'); overlay.classList.remove('visible'); hamburger.classList.remove('open'); document.body.style.overflow=''; }
    hamburger.addEventListener('click', () => sidebar.classList.contains('open') ? closeSidebar() : openSidebar());
    overlay.addEventListener('click', closeSidebar);

    // Preview image — mostrar miniatura al seleccionar
    const imgInput   = document.getElementById('previewImageInput');
    const imgName    = document.getElementById('imgName');
    const imgWrap    = document.getElementById('previewImgWrap');
    const imgEl      = document.getElementById('previewImgEl');

    imgInput.addEventListener('change', function () {
        const file = this.files[0];
        if (!file) return;
        imgName.textContent = file.name;
        const reader = new FileReader();
        reader.onload = e => {
            imgEl.src = e.target.result;
            imgWrap.style.display = 'block';
        };
        reader.readAsDataURL(file);
    });

    // ZIP — mostrar nombre del archivo
    const zipInput = document.getElementById('zipInput');
    const zipName  = document.getElementById('zipName');
    zipInput.addEventListener('change', function () {
        const file = this.files[0];
        if (file) zipName.textContent = file.name + ' (' + (file.size / 1024 / 1024).toFixed(2) + ' MB)';
    });

    // Orientación foto — resaltar seleccionada
    function updateOrientationCards() {
        document.querySelectorAll('.orientation-radio').forEach(function(radio) {
            const card = radio.nextElementSibling;
            if (radio.checked) {
                card.style.borderColor = 'var(--blue-400,#60a5fa)';
                card.style.boxShadow = '0 0 0 1px var(--blue-400,#60a5fa)';
                card.style.background = 'var(--blue-50,#eff6ff)';
            } else {
                card.style.borderColor = 'var(--admin-border)';
                card.style.boxShadow = 'none';
                card.style.background = 'var(--admin-bg)';
            }
        });
    }
    document.querySelectorAll('.orientation-radio').forEach(r => r.addEventListener('change', updateOrientationCards));
    updateOrientationCards();
})();
</script>

<script src="{{ asset('js/admin-alerts.js') }}"></script>
</body>
</html>

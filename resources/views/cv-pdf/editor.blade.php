<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>Crear CV PDF — CvXpress</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=DM+Sans:opsz,wght@9..40,300;9..40,400;9..40,500;9..40,600;9..40,700&family=Lora:ital,wght@0,400;0,600;1,400&display=swap" rel="stylesheet">
<style>
/* ═══════════════════════════════════════════════
   EDITOR SHELL
═══════════════════════════════════════════════ */
*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

:root {
  --sidebar-bg: #2D5F52;
  --accent: #2D5F52;
  --ui-bg: #F3F4F6;
  --panel-w: 360px;
  --topbar-h: 56px;
}

body {
  font-family: 'DM Sans', sans-serif;
  background: var(--ui-bg);
  height: 100dvh;
  overflow: hidden;
  display: flex;
  flex-direction: column;
}

/* ── TOP BAR ── */
.topbar {
  height: var(--topbar-h);
  background: #fff;
  border-bottom: 1px solid #E5E7EB;
  display: flex;
  align-items: center;
  padding: 0 1.25rem;
  gap: 1rem;
  flex-shrink: 0;
  z-index: 20;
}
.topbar__logo {
  font-size: .95rem;
  font-weight: 700;
  color: #111827;
  text-decoration: none;
  display: flex;
  align-items: center;
  gap: .5rem;
}
.topbar__logo span { color: #1D4ED8; }
.topbar__title {
  font-size: .85rem;
  color: #6B7280;
  font-weight: 400;
}
.topbar__sep { width: 1px; height: 20px; background: #E5E7EB; }
.topbar__colors {
  display: flex;
  align-items: center;
  gap: .45rem;
  margin-left: .5rem;
}
.topbar__colors-label {
  font-size: .75rem;
  color: #6B7280;
  font-weight: 500;
  white-space: nowrap;
}
.color-swatch {
  width: 22px; height: 22px;
  border-radius: 50%;
  cursor: pointer;
  border: 2px solid transparent;
  transition: transform 140ms, border-color 140ms;
  flex-shrink: 0;
}
.color-swatch:hover { transform: scale(1.15); }
.color-swatch.active { border-color: #111827; transform: scale(1.15); }
.topbar__spacer { flex: 1; }
.topbar__actions { display: flex; gap: .5rem; align-items: center; }
.btn-download {
  display: inline-flex; align-items: center; gap: .4rem;
  background: #1D4ED8; color: #fff;
  border: none; border-radius: 8px;
  padding: .5rem 1rem; font-size: .82rem; font-weight: 600;
  cursor: pointer; transition: background 150ms;
  font-family: inherit;
}
.btn-download:hover { background: #1e40af; }
.btn-back {
  display: inline-flex; align-items: center; gap: .4rem;
  color: #6B7280; text-decoration: none;
  font-size: .82rem; font-weight: 500;
  padding: .4rem .75rem;
  border: 1px solid #E5E7EB; border-radius: 8px;
  transition: all 140ms;
}
.btn-back:hover { background: #F9FAFB; color: #111827; }

/* ── MAIN LAYOUT ── */
.editor-body {
  flex: 1;
  display: flex;
  overflow: hidden;
}

/* ── LEFT PANEL ── */
.form-panel {
  width: var(--panel-w);
  flex-shrink: 0;
  background: #fff;
  border-right: 1px solid #E5E7EB;
  overflow-y: auto;
  display: flex;
  flex-direction: column;
}
.form-panel::-webkit-scrollbar { width: 4px; }
.form-panel::-webkit-scrollbar-thumb { background: #D1D5DB; border-radius: 4px; }

/* ── SECTIONS ── */
.fp-section {
  border-bottom: 1px solid #F3F4F6;
}
.fp-section__head {
  display: flex; align-items: center; justify-content: space-between;
  padding: .75rem 1rem;
  cursor: pointer;
  user-select: none;
  background: #fff;
  transition: background 130ms;
}
.fp-section__head:hover { background: #F9FAFB; }
.fp-section__title {
  font-size: .8rem; font-weight: 600;
  text-transform: uppercase; letter-spacing: .06em;
  color: #374151;
  display: flex; align-items: center; gap: .5rem;
}
.fp-section__icon {
  width: 24px; height: 24px; border-radius: 6px;
  display: flex; align-items: center; justify-content: center;
  font-size: .85rem;
}
.fp-section__chevron {
  color: #9CA3AF;
  transition: transform 200ms;
}
.fp-section.open .fp-section__chevron { transform: rotate(180deg); }
.fp-section__body {
  display: none;
  padding: 0 1rem 1rem;
}
.fp-section.open .fp-section__body { display: block; }

/* ── FORM ELEMENTS ── */
.fp-field { margin-bottom: .65rem; }
.fp-label {
  display: block; font-size: .72rem; font-weight: 600;
  color: #6B7280; margin-bottom: .25rem; text-transform: uppercase; letter-spacing: .04em;
}
.fp-input, .fp-textarea, .fp-select {
  width: 100%;
  padding: .5rem .7rem;
  font-family: 'DM Sans', sans-serif;
  font-size: .85rem;
  color: #111827;
  background: #F9FAFB;
  border: 1px solid #E5E7EB;
  border-radius: 7px;
  transition: border-color 150ms, background 150ms;
  outline: none;
}
.fp-input:focus, .fp-textarea:focus, .fp-select:focus {
  border-color: #93C5FD;
  background: #fff;
  box-shadow: 0 0 0 2px rgba(59,130,246,.1);
}
.fp-textarea { resize: vertical; min-height: 70px; line-height: 1.5; }
.fp-row { display: grid; grid-template-columns: 1fr 1fr; gap: .5rem; }

/* ── REPEATABLE ENTRIES ── */
.fp-entries { display: flex; flex-direction: column; gap: .75rem; }
.fp-entry {
  background: #F9FAFB;
  border: 1px solid #E5E7EB;
  border-radius: 8px;
  padding: .65rem .75rem;
  position: relative;
}
.fp-entry__remove {
  position: absolute; top: .4rem; right: .4rem;
  width: 20px; height: 20px; border-radius: 50%;
  background: #FEE2E2; border: none; cursor: pointer;
  display: flex; align-items: center; justify-content: center;
  color: #DC2626; font-size: .85rem; line-height: 1;
  transition: background 130ms;
}
.fp-entry__remove:hover { background: #FECACA; }
.fp-add-btn {
  display: flex; align-items: center; gap: .4rem;
  background: none; border: 1px dashed #D1D5DB;
  border-radius: 7px; padding: .45rem .75rem;
  font-family: 'DM Sans', sans-serif;
  font-size: .78rem; font-weight: 500; color: #6B7280;
  cursor: pointer; width: 100%; margin-top: .5rem;
  transition: all 130ms;
}
.fp-add-btn:hover { border-color: #93C5FD; color: #1D4ED8; background: #EFF6FF; }

/* ── EDIT TOGGLE BUTTON ── */
.btn-edit-toggle {
  display: inline-flex; align-items: center; gap: .4rem;
  color: #374151; background: #F3F4F6; border: 1px solid #E5E7EB;
  border-radius: 8px; padding: .45rem .85rem;
  font-size: .82rem; font-weight: 500; cursor: pointer;
  font-family: inherit; transition: all 140ms;
}
.btn-edit-toggle:hover { background: #E5E7EB; color: #111827; }
.btn-edit-toggle.is-hidden-panel { background: #EFF6FF; color: #1D4ED8; border-color: #BFDBFE; }

/* ── AI IMPROVE BUTTON ── */
.ai-improve-wrap { position: relative; }
.ai-improve-btn {
  position: absolute;
  top: 0; right: 0;
  display: inline-flex; align-items: center; gap: 4px;
  background: linear-gradient(135deg, #7C3AED 0%, #4f46e5 100%);
  color: #fff;
  border: none;
  border-radius: 0 7px 0 8px;
  padding: 4px 7px 4px 8px;
  cursor: pointer;
  font-family: inherit;
  white-space: nowrap;
  overflow: hidden;
  max-width: 26px;
  transition: max-width 220ms cubic-bezier(.4,0,.2,1), opacity 140ms;
  opacity: .85;
}
.ai-improve-btn:hover:not(:disabled) {
  max-width: 120px;
  opacity: 1;
}
.ai-improve-btn:disabled {
  opacity: .35;
  cursor: not-allowed;
  background: #9CA3AF;
}
.ai-improve-btn__label {
  font-size: .68rem; font-weight: 600;
  overflow: hidden;
  max-width: 0;
  transition: max-width 200ms cubic-bezier(.4,0,.2,1) 40ms;
  white-space: nowrap;
}
.ai-improve-btn:hover:not(:disabled) .ai-improve-btn__label { max-width: 90px; }
.ai-improve-btn__icon { flex-shrink: 0; }
.ai-improve-btn.is-loading .ai-improve-btn__icon { animation: ai-spin .7s linear infinite; }
@keyframes ai-spin { to { transform: rotate(360deg); } }

/* Photo upload */
.fp-photo-wrap {
  display: flex; align-items: center; gap: .75rem; margin-bottom: .65rem;
}
.fp-photo-preview {
  width: 52px; height: 52px; border-radius: 50%;
  background: #E5E7EB;
  object-fit: cover; object-position: center;
  border: 2px solid #D1D5DB;
  flex-shrink: 0;
}
.fp-photo-btn {
  display: inline-flex; align-items: center; gap: .35rem;
  font-family: 'DM Sans', sans-serif; font-size: .78rem; font-weight: 500;
  color: #1D4ED8; background: #EFF6FF; border: 1px solid #BFDBFE;
  border-radius: 6px; padding: .35rem .65rem; cursor: pointer;
  transition: background 130ms;
}
.fp-photo-btn:hover { background: #DBEAFE; }

/* ── RIGHT: PREVIEW ── */
.preview-panel {
  flex: 1;
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: flex-start;
  padding: 1.5rem;
  overflow-y: auto;
  background: #E5E7EB;
  gap: 1rem;
}
.preview-panel::-webkit-scrollbar { width: 6px; }
.preview-panel::-webkit-scrollbar-thumb { background: #9CA3AF; border-radius: 4px; }

.preview-scale-wrap {
  transform-origin: top center;
  width: 794px;
}

/* ══════════════════════════════════════════════
   CV TEMPLATE
══════════════════════════════════════════════ */
/* Página genérica del CV */
.cv-page {
  width: 794px;
  background: #fff;
  font-family: 'Lora', Georgia, serif;
  font-size: 13px;
  line-height: 1.55;
  color: #1a1a1a;
  box-shadow: 0 4px 32px rgba(0,0,0,.18);
  /* block layout: sidebar absolute + main con margin */
  display: block;
  position: relative;
  height: 1122px;
  overflow: hidden;
}

/* Hueco visual entre páginas (solo editor) */
.cv-page-gap {
  width: 794px;
  height: 28px;
  background: #E5E7EB;
}

/* LEFT SIDEBAR — absoluta para no afectar la altura del contenido principal */
.cv-sidebar {
  position: absolute;
  top: 0; left: 0;
  width: 252px; height: 100%;
  background: var(--sidebar-bg);
  color: #fff;
  padding: 36px 22px 36px;
  display: flex;
  flex-direction: column;
  gap: 22px;
  overflow: hidden;
  box-sizing: border-box;
}
/* Sidebar página 2: mismo estilo que la principal, contenido dinámico */
.cv-sidebar-p2 { }

/* Photo */
.cv-photo-wrap {
  display: flex;
  justify-content: center;
  margin-bottom: 4px;
}
.cv-photo {
  width: 140px; height: 140px;
  border-radius: 50%;
  object-fit: cover; object-position: center top;
  border: 4px solid rgba(255,255,255,.4);
  background: rgba(255,255,255,.15);
}
.cv-photo-placeholder {
  width: 140px; height: 140px; border-radius: 50%;
  background: rgba(255,255,255,.15);
  border: 4px solid rgba(255,255,255,.4);
  display: flex; align-items: center; justify-content: center;
  font-size: 40px; color: rgba(255,255,255,.5);
}

/* Sidebar sections */
.cv-sb-section { }
.cv-sb-title {
  font-family: 'DM Sans', sans-serif;
  font-size: 10px; font-weight: 700;
  text-transform: uppercase; letter-spacing: .12em;
  color: rgba(255,255,255,.6);
  border-bottom: 1px solid rgba(255,255,255,.2);
  padding-bottom: 5px;
  margin-bottom: 9px;
}
.cv-sb-item {
  display: flex; align-items: flex-start; gap: 6px;
  margin-bottom: 6px; font-size: 12.5px; color: rgba(255,255,255,.9);
  font-family: 'DM Sans', sans-serif;
}
.cv-sb-item svg { flex-shrink: 0; margin-top: 1px; opacity: .7; }
.cv-sb-skill {
  font-family: 'DM Sans', sans-serif;
  font-size: 12.5px; color: rgba(255,255,255,.9);
  padding: 3px 0; border-bottom: 1px solid rgba(255,255,255,.1);
  margin-bottom: 2px;
}
.cv-sb-skill:last-child { border-bottom: none; }
.cv-lang-row {
  display: flex; justify-content: space-between; align-items: center;
  font-family: 'DM Sans', sans-serif; font-size: 12.5px; color: rgba(255,255,255,.9);
  margin-bottom: 5px;
}
.cv-lang-level {
  font-size: 10.5px; opacity: .65;
  background: rgba(255,255,255,.15); border-radius: 3px; padding: 1px 6px;
}
.cv-ref-name {
  font-family: 'DM Sans', sans-serif; font-size: 12.5px; font-weight: 600;
  color: rgba(255,255,255,.95); margin-bottom: 1px;
}
.cv-ref-detail {
  font-family: 'DM Sans', sans-serif; font-size: 11px; color: rgba(255,255,255,.65);
}

/* MAIN CONTENT — offset de la sidebar, altura natural para medir en redistributePages() */
.cv-main {
  margin-left: 252px;
  overflow: hidden;
  padding: 36px 30px 36px;
  display: flex;
  flex-direction: column;
  gap: 20px;
}

/* Name block */
.cv-name-block { flex-shrink: 0; }
.cv-name {
  font-family: 'Lora', serif;
  font-size: 34px; font-weight: 600;
  color: var(--accent);
  line-height: 1.15;
  margin-bottom: 4px;
}
.cv-profession {
  font-family: 'DM Sans', sans-serif;
  font-size: 13px; font-weight: 700;
  text-transform: uppercase; letter-spacing: .14em;
  color: #374151;
}

/* Sections */
.cv-section { flex-shrink: 0; }
.cv-section-title {
  font-family: 'DM Sans', sans-serif;
  font-size: 11px; font-weight: 700;
  text-transform: uppercase; letter-spacing: .12em;
  color: var(--accent);
  border-bottom: 1.5px solid var(--accent);
  padding-bottom: 4px;
  margin-bottom: 10px;
  opacity: .9;
}
.cv-profile-text {
  font-size: 12.5px; color: #374151; line-height: 1.7;
  font-family: 'DM Sans', sans-serif;
}

/* Experience */
.cv-exp-item { margin-bottom: 13px; }
.cv-exp-item:last-child { margin-bottom: 0; }
.cv-exp-company {
  font-family: 'Lora', serif; font-size: 13.5px; font-weight: 600;
  color: var(--accent); margin-bottom: 1px;
}
.cv-exp-meta {
  font-family: 'DM Sans', sans-serif; font-size: 11px;
  color: #9CA3AF; margin-bottom: 3px;
  display: flex; gap: 6px; align-items: center;
}
.cv-exp-role {
  font-family: 'DM Sans', sans-serif; font-size: 12.5px; font-weight: 600;
  color: #1F2937; margin-bottom: 3px;
}
.cv-exp-desc {
  font-family: 'DM Sans', sans-serif; font-size: 12px; color: #4B5563;
  line-height: 1.65;
}
.cv-exp-desc ul { padding-left: 13px; }
.cv-exp-desc li { margin-bottom: 2px; }

/* Education */
.cv-edu-item { margin-bottom: 12px; display: flex; gap: 14px; }
.cv-edu-item:last-child { margin-bottom: 0; }
.cv-edu-date {
  font-family: 'DM Sans', sans-serif; font-size: 11px; color: #9CA3AF;
  min-width: 88px; padding-top: 1px;
}
.cv-edu-content { flex: 1; }
.cv-edu-degree {
  font-family: 'DM Sans', sans-serif; font-size: 12.5px; font-weight: 600;
  color: #1F2937; margin-bottom: 1px;
}
.cv-edu-school {
  font-family: 'DM Sans', sans-serif; font-size: 12px; color: #6B7280;
}

/* empty state */
.cv-empty {
  font-family: 'DM Sans', sans-serif; font-size: 8.5px;
  color: rgba(255,255,255,.35); font-style: italic;
}
.cv-empty-main {
  font-family: 'DM Sans', sans-serif; font-size: 8.5px;
  color: #D1D5DB; font-style: italic;
}
</style>
</head>
<body>

<!-- ── TOP BAR ── -->
<header class="topbar">
  <a href="{{ route('dashboard') }}" class="btn-back">
    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M19 12H5M12 5l-7 7 7 7"/></svg>
    Volver
  </a>
  <div class="topbar__sep"></div>
  <span class="topbar__logo">Cv<span>X</span>press</span>
  <span class="topbar__title">Editor de CV PDF</span>
  <div class="topbar__sep"></div>

  <!-- Color swatches -->
  <div class="topbar__colors">
    <span class="topbar__colors-label">Color:</span>
    <button class="color-swatch active" data-color="#2D5F52" style="background:#2D5F52;" title="Verde bosque"></button>
    <button class="color-swatch" data-color="#1E3A5F" style="background:#1E3A5F;" title="Azul marino"></button>
    <button class="color-swatch" data-color="#7C3AED" style="background:#7C3AED;" title="Morado"></button>
    <button class="color-swatch" data-color="#B91C1C" style="background:#B91C1C;" title="Rojo burdeos"></button>
    <button class="color-swatch" data-color="#374151" style="background:#374151;" title="Carbón"></button>
    <button class="color-swatch" data-color="#0F766E" style="background:#0F766E;" title="Teal"></button>
  </div>

  <div class="topbar__spacer"></div>
  <div class="topbar__actions">
    <button class="btn-edit-toggle" onclick="clearAll()" title="Borrar todos los datos del formulario">
      <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/><path d="M10 11v6M14 11v6"/><path d="M9 6V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2"/></svg>
      Limpiar datos
    </button>
    <button class="btn-download" onclick="window.print()">
      <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="12" y1="12" x2="12" y2="18"/><polyline points="9 15 12 18 15 15"/></svg>
      Descargar PDF
    </button>
  </div>
</header>

<!-- ── EDITOR BODY ── -->
<div class="editor-body">

  <!-- ══ LEFT FORM PANEL ══ -->
  <aside class="form-panel" id="form-panel">

    {{-- DATOS PERSONALES --}}
    <div class="fp-section open" data-section="personal">
      <div class="fp-section__head" onclick="toggleSection(this)">
        <span class="fp-section__title">
          <span class="fp-section__icon" style="background:#EFF6FF;">👤</span>
          Datos personales
        </span>
        <svg class="fp-section__chevron" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="6 9 12 15 18 9"/></svg>
      </div>
      <div class="fp-section__body">
        {{-- Photo --}}
        <div class="fp-photo-wrap">
          <img id="photo-preview" class="fp-photo-preview" src="" alt="" style="display:none;">
          <div id="photo-placeholder" style="width:52px;height:52px;border-radius:50%;background:#E5E7EB;display:flex;align-items:center;justify-content:center;font-size:20px;flex-shrink:0;">👤</div>
          <label class="fp-photo-btn" for="photo-input">
            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>
            Subir foto
          </label>
          <input type="file" id="photo-input" accept="image/*" style="display:none;" onchange="handlePhoto(this)">
        </div>
        <div class="fp-row">
          <div class="fp-field">
            <label class="fp-label">Nombre</label>
            <input class="fp-input" id="f-nombre" type="text" placeholder="Santiago" oninput="renderCv()">
          </div>
          <div class="fp-field">
            <label class="fp-label">Apellidos</label>
            <input class="fp-input" id="f-apellidos" type="text" placeholder="García" oninput="renderCv()">
          </div>
        </div>
        <div class="fp-field">
          <label class="fp-label">Profesión / Título</label>
          <input class="fp-input" id="f-profesion" type="text" placeholder="Contador Público" oninput="renderCv()">
        </div>
      </div>
    </div>

    {{-- CONTACTO --}}
    <div class="fp-section open" data-section="contacto">
      <div class="fp-section__head" onclick="toggleSection(this)">
        <span class="fp-section__title">
          <span class="fp-section__icon" style="background:#F0FDF4;">📞</span>
          Contacto
        </span>
        <svg class="fp-section__chevron" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="6 9 12 15 18 9"/></svg>
      </div>
      <div class="fp-section__body">
        <div class="fp-field">
          <label class="fp-label">Teléfono</label>
          <input class="fp-input" id="f-telefono" type="text" placeholder="+34 600 000 000" oninput="renderCv()">
        </div>
        <div class="fp-field">
          <label class="fp-label">Email</label>
          <input class="fp-input" id="f-email" type="email" placeholder="correo@email.com" oninput="renderCv()">
        </div>
        <div class="fp-field">
          <label class="fp-label">Ubicación</label>
          <input class="fp-input" id="f-ubicacion" type="text" placeholder="Madrid, España" oninput="renderCv()">
        </div>
        <div class="fp-field">
          <label class="fp-label">LinkedIn</label>
          <input class="fp-input" id="f-linkedin" type="text" placeholder="linkedin.com/in/tu-perfil" oninput="validateLinkedin(this); renderCv()">
          <span id="linkedin-err" style="display:none;font-size:.68rem;color:#ef4444;margin-top:3px;display:none;">
            Formato incorrecto. Ej: linkedin.com/in/tu-nombre
          </span>
        </div>
        <div class="fp-field">
          <label class="fp-label">Portfolio / Web</label>
          <input class="fp-input" id="f-portfolio" type="text" placeholder="miportfolio.com" oninput="renderCv()">
        </div>
      </div>
    </div>

    {{-- PERFIL --}}
    <div class="fp-section open" data-section="perfil">
      <div class="fp-section__head" onclick="toggleSection(this)">
        <span class="fp-section__title">
          <span class="fp-section__icon" style="background:#FEF3C7;">📝</span>
          Perfil profesional
        </span>
        <svg class="fp-section__chevron" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="6 9 12 15 18 9"/></svg>
      </div>
      <div class="fp-section__body">
        <div class="fp-field">
          <label class="fp-label">Descripción</label>
          <div class="ai-improve-wrap">
            <textarea class="fp-textarea" id="f-perfil" placeholder="Breve descripción profesional..." oninput="renderCv(); updateAiBtn()" rows="4"></textarea>
            <button class="ai-improve-btn" id="ai-improve-btn" onclick="improveProfile()" title="Mejorar con IA" disabled>
              <span class="ai-improve-btn__label">Mejorar con IA</span>
              <svg class="ai-improve-btn__icon" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2L15.09 8.26L22 9.27L17 14.14L18.18 21.02L12 17.77L5.82 21.02L7 14.14L2 9.27L8.91 8.26L12 2Z"/></svg>
            </button>
          </div>
        </div>
      </div>
    </div>

    {{-- EXPERIENCIA --}}
    <div class="fp-section open" data-section="experiencia">
      <div class="fp-section__head" onclick="toggleSection(this)">
        <span class="fp-section__title">
          <span class="fp-section__icon" style="background:#EFF6FF;">💼</span>
          Experiencia laboral
        </span>
        <svg class="fp-section__chevron" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="6 9 12 15 18 9"/></svg>
      </div>
      <div class="fp-section__body">
        <div class="fp-entries" id="exp-entries"></div>
        <button class="fp-add-btn" onclick="addEntry('exp')">
          <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
          Añadir experiencia
        </button>
      </div>
    </div>

    {{-- EDUCACIÓN --}}
    <div class="fp-section" data-section="educacion">
      <div class="fp-section__head" onclick="toggleSection(this)">
        <span class="fp-section__title">
          <span class="fp-section__icon" style="background:#F5F3FF;">🎓</span>
          Educación
        </span>
        <svg class="fp-section__chevron" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="6 9 12 15 18 9"/></svg>
      </div>
      <div class="fp-section__body">
        <div class="fp-entries" id="edu-entries"></div>
        <button class="fp-add-btn" onclick="addEntry('edu')">
          <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
          Añadir educación
        </button>
      </div>
    </div>

    {{-- IDIOMAS --}}
    <div class="fp-section" data-section="idiomas">
      <div class="fp-section__head" onclick="toggleSection(this)">
        <span class="fp-section__title">
          <span class="fp-section__icon" style="background:#ECFDF5;">🌍</span>
          Idiomas
        </span>
        <svg class="fp-section__chevron" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="6 9 12 15 18 9"/></svg>
      </div>
      <div class="fp-section__body">
        <div class="fp-entries" id="lang-entries"></div>
        <button class="fp-add-btn" onclick="addEntry('lang')">
          <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
          Añadir idioma
        </button>
      </div>
    </div>

    {{-- HABILIDADES --}}
    <div class="fp-section" data-section="habilidades">
      <div class="fp-section__head" onclick="toggleSection(this)">
        <span class="fp-section__title">
          <span class="fp-section__icon" style="background:#FFF7ED;">⚡</span>
          Habilidades
        </span>
        <svg class="fp-section__chevron" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="6 9 12 15 18 9"/></svg>
      </div>
      <div class="fp-section__body">
        <div class="fp-field">
          <label class="fp-label">Habilidades (una por línea)</label>
          <textarea class="fp-textarea" id="f-habilidades" placeholder="Trabajo en equipo&#10;Análisis Financiero&#10;Gestión de cuentas" oninput="renderCv()" rows="5"></textarea>
        </div>
      </div>
    </div>

    {{-- REFERENCIAS --}}
    <div class="fp-section" data-section="referencias">
      <div class="fp-section__head" onclick="toggleSection(this)">
        <span class="fp-section__title">
          <span class="fp-section__icon" style="background:#FDF2F8;">⭐</span>
          Referencias
        </span>
        <svg class="fp-section__chevron" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="6 9 12 15 18 9"/></svg>
      </div>
      <div class="fp-section__body">
        <div class="fp-entries" id="ref-entries"></div>
        <button class="fp-add-btn" onclick="addEntry('ref')">
          <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
          Añadir referencia
        </button>
      </div>
    </div>

    {{-- CERTIFICACIONES --}}
    <div class="fp-section" data-section="certificaciones">
      <div class="fp-section__head" onclick="toggleSection(this)">
        <span class="fp-section__title">
          <span class="fp-section__icon" style="background:#FFF1F2;">🏅</span>
          Certificaciones
        </span>
        <svg class="fp-section__chevron" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="6 9 12 15 18 9"/></svg>
      </div>
      <div class="fp-section__body">
        <div class="fp-entries" id="cert-entries"></div>
        <button class="fp-add-btn" onclick="addEntry('cert')">
          <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
          Añadir certificación
        </button>
      </div>
    </div>

    {{-- PROYECTOS --}}
    <div class="fp-section" data-section="proyectos">
      <div class="fp-section__head" onclick="toggleSection(this)">
        <span class="fp-section__title">
          <span class="fp-section__icon" style="background:#F0FDF4;">🚀</span>
          Proyectos
        </span>
        <svg class="fp-section__chevron" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="6 9 12 15 18 9"/></svg>
      </div>
      <div class="fp-section__body">
        <div class="fp-entries" id="proj-entries"></div>
        <button class="fp-add-btn" onclick="addEntry('proj')">
          <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
          Añadir proyecto
        </button>
      </div>
    </div>

  </aside>

  <!-- ══ RIGHT PREVIEW PANEL ══ -->
  <main class="preview-panel" id="preview-panel">
    <div class="preview-scale-wrap" id="preview-scale-wrap">
      <!-- PÁGINA 1 (sidebar + main) -->
      <div class="cv-page" id="cv-page-1">

        <!-- SIDEBAR -->
        <aside class="cv-sidebar" id="cv-sidebar">
          <div class="cv-photo-wrap">
            <img id="cv-photo-img" class="cv-photo" src="" alt="" style="display:none;">
            <div id="cv-photo-placeholder" class="cv-photo-placeholder">👤</div>
          </div>
          <div class="cv-sb-section" id="cv-sb-contacto">
            <div class="cv-sb-title">Contacto</div>
            <div id="cv-sb-contacto-items"><div class="cv-empty">Sin datos de contacto</div></div>
          </div>
          <div class="cv-sb-section" id="cv-sb-idiomas" style="display:none;">
            <div class="cv-sb-title">Idiomas</div>
            <div id="cv-sb-idiomas-items"></div>
          </div>
          <div class="cv-sb-section" id="cv-sb-habilidades" style="display:none;">
            <div class="cv-sb-title">Habilidades</div>
            <div id="cv-sb-habilidades-items"></div>
          </div>
          <div class="cv-sb-section" id="cv-sb-referencias" style="display:none;">
            <div class="cv-sb-title">Referencias</div>
            <div id="cv-sb-referencias-items"></div>
          </div>
          <div class="cv-sb-section" id="cv-sb-cert" style="display:none;">
            <div class="cv-sb-title">Certificaciones</div>
            <div id="cv-sb-cert-items"></div>
          </div>
        </aside>

        <!-- MAIN PÁGINA 1 -->
        <main class="cv-main" id="cv-main-p1">
          <div class="cv-name-block">
            <div class="cv-name" id="cv-nombre">Tu Nombre</div>
            <div class="cv-profession" id="cv-profesion">Profesión</div>
          </div>
          <div class="cv-section" id="cv-sec-perfil" style="display:none;">
            <div class="cv-section-title">Perfil</div>
            <p class="cv-profile-text" id="cv-perfil-text"></p>
          </div>
          <div class="cv-section" id="cv-sec-exp" style="display:none;">
            <div class="cv-section-title">Experiencia Laboral</div>
            <div id="cv-exp-list"></div>
          </div>
          <div class="cv-section" id="cv-sec-edu" style="display:none;">
            <div class="cv-section-title">Educación</div>
            <div id="cv-edu-list"></div>
          </div>
          <div class="cv-section" id="cv-sec-proj" style="display:none;">
            <div class="cv-section-title">Proyectos</div>
            <div id="cv-proj-list"></div>
          </div>
        </main>

      </div><!-- /cv-page-1 -->

      <!-- HUECO ENTRE PÁGINAS (solo editor, oculto en print) -->
      <div class="cv-page-gap" id="cv-page-gap" style="display:none;"></div>

      <!-- PÁGINA 2 (sidebar decorativa + main) — secciones prepopuladas, vaciadas por defecto -->
      <div class="cv-page" id="cv-page-2" style="display:none;">
        <aside class="cv-sidebar cv-sidebar-p2" id="cv-sidebar-p2">
          <div class="cv-sb-section" id="cv-sb-idiomas-p2"    style="display:none;"><div class="cv-sb-title">Idiomas</div><div id="cv-sb-idiomas-items-p2"></div></div>
          <div class="cv-sb-section" id="cv-sb-habilidades-p2" style="display:none;"><div class="cv-sb-title">Habilidades</div><div id="cv-sb-habilidades-items-p2"></div></div>
          <div class="cv-sb-section" id="cv-sb-referencias-p2" style="display:none;"><div class="cv-sb-title">Referencias</div><div id="cv-sb-referencias-items-p2"></div></div>
          <div class="cv-sb-section" id="cv-sb-cert-p2"        style="display:none;"><div class="cv-sb-title">Certificaciones</div><div id="cv-sb-cert-items-p2"></div></div>
        </aside>
        <main class="cv-main" id="cv-main-p2">
          <div class="cv-section" id="cv-sec-exp-p2"  style="display:none;">
            <div class="cv-section-title">Experiencia Laboral</div>
            <div id="cv-exp-list-p2"></div>
          </div>
          <div class="cv-section" id="cv-sec-edu-p2"  style="display:none;">
            <div class="cv-section-title">Educación</div>
            <div id="cv-edu-list-p2"></div>
          </div>
          <div class="cv-section" id="cv-sec-proj-p2" style="display:none;">
            <div class="cv-section-title">Proyectos</div>
            <div id="cv-proj-list-p2"></div>
          </div>
        </main>
      </div>
    </div>
  </main>

</div><!-- /editor-body -->

<!-- Project card styles -->
<style>
.cv-proj-item { margin-bottom: 14px; display: flex; gap: 11px; align-items: flex-start; min-width: 0; overflow: hidden; }
.cv-proj-img  { width: 78px; height: 56px; border-radius: 5px; object-fit: cover; flex-shrink: 0; border: 1px solid #E5E7EB; }
.cv-proj-content { flex: 1; min-width: 0; overflow: hidden; }
.cv-proj-name { font-family: 'DM Sans', sans-serif; font-size: 13px; font-weight: 700; color: var(--accent); margin-bottom: 2px; word-break: break-word; overflow-wrap: anywhere; }
.cv-proj-desc { font-family: 'DM Sans', sans-serif; font-size: 12px; color: #4B5563; line-height: 1.55; margin-bottom: 4px; word-break: break-word; overflow-wrap: anywhere; }
.cv-proj-link {
  display: inline-flex; align-items: center; gap: 4px;
  font-family: 'DM Sans', sans-serif; font-size: 10.5px;
  color: var(--accent); text-decoration: none;
  border: 1px solid currentColor; border-radius: 4px;
  padding: 2px 8px;
}
.fp-photo-btn {
  display: inline-flex; align-items: center; gap: 5px;
  padding: 5px 10px; border-radius: 6px; cursor: pointer;
  font-size: .72rem; font-weight: 500;
  background: #F3F4F6; color: #374151;
  border: 1px solid #D1D5DB;
  transition: background .15s;
}
.fp-photo-btn:hover { background: #E5E7EB; }

/* preview-scale-wrap aloja múltiples páginas en columna */
.preview-scale-wrap { display: flex; flex-direction: column; align-items: flex-start; }
</style>

<!-- Print styles -->
<style>
@media print {
  * {
    -webkit-print-color-adjust: exact !important;
    print-color-adjust: exact !important;
    color-adjust: exact !important;
  }
  body { overflow: visible !important; height: auto !important; }
  .topbar, .form-panel { display: none !important; }
  .editor-body { display: block !important; overflow: visible !important; }
  .preview-panel { background: none !important; padding: 0 !important; overflow: visible !important; }
  .preview-scale-wrap { transform: none !important; width: 794px !important; }

  /* Quitar sombra en print */
  .cv-page { box-shadow: none !important; }

  /* Salto de página */
  #cv-page-1 { break-after: page !important; page-break-after: always !important; }
  #cv-page-2 { break-before: page !important; page-break-before: always !important; }

  /* Ocultar hueco visual en print */
  .cv-page-gap { display: none !important; }

  /* Evitar cortes a mitad de elemento */
  .cv-exp-item, .cv-edu-item, .cv-proj-item {
    break-inside: avoid !important;
    page-break-inside: avoid !important;
  }

  @page { margin: 0; size: A4; }
}
</style>

<script>
/* ══════════════════════════════════════════════
   STATE
══════════════════════════════════════════════ */
var STORAGE_KEY = 'cvxpress_pdf_editor_v1';

var state = {
  photo: null,
  exp: [],
  edu: [],
  lang: [],
  ref: [],
  cert: [],
  proj: []
};

function saveToStorage() {
  var fields = {};
  ['f-nombre','f-apellidos','f-profesion','f-telefono','f-email','f-ubicacion','f-linkedin','f-portfolio','f-perfil','f-habilidades'].forEach(function(id) {
    var el = document.getElementById(id);
    if (el) fields[id] = el.value;
  });
  var activeSwatch = document.querySelector('.color-swatch.active');
  try {
    localStorage.setItem(STORAGE_KEY, JSON.stringify({
      fields: fields,
      entries: { exp: state.exp, edu: state.edu, lang: state.lang, ref: state.ref, cert: state.cert, proj: state.proj },
      photo: state.photo,
      color: activeSwatch ? activeSwatch.dataset.color : null
    }));
  } catch(e) {}
}

function loadFromStorage() {
  var raw = localStorage.getItem(STORAGE_KEY);
  if (!raw) return false;
  try {
    var saved = JSON.parse(raw);
    // Fields
    if (saved.fields) {
      Object.keys(saved.fields).forEach(function(id) {
        var el = document.getElementById(id);
        if (el) el.value = saved.fields[id] || '';
      });
    }
    // Color
    if (saved.color) {
      document.querySelectorAll('.color-swatch').forEach(function(sw) {
        sw.classList.toggle('active', sw.dataset.color === saved.color);
      });
      document.documentElement.style.setProperty('--sidebar-bg', saved.color);
      document.documentElement.style.setProperty('--accent', saved.color);
    }
    // Photo
    if (saved.photo) {
      state.photo = saved.photo;
      var prev = document.getElementById('photo-preview');
      var ph   = document.getElementById('photo-placeholder');
      if (prev) { prev.src = state.photo; prev.style.display = 'block'; }
      if (ph)   ph.style.display = 'none';
    }
    // Entries
    if (saved.entries) {
      ['exp','edu','lang','ref','cert','proj'].forEach(function(type) {
        if (saved.entries[type] && saved.entries[type].length) {
          state[type] = saved.entries[type];
          renderEntries(type);
        }
      });
    }
    return true;
  } catch(e) { return false; }
}

/* ══════════════════════════════════════════════
   SECTION TOGGLE
══════════════════════════════════════════════ */
function toggleSection(head) {
  head.closest('.fp-section').classList.toggle('open');
}

/* ══════════════════════════════════════════════
   COLOR SWATCHES
══════════════════════════════════════════════ */
document.querySelectorAll('.color-swatch').forEach(function(sw) {
  sw.addEventListener('click', function() {
    document.querySelectorAll('.color-swatch').forEach(function(s){ s.classList.remove('active'); });
    sw.classList.add('active');
    var color = sw.dataset.color;
    document.documentElement.style.setProperty('--sidebar-bg', color);
    document.documentElement.style.setProperty('--accent', color);
  });
});

/* ══════════════════════════════════════════════
   PHOTO
══════════════════════════════════════════════ */
function handlePhoto(input) {
  if (!input.files || !input.files[0]) return;
  var reader = new FileReader();
  reader.onload = function(e) {
    state.photo = e.target.result;
    // Form preview
    var prev = document.getElementById('photo-preview');
    var ph = document.getElementById('photo-placeholder');
    prev.src = state.photo; prev.style.display = 'block'; ph.style.display = 'none';
    renderCv();
  };
  reader.readAsDataURL(input.files[0]);
}

/* ══════════════════════════════════════════════
   ENTRY BUILDERS
══════════════════════════════════════════════ */
function addEntry(type) {
  var id = Date.now();
  state[type].push({ id: id });
  renderEntries(type);
  renderCv();
}

function removeEntry(type, id) {
  state[type] = state[type].filter(function(e){ return e.id !== id; });
  renderEntries(type);
  renderCv();
}

function renderEntries(type) {
  var container = document.getElementById(type + '-entries');
  container.innerHTML = '';
  state[type].forEach(function(entry) {
    var div = document.createElement('div');
    div.className = 'fp-entry';
    div.innerHTML = entryHTML(type, entry.id);
    container.appendChild(div);
    // Restore values
    Object.keys(entry).forEach(function(k) {
      if (k === 'id' || k === 'foto') return;
      var el = div.querySelector('[data-key="' + k + '"]');
      if (el) el.value = entry[k] || '';
    });
    // Restore project photo preview
    if (type === 'proj' && entry.foto) {
      var preview = div.querySelector('.proj-photo-preview');
      if (preview) { preview.src = entry.foto; preview.style.display = 'block'; }
    }
  });
}

function entryHTML(type, id) {
  var rm = '<button class="fp-entry__remove" onclick="removeEntry(\'' + type + '\',' + id + ')" title="Eliminar">×</button>';
  if (type === 'exp') return rm +
    '<div class="fp-field"><label class="fp-label">Empresa</label><input class="fp-input" data-key="empresa" type="text" placeholder="Empresa S.A." oninput="saveEntry(\'exp\',' + id + ',this)"></div>' +
    '<div class="fp-field"><label class="fp-label">Cargo</label><input class="fp-input" data-key="cargo" type="text" placeholder="Cargo" oninput="saveEntry(\'exp\',' + id + ',this)"></div>' +
    '<div class="fp-row">' +
      '<div class="fp-field"><label class="fp-label">Desde</label><input class="fp-input" data-key="desde" type="text" placeholder="Ene. 2022" oninput="saveEntry(\'exp\',' + id + ',this)"></div>' +
      '<div class="fp-field"><label class="fp-label">Hasta</label><input class="fp-input" data-key="hasta" type="text" placeholder="Presente" oninput="saveEntry(\'exp\',' + id + ',this)"></div>' +
    '</div>' +
    '<div class="fp-field"><label class="fp-label">Descripción</label><textarea class="fp-textarea" data-key="desc" placeholder="Responsabilidades y logros..." oninput="saveEntry(\'exp\',' + id + ',this)" rows="3"></textarea></div>';

  if (type === 'edu') return rm +
    '<div class="fp-field"><label class="fp-label">Título / Grado</label><input class="fp-input" data-key="titulo" type="text" placeholder="Grado en Contabilidad" oninput="saveEntry(\'edu\',' + id + ',this)"></div>' +
    '<div class="fp-field"><label class="fp-label">Institución</label><input class="fp-input" data-key="centro" type="text" placeholder="Universidad Ejemplo" oninput="saveEntry(\'edu\',' + id + ',this)"></div>' +
    '<div class="fp-row">' +
      '<div class="fp-field"><label class="fp-label">Desde</label><input class="fp-input" data-key="desde" type="text" placeholder="2018" oninput="saveEntry(\'edu\',' + id + ',this)"></div>' +
      '<div class="fp-field"><label class="fp-label">Hasta</label><input class="fp-input" data-key="hasta" type="text" placeholder="2022" oninput="saveEntry(\'edu\',' + id + ',this)"></div>' +
    '</div>' +
    '<div class="fp-field"><label class="fp-label">Descripción <span style="font-weight:400;opacity:.65;">(opcional)</span></label><textarea class="fp-textarea" data-key="desc" placeholder="Especialización, logros, actividades..." oninput="saveEntry(\'edu\',' + id + ',this)" rows="2"></textarea></div>';

  if (type === 'lang') return rm +
    '<div class="fp-row">' +
      '<div class="fp-field"><label class="fp-label">Idioma</label><input class="fp-input" data-key="idioma" type="text" placeholder="Inglés" oninput="saveEntry(\'lang\',' + id + ',this)"></div>' +
      '<div class="fp-field"><label class="fp-label">Nivel</label><input class="fp-input" data-key="nivel" type="text" placeholder="C1 Avanzado" oninput="saveEntry(\'lang\',' + id + ',this)"></div>' +
    '</div>';

  if (type === 'ref') return rm +
    '<div class="fp-field"><label class="fp-label">Nombre</label><input class="fp-input" data-key="nombre" type="text" placeholder="John Smith" oninput="saveEntry(\'ref\',' + id + ',this)"></div>' +
    '<div class="fp-row">' +
      '<div class="fp-field"><label class="fp-label">Cargo</label><input class="fp-input" data-key="cargo" type="text" placeholder="Director" oninput="saveEntry(\'ref\',' + id + ',this)"></div>' +
      '<div class="fp-field"><label class="fp-label">Empresa</label><input class="fp-input" data-key="empresa" type="text" placeholder="Empresa" oninput="saveEntry(\'ref\',' + id + ',this)"></div>' +
    '</div>' +
    '<div class="fp-field"><label class="fp-label">Email</label><input class="fp-input" data-key="email" type="email" placeholder="email@empresa.com" oninput="saveEntry(\'ref\',' + id + ',this)"></div>';

  if (type === 'cert') return rm +
    '<div class="fp-field"><label class="fp-label">Nombre / Título</label><input class="fp-input" data-key="nombre" type="text" placeholder="AWS Cloud Practitioner" oninput="saveEntry(\'cert\',' + id + ',this)"></div>' +
    '<div class="fp-row">' +
      '<div class="fp-field"><label class="fp-label">Entidad</label><input class="fp-input" data-key="entidad" type="text" placeholder="Amazon, Google..." oninput="saveEntry(\'cert\',' + id + ',this)"></div>' +
      '<div class="fp-field"><label class="fp-label">Año</label><input class="fp-input" data-key="año" type="text" placeholder="2024" oninput="saveEntry(\'cert\',' + id + ',this)"></div>' +
    '</div>';

  if (type === 'proj') return rm +
    '<div class="fp-field"><label class="fp-label">Nombre del proyecto</label><input class="fp-input" data-key="nombre" type="text" placeholder="Mi aplicación" oninput="saveEntry(\'proj\',' + id + ',this)"></div>' +
    '<div class="fp-field"><label class="fp-label">Descripción</label><textarea class="fp-textarea" data-key="desc" placeholder="Descripción del proyecto..." oninput="saveEntry(\'proj\',' + id + ',this)" rows="2"></textarea></div>' +
    '<div class="fp-field"><label class="fp-label">Enlace <span style="font-weight:400;opacity:.65;">(opcional)</span></label><input class="fp-input" data-key="enlace" type="text" placeholder="miproyecto.com" oninput="saveEntry(\'proj\',' + id + ',this)"></div>' +
    '<div class="fp-field"><label class="fp-label">Imagen <span style="font-weight:400;opacity:.65;">(opcional)</span></label>' +
      '<div style="display:flex;align-items:center;gap:.6rem;">' +
        '<img class="proj-photo-preview" style="display:none;width:46px;height:34px;object-fit:cover;border-radius:4px;border:1px solid #E5E7EB;" src="" alt="">' +
        '<label class="fp-photo-btn" for="proj-photo-' + id + '">' +
          '<svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>' +
          'Subir imagen' +
        '</label>' +
        '<input type="file" id="proj-photo-' + id + '" accept="image/*" style="display:none;" onchange="saveProjPhoto(' + id + ',this)">' +
      '</div>' +
    '</div>';

  return '';
}

function saveProjPhoto(id, input) {
  if (!input.files || !input.files[0]) return;
  var reader = new FileReader();
  reader.onload = function(e) {
    var entry = state.proj.find(function(p) { return p.id === id; });
    if (entry) {
      entry.foto = e.target.result;
      var wrap = input.closest('.fp-entry');
      var preview = wrap ? wrap.querySelector('.proj-photo-preview') : null;
      if (preview) { preview.src = e.target.result; preview.style.display = 'block'; }
      renderCv();
    }
  };
  reader.readAsDataURL(input.files[0]);
}

function saveEntry(type, id, input) {
  var entry = state[type].find(function(e){ return e.id === id; });
  if (entry) entry[input.dataset.key] = input.value;
  renderCv();
}

/* ══════════════════════════════════════════════
   RENDER CV
══════════════════════════════════════════════ */
function esc(s) {
  return (s || '').replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;');
}

function renderCv() {
  var nombre    = document.getElementById('f-nombre').value.trim();
  var apellidos = document.getElementById('f-apellidos').value.trim();
  var profesion = document.getElementById('f-profesion').value.trim();
  var perfil    = document.getElementById('f-perfil').value.trim();
  var tel       = document.getElementById('f-telefono').value.trim();
  var email     = document.getElementById('f-email').value.trim();
  var ubic      = document.getElementById('f-ubicacion').value.trim();
  var linkedin  = document.getElementById('f-linkedin').value.trim();
  var portfolio = document.getElementById('f-portfolio').value.trim();
  var habs      = document.getElementById('f-habilidades').value.trim();

  // Name
  var fullName = [nombre, apellidos].filter(Boolean).join(' ');
  document.getElementById('cv-nombre').textContent = fullName || 'Tu Nombre';
  document.getElementById('cv-profesion').textContent = profesion || 'Profesión';
  document.title = fullName ? fullName + ' — CvXpress' : 'CvXpress';

  // Photo
  var photoImg = document.getElementById('cv-photo-img');
  var photoPlaceholder = document.getElementById('cv-photo-placeholder');
  if (state.photo) {
    photoImg.src = state.photo; photoImg.style.display = 'block'; photoPlaceholder.style.display = 'none';
  } else {
    photoImg.style.display = 'none'; photoPlaceholder.style.display = 'flex';
  }

  // Contacto sidebar
  var contactHtml = '';
  if (tel) contactHtml += '<div class="cv-sb-item"><svg width="9" height="9" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.99 12a19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 3.9 1.24h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L8.09 8.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0 1 22 16.92z"/></svg><span>' + esc(tel) + '</span></div>';
  if (email) contactHtml += '<div class="cv-sb-item"><svg width="9" height="9" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg><span>' + esc(email) + '</span></div>';
  if (ubic) contactHtml += '<div class="cv-sb-item"><svg width="9" height="9" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg><span>' + esc(ubic) + '</span></div>';
  if (linkedin) {
    var liUrl = /^https?:\/\//i.test(linkedin) ? linkedin : 'https://' + linkedin;
    contactHtml += '<div class="cv-sb-item"><svg width="9" height="9" viewBox="0 0 24 24" fill="currentColor"><path d="M16 8a6 6 0 0 1 6 6v7h-4v-7a2 2 0 0 0-2-2 2 2 0 0 0-2 2v7h-4v-7a6 6 0 0 1 6-6z"/><rect x="2" y="9" width="4" height="12"/><circle cx="4" cy="4" r="2"/></svg><a href="' + liUrl + '" target="_blank" style="color:inherit;text-decoration:underline;text-decoration-color:rgba(255,255,255,.45);text-underline-offset:2px;">LinkedIn</a></div>';
  }
  if (portfolio) {
    var portUrl = /^https?:\/\//i.test(portfolio) ? portfolio : 'https://' + portfolio;
    contactHtml += '<div class="cv-sb-item"><svg width="9" height="9" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="2" y1="12" x2="22" y2="12"/><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/></svg><a href="' + portUrl + '" target="_blank" style="color:inherit;text-decoration:underline;text-decoration-color:rgba(255,255,255,.45);text-underline-offset:2px;">Portfolio</a></div>';
  }
  document.getElementById('cv-sb-contacto-items').innerHTML = contactHtml || '<div class="cv-empty">Sin datos de contacto</div>';

  // Perfil
  var secPerfil = document.getElementById('cv-sec-perfil');
  if (perfil) {
    secPerfil.style.display = '';
    document.getElementById('cv-perfil-text').textContent = perfil;
  } else { secPerfil.style.display = 'none'; }

  // Idiomas
  var langSec = document.getElementById('cv-sb-idiomas');
  var langItems = document.getElementById('cv-sb-idiomas-items');
  if (state.lang.length) {
    langSec.style.display = '';
    langItems.innerHTML = state.lang.map(function(l) {
      if (!l.idioma) return '';
      return '<div class="cv-lang-row"><span>' + esc(l.idioma) + '</span><span class="cv-lang-level">' + esc(l.nivel || '') + '</span></div>';
    }).join('');
  } else { langSec.style.display = 'none'; }

  // Habilidades
  var habSec = document.getElementById('cv-sb-habilidades');
  var habItems = document.getElementById('cv-sb-habilidades-items');
  if (habs) {
    habSec.style.display = '';
    habItems.innerHTML = habs.split('\n').filter(Boolean).map(function(h) {
      return '<div class="cv-sb-skill">' + esc(h.trim()) + '</div>';
    }).join('');
  } else { habSec.style.display = 'none'; }

  // Referencias
  var refSec = document.getElementById('cv-sb-referencias');
  var refItems = document.getElementById('cv-sb-referencias-items');
  if (state.ref.length && state.ref.some(function(r){ return r.nombre; })) {
    refSec.style.display = '';
    refItems.innerHTML = state.ref.map(function(r) {
      if (!r.nombre) return '';
      return '<div style="margin-bottom:8px;">' +
        '<div class="cv-ref-name">' + esc(r.nombre) + '</div>' +
        (r.cargo ? '<div class="cv-ref-detail">' + esc(r.cargo) + (r.empresa ? ' · ' + esc(r.empresa) : '') + '</div>' : '') +
        (r.email ? '<div class="cv-ref-detail">' + esc(r.email) + '</div>' : '') +
        '</div>';
    }).join('');
  } else { refSec.style.display = 'none'; }

  // Experiencia
  var expSec = document.getElementById('cv-sec-exp');
  var expList = document.getElementById('cv-exp-list');
  if (state.exp.length && state.exp.some(function(e){ return e.empresa || e.cargo; })) {
    expSec.style.display = '';
    expList.innerHTML = state.exp.map(function(e) {
      if (!e.empresa && !e.cargo) return '';
      var descLines = (e.desc || '').split('\n').filter(Boolean);
      return '<div class="cv-exp-item">' +
        (e.empresa ? '<div class="cv-exp-company">' + esc(e.empresa) + '</div>' : '') +
        '<div class="cv-exp-meta">' +
          (e.cargo ? '<span>' + esc(e.cargo) + '</span>' : '') +
          ((e.desde || e.hasta) ? '<span style="opacity:.5;">|</span><span>' + [e.desde, e.hasta].filter(Boolean).join(' – ') + '</span>' : '') +
        '</div>' +
        (descLines.length ? '<div class="cv-exp-desc"><ul>' + descLines.map(function(l){ return '<li>' + esc(l) + '</li>'; }).join('') + '</ul></div>' : '') +
        '</div>';
    }).join('');
  } else { expSec.style.display = 'none'; }

  // Educación
  var eduSec = document.getElementById('cv-sec-edu');
  var eduList = document.getElementById('cv-edu-list');
  if (state.edu.length && state.edu.some(function(e){ return e.titulo || e.centro; })) {
    eduSec.style.display = '';
    eduList.innerHTML = state.edu.map(function(e) {
      if (!e.titulo && !e.centro) return '';
      var eduDescLines = (e.desc || '').split('\n').filter(Boolean);
      return '<div class="cv-edu-item">' +
        '<div class="cv-edu-date">' + esc([e.desde, e.hasta].filter(Boolean).join(' – ')) + '</div>' +
        '<div class="cv-edu-content">' +
          (e.titulo ? '<div class="cv-edu-degree">' + esc(e.titulo) + '</div>' : '') +
          (e.centro ? '<div class="cv-edu-school">' + esc(e.centro) + '</div>' : '') +
          (eduDescLines.length ? '<div class="cv-exp-desc" style="margin-top:3px;"><ul>' + eduDescLines.map(function(l){ return '<li>' + esc(l) + '</li>'; }).join('') + '</ul></div>' : '') +
        '</div>' +
        '</div>';
    }).join('');
  } else { eduSec.style.display = 'none'; }

  // Certificaciones
  var certSec = document.getElementById('cv-sb-cert');
  var certItems = document.getElementById('cv-sb-cert-items');
  if (state.cert.length && state.cert.some(function(c){ return c.nombre; })) {
    certSec.style.display = '';
    certItems.innerHTML = state.cert.map(function(c) {
      if (!c.nombre) return '';
      return '<div style="margin-bottom:7px;">' +
        '<div class="cv-ref-name">' + esc(c.nombre) + '</div>' +
        (c.entidad || c.año ? '<div class="cv-ref-detail">' + [c.entidad, c.año].filter(Boolean).map(function(s){ return esc(s); }).join(' · ') + '</div>' : '') +
        '</div>';
    }).join('');
  } else { certSec.style.display = 'none'; }

  // Proyectos
  var projSec = document.getElementById('cv-sec-proj');
  var projList = document.getElementById('cv-proj-list');
  if (state.proj.length && state.proj.some(function(p){ return p.nombre; })) {
    projSec.style.display = '';
    projList.innerHTML = state.proj.map(function(p) {
      if (!p.nombre) return '';
      var linkUrl = p.enlace ? (/^https?:\/\//i.test(p.enlace) ? p.enlace : 'https://' + p.enlace) : '';
      return '<div class="cv-proj-item">' +
        (p.foto ? '<img src="' + p.foto + '" class="cv-proj-img" alt="">' : '') +
        '<div class="cv-proj-content">' +
          '<div class="cv-proj-name">' + esc(p.nombre) + '</div>' +
          (p.desc ? '<div class="cv-proj-desc">' + esc(p.desc) + '</div>' : '') +
          (linkUrl ? '<a href="' + linkUrl + '" target="_blank" class="cv-proj-link"><svg width="9" height="9" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/><polyline points="15 3 21 3 21 9"/><line x1="10" y1="14" x2="21" y2="3"/></svg>Ver proyecto</a>' : '') +
        '</div>' +
        '</div>';
    }).join('');
  } else { projSec.style.display = 'none'; }

  saveToStorage();
  redistributePages();
}

// Secciones de columna principal con sus réplicas en página 2
var _P2_SECS = [
  { sec1: 'cv-sec-exp',  list1: 'cv-exp-list',  sec2: 'cv-sec-exp-p2',  list2: 'cv-exp-list-p2'  },
  { sec1: 'cv-sec-edu',  list1: 'cv-edu-list',  sec2: 'cv-sec-edu-p2',  list2: 'cv-edu-list-p2'  },
  { sec1: 'cv-sec-proj', list1: 'cv-proj-list', sec2: 'cv-sec-proj-p2', list2: 'cv-proj-list-p2' }
];

// Secciones de sidebar con sus réplicas en la sidebar de página 2
var _SB_SECS = [
  { sec1: 'cv-sb-idiomas',    list1: 'cv-sb-idiomas-items',    sec2: 'cv-sb-idiomas-p2',    list2: 'cv-sb-idiomas-items-p2'    },
  { sec1: 'cv-sb-habilidades',list1: 'cv-sb-habilidades-items',sec2: 'cv-sb-habilidades-p2',list2: 'cv-sb-habilidades-items-p2' },
  { sec1: 'cv-sb-referencias', list1: 'cv-sb-referencias-items',sec2: 'cv-sb-referencias-p2',list2: 'cv-sb-referencias-items-p2' },
  { sec1: 'cv-sb-cert',       list1: 'cv-sb-cert-items',       sec2: 'cv-sb-cert-p2',       list2: 'cv-sb-cert-items-p2'       }
];

function redistributePages() {
  var page2 = document.getElementById('cv-page-2');
  var pgap  = document.getElementById('cv-page-gap');
  var main1 = document.getElementById('cv-main-p1');
  if (!page2 || !pgap || !main1) return;

  // ── 1. RESET: vaciar listas de página 2 (renderCv ya refrescó página 1 con innerHTML=)
  _P2_SECS.forEach(function(s) {
    var l2 = document.getElementById(s.list2);
    var s2 = document.getElementById(s.sec2);
    if (l2) l2.innerHTML = '';
    if (s2) s2.style.display = 'none';
  });

  // ── 2. FORCE LAYOUT: el navegador recalcula posiciones AHORA (sin pintar) ─
  void main1.offsetHeight;

  // ── 3. MEDIR Y REDISTRIBUIR ──────────────────────────────────────────────
  var GAP   = 20;   // flex gap entre secciones en cv-main
  var AVAIL = 1050; // 1122px A4 - 36 padding-top - 36 padding-bottom

  var nameBlock = main1.querySelector('.cv-name-block');
  var used = nameBlock ? nameBlock.offsetHeight : 0;

  // Contabilizar perfil (sección sin lista de ítems, siempre en página 1)
  var secPerfil = document.getElementById('cv-sec-perfil');
  if (secPerfil && window.getComputedStyle(secPerfil).display !== 'none') {
    used += (used > 0 ? GAP : 0) + secPerfil.offsetHeight;
  }

  var hasP2 = false;

  _P2_SECS.forEach(function(s) {
    var sec1  = document.getElementById(s.sec1);
    var list1 = document.getElementById(s.list1);
    var sec2  = document.getElementById(s.sec2);
    var list2 = document.getElementById(s.list2);
    if (!sec1 || window.getComputedStyle(sec1).display === 'none') return;

    var g      = used > 0 ? GAP : 0;
    var secH   = sec1.offsetHeight;
    var avail  = AVAIL - used - g;

    // ── Sección cabe entera ────────────────────────────────────────────────
    if (avail >= secH) { used += g + secH; return; }

    // ── Sección no cabe entera: división a nivel de ítem ──────────────────
    var titleEl = sec1.querySelector('.cv-section-title');
    var titleH  = titleEl ? titleEl.offsetHeight + 10 : 0; // +10 bottom margin aprox.
    var items   = Array.from(list1.children);

    if (avail < titleH + 20 || items.length === 0) {
      // No hay espacio ni para el título + 1 ítem: mover todo a página 2
      while (list1.firstChild) list2.appendChild(list1.firstChild);
      sec1.style.display = 'none';
      if (list2.children.length > 0) { sec2.style.display = ''; hasP2 = true; }
      return;
    }

    // Buscar cuántos ítems caben bajo el título
    var spaceForItems = avail - titleH;
    var usedItems = 0;
    var splitAt = 0;

    for (var i = 0; i < items.length; i++) {
      var ih = items[i].offsetHeight + (i > 0 ? 14 : 0); // 14px gap entre ítems
      if (usedItems + ih > spaceForItems) { splitAt = i; break; }
      usedItems += ih;
      splitAt = i + 1; // todos los vistos hasta aquí caben
    }

    if (splitAt === 0) {
      // Ningún ítem cabe: mover todo
      while (list1.firstChild) list2.appendChild(list1.firstChild);
      sec1.style.display = 'none';
    } else if (splitAt < items.length) {
      // Algunos ítems caben, el resto pasa a página 2
      for (var j = splitAt; j < items.length; j++) list2.appendChild(items[j]);
    }

    if (list2.children.length > 0) { sec2.style.display = ''; hasP2 = true; }
    used += g + sec1.offsetHeight; // altura actualizada (solo ítems que quedaron)
  });

  // ── 4. REDISTRIBUIR SIDEBAR ─────────────────────────────────────────────
  var sidebar1 = document.getElementById('cv-sidebar');

  // Reset sidebar p2
  _SB_SECS.forEach(function(s) {
    var l2  = document.getElementById(s.list2);
    var s2  = document.getElementById(s.sec2);
    var s1  = document.getElementById(s.sec1);
    if (l2) l2.innerHTML = '';
    if (s2) s2.style.display = 'none';
    // Restaurar sección sidebar p1 si la habíamos ocultado
    if (s1 && s1.dataset.sbHidden) { s1.style.display = ''; delete s1.dataset.sbHidden; }
  });

  if (sidebar1) {
    void sidebar1.offsetHeight; // force layout de sidebar

    var SB_GAP   = 22;  // gap entre secciones de sidebar
    var SB_AVAIL = 1050; // 1122 - 36 top - 36 bottom

    // Contar foto + contacto primero (sin redistribuir)
    var sbFixed = sidebar1.querySelector('.cv-photo-wrap');
    var sbUsed  = sbFixed ? sbFixed.offsetHeight : 0;
    var sbContacto = sidebar1.querySelector('#cv-sb-contacto');
    if (sbContacto && window.getComputedStyle(sbContacto).display !== 'none') {
      sbUsed += (sbUsed > 0 ? SB_GAP : 0) + sbContacto.offsetHeight;
    }

    _SB_SECS.forEach(function(s) {
      var sec1  = document.getElementById(s.sec1);
      var list1 = document.getElementById(s.list1);
      var sec2  = document.getElementById(s.sec2);
      var list2 = document.getElementById(s.list2);
      if (!sec1 || window.getComputedStyle(sec1).display === 'none') return;

      var g     = sbUsed > 0 ? SB_GAP : 0;
      var secH  = sec1.offsetHeight;
      var avail = SB_AVAIL - sbUsed - g;

      // Sección cabe entera
      if (avail >= secH) { sbUsed += g + secH; return; }

      // No cabe: intentar split a nivel de ítem
      var titleEl = sec1.querySelector('.cv-sb-title');
      var titleH  = titleEl ? titleEl.offsetHeight + 9 : 0;
      var items   = Array.from(list1 ? list1.children : []);

      if (avail < titleH + 16 || items.length === 0) {
        // Mover toda la sección a sidebar p2
        while (list1 && list1.firstChild) list2.appendChild(list1.firstChild);
        sec1.dataset.sbHidden = '1';
        sec1.style.display = 'none';
        if (list2.children.length > 0) { sec2.style.display = ''; hasP2 = true; }
        return;
      }

      var spaceForItems = avail - titleH;
      var usedItems = 0;
      var splitAt   = 0;

      for (var i = 0; i < items.length; i++) {
        var ih = items[i].offsetHeight + (i > 0 ? 2 : 0);
        if (usedItems + ih > spaceForItems) { splitAt = i; break; }
        usedItems += ih;
        splitAt = i + 1;
      }

      if (splitAt === 0) {
        while (list1.firstChild) list2.appendChild(list1.firstChild);
        sec1.dataset.sbHidden = '1';
        sec1.style.display = 'none';
      } else if (splitAt < items.length) {
        for (var j = splitAt; j < items.length; j++) list2.appendChild(items[j]);
      }

      if (list2.children.length > 0) { sec2.style.display = ''; hasP2 = true; }
      sbUsed += g + sec1.offsetHeight;
    });
  }

  // ── 5. MOSTRAR / OCULTAR página 2 ───────────────────────────────────────
  page2.style.display = hasP2 ? 'block' : 'none';
  pgap.style.display  = hasP2 ? 'block' : 'none';
}

/* ══════════════════════════════════════════════
   CLEAR ALL
══════════════════════════════════════════════ */
function clearAll() {
  if (!confirm('¿Seguro que quieres borrar todos los datos del CV?')) return;

  // Limpiar campos de texto
  ['f-nombre','f-apellidos','f-profesion','f-telefono','f-email','f-ubicacion','f-linkedin','f-portfolio','f-perfil','f-habilidades'].forEach(function(id) {
    var el = document.getElementById(id);
    if (el) el.value = '';
  });

  // Limpiar estado
  state.photo = null;
  state.exp   = [];
  state.edu   = [];
  state.lang  = [];
  state.ref   = [];
  state.cert  = [];
  state.proj  = [];

  // Limpiar foto
  var prev = document.getElementById('photo-preview');
  var ph   = document.getElementById('photo-placeholder');
  if (prev) { prev.src = ''; prev.style.display = 'none'; }
  if (ph)   ph.style.display = 'flex';

  // Limpiar listas de entradas
  ['exp','edu','lang','ref','cert','proj'].forEach(function(type) {
    var container = document.getElementById(type + '-entries');
    if (container) container.innerHTML = '';
  });

  // Limpiar localStorage
  localStorage.removeItem(STORAGE_KEY);

  updateAiBtn();
  renderCv();
}

/* ══════════════════════════════════════════════
   LINKEDIN VALIDATION
══════════════════════════════════════════════ */
function validateLinkedin(input) {
  var val = input.value.trim();
  var err = document.getElementById('linkedin-err');
  if (!val) {
    input.style.borderColor = '';
    if (err) err.style.display = 'none';
    return true;
  }
  var pattern = /^(https?:\/\/)?(www\.)?linkedin\.com\/in\/[\w\-\.]+\/?$/i;
  var valid = pattern.test(val);
  input.style.borderColor = valid ? '#10b981' : '#ef4444';
  input.style.boxShadow   = valid
    ? '0 0 0 2px rgba(16,185,129,.12)'
    : '0 0 0 2px rgba(239,68,68,.12)';
  if (err) err.style.display = valid ? 'none' : 'block';
  return valid;
}

/* ══════════════════════════════════════════════
   AI IMPROVE PROFILE
══════════════════════════════════════════════ */
function updateAiBtn() {
  var btn  = document.getElementById('ai-improve-btn');
  var text = document.getElementById('f-perfil').value.trim();
  btn.disabled = text.length === 0;
}

function improveProfile() {
  var btn      = document.getElementById('ai-improve-btn');
  var textarea = document.getElementById('f-perfil');
  var text     = textarea.value.trim();
  if (!text || btn.disabled) return;

  btn.classList.add('is-loading');
  btn.disabled = true;

  fetch('{{ route("cv-pdf.improve-profile") }}', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
      'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
    },
    body: JSON.stringify({ text: text })
  })
  .then(function(r) {
    if (!r.ok) throw new Error('HTTP ' + r.status);
    return r.json();
  })
  .then(function(data) {
    if (data.text) {
      textarea.value = data.text;
      renderCv();
    }
  })
  .catch(function(err) { console.error('improveProfile error:', err); })
  .finally(function() {
    btn.classList.remove('is-loading');
    btn.disabled = false;
    updateAiBtn();
  });
}

/* ══════════════════════════════════════════════
   SCALE PREVIEW TO FIT
══════════════════════════════════════════════ */
function scalePreview() {
  var panel = document.getElementById('preview-panel');
  var wrap  = document.getElementById('preview-scale-wrap');
  var available = panel.clientWidth - 48;
  var scale = Math.min(1, available / 794);
  wrap.style.transform = 'scale(' + scale + ')';
  wrap.style.marginBottom = ((scale - 1) * 1122) + 'px';
}

window.addEventListener('resize', scalePreview);
scalePreview();

// Init: restore from storage or start with empty entries
var _restored = loadFromStorage();
if (!_restored) {
  addEntry('exp');
  addEntry('edu');
  addEntry('lang');
}
renderCv();
updateAiBtn();
var _liInput = document.getElementById('f-linkedin');
if (_liInput && _liInput.value) validateLinkedin(_liInput);
</script>

</body>
</html>

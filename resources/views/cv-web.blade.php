@extends('layouts.app')

@section('title', 'CV Web — Editor · CvXpress')

@push('styles')
<style>
/* ══ PAGE WRAPPER ══ */
.cvweb-page { background:#f5f7fb; min-height:calc(100vh - 72px); }

/* ══ HERO INTRO ══ */
.cvweb-hero {
    background: linear-gradient(140deg, #eef4ff 0%, #e8f0fe 55%, #f0f7ff 100%);
    border-bottom: 1px solid #c7d7f9;
    padding: 3.5rem 1.5rem 2.75rem;
}
.cvweb-hero__inner {
    max-width: 920px; margin: 0 auto;
    display: flex; flex-direction: column; gap: 2.25rem;
}
.cvweb-hero__top {
    display: flex; flex-direction: column; gap: .625rem;
}
.cvweb-hero__badge {
    display: inline-flex; align-items: center; gap: .4rem;
    background: #dbeafe; border: 1px solid #93c5fd;
    color: #1d4ed8; border-radius: 99px;
    font-size: .72rem; font-weight: 700; letter-spacing: .05em; text-transform: uppercase;
    padding: .28rem .875rem; width: fit-content;
}
.cvweb-hero__title {
    font-size: 1.875rem; font-weight: 800; color: #0f172a;
    letter-spacing: -.04em; line-height: 1.15; margin: 0;
}
.cvweb-hero__subtitle {
    font-size: .9375rem; color: #4b5563; line-height: 1.65; margin: 0;
    max-width: 520px;
}

/* Steps row */
.cvweb-hero__steps {
    display: grid;
    grid-template-columns: 1fr auto 1fr auto 1fr;
    gap: 0; align-items: center;
}
.cvweb-hero__step {
    background: #fff;
    border: 1.5px solid #dbeafe;
    border-radius: 16px;
    padding: 1.1rem 1.25rem 1rem;
    display: flex; align-items: flex-start; gap: .875rem;
    box-shadow: 0 2px 8px rgba(37,99,235,.07);
    transition: box-shadow .18s, transform .18s;
}
.cvweb-hero__step:hover {
    box-shadow: 0 6px 20px rgba(37,99,235,.13);
    transform: translateY(-2px);
}
.cvweb-hero__step-icon {
    width: 38px; height: 38px; border-radius: 10px; flex-shrink: 0;
    display: flex; align-items: center; justify-content: center;
}
.cvweb-hero__step-icon--1 { background: #eff6ff; color: #2563eb; }
.cvweb-hero__step-icon--2 { background: #f5f3ff; color: #7c3aed; }
.cvweb-hero__step-icon--3 { background: #f0fdf4; color: #16a34a; }
.cvweb-hero__step-body { display: flex; flex-direction: column; gap: .15rem; min-width: 0; }
.cvweb-hero__step-num {
    font-size: .62rem; font-weight: 800; letter-spacing: .1em;
    text-transform: uppercase; color: #94a3b8;
}
.cvweb-hero__step-label {
    font-size: .875rem; font-weight: 700; color: #0f172a; white-space: nowrap;
}
.cvweb-hero__step-arrow {
    display: flex; align-items: center; justify-content: center;
    padding: 0 .625rem; color: #93c5fd;
}

@media (max-width: 680px) {
    .cvweb-hero { padding: 2.75rem 1rem 2rem; }
    .cvweb-hero__title { font-size: 1.5rem; }
    .cvweb-hero__steps { grid-template-columns: 1fr; gap: .625rem; }
    .cvweb-hero__step-arrow { display: none; }
}

/* ══ CONTENT AREA ══ */
.cvweb-inner {
    max-width:1300px; margin:0 auto;
    padding:1.75rem 1.25rem 4rem;
}

/* ══ FLASH ══ */
.cvweb-flash {
    display:flex; align-items:center; gap:.5rem;
    background:#f8fafc; border:1px solid #e2e8f0;
    border-radius:8px; padding:.55rem .875rem;
    margin-bottom:1rem;
    font-size:.8rem; color:#64748b;
    transition: opacity .5s ease, margin .5s ease, padding .5s ease, border .5s ease;
    opacity: 1;
}
.cvweb-flash__close {
    margin-left:auto; background:none; border:none; cursor:pointer;
    color:#94a3b8; padding:0; line-height:1; flex-shrink:0;
    display:flex; align-items:center;
}
.cvweb-flash__close:hover { color:#475569; }
.cvweb-flash.is-hiding {
    opacity: 0; margin-bottom: 0; padding-top: 0; padding-bottom: 0;
    border-top-width: 0; border-bottom-width: 0;
}

/* ══ UPSELL ══ */
.cvweb-upsell {
    max-width:780px; margin:2rem auto; text-align:center;
}
.cvweb-upsell__icon {
    width:68px; height:68px; border-radius:18px;
    background:#eff6ff; border:1px solid #bfdbfe;
    display:flex; align-items:center; justify-content:center;
    margin:0 auto 1.25rem; color:#1d4ed8;
}
.cvweb-upsell h2 { font-size:1.5rem; font-weight:800; color:#0f172a; margin-bottom:.5rem; }
.cvweb-upsell p  { font-size:.9rem; color:#6b7280; margin-bottom:2rem; }
.cvweb-plans {
    display:grid; grid-template-columns:repeat(auto-fit,minmax(220px,1fr));
    gap:1.25rem; text-align:left;
}
.cvweb-plan {
    background:#fff; border:1.5px solid #e5e7eb;
    border-radius:16px; padding:1.5rem;
    position:relative; transition:box-shadow .2s,transform .2s;
}
.cvweb-plan:hover { box-shadow:0 8px 28px rgba(0,0,0,.09); transform:translateY(-2px); }
.cvweb-plan--hi   { border-color:var(--pc,#2563eb); box-shadow:0 0 0 3px color-mix(in srgb,var(--pc) 14%,transparent); }
.cvweb-plan__badge {
    position:absolute; top:-12px; left:50%; transform:translateX(-50%);
    background:var(--pc,#2563eb); color:#fff;
    font-size:.62rem; font-weight:800; letter-spacing:.07em;
    padding:.2rem .9rem; border-radius:99px; white-space:nowrap;
}
.cvweb-plan__name  { font-size:.875rem; font-weight:700; color:#0f172a; margin-bottom:.5rem; }
.cvweb-plan__price { font-size:1.75rem; font-weight:800; color:#0f172a; letter-spacing:-.04em; margin-bottom:.875rem; }
.cvweb-plan__price small { font-size:.78rem; font-weight:400; color:#6b7280; }
.cvweb-plan__feats { list-style:none; padding:0; margin:0 0 1.25rem; display:flex; flex-direction:column; gap:.4rem; }
.cvweb-plan__feats li { display:flex; gap:.4rem; font-size:.8rem; color:#374151; align-items:flex-start; }
.cvweb-plan__btn {
    display:block; width:100%; padding:.6rem;
    border-radius:9px; text-align:center;
    font-size:.84rem; font-weight:700; cursor:pointer;
    border:1.5px solid #e5e7eb; background:#f8fafc; color:#374151;
    text-decoration:none; transition:all .15s;
}
.cvweb-plan__btn:hover { border-color:#9ca3af; }
.cvweb-plan__btn--hi { background:var(--pc,#2563eb); border-color:var(--pc); color:#fff; }
.cvweb-plan__btn--hi:hover { filter:brightness(1.08); }

/* ══ EDITOR WRAP ══ */
.cvweb-editor { display:grid; grid-template-columns:440px 1fr; gap:1.5rem; align-items:start; }

/* ══ FORM COLUMN ══ */
.cvweb-form { background:#fff; border:1px solid #e5e7eb; border-radius:14px; overflow:hidden; }
.cvweb-form__top {
    padding:.75rem 1rem; border-bottom:1px solid #f1f5f9;
    display:flex; align-items:center; justify-content:space-between; gap:.5rem;
}
.cvweb-tpl-info { display:flex; align-items:center; gap:.5rem; font-size:.8rem; }
.cvweb-tpl-thumb {
    width:32px; height:22px; border-radius:4px;
    background:#e2e8f0; overflow:hidden; flex-shrink:0;
}
.cvweb-tpl-thumb img { width:100%; height:100%; object-fit:cover; }
.cvweb-tpl-info strong { font-weight:700; color:#0f172a; }

/* action buttons row */
.cvweb-actions {
    display:flex; gap:.5rem; padding:.625rem 1rem;
    border-bottom:1px solid #f1f5f9;
}
.cvweb-btn {
    display:inline-flex; align-items:center; gap:.35rem;
    padding:.4rem .875rem; border-radius:8px;
    font-size:.78rem; font-weight:700; font-family:inherit;
    cursor:pointer; border:none; transition:all .15s; text-decoration:none;
}
.cvweb-btn--primary { background:#2563eb; color:#fff; }
.cvweb-btn--primary:hover { background:#1d4ed8; }
.cvweb-btn--ghost { background:#f8fafc; color:#374151; border:1.5px solid #d1d5db; }
.cvweb-btn--ghost:hover { border-color:#9ca3af; }
.cvweb-btn--danger { background:none; color:#ef4444; border:1.5px solid #fca5a5; }
.cvweb-btn--danger:hover { background:#fee2e2; }
.cvweb-btn--ai { background:linear-gradient(135deg,#6366f1,#8b5cf6); color:#fff; flex:1; justify-content:center; }
.cvweb-btn--ai:hover { filter:brightness(1.08); }
.cvweb-btn--sm { padding:.32rem .7rem; font-size:.74rem; }

/* tabs */
.cvweb-tabs-wrap { position:relative; padding:.5rem .75rem; border-bottom:1px solid #f1f5f9; }
.cvweb-tabs-wrap::after {
    content:''; position:absolute; right:0; top:0; bottom:0; width:32px;
    background:linear-gradient(to right,transparent,#fff);
    pointer-events:none; border-radius:0 0 0 0;
}
.cvweb-tabs-wrap.scrolled-end::after { display:none; }
.cvweb-tabs {
    display:flex; gap:.25rem; overflow-x:auto;
    scrollbar-width:none; -webkit-overflow-scrolling:touch;
}
.cvweb-tabs::-webkit-scrollbar { display:none; }
.cvweb-tab {
    padding:.3rem .65rem; border-radius:7px; border:none;
    font-size:.745rem; font-weight:600; white-space:nowrap; flex-shrink:0;
    cursor:pointer; background:transparent; color:#6b7280;
    transition:background .13s,color .13s; font-family:inherit;
}
.cvweb-tab:hover { background:#f1f5f9; color:#374151; }
.cvweb-tab--active { background:#2563eb !important; color:#fff !important; }

/* panels */
.cvweb-panels { padding:1rem; }
.cvweb-panel { display:none; }
.cvweb-panel--active { display:block; }
.cvweb-panel__label {
    font-size:.65rem; font-weight:800; letter-spacing:.1em;
    text-transform:uppercase; color:#9ca3af; margin-bottom:.875rem;
}

/* form controls */
.cvweb-field { margin-bottom:.75rem; }
.cvweb-label { display:block; font-size:.73rem; font-weight:700; color:#374151; margin-bottom:.3rem; }
.cvweb-input, .cvweb-textarea, .cvweb-select {
    width:100%; padding:.52rem .75rem;
    border:1.5px solid #d1d5db; border-radius:8px;
    font-size:.855rem; font-family:inherit; color:#0f172a;
    background:#fff; box-sizing:border-box;
    transition:border-color .15s,box-shadow .15s;
}
.cvweb-input:focus, .cvweb-textarea:focus, .cvweb-select:focus {
    outline:none; border-color:#2563eb;
    box-shadow:0 0 0 3px rgba(37,99,235,.1);
}
.cvweb-textarea { resize:vertical; min-height:88px; line-height:1.55; }
.cvweb-grid2 { display:grid; grid-template-columns:1fr 1fr; gap:.625rem; }
.cvweb-hint { font-size:.68rem; color:#9ca3af; margin-top:.25rem; display:block; }

/* entries */
.cv-entry {
    border:1.5px solid #e5e7eb; border-radius:10px;
    padding:.875rem; margin-bottom:.75rem; position:relative;
    transition:border-color .13s;
}
.cv-entry:hover { border-color:#cbd5e1; }
.cv-entry-remove {
    position:absolute; top:.5rem; right:.5rem;
    background:none; border:none; cursor:pointer;
    color:#d1d5db; padding:.2rem; border-radius:5px;
    display:flex; transition:color .13s,background .13s;
}
.cv-entry-remove:hover { color:#ef4444; background:#fee2e2; }
.cvweb-add-btn {
    display:inline-flex; align-items:center; gap:.3rem;
    font-size:.75rem; font-weight:700; color:#2563eb;
    background:none; border:none; cursor:pointer; font-family:inherit;
}
.cvweb-add-btn:hover { color:#1d4ed8; }

/* skills */
.cvweb-chips {
    display:flex; flex-wrap:wrap; gap:.375rem;
    margin-bottom:.75rem; min-height:2rem;
}
.skill-chip-item {
    display:inline-flex; align-items:center; gap:.25rem;
    background:#eff6ff; border:1px solid #bfdbfe; color:#1e40af;
    font-size:.78rem; font-weight:600;
    padding:.25rem .6rem .25rem .75rem; border-radius:99px; line-height:1.3;
}
.skill-chip-item button {
    background:none; border:none; cursor:pointer;
    color:#93c5fd; padding:0 0 0 2px; line-height:1;
    font-size:1.15rem; display:flex; align-items:center;
}
.skill-chip-item button:hover { color:#ef4444; }

/* form footer */
.cvweb-form__footer {
    padding:.75rem 1rem; border-top:1px solid #f1f5f9;
    display:flex; align-items:center; justify-content:space-between; gap:.5rem;
}

/* ══ PREVIEW COLUMN ══ */
.cvweb-preview { position:sticky; top:72px; }
.cvweb-browser {
    position:relative;
    background:#fff; border:1px solid #e5e7eb; border-radius:14px;
    overflow:hidden; box-shadow:0 6px 28px rgba(15,23,42,.09);
}
.cvweb-browser__bar {
    height:38px; background:#f8fafc; border-bottom:1px solid #e5e7eb;
    display:flex; align-items:center; gap:.625rem; padding:0 .875rem;
}
.cvweb-browser__dots { display:flex; gap:4px; }
.cvweb-browser__dots span { width:10px; height:10px; border-radius:50%; transition:filter .12s,transform .12s; }
.cvweb-browser__dots span[onclick]:hover { filter:brightness(1.15); transform:scale(1.15); }
.cvweb-browser__url {
    flex:1; background:#f1f5f9; border-radius:5px;
    padding:.22rem .65rem; font-size:.7rem; color:#6b7280;
    font-family:monospace; overflow:hidden; white-space:nowrap; text-overflow:ellipsis;
}
.cvweb-browser__screen {
    position:relative; height:62vh; min-height:450px; max-height:740px;
    overflow:hidden; background:#f8fafc;
}
#cv-preview-iframe {
    position:absolute; top:0; left:0;
    width:1280px; height:900px;
    border:none; transform-origin:top left;
}
.cvweb-browser__foot {
    padding:.55rem .875rem; border-top:1px solid #f1f5f9;
    display:flex; align-items:center; justify-content:space-between;
    font-size:.78rem; background:#fafafa;
}
.cvweb-browser__foot span { color:#6b7280; }
.cvweb-browser__foot strong { color:#0f172a; }
.cvweb-browser__foot button {
    font-size:.75rem; font-weight:700; color:#2563eb;
    background:none; border:none; cursor:pointer;
}
.cvweb-browser__foot button:hover { text-decoration:underline; }

/* ── Modo pantalla completa de la vista previa (mismo iframe, sin recargar) ── */
@keyframes cvfs-in { from{opacity:0;transform:scale(.985);} to{opacity:1;transform:scale(1);} }
.cvweb-browser--fullscreen {
    position:fixed !important; inset:0; z-index:5000;
    max-width:none; width:100vw; height:100vh;
    border-radius:0; box-shadow:none;
    display:flex; flex-direction:column;
    animation:cvfs-in .16s ease-out;
}
.cvweb-browser--fullscreen .cvweb-browser__screen {
    height:auto; min-height:0; max-height:none; flex:1 1 auto;
}
/* Al entrar en pantalla completa, se oculta el header propio de la web
   (el botón de salir vive dentro de la propia barra de la vista previa). */
body.cvweb-fs-active { overflow:hidden; }
body.cvweb-fs-active .nav { display:none !important; }

/* ══ SELECTOR GRID ══ */
.cvweb-sel-header { display:flex; align-items:flex-start; justify-content:space-between; flex-wrap:wrap; gap:1rem; margin-bottom:1.5rem; }
.cvweb-sel-header h2 { font-size:1.25rem; font-weight:800; color:#0f172a; margin:0 0 .25rem; }
.cvweb-sel-header p  { font-size:.875rem; color:#6b7280; margin:0; }
.cvweb-tpl-grid {
    display:grid; grid-template-columns:repeat(auto-fill,minmax(200px,1fr));
    gap:1.125rem;
}
.cvweb-tpl-card {
    border:2px solid #e5e7eb; border-radius:13px;
    overflow:hidden; background:#fff; cursor:pointer;
    transition:border-color .15s,box-shadow .15s,transform .15s;
}
.cvweb-tpl-card:hover { border-color:#2563eb; box-shadow:0 6px 22px rgba(37,99,235,.13); transform:translateY(-3px); }
.cvweb-tpl-card--active { border-color:#16a34a; box-shadow:0 0 0 3px #bbf7d0; }
.cvweb-tpl-card__thumb { aspect-ratio:4/3; position:relative; background:#f1f5f9; overflow:hidden; }
.cvweb-tpl-card__thumb img { width:100%; height:100%; object-fit:cover; object-position:top; display:block; }
.cvweb-tpl-card__body { padding:.75rem .875rem; }
.cvweb-tpl-card__name { font-size:.86rem; font-weight:700; color:#0f172a; display:block; margin-bottom:.15rem; }
.cvweb-tpl-card__cat  { font-size:.7rem; font-weight:700; text-transform:uppercase; letter-spacing:.06em; color:#2563eb; display:block; margin-bottom:.5rem; }
.cvweb-tpl-card__cta {
    width:100%; padding:.38rem; text-align:center;
    border-radius:7px; background:#f1f5f9; color:#6b7280;
    font-size:.72rem; font-weight:600; border:none; cursor:pointer;
    transition:background .13s,color .13s; font-family:inherit;
}
.cvweb-tpl-card__cta:hover { background:#2563eb; color:#fff; }
.cvweb-tpl-card__cta--active { background:#dcfce7; color:#16a34a; }

/* ══ HUB ══ */
.cvweb-hub__cta {
    border:2px solid var(--bc,#e5e7eb); border-radius:14px;
    padding:1.5rem; cursor:pointer; background:var(--bg,#fff);
    transition:box-shadow .15s,transform .12s;
    display:flex; gap:1.125rem; align-items:center; position:relative;
}
.cvweb-hub__cta:hover { box-shadow:0 6px 22px var(--sh,rgba(0,0,0,.07)); transform:translateY(-2px); }
.cvweb-hub__icon {
    width:52px; height:52px; border-radius:13px; flex-shrink:0;
    display:flex; align-items:center; justify-content:center;
}
.cvweb-hub__cta-label { font-size:.65rem; font-weight:800; letter-spacing:.06em; text-transform:uppercase; margin-bottom:.35rem; }
.cvweb-hub__cta-title { font-size:1.05rem; font-weight:800; margin-bottom:.3rem; }
.cvweb-hub__cta-desc  { font-size:.83rem; line-height:1.55; }
.cvweb-hub__cta-action { display:inline-flex; align-items:center; gap:.35rem; font-size:.78rem; font-weight:700; margin-top:.625rem; }

/* ══ MOBILE ══ */
@media (max-width:1100px) {
    .cvweb-editor { grid-template-columns:1fr; }
    .cvweb-preview { position:static; display:none; }
    .cvweb-preview.show { display:block; }
    .cvweb-mobile-toggle {
        display:flex; background:#fff; border:1px solid #e5e7eb;
        border-radius:10px; padding:.3rem; gap:.25rem; margin-bottom:1rem;
    }
    .cvweb-mobile-toggle button {
        flex:1; padding:.4rem; border-radius:7px; border:none; cursor:pointer;
        font-size:.78rem; font-weight:600; background:transparent; color:#6b7280;
        transition:all .13s; font-family:inherit;
    }
    .cvweb-mobile-toggle button.active { background:#2563eb; color:#fff; }
    .cvweb-browser__screen { height:52vw; min-height:260px; max-height:480px; }
    .cvweb-hub { grid-template-columns:1fr; }
}
@media (max-width:640px) {
    .cvweb-hero { padding:1.5rem 1rem 1.25rem; }
    .cvweb-hero__title { font-size:1.3rem; }
    .cvweb-inner { padding:1.25rem .75rem 3rem; }
    .cvweb-grid2 { grid-template-columns:1fr; }
    .cvweb-tpl-grid { grid-template-columns:repeat(2,1fr); }
    .cvweb-plans { grid-template-columns:1fr; }
}
.cvweb-mobile-toggle { display:none; }

/* ══ CONFIRM MODAL (propio, sin confirm() nativo) ══ */
@keyframes cvconfirm-in { from{opacity:0;transform:translateY(8px) scale(.97);} to{opacity:1;transform:translateY(0) scale(1);} }
.cvweb-confirm-overlay {
    display:none; position:fixed; inset:0; background:rgba(15,23,42,.55);
    backdrop-filter:blur(2px); z-index:10000;
    align-items:center; justify-content:center; padding:1rem;
}
.cvweb-confirm-card {
    background:#fff; border-radius:18px; padding:1.75rem 1.75rem 1.5rem;
    max-width:380px; width:100%; text-align:center;
    box-shadow:0 24px 60px rgba(15,23,42,.25);
    animation:cvconfirm-in .18s ease-out;
}
.cvweb-confirm-icon {
    width:52px; height:52px; border-radius:50%; margin:0 auto .875rem;
    display:flex; align-items:center; justify-content:center;
    background:#fee2e2; color:#dc2626;
}
.cvweb-confirm-icon--info { background:#eff6ff; color:#2563eb; }
.cvweb-confirm-title { font-size:1.05rem; font-weight:800; color:#0f172a; margin:0 0 .4rem; }
.cvweb-confirm-msg   { font-size:.83rem; color:#6b7280; line-height:1.55; margin:0 0 1.375rem; }
.cvweb-confirm-actions { display:flex; gap:.625rem; }
.cvweb-confirm-actions .cvweb-btn { flex:1; justify-content:center; }
.cvweb-btn--danger-solid { background:#ef4444; color:#fff; }
.cvweb-btn--danger-solid:hover { background:#dc2626; }

/* ══ PUBLISH MODAL ══ */
.cvweb-publish-overlay {
    display:none; position:fixed; inset:0; background:rgba(15,23,42,.55);
    z-index:10000; align-items:center; justify-content:center; padding:1rem;
}
.cvweb-publish-card {
    background:#fff; border-radius:20px; padding:1.75rem;
    max-width:620px; width:100%; max-height:90vh; overflow-y:auto;
    box-shadow:0 24px 60px rgba(15,23,42,.25);
    animation:cvconfirm-in .18s ease-out; position:relative;
}
.cvweb-publish-close {
    position:absolute; top:1rem; right:1rem; background:none; border:none;
    cursor:pointer; color:#9ca3af; padding:.25rem; display:flex;
}
.cvweb-publish-close:hover { color:#374151; }
.cvweb-publish-head { display:flex; align-items:center; gap:.875rem; margin-bottom:1.375rem; padding-right:1.5rem; }
.cvweb-publish-icon {
    width:48px; height:48px; border-radius:14px; flex-shrink:0;
    background:linear-gradient(135deg,#2563eb,#1d4ed8);
    display:flex; align-items:center; justify-content:center;
}
.cvweb-publish-title { font-size:1.05rem; font-weight:800; color:#0f172a; }
.cvweb-publish-sub { font-size:.82rem; color:#6b7280; margin-top:.15rem; }

.cvweb-publish-status {
    display:flex; align-items:center; justify-content:space-between; gap:1rem; flex-wrap:wrap;
    background:#f0fdf4; border:1.5px solid #bbf7d0; border-radius:12px;
    padding:.875rem 1.125rem; margin-bottom:1.25rem;
}
.cvweb-publish-status--pending { background:#eff6ff; border-color:#bfdbfe; }
.cvweb-publish-status strong { color:#0f172a; }

.cvweb-publish-opts { display:grid; grid-template-columns:1fr 1fr; gap:1rem; margin-bottom:1.25rem; }
@media (max-width:560px) { .cvweb-publish-opts { grid-template-columns:1fr; } }
.cvweb-publish-opt {
    border:1.5px solid #e5e7eb; border-radius:14px; padding:1.125rem;
    display:flex; flex-direction:column; gap:.75rem;
}
.cvweb-publish-opt__head { display:flex; align-items:center; gap:.6rem; }
.cvweb-publish-opt__icon { width:36px; height:36px; border-radius:10px; display:flex; align-items:center; justify-content:center; flex-shrink:0; }
.cvweb-publish-opt__name { font-size:.86rem; font-weight:700; color:#0f172a; }
.cvweb-publish-opt__tag { font-size:.72rem; font-weight:700; }
.cvweb-publish-domain-row {
    display:flex; align-items:center; border:1.5px solid #d1d5db; border-radius:8px;
    overflow:hidden; background:#f9fafb;
}
.cvweb-publish-domain-row input {
    flex:1; min-width:0; padding:.5rem .625rem; border:none; background:transparent;
    font-size:.8rem; font-family:monospace; outline:none; color:#0f172a;
}
.cvweb-publish-domain-row input:focus { background:#fff; }
.cvweb-publish-domain-row span {
    padding:.5rem .625rem; font-size:.75rem; color:#6b7280; white-space:nowrap;
    border-left:1px solid #e5e7eb; background:#f3f4f6;
}
.cvweb-publish-opt__feats { list-style:none; padding:0; margin:0; display:flex; flex-direction:column; gap:.35rem; flex:1; }
.cvweb-publish-opt__feats li { display:flex; align-items:center; gap:.4rem; font-size:.76rem; color:#374151; }

.cvweb-publish-divider { display:flex; align-items:center; gap:.75rem; margin:1.25rem 0; }
.cvweb-publish-divider::before, .cvweb-publish-divider::after { content:''; flex:1; height:1px; background:#e5e7eb; }
.cvweb-publish-divider span { font-size:.7rem; font-weight:700; color:#9ca3af; text-transform:uppercase; letter-spacing:.05em; }

.cvweb-publish-zip {
    display:flex; align-items:center; justify-content:space-between; gap:1rem; flex-wrap:wrap;
    background:#f8fafc; border:1.5px solid #e5e7eb; border-radius:12px; padding:1rem 1.125rem;
}
.cvweb-publish-zip__text { font-size:.82rem; color:#374151; }
.cvweb-publish-zip__text strong { display:block; color:#0f172a; font-size:.86rem; margin-bottom:.15rem; }

.cvweb-publish-foot { text-align:center; margin-top:1.25rem; font-size:.78rem; color:#9ca3af; }
.cvweb-publish-foot a { color:#2563eb; font-weight:600; text-decoration:none; }
.cvweb-publish-foot a:hover { text-decoration:underline; }

/* Aviso pequeño "sin cambios" que aparece junto al botón "Publicar ahora" */
.cvweb-nochange-pop {
    position:fixed; max-width:230px; background:#0f172a; color:#fff;
    font-size:.78rem; line-height:1.5; padding:.7rem .85rem; border-radius:10px;
    box-shadow:0 12px 30px rgba(15,23,42,.3); z-index:10050;
    opacity:0; transform:translateY(4px); pointer-events:none;
    transition:opacity .15s ease, transform .15s ease;
}
.cvweb-nochange-pop.is-visible { opacity:1; transform:translateY(0); pointer-events:auto; }
.cvweb-nochange-pop a { display:inline-block; margin-top:.4rem; color:#93c5fd; font-weight:700; text-decoration:none; }
.cvweb-nochange-pop a:hover { text-decoration:underline; }
</style>
@endpush

@section('content')
@php
    $u = auth()->user();
    $selected = $activePurchase?->selectedTemplate;
    $showHub  = $selected && session('show_hub');
@endphp

<div class="cvweb-page">

    {{-- ══ HERO INTRO ══ --}}
    <div class="cvweb-hero">
        <div class="cvweb-hero__inner">

            <div class="cvweb-hero__top">
                <div class="cvweb-hero__badge">
                   
                </div>
                <h1 class="cvweb-hero__title">Tu portfolio web profesional</h1>
                <p class="cvweb-hero__subtitle">Elige una plantilla, rellena tu información con IA o a mano, y publica tu CV como una web propia en minutos.</p>
            </div>

            <div class="cvweb-hero__steps">

                {{-- Paso 1 --}}
                <div class="cvweb-hero__step">
                    <div class="cvweb-hero__step-icon cvweb-hero__step-icon--1">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/></svg>
                    </div>
                    <div class="cvweb-hero__step-body">
                        <span class="cvweb-hero__step-num">Paso 01</span>
                        <span class="cvweb-hero__step-label">Elige plantilla</span>
                    </div>
                </div>

                {{-- Flecha --}}
                <div class="cvweb-hero__step-arrow">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                </div>

                {{-- Paso 2 --}}
                <div class="cvweb-hero__step">
                    <div class="cvweb-hero__step-icon cvweb-hero__step-icon--2">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 3l1.9 5.8H20l-4.9 3.6 1.9 5.8L12 15l-5 3.4 1.9-5.8L4 9h6.1z"/></svg>
                    </div>
                    <div class="cvweb-hero__step-body">
                        <span class="cvweb-hero__step-num">Paso 02</span>
                        <span class="cvweb-hero__step-label">Rellena con IA</span>
                    </div>
                </div>

                {{-- Flecha --}}
                <div class="cvweb-hero__step-arrow">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                </div>

                {{-- Paso 3 --}}
                <div class="cvweb-hero__step">
                    <div class="cvweb-hero__step-icon cvweb-hero__step-icon--3">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="2" y1="12" x2="22" y2="12"/><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/></svg>
                    </div>
                    <div class="cvweb-hero__step-body">
                        <span class="cvweb-hero__step-num">Paso 03</span>
                        <span class="cvweb-hero__step-label">Publica tu web</span>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <div class="cvweb-inner">

        @if(session('success'))
        <div class="cvweb-flash" id="cvweb-flash">
            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" style="color:#10b981;flex-shrink:0"><polyline points="20 6 9 17 4 12"/></svg>
            {{ session('success') }}
            <button class="cvweb-flash__close" onclick="dismissFlash()" aria-label="Cerrar">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
            </button>
        </div>
        <script>
        function dismissFlash() {
            var el = document.getElementById('cvweb-flash');
            if (!el) return;
            el.classList.add('is-hiding');
            setTimeout(function(){ el.remove(); }, 500);
        }
        setTimeout(dismissFlash, 3000);
        </script>
        @endif

        {{-- ══ ESTADO 1: SIN PLAN ══ --}}
        @if(!$activePurchase)
        <div class="cvweb-upsell">
            <div class="cvweb-upsell__icon">
                <svg width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6"><rect x="3" y="3" width="18" height="18" rx="2"/><path d="M3 9h18M9 21V9"/></svg>
            </div>
            <h2>Elige un plan para empezar</h2>
            <p>Adquiere un plan y accede a las plantillas de portfolio web profesional.</p>
            <div class="cvweb-plans">
                @php $planColors = ['#16a34a','#2563eb','#7c3aed']; @endphp
                @foreach($plans as $i => $plan)
                @php $pc = $planColors[$i % count($planColors)]; @endphp
                <div class="cvweb-plan {{ $plan->badge_label ? 'cvweb-plan--hi' : '' }}" style="--pc:{{ $pc }}">
                    @if($plan->badge_label)<div class="cvweb-plan__badge">{{ $plan->badge_label }}</div>@endif
                    <div class="cvweb-plan__name">{{ $plan->name }}</div>
                    <div class="cvweb-plan__price">{{ number_format($plan->price,2,',','.') }}€<small>{{ $plan->billing_cycle==='once' ? ' pago único' : '/año' }}</small></div>
                    @if($plan->features)
                    <ul class="cvweb-plan__feats">
                        @foreach($plan->features as $feat)
                        <li><svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="{{ $pc }}" stroke-width="3" style="flex-shrink:0;margin-top:2px"><polyline points="20 6 9 17 4 12"/></svg>{{ $feat }}</li>
                        @endforeach
                    </ul>
                    @endif
                    <a href="{{ route('checkout.show', $plan->slug) }}" class="cvweb-plan__btn {{ $plan->badge_label ? 'cvweb-plan__btn--hi' : '' }}">Empezar con {{ $plan->name }}</a>
                </div>
                @endforeach
            </div>
        </div>

        {{-- ══ ESTADO 2: TIENE PLAN ══ --}}
        @else

        {{-- Formularios ocultos de selección de plantilla --}}
        @foreach($availableTemplates as $tpl)
        <form id="sel-tpl-{{ $tpl->id }}" method="POST" action="{{ route('cv-web.template.select', [$activePurchase->id, $tpl->id]) }}" style="display:none;">@csrf</form>
        @endforeach

        {{-- Mobile toggle (solo visible en móvil) --}}
        <div class="cvweb-mobile-toggle" id="cwMobileToggle">
            <button class="active" onclick="cwToggle('editor',this)">
                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                Editor
            </button>
            <button onclick="cwToggle('preview',this)">
                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="3" width="20" height="14" rx="2"/><line x1="8" y1="21" x2="16" y2="21"/><line x1="12" y1="17" x2="12" y2="21"/></svg>
                Vista previa
            </button>
        </div>

        <div class="cvweb-editor" id="cwEditorWrap">

            {{-- ── COLUMNA IZQUIERDA ── --}}
            <div id="cwFormCol">

                {{-- ─── VISTA: SELECTOR ─── --}}
                <div id="cv-selector-view" style="{{ $selected ? 'display:none' : '' }}">
                    <div class="cvweb-sel-header">
                        <div>
                            <h2>Elige tu plantilla</h2>
                            <p>Tu plan <strong>{{ $activePurchase->plan->name }}</strong> incluye {{ $availableTemplates->count() }} plantilla(s).</p>
                        </div>
                        @if($selected)
                        <button onclick="showCvView('hub')" class="cvweb-btn cvweb-btn--ghost cvweb-btn--sm">
                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="15 18 9 12 15 6"/></svg> Volver
                        </button>
                        @endif
                    </div>
                    @if($availableTemplates->isEmpty())
                        <p style="color:#9ca3af;text-align:center;padding:2rem;">No hay plantillas disponibles para tu plan todavía.</p>
                    @else
                    <div class="cvweb-tpl-grid">
                        @foreach($availableTemplates as $tpl)
                        @php $isActive = $selected && $selected->id === $tpl->id; $tierData = $tiers[$tpl->plan_tier] ?? null; @endphp
                        <div class="cvweb-tpl-card {{ $isActive ? 'cvweb-tpl-card--active' : '' }}"
                             ondblclick="{{ $isActive ? "showCvView('editor')" : "document.getElementById('sel-tpl-{$tpl->id}').submit()" }}"
                             title="{{ $isActive ? 'En uso — doble clic para editar' : 'Doble clic para usar' }}">
                            <div class="cvweb-tpl-card__thumb">
                                @if($tpl->preview_image)
                                    <img src="{{ asset('storage/'.$tpl->preview_image) }}" alt="{{ $tpl->name }}">
                                @else
                                    <div style="width:100%;height:100%;display:flex;align-items:center;justify-content:center;"><svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="#cbd5e1" stroke-width="1.2"><rect x="3" y="3" width="18" height="18" rx="2"/></svg></div>
                                @endif
                                @if($isActive)<span style="position:absolute;top:7px;right:7px;background:#16a34a;color:#fff;border-radius:99px;padding:2px 9px;font-size:.65rem;font-weight:700;">✓ En uso</span>@endif
                                @if($tierData && !$isActive)<span style="position:absolute;top:7px;left:7px;background:{{ $tierData['bg'] }};color:{{ $tierData['color'] }};border-radius:99px;padding:2px 8px;font-size:.65rem;font-weight:700;">{{ $tierData['label'] }}</span>@endif
                            </div>
                            <div class="cvweb-tpl-card__body">
                                <span class="cvweb-tpl-card__name">{{ $tpl->name }}</span>
                                @if($tpl->category)<span class="cvweb-tpl-card__cat">{{ $tpl->category->name }}</span>@endif
                                @if($isActive)
                                    <div class="cvweb-tpl-card__cta cvweb-tpl-card__cta--active">✓ En uso</div>
                                @else
                                    <button class="cvweb-tpl-card__cta" onclick="document.getElementById('sel-tpl-{{ $tpl->id }}').submit()">Usar esta plantilla →</button>
                                @endif
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @endif
                </div>

                {{-- ─── VISTA: HUB ─── --}}
                <div id="cv-hub-view" style="{{ $showHub ? '' : 'display:none' }}">
                    <div style="margin-bottom:1.5rem;">
                        <div style="display:inline-flex;align-items:center;gap:.4rem;background:#eff6ff;border:1px solid #bfdbfe;border-radius:99px;padding:.28rem .875rem;font-size:.72rem;font-weight:700;color:#1d4ed8;margin-bottom:.625rem;letter-spacing:.03em;">
                            <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><rect x="3" y="3" width="18" height="18" rx="2"/><path d="M3 9h18M9 21V9"/></svg> Mi CV Web
                        </div>
                        <h2 style="font-size:1.5rem;font-weight:800;color:#0f172a;margin:0 0 .35rem;line-height:1.2;">¿Cómo quieres crear tu CV?</h2>
                        <p style="font-size:.875rem;color:#6b7280;margin:0;">Plantilla activa: <strong style="color:#0f172a;">{{ $selected?->name }}</strong></p>
                    </div>
                    <div style="display:flex;flex-direction:column;gap:.875rem;">
                        {{-- CTA IA --}}
                        <div class="cvweb-hub__cta" style="--bc:#3b82f6;--bg:linear-gradient(135deg,#eff6ff,#dbeafe);--sh:rgba(59,130,246,.22);" onclick="openAiModal()">
                            <div style="position:absolute;top:-1px;right:14px;background:#3b82f6;color:#fff;border-radius:0 0 8px 8px;padding:.2rem .75rem;font-size:.65rem;font-weight:800;letter-spacing:.05em;text-transform:uppercase;">Recomendado</div>
                            <div class="cvweb-hub__icon" style="background:#3b82f6;box-shadow:0 4px 12px rgba(59,130,246,.35);">
                                <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="1.8"><path d="M12 2L2 7l10 5 10-5-10-5z"/><path d="M2 17l10 5 10-5"/><path d="M2 12l10 5 10-5"/></svg>
                            </div>
                            <div>
                                <div class="cvweb-hub__cta-label" style="color:#1d4ed8;">Más rápido</div>
                                <div class="cvweb-hub__cta-title" style="color:#1e3a8a;">Generar con IA</div>
                                <div class="cvweb-hub__cta-desc" style="color:#1e40af;opacity:.85;">Sube tu CV en PDF y la IA rellena todo en segundos automáticamente.</div>
                                <div class="cvweb-hub__cta-action" style="background:#fff;padding:.3rem .875rem;border-radius:99px;box-shadow:0 1px 4px rgba(0,0,0,.08);color:#2563eb;display:inline-flex;align-items:center;gap:.3rem;margin-top:.5rem;">
                                    Empezar ahora <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="9 18 15 12 9 6"/></svg>
                                </div>
                            </div>
                        </div>
                        <div style="display:flex;align-items:center;gap:.75rem;">
                            <div style="flex:1;height:1px;background:#e5e7eb;"></div>
                            <span style="font-size:.72rem;font-weight:600;color:#9ca3af;">o si prefieres</span>
                            <div style="flex:1;height:1px;background:#e5e7eb;"></div>
                        </div>
                        {{-- CTA Manual --}}
                        <div class="cvweb-hub__cta" onclick="showCvView('editor')">
                            <div class="cvweb-hub__icon" style="background:#f1f5f9;border:1.5px solid #e5e7eb;">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#6b7280" stroke-width="1.8"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                            </div>
                            <div>
                                <div class="cvweb-hub__cta-title" style="color:#0f172a;">
                                    Rellenar a mano
                                    @php $hasCvData = !empty(array_filter($cvData, fn($v) => !empty($v))); @endphp
                                    @if($hasCvData)
                                    <span style="margin-left:.5rem;background:#dcfce7;color:#16a34a;border-radius:99px;padding:.1rem .55rem;font-size:.65rem;font-weight:700;vertical-align:middle;">Continuar</span>
                                    @endif
                                </div>
                                <div class="cvweb-hub__cta-desc" style="color:#6b7280;">
                                    @if($hasCvData)
                                        Tienes datos guardados, continúa editando donde lo dejaste.
                                    @else
                                        Escribe cada campo directamente a tu ritmo.
                                    @endif
                                </div>
                                <div style="display:inline-flex;align-items:center;gap:.3rem;font-size:.78rem;font-weight:700;color:#6b7280;margin-top:.35rem;">
                                    {{ $hasCvData ? 'Continuar editando' : 'Ir al editor' }} <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="9 18 15 12 9 6"/></svg>
                                </div>
                            </div>
                        </div>
                        <button onclick="showCvView('selector')" class="cvweb-btn cvweb-btn--ghost" style="width:100%;justify-content:center;font-size:.78rem;margin-top:.25rem;">
                            <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="2"/><path d="M3 9h18M9 21V9"/></svg>
                            Cambiar plantilla
                        </button>
                    </div>
                </div>

                {{-- ─── VISTA: EDITOR ─── --}}
                <div id="cv-editor-view" style="{{ ($selected && !$showHub) ? '' : 'display:none' }}">

                    <div class="cvweb-form">
                        {{-- Top: plantilla activa + acciones --}}
                        <div class="cvweb-form__top">
                            <div class="cvweb-tpl-info">
                                <div class="cvweb-tpl-thumb">
                                    @if($selected?->preview_image)<img src="{{ asset('storage/'.$selected->preview_image) }}" alt="">@endif
                                </div>
                                <strong>{{ $selected?->name }}</strong>
                            </div>
                            <button onclick="showCvView('selector')" class="cvweb-btn cvweb-btn--ghost cvweb-btn--sm">Cambiar →</button>
                        </div>
                        <div class="cvweb-actions">
                            <button type="button" onclick="openAiModal()" class="cvweb-btn cvweb-btn--ai">
                                <svg width="13" height="50" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2L2 7l10 5 10-5-10-5z"/><path d="M2 17l10 5 10-5"/><path d="M2 12l10 5 10-5"/></svg>
                                Analizar CURRICULÚM con IA
                            </button>
                            <button type="button" onclick="showCvView('hub')" class="cvweb-btn cvweb-btn--ghost">
                                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="15 18 9 12 15 6"/></svg>
                            </button>
                        </div>

                        {{-- Tabs --}}
                        <div class="cvweb-tabs-wrap" id="cwTabsWrap">
                            <div class="cvweb-tabs" id="cwTabs">
                                @foreach([
                                    'presentacion'=>'Presentación','contacto'=>'Contacto','experiencia'=>'Experiencia',
                                    'formacion'=>'Formación','habilidades'=>'Habilidades','proyectos'=>'Proyectos','idiomas'=>'Idiomas'
                                ] as $key=>$label)
                                <button type="button" class="cvweb-tab {{ $loop->first ? 'cvweb-tab--active' : '' }}"
                                        id="cvtab-{{ $key }}" onclick="showCvSection('{{ $key }}')">{{ $label }}</button>
                                @endforeach
                            </div>
                        </div>

                        <div class="cvweb-panels">

                            {{-- Presentación --}}
                            <div class="cvweb-panel cvweb-panel--active" id="cvpanel-presentacion">
                                <div class="cvweb-panel__label">Presentación</div>
                                <div class="cvweb-field">
                                    <label class="cvweb-label">Foto de perfil</label>
                                    @if($photoOrientation)
                                    <div style="font-size:.72rem;color:#1e40af;background:#eff6ff;border:1px solid #bfdbfe;border-radius:6px;padding:.32rem .65rem;margin-bottom:.5rem;display:flex;align-items:center;gap:.4rem;">
                                        {{ $photoOrientation==='vertical' ? 'Plantilla requiere foto vertical (3:4)' : 'Plantilla requiere foto horizontal (4:3)' }}
                                    </div>
                                    @endif
                                    <div style="display:flex;align-items:center;gap:.875rem;">
                                        <div id="editor-photo-preview" style="width:52px;height:52px;border-radius:10px;overflow:hidden;background:#e5e7eb;flex-shrink:0;display:flex;align-items:center;justify-content:center;">
                                            @if($photoUrl)<img src="{{ $photoUrl }}" style="width:100%;height:100%;object-fit:cover;" id="editor-photo-img">
                                            @else<svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#9ca3af" stroke-width="1.5" id="editor-photo-placeholder"><circle cx="12" cy="8" r="4"/><path d="M4 20c0-4 3.6-7 8-7s8 3 8 7"/></svg>@endif
                                        </div>
                                        <div>
                                            <button type="button" onclick="document.getElementById('editor-photo-file').click()" class="cvweb-btn cvweb-btn--ghost cvweb-btn--sm" style="margin-bottom:.3rem;">{{ $photoUrl?'Cambiar foto':'Subir foto' }}</button>
                                            <input type="file" id="editor-photo-file" accept="image/jpeg,image/png,image/webp" style="display:none;" onchange="uploadProfilePhoto(this)">
                                            <span class="cvweb-hint">JPG, PNG o WebP · Máx. 3 MB</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="cvweb-field"><label class="cvweb-label" for="cv_name">Nombre completo</label><input type="text" id="cv_name" class="cvweb-input" value="{{ $cvData['cv_name'] ?? $u->name }}" oninput="updatePreview('name',this.value)"></div>
                                <div class="cvweb-field"><label class="cvweb-label" for="cv_job_title">Título profesional</label><input type="text" id="cv_job_title" class="cvweb-input" placeholder="ej. Desarrollador Full Stack" value="{{ $cvData['cv_job_title'] ?? $u->job_title ?? '' }}" oninput="updatePreview('job_title',this.value)"></div>
                                <div class="cvweb-field"><label class="cvweb-label" for="cv_bio">Resumen / Sobre mí</label><textarea id="cv_bio" class="cvweb-textarea" rows="5" placeholder="Descripción profesional..." oninput="updatePreview('bio',this.value)">{{ $cvData['cv_bio'] ?? $u->bio ?? '' }}</textarea></div>
                            </div>

                            {{-- Contacto --}}
                            <div class="cvweb-panel" id="cvpanel-contacto">
                                <div class="cvweb-panel__label">Contacto</div>
                                @foreach([
                                    ['cv_email','email','Correo electrónico','email',$cvData['cv_email']??$u->email??'','tu@email.com'],
                                    ['cv_phone','phone','Teléfono','tel',$cvData['cv_phone']??$u->phone??'','+34 600 000 000'],
                                    ['cv_location','location','Ubicación','text',$cvData['cv_location']??$u->location??'','ej. Madrid, España'],
                                    ['cv_linkedin','linkedin','LinkedIn','url',$cvData['cv_linkedin']??$u->linkedin_url??'','https://linkedin.com/in/...'],
                                    ['cv_website','website','Sitio web','url',$cvData['cv_website']??$u->website_url??'','https://tuportfolio.com'],
                                ] as [$id,$field,$label,$type,$val,$ph])
                                <div class="cvweb-field"><label class="cvweb-label" for="{{ $id }}">{{ $label }}</label><input type="{{ $type }}" id="{{ $id }}" class="cvweb-input" placeholder="{{ $ph }}" value="{{ $val }}" oninput="updatePreview('{{ $field }}',this.value)"></div>
                                @endforeach
                            </div>

                            {{-- Experiencia --}}
                            <div class="cvweb-panel" id="cvpanel-experiencia">
                                <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:.875rem;">
                                    <div class="cvweb-panel__label" style="margin:0;">Experiencia laboral</div>
                                    <button type="button" onclick="addEntry('experiencia')" class="cvweb-add-btn"><svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg> Añadir</button>
                                </div>
                                <div id="entries-experiencia">
                                    @php $expEntries = !empty($cvData['experiencia']) ? $cvData['experiencia'] : [[]]; @endphp
                                    @foreach($expEntries as $exp)
                                    <div class="cv-entry">
                                        <button type="button" onclick="removeEntry(this)" class="cv-entry-remove" title="Eliminar"><svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg></button>
                                        <div class="cvweb-grid2" style="gap:.5rem;margin-bottom:.5rem;">
                                            <div><label class="cvweb-label">Empresa</label><input type="text" data-field="empresa" class="cvweb-input" placeholder="Empresa S.L." value="{{ $exp['empresa']??'' }}"></div>
                                            <div><label class="cvweb-label">Cargo</label><input type="text" data-field="cargo" class="cvweb-input" placeholder="Desarrollador Web" value="{{ $exp['cargo']??'' }}"></div>
                                        </div>
                                        <div class="cvweb-field"><label class="cvweb-label">Período</label><input type="text" data-field="periodo" class="cvweb-input" placeholder="2022 – presente" value="{{ $exp['periodo']??'' }}"></div>
                                        <div class="cvweb-field" style="margin:0"><label class="cvweb-label">Descripción</label><textarea data-field="descripcion" class="cvweb-textarea" rows="3" placeholder="Responsabilidades y logros...">{{ $exp['descripcion']??'' }}</textarea></div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>

                            {{-- Formación --}}
                            <div class="cvweb-panel" id="cvpanel-formacion">
                                <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:.875rem;">
                                    <div class="cvweb-panel__label" style="margin:0;">Formación académica</div>
                                    <button type="button" onclick="addEntry('formacion')" class="cvweb-add-btn"><svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg> Añadir</button>
                                </div>
                                <div id="entries-formacion">
                                    @php $formEntries = !empty($cvData['formacion']) ? $cvData['formacion'] : [[]]; @endphp
                                    @foreach($formEntries as $form)
                                    <div class="cv-entry">
                                        <button type="button" onclick="removeEntry(this)" class="cv-entry-remove" title="Eliminar"><svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg></button>
                                        <div class="cvweb-grid2" style="gap:.5rem;margin-bottom:.5rem;">
                                            <div><label class="cvweb-label">Institución</label><input type="text" data-field="institucion" class="cvweb-input" placeholder="Universidad / Centro" value="{{ $form['institucion']??'' }}"></div>
                                            <div><label class="cvweb-label">Título</label><input type="text" data-field="titulo" class="cvweb-input" placeholder="Grado en Informática" value="{{ $form['titulo']??'' }}"></div>
                                        </div>
                                        <div class="cvweb-field"><label class="cvweb-label">Período</label><input type="text" data-field="periodo" class="cvweb-input" placeholder="2018 – 2022" value="{{ $form['periodo']??'' }}"></div>
                                        <div class="cvweb-field" style="margin:0"><label class="cvweb-label">Descripción (opcional)</label><textarea data-field="descripcion" class="cvweb-textarea" rows="2" placeholder="Especialización...">{{ $form['descripcion']??'' }}</textarea></div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>

                            {{-- Habilidades --}}
                            <div class="cvweb-panel" id="cvpanel-habilidades">
                                <div class="cvweb-panel__label">Habilidades</div>
                                @php
                                    $habilidades = $cvData['habilidades'] ?? [];
                                    if (empty($habilidades) && !empty($cvData['cv_skills'])) $habilidades = array_values(array_filter(array_map('trim', explode(',', $cvData['cv_skills']))));
                                    if (!empty($cvData['cv_soft_skills'])) $habilidades = array_merge($habilidades, array_values(array_filter(array_map('trim', explode(',', $cvData['cv_soft_skills'])))));
                                @endphp
                                <div id="skills-chips" class="cvweb-chips">
                                    @foreach($habilidades as $skill)
                                        @if(trim($skill))
                                        <span class="skill-chip-item" data-skill="{{ trim($skill) }}">{{ trim($skill) }}<button type="button" onclick="removeSkill(this)" title="Eliminar">&times;</button></span>
                                        @endif
                                    @endforeach
                                </div>
                                <div style="display:flex;gap:.5rem;">
                                    <input type="text" id="skill-input" class="cvweb-input" placeholder="ej. JavaScript, trabajo en equipo..." style="flex:1;" onkeydown="if(event.key==='Enter'){event.preventDefault();addSkill();}">
                                    <button type="button" onclick="addSkill()" class="cvweb-btn cvweb-btn--ghost cvweb-btn--sm"><svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg> Añadir</button>
                                </div>
                                <span class="cvweb-hint">Escribe y pulsa Enter o el botón.</span>
                            </div>

                            {{-- Proyectos --}}
                            <div class="cvweb-panel" id="cvpanel-proyectos">
                                <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:.875rem;">
                                    <div class="cvweb-panel__label" style="margin:0;">Proyectos</div>
                                    <button type="button" onclick="addEntry('proyectos')" class="cvweb-add-btn"><svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg> Añadir</button>
                                </div>
                                <div id="entries-proyectos">
                                    @php $proyEntries = !empty($cvData['proyectos']) ? $cvData['proyectos'] : [[]]; @endphp
                                    @foreach($proyEntries as $proy)
                                    <div class="cv-entry">
                                        <button type="button" onclick="removeEntry(this)" class="cv-entry-remove" title="Eliminar"><svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg></button>
                                        <div class="cvweb-field"><label class="cvweb-label">Nombre del proyecto</label><input type="text" data-field="nombre" class="cvweb-input" placeholder="Mi Proyecto" value="{{ $proy['nombre']??'' }}"></div>
                                        <div class="cvweb-field"><label class="cvweb-label">Descripción</label><textarea data-field="descripcion" class="cvweb-textarea" rows="2" placeholder="Descripción breve...">{{ $proy['descripcion']??'' }}</textarea></div>
                                        <div class="cvweb-grid2" style="gap:.5rem;">
                                            <div><label class="cvweb-label">URL / Link</label><input type="url" data-field="url" class="cvweb-input" placeholder="https://github.com/..." value="{{ $proy['url']??'' }}"></div>
                                            <div><label class="cvweb-label">Tecnologías</label><input type="text" data-field="tecnologias" class="cvweb-input" placeholder="React, PHP..." value="{{ $proy['tecnologias']??'' }}"><span class="cvweb-hint">Separa con comas</span></div>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>

                            {{-- Idiomas --}}
                            <div class="cvweb-panel" id="cvpanel-idiomas">
                                <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:.875rem;">
                                    <div class="cvweb-panel__label" style="margin:0;">Idiomas</div>
                                    <button type="button" onclick="addEntry('idiomas')" class="cvweb-add-btn"><svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg> Añadir</button>
                                </div>
                                @php
                                    $idiomaEntries = !empty($cvData['idiomas']) ? $cvData['idiomas'] : [[]];
                                    $nivelesOpts = ['── Nivel general ──'=>['Nativo','C2 – Maestría','C1 – Avanzado','B2 – Intermedio alto','B1 – Intermedio','A2 – Básico','A1 – Elemental'],'── Certificados Inglés ──'=>['Cambridge A2 Key (KET)','Cambridge B1 Preliminary (PET)','Cambridge B2 First (FCE)','Cambridge C1 Advanced (CAE)','Cambridge C2 Proficiency (CPE)','IELTS 4.0–5.0 (B1)','IELTS 5.5–6.0 (B2)','IELTS 6.5–7.0 (C1)','IELTS 8.0+ (C2)','TOEFL 42–71 (B1)','TOEFL 72–94 (B2)','TOEFL 95–110 (C1)','TOEFL 111+ (C2)','TOEIC 550–780','TOEIC 785–900','TOEIC 905+'],'── Certificados Español ──'=>['DELE A1','DELE A2','DELE B1','DELE B2','DELE C1','DELE C2','SIELE'],'── Certificados Francés ──'=>['DELF A1','DELF A2','DELF B1','DELF B2','DALF C1','DALF C2','TCF B1','TCF B2+'],'── Certificados Alemán ──'=>['Goethe A1','Goethe A2','Goethe B1','Goethe B2','Goethe C1','Goethe C2'],'── Certificados Chino ──'=>['HSK 1–2 (A1-A2)','HSK 3–4 (B1-B2)','HSK 5–6 (C1-C2)'],'── Otros ──'=>['EOI A2','EOI B1','EOI B2','EOI C1','EOI C2']];
                                @endphp
                                <div id="entries-idiomas">
                                    @foreach($idiomaEntries as $idioma)
                                    <div class="cv-entry">
                                        <button type="button" onclick="removeEntry(this)" class="cv-entry-remove" title="Eliminar"><svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg></button>
                                        <div class="cvweb-grid2" style="gap:.5rem;">
                                            <div><label class="cvweb-label">Idioma</label><input type="text" data-field="idioma" class="cvweb-input" placeholder="ej. Inglés" value="{{ $idioma['idioma']??'' }}"></div>
                                            <div><label class="cvweb-label">Nivel / Certificado</label>
                                                <select data-field="nivel" class="cvweb-select">
                                                    <option value="">Seleccionar...</option>
                                                    @foreach($nivelesOpts as $grupo=>$opciones)<optgroup label="{{ $grupo }}">@foreach($opciones as $op)<option {{ ($idioma['nivel']??'')===$op?'selected':'' }}>{{ $op }}</option>@endforeach</optgroup>@endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>

                        </div>{{-- /panels --}}

                        <div class="cvweb-form__footer">
                            <button type="button" onclick="confirmClearCvData()" class="cvweb-btn cvweb-btn--danger cvweb-btn--sm">
                                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/><path d="M10 11v6M14 11v6"/></svg>
                                Limpiar datos
                            </button>
                            <button type="button" id="btn-save-cv" onclick="saveAndDownloadCv(event)" class="cvweb-btn cvweb-btn--primary">
                                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                                Guardar y descargar ZIP
                            </button>
                        </div>
                    </div>

                </div>{{-- /cv-editor-view --}}

            </div>{{-- /cwFormCol --}}

            {{-- ── COLUMNA DERECHA: PREVIEW ── --}}
            <div class="cvweb-preview" id="cwPreviewCol">
                <div class="cvweb-browser">
                    <div class="cvweb-browser__bar">
                        <div class="cvweb-browser__dots">
                            <span style="background:#ef4444"></span>
                            <span style="background:#f59e0b"></span>
                            <span id="cvweb-fs-toggle" onclick="togglePreviewFullscreen()" title="Ver la plantilla a tamaño completo" style="background:#22c55e;cursor:pointer;"></span>
                        </div>
                        <div class="cvweb-browser__url">{{ $selected ? $selected->slug.'.cvxpress.es' : 'preview' }}</div>
                        @if($selected?->preview_html_url)
                        <button type="button" id="cvweb-fs-btn" onclick="togglePreviewFullscreen()" title="Ver a tamaño completo" style="background:none;border:none;cursor:pointer;color:#9ca3af;display:flex;padding:0;">
                            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M8 3H5a2 2 0 0 0-2 2v3m18 0V5a2 2 0 0 0-2-2h-3m0 18h3a2 2 0 0 0 2-2v-3M3 16v3a2 2 0 0 0 2 2h3"/></svg>
                        </button>
                        @endif
                    </div>
                    <div id="cv-preview-wrap" class="cvweb-browser__screen">
                        @if($selected?->preview_html_url)
                            <iframe id="cv-preview-iframe" src="{{ $selected->preview_html_url }}"
                                    scrolling="auto" sandbox="allow-same-origin allow-scripts"
                                    onload="onPreviewLoaded(this)"></iframe>
                        @else
                            <div style="display:flex;align-items:center;justify-content:center;height:100%;flex-direction:column;gap:.75rem;color:#9ca3af;">
                                <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.2" opacity=".3"><rect x="3" y="3" width="18" height="18" rx="2"/><path d="M3 9h18M9 21V9"/></svg>
                                <span style="font-size:.85rem;">Sin plantilla seleccionada</span>
                            </div>
                        @endif
                    </div>
                    @if($selected)
                    <div class="cvweb-browser__foot">
                        <span>Plantilla: <strong>{{ $selected->name }}</strong></span>
                        <div style="display:flex;align-items:center;gap:1rem;">
                            <button type="button" onclick="openPublishModal()" style="display:inline-flex;align-items:center;gap:.35rem;background:#2563eb;color:#fff;border:none;border-radius:7px;padding:.35rem .8rem;font-size:.75rem;font-weight:700;cursor:pointer;">
                                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="2" y1="12" x2="22" y2="12"/><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/></svg>
                                Publicar web
                            </button>
                            <button type="button" onclick="showCvView('selector')">Cambiar →</button>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

        </div>{{-- /cvweb-editor --}}
        @endif

    </div>{{-- /cvweb-inner --}}
</div>

{{-- ══ AI MODAL ══ --}}
<div id="ai-modal-overlay" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.55);z-index:9999;align-items:center;justify-content:center;padding:1rem;">
    <div style="background:#fff;border-radius:20px;padding:2rem;max-width:520px;width:100%;box-shadow:0 24px 60px rgba(0,0,0,.2);position:relative;max-height:90vh;overflow-y:auto;">
        <button type="button" onclick="closeAiModal()" style="position:absolute;top:1rem;right:1rem;background:none;border:none;cursor:pointer;color:#9ca3af;padding:.25rem;">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
        </button>
        <div id="ai-modal-select">
            <div style="display:flex;align-items:center;gap:.875rem;margin-bottom:1.5rem;">
                <div style="width:48px;height:48px;border-radius:14px;background:linear-gradient(135deg,#6366f1,#8b5cf6);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2"><path d="M12 2a10 10 0 1 0 10 10"/><path d="M12 6v6l4 2"/></svg>
                </div>
                <div>
                    <div style="font-size:1.05rem;font-weight:800;color:#0f172a;">Analizar CV con IA</div>
                    <div style="font-size:.82rem;color:#6b7280;">La IA leerá tu CV y rellenará todos los campos automáticamente</div>
                </div>
            </div>
            @if($u->cv_path)
            <div id="ai-option-existing" onclick="selectAiOption('existing')" style="border:2px solid #e5e7eb;border-radius:14px;padding:1rem 1.25rem;cursor:pointer;margin-bottom:.75rem;transition:all .15s;display:flex;align-items:center;gap:1rem;">
                <div style="width:40px;height:40px;border-radius:10px;background:#eff6ff;color:#1d4ed8;display:flex;align-items:center;justify-content:center;flex-shrink:0;"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg></div>
                <div style="flex:1;"><div style="font-size:.9rem;font-weight:700;color:#0f172a;">Usar CV guardado</div><div style="font-size:.78rem;color:#6b7280;">{{ $u->cv_original_name ?? 'curriculum.pdf' }}</div></div>
                <div id="ai-check-existing" style="display:none;color:#2563eb;"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><polyline points="20 6 9 17 4 12"/></svg></div>
            </div>
            @endif
            <div id="ai-option-new" onclick="selectAiOption('new')" style="border:2px solid #e5e7eb;border-radius:14px;padding:1rem 1.25rem;cursor:pointer;margin-bottom:1.25rem;transition:all .15s;display:flex;align-items:center;gap:1rem;">
                <div style="width:40px;height:40px;border-radius:10px;background:#f0fdf4;color:#16a34a;display:flex;align-items:center;justify-content:center;flex-shrink:0;"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg></div>
                <div style="flex:1;"><div style="font-size:.9rem;font-weight:700;color:#0f172a;">Subir nuevo CV</div><div style="font-size:.78rem;color:#6b7280;">PDF hasta 10 MB</div></div>
                <div id="ai-check-new" style="display:none;color:#2563eb;"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><polyline points="20 6 9 17 4 12"/></svg></div>
            </div>
            <div id="ai-drop-zone" style="display:none;border:2px dashed #d1d5db;border-radius:12px;padding:1.5rem;text-align:center;margin-bottom:1.25rem;cursor:pointer;" ondragover="event.preventDefault();this.style.borderColor='#2563eb'" ondragleave="this.style.borderColor='#d1d5db'" ondrop="onAiDrop(event)">
                <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="#9ca3af" stroke-width="1.5" style="margin:0 auto .5rem;display:block;"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
                <div style="font-size:.875rem;font-weight:600;color:#6b7280;" id="ai-drop-label">Arrastra tu CV aquí o <span style="color:#2563eb;text-decoration:underline;cursor:pointer;" onclick="document.getElementById('ai-file-input').click()">selecciona archivo</span></div>
                <input type="file" id="ai-file-input" accept=".pdf" style="display:none;" onchange="onAiFileSelected(this)">
            </div>
            <div style="border:1px solid #e5e7eb;border-radius:12px;padding:1rem;margin-bottom:1.25rem;background:#f8fafc;">
                <div style="font-size:.83rem;font-weight:700;color:#0f172a;margin-bottom:.625rem;">📷 Foto de perfil <span style="font-weight:400;font-size:.72rem;color:#9ca3af;">(opcional)</span></div>
                <div style="display:flex;align-items:center;gap:.875rem;">
                    <div id="ai-photo-preview" style="width:48px;height:48px;border-radius:9px;overflow:hidden;background:#e5e7eb;flex-shrink:0;display:flex;align-items:center;justify-content:center;">
                        @if($photoUrl)<img src="{{ $photoUrl }}" style="width:100%;height:100%;object-fit:cover;">
                        @else<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#9ca3af" stroke-width="1.5"><circle cx="12" cy="8" r="4"/><path d="M4 20c0-4 3.6-7 8-7s8 3 8 7"/></svg>@endif
                    </div>
                    <div>
                        <button type="button" onclick="document.getElementById('ai-photo-file').click()" class="cvweb-btn cvweb-btn--ghost cvweb-btn--sm" style="margin-bottom:.3rem;">{{ $photoUrl?'Cambiar foto':'Subir foto' }}</button>
                        <input type="file" id="ai-photo-file" accept="image/jpeg,image/png,image/webp" style="display:none;" onchange="uploadProfilePhoto(this)">
                        <span style="font-size:.68rem;color:#9ca3af;">JPG, PNG o WebP · Máx. 3 MB</span>
                    </div>
                </div>
            </div>
            <button type="button" id="ai-analyze-btn" onclick="startAiAnalysis()" class="cvweb-btn cvweb-btn--ai" style="width:100%;" disabled>
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"/></svg>
                Analizar con IA
            </button>
        </div>
        <div id="ai-modal-processing" style="display:none;text-align:center;padding:1rem 0;">
            <div style="width:64px;height:64px;border-radius:50%;background:linear-gradient(135deg,#6366f1,#8b5cf6);margin:0 auto 1.25rem;display:flex;align-items:center;justify-content:center;animation:ai-pulse 1.5s ease-in-out infinite;">
                <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2"><path d="M12 2a10 10 0 1 0 10 10"/><path d="M12 6v6l4 2"/></svg>
            </div>
            <div style="font-size:1.05rem;font-weight:800;color:#0f172a;margin-bottom:.5rem;">Analizando tu CV…</div>
            <div id="ai-processing-step" style="font-size:.85rem;color:#6b7280;margin-bottom:1.5rem;">Extrayendo texto del PDF…</div>
            <div style="background:#f1f5f9;border-radius:999px;height:6px;overflow:hidden;">
                <div id="ai-progress-bar" style="height:100%;background:linear-gradient(90deg,#6366f1,#8b5cf6);border-radius:999px;width:15%;transition:width .5s ease;"></div>
            </div>
        </div>
        <div id="ai-modal-error" style="display:none;text-align:center;padding:1rem 0;">
            <div style="width:56px;height:56px;border-radius:50%;background:#fee2e2;margin:0 auto 1rem;display:flex;align-items:center;justify-content:center;">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#dc2626" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>
            </div>
            <div style="font-size:1rem;font-weight:700;color:#0f172a;margin-bottom:.5rem;">Error en el análisis</div>
            <div id="ai-error-msg" style="font-size:.83rem;color:#dc2626;margin-bottom:1.25rem;"></div>
            <button type="button" onclick="resetAiModal()" class="cvweb-btn cvweb-btn--ghost cvweb-btn--sm">Intentar de nuevo</button>
        </div>
    </div>
</div>

{{-- ══ CONFIRM MODAL (propio) ══ --}}
<div id="confirm-modal-overlay" class="cvweb-confirm-overlay">
    <div class="cvweb-confirm-card">
        <div class="cvweb-confirm-icon" id="confirm-modal-icon">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/><path d="M10 11v6M14 11v6"/></svg>
        </div>
        <h3 class="cvweb-confirm-title" id="confirm-modal-title">¿Seguro?</h3>
        <p class="cvweb-confirm-msg" id="confirm-modal-msg"></p>
        <div class="cvweb-confirm-actions">
            <button type="button" id="confirm-modal-cancel" class="cvweb-btn cvweb-btn--ghost">Cancelar</button>
            <button type="button" id="confirm-modal-ok" class="cvweb-btn cvweb-btn--danger-solid">Confirmar</button>
        </div>
    </div>
</div>

{{-- ══ PUBLISH MODAL ══ --}}
<div id="publish-modal-overlay" class="cvweb-publish-overlay">
    <div class="cvweb-publish-card">
        <button type="button" class="cvweb-publish-close" onclick="closePublishModal()" aria-label="Cerrar">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
        </button>

        <div class="cvweb-publish-head">
            <div class="cvweb-publish-icon">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="1.8"><circle cx="12" cy="12" r="10"/><line x1="2" y1="12" x2="22" y2="12"/><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/></svg>
            </div>
            <div>
                <div class="cvweb-publish-title">Publica tu CV Web</div>
                <div class="cvweb-publish-sub">Elige cómo quieres que tu portfolio sea visible para los demás.</div>
            </div>
        </div>

        @if($activePurchase && $activePurchase->canHost())
            @php $hostingType = $activePurchase->hosting_type; @endphp

            <div id="publish-status-box" class="cvweb-publish-status {{ $hostingType === 'none' ? 'cvweb-publish-status--pending' : '' }}" style="{{ $hostingType === 'none' ? 'display:none;' : 'display:flex;' }}align-items:flex-start;">
                <div id="publish-status-inner">
                    @if($hostingType === 'subdomain')
                        <span style="color:#16a34a;">●</span> <strong>{{ $activePurchase->subdomain }}.cvxpress.es</strong>
                        <div style="font-size:.75rem;color:#6b7280;margin-top:.15rem;">Subdominio gratuito reservado</div>
                    @elseif($hostingType === 'paid_hosting')
                        <span style="color:#1A56DB;">●</span> <strong>Hosting + dominio propio</strong>
                        <div style="font-size:.75rem;color:#6b7280;margin-top:.15rem;">Nos pondremos en contacto para configurarlo</div>
                    @endif
                    <div id="publish-live-row" style="margin-top:.5rem;font-size:.75rem;"></div>
                </div>
                <div style="display:flex;gap:.5rem;flex-shrink:0;">
                    <button type="button" id="publish-republish-btn" onclick="republishSite(this)" class="cvweb-btn cvweb-btn--primary cvweb-btn--sm" style="{{ $hostingType !== 'subdomain' ? 'display:none;' : '' }}">Publicar ahora</button>
                    <button type="button" onclick="resetHosting(this)" class="cvweb-btn cvweb-btn--ghost cvweb-btn--sm">Cambiar</button>
                </div>
            </div>

            <div id="publish-opts" style="{{ $hostingType !== 'none' ? 'display:none;' : '' }}">
                <div class="cvweb-publish-opts">

                    {{-- Subdominio gratis --}}
                    <div class="cvweb-publish-opt">
                        <div class="cvweb-publish-opt__head">
                            <div class="cvweb-publish-opt__icon" style="background:#dcfce7;color:#16a34a;">
                                <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="2" y1="12" x2="22" y2="12"/><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/></svg>
                            </div>
                            <div>
                                <div class="cvweb-publish-opt__name">Subdominio gratis</div>
                                <div class="cvweb-publish-opt__tag" style="color:#16a34a;">Sin coste adicional</div>
                            </div>
                        </div>
                        <div>
                            <label style="font-size:.75rem;font-weight:600;color:#374151;display:block;margin-bottom:.35rem;">Elige tu nombre</label>
                            <div class="cvweb-publish-domain-row">
                                <input type="text" id="publish-subdomain-input"
                                       value="{{ Str::slug($u->name) }}" placeholder="{{ Str::slug($u->name) }}"
                                       maxlength="50" oninput="this.value=this.value.toLowerCase().replace(/[^a-z0-9-]/g,'-')">
                                <span>.cvxpress.es</span>
                            </div>
                        </div>
                        <button type="button" id="publish-subdomain-btn"
                                onclick="submitHosting('subdomain', document.getElementById('publish-subdomain-input').value, this)"
                                class="cvweb-btn cvweb-btn--primary" style="justify-content:center;">
                            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
                            Usar este subdominio
                        </button>
                    </div>

                    {{-- Hosting + dominio propio --}}
                    <div class="cvweb-publish-opt">
                        <div class="cvweb-publish-opt__head">
                            <div class="cvweb-publish-opt__icon" style="background:#dbeafe;color:#1A56DB;">
                                <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="3" width="20" height="14" rx="2"/><line x1="8" y1="21" x2="16" y2="21"/><line x1="12" y1="17" x2="12" y2="21"/></svg>
                            </div>
                            <div>
                                <div class="cvweb-publish-opt__name">Hosting + dominio propio</div>
                                <div class="cvweb-publish-opt__tag" style="color:#1A56DB;">2–3€/mes</div>
                            </div>
                        </div>
                        <ul class="cvweb-publish-opt__feats">
                            @foreach(['Tu dominio (.com, .es…)','SSL incluido','Backups automáticos'] as $f)
                            <li><svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="#1A56DB" stroke-width="3" style="flex-shrink:0;"><polyline points="20 6 9 17 4 12"/></svg>{{ $f }}</li>
                            @endforeach
                        </ul>
                        <button type="button" id="publish-paid-btn"
                                onclick="submitHosting('paid_hosting', null, this)"
                                class="cvweb-btn cvweb-btn--ghost" style="justify-content:center;">
                            Comprar dominio / contratar
                        </button>
                    </div>

                </div>
            </div>
        @elseif($activePurchase)
            {{-- Tiene plan, pero es de solo descarga (categoría web_download) --}}
            <div style="display:flex;gap:.75rem;align-items:flex-start;background:#eff6ff;border:1.5px solid #bfdbfe;border-radius:12px;padding:1rem 1.125rem;margin-bottom:.5rem;">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#1d4ed8" stroke-width="2" style="flex-shrink:0;margin-top:1px;"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>
                <div style="font-size:.83rem;color:#1e3a8a;line-height:1.55;">
                    Tu plan <strong>{{ $activePurchase->plan->name }}</strong> es solo de descarga: puedes usar cualquier plantilla y bajarte el ZIP abajo, pero no incluye publicar con subdominio propio.
                    @if($upgradePlan)
                        <a href="{{ route('checkout.show', $upgradePlan->slug) }}" style="color:#1d4ed8;font-weight:700;">Añadir hosting desde {{ number_format($upgradePlan->price,2,',','.') }}€/año →</a>
                    @endif
                </div>
            </div>
        @else
            <div style="text-align:center;padding:1rem 0 1.5rem;color:#6b7280;font-size:.85rem;">
                Necesitas un plan activo para publicar tu web.
                <a href="{{ route('checkout.show', $plans->first()?->slug ?? '') }}" style="color:#2563eb;font-weight:600;">Ver planes →</a>
            </div>
        @endif

        <div class="cvweb-publish-divider"><span>o si lo prefieres</span></div>

        <div class="cvweb-publish-zip">
            <div class="cvweb-publish-zip__text">
                <strong>Alójala tú mismo/a</strong>
                Descarga la plantilla con tus datos ya puestos, lista para subir a cualquier hosting.
            </div>
            <button type="button" id="publish-zip-btn" onclick="publishModalDownloadZip(this)" class="cvweb-btn cvweb-btn--ghost cvweb-btn--sm">
                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                Descargar ZIP
            </button>
        </div>

        <div class="cvweb-publish-foot">
            ¿Gestionar tu plan y facturación? <a href="{{ route('dashboard') }}#orders">Ir a Mi Plan →</a>
        </div>
    </div>
</div>

{{-- Aviso "sin cambios" al pulsar "Publicar ahora" sin haber tocado nada --}}
<div id="cvweb-nochange-pop" class="cvweb-nochange-pop" hidden>
    No has hecho ningún cambio desde la última publicación.
    <a href="{{ route('dashboard') }}#orders">Ir a Mi Plan →</a>
</div>

<style>@keyframes ai-pulse{0%,100%{transform:scale(1);opacity:1}50%{transform:scale(1.08);opacity:.85}}</style>
@endsection

@push('scripts')
<script>
var _currentPhotoUrl = @json($photoUrl ?? null);
var _CSRF = '{{ csrf_token() }}';
var _hostingUrl = @json($activePurchase ? route('dashboard.purchase.hosting', $activePurchase) : null);

/* ── Views ── */
function showCvView(view) {
    ['cv-selector-view','cv-hub-view','cv-editor-view'].forEach(function(id) {
        var el = document.getElementById(id);
        if (el) el.style.display = 'none';
    });
    var target = document.getElementById('cv-' + view + '-view');
    if (target) target.style.display = '';
    if (view === 'editor') scaleCvPreview();
}

/* ── Tabs ── */
var _SECTION_ANCHORS = {
    presentacion:['#perfil','#profile','#about','#inicio','#home','#hero','.hero','header'],
    contacto:    ['#contacto','#contact','#contactame'],
    experiencia: ['#experiencia','#experience','#trabajo','#work','#exp'],
    formacion:   ['#formacion','#education','#estudios','#educacion'],
    habilidades: ['#habilidades','#skills','#competencias','#abilities'],
    idiomas:     ['#idiomas','#languages','#lenguajes'],
};
function showCvSection(section) {
    var savedY = window.scrollY || window.pageYOffset;
    document.querySelectorAll('[id^="cvpanel-"]').forEach(function(p){ p.classList.remove('cvweb-panel--active'); p.style.display='none'; });
    document.querySelectorAll('[id^="cvtab-"]').forEach(function(t){ t.classList.remove('cvweb-tab--active'); });
    var panel = document.getElementById('cvpanel-'+section);
    var tab   = document.getElementById('cvtab-'+section);
    if (panel) { panel.classList.add('cvweb-panel--active'); panel.style.display=''; }
    if (tab)   { tab.classList.add('cvweb-tab--active'); tab.blur(); }
    requestAnimationFrame(function(){ window.scrollTo(0,savedY); });
    var iframe = document.getElementById('cv-preview-iframe');
    if (!iframe) return;
    var doc; try { doc=iframe.contentDocument; } catch(e){ return; }
    if (!doc) return;
    var anchors = _SECTION_ANCHORS[section]||[];
    for (var i=0;i<anchors.length;i++) { var el=doc.querySelector(anchors[i]); if(el){ el.scrollIntoView({behavior:'smooth',block:'start'}); return; } }
}

/* ── Scale ── */
function scaleCvPreview() {
    var wrap=document.getElementById('cv-preview-wrap');
    var iframe=document.getElementById('cv-preview-iframe');
    if(!wrap||!iframe) return;
    var scale=wrap.offsetWidth/1280; if(!scale) return;
    iframe.style.transform='scale('+scale+')';
    iframe.style.width='1280px';
    iframe.style.height=Math.round((wrap.offsetHeight||580)/scale)+'px';
}
window.addEventListener('resize', scaleCvPreview);

/* ── Pantalla completa de la vista previa ──
   Agranda el mismo iframe (no lo recarga), así que conserva todos los
   datos ya rellenados en el editor. */
var _cvFsExpandIcon='<svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M8 3H5a2 2 0 0 0-2 2v3m18 0V5a2 2 0 0 0-2-2h-3m0 18h3a2 2 0 0 0 2-2v-3M3 16v3a2 2 0 0 0 2 2h3"/></svg>';
var _cvFsShrinkIcon='<svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 3v3a2 2 0 0 1-2 2H4m16-5v3a2 2 0 0 0 2 2h3M3 16h3a2 2 0 0 1 2 2v3m10-5h3a2 2 0 0 0-2 2v3"/></svg>';
function togglePreviewFullscreen(){
    var browser=document.querySelector('.cvweb-browser');
    if(!browser) return;
    var active=browser.classList.toggle('cvweb-browser--fullscreen');
    document.body.classList.toggle('cvweb-fs-active', active);
    var dot=document.getElementById('cvweb-fs-toggle');
    if(dot) dot.title=active?'Salir de pantalla completa':'Ver la plantilla a tamaño completo';
    var btn=document.getElementById('cvweb-fs-btn');
    if(btn){ btn.title=active?'Salir de pantalla completa':'Ver a tamaño completo'; btn.innerHTML=active?_cvFsShrinkIcon:_cvFsExpandIcon; }
    setTimeout(scaleCvPreview,30);
}
document.addEventListener('keydown', function(e){
    if(e.key!=='Escape') return;
    var browser=document.querySelector('.cvweb-browser');
    if(browser && browser.classList.contains('cvweb-browser--fullscreen')){ togglePreviewFullscreen(); return; }
    var pubOv=document.getElementById('publish-modal-overlay');
    if(pubOv && pubOv.style.display==='flex') closePublishModal();
});

/* ── Modal "Publicar web" ── */
function openPublishModal(){
    var ov=document.getElementById('publish-modal-overlay');
    if(!ov) return;
    ov.style.display='flex';
    document.body.style.overflow='hidden';
}
function closePublishModal(){
    var ov=document.getElementById('publish-modal-overlay');
    if(!ov) return;
    ov.style.display='none';
    document.body.style.overflow='';
}

function _renderHostingStatus(hostingType, subdomain){
    var statusBox=document.getElementById('publish-status-box');
    var optsBox=document.getElementById('publish-opts');
    var inner=document.getElementById('publish-status-inner');
    var republishBtn=document.getElementById('publish-republish-btn');
    if(hostingType==='none'){
        if(statusBox) statusBox.style.display='none';
        if(optsBox) optsBox.style.display='';
        return;
    }
    if(optsBox) optsBox.style.display='none';
    if(statusBox){ statusBox.style.display='flex'; statusBox.classList.remove('cvweb-publish-status--pending'); }
    if(republishBtn) republishBtn.style.display=(hostingType==='subdomain')?'':'none';
    if(inner){
        var head = hostingType==='subdomain'
            ? '<span style="color:#16a34a;">●</span> <strong>'+_esc(subdomain)+'.cvxpress.es</strong><div style="font-size:.75rem;color:#6b7280;margin-top:.15rem;">Subdominio gratuito reservado</div>'
            : '<span style="color:#1A56DB;">●</span> <strong>Hosting + dominio propio</strong><div style="font-size:.75rem;color:#6b7280;margin-top:.15rem;">Nos pondremos en contacto para configurarlo</div>';
        inner.innerHTML = head + '<div id="publish-live-row" style="margin-top:.5rem;font-size:.75rem;"></div>';
    }
}

function _setPublishLiveRow(html){
    var row=document.getElementById('publish-live-row');
    if(row) row.innerHTML=html;
}

function submitHosting(hostingType, subdomainValue, btn){
    if(!_hostingUrl){ alert('Necesitas un plan activo para publicar tu web.'); return; }
    if(hostingType==='subdomain'){
        subdomainValue=(subdomainValue||'').trim().toLowerCase().replace(/[^a-z0-9-]+/g,'-').replace(/^-+|-+$/g,'');
        if(!subdomainValue){ alert('Escribe un nombre para tu subdominio.'); return; }
    }
    var orig=btn?btn.innerHTML:'';
    if(btn){ btn.disabled=true; btn.style.opacity='.6'; btn.textContent='Guardando…'; }

    // OJO: nunca enviar FormData (multipart) con method PATCH — PHP no
    // procesa el body multipart en peticiones que no son POST, así que el
    // servidor recibía los campos vacíos y fallaba la validación. JSON sí
    // funciona con cualquier método.
    fetch(_hostingUrl,{
        method:'PATCH',
        headers:{ 'Content-Type':'application/json', 'X-CSRF-TOKEN':_CSRF, 'Accept':'application/json' },
        body:JSON.stringify({ hosting_type:hostingType, subdomain:subdomainValue||null })
    }).then(function(r){
        if(!r.ok) return r.json().catch(function(){return{};}).then(function(j){ throw new Error(j.message||('Error '+r.status)); });
        return r.json();
    }).then(function(data){
        _renderHostingStatus(data.hosting_type, data.subdomain);
        if(btn){ btn.disabled=false; btn.style.opacity=''; btn.innerHTML=orig; }
        // Al reservar el subdominio, publicamos la web ya mismo con los datos actuales.
        if(data.hosting_type==='subdomain') republishSite();
    }).catch(function(err){
        if(btn){ btn.disabled=false; btn.style.opacity=''; btn.innerHTML=orig; }
        alert(err.message||'No se pudo guardar la configuración.');
    });
}

function resetHosting(btn){ submitHosting('none', null, btn); }

/* Genera el sitio a partir de la vista previa actual y lo publica en el
   subdominio ya reservado. Se llama sola tras reservar el subdominio, y
   también desde el botón "Publicar ahora" para volcar cambios posteriores. */
function republishSite(btn){
    var origBtn=btn?btn.innerHTML:'';
    if(btn){ btn.disabled=true; btn.style.opacity='.6'; btn.textContent='Publicando…'; }
    _setPublishLiveRow('<span style="color:#9ca3af;">Publicando tu web…</span>');

    publishSitePromise().then(function(data){
        _setPublishLiveRow('<span style="color:#16a34a;">●</span> Publicada · <a href="'+_esc(data.url)+'" target="_blank" rel="noopener" style="color:#2563eb;font-weight:600;text-decoration:none;">Ver mi web ↗</a>');
        // Ya estaba publicado tal cual (no había ningún cambio) — lo avisamos
        // con un aviso pequeño junto al botón, sin interrumpir con un modal grande.
        if(data.unchanged && btn) _showNoChangePopover(btn);
        if(btn){ btn.disabled=false; btn.style.opacity=''; btn.innerHTML=origBtn; }
    }).catch(function(err){
        _setPublishLiveRow('<span style="color:#dc2626;">No se pudo publicar: '+_esc(err.message||'inténtalo de nuevo')+'</span>');
        if(btn){ btn.disabled=false; btn.style.opacity=''; btn.innerHTML=origBtn; }
    });
}

var _noChangeTimer=null;
function _showNoChangePopover(anchorEl){
    var pop=document.getElementById('cvweb-nochange-pop');
    if(!pop||!anchorEl) return;
    var r=anchorEl.getBoundingClientRect();
    var w=240;
    pop.style.left=Math.max(8,Math.min(r.left, window.innerWidth-w-8))+'px';
    pop.style.top='auto';
    pop.style.bottom=(window.innerHeight-r.top+8)+'px';
    pop.hidden=false;
    requestAnimationFrame(function(){ pop.classList.add('is-visible'); });
    clearTimeout(_noChangeTimer);
    _noChangeTimer=setTimeout(_hideNoChangePopover,5000);
}
function _hideNoChangePopover(){
    var pop=document.getElementById('cvweb-nochange-pop');
    if(!pop) return;
    pop.classList.remove('is-visible');
    setTimeout(function(){ pop.hidden=true; },200);
}
document.addEventListener('click',function(e){
    var pop=document.getElementById('cvweb-nochange-pop');
    if(pop && !pop.hidden && e.target!==pop && !pop.contains(e.target) && e.target.id!=='publish-republish-btn'){
        clearTimeout(_noChangeTimer);
        _hideNoChangePopover();
    }
});

function publishModalDownloadZip(btn){
    var orig=btn?btn.innerHTML:'';
    if(btn){ btn.disabled=true; btn.style.opacity='.6'; btn.textContent='Generando…'; }
    downloadCvWebZipPromise().then(function(){
        if(btn){ btn.disabled=false; btn.style.opacity=''; btn.innerHTML=orig; }
    }).catch(function(err){
        if(btn){ btn.disabled=false; btn.style.opacity=''; btn.innerHTML=orig; }
        alert(err.message||'No se pudo generar el ZIP.');
    });
}

document.getElementById('publish-modal-overlay')?.addEventListener('click',function(e){ if(e.target===this) closePublishModal(); });

/* ── Tab scroll fade ── */
(function(){
    var t=document.getElementById('cwTabs'); var w=document.getElementById('cwTabsWrap');
    if(!t||!w) return;
    function u(){ w.classList.toggle('scrolled-end', t.scrollLeft+t.clientWidth>=t.scrollWidth-4); }
    t.addEventListener('scroll',u,{passive:true}); u();
})();

/* ── Mobile toggle ── */
function cwToggle(view, btn) {
    document.querySelectorAll('#cwMobileToggle button').forEach(function(b){ b.classList.remove('active'); });
    btn.classList.add('active');
    var fc=document.getElementById('cwFormCol'); var pc=document.getElementById('cwPreviewCol');
    if(view==='editor'){ if(fc) fc.style.display=''; if(pc) pc.style.display='none'; }
    else { if(fc) fc.style.display='none'; if(pc){ pc.style.display=''; pc.classList.add('show'); } setTimeout(scaleCvPreview,80); }
}

/* ── Helpers ── */
function _esc(str){ return String(str||'').replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;'); }

function _buildExperienciaHTML(e){ if(!e.length) return '<p style="color:#999;font-style:italic;padding:.5rem 0;">Sin experiencia añadida.</p>'; return e.map(function(x){ return '<div class="timeline-item"><div class="timeline-date">'+_esc(x.periodo)+'</div><div class="timeline-content"><h3>'+_esc(x.cargo)+'</h3><h4>'+_esc(x.empresa)+'</h4><p>'+_esc(x.descripcion||'').replace(/\n/g,'<br>')+'</p></div></div>'; }).join(''); }
function _buildFormacionHTML(e){ if(!e.length) return ''; return e.map(function(x){ return '<div class="education-item"><div class="education-icon">🎓</div><div class="education-content"><h3>'+_esc(x.titulo)+'</h3><h4>'+_esc(x.institucion)+'</h4><p class="education-date">'+_esc(x.periodo)+'</p><p class="education-description">'+_esc(x.descripcion||'')+'</p></div></div>'; }).join(''); }
function _buildProyectosHTML(e){ return e.map(function(x){ if(!x.nombre&&!x.descripcion) return ''; var nm=(x.url&&x.url.trim())?'<a href="'+_esc(x.url)+'" class="cv-project-link" target="_blank" rel="noopener">'+_esc(x.nombre)+'</a>':_esc(x.nombre); var tags=(x.tecnologias||'').split(',').map(function(t){return t.trim();}).filter(Boolean); var th=tags.length?'<div class="cv-project-tags">'+tags.map(function(t){return '<span class="cv-project-tag">'+_esc(t)+'</span>';}).join('')+'</div>':''; var desc=x.descripcion?'<p class="cv-project-desc">'+_esc(x.descripcion).replace(/\n/g,'<br>')+'</p>':''; return '<div class="cv-project-item"><div class="cv-project-name">'+nm+'</div>'+desc+th+'</div>'; }).filter(Boolean).join(''); }
function _buildIdiomasHTML(e){ return e.map(function(x){ if(!x.idioma) return ''; return '<div style="display:flex;align-items:center;justify-content:space-between;padding:.5rem 0;border-bottom:1px solid rgba(255,255,255,.08);"><span style="font-weight:600;">'+_esc(x.idioma)+'</span><span style="opacity:.75;">'+_esc(x.nivel||'')+'</span></div>'; }).join(''); }
function _buildHabilidadesHTML(s){ if(!s.length) return ''; return s.map(function(x){ return '<span class="cv-skill-chip">'+_esc(x)+'</span>'; }).join(''); }

function _getSkills(){ var s=[]; document.querySelectorAll('#skills-chips .skill-chip-item').forEach(function(c){ var v=c.dataset.skill||c.textContent.replace('×','').trim(); if(v) s.push(v); }); return s; }

function _serializeEntries(type){ var r=[]; var c=document.getElementById('entries-'+type); if(!c) return r; c.querySelectorAll('.cv-entry').forEach(function(e){ var o={}; e.querySelectorAll('[data-field]').forEach(function(el){ o[el.dataset.field]=el.value; }); if(Object.values(o).some(function(v){return(v||'').trim();})) r.push(o); }); return r; }
function _toggleWrap(doc,type,show){ doc.querySelectorAll('[data-cv-section-wrap="'+type+'"]').forEach(function(el){ el.style.display=show?'':'none'; }); }

/* ── Live update ── */
var _cvTimer=null;
function updatePreview(field,value){ clearTimeout(_cvTimer); _cvTimer=setTimeout(function(){ var iframe=document.getElementById('cv-preview-iframe'); if(!iframe) return; var doc; try{doc=iframe.contentDocument;}catch(e){return;} if(!doc) return; doc.querySelectorAll('[data-cv="'+field+'"]').forEach(function(el){ if(el.tagName==='A'){ el.textContent=value; if(field==='email') el.href='mailto:'+value; else if(field==='phone') el.href='tel:'+value; else if(field==='linkedin'||field==='website') el.href=value; } else el.textContent=value; }); },180); }

var _entriesTimers={};
function updateEntriesPreview(type){ clearTimeout(_entriesTimers[type]); _entriesTimers[type]=setTimeout(function(){ var iframe=document.getElementById('cv-preview-iframe'); if(!iframe) return; var doc; try{doc=iframe.contentDocument;}catch(e){return;} if(!doc) return; var entries=_serializeEntries(type); var section=doc.querySelector('[data-cv-section="'+type+'"]'); if(!section) return; if(type==='experiencia') section.innerHTML=_buildExperienciaHTML(entries); if(type==='formacion') section.innerHTML=_buildFormacionHTML(entries); if(type==='proyectos'){var h=_buildProyectosHTML(entries);section.innerHTML=h;_toggleWrap(doc,'proyectos',h.trim()!=='');} if(type==='idiomas') section.innerHTML=_buildIdiomasHTML(entries); },220); }

['experiencia','formacion','proyectos','idiomas'].forEach(function(type){ var c=document.getElementById('entries-'+type); if(!c) return; c.addEventListener('input',function(){updateEntriesPreview(type);}); c.addEventListener('change',function(){updateEntriesPreview(type);}); });

function fillAllPreview(){ var iframe=document.getElementById('cv-preview-iframe'); if(!iframe) return; var doc; try{doc=iframe.contentDocument;}catch(e){return;} if(!doc||!doc.body) return; var map={name:'cv_name',job_title:'cv_job_title',bio:'cv_bio',email:'cv_email',phone:'cv_phone',location:'cv_location',linkedin:'cv_linkedin',website:'cv_website'}; Object.keys(map).forEach(function(f){ var inp=document.getElementById(map[f]); if(!inp||!inp.value.trim()) return; doc.querySelectorAll('[data-cv="'+f+'"]').forEach(function(el){ if(el.tagName==='A'){el.textContent=inp.value;if(f==='email')el.href='mailto:'+inp.value;if(f==='phone')el.href='tel:'+inp.value;if(f==='linkedin'||f==='website')el.href=inp.value;}else el.textContent=inp.value; }); }); if(_currentPhotoUrl) doc.querySelectorAll('[data-cv="photo"]').forEach(function(el){if(el.tagName==='IMG')el.src=_currentPhotoUrl;}); ['experiencia','formacion','proyectos','idiomas'].forEach(function(type){ var entries=_serializeEntries(type); var section=doc.querySelector('[data-cv-section="'+type+'"]'); if(!section) return; if(type==='experiencia'&&entries.length) section.innerHTML=_buildExperienciaHTML(entries); if(type==='formacion'&&entries.length) section.innerHTML=_buildFormacionHTML(entries); if(type==='proyectos'){var h=_buildProyectosHTML(entries);section.innerHTML=h;_toggleWrap(doc,'proyectos',h.trim()!=='');} if(type==='idiomas'&&entries.length) section.innerHTML=_buildIdiomasHTML(entries); }); var hab=doc.querySelector('[data-cv-section="habilidades"]'); if(hab) hab.innerHTML=_buildHabilidadesHTML(_getSkills()); }

function onPreviewLoaded(iframe){ scaleCvPreview(); setTimeout(fillAllPreview,50); try{ var doc=iframe.contentDocument; if(!doc) return; doc.addEventListener('click',function(e){ var link=e.target.closest?e.target.closest('a[href]'):(e.target.tagName==='A'?e.target:null); if(!link) return; var href=link.getAttribute('href')||''; if(!href.startsWith('#')&&!href.startsWith('mailto:')&&!href.startsWith('tel:')&&href!==''&&href!=='/') e.preventDefault(); }); }catch(e){} }

/* ── Add/Remove entries ── */
function addEntry(type){ var c=document.getElementById('entries-'+type); if(!c) return; var t=c.querySelector('.cv-entry'); if(!t) return; var clone=t.cloneNode(true); clone.querySelectorAll('input,textarea').forEach(function(el){el.value='';}); clone.querySelectorAll('select').forEach(function(el){el.selectedIndex=0;}); c.appendChild(clone); var first=clone.querySelector('input,textarea,select'); if(first){first.focus();first.scrollIntoView({behavior:'smooth',block:'nearest'});} }
function removeEntry(btn){ var entry=btn.closest?btn.closest('.cv-entry'):btn.parentElement; if(!entry) return; var cont=entry.parentElement; var type=cont.id?cont.id.replace('entries-',''):null; if(cont.querySelectorAll('.cv-entry').length<=1){ entry.querySelectorAll('input,textarea').forEach(function(el){el.value='';}); entry.querySelectorAll('select').forEach(function(el){el.selectedIndex=0;}); if(type) updateEntriesPreview(type); return; } entry.style.opacity='0'; entry.style.transition='opacity .2s'; setTimeout(function(){ entry.remove(); if(type) updateEntriesPreview(type); },200); }

/* ── Skills ── */
var _skillsTimer=null;
function addSkill(){ var inp=document.getElementById('skill-input'); if(!inp) return; var vals=inp.value.split(',').map(function(s){return s.trim();}).filter(Boolean); if(!vals.length) return; var c=document.getElementById('skills-chips'); vals.forEach(function(v){ if(!v) return; var chip=document.createElement('span'); chip.className='skill-chip-item'; chip.dataset.skill=v; chip.innerHTML=_esc(v)+'<button type="button" onclick="removeSkill(this)" title="Eliminar">&times;</button>'; if(c) c.appendChild(chip); }); inp.value=''; updateSkillsPreview(); }
function removeSkill(btn){ var chip=btn.closest?btn.closest('.skill-chip-item'):btn.parentElement; if(!chip) return; chip.style.opacity='0'; chip.style.transition='opacity .15s'; setTimeout(function(){chip.remove();updateSkillsPreview();},150); }
function updateSkillsPreview(){ clearTimeout(_skillsTimer); _skillsTimer=setTimeout(function(){ var iframe=document.getElementById('cv-preview-iframe'); if(!iframe) return; var doc; try{doc=iframe.contentDocument;}catch(e){return;} if(!doc) return; var s=doc.querySelector('[data-cv-section="habilidades"]'); if(s) s.innerHTML=_buildHabilidadesHTML(_getSkills()); },220); }

/* ── Photo ── */
function uploadProfilePhoto(input){ var file=input.files[0]; if(!file) return; if(file.size>3*1024*1024){alert('La imagen no puede superar 3 MB.');return;} var fd=new FormData(); fd.append('photo',file); fd.append('_token',_CSRF); fetch('{{ route("dashboard.photo.upload") }}',{method:'POST',body:fd}).then(function(r){return r.json();}).then(function(data){ if(!data.url) return; _currentPhotoUrl=data.url; ['ai-photo-preview','editor-photo-preview'].forEach(function(id){ var wrap=document.getElementById(id); if(!wrap) return; var img=wrap.querySelector('img'); if(img) img.src=data.url; else wrap.innerHTML='<img src="'+data.url+'" style="width:100%;height:100%;object-fit:cover;">'; }); var iframe=document.getElementById('cv-preview-iframe'); if(iframe){var doc;try{doc=iframe.contentDocument;}catch(e){} if(doc) doc.querySelectorAll('[data-cv="photo"]').forEach(function(el){if(el.tagName==='IMG')el.src=data.url;});} }).catch(function(){alert('Error al subir la foto.');}); }

/* ── Save / Clear ── */

/* Guarda cv_data en el servidor. Devuelve una promesa (no toca el botón). */
function saveCvDataPromise(){
    var data={};
    ['cv_name','cv_job_title','cv_bio','cv_email','cv_phone','cv_location','cv_linkedin','cv_website'].forEach(function(id){ var el=document.getElementById(id); if(el) data[id]=el.value; });
    data.habilidades=_getSkills();
    data.experiencia=_serializeEntries('experiencia');
    data.formacion=_serializeEntries('formacion');
    data.proyectos=_serializeEntries('proyectos');
    data.idiomas=_serializeEntries('idiomas');
    return fetch('{{ route("dashboard.cv-data.update") }}',{
        method:'POST',
        headers:{ 'Content-Type':'application/json','X-CSRF-TOKEN':_CSRF,'Accept':'application/json' },
        body:JSON.stringify({ cv_data:data })
    }).then(function(r){ if(!r.ok) throw new Error('save-failed'); return r.json(); });
}

/* ── Modal de confirmación propio (sustituye a confirm() nativo) ── */
function showConfirmModal(opts){
    var ov=document.getElementById('confirm-modal-overlay');
    if(!ov){ if(confirm(opts.message||'')&&typeof opts.onConfirm==='function') opts.onConfirm(); return; }

    document.getElementById('confirm-modal-title').textContent=opts.title||'¿Estás seguro?';
    document.getElementById('confirm-modal-msg').textContent=opts.message||'';
    var icon=document.getElementById('confirm-modal-icon');
    icon.className='cvweb-confirm-icon'+(opts.danger===false?' cvweb-confirm-icon--info':'');
    var okBtn=document.getElementById('confirm-modal-ok');
    var cancelBtn=document.getElementById('confirm-modal-cancel');
    okBtn.textContent=opts.confirmLabel||'Confirmar';
    okBtn.className='cvweb-btn '+(opts.danger===false?'cvweb-btn--primary':'cvweb-btn--danger-solid');
    cancelBtn.textContent=opts.cancelLabel||'Cancelar';

    function cleanup(){
        ov.style.display='none';
        document.body.style.overflow='';
        okBtn.removeEventListener('click',onOk);
        cancelBtn.removeEventListener('click',onCancel);
        ov.removeEventListener('click',onOverlay);
        document.removeEventListener('keydown',onKey);
    }
    function onOk(){ cleanup(); if(typeof opts.onConfirm==='function') opts.onConfirm(); }
    function onCancel(){ cleanup(); if(typeof opts.onCancel==='function') opts.onCancel(); }
    function onOverlay(e){ if(e.target===ov) onCancel(); }
    function onKey(e){ if(e.key==='Escape') onCancel(); }

    okBtn.addEventListener('click',onOk);
    cancelBtn.addEventListener('click',onCancel);
    ov.addEventListener('click',onOverlay);
    document.addEventListener('keydown',onKey);

    ov.style.display='flex';
    document.body.style.overflow='hidden';
}

function confirmClearCvData(){
    showConfirmModal({
        title:'¿Borrar todos los datos del CV?',
        message:'Se eliminarán tus textos, experiencia, formación, habilidades y la foto de perfil. Esta acción no se puede deshacer.',
        confirmLabel:'Sí, borrar todo',
        cancelLabel:'Cancelar',
        onConfirm:function(){
            fetch('{{ route("dashboard.cv-data.clear") }}',{method:'DELETE',headers:{'X-CSRF-TOKEN':_CSRF,'Accept':'application/json'}}).then(function(r){return r.json();}).then(function(){ ['cv_name','cv_job_title','cv_bio','cv_email','cv_phone','cv_location','cv_linkedin','cv_website'].forEach(function(id){var el=document.getElementById(id);if(el)el.value='';}); var cc=document.getElementById('skills-chips'); if(cc) cc.innerHTML=''; ['experiencia','formacion','proyectos'].forEach(function(type){ var c=document.getElementById('entries-'+type); if(!c) return; var entries=c.querySelectorAll('.cv-entry'); for(var i=1;i<entries.length;i++) entries[i].remove(); var first=c.querySelector('.cv-entry'); if(first){first.querySelectorAll('input,textarea').forEach(function(el){el.value='';}); first.querySelectorAll('select').forEach(function(el){el.selectedIndex=0;});} }); _currentPhotoUrl=''; var iframe=document.getElementById('cv-preview-iframe'); if(iframe) iframe.src=iframe.src; }).catch(function(){alert('Error al limpiar los datos.');});
        }
    });
}

/* ── Captura del HTML "en vivo" del iframe (plantilla + datos ya aplicados) ──
   La usan tanto la descarga ZIP como la publicación por subdominio. */
function captureLivePreviewHtml(){
    var iframe=document.getElementById('cv-preview-iframe');
    if(!iframe) return Promise.reject(new Error('No hay vista previa disponible.'));
    var doc; try{ doc=iframe.contentDocument; }catch(e){ doc=null; }
    if(!doc||!doc.documentElement) return Promise.reject(new Error('La vista previa aún no ha cargado del todo. Espera unos segundos e inténtalo otra vez.'));

    try{ fillAllPreview(); }catch(e){}

    return new Promise(function(resolve){
        setTimeout(function(){
            var clone=doc.documentElement.cloneNode(true);
            clone.querySelectorAll('[contenteditable]').forEach(function(el){ el.removeAttribute('contenteditable'); });
            clone.querySelectorAll('script[data-cvweb-editor]').forEach(function(el){ el.remove(); });
            resolve('<!DOCTYPE html>\n'+clone.outerHTML);
        },60);
    });
}

/* ── Descargar plantilla + cambios como ZIP ── */

/* Genera y descarga el ZIP a partir del iframe de vista previa. Devuelve una promesa. */
function downloadCvWebZipPromise(){
    return captureLivePreviewHtml().then(function(html){
        return fetch('{{ route("cv-web.download-zip") }}',{
            method:'POST',
            headers:{ 'Content-Type':'application/json','X-CSRF-TOKEN':_CSRF,'Accept':'application/json' },
            body:JSON.stringify({ html:html })
        });
    }).then(function(r){
        if(!r.ok) return r.json().catch(function(){return{};}).then(function(j){ throw new Error(j.message||('Error '+r.status)); });
        var name='cv-web.zip';
        var cd=r.headers.get('Content-Disposition')||'';
        var m=cd.match(/filename="?([^"]+)"?/); if(m) name=m[1];
        return r.blob().then(function(blob){ return { blob:blob, name:name }; });
    }).then(function(res){
        var url=URL.createObjectURL(res.blob);
        var a=document.createElement('a');
        a.href=url; a.download=res.name;
        document.body.appendChild(a); a.click();
        setTimeout(function(){ URL.revokeObjectURL(url); a.remove(); },1000);
    });
}

/* ── Publicar (o actualizar) la web en el subdominio reservado ── */
function publishSitePromise(){
    if(!_hostingUrl) return Promise.reject(new Error('Necesitas un plan activo para publicar tu web.'));
    return captureLivePreviewHtml().then(function(html){
        return fetch('{{ route("cv-web.publish") }}',{
            method:'POST',
            headers:{ 'Content-Type':'application/json','X-CSRF-TOKEN':_CSRF,'Accept':'application/json' },
            body:JSON.stringify({ html:html })
        });
    }).then(function(r){
        if(!r.ok) return r.json().catch(function(){return{};}).then(function(j){ throw new Error(j.message||('Error '+r.status)); });
        return r.json();
    });
}

/* Botón único: guarda cv_data y, tanto si el guardado va bien como si falla,
   genera y descarga el ZIP con el estado actual de la vista previa. */
function saveAndDownloadCv(e){
    if(e) e.preventDefault();
    var btn=document.getElementById('btn-save-cv');
    var orig=btn?btn.innerHTML:'';
    if(btn){ btn.disabled=true; btn.style.opacity='.6'; btn.textContent='Guardando…'; }

    saveCvDataPromise().catch(function(){ /* seguimos con la descarga aunque falle el guardado */ }).then(function(){
        if(btn) btn.textContent='Generando ZIP…';
        return downloadCvWebZipPromise();
    }).then(function(){
        if(btn){
            btn.disabled=false; btn.style.opacity='';
            btn.innerHTML='<svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg> Guardado y descargado';
            btn.style.background='#16a34a';
            setTimeout(function(){ btn.innerHTML=orig; btn.style.background=''; },2500);
        }
    }).catch(function(err){
        if(btn){ btn.disabled=false; btn.style.opacity=''; btn.innerHTML=orig; }
        alert(err.message||'No se pudo completar la acción.');
    });
}

/* ══ AI MODAL ══ */
var _aiSource=null,_aiFile=null,_aiParseId=null,_aiPollTimer=null,_aiStepIdx=0;
var _aiSteps=[[15,'Extrayendo texto del PDF…'],[35,'Procesando el contenido…'],[55,'Enviando a la IA…'],[75,'La IA está analizando tu CV…'],[88,'Estructurando los datos…'],[95,'Casi listo…']];

function openAiModal(){ resetAiModal(); document.getElementById('ai-modal-overlay').style.display='flex'; document.body.style.overflow='hidden'; @if($u->cv_path) selectAiOption('existing'); @endif }
function closeAiModal(){ clearInterval(_aiPollTimer); document.getElementById('ai-modal-overlay').style.display='none'; document.body.style.overflow=''; }
function resetAiModal(){ _aiSource=null;_aiFile=null;_aiParseId=null;_aiStepIdx=0; clearInterval(_aiPollTimer); _showAiState('select'); ['existing','new'].forEach(function(o){var el=document.getElementById('ai-option-'+o);var ch=document.getElementById('ai-check-'+o);if(el){el.style.borderColor='#e5e7eb';el.style.background='';}if(ch)ch.style.display='none';}); var drop=document.getElementById('ai-drop-zone');if(drop)drop.style.display='none'; var btn=document.getElementById('ai-analyze-btn');if(btn)btn.disabled=true; _aiFile=null; var lbl=document.getElementById('ai-drop-label');if(lbl)lbl.innerHTML='Arrastra tu CV aquí o <span style="color:#2563eb;text-decoration:underline;cursor:pointer;" onclick="document.getElementById(\'ai-file-input\').click()">selecciona archivo</span>'; }
function _showAiState(state){ ['select','processing','error'].forEach(function(s){var el=document.getElementById('ai-modal-'+s);if(el)el.style.display=s===state?'':'none';}); }
function selectAiOption(opt){ _aiSource=opt; ['existing','new'].forEach(function(o){var c=document.getElementById('ai-option-'+o);var ch=document.getElementById('ai-check-'+o);if(!c)return;c.style.borderColor=o===opt?'#2563eb':'#e5e7eb';c.style.background=o===opt?'#f5f3ff':'';if(ch)ch.style.display=o===opt?'':'none';}); var drop=document.getElementById('ai-drop-zone');if(drop)drop.style.display=opt==='new'?'':'none'; var btn=document.getElementById('ai-analyze-btn');if(btn)btn.disabled=opt==='new'?(_aiFile===null):false; }
function onAiFileSelected(input){ if(input.files&&input.files[0]){_aiFile=input.files[0];var lbl=document.getElementById('ai-drop-label');if(lbl)lbl.textContent='✓ '+_aiFile.name;var btn=document.getElementById('ai-analyze-btn');if(btn)btn.disabled=false;} }
function onAiDrop(e){ e.preventDefault(); document.getElementById('ai-drop-zone').style.borderColor='#d1d5db'; var file=e.dataTransfer.files[0]; if(file&&file.type==='application/pdf'){_aiFile=file;var lbl=document.getElementById('ai-drop-label');if(lbl)lbl.textContent='✓ '+file.name;var btn=document.getElementById('ai-analyze-btn');if(btn)btn.disabled=false;} }
function startAiAnalysis(){ if(!_aiSource) return; if(_aiSource==='new'&&!_aiFile) return; _showAiState('processing'); _aiStepIdx=0; _advanceAiStep(); var fd=new FormData(); fd.append('source',_aiSource); fd.append('_token',_CSRF); if(_aiSource==='new') fd.append('cv',_aiFile); fetch('{{ route("dashboard.cv.parse") }}',{method:'POST',headers:{'Accept':'application/json'},body:fd}).then(function(r){return r.json();}).then(function(data){if(data.error){_showAiError(data.error);return;} _aiParseId=data.parse_id; _startPolling();}).catch(function(){_showAiError('Error de conexión. Inténtalo de nuevo.');}); }
function _advanceAiStep(){ if(_aiStepIdx>=_aiSteps.length) return; var step=_aiSteps[_aiStepIdx++]; var bar=document.getElementById('ai-progress-bar'); var lbl=document.getElementById('ai-processing-step'); if(bar)bar.style.width=step[0]+'%'; if(lbl)lbl.textContent=step[1]; if(_aiStepIdx<_aiSteps.length) setTimeout(_advanceAiStep,2500+Math.random()*1500); }
function _startPolling(){ _aiPollTimer=setInterval(function(){ if(!_aiParseId) return; fetch('{{ url("dashboard/cv/parse") }}/'+_aiParseId+'/status',{headers:{'Accept':'application/json','X-CSRF-TOKEN':_CSRF}}).then(function(r){return r.json();}).then(function(data){ if(data.status==='completed'){clearInterval(_aiPollTimer);_onAiCompleted(data.cv_data||{});}else if(data.status==='failed'){clearInterval(_aiPollTimer);_showAiError(data.error||'El análisis falló.');} }).catch(function(){}); },2500); }
function _onAiCompleted(cvData){ var bar=document.getElementById('ai-progress-bar');var lbl=document.getElementById('ai-processing-step');if(bar)bar.style.width='100%';if(lbl)lbl.textContent='¡Análisis completado!'; setTimeout(function(){closeAiModal();_applyAiData(cvData);},800); }
function _showAiError(msg){ _showAiState('error'); var el=document.getElementById('ai-error-msg');if(el)el.textContent=msg; }

function _nullClean(v){ if(!v) return ''; var t=String(v).trim(); if(t.toLowerCase()==='null') return ''; if(/^null\s*[–\-]\s*null$/i.test(t)) return ''; return t; }

function _rebuildEntries(type,entries,fieldsFn){ var c=document.getElementById('entries-'+type);if(!c) return; var ex=c.querySelectorAll('.cv-entry'); for(var i=1;i<ex.length;i++) ex[i].remove(); if(!entries.length) return; var tmpl=c.querySelector('.cv-entry');if(!tmpl) return; var f0=fieldsFn(entries[0]); tmpl.querySelectorAll('[data-field]').forEach(function(inp,idx){if(f0[idx]!==undefined)inp.value=f0[idx];}); for(var j=1;j<entries.length;j++){var clone=tmpl.cloneNode(true);var f=fieldsFn(entries[j]);clone.querySelectorAll('[data-field]').forEach(function(inp,idx){if(f[idx]!==undefined)inp.value=f[idx];});c.appendChild(clone);} }

function _setNivelSelect(sel,nivel){ if(!nivel) return; var n=nivel.trim();var nl=n.toLowerCase(); for(var o=0;o<sel.options.length;o++){if(sel.options[o].text===n){sel.selectedIndex=o;return;}} for(var o=0;o<sel.options.length;o++){if(sel.options[o].text.toLowerCase()===nl){sel.selectedIndex=o;return;}} for(var o=0;o<sel.options.length;o++){if(sel.options[o].text.toLowerCase().startsWith(nl)){sel.selectedIndex=o;return;}} for(var o=0;o<sel.options.length;o++){var fw=sel.options[o].text.split(/[\s–-]/)[0].toLowerCase();if(fw&&(nl===fw||nl.startsWith(fw+' '))){sel.selectedIndex=o;return;}} }

function _rebuildIdiomaEntries(idiomas){ if(!idiomas.length) return; var c=document.getElementById('entries-idiomas');if(!c) return; var ex=c.querySelectorAll('.cv-entry'); for(var k=1;k<ex.length;k++) ex[k].remove(); var tmpl=c.querySelector('.cv-entry');if(!tmpl) return; var ii=tmpl.querySelector('[data-field="idioma"]');var ns=tmpl.querySelector('[data-field="nivel"]'); if(ii)ii.value=idiomas[0].idioma||''; if(ns)_setNivelSelect(ns,idiomas[0].nivel||''); for(var i=1;i<idiomas.length;i++){var clone=tmpl.cloneNode(true);var inp=clone.querySelector('[data-field="idioma"]');var sel=clone.querySelector('[data-field="nivel"]');if(inp)inp.value=idiomas[i].idioma||'';if(sel)_setNivelSelect(sel,idiomas[i].nivel||'');c.appendChild(clone);} updateEntriesPreview('idiomas'); }

function _applyAiData(cvData){ showCvView('editor'); var sf={'cv_name':cvData.cv_name||'','cv_job_title':cvData.cv_job_title||'','cv_bio':cvData.cv_bio||'','cv_email':cvData.cv_email||'','cv_phone':cvData.cv_phone||'','cv_location':cvData.cv_location||'','cv_linkedin':cvData.cv_linkedin||'','cv_website':cvData.cv_website||''}; Object.keys(sf).forEach(function(id){var el=document.getElementById(id);if(el)el.value=sf[id];}); var hc=document.getElementById('skills-chips'); if(hc){hc.innerHTML='';var habs=cvData.habilidades||[];if(!habs.length&&cvData.cv_skills)habs=cvData.cv_skills.split(',').map(function(s){return s.trim();}).filter(Boolean); habs.forEach(function(skill){if(!skill)return;var chip=document.createElement('span');chip.className='skill-chip-item';chip.dataset.skill=skill;chip.innerHTML=_esc(skill)+'<button type="button" onclick="removeSkill(this)" title="Eliminar">&times;</button>';hc.appendChild(chip);});} _rebuildEntries('experiencia',cvData.experiencia||[],function(e){return[_nullClean(e.empresa),_nullClean(e.cargo),_nullClean(e.periodo),_nullClean(e.descripcion)];}); _rebuildEntries('formacion',cvData.formacion||[],function(e){return[_nullClean(e.institucion),_nullClean(e.titulo),_nullClean(e.periodo),_nullClean(e.descripcion)];}); _rebuildEntries('proyectos',cvData.proyectos||[],function(e){return[_nullClean(e.nombre),_nullClean(e.descripcion),_nullClean(e.url),_nullClean(e.tecnologias)];}); _rebuildIdiomaEntries(cvData.idiomas||[]); var attempts=0; function tryFill(){var iframe=document.getElementById('cv-preview-iframe');if(!iframe)return;var doc;try{doc=iframe.contentDocument;}catch(ex){} if(!doc||!doc.body||doc.body.children.length===0){if(++attempts<20)setTimeout(tryFill,200);return;} fillAllPreview();} setTimeout(tryFill,150); var btn=document.getElementById('btn-save-cv');if(btn){var orig=btn.innerHTML;btn.innerHTML='<svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg> IA aplicada';btn.style.background='#16a34a';setTimeout(function(){btn.innerHTML=orig;btn.style.background='';},3500);} }

document.getElementById('ai-modal-overlay').addEventListener('click',function(e){if(e.target===this)closeAiModal();});
</script>
@endpush

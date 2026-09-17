@extends('layouts.app')

{{-- Esta vista sirve tanto para comprar un PLAN (con hosting o PDF) como
     para comprar la descarga de UNA plantilla concreta. El controlador pasa
     siempre las mismas variables genéricas: $checkoutTitle, $checkoutBadge,
     $checkoutSubtitle, $checkoutPrice, $checkoutOnce, $checkoutFeatures,
     $checkoutColor, $checkoutFormAction, $checkoutBackParam. --}}

@section('title', 'Checkout — ' . $checkoutTitle . ' · CvXpress')

@push('styles')
<style>
/* ── CHECKOUT PAGE ── */
.checkout-wrap {
    min-height: calc(100vh - 72px);
    background: #f8fafc;
    padding: 3rem 1rem 5rem;
    display: flex;
    align-items: flex-start;
    justify-content: center;
}
.checkout-grid {
    display: grid;
    grid-template-columns: 1fr 420px;
    gap: 2rem;
    width: 100%;
    max-width: 900px;
    align-items: start;
}
@media (max-width: 760px) {
    .checkout-grid { grid-template-columns: 1fr; }
    .checkout-summary { order: -1; }
}

/* Summary card */
.checkout-summary {
    background: #fff;
    border: 1px solid #e2e8f0;
    border-radius: 18px;
    padding: 2rem;
    box-shadow: 0 4px 24px rgba(0,0,0,.06);
}
.checkout-summary__plan-badge {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    font-size: .78rem;
    font-weight: 700;
    padding: 4px 12px;
    border-radius: 99px;
    margin-bottom: 1.25rem;
    letter-spacing: .03em;
    text-transform: uppercase;
}
.checkout-summary__name {
    font-size: 1.35rem;
    font-weight: 800;
    color: #0f172a;
    margin-bottom: .35rem;
}
.checkout-summary__desc {
    font-size: .875rem;
    color: #64748b;
    margin-bottom: 1.5rem;
}
.checkout-summary__features {
    list-style: none;
    padding: 0; margin: 0 0 1.75rem;
    display: flex;
    flex-direction: column;
    gap: .6rem;
}
.checkout-summary__features li {
    display: flex;
    align-items: flex-start;
    gap: 10px;
    font-size: .875rem;
    color: #334155;
}
.checkout-summary__features li svg {
    flex-shrink: 0;
    width: 16px; height: 16px;
    color: #10b981;
    margin-top: 1px;
}
.checkout-summary__divider {
    border: none;
    border-top: 1px solid #e2e8f0;
    margin: 1.25rem 0;
}
.checkout-summary__row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    font-size: .875rem;
    color: #64748b;
    margin-bottom: .5rem;
}
.checkout-summary__row--total {
    font-size: 1.15rem;
    font-weight: 800;
    color: #0f172a;
    margin-top: .25rem;
}
.checkout-summary__secure {
    display: flex;
    align-items: center;
    gap: 6px;
    font-size: .75rem;
    color: #94a3b8;
    margin-top: 1.25rem;
    justify-content: center;
}

/* Form card */
.checkout-form-card {
    background: #fff;
    border: 1px solid #e2e8f0;
    border-radius: 18px;
    padding: 2rem;
    box-shadow: 0 4px 24px rgba(0,0,0,.06);
}
.checkout-form-card__title {
    font-size: 1.1rem;
    font-weight: 700;
    color: #0f172a;
    margin-bottom: 1.5rem;
    display: flex;
    align-items: center;
    gap: 9px;
}
.checkout-form-card__title svg {
    color: #6366f1;
}
.checkout-section-label {
    font-size: .72rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .08em;
    color: #94a3b8;
    margin: 1.25rem 0 .75rem;
}
.co-field {
    margin-bottom: 1rem;
}
.co-field label {
    display: block;
    font-size: .8rem;
    font-weight: 600;
    color: #475569;
    margin-bottom: .35rem;
}
.co-field input {
    width: 100%;
    padding: .65rem .875rem;
    border: 1.5px solid #e2e8f0;
    border-radius: 10px;
    font-size: .9rem;
    color: #0f172a;
    background: #f8fafc;
    transition: border-color .15s, box-shadow .15s;
    box-sizing: border-box;
}
.co-field input:focus {
    outline: none;
    border-color: #6366f1;
    background: #fff;
    box-shadow: 0 0 0 3px rgba(99,102,241,.12);
}
.co-field-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: .75rem;
}

/* Fake card inputs */
.card-input-wrap {
    position: relative;
}
.card-input-wrap input {
    padding-right: 3rem;
}
.card-input-wrap__icon {
    position: absolute;
    right: .875rem;
    top: 50%;
    transform: translateY(-50%);
    color: #94a3b8;
}

.checkout-btn {
    width: 100%;
    padding: .9rem 1.5rem;
    background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%);
    color: #fff;
    border: none;
    border-radius: 12px;
    font-size: 1rem;
    font-weight: 700;
    cursor: pointer;
    margin-top: 1.5rem;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
    box-shadow: 0 4px 14px rgba(99,102,241,.35);
    transition: transform .15s, box-shadow .15s;
}
.checkout-btn:hover {
    transform: translateY(-1px);
    box-shadow: 0 6px 20px rgba(99,102,241,.45);
}
.checkout-btn:active { transform: scale(.98); }

.checkout-disclaimer {
    font-size: .75rem;
    color: #94a3b8;
    text-align: center;
    margin-top: .875rem;
    line-height: 1.5;
}

.checkout-breadcrumb {
    font-size: .82rem;
    color: #94a3b8;
    margin-bottom: 2rem;
    display: flex;
    align-items: center;
    gap: 6px;
    max-width: 900px;
    width: 100%;
    margin-left: auto;
    margin-right: auto;
    padding: 0;
}
.checkout-breadcrumb a { color: #6366f1; text-decoration: none; }
.checkout-breadcrumb a:hover { text-decoration: underline; }
</style>
@endpush

@section('content')
<div class="checkout-wrap">
    <div style="width:100%;max-width:900px;">

        {{-- Breadcrumb --}}
        <nav class="checkout-breadcrumb">
            <a href="{{ route('home') }}">Inicio</a>
            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 18 15 12 9 6"/></svg>
            <a href="{{ route('home') }}#precios">Planes</a>
            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 18 15 12 9 6"/></svg>
            <span>Checkout</span>
        </nav>

        <div class="checkout-grid">

            {{-- ── LEFT: FORM ── --}}
            <div class="checkout-form-card">
                <div class="checkout-form-card__title">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="1" y="4" width="22" height="16" rx="2"/><line x1="1" y1="10" x2="23" y2="10"/></svg>
                    Completa tu compra
                </div>

                <form method="POST" action="{{ $checkoutFormAction }}" id="checkoutForm">
                    @csrf
                    @if($checkoutBackParam)
                        <input type="hidden" name="back" value="{{ $checkoutBackParam }}">
                    @endif

                    {{-- Datos de facturación --}}
                    <div class="checkout-section-label">Datos de facturación</div>

                    <div class="co-field">
                        <label for="buyer_name">Nombre completo *</label>
                        <input type="text" id="buyer_name" name="buyer_name"
                               value="{{ old('buyer_name', auth()->user()->name) }}"
                               placeholder="Tu nombre completo" required>
                        @error('buyer_name')<span style="font-size:.75rem;color:#ef4444;">{{ $message }}</span>@enderror
                    </div>

                    <div class="co-field">
                        <label for="buyer_email">Email *</label>
                        <input type="email" id="buyer_email" name="buyer_email"
                               value="{{ old('buyer_email', auth()->user()->email) }}"
                               placeholder="tu@email.com" required>
                        @error('buyer_email')<span style="font-size:.75rem;color:#ef4444;">{{ $message }}</span>@enderror
                    </div>

                    <div class="co-field-row">
                        <div class="co-field">
                            <label for="buyer_nif">DNI / NIF / NIE</label>
                            <input type="text" id="buyer_nif" name="buyer_nif"
                                   value="{{ old('buyer_nif') }}"
                                   placeholder="12345678A">
                        </div>
                        <div class="co-field">
                            <label for="buyer_address">Dirección</label>
                            <input type="text" id="buyer_address" name="buyer_address"
                                   value="{{ old('buyer_address') }}"
                                   placeholder="Calle, ciudad, país">
                        </div>
                    </div>

                    {{-- Datos de pago (simulado) --}}
                    <div class="checkout-section-label" style="margin-top:1.75rem;">Datos de pago (demo)</div>

                    <div class="co-field">
                        <label>Número de tarjeta</label>
                        <div class="card-input-wrap">
                            <input type="text" value="4242 4242 4242 4242" readonly
                                   style="color:#94a3b8;cursor:not-allowed;letter-spacing:.08em;">
                            <span class="card-input-wrap__icon">
                                <svg width="22" height="16" viewBox="0 0 38 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <rect width="38" height="24" rx="4" fill="#1A1F71"/>
                                    <path d="M15 17H14L11 7H12.5L14.5 14.5L16.5 7H17.5L19.5 14.5L21.5 7H23L20 17H19L17 9.5L15 17Z" fill="white"/>
                                </svg>
                            </span>
                        </div>
                    </div>

                    <div class="co-field-row">
                        <div class="co-field">
                            <label>Caducidad</label>
                            <input type="text" value="12/30" readonly style="color:#94a3b8;cursor:not-allowed;">
                        </div>
                        <div class="co-field">
                            <label>CVV</label>
                            <input type="text" value="•••" readonly style="color:#94a3b8;cursor:not-allowed;">
                        </div>
                    </div>

                    <button type="submit" class="checkout-btn" id="payBtn">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                        @if($checkoutOnce)
                            Pagar · {{ number_format($checkoutPrice, 2, ',', '.') }}€
                        @else
                            Suscribirse · {{ number_format($checkoutPrice, 2, ',', '.') }}€/año
                        @endif
                    </button>

                    <p class="checkout-disclaimer">
                        Este es un entorno de demostración. No se realizará ningún cargo real.<br>
                        {{ $checkoutOnce ? 'Pago único, sin renovaciones' : 'Facturación anual' }} · Recibirás una factura legal descargable en PDF.
                    </p>
                </form>
            </div>

            {{-- ── RIGHT: SUMMARY ── --}}
            <div class="checkout-summary">
                @php
                    $colors = [
                        '#16a34a' => ['bg'=>'#dcfce7','fg'=>'#16a34a'],
                        '#1A56DB' => ['bg'=>'#dbeafe','fg'=>'#1A56DB'],
                        '#7c3aed' => ['bg'=>'#ede9fe','fg'=>'#7c3aed'],
                        '#d97706' => ['bg'=>'#fef3c7','fg'=>'#d97706'],
                        '#db2777' => ['bg'=>'#fce7f3','fg'=>'#db2777'],
                        '#0891b2' => ['bg'=>'#cffafe','fg'=>'#0891b2'],
                        '#475569' => ['bg'=>'#f1f5f9','fg'=>'#475569'],
                    ];
                    $tc = $colors[$checkoutColor] ?? ['bg'=>'#f1f5f9','fg'=>'#475569'];
                @endphp

                <span class="checkout-summary__plan-badge"
                      style="background:{{ $tc['bg'] }};color:{{ $tc['fg'] }};">
                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
                    {{ $checkoutBadge }}
                </span>

                <div class="checkout-summary__name">{{ $checkoutTitle }}</div>
                <div class="checkout-summary__desc">{{ $checkoutSubtitle }}</div>

                <ul class="checkout-summary__features">
                    @foreach($checkoutFeatures as $feature)
                        <li>
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                            {{ $feature }}
                        </li>
                    @endforeach
                </ul>

                <hr class="checkout-summary__divider">

                <div class="checkout-summary__row">
                    <span>Base imponible</span>
                    <span>{{ number_format($base, 2, ',', '.') }}€</span>
                </div>
                <div class="checkout-summary__row">
                    <span>IVA (21%)</span>
                    <span>{{ number_format($iva, 2, ',', '.') }}€</span>
                </div>
                <hr class="checkout-summary__divider">
                <div class="checkout-summary__row checkout-summary__row--total">
                    <span>Total</span>
                    <span>{{ number_format($checkoutPrice, 2, ',', '.') }}€</span>
                </div>

                <div class="checkout-summary__secure">
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                    Pago 100% seguro · Factura legal incluida
                </div>
            </div>

        </div>
    </div>
</div>
@endsection

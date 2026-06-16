@extends('layouts.app')

@section('title', 'Factura ' . $purchase->invoice_number . ' · CVPortfolio')

@push('styles')
<style>
.invoice-wrap {
    min-height: calc(100vh - 72px);
    background: #f8fafc;
    padding: 3rem 1rem 5rem;
    display: flex;
    justify-content: center;
}
.invoice-container {
    width: 100%;
    max-width: 780px;
}
.invoice-success-banner {
    background: linear-gradient(135deg, #10b981 0%, #059669 100%);
    color: #fff;
    border-radius: 16px;
    padding: 1.5rem 2rem;
    display: flex;
    align-items: center;
    gap: 1rem;
    margin-bottom: 2rem;
    box-shadow: 0 4px 20px rgba(16,185,129,.3);
}
.invoice-success-banner__icon {
    width: 48px; height: 48px;
    background: rgba(255,255,255,.2);
    border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0;
}
.invoice-success-banner__title { font-size: 1.15rem; font-weight: 800; margin-bottom: 2px; }
.invoice-success-banner__sub   { font-size: .875rem; opacity: .9; }

.invoice-actions {
    display: flex;
    gap: .75rem;
    margin-bottom: 2rem;
    flex-wrap: wrap;
}
.invoice-btn {
    display: inline-flex; align-items: center; gap: 8px;
    padding: .65rem 1.25rem;
    border-radius: 10px;
    font-size: .875rem; font-weight: 600;
    text-decoration: none; cursor: pointer;
    transition: transform .12s, box-shadow .12s;
    border: none;
}
.invoice-btn:active { transform: scale(.97); }
.invoice-btn--primary {
    background: #4f46e5; color: #fff;
    box-shadow: 0 2px 10px rgba(79,70,229,.3);
}
.invoice-btn--primary:hover { background: #4338ca; }
.invoice-btn--ghost {
    background: #fff; color: #475569;
    border: 1.5px solid #e2e8f0;
}
.invoice-btn--ghost:hover { background: #f1f5f9; }

/* Invoice document */
.invoice-doc {
    background: #fff;
    border: 1px solid #e2e8f0;
    border-radius: 18px;
    overflow: hidden;
    box-shadow: 0 4px 24px rgba(0,0,0,.07);
}
.invoice-doc__header {
    background: linear-gradient(135deg, #1e1b4b 0%, #312e81 100%);
    color: #fff;
    padding: 2.5rem 2.5rem 2rem;
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    gap: 1rem;
    flex-wrap: wrap;
}
.invoice-doc__brand { font-size: 1.6rem; font-weight: 900; letter-spacing: -.02em; }
.invoice-doc__brand span { color: #a5b4fc; }
.invoice-doc__tagline { font-size: .8rem; opacity: .7; margin-top: 4px; }
.invoice-doc__meta { text-align: right; }
.invoice-doc__invoice-label { font-size: .72rem; opacity: .6; text-transform: uppercase; letter-spacing: .1em; }
.invoice-doc__invoice-number { font-size: 1.4rem; font-weight: 800; letter-spacing: .02em; }
.invoice-doc__date { font-size: .82rem; opacity: .75; margin-top: 4px; }

.invoice-doc__body { padding: 2.5rem; }

.invoice-parties {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 2rem;
    margin-bottom: 2rem;
}
@media(max-width:600px) { .invoice-parties { grid-template-columns: 1fr; } }

.invoice-party__label {
    font-size: .7rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .1em;
    color: #94a3b8;
    margin-bottom: .75rem;
}
.invoice-party__name { font-size: 1rem; font-weight: 700; color: #0f172a; margin-bottom: .3rem; }
.invoice-party__detail { font-size: .82rem; color: #64748b; line-height: 1.6; }

.invoice-table {
    width: 100%;
    border-collapse: collapse;
    margin: 2rem 0 1.5rem;
    font-size: .875rem;
}
.invoice-table thead tr {
    background: #f8fafc;
    border-bottom: 2px solid #e2e8f0;
}
.invoice-table th {
    padding: .75rem 1rem;
    text-align: left;
    font-size: .72rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .06em;
    color: #94a3b8;
}
.invoice-table th:last-child { text-align: right; }
.invoice-table td {
    padding: 1rem;
    border-bottom: 1px solid #f1f5f9;
    color: #334155;
}
.invoice-table td:last-child { text-align: right; font-weight: 600; }
.invoice-table .desc-main { font-weight: 600; color: #0f172a; }
.invoice-table .desc-sub  { font-size: .78rem; color: #94a3b8; margin-top: 3px; }

.invoice-totals {
    margin-left: auto;
    width: 280px;
    margin-top: 1rem;
}
.invoice-totals__row {
    display: flex;
    justify-content: space-between;
    font-size: .875rem;
    color: #64748b;
    padding: .4rem 0;
    border-bottom: 1px solid #f1f5f9;
}
.invoice-totals__row--total {
    font-size: 1.05rem;
    font-weight: 800;
    color: #0f172a;
    border-bottom: none;
    padding-top: .75rem;
}

.invoice-doc__footer {
    background: #f8fafc;
    border-top: 1px solid #e2e8f0;
    padding: 1.5rem 2.5rem;
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 1rem;
    flex-wrap: wrap;
}
.invoice-doc__footer-note { font-size: .75rem; color: #94a3b8; line-height: 1.5; }
.invoice-doc__footer-iban { font-size: .78rem; font-weight: 600; color: #475569; }
.invoice-doc__footer-iban span { color: #94a3b8; font-weight: 400; }
</style>
@endpush

@section('content')
<div class="invoice-wrap">
    <div class="invoice-container">

        {{-- Success banner --}}
        <div class="invoice-success-banner">
            <div class="invoice-success-banner__icon">
                <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
            </div>
            <div>
                <div class="invoice-success-banner__title">¡Compra completada con éxito!</div>
                <div class="invoice-success-banner__sub">Tu factura está lista. Descárgala en PDF para tus registros.</div>
            </div>
        </div>

        {{-- Actions --}}
        <div class="invoice-actions">
            <a href="{{ route('checkout.pdf', $purchase->id) }}" class="invoice-btn invoice-btn--primary">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                Descargar factura PDF
            </a>
            <a href="{{ route('dashboard') }}" class="invoice-btn invoice-btn--ghost">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg>
                Ir a mi panel
            </a>
        </div>

        {{-- Invoice document --}}
        <div class="invoice-doc">

            <div class="invoice-doc__header">
                <div>
                    <div class="invoice-doc__brand">CV<span>Portfolio</span></div>
                    <div class="invoice-doc__tagline">Tu CV convertido en portfolio web</div>
                </div>
                <div class="invoice-doc__meta">
                    <div class="invoice-doc__invoice-label">Factura</div>
                    <div class="invoice-doc__invoice-number">{{ $purchase->invoice_number }}</div>
                    <div class="invoice-doc__date">{{ $purchase->created_at->format('d/m/Y') }}</div>
                </div>
            </div>

            <div class="invoice-doc__body">

                <div class="invoice-parties">
                    <div>
                        <div class="invoice-party__label">Emisor (Vendedor)</div>
                        <div class="invoice-party__name">{{ $seller['name'] }}</div>
                        <div class="invoice-party__detail">
                            DNI: {{ $seller['nif'] }}<br>
                            {{ $seller['address'] }}<br>
                            {{ $seller['email'] }}
                        </div>
                    </div>
                    <div>
                        <div class="invoice-party__label">Receptor (Cliente)</div>
                        <div class="invoice-party__name">{{ $purchase->buyer_name }}</div>
                        <div class="invoice-party__detail">
                            @if($purchase->buyer_nif) DNI/NIF: {{ $purchase->buyer_nif }}<br> @endif
                            @if($purchase->buyer_address) {{ $purchase->buyer_address }}<br> @endif
                            {{ $purchase->buyer_email }}
                        </div>
                    </div>
                </div>

                <table class="invoice-table">
                    <thead>
                        <tr>
                            <th style="width:50%">Descripción</th>
                            <th>Cantidad</th>
                            <th>Base imponible</th>
                            <th>IVA (21%)</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                <div class="desc-main">Plan {{ $plan->name }} — CVPortfolio</div>
                                <div class="desc-sub">Suscripción anual · Licencia 12 meses · Válido hasta {{ $purchase->expires_at?->format('d/m/Y') ?? now()->addYear()->format('d/m/Y') }}</div>
                            </td>
                            <td>1</td>
                            <td>{{ number_format($base, 2, ',', '.') }}€</td>
                            <td>{{ number_format($iva, 2, ',', '.') }}€</td>
                            <td>{{ number_format($purchase->amount_paid, 2, ',', '.') }}€</td>
                        </tr>
                    </tbody>
                </table>

                <div class="invoice-totals">
                    <div class="invoice-totals__row">
                        <span>Base imponible</span>
                        <span>{{ number_format($base, 2, ',', '.') }}€</span>
                    </div>
                    <div class="invoice-totals__row">
                        <span>IVA 21%</span>
                        <span>{{ number_format($iva, 2, ',', '.') }}€</span>
                    </div>
                    <div class="invoice-totals__row invoice-totals__row--total">
                        <span>Total factura</span>
                        <span>{{ number_format($purchase->amount_paid, 2, ',', '.') }}€</span>
                    </div>
                </div>

            </div>

            <div class="invoice-doc__footer">
                <div class="invoice-doc__footer-note">
                    Ref. pago: {{ $purchase->payment_reference }}<br>
                    Factura emitida el {{ $purchase->created_at->format('d/m/Y') }} conforme a la legislación fiscal española.
                </div>
                <div class="invoice-doc__footer-iban">
                    <span>IBAN: </span>{{ $seller['iban'] }}
                </div>
            </div>

        </div>

    </div>
</div>
@endsection

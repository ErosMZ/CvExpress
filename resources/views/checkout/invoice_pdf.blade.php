<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<style>
  * { margin: 0; padding: 0; box-sizing: border-box; }
  body { font-family: DejaVu Sans, sans-serif; font-size: 11px; color: #334155; background: #fff; }

  .page { padding: 40px 50px; }

  /* Header */
  .header { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 32px; }
  .brand { font-size: 22px; font-weight: 900; color: #1e1b4b; letter-spacing: -0.5px; }
  .brand span { color: #6366f1; }
  .brand-sub { font-size: 9px; color: #94a3b8; margin-top: 3px; }
  .invoice-meta { text-align: right; }
  .invoice-label { font-size: 8px; text-transform: uppercase; letter-spacing: 1px; color: #94a3b8; }
  .invoice-number { font-size: 18px; font-weight: 900; color: #1e1b4b; }
  .invoice-date { font-size: 10px; color: #64748b; margin-top: 3px; }

  /* Divider */
  .divider { border: none; border-top: 2px solid #e2e8f0; margin: 20px 0; }
  .divider-light { border: none; border-top: 1px solid #f1f5f9; margin: 10px 0; }

  /* Parties */
  .parties { display: flex; justify-content: space-between; margin-bottom: 28px; gap: 20px; }
  .party { width: 48%; }
  .party-label { font-size: 8px; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; color: #94a3b8; margin-bottom: 8px; border-bottom: 1px solid #e2e8f0; padding-bottom: 5px; }
  .party-name { font-size: 12px; font-weight: 700; color: #0f172a; margin-bottom: 4px; }
  .party-detail { font-size: 10px; color: #64748b; line-height: 1.7; }

  /* Table */
  .items-table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
  .items-table thead tr { background: #f8fafc; }
  .items-table th { padding: 9px 10px; text-align: left; font-size: 8px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.8px; color: #94a3b8; border-bottom: 2px solid #e2e8f0; }
  .items-table th.right { text-align: right; }
  .items-table td { padding: 12px 10px; border-bottom: 1px solid #f1f5f9; vertical-align: top; }
  .items-table td.right { text-align: right; }
  .item-name { font-weight: 700; color: #0f172a; font-size: 11px; }
  .item-desc { font-size: 9px; color: #94a3b8; margin-top: 3px; }

  /* Totals */
  .totals-wrap { display: flex; justify-content: flex-end; margin-bottom: 28px; }
  .totals { width: 260px; }
  .totals-row { display: flex; justify-content: space-between; font-size: 10px; color: #64748b; padding: 5px 0; border-bottom: 1px solid #f1f5f9; }
  .totals-row.total { font-size: 13px; font-weight: 800; color: #0f172a; border-bottom: none; padding-top: 10px; }

  /* Footer */
  .footer { border-top: 2px solid #e2e8f0; padding-top: 16px; display: flex; justify-content: space-between; align-items: flex-end; }
  .footer-note { font-size: 9px; color: #94a3b8; line-height: 1.6; }
  .footer-iban { font-size: 10px; font-weight: 700; color: #475569; text-align: right; }
  .footer-iban span { font-weight: 400; color: #94a3b8; display: block; font-size: 8px; margin-bottom: 2px; }

  .badge {
    display: inline-block;
    background: #ede9fe;
    color: #7c3aed;
    padding: 2px 8px;
    border-radius: 99px;
    font-size: 8px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-bottom: 12px;
  }
</style>
</head>
<body>
<div class="page">

  <!-- Header -->
  <div class="header">
    <div>
      <div class="brand">CV<span>Portfolio</span></div>
      <div class="brand-sub">cvportfolio.es · erosmunozzanon@gmail.com</div>
    </div>
    <div class="invoice-meta">
      <div class="invoice-label">Factura simplificada</div>
      <div class="invoice-number">{{ $purchase->invoice_number }}</div>
      <div class="invoice-date">Fecha: {{ $purchase->created_at->format('d/m/Y') }}</div>
    </div>
  </div>

  <hr class="divider">

  <!-- Parties -->
  <div class="parties">
    <div class="party">
      <div class="party-label">Emisor · Vendedor</div>
      <div class="party-name">{{ $seller['name'] }}</div>
      <div class="party-detail">
        DNI: {{ $seller['nif'] }}<br>
        {{ $seller['address'] }}<br>
        {{ $seller['email'] }}
      </div>
    </div>
    <div class="party">
      <div class="party-label">Receptor · Cliente</div>
      <div class="party-name">{{ $purchase->buyer_name }}</div>
      <div class="party-detail">
        @if($purchase->buyer_nif)DNI/NIF: {{ $purchase->buyer_nif }}<br>@endif
        @if($purchase->buyer_address){{ $purchase->buyer_address }}<br>@endif
        {{ $purchase->buyer_email }}
      </div>
    </div>
  </div>

  <!-- Items -->
  <table class="items-table">
    <thead>
      <tr>
        <th style="width:45%">Descripción</th>
        <th>Cant.</th>
        <th class="right">Base imponible</th>
        <th class="right">IVA (21%)</th>
        <th class="right">Total</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td>
          <div class="item-name">Plan {{ $plan->name }} — CVPortfolio</div>
          <div class="item-desc">Acceso completo · Pago único · Sin renovaciones automáticas</div>
        </td>
        <td>1</td>
        <td class="right">{{ number_format($base, 2, ',', '.') }} €</td>
        <td class="right">{{ number_format($iva, 2, ',', '.') }} €</td>
        <td class="right" style="font-weight:700;">{{ number_format($purchase->amount_paid, 2, ',', '.') }} €</td>
      </tr>
    </tbody>
  </table>

  <!-- Totals -->
  <div class="totals-wrap">
    <div class="totals">
      <div class="totals-row">
        <span>Base imponible</span>
        <span>{{ number_format($base, 2, ',', '.') }} €</span>
      </div>
      <div class="totals-row">
        <span>IVA 21%</span>
        <span>{{ number_format($iva, 2, ',', '.') }} €</span>
      </div>
      <div class="totals-row total">
        <span>TOTAL FACTURA</span>
        <span>{{ number_format($purchase->amount_paid, 2, ',', '.') }} €</span>
      </div>
    </div>
  </div>

  <!-- Footer -->
  <div class="footer">
    <div class="footer-note">
      Referencia de pago: {{ $purchase->payment_reference }}<br>
      Factura emitida el {{ $purchase->created_at->format('d/m/Y') }} conforme a la Ley 37/1992 del IVA y<br>
      el Real Decreto 1619/2012 sobre obligaciones de facturación.
    </div>
    <div class="footer-iban">
      <span>Transferencia bancaria:</span>
      {{ $seller['iban'] }}
    </div>
  </div>

</div>
</body>
</html>

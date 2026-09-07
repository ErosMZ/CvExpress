<?php

namespace App\Http\Controllers;

use App\Models\Plan;
use App\Models\UserPurchase;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CheckoutController extends Controller
{
    private static function seller(): array
    {
        return [
            'name'    => config('legal.owner'),
            'nif'     => config('legal.nif'),
            'address' => config('legal.address'),
            'email'   => config('legal.email'),
            'iban'    => config('legal.iban'),
        ];
    }

    public function show(Plan $plan)
    {
        abort_if(! $plan->is_active, 404);

        $base = round($plan->price / 1.21, 2);
        $iva  = round($plan->price - $base, 2);

        return view('checkout.show', compact('plan', 'base', 'iva'));
    }

    public function process(Request $request, Plan $plan)
    {
        abort_if(! $plan->is_active, 404);

        $request->validate([
            'buyer_name'    => 'required|string|max:120',
            'buyer_email'   => 'required|email|max:120',
            'buyer_nif'     => 'nullable|string|max:20',
            'buyer_address' => 'nullable|string|max:255',
        ]);

        $invoiceNumber = UserPurchase::generateInvoiceNumber();

        $purchase = UserPurchase::create([
            'user_id'           => auth()->id(),
            'plan_id'           => $plan->id,
            'status'            => 'active',
            'amount_paid'       => $plan->price,
            'payment_reference' => 'SIM-' . strtoupper(Str::random(8)),
            'invoice_number'    => $invoiceNumber,
            'buyer_name'        => $request->buyer_name,
            'buyer_email'       => $request->buyer_email,
            'buyer_nif'         => $request->buyer_nif,
            'buyer_address'     => $request->buyer_address,
            'hosting_type'      => 'none',
            'purchased_at'      => now(),
            'expires_at'        => now()->addYear(),
        ]);

        if ($request->input('back') === 'cv-pdf') {
            return redirect()->route('cv-pdf.editor')
                ->with('success', '¡Pago realizado! Ya puedes descargar tu CV en PDF.');
        }

        return redirect()->route('checkout.invoice', $purchase->id);
    }

    public function invoice(UserPurchase $purchase)
    {
        abort_if($purchase->user_id !== auth()->id(), 403);

        $base = round($purchase->amount_paid / 1.21, 2);
        $iva  = round($purchase->amount_paid - $base, 2);

        return view('checkout.invoice', [
            'purchase' => $purchase,
            'plan'     => $purchase->plan,
            'seller'   => self::seller(),
            'base'     => $base,
            'iva'      => $iva,
        ]);
    }

    public function downloadPdf(UserPurchase $purchase)
    {
        abort_if($purchase->user_id !== auth()->id(), 403);

        $base = round($purchase->amount_paid / 1.21, 2);
        $iva  = round($purchase->amount_paid - $base, 2);

        $pdf = app('dompdf.wrapper')->loadView('checkout.invoice_pdf', [
            'purchase' => $purchase,
            'plan'     => $purchase->plan,
            'seller'   => self::seller(),
            'base'     => $base,
            'iva'      => $iva,
        ]);

        $pdf->setPaper('A4', 'portrait');

        $filename = 'factura-' . $purchase->invoice_number . '.pdf';

        return $pdf->download($filename);
    }
}

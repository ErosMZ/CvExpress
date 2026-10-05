<?php

namespace App\Http\Controllers;

use App\Models\Plan;
use App\Models\Template;
use App\Models\UserPurchase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
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

        return view('checkout.show', [
            'plan'                => $plan,
            'base'                => $base,
            'iva'                 => $iva,
            'checkoutTitle'       => $plan->name,
            'checkoutBadge'       => 'Plan ' . $plan->name,
            'checkoutSubtitle'    => $plan->billing_cycle === 'once' ? 'Pago único · Sin renovaciones' : 'Suscripción anual · Acceso completo 12 meses',
            'checkoutPrice'       => $plan->price,
            'checkoutOnce'        => $plan->billing_cycle === 'once',
            'checkoutFeatures'    => $plan->features ?? [],
            'checkoutColor'       => $plan->color,
            'checkoutFormAction'  => route('checkout.process', $plan->slug),
            'checkoutBackParam'   => request('back'),
        ]);
    }

    public function process(Request $request, Plan $plan)
    {
        abort_if(! $plan->is_active, 404);

        $buyer = $this->validateBuyer($request);

        // Solo puede haber una compra activa por categoría de plan a la vez
        // (p. ej. no dos planes de CV Web activos a la vez). No toca compras
        // activas de otras categorías (un plan de PDF y uno de CV Web pueden
        // convivir). Si la que se cancela tenía una web publicada, se borran
        // sus ficheros para no dejar huérfanos.
        $toCancel = UserPurchase::where('user_id', auth()->id())
            ->where('status', 'active')
            ->whereHas('plan', fn ($q) => $q->where('category', $plan->category))
            ->get();
        foreach ($toCancel as $old) {
            if ($old->subdomain) $this->unpublishSubdomain($old->subdomain);
        }
        UserPurchase::whereIn('id', $toCancel->pluck('id'))->update(['status' => 'cancelled']);

        $purchase = UserPurchase::create(array_merge($buyer, [
            'plan_id'      => $plan->id,
            'amount_paid'  => $plan->price,
            'hosting_type' => 'none',
        ]));

        if ($request->input('back') === 'cv-pdf') {
            return redirect()->route('cv-pdf.editor')
                ->with('success', '¡Pago realizado! Ya puedes descargar tu CV en PDF.');
        }

        return redirect()->route('checkout.invoice', $purchase->id);
    }

    /**
     * Checkout para comprar la descarga de UNA plantilla concreta, al precio
     * que el admin le puso en su ficha (categoría "web_download"). No da
     * acceso a las demás plantillas — cada una se compra por separado.
     */
    public function showDownload(Template $template)
    {
        abort_if(! $template->is_active, 404);
        abort_if($template->price <= 0, 404);

        $base = round($template->price / 1.21, 2);
        $iva  = round($template->price - $base, 2);

        return view('checkout.show', [
            'plan'                => null,
            'base'                => $base,
            'iva'                 => $iva,
            'checkoutTitle'       => $template->name,
            'checkoutBadge'       => 'Plantilla · Solo descarga',
            'checkoutSubtitle'    => 'Descarga única · Lista para tu editor CV Web',
            'checkoutPrice'       => $template->price,
            'checkoutOnce'        => true,
            'checkoutFeatures'    => [
                'Descarga en ZIP con tus datos ya puestos',
                'Sin límite de descargas de esta plantilla',
                'Sin hosting ni subdominio incluido',
            ],
            'checkoutColor'       => '#16a34a',
            'checkoutFormAction'  => route('checkout.download.process', $template->slug),
            'checkoutBackParam'   => request('back'),
        ]);
    }

    public function processDownload(Request $request, Template $template)
    {
        abort_if(! $template->is_active, 404);
        abort_if($template->price <= 0, 404);

        $buyer = $this->validateBuyer($request);

        $plan = $this->downloadAnchorPlan();

        // Solo una compra "solo descarga" activa a la vez (si quiere otra
        // plantilla, la anterior se sustituye por la nueva).
        UserPurchase::where('user_id', auth()->id())
            ->where('status', 'active')
            ->whereHas('plan', fn ($q) => $q->where('category', 'web_download'))
            ->update(['status' => 'cancelled']);

        $purchase = UserPurchase::create(array_merge($buyer, [
            'plan_id'              => $plan->id,
            'amount_paid'          => $template->price,
            'hosting_type'         => 'none',
            'selected_template_id' => $template->id,
        ]));

        return redirect()->route('checkout.invoice', $purchase->id);
    }

    /**
     * El plan "ancla" al que se asocian técnicamente las compras de solo
     * descarga (la tabla exige un plan_id), pero su precio no se usa nunca
     * para cobrar — cada plantilla cobra el suyo. Se crea solo una vez.
     */
    private function downloadAnchorPlan(): Plan
    {
        return Plan::firstOrCreate(
            ['category' => 'web_download'],
            [
                'slug'          => 'descarga-plantillas',
                'name'          => 'Descarga de plantillas',
                'price'         => 0,
                'billing_cycle' => 'once',
                'color'         => '#16a34a',
                'features'      => [],
                'is_active'     => true,
                'sort_order'    => 0,
            ]
        );
    }

    /**
     * Borra del disco "sites" los ficheros publicados de un subdominio, para
     * que al cancelar/sustituir un plan no quede una web huérfana.
     */
    private function unpublishSubdomain(string $subdomain): void
    {
        Storage::disk('sites')->deleteDirectory($subdomain);
    }

    private function validateBuyer(Request $request): array
    {
        $request->validate([
            'buyer_name'    => 'required|string|max:120',
            'buyer_email'   => 'required|email|max:120',
            'buyer_nif'     => 'nullable|string|max:20',
            'buyer_address' => 'nullable|string|max:255',
        ]);

        return [
            'user_id'           => auth()->id(),
            'status'            => 'active',
            'payment_reference' => 'SIM-' . strtoupper(Str::random(8)),
            'invoice_number'    => UserPurchase::generateInvoiceNumber(),
            'buyer_name'        => $request->buyer_name,
            'buyer_email'       => $request->buyer_email,
            'buyer_nif'         => $request->buyer_nif,
            'buyer_address'     => $request->buyer_address,
            'purchased_at'      => now(),
            'expires_at'        => now()->addYear(),
        ];
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

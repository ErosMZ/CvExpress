<?php

namespace App\Http\Controllers;

use App\Models\Plan;
use App\Models\UserPurchase;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Str;

class CvPdfController extends Controller
{
    public function editor()
    {
        $pdfPlans = Plan::where('category', 'pdf_download')
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        $hasPdfAccess = false;
        if (auth()->check()) {
            $hasPdfAccess = UserPurchase::where('user_id', auth()->id())
                ->where('status', 'active')
                ->whereHas('plan', fn($q) => $q->where('category', 'pdf_download'))
                ->exists();
        }

        return view('cv-pdf.editor', compact('pdfPlans', 'hasPdfAccess'));
    }

    public function buyPdfAccess(Plan $plan): JsonResponse
    {
        if ($plan->category !== 'pdf_download' || !$plan->is_active) {
            return response()->json(['error' => 'Plan no válido.'], 400);
        }

        UserPurchase::create([
            'user_id'           => auth()->id(),
            'plan_id'           => $plan->id,
            'status'            => 'active',
            'amount_paid'       => $plan->price,
            'payment_reference' => 'SIM-PDF-' . strtoupper(Str::random(8)),
            'invoice_number'    => UserPurchase::generateInvoiceNumber(),
            'buyer_name'        => auth()->user()->name,
            'buyer_email'       => auth()->user()->email,
            'purchased_at'      => now(),
            'expires_at'        => null,
        ]);

        return response()->json(['success' => true]);
    }

    public function improveProfile(Request $request): JsonResponse
    {
        $text = trim($request->input('text', ''));

        if (!$text) {
            return response()->json(['text' => $this->genericProfile()]);
        }

        try {
            $client = \OpenAI::client(config('services.openai.api_key'));

            $response = $client->chat()->create([
                'model'       => 'gpt-4o-mini',
                'temperature' => 0.65,
                'max_tokens'  => 250,
                'messages'    => [
                    [
                        'role'    => 'system',
                        'content' => 'Eres un experto en recursos humanos que mejora perfiles profesionales para currículums en español. Mantén TODA la información y contexto que haya escrito el usuario: su sector, experiencia, habilidades o datos personales. Hazlo más fluido, profesional y atractivo. Responde únicamente con el texto mejorado, en 2-4 frases, sin comillas ni explicaciones.',
                    ],
                    [
                        'role'    => 'user',
                        'content' => 'Mejora este texto de perfil profesional conservando toda mi información:' . "\n\n" . $text,
                    ],
                ],
            ]);

            $improved = trim($response->choices[0]->message->content ?? '');

            return response()->json(['text' => $improved ?: $text]);

        } catch (\Throwable $e) {
            return response()->json(['text' => $text]);
        }
    }

    private function genericProfile(): string
    {
        return 'Profesional proactivo/a con sólida formación y experiencia en mi campo. Me caracterizo por mi capacidad de aprendizaje continuo, trabajo en equipo y orientación a resultados. Busco aportar mis habilidades y conocimientos en proyectos desafiantes que me permitan crecer profesionalmente y contribuir al éxito del equipo.';
    }
}

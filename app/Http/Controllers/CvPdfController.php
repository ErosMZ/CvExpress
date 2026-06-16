<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class CvPdfController extends Controller
{
    public function editor()
    {
        return view('cv-pdf.editor');
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

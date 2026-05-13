<?php

namespace App\Services\Cv;

use OpenAI\Client;

class OpenAICvParser
{
    private const MODEL          = 'gpt-4o-mini';
    private const MAX_TEXT_CHARS = 14000;   // ~3500 tokens input
    private const TEMPERATURE    = 0;

    public function __construct(private readonly Client $client) {}

    /**
     * @return array{parsed: array, tokens: int}
     */
    public function parse(string $cvText): array
    {
        $truncated = mb_substr($cvText, 0, self::MAX_TEXT_CHARS);

        $response = $this->client->chat()->create([
            'model'           => self::MODEL,
            'temperature'     => self::TEMPERATURE,
            'response_format' => ['type' => 'json_object'],
            'messages'        => [
                ['role' => 'system', 'content' => $this->systemPrompt()],
                ['role' => 'user',   'content' => "Extrae toda la información de este CV:\n\n" . $truncated],
            ],
        ]);

        $raw    = $response->choices[0]->message->content;
        $tokens = $response->usage->totalTokens;
        $parsed = $this->decodeAndValidate($raw);

        return ['parsed' => $parsed, 'tokens' => $tokens];
    }

    private function systemPrompt(): string
    {
        return <<<'PROMPT'
Eres un extractor experto de datos de currículums. Tu única tarea es leer el CV proporcionado y devolver un JSON estructurado con TODOS los datos que encuentres.

REGLAS ESTRICTAS:
- Devuelve ÚNICAMENTE JSON válido. Nada de texto adicional.
- No inventes datos que no estén en el CV.
- Si un campo no existe en el CV, usa null (strings) o [] (arrays).
- Normaliza fechas al formato "Mes YYYY – Mes YYYY" o "Mes YYYY – Actualidad".
- Extrae el texto completo de descripciones, no lo resummas.
- Para habilidades: extrae TODAS las que aparezcan aunque sean muchas.

ESQUEMA JSON OBLIGATORIO:
{
  "name": "Nombre completo",
  "title": "Título profesional / cargo actual",
  "bio": "Descripción o resumen profesional completo",
  "email": "email@ejemplo.com",
  "phone": "+34 600 000 000",
  "location": "Ciudad, País",
  "linkedin": "https://linkedin.com/in/...",
  "website": "https://...",
  "github": "https://github.com/...",
  "skills": ["habilidad1", "habilidad2"],
  "soft_skills": ["habilidad blanda 1"],
  "experience": [
    {
      "company": "Nombre empresa",
      "position": "Cargo / Puesto",
      "period": "Mes YYYY – Mes YYYY",
      "description": "Descripción completa de responsabilidades y logros"
    }
  ],
  "education": [
    {
      "institution": "Centro educativo",
      "degree": "Título / Grado / Ciclo",
      "period": "YYYY – YYYY",
      "description": "Descripción o especialización"
    }
  ],
  "languages": [
    {
      "language": "Idioma",
      "level": "Nativo / C2 / B2 / etc"
    }
  ],
  "projects": [
    {
      "name": "Nombre proyecto",
      "description": "Descripción",
      "url": "https://...",
      "technologies": ["tech1", "tech2"]
    }
  ],
  "certifications": [
    {
      "name": "Nombre certificación",
      "issuer": "Entidad emisora",
      "year": "2023"
    }
  ]
}
PROMPT;
    }

    private function decodeAndValidate(string $raw): array
    {
        $data = json_decode($raw, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \RuntimeException('OpenAI devolvió JSON inválido: ' . json_last_error_msg());
        }

        // Garantiza que los arrays existan aunque OpenAI los omita
        foreach (['skills', 'soft_skills', 'experience', 'education', 'languages', 'projects', 'certifications'] as $key) {
            if (!isset($data[$key]) || !is_array($data[$key])) {
                $data[$key] = [];
            }
        }

        foreach (['name', 'title', 'bio', 'email', 'phone', 'location', 'linkedin', 'website', 'github'] as $key) {
            if (!isset($data[$key])) {
                $data[$key] = null;
            }
        }

        return $data;
    }
}

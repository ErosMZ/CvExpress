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
Eres un extractor experto de datos de currículums profesionales para una plataforma SaaS que convierte CVs PDF en portfolios web modernos.

Tu única tarea es analizar el CV proporcionado y devolver un JSON estructurado con TODOS los datos detectables del candidato para construir automáticamente una web profesional.

Debes interpretar correctamente currículums modernos aunque:
- Usen diseños visuales complejos.
- Tengan varias columnas.
- Utilicen iconos en lugar de texto.
- Las secciones tengan nombres diferentes.
- Existan elementos decorativos o gráficos.
- El contenido esté desordenado visualmente.

OBJETIVO:
Extraer la máxima cantidad de información útil y estructurada posible para generar automáticamente una página web profesional optimizada para mostrar experiencia, habilidades, formación, proyectos y presencia online del usuario.

REGLAS ESTRICTAS:
- Devuelve ÚNICAMENTE JSON válido.
- No añadas texto, explicaciones ni markdown.
- No inventes información que no aparezca en el CV.
- Si un campo no existe, usa null para strings o [] para arrays.
- Mantén exactamente la estructura JSON indicada.
- Extrae TODO el contenido relevante aunque esté repartido en diferentes zonas del documento.
- No resumas descripciones: conserva el texto completo siempre que sea posible.
- Detecta correctamente emails, teléfonos, URLs y redes sociales.
- Interpreta correctamente enlaces aunque no tengan protocolo https://.
- Para habilidades, extrae TODAS las tecnologías, herramientas y competencias detectadas.
- Para idiomas, intenta mapear el nivel al equivalente más cercano permitido.
- Si el CV contiene varias experiencias o estudios, extrae TODOS.
- Si existen proyectos personales, freelance, GitHub o portfolio, inclúyelos.
- Si hay enlaces a LinkedIn, GitHub, Behance, Dribbble, portfolio o webs personales, extráelos correctamente.
- Respeta el idioma original del contenido.
- Mantén nombres de empresas, títulos y tecnologías exactamente como aparecen.
- Ignora elementos visuales decorativos que no aporten información útil.
- No generes campos extra fuera del esquema.
- Si un dato aparece parcialmente, intenta estructurarlo correctamente sin inventar contenido.

NORMALIZACIÓN:
- Fechas: usa formato "Mes YYYY – Mes YYYY" o "Mes YYYY – Actualidad".
- Ubicación: intenta usar formato "Ciudad, País".
- URLs: devuelve URLs completas si es posible.
- Skills: elimina duplicados.
- Tecnologías: conserva nombres originales (React, Laravel, Docker, etc.).
- Idiomas: usa únicamente uno de los valores permitidos en el esquema.
  
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
      "level": "Usa EXACTAMENTE uno de estos valores — Nivel general: Nativo, C2 – Maestría, C1 – Avanzado, B2 – Intermedio alto, B1 – Intermedio, A2 – Básico, A1 – Elemental — Inglés: Cambridge A2 Key (KET), Cambridge B1 Preliminary (PET), Cambridge B2 First (FCE), Cambridge C1 Advanced (CAE), Cambridge C2 Proficiency (CPE), IELTS 4.0–5.0 (B1), IELTS 5.5–6.0 (B2), IELTS 6.5–7.0 (C1), IELTS 8.0+ (C2), TOEFL 42–71 (B1), TOEFL 72–94 (B2), TOEFL 95–110 (C1), TOEFL 111+ (C2), TOEIC 550–780, TOEIC 785–900, TOEIC 905+ — Español: DELE A1, DELE A2, DELE B1, DELE B2, DELE C1, DELE C2, SIELE — Francés: DELF A1, DELF A2, DELF B1, DELF B2, DALF C1, DALF C2, TCF B1, TCF B2+ — Alemán: Goethe A1, Goethe A2, Goethe B1, Goethe B2, Goethe C1, Goethe C2 — Chino: HSK 1–2 (A1-A2), HSK 3–4 (B1-B2), HSK 5–6 (C1-C2) — Otros: EOI A2, EOI B1, EOI B2, EOI C1, EOI C2. Si hay certificado en el CV úsalo; si solo hay nivel usa el equivalente general."
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

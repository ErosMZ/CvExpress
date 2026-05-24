<?php

namespace App\Services\Cv;

/**
 * Convierte el JSON estructurado de OpenAI al formato interno cv_data
 * que usa el editor del dashboard.
 */
class CvDataMapper
{
    public function toEditorFormat(array $parsed): array
    {
        return [
            // Campos simples (panel Presentación + Contacto)
            'cv_name'       => $this->clean($parsed['name']     ?? null),
            'cv_job_title'  => $this->clean($parsed['title']    ?? null),
            'cv_bio'        => $this->clean($parsed['bio']      ?? null),
            'cv_email'      => $this->clean($parsed['email']    ?? null),
            'cv_phone'      => $this->clean($parsed['phone']    ?? null),
            'cv_location'   => $this->clean($parsed['location'] ?? null),
            'cv_linkedin'   => $this->clean($parsed['linkedin'] ?? null),
            'cv_website'    => $this->clean($parsed['website']  ?? null),
            'habilidades'   => array_values(array_filter(array_merge(
                array_map(fn($s) => $this->clean($s), $parsed['skills']      ?? []),
                array_map(fn($s) => $this->clean($s), $parsed['soft_skills'] ?? []),
            ))),

            // Arrays dinámicos (panels Experiencia, Formación, Idiomas)
            'experiencia' => array_map(fn($e) => [
                'empresa'     => $this->clean($e['company']     ?? null),
                'cargo'       => $this->clean($e['position']    ?? null),
                'periodo'     => $this->clean($e['period']      ?? null),
                'descripcion' => $this->clean($e['description'] ?? null),
            ], $parsed['experience'] ?? []),

            'formacion' => array_map(fn($e) => [
                'institucion' => $this->clean($e['institution'] ?? null),
                'titulo'      => $this->clean($e['degree']      ?? null),
                'periodo'     => $this->clean($e['period']      ?? null),
                'descripcion' => $this->clean($e['description'] ?? null),
            ], $parsed['education'] ?? []),

            'idiomas' => array_map(fn($e) => [
                'idioma' => $this->clean($e['language'] ?? null),
                'nivel'  => $this->clean($e['level']    ?? null),
            ], $parsed['languages'] ?? []),

            'proyectos' => array_map(fn($p) => [
                'nombre'      => $this->clean($p['name']        ?? null),
                'descripcion' => $this->clean($p['description'] ?? null),
                'url'         => $this->clean($p['url']          ?? null),
                'tecnologias' => implode(', ', $p['technologies'] ?? []),
            ], $parsed['projects'] ?? []),

            // Datos extra (para uso futuro)
            'certifications' => $parsed['certifications'] ?? [],
            'github'         => $this->clean($parsed['github'] ?? null),
        ];
    }

    private function clean(?string $val): string
    {
        if ($val === null) return '';
        $t = trim($val);
        // La IA a veces devuelve literales "null", "null – null", "null - null"
        if (strcasecmp($t, 'null') === 0) return '';
        if (preg_match('/^null\s*[–\-]\s*null$/i', $t)) return '';
        return $t;
    }
}

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
            'cv_name'       => $parsed['name']     ?? '',
            'cv_job_title'  => $parsed['title']    ?? '',
            'cv_bio'        => $parsed['bio']       ?? '',
            'cv_email'      => $parsed['email']     ?? '',
            'cv_phone'      => $parsed['phone']     ?? '',
            'cv_location'   => $parsed['location']  ?? '',
            'cv_linkedin'   => $parsed['linkedin']  ?? '',
            'cv_website'    => $parsed['website']   ?? '',
            'cv_skills'     => implode(', ', $parsed['skills'] ?? []),
            'cv_soft_skills'=> implode(', ', $parsed['soft_skills'] ?? []),

            // Arrays dinámicos (panels Experiencia, Formación, Idiomas)
            'experiencia' => array_map(fn($e) => [
                'empresa'     => $e['company']     ?? '',
                'cargo'       => $e['position']    ?? '',
                'periodo'     => $e['period']       ?? '',
                'descripcion' => $e['description']  ?? '',
            ], $parsed['experience'] ?? []),

            'formacion' => array_map(fn($e) => [
                'institucion' => $e['institution'] ?? '',
                'titulo'      => $e['degree']      ?? '',
                'periodo'     => $e['period']      ?? '',
                'descripcion' => $e['description'] ?? '',
            ], $parsed['education'] ?? []),

            'idiomas' => array_map(fn($e) => [
                'idioma' => $e['language'] ?? '',
                'nivel'  => $e['level']    ?? '',
            ], $parsed['languages'] ?? []),

            // Datos extra (para uso futuro / plantillas avanzadas)
            'projects'       => $parsed['projects']       ?? [],
            'certifications' => $parsed['certifications'] ?? [],
            'github'         => $parsed['github']         ?? '',
        ];
    }
}

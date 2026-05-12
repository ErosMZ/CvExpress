<?php

namespace Database\Seeders;

use App\Models\Plan;
use Illuminate\Database\Seeder;

class PlanSeeder extends Seeder
{
    public function run(): void
    {
        $plans = [
            [
                'slug'        => 'basic',
                'name'        => 'Plan Básico',
                'price'       => 4.99,
                'color'       => '#16a34a',
                'badge_label' => null,
                'features'    => [
                    'CV PDF convertido a web profesional',
                    '1 plantilla moderna',
                    'Subdominio propio (eros.expresscv.es)',
                    'Edición básica del contenido',
                    'Enlace para compartir',
                    'Diseño 100% responsivo',
                ],
                'sort_order'  => 1,
            ],
            [
                'slug'        => 'pro',
                'name'        => 'Plan Pro',
                'price'       => 14.99,
                'color'       => '#1A56DB',
                'badge_label' => 'Más popular',
                'features'    => [
                    'Todo del Plan Básico',
                    'Varias plantillas Pro disponibles',
                    'Personalización de colores',
                    'SEO optimizado',
                    'Sin branding de CvXpress',
                    'Exportación a PDF',
                    'Analytics de visitas',
                    'Sección proyectos y GitHub',
                    'Redes sociales integradas',
                ],
                'sort_order'  => 2,
            ],
            [
                'slug'        => 'super_pro',
                'name'        => 'Plan Super Pro',
                'price'       => 29.00,
                'color'       => '#7c3aed',
                'badge_label' => null,
                'features'    => [
                    'Todo del Plan Pro',
                    'Descarga ZIP completo (HTML/CSS/JS)',
                    'Deploy automático incluido',
                    'Usar en tu propio hosting',
                    'Conectar dominio personalizado',
                ],
                'sort_order'  => 3,
            ],
        ];

        foreach ($plans as $plan) {
            Plan::updateOrCreate(['slug' => $plan['slug']], $plan);
        }
    }
}

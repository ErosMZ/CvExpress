<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Los planes de categoría "pdf_download" (y desde ahora "web_download") son
 * de pago único, no anuales. Estaban marcados con billing_cycle="annual"
 * por defecto (dato mal puesto desde el principio), lo que hacía que la UI
 * mostrara "/año" en un plan de pago único. Los corrige a "once".
 */
return new class extends Migration
{
    public function up(): void
    {
        DB::table('plans')
            ->whereIn('category', ['pdf_download', 'web_download'])
            ->update(['billing_cycle' => 'once']);
    }

    public function down(): void
    {
        DB::table('plans')
            ->whereIn('category', ['pdf_download', 'web_download'])
            ->update(['billing_cycle' => 'annual']);
    }
};

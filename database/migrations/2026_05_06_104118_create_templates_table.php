<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('templates', function (Blueprint $table) {

            $table->id();

            // Categoría
            $table->foreignId('category_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();

            // Nombre
            $table->string('name');

            // URL amigable
            $table->string('slug')->unique();

            // Descripción
            $table->text('description')->nullable();

            // Carpeta plantilla
            $table->string('folder');

            // Imagen preview
            $table->string('preview_image')->nullable();

            // Archivo principal
            $table->string('main_file')->default('index.html');

            // Premium
            $table->boolean('is_premium')->default(false);

            // Precio
            $table->decimal('price', 8, 2)->default(0);

            // Destacada
            $table->boolean('is_featured')->default(false);

            // Activa
            $table->boolean('is_active')->default(true);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('templates');
    }
};
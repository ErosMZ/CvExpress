<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Añade la columna cv_path a la tabla users.
     * Los CVs se almacenan en storage/app/private/cvs/{user_id}/
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Ruta relativa al disco 'private' → storage/app/private/cvs/
            $table->string('cv_path')->nullable()->after('remember_token');
            $table->string('cv_original_name')->nullable()->after('cv_path');
            $table->timestamp('cv_uploaded_at')->nullable()->after('cv_original_name');

            // Campos extra del perfil
            $table->string('job_title')->nullable()->after('name');
            $table->string('phone')->nullable()->after('job_title');
            $table->string('location')->nullable()->after('phone');
            $table->text('bio')->nullable()->after('location');
            $table->string('linkedin_url')->nullable()->after('bio');
            $table->string('website_url')->nullable()->after('linkedin_url');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'cv_path',
                'cv_original_name',
                'cv_uploaded_at',
                'job_title',
                'phone',
                'location',
                'bio',
                'linkedin_url',
                'website_url',
            ]);
        });
    }
};
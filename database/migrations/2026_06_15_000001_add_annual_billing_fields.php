<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('plans', function (Blueprint $table) {
            $table->string('billing_cycle', 20)->default('annual')->after('price');
        });

        Schema::table('user_purchases', function (Blueprint $table) {
            $table->timestamp('expires_at')->nullable()->after('purchased_at');
        });
    }

    public function down(): void
    {
        Schema::table('plans', function (Blueprint $table) {
            $table->dropColumn('billing_cycle');
        });

        Schema::table('user_purchases', function (Blueprint $table) {
            $table->dropColumn('expires_at');
        });
    }
};

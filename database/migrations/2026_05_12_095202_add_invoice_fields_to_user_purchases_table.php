<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('user_purchases', function (Blueprint $table) {
            $table->string('invoice_number')->nullable()->unique()->after('payment_reference');
            $table->string('buyer_name')->nullable()->after('invoice_number');
            $table->string('buyer_email')->nullable()->after('buyer_name');
            $table->string('buyer_nif')->nullable()->after('buyer_email');
            $table->string('buyer_address')->nullable()->after('buyer_nif');
        });
    }

    public function down(): void
    {
        Schema::table('user_purchases', function (Blueprint $table) {
            $table->dropColumn(['invoice_number', 'buyer_name', 'buyer_email', 'buyer_nif', 'buyer_address']);
        });
    }
};

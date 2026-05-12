<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_purchases', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('plan_id')->constrained()->cascadeOnDelete();
            $table->enum('status', ['pending', 'active', 'cancelled'])->default('active');
            $table->decimal('amount_paid', 8, 2);
            $table->string('payment_reference')->nullable();
            $table->enum('hosting_type', ['none', 'subdomain', 'paid_hosting'])->default('none');
            $table->string('subdomain')->nullable();
            $table->timestamp('purchased_at')->useCurrent();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_purchases');
    }
};

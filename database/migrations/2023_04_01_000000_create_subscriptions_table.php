<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('subscriptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('plan_name');
            $table->enum('plan_type', ['monthly', 'quarterly', 'yearly'])->default('monthly');
            $table->decimal('price', 10, 2);
            $table->enum('status', ['active', 'cancelled', 'expired'])->default('active');
            $table->timestamp('started_at')->useCurrent();
            $table->timestamp('expires_at');
            $table->string('stripe_subscription_id')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('subscriptions');
    }
};

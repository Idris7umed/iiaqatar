<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->longText('description');
            $table->string('location')->nullable();
            $table->string('featured_image')->nullable();
            $table->dateTime('start_date');
            $table->dateTime('end_date');
            $table->dateTime('registration_deadline')->nullable();
            $table->integer('max_attendees')->nullable();
            $table->decimal('price', 10, 2)->default(0);
            $table->enum('status', ['draft', 'published', 'cancelled'])->default('draft');
            $table->enum('event_type', ['online', 'offline', 'hybrid'])->default('offline');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};

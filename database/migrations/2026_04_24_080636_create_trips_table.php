<?php
// database/migrations/xxxx_create_trips_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('trips', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('origin_city');
            $table->string('destination_city');
            $table->date('departure_date');
            $table->date('arrival_date')->nullable();
            $table->integer('max_requests')->default(3);
            $table->integer('remaining_slots')->default(3);
            $table->string('transport_mode')->nullable(); // pesawat, kereta, mobil, bus
            $table->string('baggage_capacity')->nullable(); // kecil, sedang, besar
            $table->text('notes')->nullable();
            $table->string('status')->default('open'); // open, full, completed, cancelled
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('trips');
    }
};
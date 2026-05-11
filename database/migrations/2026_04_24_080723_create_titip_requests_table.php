<?php
// database/migrations/xxxx_create_titip_requests_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('titip_requests', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('item_name');
            $table->text('description')->nullable();
            $table->string('category')->nullable(); // makanan, elektronik, fashion, dokumen, lainnya
            $table->string('origin_city');
            $table->string('destination_city');
            $table->decimal('budget_min', 12, 2)->nullable();
            $table->decimal('budget_max', 12, 2)->nullable();
            $table->date('deadline')->nullable();
            $table->string('weight_estimate')->nullable(); // ringan, sedang, berat
            $table->string('image')->nullable();
            $table->string('status')->default('open'); // open, assigned, completed, cancelled
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('titip_requests');
    }
};
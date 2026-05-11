<?php
// database/migrations/xxxx_create_ratings_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ratings', function (Blueprint $table) {
            $table->id();
            $table->foreignUuid('order_id')->constrained('orders')->onDelete('cascade');
            $table->foreignId('rater_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('rated_user_id')->constrained('users')->onDelete('cascade');
            $table->integer('score')->comment('1-5');
            $table->text('comment')->nullable();
            $table->timestamps();
            
            // Satu user hanya bisa rate sekali per order
            $table->unique(['order_id', 'rater_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ratings');
    }
};
<?php
// database/migrations/xxxx_create_orders_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('trip_id')->nullable()->constrained('trips')->onDelete('set null');
            $table->foreignUuid('titip_request_id')->nullable()->constrained('titip_requests')->onDelete('set null');
            $table->foreignId('traveller_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('requester_id')->constrained('users')->onDelete('cascade');
            $table->string('status')->default('pending'); 
                // pending, accepted, payment_uploaded, payment_verified, 
                // purchased, in_transit, delivered, completed, cancelled, disputed
            $table->decimal('agreed_price', 12, 2)->nullable();
            $table->decimal('service_fee', 12, 2)->default(0);
            $table->decimal('total_amount', 12, 2)->nullable();
            $table->text('notes_from_traveller')->nullable();
            $table->text('notes_from_requester')->nullable();
            $table->string('payment_proof')->nullable(); // bukti transfer escrow
            $table->string('item_photo')->nullable(); // foto barang + nota
            $table->timestamp('paid_at')->nullable();
            $table->timestamp('delivered_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
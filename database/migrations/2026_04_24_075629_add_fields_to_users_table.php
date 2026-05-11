<?php
// database/migrations/xxxx_add_fields_to_users_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('username')->unique()->after('name');
            $table->string('phone')->nullable()->after('email');
            $table->string('city')->nullable()->after('phone');
            $table->text('bio')->nullable()->after('city');
            $table->string('avatar')->nullable()->after('bio');
            $table->boolean('is_ktp_verified')->default(false)->after('avatar');
            $table->boolean('is_phone_verified')->default(false)->after('is_ktp_verified');
            $table->decimal('trust_score', 5, 2)->default(0)->after('is_phone_verified');
            $table->integer('total_trips')->default(0)->after('trust_score');
            $table->integer('total_ratings')->default(0)->after('total_trips');
            $table->decimal('rating_avg', 3, 2)->default(0)->after('total_ratings');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'username', 'phone', 'city', 'bio', 'avatar',
                'is_ktp_verified', 'is_phone_verified',
                'trust_score', 'total_trips', 'total_ratings', 'rating_avg'
            ]);
        });
    }
};
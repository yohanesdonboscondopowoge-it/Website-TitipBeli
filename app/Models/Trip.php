<?php
// app/Models/Trip.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Trip extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'user_id',
        'origin_city',
        'destination_city',
        'departure_date',
        'arrival_date',
        'max_requests',
        'remaining_slots',
        'transport_mode',
        'baggage_capacity',
        'notes',
        'status',
        'is_active',
    ];

    protected $casts = [
        'departure_date' => 'date',
        'arrival_date' => 'date',
        'is_active' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}
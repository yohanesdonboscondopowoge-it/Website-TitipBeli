<?php
// app/Models/TitipRequest.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class TitipRequest extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'user_id',
        'item_name',
        'description',
        'category',
        'origin_city',
        'destination_city',
        'budget_min',
        'budget_max',
        'deadline',
        'weight_estimate',
        'image',
        'status',
        'is_active',
    ];

    protected $casts = [
        'deadline' => 'date',
        'is_active' => 'boolean',
        'budget_min' => 'decimal:2',
        'budget_max' => 'decimal:2',
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
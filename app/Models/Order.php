<?php
// app/Models/Order.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Order extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'trip_id',
        'titip_request_id',
        'traveller_id',
        'requester_id',
        'status',
        'agreed_price',
        'service_fee',
        'total_amount',
        'notes_from_traveller',
        'notes_from_requester',
        'payment_proof',
        'item_photo',
        'paid_at',
        'delivered_at',
        'completed_at',
    ];

    protected $casts = [
        'paid_at' => 'datetime',
        'delivered_at' => 'datetime',
        'completed_at' => 'datetime',
        'agreed_price' => 'decimal:2',
        'service_fee' => 'decimal:2',
        'total_amount' => 'decimal:2',
    ];

    public function trip()
    {
        return $this->belongsTo(Trip::class);
    }

    public function titipRequest()
    {
        return $this->belongsTo(TitipRequest::class);
    }

    public function traveller()
    {
        return $this->belongsTo(User::class, 'traveller_id');
    }

    public function requester()
    {
        return $this->belongsTo(User::class, 'requester_id');
    }

    public function logs()
    {
        return $this->hasMany(OrderLog::class);
    }

    public function ratings()
    {
        return $this->hasMany(Rating::class);
    }

    // Helper: cek apakah user adalah participant
    public function isParticipant(User $user)
    {
        return $this->traveller_id === $user->id || $this->requester_id === $user->id;
    }

    // Tambah log otomatis
    public function addLog(string $status, ?string $note = null, ?User $user = null)
    {
        return $this->logs()->create([
            'status' => $status,
            'note' => $note,
            'created_by' => $user?->id,
        ]);
    }
}
<?php
// app/Models/User.php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'name',
        'username',
        'email',
        'password',
        'phone',
        'city',
        'bio',
        'avatar',
        'is_ktp_verified',
        'is_phone_verified',
        'trust_score',
        'total_trips',
        'total_ratings',
        'rating_avg',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_ktp_verified' => 'boolean',
            'is_phone_verified' => 'boolean',
            'is_admin' => 'boolean',
            'is_active' => 'boolean',
        ];
    }

    // Relasi ke trips
    public function trips()
    {
        return $this->hasMany(Trip::class);
    }

    // Relasi ke requests
    public function requests()
    {
        return $this->hasMany(TitipRequest::class);
    }

    // Relasi ke orders sebagai traveller
    public function ordersAsTraveller()
    {
        return $this->hasMany(Order::class, 'traveller_id');
    }

    // Relasi ke orders sebagai requester
    public function ordersAsRequester()
    {
        return $this->hasMany(Order::class, 'requester_id');
    }

    // Relasi ke ratings yang diberikan
    public function ratingsGiven()
    {
        return $this->hasMany(Rating::class, 'rater_id');
    }

    // Relasi ke ratings yang diterima
    public function ratingsReceived()
    {
        return $this->hasMany(Rating::class, 'rated_user_id');
    }

    // Hitung ulang trust score
    public function recalculateTrustScore()
    {
        $ratingScore = $this->rating_avg * 20; // max 100
        $transactionScore = min($this->total_trips * 2, 30); // max 30
        $verifiedScore = ($this->is_ktp_verified ? 15 : 0) + ($this->is_phone_verified ? 5 : 0);
        $ageScore = min(now()->diffInMonths($this->created_at), 10); // max 10

        $this->trust_score = $ratingScore * 0.4 + $transactionScore * 0.3 + $verifiedScore * 0.2 + $ageScore * 0.1;
        $this->save();
    }
}
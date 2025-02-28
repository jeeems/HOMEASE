<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rating extends Model
{
    use HasFactory;

    // In app/Models/Rating.php
    protected $fillable = [
        'worker_id',
        'client_id',
        'booking_id',
        'booking_title',
        'rating',
        'comment',
        'review_photos'
    ];

    public function worker()
    {
        return $this->belongsTo(User::class, 'worker_id');
    }

    public function client()
    {
        return $this->belongsTo(User::class, 'client_id');
    }
}

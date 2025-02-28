<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'worker_id',
        'client_id',
        'service_type',
        'scheduled_date',
        'status',
        'client_address',
        'booking_title',
        'notes',
        'completion_date',
        'hours_worked',
        'total_amount',
    ];

    protected $casts = [
        'scheduled_date' => 'datetime',
        'completion_date' => 'datetime',
    ];

    protected $with = ['worker', 'client']; // Eager loading

    // Status Constants
    const STATUS_PENDING = 'pending';
    const STATUS_CONFIRMED = 'confirmed';
    const STATUS_COMPLETED = 'completed';
    const STATUS_CANCELLED = 'cancelled';

    // Relationships
    public function worker()
    {
        return $this->belongsTo(User::class, 'worker_id');
    }

    public function client()
    {
        return $this->belongsTo(User::class, 'client_id');
    }
}

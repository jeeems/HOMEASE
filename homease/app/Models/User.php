<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Model;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'role',
        'first_name',
        'middle_name',
        'last_name',
        'birthdate',
        'gender',
        'email',
        'email_verified_at',
        'phone',
        'street',
        'barangay',
        'city',
        'zip_code',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    protected $appends = ['full_name'];

    public function getFullNameAttribute()
    {
        return "{$this->first_name} {$this->last_name}";
    }

    public function profile()
    {
        return $this->hasOne(Profile::class, 'user_id', 'id');
    }

    // Define the relationship
    public function workerVerification(): HasOne
    {
        return $this->hasOne(WorkerVerification::class, 'user_id', 'id');
    }

    public function services(): HasOne
    {
        return $this->hasOne(WorkerVerification::class, 'user_id', 'id');
    }

    public function ratings()
    {
        return $this->hasMany(Rating::class, 'worker_id');
    }

    public function averageRating()
    {
        return $this->ratings()->avg('rating') ?? 0; // Returns 0 if no ratings
    }
    public function getHourlyRateAttribute()
    {
        return $this->workerVerification->hourly_rate ?? 0; // Default to 0 if not set
    }

    public function clientBookings()
    {
        return $this->hasMany(Booking::class, 'client_id');
    }

    public function workerBookings()
    {
        return $this->hasMany(Booking::class, 'worker_id');
    }

    public function activeBookings()
    {
        // For clients
        if ($this->role === 'client') {
            return $this->clientBookings()->where('status', 'ongoing');
        }
        // For workers
        return $this->workerBookings()->where('status', 'ongoing');
    }
}

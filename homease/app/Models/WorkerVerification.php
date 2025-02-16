<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkerVerification extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'emergency_name',
        'emergency_relationship',
        'emergency_phone',
        'service_type',
        'hourly_rate',
        'experience',
        'specialization',
        'work_locations',
        'reference',
        'photo',
        'gov_id',
        'clearance',
        'transportation',
        'tools',
        'agreed_terms',
        'agreed_privacy_policy'
    ];
}

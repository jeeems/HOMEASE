<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkerAvailability extends Model
{
    use HasFactory;

    protected $table = 'worker_availability';

    protected $fillable = ['worker_id', 'is_available'];

    public function user()
    {
        return $this->belongsTo(User::class, 'worker_id');
    }
}

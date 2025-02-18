<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    protected $fillable = ['profile_picture'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'profile_picture',
        'name',
        'phone',
        'address',
        'bank_details',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}


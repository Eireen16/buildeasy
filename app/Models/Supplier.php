<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Supplier extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'profile_picture',
        'company_name',
        'license_number',
        'phone',
        'address',
        'location',
        'bank_details',
        'is_approved',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}


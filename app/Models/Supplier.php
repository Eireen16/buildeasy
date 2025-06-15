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

    protected $casts = [
        'is_approved' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function materials()
    {
        return $this->hasMany(Material::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}


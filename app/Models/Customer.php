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

    // Relationship: A customer has one cart
    public function cart()
    {
        return $this->hasOne(Cart::class);
    }

    // Get or create cart for customer
    public function getOrCreateCart()
    {
        if (!$this->cart) {
            $this->cart()->create();
        }
        return $this->cart;
    }
}


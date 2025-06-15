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

    //Customer Order Relationship
    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function calculatorProjects()
    {
        return $this->hasMany(CalculatorSavedProject::class);
    }

    public function likes()
    {
        return $this->hasMany(Like::class);
    }

    public function likedMaterials()
    {
        return $this->belongsToMany(Material::class, 'likes');
    }
}


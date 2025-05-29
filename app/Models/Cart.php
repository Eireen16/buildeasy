<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;

    protected $fillable = ['customer_id'];

    // Relationship: A cart belongs to a customer
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    // Relationship: A cart has many cart items
    public function cartItems()
    {
        return $this->hasMany(CartItem::class);
    }

    // Calculate total amount of cart
    public function getTotalAttribute()
    {
        return $this->cartItems->sum(function ($item) {
            return $item->price * $item->quantity;
        });
    }

    // Get total items count
    public function getTotalItemsAttribute()
    {
        return $this->cartItems->sum('quantity');
    }
}

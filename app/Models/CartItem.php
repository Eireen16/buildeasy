<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CartItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'cart_id',
        'material_id',
        'material_variation_id',
        'quantity',
        'price'
    ];

    // Relationship: A cart item belongs to a cart
    public function cart()
    {
        return $this->belongsTo(Cart::class);
    }

    // Relationship: A cart item belongs to a material
    public function material()
    {
        return $this->belongsTo(Material::class);
    }

    // Relationship: A cart item may belong to a material variation
    public function materialVariation()
    {
        return $this->belongsTo(MaterialVariation::class);
    }

    // Calculate subtotal for this item
    public function getSubtotalAttribute()
    {
        return $this->price * $this->quantity;
    }
}

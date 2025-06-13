<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'material_id',
        'material_variation_id',
        'material_name',
        'variation_name',
        'variation_value',
        'quantity',
        'unit_price',
        'subtotal',
    ];

    protected $casts = [
        'unit_price' => 'decimal:2',
        'subtotal' => 'decimal:2',
    ];

    // Relationships
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function material()
    {
        return $this->belongsTo(Material::class);
    }

    public function materialVariation()
    {
        return $this->belongsTo(MaterialVariation::class);
    }

    public function review()
    {
        return $this->hasOne(Review::class);
    }
}

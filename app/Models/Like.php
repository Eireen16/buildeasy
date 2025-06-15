<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Like extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'material_id'
    ];

    // Relationship: A like belongs to a customer
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    // Relationship: A like belongs to a material
    public function material()
    {
        return $this->belongsTo(Material::class);
    }
}
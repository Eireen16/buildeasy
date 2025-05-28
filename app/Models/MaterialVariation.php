<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MaterialVariation extends Model
{
    use HasFactory;

    protected $fillable = ['material_id', 'variation_name', 'variation_value', 'stock'];

    // Relationship: A variation belongs to a material
    public function material()
    {
        return $this->belongsTo(Material::class);
    }
}

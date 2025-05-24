<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MaterialVariation extends Model
{
    use HasFactory;

    protected $fillable = ['material_id', 'variation_name', 'stock'];

    public function material()
    {
        return $this->belongsTo(Material::class);
    }
}


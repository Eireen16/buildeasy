<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\MaterialVariation;
use App\Models\MaterialImage;


class Material extends Model
{
    use HasFactory;

    protected $fillable = [
        'supplier_id',
        'category_id',
        'name',
        'price',
        'stock',
        'images',
        'variations',
        'description',
        'environmental_impact_rating',
        'carbon_footprint_rating',
        'recyclability_rating',
        'sustainability_rating',
    ];

    protected $casts = [
        'images' => 'array',
        'variations' => 'array',
    ];

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    // public function variations()
    // {
    //     return $this->hasMany(MaterialVariation::class);
    // }

    // public function images()
    // {
    //     return $this->hasMany(MaterialImage::class);
    // }
}

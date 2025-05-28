<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Material extends Model
{
    use HasFactory;

    protected $fillable = [
        'supplier_id',
        'category_id',
        'sub_category_id',
        'name',
        'price',
        'stock',
        'description',
        'environmental_impact_rating',
        'carbon_footprint_rating',
        'recyclability_rating',
        'sustainability_rating'
    ];

    // Automatically calculate sustainability rating before saving
    protected static function boot()
    {
        parent::boot();
        
        static::saving(function ($material) {
            $material->sustainability_rating = (
                $material->environmental_impact_rating +
                $material->carbon_footprint_rating +
                $material->recyclability_rating
            ) / 3;
        });
    }

    // Relationship: A material belongs to a supplier (user)
    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'supplier_id');
    }

    // Relationship: A material belongs to a category
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    // Relationship: A material belongs to a sub-category
    public function subCategory()
    {
        return $this->belongsTo(SubCategory::class, 'sub_category_id');
    }

    // Relationship: A material has many images
    public function images()
    {
        return $this->hasMany(MaterialImage::class)->orderBy('order');
    }

    // Relationship: A material has many variations
    public function variations()
    {
        return $this->hasMany(MaterialVariation::class);
    }

    // Get first image path or placeholder
    public function getFirstImageAttribute()
    {
        return $this->images->first()->image_path ?? 'images/placeholder.jpg';
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubCategory extends Model
{
    use HasFactory;

    protected $fillable = ['category_id', 'subcategory'];

    // Relationship: A sub-category belongs to a category
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    // Relationship: A sub-category has many materials
    public function materials()
    {
        return $this->hasMany(Material::class, 'sub_category_id');
    }
}

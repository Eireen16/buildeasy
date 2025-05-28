<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = ['category'];

    // Relationship: A category has many sub-categories
    public function subCategories()
    {
        return $this->hasMany(SubCategory::class);
    }

    // Relationship: A category has many materials
    public function materials()
    {
        return $this->hasMany(Material::class);
    }
}

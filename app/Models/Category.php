<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'category',
        'sub_category',
    ];

    public function materials()
    {
        return $this->hasMany(Material::class);
    }
}


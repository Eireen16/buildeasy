<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CalculatorSavedProject extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'project_name',
        'calculations',
        'total_estimated_cost',
        'notes'
    ];

    protected $casts = [
        'calculations' => 'array',
        'total_estimated_cost' => 'decimal:2'
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}

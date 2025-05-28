<?php

namespace App\Http\Controllers;

use App\Models\Material;
use Illuminate\Http\Request;

class CustomerMaterialController extends Controller
{
    // Show customer dashboard with all materials
    public function index()
    {
        $materials = Material::with(['images', 'supplier', 'category', 'subCategory'])
                            ->latest()
                            ->get();
        
        return view('customer.dashboard', compact('materials'));
    }

    // Show single material for customer
    public function show($id)
    {
        $material = Material::with(['images', 'variations', 'category', 'subCategory', 'supplier'])
                           ->findOrFail($id);
        
        return view('customer.materials.show', compact('material'));
    }
}
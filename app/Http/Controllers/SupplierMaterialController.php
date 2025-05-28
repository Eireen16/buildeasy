<?php

namespace App\Http\Controllers;

use App\Models\Material;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SupplierMaterialController extends Controller
{
    // Show supplier dashboard with their materials
    public function index()
    {
        $materials = Material::with(['images', 'supplier', 'category', 'subCategory'])
                            ->where('supplier_id', Auth::user()->supplier->id)
                            ->latest()
                            ->get();
        
        return view('supplier.dashboard', compact('materials'));
    }

    // Show single material for supplier
    public function show($id)
    {
        $material = Material::with(['images', 'variations', 'category', 'subCategory'])
                           ->where('supplier_id', Auth::user()->supplier->id)
                           ->findOrFail($id);
        
        return view('supplier.materials.show', compact('material'));
    }
}

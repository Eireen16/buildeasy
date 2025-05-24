<?php

namespace App\Http\Controllers;

use App\Models\Material;
use Illuminate\Support\Facades\Auth;

class SupplierMaterialController extends Controller
{
    public function index()
    {
        $materials = Material::where('supplier_id', Auth::user()->supplier->id)->latest()->get();
        return view('supplier.dashboard', compact('materials'));
    }

    public function show($id)
    {
        $material = Material::with('supplier')->findOrFail($id);
        return view('supplier.materials.show', compact('material'));
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Material;
use App\Models\Category;
use App\Models\Supplier;
use Illuminate\Support\Facades\Auth;

class MaterialController extends Controller
{
    /**
     * Show form to create a new material listing.
     */
    public function create()
    {
        $categories = Category::all();
        return view('supplier.materials.create', compact('categories'));
    }

    /**
     * Store a newly created material listing.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric',
            'stock' => 'required|integer',
            'category_id' => 'required|exists:categories,id',
            'images.*' => 'nullable|image|max:2048',
            'description' => 'nullable|string',
            'variations' => 'nullable|array',
            'variations.*' => 'string|max:255',
            'environmental_impact_rating' => 'nullable|integer|min:1|max:5',
            'carbon_footprint_rating' => 'nullable|integer|min:1|max:5',
            'recyclability_rating' => 'nullable|integer|min:1|max:5',
        ]);

        // Handle image upload
        $imagePaths = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('materials', 'public');
                $imagePaths[] = '/storage/' . $path;
            }
        }

        // Calculate sustainability rating
        $ratings = array_filter([
            $request->environmental_impact_rating,
            $request->carbon_footprint_rating,
            $request->recyclability_rating
        ]);

        $sustainability = count($ratings) > 0 ? round(array_sum($ratings) / count($ratings), 1) : null;

        Material::create([
            'supplier_id' => Auth::user()->supplier->id,
            'category_id' => $request->category_id,
            'name' => $request->name,
            'price' => $request->price,
            'stock' => $request->stock,
            'images' => $imagePaths,
            'variations' => $request->variations,
            'description' => $request->description,
            'environmental_impact_rating' => $request->environmental_impact_rating,
            'carbon_footprint_rating' => $request->carbon_footprint_rating,
            'recyclability_rating' => $request->recyclability_rating,
            'sustainability_rating' => $sustainability,
        ]);

        return redirect()->route('supplier.dashboard')->with('success', 'Material added successfully!');
    }

    // public function search(Request $request)
    // {
    //     $query = $request->input('query');

    //     $materials = Material::where('name', 'like', '%' . $query . '%')->get();

    // return view('customer.materials.search', compact('materials', 'query'));
    // }

}

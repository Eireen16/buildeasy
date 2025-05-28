<?php

namespace App\Http\Controllers;

use App\Models\Material;
use App\Models\Category;
use App\Models\SubCategory;
use App\Models\MaterialImage;
use App\Models\MaterialVariation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class MaterialController extends Controller
{
    // Show the form for creating a new material
    public function create()
    {
        $categories = Category::with('subCategories')->get();
        return view('materials.create', compact('categories'));
    }

    // Store a new material
    public function store(Request $request)
    {
        // Validate the request
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'category_id' => 'required|exists:categories,id',
            'sub_category_id' => 'required|exists:sub_categories,id',
            'description' => 'required|string',
            'environmental_impact_rating' => 'required|integer|between:1,5',
            'carbon_footprint_rating' => 'required|integer|between:1,5',
            'recyclability_rating' => 'required|integer|between:1,5',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Create the material
        $material = Material::create([
            'supplier_id' => Auth::user()->supplier->id,
            'name' => $request->name,
            'price' => $request->price,
            'stock' => $request->stock,
            'category_id' => $request->category_id,
            'sub_category_id' => $request->sub_category_id,
            'description' => $request->description,
            'environmental_impact_rating' => $request->environmental_impact_rating,
            'carbon_footprint_rating' => $request->carbon_footprint_rating,
            'recyclability_rating' => $request->recyclability_rating,
        ]);

        // Handle image uploads
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $index => $image) {
                $path = $image->store('materials', 'public');
                MaterialImage::create([
                    'material_id' => $material->id,
                    'image_path' => 'storage/' . $path,
                    'order' => $index
                ]);
            }
        }

        // Handle variations
        if ($request->has('variations')) {
            foreach ($request->variations as $variation) {
                if (!empty($variation['name']) && !empty($variation['value'])) {
                    MaterialVariation::create([
                        'material_id' => $material->id,
                        'variation_name' => $variation['name'],
                        'variation_value' => $variation['value'],
                        'stock' => $variation['stock'] ?? 0
                    ]);
                }
            }
        }

        return redirect()->route('supplier.dashboard')->with('success', 'Material added successfully!');
    }

    // Get subcategories for a category (AJAX)
    public function getSubCategories($categoryId)
    {
        $subCategories = SubCategory::where('category_id', $categoryId)->get();
        return response()->json($subCategories);
    }

    // Show the form for editing a material
    public function edit(Material $material)
    {
        // Check if the material belongs to the authenticated supplier
        if ($material->supplier_id !== Auth::user()->supplier->id) {
            abort(403, 'Unauthorized action.');
        }

        $categories = Category::with('subCategories')->get();
        $material->load(['images', 'variations', 'category', 'subCategory']);
        
        return view('materials.edit', compact('material', 'categories'));
    }

    // Update an existing material
    public function update(Request $request, Material $material)
    {
        // Check if the material belongs to the authenticated supplier
        if ($material->supplier_id !== Auth::user()->supplier->id) {
            abort(403, 'Unauthorized action.');
        }

        // Validate the request
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'category_id' => 'required|exists:categories,id',
            'sub_category_id' => 'required|exists:sub_categories,id',
            'description' => 'required|string',
            'environmental_impact_rating' => 'required|integer|between:1,5',
            'carbon_footprint_rating' => 'required|integer|between:1,5',
            'recyclability_rating' => 'required|integer|between:1,5',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Update the material
        $material->update([
            'name' => $request->name,
            'price' => $request->price,
            'stock' => $request->stock,
            'category_id' => $request->category_id,
            'sub_category_id' => $request->sub_category_id,
            'description' => $request->description,
            'environmental_impact_rating' => $request->environmental_impact_rating,
            'carbon_footprint_rating' => $request->carbon_footprint_rating,
            'recyclability_rating' => $request->recyclability_rating,
        ]);

        // Handle new image uploads
        if ($request->hasFile('images')) {
            // Get current max order
            $maxOrder = $material->images()->max('order') ?? -1;
            
            foreach ($request->file('images') as $index => $image) {
                $path = $image->store('materials', 'public');
                MaterialImage::create([
                    'material_id' => $material->id,
                    'image_path' => 'storage/' . $path,
                    'order' => $maxOrder + $index + 1
                ]);
            }
        }

        // Handle image deletions
        if ($request->has('delete_images')) {
            foreach ($request->delete_images as $imageId) {
                $image = MaterialImage::where('material_id', $material->id)->find($imageId);
                if ($image) {
                    // Delete file from storage
                    $filePath = str_replace('storage/', '', $image->image_path);
                    Storage::disk('public')->delete($filePath);
                    // Delete record
                    $image->delete();
                }
            }
        }

        // Handle variations - delete existing and create new ones
        $material->variations()->delete();
        
        if ($request->has('variations')) {
            foreach ($request->variations as $variation) {
                if (!empty($variation['name']) && !empty($variation['value'])) {
                    MaterialVariation::create([
                        'material_id' => $material->id,
                        'variation_name' => $variation['name'],
                        'variation_value' => $variation['value'],
                        'stock' => $variation['stock'] ?? 0
                    ]);
                }
            }
        }

        return redirect()->route('supplier.dashboard')->with('success', 'Material updated successfully!');
    }

    // Delete a material
    public function destroy(Material $material)
    {
        // Check if the material belongs to the authenticated supplier
        if ($material->supplier_id !== Auth::user()->supplier->id) {
            abort(403, 'Unauthorized action.');
        }

        // Delete associated images from storage
        foreach ($material->images as $image) {
            $filePath = str_replace('storage/', '', $image->image_path);
            Storage::disk('public')->delete($filePath);
        }

        // Delete the material (this will cascade delete images and variations due to foreign key constraints)
        $material->delete();

        return redirect()->route('supplier.dashboard')->with('success', 'Material deleted successfully!');
    }
}
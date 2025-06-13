<?php

namespace App\Http\Controllers;

use App\Models\Material;
use App\Models\Review;
use App\Models\OrderItem;
use App\Models\Category;
use App\Models\SubCategory;
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

    //Search function 
    public function search(Request $request)
    {
        $searchQuery = $request->input('search', '');
        
        // Start building the query
        $query = Material::with(['images', 'category', 'subCategory'])
                           ->where('supplier_id', Auth::user()->supplier->id);

        // Apply search filter if search term exists
        if (!empty($searchQuery)) {
            $query->where(function($q) use ($searchQuery) {
                $q->where('name', 'LIKE', '%' . $searchQuery . '%')
                  ->orWhere('description', 'LIKE', '%' . $searchQuery . '%')
                  ->orWhereHas('category', function($categoryQuery) use ($searchQuery) {
                      $categoryQuery->where('category', 'LIKE', '%' . $searchQuery . '%');
                  })
                  ->orWhereHas('subCategory', function($subCategoryQuery) use ($searchQuery) {
                      $subCategoryQuery->where('subcategory', 'LIKE', '%' . $searchQuery . '%');
                  });
            });
        }

        // Get filtered materials with pagination for better performance
        $materials = $query->latest()->paginate(12)->appends($request->all());


        // Calculate search results count
        $totalResults = $query->count();

        return view('supplier.search', compact('materials', 'searchQuery', 'totalResults'));
    }

    // Show materials by category for supplier
    public function categoryView($categoryId)
    {
        $category = Category::with('subCategories')->findOrFail($categoryId);
        
        // Get materials for this category that belong to the authenticated supplier
        $materials = Material::with(['images', 'category', 'subCategory'])
                            ->where('supplier_id', Auth::user()->supplier->id)
                            ->where('category_id', $categoryId)
                            ->latest()
                            ->paginate(12);

        return view('supplier.category', compact('category', 'materials'));
    }

    // Show materials by subcategory for supplier
    public function subCategoryView($categoryId, $subCategoryId)
    {
        $category = Category::findOrFail($categoryId);
        $subCategory = SubCategory::where('id', $subCategoryId)
                                 ->where('category_id', $categoryId)
                                 ->firstOrFail();

        // Get materials for this subcategory that belong to the authenticated supplier
        $materials = Material::with(['images', 'category', 'subCategory'])
                            ->where('supplier_id', Auth::user()->supplier->id)
                            ->where('sub_category_id', $subCategoryId)
                            ->latest()
                            ->paginate(12);

        return view('supplier.subcategory', compact('category', 'subCategory', 'materials'));
    }


    // Show single material for supplier
    public function show($id)
    {
        $material = Material::with(['images', 'variations', 'category', 'subCategory'])
                           ->where('supplier_id', Auth::user()->supplier->id)
                           ->findOrFail($id);

        // Get reviews with pagination (show 10 per page)
        $reviews = Review::where('material_id', $id)
                        ->orderBy('created_at', 'desc')
                        ->paginate(10);

        // Calculate review statistics
        $allReviews = Review::where('material_id', $id)->get();
        $averageRating = $allReviews->avg('rating');
        $reviewCount = $allReviews->count();
        
        // Get rating breakdown
        $ratingBreakdown = [
            5 => $allReviews->where('rating', 5)->count(),
            4 => $allReviews->where('rating', 4)->count(),
            3 => $allReviews->where('rating', 3)->count(),
            2 => $allReviews->where('rating', 2)->count(),
            1 => $allReviews->where('rating', 1)->count(),
        ];

        // Calculate order count for this material
        $orderCount = OrderItem::where('material_id', $id)->count();
        
        return view('supplier.materials.show', compact('material','reviews', 'averageRating', 'reviewCount', 'ratingBreakdown','orderCount'));
    }
}

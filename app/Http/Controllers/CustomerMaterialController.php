<?php

namespace App\Http\Controllers;

use App\Models\Material;
use App\Models\Review;
use App\Models\Supplier;
use App\Models\Category;
use App\Models\SubCategory;
use Illuminate\Http\Request;

class CustomerMaterialController extends Controller
{
    // Show customer dashboard with all materials and filters
    public function index(Request $request)
    {
        $query = Material::with(['images', 'supplier', 'category', 'subCategory']);

        // Filter by sustainability rating
        if ($request->has('sustainability_filter') && $request->sustainability_filter != '') {
            $sustainabilityFilter = $request->sustainability_filter;
            
            switch ($sustainabilityFilter) {
                case '5':
                    $query->where('sustainability_rating', '>=', 4.5);
                    break;
                case '4':
                    $query->whereBetween('sustainability_rating', [3.5, 4.49]);
                    break;
                case '3':
                    $query->whereBetween('sustainability_rating', [2.5, 3.49]);
                    break;
                case '2':
                    $query->whereBetween('sustainability_rating', [1.5, 2.49]);
                    break;
                case '1':
                    $query->where('sustainability_rating', '<', 1.5);
                    break;
            }
        }

        // Filter by location
        if ($request->has('location_filter') && $request->location_filter != '') {
            $query->whereHas('supplier', function($q) use ($request) {
                $q->where('location', 'LIKE', '%' . $request->location_filter . '%');
            });
        }

        // Get filtered materials
        $materials = $query->latest()->get();

        // Get unique locations for filter dropdown
        $locations = Supplier::whereNotNull('location')
                            ->where('location', '!=', '')
                            ->distinct()
                            ->pluck('location')
                            ->sort();

        return view('customer.dashboard', compact('materials', 'locations'));
    }

    // Search function with filters
    public function search(Request $request)
    {
        $searchQuery = $request->input('search', '');
        
        // Start building the query
        $query = Material::with(['images', 'supplier', 'category', 'subCategory']);

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
                  })
                  ->orWhereHas('supplier', function($supplierQuery) use ($searchQuery) {
                      $supplierQuery->where('company_name', 'LIKE', '%' . $searchQuery . '%');
                  });
            });
        }

        // Apply sustainability filter
        if ($request->has('sustainability_filter') && $request->sustainability_filter != '') {
            $sustainabilityFilter = $request->sustainability_filter;
            
            switch ($sustainabilityFilter) {
                case '5':
                    $query->where('sustainability_rating', '>=', 4.5);
                    break;
                case '4':
                    $query->whereBetween('sustainability_rating', [3.5, 4.49]);
                    break;
                case '3':
                    $query->whereBetween('sustainability_rating', [2.5, 3.49]);
                    break;
                case '2':
                    $query->whereBetween('sustainability_rating', [1.5, 2.49]);
                    break;
                case '1':
                    $query->where('sustainability_rating', '<', 1.5);
                    break;
            }
        }

        // Apply location filter
        if ($request->has('location_filter') && $request->location_filter != '') {
            $query->whereHas('supplier', function($q) use ($request) {
                $q->where('location', 'LIKE', '%' . $request->location_filter . '%');
            });
        }

        // Get filtered materials with pagination for better performance
        $materials = $query->latest()->paginate(12)->appends($request->all());

        // Get unique locations for filter dropdown
        $locations = Supplier::whereNotNull('location')
                            ->where('location', '!=', '')
                            ->distinct()
                            ->pluck('location')
                            ->sort();

        // Calculate search results count
        $totalResults = $query->count();

        return view('customer.search', compact('materials', 'locations', 'searchQuery', 'totalResults'));
    }

        // Show materials by category
    public function showByCategory($categoryId, Request $request)
    {
        $category = Category::with('subCategories')->findOrFail($categoryId);
        
        $query = Material::with(['images', 'supplier', 'category', 'subCategory'])
                         ->where('category_id', $categoryId);

        // Apply filters similar to search method
        if ($request->has('sustainability_filter') && $request->sustainability_filter != '') {
            $sustainabilityFilter = $request->sustainability_filter;
            
            switch ($sustainabilityFilter) {
                case '5':
                    $query->where('sustainability_rating', '>=', 4.5);
                    break;
                case '4':
                    $query->whereBetween('sustainability_rating', [3.5, 4.49]);
                    break;
                case '3':
                    $query->whereBetween('sustainability_rating', [2.5, 3.49]);
                    break;
                case '2':
                    $query->whereBetween('sustainability_rating', [1.5, 2.49]);
                    break;
                case '1':
                    $query->where('sustainability_rating', '<', 1.5);
                    break;
            }
        }

        if ($request->has('location_filter') && $request->location_filter != '') {
            $query->whereHas('supplier', function($q) use ($request) {
                $q->where('location', 'LIKE', '%' . $request->location_filter . '%');
            });
        }

        $materials = $query->latest()->paginate(12)->appends($request->all());

        // Get unique locations for filter dropdown
        $locations = Supplier::whereNotNull('location')
                            ->where('location', '!=', '')
                            ->distinct()
                            ->pluck('location')
                            ->sort();

        return view('customer.category', compact('materials', 'category', 'locations'));
    }

    // Show materials by subcategory
    public function showBySubCategory($categoryId, $subCategoryId, Request $request)
    {
        $category = Category::findOrFail($categoryId);
        $subCategory = SubCategory::where('id', $subCategoryId)
                                 ->where('category_id', $categoryId)
                                 ->firstOrFail();
        
        $query = Material::with(['images', 'supplier', 'category', 'subCategory'])
                         ->where('category_id', $categoryId)
                         ->where('sub_category_id', $subCategoryId);

        // Apply filters
        if ($request->has('sustainability_filter') && $request->sustainability_filter != '') {
            $sustainabilityFilter = $request->sustainability_filter;
            
            switch ($sustainabilityFilter) {
                case '5':
                    $query->where('sustainability_rating', '>=', 4.5);
                    break;
                case '4':
                    $query->whereBetween('sustainability_rating', [3.5, 4.49]);
                    break;
                case '3':
                    $query->whereBetween('sustainability_rating', [2.5, 3.49]);
                    break;
                case '2':
                    $query->whereBetween('sustainability_rating', [1.5, 2.49]);
                    break;
                case '1':
                    $query->where('sustainability_rating', '<', 1.5);
                    break;
            }
        }

        if ($request->has('location_filter') && $request->location_filter != '') {
            $query->whereHas('supplier', function($q) use ($request) {
                $q->where('location', 'LIKE', '%' . $request->location_filter . '%');
            });
        }

        $materials = $query->latest()->paginate(12)->appends($request->all());

        // Get unique locations for filter dropdown
        $locations = Supplier::whereNotNull('location')
                            ->where('location', '!=', '')
                            ->distinct()
                            ->pluck('location')
                            ->sort();

        return view('customer.subcategory', compact('materials', 'category', 'subCategory', 'locations'));
    }

    // Show single material for customer
    public function show($id)
    {
        $material = Material::with(['images', 'variations', 'category', 'subCategory', 'supplier'])
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
        
        return view('customer.materials.show', compact('material', 'reviews', 'averageRating', 'reviewCount', 'ratingBreakdown'));
    }
}
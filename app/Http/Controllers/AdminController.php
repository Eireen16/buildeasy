<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Supplier;
use App\Models\Customer;
use App\Models\Material;
use App\Models\MaterialImage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class AdminController extends Controller
{
    // Admin Dashboard with User Management
    public function dashboard(Request $request)
    {
        $search = $request->get('search');
        
        // Get statistics
        $totalUsers = User::whereIn('role', ['supplier', 'customer'])->count();
        $totalCustomers = User::where('role', 'customer')->count();
        $totalSuppliers = User::where('role', 'supplier')->count();
        $pendingSuppliers = Supplier::where('is_approved', false)->count();
        
        // Get suppliers with their user data
        $suppliersQuery = User::where('role', 'supplier')
            ->with('supplier');
            
        // Get customers with their user data
        $customersQuery = User::where('role', 'customer')
            ->with('customer');

        // Apply search filter if provided
        if ($search) {
            $suppliersQuery->where(function($query) use ($search) {
                $query->where('id', 'like', "%{$search}%")
                      ->orWhere('username', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%")
                      ->orWhereHas('supplier', function($q) use ($search) {
                          $q->where('company_name', 'like', "%{$search}%");
                      });
            });
            
            $customersQuery->where(function($query) use ($search) {
                $query->where('id', 'like', "%{$search}%")
                      ->orWhere('username', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%")
                      ->orWhereHas('customer', function($q) use ($search) {
                          $q->where('name', 'like', "%{$search}%");
                      });
            });
        }

        $suppliers = $suppliersQuery->get();
        $customers = $customersQuery->get();

        return view('admin.dashboard', compact(
            'suppliers', 
            'customers', 
            'search',
            'totalUsers',
            'totalCustomers',
            'totalSuppliers',
            'pendingSuppliers'
        ));
    }

    // Show all suppliers who need approval
    public function pendingSuppliers()
    {
        $suppliers = Supplier::where('is_approved', false)->with('user')->get();
        return view('admin.pending-suppliers', compact('suppliers'));
    }

    // Approve a supplier
    public function approveSupplier($id)
    {
        $supplier = Supplier::findOrFail($id);
        $supplier->is_approved = true;
        $supplier->save();

        return redirect()->back()->with('success', 'Supplier approved successfully!');
    }

    // View all users (suppliers and customers) - kept for backward compatibility
    public function viewUsers(Request $request)
    {
        return $this->dashboard($request);
    }

    // View specific user details
    public function viewUser($id)
    {
        $user = User::with(['supplier', 'customer'])->findOrFail($id);
        
        return view('admin.user-details', compact('user'));
    }

    // Delete user account
    public function deleteUser($id)
    {
        try {
            DB::transaction(function() use ($id) {
                $user = User::findOrFail($id);
                
                // Delete related data based on role
                if ($user->role === 'supplier' && $user->supplier) {
                    // You might want to handle materials, orders, etc. here
                    $user->supplier->delete();
                } elseif ($user->role === 'customer' && $user->customer) {
                    // Handle cart, orders, reviews, etc. here
                    if ($user->customer->cart) {
                        $user->customer->cart->cartItems()->delete();
                        $user->customer->cart->delete();
                    }
                    $user->customer->delete();
                }
                
                $user->delete();
            });

            return redirect()->route('admin.dashboard')->with('success', 'User deleted successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error deleting user: ' . $e->getMessage());
        }
    }

    // (Optional) Delete a supplier - kept for backward compatibility
    public function deleteSupplier($id)
    {
        $supplier = Supplier::findOrFail($id);
        $supplier->user()->delete(); // Delete user linked to supplier
        $supplier->delete(); // Delete supplier data

        return redirect()->back()->with('success', 'Supplier deleted successfully!');
    }

    // Show all materials posted by suppliers
    public function viewMaterials(Request $request)
    {
        $search = $request->get('search');
        $category = $request->get('category');
        $supplier = $request->get('supplier');
        
        $materialsQuery = Material::with(['supplier.user', 'category', 'subCategory', 'images'])
            ->orderBy('created_at', 'desc');

        // Apply search filter
        if ($search) {
            $materialsQuery->where(function($query) use ($search) {
                $query->where('name', 'like', "%{$search}%")
                      ->orWhere('description', 'like', "%{$search}%")
                      ->orWhereHas('supplier.user', function($q) use ($search) {
                          $q->where('username', 'like', "%{$search}%");
                      })
                      ->orWhereHas('supplier', function($q) use ($search) {
                          $q->where('company_name', 'like', "%{$search}%");
                      });
            });
        }

        // Apply category filter
        if ($category) {
            $materialsQuery->where('category_id', $category);
        }

        // Apply supplier filter
        if ($supplier) {
            $materialsQuery->where('supplier_id', $supplier);
        }

        $materials = $materialsQuery->paginate(12);

        // Get filter options
        $categories = \App\Models\Category::all();
        $suppliers = Supplier::with('user')->get();

        return view('admin.materials.index', compact(
            'materials', 
            'search', 
            'category', 
            'supplier',
            'categories',
            'suppliers'
        ));
    }

    // Show material details
    public function viewMaterial($id)
    {
        $material = Material::with(['supplier.user', 'category', 'subCategory', 'images', 'variations'])
                           ->findOrFail($id);

        return view('admin.materials.show', compact('material'));
    }

    // Delete material
    public function deleteMaterial($id)
    {
        try {
            DB::transaction(function() use ($id) {
                $material = Material::findOrFail($id);

                // Delete associated images from storage
                foreach ($material->images as $image) {
                    $filePath = str_replace('storage/', '', $image->image_path);
                    Storage::disk('public')->delete($filePath);
                }

                // Delete the material (this will cascade delete images and variations due to foreign key constraints)
                $material->delete();
            });

            return redirect()->route('admin.materials')->with('success', 'Material deleted successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error deleting material: ' . $e->getMessage());
        }
    }

    /**
 * Display categories and subcategories management page
 */
public function manageCategories()
{
    $categories = \App\Models\Category::with('subCategories')->orderBy('category', 'asc')->get();
    return view('admin.categories.index', compact('categories'));
}

/**
 * Store a new category
 */
public function storeCategory(Request $request)
{
    $request->validate([
        'category' => 'required|string|max:255|unique:categories,category',
    ]);

    try {
        \App\Models\Category::create([
            'category' => $request->category
        ]);

        return redirect()->route('admin.categories')->with('success', 'Category created successfully!');
    } catch (\Exception $e) {
        return redirect()->back()->with('error', 'Error creating category: ' . $e->getMessage());
    }
}

 
    // Store a new subcategory
    public function storeSubCategory(Request $request)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'subcategory' => 'required|string|max:255',
        ]);

        try {
            // Check if subcategory already exists for this category
            $exists = \App\Models\SubCategory::where('category_id', $request->category_id)
                                        ->where('subcategory', $request->subcategory)
                                        ->exists();

            if ($exists) {
                return redirect()->back()->with('error', 'This subcategory already exists for the selected category.');
            }

            \App\Models\SubCategory::create([
                'category_id' => $request->category_id,
                'subcategory' => $request->subcategory
            ]);

            return redirect()->route('admin.categories')->with('success', 'Subcategory created successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error creating subcategory: ' . $e->getMessage());
        }
    }

    //Delete a category
    public function deleteCategory($id)
    {
        try {
            DB::transaction(function() use ($id) {
                $category = \App\Models\Category::findOrFail($id);
                
                // Check if category has materials
                $materialCount = $category->materials()->count();
                if ($materialCount > 0) {
                    throw new \Exception("Cannot delete category. It has {$materialCount} materials associated with it.");
                }

                // Delete all subcategories first
                $category->subCategories()->delete();
                
                // Delete the category
                $category->delete();
            });

            return redirect()->route('admin.categories')->with('success', 'Category and its subcategories deleted successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error deleting category: ' . $e->getMessage());
        }
    }

    //Delete a subcategory
    public function deleteSubCategory($id)
    {
        try {
            $subCategory = \App\Models\SubCategory::findOrFail($id);
            
            // Check if subcategory has materials
            $materialCount = $subCategory->materials()->count();
            if ($materialCount > 0) {
                return redirect()->back()->with('error', "Cannot delete subcategory. It has {$materialCount} materials associated with it.");
            }

            $subCategory->delete();

            return redirect()->route('admin.categories')->with('success', 'Subcategory deleted successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error deleting subcategory: ' . $e->getMessage());
        }
    }


    //Update a category
    public function updateCategory(Request $request, $id)
    {
        $request->validate([
            'category' => 'required|string|max:255|unique:categories,category,' . $id,
        ]);

        try {
            $category = \App\Models\Category::findOrFail($id);
            $category->update([
                'category' => $request->category
            ]);

            return redirect()->route('admin.categories')->with('success', 'Category updated successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error updating category: ' . $e->getMessage());
        }
    }

    
    //Update a subcategory
    public function updateSubCategory(Request $request, $id)
    {
        $request->validate([
            'subcategory' => 'required|string|max:255',
        ]);

        try {
            $subCategory = \App\Models\SubCategory::findOrFail($id);
            
            // Check if subcategory already exists for this category (excluding current one)
            $exists = \App\Models\SubCategory::where('category_id', $subCategory->category_id)
                                        ->where('subcategory', $request->subcategory)
                                        ->where('id', '!=', $id)
                                        ->exists();

            if ($exists) {
                return redirect()->back()->with('error', 'This subcategory name already exists for this category.');
            }

            $subCategory->update([
                'subcategory' => $request->subcategory
            ]);

            return redirect()->route('admin.categories')->with('success', 'Subcategory updated successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error updating subcategory: ' . $e->getMessage());
        }
    }

}
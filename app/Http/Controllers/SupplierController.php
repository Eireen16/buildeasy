<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use App\Models\User;
use App\Models\Supplier;
use App\Models\Category;

class SupplierController extends Controller
{

    public function __construct()
    {
        // Middleware to share categories for supplier views
        $this->middleware(function ($request, $next) {
            try {
                if (Auth::check() && Auth::user()->role === 'supplier') {
                    $categories = Category::with('subCategories')->get();
                    View::share('categories', $categories);
                } else {
                    View::share('categories', collect());
                }
            } catch (\Exception $e) {
                View::share('categories', collect());
            }

            return $next($request);
        });
    }

    /**
     * Display the supplier profile page.
     */
    public function showProfile()
    {
        $user = Auth::user();
        $supplier = $user->supplier;

        return view('supplier.profile', compact('user', 'supplier'));
    }

    /**
     * Display the supplier profile page but in edit mode
     */
    public function editProfile()
    {
        $user = Auth::user();
        $supplier = $user->supplier;

        return view('supplier.profile-edit', compact('user', 'supplier'))->with('editMode', true);
    }

    /**
     * Update the supplier profile page 
     */
    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        $supplier = $user->supplier;

        $request->validate([
            'username' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'company_name' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'location' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:255',
            'bank_details' => 'nullable|string|max:255',
            'profile_picture' => 'nullable|image|max:2048',
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        // Update user info
        $user->username = $request->username;
        $user->email = $request->email;

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        // Update supplier info
        $supplier->company_name = $request->company_name;
        $supplier->phone = $request->phone;
        $supplier->location = $request->location;
        $supplier->address = $request->address;
        $supplier->bank_details = $request->bank_details;

        // Handle profile picture upload
        if ($request->hasFile('profile_picture')) {
            $image = $request->file('profile_picture');
            $imagePath = $image->store('profile_pictures', 'public');
            $supplier->profile_picture = '/storage/' . $imagePath;
        }

       $supplier->save();

       return redirect()->route('supplier.profile')->with('success', 'Profile updated successfully.');
    }
}

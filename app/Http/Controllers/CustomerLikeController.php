<?php

namespace App\Http\Controllers;

use App\Models\Like;
use App\Models\Material;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CustomerLikeController extends Controller
{
    // Toggle like/unlike for a material
    public function toggle(Request $request, $materialId)
    {
        $user = Auth::user();
        $customer = Customer::where('user_id', $user->id)->first();
        
        if (!$customer) {
            return response()->json(['error' => 'Customer not found'], 404);
        }

        $material = Material::findOrFail($materialId);
        
        // Check if already liked
        $existingLike = Like::where('customer_id', $customer->id)
                           ->where('material_id', $materialId)
                           ->first();

        if ($existingLike) {
            // Unlike
            $existingLike->delete();
            return response()->json([
                'status' => 'unliked',
                'message' => 'Material removed from likes'
            ]);
        } else {
            // Like
            Like::create([
                'customer_id' => $customer->id,
                'material_id' => $materialId
            ]);
            return response()->json([
                'status' => 'liked',
                'message' => 'Material added to likes'
            ]);
        }
    }

    // Show customer's liked materials page
    public function index()
    {
        $user = Auth::user();
        $customer = Customer::where('user_id', $user->id)->first();
        
        if (!$customer) {
            return redirect()->route('customer.dashboard')->with('error', 'Customer profile not found');
        }

        // Get liked materials with pagination
        $likedMaterials = $customer->likedMaterials()
                                 ->with(['images', 'supplier', 'category', 'subCategory'])
                                 ->latest('likes.created_at')
                                 ->paginate(12);

        return view('customer.likes.index', compact('likedMaterials'));
    }

    // Remove from likes (for the likes page)
    public function remove($materialId)
    {
        $user = Auth::user();
        $customer = Customer::where('user_id', $user->id)->first();
        
        if (!$customer) {
            return redirect()->back()->with('error', 'Customer not found');
        }

        $like = Like::where('customer_id', $customer->id)
                   ->where('material_id', $materialId)
                   ->first();

        if ($like) {
            $like->delete();
            return redirect()->back()->with('success', 'Material removed from likes');
        }

        return redirect()->back()->with('error', 'Material not found in likes');
    }
}

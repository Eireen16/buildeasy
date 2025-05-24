<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Material;


class CustomerMaterialController extends Controller
{
    public function index()
    {
        $materials = Material::with('supplier')->latest()->get();
        return view('customer.dashboard', compact('materials'));
    }

    public function show($id)
    {
        $material = Material::with(['supplier.user', 'variations', 'images'])->findOrFail($id);
        return view('customer.materials.show', compact('material'));
    }


//     public function search(Request $request)
// {
//     $query = $request->input('query');
//     $location = $request->input('location');
//     $sustainability = $request->input('sustainability');

//     $materials = Material::with('supplier');

//     // Filter by search keyword
//     if ($query) {
//         $materials->where('name', 'like', "%{$query}%");
//     }

//     // Filter by location (from supplier table via relationship)
//     if ($location) {
//         $materials->whereHas('supplier', function ($q) use ($location) {
//             $q->where('location', 'like', "%{$location}%");
//         });
//     }

//     // Filter by sustainability rating
//     if ($sustainability) {
//         $materials->where('sustainability_rating', '>=', $sustainability);
//     }

//     $results = $materials->get();

//     return view('customer.materials.search', [
//         'materials' => $results,
//         'query' => $query,
//         'location' => $location,
//         'sustainability' => $sustainability
//     ]);
// }

}



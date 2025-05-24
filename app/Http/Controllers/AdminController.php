<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Supplier;

class AdminController extends Controller
{
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

    // (Optional) Reject/Delete a supplier
    public function deleteSupplier($id)
    {
        $supplier = Supplier::findOrFail($id);
        $supplier->user()->delete(); // Delete user linked to supplier
        $supplier->delete(); // Delete supplier data

        return redirect()->back()->with('success', 'Supplier deleted successfully!');
    }
}

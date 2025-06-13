<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class SupplierOrderHistoryController extends Controller
{
    public function index()
    {
        try {
            $supplier = Supplier::where('user_id', Auth::id())->first();
            
            if (!$supplier) {
                return redirect()->back()->with('error', 'Supplier profile not found.');
            }

            // Get all completed and cancelled orders for this supplier
            $orders = Order::where('supplier_id', $supplier->id)
                ->whereIn('order_status', ['completed', 'cancelled'])
                ->with([
                    'customer.user',
                    'orderItems.material.images',
                    'orderItems.materialVariation'
                ])
                ->orderBy('created_at', 'desc')
                ->get();

            return view('supplier.orders.history', compact('orders'));

        } catch (\Exception $e) {
            Log::error('Error loading supplier order history: ' . $e->getMessage());
            return redirect()->back()->with('error', 'An error occurred while loading order history.');
        }
    }
}
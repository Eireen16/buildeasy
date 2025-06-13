<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class SupplierOrderController extends Controller
{
    public function index()
    {
        try {
            $supplier = Supplier::where('user_id', Auth::id())->first();
            
            if (!$supplier) {
                return redirect()->back()->with('error', 'Supplier profile not found.');
            }

            // Get all orders for this supplier excluding completed and cancelled orders (they go to order history)
            $orders = Order::where('supplier_id', $supplier->id)
                ->whereNotIn('order_status', ['completed', 'cancelled'])
                ->with([
                    'customer.user',
                    'orderItems.material.images',
                    'orderItems.materialVariation'
                ])
                ->orderBy('created_at', 'desc')
                ->get();

            return view('supplier.orders.index', compact('orders'));

        } catch (\Exception $e) {
            Log::error('Error loading supplier orders: ' . $e->getMessage());
            return redirect()->back()->with('error', 'An error occurred while loading orders.');
        }
    }

    public function updateStatus(Request $request, Order $order)
    {
        try {
            $supplier = Supplier::where('user_id', Auth::id())->first();
            
            if (!$supplier || $order->supplier_id !== $supplier->id) {
                return redirect()->back()->with('error', 'Unauthorized access.');
            }

            $request->validate([
                'status' => 'required|in:shipped,completed,ready_to_pickup'
            ]);

            // Validate status transition based on delivery method
            $newStatus = $request->status;
            $currentStatus = $order->order_status;

            if ($order->delivery_method === 'delivery') {
                if (!in_array($newStatus, ['shipped', 'completed'])) {
                    return redirect()->back()->with('error', 'Invalid status for delivery orders.');
                }
                
                // Check valid transitions for delivery
                if ($currentStatus === 'to_ship' && $newStatus === 'completed') {
                    return redirect()->back()->with('error', 'Order must be shipped before completion.');
                }
            } else { // self-pickup
                if (!in_array($newStatus, ['ready_to_pickup', 'completed'])) {
                    return redirect()->back()->with('error', 'Invalid status for pickup orders.');
                }
                
                // Check valid transitions for pickup
                if ($currentStatus === 'preparing_to_pickup' && $newStatus === 'completed') {
                    return redirect()->back()->with('error', 'Order must be ready for pickup before completion.');
                }
            }

            $order->update(['order_status' => $newStatus]);

            Log::info('Order status updated: ' . $order->order_id . ' to ' . $newStatus);

            return redirect()->back()->with('success', 'Order status updated successfully.');

        } catch (\Exception $e) {
            Log::error('Error updating order status: ' . $e->getMessage());
            return redirect()->back()->with('error', 'An error occurred while updating order status.');
        }
    }

    public function cancelOrder(Order $order)
    {
        try {
            $supplier = Supplier::where('user_id', Auth::id())->first();
            
            if (!$supplier || $order->supplier_id !== $supplier->id) {
                return redirect()->back()->with('error', 'Unauthorized access.');
            }

            // Check if order can be cancelled
            if (in_array($order->order_status, ['completed', 'cancelled'])) {
                return redirect()->back()->with('error', 'This order cannot be cancelled.');
            }

            $order->update(['order_status' => 'cancelled']);

            // Restore stock for cancelled order
            foreach ($order->orderItems as $item) {
                if ($item->material_variation_id) {
                    $item->materialVariation->increment('stock', $item->quantity);
                } else {
                    $item->material->increment('stock', $item->quantity);
                }
            }

            Log::info('Order cancelled by supplier: ' . $order->order_id);

            return redirect()->back()->with('success', 'Order cancelled successfully.');

        } catch (\Exception $e) {
            Log::error('Error cancelling order: ' . $e->getMessage());
            return redirect()->back()->with('error', 'An error occurred while cancelling the order.');
        }
    }
}
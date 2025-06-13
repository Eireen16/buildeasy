<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class CustomerOrderController extends Controller
{
    public function index()
    {
        try {
            $customer = Customer::where('user_id', Auth::id())->first();
            
            if (!$customer) {
                return redirect()->back()->with('error', 'Customer profile not found.');
            }

            // Get all orders for this customer excluding completed and cancelled orders (they go to order history)
            $orders = Order::where('customer_id', $customer->id)
                ->whereNotIn('order_status', ['completed', 'cancelled'])
                ->with([
                    'supplier.user',
                    'orderItems.material.images',
                    'orderItems.materialVariation'
                ])
                ->orderBy('created_at', 'desc')
                ->get();

            return view('customer.orders.index', compact('orders'));

        } catch (\Exception $e) {
            Log::error('Error loading customer orders: ' . $e->getMessage());
            return redirect()->back()->with('error', 'An error occurred while loading orders.');
        }
    }

    public function cancelOrder(Order $order)
    {
        try {
            $customer = Customer::where('user_id', Auth::id())->first();
            
            if (!$customer || $order->customer_id !== $customer->id) {
                return redirect()->back()->with('error', 'Unauthorized access.');
            }

            // Check if order can be cancelled
            if (in_array($order->order_status, ['completed', 'cancelled', 'shipped', 'ready_to_pickup'])) {
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

            Log::info('Order cancelled by customer: ' . $order->order_id);

            return redirect()->back()->with('success', 'Order cancelled successfully.');

        } catch (\Exception $e) {
            Log::error('Error cancelling order: ' . $e->getMessage());
            return redirect()->back()->with('error', 'An error occurred while cancelling the order.');
        }
    }

     public function trackOrder($order_id)
{
    try {
        // Log the start of the method
        Log::info('TrackOrder method called with order_id: ' . $order_id);
        Log::info('Current user ID: ' . Auth::id());

        // Get the current authenticated customer
        $customer = Customer::where('user_id', Auth::id())->first();
        
        if (!$customer) {
            Log::error('Customer not found for user ID: ' . Auth::id());
            return redirect()->back()->with('error', 'Customer not found.');
        }

        Log::info('Customer found with ID: ' . $customer->id);

        // Find the order by order_id and ensure it belongs to the current customer
        $order = Order::with('supplier')
            ->where('order_id', $order_id)
            ->where('customer_id', $customer->id)
            ->first();

        if (!$order) {
            Log::error('Order not found with order_id: ' . $order_id . ' for customer_id: ' . $customer->id);
            
            // Check if order exists but doesn't belong to customer
            $orderExists = Order::where('order_id', $order_id)->first();
            if ($orderExists) {
                Log::error('Order exists but belongs to different customer. Order customer_id: ' . $orderExists->customer_id);
                return redirect()->back()->with('error', 'You do not have permission to view this order.');
            } else {
                Log::error('Order does not exist in database');
                return redirect()->back()->with('error', 'Order not found.');
            }
        }

        Log::info('Order found successfully for order_id: ' . $order_id);
        Log::info('Order details - ID: ' . $order->id . ', Status: ' . $order->order_status . ', Supplier ID: ' . $order->supplier_id);

        // Check if supplier relationship exists
        if (!$order->supplier) {
            Log::error('Supplier not found for order. Supplier ID: ' . $order->supplier_id);
            return redirect()->back()->with('error', 'Supplier information not found for this order.');
        }

        Log::info('Supplier found: ' . $order->supplier->company_name);

        return view('customer.orders.track', compact('order'));

    } catch (\Exception $e) {
        Log::error('Error in trackOrder method: ' . $e->getMessage());
        Log::error('Stack trace: ' . $e->getTraceAsString());
        return redirect()->back()->with('error', 'An error occurred while retrieving order information: ' . $e->getMessage());
    }
}

public function showPickupAddress($orderId)
{
    try {
        // Get the authenticated customer
        $customer = Customer::where('user_id', Auth::id())->first();
        
        if (!$customer) {
            Log::error('Customer not found for user ID: ' . Auth::id());
            return redirect()->route('customer.orders.index')->with('error', 'Customer profile not found.');
        }

        // Find the order with supplier information
        $order = Order::with('supplier')
            ->where('id', $orderId)
            ->where('customer_id', $customer->id)
            ->where('delivery_method', 'self-pickup')
            ->first();

        if (!$order) {
            Log::error('Order not found or not accessible for customer ID: ' . $customer->id . ', Order ID: ' . $orderId);
            return redirect()->route('customer.orders.index')->with('error', 'Order not found or not accessible.');
        }

        // Check if order is ready for pickup
        if (!in_array($order->order_status, ['ready_to_pickup', 'preparing_to_pickup'])) {
            return redirect()->route('customer.orders.index')->with('error', 'This order is not available for pickup yet.');
        }

        Log::info('Pickup address page loaded for order: ' . $order->order_id);

        return view('customer.orders.pickup', compact('order'));

    } catch (\Exception $e) {
        Log::error('Error in showPickupAddress method: ' . $e->getMessage());
        return redirect()->route('customer.orders.index')->with('error', 'An error occurred while loading pickup address.');
    }
}

}
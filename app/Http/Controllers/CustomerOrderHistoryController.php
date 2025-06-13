<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Customer;
use App\Models\Cart;
use App\Models\CartItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class CustomerOrderHistoryController extends Controller
{
    public function index()
    {
        try {
            $customer = Customer::where('user_id', Auth::id())->first();
            
            if (!$customer) {
                return redirect()->back()->with('error', 'Customer profile not found.');
            }

            // Get all completed and cancelled orders for this customer
            $orders = Order::where('customer_id', $customer->id)
                ->whereIn('order_status', ['completed', 'cancelled'])
                ->with([
                    'supplier.user',
                    'orderItems.material.images',
                    'orderItems.materialVariation',
                    'orderItems.review'
                ])
                ->orderBy('created_at', 'desc')
                ->get();

            return view('customer.orders.history', compact('orders'));

        } catch (\Exception $e) {
            Log::error('Error loading customer order history: ' . $e->getMessage());
            return redirect()->back()->with('error', 'An error occurred while loading order history.');
        }
    }

}

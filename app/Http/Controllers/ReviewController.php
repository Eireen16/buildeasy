<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Customer;
use App\Models\Material;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ReviewController extends Controller
{
    // Show review form for a specific order item
    public function create($orderItemId)
    {
        $customer = Customer::where('user_id', Auth::id())->first();
        
        if (!$customer) {
            return redirect()->back()->with('error', 'Customer profile not found.');
        }

        // Get order item with order and material
        $orderItem = OrderItem::with(['order', 'material'])
                             ->whereHas('order', function($query) use ($customer) {
                                 $query->where('customer_id', $customer->id)
                                       ->where('order_status', 'completed');
                             })
                             ->findOrFail($orderItemId);

        $order = $orderItem->order;
        $material = $orderItem->material;

        // Check if review already exists
        $existingReview = Review::where('customer_id', $customer->id)
                              ->where('order_item_id', $orderItemId)
                              ->first();

        if ($existingReview) {
            return redirect()->back()->with('error', 'You have already reviewed this item.');
        }

        return view('customer.reviews.create', compact('order', 'material', 'orderItem'));
    }

    // Store the review
    public function store(Request $request, $orderItemId)
    {
        $customer = Customer::where('user_id', Auth::id())->first();
        
        if (!$customer) {
            return redirect()->back()->with('error', 'Customer profile not found.');
        }

        // Debug: Log customer data
        Log::info('Customer data:', [
            'customer_id' => $customer->id,
            'customer_name' => $customer->name,
            'customer_data' => $customer->toArray()
        ]);

        $request->validate([
            'rating' => 'required|integer|between:1,5',
            'comment' => 'required|string|max:1000',
        ]);

        // Get order item with order
        $orderItem = OrderItem::with('order')
                             ->whereHas('order', function($query) use ($customer) {
                                 $query->where('customer_id', $customer->id)
                                       ->where('order_status', 'completed');
                             })
                             ->findOrFail($orderItemId);

        // Check if review already exists
        $existingReview = Review::where('customer_id', $customer->id)
                              ->where('order_item_id', $orderItemId)
                              ->first();

        if ($existingReview) {
            return redirect()->back()->with('error', 'You have already reviewed this item.');
        }

        // Prepare review data with fallback for customer name
        $customerName = $customer->name ?? 'Anonymous Customer';
        
        // Debug: Log what we're about to insert
        $reviewData = [
            'customer_id' => $customer->id,
            'material_id' => $orderItem->material_id,
            'order_item_id' => $orderItemId,
            'customer_name' => $customerName,
            'rating' => $request->rating,
            'comment' => $request->comment,
        ];
        
        Log::info('Review data to insert:', $reviewData);

        try {
            Review::create($reviewData);
            
            return redirect()->route('customer.materials.show', $orderItem->material_id)
                            ->with('success', 'Thank you for your review!');
        } catch (\Exception $e) {
            Log::error('Error creating review:', [
                'error' => $e->getMessage(),
                'review_data' => $reviewData
            ]);
            
            return redirect()->back()
                           ->with('error', 'There was an error submitting your review. Please try again.')
                           ->withInput();
        }
    }

    // Check if an order item can be reviewed by customer
    public function canReview($orderItemId)
    {
        $customer = Customer::where('user_id', Auth::id())->first();
        
        if (!$customer) {
            return response()->json(['can_review' => false]);
        }

        // Check if order item exists and belongs to customer with completed order
        $orderItem = OrderItem::whereHas('order', function($query) use ($customer) {
                                 $query->where('customer_id', $customer->id)
                                       ->where('order_status', 'completed');
                             })
                             ->find($orderItemId);

        if (!$orderItem) {
            return response()->json(['can_review' => false]);
        }

        // Check if review already exists
        $existingReview = Review::where('customer_id', $customer->id)
                              ->where('order_item_id', $orderItemId)
                              ->exists();

        return response()->json(['can_review' => !$existingReview]);
    }
}
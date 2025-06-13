<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Stripe\Stripe;
use Stripe\Checkout\Session;

class OrderController extends Controller
{
    public function __construct()
    {
        Stripe::setApiKey(config('services.stripe.secret'));
    }

    // Show checkout form
    public function checkout()
    {
        try {
            $customer = Customer::where('user_id', Auth::id())->first();
            
            if (!$customer) {
                Log::error('Customer not found for user ID: ' . Auth::id());
                return redirect()->route('customer.cart.index')->with('error', 'Customer profile not found.');
            }

            if (!$customer->cart) {
                Log::error('Cart not found for customer ID: ' . $customer->id);
                return redirect()->route('customer.cart.index')->with('error', 'Your cart is empty.');
            }

            if ($customer->cart->cartItems->isEmpty()) {
                Log::error('Cart is empty for customer ID: ' . $customer->id);
                return redirect()->route('customer.cart.index')->with('error', 'Your cart is empty.');
            }

            $cart = $customer->cart;
            $cartItems = $cart->cartItems()->with(['material.images', 'material.supplier', 'materialVariation'])->get();
            
            // Group items by supplier
            $supplierGroups = $cartItems->groupBy('material.supplier_id');
            $subtotal = $cart->total;

            Log::info('Checkout form loaded successfully for customer ID: ' . $customer->id);
            Log::info('Items grouped by ' . $supplierGroups->count() . ' suppliers');
            
            return view('customer.checkout.form', compact('cartItems', 'subtotal', 'supplierGroups'));

        } catch (\Exception $e) {
            Log::error('Error in checkout method: ' . $e->getMessage());
            return redirect()->route('customer.cart.index')->with('error', 'An error occurred while loading checkout.');
        }
    }

    // Process checkout and create Stripe session
    public function processCheckout(Request $request)
    {
        Log::info('ProcessCheckout called with data: ', $request->all());

        try {
            // Updated validation - name and phone required for both delivery and self-pickup
            $validatedData = $request->validate([
                'delivery_method' => 'required|in:delivery,self-pickup',
                'delivery_name' => 'required|string|max:255',
                'delivery_phone' => 'required|string|max:20',
                'delivery_address' => 'required_if:delivery_method,delivery|nullable|string',
                'delivery_state' => 'required_if:delivery_method,delivery|nullable|string|max:100',
                'delivery_city' => 'required_if:delivery_method,delivery|nullable|string|max:100',
                'delivery_postal_code' => 'required_if:delivery_method,delivery|nullable|string|max:10',
            ]);

            Log::info('Validation passed');

            $customer = Customer::where('user_id', Auth::id())->first();
            
            if (!$customer) {
                Log::error('Customer not found during checkout process');
                return redirect()->back()->with('error', 'Customer not found.');
            }

            $cart = $customer->cart;
            
            if (!$cart || $cart->cartItems->isEmpty()) {
                Log::error('Cart is empty during checkout process');
                return redirect()->route('customer.cart.index')->with('error', 'Your cart is empty.');
            }

            $cartItems = $cart->cartItems()->with(['material', 'material.supplier', 'materialVariation'])->get();
            $subtotal = $cart->total;
            
            // Group cart items by supplier
            $supplierGroups = $cartItems->groupBy('material.supplier_id');
            
            // Calculate shipping cost - if delivery method and multiple suppliers, charge shipping for each supplier
            $baseShippingCost = $request->delivery_method === 'delivery' ? 50.00 : 0.00;
            $totalShippingCost = $baseShippingCost * $supplierGroups->count();
            $total = $subtotal + $totalShippingCost;

            Log::info('Order totals calculated - Subtotal: ' . $subtotal . ', Shipping: ' . $totalShippingCost . ', Total: ' . $total);
            Log::info('Number of suppliers: ' . $supplierGroups->count());

            // Verify Stripe configuration
            if (!config('services.stripe.secret')) {
                Log::error('Stripe secret key not configured');
                return redirect()->back()->with('error', 'Payment system not configured properly.');
            }

            // Create Stripe Checkout Session
            $lineItems = [];
            
            foreach ($cartItems as $item) {
                $itemName = $item->material->name;
                if ($item->materialVariation) {
                    $itemName .= ' - ' . $item->materialVariation->variation_name . ': ' . $item->materialVariation->variation_value;
                }
                // Add supplier name to distinguish items
                $itemName .= ' (from ' . $item->material->supplier->name . ')';
                
                $lineItems[] = [
                    'price_data' => [
                        'currency' => 'myr',
                        'product_data' => [
                            'name' => $itemName,
                        ],
                        'unit_amount' => (int)($item->price * 100), // Convert to cents and ensure integer
                    ],
                    'quantity' => $item->quantity,
                ];
            }

            // Add shipping if delivery - show breakdown per supplier
            if ($totalShippingCost > 0) {
                if ($supplierGroups->count() > 1) {
                    $lineItems[] = [
                        'price_data' => [
                            'currency' => 'myr',
                            'product_data' => [
                                'name' => 'Delivery Fee (' . $supplierGroups->count() . ' suppliers × RM' . $baseShippingCost . ')',
                            ],
                            'unit_amount' => (int)($totalShippingCost * 100),
                        ],
                        'quantity' => 1,
                    ];
                } else {
                    $lineItems[] = [
                        'price_data' => [
                            'currency' => 'myr',
                            'product_data' => [
                                'name' => 'Delivery Fee',
                            ],
                            'unit_amount' => (int)($totalShippingCost * 100),
                        ],
                        'quantity' => 1,
                    ];
                }
            }

            Log::info('Line items prepared: ', $lineItems);

            $sessionData = [
                'payment_method_types' => ['card'],
                'line_items' => $lineItems,
                'mode' => 'payment',
                'success_url' => route('checkout.success') . '?session_id={CHECKOUT_SESSION_ID}',
                'cancel_url' => route('checkout.cancel'),
                'metadata' => [
                    'customer_id' => (string)$customer->id,
                    'delivery_method' => $request->delivery_method,
                    'delivery_name' => $request->delivery_name ?? '',
                    'delivery_phone' => $request->delivery_phone ?? '',
                    'delivery_address' => $request->delivery_address ?? '',
                    'delivery_state' => $request->delivery_state ?? '',
                    'delivery_city' => $request->delivery_city ?? '',
                    'delivery_postal_code' => $request->delivery_postal_code ?? '',
                    'supplier_count' => (string)$supplierGroups->count(),
                ],
            ];

            Log::info('Creating Stripe session with data: ', $sessionData);

            $session = Session::create($sessionData);

            Log::info('Stripe session created successfully: ' . $session->id);
            Log::info('Redirecting to: ' . $session->url);

            return redirect($session->url);

        } catch (\Stripe\Exception\ApiErrorException $e) {
            Log::error('Stripe API Error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Payment system error: ' . $e->getMessage());
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validation Error: ', $e->errors());
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            Log::error('General Error in processCheckout: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            return redirect()->back()->with('error', 'Something went wrong: ' . $e->getMessage());
        }
    }

    // Handle successful payment
    public function success(Request $request)
    {
        Log::info('Success callback called with session_id: ' . $request->get('session_id'));

        $sessionId = $request->get('session_id');
        
        if (!$sessionId) {
            Log::error('No session ID provided in success callback');
            return redirect()->route('customer.cart.index')->with('error', 'Invalid session.');
        }

        try {
            $session = Session::retrieve($sessionId);
            Log::info('Retrieved session: ' . $session->id . ', Status: ' . $session->payment_status);
            
            if ($session->payment_status === 'paid') {
                $this->createOrders($session);
                return view('customer.checkout.success');
            } else {
                Log::error('Payment not completed. Status: ' . $session->payment_status);
            }

        } catch (\Exception $e) {
            Log::error('Error in success callback: ' . $e->getMessage());
            return redirect()->route('customer.cart.index')->with('error', 'Payment verification failed.');
        }

        return redirect()->route('customer.cart.index')->with('error', 'Payment was not completed.');
    }

    // Handle cancelled payment
    public function cancel()
    {
        Log::info('Payment cancelled by user');
        return redirect()->route('customer.cart.index')->with('error', 'Payment was cancelled.');
    }

    // Create separate orders for each supplier after successful payment
    private function createOrders($session)
    {
        Log::info('Creating orders for session: ' . $session->id);

        try {
            $metadata = $session->metadata;
            $customer = Customer::find($metadata->customer_id);
            
            if (!$customer) {
                Log::error('Customer not found with ID: ' . $metadata->customer_id);
                return;
            }

            $cart = $customer->cart;

            if (!$cart || $cart->cartItems->isEmpty()) {
                Log::error('Cart is empty when creating order');
                return;
            }

            DB::transaction(function () use ($session, $metadata, $customer, $cart) {
                $cartItems = $cart->cartItems()->with(['material', 'material.supplier', 'materialVariation'])->get();
                
                // Group cart items by supplier
                $supplierGroups = $cartItems->groupBy('material.supplier_id');
                
                $baseShippingCost = $metadata->delivery_method === 'delivery' ? 50.00 : 0.00;
                $supplierCount = $supplierGroups->count();

                foreach ($supplierGroups as $supplierId => $items) {
                    $supplierSubtotal = $items->sum('subtotal');
                    $supplierShippingCost = $baseShippingCost; // Each supplier gets base shipping cost
                    $supplierTotal = $supplierSubtotal + $supplierShippingCost;

                    // Create separate order for each supplier
                    $order = Order::create([
                        'order_id' => Order::generateOrderId(),
                        'customer_id' => $customer->id,
                        'supplier_id' => $supplierId,
                        'delivery_method' => $metadata->delivery_method,
                        'subtotal' => $supplierSubtotal,
                        'shipping_cost' => $supplierShippingCost,
                        'total' => $supplierTotal,
                        'order_status' => $metadata->delivery_method === 'delivery' ? 'to_ship' : 'preparing_to_pickup',
                        'delivery_name' => $metadata->delivery_name,
                        'delivery_phone' => $metadata->delivery_phone,
                        'delivery_address' => $metadata->delivery_address,
                        'delivery_state' => $metadata->delivery_state,
                        'delivery_city' => $metadata->delivery_city,
                        'delivery_postal_code' => $metadata->delivery_postal_code,
                        'stripe_payment_intent_id' => $session->payment_intent,
                    ]);

                    Log::info('Order created with ID: ' . $order->order_id . ' for supplier: ' . $supplierId);

                    // Create order items for this supplier
                    foreach ($items as $cartItem) {
                        OrderItem::create([
                            'order_id' => $order->id,
                            'material_id' => $cartItem->material_id,
                            'material_variation_id' => $cartItem->material_variation_id,
                            'material_name' => $cartItem->material->name,
                            'variation_name' => $cartItem->materialVariation->variation_name ?? null,
                            'variation_value' => $cartItem->materialVariation->variation_value ?? null,
                            'quantity' => $cartItem->quantity,
                            'unit_price' => $cartItem->price,
                            'subtotal' => $cartItem->subtotal,
                        ]);

                        // Update stock
                        if ($cartItem->materialVariation) {
                            $cartItem->materialVariation->decrement('stock', $cartItem->quantity);
                            $cartItem->material->decrement('stock', $cartItem->quantity);
                        } else {
                            $cartItem->material->decrement('stock', $cartItem->quantity);
                        }
                    }
                }

                // Clear cart after creating all orders
                $cart->cartItems()->delete();
                
                Log::info('All orders processing completed successfully. Created ' . $supplierCount . ' orders.');
            });

        } catch (\Exception $e) {
            Log::error('Error creating orders: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
        }
    }

}
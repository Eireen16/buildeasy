<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Customer;
use App\Models\Material;
use App\Models\MaterialVariation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    // Show cart page
    public function index()
    {
        $customer = Customer::where('user_id', Auth::id())->first();
        
        if (!$customer) {
            return redirect()->route('customer.dashboard')->with('error', 'Customer profile not found.');
        }

        $cart = $customer->cart;
        $cartItems = $cart ? $cart->cartItems()->with(['material.images', 'materialVariation'])->get() : collect();
        $total = $cart ? $cart->total : 0;

        return view('customer.cart.index', compact('cartItems', 'total'));
    }

    // Add item to cart
    public function addToCart(Request $request)
    {
        $request->validate([
            'material_id' => 'required|exists:materials,id',
            'quantity' => 'required|integer|min:1',
            'variation_id' => 'nullable|exists:material_variations,id'
        ]);

        $customer = Customer::where('user_id', Auth::id())->first();
        
        if (!$customer) {
            return response()->json(['error' => 'Customer profile not found.'], 404);
        }

        $material = Material::findOrFail($request->material_id);
        $cart = $customer->getOrCreateCart();
        
        // Check if variation is selected and belongs to the material
        $variation = null;
        if ($request->variation_id) {
            $variation = MaterialVariation::where('id', $request->variation_id)
                                        ->where('material_id', $request->material_id)
                                        ->first();
            
            if (!$variation) {
                return response()->json(['error' => 'Invalid variation selected.'], 400);
            }

            // Check variation stock
            if ($variation->stock < $request->quantity) {
                return response()->json(['error' => 'Insufficient stock for selected variation.'], 400);
            }
        } else {
            // Check material stock if no variation
            if ($material->stock < $request->quantity) {
                return response()->json(['error' => 'Insufficient stock.'], 400);
            }
        }

        // Check if item already exists in cart
        $existingItem = $cart->cartItems()
                            ->where('material_id', $request->material_id)
                            ->where('material_variation_id', $request->variation_id)
                            ->first();

        if ($existingItem) {
            // Update quantity
            $newQuantity = $existingItem->quantity + $request->quantity;
            
            // Check stock again for new quantity
            $maxStock = $variation ? $variation->stock : $material->stock;
            if ($newQuantity > $maxStock) {
                return response()->json(['error' => 'Not enough stock available.'], 400);
            }
            
            $existingItem->update(['quantity' => $newQuantity]);
        } else {
            // Create new cart item
            CartItem::create([
                'cart_id' => $cart->id,
                'material_id' => $request->material_id,
                'material_variation_id' => $request->variation_id,
                'quantity' => $request->quantity,
                'price' => $material->price
            ]);
        }

        return response()->json([
            'success' => 'Item added to cart successfully!',
            'cart_count' => $cart->fresh()->total_items
        ]);
    }

    // Update cart item quantity
    public function updateQuantity(Request $request, $id)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1'
        ]);

        $customer = Customer::where('user_id', Auth::id())->first();
        $cart = $customer->cart;
        
        if (!$cart) {
            return response()->json(['error' => 'Cart not found.'], 404);
        }

        $cartItem = $cart->cartItems()->findOrFail($id);
        
        // Check stock
        $maxStock = $cartItem->materialVariation ? 
                   $cartItem->materialVariation->stock : 
                   $cartItem->material->stock;
        
        if ($request->quantity > $maxStock) {
            return response()->json(['error' => 'Not enough stock available.'], 400);
        }

        $cartItem->update(['quantity' => $request->quantity]);
        
        return response()->json([
            'success' => 'Quantity updated successfully!',
            'subtotal' => $cartItem->subtotal,
            'total' => $cart->fresh()->total,
            'cart_count' => $cart->fresh()->total_items
        ]);
    }

    // Remove item from cart
    public function removeItem($id)
    {
        $customer = Customer::where('user_id', Auth::id())->first();
        $cart = $customer->cart;
        
        if (!$cart) {
            return response()->json(['error' => 'Cart not found.'], 404);
        }

        $cartItem = $cart->cartItems()->findOrFail($id);
        $cartItem->delete();

        return response()->json([
            'success' => 'Item removed from cart!',
            'total' => $cart->fresh()->total,
            'cart_count' => $cart->fresh()->total_items

        ]);
    }

    public function debugCart()
{
    $customer = Customer::where('user_id', Auth::id())->first();
    $cart = $customer->cart;
    $cartItems = $cart->cartItems()->with(['material', 'materialVariation'])->get();
    
    $debug = [
        'customer_id' => $customer->id,
        'cart_id' => $cart->id,
        'total_items' => $cart->total_items,
        'cart_total' => $cart->total,
        'items' => []
    ];
    
    foreach ($cartItems as $item) {
        $debug['items'][] = [
            'id' => $item->id,
            'material_name' => $item->material->name,
            'quantity' => $item->quantity,
            'price' => $item->price,
            'subtotal' => $item->subtotal,
            'variation' => $item->materialVariation ? 
                        $item->materialVariation->variation_name . ': ' . $item->materialVariation->variation_value : 
                         'No variation'
        ];
    }
    
    dd($debug);
}
}

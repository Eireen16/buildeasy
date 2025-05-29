@extends('layouts.customer')

@section('content')
<div class = "mt-3" style="background-color: #a8d8e8; padding: 20px 0;">
<div class="container mt-4">
    <h1 class="mb-4" style="color: #2980b9; font-size: 2.5rem; font-weight: bold; font-family: Arial, sans-serif;">My Cart</h1>
    <div class="row">
        <div class="col-12">
            
            @if($cartItems->count() > 0)
                <div class="cart-items">
                    @foreach($cartItems as $item)
                    <div class="card mb-3" id="cart-item-{{ $item->id }}">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <!-- Product Image -->
                                <div class="col-md-2">
                                    <img src="{{ asset($item->material->first_image) }}" 
                                         alt="{{ $item->material->name }}" 
                                         class="img-fluid rounded" 
                                         style="max-height: 100px; object-fit: cover;">
                                </div>
                                
                                <!-- Product Details -->
                                <div class="col-md-4">
                                    <h5 class="card-title">{{ $item->material->name }}</h5>
                                    @if($item->materialVariation)
                                        <p class="text-muted mb-1">
                                            <small>{{ $item->materialVariation->variation_name }}: {{ $item->materialVariation->variation_value }}</small>
                                        </p>
                                    @endif
                                    <p class="text-muted mb-0">
                                        <small>Price: RM {{ number_format($item->price, 2) }}</small>
                                    </p>
                                </div>
                                
                                <!-- Quantity Controls -->
                                <div class="col-md-3">
                                    <div class="d-flex align-items-center">
                                        <button class="btn btn-outline-secondary btn-sm" 
                                                onclick="updateQuantity({{ $item->id }}, {{ $item->quantity - 1 }})"
                                                {{ $item->quantity <= 1 ? 'disabled' : '' }}>
                                            -
                                        </button>
                                        <span class="mx-3 fw-bold" id="quantity-{{ $item->id }}">{{ $item->quantity }}</span>
                                        <button class="btn btn-outline-secondary btn-sm" 
                                                onclick="updateQuantity({{ $item->id }}, {{ $item->quantity + 1 }})">
                                            +
                                        </button>
                                    </div>
                                </div>
                                
                                <!-- Subtotal -->
                                <div class="col-md-2">
                                    <p class="fw-bold mb-0" id="subtotal-{{ $item->id }}">
                                        RM {{ number_format($item->subtotal, 2) }}
                                    </p>
                                </div>
                                
                                <!-- Remove Button -->
                                <div class="col-md-1">
                                    <button class="btn btn-outline-danger btn-sm" 
                                            onclick="removeItem({{ $item->id }})">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                
                <!-- Cart Summary -->
                <div class="card mt-4">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-8">
                                <button class="btn btn-outline-primary" 
                                        onclick="window.location.href='{{ route('customer.dashboard') }}'">
                                    <i class="fas fa-arrow-left"></i> Continue Shopping
                                </button>
                            </div>
                            <div class="col-md-4 text-end">
                                <h4>Total: <span id="cart-total">RM {{ number_format($total, 2) }}</span></h4>
                                <button class="btn btn-success btn-lg mt-2" onclick="checkout()">
                                    <i class="fas fa-credit-card"></i> Checkout
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <!-- Empty Cart -->
                <div class="text-center py-5">
                    <i class="fas fa-shopping-cart fa-5x text-muted mb-3"></i>
                    <h3>Your cart is empty</h3>
                    <p class="text-muted">Add some materials to get started!</p>
                    <button class="btn btn-primary" 
                            onclick="window.location.href='{{ route('customer.dashboard') }}'">
                        Start Shopping
                    </button>
                </div>
            @endif
        </div>
    </div>
</div>
</div>

<script>
// Update quantity function
async function updateQuantity(itemId, newQuantity) {
    if (newQuantity < 1) return;
    
    try {
        const response = await fetch(`/customer/cart/update/${itemId}`, {
            method: 'PATCH',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                quantity: newQuantity
            })
        });
        
        const data = await response.json();
        
        if (data.success) {
            // Update quantity display
            document.getElementById(`quantity-${itemId}`).textContent = newQuantity;
            // Update subtotal
            document.getElementById(`subtotal-${itemId}`).textContent = `RM ${data.subtotal.toFixed(2)}`;
            // Update total
            document.getElementById('cart-total').textContent = `RM ${data.total.toFixed(2)}`;
        } else {
            alert(data.error || 'Error updating quantity');
        }
    } catch (error) {
        console.error('Error:', error);
        alert('Error updating quantity');
    }
}

// Remove item function
async function removeItem(itemId) {
    if (!confirm('Are you sure you want to remove this item?')) {
        return;
    }
    
    try {
        const response = await fetch(`/customer/cart/remove/${itemId}`, {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        });
        
        const data = await response.json();
        
        if (data.success) {
            // Remove item from DOM
            document.getElementById(`cart-item-${itemId}`).remove();
            // Update total
            document.getElementById('cart-total').textContent = `RM ${data.total.toFixed(2)}`;
            
            // Reload page if cart is empty
            if (data.total === 0) {
                location.reload();
            }
        } else {
            alert(data.error || 'Error removing item');
        }
    } catch (error) {
        console.error('Error:', error);
        alert('Error removing item');
    }
}

// Checkout function (placeholder)
function checkout() {
    alert('Checkout functionality will be implemented later');
}
</script>
@endsection
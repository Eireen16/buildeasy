@extends('layouts.customer')

@section('content')
<div class="container-fluid mt-3" style="background-color: #a8d8e8; padding: 20px 0;">
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4>Checkout</h4>
                </div>
                <div class="card-body">
                    @if(isset($supplierGroups) && $supplierGroups->count() > 1)
                        <small class="text-muted">Your order contains items from {{ $supplierGroups->count() }} different suppliers. Separate orders will be created for each supplier.</small>
                    @endif
                </div>
                <div class="card-body">
                    <!-- Display validation errors -->
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <!-- Display flash messages -->
                    @if(session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif

                    <form id="checkout-form" action="{{ route('checkout.process') }}" method="POST">
                        @csrf
                        
                        <!-- Delivery Method -->
                        <div class="mb-4">
                            <h5>Delivery Method</h5>
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="radio" name="delivery_method" id="delivery" value="delivery" 
                                       {{ old('delivery_method', 'delivery') == 'delivery' ? 'checked' : '' }}>
                                <label class="form-check-label" for="delivery">
                                    Delivery 
                                    @if(isset($supplierGroups) && $supplierGroups->count() > 1)
                                        (+RM {{ number_format(50.00 * $supplierGroups->count(), 2) }} - RM 50.00 per supplier)
                                    @else
                                        (+RM 50.00)
                                    @endif
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="delivery_method" id="self-pickup" value="self-pickup"
                                       {{ old('delivery_method') == 'self-pickup' ? 'checked' : '' }}>
                                <label class="form-check-label" for="self-pickup">
                                    Self Pickup (Free)
                                    @if(isset($supplierGroups) && $supplierGroups->count() > 1)
                                        <small class="text-muted d-block">You will need to pick up from {{ $supplierGroups->count() }} different suppliers</small>
                                    @endif
                                </label>
                            </div>
                        </div>

                        <!-- Order Summary -->
                        <div class="mb-4">
                            <h5>Order Summary</h5>
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Item</th>
                                            <th>Variation</th>
                                            <th>Quantity</th>
                                            <th>Subtotal</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($cartItems as $item)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <img src="{{ asset($item->material->first_image) }}" 
                                                         alt="{{ $item->material->name }}" 
                                                         class="img-thumbnail me-2" 
                                                         style="width: 50px; height: 50px; object-fit: cover;">
                                                    <span>{{ $item->material->name }}</span>
                                                </div>
                                            </td>
                                            <td>
                                                @if($item->materialVariation)
                                                    {{ $item->materialVariation->variation_name }}: {{ $item->materialVariation->variation_value }}
                                                @else
                                                    -
                                                @endif
                                            </td>
                                            <td>{{ $item->quantity }}</td>
                                            <td>RM {{ number_format($item->subtotal, 2) }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th colspan="3">Subtotal</th>
                                            <th>RM <span id="subtotal">{{ number_format($subtotal, 2) }}</span></th>
                                        </tr>
                                        <tr>
                                            <th colspan="3">Shipping Cost</th>
                                            <th>RM <span id="shipping-cost">{{ isset($supplierGroups) ? number_format(50.00 * $supplierGroups->count(), 2) : '50.00' }}</span></th>
                                        </tr>
                                        <tr>
                                            <th colspan="3">Total</th>
                                            <th>RM <span id="total">{{ isset($supplierGroups) ? number_format($subtotal + (50.00 * $supplierGroups->count()), 2) : number_format($subtotal + 50, 2) }}</span></th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>

                        <!-- Contact Details - Always shown -->
                        <div class="mb-4">
                            <h5 id="contact-title">Contact Details</h5>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="delivery_name" class="form-label">Full Name *</label>
                                    <input type="text" class="form-control @error('delivery_name') is-invalid @enderror" 
                                           id="delivery_name" name="delivery_name" value="{{ old('delivery_name') }}" required>
                                    @error('delivery_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="delivery_phone" class="form-label">Phone Number *</label>
                                    <input type="tel" class="form-control @error('delivery_phone') is-invalid @enderror" 
                                           id="delivery_phone" name="delivery_phone" value="{{ old('delivery_phone') }}" required>
                                    @error('delivery_phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Delivery Address Details - Only shown for delivery -->
                        <div id="delivery-address" class="mb-4">
                            <h5>Delivery Address</h5>
                            <div class="mb-3">
                                <label for="delivery_address" class="form-label">Delivery Address *</label>
                                <textarea class="form-control @error('delivery_address') is-invalid @enderror" 
                                          id="delivery_address" name="delivery_address" rows="3">{{ old('delivery_address') }}</textarea>
                                @error('delivery_address')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label for="delivery_state" class="form-label">State *</label>
                                    <input type="text" class="form-control @error('delivery_state') is-invalid @enderror" 
                                           id="delivery_state" name="delivery_state" value="{{ old('delivery_state') }}">
                                    @error('delivery_state')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="delivery_city" class="form-label">City *</label>
                                    <input type="text" class="form-control @error('delivery_city') is-invalid @enderror" 
                                           id="delivery_city" name="delivery_city" value="{{ old('delivery_city') }}">
                                    @error('delivery_city')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="delivery_postal_code" class="form-label">Postal Code *</label>
                                    <input type="text" class="form-control @error('delivery_postal_code') is-invalid @enderror" 
                                           id="delivery_postal_code" name="delivery_postal_code" value="{{ old('delivery_postal_code') }}">
                                    @error('delivery_postal_code')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                    <div class="d-flex justify-content-between align-items-center">
                        <a href="{{ route('customer.cart.index') }}" class="btn btn-secondary btn-lg">
                            Back to Cart
                        </a>

                        <button type="submit" class="btn btn-primary btn-lg" id="submit-btn">
                            <span id="btn-text">Proceed to Payment</span>
                            <span id="btn-spinner" class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                        </button>
                    </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const deliveryRadio = document.getElementById('delivery');
    const selfPickupRadio = document.getElementById('self-pickup');
    const deliveryAddress = document.getElementById('delivery-address');
    const contactTitle = document.getElementById('contact-title');
    const shippingCostSpan = document.getElementById('shipping-cost');
    const totalSpan = document.getElementById('total');
    const subtotal = {{ $subtotal }};
    const supplierCount = {{ isset($supplierGroups) ? $supplierGroups->count() : 1 }};
    const baseShipping = 50.00;
    const form = document.getElementById('checkout-form');
    const submitBtn = document.getElementById('submit-btn');
    const btnText = document.getElementById('btn-text');
    const btnSpinner = document.getElementById('btn-spinner');

    function updateDeliveryOption() {
        if (deliveryRadio.checked) {
            // Delivery selected
            deliveryAddress.style.display = 'block';
            contactTitle.textContent = 'Contact Details';
            const totalShipping = baseShipping * supplierCount;
            shippingCostSpan.textContent = totalShipping.toFixed(2);
            totalSpan.textContent = (subtotal + totalShipping).toFixed(2);
           
            
            // Make address fields required
            document.querySelectorAll('#delivery-address input, #delivery-address textarea').forEach(field => {
                field.required = true;
            });
        } else {
            // Self-pickup selected
            deliveryAddress.style.display = 'none';
            contactTitle.textContent = 'Contact Details (for pickup identification)';
            shippingCostSpan.textContent = '0.00';
            totalSpan.textContent = subtotal.toFixed(2);
            
            // Remove required from address fields and clear values
            document.querySelectorAll('#delivery-address input, #delivery-address textarea').forEach(field => {
                field.required = false;
                field.value = ''; // Clear values when not required
            });
        }
    }


    // Form submission handler
    form.addEventListener('submit', function(e) {
        console.log('Form is being submitted...');
        
        // Show loading state
        submitBtn.disabled = true;
        btnText.classList.add('d-none');
        btnSpinner.classList.remove('d-none');
        
        // Re-enable button after 10 seconds as fallback
        setTimeout(function() {
            submitBtn.disabled = false;
            btnText.classList.remove('d-none');
            btnSpinner.classList.add('d-none');
        }, 10000);
    });

    deliveryRadio.addEventListener('change', updateDeliveryOption);
    selfPickupRadio.addEventListener('change', updateDeliveryOption);
    
    // Initial call
    updateDeliveryOption();
});
</script>
@endsection
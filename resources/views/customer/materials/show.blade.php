@extends('layouts.customer')

@section('content')
<div class="material-detail py-3 m-3" style="background-color: #a8d8e8; padding: 20px 0;">
    <div class="container">
         <h1 class="mb-4" style="color: #2980b9; font-size: 2.5rem; font-weight: bold; font-family: Arial, sans-serif;">Material Details</h1>
        <div class="row">
            <div class="col-md-6">
                <!-- Image Gallery -->
                <div class="image-gallery">
                    @if($material->images->count() > 0)
                        <div id="materialCarousel" class="carousel slide" data-bs-ride="carousel">
                            <div class="carousel-inner">
                                @foreach($material->images as $index => $image)
                                    <div class="carousel-item {{ $index == 0 ? 'active' : '' }}">
                                        <img src="{{ asset($image->image_path) }}" class="d-block w-100" alt="{{ $material->name }}" style="height: 400px; object-fit: cover;">
                                    </div>
                                @endforeach
                            </div>
                            @if($material->images->count() > 1)
                                <button class="carousel-control-prev" type="button" data-bs-target="#materialCarousel" data-bs-slide="prev">
                                    <span class="carousel-control-prev-icon"></span>
                                </button>
                                <button class="carousel-control-next" type="button" data-bs-target="#materialCarousel" data-bs-slide="next">
                                    <span class="carousel-control-next-icon"></span>
                                </button>
                            @endif
                        </div>
                    @else
                        <img src="{{ asset('images/placeholder.png') }}" class="img-fluid" alt="No image" style="height: 400px; object-fit: cover;">
                    @endif
                </div>
            </div>
            
            <div class="col-md-6">
                <!-- Material Details -->
                <div class="material-info">
                    <h1>{{ $material->name }}</h1>
                    <p class="text-muted">{{ $material->category->category }} > {{ $material->subCategory->subcategory }}</p>
                    
                    <div class="price-info mb-3">
                        <h2 class="text-dark">RM{{ number_format($material->price, 2) }}</h2>
                        <p class="text-muted">Stock Available: {{ $material->stock }} units</p>
                    </div>

                <!-- Variations Section -->
                    @if($material->variations->count() > 0)
                    <div class="variations-section mb-3">
                        <p class="fw-semibold mb-2">Variations</p>
                        <div class="d-flex flex-wrap gap-2">
                            @foreach($material->variations as $variation)
                                <button class="btn btn-outline-secondary btn-sm variation-btn text-center" data-variation="{{ $variation->id }}">
                                    {{ $variation->variation_name }}: {{ $variation->variation_value }}<br>
                                    <small>(Stock: {{ $variation->stock }})</small>
                                </button>
                            @endforeach
                        </div>
                    </div>
                    @endif

                <!-- Quantity -->
                    <div class="quantity-section mb-3">
                        <p class="fw-semibold mb-2">Quantity</p>
                        <div class="d-flex align-items-center">
                            <button class="btn btn-outline-secondary" type="button" onclick="decreaseQuantity()" style="width: 40px;">-</button>
                            <input type="number" class="form-control text-center mx-2" id="quantity" value="1" min="1" max="{{ $material->stock }}" style="width: 60px;">
                            <button class="btn btn-outline-secondary" type="button" onclick="increaseQuantity()" style="width: 40px;">+</button>
                        </div>
                    </div>


                    <!-- Action Buttons -->
                    <div class="action-buttons">
                        <button class="btn btn-primary btn-lg me-2" onclick="addToCart()">
                            <i class="fas fa-shopping-cart"></i> Add to Cart
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Description -->
        <div class="row mt-5">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5>Product Description</h5>
                    </div>
                    <div class="card-body">
                        <p>{{ $material->description }}</p>
                    </div>
                </div>
            </div>
        </div>

               <!-- Sustainability Rating Section -->
        <div class="row mt-5">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5>Sustainability Ratings</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <div class="col-12">
                                <small class="text-muted d-block fw-bold">Recyclability</small>
                                <small class="text-muted d-block" style="font-size: 0.6em;">1 Star: Not recyclable, or rarely recycled.<br>5 Stars: Widely recyclable, high demand for recycled content, includes post-consumer recycled content in the material itself.</small>
                                <div>
                                    @for($i = 1; $i <= 5; $i++)
                                        <i class="fas fa-star {{ $i <= $material->recyclability_rating ? 'text-warning' : 'text-muted' }}" style="font-size: 0.8rem;"></i>
                                    @endfor
                                </div>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <div class="col-12">
                                <small class="text-muted d-block fw-bold">Carbon Footprint</small>
                                <small class="text-muted d-block" style="font-size: 0.6em;">1 Star: High embodied carbon, energy-intensive production, long-distance transport.<br>5 Stars: Low embodied carbon, renewable energy in production, optimized logistics.</small>
                                <div>
                                    @for($i = 1; $i <= 5; $i++)
                                        <i class="fas fa-star {{ $i <= $material->carbon_footprint_rating ? 'text-warning' : 'text-muted' }}" style="font-size: 0.8rem;"></i>
                                    @endfor
                                </div>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <div class="col-12">
                                <small class="text-muted d-block fw-bold">Environmental Impact</small>
                                <small class="text-muted d-block" style="font-size: 0.6em;">1 Star: Significant negative impact, non-renewable resources, high pollution processes.<br>5 Stars: Minimal impact, closed-loop systems, renewable resources, local sourcing.</small>
                                <div>
                                    @for($i = 1; $i <= 5; $i++)
                                        <i class="fas fa-star {{ $i <= $material->environmental_impact_rating ? 'text-warning' : 'text-muted' }}" style="font-size: 0.8rem;"></i>
                                    @endfor
                                </div>
                            </div>
                        </div>
                        
                        <div class="mt-4">
                            <small class="fw-bold">Overall Sustainability Rating: {{ number_format($material->sustainability_rating, 1) }}/5</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Shop Information Section -->
        <div class="row mt-5">
            <div class="col-12">
                <div class="card">
                    <div class="card-header bg-light">
                        <h5>Shop Information</h5>
                    </div>
                    <div class="card-body d-flex justify-content-between align-items-center flex-wrap">
                        <div class="d-flex align-items-center gap-3">
                            <img 
                                src="{{ $material->supplier->profile_picture ? asset('storage/profile_pictures' . $material->supplier->profile_picture) : asset('images/DefaultProfile.png') }}" 
                                alt="Profile Picture" 
                                class="rounded-circle" 
                                style="width: 60px; height: 60px; object-fit: cover;"
                            >
                            <div>
                                <h6 class="mb-1 fw-bold">{{ $material->supplier->company_name }}</h6>
                                    <div>
                                        <small class="d-block">
                                            <span class="fw-bold">Phone Number</span> {{ $material->supplier->phone ?? 'Not Available' }}
                                        </small>
                                        <small class="d-block">
                                            <span class="fw-bold">Shop Address</span> {{ $material->supplier->address?? 'Not Available' }}
                                        </small>
                                    </div>
                            </div>
                        </div>
                        <div class="text-center mt-3 mt-md-0">
                            <button class="btn btn-outline-dark" onclick="chatWithSupplier()">
                        <i class="fas fa-comment-dots me-1"></i> Chat with supplier
                    </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Review Section -->
        <div class="row mt-5">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Reviews</h5>
                    @if($reviewCount > 0)
                        <div class="text-muted">
                            <span class="fw-bold">{{ number_format($averageRating, 1) }}</span>
                            <span class="rating me-2">
                                @for($i = 1; $i <= 5; $i++)
                                    <i class="fas fa-star {{ $i <= round($averageRating) ? 'text-warning' : 'text-muted' }}" style="font-size: 0.9rem;"></i>
                                @endfor
                            </span>
                            ({{ $reviewCount }} {{ $reviewCount == 1 ? 'review' : 'reviews' }})
                        </div>
                    @endif
                </div>
            </div>
            <div class="card-body">
                @if($reviews && $reviews->count() > 0)
                    <!-- Rating Summary -->
                    @if($reviewCount > 1)
                        <div class="rating-summary mb-4 p-3 bg-light rounded">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="text-center">
                                        <div class="display-4 fw-bold text-primary">{{ number_format($averageRating, 1) }}</div>
                                        <div class="rating mb-2">
                                            @for($i = 1; $i <= 5; $i++)
                                                <i class="fas fa-star {{ $i <= round($averageRating) ? 'text-warning' : 'text-muted' }}"></i>
                                            @endfor
                                        </div>
                                        <div class="text-muted">Based on {{ $reviewCount }} reviews</div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    @foreach([5,4,3,2,1] as $star)
                                        <div class="d-flex align-items-center mb-1">
                                            <span class="me-2">{{ $star }}</span>
                                            <i class="fas fa-star text-warning me-2" style="font-size: 0.8rem;"></i>
                                            <div class="progress flex-grow-1 me-2" style="height: 8px;">
                                                <div class="progress-bar bg-warning" 
                                                     style="width: {{ $reviewCount > 0 ? ($ratingBreakdown[$star] / $reviewCount) * 100 : 0 }}%">
                                                </div>
                                            </div>
                                            <span class="text-muted small">{{ $ratingBreakdown[$star] }}</span>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Individual Reviews -->
                    <div class="reviews-list">
                        @foreach($reviews as $review)
                            <div class="review-item mb-4 pb-3 {{ !$loop->last ? 'border-bottom' : '' }}">
                                <div class="d-flex align-items-start">
                                    <div class="review-avatar me-3">
                                        <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" 
                                             style="width: 40px; height: 40px; font-size: 1.2rem;">
                                            {{ strtoupper(substr($review->customer_name ?? 'C', 0, 1)) }}
                                        </div>
                                    </div>
                                    <div class="flex-grow-1">
                                        <div class="d-flex justify-content-between align-items-start mb-2">
                                            <div>
                                                <div class="fw-bold">{{ $review->customer_name ?? 'Customer' }}</div>
                                                <div class="rating mb-1">
                                                    @for($i = 1; $i <= 5; $i++)
                                                        <i class="fas fa-star {{ $i <= $review->rating ? 'text-warning' : 'text-muted' }}" 
                                                           style="font-size: 0.9rem;"></i>
                                                    @endfor
                                                </div>
                                            </div>
                                            <small class="text-muted">{{ $review->created_at->format('M d, Y') }}</small>
                                        </div>
                                        <p class="mb-0 text-dark">{{ $review->comment }}</p>
                                        
                                        <!-- Verified Purchase Badge -->
                                        <div class="mt-2">
                                            <span class="badge bg-success-subtle text-success border border-success-subtle">
                                                <i class="fas fa-check-circle me-1" style="font-size: 0.8rem;"></i>
                                                Verified Purchase
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Pagination -->
                    @if($reviews->hasPages())
                        <div class="d-flex justify-content-center mt-4">
                            {{ $reviews->links() }}
                        </div>
                    @endif

                @else
                    <!-- No Reviews State -->
                    <div class="text-center py-5">
                        <i class="fas fa-star fa-3x text-muted mb-3"></i>
                        <h6 class="text-muted">No reviews yet</h6>
                        <p class="text-muted mb-0">Be the first to review this material!</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
</div>
</div>

<style>
.variation-btn.active {
    background-color: #007bff;
    color: white;
    border-color: #007bff;
}

.product-info-card {
    background-color: rgba(173, 216, 230, 0.3) !important;
}

.rating-summary .progress {
    background-color: #e9ecef;
}

.review-item:hover {
    background-color: #f8f9fa;
    border-radius: 8px;
    padding: 15px;
    margin: -15px;
    transition: all 0.2s ease;
}

.badge.bg-success-subtle {
    --bs-bg-opacity: 0.1;
    background-color: rgba(var(--bs-success-rgb), var(--bs-bg-opacity)) !important;
}

.text-success {
    color: #198754 !important;
}

.border-success-subtle {
    border-color: rgba(var(--bs-success-rgb), 0.25) !important;
}
</style>

<script>

async function addToCart() {
    const quantityInput = document.getElementById('quantity');
    const quantity = parseInt(quantityInput.value);
    
    // Get selected variation if any
    const selectedVariation = document.querySelector('.variation-btn.active');
    const variationId = selectedVariation ? selectedVariation.getAttribute('data-variation') : null;
    
    // Validate selection
    if (document.querySelectorAll('.variation-btn').length > 0 && !variationId) {
        alert('Please select a variation');
        return;
    }
    
    if (quantity <= 0) {
        alert('Please enter a valid quantity');
        return;
    }
    
    try {
        const response = await fetch('/customer/cart/add', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                material_id: {{ $material->id }}, 
                quantity: quantity,
                variation_id: variationId
            })
        });
        
        const data = await response.json();
        
        if (data.success) {
            alert(data.success);

            // Update cart count if provided in response
            if (data.cart_count !== undefined) {
                updateCartCount(data.cart_count);
            }

            // Optional: Reset quantity to 1
            quantityInput.value = 1;
            // Optional: Clear variation selection
            if (selectedVariation) {
                selectedVariation.classList.remove('active');
            }
        } else {
            alert(data.error || 'Error adding item to cart');
        }
    } catch (error) {
        console.error('Error:', error);
        alert('Error adding item to cart');
    }
}

function increaseQuantity() {
    const quantityInput = document.getElementById('quantity');
    const currentValue = parseInt(quantityInput.value);
    const maxValue = parseInt(quantityInput.max);
    
    if (currentValue < maxValue) {
        quantityInput.value = currentValue + 1;
    }
}

function decreaseQuantity() {
    const quantityInput = document.getElementById('quantity');
    const currentValue = parseInt(quantityInput.value);
    
    if (currentValue > 1) {
        quantityInput.value = currentValue - 1;
    }
}

// Variation selection
document.addEventListener('DOMContentLoaded', function() {
    const variationBtns = document.querySelectorAll('.variation-btn');
    
    variationBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            variationBtns.forEach(b => b.classList.remove('active'));
            this.classList.add('active');

            // Update max quantity based on selected variation
            const quantityInput = document.getElementById('quantity');
            const variationStock = this.querySelector('small').textContent.match(/\d+/)[0];
            quantityInput.max = variationStock;
            
            // Reset quantity to 1 if current quantity exceeds new max
            if (parseInt(quantityInput.value) > parseInt(variationStock)) {
                quantityInput.value = 1;
            }
        });
    });
});

function chatWithSupplier() {
    alert('Chat with supplier will be implemented later');
}
</script>

@endsection
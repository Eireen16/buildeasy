@extends('layouts.customer')

@section('content')
<div class="container-fluid mt-3" style="background-color: #a8d8e8; padding: 20px 0;">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card mt-3">
                <div class="card-header">
                    <h5>Write Review</h5>
                </div>
                <div class="card-body">
                    <!-- Material Information -->
                    <div class="row mb-4">
                        <div class="col-md-3">
                            @if($material->images->count() > 0)
                                <img src="{{ asset($material->images->first()->image_path) }}" 
                                     alt="{{ $material->name }}" 
                                     class="img-fluid rounded">
                            @else
                                <div class="bg-light d-flex align-items-center justify-content-center rounded" style="height: 120px;">
                                    <i class="fas fa-image fa-2x text-muted"></i>
                                </div>
                            @endif
                        </div>
                        <div class="col-md-9">
                            <h6>{{ $material->name }}</h6>
                            <p class="text-muted mb-1">Order ID: {{ $order->order_id }}</p>
                            <p class="text-muted mb-1">Supplier: {{ $material->supplier->company_name ?? 'N/A' }}</p>
                            @if($orderItem->variation_name)
                                <p class="text-muted mb-0">
                                    {{ $orderItem->variation_name }}: {{ $orderItem->variation_value }}
                                </p>
                            @endif
                            <p class="text-muted mb-0">Quantity: {{ $orderItem->quantity }}</p>
                        </div>
                    </div>

                    <!-- Review Form -->
                    <form action="{{ route('customer.reviews.store', $orderItem->id) }}" method="POST">
                        @csrf
                        
                        <!-- Rating -->
                        <div class="mb-3">
                            <label class="form-label fw-bold">Rating <span class="text-danger">*</span></label>
                            <div class="rating-input">
                                <div class="star-rating" id="starRating">
                                    @for($i = 1; $i <= 5; $i++)
                                        <i class="fas fa-star star" data-rating="{{ $i }}" style="font-size: 1.5rem; cursor: pointer; color: #ddd;"></i>
                                    @endfor
                                </div>
                                <input type="hidden" name="rating" id="ratingValue" value="{{ old('rating') }}">
                                <div class="form-text">Click on the stars to rate this material</div>
                            </div>
                            @error('rating')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Comment -->
                        <div class="mb-4">
                            <label for="comment" class="form-label fw-bold">Review Comment <span class="text-danger">*</span></label>
                            <textarea class="form-control @error('comment') is-invalid @enderror" 
                                      id="comment" 
                                      name="comment" 
                                      rows="5" 
                                      maxlength="1000"
                                      placeholder="Share your experience with this material...">{{ old('comment') }}</textarea>
                            <div class="form-text">Maximum 1000 characters</div>
                            @error('comment')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Submit Buttons -->
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-star me-2"></i>Submit Review
                            </button>
                            <a href="{{ route('customer.orders.index') }}" class="btn btn-secondary">
                                Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.star:hover,
.star.active {
    color: #ffc107 !important;
}

.star {
    transition: color 0.2s;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const stars = document.querySelectorAll('.star');
    const ratingInput = document.getElementById('ratingValue');
    
    // Set initial rating if there's an old value
    const oldRating = {{ old('rating', 0) }};
    if (oldRating > 0) {
        updateStars(oldRating);
        ratingInput.value = oldRating;
    }
    
    stars.forEach(star => {
        star.addEventListener('click', function() {
            const rating = parseInt(this.dataset.rating);
            ratingInput.value = rating;
            updateStars(rating);
        });
        
        star.addEventListener('mouseover', function() {
            const rating = parseInt(this.dataset.rating);
            highlightStars(rating);
        });
    });
    
    document.getElementById('starRating').addEventListener('mouseleave', function() {
        const currentRating = parseInt(ratingInput.value) || 0;
        updateStars(currentRating);
    });
    
    function updateStars(rating) {
        stars.forEach((star, index) => {
            if (index < rating) {
                star.classList.add('active');
                star.style.color = '#ffc107';
            } else {
                star.classList.remove('active');
                star.style.color = '#ddd';
            }
        });
    }
    
    function highlightStars(rating) {
        stars.forEach((star, index) => {
            if (index < rating) {
                star.style.color = '#ffc107';
            } else {
                star.style.color = '#ddd';
            }
        });
    }
});
</script>
@endsection
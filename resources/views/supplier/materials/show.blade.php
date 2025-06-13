@extends('layouts.supplier')

@section('content')
<div class="material-detail py-3 mt-3" style="background-color: #a8d8e8; padding: 20px 0;">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h1 class="mb-4 mt-2" style="color: #2980b9; font-size: 2.5rem; font-weight: bold; font-family: Arial, sans-serif;"> Material Details</h1>
            <div>
                <a href="{{ route('materials.edit', $material) }}" class="btn btn-warning me-2">
                    <i class="fas fa-edit"></i> Edit
                </a>
                <button class="btn btn-danger" onclick="confirmDelete({{ $material->id }})">
                    <i class="fas fa-trash"></i> Delete
                </button>
                
                <!-- Hidden form for delete -->
                <form id="delete-form-{{ $material->id }}" action="{{ route('materials.destroy', $material) }}" method="POST" style="display: none;">
                    @csrf
                    @method('DELETE')
                </form>
            </div>
        </div>

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

                    <!-- Statistics -->
                    <div class="col-4 text-center">
                        <div class="card bg-light">
                            <div class="card-body">
                                <h4 class="text-success">{{ $orderCount }}</h4>
                                <small>Orders</small>
                            </div>
                        </div>
                    </div>

                    <!-- Variations -->
                    @if($material->variations->count() > 0)
                        <div class="variations mb-4 mt-3">
                            <h5>Available Variations</h5>
                            <div class="table-responsive">
                                <table class="table table-sm">
                                    <thead>
                                        <tr>
                                            <th>Type</th>
                                            <th>Value</th>
                                            <th>Stock</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($material->variations as $variation)
                                            <tr>
                                                <td>{{ $variation->variation_name }}</td>
                                                <td>{{ $variation->variation_value }}</td>
                                                <td>{{ $variation->stock }} units</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @endif
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

<script>
function confirmDelete(materialId) {
    if (confirm('Are you sure you want to delete this material? This action cannot be undone.')) {
        document.getElementById('delete-form-' + materialId).submit();
    }
}
</script>

@endsection
@extends('layouts.customer')

@section('content')
<div class="daily-discover py-3">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="mb-4" style="color: #2980b9; font-size: 2.5rem; font-weight: bold; font-family: Arial, sans-serif;">
            <i class="fas fa-heart text-danger me-2"></i>My Likes
        </h1>
    </div>
    
    <!-- Materials Grid -->
    <div class="container">
        <div class="row">
            @forelse ($likedMaterials as $material)
            <div class="col-md-4 col-sm-6 mb-4">
                <div class="card h-100 position-relative">
                    <!-- Remove Like Button (Top Right) -->
                    <form action="{{ route('customer.likes.remove', $material->id) }}" 
                          method="POST" 
                          class="position-absolute" 
                          style="top: 10px; right: 10px; z-index: 10;"
                          onsubmit="return confirm('Are you sure you want to remove this from your likes?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger rounded-circle" style="width: 35px; height: 35px; padding: 0;">
                            <i class="fas fa-heart-broken"></i>
                        </button>
                        <small><br>Unlike</small>
                    </form>
                    
                    <a href="{{ route('customer.materials.show', $material->id) }}" class="text-decoration-none text-dark">
                        <div class="card-body p-3">
                            <div class="text-center mb-3">
                                <img src="{{ asset($material->first_image) }}" class="img-fluid" alt="{{ $material->name }}" style="height: 200px; object-fit: cover;">
                            </div>
                            <h5>{{ $material->name }}</h5>
                            
                            
                            <div class="d-flex justify-content-between align-items-center">
                                <div><h4>RM{{ number_format($material->price, 2) }}</h4></div>
                                <div class="text-end small">
                                    <div>{{ $material->supplier->location ?? 'Unknown Location' }}</div>
                                    <div>Stock: {{ $material->stock }}</div>
                                </div>
                            </div>
                            
                            <!-- Sustainability Rating -->
                            <div class="mt-2">
                                <small class="text-muted">Sustainability: </small>
                                @for($i = 1; $i <= 5; $i++)
                                    <span class="{{ $i <= $material->sustainability_rating ? 'text-warning' : 'text-muted' }}">★</span>
                                @endfor
                                <small class="text-muted">({{ number_format($material->sustainability_rating, 1) }})</small>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
            @empty
            <div class="col-12">
                <div class="text-center py-5">
                    <i class="fas fa-heart-broken fa-5x text-muted mb-3"></i>
                    <h4>No Liked Materials Yet</h4>
                    <p class="text-muted">Start exploring materials and like the ones you're interested in!</p>
                    <a href="{{ route('customer.dashboard') }}" class="btn btn-primary">
                        <i class="fas fa-search"></i> Browse Materials
                    </a>
                </div>
            </div>
            @endforelse
        </div>
        
        <!-- Pagination -->
        @if($likedMaterials->hasPages())
            <div class="d-flex justify-content-center mt-4">
                {{ $likedMaterials->links() }}
            </div>
        @endif
    </div>
</div>

<!-- Success/Error Messages -->
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show position-fixed" 
         style="top: 20px; right: 20px; z-index: 1050;" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show position-fixed" 
         style="top: 20px; right: 20px; z-index: 1050;" role="alert">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<style>
.card {
    transition: transform 0.2s ease-in-out;
}

.card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
}

.btn-danger:hover {
    background-color: #dc3545;
    border-color: #dc3545;
}

/* Ensure the remove button doesn't interfere with card hover */
.card .btn {
    transition: all 0.2s ease;
}

.card:hover .btn-danger {
    opacity: 1;
}

.btn-danger {
    opacity: 0.8;
}

.btn-danger:hover {
    opacity: 1 !important;
}
</style>
@endsection
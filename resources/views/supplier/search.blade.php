@extends('layouts.supplier')
@section('content')
<div class="search-results py-3">
  <div class="container-fluid mt-3" style="background-color: #a8d8e8; padding: 20px 0;">
    <div class="container">
        <!-- Search Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="mb-2" style="color: #2980b9; font-size: 2.5rem; font-weight: bold; font-family: Arial, sans-serif;">
                    Search Results
                </h1>
                @if(!empty($searchQuery))
                    <p class="text-muted mb-0">
                        Showing {{ $materials->total() }} results for "<strong>{{ $searchQuery }}</strong>"
                    </p>
                @else
                    <p class="text-muted mb-0">
                        Showing {{ $materials->total() }} materials
                    </p>
                @endif
            </div>
            <div>
                <a href="{{ route('supplier.dashboard') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Back to Dashboard
                </a>
            </div>
        </div>


        <!-- Materials Grid -->
        <div class="row">
            @forelse ($materials as $material)
            <div class="col-md-4 col-sm-6 mb-4">
                <a href="{{ route('supplier.materials.show', $material->id) }}" class="text-decoration-none text-dark">
                    <div class="card h-100">
                        <div class="card-body p-3">
                            <div class="text-center mb-3">
                                <img src="{{ asset($material->first_image) }}" class="img-fluid" alt="{{ $material->name }}" style="height: 200px; object-fit: cover;">
                            </div>
                            <h5>{{ $material->name }}</h5>
                            <div class="d-flex justify-content-between align-items-center">
                                <div><h4>RM{{ number_format($material->price, 2) }}</h4></div>
                                <div class="text-end small">
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
                    </div>
                </a>
            </div>
            @empty
            <div class="col-12">
                <div class="text-center py-5">
                    <div class="mb-3">
                        <i class="fas fa-search fa-3x text-muted"></i>
                    </div>
                    <h4>No materials found</h4>
                </div>
            </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if($materials->hasPages())
            <div class="d-flex justify-content-center mt-4">
                {{ $materials->links() }}
            </div>
        @endif
    </div>
</div>
</div>


<style>
.card {
    transition: transform 0.2s ease-in-out;
}

.card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
}

.form-select:focus, .form-control:focus, .btn:focus {
    box-shadow: 0 0 0 0.2rem rgba(41, 128, 185, 0.25);
    border-color: #2980b9;
}

.btn-outline-secondary {
    border-color: #6c757d;
    color: #6c757d;
}

.btn-outline-secondary:hover {
    background-color: #6c757d;
    border-color: #6c757d;
}

.badge a:hover {
    opacity: 0.8;
}

.search-results .card-body {
    position: relative;
}

.search-results .card-body::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(135deg, rgba(41, 128, 185, 0.02) 0%, rgba(52, 152, 219, 0.02) 100%);
    pointer-events: none;
}
</style>
@endsection
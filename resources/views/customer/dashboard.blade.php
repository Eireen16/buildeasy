@extends('layouts.customer')
@section('content')
<div class="daily-discover py-3">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="mb-4" style="color: #2980b9; font-size: 2.5rem; font-weight: bold; font-family: Arial, sans-serif;">Daily Discover</h1>
    </div>
    
    <!-- Filter Toggle Button -->
    <div class="container mb-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <button class="btn btn-outline-primary" type="button" data-bs-toggle="collapse" data-bs-target="#filterSection" aria-expanded="false" aria-controls="filterSection">
                <i class="fas fa-filter me-2"></i>Filters
                <i class="fas fa-chevron-down ms-2" id="filterChevron"></i>
            </button>
            @if(request('sustainability_filter') || request('location_filter'))
                <div class="active-filters-summary">
                    <small class="text-muted">Active filters: </small>
                    @if(request('sustainability_filter'))
                        <span class="badge bg-info me-1">
                            Sustainability: 
                            @switch(request('sustainability_filter'))
                                @case('5') 4.5+ stars @break
                                @case('4') 3.5-4.4 stars @break
                                @case('3') 2.5-3.4 stars @break
                                @case('2') 1.5-2.4 stars @break
                                @case('1') Below 1.5 stars @break
                            @endswitch
                        </span>
                    @endif
                    @if(request('location_filter'))
                        <span class="badge bg-success">Location: {{ request('location_filter') }}</span>
                    @endif
                </div>
            @endif
        </div>
        
        <!-- Collapsible Filter Section -->
        <div class="collapse" id="filterSection">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title mb-3">
                        <i class="fas fa-sliders-h me-2"></i>Filter Materials
                    </h5>
                    <form method="GET" action="{{ route('customer.dashboard') }}" class="row g-3">
                    <!-- Sustainability Filter -->
                    <div class="col-md-4">
                        <label for="sustainability_filter" class="form-label">Sustainability Rating</label>
                        <select name="sustainability_filter" id="sustainability_filter" class="form-select">
                            <option value="">All Ratings</option>
                            <option value="5" {{ request('sustainability_filter') == '5' ? 'selected' : '' }}>
                                ★★★★★ (4.5+ stars)
                            </option>
                            <option value="4" {{ request('sustainability_filter') == '4' ? 'selected' : '' }}>
                                ★★★★☆ (3.5-4.4 stars)
                            </option>
                            <option value="3" {{ request('sustainability_filter') == '3' ? 'selected' : '' }}>
                                ★★★☆☆ (2.5-3.4 stars)
                            </option>
                            <option value="2" {{ request('sustainability_filter') == '2' ? 'selected' : '' }}>
                                ★★☆☆☆ (1.5-2.4 stars)
                            </option>
                            <option value="1" {{ request('sustainability_filter') == '1' ? 'selected' : '' }}>
                                ★☆☆☆☆ (Below 1.5 stars)
                            </option>
                        </select>
                    </div>

                    <!-- Location Filter -->
                    <div class="col-md-4">
                        <label for="location_filter" class="form-label">Location</label>
                        <select name="location_filter" id="location_filter" class="form-select">
                            <option value="">All Locations</option>
                            @foreach($locations as $location)
                                <option value="{{ $location }}" {{ request('location_filter') == $location ? 'selected' : '' }}>
                                    {{ $location }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Filter Buttons -->
                    <div class="col-md-4 d-flex align-items-end gap-2">
                        <button type="submit" class="btn btn-primary">Apply Filters</button>
                        <a href="{{ route('customer.dashboard') }}" class="btn btn-secondary">Clear Filters</a>
                    </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Materials Grid -->
    <div class="container">
        <div class="row">
            @forelse ($materials as $material)
            <div class="col-md-4 col-sm-6 mb-4">
                <a href="{{ route('customer.materials.show', $material->id) }}" class="text-decoration-none text-dark">
                    <div class="card h-100">
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
                    </div>
                </a>
            </div>
            @empty
            <div class="col-12">
                <div class="text-center py-5">
                    <h4>No materials found</h4>
                    @if(request('sustainability_filter') || request('location_filter'))
                        <p class="text-muted">Try adjusting your filters or <a href="{{ route('customer.dashboard') }}">clear all filters</a> to see more results.</p>
                    @else
                        <p class="text-muted">Check back later for new listings!</p>
                    @endif
                </div>
            </div>
            @endforelse
        </div>
    </div>
</div>

<style>
.active-filters-summary .badge {
    font-size: 0.75em;
}

.card {
    transition: transform 0.2s ease-in-out;
}

.card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
}

.form-select:focus, .btn:focus {
    box-shadow: 0 0 0 0.2rem rgba(41, 128, 185, 0.25);
    border-color: #2980b9;
}

.btn-outline-primary {
    border-color: #2980b9;
    color: #2980b9;
}

.btn-outline-primary:hover {
    background-color: #2980b9;
    border-color: #2980b9;
}

#filterChevron {
    transition: transform 0.3s ease;
}

.collapsed #filterChevron {
    transform: rotate(0deg);
}

#filterSection.show ~ * #filterChevron {
    transform: rotate(180deg);
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const filterSection = document.getElementById('filterSection');
    const filterChevron = document.getElementById('filterChevron');
    
    filterSection.addEventListener('show.bs.collapse', function () {
        filterChevron.style.transform = 'rotate(180deg)';
    });
    
    filterSection.addEventListener('hide.bs.collapse', function () {
        filterChevron.style.transform = 'rotate(0deg)';
    });
});
</script>
@endsection
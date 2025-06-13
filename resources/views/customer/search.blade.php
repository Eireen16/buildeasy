@extends('layouts.customer')
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
                <a href="{{ route('customer.dashboard') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Back to Dashboard
                </a>
            </div>
        </div>

        <!-- Search Form -->
        <div class="container mb-4">
            <!-- Toggle Button -->
            <div class="d-flex justify-content-between align-items-center mb-3">
                <button class="btn btn-outline-primary" type="button" data-bs-toggle="collapse" data-bs-target="#filterSection" aria-expanded="false" aria-controls="filterSection">
                    <i class="fas fa-filter me-2"></i> Filters
                    <i class="fas fa-chevron-down ms-2" id="filterChevron"></i>
                </button>
            </div>

            <!-- Collapsible Search & Filters Section -->
            <div class="collapse" id="filterSection">
                <!-- Search Form -->
                <div class="card mb-3">
                    <div class="card-body">
                        <form method="GET" action="{{ route('customer.search') }}" class="row g-3">
                            <!-- Search Input -->
                            <div class="col-md-6">
                                <label for="search" class="form-label">Search Materials</label>
                                <input type="text" name="search" id="search" class="form-control" 
                                    placeholder="Search by name, description, category, or supplier..." 
                                    value="{{ $searchQuery }}">
                            </div>

                            <!-- Sustainability Filter -->
                            <div class="col-md-3">
                                <label for="sustainability_filter" class="form-label">Sustainability Rating</label>
                                <select name="sustainability_filter" id="sustainability_filter" class="form-select">
                                    <option value="">All Ratings</option>
                                    <option value="5" {{ request('sustainability_filter') == '5' ? 'selected' : '' }}>
                                        ★★★★★ (4.5+ stars)
                                    </option>
                                    <option value="4" {{ request('sustainability_filter') == '4' ? 'selected' : '' }}>
                                        ★★★★☆ (3.5–4.4 stars)
                                    </option>
                                    <option value="3" {{ request('sustainability_filter') == '3' ? 'selected' : '' }}>
                                        ★★★☆☆ (2.5–3.4 stars)
                                    </option>
                                    <option value="2" {{ request('sustainability_filter') == '2' ? 'selected' : '' }}>
                                        ★★☆☆☆ (1.5–2.4 stars)
                                    </option>
                                    <option value="1" {{ request('sustainability_filter') == '1' ? 'selected' : '' }}>
                                        ★☆☆☆☆ (Below 1.5 stars)
                                    </option>
                                </select>
                            </div>

                            <!-- Location Filter -->
                            <div class="col-md-3">
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

                            <!-- Buttons -->
                            <div class="col-12">
                                <div class="d-flex gap-2">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-search me-2"></i>Search
                                    </button>
                                    <a href="{{ route('customer.search') }}" class="btn btn-secondary">
                                        <i class="fas fa-times me-2"></i>Clear All
                                    </a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

        <!-- Active Filters Display -->
        @if(request('sustainability_filter') || request('location_filter') || !empty($searchQuery))
            <div class="card card-body">
                <div class="d-flex flex-wrap gap-2 align-items-center">
                    <small class="text-muted me-2">Active filters:</small>

                    @if(!empty($searchQuery))
                        <span class="badge bg-primary">
                            Search: "{{ $searchQuery }}"
                            <a href="{{ route('customer.search', array_merge(request()->except('search'), [])) }}" 
                               class="text-white ms-1 text-decoration-none">×</a>
                        </span>
                    @endif

                    @if(request('sustainability_filter'))
                        <span class="badge bg-info">
                            Sustainability: 
                            @switch(request('sustainability_filter'))
                                @case('5') 4.5+ stars @break
                                @case('4') 3.5–4.4 stars @break
                                @case('3') 2.5–3.4 stars @break
                                @case('2') 1.5–2.4 stars @break
                                @case('1') Below 1.5 stars @break
                            @endswitch
                            <a href="{{ route('customer.search', array_merge(request()->except('sustainability_filter'), [])) }}" 
                               class="text-white ms-1 text-decoration-none">×</a>
                        </span>
                    @endif

                    @if(request('location_filter'))
                        <span class="badge bg-success">
                            Location: {{ request('location_filter') }}
                            <a href="{{ route('customer.search', array_merge(request()->except('location_filter'), [])) }}" 
                               class="text-white ms-1 text-decoration-none">×</a>
                        </span>
                    @endif
                </div>
            </div>
        @endif
    </div>
</div>

        <!-- Active Filters Display -->
        @if(request('sustainability_filter') || request('location_filter') || !empty($searchQuery))
            <div class="mb-4">
                <div class="d-flex flex-wrap gap-2 align-items-center">
                    <small class="text-muted me-2">Active filters:</small>
                    
                    @if(!empty($searchQuery))
                        <span class="badge bg-primary">
                            Search: "{{ $searchQuery }}"
                            <a href="{{ route('customer.search', array_merge(request()->except('search'), [])) }}" 
                               class="text-white ms-1 text-decoration-none">×</a>
                        </span>
                    @endif
                    
                    @if(request('sustainability_filter'))
                        <span class="badge bg-info">
                            Sustainability: 
                            @switch(request('sustainability_filter'))
                                @case('5') 4.5+ stars @break
                                @case('4') 3.5-4.4 stars @break
                                @case('3') 2.5-3.4 stars @break
                                @case('2') 1.5-2.4 stars @break
                                @case('1') Below 1.5 stars @break
                            @endswitch
                            <a href="{{ route('customer.search', array_merge(request()->except('sustainability_filter'), [])) }}" 
                               class="text-white ms-1 text-decoration-none">×</a>
                        </span>
                    @endif
                    
                    @if(request('location_filter'))
                        <span class="badge bg-success">
                            Location: {{ request('location_filter') }}
                            <a href="{{ route('customer.search', array_merge(request()->except('location_filter'), [])) }}" 
                               class="text-white ms-1 text-decoration-none">×</a>
                        </span>
                    @endif
                </div>
            </div>
        @endif

        <!-- Materials Grid -->
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
                    <div class="mb-3">
                        <i class="fas fa-search fa-3x text-muted"></i>
                    </div>
                    <h4>No materials found</h4>
                    @if(!empty($searchQuery) || request('sustainability_filter') || request('location_filter'))
                        <p class="text-muted">Try adjusting your search terms or filters, or <a href="{{ route('customer.search') }}">clear all filters</a> to see more results.</p>
                    @else
                        <p class="text-muted">Start searching to find materials that match your needs!</p>
                    @endif
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
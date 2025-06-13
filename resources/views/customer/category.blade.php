@extends('layouts.customer')

@section('title', $category->category . ' - Construction Materials')

@section('content')
<div class="category-results py-3">
    <div class="container-fluid mt-3" style="background-color: #a8d8e8; padding: 20px 0;">
        <div class="container">
            <!-- Breadcrumb -->
            <nav aria-label="breadcrumb" class="mb-4">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('customer.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ $category->category }}</li>
                </ol>
            </nav>

            <!-- Category Header -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h1 class="mb-2" style="color: #2980b9; font-size: 2.5rem; font-weight: bold; font-family: Arial, sans-serif;">
                        {{ $category->category }} Materials
                    </h1>
                    <p class="text-muted mb-0">
                        Showing {{ $materials->total() }} materials from our verified suppliers
                    </p>
                </div>
            </div>

            <!-- Subcategories Quick Links (if available) -->
            @if($category->subCategories->count() > 0)
            <div class="card mb-4">
                <div class="card-body">
                    <h5 class="mb-3">Browse by Subcategory:</h5>
                    <div class="d-flex flex-wrap gap-2">
                        @foreach($category->subCategories as $subCategory)
                            <a href="{{ route('customer.subcategory', [$category->id, $subCategory->id]) }}" 
                               class="btn btn-outline-primary btn-sm">
                                {{ $subCategory->subcategory }}
                                <span class="badge bg-secondary ms-1">{{ $subCategory->materials->count() }}</span>
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif

            <!-- Filters Section -->
            <div class="container mb-4">
                <!-- Toggle Button -->
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <button class="btn btn-outline-primary" type="button" data-bs-toggle="collapse" data-bs-target="#filterSection" aria-expanded="false" aria-controls="filterSection">
                        <i class="fas fa-filter me-2"></i> Filters
                        <i class="fas fa-chevron-down ms-2" id="filterChevron"></i>
                    </button>
                </div>

                <!-- Collapsible Filters Section -->
                <div class="collapse" id="filterSection">
                    <div class="card mb-3">
                        <div class="card-body">
                            <form method="GET" action="{{ route('customer.category', $category->id) }}" class="row g-3">
                                <!-- Sustainability Filter -->
                                <div class="col-md-6">
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
                                <div class="col-md-6">
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
                                            <i class="fas fa-filter me-2"></i>Apply Filters
                                        </button>
                                        <a href="{{ route('customer.category', $category->id) }}" class="btn btn-secondary">
                                            <i class="fas fa-times me-2"></i>Clear All
                                        </a>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Active Filters Display -->
                    @if(request('sustainability_filter') || request('location_filter'))
                        <div class="card card-body">
                            <div class="d-flex flex-wrap gap-2 align-items-center">
                                <small class="text-muted me-2">Active filters:</small>

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
                                        <a href="{{ route('customer.category', array_merge([$category->id], request()->except('sustainability_filter'))) }}" 
                                           class="text-white ms-1 text-decoration-none">×</a>
                                    </span>
                                @endif

                                @if(request('location_filter'))
                                    <span class="badge bg-success">
                                        Location: {{ request('location_filter') }}
                                        <a href="{{ route('customer.category', array_merge([$category->id], request()->except('location_filter'))) }}" 
                                           class="text-white ms-1 text-decoration-none">×</a>
                                    </span>
                                @endif
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Materials Grid -->
            <div class="row">
                @forelse ($materials as $material)
                <div class="col-md-4 col-sm-6 mb-4">
                    <a href="{{ route('customer.materials.show', $material->id) }}" class="text-decoration-none text-dark">
                        <div class="card h-100">
                            <div class="card-body p-3">
                                <div class="text-center mb-3">
                                    @if($material->images->isNotEmpty())
                                        <img src="{{ asset($material->images->first()->image_path) }}" 
                                             class="img-fluid" 
                                             alt="{{ $material->name }}" 
                                             style="height: 200px; object-fit: cover;">
                                    @else
                                        <div class="bg-light d-flex align-items-center justify-content-center" 
                                             style="height: 200px;">
                                            <i class="fas fa-image fa-3x text-muted"></i>
                                        </div>
                                    @endif
                                </div>
                                <h5>{{ $material->name }}</h5>
                                
                                <div class="d-flex justify-content-between align-items-center">
                                    <div><h4>RM{{ number_format($material->price, 2) }}</h4></div>
                                    <div class="text-end small">
                                        <div>Stock: {{ $material->stock }}</div>
                                        <small class="text-muted">{{ $material->supplier->location ?? 'Unknown Location' }}</small>
                                    </div>
                                </div>
                                
                                <!-- Sustainability Rating -->
                                @if($material->sustainability_rating)
                                    <div class="mt-2">
                                        <small class="text-muted">Sustainability: </small>
                                        @for($i = 1; $i <= 5; $i++)
                                            <span class="{{ $i <= $material->sustainability_rating ? 'text-warning' : 'text-muted' }}">★</span>
                                        @endfor
                                        <small class="text-muted">({{ number_format($material->sustainability_rating, 1) }})</small>
                                    </div>
                                @endif
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
                        @if(request('sustainability_filter') || request('location_filter'))
                            <p class="text-muted">No materials available in this category with your current filters. Try <a href="{{ route('customer.category', $category->id) }}">clearing all filters</a> to see more results.</p>
                        @else
                            <p class="text-muted">No materials are currently available in this category.</p>
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

.category-results .card-body {
    position: relative;
}

.category-results .card-body::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(135deg, rgba(41, 128, 185, 0.02) 0%, rgba(52, 152, 219, 0.02) 100%);
    pointer-events: none;
}

.breadcrumb {
    background: rgba(255, 255, 255, 0.1);
    padding: 0.75rem 1rem;
    border-radius: 0.375rem;
}

.breadcrumb-item + .breadcrumb-item::before {
    color: #6c757d;
}

.breadcrumb-item a {
    color: #2980b9;
    text-decoration: none;
}

.breadcrumb-item a:hover {
    text-decoration: underline;
}

.breadcrumb-item.active {
    color: #495057;
}
</style>
@endsection
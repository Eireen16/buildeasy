@extends('layouts.supplier')

@section('title', $subCategory->subcategory . ' - ' . $category->category . ' Materials - Supplier')

@section('content')
<div class="subcategory-results py-3">
    <div class="container-fluid mt-3" style="background-color: #a8d8e8; padding: 20px 0;">
        <div class="container">
            <!-- Breadcrumb -->
            <nav aria-label="breadcrumb" class="mb-4">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('supplier.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('supplier.category', $category->id) }}">{{ $category->category }}</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ $subCategory->subcategory }}</li>
                </ol>
            </nav>

            <!-- Subcategory Header -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h1 class="mb-2" style="color: #2980b9; font-size: 2.5rem; font-weight: bold; font-family: Arial, sans-serif;">
                        {{ $subCategory->subcategory }}
                    </h1>
                    <p class="text-muted mb-0">
                        {{ $category->category }} - Your materials in this subcategory ({{ $materials->total() }} materials)
                    </p>
                </div>
            </div>

            <!-- Related Subcategories -->
            @if($category->subCategories->where('id', '!=', $subCategory->id)->count() > 0)
            <div class="card mb-4">
                <div class="card-body">
                    <h6 class="mb-3">Other {{ $category->category }} Subcategories:</h6>
                    <div class="d-flex flex-wrap gap-2">
                        @foreach($category->subCategories->where('id', '!=', $subCategory->id) as $otherSubCategory)
                            @php
                                $otherSubCategoryMaterialCount = \App\Models\Material::where('supplier_id', Auth::user()->supplier->id)
                                                                                    ->where('sub_category_id', $otherSubCategory->id)
                                                                                    ->count();
                            @endphp
                            <a href="{{ route('supplier.subcategory', [$category->id, $otherSubCategory->id]) }}" 
                               class="btn btn-outline-secondary btn-sm">
                                {{ $otherSubCategory->subcategory }}
                                <span class="badge bg-light text-dark ms-1">{{ $otherSubCategoryMaterialCount }}</span>
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif

            <!-- Materials Grid -->
            <div class="row">
                @forelse ($materials as $material)
                <div class="col-md-4 col-sm-6 mb-4">
                    <a href="{{ route('supplier.materials.show', $material->id) }}" class="text-decoration-none text-dark">
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
                            
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <div><h4>RM{{ number_format($material->price, 2) }}</h4></div>
                                <div class="text-end small">
                                    <div>Stock: {{ $material->stock }}</div>
                                </div>
                               </div>

                            <!-- Sustainability Rating -->
                            @if($material->sustainability_rating)
                                <div class="mb-2">
                                    <small class="text-muted">Sustainability: </small>
                                    @for($i = 1; $i <= 5; $i++)
                                        <span class="{{ $i <= $material->sustainability_rating ? 'text-warning' : 'text-muted' }}">★</span>
                                    @endfor
                                    <small class="text-muted">({{ number_format($material->sustainability_rating, 1) }})</small>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-12">
                    <div class="text-center py-5">
                        <div class="mb-3">
                            <i class="fas fa-box-open fa-3x text-muted"></i>
                        </div>
                        <h4>No materials found</h4>
                        <p class="text-muted">You haven't added any materials in this subcategory yet.</p>
                        <a href="{{ route('materials.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus me-2"></i>Add Your First Material
                        </a>
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

.btn:focus {
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

.subcategory-results .card-body {
    position: relative;
}

.subcategory-results .card-body::before {
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
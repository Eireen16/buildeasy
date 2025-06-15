@extends('layouts.admin')

@section('title', 'Material Details')

@section('content')
<div class="container mt-4">
    <div class="row">
        <div class="col-12">
            <!-- Header -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <a href="{{ route('admin.materials') }}" class="btn btn-outline-secondary me-3">
                        <i class="fas fa-arrow-left me-1"></i>Back to Materials
                    </a>
                    <h2 class="d-inline mb-0">Material Details</h2>
                </div>
                <button type="button" 
                        class="btn btn-danger" 
                        data-bs-toggle="modal" 
                        data-bs-target="#deleteModal">
                    <i class="fas fa-trash me-1"></i>Delete Material
                </button>
            </div>

            <div class="row">
                <!-- Images Section -->
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">
                                <i class="fas fa-images me-2"></i>Material Images
                            </h5>
                        </div>
                        <div class="card-body">
                            @if($material->images->count() > 0)
                                <!-- Main Image -->
                                <div id="materialCarousel" class="carousel slide mb-3" data-bs-ride="carousel">
                                    <div class="carousel-inner">
                                        @foreach($material->images as $index => $image)
                                            <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                                                <img src="{{ asset($image->image_path) }}" 
                                                     class="d-block w-100" 
                                                     alt="Material Image"
                                                     style="height: 400px; object-fit: cover;">
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

                                <!-- Thumbnail Navigation -->
                                @if($material->images->count() > 1)
                                    <div class="d-flex flex-wrap gap-2">
                                        @foreach($material->images as $index => $image)
                                            <img src="{{ asset($image->image_path) }}" 
                                                 class="img-thumbnail cursor-pointer" 
                                                 style="width: 80px; height: 80px; object-fit: cover;"
                                                 onclick="document.querySelector('#materialCarousel .carousel-item:nth-child({{ $index + 1 }})').click()"
                                                 data-bs-target="#materialCarousel" 
                                                 data-bs-slide-to="{{ $index }}">
                                        @endforeach
                                    </div>
                                @endif
                            @else
                                <div class="text-center py-5">
                                    <i class="fas fa-image fa-3x text-muted mb-3"></i>
                                    <p class="text-muted">No images available</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Material Information -->
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">
                                <i class="fas fa-info-circle me-2"></i>Material Information
                            </h5>
                        </div>
                        <div class="card-body">
                            <table class="table table-borderless">
                                <tr>
                                    <td class="fw-bold text-muted" style="width: 40%;">Name:</td>
                                    <td>{{ $material->name }}</td>
                                </tr>
                                <tr>
                                    <td class="fw-bold text-muted">Price:</td>
                                    <td class="text-primary h5">RM {{ number_format($material->price, 2) }}</td>
                                </tr>
                                <tr>
                                    <td class="fw-bold text-muted">Stock:</td>
                                    <td>
                                        <span class="badge {{ $material->stock > 0 ? 'bg-success' : 'bg-danger' }}">
                                            {{ $material->stock }} units
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="fw-bold text-muted">Category:</td>
                                    <td>{{ $material->category->category }}</td>
                                </tr>
                                <tr>
                                    <td class="fw-bold text-muted">Sub-Category:</td>
                                    <td>{{ $material->subCategory->subcategory ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <td class="fw-bold text-muted">Added:</td>
                                    <td>{{ $material->created_at->format('M d, Y \a\t g:i A') }}</td>
                                </tr>
                                <tr>
                                    <td class="fw-bold text-muted">Updated:</td>
                                    <td>{{ $material->updated_at->format('M d, Y \a\t g:i A') }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <!-- Supplier Information -->
                    <div class="card mt-3">
                        <div class="card-header">
                            <h5 class="mb-0">
                                <i class="fas fa-building me-2"></i>Supplier Information
                            </h5>
                        </div>
                        <div class="card-body">
                            <table class="table table-borderless">
                                <tr>
                                    <td class="fw-bold text-muted" style="width: 40%;">Company:</td>
                                    <td>{{ $material->supplier->company_name }}</td>
                                </tr>
                                <tr>
                                    <td class="fw-bold text-muted">Username:</td>
                                    <td>{{ $material->supplier->user->username }}</td>
                                </tr>
                                <tr>
                                    <td class="fw-bold text-muted">Email:</td>
                                    <td>{{ $material->supplier->user->email }}</td>
                                </tr>
                                <tr>
                                    <td class="fw-bold text-muted">Location:</td>
                                    <td>{{ $material->supplier->location ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <td class="fw-bold text-muted">Status:</td>
                                    <td>
                                        <span class="badge {{ $material->supplier->is_approved ? 'bg-success' : 'bg-warning' }}">
                                            {{ $material->supplier->is_approved ? 'Approved' : 'Pending' }}
                                        </span>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Description -->
            <div class="row mt-4">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">
                                <i class="fas fa-align-left me-2"></i>Description
                            </h5>
                        </div>
                        <div class="card-body">
                            <p class="mb-0">{{ $material->description }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sustainability Ratings -->
            <div class="row mt-4">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">
                                <i class="fas fa-leaf me-2"></i>Sustainability Ratings
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row text-center">
                                <div class="col-md-3">
                                    <div class="border rounded p-3">
                                        <i class="fas fa-seedling fa-2x text-success mb-2"></i>
                                        <h6>Environmental Impact</h6>
                                        <div class="h4 text-success">{{ $material->environmental_impact_rating }}/5</div>
                                        <div class="progress" style="height: 8px;">
                                            <div class="progress-bar bg-success" 
                                                 style="width: {{ ($material->environmental_impact_rating / 5) * 100 }}%"></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="border rounded p-3">
                                        <i class="fas fa-smog fa-2x text-warning mb-2"></i>
                                        <h6>Carbon Footprint</h6>
                                        <div class="h4 text-warning">{{ $material->carbon_footprint_rating }}/5</div>
                                        <div class="progress" style="height: 8px;">
                                            <div class="progress-bar bg-warning" 
                                                 style="width: {{ ($material->carbon_footprint_rating / 5) * 100 }}%"></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="border rounded p-3">
                                        <i class="fas fa-recycle fa-2x text-info mb-2"></i>
                                        <h6>Recyclability</h6>
                                        <div class="h4 text-info">{{ $material->recyclability_rating }}/5</div>
                                        <div class="progress" style="height: 8px;">
                                            <div class="progress-bar bg-info" 
                                                 style="width: {{ ($material->recyclability_rating / 5) * 100 }}%"></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="border rounded p-3 bg-light">
                                        <i class="fas fa-award fa-2x text-primary mb-2"></i>
                                        <h6>Overall Sustainability</h6>
                                        <div class="h4 text-primary">{{ number_format($material->sustainability_rating, 1) }}/5</div>
                                        <div class="progress" style="height: 8px;">
                                            <div class="progress-bar bg-primary" 
                                                 style="width: {{ ($material->sustainability_rating / 5) * 100 }}%"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Variations -->
            @if($material->variations->count() > 0)
                <div class="row mt-4">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0">
                                    <i class="fas fa-list me-2"></i>Material Variations
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>Variation Name</th>
                                                <th>Variation Value</th>
                                                <th>Stock</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($material->variations as $variation)
                                                <tr>
                                                    <td>{{ $variation->variation_name }}</td>
                                                    <td>{{ $variation->variation_value }}</td>
                                                    <td>
                                                        <span class="badge {{ $variation->stock > 0 ? 'bg-success' : 'bg-danger' }}">
                                                            {{ $variation->stock }} units
                                                        </span>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirm Delete</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete the material "<strong>{{ $material->name }}</strong>"?</p>
                <p class="text-muted">This action cannot be undone. All associated images, variations, and related data will also be deleted.</p>
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <strong>Warning:</strong> This material might be referenced in orders or reviews. Please ensure this deletion won't affect existing data.
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form method="POST" action="{{ route('admin.materials.delete', $material->id) }}" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-trash me-1"></i>Delete Material
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
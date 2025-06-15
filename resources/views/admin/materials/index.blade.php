@extends('layouts.admin')

@section('title', 'Materials Management')

@section('content')
<div class="container mt-4">
    <div class="row">
        <div class="col-12">
            <!-- Header -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="mb-0">
                    <i class="fas fa-boxes me-2"></i>
                    Materials Management
                </h2>
                <div class="text-muted">
                    Total Materials: {{ $materials->total() }}
                </div>
            </div>

            <!-- Filters -->
            <div class="card mb-4">
                <div class="card-body">
                    <form method="GET" action="{{ route('admin.materials') }}">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label">Search</label>
                                <input type="text" name="search" class="form-control" placeholder="Search materials..." value="{{ $search }}">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Category</label>
                                <select name="category" class="form-select">
                                    <option value="">All Categories</option>
                                    @foreach($categories as $cat)
                                        <option value="{{ $cat->id }}" {{ $category == $cat->id ? 'selected' : '' }}>
                                            {{ $cat->category }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">&nbsp;</label>
                                <div class="d-flex gap-2">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-filter me-1"></i>Filter
                                    </button>
                                    <a href="{{ route('admin.materials') }}" class="btn btn-outline-secondary">
                                        <i class="fas fa-times me-1"></i>Clear
                                    </a>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Materials Grid -->
            @if($materials->count() > 0)
                <div class="row">
                    @foreach($materials as $material)
                        <div class="col-md-6 col-lg-4 mb-4">
                            <div class="card h-100 shadow-sm">
                                <!-- Material Image -->
                                <div class="position-relative">
                                    <img src="{{ asset($material->first_image) }}" 
                                         class="card-img-top" 
                                         alt="{{ $material->name }}" 
                                         style="height: 200px; object-fit: cover;">
                                    <div class="position-absolute top-0 end-0 m-2">
                                    </div>
                                </div>

                                <div class="card-body d-flex flex-column">
                                    <!-- Material Info -->
                                    <h5 class="card-title mb-2">{{ $material->name }}</h5>
                                    <!-- Price and Stock -->
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <span class="h6 text-primary mb-0">RM {{ number_format($material->price, 2) }}</span>
                                        <span class="badge {{ $material->stock > 0 ? 'bg-success' : 'bg-danger' }}">
                                            Stock: {{ $material->stock }}
                                        </span>
                                    </div>

                                    <!-- Action Buttons -->
                                    <div class="mt-auto">
                                        <div class="d-flex gap-2">
                                            <a href="{{ route('admin.materials.show', $material->id) }}" 
                                               class="btn btn-sm btn-outline-primary flex-grow-1">
                                                <i class="fas fa-eye me-1"></i>View Details
                                            </a>
                                            <button type="button" 
                                                    class="btn btn-sm btn-outline-danger" 
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#deleteModal{{ $material->id }}">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                <!-- Date -->
                                <div class="card-footer">
                                    <small class="text-muted">
                                        <i class="fas fa-calendar me-1"></i>
                                        Added: {{ $material->created_at->format('M d, Y') }}
                                    </small>
                                </div>
                            </div>
                        </div>

                        <!-- Delete Confirmation Modal -->
                        <div class="modal fade" id="deleteModal{{ $material->id }}" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Confirm Delete</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <p>Are you sure you want to delete the material "<strong>{{ $material->name }}</strong>"?</p>
                                        <p class="text-muted">This action cannot be undone. All associated images and variations will also be deleted.</p>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                        <form method="POST" action="{{ route('admin.materials.delete', $material->id) }}" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger">Delete Material</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="d-flex justify-content-center mt-4">
                    {{ $materials->appends(request()->query())->links() }}
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-box-open fa-3x text-muted mb-3"></i>
                    <h4 class="text-muted">No Materials Found</h4>
                    <p class="text-muted">No materials match your current filters.</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
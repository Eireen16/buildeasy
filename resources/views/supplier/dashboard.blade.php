@extends('layouts.supplier')

@section('content')
<div class="my-listing py-3">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="mb-4" style="color: #2980b9; font-size: 2.5rem; font-weight: bold; font-family: Arial, sans-serif;">My Listing</h1>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="container">
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
                                    @if($material->variations->count() > 0)
                                        <div class="text-info">{{ $material->variations->count() }} variations</div>
                                    @endif
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
                    <h4>No materials listed yet</h4>
                    <p class="text-muted">Start by adding your first material listing!</p>
                    <a href="{{ route('materials.create') }}" class="btn btn-primary btn-lg">
                        <i class="fas fa-plus"></i> Add Your First Material
                    </a>
                </div>
            </div>
            @endforelse
        </div>
    </div>
</div>
@endsection



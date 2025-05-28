@extends('layouts.customer')
@section('content')
<div class="daily-discover py-3">
    <div class="d-flex justify-content-between align-items-center mb-3">
                 <h1 class="mb-4" style="color: #2980b9; font-size: 2.5rem; font-weight: bold; font-family: Arial, sans-serif;">Daily Discover</h1>
    </div>
    
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
                    <p class="text-muted">Check back later for new listings!</p>
                </div>
            </div>
            @endforelse
        </div>
    </div>
</div>
@endsection
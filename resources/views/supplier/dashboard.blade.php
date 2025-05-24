@extends('layouts.supplier')

@section('content')

<div class="my-listing py-3">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2>My Listing</h2>
    </div>

<div class="container">
    <div class="row">
        @forelse ($materials as $material)
        <div class="col-md-4 col-sm-6 mb-4">
            <a href="{{ route('supplier.materials.show', $material->id) }}" class="text-decoration-none text-dark">
                <div class="card h-100">
                    <div class="card-body p-3">
                        <div class="text-center mb-3">
                            <img src="{{ asset($material->images[0] ?? 'placeholder.jpg') }}" class="img-fluid" alt="Image">
                        </div>
                        <h5>{{ $material->name }}</h5>
                        <div class="d-flex justify-content-between align-items-center">
                            <div><h4>${{ number_format($material->price, 2) }}</h4></div>
                            <div class="text-end small">
                                <div>{{ $material->supplier->location ?? 'Unknown Location' }}</div>
                                <div>Stock: {{ $material->stock }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        @empty
        <p class="text-center">No materials found.</p>
        @endforelse
    </div>
</div>
@endsection



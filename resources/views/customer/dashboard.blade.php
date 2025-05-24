@extends('layouts.customer')

@section('content')
<div class="daily-discover py-3">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2>Daily Discover</h2>
        <!-- <div>
            <input type="text" class="form-control" placeholder="Filter Location">
        </div> 
        <form action="{{ route('materials.search') }}" method="GET" class="mb-3">
        <div class="d-flex justify-content-between align-items-center">
            <div class="d-flex gap-2">
                {{-- Filter by location --}}
                <input 
                    type="text" 
                    name="location" 
                    class="form-control" 
                    placeholder="Filter Location" 
                    value="{{ request('location') }}"
                >

                {{-- Filter by sustainability --}}
                <select name="sustainability" class="form-control">
                    <option value="">Sustainability</option>
                    @for ($i = 5; $i >= 1; $i--)
                        <option value="{{ $i }}" {{ request('sustainability') == $i ? 'selected' : '' }}>
                            {{ $i }} ★ & Up
                        </option>
                    @endfor
                </select>

                <button type="submit" class="btn btn-primary">Filter</button>
            </div>
        </div> -->
    </form>
    </div>
    
    <div class="container">
    <div class="row">
        @forelse ($materials as $material)
        <div class="col-md-4 col-sm-6 mb-4">
            <a href="{{ route('customer.materials.show', $material->id) }}" class="text-decoration-none text-dark">
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



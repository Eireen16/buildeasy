@extends('layouts.supplier') 

@section('content')
<div class="container mt-5">
    <h2>Add New Material</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form action="{{ route('materials.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        {{-- Name --}}
        <div class="mb-3">
            <label class="form-label">Material Name</label>
            <input type="text" name="name" class="form-control" required>
        </div>

        {{-- Price --}}
        <div class="mb-3">
            <label class="form-label">Price (RM)</label>
            <input type="number" name="price" step="0.01" class="form-control" required>
        </div>

        {{-- Stock --}}
        <div class="mb-3">
            <label class="form-label">Stock Quantity</label>
            <input type="number" name="stock" class="form-control" required>
        </div>

        {{-- Category --}}
        <div class="mb-3">
            <label class="form-label">Category</label>
            <select name="category_id" class="form-select" required>
                <option value="">Select a Category</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}">{{ $category->category }} - {{ $category->sub_category }}</option>
                @endforeach
            </select>
        </div>

        {{-- Description --}}
        <div class="mb-3">
            <label class="form-label">Description</label>
            <textarea name="description" class="form-control" rows="3" required></textarea>
        </div>

        {{-- Product Images --}}
        <div class="mb-3">
            <label class="form-label">Product Images</label>
            <input type="file" name="images[]" class="form-control" multiple>
        </div>

        {{-- Variations --}}
        <div class="mb-3">
            <label class="form-label">Variations (e.g., color options)</label>
            <input type="text" name="variations[]" class="form-control mb-2" placeholder="Variation 1">
            <input type="text" name="variations[]" class="form-control mb-2" placeholder="Variation 2">
            <input type="text" name="variations[]" class="form-control" placeholder="Variation 3">
        </div>

        {{-- Ratings --}}
        <div class="mb-3">
            <label class="form-label">Recyclability Rating</label>
            <input type="number" name="recyclability_rating" min="1" max="5" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Carbon Footprint Rating</label>
            <input type="number" name="carbon_footprint_rating" min="1" max="5" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Environmental Impact Rating</label>
            <input type="number" name="environmental_impact_rating" min="1" max="5" class="form-control" required>
        </div>

        {{-- Submit --}}
        <div class="text-end">
            <button type="submit" class="btn btn-primary">Add Material</button>
        </div>
    </form>
</div>
@endsection

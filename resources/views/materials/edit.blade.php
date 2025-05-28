@extends('layouts.supplier')

@section('content')
<div class="add-listing py-3 mt-3" style="background-color: #a8d8e8; padding: 20px 0;">
    <div class="d-flex justify-content-between align-items-center mb-3">

    <div class="container">
        <h1 class="mb-4" style="color: #2980b9; font-size: 2.5rem; font-weight: bold; font-family: Arial, sans-serif;">Edit Listing</h1>
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('materials.update', $material) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <!-- Current Images Display -->
                            @if($material->images->count() > 0)
                            <div class="mb-3">
                                <label class="form-label">Current Images</label>
                                <div class="row">
                                    @foreach($material->images as $image)
                                    <div class="col-md-3 mb-2">
                                        <div class="position-relative">
                                            <img src="{{ asset($image->image_path) }}" alt="Material Image" class="img-thumbnail" style="width: 100%; height: 150px; object-fit: cover;">
                                            <div class="form-check position-absolute top-0 end-0 bg-white p-1 rounded">
                                                <input class="form-check-input" type="checkbox" name="delete_images[]" value="{{ $image->id }}" id="delete_{{ $image->id }}">
                                                <label class="form-check-label text-danger" for="delete_{{ $image->id }}">
                                                    Delete
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                            @endif

                            <!-- Add New Images Upload -->
                            <div class="mb-3">
                                <label for="images" class="form-label">Add New Images (Optional)</label>
                                <input type="file" class="form-control" id="images" name="images[]" multiple accept="image/*">
                                <div class="form-text">You can select multiple images to add to existing ones.</div>
                                @error('images.*')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Material Name -->
                            <div class="mb-3">
                                <label for="name" class="form-label">Material Name *</label>
                                <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $material->name) }}" required>
                                @error('name')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Price and Stock -->
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="price" class="form-label">Price (RM) *</label>
                                        <input type="number" class="form-control" id="price" name="price" step="0.01" min="0" value="{{ old('price', $material->price) }}" required>
                                        @error('price')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="stock" class="form-label">Stock Available *</label>
                                        <input type="number" class="form-control" id="stock" name="stock" min="0" value="{{ old('stock', $material->stock) }}" required>
                                        @error('stock')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Category and Sub-category -->
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="category_id" class="form-label">Category *</label>
                                        <select class="form-select" id="category_id" name="category_id" required>
                                            <option value="">Select Category</option>
                                            @foreach($categories as $category)
                                                <option value="{{ $category->id }}" {{ old('category_id', $material->category_id) == $category->id ? 'selected' : '' }}>
                                                    {{ $category->category }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('category_id')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="sub_category_id" class="form-label">Sub-category *</label>
                                        <select class="form-select" id="sub_category_id" name="sub_category_id" required>
                                            <option value="">Select Sub-category</option>
                                            @if($material->subCategory)
                                                <option value="{{ $material->subCategory->id }}" selected>{{ $material->subCategory->subcategory }}</option>
                                            @endif
                                        </select>
                                        @error('sub_category_id')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Description -->
                            <div class="mb-3">
                                <label for="description" class="form-label">Description *</label>
                                <textarea class="form-control" id="description" name="description" rows="4" required>{{ old('description', $material->description) }}</textarea>
                                @error('description')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Sustainability Ratings -->
                            <div class="mb-4">
                                <h5>Sustainability Ratings</h5>
                                <div class="form-text">Please correctly rate the material using the guide.</div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <label for="environmental_impact_rating" class="form-label">Environmental Impact</label>
                                        <div class="rating-container">
                                            @for($i = 5; $i >= 1; $i--)
                                                <input type="radio" id="env_{{ $i }}" name="environmental_impact_rating" value="{{ $i }}" {{ old('environmental_impact_rating', $material->environmental_impact_rating) == $i ? 'checked' : '' }} required>
                                                <label for="env_{{ $i }}" class="star">★</label>
                                            @endfor
                                        </div>
                                        <div class="form-text" style="font-size: 0.6em;">1 Star: Significant negative impact, non-renewable resources, high pollution processes.<br>
                                                                       5 Stars: Minimal impact, closed-loop systems, renewable resources, local sourcing.</div>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="carbon_footprint_rating" class="form-label">Carbon Footprint</label>
                                        <div class="rating-container">
                                            @for($i = 5; $i >= 1; $i--)
                                                <input type="radio" id="carbon_{{ $i }}" name="carbon_footprint_rating" value="{{ $i }}" {{ old('carbon_footprint_rating', $material->carbon_footprint_rating) == $i ? 'checked' : '' }} required>
                                                <label for="carbon_{{ $i }}" class="star">★</label>
                                            @endfor
                                        </div>
                                        <div class="form-text" style="font-size: 0.6em;">1 Star: High embodied carbon, energy-intensive production, long-distance transport.<br>
                                                                       5 Stars: Low embodied carbon, renewable energy in production, optimized logistics.</div>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="recyclability_rating" class="form-label">Recyclability</label>
                                        <div class="rating-container">
                                            @for($i = 5; $i >= 1; $i--)
                                                <input type="radio" id="recycle_{{ $i }}" name="recyclability_rating" value="{{ $i }}" {{ old('recyclability_rating', $material->recyclability_rating) == $i ? 'checked' : '' }} required>
                                                <label for="recycle_{{ $i }}" class="star">★</label>
                                            @endfor
                                        </div>
                                        <div class="form-text" style="font-size: 0.6em;">1 Star: Not recyclable, or rarely recycled (e.g., composite materials).<br>
                                                                       5 Stars: Widely recyclable, high demand for recycled content, includes post-consumer recycled content in the material itself.</div>
                                    </div>
                                </div>
                            </div>

                            <!-- Variations -->
                            <div class="mb-4">
                                <h5>Product Variations (Optional)</h5>
                                <div id="variations-container">
                                    @if($material->variations->count() > 0)
                                        @foreach($material->variations as $index => $variation)
                                        <div class="variation-row row mb-2">
                                            <div class="col-md-4">
                                                <input type="text" class="form-control" name="variations[{{ $index }}][name]" placeholder="Variation Name (e.g., Color)" value="{{ $variation->variation_name }}">
                                            </div>
                                            <div class="col-md-4">
                                                <input type="text" class="form-control" name="variations[{{ $index }}][value]" placeholder="Variation Value (e.g., Blue)" value="{{ $variation->variation_value }}">
                                            </div>
                                            <div class="col-md-3">
                                                <input type="number" class="form-control" name="variations[{{ $index }}][stock]" placeholder="Stock" min="0" value="{{ $variation->stock }}">
                                            </div>
                                            <div class="col-md-1">
                                                <button type="button" class="btn btn-danger btn-sm remove-variation">×</button>
                                            </div>
                                        </div>
                                        @endforeach
                                    @else
                                        <div class="variation-row row mb-2">
                                            <div class="col-md-4">
                                                <input type="text" class="form-control" name="variations[0][name]" placeholder="Variation Name (e.g., Color)">
                                            </div>
                                            <div class="col-md-4">
                                                <input type="text" class="form-control" name="variations[0][value]" placeholder="Variation Value (e.g., Blue)">
                                            </div>
                                            <div class="col-md-3">
                                                <input type="number" class="form-control" name="variations[0][stock]" placeholder="Stock" min="0">
                                            </div>
                                            <div class="col-md-1">
                                                <button type="button" class="btn btn-danger btn-sm remove-variation">×</button>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                                <button type="button" class="btn btn-secondary btn-sm" id="add-variation">+ Add Variation</button>
                            </div>

                            <!-- Submit Button -->
                            <div class="text-center">
                                <button type="submit" class="btn btn-primary btn-lg">Update Material Listing</button>
                                <a href="{{ route('supplier.dashboard') }}" class="btn btn-secondary btn-lg ms-2">Cancel</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .rating-container {
        display: flex;
        flex-direction: row-reverse;
        justify-content: flex-end;
    }

    .rating-container input[type="radio"] {
        display: none;
    }

    .rating-container .star {
        font-size: 2rem;
        color: #ddd;
        cursor: pointer;
        transition: color 0.2s;
    }

    .rating-container input[type="radio"]:checked ~ .star,
    .rating-container .star:hover,
    .rating-container .star:hover ~ .star {
        color: #ffc107;
    }

    .variation-row {
        border: 1px solid #ddd;
        padding: 10px;
        margin-bottom: 10px;
        border-radius: 5px;
    }
</style>

<script>
    // Handle category change to load subcategories
    document.getElementById('category_id').addEventListener('change', function() {
        const categoryId = this.value;
        const subCategorySelect = document.getElementById('sub_category_id');
        
        // Clear subcategories
        subCategorySelect.innerHTML = '<option value="">Select Sub-category</option>';
        
        if (categoryId) {
            fetch(`/api/categories/${categoryId}/subcategories`)
                .then(response => response.json())
                .then(data => {
                    data.forEach(subCategory => {
                        const option = document.createElement('option');
                        option.value = subCategory.id;
                        option.textContent = subCategory.subcategory;
                        subCategorySelect.appendChild(option);
                    });
                });
        }
    });

    // Load subcategories on page load if category is selected
    document.addEventListener('DOMContentLoaded', function() {
        const categoryId = document.getElementById('category_id').value;
        const currentSubCategoryId = {{ $material->sub_category_id ?? 'null' }};
        
        if (categoryId) {
            fetch(`/api/categories/${categoryId}/subcategories`)
                .then(response => response.json())
                .then(data => {
                    const subCategorySelect = document.getElementById('sub_category_id');
                    subCategorySelect.innerHTML = '<option value="">Select Sub-category</option>';
                    
                    data.forEach(subCategory => {
                        const option = document.createElement('option');
                        option.value = subCategory.id;
                        option.textContent = subCategory.subcategory;
                        if (subCategory.id === currentSubCategoryId) {
                            option.selected = true;
                        }
                        subCategorySelect.appendChild(option);
                    });
                });
        }
    });

    // Handle variations
    let variationCount = {{ $material->variations->count() > 0 ? $material->variations->count() : 1 }};
    
    document.getElementById('add-variation').addEventListener('click', function() {
        const container = document.getElementById('variations-container');
        const newRow = document.createElement('div');
        newRow.className = 'variation-row row mb-2';
        newRow.innerHTML = `
            <div class="col-md-4">
                <input type="text" class="form-control" name="variations[${variationCount}][name]" placeholder="Variation Name (e.g., Color)">
            </div>
            <div class="col-md-4">
                <input type="text" class="form-control" name="variations[${variationCount}][value]" placeholder="Variation Value (e.g., Blue)">
            </div>
            <div class="col-md-3">
                <input type="number" class="form-control" name="variations[${variationCount}][stock]" placeholder="Stock" min="0">
            </div>
            <div class="col-md-1">
                <button type="button" class="btn btn-danger btn-sm remove-variation">×</button>
            </div>
        `;
        container.appendChild(newRow);
        variationCount++;
    });

    // Remove variation
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('remove-variation')) {
            e.target.closest('.variation-row').remove();
        }
    });
</script>

@endsection
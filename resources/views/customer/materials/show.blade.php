@extends('layouts.customer')

@section('content')
<div class="container">
    <div class="row">
        <!-- Material Images -->
        <div class="col-md-6">
    @if (!empty($material->images) && is_array($material->images))
        <img src="{{ asset($material->images[0]) }}" class="img-fluid mb-3" alt="Material Image">
        <div class="d-flex flex-wrap">
            @foreach ($material->images as $image)
                <img src="{{ asset($image) }}" class="img-thumbnail m-1" style="width: 80px; height: 80px;">
            @endforeach
        </div>
    @else
        <img src="https://via.placeholder.com/400" class="img-fluid" alt="No Image">
    @endif
    </div>
</div>

        <!-- Material Info -->
        <div class="col-md-6">
            <h2>{{ $material->name }}</h2>
            <h4>${{ $material->price }}</h4>
            <p><strong>Stock:</strong> {{ $material->stock }}</p>

            <div class="mb-3">
                <strong>Variation:</strong><br>
                @foreach ($material->variations as $variation)
                    <button class="btn btn-outline-primary btn-sm m-1">{{ $variation->name }}</button>
                @endforeach
            </div>

            <p><strong>Description:</strong><br>{{ $material->description }}</p>

            <div>
                <strong>Sustainability Rating:</strong><br>
                <div>
                    Environmental Impact:
                    @for ($i = 0; $i < $material->environmental_impact_rating; $i++)
                        ⭐
                    @endfor
                </div>
                <div>
                    Carbon Footprint:
                    @for ($i = 0; $i < $material->carbon_footprint_rating; $i++)
                        ⭐
                    @endfor
                </div>
                <div>
                    Recyclability:
                    @for ($i = 0; $i < $material->recyclability_rating; $i++)
                        ⭐
                    @endfor
                </div>
                <div>
                    Overall Sustainability: {{ number_format($material->sustainability_rating, 1) }}/5
                </div>
            </div>

            <hr>

            <!-- Supplier Info -->
            <p><strong>Shop Info:</strong><br>
                Address: {{ $material->supplier->shop_address }}<br>
                Phone: {{ $material->supplier->phone }}
            </p>

            <a href="#" class="btn btn-success mt-3">Add to Cart</a>
        </div>
    </div>
</div>
@endsection


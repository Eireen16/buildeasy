@extends('layouts.customer')

@section('content')
<div class="container-fluid mt-3" style="background-color: #a8d8e8; padding: 20px 0;">
    <div class="row justify-content-center">
        <div class="col-12 col-lg-10">
            <!-- Page Header -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="mb-4" style="color: #2980b9; font-size: 2.5rem; font-weight: bold; font-family: Arial, sans-serif;">
                    <i class="fas fa-map-marker-alt me-3"></i>Pickup Address
                </h1>
                <a href="{{ route('customer.orders.index') }}" class="btn btn-light btn-lg">
                    <i class="fas fa-arrow-left me-2"></i>Back to Orders
                </a>
            </div>

            <!-- Order Details Card -->
            <div class="card shadow-lg border-0 mb-4">
                <div class="card-header bg-primary text-white py-3">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h5 class="mb-0 fw-bold">Pickup for: {{ $order->order_id }}</h5>
                        </div>
                        <div class="col-md-4 text-md-end">
                            <span class="badge bg-light text-dark fs-6 px-3 py-2">
                                Status: 
                                @if($order->order_status === 'ready_to_pickup')
                                    Your order is ready for pickup
                                @elseif($order->order_status === 'preparing_to_pickup')
                                    Your order is being prepared
                                @else
                                    {{ ucfirst(str_replace('_', ' ', $order->order_status)) }}
                                @endif
                            </span>
                        </div>
                    </div>
                </div>
                <div class="card-body p-4">
                    <h6 class="fw-bold text-success mb-3">
                        <i class="fas fa-user me-2"></i>Customer Details:
                    </h6>
                    <div class="ms-3">
                        <div class="row">
                            <div class="col-md-6">
                                <p class="mb-1 fw-semibold">{{ $order->delivery_name }}</p>
                            </div>
                            <div class="col-md-6">
                                <p class="mb-1 text-muted">
                                    <i class="fas fa-phone me-1"></i>{{ $order->delivery_phone }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pickup Address Card -->
            <div class="card shadow-sm border-0">
                <div class="card-header bg-gradient text-white py-3" style="background: linear-gradient(135deg, #00b894 0%, #00a085 100%);">
                    <h5 class="mb-0 fw-bold">
                        <i class="fas fa-store me-2"></i>Pickup Address:
                    </h5>
                </div>

                <div class="card-body pickup-address-details">
                    <div class="mb-3">
                        <h5 class="fw-bold text-primary">{{ $order->supplier->company_name }}</h5>
                    </div>

                    <div class="mb-3 d-flex align-items-start">
                        <i class="fas fa-map-marker-alt text-danger me-2 mt-1"></i>
                        <div>
                            <strong>Address:</strong><br>
                            <span>{{ $order->supplier->address }}</span>
                        </div>
                    </div>

                    @if($order->supplier->location)
                    <div class="mb-3 d-flex align-items-start">
                        <i class="fas fa-location-dot text-info me-2 mt-1"></i>
                        <div>
                            <strong>Location:</strong><br>
                            <span>{{ $order->supplier->location }}</span>
                        </div>
                    </div>
                    @endif

                    <div class="mb-3 d-flex align-items-center">
                        <i class="fas fa-phone text-success me-2"></i>
                        <strong>Phone:</strong>
                        <a href="tel:{{ $order->supplier->phone }}" class="text-decoration-none ms-2">
                            {{ $order->supplier->phone }}
                        </a>
                    </div>
                </div>

                <div class="card-footer bg-white border-0 pt-4">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <a href="tel:{{ $order->supplier->phone }}" class="btn btn-success btn-lg w-100">
                                <i class="fas fa-phone me-2"></i>Call Supplier
                            </a>
                        </div>
                        <div class="col-md-6">
                            @if($order->supplier->location)
                            <a href="https://www.google.com/maps/search/?api=1&query={{ urlencode($order->supplier->address) }}"
                            target="_blank" class="btn btn-primary btn-lg w-100">
                                <i class="fas fa-map me-2"></i>Open in Maps
                            </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.pickup-address-content {
    position: relative;
    padding-left: 3rem;
}

.address-item {
    position: relative;
    display: flex;
    align-items: flex-start;
    padding-left: 2rem;
}

.address-marker {
    position: absolute;
    left: -2.25rem;
    top: 0;
    width: 3rem;
    height: 3rem;
    background: linear-gradient(135deg, #00b894, #00a085);
    border: 3px solid #00b894;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1rem;
    color: white;
    z-index: 1;
    box-shadow: 0 2px 10px rgba(0,184,148,0.3);
}

.address-details {
    background: white;
    padding: 1.5rem;
    border-radius: 0.5rem;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    border-left: 4px solid #00b894;
    width: 100%;
    transform: translateX(0.25rem);
}

.address-details h5,
.address-details h6 {
    color: #495057;
}

.card {
    border-radius: 1rem;
}

.card-header {
    border-radius: 1rem 1rem 0 0 !important;
}

.bg-gradient {
    background: linear-gradient(135deg, #00b894 0%, #00a085 100%) !important;
}

.btn {
    border-radius: 0.5rem;
    font-weight: 600;
    transition: all 0.3s ease;
}

.btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(0,0,0,0.2);
}

.btn-success {
    background: linear-gradient(135deg, #28a745, #20c997);
    border: none;
}

.btn-primary {
    background: linear-gradient(135deg, #007bff, #0056b3);
    border: none;
}

.btn-light {
    background: white;
    border: 2px solid #dee2e6;
    color: #495057;
    font-weight: 600;
}

.btn-light:hover {
    background: #f8f9fa;
    border-color: #adb5bd;
}

.badge {
    border-radius: 0.5rem;
    font-weight: 500;
}
</style>
@endsection
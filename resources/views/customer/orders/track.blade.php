@extends('layouts.customer')


@section('content')
<div class="container-fluid mt-3" style="background-color: #a8d8e8; padding: 20px 0;">
    <div class="row justify-content-center">
        <div class="col-12 col-lg-10">
            <!-- Page Header -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="mb-4" style="color: #2980b9; font-size: 2.5rem; font-weight: bold; font-family: Arial, sans-serif;">
                    <i class="fas fa-truck me-3"></i>Track Order
                </h1>
                <a href="{{ route('customer.orders.index') }}" class="btn btn-light btn-lg">
                    <i class="fas fa-arrow-left me-2"></i>Back to Orders
                </a>
            </div>

            <!-- Order Tracking Card -->
            <div class="card shadow-lg border-0 mb-4">
                <div class="card-header bg-primary text-white py-3">
                    <div class="row align-items-center">
                        <div class="col-md-6">
                            <h5 class="mb-0 fw-bold">Tracking for: {{ $order->order_id }}</h5>
                        </div>
                        <div class="col-md-6 text-md-end">
                            <span class="badge bg-light text-dark fs-6 px-3 py-2">
                                Status: 
                                @switch($order->order_status)
                                    @case('to_ship')
                                        Preparing to Ship
                                        @break
                                    @case('shipped')
                                        Shipped
                                        @break
                                    @case('completed')
                                        Delivered
                                        @break
                                    @case('preparing_to_pickup')
                                        Preparing for Pickup
                                        @break
                                    @case('ready_to_pickup')
                                        Ready for Pickup
                                        @break
                                    @case('cancelled')
                                        Cancelled
                                        @break
                                    @default
                                        {{ ucfirst(str_replace('_', ' ', $order->order_status)) }}
                                @endswitch
                            </span>
                        </div>
                    </div>
                </div>
                <div class="card-body p-4">
                    <div class="row">
                        <!-- Delivering From -->
                        <div class="col-md-6">
                            <h6 class="fw-bold text-primary mb-3">
                                <i class="fas fa-store me-2"></i>Delivering from:
                            </h6>
                            <div class="ms-3">
                                <p class="mb-1 fw-semibold">{{ $order->supplier->company_name }}</p>
                                <p class="mb-1 text-muted">{{ $order->supplier->address }}</p>
                                <p class="mb-1 text-muted">{{ $order->supplier->location }}</p>
                                <p class="mb-0 text-muted">
                                    <i class="fas fa-phone me-1"></i>{{ $order->supplier->phone }}
                                </p>
                            </div>
                        </div>

                        <!-- Delivering To -->
                        <div class="col-md-6">
                            <h6 class="fw-bold text-success mb-3">
                                <i class="fas fa-map-marker-alt me-2"></i>Delivering to:
                            </h6>
                            <div class="ms-3">
                                <p class="mb-1 fw-semibold">{{ $order->delivery_name }}</p>
                                @if($order->delivery_method === 'delivery' && $order->delivery_address)
                                    <p class="mb-1 text-muted">{{ $order->delivery_address }}</p>
                                    <p class="mb-1 text-muted">
                                        {{ $order->delivery_city }}, {{ $order->delivery_state }} {{ $order->delivery_postal_code }}
                                    </p>
                                @else
                                    <p class="mb-1 text-muted">Self-Pickup at Supplier Location</p>
                                @endif
                                <p class="mb-0 text-muted">
                                    <i class="fas fa-phone me-1"></i>{{ $order->delivery_phone }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Order Progress Timeline -->
            <div class="card shadow-lg border-0">
                <div class="card-header bg-gradient text-white py-3" style="background: linear-gradient(135deg, #00b894 0%, #00a085 100%);">
                    <h5 class="mb-0 fw-bold">
                        <i class="fas fa-timeline me-2"></i>Order Progress
                    </h5>
                </div>
                <div class="card-body p-4">
                    <div class="timeline">
                        @if($order->delivery_method === 'delivery')
                            <!-- Timeline for Delivery Orders -->
                            <div class="timeline-item {{ in_array($order->order_status, ['to_ship', 'shipped', 'completed']) ? 'completed' : 'pending' }}">
                                <div class="timeline-marker">
                                    <i class="fas fa-box"></i>
                                </div>
                                <div class="timeline-content">
                                    <h6 class="fw-bold">Your order is picked up from the supplier's shop</h6>
                                    <p class="text-muted mb-0">Order has been collected from {{ $order->supplier->company_name }}</p>
                                </div>
                            </div>

                            <div class="timeline-item {{ in_array($order->order_status, ['shipped', 'completed']) ? 'completed' : 'pending' }}">
                                <div class="timeline-marker">
                                    <i class="fas fa-cogs"></i>
                                </div>
                                <div class="timeline-content">
                                    <h6 class="fw-bold">Your order is being processed</h6>
                                    <p class="text-muted mb-0">Order is being prepared for shipment</p>
                                </div>
                            </div>

                            <div class="timeline-item {{ in_array($order->order_status, ['shipped', 'completed']) ? 'completed' : 'pending' }}">
                                <div class="timeline-marker">
                                    <i class="fas fa-truck"></i>
                                </div>
                                <div class="timeline-content">
                                    <h6 class="fw-bold">Your order is on the way to you!</h6>
                                    <p class="text-muted mb-0">Package is out for delivery</p>
                                </div>
                            </div>

                            <div class="timeline-item {{ $order->order_status === 'completed' ? 'completed' : 'pending' }}">
                                <div class="timeline-marker">
                                    <i class="fas fa-check-circle"></i>
                                </div>
                                <div class="timeline-content">
                                    <h6 class="fw-bold">Your order is delivered</h6>
                                    <p class="text-muted mb-0">Package has been successfully delivered</p>
                                </div>
                            </div>
                        @else
                            <!-- Timeline for Self-Pickup Orders -->
                            <div class="timeline-item {{ in_array($order->order_status, ['preparing_to_pickup', 'ready_to_pickup', 'completed']) ? 'completed' : 'pending' }}">
                                <div class="timeline-marker">
                                    <i class="fas fa-box"></i>
                                </div>
                                <div class="timeline-content">
                                    <h6 class="fw-bold">Your order is being prepared</h6>
                                    <p class="text-muted mb-0">{{ $order->supplier->company_name }} is preparing your order</p>
                                </div>
                            </div>

                            <div class="timeline-item {{ in_array($order->order_status, ['ready_to_pickup', 'completed']) ? 'completed' : 'pending' }}">
                                <div class="timeline-marker">
                                    <i class="fas fa-bell"></i>
                                </div>
                                <div class="timeline-content">
                                    <h6 class="fw-bold">Your order is ready for pickup</h6>
                                    <p class="text-muted mb-0">You can now collect your order from the supplier</p>
                                </div>
                            </div>

                            <div class="timeline-item {{ $order->order_status === 'completed' ? 'completed' : 'pending' }}">
                                <div class="timeline-marker">
                                    <i class="fas fa-check-circle"></i>
                                </div>
                                <div class="timeline-content">
                                    <h6 class="fw-bold">Order collected</h6>
                                    <p class="text-muted mb-0">You have successfully collected your order</p>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.timeline {
    position: relative;
    padding-left: 3rem;
}

.timeline::before {
    content: '';
    position: absolute;
    left: 1.5rem;
    top: 0;
    height: 100%;
    width: 3px;
    background: linear-gradient(to bottom, #74b9ff, #0984e3);
}

.timeline-item {
    position: relative;
    margin-bottom: 2rem;
    padding-left: 2rem;
}

.timeline-marker {
    position: absolute;
    left: -2.25rem;
    top: 0;
    width: 3rem;
    height: 3rem;
    background: #e9ecef;
    border: 3px solid #dee2e6;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1rem;
    color: #6c757d;
    z-index: 1;
    transition: all 0.3s ease;
}

.timeline-item.completed .timeline-marker {
    background: linear-gradient(135deg, #00b894, #00a085);
    border-color: #00b894;
    color: white;
    transform: scale(1.1);
}

.timeline-item.completed .timeline-content h6 {
    color: #00b894;
}

.timeline-content {
    background: white;
    padding: 1.5rem;
    border-radius: 0.5rem;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    border-left: 4px solid #e9ecef;
    transition: all 0.3s ease;
}

.timeline-item.completed .timeline-content {
    border-left-color: #00b894;
    transform: translateX(0.25rem);
}

.timeline-content h6 {
    margin-bottom: 0.5rem;
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
</style>
@endsection
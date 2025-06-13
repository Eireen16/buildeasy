@extends('layouts.customer')

@section('content')
<div class="container-fluid mt-3" style="background-color: #a8d8e8; padding: 20px 0;">
    <div class="container">
        <h1 class="mb-4" style="color: #2980b9; font-size: 2.5rem; font-weight: bold; font-family: Arial, sans-serif;">Order History</h1>
        <div class="row justify-content-center">
            <div class="card-header d-flex justify-content-between align-items-center">  
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if($orders->isEmpty())
                        <div class="text-center py-5">
                            <i class="fas fa-history fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">No order history found</h5>
                            <p class="text-muted">You don't have any completed or cancelled orders yet.</p>
                            <a href="{{ url('/customer/materials') }}" class="btn btn-primary mt-2">Start Shopping</a>
                        </div>
                    @else
                        @foreach($orders as $order)
                            <div class="card mb-4 border">
                                <div class="card-header bg-light">
                                    <div class="row align-items-center">
                                        <div class="col-md-6">
                                            <h6 class="mb-0">Order #{{ $order->order_id }}</h6>
                                            <small class="text-muted">{{ $order->created_at->format('M d, Y \a\t g:i A') }}</small>
                                        </div>
                                        <div class="col-md-6 text-md-end">
                                            <span class="badge 
                                                @if($order->order_status === 'completed') badge-success
                                                @elseif($order->order_status === 'cancelled') badge-danger
                                                @else badge-secondary
                                                @endif
                                            ">
                                                {{ ucfirst(str_replace('_', ' ', $order->order_status)) }}
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <div class="card-body">
                                    <!-- Supplier Information -->
                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <h6 class="fw-bold">Supplier Information</h6>
                                            <p class="mb-1"><strong>Company:</strong> {{ $order->supplier->company_name ?? 'N/A' }}</p>
                                            <p class="mb-1"><strong>Phone:</strong> {{ $order->supplier->phone ?? 'N/A' }}</p>
                                        </div>
                                        <div class="col-md-6">
                                            <h6 class="fw-bold">Order Details</h6>
                                            <p class="mb-1"><strong>Delivery Method:</strong> {{ ucfirst(str_replace('-', ' ', $order->delivery_method)) }}</p>
                                            @if($order->delivery_method === 'delivery')
                                                <p class="mb-1"><strong>Delivery Address:</strong> 
                                                    {{ $order->delivery_address }}, {{ $order->delivery_city }}, 
                                                    {{ $order->delivery_state }} {{ $order->delivery_postal_code }}
                                                </p>
                                            @endif
                                        </div>
                                    </div>

                                    <!-- Order Items -->
                                    <h6 class="fw-bold mb-3">Order Items</h6>
                                    <div class="table-responsive">
                                        <table class="table table-sm">
                                            <thead>
                                                <tr>
                                                    <th>Image</th>
                                                    <th>Material</th>
                                                    <th>Variation</th>
                                                    <th>Quantity</th>
                                                    <th>Unit Price</th>
                                                    <th>Subtotal</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($order->orderItems as $orderItem)
                                                    <tr>
                                                        <td>
                                                            @if($orderItem->material)
                                                                <img src="{{ asset($orderItem->material->first_image) }}" 
                                                                    alt="{{ $orderItem->material->name }}" 
                                                                    class="material-image img-fluid rounded"
                                                                    style="width: 100px; height: 100px; object-fit: cover;">
                                                            @else
                                                                <div class="material-image-placeholder" 
                                                                    style="width: 100px; height: 100px; background-color: #f8f9fa; border: 1px solid #dee2e6; border-radius: 5px; display: flex; align-items: center; justify-content: center;">
                                                                    <i class="fas fa-image text-muted"></i>
                                                                </div>
                                                            @endif
                                                        </td>
                                                        <td>{{ $orderItem->material_name }}</td>
                                                        <td>
                                                            @if($orderItem->variation_name && $orderItem->variation_value)
                                                                {{ $orderItem->variation_name }}: {{ $orderItem->variation_value }}
                                                            @else
                                                                -
                                                            @endif
                                                        </td>
                                                        <td>{{ $orderItem->quantity }}</td>
                                                        <td>RM {{ number_format($orderItem->unit_price, 2) }}</td>
                                                        <td>RM {{ number_format($orderItem->subtotal, 2) }}</td>
                                                        <td></td> <!-- Empty cell for alignment -->
                                                    </tr>
                                                    <tr>
                                                        <td colspan="7">
                                                            <div class="d-flex flex-wrap gap-2">
                                                                @if($order->order_status === 'completed')
                                                                    @php
                                                                        $hasReview = $orderItem->review !== null;
                                                                    @endphp

                                                                    @if($hasReview)
                                                                        <button class="btn btn-success btn-sm" disabled>
                                                                            <i class="fas fa-check me-1"></i>Reviewed
                                                                        </button>
                                                                    @else
                                                                        <a href="{{ route('customer.reviews.create', $orderItem->id) }}" 
                                                                        class="btn btn-warning btn-sm">
                                                                            <i class="fas fa-star me-1"></i>Review
                                                                        </a>
                                                                    @endif
                                                                @endif

                                                                <!-- Reorder Button (Always Visible) -->
                                                                <a href="{{ route('customer.materials.show', ['id' => $orderItem->material_id]) }}" 
                                                                class="btn btn-primary btn-sm">
                                                                    <i class="fas fa-redo me-1"></i>Reorder
                                                                </a>
                                                            </div>
                                                        </td>
                                                    </tr>

                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>

                                    <!-- Order Summary -->
                                    <div class="row justify-content-end">
                                        <div class="col-md-4">
                                            <table class="table table-sm">
                                                <tr>
                                                    <td><strong>Subtotal:</strong></td>
                                                    <td class="text-end">RM {{ number_format($order->subtotal, 2) }}</td>
                                                </tr>
                                                <tr>
                                                    <td><strong>Shipping:</strong></td>
                                                    <td class="text-end">RM {{ number_format($order->shipping_cost, 2) }}</td>
                                                </tr>
                                                <tr class="table-active">
                                                    <td><strong>Total:</strong></td>
                                                    <td class="text-end"><strong>RM {{ number_format($order->total, 2) }}</strong></td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.badge-success { background-color: #28a745; }
.badge-danger { background-color: #dc3545; }
.badge-secondary { background-color: #6c757d; }
</style>
@endsection
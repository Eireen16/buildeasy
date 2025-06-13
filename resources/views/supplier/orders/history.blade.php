@extends('layouts.supplier')

@section('content')
<div class="container-fluid mt-3" style="background-color: #a8d8e8; padding: 20px 0;">
    <div class="container">
        <h1 class="mb-4" style="color: #2980b9; font-size: 2.5rem; font-weight: bold; font-family: Arial, sans-serif;">Order History</h1
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
                                    <!-- Customer Information -->
                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <h6 class="fw-bold">Customer Information</h6>
                                            <p class="mb-1"><strong>Name:</strong> {{ $order->delivery_name }}</p>
                                            <p class="mb-1"><strong>Phone:</strong> {{ $order->delivery_phone }}</p>
                                            <p class="mb-1"><strong>Email:</strong> {{ $order->customer->user->email }}</p>
                                        </div>
                                        <div class="col-md-6">
                                            <h6 class="fw-bold">Order Details</h6>
                                            <p class="mb-1"><strong>Delivery Method:</strong> {{ ucfirst(str_replace('-', ' ', $order->delivery_method)) }}</p>
                                            @if($order->delivery_method === 'delivery')
                                                <p class="mb-1"><strong>Address:</strong> 
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
                                                    <th>Images</th>
                                                    <th>Material</th>
                                                    <th>Variation</th>
                                                    <th>Quantity</th>
                                                    <th>Unit Price</th>
                                                    <th>Subtotal</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($order->orderItems as $item)
                                                    <tr>
                                                        <td>
                                                            @if($item->material)
                                                                <img src="{{ asset($item->material->first_image) }}" 
                                                                     alt="{{ $item->material->name }}" 
                                                                     class="material-image img-fluid rounded"
                                                                     style="width: 100px; height: 100px; object-fit: cover;">
                                                            @else
                                                                <div class="material-image-placeholder" 
                                                                     style="width: 100px; height: 100px; background-color: #f8f9fa; border: 1px solid #dee2e6; border-radius: 5px; display: flex; align-items: center; justify-content: center;">
                                                                    <i class="fas fa-image text-muted"></i>
                                                                </div>
                                                            @endif
                                                        </td>
                                                        <td>{{ $item->material_name }}</td>
                                                        <td>
                                                            @if($item->variation_name && $item->variation_value)
                                                                {{ $item->variation_name }}: {{ $item->variation_value }}
                                                            @else
                                                                -
                                                            @endif
                                                        </td>
                                                        <td>{{ $item->quantity }}</td>
                                                        <td>RM {{ number_format($item->unit_price, 2) }}</td>
                                                        <td>RM {{ number_format($item->subtotal, 2) }}</td>
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
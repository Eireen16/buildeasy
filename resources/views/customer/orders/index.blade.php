@extends('layouts.customer')

@section('content')
<div class="container-fluid mt-3" style="background-color: #a8d8e8; padding: 20px 0;">
    <div class="container">
        <h1 class="mb-4" style="color: #2980b9; font-size: 2.5rem; font-weight: bold; font-family: Arial, sans-serif;">My Orders</h1>
        <div class="row justify-content-center">
                    <div class="d-flex justify-content-end mb-3">
                    <a href="{{ route('customer.orders.history') }}" class="btn bg-white text-primary btn-sm">
                        <i class="fas fa-history me-2"></i>Order History
                    </a>
            </div>
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
                            <i class="fas fa-shopping-bag fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">No active orders found</h5>
                            <p class="text-muted">You don't have any pending orders at the moment.</p>
                            <small class="text-muted">Completed orders can be found in Order History.</small>
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
                                                @switch($order->order_status)
                                                    @case('to_ship') badge-warning @break
                                                    @case('shipped') badge-info @break
                                                    @case('preparing_to_pickup') badge-warning @break
                                                    @case('ready_to_pickup') badge-info @break
                                                    @case('completed') badge-success @break
                                                    @case('cancelled') badge-danger @break
                                                    @default badge-secondary
                                                @endswitch
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

                                    <!-- Action Buttons -->
                                    <div class="d-flex gap-2 mt-3">
                                        @if(!in_array($order->order_status, ['completed', 'cancelled', 'shipped', 'ready_to_pickup']))
                                            <!-- Cancel Order Button -->
                                            <form action="{{ route('customer.orders.cancel', $order) }}" method="POST" class="d-inline"
                                                  onsubmit="return confirm('Are you sure you want to cancel this order?')">
                                                @csrf
                                                @method('PUT')
                                                <button type="submit" class="btn btn-danger btn-sm">Cancel Order</button>
                                            </form>
                                        @endif

                                        @if($order->delivery_method === 'delivery' && $order->order_status === 'shipped')
                                            <!-- Track Order Button -->
                                            <a href="{{ route('customer.track.order', $order->order_id) }}" class="btn btn-info btn-sm">
                                                <i class="fas fa-truck me-2"></i>Track Order
                                            </a>
                                        @endif

                                        @if($order->delivery_method === 'self-pickup' && $order->order_status === 'ready_to_pickup')
                                            <!-- Pickup Address Button -->
                                            <a href="{{ route('orders.pickup', $order->id) }}" class="btn btn-success btn-sm">
                                                <i class="fas fa-map-marker-alt me-2"></i>Pickup Address
                                            </a>
                                        @endif
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
.badge-warning { background-color: #ffc107; color: #000; }
.badge-info { background-color: #17a2b8; }
.badge-success { background-color: #28a745; }
.badge-danger { background-color: #dc3545; }
.badge-secondary { background-color: #6c757d; }

</style>
@endsection
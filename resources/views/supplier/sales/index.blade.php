@extends('layouts.supplier')

@section('content')
<div class="container-fluid p-0 mt-3" style="background-color: #a8d8e8; padding: 20px 0;">
    <div class="container pb-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="mb-0 mt-3" style="color: #2980b9; font-size: 2.5rem; font-weight: bold; font-family: Arial, sans-serif;">Sales Report</h1>
            <div class="d-flex gap-2">
                <a href="{{ route('supplier.sales.export', ['month' => $month]) }}" class="btn btn-success">
                    <i class="fas fa-download"></i> Export CSV
                </a>
            </div>
        </div>

        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Filter Sales Data</h6>
            </div>
            <div class="card-body">
                <form method="GET" action="{{ route('supplier.sales.index') }}" class="row">
                    <div class="col-md-4">
                        <label for="month" class="form-label">Select Month</label>
                        <select name="month" id="month" class="form-control">
                            <option value="">All Time</option>
                            @foreach($availableMonths as $monthOption)
                                <option value="{{ $monthOption['value'] }}"
                                    {{ $month == $monthOption['value'] ? 'selected' : '' }}>
                                    {{ $monthOption['label'] }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary">Filter</button>
                    </div>
                </form>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-primary shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                    Total Orders
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalOrders }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-shopping-cart fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-success shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                    Total Revenue
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">RM{{ number_format($totalRevenue, 2) }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-info shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                    Items Sold
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalQuantitySold }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-boxes fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-warning shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                    Average Order Value
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    RM{{ $totalOrders > 0 ? number_format($totalRevenue / $totalOrders, 2) : '0.00' }}
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-chart-line fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card shadow mb-4">
                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                        <h6 class="m-0 font-weight-bold text-primary">Top Selling Materials</h6>
                    </div>
                    <div class="card-body">
                        @if($topMaterials->count() > 0)
                            @foreach($topMaterials as $material)
                                <div class="d-flex align-items-center mb-3 p-2 border rounded">
                                    <div class="flex-shrink-0 me-3">
                                        @if($material->material && $material->material->images && $material->material->images->first())
                                            <img src="{{ asset($material->material->images->first()->image_path) }}"
                                                alt="{{ $material->material_name }}"
                                                class="rounded"
                                                style="width: 50px; height: 50px; object-fit: cover;">
                                        @else
                                            <div class="bg-light rounded d-flex align-items-center justify-content-center"
                                                style="width: 50px; height: 50px;">
                                                <i class="fas fa-image text-muted"></i>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <div class="small text-gray-600 font-weight-bold">{{ $material->material_name }}</div>
                                        <div class="d-flex justify-content-between">
                                            <span class="text-dark small">{{ $material->total_quantity }} units sold</span>
                                            <span class="text-success small font-weight-bold">RM{{ number_format($material->total_revenue, 2) }}</span>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <p class="text-muted">No sales data available for the selected period.</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">
                    Order Details
                    @if($month)
                        - {{ $filterDate->format('F Y') }}
                    @endif
                </h6>
            </div>
            <div class="card-body">
                @if($orders->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-bordered" id="ordersTable" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>Order ID</th>
                                    <th>Customer</th>
                                    <th>Date</th>
                                    <th>Delivery Method</th>
                                    <th>Status</th>
                                    <th>Items & Quantities</th>
                                    <th>Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($orders as $order)
                                    <tr>
                                        <td>{{ $order->order_id }}</td>
                                        <td>{{ $order->customer->user->username }}</td>
                                        <td>{{ $order->created_at->format('M d, Y') }}</td>
                                        <td>
                                            <span class="badge badge-{{ $order->delivery_method == 'delivery' ? 'info' : 'warning' }} text-dark">
                                                {{ ucfirst(str_replace(['_', '-'], ' ', $order->delivery_method)) }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge badge-{{
                                                $order->order_status == 'completed' ? 'success' :
                                                ($order->order_status == 'cancelled' ? 'danger' : 'primary')
                                            }} text-primary">
                                                {{ ucfirst(str_replace('_', ' ', $order->order_status)) }}
                                            </span>
                                        </td>
                                        <td>
                                            @foreach($order->orderItems as $item)
                                                <div class="mb-1">
                                                    <strong>{{ $item->material_name }}</strong>
                                                    @if($item->variation_name)
                                                        <small class="text-muted">({{ $item->variation_name }}: {{ $item->variation_value }})</small>
                                                    @endif
                                                    <span class="badge badge-light text-secondary">Qty: {{ $item->quantity }}</span>
                                                </div>
                                            @endforeach
                                        </td>
                                        <td>RM{{ number_format($order->total, 2) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-4">
                        <p class="text-muted">No orders found for the selected period.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="orderDetailsModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Order Details</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body" id="orderDetailsContent">
                </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// DataTable initialization
$(document).ready(function() {
    $('#ordersTable').DataTable({
        "order": [[ 2, "desc" ]],
        "pageLength": 25,
        "columnDefs": [
            {
                "targets": 5, // Items & Quantities column
                "orderable": false
            }
        ]
    });
});

// Function to show order details
function showOrderDetails(orderId) {
    const orderRow = event.target.closest('tr');
    const orderData = {
        id: orderRow.cells[0].textContent,
        customer: orderRow.cells[1].textContent,
        date: orderRow.cells[2].textContent,
        method: orderRow.cells[3].textContent.trim(),
        status: orderRow.cells[4].textContent.trim(),
        total: orderRow.cells[6].textContent
    };

    const content = `
        <div class="row">
            <div class="col-md-6">
                <strong>Order ID:</strong> ${orderData.id}<br>
                <strong>Customer:</strong> ${orderData.customer}<br>
                <strong>Date:</strong> ${orderData.date}
            </div>
            <div class="col-md-6">
                <strong>Delivery Method:</strong> ${orderData.method}<br>
                <strong>Status:</strong> ${orderData.status}<br>
                <strong>Total:</strong> ${orderData.total}
            </div>
        </div>
        <hr>
        <div class="row">
            <div class="col-12">
                <strong>Order Items:</strong><br>
                ${orderRow.cells[5].innerHTML}
            </div>
        </div>
    `;

    document.getElementById('orderDetailsContent').innerHTML = content;
    $('#orderDetailsModal').modal('show');
}
</script>
@endsection
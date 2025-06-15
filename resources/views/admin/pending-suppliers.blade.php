@extends('layouts.admin')

@section('title', 'Pending Suppliers')
@section('page-title', 'Pending Supplier Approvals')

@section('content')
<div class="row">
    <div class="col-12">
        <!-- Header Section -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h3 class="mb-1">Pending Supplier Approvals</h3>
                <p class="text-muted mb-0">Review and manage supplier account requests</p>
            </div>
            <div class="d-flex gap-2">
                <span class="badge bg-warning text-dark px-3 py-2">
                    <i class="fas fa-clock me-1"></i>
                    {{ $suppliers->count() }} Pending
                </span>
                <button class="btn btn-outline-secondary" onclick="location.reload()">
                    <i class="fas fa-sync-alt me-1"></i>
                    Refresh
                </button>
            </div>
        </div>

        @if($suppliers->count() > 0)
            <!-- Suppliers Grid -->
            <div class="row">
                @foreach($suppliers as $supplier)
                <div class="py-12 max-w-7xl mx-auto sm:px-6 lg:px-8 mb-6">
                    <div class="card border-0 shadow-sm h-100 supplier-card">
                        <div class="card-header bg-light border-0 d-flex justify-content-between align-items-center">
                            <div class="d-flex align-items-center">
                                <div class="user-avatar-lg me-3">
                                    {{ strtoupper(substr($supplier->user->username, 0, 2)) }}
                                </div>
                                <div>
                                    <h6 class="mb-0 fw-bold">{{ $supplier->user->username }}</h6>
                                    <small class="text-muted">
                                        <i class="fas fa-calendar me-1"></i>
                                        Applied {{ $supplier->created_at->diffForHumans() }}
                                    </small>
                                </div>
                            </div>
                            <span class="badge bg-warning text-dark">
                                <i class="fas fa-hourglass-half me-1"></i>
                                Pending
                            </span>
                        </div>
                        
                        <div class="card-body">
                            <!-- Contact Information -->
                            <div class="mb-3">
                                <h6 class="text-primary fw-semibold mb-2">
                                    <i class="fas fa-envelope me-2"></i>
                                    Email
                                </h6>
                                <div class="info-item">
                                    <i class="text-muted"></i>
                                    <span>{{ $supplier->user->email }}</span>
                                </div>
                            </div>

                            <!-- Business Information -->
                            <div class="mb-3">
                                <h6 class="text-primary fw-semibold mb-2">
                                    <i class="fas fa-building me-2"></i>
                                    Business Information
                                </h6>
                                <div class="info-item">
                                    <i class="text-muted"></i>
                                    <span>Company Name: {{ $supplier->company_name }}</span>
                                </div>
                                <div class="info-item">
                                    <i class="text-muted"></i>
                                    <span>License: {{ $supplier->license_number }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="card-footer bg-light border-0">
                            <div class="d-flex gap-2">
                                <!-- Approve Button -->
                                <form action="{{ route('admin.approve.supplier', ['id' => $supplier->id]) }}" method="POST" class="flex-fill">
                                    @csrf
                                    <button type="submit" class="btn btn-success w-100 btn-approve" 
                                            onclick="return confirm('Are you sure you want to approve {{ $supplier->company_name }}?')">
                                        <i class="fas fa-check me-1"></i>
                                        Approve
                                    </button>
                                </form>

                                <!-- View Details Button -->
                                <button type="button" class="btn btn-outline-primary" 
                                        data-bs-toggle="modal" 
                                        data-bs-target="#supplierModal{{ $supplier->id }}">
                                    <i class="fas fa-eye"></i>
                                </button>

                                <!-- Delete Button -->
                                <form action="{{ route('admin.delete.supplier', ['id' => $supplier->id]) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger" 
                                            onclick="return confirm('Are you sure you want to delete {{ $supplier->company_name }}? This action cannot be undone.')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Supplier Details Modal -->
                <div class="modal fade" id="supplierModal{{ $supplier->id }}" tabindex="-1" aria-labelledby="supplierModalLabel{{ $supplier->id }}" aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="supplierModalLabel{{ $supplier->id }}">
                                    <i class="fas fa-building me-2"></i>
                                    Supplier Details
                                </h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <h6 class="text-primary fw-semibold mb-3">User Information</h6>
                                        <table class="table table-borderless table-sm">
                                            <tr>
                                                <td class="text-muted">Username:</td>
                                                <td class="fw-medium">{{ $supplier->user->username }}</td>
                                            </tr>
                                            <tr>
                                                <td class="text-muted">Email:</td>
                                                <td>{{ $supplier->user->email }}</td>
                                            </tr>
                                            <tr>
                                                <td class="text-muted">Joined:</td>
                                                <td>{{ $supplier->user->created_at->format('M d, Y') }}</td>
                                            </tr>
                                        </table>
                                    </div>
                                    <div class="col-md-6">
                                        <h6 class="text-primary fw-semibold mb-3">Business Details</h6>
                                        <table class="table table-borderless table-sm">
                                            <tr>
                                                <td class="text-muted">Company:</td>
                                                <td class="fw-medium">{{ $supplier->company_name }}</td>
                                            </tr>
                                            <tr>
                                                <td class="text-muted">License:</td>
                                                <td>{{ $supplier->license_number }}</td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                <div class="d-flex gap-2">
                                    <form action="{{ route('admin.approve.supplier', ['id' => $supplier->id]) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn btn-success"
                                                onclick="return confirm('Approve {{ $supplier->company_name }}?')">
                                            <i class="fas fa-check me-1"></i>
                                            Approve Supplier
                                        </button>
                                    </form>
                                    <form action="{{ route('admin.delete.supplier', ['id' => $supplier->id]) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger"
                                                onclick="return confirm('Delete {{ $supplier->company_name }}? This cannot be undone.')">
                                            <i class="fas fa-trash me-1"></i>
                                            Delete
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <!-- Pagination if needed -->
            @if(method_exists($suppliers, 'links'))
            <div class="d-flex justify-content-center mt-4">
                {{ $suppliers->links() }}
            </div>
            @endif

        @else
            <!-- Empty State -->
            <div class="text-center py-5">
                <div class="empty-state">
                    <i class="fas fa-user-check fa-4x text-muted mb-3"></i>
                    <h4 class="text-muted">No Pending Suppliers</h4>
                    <p class="text-muted mb-4">All supplier applications have been processed.</p>
                </div>
            </div>
        @endif
    </div>
</div>

<style>
.supplier-card {
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}

.supplier-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 20px rgba(0,0,0,0.1) !important;
}

.user-avatar-lg {
    width: 45px;
    height: 45px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-weight: bold;
    font-size: 14px;
}

.info-item {
    display: flex;
    align-items: center;
    margin-bottom: 8px;
    font-size: 14px;
}

.info-item:last-child {
    margin-bottom: 0;
}

.btn-approve {
    background: linear-gradient(135deg, #28a745, #20c997);
    border: none;
    transition: all 0.3s ease;
}

.btn-approve:hover {
    background: linear-gradient(135deg, #218838, #1ea080);
    transform: translateY(-1px);
}

.empty-state {
    max-width: 400px;
    margin: 0 auto;
}

.badge {
    font-size: 11px;
}

.card-header {
    padding: 1rem 1.25rem 0.75rem;
}

.modal-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
}

.modal-header .btn-close {
    filter: invert(1);
}
</style>
@endsection
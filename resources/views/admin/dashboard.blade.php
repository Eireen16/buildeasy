@extends('layouts.admin')

@section('title', 'Admin Dashboard')

@section('content')
<div class="container-fluid">
    <!-- Dashboard Stats Row -->
    <div class="row mb-4">
        <div class="col-lg-3 col-md-6">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4>{{ $totalUsers }}</h4>
                            <p class="mb-0">Total Users</p>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-users fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-3 col-md-6">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4>{{ $totalCustomers }}</h4>
                            <p class="mb-0">Customers</p>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-user-friends fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-3 col-md-6">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4>{{ $totalSuppliers }}</h4>
                            <p class="mb-0">Suppliers</p>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-building fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-3 col-md-6">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4>{{ $pendingSuppliers }}</h4>
                            <p class="mb-0">Pending Approvals</p>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-clock fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- User Management Section -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">User Management</h3>
                </div>
                
                <div class="card-body">
                    <!-- Search Form -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <form method="GET" action="{{ route('admin.dashboard') }}" class="d-flex">
                                <input type="text" 
                                       name="search" 
                                       class="form-control me-2" 
                                       placeholder="Search by User ID, Username, Email, or Name"
                                       value="{{ $search }}">
                                <button type="submit" class="btn btn-primary">Search</button>
                                @if($search)
                                    <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary ms-2">Clear</a>
                                @endif
                            </form>
                        </div>
                    </div>

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

                    <!-- Navigation Tabs -->
                    <ul class="nav nav-tabs mb-3" id="userTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="suppliers-tab" data-bs-toggle="tab" data-bs-target="#suppliers" type="button" role="tab">
                                <i class="fas fa-building me-2"></i>Suppliers <span class="badge bg-primary ms-1">{{ $suppliers->count() }}</span>
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="customers-tab" data-bs-toggle="tab" data-bs-target="#customers" type="button" role="tab">
                                <i class="fas fa-users me-2"></i>Customers <span class="badge bg-success ms-1">{{ $customers->count() }}</span>
                            </button>
                        </li>
                    </ul>

                    <!-- Tab Content -->
                    <div class="tab-content" id="userTabsContent">
                        <!-- Suppliers Tab -->
                        <div class="tab-pane fade show active" id="suppliers" role="tabpanel">
                            @if($suppliers->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-striped table-hover">
                                        <thead class="table-dark">
                                            <tr>
                                                <th>User ID</th>
                                                <th>Profile</th>
                                                <th>Username</th>
                                                <th>Email</th>
                                                <th>Company Name</th>
                                                <th>Phone</th>
                                                <th>Status</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($suppliers as $supplierUser)
                                                <tr>
                                                    <td>{{ $supplierUser->id }}</td>
                                                    <td>
                                                        @if($supplierUser->supplier && $supplierUser->supplier->profile_picture)
                                                            <img src="{{ $supplierUser->supplier->profile_picture }}" 
                                                                 alt="Profile" 
                                                                 class="rounded-circle" 
                                                                 width="40" height="40">
                                                        @else
                                                            <div class="bg-secondary rounded-circle d-flex align-items-center justify-content-center" 
                                                                 style="width: 40px; height: 40px;">
                                                                <i class="fas fa-user text-white"></i>
                                                            </div>
                                                        @endif
                                                    </td>
                                                    <td>{{ $supplierUser->username }}</td>
                                                    <td>{{ $supplierUser->email }}</td>
                                                    <td>{{ $supplierUser->supplier->company_name ?? 'N/A' }}</td>
                                                    <td>{{ $supplierUser->supplier->phone ?? 'N/A' }}</td>
                                                    <td>
                                                        @if($supplierUser->supplier && $supplierUser->supplier->is_approved)
                                                            <span class="badge bg-success">Approved</span>
                                                        @else
                                                            <span class="badge bg-warning">Pending</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <div class="btn-group" role="group">
                                                            <a href="{{ route('admin.view-user', $supplierUser->id) }}" 
                                                               class="btn btn-sm btn-info">
                                                                <i class="fas fa-eye"></i> View
                                                            </a>
                                                            <button type="button" 
                                                                    class="btn btn-sm btn-danger" 
                                                                    onclick="confirmDelete({{ $supplierUser->id }}, '{{ $supplierUser->username }}')">
                                                                <i class="fas fa-trash"></i> Delete
                                                            </button>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div class="alert alert-info">
                                    <i class="fas fa-info-circle me-2"></i>No suppliers found.
                                </div>
                            @endif
                        </div>

                        <!-- Customers Tab -->
                        <div class="tab-pane fade" id="customers" role="tabpanel">
                            @if($customers->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-striped table-hover">
                                        <thead class="table-dark">
                                            <tr>
                                                <th>User ID</th>
                                                <th>Profile</th>
                                                <th>Username</th>
                                                <th>Email</th>
                                                <th>Name</th>
                                                <th>Phone</th>
                                                <th>Joined</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($customers as $customerUser)
                                                <tr>
                                                    <td>{{ $customerUser->id }}</td>
                                                    <td>
                                                        @if($customerUser->customer && $customerUser->customer->profile_picture)
                                                            <img src="{{ $customerUser->customer->profile_picture }}" 
                                                                 alt="Profile" 
                                                                 class="rounded-circle" 
                                                                 width="40" height="40">
                                                        @else
                                                            <div class="bg-secondary rounded-circle d-flex align-items-center justify-content-center" 
                                                                 style="width: 40px; height: 40px;">
                                                                <i class="fas fa-user text-white"></i>
                                                            </div>
                                                        @endif
                                                    </td>
                                                    <td>{{ $customerUser->username }}</td>
                                                    <td>{{ $customerUser->email }}</td>
                                                    <td>{{ $customerUser->customer->name ?? 'N/A' }}</td>
                                                    <td>{{ $customerUser->customer->phone ?? 'N/A' }}</td>
                                                    <td>{{ $customerUser->created_at->format('M d, Y') }}</td>
                                                    <td>
                                                        <div class="btn-group" role="group">
                                                            <a href="{{ route('admin.view-user', $customerUser->id) }}" 
                                                               class="btn btn-sm btn-info">
                                                                <i class="fas fa-eye"></i> View
                                                            </a>
                                                            <button type="button" 
                                                                    class="btn btn-sm btn-danger" 
                                                                    onclick="confirmDelete({{ $customerUser->id }}, '{{ $customerUser->username }}')">
                                                                <i class="fas fa-trash"></i> Delete
                                                            </button>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div class="alert alert-info">
                                    <i class="fas fa-info-circle me-2"></i>No customers found.
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirm Delete</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete the user <strong id="deleteUsername"></strong>?</p>
                <p class="text-danger"><small>This action cannot be undone and will delete all associated data.</small></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form id="deleteForm" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Delete User</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function confirmDelete(userId, username) {
    document.getElementById('deleteUsername').textContent = username;
    document.getElementById('deleteForm').action = '/users/' + userId;
    var deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
    deleteModal.show();
}
</script>
@endsection
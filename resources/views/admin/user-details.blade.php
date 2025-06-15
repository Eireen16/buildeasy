@extends('layouts.admin')

@section('title', 'User Details')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">User Details - {{ $user->username }}</h3>
                    <a href="{{ route('admin.view-users') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Back to Users
                    </a>
                </div>
                
                <div class="card-body">
                    <div class="row">
                        <!-- User Information -->
                        <div class="col-md-6">
                            <div class="card mb-4">
                                <div class="card-header">
                                    <h5><i class="fas fa-user"></i> User Information</h5>
                                </div>
                                <div class="card-body">
                                    <table class="table table-borderless">
                                        <tr>
                                            <td><strong>User ID:</strong></td>
                                            <td>{{ $user->id }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Username:</strong></td>
                                            <td>{{ $user->username }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Email:</strong></td>
                                            <td>{{ $user->email }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Role:</strong></td>
                                            <td>
                                                <span class="badge bg-{{ $user->role === 'supplier' ? 'primary' : 'success' }}">
                                                    {{ ucfirst($user->role) }}
                                                </span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><strong>Joined:</strong></td>
                                            <td>{{ $user->created_at->format('M d, Y') }}</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <!-- Profile Picture -->
                        <div class="col-md-6">
                            <div class="card mb-4">
                                <div class="card-header">
                                    <h5><i class="fas fa-image"></i> Profile Picture</h5>
                                </div>
                                <div class="card-body text-center">
                                    @php
                                        $profilePicture = null;
                                        if ($user->role === 'supplier' && $user->supplier) {
                                            $profilePicture = $user->supplier->profile_picture;
                                        } elseif ($user->role === 'customer' && $user->customer) {
                                            $profilePicture = $user->customer->profile_picture;
                                        }
                                    @endphp
                                    
                                    @if($profilePicture)
                                        <img src="{{ $profilePicture }}" 
                                             alt="Profile Picture" 
                                             class="img-fluid rounded-circle" 
                                             style="max-width: 200px; max-height: 200px;">
                                    @else
                                        <div class="bg-secondary rounded-circle d-flex align-items-center justify-content-center mx-auto" 
                                             style="width: 150px; height: 150px;">
                                            <i class="fas fa-user fa-4x text-white"></i>
                                        </div>
                                        <p class="mt-2 text-muted">No profile picture uploaded</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Role-specific Information -->
                    @if($user->role === 'supplier' && $user->supplier)
                        <div class="row">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h5><i class="fas fa-building"></i> Supplier Information</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <table class="table table-borderless">
                                                    <tr>
                                                        <td><strong>Company Name:</strong></td>
                                                        <td>{{ $user->supplier->company_name ?? 'N/A' }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td><strong>License Number:</strong></td>
                                                        <td>{{ $user->supplier->license_number ?? 'N/A' }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td><strong>Phone:</strong></td>
                                                        <td>{{ $user->supplier->phone ?? 'N/A' }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td><strong>Location:</strong></td>
                                                        <td>{{ $user->supplier->location ?? 'N/A' }}</td>
                                                    </tr>
                                                </table>
                                            </div>
                                            <div class="col-md-6">
                                                <table class="table table-borderless">
                                                    <tr>
                                                        <td><strong>Address:</strong></td>
                                                        <td>{{ $user->supplier->address ?? 'N/A' }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td><strong>Bank Details:</strong></td>
                                                        <td>{{ $user->supplier->bank_details ?? 'N/A' }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td><strong>Approval Status:</strong></td>
                                                        <td>
                                                            @if($user->supplier->is_approved)
                                                                <span class="badge bg-success">Approved</span>
                                                            @else
                                                                <span class="badge bg-warning">Pending Approval</span>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @elseif($user->role === 'customer' && $user->customer)
                        <div class="row">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h5><i class="fas fa-user-circle"></i> Customer Information</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <table class="table table-borderless">
                                                    <tr>
                                                        <td><strong>Name:</strong></td>
                                                        <td>{{ $user->customer->name ?? 'N/A' }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td><strong>Phone:</strong></td>
                                                        <td>{{ $user->customer->phone ?? 'N/A' }}</td>
                                                    </tr>
                                                </table>
                                            </div>
                                            <div class="col-md-6">
                                                <table class="table table-borderless">
                                                    <tr>
                                                        <td><strong>Address:</strong></td>
                                                        <td>{{ $user->customer->address ?? 'N/A' }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td><strong>Bank Details:</strong></td>
                                                        <td>{{ $user->customer->bank_details ?? 'N/A' }}</td>
                                                    </tr>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Action Buttons -->
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="d-flex justify-content-end">
                                <button type="button" 
                                        class="btn btn-danger" 
                                        onclick="confirmDelete({{ $user->id }}, '{{ $user->username }}')">
                                    <i class="fas fa-trash"></i> Delete User
                                </button>
                            </div>
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
    document.getElementById('deleteForm').action = '/admin/users/' + userId;
    var deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
    deleteModal.show();
}
</script>
@endsection
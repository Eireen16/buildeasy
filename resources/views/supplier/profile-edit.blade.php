@extends('layouts.supplier')

@section('content')
<div class="container-fluid p-0">
    <div style="background-color: #a8d8e8; padding: 20px 0;">
        <div class="container">
            <h1 class="mb-4" style="color: #2980b9; font-size: 2.5rem; font-weight: bold; font-family: Arial, sans-serif;">Edit Profile</h1>

            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('supplier.profile.update') }}" method="POST" enctype="multipart/form-data" class="card bg-white shadow-sm p-4">
                @csrf
                @method('PUT')

                <!-- Profile Picture -->
                <div class="text-center mb-4">
                    <div class="mx-auto mb-2" style="width: 100px; height: 100px; border-radius: 50%; overflow: hidden; background-color: #f8f9fa; border: 1px solid #dee2e6;">
                        <img src="{{ $supplier->profile_picture ?? asset('images/DefaultProfile.png') }}" 
                             alt="Profile Picture" 
                             class="w-100 h-100 object-fit-cover"
                             onerror="this.src='{{ asset('images/DefaultProfile.png') }}'">
                    </div>
                    <input type="file" name="profile_picture" class="form-control mt-2">
                </div>

                <!-- Username -->
                <div class="mb-3">
                    <label for="username" class="form-label">Username</label>
                    <input type="text" name="username" id="username" class="form-control"
                           value="{{ old('username', $user->username) }}">
                </div>

                <!-- Company Name -->
                <div class="mb-3">
                    <label for="company_name" class="form-label">Company Name</label>
                    <input type="text" name="company_name" id="company_name" class="form-control"
                           value="{{ old('company_name', $supplier->company_name) }}">
                </div>

                <!-- Phone -->
                <div class="mb-3">
                    <label for="phone" class="form-label">Phone Number</label>
                    <input type="text" name="phone" id="phone" class="form-control"
                           value="{{ old('phone', $supplier->phone) }}">
                </div>

                <!-- Email -->
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" name="email" id="email" class="form-control"
                           value="{{ old('email', $user->email) }}">
                </div>

                <!-- Address -->
                <div class="mb-3">
                    <label for="address" class="form-label">Shop Address</label>
                    <input type="text" name="address" id="address" class="form-control"
                           value="{{ old('address', $supplier->address) }}">
                </div>

                <!-- Location -->
                <div class="mb-3">
                    <label for="location" class="form-label">Location</label>
                    <input type="text" name="location" id="location" class="form-control"
                           value="{{ old('location', $supplier->location) }}">
                </div>

                <!-- Bank Details -->
                <div class="mb-3">
                    <label for="bank_details" class="form-label">Bank Details</label>
                    <input type="text" name="bank_details" id="bank_details" class="form-control"
                           value="{{ old('bank_details', $supplier->bank_details) }}">
                </div>

                <!-- Password -->
                <div class="mb-3">
                    <label for="password" class="form-label">New Password</label>
                    <input type="password" name="password" id="password" class="form-control">
                </div>

                <div class="mb-4">
                    <label for="password_confirmation" class="form-label">Confirm New Password</label>
                    <input type="password" name="password_confirmation" id="password_confirmation" class="form-control">
                </div>

                <div class="text-end">
                    <button type="submit" class="btn text-white px-4 py-2" style="background-color: #2980b9; border-radius: 4px;">
                        Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

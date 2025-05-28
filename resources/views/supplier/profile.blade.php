@extends('layouts.supplier')

@section('content')
<div class="container-fluid p-0 mt-3">
    <div style="background-color: #a8d8e8; padding: 20px 0;">
        <div class="container">
            <h1 class="mb-4" style="color: #2980b9; font-size: 2.5rem; font-weight: bold; font-family: Arial, sans-serif;">My Profile</h1>
            
            <div class="card bg-white shadow-sm p-4 mb-4">
                <div class="text-center mb-4">
                    <div class="mx-auto" style="width: 100px; height: 100px; border-radius: 50%; overflow: hidden; background-color: #f8f9fa; border: 1px solid #dee2e6;">
                        <img src="{{ $supplier->profile_picture ?? asset('images/DefaultProfile.png') }}" 
                             alt="Profile Picture" 
                             class="w-100 h-100 object-fit-cover"
                             onerror="this.src='{{ asset('images/DefaultProfile.png') }}'">
                    </div>
                    <h3 class="mt-3 mb-4" style="font-weight: bold;">username: {{ $user->username }}</h3>
                </div>

                <div class="mb-4">
                    <label for="companyName" class="form-label">Company Name</label>
                    <input type="text" class="form-control" id="companyName" 
                           value="{{ $supplier->company_name ?? '' }}" 
                           {{ isset($editMode) && $editMode ? '' : 'readonly' }}>
                </div>

                <div class="mb-4">
                    <label for="phoneNumber" class="form-label">Phone number</label>
                    <input type="text" class="form-control" id="phoneNumber" 
                           value="{{ $supplier->phone ?? 'Please set up your phone number' }}" 
                           {{ isset($editMode) && $editMode ? '' : 'readonly' }}>
                </div>

                <div class="mb-4">
                    <label for="email" class="form-label">Email</label>
                    <input type="text" class="form-control" id="email" 
                           value="{{ $user->email ?? '' }}" 
                           {{ isset($editMode) && $editMode ? '' : 'readonly' }}>
                </div>

                <div class="mb-4">
                    <label for="shopAddress" class="form-label">Shop Address</label>
                    <input type="text" class="form-control" id="shopAddress" 
                           value="{{ $supplier->address ?? 'Please set up your address' }}" 
                           {{ isset($editMode) && $editMode ? '' : 'readonly' }}>
                </div>

                <div class="mb-4">
                    <label for="location" class="form-label">Location</label>
                    <input type="text" class="form-control" id="location" 
                           value="{{ $supplier->location ?? 'Please set up your location' }}" 
                           {{ isset($editMode) && $editMode ? '' : 'readonly' }}>
                </div>

                <div class="mb-4">
                    <label for="bankDetails" class="form-label">Bank Details</label>
                    <input type="text" class="form-control" id="bankDetails" 
                           value="{{ $supplier->bank_details ?? 'Please set up your bank details' }}" 
                           {{ isset($editMode) && $editMode ? '' : 'readonly' }}>
                </div>

                <div class="text-center">
                    <a href="{{ route('supplier.profile.edit') }}" class="btn text-white px-4 py-2" style="background-color: #2980b9; border-radius: 4px;">
                        Update Profile
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
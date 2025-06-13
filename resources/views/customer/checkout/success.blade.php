@extends('layouts.customer')

@section('content')

<div class="container-fluid mt-3" style="background-color: #a8d8e8; padding: 20px 0;">
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card text-center mt-3">
                <div class="card-body mt-3">
                    <div class="mb-4">
                        <i class="fas fa-check-circle text-success" style="font-size: 4rem;"></i>
                    </div>
                    <h2 class="card-title text-success">Payment Successful!</h2>
                    <p class="card-text">
                        Thank you for your order. Your payment has been processed successfully and your order has been placed.
                    </p>
                    <p class="text-muted">
                        You will receive an email confirmation shortly with your order details.
                    </p>
                    <div class="mt-4">
                        <a href="{{ route('customer.dashboard') }}" class="btn btn-outline-primary">
                            Continue Shopping
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>

@endsection
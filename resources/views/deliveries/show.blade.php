@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4">
        <h1 class="display-6 fw-bold text-primary mb-3 mb-md-0">
            <i class="bi bi-truck-flatbed me-2"></i>Delivery Details
        </h1>
        <a href="{{ route('deliveries.index') }}" class="btn btn-outline-secondary w-100 w-md-auto">
            <i class="bi bi-arrow-left me-1"></i> Back to Deliveries
        </a>
    </div>

    <div class="row">
        <div class="col-12 col-lg-8">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Order #{{ $order->id }}</h5>
                    <span class="badge {{ $order->order_status == 'completed' ? 'bg-success' : ($order->order_status == 'pending' ? 'bg-warning text-dark' : 'bg-danger') }}">
                        {{ ucfirst($order->order_status) }}
                    </span>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-12 col-md-6 mb-4 mb-md-0">
                            <h6 class="text-muted mb-3">Customer Information</h6>
                            <div class="d-flex align-items-start">
                                <div class="avatar-circle bg-primary me-3" style="flex-shrink: 0;">
                                    {{ substr($order->customer->name, 0, 1) }}
                                </div>
                                <div>
                                    <h6 class="mb-1">{{ $order->customer->name }}</h6>
                                    <div><i class="bi bi-telephone me-1 text-muted"></i> {{ $order->customer->phone }}</div>
                                    <div><i class="bi bi-geo-alt me-1 text-muted"></i> {{ $order->customer->address }}</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <h6 class="text-muted mb-3">Delivery Details</h6>
                            <ul class="list-unstyled">
                                <li class="mb-2">
                                    <span class="text-muted me-1">Assigned To:</span>
                                    {{ $order->deliveryPerson->name ?? 'Not Assigned' }}
                                </li>
                                <li class="mb-2">
                                    <span class="text-muted me-1">Order Date:</span>
                                    {{ $order->created_at->format('M d, Y h:i A') }}
                                </li>
                                @if($order->order_status == 'completed' && $order->delivery_date)
                                <li class="mb-2">
                                    <span class="text-muted me-1">Delivered On:</span>
                                    {{ Carbon\Carbon::parse($order->delivery_date)->format('M d, Y h:i A') }}
                                </li>
                                @endif
                                @if($order->notes)
                                <li class="mb-2">
                                    <span class="text-muted me-1">Notes:</span>
                                    {{ $order->notes }}
                                </li>
                                @endif
                            </ul>
                        </div>
                    </div>

                    <hr>

                    <h6 class="text-muted mb-3">Order Summary</h6>
                    
                    <div class="table-responsive">
                        <table class="table table-borderless">
                            <tbody>
                                <tr>
                                    <td width="60%">Water ({{ $order->quantity }} x ₱{{ number_format($order->water_price, 2) }})</td>
                                    <td class="text-end">₱{{ number_format($order->quantity * $order->water_price, 2) }}</td>
                                </tr>
                                @if($order->is_delivery)
                                <tr>
                                    <td>Delivery Fee ({{ $order->quantity }} x ₱{{ number_format($order->delivery_fee, 2) }})</td>
                                    <td class="text-end">₱{{ number_format($order->quantity * $order->delivery_fee, 2) }}</td>
                                </tr>
                                @endif
                                <tr>
                                    <th>Total</th>
                                    <th class="text-end text-primary fs-5">₱{{ number_format($order->total_amount, 2) }}</th>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="alert {{ $order->payment_status == 'paid' ? 'alert-success' : 'alert-warning' }} d-flex align-items-center">
                        <i class="{{ $order->payment_status == 'paid' ? 'bi bi-check-circle-fill' : 'bi bi-exclamation-triangle-fill' }} me-2"></i>
                        <div>
                            <strong>{{ $order->payment_status == 'paid' ? 'Paid' : 'Payment Pending' }}</strong>
                            @if($order->payment_status == 'paid')
                                <div>Payment Method: {{ ucfirst($order->payment_method) }}</div>
                                @if($order->payment_reference)
                                <div>Reference: {{ $order->payment_reference }}</div>
                                @endif
                            @else
                                <div>Please collect payment upon delivery</div>
                            @endif
                        </div>
                    </div>
                </div>
                
                @if($order->order_status == 'pending')
                <div class="card-footer bg-white">
                    @if(auth()->user()->isDelivery())
                    <form action="{{ route('deliveries.complete', $order->id) }}" method="POST">
                        @csrf
                        <div class="row g-3">
                            @if($order->payment_status == 'unpaid')
                            <div class="col-12 col-md-6 mb-3 mb-md-0">
                                <label for="payment_method" class="form-label">Payment Method</label>
                                <select class="form-select" name="payment_method" id="payment_method" required>
                                    <option value="">Select payment method</option>
                                    <option value="cash">Cash</option>
                                    <option value="gcash">GCash</option>
                                </select>
                            </div>
                            <div class="col-md-6" id="referenceContainer" style="display: none;">
                                <label for="payment_reference" class="form-label">Reference Number</label>
                                <input type="text" class="form-control" name="payment_reference" id="payment_reference">
                            </div>
                            @endif
                            <div class="col-12">
                                <div class="d-grid">
                                    <button type="submit" class="btn btn-primary btn-lg">
                                        <i class="bi bi-check2-circle me-2"></i>Mark as Delivered{{ $order->payment_status == 'unpaid' ? ' & Collected Payment' : '' }}
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                    @else
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <span class="text-muted">Status:</span>
                            <span class="badge bg-warning text-dark ms-1">Awaiting Delivery</span>
                        </div>
                        <form action="{{ route('deliveries.cancel', $order->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to cancel this delivery?');">
                            @csrf
                            <button type="submit" class="btn btn-danger">
                                <i class="bi bi-x-circle me-1"></i> Cancel Delivery
                            </button>
                        </form>
                    </div>
                    @endif
                </div>
                @endif
            </div>
        </div>
        
        <div class="col-12 col-lg-4 mt-4 mt-lg-0">
            <!-- Delivery Map Card -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white">
                    <h5 class="card-title mb-0">Delivery Location</h5>
                </div>
                <div class="card-body p-0">
                    <div class="ratio ratio-4x3">
                        <div id="map" class="bg-light d-flex align-items-center justify-content-center">
                            <p class="text-muted mb-0">Map will be displayed here</p>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-white">
                    <button type="button" class="btn btn-outline-primary btn-sm w-100" disabled style="cursor: not-allowed; opacity: 0.6;">
                        <i class="bi bi-map me-1"></i> Open in Google Maps
                    </button>
                </div>
            </div>
            
            <!-- Customer History Card -->
            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="card-title mb-0">Customer History</h5>
                </div>
                <div class="card-body">
                    <p class="mb-2">
                        <span class="text-muted">Total Orders:</span>
                        <span class="ms-1 fw-bold">{{ $order->customer->orders->count() }}</span>
                    </p>
                    <p class="mb-3">
                        <span class="text-muted">Since:</span>
                        <span class="ms-1">{{ $order->customer->created_at->format('M d, Y') }}</span>
                    </p>
                    
                    <a href="{{ route('customers.show', $order->customer_id) }}" class="btn btn-outline-primary btn-sm w-100">
                        <i class="bi bi-person me-1"></i> View Customer Profile
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const paymentMethod = document.getElementById('payment_method');
    const referenceContainer = document.getElementById('referenceContainer');
    const paymentReference = document.getElementById('payment_reference');
    
    if (paymentMethod) {
        paymentMethod.addEventListener('change', function() {
            if (this.value === 'gcash') {
                referenceContainer.style.display = 'block';
                paymentReference.required = true;
            } else {
                referenceContainer.style.display = 'none';
                paymentReference.required = false;
            }
        });
    }
});
</script>
@endsection
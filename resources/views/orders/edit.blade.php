@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="display-6 fw-bold text-primary">
            <i class="bi bi-pencil-square me-2"></i>Edit Order #{{ $order->id }}
        </h1>
        <a href="{{ route('orders.show', $order->id) }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i> Back to Order
        </a>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <div class="d-flex">
                <div class="me-3">
                    <i class="bi bi-exclamation-triangle-fill fs-3"></i>
                </div>
                <div>
                    <h5 class="alert-heading">Please fix the following errors:</h5>
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card shadow-sm">
        <div class="card-body">
            <form method="POST" action="{{ route('orders.update', $order->id) }}">
                @csrf
                @method('PUT')
                
                <div class="row g-4">
                    <div class="col-12 col-md-6">
                        <h5 class="card-title text-primary mb-3">Order Details</h5>
                        
                        <div class="mb-3">
                            <label for="customer_id" class="form-label">Customer <span class="text-danger">*</span></label>
                            <select class="form-select @error('customer_id') is-invalid @enderror" id="customer_id" name="customer_id" required>
                                @foreach($customers as $customer)
                                    <option value="{{ $customer->id }}" {{ old('customer_id', $order->customer_id) == $customer->id ? 'selected' : '' }}>
                                        {{ $customer->name }} ({{ $customer->phone }})
                                    </option>
                                @endforeach
                            </select>
                            @error('customer_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="order_status" class="form-label">Order Status <span class="text-danger">*</span></label>
                            <select class="form-select @error('order_status') is-invalid @enderror" id="order_status" name="order_status" required>
                                <option value="pending" {{ old('order_status', $order->order_status) == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="completed" {{ old('order_status', $order->order_status) == 'completed' ? 'selected' : '' }}>Completed</option>
                                <option value="cancelled" {{ old('order_status', $order->order_status) == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                            </select>
                            @error('order_status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        @if($order->is_delivery)
                        <div class="mb-3">
                            <label for="delivery_user_id" class="form-label">Delivery Personnel</label>
                            <select class="form-select @error('delivery_user_id') is-invalid @enderror" id="delivery_user_id" name="delivery_user_id">
                                <option value="">Select delivery personnel</option>
                                @foreach($deliveryPersonnel as $personnel)
                                    <option value="{{ $personnel->id }}" {{ old('delivery_user_id', $order->delivery_user_id) == $personnel->id ? 'selected' : '' }}>
                                        {{ $personnel->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('delivery_user_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        @endif
                        
                        <div class="mb-3">
                            <label for="notes" class="form-label">Notes</label>
                            <textarea class="form-control @error('notes') is-invalid @enderror" id="notes" name="notes" rows="3">{{ old('notes', $order->notes) }}</textarea>
                            @error('notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="col-12 col-md-6">
                        <h5 class="card-title text-primary mb-3">Payment Details</h5>
                        
                        <div class="mb-3">
                            <label for="payment_status" class="form-label">Payment Status <span class="text-danger">*</span></label>
                            <select class="form-select @error('payment_status') is-invalid @enderror" id="payment_status" name="payment_status" required>
                                <option value="paid" {{ old('payment_status', $order->payment_status) == 'paid' ? 'selected' : '' }}>Paid</option>
                                <option value="unpaid" {{ old('payment_status', $order->payment_status) == 'unpaid' ? 'selected' : '' }}>Unpaid</option>
                            </select>
                            @error('payment_status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div id="paymentMethodSection" class="{{ old('payment_status', $order->payment_status) == 'unpaid' ? 'd-none' : '' }}">
                            <div class="mb-3">
                                <label for="payment_method" class="form-label">Payment Method <span class="text-danger">*</span></label>
                                <select class="form-select @error('payment_method') is-invalid @enderror" id="payment_method" name="payment_method">
                                    <option value="">Select payment method</option>
                                    <option value="cash" {{ old('payment_method', $order->payment_method) == 'cash' ? 'selected' : '' }}>Cash</option>
                                    <option value="gcash" {{ old('payment_method', $order->payment_method) == 'gcash' ? 'selected' : '' }}>GCash</option>
                                </select>
                                @error('payment_method')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div id="paymentReferenceSection" class="{{ old('payment_method', $order->payment_method) != 'gcash' ? 'd-none' : '' }}">
                                <div class="mb-3">
                                    <label for="payment_reference" class="form-label">Payment Reference Number</label>
                                    <input type="text" class="form-control @error('payment_reference') is-invalid @enderror" id="payment_reference" name="payment_reference" value="{{ old('payment_reference', $order->payment_reference) }}">
                                    @error('payment_reference')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <div class="alert alert-info mt-4">
                            <div class="d-flex">
                                <i class="bi bi-info-circle-fill me-2"></i>
                                <div>
                                    <h6 class="mb-1">Order Summary</h6>
                                    <div class="mb-1">Quantity: {{ $order->quantity }} container(s)</div>
                                    <div class="mb-1">Water Price: ₱{{ number_format($order->water_price, 2) }} each</div>
                                    @if($order->is_delivery)
                                    <div class="mb-1">
                                        Delivery Fee: 
                                        @if($order->delivery_fee == 0)
                                            <span class="text-success fw-bold">FREE</span>
                                            @if($order->quantity >= 3)
                                                <small class="text-muted">(3+ containers)</small>
                                            @endif
                                        @else
                                            ₱{{ number_format($order->delivery_fee, 2) }} each
                                        @endif
                                    </div>
                                    @endif
                                    <div class="mb-1">
                                        <strong>Total Amount: ₱{{ number_format($order->total_amount, 2) }}</strong>
                                    </div>
                                    <div class="mt-2 small text-muted">
                                        Note: To modify quantities or prices, please create a new order.
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <hr class="my-4">
                
                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('orders.show', $order->id) }}" class="btn btn-outline-secondary">
                        Cancel
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save me-1"></i> Update Order
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const paymentStatus = document.getElementById('payment_status');
    const paymentMethodSection = document.getElementById('paymentMethodSection');
    const paymentMethod = document.getElementById('payment_method');
    const paymentReferenceSection = document.getElementById('paymentReferenceSection');
    
    paymentStatus.addEventListener('change', function() {
        if (this.value === 'paid') {
            paymentMethodSection.classList.remove('d-none');
            paymentMethod.setAttribute('required', 'required');
        } else {
            paymentMethodSection.classList.add('d-none');
            paymentMethod.removeAttribute('required');
            // Add hidden field for payment method 'none'
            if (!document.getElementById('payment_method_hidden')) {
                const hiddenInput = document.createElement('input');
                hiddenInput.type = 'hidden';
                hiddenInput.id = 'payment_method_hidden';
                hiddenInput.name = 'payment_method';
                hiddenInput.value = 'none';
                paymentMethodSection.appendChild(hiddenInput);
            }
        }
    });
    
    paymentMethod.addEventListener('change', function() {
        if (this.value === 'gcash') {
            paymentReferenceSection.classList.remove('d-none');
            document.getElementById('payment_reference').setAttribute('required', 'required');
        } else {
            paymentReferenceSection.classList.add('d-none');
            document.getElementById('payment_reference').removeAttribute('required');
        }
    });
    
    // Initialize payment method hidden field if status is unpaid
    if (paymentStatus.value === 'unpaid' && !document.getElementById('payment_method_hidden')) {
        const hiddenInput = document.createElement('input');
        hiddenInput.type = 'hidden';
        hiddenInput.id = 'payment_method_hidden';
        hiddenInput.name = 'payment_method';
        hiddenInput.value = 'none';
        paymentMethodSection.appendChild(hiddenInput);
    }
});
</script>
@endsection
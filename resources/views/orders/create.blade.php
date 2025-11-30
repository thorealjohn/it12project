@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="display-6 fw-bold text-primary">
            <i class="bi bi-cart-plus me-2"></i>Create New Order
        </h1>
        <a href="{{ route('orders.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i> Back to Orders
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

    <div class="row">
        <div class="col-12 col-lg-8">
            <div class="card shadow-sm">
                <div class="card-body">
                    <form id="orderForm" method="POST" action="{{ route('orders.store') }}">
                        @csrf
                        
                        <div class="mb-4">
                            <h5 class="card-title text-primary mb-3">Customer Information</h5>
                            
                            <div class="form-group mb-3">
                                <label for="customer_id" class="form-label">Customer <span class="text-danger">*</span></label>
                                <select class="form-select @error('customer_id') is-invalid @enderror" id="customer_id" name="customer_id" required>
                                    <option value="">Select a customer</option>
                                    @foreach($customers as $customer)
                                        <option value="{{ $customer->id }}" {{ old('customer_id') == $customer->id ? 'selected' : '' }}
                                            data-phone="{{ $customer->phone }}" data-address="{{ $customer->address }}">
                                            {{ $customer->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('customer_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        
                            <div id="customerDetails" class="bg-light p-3 rounded mb-3" style="display: none;">
                                <div class="row">
                                    <div class="col-12 col-sm-6 mb-2 mb-sm-0">
                                        <p class="mb-1"><strong>Phone:</strong> <span id="customerPhone"></span></p>
                                    </div>
                                    <div class="col-12 col-sm-6">
                                        <p class="mb-1"><strong>Address:</strong> <span id="customerAddress"></span></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mb-4">
                            <h5 class="card-title text-primary mb-3">Order Details</h5>
                            
                            <div class="form-group mb-3">
                                <label for="quantity" class="form-label">Quantity <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input type="number" class="form-control @error('quantity') is-invalid @enderror" id="quantity" name="quantity" min="1" value="{{ old('quantity', 1) }}" required>
                                    <span class="input-group-text">container(s)</span>
                                    @error('quantity')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="form-group mb-3">
                                <label for="water_price" class="form-label">Water Price per Container <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text">₱</span>
                                    <input type="number" step="0.01" class="form-control @error('water_price') is-invalid @enderror" id="water_price" name="water_price" min="0" value="{{ old('water_price', 25.00) }}" required>
                                    @error('water_price')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <small class="form-text text-muted">Default price: ₱25.00 per container</small>
                            </div>
                            
                            <div class="form-group mb-3">
                                <label for="order_status" class="form-label">Order Status <span class="text-danger">*</span></label>
                                <select class="form-select @error('order_status') is-invalid @enderror" id="order_status" name="order_status" required>
                                    <option value="pending" {{ old('order_status', 'pending') == 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="completed" {{ old('order_status') == 'completed' ? 'selected' : '' }}>Completed</option>
                                    <option value="cancelled" {{ old('order_status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                </select>
                                @error('order_status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="form-group mb-3">
                                <label class="form-label d-block">Delivery Type</label>
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="is_delivery" name="is_delivery" value="1" {{ old('is_delivery') ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_delivery">
                                        Delivery (₱5.00 per container, FREE for 3+ containers)
                                    </label>
                                </div>
                            </div>
                            
                            <div id="deliveryFields" class="mb-3" style="{{ old('is_delivery') ? '' : 'display: none;' }}">
                                <label for="delivery_user_id" class="form-label">Assign Delivery Personnel <span class="text-danger">*</span></label>
                                <select class="form-select @error('delivery_user_id') is-invalid @enderror" id="delivery_user_id" name="delivery_user_id" {{ old('is_delivery') ? 'required' : '' }}>
                                    <option value="">Select personnel</option>
                                    @foreach($deliveryPersonnel as $personnel)
                                        <option value="{{ $personnel->id }}" {{ old('delivery_user_id') == $personnel->id ? 'selected' : '' }}>
                                            {{ $personnel->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('delivery_user_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="form-group mb-3">
                                <label for="payment_status" class="form-label">Payment Status <span class="text-danger">*</span></label>
                                <select class="form-select @error('payment_status') is-invalid @enderror" id="payment_status" name="payment_status" required>
                                    <option value="paid" {{ old('payment_status') == 'paid' ? 'selected' : '' }}>Paid</option>
                                    <option value="unpaid" {{ old('payment_status') == 'unpaid' ? 'selected' : '' }}>Unpaid</option>
                                </select>
                                @error('payment_status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div id="paymentFields" class="{{ old('payment_status') == 'unpaid' ? 'd-none' : '' }}">
                                <div class="form-group mb-3">
                                    <label for="payment_method" class="form-label">Payment Method <span class="text-danger">*</span></label>
                                    <select class="form-select @error('payment_method') is-invalid @enderror" id="payment_method" name="payment_method">
                                        <option value="cash" {{ old('payment_method') == 'cash' ? 'selected' : '' }}>Cash</option>
                                        <option value="gcash" {{ old('payment_method') == 'gcash' ? 'selected' : '' }}>GCash</option>
                                    </select>
                                    @error('payment_method')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div id="gcashFields" class="form-group mb-3 {{ old('payment_method') != 'gcash' ? 'd-none' : '' }}">
                                    <label for="payment_reference" class="form-label">GCash Reference Number <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('payment_reference') is-invalid @enderror" id="payment_reference" name="payment_reference" value="{{ old('payment_reference') }}">
                                    @error('payment_reference')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="form-group mb-3">
                                <label for="notes" class="form-label">Notes</label>
                                <textarea class="form-control @error('notes') is-invalid @enderror" id="notes" name="notes" rows="2">{{ old('notes') }}</textarea>
                                @error('notes')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="bi bi-check-circle me-2"></i>Submit Order
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="col-12 col-lg-4 mt-4 mt-lg-0">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="card-title mb-0">Order Summary</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-2">
                        <span>Water (<span id="summaryQuantity">1</span> x ₱<span id="summaryWaterPrice">25.00</span>)</span>
                        <span id="waterTotal">₱25.00</span>
                    </div>
                    
                    <div id="deliveryFeeRow" class="d-flex justify-content-between mb-2" style="display: none;">
                        <span>Delivery Fee (<span id="deliveryQuantity">1</span> x <span id="deliveryFeePerUnit">₱5.00</span>)</span>
                        <span id="deliveryTotal">₱5.00</span>
                    </div>
                    
                    <hr>
                    
                    <div class="d-flex justify-content-between fw-bold">
                        <span>Total</span>
                        <span id="orderTotal" class="fs-5">₱25.00</span>
                    </div>
                </div>
            </div>
            
            <div class="card shadow-sm bg-light">
                <div class="card-body">
                    <h5 class="card-title">
                        <i class="bi bi-info-circle me-2"></i>Order Tips
                    </h5>
                    <ul class="card-text">
                        <li>For regular customers, ensure their details are up-to-date</li>
                        <li>Unpaid orders will be marked as "pending"</li>
                        <li>Delivery orders will appear in the Deliveries section</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Customer selection handling
    const customerSelect = document.getElementById('customer_id');
    const customerDetails = document.getElementById('customerDetails');
    const customerPhone = document.getElementById('customerPhone');
    const customerAddress = document.getElementById('customerAddress');
    
    // Order calculation elements
    const quantityInput = document.getElementById('quantity');
    const waterPriceInput = document.getElementById('water_price');
    const isDeliveryCheckbox = document.getElementById('is_delivery');
    const deliveryFields = document.getElementById('deliveryFields');
    const deliveryPersonnel = document.getElementById('delivery_user_id');
    
    // Payment fields
    const paymentStatus = document.getElementById('payment_status');
    const paymentFields = document.getElementById('paymentFields');
    const paymentMethod = document.getElementById('payment_method');
    const gcashFields = document.getElementById('gcashFields');
    
    // Summary elements
    const summaryQuantity = document.getElementById('summaryQuantity');
    const deliveryFeeRow = document.getElementById('deliveryFeeRow');
    const deliveryQuantity = document.getElementById('deliveryQuantity');
    const deliveryFeePerUnit = document.getElementById('deliveryFeePerUnit');
    const waterTotal = document.getElementById('waterTotal');
    const deliveryTotal = document.getElementById('deliveryTotal');
    const orderTotal = document.getElementById('orderTotal');
    
    // Customer selection change
    customerSelect.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        
        if (this.value) {
            customerPhone.textContent = selectedOption.dataset.phone || 'Not provided';
            customerAddress.textContent = selectedOption.dataset.address || 'Not provided';
            customerDetails.style.display = 'block';
        } else {
            customerDetails.style.display = 'none';
        }
    });
    
    // Initialize customer details if already selected
    if (customerSelect.value) {
        const selectedOption = customerSelect.options[customerSelect.selectedIndex];
        customerPhone.textContent = selectedOption.dataset.phone || 'Not provided';
        customerAddress.textContent = selectedOption.dataset.address || 'Not provided';
        customerDetails.style.display = 'block';
    }
    
    // Delivery checkbox change
    isDeliveryCheckbox.addEventListener('change', function() {
        deliveryFields.style.display = this.checked ? 'block' : 'none';
        deliveryPersonnel.required = this.checked;
        deliveryFeeRow.style.display = this.checked ? 'flex' : 'none';
        updateOrderSummary();
    });
    
    // Payment status change
    paymentStatus.addEventListener('change', function() {
        paymentFields.classList.toggle('d-none', this.value === 'unpaid');
        paymentMethod.required = this.value === 'paid';
        
        if (this.value === 'unpaid') {
            // Set a hidden default value for payment_method when unpaid
            if (!document.getElementById('payment_method_hidden')) {
                const hiddenInput = document.createElement('input');
                hiddenInput.type = 'hidden';
                hiddenInput.id = 'payment_method_hidden';
                hiddenInput.name = 'payment_method';
                hiddenInput.value = 'none';
                document.getElementById('orderForm').appendChild(hiddenInput);
            }
        } else {
            // Remove hidden input if it exists
            const hiddenInput = document.getElementById('payment_method_hidden');
            if (hiddenInput) hiddenInput.remove();
        }
    });
    
    // Payment method change
    paymentMethod.addEventListener('change', function() {
        gcashFields.classList.toggle('d-none', this.value !== 'gcash');
        document.getElementById('payment_reference').required = this.value === 'gcash';
    });
    
    // Quantity and water price change
    quantityInput.addEventListener('input', updateOrderSummary);
    waterPriceInput.addEventListener('input', updateOrderSummary);
    
    // Initialize order summary
    function updateOrderSummary() {
        const quantity = parseInt(quantityInput.value) || 1;
        const isDelivery = isDeliveryCheckbox.checked;
        const waterPrice = parseFloat(waterPriceInput.value) || 25.00;
        const defaultDeliveryFee = 5.00;
        
        // Calculate delivery fee: Free if quantity is 3 or more
        let deliveryFee = 0;
        if (isDelivery) {
            if (quantity >= 3) {
                deliveryFee = 0; // Free delivery for 3 or more containers
            } else {
                deliveryFee = defaultDeliveryFee; // Charge delivery fee for less than 3 containers
            }
        }
        
        const waterTotalAmount = quantity * waterPrice;
        const deliveryTotalAmount = isDelivery ? quantity * deliveryFee : 0;
        const totalAmount = waterTotalAmount + deliveryTotalAmount;
        
        // Update summary display
        summaryQuantity.textContent = quantity;
        document.getElementById('summaryWaterPrice').textContent = waterPrice.toFixed(2);
        waterTotal.textContent = `₱${waterTotalAmount.toFixed(2)}`;
        
        if (isDelivery) {
            deliveryQuantity.textContent = quantity;
            if (quantity >= 3) {
                deliveryFeePerUnit.textContent = 'FREE';
                deliveryTotal.textContent = 'FREE';
            } else {
                deliveryFeePerUnit.textContent = `₱${defaultDeliveryFee.toFixed(2)}`;
                deliveryTotal.textContent = `₱${deliveryTotalAmount.toFixed(2)}`;
            }
            deliveryFeeRow.style.display = 'flex';
        } else {
            deliveryFeeRow.style.display = 'none';
        }
        
        orderTotal.textContent = `₱${totalAmount.toFixed(2)}`;
    }
    
    // Initialize form state
    updateOrderSummary();
    deliveryFields.style.display = isDeliveryCheckbox.checked ? 'block' : 'none';
    deliveryPersonnel.required = isDeliveryCheckbox.checked;
    gcashFields.classList.toggle('d-none', paymentMethod.value !== 'gcash');
    document.getElementById('payment_reference').required = paymentMethod.value === 'gcash';
    
    // Form validation before submit
    document.getElementById('orderForm').addEventListener('submit', function(e) {
        if (isDeliveryCheckbox.checked && !deliveryPersonnel.value) {
            e.preventDefault();
            alert('Please select delivery personnel');
            deliveryPersonnel.focus();
        }
        
        if (paymentStatus.value === 'paid' && !paymentMethod.value) {
            e.preventDefault();
            alert('Please select a payment method');
            paymentMethod.focus();
        }
        
        if (paymentMethod.value === 'gcash' && !document.getElementById('payment_reference').value) {
            e.preventDefault();
            alert('Please enter GCash reference number');
            document.getElementById('payment_reference').focus();
        }
    });
});
</script>
@endsection
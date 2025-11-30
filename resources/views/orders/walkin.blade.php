@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="display-6 fw-bold text-primary">
            <i class="bi bi-person-plus me-2"></i>Walk-in
        </h1>
        <a href="{{ route('orders.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i> Back to Orders
        </a>
    </div>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <div class="d-flex">
            <div class="me-3">
                <i class="bi bi-check-circle-fill fs-3"></i>
            </div>
            <div>
                <h5 class="alert-heading">Success!</h5>
                <p class="mb-0">{{ session('success') }}</p>
            </div>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

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
        <div class="col-lg-7">
            <div class="card shadow-sm">
                <div class="card-body p-4">
                    <form id="walkinForm" method="POST" action="{{ route('orders.walkin.store') }}">
                        @csrf
                        
                        <div class="mb-4">
                            <h5 class="card-title text-primary mb-3">Customer Information</h5>
                            
                            <div class="mb-3">
                                <label for="customer_name" class="form-label">Customer Name <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-person"></i></span>
                                    <input type="text" class="form-control @error('customer_name') is-invalid @enderror" id="customer_name" name="customer_name" value="{{ old('customer_name') }}" required autocomplete="off">
                                    @error('customer_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div id="customer_suggestions" class="list-group position-absolute" style="z-index: 1000; width: calc(100% - 2rem);"></div>
                            </div>
                        </div>
                        
                        <div class="mb-4">
                            <h5 class="card-title text-primary mb-3">Order Details</h5>
                            
                            <div class="mb-3">
                                <label for="quantity" class="form-label">Quantity <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <button type="button" class="btn btn-outline-secondary" id="decreaseQuantity">
                                        <i class="bi bi-dash"></i>
                                    </button>
                                    <input type="number" class="form-control text-center @error('quantity') is-invalid @enderror" id="quantity" name="quantity" min="1" value="{{ old('quantity', 1) }}" required>
                                    <button type="button" class="btn btn-outline-secondary" id="increaseQuantity">
                                        <i class="bi bi-plus"></i>
                                    </button>
                                    <span class="input-group-text">container(s)</span>
                                    @error('quantity')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label for="payment_method" class="form-label">Payment Method <span class="text-danger">*</span></label>
                                <div class="d-flex gap-3">
                                    <div class="form-check form-check-inline flex-fill">
                                        <input class="form-check-input" type="radio" name="payment_method" id="payment_cash" value="cash" {{ old('payment_method', 'cash') === 'cash' ? 'checked' : '' }} required>
                                        <label class="form-check-label w-100 p-3 border rounded text-center" for="payment_cash">
                                            <i class="bi bi-cash-stack fs-3 d-block mb-2"></i>
                                            Cash
                                        </label>
                                    </div>
                                    <div class="form-check form-check-inline flex-fill">
                                        <input class="form-check-input" type="radio" name="payment_method" id="payment_gcash" value="gcash" {{ old('payment_method') === 'gcash' ? 'checked' : '' }} required>
                                        <label class="form-check-label w-100 p-3 border rounded text-center" for="payment_gcash">
                                            <i class="bi bi-phone fs-3 d-block mb-2"></i>
                                            GCash
                                        </label>
                                    </div>
                                </div>
                            </div>
                            
                            <div id="gcash_reference_container" class="mb-3 {{ old('payment_method') === 'gcash' ? '' : 'd-none' }}">
                                <label for="payment_reference" class="form-label">GCash Reference Number <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('payment_reference') is-invalid @enderror" id="payment_reference" name="payment_reference" value="{{ old('payment_reference') }}">
                                @error('payment_reference')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="d-grid mt-4">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="bi bi-check-circle me-2"></i>Complete Sale
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="col-lg-5">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="card-title mb-0">Order Summary</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-2">
                        <span>Water (<span id="summary_quantity">1</span> x ₱25.00)</span>
                        <span id="water_total">₱25.00</span>
                    </div>
                    
                    <hr>
                    
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="fs-5 fw-bold">Total</span>
                        <span id="total_amount" class="fs-5 fw-bold text-primary">₱25.00</span>
                    </div>
                </div>
            </div>
            
            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="card-title mb-0">Quick Tips</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <div class="d-flex">
                            <div class="me-3 fs-3 text-primary">
                                <i class="bi bi-search"></i>
                            </div>
                            <div>
                                <h6>Customer Search</h6>
                                <p class="mb-0 small text-muted">Start typing to find existing customers. New customers will be automatically added to the database.</p>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="d-flex">
                            <div class="me-3 fs-3 text-primary">
                                <i class="bi bi-printer"></i>
                            </div>
                            <div>
                                <h6>Receipt</h6>
                                <p class="mb-0 small text-muted">A receipt will be available for printing after the sale is completed.</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-white">
                    <a href="{{ route('orders.create') }}" class="btn btn-outline-primary w-100">
                        <i class="bi bi-cart-plus me-1"></i> Need to create a regular order instead?
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
    // Quantity buttons
    const quantityInput = document.getElementById('quantity');
    const decreaseBtn = document.getElementById('decreaseQuantity');
    const increaseBtn = document.getElementById('increaseQuantity');
    
    // Summary elements
    const summaryQuantity = document.getElementById('summary_quantity');
    const waterTotal = document.getElementById('water_total');
    const totalAmount = document.getElementById('total_amount');
    
    // Payment method
    const paymentCash = document.getElementById('payment_cash');
    const paymentGcash = document.getElementById('payment_gcash');
    const gcashReferenceContainer = document.getElementById('gcash_reference_container');
    const paymentReference = document.getElementById('payment_reference');
    
    // Customer search
    const customerNameInput = document.getElementById('customer_name');
    const customerSuggestions = document.getElementById('customer_suggestions');
    
    // Quantity handling
    function updateQuantity() {
        let qty = parseInt(quantityInput.value);
        if (isNaN(qty) || qty < 1) qty = 1;
        
        const waterPrice = 25.00;
        const waterTotalValue = qty * waterPrice;
        
        // Update display
        quantityInput.value = qty;
        summaryQuantity.textContent = qty;
        waterTotal.textContent = `₱${waterTotalValue.toFixed(2)}`;
        totalAmount.textContent = `₱${waterTotalValue.toFixed(2)}`;
    }
    
    decreaseBtn.addEventListener('click', function() {
        quantityInput.value = Math.max(1, parseInt(quantityInput.value) - 1);
        updateQuantity();
    });
    
    increaseBtn.addEventListener('click', function() {
        quantityInput.value = parseInt(quantityInput.value) + 1;
        updateQuantity();
    });
    
    quantityInput.addEventListener('change', updateQuantity);
    
    // Payment method handling
    paymentCash.addEventListener('change', function() {
        if (this.checked) {
            gcashReferenceContainer.classList.add('d-none');
            paymentReference.removeAttribute('required');
        }
    });
    
    paymentGcash.addEventListener('change', function() {
        if (this.checked) {
            gcashReferenceContainer.classList.remove('d-none');
            paymentReference.setAttribute('required', 'required');
        }
    });
    
    // Customer search functionality
    let customerSearchTimeout;
    
    customerNameInput.addEventListener('input', function() {
        const searchTerm = this.value.trim();
        
        clearTimeout(customerSearchTimeout);
        
        if (searchTerm.length < 2) {
            customerSuggestions.innerHTML = '';
            return;
        }
        
        customerSearchTimeout = setTimeout(function() {
            fetch(`/api/customers/search?term=${encodeURIComponent(searchTerm)}`)
                .then(response => response.json())
                .then(data => {
                    customerSuggestions.innerHTML = '';
                    
                    if (data.length > 0) {
                        data.forEach(customer => {
                            const item = document.createElement('a');
                            item.href = '#';
                            item.className = 'list-group-item list-group-item-action';
                            item.innerHTML = `
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <div class="fw-bold">${customer.name}</div>
                                        <small>${customer.phone || ''}</small>
                                    </div>
                                    ${customer.is_regular ? '<span class="badge bg-success">Regular</span>' : ''}
                                </div>
                            `;
                            
                            item.addEventListener('click', function(e) {
                                e.preventDefault();
                                customerNameInput.value = customer.name;
                                customerSuggestions.innerHTML = '';
                            });
                            
                            customerSuggestions.appendChild(item);
                        });
                    }
                })
                .catch(error => console.error('Error searching customers:', error));
        }, 300);
    });
    
    // Close suggestions when clicking outside
    document.addEventListener('click', function(e) {
        if (e.target !== customerNameInput) {
            customerSuggestions.innerHTML = '';
        }
    });
    
    // Form validation
    document.getElementById('walkinForm').addEventListener('submit', function(e) {
        if (paymentGcash.checked && !paymentReference.value.trim()) {
            e.preventDefault();
            alert('Please enter a GCash reference number');
            paymentReference.focus();
        }
    });
    
    // Initialize
    updateQuantity();
});
</script>
@endsection
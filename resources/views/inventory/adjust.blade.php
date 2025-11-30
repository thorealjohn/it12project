@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="display-6 fw-bold text-primary">
            <i class="bi bi-sliders me-2"></i>Adjust Inventory
        </h1>
        <a href="{{ route('inventory.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i> Back to Inventory
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
        <div class="col-lg-8">
            <div class="card shadow-sm">
                <div class="card-body p-4">
                    <div class="alert alert-info">
                        <div class="d-flex">
                            <i class="bi bi-info-circle-fill fs-4 me-3"></i>
                            <div>
                                <h5 class="mb-1">Inventory Adjustment</h5>
                                <p class="mb-0">Use this form to add or remove stock from the inventory. Make sure to provide a detailed reason for the adjustment.</p>
                            </div>
                        </div>
                    </div>
                    
                    <form method="POST" action="{{ route('inventory.adjust.store') }}">
                        @csrf
                        <input type="hidden" name="inventory_item_id" value="{{ $item->id }}">
                        
                        <div class="mb-3">
                            <h5 class="border-bottom pb-2">Item Details</h5>
                            <div class="row">
                                <div class="col-md-6">
                                    <p><strong>Item Name:</strong> {{ $item->name }}</p>
                                    <p><strong>Type:</strong> {{ ucfirst($item->type) }}</p>
                                </div>
                                <div class="col-md-6">
                                    <p><strong>Current Quantity:</strong> {{ $item->quantity }}</p>
                                    <p><strong>Threshold:</strong> {{ $item->threshold }}</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mb-4">
                            <h5 class="border-bottom pb-2">Adjustment Details</h5>
                            
                            <div class="mb-3">
                                <label class="form-label">Adjustment Type</label>
                                <div class="d-flex gap-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="adjustment_type" id="add_stock" value="increase" checked>
                                        <label class="form-check-label" for="add_stock">
                                            <i class="bi bi-plus-circle text-success me-1"></i> Add to Inventory
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="adjustment_type" id="remove_stock" value="decrease">
                                        <label class="form-check-label" for="remove_stock">
                                            <i class="bi bi-dash-circle text-danger me-1"></i> Remove from Inventory
                                        </label>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label for="quantity" class="form-label">Quantity</label>
                                <input type="number" class="form-control @error('quantity') is-invalid @enderror" id="quantity" name="quantity" min="1" value="{{ old('quantity', 1) }}" required>
                                <div class="form-text">Enter the quantity you want to add or remove.</div>
                                @error('quantity')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="mb-3">
                                <label for="notes" class="form-label">Reason for Adjustment</label>
                                <textarea class="form-control @error('notes') is-invalid @enderror" id="notes" name="notes" rows="3" required>{{ old('notes') }}</textarea>
                                <div class="form-text">Provide a detailed explanation for this inventory adjustment.</div>
                                @error('notes')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="bi bi-save me-2"></i>Save Adjustment
                            </button>
                            <a href="{{ route('inventory.index') }}" class="btn btn-outline-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white">
                    <h5 class="card-title mb-0">Recent Transactions</h5>
                </div>
                <div class="card-body p-0">
                    <ul class="list-group list-group-flush">
                        @forelse($transactions as $transaction)
                        <li class="list-group-item">
                            <div class="d-flex align-items-center">
                                <div class="me-3">
                                    @if($transaction->quantity_change > 0)
                                        <span class="badge bg-success rounded-circle p-2">
                                            <i class="bi bi-plus"></i>
                                        </span>
                                    @else
                                        <span class="badge bg-danger rounded-circle p-2">
                                            <i class="bi bi-dash"></i>
                                        </span>
                                    @endif
                                </div>
                                <div>
                                    <div class="fw-semibold">
                                        {{ abs($transaction->quantity_change) }} 
                                        {{ $transaction->quantity_change > 0 ? 'added' : 'removed' }}
                                    </div>
                                    <small class="text-muted">{{ $transaction->created_at->format('M d, Y h:i A') }}</small>
                                    @if($transaction->notes)
                                    <div class="text-truncate" style="max-width: 200px;" title="{{ $transaction->notes }}">
                                        {{ $transaction->notes }}
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </li>
                        @empty
                        <li class="list-group-item text-center py-3">
                            <span class="text-muted">No recent transactions</span>
                        </li>
                        @endforelse
                    </ul>
                </div>
                @if(count($transactions) > 0)
                <div class="card-footer bg-white">
                    <a href="{{ route('inventory.show', $item->id) }}" class="btn btn-sm btn-outline-primary w-100">
                        <i class="bi bi-clock-history me-1"></i>View Full History
                    </a>
                </div>
                @endif
            </div>
            
            <div class="card shadow-sm bg-light">
                <div class="card-body">
                    <h5 class="card-title">
                        <i class="bi bi-info-circle me-2"></i>Adjustment Tips
                    </h5>
                    <ul class="card-text">
                        <li>Use "Add to Inventory" when you receive new stock</li>
                        <li>Use "Remove from Inventory" for damaged items or manual corrections</li>
                        <li>Always provide a detailed reason for the adjustment</li>
                        <li>Inventory adjustments are logged for audit purposes</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
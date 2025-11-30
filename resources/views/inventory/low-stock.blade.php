@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="display-5 fw-bold text-primary">
            <i class="bi bi-exclamation-triangle me-2"></i>Low Stock Items
        </h1>
        <a href="{{ route('inventory.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i> Back to Inventory
        </a>
    </div>
    
    <div class="alert alert-warning mb-4">
        <div class="d-flex align-items-center">
            <i class="bi bi-info-circle-fill fs-4 me-3"></i>
            <div>
                <h5 class="mb-1">Low Stock Warning</h5>
                <p class="mb-0">The following items are below their recommended stock levels and may need to be restocked soon.</p>
            </div>
        </div>
    </div>
    
    <div class="row">
        @forelse($items as $item)
        <div class="col-md-6 col-xl-4 mb-4">
            <div class="card shadow-sm h-100 {{ $item->quantity <= $item->threshold/2 ? 'border-danger' : 'border-warning' }}">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">{{ $item->name }}</h5>
                    <span class="badge {{ $item->quantity <= $item->threshold/2 ? 'bg-danger' : 'bg-warning text-dark' }} rounded-pill">
                        {{ $item->quantity <= $item->threshold/2 ? 'Critical' : 'Low Stock' }}
                    </span>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <div class="d-flex justify-content-between mb-1">
                            <span>Current Quantity:</span>
                            <span class="fw-bold">{{ $item->quantity }}</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span>Threshold:</span>
                            <span>{{ $item->threshold }}</span>
                        </div>
                        <div class="progress mt-2" style="height: 8px;">
                            <div class="progress-bar {{ $item->quantity <= $item->threshold/2 ? 'bg-danger' : 'bg-warning' }}" 
                                 role="progressbar" 
                                 style="width: {{ min(($item->quantity / $item->threshold) * 100, 100) }}%" 
                                 aria-valuenow="{{ $item->quantity }}" 
                                 aria-valuemin="0" 
                                 aria-valuemax="{{ $item->threshold }}"></div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <small class="text-muted d-block mb-1">Type</small>
                        <span class="badge {{ 
                            $item->type == 'water' ? 'bg-primary' :
                            ($item->type == 'container' ? 'bg-info text-dark' : 
                            ($item->type == 'cap' ? 'bg-secondary' : 'bg-dark'))
                        }}">
                            {{ ucfirst($item->type) }}
                        </span>
                    </div>
                    
                    @if($item->description)
                    <div class="mb-3">
                        <small class="text-muted d-block mb-1">Description</small>
                        {{ $item->description }}
                    </div>
                    @endif
                    
                    <div class="mt-3">
                        <a href="{{ route('inventory.adjust', $item->id) }}" class="btn btn-primary w-100">
                            <i class="bi bi-plus-circle me-1"></i> Add Stock
                        </a>
                    </div>
                </div>
                <div class="card-footer bg-white">
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('inventory.show', $item->id) }}" class="btn btn-sm btn-outline-secondary">
                            <i class="bi bi-clock-history me-1"></i> History
                        </a>
                        <a href="{{ route('inventory.edit', $item->id) }}" class="btn btn-sm btn-outline-primary">
                            <i class="bi bi-pencil me-1"></i> Edit
                        </a>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12">
            <div class="alert alert-success">
                <div class="d-flex align-items-center">
                    <i class="bi bi-check-circle-fill fs-4 me-3"></i>
                    <div>
                        <h5 class="mb-1">All Items in Stock</h5>
                        <p class="mb-0">There are currently no items below their recommended stock levels.</p>
                    </div>
                </div>
            </div>
        </div>
        @endforelse
    </div>
</div>
@endsection
@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="display-6 fw-bold text-primary">
            <i class="bi bi-box me-2"></i>Inventory Item History
        </h1>
        <a href="{{ route('inventory.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i> Back to Inventory
        </a>
    </div>

    <div class="row">
        <div class="col-lg-4 mb-4">
            <!-- Item Details Card -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Item Details</h5>
                    <a href="{{ route('inventory.edit', $item->id) }}" class="btn btn-sm btn-outline-primary">
                        <i class="bi bi-pencil me-1"></i> Edit
                    </a>
                </div>
                <div class="card-body">
                    <h4 class="mb-3">{{ $item->name }}</h4>
                    
                    <div class="mb-3">
                        <span class="badge {{ 
                            $item->type == 'water' ? 'bg-primary' :
                            ($item->type == 'container' ? 'bg-info text-dark' : 
                            ($item->type == 'cap' ? 'bg-secondary' : 
                            ($item->type == 'seal' ? 'bg-dark' : 'bg-light text-dark')))
                        }} mb-2">
                            {{ ucfirst($item->type) }}
                        </span>
                        
                        @if($item->description)
                        <p class="text-muted">{{ $item->description }}</p>
                        @endif
                    </div>
                    
                    <div class="mb-3">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <span>Current Stock</span>
                            <span class="fw-bold fs-5">{{ $item->quantity }}</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span>Low Stock Threshold</span>
                            <span>{{ $item->threshold }}</span>
                        </div>
                        
                        <div class="progress" style="height: 10px;">
                            <div class="progress-bar {{ $item->quantity <= $item->threshold ? ($item->quantity <= $item->threshold/2 ? 'bg-danger' : 'bg-warning') : 'bg-success' }}" 
                                 role="progressbar" 
                                 style="width: {{ min(($item->quantity / max($item->threshold, 1)) * 100, 100) }}%" 
                                 aria-valuenow="{{ $item->quantity }}" 
                                 aria-valuemin="0" 
                                 aria-valuemax="{{ $item->threshold }}"></div>
                        </div>
                        
                        <div class="mt-2 text-center">
                            <span class="badge {{ $item->quantity <= $item->threshold ? ($item->quantity <= $item->threshold/2 ? 'bg-danger' : 'bg-warning text-dark') : 'bg-success' }}">
                                {{ $item->quantity <= $item->threshold ? ($item->quantity <= $item->threshold/2 ? 'Critical Stock' : 'Low Stock') : 'In Stock' }}
                            </span>
                        </div>
                    </div>
                    
                    <div class="d-grid gap-2 mt-4">
                        <a href="{{ route('inventory.adjust', $item->id) }}" class="btn btn-primary">
                            <i class="bi bi-sliders me-1"></i> Adjust Stock
                        </a>
                    </div>
                </div>
                <div class="card-footer bg-white">
                    <small class="text-muted">Last Updated: {{ $item->updated_at->format('M d, Y h:i A') }}</small>
                </div>
            </div>
            
            <!-- Summary Stats Card -->
            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="card-title mb-0">Transaction Summary</h5>
                </div>
                <div class="card-body p-0">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            Total Transactions
                            <span class="badge bg-primary rounded-pill">{{ $transactions->count() }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            Stock Added
                            <span class="badge bg-success rounded-pill">{{ $transactions->where('quantity_change', '>', 0)->sum('quantity_change') }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            Stock Removed
                            <span class="badge bg-danger rounded-pill">{{ abs($transactions->where('quantity_change', '<', 0)->sum('quantity_change')) }}</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        
        <div class="col-lg-8">
            <!-- Transaction History Card -->
            <div class="card shadow-sm">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Transaction History</h5>
                    <div class="dropdown">
                        <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                            <i class="bi bi-filter me-1"></i> Filter
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="{{ route('inventory.show', $item->id) }}">All Transactions</a></li>
                            <li><a class="dropdown-item" href="{{ route('inventory.show', ['id' => $item->id, 'filter' => 'added']) }}">Added Stock</a></li>
                            <li><a class="dropdown-item" href="{{ route('inventory.show', ['id' => $item->id, 'filter' => 'removed']) }}">Removed Stock</a></li>
                            <li><a class="dropdown-item" href="{{ route('inventory.show', ['id' => $item->id, 'filter' => 'manual']) }}">Manual Adjustments</a></li>
                            <li><a class="dropdown-item" href="{{ route('inventory.show', ['id' => $item->id, 'filter' => 'order']) }}">Order Transactions</a></li>
                        </ul>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0 align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Date & Time</th>
                                    <th>Change</th>
                                    <th>Type</th>
                                    <th>User</th>
                                    <th>Notes</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($transactions as $transaction)
                                <tr>
                                    <td>{{ $transaction->created_at->format('M d, Y h:i A') }}</td>
                                    <td>
                                        <span class="badge {{ $transaction->quantity_change > 0 ? 'bg-success' : 'bg-danger' }}">
                                            {{ $transaction->quantity_change > 0 ? '+' : '' }}{{ $transaction->quantity_change }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge {{ 
                                            $transaction->transaction_type == 'order' ? 'bg-primary' : 
                                            ($transaction->transaction_type == 'adjustment' ? 'bg-warning text-dark' : 
                                            'bg-info text-dark') 
                                        }}">
                                            {{ ucfirst($transaction->transaction_type) }}
                                        </span>
                                    </td>
                                    <td>{{ $transaction->user->name ?? 'System' }}</td>
                                    <td>
                                        <div class="text-truncate" style="max-width: 200px;" title="{{ $transaction->notes }}">
                                            {{ $transaction->notes ?: 'No notes' }}
                                        </div>
                                        @if($transaction->order_id)
                                        <div>
                                            <a href="{{ route('orders.show', $transaction->order_id) }}" class="text-decoration-none small">
                                                <i class="bi bi-link-45deg"></i> Order #{{ $transaction->order_id }}
                                            </a>
                                        </div>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center py-3">No transaction history found</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                @if($transactions->hasPages())
                <div class="card-footer bg-white">
                    {{ $transactions->withQueryString()->links() }}
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
@extends('layouts.app')

@section('content')
<style>
    .page-header {
        background: linear-gradient(135deg, #00B8D4, #01579B);
        border-radius: 1rem;
        padding: 2rem;
        color: white;
        margin-bottom: 2rem;
        box-shadow: 0 8px 25px rgba(0, 184, 212, 0.2);
    }
    
    .page-header h1 {
        color: white;
        margin: 0;
        font-size: 2.5rem;
        font-weight: 800;
    }
    
    @media (max-width: 768px) {
        .page-header {
            padding: 1.5rem;
        }
        
        .page-header h1 {
            font-size: 1.75rem;
        }
        
        .page-header .btn {
            margin-top: 1rem;
            width: 100%;
        }
    }
    
    @media (max-width: 576px) {
        .page-header {
            padding: 1rem;
        }
        
        .page-header h1 {
            font-size: 1.5rem;
        }
    }
    
    .page-header .btn {
        background: white;
        color: #00B8D4;
        border: none;
        border-radius: 0.75rem;
        padding: 0.75rem 1.5rem;
        font-weight: 600;
        transition: all 0.3s ease;
    }
    
    .page-header .btn-success {
        background: #198754;
        color: white;
        border: none;
    }
    
    .page-header .btn-success:hover {
        background: #157347;
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(25, 135, 84, 0.3);
    }
    
    .page-header .btn:hover {
        background: #F5F5F5;
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(255, 255, 255, 0.3);
    }
</style>
<div class="container py-4">
    <!-- Page Header -->
    <div class="page-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1>
                    <i class="bi bi-archive me-2"></i>Inventory
                </h1>
                <p class="mb-0 mt-2" style="opacity: 0.9;">Manage your inventory items and stock levels</p>
            </div>
            <a href="{{ route('inventory.create') }}" class="btn btn-success">
                <i class="bi bi-plus-lg me-1"></i> Add New Item
            </a>
        </div>
    </div>
    
    <!-- Inventory Filters -->
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <form action="{{ route('inventory.index') }}" method="GET" class="row g-3 align-items-end">
                <div class="col-md-4">
                    <label for="filter_type" class="form-label">Filter by Type</label>
                    <select name="filter_type" id="filter_type" class="form-select">
                        <option value="">All Types</option>
                        <option value="water" {{ request('filter_type') == 'water' ? 'selected' : '' }}>Water</option>
                        <option value="container" {{ request('filter_type') == 'container' ? 'selected' : '' }}>Containers</option>
                        <option value="cap" {{ request('filter_type') == 'cap' ? 'selected' : '' }}>Caps</option>
                        <option value="seal" {{ request('filter_type') == 'seal' ? 'selected' : '' }}>Seals</option>
                        <option value="other" {{ request('filter_type') == 'other' ? 'selected' : '' }}>Other</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label for="show_low_stock" class="form-label">Stock Level</label>
                    <select name="show_low_stock" id="show_low_stock" class="form-select">
                        <option value="">All Items</option>
                        <option value="1" {{ request('show_low_stock') == '1' ? 'selected' : '' }}>Low Stock Items</option>
                        <option value="2" {{ request('show_low_stock') == '2' ? 'selected' : '' }}>Critical Stock Items</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary flex-grow-1">
                            <i class="bi bi-filter me-1"></i> Apply Filters
                        </button>
                        <a href="{{ route('inventory.index') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-x-lg"></i>
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>
    
    <!-- Low Stock Alert -->
    @if($lowStockCount > 0)
    <div class="alert alert-warning alert-dismissible fade show mb-4" role="alert">
        <div class="d-flex align-items-center">
            <i class="bi bi-exclamation-triangle-fill fs-4 me-3"></i>
            <div>
                <h5 class="mb-1">Low Stock Alert</h5>
                <p class="mb-0">{{ $lowStockCount }} {{ $lowStockCount == 1 ? 'item is' : 'items are' }} below the recommended threshold.</p>
            </div>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif
    
    <!-- Main Inventory Table -->
    <div class="card shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0 align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Item</th>
                            <th>Type</th>
                            <th class="text-center">Current Qty</th>
                            <th class="text-center">Threshold</th>
                            <th>Status</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($items as $item)
                        <tr>
                            <td>
                                <div class="fw-semibold">{{ $item->name }}</div>
                                <small class="text-muted">{{ $item->description }}</small>
                            </td>
                            <td>
                                <span class="badge rounded-pill {{ 
                                    $item->type == 'water' ? 'bg-primary' :
                                    ($item->type == 'container' ? 'bg-info text-dark' : 
                                    ($item->type == 'cap' ? 'bg-secondary' : 
                                    ($item->type == 'seal' ? 'bg-dark' : 'bg-light text-dark')))
                                }}">
                                    {{ ucfirst($item->type) }}
                                </span>
                            </td>
                            <td class="text-center fw-bold">
                                {{ $item->quantity }}
                            </td>
                            <td class="text-center">{{ $item->threshold }}</td>
                            <td>
                                @if($item->quantity <= $item->threshold/2)
                                    <div class="d-flex align-items-center text-danger">
                                        <i class="bi bi-exclamation-triangle-fill me-1"></i>
                                        <span>Critical</span>
                                    </div>
                                @elseif($item->quantity <= $item->threshold)
                                    <div class="d-flex align-items-center text-warning">
                                        <i class="bi bi-exclamation-circle-fill me-1"></i>
                                        <span>Low Stock</span>
                                    </div>
                                @else
                                    <div class="d-flex align-items-center text-success">
                                        <i class="bi bi-check-circle-fill me-1"></i>
                                        <span>In Stock</span>
                                    </div>
                                @endif
                            </td>
                            <td>
                                <div class="dropdown text-end">
                                    <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                        Actions
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end">
                                        <li>
                                            <a class="dropdown-item" href="{{ route('inventory.show', $item->id) }}">
                                                <i class="bi bi-clock-history me-2"></i> View History
                                            </a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item" href="{{ route('inventory.adjust', $item->id) }}">
                                                <i class="bi bi-sliders me-2"></i> Adjust Stock
                                            </a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item" href="{{ route('inventory.edit', $item->id) }}">
                                                <i class="bi bi-pencil me-2"></i> Edit
                                            </a>
                                        </li>
                                        <li><hr class="dropdown-divider"></li>
                                        <li>
                                            <form action="{{ route('inventory.destroy', $item->id) }}" method="POST" class="d-inline delete-form">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button" class="dropdown-item text-danger delete-btn">
                                                    <i class="bi bi-trash me-2"></i> Delete
                                                </button>
                                            </form>
                                        </li>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-4">
                                <div class="d-flex flex-column align-items-center">
                                    <i class="bi bi-inbox text-muted mb-2" style="font-size: 3rem;"></i>
                                    <p class="text-muted mb-2">No inventory items found</p>
                                    <a href="{{ route('inventory.create') }}" class="btn btn-sm btn-primary">Add First Item</a>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            @if($items->hasPages())
            <div class="px-4 py-3 border-top">
                {{ $items->withQueryString()->links() }}
            </div>
            @endif
        </div>
    </div>
    
    <!-- Quick Actions Card -->
    <div class="card shadow-sm mt-4">
        <div class="card-header bg-white">
            <h5 class="card-title mb-0">Quick Actions</h5>
        </div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-4">
                    <a href="{{ route('inventory.create') }}" class="btn btn-outline-primary w-100">
                        <i class="bi bi-plus-circle fs-4 d-block mb-2"></i>
                        Add New Item
                    </a>
                </div>
                <div class="col-md-4">
                    <a href="{{ route('inventory.index', ['show_low_stock' => 1]) }}" class="btn btn-outline-warning w-100">
                        <i class="bi bi-exclamation-triangle fs-4 d-block mb-2"></i>
                        View Low Stock
                    </a>
                </div>
                <div class="col-md-4">
                    <a href="#" data-bs-toggle="modal" data-bs-target="#exportInventoryModal" class="btn btn-outline-success w-100">
                        <i class="bi bi-file-earmark-excel fs-4 d-block mb-2"></i>
                        Export Inventory
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Export Inventory Modal -->
<div class="modal fade" id="exportInventoryModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Export Inventory</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Select the format you want to export the inventory data in:</p>
                <div class="d-grid gap-2">
                    <a href="{{ route('inventory.export', ['format' => 'csv']) }}" class="btn btn-outline-primary">
                        <i class="bi bi-filetype-csv me-2"></i> Export as CSV
                    </a>
                    <a href="{{ route('inventory.export', ['format' => 'pdf']) }}" class="btn btn-outline-danger">
                        <i class="bi bi-filetype-pdf me-2"></i> Export as PDF
                    </a>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Delete confirmation
    const deleteForms = document.querySelectorAll('.delete-form');
    deleteForms.forEach(form => {
        const deleteBtn = form.querySelector('.delete-btn');
        deleteBtn.addEventListener('click', function(e) {
            e.preventDefault();
            if (confirm('Are you sure you want to delete this inventory item? This action cannot be undone.')) {
                form.submit();
            }
        });
    });
});
</script>
@endsection
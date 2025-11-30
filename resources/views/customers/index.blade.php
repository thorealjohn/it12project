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
    
    /* Responsive Styles */
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
        
        .filter-card {
            padding: 1rem;
        }
        
        .table-modern thead th,
        .table-modern tbody td {
            padding: 0.5rem;
            font-size: 0.85rem;
        }
        
        .customer-avatar {
            width: 35px;
            height: 35px;
            font-size: 1rem;
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
    
    .filter-card {
        background: white;
        border-radius: 1rem;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
        padding: 1.5rem;
        margin-bottom: 2rem;
        border-left: 4px solid #00B8D4;
    }
    
    .filter-card .form-control,
    .filter-card .form-select {
        border: 2px solid #CFD8DC;
        border-radius: 0.5rem;
        padding: 0.75rem 1rem;
    }
    
    .filter-card .form-control:focus,
    .filter-card .form-select:focus {
        border-color: #00B8D4;
        box-shadow: 0 0 0 0.2rem rgba(0, 184, 212, 0.15);
    }
    
    .filter-card .btn-primary {
        background: #00B8D4;
        border: none;
        border-radius: 0.5rem;
        padding: 0.75rem 1.5rem;
        font-weight: 600;
    }
    
    .filter-card .btn-primary:hover {
        background: #01579B;
    }
    
    .customers-card {
        background: white;
        border-radius: 1rem;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
        overflow: visible;
    }
    
    .customers-card-header {
        background: linear-gradient(135deg, #F5F5F5, #CFD8DC);
        padding: 1.25rem 1.5rem;
        border-bottom: 2px solid #CFD8DC;
    }
    
    .table-modern {
        margin: 0;
    }
    
    .table-modern thead {
        background: #CFD8DC;
    }
    
    .table-modern thead th {
        color: #01579B;
        font-weight: 700;
        text-transform: uppercase;
        font-size: 0.8rem;
        letter-spacing: 0.5px;
        padding: 1rem;
        border: none;
    }
    
    .table-modern tbody td {
        padding: 1rem;
        border-bottom: 1px solid #F5F5F5;
        vertical-align: middle;
    }
    
    .table-modern tbody td.text-end {
        position: relative;
        z-index: 10;
        overflow: visible;
    }
    
    .table-modern tbody tr {
        position: relative;
    }
    
    .table-modern tbody tr:hover {
        background: #F5F5F5;
    }
    
    .customer-avatar {
        width: 45px;
        height: 45px;
        border-radius: 50%;
        background: linear-gradient(135deg, #00B8D4, #01579B);
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        font-size: 1.2rem;
    }
    
    .badge-modern {
        padding: 0.5rem 1rem;
        border-radius: 0.5rem;
        font-weight: 600;
        font-size: 0.85rem;
    }
    
    .badge-success-modern {
        background: #16a34a;
        color: white;
    }
    
    .badge-secondary-modern {
        background: #CFD8DC;
        color: #01579B;
    }
    
    .orders-link {
        color: #00B8D4;
        text-decoration: none;
        font-weight: 600;
    }
    
    .orders-link:hover {
        color: #01579B;
        text-decoration: underline;
    }
    
    .action-dropdown-btn {
        background: white;
        border: 2px solid #CFD8DC;
        color: #01579B;
        border-radius: 0.5rem;
        padding: 0.5rem 1rem;
        font-weight: 600;
        cursor: pointer;
        pointer-events: auto;
    }
    
    .action-dropdown-btn:hover {
        background: #00B8D4;
        border-color: #00B8D4;
        color: white;
    }
    
    .action-dropdown-btn:active {
        transform: scale(0.98);
    }
    
    .empty-state {
        padding: 4rem 2rem;
        text-align: center;
    }
    
    .empty-state i {
        font-size: 4rem;
        color: #CFD8DC;
        margin-bottom: 1rem;
    }
</style>

<div class="container py-4">
    <!-- Page Header -->
    <div class="page-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1>
                    <i class="bi bi-person-badge me-2"></i>Customers
                </h1>
                <p class="mb-0 mt-2" style="opacity: 0.9;">Manage your customer database</p>
            </div>
            <a href="{{ route('customers.create') }}" class="btn btn-success">
                <i class="bi bi-person-plus me-1"></i> Add Customer
            </a>
        </div>
    </div>
    
    <!-- Search and Filter -->
    <div class="filter-card">
        <form action="{{ route('customers.index') }}" method="GET" class="row g-3 align-items-end">
            <div class="col-12 col-md-4">
                <label for="search" class="form-label" style="color: #01579B; font-weight: 600;">Search</label>
                <input type="text" class="form-control" id="search" name="search" value="{{ request('search') }}" placeholder="Name, phone, address...">
            </div>
            <div class="col-12 col-md-3">
                <label for="filter" class="form-label" style="color: #01579B; font-weight: 600;">Customer Type</label>
                <select name="filter" id="filter" class="form-select">
                    <option value="">All Customers</option>
                    <option value="regular" {{ request('filter') == 'regular' ? 'selected' : '' }}>Regular Customers</option>
                    <option value="non-regular" {{ request('filter') == 'non-regular' ? 'selected' : '' }}>Non-Regular Customers</option>
                </select>
            </div>
            <div class="col-12 col-md-3">
                <label for="sort" class="form-label" style="color: #01579B; font-weight: 600;">Sort By</label>
                <select name="sort" id="sort" class="form-select">
                    <option value="newest" {{ request('sort', 'newest') == 'newest' ? 'selected' : '' }}>Newest First</option>
                    <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Oldest First</option>
                    <option value="name" {{ request('sort') == 'name' ? 'selected' : '' }}>Name (A-Z)</option>
                    <option value="orders" {{ request('sort') == 'orders' ? 'selected' : '' }}>Most Orders</option>
                </select>
            </div>
            <div class="col-12 col-md-2">
                <button type="submit" class="btn btn-primary w-100 mb-2">
                    <i class="bi bi-search me-1"></i> Search
                </button>
                @if(request('search') || request('filter') || request('sort') != 'newest')
                <a href="{{ route('customers.index', ['per_page' => request('per_page')]) }}" class="btn btn-outline-secondary w-100">
                    <i class="bi bi-x-circle me-1"></i> Clear
                </a>
                @endif
            </div>
            <!-- Preserve per_page parameter if it exists -->
            @if(request('per_page'))
                <input type="hidden" name="per_page" value="{{ request('per_page') }}">
            @endif
        </form>
    </div>
    
    <!-- Customers Table -->
    <div class="customers-card">
        <div class="customers-card-header">
            <h5 class="mb-0" style="color: #01579B; font-weight: 700;">
                <i class="bi bi-list-ul me-2"></i>All Customers
            </h5>
        </div>
        <div class="table-responsive">
            <table class="table table-modern">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Customer</th>
                        <th>Contact</th>
                        <th>Address</th>
                        <th class="text-center">Status</th>
                        <th>Orders</th>
                        <th class="text-center">Created</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($customers as $customer)
                    <tr>
                        <td><strong style="color: #01579B;">#{{ $customer->id }}</strong></td>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="customer-avatar me-2">
                                    {{ strtoupper(substr($customer->name, 0, 1)) }}
                                </div>
                                <div class="fw-semibold" style="color: #01579B;">{{ $customer->name }}</div>
                            </div>
                        </td>
                        <td style="color: #606f7b;">{{ $customer->phone }}</td>
                        <td>
                            <div class="text-truncate" style="max-width: 200px; color: #606f7b;" title="{{ $customer->address }}">
                                {{ $customer->address }}
                            </div>
                        </td>
                        <td class="text-center">
                            @if($customer->is_regular)
                                <span class="badge-modern badge-success-modern">Regular</span>
                            @else
                                <span class="badge-modern badge-secondary-modern">One-time</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('customers.show', $customer->id) }}" class="orders-link">
                                {{ $customer->orders_count ?? $customer->orders->count() }} orders
                            </a>
                        </td>
                        <td class="text-center" style="color: #606f7b;">{{ $customer->created_at->format('M d, Y') }}</td>
                        <td class="text-end">
                            <div class="custom-dropdown">
                                <button class="btn btn-sm action-dropdown-btn custom-dropdown-toggle" type="button">
                                    Actions <i class="bi bi-chevron-down ms-1"></i>
                                </button>
                                <div class="custom-dropdown-menu">
                                    <a class="custom-dropdown-item" href="{{ route('customers.show', $customer->id) }}">
                                        <i class="bi bi-eye me-2"></i>View Profile
                                    </a>
                                    <a class="custom-dropdown-item" href="{{ route('customers.edit', $customer->id) }}">
                                        <i class="bi bi-pencil me-2"></i>Edit
                                    </a>
                                    @if(($customer->orders_count ?? $customer->orders->count()) == 0)
                                    <div class="custom-dropdown-divider"></div>
                                    <form method="POST" action="{{ route('customers.destroy', $customer->id) }}" class="delete-form d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="custom-dropdown-item text-danger delete-btn w-100 text-start border-0 bg-transparent">
                                            <i class="bi bi-trash me-2"></i>Delete
                                        </button>
                                    </form>
                                    @endif
                                </div>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="empty-state">
                            <i class="bi bi-people"></i>
                            <p class="text-muted mb-3" style="font-size: 1.1rem;">No customers found</p>
                            <a href="{{ route('customers.create') }}" class="btn btn-primary" style="background: #00B8D4; border: none;">
                                <i class="bi bi-person-plus me-1"></i>Add First Customer
                            </a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($customers->hasPages())
        <div class="px-4 py-3 border-top" style="background: #F5F5F5;">
            {{ $customers->withQueryString()->links() }}
        </div>
        @endif
    </div>
</div>
@endsection

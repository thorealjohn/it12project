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
        
        .page-header .d-flex {
            flex-direction: column;
            align-items: flex-start !important;
        }
        
        .action-buttons {
            margin-top: 1rem;
            width: 100%;
        }
        
        .action-buttons .btn {
            width: 100%;
            margin-bottom: 0.5rem;
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
    
    .action-buttons .btn {
        border-radius: 0.75rem;
        padding: 0.75rem 1.5rem;
        font-weight: 600;
        border: none;
        transition: all 0.3s ease;
    }
    
    .action-buttons .btn-primary {
        background: #00B8D4;
    }
    
    .action-buttons .btn-primary:hover {
        background: #01579B;
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(0, 184, 212, 0.3);
    }
    
    .action-buttons .btn-success {
        background: #16a34a;
    }
    
    .action-buttons .btn-success:hover {
        background: #15803d;
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(22, 163, 74, 0.3);
    }

    
    .orders-card {
        background: white;
        border-radius: 1rem;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
        overflow: visible;
    }
    
    .orders-card-header {
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
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: linear-gradient(135deg, #00B8D4, #01579B);
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        font-size: 1.1rem;
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
    
    .badge-warning-modern {
        background: #d97706;
        color: white;
    }
    
    .badge-danger-modern {
        background: #e3342f;
        color: white;
    }
    
    .badge-info-modern {
        background: #0097A7;
        color: white;
    }
    
    .badge-secondary-modern {
        background: #CFD8DC;
        color: #01579B;
    }
    
    .amount-text {
        color: #00B8D4;
        font-weight: 700;
        font-size: 1.1rem;
    }
    
    .action-dropdown-btn {
        background: white;
        border: 2px solid #CFD8DC;
        color: #01579B;
        border-radius: 0.5rem;
        padding: 0.5rem 1rem;
        font-weight: 600;
    }
    
    .action-dropdown-btn:hover {
        background: #00B8D4;
        border-color: #00B8D4;
        color: white;
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
                    <i class="bi bi-currency-dollar me-2"></i>Sales
                </h1>
                <p class="mb-0 mt-2" style="opacity: 0.9;">Manage and track all your orders</p>
            </div>
            <div class="action-buttons">
                <a href="{{ route('orders.walkin') }}" class="btn btn-success me-2">
                    <i class="bi bi-person-plus me-1"></i> Walk-in
                </a>
                <a href="{{ route('orders.create') }}" class="btn btn-primary">
                    <i class="bi bi-plus-lg me-1"></i> New Order
                </a>
            </div>
        </div>
    </div>
    
    <!-- Orders Table -->
    <div class="orders-card">
        <div class="orders-card-header">
            <h5 class="mb-0" style="color: #01579B; font-weight: 700;">
                <i class="bi bi-list-ul me-2"></i>All Orders
            </h5>
        </div>
        <div class="table-responsive">
            <table class="table table-modern">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Customer</th>
                        <th>Quantity</th>
                        <th class="text-center">Delivery</th>
                        <th>Amount</th>
                        <th>Payment</th>
                        <th>Status</th>
                        <th>Date</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $order)
                    <tr>
                        <td><strong style="color: #01579B;">#{{ $order->id }}</strong></td>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="customer-avatar me-2">
                                    {{ strtoupper(substr($order->customer->name, 0, 1)) }}
                                </div>
                                <div>
                                    <div class="fw-semibold" style="color: #01579B;">{{ $order->customer->name }}</div>
                                    <small style="color: #606f7b;">{{ $order->customer->phone }}</small>
                                </div>
                            </div>
                        </td>
                        <td>
                            <span class="badge-modern badge-info-modern">{{ $order->quantity }}</span>
                        </td>
                        <td class="text-center">
                            @if($order->is_delivery)
                                <span class="badge-modern badge-info-modern">
                                    <i class="bi bi-truck me-1"></i>Yes
                                </span>
                            @else
                                <span class="badge-modern badge-secondary-modern">No</span>
                            @endif
                        </td>
                        <td>
                            <span class="amount-text">₱{{ number_format($order->total_amount, 2) }}</span>
                        </td>
                        <td>
                            @if($order->payment_status == 'paid')
                                <span class="badge-modern badge-success-modern">
                                    Paid
                                </span>
                            @else
                                <span class="badge-modern badge-warning-modern">
                                    <i class="bi bi-clock-history me-1"></i>Unpaid
                                </span>
                            @endif
                        </td>
                        <td>
                            @if($order->order_status == 'completed')
                                <span class="badge-modern badge-success-modern">Completed</span>
                            @elseif($order->order_status == 'pending')
                                <span class="badge-modern badge-warning-modern">Pending</span>
                            @else
                                <span class="badge-modern badge-danger-modern">Cancelled</span>
                            @endif
                        </td>
                        <td style="color: #606f7b;">
                            <div>{{ $order->created_at->format('M d, Y') }}</div>
                            <small>{{ $order->created_at->format('h:i A') }}</small>
                        </td>
                        <td class="text-end">
                            <div class="custom-dropdown">
                                <button class="btn btn-sm action-dropdown-btn custom-dropdown-toggle" type="button">
                                    Actions <i class="bi bi-chevron-down ms-1"></i>
                                </button>
                                <div class="custom-dropdown-menu">
                                    <a class="custom-dropdown-item" href="{{ route('orders.show', $order->id) }}">
                                        <i class="bi bi-eye me-2"></i>View Details
                                    </a>
                                    @if($order->order_status == 'pending')
                                        <a class="custom-dropdown-item" href="{{ route('orders.edit', $order->id) }}">
                                            <i class="bi bi-pencil me-2"></i>Edit Order
                                        </a>
                                        @if(!$order->is_delivery && $order->payment_status == 'unpaid')
                                            <a class="custom-dropdown-item text-success" href="{{ route('orders.edit', $order->id) }}">
                                                <i class="bi bi-cash me-2"></i>Record Payment
                                            </a>
                                        @endif
                                        <div class="custom-dropdown-divider"></div>
                                        <form method="POST" action="{{ route('orders.cancel', $order->id) }}" class="delete-form d-inline">
                                            @csrf
                                            <button type="submit" class="custom-dropdown-item text-danger delete-btn w-100 text-start border-0 bg-transparent">
                                                <i class="bi bi-x-circle me-2"></i>Cancel Order
                                            </button>
                                        </form>
                                    @endif
                                    @if($order->order_status == 'pending' && !$order->is_delivery)
                                        <div class="custom-dropdown-divider"></div>
                                        <form method="POST" action="{{ route('orders.complete', $order->id) }}" class="delete-form d-inline">
                                            @csrf
                                            <button type="submit" class="custom-dropdown-item text-success delete-btn w-100 text-start border-0 bg-transparent">
                                                <i class="bi bi-check-circle me-2"></i>Mark Complete
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="empty-state">
                            <i class="bi bi-inbox"></i>
                            <p class="text-muted mb-3" style="font-size: 1.1rem;">No orders found</p>
                            <a href="{{ route('orders.create') }}" class="btn btn-primary" style="background: #00B8D4; border: none;">
                                <i class="bi bi-plus-lg me-1"></i>Create First Order
                            </a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($orders->hasPages())
        <div class="px-4 py-3 border-top" style="background: #F5F5F5;">
            {{ $orders->links() }}
        </div>
        @endif
    </div>
</div>
@endsection

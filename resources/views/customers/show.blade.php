@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="display-6 fw-bold text-primary">
            <i class="bi bi-person me-2"></i>Customer Profile
        </h1>
        <div>
            <a href="{{ route('orders.create') }}?customer_id={{ $customer->id }}" class="btn btn-success me-2">
                <i class="bi bi-cart-plus me-1"></i> Create Order
            </a>
            <a href="{{ route('customers.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i> Back to Customers
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-4 mb-4">
            <!-- Customer Info Card -->
            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <div class="text-center mb-4">
                        <div class="avatar-circle mx-auto bg-primary" style="width: 80px; height: 80px; font-size: 2rem;">
                            {{ substr($customer->name, 0, 1) }}
                        </div>
                        <h3 class="mt-3 mb-0">{{ $customer->name }}</h3>
                        @if($customer->is_regular)
                            <span class="badge bg-success mt-2">Regular Customer</span>
                        @else
                            <span class="badge bg-secondary mt-2">One-time Customer</span>
                        @endif
                    </div>
                    
                    <hr>
                    
                    <div class="mb-3">
                        <h6 class="text-muted mb-1">Phone Number</h6>
                        <p class="mb-0 fs-5">{{ $customer->phone }}</p>
                    </div>
                    
                    <div class="mb-3">
                        <h6 class="text-muted mb-1">Address</h6>
                        <p class="mb-0">{{ $customer->address }}</p>
                    </div>
                    
                    <div class="mb-3">
                        <h6 class="text-muted mb-1">Notes</h6>
                        <p class="mb-0">{{ $customer->notes ?: 'No notes available' }}</p>
                    </div>
                    
                    <div class="mb-3">
                        <h6 class="text-muted mb-1">Customer Since</h6>
                        <p class="mb-0">{{ $customer->created_at->format('F d, Y') }}</p>
                    </div>
                    
                    <div class="d-flex mt-4">
                        <a href="{{ route('customers.edit', $customer->id) }}" class="btn btn-primary flex-grow-1 me-2">
                            <i class="bi bi-pencil-square me-1"></i> Edit
                        </a>
                        <button type="button" class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deleteCustomerModal">
                            <i class="bi bi-trash"></i>
                        </button>
                    </div>
                </div>
            </div>
            
            <!-- Customer Stats Card -->
            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="card-title mb-0">Customer Statistics</h5>
                </div>
                <div class="card-body p-0">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            Total Orders
                            <span class="badge bg-primary rounded-pill">{{ $customer->orders->count() }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            Completed Orders
                            <span class="badge bg-success rounded-pill">{{ $customer->orders->where('order_status', 'completed')->count() }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            Pending Orders
                            <span class="badge bg-warning text-dark rounded-pill">{{ $customer->orders->where('order_status', 'pending')->count() }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            Total Spent
                            <span class="badge bg-info text-dark rounded-pill">₱{{ number_format($customer->orders->sum('total_amount'), 2) }}</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        
        <div class="col-lg-8">
            <!-- Recent Orders -->
            <div class="card shadow-sm">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Order History</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>#</th>
                                    <th>Date</th>
                                    <th>Quantity</th>
                                    <th>Amount</th>
                                    <th>Status</th>
                                    <th>Payment</th>
                                    <th class="text-end">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($customer->orders->sortByDesc('created_at') as $order)
                                <tr>
                                    <td>{{ $order->id }}</td>
                                    <td>{{ $order->created_at->format('M d, Y') }}</td>
                                    <td>{{ $order->quantity }}</td>
                                    <td>₱{{ number_format($order->total_amount, 2) }}</td>
                                    <td>
                                        @if($order->order_status == 'completed')
                                            <span class="badge bg-success">Completed</span>
                                        @elseif($order->order_status == 'pending')
                                            <span class="badge bg-warning text-dark">Pending</span>
                                        @else
                                            <span class="badge bg-danger">{{ ucfirst($order->order_status) }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($order->payment_status == 'paid')
                                            <span class="badge bg-success">Paid</span>
                                        @else
                                            <span class="badge bg-warning text-dark">Unpaid</span>
                                        @endif
                                    </td>
                                    <td class="text-end">
                                        <a href="{{ route('orders.show', $order->id) }}" class="btn btn-sm btn-outline-primary">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center py-3">
                                        <p class="text-muted mb-0">No orders found</p>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Customer Modal -->
<div class="modal fade" id="deleteCustomerModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Delete Customer</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-danger">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>
                    <strong>Warning:</strong> This action cannot be undone.
                </div>
                <p>Are you sure you want to delete this customer? All associated data including order history will remain in the system but will no longer be linked to this customer.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form action="{{ route('customers.destroy', $customer->id) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Delete Customer</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
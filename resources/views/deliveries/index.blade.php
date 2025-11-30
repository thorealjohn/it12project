@extends('layouts.app')

@section('content')
<style>
    @media (max-width: 768px) {
        .nav-tabs {
            flex-wrap: wrap;
        }
        
        .nav-tabs .nav-item {
            flex: 1 1 auto;
            min-width: 120px;
        }
        
        .nav-tabs .nav-link {
            font-size: 0.85rem;
            padding: 0.5rem 0.75rem;
        }
        
        .table th,
        .table td {
            padding: 0.5rem;
            font-size: 0.85rem;
        }
        
        .table th:nth-child(n+4),
        .table td:nth-child(n+4) {
            display: none;
        }
        
        .table th:nth-child(1),
        .table td:nth-child(1),
        .table th:nth-child(2),
        .table td:nth-child(2),
        .table th:nth-child(3),
        .table td:nth-child(3),
        .table th:last-child,
        .table td:last-child {
            display: table-cell;
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
    }
</style>
<div class="container py-4">
    <!-- Page Header -->
    <div class="page-header">
        <div>
            <h1>
                <i class="bi bi-truck-flatbed me-2"></i>Deliveries
            </h1>
            <p class="mb-0 mt-2" style="opacity: 0.9;">Track and manage all delivery orders</p>
        </div>
    </div>
    
    <!-- Filter Tabs -->
    <ul class="nav nav-tabs mb-4">
        <li class="nav-item">
            <a class="nav-link {{ !request('status') || request('status') == 'pending' ? 'active' : '' }}" href="{{ route('deliveries.index', ['status' => 'pending']) }}">
                Pending <span class="badge bg-warning text-dark ms-1">{{ $pendingCount }}</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request('status') == 'completed' ? 'active' : '' }}" href="{{ route('deliveries.index', ['status' => 'completed']) }}">
                Completed
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request('status') == 'all' ? 'active' : '' }}" href="{{ route('deliveries.index', ['status' => 'all']) }}">
                All Deliveries
            </a>
        </li>
    </ul>
    
    <!-- Delivery Table View -->
    <div class="card shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Order #</th>
                            <th>Customer</th>
                            <th>Date Ordered</th>
                            <th>Quantity</th>
                            <th>Total</th>
                            <th class="text-center">Status</th>
                            <th class="text-center">Payment</th>
                            <th>Delivery Person</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($deliveries as $delivery)
                        <tr class="{{ $delivery->order_status == 'pending' ? 'table-warning' : '' }}">
                            <td>#{{ $delivery->id }}</td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="avatar-circle bg-primary me-2">
                                        {{ substr($delivery->customer->name, 0, 1) }}
                                    </div>
                                    <div>
                                        <div class="fw-semibold">{{ $delivery->customer->name }}</div>
                                        <small class="text-muted">{{ $delivery->customer->phone ?? 'No phone' }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>{{ $delivery->created_at->format('M d, Y') }}</td>
                            <td>{{ $delivery->quantity }} gallon(s)</td>
                            <td class="text-primary fw-semibold">₱{{ number_format($delivery->total_amount, 2) }}</td>
                            <td class="text-center">
                                <span class="badge-modern {{ $delivery->order_status == 'pending' ? 'badge-warning-modern' : 'badge-danger-modern' }}">
                                    {{ ucfirst($delivery->order_status) }}
                                </span>
                            </td>
                            <td class="text-center">
                                <span class="badge-modern {{ $delivery->payment_status == 'paid' ? 'badge-success-modern' : 'bg-warning text-dark' }}">
                                    {{ ucfirst($delivery->payment_status) }}
                                </span>
                            </td>
                            <td>{{ $delivery->deliveryPerson->name ?? 'Not Assigned' }}</td>
                            <td class="text-end">
                                @if(auth()->user()->isDelivery())
                                    @if($delivery->order_status == 'pending')
                                        <button class="btn btn-sm btn-primary complete-btn" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#completeModal" 
                                            data-order-id="{{ $delivery->id }}"
                                            data-payment-status="{{ $delivery->payment_status }}">
                                            <i class="bi bi-check2-circle me-1"></i> Complete
                                        </button>
                                    @else
                                        <a href="{{ route('deliveries.show', $delivery->id) }}" class="btn btn-sm btn-outline-primary">
                                            <i class="bi bi-eye me-1"></i> View
                                        </a>
                                    @endif
                                @else
                                    <a href="{{ route('deliveries.show', $delivery->id) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-eye me-1"></i> Details
                                    </a>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9" class="text-center py-4">
                                <div class="d-flex flex-column align-items-center">
                                    <i class="bi bi-truck text-muted mb-2" style="font-size: 3rem;"></i>
                                    <p class="text-muted mb-2">No deliveries found</p>
                                    <p class="mb-0">There are currently no {{ request('status', 'pending') }} deliveries in the system.</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            @if($deliveries->hasPages())
            <div class="card-footer bg-white d-flex justify-content-between align-items-center">
                <div class="per-page-selector">
                    <span class="me-2">Show:</span>
                    <div class="btn-group btn-group-sm" role="group">
                        <a href="{{ request()->fullUrlWithQuery(['per_page' => 10, 'page' => 1]) }}" 
                           class="btn {{ request('per_page', 10) == 10 ? 'btn-primary' : 'btn-outline-secondary' }}">10</a>
                        <a href="{{ request()->fullUrlWithQuery(['per_page' => 20, 'page' => 1]) }}" 
                           class="btn {{ request('per_page', 10) == 20 ? 'btn-primary' : 'btn-outline-secondary' }}">20</a>
                        <a href="{{ request()->fullUrlWithQuery(['per_page' => 50, 'page' => 1]) }}" 
                           class="btn {{ request('per_page', 10) == 50 ? 'btn-primary' : 'btn-outline-secondary' }}">50</a>
                        <a href="{{ request()->fullUrlWithQuery(['per_page' => 100, 'page' => 1]) }}" 
                           class="btn {{ request('per_page', 10) == 100 ? 'btn-primary' : 'btn-outline-secondary' }}">100</a>
                    </div>
                </div>
                <div>
                    {{ $deliveries->withQueryString()->links() }}
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Complete Delivery Modal -->
<div class="modal fade" id="completeModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Complete Delivery</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="completeDeliveryForm" method="POST" action="">
                @csrf
                <div class="modal-body">
                    <p>Are you sure you want to mark this delivery as completed?</p>
                    
                    <div id="paymentSection" style="display:none;">
                        <div class="alert alert-info">
                            <i class="bi bi-info-circle me-2"></i>
                            This order is marked as unpaid. Did you receive payment during delivery?
                        </div>
                        
                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="payment_received" name="payment_received" value="1">
                            <label class="form-check-label" for="payment_received">Yes, payment was received</label>
                        </div>
                        
                        <div id="paymentMethodSection" style="display:none;">
                            <div class="mb-3">
                                <label class="form-label">Payment Method</label>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="payment_method" id="method_cash" value="cash" checked>
                                    <label class="form-check-label" for="method_cash">
                                        Cash
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="payment_method" id="method_gcash" value="gcash">
                                    <label class="form-check-label" for="method_gcash">
                                        GCash
                                    </label>
                                </div>
                            </div>
                            
                            <div id="gcashReferenceSection" style="display:none;">
                                <div class="mb-3">
                                    <label for="payment_reference" class="form-label">GCash Reference Number</label>
                                    <input type="text" class="form-control" id="payment_reference" name="payment_reference">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">Complete Delivery</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Modal data setup
        const completeButtons = document.querySelectorAll('.complete-btn');
        const completeForm = document.getElementById('completeDeliveryForm');
        const paymentSection = document.getElementById('paymentSection');
        const paymentReceivedCheck = document.getElementById('payment_received');
        const paymentMethodSection = document.getElementById('paymentMethodSection');
        const methodGCash = document.getElementById('method_gcash');
        const gcashReferenceSection = document.getElementById('gcashReferenceSection');
        
        completeButtons.forEach(button => {
            button.addEventListener('click', function() {
                const orderId = this.getAttribute('data-order-id');
                const paymentStatus = this.getAttribute('data-payment-status');
                
                // Set form action
                completeForm.action = `/deliveries/${orderId}/complete`;
                
                // Show/hide payment section based on current payment status
                if (paymentStatus === 'unpaid') {
                    paymentSection.style.display = 'block';
                } else {
                    paymentSection.style.display = 'none';
                }
                
                // Reset form
                paymentReceivedCheck.checked = false;
                paymentMethodSection.style.display = 'none';
                document.getElementById('method_cash').checked = true;
                gcashReferenceSection.style.display = 'none';
                document.getElementById('payment_reference').value = '';
            });
        });
        
        // Toggle payment method section visibility
        paymentReceivedCheck.addEventListener('change', function() {
            paymentMethodSection.style.display = this.checked ? 'block' : 'none';
        });
        
        // Toggle GCash reference section visibility
        methodGCash.addEventListener('change', function() {
            gcashReferenceSection.style.display = this.checked ? 'block' : 'none';
        });
        
        document.getElementById('method_cash').addEventListener('change', function() {
            gcashReferenceSection.style.display = 'none';
        });
    });
</script>
@endsection
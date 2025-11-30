@extends('layouts.app')

@section('content')
<style>
    .dashboard-header {
        background: linear-gradient(135deg, #00B8D4, #01579B);
        border-radius: 1rem;
        padding: 2rem;
        color: white;
        margin-bottom: 2rem;
        box-shadow: 0 8px 25px rgba(0, 184, 212, 0.2);
    }
    
    .dashboard-header h1 {
        color: white;
        margin: 0;
        font-size: 2.5rem;
        font-weight: 800;
    }
    
    .date-badge {
        background: rgba(255, 255, 255, 0.2);
        backdrop-filter: blur(10px);
        padding: 0.5rem 1.5rem;
        border-radius: 2rem;
        border: 1px solid rgba(255, 255, 255, 0.3);
    }
    
    .stat-card {
        background: white;
        border-radius: 1rem;
        padding: 1.5rem;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
        border-left: 4px solid;
        transition: all 0.3s ease;
        height: 100%;
    }
    
    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.12);
    }
    
    .stat-card.primary {
        border-left-color: #00B8D4;
    }
    
    .stat-card.secondary {
        border-left-color: #0097A7;
    }
    
    .stat-card.success {
        border-left-color: #16a34a;
    }
    
    .stat-card.warning {
        border-left-color: #d97706;
    }
    
    .stat-icon {
        width: 60px;
        height: 60px;
        border-radius: 0.75rem;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.75rem;
        margin-bottom: 1rem;
    }
    
    .stat-icon.primary {
        background: linear-gradient(135deg, #00B8D4, #4DD0E1);
        color: white;
    }
    
    .stat-icon.secondary {
        background: linear-gradient(135deg, #0097A7, #26C6DA);
        color: white;
    }
    
    .stat-icon.success {
        background: linear-gradient(135deg, #16a34a, #22c55e);
        color: white;
    }
    
    .stat-icon.warning {
        background: linear-gradient(135deg, #d97706, #f59e0b);
        color: white;
    }
    
    .stat-value {
        font-size: 2rem;
        font-weight: 800;
        color: #01579B;
        margin: 0.5rem 0;
        text-align: right;
    }
    
    .stat-label {
        color: #606f7b;
        font-size: 0.9rem;
        font-weight: 500;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    .stat-subtext {
        color: #CFD8DC;
        font-size: 0.85rem;
        margin-top: 0.5rem;
    }
    
    .content-card {
        background: white;
        border-radius: 1rem;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
        overflow: hidden;
        height: 100%;
    }
    
    .content-card-header {
        background: linear-gradient(135deg, #F5F5F5, #CFD8DC);
        padding: 1.25rem 1.5rem;
        border-bottom: 2px solid #CFD8DC;
    }
    
    .content-card-header h5 {
        color: #01579B;
        font-weight: 700;
        margin: 0;
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
    
    .table-modern tbody tr:hover {
        background: #F5F5F5;
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
    
    .alert-card {
        background: white;
        border-radius: 1rem;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
        border-left: 4px solid #d97706;
        overflow: hidden;
    }
    
    .alert-card-header {
        background: linear-gradient(135deg, #F5F5F5, #CFD8DC);
        padding: 1rem 1.5rem;
        border-bottom: 2px solid #CFD8DC;
    }
    
    .alert-item {
        padding: 1rem 1.5rem;
        border-bottom: 1px solid #F5F5F5;
        transition: background 0.2s;
    }
    
    .alert-item:hover {
        background: #F5F5F5;
    }
    
    .alert-item:last-child {
        border-bottom: none;
    }
    
    .quick-action-btn {
        background: white;
        border: 2px solid #CFD8DC;
        border-radius: 0.75rem;
        padding: 1rem;
        text-align: center;
        text-decoration: none;
        color: #01579B;
        font-weight: 600;
        transition: all 0.3s ease;
        display: block;
    }
    
    .quick-action-btn:hover {
        background: linear-gradient(135deg, #00B8D4, #01579B);
        color: white;
        border-color: #00B8D4;
        transform: translateY(-3px);
        box-shadow: 0 6px 20px rgba(0, 184, 212, 0.3);
    }
    
    .quick-action-btn i {
        display: block;
        font-size: 2rem;
        margin-bottom: 0.5rem;
        color: #00B8D4;
    }
    
    .quick-action-btn:hover i {
        color: white;
    }
    
    .search-control {
        border: 2px solid #CFD8DC;
        border-radius: 0.5rem;
        padding: 0.5rem 1rem;
    }
    
    .search-control:focus {
        border-color: #00B8D4;
        box-shadow: 0 0 0 0.2rem rgba(0, 184, 212, 0.15);
    }
    
    .btn-filter {
        background: white;
        border: 2px solid #CFD8DC;
        color: #01579B;
        border-radius: 0.5rem;
        padding: 0.5rem 1rem;
    }
    
    .btn-filter:hover {
        background: #00B8D4;
        border-color: #00B8D4;
        color: white;
    }

    .content-card:last-child {
    height: auto !important;
    }
    
    /* Responsive Styles */
    @media (max-width: 768px) {
        .dashboard-header {
            padding: 1.5rem;
        }
        
        .dashboard-header h1 {
            font-size: 1.75rem;
        }
        
        .date-badge {
            margin-top: 1rem;
            padding: 0.4rem 1rem;
            font-size: 0.9rem;
        }
        
        .stat-card {
            margin-bottom: 1rem;
        }
        
        .stat-value {
            font-size: 1.5rem;
        }
        
        .quick-action-btn {
            padding: 0.75rem;
            font-size: 0.9rem;
        }
        
        .quick-action-btn i {
            font-size: 1.5rem;
        }
        
        .content-card-header h5 {
            font-size: 1rem;
        }
        
        .table-modern thead th,
        .table-modern tbody td {
            padding: 0.5rem;
            font-size: 0.85rem;
        }
    }
    
    @media (max-width: 576px) {
        .dashboard-header {
            padding: 1rem;
        }
        
        .dashboard-header h1 {
            font-size: 1.5rem;
        }
        
        .stat-icon {
            width: 50px;
            height: 50px;
            font-size: 1.5rem;
        }
        
        .stat-value {
            font-size: 1.25rem;
        }
        
        .stat-label {
            font-size: 0.8rem;
        }
    }
</style>

<div class="container py-4">
    <!-- Dashboard Header -->
    <div class="dashboard-header">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center">
            <div class="mb-3 mb-md-0">
                <h1>
                    <i class="bi bi-grid-3x3-gap me-2"></i>Dashboard
                </h1>
                <p class="mb-0 mt-2" style="opacity: 0.9;">Welcome back! Here's your business overview</p>
            </div>
            <div class="date-badge">
                <i class="bi bi-calendar3 me-2"></i>
                <strong>{{ now()->format('M d, Y') }}</strong>
            </div>
        </div>
    </div>

    <!-- Stats Overview -->
    <div class="row g-4 mb-4">
        <div class="col-12 col-sm-6 col-md-3">
            <div class="stat-card primary">
                <div class="stat-icon primary">
                    <i class="bi bi-cash-stack"></i>
                </div>
                <div class="stat-label">Today's Sales</div>
                <div class="stat-value">₱{{ number_format($todaySales, 2) }}</div>
                <div class="stat-subtext">
                    <i class="bi bi-receipt me-1"></i>{{ $todayOrders }} orders today
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-md-3">
            <div class="stat-card secondary">
                <div class="stat-icon secondary">
                    <i class="bi bi-graph-up-arrow"></i>
                </div>
                <div class="stat-label">Weekly Sales</div>
                <div class="stat-value">₱{{ number_format($weeklySales, 2) }}</div>
                <div class="stat-subtext">
                    <div class="progress mt-2" style="height: 6px; background: #CFD8DC;">
                        <div class="progress-bar" style="background: #0097A7; width: {{ ($weeklySales > 0 && $monthlySales > 0) ? ($weeklySales / $monthlySales * 100) : 0 }}%"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-md-3">
            <div class="stat-card success">
                <div class="stat-icon success">
                    <i class="bi bi-calendar-check"></i>
                </div>
                <div class="stat-label">Monthly Sales</div>
                <div class="stat-value">₱{{ number_format($monthlySales, 2) }}</div>
                <div class="stat-subtext">
                    <i class="bi bi-info-circle me-1"></i>Current month
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-md-3">
            <div class="stat-card warning">
                <div class="stat-icon warning">
                    <i class="bi bi-truck"></i>
                </div>
                <div class="stat-label">Pending Deliveries</div>
                <div class="stat-value">{{ $pendingDeliveries }}</div>
                <div class="stat-subtext">
                    <a href="{{ route('deliveries.index', ['status' => 'pending']) }}" style="color: #d97706; text-decoration: none; font-weight: 600;">
                        Manage <i class="bi bi-arrow-right"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <!-- Recent Orders -->
        <div class="col-12 col-lg-8">
            <div class="content-card">
                <div class="content-card-header d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-2">
                    <h5>
                        <i class="bi bi-clock-history me-2"></i>Recent Orders
                    </h5>
                    <div class="d-flex flex-column flex-sm-row align-items-stretch align-items-sm-center gap-2 w-100 w-md-auto">
                        <form action="{{ route('dashboard') }}" method="GET" class="d-flex flex-column flex-sm-row align-items-stretch align-items-sm-center gap-2 w-100 w-md-auto">
                            <input type="text" name="search" class="form-control form-control-sm search-control" placeholder="Search customer" value="{{ request('search') }}" style="min-width: 150px;">
                            <select name="order_period" class="form-select form-select-sm search-control" style="min-width: 140px;">
                                <option value="today" {{ request('order_period', 'today') == 'today' ? 'selected' : '' }}>Today</option>
                                <option value="week" {{ request('order_period') == 'week' ? 'selected' : '' }}>This Week</option>
                                <option value="month" {{ request('order_period') == 'month' ? 'selected' : '' }}>This Month</option>
                            </select>
                            <input type="hidden" name="per_page" value="{{ $perPage }}">
                            <button type="submit" class="btn btn-sm w-100 w-sm-auto" style="background: #00B8D4; color: white; border: none;">
                                <i class="bi bi-search me-1"></i> Search
                            </button>
                            <a href="{{ route('orders.index') }}" class="btn btn-sm w-100 w-sm-auto" style="background: #00B8D4; color: white; border: none;">
                                View All
                            </a>
                        </form>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-modern mb-0">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Customer</th>
                                <th>Amount</th>
                                <th>Status</th>
                                <th>Payment</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentOrders as $order)
                            <tr>
                                <td><strong>#{{ $order->id }}</strong></td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-circle me-2" style="background: #00B8D4; color: white; width: 36px; height: 36px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: bold;">
                                            {{ substr($order->customer->name, 0, 1) }}
                                        </div>
                                        <div>
                                            <div class="fw-semibold" style="color: #01579B;">{{ $order->customer->name }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td><strong style="color: #00B8D4;">₱{{ number_format($order->total_amount, 2) }}</strong></td>
                                <td>
                                    <span class="badge-modern {{ $order->order_status == 'completed' ? 'badge-success-modern' : ($order->order_status == 'pending' ? 'badge-warning-modern' : 'badge-danger-modern') }}">
                                        {{ ucfirst($order->order_status) }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge-modern {{ $order->payment_status == 'paid' ? 'badge-success-modern' : 'badge-warning-modern' }}">
                                        {{ ucfirst($order->payment_status) }}
                                    </span>
                                </td>
                                <td style="color: #606f7b;">{{ $order->created_at->format('M d, H:i') }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center py-5">
                                    <i class="bi bi-inbox" style="font-size: 3rem; color: #CFD8DC;"></i>
                                    <p class="mt-3 text-muted">
                                        @if(request('search'))
                                            No orders found for "{{ request('search') }}"
                                        @else
                                            No {{ request('order_period', 'today') == 'today' ? 'today\'s' : (request('order_period') == 'week' ? 'this week\'s' : 'this month\'s') }} orders found
                                        @endif
                                    </p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="px-4 py-3 border-top d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center gap-3" style="background: #F5F5F5;">
                    <div class="d-flex flex-column flex-sm-row align-items-start align-items-sm-center gap-2">
                        <span style="color: #606f7b;">Show:</span>
                        <div class="btn-group btn-group-sm">
                            <a href="{{ route('dashboard', array_merge(request()->except('per_page', 'page'), ['per_page' => 10])) }}" 
                               class="btn {{ $perPage == 10 ? 'btn-primary' : 'btn-outline-secondary' }}" 
                               style="{{ $perPage == 10 ? 'background: #00B8D4; border-color: #00B8D4;' : '' }}">10</a>
                            <a href="{{ route('dashboard', array_merge(request()->except('per_page', 'page'), ['per_page' => 25])) }}" 
                               class="btn {{ $perPage == 25 ? 'btn-primary' : 'btn-outline-secondary' }}"
                               style="{{ $perPage == 25 ? 'background: #00B8D4; border-color: #00B8D4;' : '' }}">25</a>
                            <a href="{{ route('dashboard', array_merge(request()->except('per_page', 'page'), ['per_page' => 50])) }}" 
                               class="btn {{ $perPage == 50 ? 'btn-primary' : 'btn-outline-secondary' }}"
                               style="{{ $perPage == 50 ? 'background: #00B8D4; border-color: #00B8D4;' : '' }}">50</a>
                        </div>
                    </div>
                    <div>
                        {{ $recentOrders->links() }}
                    </div>
                </div>
            </div>
        </div>

        <!-- Low Stock & Quick Actions -->
        <div class="col-12 col-lg-4">
            <!-- Low Stock Alerts -->
            <div class="alert-card mb-4">
                <div class="alert-card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0" style="color: #01579B;">
                        <i class="bi bi-exclamation-triangle me-2" style="color: #d97706;"></i>Low Stock Alerts
                    </h5>
                    <a href="{{ route('inventory.index') }}" class="badge-modern {{ $lowStockCount > 0 ? 'badge-danger-modern' : 'badge-success-modern' }}" style="text-decoration: none;">
                        {{ $lowStockCount }}
                    </a>
                </div>
                <div>
                    @if($lowStockItems->isEmpty())
                        <div class="text-center py-5">
                            <i class="bi bi-check-circle-fill" style="font-size: 3rem; color: #16a34a;"></i>
                            <p class="mt-3 text-muted">All items are in stock</p>
                        </div>
                    @else
                        @foreach($lowStockItems as $item)
                            <a href="{{ route('inventory.index') }}" class="alert-item" style="text-decoration: none; display: block; color: inherit;">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <div class="fw-semibold" style="color: #01579B;">{{ $item->name }}</div>
                                        <small style="color: #606f7b;">{{ ucfirst($item->type) }}</small>
                                    </div>
                                    <div class="text-end">
                                        <div class="fw-bold" style="color: {{ $item->quantity > 0 ? '#d97706' : '#e3342f' }}; font-size: 1.1rem;">
                                            {{ $item->quantity }}
                                        </div>
                                        <small style="color: #CFD8DC;">Min: {{ $item->threshold }}</small>
                                    </div>
                                </div>
                            </a>
                        @endforeach
                    @endif
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="content-card">
                <div class="content-card-header">
                    <h5>
                        <i class="bi bi-lightning-charge me-2"></i>Quick Actions
                    </h5>
                </div>
                <div class="p-3">
                    <div class="row g-2">
                        <div class="col-6">
                            <a href="{{ route('orders.create') }}" class="quick-action-btn">
                                <i class="bi bi-plus-circle"></i>
                                <span>New Order</span>
                            </a>
                        </div>
                        <div class="col-6">
                            <a href="{{ route('orders.walkin') }}" class="quick-action-btn">
                                <i class="bi bi-person-plus"></i>
                                <span>Walk-in</span>
                            </a>
                        </div>
                        <div class="col-6">
                            <a href="{{ route('customers.create') }}" class="quick-action-btn">
                                <i class="bi bi-person-add"></i>
                                <span>Add Customer</span>
                            </a>
                        </div>
                        <div class="col-6">
                            <a href="{{ route('deliveries.index') }}" class="quick-action-btn">
                                <i class="bi bi-truck"></i>
                                <span>Deliveries</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

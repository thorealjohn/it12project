<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'CLEARpro Water Station') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.2/font/bootstrap-icons.min.css">
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Theme CSS -->
    <link href="{{ asset('css/clearpro-theme.css') }}" rel="stylesheet">
    
    <!-- Custom CSS -->
    <link href="{{ asset('css/custom.css') }}" rel="stylesheet">
    
    <style>
        :root {
            --primary-color: #00B8D4;
            --primary-light: #4DD0E1;
            --primary-dark: #01579B;
            --secondary-color: #0097A7;
            --sidebar-width: 260px;
        }
        
        body {
            font-family: 'Nunito', sans-serif;
            background-color: #f5f6fa;
        }
        
        .guest-full-main {
            padding: 0;
        }
        
        .sidebar-toggle {
            position: fixed;
            top: 1rem;
            left: 1rem;
            z-index: 1100;
            background: var(--primary-color);
            color: white;
            border: none;
            padding: 0.5rem 0.75rem;
            border-radius: 0.5rem;
            box-shadow: 0 2px 8px rgba(0,0,0,0.2);
            display: flex;
            align-items: center;
            gap: 0.35rem;
            font-weight: 600;
        }
        
        .sidebar-toggle i {
            font-size: 1.1rem;
        }
        
        body.with-sidebar:not(.sidebar-collapsed) .sidebar-toggle {
            left: calc(var(--sidebar-width) + 1rem);
        }
        
        body.sidebar-collapsed .sidebar-toggle {
            left: 1rem;
        }
        
        body:not(.with-sidebar) .sidebar-toggle {
            display: none;
        }
        
        /* Water drop animation */
        .water-drop {
            position: relative;
            width: 24px;
            height: 24px;
            background-color: rgba(255, 255, 255, 0.9);
            border-radius: 50% 50% 50% 0;
            transform: rotate(45deg);
            animation: dropPulse 2s infinite;
        }

        @keyframes dropPulse {
            0% { transform: rotate(45deg) scale(1); }
            50% { transform: rotate(45deg) scale(1.1); }
            100% { transform: rotate(45deg) scale(1); }
        }
        
        /* Sidebar Styles */
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            width: var(--sidebar-width);
            background: linear-gradient(180deg, #01579B 0%, #00B8D4 100%);
            box-shadow: 2px 0 10px rgba(0,0,0,0.1);
            z-index: 1000;
            overflow-y: auto;
            transition: transform 0.3s ease;
            display: flex;
            flex-direction: column;
        }
        
        .sidebar-header {
            padding: 1.5rem 1.25rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }
        
        .sidebar-brand {
            color: white;
            font-weight: 700;
            font-size: 1.25rem;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .sidebar-brand:hover {
            color: white;
            text-decoration: none;
        }
        
        .sidebar-brand .brand-container {
            background: rgba(255, 255, 255, 0.9);
            padding: 2px 8px;
            border-radius: 4px;
            display: inline-block;
        }
        
        .sidebar-brand .brand-clear {
            color: #01579B;
            text-shadow: 0 1px 2px rgba(255, 255, 255, 0.8);
            font-weight: 800;
        }
        
        .sidebar-brand .brand-pro {
            color: #00B8D4;
            text-shadow: 0 1px 2px rgba(255, 255, 255, 0.8);
            font-weight: 800;
        }
        
        .sidebar-nav {
            padding: 1rem 0;
            flex: 1;
            overflow-y: auto;
        }
        
        .sidebar-nav-item {
            margin: 0.25rem 0.75rem;
        }
        
        .sidebar-dropdown-toggle {
            width: 100%;
            background: none;
            border: none;
            text-align: left;
            padding: 0;
        }
        
        .sidebar-nav-link {
            display: flex;
            align-items: center;
            padding: 0.875rem 1rem;
            color: rgba(255, 255, 255, 0.85);
            text-decoration: none;
            border-radius: 0.5rem;
            transition: all 0.2s ease;
            font-weight: 500;
            gap: 0.75rem;
        }
        
        .sidebar-nav-link .toggle-icon {
            margin-left: auto;
            transition: transform 0.2s ease;
        }
        
        .sidebar-dropdown.open .toggle-icon {
            transform: rotate(180deg);
        }
        
        .sidebar-submenu {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.3s ease;
        }
        
        .sidebar-submenu.show {
            max-height: 500px;
        }
        
        .sidebar-submenu a {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.65rem 1rem 0.65rem 2.8rem;
            font-size: 0.9rem;
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
            border-radius: 0.5rem;
            margin: 0.15rem 0;
            transition: all 0.2s ease;
            cursor: pointer;
        }
        
        .sidebar-submenu a:hover,
        .sidebar-submenu a.active {
            background-color: rgba(255, 255, 255, 0.15);
            color: white;
            transform: translateX(4px);
        }
        
        .sidebar-nav-link:hover {
            background-color: rgba(255, 255, 255, 0.15);
            color: white;
            transform: translateX(4px);
        }
        
        .sidebar-nav-link.active {
            background-color: rgba(255, 255, 255, 0.2);
            color: white;
            font-weight: 600;
            border-left: 3px solid white;
        }
        
        .sidebar-nav-link i {
            font-size: 1.1rem;
            width: 20px;
            text-align: center;
        }
        
        .sidebar-footer {
            margin-top: auto;
            padding: 1rem 1.25rem;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            background: rgba(0, 0, 0, 0.1);
        }
        
        .sidebar-user {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            color: white;
            margin-bottom: 0.75rem;
        }
        
        .avatar-circle {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background-color: rgba(255, 255, 255, 0.2);
            color: white;
            font-weight: bold;
            letter-spacing: -0.05em;
            font-size: 1rem;
        }
        
        .sidebar-user-info {
            flex: 1;
        }
        
        .sidebar-user-name {
            font-weight: 600;
            font-size: 0.95rem;
            margin: 0;
        }
        
        .sidebar-logout {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem 1rem;
            color: rgba(255, 255, 255, 0.85);
            text-decoration: none;
            border-radius: 0.5rem;
            transition: all 0.2s ease;
            font-weight: 500;
            width: 100%;
            background: transparent;
            border: none;
            cursor: pointer;
        }
        
        .sidebar-logout:hover {
            background-color: rgba(255, 255, 255, 0.15);
            color: white;
        }
        
        /* Main Content with Sidebar */
        .main-wrapper {
            margin-left: var(--sidebar-width);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        
        .main-content {
            flex: 1;
            padding: 2rem;
        }
        
        body.sidebar-collapsed .sidebar {
            transform: translateX(-100%);
        }
        
        body.sidebar-collapsed .main-wrapper {
            margin-left: 0;
        }
        
        @media (max-width: 768px) {
            .main-content {
                padding: 1rem;
            }
            
            .container {
                padding-left: 1rem;
                padding-right: 1rem;
            }
        }
        
        /* Footer styles */
        footer {
            box-shadow: 0 -2px 10px rgba(0,0,0,0.05);
            margin-left: 0;
            width: 100%;
        }
        
        body.with-sidebar footer {
            margin-left: var(--sidebar-width);
        }
        
        body.sidebar-collapsed footer {
            margin-left: 0;
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
                width: 280px;
            }
            
            .sidebar.show {
                transform: translateX(0);
            }
            
            .main-wrapper {
                margin-left: 0;
            }
            
            .main-content {
                padding: 1rem;
            }
            
            body.with-sidebar footer {
                margin-left: 0;
            }
            
            footer .col-md-6 {
                text-align: center !important;
                margin-bottom: 1rem;
            }
            
            footer .col-md-6:last-child {
                margin-bottom: 0;
            }
            
            .sidebar-toggle {
                left: 1rem !important;
            }
            
            .sidebar-brand {
                font-size: 1.1rem;
            }
            
            .sidebar-nav-link {
                padding: 0.75rem 0.875rem;
                font-size: 0.9rem;
            }
        }
        
        @media (min-width: 769px) and (max-width: 992px) {
            .main-content {
                padding: 1.5rem;
            }
        }
        
        @media (min-width: 769px) {
            body.sidebar-collapsed .sidebar {
                box-shadow: none;
            }
        }
    </style>

    @yield('styles')
</head>
<body class="{{ auth()->check() ? 'with-sidebar' : 'guest-layout' }}">
    <div id="app">
        @auth
        <!-- Sidebar -->
        <aside class="sidebar">
            <div class="sidebar-header">
                <a href="{{ route('dashboard') }}" class="sidebar-brand">
                    <div class="water-drop"></div>
                    <span><span class="brand-container"><span class="brand-clear">CLEAR</span><span class="brand-pro">pro</span></span> Water</span>
                </a>
            </div>
            
            <nav class="sidebar-nav">
                <div class="sidebar-nav-item">
                    <a href="{{ route('dashboard') }}" class="sidebar-nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                        <i class="bi bi-grid-3x3-gap"></i>
                        <span>Dashboard</span>
                    </a>
                </div>
                
                <div class="sidebar-nav-item">
                    <a href="{{ route('customers.index') }}" class="sidebar-nav-link {{ request()->routeIs('customers.*') ? 'active' : '' }}">
                        <i class="bi bi-person-badge"></i>
                        <span>Customers</span>
                    </a>
                </div>
                
                <div class="sidebar-nav-item">
                    <a href="{{ route('deliveries.index') }}" class="sidebar-nav-link {{ request()->routeIs('deliveries.*') ? 'active' : '' }}">
                        <i class="bi bi-truck-flatbed"></i>
                        <span>Deliveries</span>
                    </a>
                </div>
                
                <div class="sidebar-nav-item">
                    <a href="{{ route('orders.index') }}" class="sidebar-nav-link {{ request()->routeIs('orders.*') ? 'active' : '' }}">
                        <i class="bi bi-currency-dollar"></i>
                        <span>Sales</span>
                    </a>
                </div>
                
                <div class="sidebar-nav-item">
                    <a href="{{ route('inventory.index') }}" class="sidebar-nav-link {{ request()->routeIs('inventory.*') ? 'active' : '' }}">
                        <i class="bi bi-archive"></i>
                        <span>Inventory</span>
                    </a>
                </div>
                
                @if(auth()->user()->isOwner() || auth()->user()->isDelivery() || auth()->user()->isHelper())
                <div class="sidebar-nav-item sidebar-dropdown {{ request()->routeIs('reports.*') ? 'open' : '' }}">
                    <button class="sidebar-dropdown-toggle">
                        <span class="sidebar-nav-link {{ request()->routeIs('reports.*') ? 'active' : '' }}">
                            <i class="bi bi-file-earmark-bar-graph"></i>
                            <span>Reports</span>
                            <i class="bi bi-chevron-down toggle-icon"></i>
                        </span>
                    </button>
                    <div class="sidebar-submenu {{ request()->routeIs('reports.*') ? 'show' : '' }}">
                        <a href="{{ route('reports.sales') }}" class="{{ request()->routeIs('reports.sales') ? 'active' : '' }}">
                            <i class="bi bi-dot"></i>
                            <span>Sales Report</span>
                        </a>
                        <a href="{{ route('reports.inventory') }}" class="{{ request()->routeIs('reports.inventory') ? 'active' : '' }}">
                            <i class="bi bi-dot"></i>
                            <span>Inventory Report</span>
                        </a>
                        <a href="{{ route('reports.customer') }}" class="{{ request()->routeIs('reports.customer') ? 'active' : '' }}">
                            <i class="bi bi-dot"></i>
                            <span>Customer Report</span>
                        </a>
                        <a href="{{ route('reports.delivery') }}" class="{{ request()->routeIs('reports.delivery') ? 'active' : '' }}">
                            <i class="bi bi-dot"></i>
                            <span>Delivery Report</span>
                        </a>
                    </div>
                </div>
                @endif
            </nav>
            
            <div class="sidebar-footer">
                <div class="sidebar-user">
                    <div class="avatar-circle">
                        {{ strtoupper(substr(Auth::user()->name ?? 'U', 0, 1)) }}
                    </div>
                    <div class="sidebar-user-info">
                        <p class="sidebar-user-name mb-0">{{ Auth::user()->name ?? 'User' }}</p>
                    </div>
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="sidebar-logout">
                        <i class="bi bi-box-arrow-right"></i>
                        <span>Logout</span>
                    </button>
                </form>
            </div>
        </aside>
        
        <!-- Sidebar Toggle Button -->
        <button type="button" class="sidebar-toggle" aria-label="Toggle sidebar">
            <i class="bi bi-list"></i>
            <span class="d-none d-md-inline">Menu</span>
        </button>
        @endauth

        <!-- Main Wrapper -->
        @auth
        <div class="main-wrapper">
            <!-- Flash Messages -->
            @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert" style="margin: 1rem 1rem 0 1rem;">
                <i class="bi bi-check-circle me-2"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            @endif

            @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert" style="margin: 1rem 1rem 0 1rem;">
                <i class="bi bi-exclamation-triangle me-2"></i> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            @endif

            <!-- Main Content -->
            <main class="main-content">
                @yield('content')
            </main>
        </div>
        @else
        <!-- Flash Messages -->
        @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <div class="container">
                <i class="bi bi-check-circle me-2"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        </div>
        @endif

        @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <div class="container">
                <i class="bi bi-exclamation-triangle me-2"></i> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        </div>
        @endif

        @php
            $guestFullWidth = trim($__env->yieldContent('guest_full_width')) === 'true';
        @endphp

        <!-- Main Content -->
        <main class="{{ $guestFullWidth ? 'guest-full-main' : 'py-4' }}">
            @if($guestFullWidth)
                @yield('content')
            @else
                <div class="container">
                    @yield('content')
                </div>
            @endif
        </main>
        @endauth
        
        <!-- Footer -->
        @auth
        <footer class="py-4 border-top bg-white">
            <div class="container">
                <div class="row">
                    <div class="col-md-6">
                        <div class="d-flex align-items-center">
                            <div class="water-drop me-2"></div>
                            <h5 class="mb-0"><span style="color: #01579B;">CLEAR</span><span style="color: #00B8D4;">pro</span> Water Refilling Station</h5>
                        </div>
                        <p class="text-muted mb-0 mt-2">Providing clean and Safe water</p>
                    </div>
                    <div class="col-md-6 text-md-end">
                        <p class="mb-0 text-muted">© {{ date('Y') }} <span style="color: #01579B;">CLEAR</span><span style="color: #00B8D4;">pro</span> Water System</p>
                        <p class="mb-0 text-muted">All rights reserved</p>
                    </div>
                </div>
            </div>
        </footer>
        @else
        <footer class="py-4 mt-4 border-top bg-white">
            <div class="container">
                <div class="row">
                    <div class="col-md-6">
                        <div class="d-flex align-items-center">
                            <div class="water-drop me-2"></div>
                            <h5 class="mb-0"><span style="color: #01579B;">CLEAR</span><span style="color: #00B8D4;">pro</span> Water Refilling Station</h5>
                        </div>
                        <p class="text-muted mb-0 mt-2">Providing clean and Safe water</p>
                    </div>
                    <div class="col-md-6 text-md-end">
                        <p class="mb-0 text-muted">© {{ date('Y') }} <span style="color: #01579B;">CLEAR</span><span style="color: #00B8D4;">pro</span> Water System</p>
                        <p class="mb-0 text-muted">All rights reserved</p>
                    </div>
                </div>
            </div>
        </footer>
        @endauth
    </div>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const sidebar = document.querySelector('.sidebar');
            const sidebarToggle = document.querySelector('.sidebar-toggle');
            const bodyEl = document.body;
            const isMobile = () => window.innerWidth <= 768;
            
            if (sidebar && sidebarToggle) {
                sidebarToggle.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    
                    if (isMobile()) {
                        sidebar.classList.toggle('show');
                    } else {
                        bodyEl.classList.toggle('sidebar-collapsed');
                    }
                });
            }
            
            window.addEventListener('resize', function() {
                if (!isMobile()) {
                    sidebar?.classList.remove('show');
                } else {
                    bodyEl.classList.remove('sidebar-collapsed');
                }
            });
            
            // Sidebar dropdowns (Reports)
            const reportDropdowns = document.querySelectorAll('.sidebar-dropdown');
            reportDropdowns.forEach(dropdown => {
                const toggle = dropdown.querySelector('.sidebar-dropdown-toggle');
                const submenu = dropdown.querySelector('.sidebar-submenu');
                const links = dropdown.querySelectorAll('.sidebar-submenu a');
                
                // Handle toggle button click
                toggle?.addEventListener('click', (e) => {
                    e.preventDefault();
                    e.stopPropagation();
                    dropdown.classList.toggle('open');
                    submenu?.classList.toggle('show');
                });
                
                // Ensure links work properly - don't prevent their default behavior
                links.forEach(link => {
                    link.addEventListener('click', (e) => {
                        // Allow the link to navigate normally
                        e.stopPropagation(); // Prevent bubbling to parent elements
                    });
                });
            });
            
            // Initialize Bootstrap dropdowns
            var dropdownElementList = [].slice.call(document.querySelectorAll('.dropdown-toggle'));
            dropdownElementList.map(function (dropdownToggleEl) {
                return new bootstrap.Dropdown(dropdownToggleEl);
            });
            
            // Handle custom dropdowns for tables - use event delegation only
            document.addEventListener('click', function(e) {
                // Don't interfere with links, buttons, or form elements inside dropdown
                if (e.target.closest('.custom-dropdown-menu')) {
                    // If clicking on a link or button inside the menu, let it work normally
                    if (e.target.tagName === 'A' || e.target.tagName === 'BUTTON' || e.target.closest('a') || e.target.closest('button') || e.target.closest('form')) {
                        // Only close the dropdown, don't prevent default
                        const dropdown = e.target.closest('.custom-dropdown');
                        if (dropdown) {
                            const menu = dropdown.querySelector('.custom-dropdown-menu');
                            if (menu) {
                                // Small delay to allow the link/button to work
                                setTimeout(() => {
                                    menu.classList.remove('show');
                                }, 100);
                            }
                        }
                        return; // Let the default action proceed
                    }
                }
                
                // Check if click is on dropdown toggle or its children (including icons)
                let toggle = null;
                
                // Check if the clicked element is the toggle button itself
                if (e.target.classList.contains('custom-dropdown-toggle')) {
                    toggle = e.target;
                }
                // Check if clicking on a child element (like an icon) inside the toggle
                else if (e.target.closest('.custom-dropdown-toggle')) {
                    toggle = e.target.closest('.custom-dropdown-toggle');
                }
                
                if (toggle) {
                    e.preventDefault();
                    e.stopPropagation();
                    
                    const dropdown = toggle.closest('.custom-dropdown');
                    if (!dropdown) {
                        console.warn('Dropdown container not found for toggle');
                        return;
                    }
                    
                    const menu = dropdown.querySelector('.custom-dropdown-menu');
                    if (!menu) {
                        console.warn('Dropdown menu not found');
                        return;
                    }
                    
                    const isOpen = menu.classList.contains('show');
                    
                    // Close all other open dropdowns
                    document.querySelectorAll('.custom-dropdown-menu.show').forEach(otherMenu => {
                        if (otherMenu !== menu) {
                            otherMenu.classList.remove('show');
                        }
                    });
                    
                    // Toggle current dropdown
                    if (isOpen) {
                        menu.classList.remove('show');
                    } else {
                        menu.classList.add('show');
                    }
                    return;
                }
                
                // Close dropdowns when clicking outside
                if (!e.target.closest('.custom-dropdown')) {
                    document.querySelectorAll('.custom-dropdown-menu.show').forEach(menu => {
                        menu.classList.remove('show');
                    });
                }
            });
            
            // Handle delete confirmations
            const deleteForms = document.querySelectorAll('.delete-form');
            if (deleteForms.length > 0) {
                deleteForms.forEach(form => {
                    const deleteBtn = form.querySelector('.delete-btn');
                    if (deleteBtn) {
                        deleteBtn.addEventListener('click', function(e) {
                            e.preventDefault();
                            e.stopPropagation();
                            
                            // Close dropdown menu
                            const dropdown = this.closest('.custom-dropdown');
                            const menu = dropdown?.querySelector('.custom-dropdown-menu');
                            if (menu) {
                                menu.classList.remove('show');
                            }
                            
                            // Get action type from button text or form action
                            const actionText = this.textContent.trim().toLowerCase();
                            let confirmMessage = 'Are you sure you want to proceed?';
                            
                            if (actionText.includes('delete')) {
                                confirmMessage = 'Are you sure you want to delete this item? This action cannot be undone.';
                            } else if (actionText.includes('cancel')) {
                                confirmMessage = 'Are you sure you want to cancel this order?';
                            } else if (actionText.includes('complete')) {
                                confirmMessage = 'Are you sure you want to mark this order as completed?';
                            }
                            
                            if (confirm(confirmMessage)) {
                                form.submit();
                            }
                        });
                    }
                });
            }
            
            // Live clock functionality - Manila Time (UTC+8) - Compact Version
            function updateClock() {
                const options = { 
                    timeZone: 'Asia/Manila',
                    hour: '2-digit', 
                    minute: '2-digit',
                    second: '2-digit',
                    hour12: true
                };
                
                const now = new Date();
                const clockElement = document.getElementById('live-clock');
                
                if (clockElement) {
                    clockElement.textContent = now.toLocaleTimeString('en-PH', options);
                }
            }
            
            // Initialize and start clock if element exists
            if (document.getElementById('live-clock')) {
                updateClock();
                setInterval(updateClock, 1000);
            }
            
            // Auto-hide alerts after 5 seconds
            setTimeout(function() {
                const alerts = document.querySelectorAll('.alert');
                alerts.forEach(alert => {
                    const bsAlert = new bootstrap.Alert(alert);
                    bsAlert.close();
                });
            }, 5000);
            
            // Close sidebar when clicking outside on mobile
            document.addEventListener('click', function(e) {
                if (window.innerWidth <= 768 && sidebar && sidebarToggle) {
                    if (!sidebar.contains(e.target) && !sidebarToggle.contains(e.target) && sidebar.classList.contains('show')) {
                        sidebar.classList.remove('show');
                    }
                }
            });
        });
    </script>
    
    @yield('scripts')
</body>
</html>
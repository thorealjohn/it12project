<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Carbon\Carbon;

class ReportController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    /**
     * Display the sales report.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function salesReport(Request $request)
    {
        $reportPeriod = $request->period ?? 'custom';
        $granularity = $request->granularity ?? 'daily';
        $perPage = in_array($request->per_page, [10, 20, 50, 100]) ? $request->per_page : 20;
        
        // Static mock data
        $totalSales = 12500.00;
        $totalQuantity = 500;
        $totalOrders = 50;
        $paidOrders = 45;
        $paidSales = 11250.00;
        $unpaidOrders = 5;
        $unpaidSales = 1250.00;
        $deliveryOrders = 20;
        $pickupOrders = 30;
        
        // Mock orders
        $mockOrders = collect([
            (object)[
                'id' => 101,
                'customer' => (object)['name' => 'John Doe'],
                'total_amount' => 250.00,
                'quantity' => 10,
                'payment_status' => 'paid',
                'is_delivery' => false,
                'created_at' => Carbon::now()->subDays(2),
            ],
        ]);
        
        $orders = new LengthAwarePaginator(
            $mockOrders,
            $mockOrders->count(),
            $perPage,
            1,
            ['path' => $request->url(), 'query' => $request->query()]
        );
        
        // Mock chart data
        $chartData = [
            'labels' => ['Day 1', 'Day 2', 'Day 3', 'Day 4', 'Day 5'],
            'sales' => [500, 750, 600, 800, 650],
            'quantities' => [20, 30, 24, 32, 26],
        ];
        
        $startDate = Carbon::now()->startOfMonth();
        $endDate = Carbon::now()->endOfDay();
        
        return view('reports.sales', compact(
            'orders', 
            'totalSales', 
            'totalQuantity', 
            'totalOrders',
            'paidOrders', 
            'paidSales', 
            'unpaidOrders', 
            'unpaidSales',
            'deliveryOrders',
            'pickupOrders', 
            'chartData',
            'startDate',
            'endDate',
            'reportPeriod',
            'granularity',
            'perPage'
        ));
    }

    /**
     * Display the delivery report.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function deliveryReport(Request $request)
    {
        $reportPeriod = $request->period ?? 'custom';
        $granularity = $request->granularity ?? 'daily';
        $perPage = in_array($request->per_page, [10, 20, 50, 100]) ? $request->per_page : 20;
        $filterStatus = $request->status ?? 'all';
        
        // Static mock data
        $totalDeliveries = 20;
        $totalDeliveryAmount = 5000.00;
        $completedDeliveries = 18;
        $pendingDeliveries = 2;
        $totalQuantity = 100;
        
        // Mock deliveries
        $mockDeliveries = collect([
            (object)[
                'id' => 102,
                'customer' => (object)['name' => 'Jane Smith'],
                'deliveryPerson' => (object)['name' => 'Delivery Person 1'],
                'total_amount' => 150.00,
                'quantity' => 5,
                'order_status' => 'pending',
                'created_at' => Carbon::now()->subDays(1),
            ],
        ]);
        
        $deliveries = new LengthAwarePaginator(
            $mockDeliveries,
            $mockDeliveries->count(),
            $perPage,
            1,
            ['path' => $request->url(), 'query' => $request->query()]
        );
        
        // Mock personnel stats
        $personnelStats = collect([
            (object)[
                'id' => 1,
                'name' => 'Delivery Person 1',
                'total_deliveries' => 15,
                'completed_deliveries' => 14,
                'total_quantity' => 75,
            ],
        ]);
        
        $drivers = collect([
            (object)['id' => 1, 'name' => 'Delivery Person 1'],
        ]);
        $filterDriver = $request->driver ?? 'all';
        
        $startDate = Carbon::now()->startOfMonth();
        $endDate = Carbon::now()->endOfDay();
        
        return view('reports.delivery', compact(
            'deliveries',
            'totalDeliveries',
            'totalDeliveryAmount',
            'completedDeliveries',
            'pendingDeliveries',
            'totalQuantity',
            'personnelStats',
            'startDate',
            'endDate',
            'reportPeriod',
            'granularity',
            'perPage',
            'filterStatus',
            'drivers',
            'filterDriver'
        ));
    }

    /**
     * Display the customer report.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function customerReport(Request $request)
    {
        $reportPeriod = $request->period ?? 'custom';
        $perPage = in_array($request->per_page, [10, 20, 50, 100]) ? $request->per_page : 20;
        
        // Static mock data
        $mockCustomers = collect([
            (object)[
                'id' => 1,
                'name' => 'John Doe',
                'orders_count' => 10,
                'orders_sum_total_amount' => 2500.00,
                'is_regular' => true,
            ],
            (object)[
                'id' => 2,
                'name' => 'Jane Smith',
                'orders_count' => 5,
                'orders_sum_total_amount' => 1250.00,
                'is_regular' => false,
            ],
        ]);
        
        $customers = new LengthAwarePaginator(
            $mockCustomers,
            $mockCustomers->count(),
            $perPage,
            1,
            ['path' => $request->url(), 'query' => $request->query()]
        );
        
        $totalCustomers = 45;
        $regularCustomers = 30;
        $totalOrders = 50;
        $totalSales = 12500.00;
        $avgOrderValue = 250.00;
        $repeatCustomers = 25;
        
        // Mock chart data
        $chartData = [
            'names' => ['John Doe', 'Jane Smith', 'Bob Johnson'],
            'revenues' => [2500, 1250, 1000],
            'orders' => [10, 5, 4],
        ];
        
        $startDate = Carbon::now()->subMonths(3);
        $endDate = Carbon::now()->endOfDay();
        
        return view('reports.customer', compact(
            'customers',
            'totalCustomers',
            'regularCustomers',
            'totalOrders',
            'totalSales',
            'avgOrderValue',
            'repeatCustomers',
            'chartData',
            'startDate',
            'endDate',
            'reportPeriod',
            'perPage'
        ));
    }

    /**
     * Display the inventory report.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function inventoryReport(Request $request)
    {
        $reportPeriod = $request->period ?? 'custom';
        $perPage = in_array($request->per_page, [10, 20, 50, 100]) ? $request->per_page : 20;
        
        // Static mock inventory
        $currentInventory = collect([
            (object)[
                'id' => 1,
                'name' => '5 Gallon Water',
                'type' => 'water',
                'quantity' => 150,
                'threshold' => 50,
                'updated_at' => Carbon::now()->subDays(1),
            ],
        ]);
        
        // Mock inventory logs
        $mockLogs = collect([
            (object)[
                'id' => 1,
                'inventoryItem' => (object)['name' => '5 Gallon Water'],
                'user' => (object)['name' => 'Admin User'],
                'order' => null,
                'quantity_change' => -10,
                'transaction_type' => 'order',
                'created_at' => Carbon::now()->subDays(2),
            ],
        ]);
        
        $inventoryLogs = new LengthAwarePaginator(
            $mockLogs,
            $mockLogs->count(),
            $perPage,
            1,
            ['path' => $request->url(), 'query' => $request->query()]
        );
        
        $totalTransactions = 50;
        $totalIncoming = 200;
        $totalOutgoing = 150;
        
        $itemStats = [
            'water' => [
                'current' => 150,
                'incoming' => 200,
                'outgoing' => 150,
                'last_updated' => Carbon::now()->subDays(1),
            ],
        ];
        
        $startDate = Carbon::now()->subMonth();
        $endDate = Carbon::now()->endOfDay();
        
        return view('reports.inventory', compact(
            'currentInventory',
            'inventoryLogs',
            'totalTransactions',
            'totalIncoming',
            'totalOutgoing',
            'itemStats',
            'startDate',
            'endDate',
            'reportPeriod',
            'perPage'
        ));
    }

    /**
     * Export sales report.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function exportSalesReport(Request $request)
    {
        return redirect()->back()
            ->with('info', 'Functionality disabled. This is a UI-only demo.');
    }

    /**
     * Export delivery report.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function exportDeliveryReport(Request $request)
    {
        return redirect()->back()
            ->with('info', 'Functionality disabled. This is a UI-only demo.');
    }

    /**
     * Export customer report.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function exportCustomerReport(Request $request)
    {
        return redirect()->back()
            ->with('info', 'Functionality disabled. This is a UI-only demo.');
    }

    /**
     * Export inventory report.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function exportInventoryReport(Request $request)
    {
        return redirect()->back()
            ->with('info', 'Functionality disabled. This is a UI-only demo.');
    }
}

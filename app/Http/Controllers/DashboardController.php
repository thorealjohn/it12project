<?php
// app/Http/Controllers/DashboardController.php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Carbon\Carbon;

class DashboardController extends Controller
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
     * Show the dashboard.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        // Static data for UI display only
        $todaySales = 1250.00;
        $todayOrders = 5;
        $weeklySales = 8750.00;
        $monthlySales = 32500.00;
        $pendingDeliveries = 3;
        $totalCustomers = 45;
        
        // Create mock low stock items
        $lowStockItems = collect([
            (object)[
                'id' => 1,
                'name' => '5 Gallon Containers',
                'type' => 'container',
                'quantity' => 5,
                'threshold' => 20,
            ],
            (object)[
                'id' => 2,
                'name' => 'Caps',
                'type' => 'cap',
                'quantity' => 12,
                'threshold' => 50,
            ],
        ]);
        $lowStockCount = $lowStockItems->count();
        
        // Create mock recent orders
        $mockOrders = collect([
            (object)[
                'id' => 101,
                'customer' => (object)['name' => 'John Doe'],
                'total_amount' => 250.00,
                'order_status' => 'completed',
                'payment_status' => 'paid',
                'created_at' => Carbon::now()->subHours(2),
            ],
            (object)[
                'id' => 102,
                'customer' => (object)['name' => 'Jane Smith'],
                'total_amount' => 300.00,
                'order_status' => 'pending',
                'payment_status' => 'unpaid',
                'created_at' => Carbon::now()->subHours(1),
            ],
            (object)[
                'id' => 103,
                'customer' => (object)['name' => 'Bob Johnson'],
                'total_amount' => 275.00,
                'order_status' => 'completed',
                'payment_status' => 'paid',
                'created_at' => Carbon::now()->subMinutes(30),
            ],
        ]);
        
        // Paginate mock orders
        $perPage = in_array($request->input('per_page', 10), [10, 25, 50]) ? $request->input('per_page', 10) : 10;
        $currentPage = $request->input('page', 1);
        $recentOrders = new LengthAwarePaginator(
            $mockOrders->forPage($currentPage, $perPage),
            $mockOrders->count(),
            $perPage,
            $currentPage,
            ['path' => $request->url(), 'query' => $request->query()]
        );
        
        $orderPeriod = $request->input('order_period', 'today');
        $showSalesData = true;
        
        return view('dashboard', compact(
            'todaySales', 
            'todayOrders', 
            'weeklySales', 
            'monthlySales', 
            'pendingDeliveries', 
            'lowStockItems',
            'lowStockCount',
            'totalCustomers',
            'recentOrders',
            'orderPeriod',
            'perPage',
            'showSalesData'
        ));
    }
}
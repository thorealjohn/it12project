<?php
// app/Http/Controllers/OrderController.php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Carbon\Carbon;
use App\Models\Order;
use App\Models\Customer;

class OrderController extends Controller
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
     * Display a listing of orders.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $query = \App\Models\Order::with(['customer', 'user', 'deliveryPerson']);
        
        // Filter by status
        if ($request->filled('status')) {
            $query->where('order_status', $request->status);
        }
        
        // Filter by payment status
        if ($request->filled('payment_status')) {
            $query->where('payment_status', $request->payment_status);
        }
        
        // Filter by delivery type
        if ($request->filled('delivery_type')) {
            if ($request->delivery_type === 'delivery') {
                $query->where('is_delivery', true);
            } else {
                $query->where('is_delivery', false);
            }
        }
        
        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('customer', function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }
        
        $perPage = $request->input('per_page', 20);
        $orders = $query->orderBy('created_at', 'desc')->paginate($perPage)->withQueryString();
            
        return view('orders.index', compact('orders'));
    }

    /**
     * Show form to create a new order.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $customers = \App\Models\Customer::orderBy('name')->get();
        $deliveryPersonnel = \App\Models\User::where('role', 'delivery')->orWhere('role', 'helper')->orderBy('name')->get();
        
        return view('orders.create', compact('customers', 'deliveryPersonnel'));
    }

    /**
     * Store a newly created order.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'quantity' => 'required|integer|min:1',
            'water_price' => 'required|numeric|min:0',
            'is_delivery' => 'boolean',
            'delivery_fee' => 'nullable|numeric|min:0',
            'delivery_user_id' => 'nullable|exists:users,id',
            'delivery_date' => 'nullable|date',
            'payment_status' => 'required|in:paid,unpaid',
            'payment_method' => 'required|in:cash,gcash,none',
            'payment_reference' => 'nullable|string|max:255',
            'order_status' => 'required|in:pending,completed,cancelled',
            'notes' => 'nullable|string',
        ]);
        
        $validated['is_delivery'] = $request->has('is_delivery');
        $validated['user_id'] = auth()->id();
        
        // Calculate delivery fee: Free if quantity is 3 or more
        $defaultDeliveryFee = 5.00;
        if ($validated['is_delivery']) {
            if ($validated['quantity'] >= 3) {
                // Free delivery for 3 or more containers
                $validated['delivery_fee'] = 0;
            } else {
                // Charge delivery fee for less than 3 containers
                $validated['delivery_fee'] = $validated['delivery_fee'] ?? $defaultDeliveryFee;
            }
        } else {
            $validated['delivery_fee'] = 0;
        }
        
        // Calculate total amount
        $waterTotal = $validated['quantity'] * $validated['water_price'];
        $deliveryTotal = $validated['is_delivery'] ? ($validated['quantity'] * $validated['delivery_fee']) : 0;
        $validated['total_amount'] = $waterTotal + $deliveryTotal;
        
        $order = \App\Models\Order::create($validated);
        
        return redirect()->route('orders.show', $order->id)
            ->with('success', 'Order created successfully!');
    }

    /**
     * Display the order details.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        $order = \App\Models\Order::with(['customer', 'user', 'deliveryPerson', 'inventoryTransactions'])->findOrFail($id);
        
        return view('orders.show', compact('order'));
    }

    /**
     * Show form to edit order.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        $order = \App\Models\Order::findOrFail($id);
        $customers = \App\Models\Customer::orderBy('name')->get();
        $deliveryPersonnel = \App\Models\User::where('role', 'delivery')->orWhere('role', 'helper')->orderBy('name')->get();
        
        return view('orders.edit', compact('order', 'customers', 'deliveryPersonnel'));
    }

    /**
     * Update the order.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        $order = \App\Models\Order::findOrFail($id);
        
        $validated = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'quantity' => 'required|integer|min:1',
            'water_price' => 'required|numeric|min:0',
            'is_delivery' => 'boolean',
            'delivery_fee' => 'nullable|numeric|min:0',
            'delivery_user_id' => 'nullable|exists:users,id',
            'delivery_date' => 'nullable|date',
            'payment_status' => 'required|in:paid,unpaid',
            'payment_method' => 'required|in:cash,gcash,none',
            'payment_reference' => 'nullable|string|max:255',
            'order_status' => 'required|in:pending,completed,cancelled',
            'notes' => 'nullable|string',
        ]);
        
        $validated['is_delivery'] = $request->has('is_delivery');
        
        // Calculate delivery fee: Free if quantity is 3 or more
        $defaultDeliveryFee = 5.00;
        if ($validated['is_delivery']) {
            if ($validated['quantity'] >= 3) {
                // Free delivery for 3 or more containers
                $validated['delivery_fee'] = 0;
            } else {
                // Charge delivery fee for less than 3 containers
                $validated['delivery_fee'] = $validated['delivery_fee'] ?? $defaultDeliveryFee;
            }
        } else {
            $validated['delivery_fee'] = 0;
        }
        
        // Calculate total amount
        $waterTotal = $validated['quantity'] * $validated['water_price'];
        $deliveryTotal = $validated['is_delivery'] ? ($validated['quantity'] * $validated['delivery_fee']) : 0;
        $validated['total_amount'] = $waterTotal + $deliveryTotal;
        
        $order->update($validated);
        
        return redirect()->route('orders.show', $order->id)
            ->with('success', 'Order updated successfully!');
    }

    /**
     * Create a walk-in sale quickly.
     *
     * @return \Illuminate\View\View
     */
    public function createWalkin()
    {
        return view('orders.walkin');
    }

    /**
     * Store a walk-in sale.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function storeWalkin(Request $request)
    {
        $validated = $request->validate([
            'customer_name' => 'required|string|max:255',
            'quantity' => 'required|integer|min:1',
            'water_price' => 'required|numeric|min:0',
            'payment_status' => 'required|in:paid,unpaid',
            'payment_method' => 'required|in:cash,gcash',
            'payment_reference' => 'nullable|string|max:255',
        ]);
        
        // Create or find walk-in customer
        $customer = \App\Models\Customer::firstOrCreate(
            ['phone' => $request->input('phone', 'WALKIN-' . time())],
            [
                'name' => $validated['customer_name'],
                'address' => 'Walk-in Customer',
                'is_regular' => false,
            ]
        );
        
        // Create order
        $orderData = [
            'customer_id' => $customer->id,
            'user_id' => auth()->id(),
            'quantity' => $validated['quantity'],
            'water_price' => $validated['water_price'],
            'is_delivery' => false,
            'delivery_fee' => 0,
            'total_amount' => $validated['quantity'] * $validated['water_price'],
            'payment_status' => $validated['payment_status'],
            'payment_method' => $validated['payment_method'],
            'payment_reference' => $validated['payment_reference'] ?? null,
            'order_status' => 'completed',
        ];
        
        $order = \App\Models\Order::create($orderData);
        
        return redirect()->route('orders.show', $order->id)
            ->with('success', 'Walk-in order created successfully!');
    }
    
    /**
     * Mark an order as completed.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function complete($id)
    {
        $order = \App\Models\Order::findOrFail($id);
        
        if ($order->order_status === 'completed') {
            return redirect()->back()
                ->with('error', 'Order is already completed.');
        }
        
        $order->update([
            'order_status' => 'completed',
            'delivery_date' => $order->is_delivery ? now() : null,
        ]);
        
        return redirect()->back()
            ->with('success', 'Order marked as completed!');
    }
    
    /**
     * Cancel an order.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function cancel($id)
    {
        $order = \App\Models\Order::findOrFail($id);
        
        if ($order->order_status === 'cancelled') {
            return redirect()->back()
                ->with('error', 'Order is already cancelled.');
        }
        
        if ($order->order_status === 'completed') {
            return redirect()->back()
                ->with('error', 'Cannot cancel a completed order.');
        }
        
        $order->update(['order_status' => 'cancelled']);
        
        return redirect()->back()
            ->with('success', 'Order cancelled successfully!');
    }
    
    /**
     * Remove the specified order from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        $order = \App\Models\Order::findOrFail($id);
        
        // Only allow deletion of cancelled orders or pending orders
        if ($order->order_status === 'completed') {
            return redirect()->back()
                ->with('error', 'Cannot delete a completed order.');
        }
        
        $order->delete();
        
        return redirect()->route('orders.index')
            ->with('success', 'Order deleted successfully!');
    }
}

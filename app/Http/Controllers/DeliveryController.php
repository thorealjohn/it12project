<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Carbon\Carbon;
use App\Models\Order;

class DeliveryController extends Controller
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
     * Display a listing of deliveries.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $status = $request->status ?? 'pending';
        
        $query = \App\Models\Order::where('is_delivery', true)
            ->with(['customer', 'deliveryPerson']);
        
        // Filter by status
        if ($status === 'pending') {
            $query->where('order_status', 'pending');
        } elseif ($status === 'completed') {
            $query->where('order_status', 'completed');
        }
        // 'all' shows everything
        
        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('customer', function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }
        
        $perPage = in_array($request->per_page, [10, 20, 50, 100]) ? $request->per_page : 10;
        $deliveries = $query->orderBy('created_at', 'desc')->paginate($perPage)->withQueryString();
        
        $pendingCount = \App\Models\Order::where('is_delivery', true)
            ->where('order_status', 'pending')
            ->count();
    
        return view('deliveries.index', compact('deliveries', 'pendingCount'));
    }

    /**
     * Show a specific delivery order details.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        $order = \App\Models\Order::where('is_delivery', true)
            ->with(['customer', 'deliveryPerson', 'user'])
            ->findOrFail($id);
        
        return view('deliveries.show', compact('order'));
    }

    /**
     * Mark a delivery as completed.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function complete(Request $request, $id)
    {
        $order = \App\Models\Order::where('is_delivery', true)->findOrFail($id);
        
        if ($order->order_status === 'completed') {
            return redirect()->back()
                ->with('error', 'Delivery is already completed.');
        }
        
        $validated = $request->validate([
            'payment_status' => 'sometimes|in:paid,unpaid',
            'payment_method' => 'sometimes|in:cash,gcash',
            'payment_reference' => 'nullable|string|max:255',
        ]);
        
        $order->update([
            'order_status' => 'completed',
            'delivery_date' => now(),
            'payment_status' => $validated['payment_status'] ?? $order->payment_status,
            'payment_method' => $validated['payment_method'] ?? $order->payment_method,
            'payment_reference' => $validated['payment_reference'] ?? $order->payment_reference,
        ]);
        
        return redirect()->route('deliveries.index')
            ->with('success', 'Delivery marked as completed!');
    }
    
    /**
     * Cancel a delivery.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function cancel($id)
    {
        $order = \App\Models\Order::where('is_delivery', true)->findOrFail($id);
        
        if ($order->order_status === 'cancelled') {
            return redirect()->back()
                ->with('error', 'Delivery is already cancelled.');
        }
        
        if ($order->order_status === 'completed') {
            return redirect()->back()
                ->with('error', 'Cannot cancel a completed delivery.');
        }
        
        $order->update(['order_status' => 'cancelled']);
        
        return redirect()->route('deliveries.index')
            ->with('success', 'Delivery cancelled successfully!');
    }
}

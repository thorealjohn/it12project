<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\InventoryItem;
use App\Models\InventoryTransaction;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class InventoryController extends Controller
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
     * Display a listing of inventory items.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $query = InventoryItem::query();
        
        // Filter by type
        if ($request->filled('filter_type')) {
            $query->where('type', $request->filter_type);
        }
        
        // Filter by stock level
        if ($request->filled('show_low_stock')) {
            if ($request->show_low_stock == '1') {
                // Low stock items (at or below threshold)
                $query->whereRaw('quantity <= threshold');
            } elseif ($request->show_low_stock == '2') {
                // Critical stock items (at or below half threshold)
                $query->whereRaw('quantity <= (threshold / 2)');
            }
        }
        
        $items = $query->orderBy('name')->paginate(15)->withQueryString();
        $lowStockCount = InventoryItem::whereRaw('quantity <= threshold')->count();
        
        return view('inventory.index', compact('items', 'lowStockCount'));
    }

    /**
     * Show the form for creating a new inventory item.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('inventory.create');
    }

    /**
     * Store a newly created inventory item in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:water,container,cap,seal,other',
            'description' => 'nullable|string',
            'quantity' => 'required|integer|min:0',
            'threshold' => 'required|integer|min:1',
        ]);
        
        $item = InventoryItem::create($validated);
        
        // Create initial transaction log
        InventoryTransaction::create([
            'inventory_item_id' => $item->id,
            'user_id' => auth()->id(),
            'quantity_change' => $validated['quantity'],
            'transaction_type' => 'restock',
            'notes' => 'Initial stock entry',
        ]);
        
        return redirect()->route('inventory.index')
            ->with('success', 'Inventory item created successfully.');
    }

    /**
     * Display the specified inventory item with transaction history.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function show(Request $request, $id)
    {
        $item = InventoryItem::findOrFail($id);
        
        $transactions = InventoryTransaction::where('inventory_item_id', $id)
            ->with(['user', 'order'])
            ->orderBy('created_at', 'desc')
            ->paginate(15)
            ->withQueryString();
        
        return view('inventory.show', compact('item', 'transactions'));
    }

    /**
     * Show the form for editing the specified inventory item.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        $item = InventoryItem::findOrFail($id);
        return view('inventory.edit', compact('item'));
    }

    /**
     * Update the specified inventory item in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        $item = InventoryItem::findOrFail($id);
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:water,container,cap,seal,other',
            'description' => 'nullable|string',
            'threshold' => 'required|integer|min:1',
        ]);
        
        $item->update($validated);
        
        return redirect()->route('inventory.show', $id)
            ->with('success', 'Inventory item updated successfully.');
    }

    /**
     * Remove the specified inventory item from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        $item = InventoryItem::findOrFail($id);
        
        // Check if item has transactions
        if ($item->transactions()->count() > 0) {
            return redirect()->route('inventory.index')
                ->with('error', 'Cannot delete inventory item with existing transactions.');
        }
        
        $item->delete();
        
        return redirect()->route('inventory.index')
            ->with('success', 'Inventory item deleted successfully.');
    }

    /**
     * Show inventory adjustment form.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function showAdjustForm($id)
    {
        $item = InventoryItem::findOrFail($id);
        
        $transactions = InventoryTransaction::where('inventory_item_id', $id)
            ->with(['user'])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();
            
        return view('inventory.adjust', compact('item', 'transactions'));
    }

    /**
     * Process inventory adjustment.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function adjustStore(Request $request)
    {
        $validated = $request->validate([
            'inventory_item_id' => 'required|exists:inventory_items,id',
            'adjustment_type' => 'required|in:increase,decrease',
            'quantity' => 'required|integer|min:1',
            'notes' => 'required|string|max:500',
        ]);
        
        $item = InventoryItem::findOrFail($validated['inventory_item_id']);
        
        DB::beginTransaction();
        try {
            $quantityChange = $validated['adjustment_type'] === 'increase' 
                ? $validated['quantity'] 
                : -$validated['quantity'];
            
            // Check if decrease would result in negative quantity
            if ($quantityChange < 0 && ($item->quantity + $quantityChange) < 0) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Insufficient stock. Cannot decrease quantity below zero.');
            }
            
            // Update inventory quantity
            $item->quantity += $quantityChange;
            $item->save();
            
            // Create transaction log
            InventoryTransaction::create([
                'inventory_item_id' => $item->id,
                'user_id' => auth()->id(),
                'quantity_change' => $quantityChange,
                'transaction_type' => 'adjustment',
                'notes' => $validated['notes'],
            ]);
            
            DB::commit();
            
            return redirect()->route('inventory.show', $item->id)
                ->with('success', 'Inventory adjusted successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to adjust inventory: ' . $e->getMessage());
        }
    }

    /**
     * Show low stock items.
     *
     * @return \Illuminate\View\View
     */
    public function lowStock()
    {
        $items = InventoryItem::whereRaw('quantity <= threshold')
            ->orderByRaw('(quantity / threshold) ASC')
            ->get();
            
        return view('inventory.low-stock', compact('items'));
    }

    /**
     * Export inventory data to CSV or PDF.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function export(Request $request)
    {
        // Functionality disabled - UI only
        return redirect()->back()
            ->with('info', 'Functionality disabled. This is a UI-only demo.');
    }
}

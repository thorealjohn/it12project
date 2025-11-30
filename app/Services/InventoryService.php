<?php
// App/Services/InventoryService.php

namespace App\Services;

use App\Models\Inventory;
use Carbon\Carbon;

class InventoryService
{
    public function increaseStock($itemType, $quantity)
    {
        $inventory = Inventory::where('item_type', $itemType)->first();
        
        if (!$inventory) {
            $inventory = new Inventory();
            $inventory->item_type = $itemType;
            $inventory->quantity = 0;
        }
        
        $inventory->quantity += $quantity;
        $inventory->last_updated = Carbon::now();
        $inventory->save();
        
        // Log inventory change
        $this->logInventoryChange($itemType, $quantity, 'increase');
        
        return $inventory;
    }
    
    public function decreaseStock($itemType, $quantity)
    {
        $inventory = Inventory::where('item_type', $itemType)->first();
        
        if (!$inventory || $inventory->quantity < $quantity) {
            throw new \Exception("Insufficient inventory for {$itemType}");
        }
        
        $inventory->quantity -= $quantity;
        $inventory->last_updated = Carbon::now();
        $inventory->save();
        
        // Log inventory change
        $this->logInventoryChange($itemType, $quantity, 'decrease');
        
        return $inventory;
    }
    
    private function logInventoryChange($itemType, $quantity, $action)
    {
        // Create inventory log entry
        InventoryLog::create([
            'item_type' => $itemType,
            'quantity' => $quantity,
            'action' => $action,
            'user_id' => auth()->id(),
            'created_at' => Carbon::now()
        ]);
    }
}


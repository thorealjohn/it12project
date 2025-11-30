<?php
// app/Models/InventoryTransaction.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventoryTransaction extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'inventory_item_id',
        'user_id',
        'quantity_change',
        'transaction_type',
        'order_id',
        'notes',
    ];

    /**
     * Get inventory item for this transaction
     */
    public function inventoryItem()
    {
        return $this->belongsTo(InventoryItem::class);
    }

    /**
     * Get user who performed this transaction
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get order associated with this transaction
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
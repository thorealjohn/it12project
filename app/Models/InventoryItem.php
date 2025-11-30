<?php
// app/Models/InventoryItem.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventoryItem extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'description',
        'quantity',
        'threshold',
        'type',
    ];

    /**
     * Check if item is below threshold
     * 
     * @return bool
     */
    public function isLowStock()
    {
        return $this->quantity <= $this->threshold;
    }

    /**
     * Get transactions for this item
     */
    public function transactions()
    {
        return $this->hasMany(InventoryTransaction::class);
    }
}
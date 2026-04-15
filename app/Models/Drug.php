<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use App\Traits\BelongsToPharmacy; // 1. Import the Trait

class Drug extends Model
{
    // 2. Use the Trait inside the class
    // This automatically handles filtering by pharmacy and assigning pharmacy_id on creation
    use HasFactory, SoftDeletes, BelongsToPharmacy; 

    protected $fillable = [
        'name',
        'generic_name',
        'sku',
        'barcode',
        'category_id',
        'supplier_id',
        'description',
        'dosage_form',
        'strength',
        'unit',
        'cost_price',
        'selling_price',
        'quantity_in_stock',
        'reorder_level',
        'requires_prescription',
        'is_controlled',
        'storage_condition',
        'pharmacy_id', // 3. Add pharmacy_id to fillable
        'slug',        // 4. Add slug to fillable (used in controllers)
    ];

    // Ensure these are cast as booleans/numbers
    protected $casts = [
        'requires_prescription' => 'boolean',
        'is_controlled' => 'boolean',
        'cost_price' => 'decimal:2',
        'selling_price' => 'decimal:2',
    ];


    /**
     * Relationships
     */

    // 5. New Relationship for Multi-tenancy
    public function pharmacy()
    {
        return $this->belongsTo(Pharmacy::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function batches()
    {
        return $this->hasMany(DrugBatch::class);
    }

    public function saleItems()
    {
        return $this->hasMany(SaleItem::class);
    }

    public function inventoryMovements()
    {
        return $this->hasMany(InventoryMovement::class);
    }

    /**
     * Active batches relationship for eager loading in POS
     */
    public function activeBatches()
    {
        return $this->hasMany(DrugBatch::class)
            ->where('quantity_remaining', '>', 0)
            ->where('expiry_date', '>', Carbon::now())
            ->orderBy('expiry_date');
    }

    /**
     * Convenience accessor (optional)
     */
    public function getActiveBatchesAttribute()
    {
        // Use the relationship to get a collection
        return $this->activeBatches()->get();
    }

    /**
     * Check if drug is low on stock
     */
    public function isLowStock(): bool
    {
        return $this->quantity_in_stock <= $this->reorder_level;
    }

    /**
     * Calculate profit margin %
     */
    public function getProfitMarginAttribute(): float
    {
        if ($this->cost_price == 0) return 0;
        return round((($this->selling_price - $this->cost_price) / $this->cost_price) * 100, 2);
    }
}

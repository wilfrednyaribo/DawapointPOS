<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\BelongsToPharmacy; // 1. Import the Trait

class PurchaseItem extends Model
{
    // 2. Use the Trait
    // This ensures purchase items are automatically scoped to the user's pharmacy
    use HasFactory, BelongsToPharmacy;

    protected $fillable = [
        'purchase_id',
        'drug_id',
        'batch_number',
        'quantity',
        'unit_cost',
        'total',
        'pharmacy_id', // 3. Add pharmacy_id to fillable
    ];

    /**
     * Relationship to Pharmacy (Multi-tenancy)
     */
    public function pharmacy()
    {
        return $this->belongsTo(Pharmacy::class);
    }
    
    public function drug()
    {
        return $this->belongsTo(Drug::class);
    }

    public function purchase()
    {
        return $this->belongsTo(Purchase::class);
    }
}
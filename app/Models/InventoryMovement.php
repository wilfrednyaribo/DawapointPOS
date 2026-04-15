<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\BelongsToPharmacy; // 1. Import the Trait

class InventoryMovement extends Model
{
    // 2. Use the Trait
    // This ensures movements are automatically scoped to the user's pharmacy
    use BelongsToPharmacy;

    protected $fillable = [
        'drug_id',
        'drug_batch_id',
        'type',
        'quantity',
        'quantity_before',
        'quantity_after',
        'reference_type',
        'reference_id',
        'user_id',
        'notes',
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

    public function batch()
    {
        return $this->belongsTo(DrugBatch::class, 'drug_batch_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\BelongsToPharmacy; // 1. Import the Trait

class SaleItem extends Model
{
    // 2. Use the Trait
    // This ensures sale items are automatically scoped to the user's pharmacy
    use BelongsToPharmacy;

    protected $fillable = [
        'sale_id',
        'drug_id',
        'drug_batch_id',
        'drug_name',
        'batch_number',
        'quantity',
        'unit_price',
        'discount',
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

    public function sale()
    {
        return $this->belongsTo(Sale::class);
    }

    public function drug()
    {
        return $this->belongsTo(Drug::class);
    }

    public function batch()
    {
        return $this->belongsTo(DrugBatch::class, 'drug_batch_id');
    }
}
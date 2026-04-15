<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\BelongsToPharmacy; // 1. Import the Trait

class DrugBatch extends Model
{
    // 2. Use the Trait
    // This ensures batches are automatically scoped to the user's pharmacy
    use BelongsToPharmacy;

    protected $fillable = [
        'drug_id',
        'batch_number',
        'expiry_date',
        'manufacturing_date',
        'quantity_received',
        'quantity_remaining',
        'purchase_price',
        'received_date',
        'pharmacy_id', // 3. Add pharmacy_id to fillable
    ];

    protected $casts = [
        'expiry_date' => 'date',
        'manufacturing_date' => 'date',
        'received_date' => 'date',
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

    public function isExpired(): bool
    {
        return $this->expiry_date < now();
    }

    public function isExpiringSoon(int $days = 90): bool
    {
        return $this->expiry_date <= now()->addDays($days) && !$this->isExpired();
    }

    public function getDaysUntilExpiryAttribute(): int
    {
        return now()->diffInDays($this->expiry_date, false);
    }
}
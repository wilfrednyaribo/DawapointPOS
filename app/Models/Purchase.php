<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\BelongsToPharmacy; // 1. Import the Trait

class Purchase extends Model
{
    // 2. Use the Trait
    // This ensures purchases are automatically scoped to the user's pharmacy
    use HasFactory, BelongsToPharmacy;

    protected $fillable = [
        'supplier_id',
        'invoice_number',
        'purchase_date',
        'total_amount',
        'notes',
        'user_id',
        'status',
        'pharmacy_id', // 3. Add pharmacy_id to fillable
    ];

    /**
     * Relationship to Pharmacy (Multi-tenancy)
     */
    public function pharmacy()
    {
        return $this->belongsTo(Pharmacy::class);
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function items()
    {
        return $this->hasMany(PurchaseItem::class);
    }
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    protected $casts = [
        'purchase_date' => 'date', 
    ];
}
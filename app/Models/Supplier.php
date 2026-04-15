<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\BelongsToPharmacy; // 1. Import the Trait

class Supplier extends Model
{
    // 2. Use the Trait
    // This ensures suppliers are automatically scoped to the user's pharmacy
    use BelongsToPharmacy;

    protected $fillable = [
        'name',
        'contact_person',
        'phone',
        'email',
        'address',
        'pharmacy_id', // 3. Add pharmacy_id to fillable
    ];

    /**
     * Relationship to Pharmacy (Multi-tenancy)
     */
    public function pharmacy()
    {
        return $this->belongsTo(Pharmacy::class);
    }

    /**
     * A supplier can supply many drugs.
     * Use this to list which drugs a supplier usually provides.
     */
    public function drugs()
    {
        return $this->hasMany(Drug::class);
    }

    /**
     * A supplier has many purchase transactions.
     * Use this to see the history of invoices/orders from this supplier.
     */
    public function purchases()
    {
        return $this->hasMany(Purchase::class);
    }
}
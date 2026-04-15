<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\BelongsToPharmacy; // 1. Import the Trait

class Customer extends Model
{
    // 2. Use the Trait
    // This ensures customers are automatically scoped to the user's pharmacy
    use BelongsToPharmacy;

    protected $fillable = [
        'name',
        'phone',
        'email',
        'address',
        'date_of_birth',
        'gender',
        'allergies',
        'medical_conditions',
        'pharmacy_id', // 3. Add pharmacy_id to fillable
    ];

    protected $casts = [
        'date_of_birth' => 'date',
    ];

    /**
     * Relationship to Pharmacy (Multi-tenancy)
     */
    public function pharmacy()
    {
        return $this->belongsTo(Pharmacy::class);
    }

    public function sales()
    {
        return $this->hasMany(Sale::class);
    }

    public function getTotalSpentAttribute()
    {
        return $this->sales()->completed()->sum('total');
    }
}
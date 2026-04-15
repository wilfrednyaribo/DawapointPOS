<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use App\Traits\BelongsToPharmacy; // 1. Import the Trait

class Sale extends Model
{
    // 2. Use the Trait
    // This ensures sales are automatically scoped to the user's pharmacy
    use BelongsToPharmacy;

    protected $fillable = [
        'invoice_number',
        'customer_id',
        'customer_name',
        'user_id',
        'subtotal',
        'tax',
        'discount',
        'total',
        'amount_paid',
        'change_given',
        'payment_method',
        'status',
        'notes',
        'prescription_number',
        'prescriber_name',
        'pharmacy_id', // 3. Add pharmacy_id to fillable
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($sale) {
            // The BelongsToPharmacy trait handles assigning pharmacy_id automatically.
            
            // Generate invoice number if not provided
            if (!$sale->invoice_number) {
                // Because of the Global Scope in the Trait, this count() 
                // will automatically only count sales for the current pharmacy.
                $sale->invoice_number = 'INV-' . date('Ymd') . '-' . str_pad(Sale::whereDate('created_at', today())->count() + 1, 4, '0', STR_PAD_LEFT);
            }
        });
    }

    /**
     * Relationship to Pharmacy (Multi-tenancy)
     */
    public function pharmacy()
    {
        return $this->belongsTo(Pharmacy::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(SaleItem::class);
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopeToday($query)
    {
        return $query->whereDate('created_at', today());
    }

    public function scopeThisWeek($query)
    {
        return $query->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()]);
    }

    public function scopeThisMonth($query)
    {
        return $query->whereMonth('created_at', now()->month)
                     ->whereYear('created_at', now()->year);
    }
}
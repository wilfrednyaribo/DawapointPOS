<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;

class Pharmacy extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'code',                   // <--- ADDED THIS
        'email',
        'phone',
        'address',
        'is_active',
        'subscription_ends_at',    // <--- ADDED THIS
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'subscription_ends_at' => 'date',
    ];

    /**
     * Automatically generate a slug for the pharmacy when creating.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($pharmacy) {
            if (empty($pharmacy->slug)) {
                $pharmacy->slug = Str::slug($pharmacy->name);
            }
        });
    }

    /**
     * Get the pharmacy's code.
     * If not manually set, generate from name.
     */
    public function getCodeAttribute($value)
    {
        // If code exists in DB, return it
        if ($value) {
            return $value;
        }

        // Fallback: Generate from name "Shahsan Pharmacy" -> "SHA"
        $words = explode(' ', trim($this->name));
        return strtoupper(Str::substr($words[0], 0, 3));
    }

    /**
     * The users (staff) that belong to this pharmacy.
     */
    public function users()
    {
        return $this->hasMany(User::class);
    }

    /**
     * The drugs that belong to this pharmacy.
     */
    public function drugs()
    {
        return $this->hasMany(Drug::class);
    }

    /**
     * The sales made by this pharmacy.
     */
    public function sales()
    {
        return $this->hasMany(Sale::class);
    }

    /**
     * The customers registered at this pharmacy.
     */
    public function customers()
    {
        return $this->hasMany(Customer::class);
    }

    /**
     * The suppliers associated with this pharmacy.
     */
    public function suppliers()
    {
        return $this->hasMany(Supplier::class);
    }

    /**
     * The categories created by this pharmacy.
     */
    public function categories()
    {
        return $this->hasMany(Category::class);
    }

    /**
     * The purchases made by this pharmacy.
     */
    public function purchases()
    {
        return $this->hasMany(Purchase::class);
    }
    
    /**
     * Check if the pharmacy is active.
     */
    public function isActive(): bool
    {
        return $this->is_active;
    }

    public function subscriptionPayments()
    {
        return $this->hasMany(SubscriptionPayment::class)->latest();
    }
}
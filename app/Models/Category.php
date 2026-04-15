<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use App\Traits\BelongsToPharmacy; // 1. Import the Trait

class Category extends Model
{
    // 2. Use the Trait
    // This ensures categories are automatically scoped to the user's pharmacy
    use BelongsToPharmacy;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'pharmacy_id', // 3. Add pharmacy_id to fillable
    ];

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($category) {
            // Generate slug if not provided
            if (empty($category->slug)) {
                $category->slug = Str::slug($category->name);
            }
            
            // Note: The BelongsToPharmacy trait automatically handles 
            // assigning pharmacy_id on creation, so you don't need to do it manually here.
        });
    }

    /**
     * Relationship to Pharmacy (Multi-tenancy)
     */
    public function pharmacy()
    {
        return $this->belongsTo(Pharmacy::class);
    }

    public function drugs()
    {
        return $this->hasMany(Drug::class);
    }

    
}
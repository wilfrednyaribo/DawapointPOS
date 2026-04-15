<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;

trait BelongsToPharmacy
{
    protected static function bootBelongsToPharmacy()
    {
        // 1. Automatically assign pharmacy_id when creating a model
        static::creating(function ($model) {
            if (auth()->check() && auth()->user()->pharmacy_id && !$model->pharmacy_id) {
                $model->pharmacy_id = auth()->user()->pharmacy_id;
            }
        });

        // 2. Global Scope to filter queries automatically
        static::addGlobalScope('pharmacy', function (Builder $builder) {
            if (auth()->check()) {
                // If user is NOT Super Admin (has a pharmacy_id), filter results
                if (auth()->user()->pharmacy_id) {
                    
                    // FIX: Get the table name for the current model
                    // (e.g., 'sales', 'drugs', 'users')
                    $table = $builder->getModel()->getTable();

                    // FIX: Use table.pharmacy_id to avoid "Ambiguous column" error
                    $builder->where("{$table}.pharmacy_id", auth()->user()->pharmacy_id);
                }
            }
        });
    }
}
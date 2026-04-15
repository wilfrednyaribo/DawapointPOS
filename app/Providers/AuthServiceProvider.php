<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Gate::before(function ($user, $ability) {
            // 1. Super Admin Check
            // If user is ID 1 (You) or has no pharmacy_id (Global Admin)
            if ($user->id === 1 || $user->isSuperAdmin()) {
                return true;
            }

            // 2. Pharmacy Admin Check
            // If user has 'admin' role, grant access to all menus.
            // The BelongsToPharmacy trait handles data restriction.
            if ($user->hasRole('admin')) {
                return true;
            }
        });
    }
}
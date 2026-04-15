<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'pharmacy_id', // 1. IMPORTANT: Add this for Multi-tenancy
        'is_active',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean', // Add this to cast is_active as a boolean
        ];
    }

    // 2. RELATIONSHIP: Link to the Pharmacy
    public function pharmacy()
    {
        return $this->belongsTo(Pharmacy::class);
    }

    public function sales()
    {
        return $this->hasMany(Sale::class);
    }

    /**
     * 3. SUPER ADMIN CHECK (Global Owner)
     * Returns TRUE if the user is the Global Admin (You).
     * Logic: If they have no pharmacy_id assigned, they are the Super Admin.
     */
    public function isSuperAdmin(): bool
    {
        return is_null($this->pharmacy_id);
    }

    /**
     * 4. PHARMACY ADMIN CHECK
     * Returns TRUE if the user is an Admin of a specific pharmacy.
     * Logic: They have the role 'admin' AND they belong to a pharmacy.
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin' && !$this->isSuperAdmin();
    }

    public function isPharmacist(): bool
    {
        return $this->role === 'pharmacist';
    }
}
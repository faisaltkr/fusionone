<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    protected static function booted(): void
    {
        // Remove a company's related records whenever the company is deleted
        // (covers Filament single/bulk delete and the API delete).
        static::deleting(function (Company $company) {
            $company->clientPCs()->delete();
            $company->licenses()->delete();
            $company->sales()->delete();
            $company->purchases()->delete();

            // Delete the company's users, but never a super admin.
            $company->users()
                ->where('user_type', '!=', 'super_admin')
                ->delete();
        });
    }

    protected $fillable = [
        'name',
        'email',
        'unique_register_id',
        'address',
        'phone',
        'place',
        'contact_person',
        'activation_count',
        'allowed_devices',
        'active_devices',
        'status',
    ];

    public function clientPCs()
    {
        return $this->hasMany(NumberOfClientPC::class, 'company_id','id');
    }

    public function licenses()
    {
        return $this->hasMany(License::class, 'company_id', 'id');
    }

    public function users()
    {
        return $this->hasMany(User::class, 'company_id', 'id');
    }

    public function sales()
    {
        return $this->hasMany(Sale::class, 'company_id', 'id');
    }

    public function purchases()
    {
        return $this->hasMany(Purchase::class, 'company_id', 'id');
    }
}

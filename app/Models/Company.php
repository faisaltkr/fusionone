<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    protected static function booted(): void
    {
        // Remove a company's client PCs whenever the company is deleted
        // (covers Filament single/bulk delete and the API delete).
        static::deleting(function (Company $company) {
            $company->clientPCs()->delete();
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
}

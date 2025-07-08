<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Company extends Model
{
    
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($company) {
            if (empty($company->company_registration_id)) {
                $company->company_registration_id = (string) Str::uuid();
            }
        });
    }
}

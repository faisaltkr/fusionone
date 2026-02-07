<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Company extends Model
{
    
    protected $fillable = [
        'name',
        'email',
        'unique_register_id',
        'address',
        'phone',
        'place',
        'contact_person',
        'activation_count',
        'allowed_activations',
        'active_devices',
        'status',
    ];

    public function clientPCs()
    {
        return $this->hasMany(NumberOfClientPC::class, 'company_id','id');
    }
}

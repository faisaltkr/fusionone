<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    protected $fillable = [
        /* The code snippet you provided is from a PHP file defining a model class named `Client` in a
        Laravel application. */
        'name',
        'email',
        'phone',
        'unique_register_id',
        'contact_person',
        'place',
        'address',
        'status',
        'activation_count',
    ];

    public function clientPCs()
    {
        return $this->hasMany(NumberOfClientPC::class, 'client_id','id');
    }
}

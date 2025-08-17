<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NumberOfClientPC extends Model
{
    
    protected $table='number_of_client_pc';
    protected $fillable = [
        'company_id',
        'type',
        'app_id',
        'hardware_id',
        'latitude',
        'longitude',
        'pc_name',
        'status',
    ];

    public function company()
    {
        return $this->belongsTo(User::class, 'company_id', 'id');
    }
}

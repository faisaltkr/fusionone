<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Company;

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
        return $this->belongsTo(Company::class, 'company_id', 'id');
    }
}

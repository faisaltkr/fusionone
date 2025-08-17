<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $fillable = [
        'company_id',
        'branch_id',
        'entry_no',
        'purchase_purchase_return_no',
        'supplier_id',
        'supplier_name',
        'transaction_type', // sale or sale return
        'mode_of_transaction', // cash, credit, bank transfer, etc.
        'gross_amount',
        'discount',
        'net_amount',
        'vat_amount',
        'grand_amount',
        'tr_date'
    ];

    protected $casts = [
        'gross_amount' => 'decimal:2',
        'discount' => 'decimal:2',
        'net_amount' => 'decimal:2',
        'vat_amount' => 'decimal:2',
        'grand_amount' => 'decimal:2',
    ];
    protected $dates = [
        'created_at',
        'updated_at',
    ];
}

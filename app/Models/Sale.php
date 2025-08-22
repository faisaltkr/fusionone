<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Sale extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $fillable = [
        'company_id',
        'branch_id',
        'entry_no',
        'sales_sale_return_no',
        'customer_name',
        'transaction_type', // sale or sale return
        'customer_id',
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

    #[Scope]
    public function filterUserId($query)
    {
        if(Auth::user()->user_type!='super_admin')
        {
            return $query->where('company_id', Auth::id());
        }
    }
}

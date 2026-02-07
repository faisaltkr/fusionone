<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EInvoiceTransactionLog extends Model
{
    protected $fillable = [
        'inv_trn_log_id',
        'invoice_type',
        'invoice_id',
        'invoice_transaction_type',
        'invoice_date',
        'qr_code',
        'zatca_status',
        'invoice_base64',
        'invoice_file_name',
        'invoice_counter_value',
        'invoice_reported',
        'invoice_cleared',
        'invoice_hash',
        'buyer_name',
        'buyer_vat_no',
        'seller_name',
        'buyer_address',
        'seller_address',
        'seller_vat_no',
        'previous_invoice_hash',
        'validation_results',
        'error_results',
        'zatca_response_code',
        'einvoice_sync_time',
        'einvoice_uu_id',
        'einvoice_no',
        'resend'
    ];
}

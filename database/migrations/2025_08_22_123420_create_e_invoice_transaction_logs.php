<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {

        Schema::create('e_invoice_transaction_logs', function (Blueprint $table) {
            $table->id();
            $table->integer('inv_trn_log_id');
            $table->string('invoice_type', 50);
            $table->integer('invoice_id');
            $table->string('invoice_transaction_type', 50);
            $table->dateTime('invoice_date');
            $table->longText('qr_code')->nullable();
            $table->string('zatca_status', 50)->nullable();
            $table->longText('invoice_base64')->nullable();
            $table->string('invoice_file_name', 255)->nullable();
            $table->integer('invoice_counter_value')->nullable();
            $table->string('invoice_reported', 50)->nullable();
            $table->string('invoice_cleared', 50)->nullable();
            $table->string('invoice_hash', 255)->nullable();
            $table->string('buyer_name', 255)->nullable();
            $table->string('buyer_vat_no', 15)->nullable();
            $table->string('seller_name', 255)->nullable();
            $table->longText('buyer_address')->nullable();
            $table->longText('seller_address')->nullable();
            $table->string('seller_vat_no', 15)->nullable();
            $table->string('previous_invoice_hash', 255)->nullable();
            $table->longText('validation_results')->nullable();
            $table->longText('error_results')->nullable();
            $table->string('zatca_response_code', 10)->nullable();
            $table->dateTime('einvoice_sync_time')->nullable();
            $table->string('einvoice_uu_id', 150)->nullable();
            $table->string('einvoice_no', 50)->nullable();
            $table->boolean('resend')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('e_invoice_transaction_logs');
    }
};

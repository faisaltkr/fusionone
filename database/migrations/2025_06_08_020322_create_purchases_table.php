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
        Schema::create('purchases', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id')->nullable();
            $table->unsignedBigInteger('branch_id')->nullable();
            $table->integer('entry_no');
            $table->string('purchase_purchase_return_no');
            $table->integer('supplier_id')->nullable();
            $table->string('supplier_name');
            $table->enum('transaction_type',['purchase','purchase_return'])->default('purchase');// sale or sale return
            $table->enum('mode_of_transaction', ['cash','credit','bank'])->default('cash');// cash, credit, bank transfer, etc.
            $table->decimal('gross_amount', 10, 2)->default(0.00);
            $table->decimal('discount', 10, 2)->default(0.00);  
            $table->decimal('net_amount', 10, 2)->default(0.00);
            $table->decimal('vat_amount', 10, 2)->default(0.00);
            $table->decimal('grand_amount', 10, 2)->default(0.00);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchases');
    }
};

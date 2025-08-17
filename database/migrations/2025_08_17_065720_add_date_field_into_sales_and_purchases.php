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
      
        Schema::table('sales', function (Blueprint $table) {
            $table->date('tr_date')->nullable();
        });
        Schema::table('purchases', function (Blueprint $table) {
            $table->date('tr_date')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sales', function (Blueprint $table) {
            $table->dropColumn('tr_date');
        });
        Schema::table('purchases', function (Blueprint $table) {
            $table->dropColumn('tr_date');
        });
    }
};

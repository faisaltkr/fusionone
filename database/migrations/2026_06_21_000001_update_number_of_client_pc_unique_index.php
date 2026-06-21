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
        Schema::table('number_of_client_pc', function (Blueprint $table) {
            $table->dropUnique(['hardware_id']);
            $table->unique(['hardware_id', 'app_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('number_of_client_pc', function (Blueprint $table) {
            $table->dropUnique(['hardware_id', 'app_id']);
            $table->unique('hardware_id');
        });
    }
};

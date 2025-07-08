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
        Schema::create('number_of_client_pc', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id')->nullable();
            $table->enum('type',['server','client'])->default('client');
            $table->enum('app_id',['fusionOne','R-Pos','Pos'])->default('fusionOne');
            $table->string('hardware_id');
            $table->decimal('latitude')->nullable();
            $table->decimal('longitude')->nullable();
            $table->string('pc_name')->nullable();
            $table->enum('status',['activate','deactivate','surrender'])->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('number_of_client_pc');
    }
};

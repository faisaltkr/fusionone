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
        // Schema::create('companies', function (Blueprint $table) {
        //     $table->id(); // or $table->bigIncrements('id') if preferred
        //     $table->uuid('company_registration_id')->unique(); // UUID for registration
        //     $table->string('company_name');
        //     $table->string('contact_person');
        //     $table->string('place');
        //     $table->text('address');
        //     $table->string('phone');
        //     $table->boolean('status')->default(true);
        //     $table->timestamps();
        // });
    }

  



    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('companies');
    }
};

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
        Schema::create('companies', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('phone')->nullable();
            $table->uuid('unique_register_id')->unique(); // UUID for registration
            $table->string('contact_person');
            $table->string('place');
            $table->text('address');
            $table->boolean('status')->default(true);
            $table->integer('activation_count')->default(1);
            $table->integer('allowed_devices')->default(1);
            $table->integer('active_devices')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};

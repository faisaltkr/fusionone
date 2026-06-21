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
        Schema::create('licenses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->nullable()->constrained('companies')->nullOnDelete();
            $table->enum('license_type', ['demo', 'full'])->default('demo');
            $table->string('unique_register_id');
            $table->enum('app_id', ['fusionOne', 'R-Pos', 'Pos'])->default('fusionOne');
            $table->string('hardware_id');
            $table->dateTime('expiry')->nullable();
            $table->string('license_key')->nullable()->unique();
            $table->date('support_expiry_date')->nullable();
            $table->enum('status', ['pending', 'active', 'expired', 'revoked'])->default('pending');
            $table->dateTime('activated_at')->nullable();
            $table->timestamps();

            // One license per device per app.
            $table->unique(['hardware_id', 'app_id']);
            $table->index('unique_register_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('licenses');
    }
};

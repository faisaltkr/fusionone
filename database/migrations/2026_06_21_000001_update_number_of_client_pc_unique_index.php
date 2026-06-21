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
        $indexes = collect(Schema::getIndexes('number_of_client_pc'));

        Schema::table('number_of_client_pc', function (Blueprint $table) use ($indexes) {
            // Drop the single-column unique on hardware_id only if it exists.
            $singleColumnUnique = $indexes->first(fn ($index) => $index['unique']
                && $index['columns'] === ['hardware_id']);

            if ($singleColumnUnique) {
                $table->dropUnique($singleColumnUnique['name']);
            }

            // Add the composite unique only if it isn't already present.
            $compositeExists = $indexes->contains(fn ($index) => $index['unique']
                && $index['columns'] === ['hardware_id', 'app_id']);

            if (! $compositeExists) {
                $table->unique(['hardware_id', 'app_id']);
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $indexes = collect(Schema::getIndexes('number_of_client_pc'));

        Schema::table('number_of_client_pc', function (Blueprint $table) use ($indexes) {
            $compositeUnique = $indexes->first(fn ($index) => $index['unique']
                && $index['columns'] === ['hardware_id', 'app_id']);

            if ($compositeUnique) {
                $table->dropUnique($compositeUnique['name']);
            }

            $singleExists = $indexes->contains(fn ($index) => $index['unique']
                && $index['columns'] === ['hardware_id']);

            if (! $singleExists) {
                $table->unique('hardware_id');
            }
        });
    }
};

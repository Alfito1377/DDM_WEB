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
        Schema::table('logistic_scans', function (Blueprint $table) {
            $table->string('itemCode')->nullable()->after('barcode');
            $table->string('itemName')->nullable()->after('itemCode');
            $table->integer('weightKg')->nullable()->after('itemName');
            $table->string('palletCode')->nullable()->after('weightKg');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('logistic_scans', function (Blueprint $table) {
            $table->dropColumn('palletCode');
            $table->dropColumn('weightKg');
            $table->dropColumn('itemName');
            $table->dropColumn('itemCode');
        });
    }
};

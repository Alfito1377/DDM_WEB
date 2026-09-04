<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement("ALTER TABLE logistic MODIFY status ENUM('pending', 'packed', 'out_of_transit', 'in_transit', 'arrived', 'completed', 'cancelled') NOT NULL");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("ALTER TABLE logistic MODIFY status ENUM('pending', 'packed', 'out_of_transit', 'in_transit', 'completed', 'cancelled') NOT NULL");
    }
};
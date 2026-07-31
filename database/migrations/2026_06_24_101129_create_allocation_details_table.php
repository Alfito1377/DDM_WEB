<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('allocation_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('allocation_id')->constrained('allocations')->cascadeOnDelete();
            $table->string('barcode');
            $table->integer('quantity_allocated'); // Jumlah awal dikirim
            $table->integer('quantity_returned')->default(0); // Jumlah rusak/diretur (Akan terisi otomatis dari Controller Retur)
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('allocation_details');
    }
};
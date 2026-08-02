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
        Schema::create('logistic_scans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('logistic_id')->constrained('logistic')->onDelete('cascade'); 
            $table->string('sack_id')->nullable();
            $table->string('barcode');
            $table->timestamp('departed_at')->useCurrent();
            $table->timestamp('received_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('logistic_scans');
    }
};

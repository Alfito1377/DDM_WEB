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
        Schema::create('logistic', function (Blueprint $table) {
            $table->id();
            $table->uuid('id_logistic');
            $table->uuid('shipmentId');
            $table->enum('status', ['pending','packed','out_of_transit', 'in_transit', 'completed', 'cancelled']);
            $table->bigInteger('id_mitra');
            $table->foreign('id_mitra')->references('id')->on('jenis_mitra')->onDelete('cascade');
            $table->string('destination');
            $table->uuid('driverId');
            $table->uuid('vehicleId');
            $table->foreign('driverId')->references('id_driver')->on('driver')->onDelete('cascade');
            $table->foreign('vehicleId')->references('id_vehicle')->on('vehicle')->onDelete('cascade');
            $table->timestamp('departedAt')->nullable();
            $table->timestamp('arrivedAt')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('logistic');
    }
};

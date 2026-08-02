<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('return_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('return_id')->constrained('returns')->onDelete('cascade');
            $table->string('barcode');
            
            $table->integer('quantity');
            $table->enum('reason', ['Cacat', 'Rusak', 'Kedaluwarsa', 'Lainnya']);
            $table->string('proof_image_url'); // Path penyimpanan foto bukti
            
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('return_details');
    }
};
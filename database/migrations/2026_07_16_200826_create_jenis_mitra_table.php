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
        Schema::create('jenis_mitra', function (Blueprint $table) {
            $table->bigInteger('id', true); // Primary Key
            $table->string('nama_jenis_mitra', 100); // Nama Jenis Mitra
            $table->string('deskripsi', 255)->nullable(); // Deskripsi
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jenis_mitra');
    }
};

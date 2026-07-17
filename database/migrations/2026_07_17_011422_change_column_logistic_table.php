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
        Schema::table('logistic', function (Blueprint $table) {
            $table->dropForeign(['id_mitra']);
            $table->bigInteger('id_mitra')->unsigned()->change();
            $table->foreign('id_mitra')->references('id')->on('stores')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('logistic', function (Blueprint $table) {
            $table->dropForeign(['id_mitra']);
            $table->foreign('id_mitra')->references('id')->on('jenis_mitra')->onDelete('cascade');
        });
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('stores', function (Blueprint $table) {
            // Menambahkan kolom latitude dan longitude, set nullable agar boleh kosong di awal
            $table->string('latitude')->nullable()->after('address');
            $table->string('longitude')->nullable()->after('latitude');
        });
    }

    public function down()
    {
        Schema::table('stores', function (Blueprint $table) {
            // Menghapus kolom jika di-rollback
            $table->dropColumn(['latitude', 'longitude']);
        });
    }
};
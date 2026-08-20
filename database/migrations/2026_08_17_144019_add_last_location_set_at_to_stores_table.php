<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('stores', function (Blueprint $table) {
            // Tambahkan kolom untuk mencatat waktu (nullable)
            $table->timestamp('last_location_set_at')->nullable()->after('request_reset_lokasi');
        });
    }

    public function down()
    {
        Schema::table('stores', function (Blueprint $table) {
            $table->dropColumn('last_location_set_at');
        });
    }
};
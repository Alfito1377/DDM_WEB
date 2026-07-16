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
        Schema::table('stores', function (Blueprint $table) {
            $table->dropForeign(['sales_id']); // Hapus foreign key sales_id jika ada
            $table->dropColumn('sales_id'); // Hapus kolom sales_id dari tabel stores
            $table->bigInteger('jenis_mitra_id')->nullable()->after('id'); // Tambahkan kolom jenis_mitra_id baru setelah kolom id
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('stores', function (Blueprint $table) {
            $table->dropForeign(['jenis_mitra_id']); // Hapus foreign key jenis_mitra_id jika ada
            $table->unsignedBigInteger('sales_id')->nullable()->after('id'); // Kembalikan kolom sales_id
            $table->dropColumn('jenis_mitra_id'); // Hapus kolom jenis_mitra_id
        });
    }
};

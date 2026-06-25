<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('returns', function (Blueprint $table) {
            // Menggunakan tipe 'text' agar mampu menampung teks array JSON yang panjang
            // nullable() ditambahkan agar data lama (jika ada) tidak bentrok
            $table->text('proof_image')->nullable()->after('reason');
        });
    }

    public function down(): void
    {
        Schema::table('returns', function (Blueprint $table) {
            $table->dropColumn('proof_image');
        });
    }
};
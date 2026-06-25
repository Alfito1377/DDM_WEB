<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Menambahkan kolom store_id yang boleh kosong (nullable) 
            // karena Admin dan Manajer tidak memiliki toko
            $table->foreignId('store_id')
                  ->nullable()
                  ->after('role_id') // Meletakkan kolom ini setelah role_id
                  ->constrained('stores')
                  ->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['store_id']);
            $table->dropColumn('store_id');
        });
    }
};
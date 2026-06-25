<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('knowledge_bases', function (Blueprint $table) {
            $table->id();
            $table->string('title'); // Nama/Judul Dokumen
            $table->string('file_path'); // Jalur penyimpanan file di server
            $table->string('file_type'); // Ekstensi file (pdf, csv, txt, xlsx)
            $table->string('category'); // Kategori: 'Chatbot', 'Visualisasi', atau 'Umum'
            $table->bigInteger('file_size'); // Ukuran file dalam bytes
            $table->text('description')->nullable(); // Deskripsi singkat isi dokumen
            $table->foreignId('uploaded_by')->constrained('users')->cascadeOnDelete(); // ID Manajer yang mengunggah
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('knowledge_bases');
    }
};
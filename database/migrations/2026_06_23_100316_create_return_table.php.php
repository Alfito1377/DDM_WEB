<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('returns', function (Blueprint $table) {
            $table->id();
            $table->string('return_code')->unique(); // Contoh: RT-2026-06-01
            $table->foreignId('store_id')->constrained('stores')->onDelete('cascade');
            
            // manager_id bersifat nullable karena saat diajukan, belum ada yang approve
            $table->foreignId('manager_id')->nullable()->constrained('users')->onDelete('set null');
            
            $table->enum('status', ['Pending', 'Approved', 'Rejected'])->default('Pending');
            $table->timestamp('submitted_at')->useCurrent();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('returns');
    }
};
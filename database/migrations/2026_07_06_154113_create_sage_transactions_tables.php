<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // 1. Tabel Header Turn Over (Barang Masuk/Produksi)
        Schema::create('turnovers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('api_id')->unique()->nullable()->comment('ID asli dari API SAGE');
            $table->string('doc_no')->unique();
            $table->date('doc_date');
            $table->string('location')->nullable();
            $table->string('warehouse')->nullable();
            $table->decimal('total_kg', 12, 2)->default(0);
            $table->text('remark')->nullable();
            $table->date('posted_at')->nullable();
            $table->timestamps();
        });

        // 2. Tabel Detail Turn Over (Item Barang Masuk)
        Schema::create('turnover_details', function (Blueprint $table) {
            $table->id();
            $table->string('doc_no')->index()->comment('Relasi ke turnovers.doc_no');
            $table->string('batch_no')->nullable();
            $table->string('variety_code')->nullable()->comment('Relasi ke products.barcode lokal');
            $table->string('variety_name')->nullable();
            $table->string('product_name')->nullable();
            $table->string('unit_code')->nullable();
            $table->decimal('qty', 12, 2)->default(0);
            $table->decimal('total_kg', 12, 2)->default(0);
            $table->timestamps();
        });

        // 3. Tabel Header Delivery Receipt (Barang Keluar/Distribusi)
        Schema::create('delivery_receipts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('api_id')->unique()->nullable()->comment('ID asli dari API SAGE');
            $table->string('doc_no')->unique();
            $table->date('doc_date');
            $table->string('customer_name')->nullable();
            $table->string('order_no')->nullable();
            $table->text('remark')->nullable();
            $table->date('posted_at')->nullable();
            $table->timestamps();
        });

        // 4. Tabel Detail Delivery Receipt (Item Barang Keluar)
        Schema::create('delivery_receipt_details', function (Blueprint $table) {
            $table->id();
            $table->string('doc_no')->index()->comment('Relasi ke delivery_receipts.doc_no');
            $table->string('product_name')->nullable();
            $table->string('location')->nullable();
            $table->string('warehouse')->nullable();
            $table->string('lot_no')->nullable();
            $table->date('lot_expired')->nullable();
            $table->string('unit_code')->nullable();
            $table->decimal('qty', 12, 2)->default(0);
            $table->decimal('total_kg', 12, 2)->default(0);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('delivery_receipt_details');
        Schema::dropIfExists('delivery_receipts');
        Schema::dropIfExists('turnover_details');
        Schema::dropIfExists('turnovers');
    }
};
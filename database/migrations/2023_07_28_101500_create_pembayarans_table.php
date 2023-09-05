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
        Schema::create('pembayarans', function (Blueprint $table) {
            $table->id();
            $table->integer("user_id");
            $table->integer("pesan_id");
            $table->datetime("tanggal_pembelian");
            $table->integer("total_pembelian");
            $table->enum("status_pembelian", ["PENDING", "SUDAH PEMBAYARAN", "BARANG DIKIRIM"]);
            $table->string("bukti_pembayaran")->nullable();
            $table->string("resi_pengiriman", 100)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pembayarans');
    }
};

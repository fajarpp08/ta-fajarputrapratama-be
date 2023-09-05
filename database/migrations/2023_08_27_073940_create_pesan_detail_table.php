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
        Schema::create('pesan_detail', function (Blueprint $table) {
            $table->id();
            $table->integer("pesan_id");
            $table->string("nama_barang");
            $table->string("foto")->nullable();
            $table->string("deskripsi");
            $table->integer("harga");
            $table->integer("jumlah");
            $table->integer("jumlah_harga");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pesan_detail');
    }
};

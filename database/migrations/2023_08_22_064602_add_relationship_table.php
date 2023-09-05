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
        Schema::table('barangs', function (Blueprint $table) {

            $table->unsignedBigInteger('kategori_id')->nullable()->after("id");
             $table->foreign('kategori_id')
                ->references('id')
                ->on('kategoris')
                ->onUpdate('cascade')->onDelete('cascade');

        });
        Schema::table('pemesanans', function (Blueprint $table) {

            $table->unsignedBigInteger('barang_id')->nullable()->after("id");
             $table->foreign('barang_id')
                ->references('id')
                ->on('barangs')
                ->onUpdate('cascade')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};

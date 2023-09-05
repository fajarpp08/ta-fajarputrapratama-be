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
        Schema::create('pemesanans', function (Blueprint $table) {
            $table->id();
            $table->string('nomor')->unique();
            $table->enum('jk', ['None', 'Tuan', 'Nyonya', 'cancelled'])->default('None');
            $table->string('name');
            $table->double('phone');
            $table->date('time_from');
            $table->date('time_to')->nullable();
            $table->double('total');
            $table->double('harga');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pemesanans');
    }
};

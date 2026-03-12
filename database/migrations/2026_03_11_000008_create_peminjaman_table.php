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
        if (! Schema::hasTable('peminjaman')) {
            Schema::create('peminjaman', function (Blueprint $table) {
                $table->increments('PeminjamanID');
                $table->unsignedBigInteger('UserID');
                $table->unsignedInteger('BukuID');
                $table->date('TanggalPeminjaman');
                $table->date('TanggalPengembalian')->nullable();
                $table->enum('StatusPeminjaman', ['Belum', 'Selesai'])->default('Belum');

                $table->foreign('UserID')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
                $table->foreign('BukuID')->references('BukuID')->on('buku')->onUpdate('cascade')->onDelete('cascade');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('peminjaman');
    }
};

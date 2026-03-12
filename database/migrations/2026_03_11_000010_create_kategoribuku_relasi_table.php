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
        if (! Schema::hasTable('kategoribuku_relasi')) {
            Schema::create('kategoribuku_relasi', function (Blueprint $table) {
                $table->increments('KategoriBukuID');
                $table->unsignedInteger('BukuID');
                $table->unsignedInteger('KategoriID');

                $table->foreign('BukuID')->references('BukuID')->on('buku')->onUpdate('cascade')->onDelete('cascade');
                $table->foreign('KategoriID')->references('KategoriID')->on('kategoribuku')->onUpdate('cascade')->onDelete('cascade');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kategoribuku_relasi');
    }
};

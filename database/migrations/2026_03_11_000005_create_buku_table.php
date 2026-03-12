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
        if (! Schema::hasTable('buku')) {
            Schema::create('buku', function (Blueprint $table) {
                $table->increments('BukuID');
                $table->string('Judul', 255);
                $table->string('Penulis', 255);
                $table->string('Penerbit', 255);
                $table->integer('TahunTerbit');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('buku');
    }
};

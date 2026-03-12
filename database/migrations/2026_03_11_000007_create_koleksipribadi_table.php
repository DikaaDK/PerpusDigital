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
        if (! Schema::hasTable('koleksipribadi')) {
            Schema::create('koleksipribadi', function (Blueprint $table) {
                $table->increments('KoleksiID');
                $table->unsignedBigInteger('UserID');
                $table->unsignedInteger('BukuID');

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
        Schema::dropIfExists('koleksipribadi');
    }
};

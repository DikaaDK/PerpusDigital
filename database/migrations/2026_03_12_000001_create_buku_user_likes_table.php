<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('buku_user')) {
            Schema::create('buku_user', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->unsignedInteger('buku_id');
            $table->foreign('buku_id')->references('BukuID')->on('buku')->cascadeOnDelete();
            $table->timestamps();
            $table->unique(['user_id', 'buku_id']);
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('buku_user');
    }
};

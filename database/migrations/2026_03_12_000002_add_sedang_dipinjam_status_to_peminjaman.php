<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('peminjaman')) {
            return;
        }

        DB::statement("ALTER TABLE `peminjaman` MODIFY COLUMN `StatusPeminjaman` ENUM('Belum','Sedang dipinjam','Selesai') NOT NULL DEFAULT 'Sedang dipinjam'");
        DB::statement("UPDATE `peminjaman` SET `StatusPeminjaman` = 'Sedang dipinjam' WHERE `StatusPeminjaman` = 'Belum'");
    }

    public function down(): void
    {
        if (! Schema::hasTable('peminjaman')) {
            return;
        }

        DB::statement("ALTER TABLE `peminjaman` MODIFY COLUMN `StatusPeminjaman` ENUM('Belum','Selesai') NOT NULL DEFAULT 'Belum'");
    }
};

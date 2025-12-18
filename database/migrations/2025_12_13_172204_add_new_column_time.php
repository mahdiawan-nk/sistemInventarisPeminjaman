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
        Schema::table('peminjamen', function (Blueprint $table) {
            $table->time('waktu_peminjaman')->after('tanggal_pinjam');
            $table->time('waktu_pengembalian')->after('tanggal_kembali');
            $table->time('aktual_waktu_pengembalian')->after('aktiual_tanggal_kembali');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('peminjamen', function (Blueprint $table) {
            $table->dropColumn('waktu_peminjaman');
            $table->dropColumn('waktu_pengembalian');
            $table->dropColumn('aktiual_waktu_pengembalian');
        });
    }
};

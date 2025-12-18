<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('peminjaman_logs', function (Blueprint $table) {
            $table->enum('old_approval_peminjaman', [
                'process',
                'approved',
                'rejected'
            ])->nullable()->change();
            $table->enum('new_approval_peminjaman', [
                'process',
                'approved',
                'rejected'
            ])->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('peminjaman_logs', function (Blueprint $table) {
            $table->enum('old_approval_peminjaman', [
                'prosess',
                'approved',
                'rejected'
            ])->nullable()->change();
            $table->enum('new_approval_peminjaman', [
                'prosess',
                'approved',
                'rejected'
            ])->nullable()->change();
        });
    }
};

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
        Schema::create('peminjaman_logs', function (Blueprint $table) {
            $table->id();

            $table->foreignId('peminjaman_id')
                ->constrained('peminjamen')
                ->cascadeOnDelete();

            // jenis aksi
            $table->enum('action', [
                'create',
                'update',
                'delete',
                'status_change',
                'approval_change'
            ]);

            // status peminjaman
            $table->enum('old_status_peminjaman', ['pinjam', 'kembali'])->nullable();
            $table->enum('new_status_peminjaman', ['pinjam', 'kembali'])->nullable();

            // approval peminjaman
            $table->enum('old_approval_peminjaman', [
                'prosess',
                'approved',
                'rejected'
            ])->nullable();

            $table->enum('new_approval_peminjaman', [
                'prosess',
                'approved',
                'rejected'
            ])->nullable();

            // detail aksi
            $table->text('action_detail')->nullable();

            // siapa yang melakukan
            $table->foreignId('performed_by')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('peminjaman_logs');
    }
};

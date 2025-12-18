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
        Schema::create('peminjamen', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();

            $table->string('code_data_pinjaman');
            $table->string('nama_peminjam')->nullable();
            $table->string('code_peminjam')->nullable();
            $table->string('no_hp')->nullable();
            $table->string('unit')->nullable();

            $table->string('keperluan');
            $table->date('tanggal_pinjam');
            $table->date('tanggal_kembali');
            $table->date('aktiual_tanggal_kembali')->nullable();

            $table->enum('status_peminjaman', ['pinjam', 'kembali'])
                ->default('pinjam');

            $table->enum('approval_peminjaman', ['process', 'approved', 'rejected'])
                ->default('process');

            $table->text('notes')->nullable();

            $table->unsignedBigInteger('created_by');

            $table->softDeletes();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();
            $table->foreign('created_by')->references('id')->on('users')->cascadeOnDelete();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('peminjamen');
    }
};

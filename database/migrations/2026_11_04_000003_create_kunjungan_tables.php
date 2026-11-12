<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('pengendali_antrean_harian', function (Blueprint $table) {
            $table->date('tanggal_operasional')->primary();
            $table->unsignedInteger('nomor_berikutnya')->default(1);
            $table->timestamp('diperbarui_pada')->useCurrent()->useCurrentOnUpdate();
        });

        Schema::create('kunjungan', function (Blueprint $table) {
            $table->id('id_kunjungan');
            $table->unsignedBigInteger('id_pasien');
            $table->date('tanggal_kunjungan')->index();
            $table->unsignedInteger('nomor_antrean');
            $table->time('waktu_pendaftaran');
            $table->text('keluhan_awal')->nullable();
            $table->string('status_kunjungan', 30)->default('menunggu')->index();
            $table->unsignedBigInteger('id_pengguna_pendaftar');
            $table->timestamp('dipanggil_pada')->nullable();
            $table->timestamp('dimulai_pada')->nullable();
            $table->timestamp('diselesaikan_pada')->nullable();
            $table->timestamp('dibatalkan_pada')->nullable();
            $table->unsignedBigInteger('id_pengguna_pengubah')->nullable();
            $table->timestamp('dibuat_pada')->useCurrent();
            $table->timestamp('diperbarui_pada')->useCurrent()->useCurrentOnUpdate();
            $table->unique(['tanggal_kunjungan', 'nomor_antrean']);
            $table->foreign('id_pasien')->references('id_pasien')->on('pasien')->restrictOnDelete();
            $table->foreign('id_pengguna_pendaftar')->references('id_pengguna')->on('pengguna')->restrictOnDelete();
            $table->foreign('id_pengguna_pengubah')->references('id_pengguna')->on('pengguna')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kunjungan');
        Schema::dropIfExists('pengendali_antrean_harian');
    }
};

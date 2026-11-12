<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('rekam_medis', function (Blueprint $table) {
            $table->id('id_rekam_medis');
            $table->unsignedBigInteger('id_kunjungan')->unique();
            $table->unsignedBigInteger('id_pengguna_dokter');
            $table->text('anamnesis')->nullable();
            $table->text('hasil_pemeriksaan')->nullable();
            $table->json('tanda_vital')->nullable();
            $table->text('rencana_terapi')->nullable();
            $table->text('instruksi_tindak_lanjut')->nullable();
            $table->string('status_rekam_medis', 30)->default('draf');
            $table->timestamp('difinalisasi_pada')->nullable();
            $table->timestamp('diperbarui_pada')->useCurrent()->useCurrentOnUpdate();
            $table->timestamp('dibuat_pada')->useCurrent();
            $table->foreign('id_kunjungan')->references('id_kunjungan')->on('kunjungan')->restrictOnDelete();
            $table->foreign('id_pengguna_dokter')->references('id_pengguna')->on('pengguna')->restrictOnDelete();
        });
        Schema::create('diagnosa', function (Blueprint $table) {
            $table->id('id_diagnosa'); $table->unsignedBigInteger('id_rekam_medis'); $table->string('nama_diagnosa', 255); $table->string('jenis_diagnosa', 30)->default('utama'); $table->text('catatan_diagnosa')->nullable(); $table->timestamp('dibuat_pada')->useCurrent(); $table->foreign('id_rekam_medis')->references('id_rekam_medis')->on('rekam_medis')->cascadeOnDelete();
        });
        Schema::create('tindakan_medis', function (Blueprint $table) {
            $table->id('id_tindakan_medis'); $table->unsignedBigInteger('id_rekam_medis'); $table->string('nama_tindakan', 255); $table->text('catatan_tindakan')->nullable(); $table->timestamp('dilakukan_pada')->nullable(); $table->timestamp('dibuat_pada')->useCurrent(); $table->foreign('id_rekam_medis')->references('id_rekam_medis')->on('rekam_medis')->cascadeOnDelete();
        });
        Schema::create('obat', function (Blueprint $table) {
            $table->id('id_obat'); $table->string('nama_obat', 150); $table->string('kekuatan_obat', 80)->nullable(); $table->string('bentuk_sediaan', 80)->nullable(); $table->string('satuan_obat', 40)->nullable(); $table->boolean('status_aktif')->default(true); $table->timestamp('dibuat_pada')->useCurrent(); $table->timestamp('diperbarui_pada')->useCurrent()->useCurrentOnUpdate(); $table->index(['nama_obat', 'status_aktif']);
        });
        Schema::create('resep', function (Blueprint $table) {
            $table->id('id_resep'); $table->unsignedBigInteger('id_kunjungan'); $table->unsignedBigInteger('id_rekam_medis'); $table->unsignedBigInteger('id_pengguna_dokter'); $table->string('nomor_resep', 50)->unique(); $table->string('status_resep', 30)->default('draf')->index(); $table->text('catatan_resep')->nullable(); $table->timestamp('difinalisasi_pada')->nullable(); $table->timestamp('dibatalkan_pada')->nullable(); $table->text('alasan_pembatalan')->nullable(); $table->timestamp('dibuat_pada')->useCurrent(); $table->timestamp('diperbarui_pada')->useCurrent()->useCurrentOnUpdate(); $table->foreign('id_kunjungan')->references('id_kunjungan')->on('kunjungan')->restrictOnDelete(); $table->foreign('id_rekam_medis')->references('id_rekam_medis')->on('rekam_medis')->restrictOnDelete(); $table->foreign('id_pengguna_dokter')->references('id_pengguna')->on('pengguna')->restrictOnDelete();
        });
        Schema::create('rincian_resep', function (Blueprint $table) {
            $table->id('id_rincian_resep'); $table->unsignedBigInteger('id_resep'); $table->unsignedBigInteger('id_obat'); $table->string('nama_obat_saat_diresepkan', 150); $table->string('kekuatan_saat_diresepkan', 80)->nullable(); $table->string('bentuk_sediaan_saat_diresepkan', 80)->nullable(); $table->string('dosis', 100); $table->string('frekuensi_pemberian', 100); $table->string('cara_pemberian', 100)->nullable(); $table->unsignedSmallInteger('lama_penggunaan_hari')->nullable(); $table->decimal('jumlah_diresepkan', 10, 2); $table->string('satuan_jumlah', 40); $table->text('petunjuk_penggunaan'); $table->timestamp('dibuat_pada')->useCurrent(); $table->timestamp('diperbarui_pada')->useCurrent()->useCurrentOnUpdate(); $table->foreign('id_resep')->references('id_resep')->on('resep')->cascadeOnDelete(); $table->foreign('id_obat')->references('id_obat')->on('obat')->restrictOnDelete();
        });
    }
    public function down(): void { foreach (['rincian_resep','resep','obat','tindakan_medis','diagnosa','rekam_medis'] as $table) Schema::dropIfExists($table); }
};

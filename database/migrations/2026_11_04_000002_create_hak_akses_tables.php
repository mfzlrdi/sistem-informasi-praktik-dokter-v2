<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('hak_akses', function (Blueprint $table) {
            $table->id('id_hak_akses');
            $table->string('nama_hak_akses', 80)->unique();
            $table->string('keterangan_hak_akses')->nullable();
            $table->timestamp('dibuat_pada')->useCurrent();
            $table->timestamp('diperbarui_pada')->useCurrent()->useCurrentOnUpdate();
        });
        Schema::create('peran_hak_akses', function (Blueprint $table) {
            $table->unsignedBigInteger('id_peran');
            $table->unsignedBigInteger('id_hak_akses');
            $table->primary(['id_peran', 'id_hak_akses']);
            $table->foreign('id_peran')->references('id_peran')->on('peran')->cascadeOnDelete();
            $table->foreign('id_hak_akses')->references('id_hak_akses')->on('hak_akses')->cascadeOnDelete();
        });
        Schema::create('pasien', function (Blueprint $table) {
            $table->id('id_pasien');
            $table->string('nomor_rekam_medis', 40)->unique();
            $table->string('nama_lengkap', 150)->index();
            $table->string('nomor_identitas', 80)->nullable()->index();
            $table->date('tanggal_lahir')->nullable();
            $table->string('jenis_kelamin', 30)->nullable();
            $table->text('alamat')->nullable();
            $table->string('nomor_telepon', 30)->nullable()->index();
            $table->string('alamat_email', 191)->nullable();
            $table->text('catatan_administratif')->nullable();
            $table->timestamp('dibuat_pada')->useCurrent();
            $table->timestamp('diperbarui_pada')->useCurrent()->useCurrentOnUpdate();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pasien');
        Schema::dropIfExists('peran_hak_akses');
        Schema::dropIfExists('hak_akses');
    }
};

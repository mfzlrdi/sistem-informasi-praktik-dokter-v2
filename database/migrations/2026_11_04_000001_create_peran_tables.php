<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('peran', function (Blueprint $table) {
            $table->id('id_peran');
            $table->string('nama_peran', 40)->unique();
            $table->string('keterangan_peran')->nullable();
            $table->timestamp('dibuat_pada')->useCurrent();
            $table->timestamp('diperbarui_pada')->useCurrent()->useCurrentOnUpdate();
        });

        Schema::create('pengguna_peran', function (Blueprint $table) {
            $table->unsignedBigInteger('id_pengguna');
            $table->unsignedBigInteger('id_peran');
            $table->primary(['id_pengguna', 'id_peran']);
            $table->foreign('id_pengguna')->references('id_pengguna')->on('pengguna')->cascadeOnDelete();
            $table->foreign('id_peran')->references('id_peran')->on('peran')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pengguna_peran');
        Schema::dropIfExists('peran');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('kunjungan', function (Blueprint $table): void {
            $table->timestamp('tiket_dicetak_pada')->nullable()->after('id_pengguna_pendaftar');
        });
    }

    public function down(): void
    {
        Schema::table('kunjungan', function (Blueprint $table): void {
            $table->dropColumn('tiket_dicetak_pada');
        });
    }
};

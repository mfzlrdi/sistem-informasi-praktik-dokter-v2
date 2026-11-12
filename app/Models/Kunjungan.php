<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Kunjungan extends Model
{
    protected $table = 'kunjungan';
    protected $primaryKey = 'id_kunjungan';
    public const CREATED_AT = 'dibuat_pada';
    public const UPDATED_AT = 'diperbarui_pada';
    protected $fillable = ['id_pasien', 'tanggal_kunjungan', 'nomor_antrean', 'waktu_pendaftaran', 'keluhan_awal', 'status_kunjungan', 'id_pengguna_pendaftar', 'tiket_dicetak_pada', 'dipanggil_pada', 'dimulai_pada', 'diselesaikan_pada', 'dibatalkan_pada', 'id_pengguna_pengubah'];
    protected function casts(): array { return ['tanggal_kunjungan' => 'date', 'tiket_dicetak_pada' => 'datetime', 'dipanggil_pada' => 'datetime', 'dimulai_pada' => 'datetime', 'diselesaikan_pada' => 'datetime', 'dibatalkan_pada' => 'datetime']; }
    public function pasien(): BelongsTo { return $this->belongsTo(Pasien::class, 'id_pasien', 'id_pasien'); }
    public function pendaftar(): BelongsTo { return $this->belongsTo(User::class, 'id_pengguna_pendaftar', 'id_pengguna'); }
    public function pengubah(): BelongsTo { return $this->belongsTo(User::class, 'id_pengguna_pengubah', 'id_pengguna'); }
    public function rekamMedis(): HasOne { return $this->hasOne(RekamMedis::class, 'id_kunjungan', 'id_kunjungan'); }
    public function nomorAntrean(): string { return str_pad((string) $this->nomor_antrean, 3, '0', STR_PAD_LEFT); }
}

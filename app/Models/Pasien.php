<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Pasien extends Model
{
    protected $table = 'pasien';
    protected $primaryKey = 'id_pasien';
    public const CREATED_AT = 'dibuat_pada';
    public const UPDATED_AT = 'diperbarui_pada';
    protected $fillable = ['nomor_rekam_medis', 'nama_lengkap', 'nomor_identitas', 'tanggal_lahir', 'jenis_kelamin', 'alamat', 'nomor_telepon', 'alamat_email', 'catatan_administratif'];
    protected function casts(): array { return ['tanggal_lahir' => 'date']; }
    public function kunjungan(): HasMany { return $this->hasMany(Kunjungan::class, 'id_pasien', 'id_pasien'); }
}

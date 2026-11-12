<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
class RekamMedis extends Model
{
    protected $table='rekam_medis'; protected $primaryKey='id_rekam_medis'; public const CREATED_AT='dibuat_pada'; public const UPDATED_AT='diperbarui_pada'; protected $fillable=['id_kunjungan','id_pengguna_dokter','anamnesis','hasil_pemeriksaan','tanda_vital','rencana_terapi','instruksi_tindak_lanjut','status_rekam_medis','difinalisasi_pada'];
    protected function casts(): array { return ['tanda_vital'=>'array','difinalisasi_pada'=>'datetime']; }
    public function kunjungan(): BelongsTo { return $this->belongsTo(Kunjungan::class,'id_kunjungan','id_kunjungan'); }
    public function dokter(): BelongsTo { return $this->belongsTo(User::class,'id_pengguna_dokter','id_pengguna'); }
    public function diagnosa(): HasMany { return $this->hasMany(Diagnosa::class,'id_rekam_medis','id_rekam_medis'); }
    public function tindakan(): HasMany { return $this->hasMany(TindakanMedis::class,'id_rekam_medis','id_rekam_medis'); }
    public function resep(): HasMany { return $this->hasMany(Resep::class,'id_rekam_medis','id_rekam_medis'); }
}

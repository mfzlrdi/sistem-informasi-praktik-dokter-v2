<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
class Resep extends Model { protected $table='resep'; protected $primaryKey='id_resep'; public const CREATED_AT='dibuat_pada'; public const UPDATED_AT='diperbarui_pada'; protected $fillable=['id_kunjungan','id_rekam_medis','id_pengguna_dokter','nomor_resep','status_resep','catatan_resep','difinalisasi_pada','dibatalkan_pada','alasan_pembatalan']; protected $casts=['difinalisasi_pada'=>'datetime','dibatalkan_pada'=>'datetime']; public function rincian(): HasMany{return $this->hasMany(RincianResep::class,'id_resep','id_resep');} public function rekamMedis(){return $this->belongsTo(RekamMedis::class,'id_rekam_medis','id_rekam_medis');} }

<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class RincianResep extends Model { protected $table='rincian_resep'; protected $primaryKey='id_rincian_resep'; public const CREATED_AT='dibuat_pada'; public const UPDATED_AT='diperbarui_pada'; protected $fillable=['id_resep','id_obat','nama_obat_saat_diresepkan','kekuatan_saat_diresepkan','bentuk_sediaan_saat_diresepkan','dosis','frekuensi_pemberian','cara_pemberian','lama_penggunaan_hari','jumlah_diresepkan','satuan_jumlah','petunjuk_penggunaan']; public function obat(){return $this->belongsTo(Obat::class,'id_obat','id_obat');} }

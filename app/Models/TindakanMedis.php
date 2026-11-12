<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class TindakanMedis extends Model { protected $table='tindakan_medis'; protected $primaryKey='id_tindakan_medis'; public const CREATED_AT='dibuat_pada'; public const UPDATED_AT=null; protected $fillable=['id_rekam_medis','nama_tindakan','catatan_tindakan','dilakukan_pada']; public function rekamMedis(){return $this->belongsTo(RekamMedis::class,'id_rekam_medis','id_rekam_medis');} }

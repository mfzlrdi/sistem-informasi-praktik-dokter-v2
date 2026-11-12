<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Diagnosa extends Model { protected $table='diagnosa'; protected $primaryKey='id_diagnosa'; public const CREATED_AT='dibuat_pada'; public const UPDATED_AT=null; protected $fillable=['id_rekam_medis','nama_diagnosa','jenis_diagnosa','catatan_diagnosa']; public function rekamMedis(){return $this->belongsTo(RekamMedis::class,'id_rekam_medis','id_rekam_medis');} }

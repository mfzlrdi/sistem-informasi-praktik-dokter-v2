<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class PemenuhanResep extends Model { protected $table='pemenuhan_resep';protected $primaryKey='id_pemenuhan_resep';public const CREATED_AT='dibuat_pada';public const UPDATED_AT='diperbarui_pada';protected $fillable=['id_resep','status_pemenuhan','id_pengguna_penanggung_jawab','catatan_pemenuhan','mulai_diproses_pada','selesai_diproses_pada'];protected $casts=['mulai_diproses_pada'=>'datetime','selesai_diproses_pada'=>'datetime'];public function resep(){return $this->belongsTo(Resep::class,'id_resep','id_resep');}public function penanggungJawab(){return $this->belongsTo(User::class,'id_pengguna_penanggung_jawab','id_pengguna');}}

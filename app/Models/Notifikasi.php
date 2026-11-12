<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Notifikasi extends Model { protected $table='notifikasi';protected $primaryKey='id_notifikasi';public const CREATED_AT='dibuat_pada';public const UPDATED_AT=null;protected $fillable=['id_pengguna_penerima','jenis_notifikasi','judul_notifikasi','pesan_notifikasi','data_tambahan','kunci_peristiwa','dibaca_pada'];protected $casts=['data_tambahan'=>'array','dibaca_pada'=>'datetime'];public function pengguna(){return $this->belongsTo(User::class,'id_pengguna_penerima','id_pengguna');}public function belumDibaca():bool{return $this->dibaca_pada===null;}}

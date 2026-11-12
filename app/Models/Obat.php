<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Obat extends Model { protected $table='obat'; protected $primaryKey='id_obat'; public const CREATED_AT='dibuat_pada'; public const UPDATED_AT='diperbarui_pada'; protected $fillable=['nama_obat','kekuatan_obat','bentuk_sediaan','satuan_obat','status_aktif']; protected $casts=['status_aktif'=>'boolean']; }

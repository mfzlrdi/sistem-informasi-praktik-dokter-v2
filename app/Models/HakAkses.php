<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class HakAkses extends Model
{
    protected $table = 'hak_akses';
    protected $primaryKey = 'id_hak_akses';
    public const CREATED_AT = 'dibuat_pada';
    public const UPDATED_AT = 'diperbarui_pada';
    protected $fillable = ['nama_hak_akses', 'keterangan_hak_akses'];

    public function peran(): BelongsToMany { return $this->belongsToMany(Role::class, 'peran_hak_akses', 'id_hak_akses', 'id_peran'); }
}

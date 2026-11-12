<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Role extends Model
{
    protected $table = 'peran';
    protected $primaryKey = 'id_peran';
    public const CREATED_AT = 'dibuat_pada';
    public const UPDATED_AT = 'diperbarui_pada';
    protected $fillable = ['nama_peran', 'keterangan_peran'];

    public function pengguna(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'pengguna_peran', 'id_peran', 'id_pengguna');
    }

    public function hakAkses(): BelongsToMany
    {
        return $this->belongsToMany(HakAkses::class, 'peran_hak_akses', 'id_peran', 'id_hak_akses');
    }
}

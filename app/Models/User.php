<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['nama_lengkap', 'alamat_email', 'kata_sandi', 'status_aktif'])]
#[Hidden(['kata_sandi', 'token_ingatan'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    protected $table = 'pengguna';
    protected $primaryKey = 'id_pengguna';
    public const CREATED_AT = 'dibuat_pada';
    public const UPDATED_AT = 'diperbarui_pada';

    public function getAuthPassword(): string
    {
        return $this->kata_sandi;
    }

    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'pengguna_peran', 'id_pengguna', 'id_peran');
    }

    public function memilikiPeran(string $namaPeran): bool
    {
        return $this->roles->contains('nama_peran', $namaPeran);
    }

    public function hakAkses()
    {
        return $this->roles()->with('hakAkses')->get()->flatMap->hakAkses->unique('id_hak_akses');
    }

    public function notifikasi() { return $this->hasMany(Notifikasi::class, 'id_pengguna_penerima', 'id_pengguna'); }

    public function memilikiHakAkses(string $namaHakAkses): bool
    {
        return $this->roles()->whereHas('hakAkses', fn ($query) => $query->where('nama_hak_akses', $namaHakAkses))->exists();
    }

    protected function casts(): array
    {
        return [
            'email_verified_pada' => 'datetime',
            'kata_sandi' => 'hashed',
            'status_aktif' => 'boolean',
            'terakhir_masuk_pada' => 'datetime',
        ];
    }
}

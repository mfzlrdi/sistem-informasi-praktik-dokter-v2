<?php

namespace App\Policies;

use App\Models\Pasien;
use App\Models\User;

class PasienPolicy
{
    public function viewAny(User $user): bool { return $user->memilikiHakAkses('pasien.lihat'); }
    public function view(User $user, Pasien $pasien): bool { return $user->memilikiHakAkses('pasien.lihat'); }
    public function create(User $user): bool { return $user->memilikiHakAkses('pasien.tambah'); }
    public function update(User $user, Pasien $pasien): bool { return $user->memilikiHakAkses('pasien.ubah'); }
}

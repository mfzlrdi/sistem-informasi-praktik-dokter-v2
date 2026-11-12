<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $peran = collect([
            'dokter' => 'Akses antrean dan pencatatan klinis.',
            'resepsionis' => 'Akses administrasi pasien dan pendaftaran.',
            'administrator' => 'Akses pengaturan dan administrasi sistem.',
        ])->mapWithKeys(fn (string $keterangan, string $nama) => [
            $nama => Role::updateOrCreate(['nama_peran' => $nama], ['keterangan_peran' => $keterangan]),
        ]);

        $hakAkses = collect([
            'pasien.lihat' => 'Melihat data pasien.',
            'pasien.tambah' => 'Menambahkan pasien.',
            'pasien.ubah' => 'Mengubah data pasien.',
            'pengguna.role.ubah' => 'Mengubah role pengguna.',
            'kunjungan.lihat' => 'Melihat antrean dan kunjungan administratif.',
            'kunjungan.daftar' => 'Mendaftarkan kunjungan pasien.',
            'antrean.kelola' => 'Mengubah status antrean sesuai kewenangan.',
            'klinis.lihat' => 'Melihat catatan klinis sesuai kewenangan.',
            'klinis.ubah' => 'Membuat dan mengubah catatan klinis.',
            'resep.kelola' => 'Membuat dan memfinalisasi resep.',
            'pemenuhan.lihat' => 'Melihat tugas pemenuhan resep.',
            'pemenuhan.ubah' => 'Mengubah status tugas pemenuhan sesuai kewenangan.',
            'laporan.lihat' => 'Melihat laporan pelayanan sesuai kewenangan.',
            'obat.lihat' => 'Melihat katalog obat klinis.',
            'obat.kelola' => 'Menambah, mengubah, dan menonaktifkan obat.'
        ])->mapWithKeys(fn (string $keterangan, string $nama) => [$nama => \App\Models\HakAkses::updateOrCreate(['nama_hak_akses' => $nama], ['keterangan_hak_akses' => $keterangan])]);
        $peran['dokter']->hakAkses()->syncWithoutDetaching([$hakAkses['pasien.lihat']->id_hak_akses]);
        $peran['resepsionis']->hakAkses()->syncWithoutDetaching([$hakAkses['pasien.lihat']->id_hak_akses, $hakAkses['pasien.tambah']->id_hak_akses, $hakAkses['pasien.ubah']->id_hak_akses]);
        $peran['dokter']->hakAkses()->syncWithoutDetaching([$hakAkses['kunjungan.lihat']->id_hak_akses, $hakAkses['antrean.kelola']->id_hak_akses, $hakAkses['klinis.lihat']->id_hak_akses, $hakAkses['klinis.ubah']->id_hak_akses, $hakAkses['resep.kelola']->id_hak_akses]);
        $peran['resepsionis']->hakAkses()->syncWithoutDetaching([$hakAkses['kunjungan.lihat']->id_hak_akses, $hakAkses['kunjungan.daftar']->id_hak_akses, $hakAkses['pemenuhan.lihat']->id_hak_akses, $hakAkses['pemenuhan.ubah']->id_hak_akses, $hakAkses['laporan.lihat']->id_hak_akses]);
        $peran['dokter']->hakAkses()->syncWithoutDetaching([$hakAkses['laporan.lihat']->id_hak_akses]);
        $peran['administrator']->hakAkses()->syncWithoutDetaching([$hakAkses['laporan.lihat']->id_hak_akses, $hakAkses['obat.lihat']->id_hak_akses, $hakAkses['obat.kelola']->id_hak_akses]);
        $peran['dokter']->hakAkses()->syncWithoutDetaching([$hakAkses['obat.lihat']->id_hak_akses, $hakAkses['obat.kelola']->id_hak_akses]);
        $peran['administrator']->hakAkses()->syncWithoutDetaching([$hakAkses['pasien.lihat']->id_hak_akses, $hakAkses['pasien.tambah']->id_hak_akses, $hakAkses['pasien.ubah']->id_hak_akses, $hakAkses['pengguna.role.ubah']->id_hak_akses, $hakAkses['kunjungan.lihat']->id_hak_akses]);

        foreach ([['Parasetamol','500 mg','tablet','tablet'],['Amoksisilin','500 mg','kapsul','kapsul'],['Salbutamol','2 mg','tablet','tablet']] as [$nama,$kekuatan,$bentuk,$satuan]) { \App\Models\Obat::updateOrCreate(['nama_obat'=>$nama,'kekuatan_obat'=>$kekuatan],['bentuk_sediaan'=>$bentuk,'satuan_obat'=>$satuan,'status_aktif'=>true]); }

        $dokter = User::updateOrCreate(
            ['alamat_email' => 'dokter.demo@praktik.test'],
            ['nama_lengkap' => 'dr. Sumarwanto (Demo)', 'kata_sandi' => Hash::make('password-demo'), 'status_aktif' => true],
        );
        $dokter->roles()->syncWithoutDetaching([$peran['dokter']->id_peran, $peran['administrator']->id_peran]);

        $resepsionis = User::updateOrCreate(
            ['alamat_email' => 'resepsionis.demo@praktik.test'],
            ['nama_lengkap' => 'Resepsionis Demo', 'kata_sandi' => Hash::make('password-demo'), 'status_aktif' => true],
        );
        $resepsionis->roles()->syncWithoutDetaching([$peran['resepsionis']->id_peran]);
    }
}

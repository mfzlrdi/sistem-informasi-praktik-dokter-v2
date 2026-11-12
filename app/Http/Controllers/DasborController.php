<?php

namespace App\Http\Controllers;

use App\Models\Kunjungan;
use App\Models\Pasien;
use App\Models\PemenuhanResep;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class DasborController extends Controller
{
    public function pilih(): RedirectResponse
    {
        $pengguna = request()->user();

        return $pengguna->memilikiPeran('dokter')
            ? redirect()->route('dasbor.dokter')
            : ($pengguna->memilikiPeran('resepsionis')
                ? redirect()->route('dasbor.resepsionis')
                : redirect()->route('dasbor.administrator'));
    }

    public function dokter(): View
    {
        return $this->halaman('dokter', 'Dasbor Dokter', 'Ruang kerja untuk antrean konsultasi dan pencatatan klinis.');
    }

    public function resepsionis(): View
    {
        return $this->halaman('resepsionis', 'Dasbor Resepsionis', 'Ruang kerja untuk administrasi pasien dan pendaftaran.');
    }

    public function administrator(): View
    {
        return $this->halaman('administrator', 'Dasbor Administrator', 'Ruang kerja untuk pengaturan dan administrasi sistem.');
    }

    private function halaman(string $jenis, string $judul, string $deskripsi): View
    {
        $pengguna = request()->user();
        $kunjunganHariIni = Kunjungan::whereDate('tanggal_kunjungan', now()->toDateString());
        $statusAntreanAktif = ['menunggu', 'dipanggil', 'sedang_konsultasi'];
        $dapatMelihatAntrean = $jenis === 'dokter' && $pengguna->memilikiHakAkses('kunjungan.lihat');

        $statistik = [
            'pasien_hari_ini' => (clone $kunjunganHariIni)->distinct('id_pasien')->count('id_pasien'),
            'antrean' => (clone $kunjunganHariIni)->whereIn('status_kunjungan', $statusAntreanAktif)->count(),
            'selesai' => (clone $kunjunganHariIni)->where('status_kunjungan', 'selesai')->count(),
            'tugas' => (int) PemenuhanResep::whereNotIn('status_pemenuhan', ['diserahkan', 'dibatalkan'])->count(),
            'pasien' => (int) Pasien::count(),
            'pengguna_aktif' => (int) User::where('status_aktif', true)->count(),
        ];

        $antreanHariIni = $dapatMelihatAntrean
            ? (clone $kunjunganHariIni)
                ->with('pasien')
                ->whereIn('status_kunjungan', $statusAntreanAktif)
                ->orderBy('nomor_antrean')
                ->limit(5)
                ->get()
            : collect();

        $pasienBerikutnya = $antreanHariIni->first();

        $dapatMelihatKeluhan = $dapatMelihatAntrean && $pengguna->memilikiHakAkses('klinis.lihat');

        return view('dasbor.halaman', compact(
            'jenis',
            'judul',
            'deskripsi',
            'statistik',
            'antreanHariIni',
            'pasienBerikutnya',
            'dapatMelihatAntrean',
            'dapatMelihatKeluhan',
        ));
    }
}

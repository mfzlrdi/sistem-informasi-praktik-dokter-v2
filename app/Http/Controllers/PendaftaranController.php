<?php

namespace App\Http\Controllers;

use App\Models\Pasien;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PendaftaranController extends Controller
{
    public function index(Request $request): View
    {
        $pengguna = $request->user();
        $bolehDaftarPasien = $pengguna->can('create', Pasien::class);
        $bolehDaftarKunjungan = $pengguna->memilikiHakAkses('kunjungan.daftar');

        abort_unless($bolehDaftarPasien || $bolehDaftarKunjungan, 403);

        $adaPasienTerdaftar = $bolehDaftarKunjungan && Pasien::query()->exists();
        $cariPasien = trim($request->validate([
            'q' => ['nullable', 'string', 'max:100'],
        ])['q'] ?? '');
        $hasilPencarianPasien = collect();

        if ($bolehDaftarKunjungan && $cariPasien !== '') {
            $polaPencarian = "%{$cariPasien}%";
            $hasilPencarianPasien = Pasien::query()
                ->where(function ($query) use ($polaPencarian): void {
                    $query->where('nomor_rekam_medis', 'like', $polaPencarian)
                        ->orWhere('nama_lengkap', 'like', $polaPencarian)
                        ->orWhere('nomor_telepon', 'like', $polaPencarian)
                        ->orWhere('nomor_identitas', 'like', $polaPencarian);
                })
                ->orderBy('nama_lengkap')
                ->limit(20)
                ->get(['id_pasien', 'nomor_rekam_medis', 'nama_lengkap', 'nomor_telepon']);
        }

        return view('pendaftaran.index', compact(
            'bolehDaftarPasien',
            'bolehDaftarKunjungan',
            'adaPasienTerdaftar',
            'cariPasien',
            'hasilPencarianPasien',
        ));
    }
}

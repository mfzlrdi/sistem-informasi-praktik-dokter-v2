<?php

namespace App\Http\Controllers;

use App\Http\Requests\SimpanKunjunganRequest;
use App\Models\Kunjungan;
use App\Models\Pasien;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use App\Services\NotifikasiOperasionalService;

class KunjunganController extends Controller
{
    public function create(Request $request): View
    {
        abort_unless($request->user()->memilikiHakAkses('kunjungan.daftar'), 403);
        $pasien = Pasien::orderBy('nama_lengkap')->get(['id_pasien', 'nomor_rekam_medis', 'nama_lengkap', 'nomor_telepon']);
        $pasienTerpilih = $pasien->firstWhere('id_pasien', $request->integer('id_pasien'));

        return view('kunjungan.create', compact('pasien', 'pasienTerpilih'));
    }

    public function store(SimpanKunjunganRequest $request): RedirectResponse
    {
        $kunjungan = DB::transaction(function () use ($request) {
            $tanggal = $request->date('tanggal_kunjungan');
            $pengendali = DB::table('pengendali_antrean_harian')->where('tanggal_operasional', $tanggal->toDateString())->lockForUpdate()->first();
            if (! $pengendali) {
                DB::table('pengendali_antrean_harian')->insert(['tanggal_operasional' => $tanggal->toDateString(), 'nomor_berikutnya' => 2, 'diperbarui_pada' => now()]);
                $nomor = 1;
            } else {
                $nomor = $pengendali->nomor_berikutnya;
                DB::table('pengendali_antrean_harian')->where('tanggal_operasional', $tanggal->toDateString())->update(['nomor_berikutnya' => $nomor + 1, 'diperbarui_pada' => now()]);
            }
            return Kunjungan::create([...$request->validated(), 'nomor_antrean' => $nomor, 'waktu_pendaftaran' => now()->format('H:i:s'), 'status_kunjungan' => 'menunggu', 'id_pengguna_pendaftar' => $request->user()->id_pengguna]);
        });
        app(NotifikasiOperasionalService::class)->kirimUntukPermission('antrean.kelola', 'kunjungan_baru', 'Pasien baru terdaftar', 'Antrean A-'.$kunjungan->nomorAntrean().' masuk ke antrean konsultasi.', ['id_kunjungan'=>$kunjungan->id_kunjungan,'nomor_antrean'=>$kunjungan->nomorAntrean()], 'kunjungan-baru-'.$kunjungan->id_kunjungan);
        return redirect()->route('kunjungan.tiket', $kunjungan)->with('status', 'Pendaftaran kunjungan berhasil dibuat.');
    }

    public function ticket(Kunjungan $kunjungan): View
    {
        abort_unless(request()->user()->memilikiHakAkses('kunjungan.lihat'), 403);
        $kunjungan->load('pasien');
        return view('kunjungan.tiket', compact('kunjungan'));
    }
}

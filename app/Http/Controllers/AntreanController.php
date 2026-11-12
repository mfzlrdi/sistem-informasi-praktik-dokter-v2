<?php

namespace App\Http\Controllers;

use App\Models\Kunjungan;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use App\Services\NotifikasiOperasionalService;

class AntreanController extends Controller
{
    public function index(Request $request): View
    {
        abort_unless($request->user()->memilikiHakAkses('kunjungan.lihat'), 403);
        $tanggal = $request->date('tanggal')?->toDateString() ?? now()->toDateString();
        $status = $request->string('status')->toString();
        $riwayat = $request->routeIs('riwayat.kunjungan');
        $kunjungan = $this->query($tanggal, $status, $riwayat)->paginate(12)->withQueryString();
        return view('antrean.index', compact('kunjungan', 'tanggal', 'status', 'riwayat'));
    }

    public function riwayat(Request $request): View
    {
        $request->merge(['status' => 'selesai']);
        return $this->index($request);
    }

    public function data(Request $request): JsonResponse
    {
        abort_unless($request->user()->memilikiHakAkses('kunjungan.lihat'), 403);
        $tanggal = $request->date('tanggal')?->toDateString() ?? now()->toDateString();
        return response()->json($this->query($tanggal, $request->string('status')->toString())->get()->map(fn (Kunjungan $item) => ['id_kunjungan' => $item->id_kunjungan, 'nomor_antrean' => $item->nomorAntrean(), 'nama_pasien' => $item->pasien->nama_lengkap, 'status_kunjungan' => $item->status_kunjungan, 'label_status' => $this->labelStatus($item->status_kunjungan), 'diperbarui_pada' => $item->diperbarui_pada?->toIso8601String()]));
    }

    public function update(Request $request, Kunjungan $kunjungan): RedirectResponse|JsonResponse
    {
        $pengguna = $request->user();
        $tujuan = $request->validate(['status_kunjungan' => ['required', 'in:dipanggil,dibatalkan,selesai']])['status_kunjungan'];
        $bolehDokterKelola = $pengguna->memilikiPeran('dokter') && $pengguna->memilikiHakAkses('antrean.kelola');
        $bolehBatalkan = $bolehDokterKelola || $pengguna->memilikiPeran('administrator');
        abort_unless($tujuan === 'dibatalkan' ? $bolehBatalkan : $bolehDokterKelola, 403);

        $kunjungan = DB::transaction(function () use ($kunjungan, $tujuan, $pengguna): Kunjungan {
            $kunjungan = Kunjungan::query()->lockForUpdate()->findOrFail($kunjungan->id_kunjungan);
            $transisi = [
                'menunggu' => ['dipanggil', 'dibatalkan'],
                'dipanggil' => ['sedang_konsultasi', 'dibatalkan'],
                'sedang_konsultasi' => ['selesai', 'dibatalkan'],
                'selesai' => [],
                'dibatalkan' => [],
            ];
            abort_unless(in_array($tujuan, $transisi[$kunjungan->status_kunjungan] ?? [], true), 422, 'Transisi status antrean tidak valid.');
            abort_if($tujuan === 'dipanggil' && ! $kunjungan->tiket_dicetak_pada, 422, 'Antrean hanya dapat dipanggil setelah tiket dicetak resepsionis.');

            $kunjungan->update([
                'status_kunjungan' => $tujuan,
                'id_pengguna_pengubah' => $pengguna->id_pengguna,
                'dipanggil_pada' => $tujuan === 'dipanggil' ? now() : $kunjungan->dipanggil_pada,
                'dimulai_pada' => $tujuan === 'sedang_konsultasi' ? now() : $kunjungan->dimulai_pada,
                'diselesaikan_pada' => $tujuan === 'selesai' ? now() : $kunjungan->diselesaikan_pada,
                'dibatalkan_pada' => $tujuan === 'dibatalkan' ? now() : $kunjungan->dibatalkan_pada,
            ]);

            return $kunjungan->load('pasien');
        });

        app(NotifikasiOperasionalService::class)->kirimUntukPermission('kunjungan.daftar', 'status_antrean', 'Status antrean diperbarui', 'Antrean A-'.$kunjungan->nomorAntrean().' sekarang berstatus '.str_replace('_',' ',$tujuan).'.', ['id_kunjungan'=>$kunjungan->id_kunjungan,'nomor_antrean'=>$kunjungan->nomorAntrean(),'status'=>$tujuan], 'status-antrean-'.$kunjungan->id_kunjungan.'-'.$tujuan);

        if ($request->expectsJson()) {
            return response()->json([
                'status' => $tujuan,
                'pesan' => 'Status antrean diperbarui.',
                'pengumuman' => $tujuan === 'dipanggil' ? 'Nomor antrean A-'.$kunjungan->nomorAntrean().', silakan menuju ruangan.' : null,
            ]);
        }

        return back()->with('status', 'Status antrean diperbarui.');
    }

    public function cetakTiket(Request $request, Kunjungan $kunjungan): RedirectResponse
    {
        $pengguna = $request->user();
        abort_unless($pengguna->memilikiHakAkses('kunjungan.daftar') || $pengguna->memilikiPeran('administrator'), 403);
        abort_unless($kunjungan->status_kunjungan !== 'dibatalkan', 422, 'Tiket tidak dapat dicetak untuk antrean yang telah dibatalkan.');

        $kunjungan->update(['tiket_dicetak_pada' => now()]);

        return back()->with('cetak_tiket', true)->with('status', 'Tiket antrean ditandai telah dicetak.');
    }

    private function query(string $tanggal, string $status = '', bool $riwayat = false)
    {
        $query = Kunjungan::with('pasien')->whereDate('tanggal_kunjungan', $tanggal);

        if ($riwayat) {
            return $query->where('status_kunjungan', 'selesai')->orderBy('nomor_antrean');
        }

        return $query
            ->whereIn('status_kunjungan', ['menunggu', 'dipanggil', 'sedang_konsultasi'])
            ->when($status, fn ($query) => $query->where('status_kunjungan', $status))
            ->orderBy('nomor_antrean');
    }
    private function labelStatus(string $status): string { return ['menunggu' => 'Menunggu', 'dipanggil' => 'Dipanggil', 'sedang_konsultasi' => 'Sedang Konsultasi', 'selesai' => 'Selesai', 'dibatalkan' => 'Dibatalkan'][$status] ?? $status; }
}

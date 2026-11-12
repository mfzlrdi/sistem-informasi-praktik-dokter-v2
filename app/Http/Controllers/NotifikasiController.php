<?php

namespace App\Http\Controllers;

use App\Models\Notifikasi;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class NotifikasiController extends Controller
{
    public function index(Request $request): View
    {
        $data = $request->validate(['status' => ['nullable', 'in:semua,belum,sudah'], 'kata_kunci' => ['nullable', 'string', 'max:100']]);
        $status = $data['status'] ?? 'semua';
        $kataKunci = trim($data['kata_kunci'] ?? '');
        $notifikasi = Notifikasi::where('id_pengguna_penerima', $request->user()->id_pengguna)->when($status === 'belum', fn ($q) => $q->whereNull('dibaca_pada'))->when($status === 'sudah', fn ($q) => $q->whereNotNull('dibaca_pada'))->when($kataKunci, fn ($q) => $q->where(fn ($sub) => $sub->where('judul_notifikasi', 'like', "%{$kataKunci}%")->orWhere('pesan_notifikasi', 'like', "%{$kataKunci}%")))->latest('dibuat_pada')->paginate(15)->withQueryString();
        $jumlahBelumDibaca = Notifikasi::where('id_pengguna_penerima', $request->user()->id_pengguna)->whereNull('dibaca_pada')->count();

        return view('notifikasi.index', compact('notifikasi', 'status', 'kataKunci', 'jumlahBelumDibaca'));
    }

    public function unread(Request $request): JsonResponse
    {
        $belumDibaca = Notifikasi::where('id_pengguna_penerima', $request->user()->id_pengguna)
            ->whereNull('dibaca_pada');

        return response()->json([
            'jumlah' => (clone $belumDibaca)->count(),
            'data' => $belumDibaca
                ->latest('dibuat_pada')
                ->limit(8)
                ->get()
                ->map(fn ($notifikasi) => [
                    'id_notifikasi' => $notifikasi->id_notifikasi,
                    'judul_notifikasi' => $notifikasi->judul_notifikasi,
                    'pesan_notifikasi' => $notifikasi->pesan_notifikasi,
                    'dibuat_pada' => $notifikasi->dibuat_pada?->diffForHumans(),
                    'data_tambahan' => $notifikasi->data_tambahan,
                ]),
        ]);
    }

    public function read(Request $request, Notifikasi $notifikasi): RedirectResponse
    {
        $this->own($request, $notifikasi);
        $notifikasi->update(['dibaca_pada' => now()]);

        return back();
    }

    public function readAll(Request $request): RedirectResponse
    {
        Notifikasi::where('id_pengguna_penerima', $request->user()->id_pengguna)->whereNull('dibaca_pada')->update(['dibaca_pada' => now()]);

        return back();
    }

    private function own(Request $r, Notifikasi $n): void
    {
        abort_unless($n->id_pengguna_penerima === $r->user()->id_pengguna, 403);
    }
}

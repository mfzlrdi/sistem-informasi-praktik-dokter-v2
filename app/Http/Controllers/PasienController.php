<?php

namespace App\Http\Controllers;

use App\Http\Requests\SimpanPasienRequest;
use App\Models\Pasien;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class PasienController extends Controller
{
    public function index(Request $request): View
    {
        abort_unless($request->user()->can('viewAny', Pasien::class), 403);
        $kataKunci = trim((string) $request->string('kata_kunci'));
        $pasien = Pasien::query()->when($kataKunci, fn ($query) => $query->where(fn ($q) => $q->where('nama_lengkap', 'like', "%{$kataKunci}%")->orWhere('nomor_rekam_medis', 'like', "%{$kataKunci}%")->orWhere('nomor_identitas', 'like', "%{$kataKunci}%")->orWhere('nomor_telepon', 'like', "%{$kataKunci}%")))->orderBy('nama_lengkap')->paginate(10)->withQueryString();
        return view('pasien.index', compact('pasien', 'kataKunci'));
    }

    public function create(Request $request): View { abort_unless($request->user()->can('create', Pasien::class), 403); return view('pasien.create'); }

    public function store(SimpanPasienRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['nomor_rekam_medis'] = $this->buatNomorRekamMedis();
        $pasien = Pasien::create($data);
        return redirect()->route('pasien.show', $pasien)->with('status', 'Data pasien berhasil ditambahkan.');
    }

    public function show(Request $request, Pasien $pasien): View { abort_unless($request->user()->can('view', $pasien), 403); return view('pasien.show', compact('pasien')); }
    public function edit(Request $request, Pasien $pasien): View { abort_unless($request->user()->can('update', $pasien), 403); return view('pasien.edit', compact('pasien')); }
    public function update(SimpanPasienRequest $request, Pasien $pasien): RedirectResponse { $pasien->update($request->validated()); return redirect()->route('pasien.show', $pasien)->with('status', 'Data pasien berhasil diperbarui.'); }

    private function buatNomorRekamMedis(): string
    {
        do { $nomor = 'RM-' . now()->format('Ymd') . '-' . Str::upper(Str::random(4)); } while (Pasien::where('nomor_rekam_medis', $nomor)->exists());
        return $nomor;
    }
}

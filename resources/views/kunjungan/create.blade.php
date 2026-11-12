@extends('layouts.aplikasi')

@section('title', 'Pendaftaran kunjungan | Praktik dr. Sumarwanto')

@section('content')
<div class="mx-auto max-w-4xl">
    <header class="mb-7">
        <p class="text-xs font-bold uppercase tracking-[0.18em] text-emerald-700">Pendaftaran</p>
        <h1 id="judul-pendaftaran-kunjungan" class="mt-2 text-3xl font-semibold">Pendaftaran kunjungan</h1>
        <p class="mt-2 text-sm text-slate-500">Pilih pasien yang sudah terdaftar, lalu catat keluhan awal secara administratif.</p>
    </header>

    @if($pasien->isEmpty())
        <section class="rounded-2xl border border-amber-200 bg-amber-50 p-6" role="status" aria-live="polite" aria-labelledby="judul-pasien-kosong">
            <div class="flex items-start gap-4">
                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-amber-100 text-amber-800" aria-hidden="true">
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7"><circle cx="10" cy="8" r="3"/><path d="M4.5 19c.5-3 2.3-4.5 5.5-4.5M18 14v6M15 17h6" stroke-linecap="round" stroke-linejoin="round"/></svg>
                </div>
                <div>
                    <h2 id="judul-pasien-kosong" class="font-semibold text-amber-950">Belum ada pasien terdaftar</h2>
                    <p class="mt-1 text-sm leading-6 text-amber-900">Kunjungan belum dapat dibuat karena belum ada pasien untuk dipilih. Daftarkan data pasien terlebih dahulu; setelah tersimpan, kembali ke sini untuk membuat kunjungan.</p>
                    @if($errors->has('id_pasien'))
                        <p class="mt-3 text-sm font-medium text-rose-700" role="alert">{{ $errors->first('id_pasien') }}</p>
                    @endif
                    <div class="mt-5 flex flex-wrap gap-3">
                        @can('create', App\Models\Pasien::class)
                            <a href="{{ route('pasien.create') }}" class="inline-flex min-h-11 items-center rounded-xl bg-emerald-700 px-4 py-3 text-sm font-semibold text-white transition hover:bg-emerald-800 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-emerald-500 focus-visible:ring-offset-2">Daftarkan pasien terlebih dahulu</a>
                        @else
                            <p class="self-center text-sm text-amber-950">Hubungi administrator agar pasien dapat didaftarkan.</p>
                        @endcan
                        <a href="{{ route('antrean.index') }}" class="inline-flex min-h-11 items-center rounded-xl border border-amber-300 bg-white px-4 py-3 text-sm font-semibold text-slate-800 transition hover:bg-amber-100 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-amber-500 focus-visible:ring-offset-2">Kembali ke antrean</a>
                    </div>
                </div>
            </div>
        </section>
    @else
        @if($errors->any())
            <div class="mb-5 rounded-xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-800" role="alert" aria-live="polite">
                Periksa kembali data yang ditandai sebelum menyimpan pendaftaran.
            </div>
        @endif

        <form method="POST" action="{{ route('kunjungan.store') }}" class="rounded-2xl border border-slate-200 bg-white p-6">
            @csrf
            <div class="grid gap-5 md:grid-cols-2">
                <div class="md:col-span-2">
                    @if($pasienTerpilih)
                        <div class="mb-3 rounded-xl border border-emerald-100 bg-emerald-50/70 px-4 py-3" role="status">
                            <p class="text-sm font-semibold text-emerald-900">Pasien dari hasil pencarian telah dipilih</p>
                            <p class="mt-1 text-sm text-emerald-800">{{ $pasienTerpilih->nama_lengkap }} <span aria-hidden="true">·</span> {{ $pasienTerpilih->nomor_rekam_medis }}</p>
                        </div>
                    @endif
                    <label for="id_pasien" class="mb-2 block text-sm font-semibold text-slate-800">Pasien <span class="text-rose-600" aria-hidden="true">*</span><span class="sr-only"> wajib diisi</span></label>
                    <select id="id_pasien" name="id_pasien" required aria-invalid="{{ $errors->has('id_pasien') ? 'true' : 'false' }}" aria-describedby="pasien-hint{{ $errors->has('id_pasien') ? ' pasien-error' : '' }}" class="w-full rounded-xl border px-4 py-3 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-emerald-500 {{ $errors->has('id_pasien') ? 'border-rose-400' : 'border-slate-300' }}">
                        <option value="">Pilih pasien</option>
                        @foreach($pasien as $item)
                            <option value="{{ $item->id_pasien }}" @selected(old('id_pasien', $pasienTerpilih?->id_pasien) == $item->id_pasien)>{{ $item->nomor_rekam_medis }} — {{ $item->nama_lengkap }}{{ $item->nomor_telepon ? ' · '.$item->nomor_telepon : '' }}</option>
                        @endforeach
                    </select>
                    <p id="pasien-hint" class="mt-2 text-xs leading-5 text-slate-500">Pastikan nama dan nomor rekam medis sesuai. Pasien yang dipilih akan terhubung langsung ke kunjungan ini.</p>
                    @error('id_pasien')
                        <p id="pasien-error" class="mt-1 text-sm text-rose-700" role="alert">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="tanggal_kunjungan" class="mb-2 block text-sm font-semibold text-slate-800">Tanggal kunjungan <span class="text-rose-600" aria-hidden="true">*</span><span class="sr-only"> wajib diisi</span></label>
                    <input id="tanggal_kunjungan" type="date" name="tanggal_kunjungan" value="{{ old('tanggal_kunjungan', now()->format('Y-m-d')) }}" required aria-invalid="{{ $errors->has('tanggal_kunjungan') ? 'true' : 'false' }}" aria-describedby="tanggal-hint{{ $errors->has('tanggal_kunjungan') ? ' tanggal-error' : '' }}" class="w-full rounded-xl border px-4 py-3 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-emerald-500 {{ $errors->has('tanggal_kunjungan') ? 'border-rose-400' : 'border-slate-300' }}">
                    <p id="tanggal-hint" class="mt-2 text-xs leading-5 text-slate-500">Tanggal yang dipilih menentukan urutan nomor antrean.</p>
                    @error('tanggal_kunjungan')
                        <p id="tanggal-error" class="mt-1 text-sm text-rose-700" role="alert">{{ $message }}</p>
                    @enderror
                </div>

                <aside class="rounded-xl border border-emerald-100 bg-emerald-50/60 p-4 text-sm text-slate-600" aria-label="Informasi nomor antrean">
                    <p class="font-semibold text-emerald-800">Nomor antrean otomatis</p>
                    <p class="mt-1 text-xs leading-5">Nomor dibuat oleh server setelah pendaftaran berhasil dan unik untuk tanggal kunjungan yang dipilih. Nomor akan terlihat pada tiket.</p>
                </aside>

                <div class="md:col-span-2">
                    <label for="keluhan_awal" class="mb-2 block text-sm font-semibold text-slate-800">Keluhan awal <span class="font-normal text-slate-400">(opsional)</span></label>
                    <textarea id="keluhan_awal" name="keluhan_awal" rows="4" maxlength="2000" aria-describedby="keluhan-hint{{ $errors->has('keluhan_awal') ? ' keluhan-error' : '' }}" placeholder="Catat informasi yang disampaikan pasien, tanpa diagnosis." class="w-full rounded-xl border px-4 py-3 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-emerald-500 {{ $errors->has('keluhan_awal') ? 'border-rose-400' : 'border-slate-300' }}">{{ old('keluhan_awal') }}</textarea>
                    <p id="keluhan-hint" class="mt-2 text-xs leading-5 text-slate-500">Catat keluhan dengan kalimat pasien. Diagnosis dan hasil pemeriksaan dicatat oleh dokter pada rekam medis.</p>
                    @error('keluhan_awal')
                        <p id="keluhan-error" class="mt-1 text-sm text-rose-700" role="alert">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="mt-7 flex flex-wrap gap-3">
                <button type="submit" class="min-h-11 rounded-xl bg-emerald-700 px-5 py-3 text-sm font-semibold text-white transition hover:bg-emerald-800 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-emerald-500 focus-visible:ring-offset-2">Simpan pendaftaran</button>
                <a href="{{ route('antrean.index') }}" class="inline-flex min-h-11 items-center rounded-xl border border-slate-300 px-5 py-3 text-sm font-semibold text-slate-800 transition hover:bg-slate-50 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-slate-400 focus-visible:ring-offset-2">Batal</a>
            </div>
        </form>
    @endif
</div>
@endsection

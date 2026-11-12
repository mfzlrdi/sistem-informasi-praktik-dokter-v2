@extends('layouts.aplikasi')

@section('content')
@php
    $statusAntrean = [
        'menunggu' => ['Menunggu', 'border-amber-200 bg-amber-50 text-amber-700'],
        'dipanggil' => ['Dipanggil', 'border-sky-200 bg-sky-50 text-sky-700'],
        'sedang_konsultasi' => ['Sedang Konsultasi', 'border-indigo-200 bg-indigo-50 text-indigo-700'],
    ];
@endphp
<div class="mx-auto max-w-[1260px]">
    <div class="flex flex-col justify-between gap-5 md:flex-row md:items-end">
        <div>
            <h1 class="text-[25px] font-semibold tracking-tight">Selamat pagi, {{ auth()->user()->nama_lengkap }}</h1>
            <p class="mt-2 text-sm text-slate-500">Ringkasan aktivitas berdasarkan database praktik.</p>
        </div>
        <div class="flex gap-2">
            @if(auth()->user()->memilikiHakAkses('kunjungan.daftar'))
                <a href="{{ route('kunjungan.create') }}" class="rounded-lg bg-emerald-700 px-4 py-2.5 text-xs font-semibold text-white">+ Pendaftaran baru</a>
            @endif
            @if(auth()->user()->memilikiHakAkses('laporan.lihat'))
                <a href="{{ route('laporan.index') }}" class="rounded-lg border border-slate-200 bg-white px-4 py-2.5 text-xs font-semibold text-slate-600">Lihat laporan</a>
            @endif
        </div>
    </div>

    <div class="mt-8 grid gap-4 xl:grid-cols-4">
        @foreach([['pasien_hari_ini','Total pasien hari ini','bg-emerald-50 text-emerald-600'],['antrean','Dalam antrean','bg-blue-50 text-blue-600'],['selesai','Konsultasi selesai','bg-violet-50 text-violet-600'],['tugas','Tugas tindak lanjut','bg-amber-50 text-amber-600']] as [$key, $label, $warna])
            <div class="rounded-xl border border-slate-200 bg-white p-5"><span class="inline-flex rounded-lg px-3 py-2 text-xs {{ $warna }}">Data aktual</span><p class="mt-4 text-2xl font-semibold">{{ $statistik[$key] }}</p><p class="mt-1 text-xs font-medium text-slate-600">{{ $label }}</p><p class="mt-2 text-[10px] text-slate-400">Dihitung dari data tersimpan</p></div>
        @endforeach
    </div>

    @if($jenis === 'dokter' && $dapatMelihatAntrean)
        <section class="mt-5 rounded-xl border border-teal-700 bg-teal-700 p-5 text-white shadow-sm sm:px-6 sm:py-5" aria-labelledby="pasien-berikutnya-heading">
            <div class="flex flex-col justify-between gap-5 sm:flex-row sm:items-center">
                <div class="min-w-0">
                    <p class="text-[11px] font-semibold uppercase tracking-[0.12em] text-teal-100">Pasien berikutnya</p>
                    @if($pasienBerikutnya)
                        @php
                            [$labelStatusBerikutnya, $kelasStatusBerikutnya] = $statusAntrean[$pasienBerikutnya->status_kunjungan] ?? ['Aktif', 'border-white/30 bg-white/10 text-white'];
                        @endphp
                        <h2 id="pasien-berikutnya-heading" class="mt-2 text-2xl font-semibold tracking-tight">A-{{ $pasienBerikutnya->nomorAntrean() }}</h2>
                        <p class="mt-1 text-sm font-medium text-white">{{ $pasienBerikutnya->pasien->nama_lengkap }}</p>
                        @if($dapatMelihatKeluhan && filled($pasienBerikutnya->keluhan_awal))
                            <p class="mt-1 max-w-2xl text-xs leading-5 text-teal-100">Keluhan awal: {{ $pasienBerikutnya->keluhan_awal }}</p>
                        @endif
                        <span class="mt-3 inline-flex items-center rounded-full border px-2.5 py-1 text-[11px] font-medium {{ $kelasStatusBerikutnya }}">{{ $labelStatusBerikutnya }}</span>
                    @else
                        <h2 id="pasien-berikutnya-heading" class="mt-2 text-lg font-semibold">Belum ada pasien menunggu</h2>
                        <p class="mt-1 text-xs text-teal-100">Pasien yang sedang dipanggil atau menunggu akan muncul di sini.</p>
                    @endif
                </div>
                <a href="{{ route('antrean.index') }}" class="inline-flex shrink-0 items-center justify-center gap-3 rounded-lg bg-white px-4 py-3 text-sm font-semibold text-teal-800 transition hover:bg-teal-50 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-white">
                    Buka antrean <span aria-hidden="true">→</span>
                </a>
            </div>
        </section>
    @endif

    <div class="mt-5 grid gap-5 xl:grid-cols-[minmax(0,1fr)_280px]">
        <section class="overflow-hidden rounded-xl border border-slate-200 bg-white" aria-labelledby="antrean-hari-ini-heading">
            <div class="flex items-start justify-between gap-4 border-b border-slate-100 px-5 py-5">
                <div>
                    <h2 id="antrean-hari-ini-heading" class="text-sm font-semibold">Antrean pasien hari ini</h2>
                    <p class="mt-1 text-[11px] text-slate-400">{{ $statistik['antrean'] }} pasien aktif hari ini.</p>
                </div>
                @if($jenis === 'dokter' && $dapatMelihatAntrean)
                    <a href="{{ route('antrean.index') }}" class="shrink-0 text-xs font-medium text-teal-700">Lihat semua <span aria-hidden="true">→</span></a>
                @endif
            </div>

            @if($jenis === 'dokter' && $dapatMelihatAntrean)
                @if($antreanHariIni->isNotEmpty())
                    <ol class="divide-y divide-slate-100" aria-label="Daftar antrean aktif">
                        @foreach($antreanHariIni as $kunjungan)
                            @php
                                [$labelStatus, $kelasStatus] = $statusAntrean[$kunjungan->status_kunjungan] ?? ['Aktif', 'border-slate-200 bg-slate-50 text-slate-600'];
                            @endphp
                            <li class="flex flex-col justify-between gap-3 px-5 py-4 sm:flex-row sm:items-center">
                                <div class="min-w-0">
                                    <p class="text-sm font-medium text-slate-900"><span class="font-semibold text-teal-700">A-{{ $kunjungan->nomorAntrean() }}</span> <span>{{ $kunjungan->pasien->nama_lengkap }}</span></p>
                                    @if($dapatMelihatKeluhan && filled($kunjungan->keluhan_awal))
                                        <p class="mt-1 text-xs text-slate-500">{{ $kunjungan->keluhan_awal }}</p>
                                    @endif
                                </div>
                                <span class="inline-flex w-fit shrink-0 items-center gap-1.5 rounded-full border px-2.5 py-1 text-[11px] font-medium {{ $kelasStatus }}">
                                    <span class="h-1.5 w-1.5 rounded-full bg-current opacity-70" aria-hidden="true"></span>
                                    {{ $labelStatus }}
                                </span>
                            </li>
                        @endforeach
                    </ol>
                @else
                    <div class="px-5 py-12 text-center">
                        <h3 class="text-sm font-semibold text-slate-700">Belum ada antrean aktif hari ini</h3>
                        <p class="mt-2 text-xs text-slate-400">Pasien yang terdaftar akan muncul di sini.</p>
                    </div>
                @endif
            @else
                <div class="px-5 py-12 text-center">
                    <div class="mx-auto h-10 w-10 rounded-full border border-dashed border-slate-200 bg-slate-50" aria-hidden="true"></div>
                    <h3 class="mt-4 text-sm font-semibold text-slate-700">Buka antrean untuk melihat detail</h3>
                    <p class="mx-auto mt-2 max-w-sm text-xs leading-5 text-slate-400">Nama pasien dan keluhan hanya ditampilkan pada halaman dengan izin akses yang sesuai.</p>
                </div>
            @endif
        </section>
        <div class="space-y-5"><section class="rounded-xl border border-slate-200 bg-white p-5"><h2 class="text-sm font-semibold">Ringkasan operasional</h2><div class="mt-5 space-y-4 text-xs"><div class="flex justify-between"><span class="text-slate-500">Pasien terdaftar</span><strong>{{ $statistik['pasien'] }}</strong></div><div class="flex justify-between"><span class="text-slate-500">Pengguna aktif</span><strong>{{ $statistik['pengguna_aktif'] }}</strong></div><div class="flex justify-between"><span class="text-slate-500">Tanggal laporan</span><strong>{{ now()->format('d/m/Y') }}</strong></div></div></section>
            <section class="rounded-xl border border-slate-200 bg-white p-5"><h2 class="text-sm font-semibold">Tugas resep</h2><p class="mt-1 text-[11px] text-slate-400">Memerlukan tindak lanjut</p>
                @if(auth()->user()->memilikiHakAkses('pemenuhan.lihat'))
                    <a href="{{ route('pemenuhan.index') }}" class="mt-5 block text-xs font-semibold text-emerald-700">Buka tugas →</a>
                @else
                    <div class="mt-5 text-xs text-slate-400">Tidak tersedia untuk akun ini.</div>
                @endif
            </section>
        </div>
    </div>
</div>
@endsection

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', $judul ?? 'Praktik dr. Sumarwanto')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body data-pengguna-id="{{ auth()->id() }}" class="min-h-screen bg-[#f5f8f8] text-slate-900 antialiased">
<div class="flex min-h-screen">
    <aside class="hidden w-[274px] shrink-0 border-r border-slate-200/80 bg-white lg:flex lg:flex-col">
        <div class="border-b border-slate-100 px-7 py-6">
            <a href="{{ route('dasbor.pilih') }}" aria-label="Kembali ke Dashboard" class="group flex items-center gap-3 rounded-xl outline-none transition focus-visible:ring-2 focus-visible:ring-emerald-500"><div class="flex h-9 w-9 items-center justify-center rounded-xl bg-[#dff3ef] text-emerald-700 transition group-hover:bg-emerald-100"><svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7"><path d="M4 11.5V9a2 2 0 0 1 2-2h2l1.5 3h5L16 7h2a2 2 0 0 1 2 2v2.5" stroke-linecap="round" stroke-linejoin="round"/><path d="M4 11.5h3l1 2.5h8l1-2.5h3M9 17h6M12 14v3" stroke-linecap="round" stroke-linejoin="round"/></svg></div><div><p class="text-sm font-bold text-slate-800 transition group-hover:text-emerald-700">Praktik dr. Sumarwanto</p><p class="text-[11px] text-slate-400">Administrasi Klinik</p></div></a>
            <div id="status-praktik" data-jam-buka="8" data-jam-tutup="20" class="mt-6 flex items-center gap-2 rounded-xl border px-3 py-2.5 text-[11px] {{ $praktikBuka ? 'border-emerald-100 bg-emerald-50/60 text-slate-600' : 'border-red-100 bg-red-50 text-red-700' }}"><span id="status-praktik-titik" class="h-2 w-2 rounded-full {{ $praktikBuka ? 'bg-emerald-500 ring-4 ring-emerald-100' : 'bg-red-500 ring-4 ring-red-100' }}"></span><span id="status-praktik-label" class="font-medium">{{ $praktikStatus }}</span><span class="text-slate-400">·</span><span>08:00–20:00</span><span id="status-praktik-keterangan" class="sr-only">{{ $praktikKeterangan }}</span></div>
        </div>
        <nav class="flex-1 px-4 py-7" aria-label="Navigasi utama">
            <p class="px-3 text-[10px] font-bold uppercase tracking-[0.18em] text-slate-400">Menu utama</p>
            @php($menuAktif = 'rounded-xl bg-[#e4f5f1] font-semibold text-emerald-700')
            @php($menuBiasa = 'rounded-xl text-slate-500 hover:bg-slate-50 hover:text-slate-700')
            <a href="{{ route('dasbor.pilih') }}" aria-label="Dashboard (Ringkasan Aktivitas Klinik)" aria-current="{{ request()->routeIs('dasbor.*') ? 'page' : 'false' }}" class="mt-3 flex items-center gap-3 px-3 py-3 text-[13px] {{ request()->routeIs('dasbor.*') ? $menuAktif : $menuBiasa }}"><svg class="h-[18px] w-[18px] shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7"><rect x="4" y="4" width="6" height="6" rx="1"/><rect x="14" y="4" width="6" height="6" rx="1"/><rect x="4" y="14" width="6" height="6" rx="1"/><rect x="14" y="14" width="6" height="6" rx="1"/></svg><span class="flex-1 leading-4"><span class="block font-semibold">Dashboard</span><span class="block text-[10px] leading-4 opacity-70">(Ringkasan Aktivitas Klinik)</span></span></a>
            @if(auth()->user()->memilikiHakAkses('kunjungan.lihat'))<a href="{{ route('antrean.index') }}" class="mt-1 flex items-center gap-3 px-3 py-3 text-[13px] {{ request()->routeIs('antrean.*','kunjungan.tiket') ? $menuAktif : $menuBiasa }}"><svg class="h-[18px] w-[18px] shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7"><circle cx="9" cy="8" r="3"/><path d="M3.5 19c.5-3 2.3-4.5 5.5-4.5s5 1.5 5.5 4.5M16 11a3 3 0 1 0 0-6M16 14.5c2.6.2 4 1.7 4.5 4.5" stroke-linecap="round"/></svg><span class="flex-1 leading-4"><span class="block">Antrean pasien</span><span class="block text-[10px] opacity-70">Pantau antrean aktif</span></span>@if($jumlahAntreanAktif > 0)<span class="grid h-6 min-w-6 place-items-center rounded-full bg-amber-400 px-1.5 text-[10px] font-bold text-white">{{ $jumlahAntreanAktif }}</span>@endif</a>@endif
            @if(auth()->user()->memilikiHakAkses('kunjungan.daftar') || auth()->user()->can('create', \App\Models\Pasien::class))
                @php($pendaftaranAktif = request()->routeIs('pendaftaran.*', 'kunjungan.create', 'pasien.create'))
                <a href="{{ route('pendaftaran.index') }}" aria-label="Pendaftaran pasien dan kunjungan" aria-current="{{ $pendaftaranAktif ? 'page' : 'false' }}" class="mt-1 flex items-center gap-3 px-3 py-3 text-[13px] {{ $pendaftaranAktif ? $menuAktif : $menuBiasa }}"><svg class="h-[18px] w-[18px] shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" aria-hidden="true"><rect x="5" y="4" width="14" height="16" rx="2"/><path d="M9 4V3h6v1M9 10h6M9 14h3M17 15v4M15 17h4" stroke-linecap="round" stroke-linejoin="round"/></svg><span class="flex-1 leading-4"><span class="block font-medium">Pendaftaran</span><span class="block text-[10px] opacity-70">Pasien dan kunjungan</span></span></a>
            @endif
            @if(auth()->user()->memilikiHakAkses('pasien.lihat'))<a href="{{ route('pasien.index') }}" class="flex items-center gap-3 px-3 py-3 text-[13px] {{ request()->routeIs('pasien.index','pasien.show','pasien.edit') ? $menuAktif : $menuBiasa }}"><svg class="h-[18px] w-[18px] shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7"><circle cx="12" cy="8" r="3.5"/><path d="M5 20c.6-4 2.8-6 7-6s6.4 2 7 6" stroke-linecap="round"/></svg><span class="flex-1 leading-4"><span class="block">Data pasien</span><span class="block text-[10px] opacity-70">Kelola data pasien</span></span></a>@endif
            @if(auth()->user()->memilikiHakAkses('obat.lihat'))<a href="{{ route('obat.index') }}" class="flex items-center gap-3 px-3 py-3 text-[13px] {{ request()->routeIs('obat.*') ? $menuAktif : $menuBiasa }}"><svg class="h-[18px] w-[18px] shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7"><rect x="5" y="5" width="14" height="14" rx="2"/><path d="m9 9 6 6M15 9l-6 6" stroke-linecap="round"/></svg><span class="flex-1 leading-4"><span class="block">Katalog obat</span><span class="block text-[10px] opacity-70">Kelola stok obat</span></span></a>@elseif(auth()->user()->memilikiHakAkses('kunjungan.lihat'))<a href="{{ route('riwayat.kunjungan') }}" class="flex items-center gap-3 px-3 py-3 text-[13px] {{ request()->routeIs('riwayat.kunjungan') ? $menuAktif : $menuBiasa }}"><svg class="h-[18px] w-[18px] shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7"><rect x="5" y="4" width="14" height="16" rx="2"/><path d="M9 4.5h6M9 10h6M9 14h6M9 18h3" stroke-linecap="round"/></svg><span class="flex-1 leading-4"><span class="block">Riwayat kunjungan</span><span class="block text-[10px] opacity-70">Tinjau riwayat pasien</span></span></a>@endif
            <p class="mt-7 px-3 text-[10px] font-bold uppercase tracking-[0.18em] text-slate-400">Lainnya</p>
            @if(auth()->user()->memilikiHakAkses('laporan.lihat'))<a href="{{ route('laporan.index') }}" class="mt-2 flex items-center gap-3 px-3 py-3 text-[13px] {{ request()->routeIs('laporan.*') ? $menuAktif : $menuBiasa }}"><svg class="h-[18px] w-[18px] shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7"><path d="M6 3h9l3 3v15H6zM9 11h6M9 15h5" stroke-linecap="round"/></svg><span class="flex-1 leading-4"><span class="block">Laporan</span><span class="block text-[10px] opacity-70">Ringkasan kinerja klinik</span></span></a>@endif
            @if(auth()->user()->memilikiHakAkses('pemenuhan.lihat'))<a href="{{ route('pemenuhan.index') }}" class="flex items-center gap-3 px-3 py-3 text-[13px] {{ request()->routeIs('pemenuhan.*') ? $menuAktif : $menuBiasa }}"><svg class="h-[18px] w-[18px] shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7"><path d="M6 3h9l3 3v15H6zM9 11h6M9 15h5" stroke-linecap="round"/></svg><span class="flex-1 leading-4"><span class="block">Tugas resep</span><span class="block text-[10px] opacity-70">Pantau status resep</span></span></a>@endif
            @if(auth()->user()->memilikiHakAkses('pengguna.role.ubah'))<a href="{{ route('pengguna.index') }}" class="flex items-center gap-3 px-3 py-3 text-[13px] {{ request()->routeIs('pengguna.*') ? $menuAktif : $menuBiasa }}"><svg class="h-[18px] w-[18px] shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.7 1.7 0 0 0 .3 1.9l.1.1-1.7 1.7-.1-.1a1.7 1.7 0 0 0-1.9-.3 1.7 1.7 0 0 0-1 1.5v.2h-2.4v-.2a1.7 1.7 0 0 0-1-1.5 1.7 1.7 0 0 0-1.9.3l-.1.1-1.7-1.7.1-.1A1.7 1.7 0 0 0 8.4 15a1.7 1.7 0 0 0-1.5-1H6v-2.4h.9a1.7 1.7 0 0 0 1.5-1 1.7 1.7 0 0 0-.3-1.9L8 8.6l1.7-1.7.1.1a1.7 1.7 0 0 0 1.9.3 1.7 1.7 0 0 0 1-1.5v-.2h2.4v.2a1.7 1.7 0 0 0 1 1.5 1.7 1.7 0 0 0 1.9-.3l.1-.1 1.7 1.7-.1.1a1.7 1.7 0 0 0-.3 1.9 1.7 1.7 0 0 0 1.5 1h.2V14h-.2a1.7 1.7 0 0 0-1.5 1z" stroke-linecap="round" stroke-linejoin="round"/></svg><span class="min-w-0 leading-4"><span class="block">Data Pengguna</span><span class="block text-[10px] opacity-70">Kelola akses pengguna</span></span></a>@endif
        </nav>
        <div class="border-t border-slate-100 p-5"><div class="flex items-center justify-between text-[11px] text-slate-500"><span>Butuh bantuan?</span><span>•••</span></div><p class="mt-1 text-[10px] text-slate-400">Hubungi admin sistem</p></div>
    </aside>
    <div class="flex min-w-0 flex-1 flex-col">
        <header class="flex h-[74px] items-center justify-between border-b border-slate-200/80 bg-white px-5 sm:px-8"><div class="flex w-full max-w-xl items-center gap-3 text-sm text-slate-400"><svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="7"/><path d="m20 20-4-4"/></svg><span>Cari pasien, nomor rekam medis...</span><kbd class="ml-auto hidden rounded border border-slate-200 px-2 py-1 text-[10px] sm:block">⌘ K</kbd></div><div class="ml-4 flex items-center gap-4"><div id="notifikasi-root" class="relative"><button id="tombol-notifikasi" type="button" aria-label="Buka notifikasi" aria-expanded="false" aria-controls="panel-notifikasi" class="relative grid h-9 w-9 place-items-center rounded-full text-slate-500 transition hover:bg-slate-50 hover:text-slate-700"><svg class="h-[21px] w-[21px]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" aria-hidden="true"><path d="M18 8a6 6 0 0 0-12 0c0 7-3 7-3 9h18c0-2-3-2-3-9M10 21h4" stroke-linecap="round" stroke-linejoin="round"/></svg><span id="jumlah-notifikasi" class="absolute right-0 top-0 hidden min-h-4 min-w-4 items-center justify-center rounded-full bg-red-400 px-1 text-[9px] font-bold text-white">0</span></button><div id="panel-notifikasi" hidden class="absolute right-0 top-11 z-50 w-[340px] overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-[0_18px_45px_rgba(15,23,42,0.14)]"><div class="flex items-center justify-between border-b border-slate-100 px-4 py-4"><h3 class="text-sm font-semibold text-slate-900">Notifikasi</h3><button id="tandai-semua-notifikasi" type="button" class="text-[10px] font-medium text-emerald-700 hover:underline">Tandai semua dibaca</button></div><div id="daftar-notifikasi" class="max-h-[280px] overflow-y-auto"><div class="px-5 py-10 text-center text-xs text-slate-400">Memuat notifikasi...</div></div><a href="{{ route('notifikasi.index') }}" class="block border-t border-slate-100 px-4 py-4 text-center text-xs font-semibold text-emerald-700 hover:bg-slate-50">Lihat semua notifikasi</a></div></div><div id="profil-root" class="relative"><button id="tombol-profil" type="button" aria-label="Buka menu profil" aria-expanded="false" aria-controls="panel-profil" class="flex items-center gap-2 rounded-full border border-transparent px-1.5 py-1 transition hover:border-slate-200 hover:bg-slate-50"><span class="grid h-8 w-8 place-items-center rounded-full bg-[#dff3ef] text-[11px] font-bold text-emerald-700">DS</span><span class="hidden text-right sm:block"><span class="block text-xs font-semibold">{{ auth()->user()->nama_lengkap }}</span><span class="block text-[10px] capitalize text-slate-400">{{ auth()->user()->roles->pluck('nama_peran')->join(' · ') }}</span></span><svg id="ikon-panah-profil" class="h-3.5 w-3.5 text-slate-400 transition-transform duration-200" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.8"><path d="m5 7 5 5 5-5" stroke-linecap="round" stroke-linejoin="round"/></svg></button><div id="panel-profil" hidden class="absolute right-0 top-12 z-50 w-56 overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-[0_16px_35px_rgba(15,23,42,0.14)]"><div class="border-b border-slate-100 px-4 py-3"><p class="truncate text-sm font-semibold text-slate-800">{{ auth()->user()->nama_lengkap }}</p><p class="truncate text-xs text-slate-400">{{ auth()->user()->alamat_email }}</p></div><form method="POST" action="{{ route('keluar') }}" class="p-2">@csrf<button class="flex w-full items-center gap-3 rounded-xl px-3 py-2.5 text-left text-sm text-slate-600 transition hover:bg-slate-50"><svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M10 17l5-5-5-5M15 12H3M21 3v18" stroke-linecap="round" stroke-linejoin="round"/></svg>Keluar</button></form></div></div></div></header>
        <main class="flex-1 px-5 py-7 sm:px-8 lg:px-12">
            @include('layouts.breadcrumbs')
            @if(session('status'))
                <div class="mb-6 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800">{{ session('status') }}</div>
            @endif
            @yield('content')
        </main>
    </div>
</div>
<div id="toast-notifikasi" aria-live="polite" aria-atomic="true" class="fixed right-5 top-5 z-[80] flex w-[min(360px,calc(100vw-2rem))] flex-col gap-3"></div>
<dialog id="modal-konfirmasi-admin" class="confirmation-dialog m-auto w-[calc(100%-2rem)] max-w-lg overflow-y-auto rounded-2xl border-0 bg-white p-0 text-slate-900 shadow-2xl" aria-labelledby="judul-modal-konfirmasi" aria-describedby="pesan-modal-konfirmasi">
    <div class="flex items-center gap-4 bg-teal-700 px-5 py-4 text-white sm:px-6">
        <span class="grid h-11 w-11 shrink-0 place-items-center rounded-xl bg-white/15" aria-hidden="true">
            <svg data-confirm-icon="status" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M12 3v3m0 12v3M4.2 6.2l2.1 2.1m11.4 7.4 2.1 2.1M3 12h3m12 0h3M4.2 17.8l2.1-2.1m11.4-7.4 2.1-2.1" stroke-linecap="round"/><circle cx="12" cy="12" r="4"/></svg>
            <svg data-confirm-icon="danger" class="hidden h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M4 7h16M10 11v6m4-6v6M6 7l1 14h10l1-14M9 7V4h6v3" stroke-linecap="round" stroke-linejoin="round"/></svg>
        </span>
        <div class="min-w-0 flex-1">
            <h2 id="judul-modal-konfirmasi" class="text-base font-bold">Konfirmasi tindakan</h2>
            <p id="subjudul-modal-konfirmasi" class="mt-0.5 text-xs text-teal-50">Pastikan tindakan ini sesuai</p>
        </div>
        <button id="tutup-modal-konfirmasi" type="button" aria-label="Tutup dialog konfirmasi" class="grid h-9 w-9 shrink-0 place-items-center rounded-lg bg-white/10 transition hover:bg-white/20 focus:outline-none focus:ring-2 focus:ring-white/80">
            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="m6 6 12 12M18 6 6 18" stroke-linecap="round"/></svg>
        </button>
    </div>
    <div class="space-y-5 p-5 sm:p-6">
        <div class="flex min-w-0 items-center gap-3 rounded-xl border border-slate-200 bg-white p-4">
            <span class="grid h-11 w-11 shrink-0 place-items-center rounded-xl bg-teal-50 text-teal-700" aria-hidden="true">
                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7"><path d="M7 4h10a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2Z"/><path d="M9 9h6M9 13h6M9 17h3" stroke-linecap="round"/></svg>
            </span>
            <div class="min-w-0">
                <p id="item-modal-konfirmasi" class="break-words text-sm font-semibold text-slate-900"></p>
                <p id="detail-modal-konfirmasi" class="mt-1 break-words text-xs text-slate-500"></p>
            </div>
        </div>
        <div id="peringatan-modal-konfirmasi" data-tone="teal" role="note" class="confirmation-modal__notice">
            <svg class="mt-0.5 h-5 w-5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><circle cx="12" cy="12" r="9"/><path d="M12 11v5m0-8h.01" stroke-linecap="round"/></svg>
            <div>
                <p class="text-sm font-semibold">Perhatian</p>
                <p id="pesan-modal-konfirmasi" class="mt-1 text-sm leading-5"></p>
            </div>
        </div>
        <label id="acknowledgement-modal-konfirmasi" hidden class="flex cursor-pointer items-start gap-3 rounded-xl border border-slate-200 bg-white p-4 text-sm text-slate-700 transition hover:border-teal-300">
            <input id="checkbox-modal-konfirmasi" type="checkbox" class="mt-0.5 h-4 w-4 shrink-0 rounded border-slate-300 text-teal-700 accent-teal-700 focus:ring-2 focus:ring-teal-600">
            <span id="teks-acknowledgement-modal-konfirmasi" class="leading-5"></span>
        </label>
    </div>
    <div class="flex flex-col-reverse gap-2 border-t border-slate-100 px-5 py-4 sm:flex-row sm:justify-end sm:px-6">
        <button id="batal-modal-konfirmasi" type="button" class="inline-flex min-h-11 items-center justify-center rounded-xl bg-slate-100 px-5 text-sm font-semibold text-slate-700 transition hover:bg-slate-200 focus:outline-none focus:ring-2 focus:ring-slate-400 focus:ring-offset-2">Batal</button>
        <button id="lanjut-modal-konfirmasi" type="button" class="inline-flex min-h-11 items-center justify-center rounded-xl bg-teal-700 px-5 text-sm font-semibold text-white shadow-sm transition hover:bg-teal-800 focus:outline-none focus:ring-2 focus:ring-teal-600 focus:ring-offset-2 disabled:cursor-not-allowed disabled:bg-teal-300">Konfirmasi</button>
    </div>
</dialog>
<script>
(() => {
    const root = document.getElementById('profil-root');
    const button = document.getElementById('tombol-profil');
    const panel = document.getElementById('panel-profil');
    const arrow = document.getElementById('ikon-panah-profil');
    if (!root || !button || !panel || !arrow) return;
    const setOpen = (open) => { panel.hidden = !open; button.setAttribute('aria-expanded', String(open)); arrow.classList.toggle('rotate-180', open); };
    button.addEventListener('click', () => setOpen(panel.hidden));
    document.addEventListener('click', (event) => { if (!root.contains(event.target)) setOpen(false); });
})();
</script>
<script>
(() => {
    const status = document.getElementById('status-praktik');
    const label = document.getElementById('status-praktik-label');
    const titik = document.getElementById('status-praktik-titik');
    const keterangan = document.getElementById('status-praktik-keterangan');
    if (!status || !label || !titik || !keterangan) return;
    const perbaruiStatusPraktik = () => {
        const waktu = new Intl.DateTimeFormat('id-ID', { timeZone: 'Asia/Jakarta', hour: 'numeric', minute: 'numeric', hour12: false }).formatToParts(new Date());
        const jam = Number(waktu.find((bagian) => bagian.type === 'hour')?.value ?? 0);
        const menit = Number(waktu.find((bagian) => bagian.type === 'minute')?.value ?? 0);
        const terbuka = jam >= Number(status.dataset.jamBuka) && jam < Number(status.dataset.jamTutup);
        status.classList.toggle('border-emerald-100', terbuka); status.classList.toggle('bg-emerald-50/60', terbuka); status.classList.toggle('text-slate-600', terbuka);
        status.classList.toggle('border-red-100', !terbuka); status.classList.toggle('bg-red-50', !terbuka); status.classList.toggle('text-red-700', !terbuka);
        titik.classList.toggle('bg-emerald-500', terbuka); titik.classList.toggle('ring-emerald-100', terbuka); titik.classList.toggle('bg-red-500', !terbuka); titik.classList.toggle('ring-red-100', !terbuka);
        label.textContent = terbuka ? 'Praktik buka' : 'Praktik tutup';
        keterangan.textContent = terbuka ? 'Layanan sedang tersedia' : (jam < Number(status.dataset.jamBuka) ? 'Layanan mulai pukul 08:00' : `Layanan berakhir pukul 20:00 (WIB ${String(jam).padStart(2, '0')}:${String(menit).padStart(2, '0')})`);
    };
    perbaruiStatusPraktik(); setInterval(perbaruiStatusPraktik, 30000);
})();
</script>
</body>
</html>

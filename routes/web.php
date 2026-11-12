<?php

use App\Http\Controllers\AutentikasiController;
use App\Http\Controllers\DasborController;
use App\Http\Controllers\AntreanController;
use App\Http\Controllers\KunjunganController;
use App\Http\Controllers\PemeriksaanController;
use App\Http\Controllers\NotifikasiController;
use App\Http\Controllers\PemenuhanController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\ResepController;
use App\Http\Controllers\KatalogObatController;
use App\Http\Controllers\PasienController;
use App\Http\Controllers\PendaftaranController;
use App\Http\Controllers\PengelolaanPenggunaController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn () => auth()->check() ? redirect()->route('dasbor.pilih') : redirect()->route('masuk'))->name('beranda');

Route::middleware('guest')->group(function (): void {
    Route::get('/masuk', [AutentikasiController::class, 'create'])->name('masuk');
    Route::get('/login', fn () => redirect()->route('masuk'))->name('login');
    Route::post('/masuk', [AutentikasiController::class, 'store'])->name('masuk.simpan');
});

Route::middleware(['auth', 'akun.aktif'])->group(function (): void {
    Route::post('/keluar', [AutentikasiController::class, 'destroy'])->name('keluar');
    Route::get('/dasbor', [DasborController::class, 'pilih'])->name('dasbor.pilih');
    Route::get('/dasbor/dokter', [DasborController::class, 'dokter'])->middleware('peran:dokter')->name('dasbor.dokter');
    Route::get('/dasbor/resepsionis', [DasborController::class, 'resepsionis'])->middleware('peran:resepsionis')->name('dasbor.resepsionis');
    Route::get('/dasbor/administrator', [DasborController::class, 'administrator'])->middleware('peran:administrator')->name('dasbor.administrator');
    Route::resource('pasien', PasienController::class)->except(['destroy']);
    Route::get('/pendaftaran', [PendaftaranController::class, 'index'])->name('pendaftaran.index');
    Route::middleware('peran:administrator')->group(function (): void {
        Route::get('/pengguna', [PengelolaanPenggunaController::class, 'index'])->name('pengguna.index');
        Route::get('/pengguna/tambah', [PengelolaanPenggunaController::class, 'create'])->name('pengguna.create');
        Route::post('/pengguna', [PengelolaanPenggunaController::class, 'store'])->name('pengguna.store');
        Route::get('/pengguna/{pengguna}/edit', [PengelolaanPenggunaController::class, 'edit'])->name('pengguna.edit');
        Route::put('/pengguna/{pengguna}', [PengelolaanPenggunaController::class, 'update'])->name('pengguna.update');
        Route::delete('/pengguna/{pengguna}', [PengelolaanPenggunaController::class, 'destroy'])->name('pengguna.destroy');
    });
    Route::get('/kunjungan/daftar', [KunjunganController::class, 'create'])->name('kunjungan.create');
    Route::post('/kunjungan', [KunjunganController::class, 'store'])->name('kunjungan.store');
    Route::get('/kunjungan/{kunjungan}/tiket', [KunjunganController::class, 'ticket'])->name('kunjungan.tiket');
    Route::post('/kunjungan/{kunjungan}/tiket/cetak', [AntreanController::class, 'cetakTiket'])->name('kunjungan.tiket.cetak');
    Route::get('/antrean', [AntreanController::class, 'index'])->name('antrean.index');
    Route::get('/riwayat-kunjungan', [AntreanController::class, 'riwayat'])->name('riwayat.kunjungan');
    Route::get('/antrean/data', [AntreanController::class, 'data'])->name('antrean.data');
    Route::patch('/antrean/{kunjungan}', [AntreanController::class, 'update'])->name('antrean.update');
    Route::post('/pemeriksaan/{kunjungan}/mulai', [PemeriksaanController::class, 'mulai'])->name('pemeriksaan.mulai');
    Route::get('/pemeriksaan/{kunjungan}', [PemeriksaanController::class, 'show'])->name('pemeriksaan.show');
    Route::post('/pemeriksaan/{kunjungan}', [PemeriksaanController::class, 'store'])->name('pemeriksaan.store');
    Route::get('/pemeriksaan/{kunjungan}/cetak', [PemeriksaanController::class, 'print'])->name('pemeriksaan.print');
    Route::get('/notifikasi', [NotifikasiController::class, 'index'])->name('notifikasi.index');
    Route::get('/notifikasi/belum-dibaca', [NotifikasiController::class, 'unread'])->name('notifikasi.unread');
    Route::patch('/notifikasi/{notifikasi}/baca', [NotifikasiController::class, 'read'])->name('notifikasi.read');
    Route::patch('/notifikasi/baca-semua', [NotifikasiController::class, 'readAll'])->name('notifikasi.read-all');
    Route::get('/pemenuhan', [PemenuhanController::class, 'index'])->name('pemenuhan.index');
    Route::patch('/pemenuhan/{pemenuhan}/status', [PemenuhanController::class, 'update'])->name('pemenuhan.update');
    Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');
    Route::get('/resep/{resep}/cetak', [ResepController::class, 'print'])->name('resep.print');
    Route::get('/obat', [KatalogObatController::class, 'index'])->name('obat.index');
    Route::get('/obat/tambah', [KatalogObatController::class, 'create'])->name('obat.create');
    Route::post('/obat', [KatalogObatController::class, 'store'])->name('obat.store');
    Route::get('/obat/{obat}/ubah', [KatalogObatController::class, 'edit'])->name('obat.edit');
    Route::put('/obat/{obat}', [KatalogObatController::class, 'update'])->name('obat.update');
    Route::delete('/obat/{obat}', [KatalogObatController::class, 'destroy'])->name('obat.destroy');
});

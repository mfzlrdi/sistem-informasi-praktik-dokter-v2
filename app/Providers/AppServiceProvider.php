<?php

namespace App\Providers;

use App\Models\Kunjungan;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        View::composer('layouts.aplikasi', function ($view): void {
            $jumlahAntreanAktif = auth()->check()
                ? Kunjungan::whereDate('tanggal_kunjungan', now()->toDateString())->whereIn('status_kunjungan', ['menunggu', 'dipanggil', 'sedang_konsultasi'])->count()
                : 0;
            $waktuJakarta = now('Asia/Jakarta');
            $praktikBuka = $waktuJakarta->hour >= 8 && $waktuJakarta->hour < 20;
            $view->with([
                'jumlahAntreanAktif' => $jumlahAntreanAktif,
                'praktikBuka' => $praktikBuka,
                'praktikStatus' => $praktikBuka ? 'Praktik buka' : 'Praktik tutup',
                'praktikKeterangan' => $praktikBuka ? 'Layanan sedang tersedia' : ($waktuJakarta->hour < 8 ? 'Layanan mulai pukul 08:00' : 'Layanan berakhir pukul 20:00'),
            ]);
        });
    }
}

# Catatan Implementasi Task 6

## Cakupan
Task 6 menambahkan notifikasi in-app persisten, broadcasting Reverb/Echo, tugas pemenuhan resep, serta status pemenuhan administratif. WhatsApp, SMS, email, browser push, stok penuh, dan SATUSEHAT tidak termasuk.

## Notifikasi
Tabel `notifikasi` memakai Bahasa Indonesia dan menyimpan penerima, jenis, judul, pesan, data minimum, kunci idempotensi, status baca, serta waktu dibuat. Endpoint notifikasi selalu membatasi data dengan `id_pengguna_penerima` milik akun aktif. Notifikasi resep tidak memuat diagnosis atau rincian klinis.

Penerima finalisasi resep adalah pengguna aktif yang memiliki permission `kunjungan.daftar` (resepsionis operasional). Satu penerima mendapat satu notifikasi untuk satu resep melalui `kunci_peristiwa`. Event `NotifikasiBaru` memakai private channel `pengguna.{id}` dan dikirim melalui Laravel Broadcasting setelah data tersimpan.

## Reverb/Echo
- Dependency Composer: `laravel/reverb`.
- Dependency frontend: `laravel-echo`, `pusher-js`.
- Konfigurasi dibuat oleh `install:broadcasting --reverb`; `routes/channels.php` memvalidasi `id_pengguna`.
- Local environment membutuhkan `REVERB_APP_ID`, `REVERB_APP_KEY`, `REVERB_APP_SECRET`, `REVERB_HOST`, `REVERB_PORT`, dan Vite variables pada `.env`.
- Perintah lokal: `php artisan reverb:start`, `php artisan queue:work`, `npm run dev`, dan `php artisan serve`.
- Jika Reverb/queue belum aktif, notifikasi database tetap tersedia; broadcast belum boleh dianggap berhasil.

## Pemenuhan resep
`pemenuhan_resep` dibuat idempotent setelah resep berstatus `difinalisasi`. Status dipisahkan dari status resep: `menunggu_tindak_lanjut`, `sedang_diproses`, `siap_diserahkan`, `terhambat`, `dirujuk_ke_apotek_luar`, dan `dibatalkan`. Permission `pemenuhan.lihat` dan `pemenuhan.ubah` diberikan kepada resepsionis demo sebagai kewenangan administratif yang dapat disesuaikan prosedur praktik.

`rincian_penyerahan_obat` tersedia untuk pencatatan jumlah aktual dan petugas aktual, tetapi Task 6 belum mengaktifkan klaim stok atau menganggap obat sudah diserahkan hanya karena notifikasi dibaca.

## Verifikasi
- Migration/seeder berhasil dengan SQLite lokal menggunakan `BROADCAST_CONNECTION=log`.
- `php artisan test`: 2 test scaffold lulus.
- `php artisan view:cache` dan `npm run build` berhasil.
- Riwayat notifikasi berhasil diverifikasi melalui browser.
- Reverb package terpasang; pengujian socket langsung memerlukan environment Reverb credentials dan proses `php artisan reverb:start` aktif.

# Catatan Implementasi Task 4

## Cakupan
Task 4 menghubungkan `pasien` dengan `kunjungan` dan antrean. Modul pemeriksaan klinis, diagnosis, resep, stok, dan notifikasi resep belum dibuat.

## Database
- `kunjungan` menyimpan pasien, tanggal operasional, nomor antrean, waktu daftar, keluhan awal administratif, status, dan identitas pengguna.
- `pengendali_antrean_harian` menyimpan nomor berikutnya per tanggal untuk menghindari penggunaan `MAX(nomor_antrean) + 1` tanpa kontrol.
- Unique constraint (`tanggal_kunjungan`, `nomor_antrean`) mencegah nomor ganda.
- Status: `menunggu`, `dipanggil`, `sedang_konsultasi`, `selesai`, `dibatalkan`.

## Hak akses
- Resepsionis: `kunjungan.lihat`, `kunjungan.daftar`; dapat mendaftarkan pasien dan memantau status.
- Dokter: `kunjungan.lihat`, `antrean.kelola`; dapat memanggil, memulai konsultasi, dan menyelesaikan antrean.
- Administrator: `kunjungan.lihat`; tidak memperoleh kewenangan klinis otomatis.

## Near-realtime
Dashboard antrean mengambil data dari database dan melakukan refresh halaman ringan setiap 15 detik. Ini adalah near-realtime berbasis polling sederhana, bukan WebSocket. Endpoint JSON `/antrean/data` tersedia dan dilindungi autentikasi/permission untuk pengembangan polling AJAX berikutnya.

## Tiket
Tiket hanya memuat nama praktik, nomor antrean, nama pasien, tanggal/waktu daftar, dan instruksi menunggu. Keluhan awal serta data klinis tidak dicetak. CSS `@media print` menyembunyikan kontrol cetak.

## Batasan verifikasi
Migration, seeder, build Vite, dan alur browser diuji menggunakan SQLite lokal karena MySQL workspace tidak aktif. Pada Laragon, gunakan MySQL database `sistem_informasi_praktik_dokter` dan jalankan `php artisan migrate` sebelum seeding.

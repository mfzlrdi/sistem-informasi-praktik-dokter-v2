# Catatan Implementasi Task 5

## Cakupan
Task 5 menambahkan pemeriksaan dokter, rekam medis, diagnosis, tindakan, terapi, katalog obat, resep draf, dan finalisasi resep. Notifikasi resep, pemenuhan obat, stok, dan Reverb tetap menjadi Task 6.

## Permission klinis
- `klinis.lihat`: melihat konteks pasien dan catatan klinis.
- `klinis.ubah`: menyimpan catatan pemeriksaan dokter.
- `resep.kelola`: menambahkan dan memfinalisasi resep.

Permission tersebut hanya diberikan ke role `dokter`. Administrator tidak otomatis memperoleh akses klinis.

## Model dan data
Migration `2026_11_04_000004_create_rekam_medis_tables.php` membuat `rekam_medis`, `diagnosa`, `tindakan_medis`, `obat`, `resep`, dan `rincian_resep`. `rekam_medis.id_pengguna_dokter` digunakan sebagai referensi dokter karena tabel profil `dokter` belum tersedia pada Task 2-4; ini adalah penyesuaian terkontrol tanpa membuat tabel bisnis duplikat.

Resep menyimpan snapshot nama/kekuatan/bentuk obat pada rincian agar riwayat tidak berubah ketika katalog diperbarui. Status resep: `draf`, `difinalisasi`, dan `dibatalkan`.

## Alur
Dokter membuka kunjungan berstatus `dipanggil` atau `sedang_konsultasi`, mengisi anamnesis, pemeriksaan, tanda vital opsional, diagnosis, tindakan, terapi, dan resep. Simpan draf belum menganggap catatan final. Finalisasi menyimpan timestamp dan status rekam medis/resep melalui transaksi. Notifikasi belum dikirim pada Task 5.

## Verifikasi
- Migration, seeder, cache Blade, dan build Vite berhasil dengan SQLite lokal.
- Browser memverifikasi dashboard antrean dokter, pembukaan form pemeriksaan, katalog obat, dan penyimpanan catatan pemeriksaan draf.
- `php artisan test` menjalankan 2 test scaffold yang tersedia dan keduanya lulus.
- Pada Laragon, gunakan MySQL database `sistem_informasi_praktik_dokter` lalu jalankan migration dan seeder sebelum memakai data klinis.

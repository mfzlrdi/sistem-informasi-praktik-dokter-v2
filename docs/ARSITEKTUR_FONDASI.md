# Arsitektur Fondasi Task 2

- Laravel 13 MVC dengan Blade dan Vite.
- `pengguna` menjadi tabel autentikasi dengan pengecualian class internal `User` karena kontrak scaffold Laravel; nama tabel dan kolom bisnis tetap Bahasa Indonesia.
- `peran` dan `pengguna_peran` menyediakan multi-role. Middleware `peran` melakukan pemeriksaan server-side.
- Route dashboard dipisahkan untuk dokter, resepsionis, dan administrator.
- Tidak ada registrasi publik dan tidak ada data pasien pada seeder.
- Tabel cache/jobs bawaan framework tetap mengikuti konvensi internal karena belum menjadi tabel bisnis; keputusan ini dicatat untuk audit kompatibilitas.

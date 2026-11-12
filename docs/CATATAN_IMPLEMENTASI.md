# Catatan Implementasi Task 3

## Permission
Permission fondasi menggunakan tabel `hak_akses` dan `peran_hak_akses`. Permission yang aktif: `pasien.lihat`, `pasien.tambah`, `pasien.ubah`, dan `pengguna.role.ubah`.

- Dokter: melihat pasien.
- Resepsionis: melihat, menambah, dan mengubah pasien.
- Administrator: melihat, menambah, mengubah pasien, serta mengelola role pengguna.

Administrator tidak otomatis memperoleh kewenangan klinis. Perubahan role akun sendiri ditolak untuk mencegah kehilangan akses administratif.

## Modul pasien
Model `Pasien` memakai tabel `pasien`, primary key `id_pasien`, nomor rekam medis otomatis `RM-YYYYMMDD-XXXX`, validasi backend, pencarian nama/nomor RM/telepon, pagination, detail, tambah, dan edit. Penghapusan permanen belum disediakan karena pasien merupakan data historis penting.

Nomor rekam medis dibuat backend dan dilindungi unique constraint. Pencarian sebelum pendaftaran tetap dianjurkan; sistem tidak menggabungkan pasien otomatis berdasarkan kemiripan nama.

## Verifikasi
Jalankan `php artisan migrate`, `php artisan db:seed`, `php artisan test`, lalu `npm run build`. Task 3 tidak mengimplementasikan kunjungan, antrean, rekam medis klinis, resep, stok, atau notifikasi realtime.

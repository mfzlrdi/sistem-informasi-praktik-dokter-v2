# Panduan Setup Lokal

## Prasyarat
- PHP 8.3 atau lebih baru yang kompatibel dengan Laravel 13.
- Composer 2.
- Node.js dan npm.
- MySQL 8.4 melalui Laragon.

## Instalasi
Dari folder proyek:

```powershell
composer install
npm install
Copy-Item .env.example .env
php artisan key:generate
```

Buat database MySQL bernama `sistem_informasi_praktik_dokter`, lalu atur `.env`:

```dotenv
APP_NAME="Praktik dr. Sumarwanto"
APP_TIMEZONE=Asia/Jakarta
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=sistem_informasi_praktik_dokter
DB_USERNAME=root
DB_PASSWORD=
```

Jalankan fondasi:

```powershell
php artisan migrate
php artisan db:seed
npm run build
```

Untuk pengembangan, jalankan terminal berikut:

```powershell
php artisan serve
npm run dev
php artisan reverb:start
php artisan queue:work
```

Jika belum memakai realtime push, gunakan `BROADCAST_CONNECTION=log`; data notifikasi tetap tersimpan di database. Untuk realtime, pastikan nilai `REVERB_*` dan `VITE_REVERB_*` ada di `.env`.

Akun demo lokal:
- Dokter + administrator: `dokter.demo@praktik.test` / `password-demo`
- Resepsionis: `resepsionis.demo@praktik.test` / `password-demo`

Password tersebut hanya untuk development lokal. Jangan gunakan pada lingkungan produksi.

## Verifikasi

```powershell
php artisan test
php artisan route:list
php artisan migrate:status
```

Task 2 belum mengaktifkan modul pasien, antrean, klinis, resep, stok, atau notifikasi realtime.

# Order App Laravel

Aplikasi pemesanan makanan dengan database MySQL.

## Struktur database

- `menus`: data menu.
- `orders`: data utama pesanan dan status.
- `order_items`: rincian menu pada setiap pesanan.

## Instalasi XAMPP

1. Aktifkan ekstensi PHP `pdo_mysql`, `mbstring`, dan `dom`, lalu jalankan Apache dan MySQL.
2. Buat database `order_app` di phpMyAdmin.
3. Salin `.env.example` menjadi `.env`.
4. Jalankan:

```bash
composer install
php artisan key:generate
php artisan migrate
php artisan serve
```

Alternatif tanpa migration: impor file `order_app.sql` melalui phpMyAdmin.

## Halaman

- `/menu`: daftar menu.
- `/cart`: keranjang dan checkout.
- `/status`: pelacakan kode pesanan.
- `/admin/menus`: pengelolaan menu.
- `/admin/orders`: pengelolaan status pesanan.

Catatan: halaman admin belum memakai autentikasi.

## Relasi

`orders` memiliki banyak `order_items`. Setiap `order_items` dapat merujuk ke satu `menus`. Nama dan harga menu juga disalin ke `order_items` agar riwayat pesanan tidak berubah ketika menu diedit atau dihapus.

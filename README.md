# Pelaporan dan Pemantauan Keamanan Makanan MBG

Mini Project Proyek 3: Pengembangan Perangkat Lunak Berbasis Web,
D3 Teknik Informatika.

## Identitas
- Nama  : Rainissa Azizah
- NIM   : 251511059
- Kelas : 2B-D3

## Deskripsi singkat
Aplikasi web untuk melaporkan kejadian terkait makanan MBG, meninjau laporan,
mencatat penerusan informasi kepada pihak terkait, dan memantau rekapnya.
Terdapat tiga peran: Pelapor, Pemantau Komunitas, dan Koordinator Komunitas.

## Kebutuhan environment
- PHP 8.3 atau lebih baru
- Composer
- MySQL atau MariaDB
- Git
- Ekstensi PHP: fileinfo, pdo_mysql, mbstring

## Langkah instalasi
1. Clone repository:
   `git clone https://github.com/rainissa/Mini-Project-MBG.git`
2. Masuk ke folder: `cd mbg-pelaporan`
3. Pasang dependensi: `composer install`
4. Salin konfigurasi: `copy .env.example .env`
5. Buat kunci aplikasi: `php artisan key:generate`

## Konfigurasi database
1. Buat database kosong bernama `mbg_pelaporan` (collation `utf8mb4_unicode_ci`).
2. Ubah bagian database pada `.env`:

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=mbg_pelaporan
DB_USERNAME=root
DB_PASSWORD=

## Migration dan seeding
php artisan migrate:fresh --seed

## Akun demo
Semua akun memakai password: `password`

| Peran | Email |
|---|---|
| Koordinator Komunitas | koordinator@mbg.test |
| Pemantau Komunitas | pemantau@mbg.test |
| Pelapor 1 | pelapor1@mbg.test |
| Pelapor 2 | pelapor2@mbg.test |

## Menjalankan aplikasi
php artisan serve

Lalu buka http://127.0.0.1:8000

## Catatan
- File bukti disimpan pada disk private Laravel Storage (`storage/app/private/bukti`).
- Seluruh data pada seeder adalah data dummy.
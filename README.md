# Quiz Interaktif Laravel 13

Aplikasi kuis pilihan ganda A–D dengan:

- halaman admin;
- CRUD kuis dan soal;
- soal dapat diubah kapan saja;
- timer berbeda untuk setiap soal;
- jawaban otomatis dikirim saat waktu habis;
- skor otomatis;
- peringkat berdasarkan skor tertinggi dan waktu tercepat;
- layar penyaji yang memperbarui data peserta setiap 2 detik;
- tampilan responsif untuk laptop dan HP.

## Persyaratan

- PHP 8.3 atau lebih baru;
- Composer;
- ekstensi SQLite/PDO SQLite;
- Laravel Herd dapat digunakan agar instalasi PHP dan Composer lebih mudah.

Aplikasi ini tidak membutuhkan `npm install` karena CSS dan JavaScript sudah tersedia langsung di folder `public`.

## Cara menjalankan di Windows

Buka PowerShell pada folder proyek, lalu jalankan:

```powershell
composer install
Copy-Item .env.example .env
php artisan key:generate
New-Item database/database.sqlite -ItemType File -Force
php artisan migrate --seed
php artisan serve
```

Kemudian buka:

```text
http://127.0.0.1:8000
```

## Akun admin bawaan

```text
Alamat admin : http://127.0.0.1:8000/admin/login
Email        : admin@quiz.test
Password     : password123
```

Segera ubah akun bawaan jika aplikasi akan dipublikasikan.

## Kuis contoh

```text
Kode kuis : 123456
```

Kuis contoh sudah aktif dan berisi empat soal Pancasila.

## Urutan penggunaan

1. Masuk ke halaman admin.
2. Buka **Kelola Kuis**.
3. Buat kuis atau buka kuis contoh.
4. Tambah/edit soal A, B, C, dan D.
5. Tentukan jawaban benar, poin, urutan, dan timer.
6. Aktifkan kuis.
7. Buka **Layar Penyaji**.
8. Peserta membuka halaman utama dan memasukkan kode kuis.
9. Hasil peserta dapat dilihat melalui menu **Lihat Hasil**.

## Menggunakan MySQL

Ubah bagian database di `.env`:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=quiz_interaktif
DB_USERNAME=root
DB_PASSWORD=
```

Buat database `quiz_interaktif`, kemudian jalankan:

```powershell
php artisan migrate:fresh --seed
```

## Struktur penting

```text
app/Http/Controllers/Admin     Controller halaman admin
app/Http/Controllers           Login, peserta, dan presenter
app/Models                     Model database
resources/views/admin          Tampilan admin
resources/views/public         Tampilan peserta dan penyaji
public/css/app.css              Seluruh desain aplikasi
routes/web.php                  Daftar alamat/route
 database/migrations            Struktur tabel
 database/seeders               Akun admin dan kuis contoh
```

## Catatan timer

Waktu mulai soal disimpan di database pada kolom `question_started_at`. Karena itu, me-refresh halaman tidak mengulang timer dari awal. Server juga memeriksa lama pengerjaan ketika jawaban dikirim.

## Reset seluruh database

```powershell
php artisan migrate:fresh --seed
```

Perintah tersebut menghapus seluruh kuis, soal, peserta, dan jawaban, kemudian memasukkan kembali data contoh.

## Paket soal bawaan 10 soal

Kuis contoh `123456` sekarang berisi:

- 2 soal mudah: 20 detik, 10 poin per soal.
- 3 soal sedang: 30 detik, 15 poin per soal.
- 5 soal sulit: 45 detik, 20 poin per soal.
- Total waktu timer: 355 detik atau 5 menit 55 detik.
- Total nilai maksimum: 145 poin.

Untuk memasukkan paket soal ini ke database yang sudah pernah digunakan:

```bash
herd php artisan migrate
herd php artisan db:seed
herd php artisan optimize:clear
```

Perintah `db:seed` akan mengganti bank soal kuis kode `123456` dan menghapus hasil peserta lama pada kuis tersebut agar perhitungan nilai tetap konsisten.

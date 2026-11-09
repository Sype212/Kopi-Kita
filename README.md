# ☕ Kopi Kita — Point of Sale (POS) & Coffee Shop Management System

Sistem Informasi Kasir dan Manajemen Operasional Kedai Kopi berbasis web yang modern, responsif, dan mudah digunakan. Dibangun dengan ekosistem **Laravel 13**, **Tailwind CSS v4**, dan **MySQL**.

Aplikasi ini dirancang khusus untuk mempermudah operasional kasir, pemrosesan pesanan cepat (*Dine-in* & *Takeaway*), manajemen ketersediaan menu dan stok bahan, pencetakan struk belanja, hingga rekapitulasi laporan penjualan berkala.

---

## 📋 Daftar Isi
1. [Fitur Utama](#-fitur-utama)
2. [Spesifikasi & Kebutuhan Sistem](#-spesifikasi--kebutuhan-sistem)
3. [Konfigurasi Penting XAMPP (Wajib Dibaca)](#-konfigurasi-penting-xampp-wajib-dibaca)
4. [Panduan Instalasi & Menjalankan Aplikasi](#-panduan-instalasi--menjalankan-aplikasi)
5. [Akun Pengguna untuk Pengujian](#-akun-pengguna-untuk-pengujian)
6. [Skenario & Panduan Pengujian untuk Penguji](#-skenario--panduan-pengujian-untuk-penguji)
7. [Struktur Proyek](#-struktur-proyek)
8. [Troubleshooting & Solusi Kendala](#-troubleshooting--solusi-kendala)

---

## 🚀 Fitur Utama

- **Dashboard Real-time**: Ringkasan performa omzet harian, transaksi sukses, item terjual, grafik tren mingguan, dan 5 transaksi terkini.
- **Transaksi Kasir (Point of Sale)**:
  - Tampilan grid menu 4-kolom yang proporsional dengan gambar produk.
  - Filter kategori (*Kopi*, *Non-kopi*, *Makanan*) dan live filter pencarian nama produk.
  - Opsi pesanan: **Makan di Tempat** (input nomor meja) & **Bawa Pulang (Takeaway)**.
  - Multi-metode pembayaran: **Tunai** (otomatis kalkulasi kembalian), **QRIS** (dilengkapi fitur unggah QRIS kustom milik toko), dan **Debit Card**.
  - Responsif di berbagai perangkat: Desktop, Tablet (iPad), dan Smartphone (dengan floating cart bar interaktif).
- **Cetak Struk Transaksi**: Cetak nota format thermal 80mm standar struk kasir dengan layout presisi, siap cetak ke printer thermal maupun simpan sebagai PDF.
- **Manajemen Menu & Stok (CRUD)**:
  - Tambah, edit, dan hapus menu dengan foto produk (JPG, PNG, WebP hingga 20 MB).
  - Pratinjau langsung gambar saat memilih file sebelum disimpan (*Live Image Preview*).
  - Otomatisasi stok: Menu dengan stok `0` otomatis non-aktif di katalog kasir.
  - Filter pencarian dan pagination rapi.
- **Laporan & Rekap Penjualan**:
  - Filter laporan berdasarkan rentang tanggal.
  - Pratinjau struk transaksi langsung di halaman laporan.
  - Ekspor laporan ke **Excel (CSV)**.
  - Cetak rekap laporan formal ukuran **A4**.

---

## 💻 Spesifikasi & Kebutuhan Sistem

- **Sistem Operasi**: Windows 10/11, macOS, atau Linux
- **Web Server**: Apache (via XAMPP / Laragon)
- **Database Server**: MySQL / MariaDB (via XAMPP)
- **PHP**: Versi **8.3** atau **8.5**
  - Ekstensi PHP wajib: `pdo_mysql`, `gd`, `exif`, `fileinfo`, `mbstring`, `openssl`
- **Composer**: Versi **2.x**
- **Node.js**: Versi **18.x** ke atas & **npm**

---

## ⚙️ Konfigurasi Penting XAMPP (Wajib Dibaca)

Aplikasi memiliki fitur unggah foto menu resolusi tinggi dan gambar QRIS kustom. Agar fitur unggah gambar berjalan lancar tanpa terhalang limit bawaan PHP:

1. Buka file konfigurasi PHP: `C:\xampp\php\php.ini` (atau melalui **XAMPP Control Panel** -> Klik tombol **Config** pada baris Apache -> pilih `php.ini`).
2. Pastikan baris ekstensi berikut aktif (hilangkan tanda titik koma `;` di depannya jika ada):
   ```ini
   extension=gd
   extension=exif
   extension=fileinfo
   ```
3. Sesuaikan batas kapasitas upload:
   ```ini
   upload_max_filesize = 30M
   post_max_size = 35M
   memory_limit = 256M
   ```
4. **Simpan file** lalu **Restart Apache** pada XAMPP Control Panel.

---

## 🛠️ Panduan Instalasi & Menjalankan Aplikasi

Ikuti langkah-langkah berurutan di bawah ini untuk mempersiapkan proyek dari nol:

### 1. Letakkan Proyek di Folder Web Server
Pastikan folder proyek berada di direktori XAMPP:
```
C:\xampp\htdocs\Kopi-Kita
```

### 2. Buka Terminal / PowerShell
Buka terminal (PowerShell atau Command Prompt) dan arahkan ke direktori proyek:
```bash
cd C:\xampp\htdocs\Kopi-Kita
```

### 3. Install Dependensi PHP (Composer)
```bash
composer install
```

### 4. Konfigurasi File Environment (.env)
Salin file template `.env.example` menjadi `.env` jika belum ada:
```bash
copy .env.example .env
```
Pastikan pengaturan koneksi database di file `.env` sudah sesuai dengan MySQL Anda:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=Kopi-Kita
DB_USERNAME=root
DB_PASSWORD=
```
*(Catatan: Secara default XAMPP menggunakan username `root` tanpa password).*

### 5. Generate Application Key
```bash
php artisan key:generate
```

### 6. Buat Database MySQL
Pastikan **MySQL** di XAMPP sudah berjalan (**Start**), lalu buat database baru dengan nama `Kopi-Kita`:
- Bisa melalui **phpMyAdmin** (`http://localhost/phpmyadmin`) -> Buat database baru: `Kopi-Kita`.
- Atau lewat terminal MySQL:
  ```sql
  CREATE DATABASE `Kopi-Kita` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
  ```

### 7. Jalankan Migrasi & Seeder Database
Perintah ini akan membuat struktur tabel beserta data awal sampel (menu, kategori, akun kasir & admin, serta sampel transaksi):
```bash
php artisan migrate:fresh --seed
```

### 8. Buat Storage Symlink (Wajib untuk Foto Produk & QRIS)
Jalankan perintah ini agar file media yang diunggah dapat diakses dari browser:
```bash
php artisan storage:link
```

### 9. Install & Kompilasi Asset Frontend (Tailwind CSS v4 & Vite)
```bash
npm install
npm run build
```

### 10. Jalankan Server Aplikasi
Jalankan development server Laravel:
```bash
php artisan serve
```
Aplikasi kini siap diakses melalui peramban web di:
👉 **[http://127.0.0.1:8000](http://127.0.0.1:8000)** atau **[http://localhost:8000](http://localhost:8000)**

---

## 🔑 Akun Pengguna untuk Pengujian

Database seeder telah menyediakan dua akun dengan role berbeda:

| Role | Username | Email | Password | Hak Akses Utama |
| :--- | :--- | :--- | :--- | :--- |
| **Admin** | `admin` | `admin@kopikita.id` | `password` | Dashboard, Transaksi Kasir, Manajemen Menu CRUD & Stok, Laporan & Ekspor |
| **Kasir** | `ayu.pratama` | `ayu@kopikita.id` | `password` | Transaksi Kasir, Proses Pembayaran, Cetak Struk |

---

## 🧪 Skenario & Panduan Pengujian untuk Penguji

Berikut adalah alur skenario pengujian (*Testing Checklist*) yang direkomendasikan untuk menguji seluruh fungsi sistem:

### 1. Pengujian Autentikasi (`/login`)
- [ ] Buka `http://127.0.0.1:8000/login`.
- [ ] Coba masukkan kombinasi username/password salah -> pastikan muncul pesan validasi error.
- [ ] Masukkan username `admin` dan password `password` -> pastikan diarahkan ke `/dashboard`.
- [ ] Cek status profil pengguna di sidebar kiri bawah (menampilkan nama, role, dan tombol keluar).

### 2. Pengujian Dashboard Utama (`/dashboard`)
- [ ] Periksa kartu ringkasan KPI (Penjualan Hari Ini, Total Transaksi, Produk Terjual, Rata-rata Penjualan).
- [ ] Periksa chart/grafik tren penjualan 7 hari terakhir.
- [ ] Periksa daftar 5 transaksi terbaru pada panel bawah.

### 3. Pengujian Transaksi Kasir (`/transaksi`)
- [ ] **Katalog Menu**:
  - Tampilan menu tersusun rapi dalam 4 kolom dengan foto produk proporsional.
  - Coba tombol filter kategori (*Semua menu*, *Kopi*, *Non-kopi*, *Makanan*).
  - Coba ketik nama menu di kotak pencarian (misal: "Americano" atau "Croissant").
- [ ] **Keranjang Pesanan**:
  - Klik kartu produk atau tombol `+ Tambah` untuk memasukkan item ke keranjang.
  - Ubah kuantitas produk menggunakan tombol `+` dan `-`.
  - Pilih jenis layanan: **Makan di Tempat** (isikan nomor meja, misal `Meja 05`) atau **Bawa Pulang**.
- [ ] **Pembayaran Tunai**:
  - Pilih metode **Tunai**.
  - Masukkan nominal uang yang diterima (misal Rp 50.000 atau klik nominal cepat).
  - Periksa apakah hitungan kembalian muncul secara otomatis dan akurat.
  - Klik **Selesaikan Pembayaran** -> Transaksi tersimpan dan langsung dialihkan ke pratinjau Struk Kasir.
- [ ] **Pembayaran QRIS**:
  - Pilih metode **QRIS**.
  - Pastikan barcode QRIS tampil.
  - Uji fitur **Ganti QRIS Toko**: Unggah file gambar QRIS baru -> gambar akan langsung diperbarui dan tersimpan.
- [ ] **Pembayaran Kartu Debit**:
  - Pilih metode **Debit** -> tekan tombol bayar untuk menyelesaikan transaksi non-tunai.
- [ ] **Responsif & Mobile**:
  - Tekan `F12` pada browser dan aktifkan *Toggle Device Toolbar* (pilih tampilan iPhone / iPad).
  - Pastikan floating bar pesanan di bagian bawah tidak menutupi tombol bayar dan modal kasir terbuka secara mulus.

### 4. Pengujian Cetak Struk (`/transaksi/{id}/struk`)
- [ ] Buka struk hasil transaksi.
- [ ] Cek rincian: Nomor nota unik (`KK-XXX`), waktu transaksi, nama kasir, item pesanan, subtotal, PPN 10%, dan total akhir.
- [ ] Klik tombol **Cetak Struk** -> dialog cetak bawaan browser terbuka dengan format ukuran thermal 80mm yang bersih tanpa elemen website yang tidak perlu.

### 5. Pengujian Manajemen Data Menu (`/menu`)
- [ ] **Tambah Menu Baru**:
  - Klik tombol `+ Tambah menu`.
  - Isi nama menu, pilih kategori, tentukan harga dan stok awal, serta satuan (gelas/porsi).
  - Pilih foto menu (JPG, PNG, atau WebP) -> pastikan **Live Preview gambar** muncul sebelum disimpan.
  - Klik **Simpan Menu** -> periksa menu baru langsung tampil di daftar tabel dan kasir.
- [ ] **Edit Menu & Uji Stok Habis**:
  - Klik tombol **Edit** pada salah satu menu.
  - Ubah stok menjadi `0` -> Simpan.
  - Buka halaman **Transaksi Kasir** (`/transaksi`) -> pastikan menu dengan stok 0 otomatis tersembunyi dari katalog kasir agar kasir tidak menjual produk kosong.
- [ ] **Hapus Menu**:
  - Klik tombol **Hapus** pada menu yang ingin diuji -> konfirmasi penghapusan.

### 6. Pengujian Laporan & Rekapitulasi (`/laporan`)
- [ ] Buka menu **Laporan & Struk**.
- [ ] Uji filter tanggal (ubah tanggal mulai dan tanggal akhir) -> klik **Terapkan filter**.
- [ ] Klik salah satu transaksi pada tabel riwayat -> pastikan panel **Pratinjau struk** di sebelah kanan menampilkan isi transaksi yang dipilih.
- [ ] Klik tombol **Unduh Excel (CSV)** -> pastikan file format `.csv` otomatis terunduh dengan data transaksi lengkap.
- [ ] Klik tombol **Cetak Rekap (A4)** -> tampilan cetak resmi A4 untuk arsip laporan keuangan kasir terbuka rapi.

---

## 📂 Struktur Direktori Proyek

```
Kopi-Kita/
├── app/
│   ├── Http/Controllers/
│   │   ├── Auth/LoginController.php     # Logika Login & Logout
│   │   ├── DashboardController.php      # Kalkulasi metrik & chart dashboard
│   │   ├── TransaksiController.php      # POS, keranjang, order, upload QRIS, struk
│   │   ├── MenuController.php           # CRUD Menu, validasi foto, stok
│   │   └── LaporanController.php        # Rekap penjualan, export CSV, cetak A4
│   └── Models/
│       ├── User.php                     # Model pengguna (Admin / Kasir)
│       ├── Category.php                 # Kategori menu (Kopi, Non-kopi, Makanan)
│       ├── Product.php                  # Data produk, harga, stok, gambar
│       ├── Order.php                    # Transaksi penjualan
│       └── OrderItem.php                # Rincian item per transaksi
├── database/
│   ├── migrations/                      # Skema tabel database
│   └── seeders/DatabaseSeeder.php       # Data awal demo & akun bawaan
├── resources/
│   ├── css/app.css                      # Tailwind CSS v4 & custom animasi UI
│   └── views/
│       ├── components/layouts/app.blade.php  # Template utama & sidebar
│       ├── auth/login.blade.php              # Halaman login
│       ├── dashboard.blade.php               # Tampilan dashboard utama
│       ├── transaksi/
│       │   ├── index.blade.php          # Halaman kasir & POS
│       │   └── struk.blade.php          # Template struk nota 80mm
│       ├── menu/index.blade.php         # Manajemen data menu
│       └── laporan/
│           ├── index.blade.php          # Laporan & riwayat transaksi
│           └── cetak.blade.php          # Lembar rekap cetak A4
├── public/
│   ├── storage/                         # Symlink ke storage/app/public
│   └── build/                           # Hasil kompilasi Vite (CSS & JS)
├── routes/web.php                       # Definisi rute aplikasi
├── .env.example                         # Contoh konfigurasi environment
├── composer.json                        # Dependensi PHP
├── package.json                         # Dependensi JavaScript & Tailwind CSS
└── README.md                            # Dokumentasi & panduan penguji
```

---

## ❓ Troubleshooting & Solusi Kendala

| Kendala | Penyebab Umum | Solusi |
| :--- | :--- | :--- |
| **Error: "The image failed to upload" saat simpan menu** | Ekstensi PHP `gd` belum aktif atau file terlalu besar dari batas `php.ini`. | Buka `C:\xampp\php\php.ini`, pastikan `extension=gd` aktif (tanpa `;`), ubah `upload_max_filesize = 30M`, lalu restart Apache. |
| **Gambar produk tidak muncul / broken link** | Symbolic link storage Laravel belum dibuat. | Jalankan `php artisan storage:link` di terminal proyek. |
| **Tampilan CSS berantakan / tidak ada styling** | Asset Tailwind CSS belum dikompilasi oleh Vite. | Jalankan perintah `npm install` dilanjutkan dengan `npm run build`. |
| **Error Database: "Connection refused" / "Base table not found"** | MySQL XAMPP belum aktif atau migrasi belum dijalankan. | Buka XAMPP Control Panel, Start MySQL, lalu jalankan `php artisan migrate:fresh --seed`. |
| **Error: "Integrity constraint violation duplicate entry"** | Nomor urut order bentrok dengan data lama. | Sistem sudah dilengkapi auto-increment pengaman `KK-XXX`. Jika terjadi saat testing manual data mentah, jalankan `php artisan migrate:fresh --seed` untuk me-reset data sampel secara bersih. |

---

## 👨‍💻 Pengembang & Lisensi

Proyek ini dikembangkan untuk kebutuhan sistem operasional kasir kedai kopi **Kopi Kita**.  
Open-source di bawah lisensi [MIT License](https://opensource.org/licenses/MIT).

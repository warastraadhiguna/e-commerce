# Panduan presentasi

## Preset Mitra Sehati dari Instagram publik

Sesudah mengambil kode terbaru, jalankan:

```bash
php artisan migrate --force
php artisan db:seed --class=MitraSehatiSeeder --force
php artisan optimize:clear
```

Seeder memasang 7 produk dengan harga dan stok dummy untuk simulasi belanja, 12 publikasi galeri, foto profil sebagai logo/favicon, serta tema biru. Gambar sumber sudah disertakan di `database/seeders/data/mitra-sehati/images`; saat seeding akan disalin ke storage publik. Tidak membutuhkan koneksi atau login Instagram pada server. Seeder membuat tautan public/storage bila belum tersedia.

Data berasal dari bagian publik akun `https://www.instagram.com/mitrasehati.pia/` dan posting kolaborasinya, dikumpulkan 23 November 2026. Produk yang namanya terbaca pada gambar mencakup Teh Telang Malika, tas bordir Aceh Putroena Souvenir, Tas Gayo Aceh, kerudung dan celana Wearing Klamby, baju batik motif Gringsing, serta alas kaki Buttonscarves. Sebagian gambar adalah poster atau kolase sumber, bukan foto SKU tunggal. Ini bukan katalog lengkap seluruh riwayat Instagram.

Harga dan stok belum diketahui. Mode **Katalog informasi** menampilkan “Harga belum tersedia” dan memblokir pembelian hingga informasi penjualan dikonfirmasi. Nilai nol pada kolom numerik internal bukan harga jual. Tautan posting asli tersedia pada halaman detail. Produk yang sudah memiliki harga dan stok dapat diaktifkan untuk penjualan melalui admin setelah mematikan mode Katalog informasi.

Pemanggilan pertama mengganti identitas toko dengan preset Mitra Sehati dan menonaktifkan produk demo `DUMMY-PRD-*`. Produk pelanggan, akun, pesanan, dan keuangan tidak dihapus. Profil sebelumnya disimpan di disk lokal privat `branding-backups/`. Kontak toko lama dikosongkan bila tidak berasal dari profil Mitra Sehati; tidak ada nomor kontak yang direka.

Pemanggilan ulang tidak menggandakan produk/galeri atau mereset harga, stok, tema, dan gambar yang sudah diedit. Khusus produk dari versi lama yang masih berstatus katalog informasi dengan harga dan stok nol, seeder sekali saja mengisinya dengan data dummy dan mengaktifkan pembelian. Stok yang berkurang setelah checkout tidak diisi ulang saat seeder dipanggil lagi. Gambar bawaan yang hilang dari storage akan dipulihkan. Seeder ini dipanggil secara eksplisit dan tidak dijalankan oleh DatabaseSeeder produksi.

- Aplikasi: http://localhost:8000
- Admin: http://localhost:8000/management
- Keuangan: http://localhost:8000/management/finance

## Akun lokal

| Peran | Login | Password |
| --- | --- | --- |
| Superadmin (panel management, pakai email) | superadmin@example.com | password |
| Admin (panel management, pakai email) | admin@example.com | password |
| Pembeli demo (halaman login toko, pakai nomor HP) | 081234567890 | Demo12345! |
| Mitra demo (halaman login toko, pakai nomor HP) | 081234567891 | Demo12345! |

Gunakan jendela browser biasa untuk admin dan jendela Incognito untuk pembeli agar sesi tidak saling mengganti.

## Urutan demo, sekitar 5–7 menit

1. Buka halaman depan. Tunjukkan banner, produk terbaru, kategori, pencarian, dan wishlist.
2. Klik **Product → All Products** untuk katalog. Klik nama brand untuk filter merek.
3. Login pembeli menggunakan nomor HP. Buka detail produk, tambahkan ke keranjang, lalu checkout. Alamat demo sudah tersedia.
4. Buat pesanan. Stok berkurang setelah checkout. Buka **Cetak Invoice**, lalu pilih Print / Save as PDF.
5. Di panel admin, buka **Transaksi → Pesanan**. Cari nomor pesanan yang baru dibuat, ubah status menjadi Diproses.
6. Pada bagian Pembayaran, pilih Lunas dan metode pembayaran. Simpan. Lakukan ini sebagai simulasi pembayaran saat demo.
7. Buka **Keuangan → Laporan Keuangan**. Penerimaan bertambah otomatis. Tunjukkan filter bulan, grafik harian, rincian transaksi, dan Unduh CSV.
8. Buka **Keuangan → Pengeluaran**, catat biaya operasional. Kembali ke laporan untuk melihat perubahan selisih kas.
9. Opsional: login sebagai mitra di jendela Incognito untuk memperlihatkan harga mitra yang berbeda dari harga pembeli.

## Cakupan

- Transaksi berawalan `DEMO-` dan pengeluaran `DEMO-EXP-` adalah data contoh, bukan transaksi nyata. Data ini tersedia untuk memperlihatkan dashboard dan laporan, tanpa mengurangi stok.
- Checkout lewat aplikasi menghasilkan nomor `ORD-...` dan benar-benar mengurangi stok. Pengujian browser membuat satu pesanan dari akun demo dengan catatan pengujian.
- Laporan keuangan adalah laporan kas sederhana: pembayaran yang dikonfirmasi lunas dikurangi pengeluaran. Tagihan belum lunas ditampilkan terpisah, tanpa pesanan batal.
- Pembayaran diverifikasi manual oleh admin. Belum ada payment gateway, verifikasi bank otomatis, akuntansi lengkap, atau alur refund.
- Harga, identitas toko Limupa, nomor kontak template, dan alamat demo adalah contoh. Ganti profil lewat Company Setting sebelum memakai aplikasi untuk bisnis nyata.

## Menjalankan ulang

### Jika gambar mengarah ke domain lama

Secara bawaan, gambar public memakai URL `/storage/...` pada domain yang sedang dibuka. File gambar tetap berada di `storage/app/public` dengan tautan `public/storage`. Jika menggunakan domain CDN atau direktori khusus, atur `PUBLIC_STORAGE_URL` secara eksplisit.

Contoh konfigurasi server Mitra Sehati:

```dotenv
APP_URL=https://mitrasehati-pia.warastra-adhiguna.com
PUBLIC_STORAGE_URL=/storage
```

Sesudah mengubah `.env` atau menarik perbaikan konfigurasi dari Git, jalankan `php artisan optimize:clear`. Jika file gambar sudah dapat dibuka langsung di `/storage/mitra-sehati/images/profile.jpg`, tidak perlu mengunggah atau menjalankan seeder ulang.

### Pengaturan dasar khusus Developer

Logo, favicon, identitas toko, warna utama/pendamping, warna latar, font, dan motif hanya dapat diedit melalui **Developer → Identitas & Tema**. Admin dan superadmin tetap menangani operasional, tetapi tidak mendapat akses pengaturan ini, termasuk melalui URL langsung. Mereka juga tidak dapat membuat, mengubah, menghapus, atau mereset akun Developer.

Untuk pemasangan/pembaruan di server:

```bash
git pull origin master
php artisan migrate --force
php artisan optimize:clear
php artisan storage:link
php artisan store:create-developer email-developer@domainmu.com
```

Perintah terakhir meminta nama, nomor HP, dan password minimal 12 karakter melalui prompt tersembunyi. Gunakan email baru; perintah ini tidak menimpa akun yang sudah ada. Tidak ada akun Developer dengan password bawaan. Setelah dibuat, login memakai email di `/management/login`.

Setelah menyimpan tema, buka ulang halaman toko dan panel untuk melihat hasilnya. Warna kuning tetap digunakan sampai Developer mengubahnya. Logo mendukung gambar persegi maupun horizontal; motif disimpan di disk public, sehingga ikut dipindahkan saat migrasi server seperti gambar produk.

### Server lokal

Server lokal saat ini sudah berjalan. Bila berhenti, buka terminal di folder `e-commerce-new` dan jalankan:

```powershell
php artisan serve
```

Perubahan database sudah dimigrasikan. Untuk menyiapkan lingkungan lain:

```powershell
php artisan migrate
php artisan storage:link
php artisan db:seed --class=PresentationDemoSeeder
```

Seeder demo dijalankan secara eksplisit; tidak dimasukkan ke seeder produksi. Menjalankan ulang pada bulan yang sama tidak menggandakan pesanan atau pengeluaran demo. Seeder hanya menyesuaikan katalog template dengan SKU `DUMMY-PRD-*` dan tidak mengganti password akun yang sudah ada.

Cadangan data sebelum persiapan tersedia secara lokal di `storage/app/private/before-presentation-*.json`. File ini berisi data pribadi dan hanya untuk pemulihan lokal.

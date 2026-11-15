# Panduan presentasi

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

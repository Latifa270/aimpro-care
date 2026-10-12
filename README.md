A. AIMpro Care
AIMpro Care adalah aplikasi web untuk mengelola pemeriksaan produk, penjualan, garansi, dan klaim garansi. Aplikasi ini membantu petugas dan admin menyimpan data dengan lebih rapi, sementara pembeli dapat mengecek garansi serta mengajukan klaim tanpa login.
Alur utama: Pemeriksaan QC → Produk Siap Dijual → Penjualan → Garansi Aktif → Cek Garansi → Klaim Garansi.

B. Daftar Isi

- Tentang AIMpro Care — Gambaran singkat tentang fungsi aplikasi.
- Teknologi — Teknologi yang digunakan untuk membangun aplikasi.
- Pengguna dan Hak Akses — Penjelasan tugas petugas QC, admin, dan pembeli.
- Fitur Utama — Ringkasan fitur yang tersedia di aplikasi.
- Alur QC, Penjualan, dan Garansi — Cara produk diperiksa, dijual, lalu mendapat garansi.
- Klaim Garansi — Cara pembeli mengajukan klaim dan status prosesnya.
- Portal Pembeli — Cara pembeli mengecek garansi dan melihat kartu garansi.
- WhatsApp, Dashboard, dan Laporan — Informasi notifikasi dan ringkasan data.
- Keamanan — Cara aplikasi menjaga akun dan data.
- Database dan Struktur Project — Gambaran tabel dan susunan folder project.
- Instalasi — Langkah umum untuk menjalankan aplikasi di komputer lokal.
- Catatan Pengembangan — Hal penting yang perlu diperhatikan saat mengembangkan aplikasi.
- Lisensi — Informasi lisensi project.

C. Tentang AIMpro Care
AIMpro Care memiliki dua fungsi utama:

1. Mencatat hasil pemeriksaan produk sebelum dijual.
2. Mengelola garansi dan klaim setelah produk dibeli.
   Setiap unit memiliki nomor seri unik. Setelah lolos QC, unit dapat dicatat sebagai penjualan dan diberi garansi. Pembeli dapat mengecek garansi menggunakan nomor seri, nomor garansi, atau QR Code.

D. Teknologi

- Backend: PHP 8 dan CodeIgniter 4
- Database: MySQL atau MariaDB
- Tampilan: Bootstrap 5, HTML, CSS, dan JavaScript
- QR Code: endroid/qr-code
- PDF: dompdf/dompdf
- Excel: phpoffice/phpspreadsheet
- WhatsApp: WAKITA — https://wakita.aminsproject.com
- PWA: Manifest dan Service Worker

E. Pengguna dan Hak Akses

- Petugas QC: memasukkan data produk, mengisi checklist, mengunggah foto atau video, dan mencatat hasil pemeriksaan.
- Admin: mengelola produk, pembeli, penjualan, garansi, klaim, laporan, dan pengaturan.
- Pembeli: mengecek garansi, melihat kartu garansi, mengajukan klaim, dan memantau status klaim tanpa login.

F. Fitur Utama

- Manajemen produk: mencatat data produk dan nomor seri setiap unit.
- Quality Control (QC): menyimpan checklist, foto, video, catatan, dan hasil pemeriksaan.
- Penjualan: mencatat data pembeli, tanggal pembelian, dan informasi transaksi.
- Garansi digital: membuat nomor garansi, menentukan masa berlaku, serta membuat QR Code dan kartu garansi.
- Klaim garansi: mencatat pengajuan, bukti kerusakan, status, dan riwayat klaim.
- Dashboard dan laporan: menampilkan ringkasan QC, penjualan, garansi, dan klaim.
- Activity log: mencatat aktivitas penting pengguna.

G. Alur QC, Penjualan, dan Garansi

1. Petugas QC memasukkan data unit dan nomor seri.
2. Petugas memeriksa kondisi fisik, kelengkapan, dan fungsi produk.
3. Petugas mengunggah dokumentasi serta menentukan hasil QC.
4. Unit yang lolos QC dapat diproses untuk dijual.
5. Admin mencatat penjualan dan data pembeli.
6. Sistem membuat nomor garansi, tanggal berlaku, QR Code, dan kartu garansi.
   Hasil QC dapat berupa Lolos QC, Tidak Lolos QC, atau Perlu Pemeriksaan Ulang. Unit yang belum lolos QC tidak boleh dijual.

H. Klaim Garansi
Pembeli dapat mengajukan klaim melalui portal dengan mengisi nomor seri atau nomor garansi, menjelaskan kerusakan, dan mengunggah bukti jika diperlukan.
Status klaim dapat meliputi:

- Pengajuan Baru
- Sedang Diverifikasi
- Klaim Diterima atau Ditolak
- Barang Diterima
- Sedang Diperiksa
- Sedang Diproses
- Selesai
  Perubahan status sebaiknya disertai waktu, pengguna, dan catatan.

I. Portal Pembeli
Pembeli dapat menggunakan nomor seri, nomor garansi, atau QR Code untuk mengecek status garansi. Portal juga dapat menampilkan kartu garansi dan menyediakan formulir klaim.
Perlindungan data: samarkan informasi pribadi pembeli. Foto dan video pemeriksaan internal tidak boleh ditampilkan secara publik.

J. WhatsApp, Dashboard, dan Laporan
Jika WAKITA sudah dikonfigurasi, aplikasi dapat mengirim notifikasi tentang aktivasi garansi, pengajuan klaim, perubahan status klaim, dan penyelesaian klaim.
Dashboard dan laporan dapat merangkum data QC, penjualan, garansi, serta klaim. Ekspor PDF atau Excel digunakan jika fiturnya sudah tersedia. Pastikan data yang ditampilkan berasal dari database.

K. Keamanan

- Simpan password dalam bentuk hash.
- Batasi akses sesuai peran pengguna.
- Gunakan validasi input dan perlindungan CSRF.
- Lindungi aplikasi dari SQL Injection dan XSS.
- Periksa jenis serta ukuran file yang diunggah.
- Gunakan token QR yang sulit ditebak.
- Lindungi data pribadi, foto, dan video internal.
- Gunakan HTTPS pada server produksi.

L. Database dan Struktur Project
Tabel utama yang direncanakan mencakup:

- users — data pengguna.
- products, product_units — data produk dan unit.
- qc_inspections, qc_checklist_items, qc_documentations — hasil dan dokumentasi QC.
- customers, sales — data pembeli dan penjualan.
- warranties, warranty_claims, claim_histories — garansi dan riwayat klaim.
- notifications, activity_logs, settings — notifikasi, catatan aktivitas, dan pengaturan.
  Struktur folder mengikuti CodeIgniter 4, seperti app/Controllers, app/Models, app/Views, app/Database, dan public/assets.

M. Instalasi
Persyaratan umum: PHP 8.x, Composer, MySQL/MariaDB, dan ekstensi PHP yang dibutuhkan.

1. Unduh atau clone repository.
2. Jalankan composer install.
3. Salin file env menjadi .env.
4. Atur URL aplikasi dan koneksi database di .env.
5. Buat database sesuai konfigurasi.
6. Jika migration tersedia, jalankan php spark migrate.
7. Jalankan aplikasi dengan php spark serve.
8. Buka http://localhost:8080 di browser.
   Perintah di atas perlu disesuaikan dengan isi repository. Jangan gunakan konfigurasi development untuk server produksi.

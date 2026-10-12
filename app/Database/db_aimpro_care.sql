-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Waktu pembuatan: 12 Okt 2026 pada 07.58
-- Versi server: 8.0.30
-- Versi PHP: 8.4.19

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Basis data: `db_aimpro_care`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `activity_logs`
--

CREATE TABLE `activity_logs` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `action` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `description` text COLLATE utf8mb4_general_ci,
  `ip_address` varchar(45) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `activity_logs`
--

INSERT INTO `activity_logs` (`id`, `user_id`, `action`, `description`, `ip_address`, `created_at`) VALUES
(1, 3, 'CREATE_UNIT', 'Menambahkan unit dengan serial number 098765432ds34565 untuk produk kulkas.', '::1', '0000-00-00 00:00:00'),
(2, 3, 'CREATE_PRODUCT', 'Menambahkan produk sun screen (Scora SPF PA++++).', '::1', '0000-00-00 00:00:00'),
(3, 3, 'UPDATE_PRODUCT', 'Memperbarui data produk sun screen (Scora SPF PA++++).', '::1', '0000-00-00 00:00:00'),
(4, 3, 'DELETE_PRODUCT', 'Menghapus produk sun screen (Scora SPF PA++++).', '::1', '0000-00-00 00:00:00'),
(5, 3, 'CREATE_PRODUCT', 'Menambahkan produk sun screen (Scora SPF PA++++).', '::1', '0000-00-00 00:00:00'),
(6, 3, 'CREATE_UNIT', 'Menambahkan unit dengan serial number 8994460553218 untuk produk sun screen.', '::1', '0000-00-00 00:00:00'),
(7, 4, 'CREATE_QC_INSPECTION', 'Memulai pemeriksaan QC untuk serial number 8994460553218.', '::1', '0000-00-00 00:00:00'),
(8, 4, 'SAVE_QC_CHECKLIST', 'Menyimpan 7 item checklist QC untuk serial number 8994460553218.', '::1', '0000-00-00 00:00:00'),
(9, 4, 'UPLOAD_QC_DOCUMENTATION', 'Mengunggah foto produk, foto nomor seri, dan video unboxing untuk serial number 8994460553218.', '::1', '0000-00-00 00:00:00'),
(10, 3, 'RESEND_TO_QC', 'Mengirim ulang unit dengan serial number 8994460553218 ke QC.', '::1', '0000-00-00 00:00:00'),
(11, 4, 'CREATE_QC_INSPECTION', 'Memulai pemeriksaan QC untuk serial number 8994460553218.', '::1', '0000-00-00 00:00:00'),
(12, 4, 'SAVE_QC_CHECKLIST', 'Menyimpan 7 item checklist QC untuk serial number 8994460553218.', '::1', '0000-00-00 00:00:00'),
(13, 4, 'UPLOAD_QC_DOCUMENTATION', 'Mengunggah foto produk, foto nomor seri, dan video unboxing untuk serial number 8994460553218.', '::1', '0000-00-00 00:00:00'),
(14, 3, 'CREATE_PRODUCT', 'Menambahkan produk Sun screen (Scora 892232109872).', '::1', '0000-00-00 00:00:00'),
(15, 3, 'CREATE_UNIT', 'Menambahkan unit dengan serial number 892232109872 untuk produk Sun screen.', '::1', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Struktur dari tabel `claim_histories`
--

CREATE TABLE `claim_histories` (
  `id` bigint UNSIGNED NOT NULL,
  `warranty_claim_id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `status` enum('Pengajuan Baru','Sedang Diverifikasi','Klaim Diterima','Klaim Ditolak','Barang Diterima','Sedang Diperiksa','Sedang Diproses','Selesai') COLLATE utf8mb4_general_ci NOT NULL,
  `note` text COLLATE utf8mb4_general_ci,
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `customers`
--

CREATE TABLE `customers` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `whatsapp` varchar(20) COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `address` text COLLATE utf8mb4_general_ci NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `migrations`
--

CREATE TABLE `migrations` (
  `id` bigint UNSIGNED NOT NULL,
  `version` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `class` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `group` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `namespace` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `time` int NOT NULL,
  `batch` int UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `migrations`
--

INSERT INTO `migrations` (`id`, `version`, `class`, `group`, `namespace`, `time`, `batch`) VALUES
(1, '2026-09-21-061639', 'App\\Database\\Migrations\\CreateUsersTable', 'default', 'App', 1789974474, 1),
(2, '2026-09-21-062228', 'App\\Database\\Migrations\\CreateProductsTable', 'default', 'App', 1789974474, 1),
(3, '2026-09-21-071519', 'App\\Database\\Migrations\\CreateCustomersTable', 'default', 'App', 1789975072, 2),
(4, '2026-09-21-072253', 'App\\Database\\Migrations\\CreateSettingsTable', 'default', 'App', 1789976116, 3),
(5, '2026-09-21-072919', 'App\\Database\\Migrations\\CreateProductUnitsTable', 'default', 'App', 1789976116, 3),
(6, '2026-09-21-074215', 'App\\Database\\Migrations\\CreateQcInspectionsTable', 'default', 'App', 1789977033, 4),
(7, '2026-09-21-074539', 'App\\Database\\Migrations\\CreateQcChecklistItemsTable', 'default', 'App', 1789977033, 4),
(8, '2026-09-21-074751', 'App\\Database\\Migrations\\CreateQcDocumentationsTable', 'default', 'App', 1789977034, 4),
(9, '2026-09-21-075915', 'App\\Database\\Migrations\\CreateSalesTable', 'default', 'App', 1789977971, 5),
(10, '2026-09-21-080146', 'App\\Database\\Migrations\\CreateWarrantiesTable', 'default', 'App', 1789977971, 5),
(11, '2026-09-21-080423', 'App\\Database\\Migrations\\CreateWarrantyClaimsTable', 'default', 'App', 1789977972, 5),
(15, '2026-09-21-080900', 'App\\Database\\Migrations\\CreateClaimHistoriesTable', 'default', 'App', 1790825334, 6),
(16, '2026-09-21-081125', 'App\\Database\\Migrations\\CreateNotificationsTable', 'default', 'App', 1790825335, 6),
(17, '2026-09-21-081307', 'App\\Database\\Migrations\\CreateActivityLogsTable', 'default', 'App', 1790825336, 6),
(18, '2026-10-01-221425', 'App\\Database\\Migrations\\UpdateWarrantyAndProductDuration', 'default', 'App', 1790894106, 7),
(19, '2026-10-04-233629', 'App\\Database\\Migrations\\MakeClaimHistoryUserIdNullable', 'default', 'App', 1791157081, 8);

-- --------------------------------------------------------

--
-- Struktur dari tabel `notifications`
--

CREATE TABLE `notifications` (
  `id` bigint UNSIGNED NOT NULL,
  `warranty_id` bigint UNSIGNED DEFAULT NULL,
  `warranty_claim_id` bigint UNSIGNED DEFAULT NULL,
  `destination` varchar(20) COLLATE utf8mb4_general_ci NOT NULL,
  `template` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `sent_at` datetime DEFAULT NULL,
  `status` enum('Berhasil','Gagal') COLLATE utf8mb4_general_ci NOT NULL,
  `gateway_response` text COLLATE utf8mb4_general_ci,
  `created_at` datetime NOT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `products`
--

CREATE TABLE `products` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `brand` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `model` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `description` text COLLATE utf8mb4_general_ci,
  `warranty_duration_months` int UNSIGNED DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `products`
--

INSERT INTO `products` (`id`, `name`, `brand`, `model`, `description`, `warranty_duration_months`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'latifa jauharatul', 'H&M', 'Croptop', 'bahan premium, lembut', NULL, '2026-09-23 09:54:39', '2026-09-24 10:56:34', '2026-09-24 10:56:34'),
(2, 'kulkas', 'LG', '2 pintu', 'dingin, sesuai dengan identitas yang tertera', NULL, '2026-09-24 03:15:02', '2026-09-24 03:15:02', NULL),
(3, 'Laptop ASUS Vivobook 15', 'Asus', 'X1504', 'product sudah diperbarui', NULL, '2026-09-24 10:25:34', '2026-09-24 10:41:46', NULL),
(4, 'Mesin Cuci', 'Sharp', 'X1504', 'Mesin cuci 2 lubang', NULL, '2026-09-29 13:06:49', '2026-09-30 15:43:41', NULL),
(5, 'sun screen', 'Scora', 'SPF PA++++', 'anti UV, UVB', NULL, '2026-10-12 06:48:21', '2026-10-12 07:02:01', '2026-10-12 07:02:01'),
(6, 'sun screen', 'Scora', 'SPF PA++++', 'anti UVA, UVB', NULL, '2026-10-12 07:02:25', '2026-10-12 07:02:25', NULL),
(7, 'Sun screen', 'Scora', '892232109872', 'sun screen dengan spf 40 PA++++', NULL, '2026-10-12 09:52:58', '2026-10-12 09:52:58', NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `product_units`
--

CREATE TABLE `product_units` (
  `id` bigint UNSIGNED NOT NULL,
  `product_id` bigint UNSIGNED NOT NULL,
  `serial_number` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `status` enum('Draft','Menunggu QC','Perlu Pemeriksaan Ulang','Tidak Lolos QC','Siap Dijual','Terjual','Dikembalikan') COLLATE utf8mb4_general_ci NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `product_units`
--

INSERT INTO `product_units` (`id`, `product_id`, `serial_number`, `status`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 2, '098765432ds34565556', 'Draft', '2026-09-24 11:29:05', '2026-09-24 11:29:05', NULL),
(2, 2, '098765DSA34', 'Siap Dijual', '2026-09-24 14:33:08', '2026-09-24 16:11:26', NULL),
(3, 3, '098765DSA345R', 'Siap Dijual', '2026-09-24 16:39:22', '2026-09-25 09:49:54', NULL),
(4, 4, '098765DSA346', 'Perlu Pemeriksaan Ulang', '2026-09-29 13:07:43', '2026-09-29 14:12:13', NULL),
(5, 2, '098765432ds34565', 'Draft', '2026-10-07 11:28:59', '2026-10-07 11:28:59', NULL),
(6, 6, '8994460553218', 'Siap Dijual', '2026-10-12 07:04:48', '2026-10-12 07:36:17', NULL),
(7, 7, '892232109872', 'Menunggu QC', '2026-10-12 09:54:01', '2026-10-12 09:54:01', NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `qc_checklist_items`
--

CREATE TABLE `qc_checklist_items` (
  `id` bigint UNSIGNED NOT NULL,
  `qc_inspection_id` bigint UNSIGNED NOT NULL,
  `item_name` varchar(150) COLLATE utf8mb4_general_ci NOT NULL,
  `result` tinyint(1) NOT NULL,
  `note` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `qc_checklist_items`
--

INSERT INTO `qc_checklist_items` (`id`, `qc_inspection_id`, `item_name`, `result`, `note`, `created_at`, `updated_at`) VALUES
(1, 5, 'Kemasan dalam kondisi baik', 1, NULL, '2026-09-24 16:11:11', '2026-09-24 16:11:11'),
(2, 5, 'Produk tidak lecet atau retak', 1, NULL, '2026-09-24 16:11:11', '2026-09-24 16:11:11'),
(3, 5, 'Nomor seri produk sesuai dengan kemasan', 1, NULL, '2026-09-24 16:11:11', '2026-09-24 16:11:11'),
(4, 5, 'Kelengkapan produk sesuai', 1, NULL, '2026-09-24 16:11:11', '2026-09-24 16:11:11'),
(5, 5, 'Produk dapat dinyalakan', 1, NULL, '2026-09-24 16:11:11', '2026-09-24 16:11:11'),
(6, 5, 'Fungsi dasar berjalan', 1, NULL, '2026-09-24 16:11:11', '2026-09-24 16:11:11'),
(7, 5, 'Tidak ditemukan kerusakan', 1, NULL, '2026-09-24 16:11:11', '2026-09-24 16:11:11'),
(8, 6, 'Kemasan dalam kondisi baik', 1, NULL, '2026-09-24 16:43:02', '2026-09-24 16:43:02'),
(9, 6, 'Produk tidak lecet atau retak', 1, NULL, '2026-09-24 16:43:02', '2026-09-24 16:43:02'),
(10, 6, 'Nomor seri produk sesuai dengan kemasan', 1, NULL, '2026-09-24 16:43:02', '2026-09-24 16:43:02'),
(11, 6, 'Kelengkapan produk sesuai', 1, NULL, '2026-09-24 16:43:02', '2026-09-24 16:43:02'),
(12, 6, 'Produk dapat dinyalakan', 1, NULL, '2026-09-24 16:43:02', '2026-09-24 16:43:02'),
(13, 6, 'Fungsi dasar berjalan', 1, NULL, '2026-09-24 16:43:02', '2026-09-24 16:43:02'),
(14, 6, 'Tidak ditemukan kerusakan', 1, NULL, '2026-09-24 16:43:02', '2026-09-24 16:43:02'),
(15, 7, 'Kemasan dalam kondisi baik', 1, NULL, '2026-09-25 09:47:15', '2026-09-25 09:47:15'),
(16, 7, 'Produk tidak lecet atau retak', 1, NULL, '2026-09-25 09:47:16', '2026-09-25 09:47:16'),
(17, 7, 'Nomor seri produk sesuai dengan kemasan', 1, NULL, '2026-09-25 09:47:16', '2026-09-25 09:47:16'),
(18, 7, 'Kelengkapan produk sesuai', 1, NULL, '2026-09-25 09:47:16', '2026-09-25 09:47:16'),
(19, 7, 'Produk dapat dinyalakan', 1, NULL, '2026-09-25 09:47:16', '2026-09-25 09:47:16'),
(20, 7, 'Fungsi dasar berjalan', 1, NULL, '2026-09-25 09:47:16', '2026-09-25 09:47:16'),
(21, 7, 'Tidak ditemukan kerusakan', 1, NULL, '2026-09-25 09:47:16', '2026-09-25 09:47:16'),
(22, 8, 'Kemasan dalam kondisi baik', 1, NULL, '2026-09-29 13:11:12', '2026-09-29 13:11:12'),
(23, 8, 'Produk tidak lecet atau retak', 1, NULL, '2026-09-29 13:11:12', '2026-09-29 13:11:12'),
(24, 8, 'Nomor seri produk sesuai dengan kemasan', 0, NULL, '2026-09-29 13:11:12', '2026-09-29 13:11:12'),
(25, 8, 'Kelengkapan produk sesuai', 1, NULL, '2026-09-29 13:11:12', '2026-09-29 13:11:12'),
(26, 8, 'Produk dapat dinyalakan', 1, NULL, '2026-09-29 13:11:12', '2026-09-29 13:11:12'),
(27, 8, 'Fungsi dasar berjalan', 1, NULL, '2026-09-29 13:11:12', '2026-09-29 13:11:12'),
(28, 8, 'Tidak ditemukan kerusakan', 1, NULL, '2026-09-29 13:11:12', '2026-09-29 13:11:12'),
(29, 9, 'Kemasan dalam kondisi baik', 1, NULL, '2026-09-29 14:02:52', '2026-09-29 14:02:52'),
(30, 9, 'Produk tidak lecet atau retak', 1, NULL, '2026-09-29 14:02:52', '2026-09-29 14:02:52'),
(31, 9, 'Nomor seri produk sesuai dengan kemasan', 0, NULL, '2026-09-29 14:02:52', '2026-09-29 14:02:52'),
(32, 9, 'Kelengkapan produk sesuai', 1, NULL, '2026-09-29 14:02:52', '2026-09-29 14:02:52'),
(33, 9, 'Produk dapat dinyalakan', 1, NULL, '2026-09-29 14:02:52', '2026-09-29 14:02:52'),
(34, 9, 'Fungsi dasar berjalan', 1, NULL, '2026-09-29 14:02:52', '2026-09-29 14:02:52'),
(35, 9, 'Tidak ditemukan kerusakan', 1, NULL, '2026-09-29 14:02:52', '2026-09-29 14:02:52'),
(36, 10, 'Kemasan dalam kondisi baik', 1, NULL, '2026-10-12 07:06:06', '2026-10-12 07:06:06'),
(37, 10, 'Produk tidak lecet atau retak', 1, NULL, '2026-10-12 07:06:06', '2026-10-12 07:06:06'),
(38, 10, 'Nomor seri produk sesuai dengan kemasan', 1, NULL, '2026-10-12 07:06:06', '2026-10-12 07:06:06'),
(39, 10, 'Kelengkapan produk sesuai', 1, NULL, '2026-10-12 07:06:06', '2026-10-12 07:06:06'),
(40, 10, 'Produk dapat dinyalakan', 1, NULL, '2026-10-12 07:06:06', '2026-10-12 07:06:06'),
(41, 10, 'Fungsi dasar berjalan', 1, NULL, '2026-10-12 07:06:06', '2026-10-12 07:06:06'),
(42, 10, 'Tidak ditemukan kerusakan', 1, NULL, '2026-10-12 07:06:06', '2026-10-12 07:06:06'),
(43, 11, 'Kemasan dalam kondisi baik', 1, NULL, '2026-10-12 07:35:17', '2026-10-12 07:35:17'),
(44, 11, 'Produk tidak lecet atau retak', 1, NULL, '2026-10-12 07:35:17', '2026-10-12 07:35:17'),
(45, 11, 'Nomor seri produk sesuai dengan kemasan', 1, NULL, '2026-10-12 07:35:17', '2026-10-12 07:35:17'),
(46, 11, 'Kelengkapan produk sesuai', 1, NULL, '2026-10-12 07:35:17', '2026-10-12 07:35:17'),
(47, 11, 'Produk dapat dinyalakan', 1, NULL, '2026-10-12 07:35:17', '2026-10-12 07:35:17'),
(48, 11, 'Fungsi dasar berjalan', 1, NULL, '2026-10-12 07:35:17', '2026-10-12 07:35:17'),
(49, 11, 'Tidak ditemukan kerusakan', 1, NULL, '2026-10-12 07:35:17', '2026-10-12 07:35:17');

-- --------------------------------------------------------

--
-- Struktur dari tabel `qc_documentations`
--

CREATE TABLE `qc_documentations` (
  `id` bigint UNSIGNED NOT NULL,
  `qc_inspection_id` bigint UNSIGNED NOT NULL,
  `document_type` enum('Video Unboxing','Foto Nomor Seri','Foto Produk') COLLATE utf8mb4_general_ci NOT NULL,
  `file_path` varchar(500) COLLATE utf8mb4_general_ci NOT NULL,
  `file_name` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `mime_type` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `file_size` bigint UNSIGNED NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `qc_documentations`
--

INSERT INTO `qc_documentations` (`id`, `qc_inspection_id`, `document_type`, `file_path`, `file_name`, `mime_type`, `file_size`, `created_at`, `updated_at`) VALUES
(1, 6, 'Foto Produk', 'uploads/qc/1790243098_bb97f6d8027c23c3b9b6.png', 'Cuplikan layar 2026-06-23 073832.png', 'image/png', 94756, '2026-09-24 16:44:58', '2026-09-24 16:44:58'),
(2, 7, 'Foto Produk', 'uploads/qc/1790304583_fc7480164ae7ef6e33d9.png', 'Cuplikan layar 2026-09-24 170045.png', 'image/png', 3047, '2026-09-25 09:49:43', '2026-09-25 09:49:43'),
(3, 7, 'Foto Nomor Seri', 'uploads/qc/1790304583_b0bdde6e0bf36001e360.png', 'Cuplikan layar 2026-09-24 170045.png', 'image/png', 3047, '2026-09-25 09:49:44', '2026-09-25 09:49:44'),
(4, 7, 'Video Unboxing', 'uploads/qc/1790304583_75d648ef4af20fc63ea3.mp4', 'Perekaman Layar 2026-06-18 060902.mp4', 'video/mp4', 3773150, '2026-09-25 09:49:44', '2026-09-25 09:49:44'),
(5, 9, 'Foto Produk', 'uploads/qc/1790665440_da7932b8e1568ea2987b.png', 'Cuplikan layar 2026-09-25 100121.png', 'image/png', 1190, '2026-09-29 14:04:00', '2026-09-29 14:04:00'),
(6, 9, 'Foto Nomor Seri', 'uploads/qc/1790665440_ca4386c0e6f4130fa8f5.png', 'Cuplikan layar 2026-09-25 095647.png', 'image/png', 3173, '2026-09-29 14:04:00', '2026-09-29 14:04:00'),
(7, 9, 'Video Unboxing', 'uploads/qc/1790665440_fea72bfa4a35a90f576a.mp4', 'Perekaman Layar 2026-06-18 060902.mp4', 'video/mp4', 3773150, '2026-09-29 14:04:00', '2026-09-29 14:04:00'),
(8, 10, 'Foto Produk', 'uploads/qc/1791764248_b28e9edf92fca9f8dbed.jpg', 'LOGO1.jpg', 'image/jpeg', 731074, '2026-10-12 07:17:28', '2026-10-12 07:17:28'),
(9, 10, 'Foto Nomor Seri', 'uploads/qc/1791764248_1b9f37a330ab4684b02f.jpg', 'LOGO1.jpg', 'image/jpeg', 731074, '2026-10-12 07:17:28', '2026-10-12 07:17:28'),
(10, 11, 'Foto Produk', 'uploads/qc/1791765330_bfe7a5ff68fec931600e.jpg', 'LOGO1.jpg', 'image/jpeg', 731074, '2026-10-12 07:35:30', '2026-10-12 07:35:30'),
(11, 11, 'Foto Nomor Seri', 'uploads/qc/1791765330_e658d1f78bc6fe5465ab.jpg', 'LOGO1.jpg', 'image/jpeg', 731074, '2026-10-12 07:35:30', '2026-10-12 07:35:30');

-- --------------------------------------------------------

--
-- Struktur dari tabel `qc_inspections`
--

CREATE TABLE `qc_inspections` (
  `id` bigint UNSIGNED NOT NULL,
  `product_unit_id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `inspection_date` datetime NOT NULL,
  `physical_condition` text COLLATE utf8mb4_general_ci NOT NULL,
  `completeness` text COLLATE utf8mb4_general_ci NOT NULL,
  `result` enum('Lolos QC','Tidak Lolos QC','Perlu Pemeriksaan Ulang') COLLATE utf8mb4_general_ci NOT NULL,
  `notes` text COLLATE utf8mb4_general_ci,
  `created_at` datetime NOT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `qc_inspections`
--

INSERT INTO `qc_inspections` (`id`, `product_unit_id`, `user_id`, `inspection_date`, `physical_condition`, `completeness`, `result`, `notes`, `created_at`, `updated_at`) VALUES
(1, 2, 4, '2026-09-24 15:19:59', 'baik', 'semua masih dalam keadaan ORI', 'Perlu Pemeriksaan Ulang', 'siap dijual', '2026-09-24 15:19:59', '2026-09-24 15:19:59'),
(2, 2, 4, '2026-09-24 15:22:57', 'baik sekali', 'kelengkapan produk, semua lengkap dan masih dalam kondisi ORIII', 'Perlu Pemeriksaan Ulang', 'baik', '2026-09-24 15:22:57', '2026-09-24 15:22:57'),
(3, 2, 4, '2026-09-24 16:02:38', 'baik', 'baik', 'Perlu Pemeriksaan Ulang', 'baik', '2026-09-24 16:02:38', '2026-09-24 16:02:38'),
(4, 2, 4, '2026-09-24 16:04:11', 'baik', 'baik', 'Perlu Pemeriksaan Ulang', 'baik', '2026-09-24 16:04:11', '2026-09-24 16:04:11'),
(5, 2, 4, '2026-09-24 16:10:41', 'baik', 'baik', 'Lolos QC', 'baik', '2026-09-24 16:10:41', '2026-09-24 16:11:26'),
(6, 3, 4, '2026-09-24 16:42:40', 'Bagus', 'Perangkat masih lengkap dan ORI', 'Perlu Pemeriksaan Ulang', '', '2026-09-24 16:42:40', '2026-09-24 16:42:40'),
(7, 3, 4, '2026-09-25 09:46:53', 'baik', 'baik', 'Lolos QC', 'baik', '2026-09-25 09:46:53', '2026-09-25 09:49:54'),
(8, 4, 4, '2026-09-29 13:10:34', 'sedikit lecet pada bagian body mesin cuci', 'semua lengkap, ORI', 'Perlu Pemeriksaan Ulang', 'cek kembali sebelum lolos', '2026-09-29 13:10:34', '2026-09-29 13:10:34'),
(9, 4, 4, '2026-09-29 14:02:17', 'sedikit lecet bagian tutupnya', 'semuanya lengkap mulai dari kabel, kardusnya, dll', 'Perlu Pemeriksaan Ulang', NULL, '2026-09-29 14:02:17', '2026-09-29 14:12:13'),
(10, 6, 4, '2026-10-12 07:05:43', 'kondisi fisik, bagus, masih bersegel rapi', 'semua lengkap, dan produk sudah dipastikan ORI', 'Perlu Pemeriksaan Ulang', NULL, '2026-10-12 07:05:43', '2026-10-12 07:17:36'),
(11, 6, 4, '2026-10-12 07:33:13', 'kondisi aman, masih tersegel rapi dan tidak ada tanda-tanda kecacatan', 'produk lengkap, dan sudah dipastikan ORI', 'Lolos QC', NULL, '2026-10-12 07:33:13', '2026-10-12 07:36:17');

-- --------------------------------------------------------

--
-- Struktur dari tabel `sales`
--

CREATE TABLE `sales` (
  `id` bigint UNSIGNED NOT NULL,
  `product_unit_id` bigint UNSIGNED NOT NULL,
  `customer_id` bigint UNSIGNED NOT NULL,
  `purchase_date` date NOT NULL,
  `invoice_number` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `marketplace` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `marketplace_order_number` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `receipt_path` varchar(500) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `sales_note` text COLLATE utf8mb4_general_ci,
  `created_at` datetime NOT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `settings`
--

CREATE TABLE `settings` (
  `id` bigint UNSIGNED NOT NULL,
  `key` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `value` text COLLATE utf8mb4_general_ci,
  `created_at` datetime NOT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `settings`
--

INSERT INTO `settings` (`id`, `key`, `value`, `created_at`, `updated_at`) VALUES
(1, 'company_name', 'AIMpro Care', '2026-10-11 08:10:41', '2026-10-11 08:12:12'),
(2, 'company_address', '', '2026-10-11 08:10:41', '2026-10-11 08:12:13'),
(3, 'company_phone', '', '2026-10-11 08:10:41', '2026-10-11 08:12:13'),
(4, 'max_file_size', '5094', '2026-10-11 08:10:41', '2026-10-11 08:12:13'),
(5, 'warranty_reminder_days', '3', '2026-10-11 08:10:41', '2026-10-11 08:12:13');

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `password` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `role` enum('QC','Admin') COLLATE utf8mb4_general_ci NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `role`, `created_at`, `updated_at`, `deleted_at`) VALUES
(3, 'Administrator', 'admin@aimprocare.test', '$2y$12$vzfR/xUxxE1aAN8UXvBQp.drxTj48e.nyva8Im.9d16vLob/VR46e', 'Admin', '2026-09-23 06:49:29', '2026-09-23 06:49:29', NULL),
(4, 'Petugas QC', 'qc@aimprocare.test', '$2y$12$M7DD2kUFx.jr/nrb3/DxE.y5Aqt5eD/72FceClC9R2l0g/pwkyWgW', 'QC', '2026-09-23 06:49:30', '2026-09-23 06:49:30', NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `warranties`
--

CREATE TABLE `warranties` (
  `id` bigint UNSIGNED NOT NULL,
  `sales_id` bigint UNSIGNED NOT NULL,
  `warranty_number` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `duration_months` int UNSIGNED DEFAULT NULL,
  `qr_token` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `card_path` varchar(500) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `status` enum('Garansi Aktif','Garansi Berakhir') COLLATE utf8mb4_general_ci NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `warranty_claims`
--

CREATE TABLE `warranty_claims` (
  `id` bigint UNSIGNED NOT NULL,
  `warranty_id` bigint UNSIGNED NOT NULL,
  `complaint` text COLLATE utf8mb4_general_ci NOT NULL,
  `damage_start_date` date NOT NULL,
  `damage_photo_path` varchar(500) COLLATE utf8mb4_general_ci NOT NULL,
  `damage_video_path` varchar(500) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `whatsapp` varchar(20) COLLATE utf8mb4_general_ci NOT NULL,
  `handover_method` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `shipping_address` text COLLATE utf8mb4_general_ci,
  `terms_agreed` tinyint(1) NOT NULL,
  `status` enum('Pengajuan Baru','Sedang Diverifikasi','Klaim Diterima','Klaim Ditolak','Barang Diterima','Sedang Diperiksa','Sedang Diproses','Selesai') COLLATE utf8mb4_general_ci NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indeks untuk tabel yang dibuang
--

--
-- Indeks untuk tabel `activity_logs`
--
ALTER TABLE `activity_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `activity_logs_user_id_foreign` (`user_id`),
  ADD KEY `action` (`action`);

--
-- Indeks untuk tabel `claim_histories`
--
ALTER TABLE `claim_histories`
  ADD PRIMARY KEY (`id`),
  ADD KEY `claim_histories_warranty_claim_id_foreign` (`warranty_claim_id`),
  ADD KEY `claim_histories_user_id_foreign` (`user_id`),
  ADD KEY `status` (`status`);

--
-- Indeks untuk tabel `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `notifications_warranty_id_foreign` (`warranty_id`),
  ADD KEY `notifications_warranty_claim_id_foreign` (`warranty_claim_id`),
  ADD KEY `status` (`status`);

--
-- Indeks untuk tabel `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `product_units`
--
ALTER TABLE `product_units`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `product_units_serial_number_unique` (`serial_number`),
  ADD KEY `product_units_product_id_foreign` (`product_id`),
  ADD KEY `status` (`status`);

--
-- Indeks untuk tabel `qc_checklist_items`
--
ALTER TABLE `qc_checklist_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `qc_checklist_items_qc_inspection_id_foreign` (`qc_inspection_id`);

--
-- Indeks untuk tabel `qc_documentations`
--
ALTER TABLE `qc_documentations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `qc_documentations_qc_inspection_id_foreign` (`qc_inspection_id`);

--
-- Indeks untuk tabel `qc_inspections`
--
ALTER TABLE `qc_inspections`
  ADD PRIMARY KEY (`id`),
  ADD KEY `qc_inspections_product_unit_id_foreign` (`product_unit_id`),
  ADD KEY `qc_inspections_user_id_foreign` (`user_id`),
  ADD KEY `result` (`result`);

--
-- Indeks untuk tabel `sales`
--
ALTER TABLE `sales`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `sales_product_unit_unique` (`product_unit_id`),
  ADD KEY `sales_customer_id_foreign` (`customer_id`),
  ADD KEY `invoice_number` (`invoice_number`);

--
-- Indeks untuk tabel `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `settings_key_unique` (`key`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- Indeks untuk tabel `warranties`
--
ALTER TABLE `warranties`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `warranties_sale_unique` (`sales_id`),
  ADD UNIQUE KEY `warranties_number_unique` (`warranty_number`),
  ADD UNIQUE KEY `warranties_qr_token_unique` (`qr_token`),
  ADD KEY `end_date` (`end_date`),
  ADD KEY `status` (`status`);

--
-- Indeks untuk tabel `warranty_claims`
--
ALTER TABLE `warranty_claims`
  ADD PRIMARY KEY (`id`),
  ADD KEY `warranty_claims_warranty_id_foreign` (`warranty_id`),
  ADD KEY `status` (`status`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `activity_logs`
--
ALTER TABLE `activity_logs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT untuk tabel `claim_histories`
--
ALTER TABLE `claim_histories`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `customers`
--
ALTER TABLE `customers`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT untuk tabel `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `products`
--
ALTER TABLE `products`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT untuk tabel `product_units`
--
ALTER TABLE `product_units`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT untuk tabel `qc_checklist_items`
--
ALTER TABLE `qc_checklist_items`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=50;

--
-- AUTO_INCREMENT untuk tabel `qc_documentations`
--
ALTER TABLE `qc_documentations`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT untuk tabel `qc_inspections`
--
ALTER TABLE `qc_inspections`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT untuk tabel `sales`
--
ALTER TABLE `sales`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `settings`
--
ALTER TABLE `settings`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT untuk tabel `warranties`
--
ALTER TABLE `warranties`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `warranty_claims`
--
ALTER TABLE `warranty_claims`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `activity_logs`
--
ALTER TABLE `activity_logs`
  ADD CONSTRAINT `activity_logs_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `claim_histories`
--
ALTER TABLE `claim_histories`
  ADD CONSTRAINT `claim_histories_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  ADD CONSTRAINT `claim_histories_warranty_claim_id_foreign` FOREIGN KEY (`warranty_claim_id`) REFERENCES `warranty_claims` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `notifications`
--
ALTER TABLE `notifications`
  ADD CONSTRAINT `notifications_warranty_claim_id_foreign` FOREIGN KEY (`warranty_claim_id`) REFERENCES `warranty_claims` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  ADD CONSTRAINT `notifications_warranty_id_foreign` FOREIGN KEY (`warranty_id`) REFERENCES `warranties` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `product_units`
--
ALTER TABLE `product_units`
  ADD CONSTRAINT `product_units_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `qc_checklist_items`
--
ALTER TABLE `qc_checklist_items`
  ADD CONSTRAINT `qc_checklist_items_qc_inspection_id_foreign` FOREIGN KEY (`qc_inspection_id`) REFERENCES `qc_inspections` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `qc_documentations`
--
ALTER TABLE `qc_documentations`
  ADD CONSTRAINT `qc_documentations_qc_inspection_id_foreign` FOREIGN KEY (`qc_inspection_id`) REFERENCES `qc_inspections` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `qc_inspections`
--
ALTER TABLE `qc_inspections`
  ADD CONSTRAINT `qc_inspections_product_unit_id_foreign` FOREIGN KEY (`product_unit_id`) REFERENCES `product_units` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  ADD CONSTRAINT `qc_inspections_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `sales`
--
ALTER TABLE `sales`
  ADD CONSTRAINT `sales_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  ADD CONSTRAINT `sales_product_unit_id_foreign` FOREIGN KEY (`product_unit_id`) REFERENCES `product_units` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `warranties`
--
ALTER TABLE `warranties`
  ADD CONSTRAINT `warranties_sale_id_foreign` FOREIGN KEY (`sales_id`) REFERENCES `sales` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `warranty_claims`
--
ALTER TABLE `warranty_claims`
  ADD CONSTRAINT `warranty_claims_warranty_id_foreign` FOREIGN KEY (`warranty_id`) REFERENCES `warranties` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

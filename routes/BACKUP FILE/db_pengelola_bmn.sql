-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Waktu pembuatan: 18 Feb 2026 pada 06.06
-- Versi server: 8.0.30
-- Versi PHP: 8.2.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_pengelola_bmn`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `barangs`
--

CREATE TABLE `barangs` (
  `id` bigint UNSIGNED NOT NULL,
  `kode_barang` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `nama_barang` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `kategori_id` bigint UNSIGNED NOT NULL,
  `jumlah` int NOT NULL DEFAULT '0',
  `satuan` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `barangs`
--

INSERT INTO `barangs` (`id`, `kode_barang`, `nama_barang`, `kategori_id`, `jumlah`, `satuan`, `created_at`, `updated_at`) VALUES
(1, 'BMN-001', 'Laptop Dell Latitude 5420', 1, 10, 'Unit', '2026-01-08 06:48:09', '2026-02-04 03:40:46'),
(2, 'BMN-002', 'Printer HP LaserJet Pro', 1, 10, 'Unit', '2026-01-08 06:48:09', '2026-01-21 20:14:44'),
(3, 'BMN-003', 'Kursi Kantor Ergonomis', 2, 42, 'Unit', '2026-01-08 06:48:09', '2026-02-18 05:44:12'),
(4, 'BMN-004', 'Meja Kerja Kayu', 2, 40, 'Unit', '2026-01-08 06:48:09', '2026-02-13 08:38:59'),
(5, 'BMN-005', 'AC Daikin 2 PK', 1, 9, 'Unit', '2026-01-08 06:48:09', '2026-01-23 00:52:59'),
(6, 'BMN-006', 'Mobil Dinas Toyota', 3, 4, 'Unit', '2026-01-08 06:48:09', '2026-02-05 07:53:46'),
(7, 'BMN-007', 'Komputer Desktop', 1, 12, 'Unit', '2026-01-08 06:48:09', '2026-02-02 02:20:41'),
(8, 'BMN-008', 'Lemari Arsip Besi', 2, 25, 'Unit', '2026-01-08 06:48:09', '2026-01-23 01:21:48'),
(9, 'BMN-009', 'Scanner Canon', 1, 5, 'Unit', '2026-01-08 06:48:09', '2026-01-27 06:55:38'),
(10, 'BMN-010', 'Proyektor Epson', 1, 5, 'Unit', '2026-01-08 06:48:09', '2026-02-03 06:18:58'),
(11, 'BMN-011', 'Bolpoin STK', 4, 0, 'Lusin', '2026-01-13 21:20:30', '2026-01-22 20:18:35');

-- --------------------------------------------------------

--
-- Struktur dari tabel `barang_masuk`
--

CREATE TABLE `barang_masuk` (
  `id` bigint UNSIGNED NOT NULL,
  `barang_id` bigint UNSIGNED NOT NULL,
  `jumlah` int NOT NULL,
  `harga_satuan` decimal(15,2) NOT NULL,
  `harga_total` decimal(15,2) NOT NULL,
  `tanggal` date NOT NULL,
  `keterangan` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `barang_masuk`
--

INSERT INTO `barang_masuk` (`id`, `barang_id`, `jumlah`, `harga_satuan`, `harga_total`, `tanggal`, `keterangan`, `created_at`, `updated_at`) VALUES
(3, 1, 5, 10000000.00, 50000000.00, '2026-01-13', NULL, '2026-01-12 23:40:44', '2026-01-12 23:40:44'),
(4, 2, 2, 5000000.00, 10000000.00, '2026-01-14', NULL, '2026-01-13 22:53:41', '2026-01-13 22:53:41'),
(5, 5, 5, 4000000.00, 20000000.00, '2026-01-23', NULL, '2026-01-23 00:52:59', '2026-01-23 00:52:59'),
(6, 8, 5, 9000000.00, 45000000.00, '2026-01-23', 'gsdhfgjhsdfgj', '2026-01-23 01:19:36', '2026-01-23 01:19:36'),
(7, 10, 2, 4000000.00, 8000000.00, '2026-02-03', 'afwjhLIGLSHGDKSJADx', '2026-02-03 06:18:58', '2026-02-03 06:18:58'),
(8, 6, 1, 350000000.00, 350000000.00, '2026-02-05', NULL, '2026-02-05 07:53:46', '2026-02-05 07:53:46'),
(9, 4, 2, 500000.00, 1000000.00, '2026-02-13', 'menambah stock meja', '2026-02-13 08:39:00', '2026-02-13 08:39:00');

-- --------------------------------------------------------

--
-- Struktur dari tabel `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint UNSIGNED NOT NULL,
  `reserved_at` int UNSIGNED DEFAULT NULL,
  `available_at` int UNSIGNED NOT NULL,
  `created_at` int UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `kategori`
--

CREATE TABLE `kategori` (
  `id` bigint UNSIGNED NOT NULL,
  `nama` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `kategori`
--

INSERT INTO `kategori` (`id`, `nama`, `created_at`, `updated_at`) VALUES
(1, 'Elektronik', '2026-01-09 01:37:37', '2026-01-09 01:37:37'),
(2, 'Furniture', '2026-01-09 01:37:37', '2026-01-09 01:37:37'),
(3, 'Kendaraan', '2026-01-09 01:37:37', '2026-01-09 01:37:37'),
(4, 'Alat Tulis', '2026-01-09 01:37:37', '2026-01-09 01:37:37');

-- --------------------------------------------------------

--
-- Struktur dari tabel `lokasi`
--

CREATE TABLE `lokasi` (
  `id` bigint UNSIGNED NOT NULL,
  `nama` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `lokasi`
--

INSERT INTO `lokasi` (`id`, `nama`, `created_at`, `updated_at`) VALUES
(1, 'Ruang Seksi Tikkim', '2026-01-09 01:39:09', '2026-01-09 01:39:09'),
(2, 'Ruang Seksi Inteldakim', '2026-01-09 01:39:09', '2026-01-09 01:39:09'),
(3, 'Ruang Seksi Tata Usaha', '2026-01-09 01:39:09', '2026-01-09 01:39:09'),
(4, 'Ruang Seksi Doklan', '2026-01-09 01:39:09', '2026-01-09 01:39:09'),
(5, 'Ruang Kepala', '2026-01-09 01:39:09', '2026-01-09 01:39:09'),
(6, 'Gudang', '2026-01-09 01:39:09', '2026-01-09 01:39:09'),
(7, 'Ruang Arsip', '2026-01-09 01:39:09', '2026-01-09 01:39:09'),
(8, 'Parkir', '2026-01-09 01:39:09', '2026-01-09 01:39:09'),
(9, 'Ruang Server', '2026-01-09 01:39:09', '2026-01-09 01:39:09'),
(10, 'Ruang Staff', '2026-01-09 01:45:47', '2026-01-09 01:45:47');

-- --------------------------------------------------------

--
-- Struktur dari tabel `migrations`
--

CREATE TABLE `migrations` (
  `id` int UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data untuk tabel `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2026_01_13_033907_create_pengajuans_table', 2);

-- --------------------------------------------------------

--
-- Struktur dari tabel `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `pengajuans`
--

CREATE TABLE `pengajuans` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `barang_id` bigint UNSIGNED NOT NULL,
  `jumlah` int NOT NULL,
  `keperluan` text NOT NULL,
  `tanggal_pengajuan` date NOT NULL,
  `status` enum('Diajukan','Disetujui','Ditolak') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT 'Diajukan',
  `tanggal_proses` date DEFAULT NULL,
  `read_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data untuk tabel `pengajuans`
--

INSERT INTO `pengajuans` (`id`, `user_id`, `barang_id`, `jumlah`, `keperluan`, `tanggal_pengajuan`, `status`, `tanggal_proses`, `read_at`, `created_at`, `updated_at`) VALUES
(2, 2, 5, 2, 'zfsdf', '2026-01-15', 'Disetujui', '2026-01-16', '2026-02-02 06:00:17', '2026-01-14 19:48:26', '2026-02-02 06:00:17'),
(3, 2, 7, 1, 'sdsdf', '2026-01-16', 'Ditolak', '2026-01-16', '2026-02-02 06:00:17', '2026-01-15 22:56:20', '2026-02-02 06:00:17'),
(5, 2, 7, 1, 'asdhasdkajsdkjgwjegqwdugsai', '2026-01-23', 'Disetujui', '2026-02-02', '2026-02-02 06:00:16', '2026-01-22 19:11:26', '2026-02-02 06:00:16'),
(6, 3, 4, 2, 'askdgsgdkajsdgkjasgd', '2026-01-23', 'Disetujui', '2026-01-23', NULL, '2026-01-22 19:34:24', '2026-01-22 19:56:32'),
(7, 3, 10, 1, 'Untuk nobar netflix', '2026-01-23', 'Disetujui', '2026-01-24', NULL, '2026-01-23 00:29:48', '2026-01-28 03:03:23'),
(8, 2, 3, 2, 'buat duduk', '2026-02-03', 'Ditolak', '2026-02-13', '2026-02-13 02:45:55', '2026-02-03 01:04:56', '2026-02-13 02:45:55'),
(9, 2, 3, 2, 'chgc', '2026-02-18', 'Disetujui', '2026-02-18', NULL, '2026-02-18 04:46:18', '2026-02-18 05:44:12');

-- --------------------------------------------------------

--
-- Struktur dari tabel `seksi`
--

CREATE TABLE `seksi` (
  `id` bigint UNSIGNED NOT NULL,
  `seksi` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `nama_kepala` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `nip_kepala` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `seksi`
--

INSERT INTO `seksi` (`id`, `seksi`, `nama_kepala`, `nip_kepala`, `created_at`, `updated_at`) VALUES
(1, 'Seksi Teknologi Informasi dan Komunikasi Keimigrasian', 'M. Hari Ariansyah', '19781102 200501 1 008', '2026-02-04 01:27:25', '2026-02-05 00:57:17'),
(2, 'Seksi Dokumen Perjalanan dan Izin Tinggal Keimigrasian', 'Agus', '19850312 201012 1 002', '2026-02-04 01:34:27', '2026-02-05 00:56:37'),
(3, 'Seksi Intelijen dan Penindakan Keimigrasian', 'Wali', '19920725 201503 2 015', '2026-02-04 01:34:59', '2026-02-05 00:57:02'),
(4, 'Subbagian Tata Usaha', 'Sri Pamungkas Handayani', '19900115 201902 2 001', '2026-02-04 01:35:20', '2026-02-05 00:57:39'),
(5, 'PPNPN', 'AAA', '-', '2026-02-04 01:35:43', '2026-02-12 02:56:42');

-- --------------------------------------------------------

--
-- Struktur dari tabel `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text,
  `payload` longtext NOT NULL,
  `last_activity` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data untuk tabel `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('UnPlXBpCVhS4T4uFYnrYEginF4xGnraHPYH9wlts', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiNDg4d3lPa1ZBQVJwRXZpWHVGWkI1aHB6NFg4Wm01ZEx0RERJbkhhbCI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJuZXciO2E6MDp7fXM6Mzoib2xkIjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6NDg6Imh0dHA6Ly9sYXJhdmVsMTEtcGVuZ2Vsb2xhYm1uLnRlc3QvYmFyYW5nLWtlbHVhciI7fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjE7fQ==', 1771393469);

-- --------------------------------------------------------

--
-- Struktur dari tabel `settings`
--

CREATE TABLE `settings` (
  `id` bigint UNSIGNED NOT NULL,
  `sidebar_color` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'blue',
  `logo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `login_opening_text` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `settings`
--

INSERT INTO `settings` (`id`, `sidebar_color`, `logo`, `login_opening_text`, `created_at`, `updated_at`) VALUES
(1, '#000a29', 'logo_1770948929.png', 'Aplikasi ini digunakan untuk mengelola persediaan Barang Milik Negara, memfasilitasi proses permintaan barang secara digital, serta memantau distribusi stok secara real-time, akurat, dan transparan.', '2026-02-12 02:36:04', '2026-02-13 02:45:14');

-- --------------------------------------------------------

--
-- Struktur dari tabel `transaksi`
--

CREATE TABLE `transaksi` (
  `id` bigint UNSIGNED NOT NULL,
  `barang_id` bigint UNSIGNED NOT NULL,
  `tanggal` date NOT NULL,
  `jenis` enum('Masuk','Keluar') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `jumlah` int NOT NULL,
  `status` enum('Proses','Selesai') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `transaksi`
--

INSERT INTO `transaksi` (`id`, `barang_id`, `tanggal`, `jenis`, `jumlah`, `status`, `created_at`, `updated_at`) VALUES
(1, 1, '2024-01-15', 'Masuk', 5, 'Selesai', '2026-01-12 02:36:14', '2026-01-12 02:36:14'),
(2, 2, '2024-01-15', 'Keluar', 2, 'Proses', '2026-01-12 02:36:14', '2026-01-12 02:36:14'),
(3, 3, '2024-01-14', 'Masuk', 10, 'Selesai', '2026-01-12 02:36:14', '2026-01-12 02:36:14'),
(4, 4, '2024-01-14', 'Keluar', 3, 'Selesai', '2026-01-12 02:36:14', '2026-01-12 02:36:14'),
(5, 5, '2024-01-13', 'Masuk', 1, 'Selesai', '2026-01-12 02:36:14', '2026-01-12 02:36:14'),
(6, 6, '2024-01-12', 'Keluar', 1, 'Selesai', '2026-01-12 02:36:14', '2026-01-12 02:36:14'),
(7, 7, '2024-01-11', 'Masuk', 4, 'Selesai', '2026-01-12 02:36:14', '2026-01-12 02:36:14'),
(8, 8, '2024-01-10', 'Keluar', 2, 'Proses', '2026-01-12 02:36:14', '2026-01-12 02:36:14');

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `photo` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `seksi_id` bigint UNSIGNED DEFAULT NULL,
  `role` enum('Administrator','Staff') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Staff',
  `status` enum('Aktif','Tidak Aktif') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Aktif',
  `last_login_at` datetime DEFAULT NULL,
  `remember_token` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `photo`, `seksi_id`, `role`, `status`, `last_login_at`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Admin Utama', 'admin@imigrasi-ponorogo.go.id', '$2y$12$minBmJ/.TRH0.WCOS0sBgOvWEem.M6zJGHm8qzQVS./TkNE9hUcG.', 'profiles/YgmZ0bqAbL7lN7uqSwPSzzhZ7mVAErWe1TLL4yuc.png', 4, 'Administrator', 'Aktif', '2026-02-18 11:57:08', 'UdHqx3NbJZX45gG1fg8YxsfbdrQ2pnHHItf5bKGrqIBnFs37LXfCGjyAs26L', '2026-01-07 07:07:45', '2026-02-18 04:57:08'),
(2, 'Seksi Tikim', 'tikim01@gmail.com', '$2y$12$DEafOhFWSkBvS7nNq2STBe1RI4hUNfSU9SbeImONMWtACkPw1HwFG', 'profiles/yZZa6sGcWN8qvzPP2UCSnGFlBmsbYtiQdY1gQw6l.jpg', 1, 'Staff', 'Aktif', '2026-02-18 10:59:20', NULL, '2026-01-07 21:40:35', '2026-02-18 03:59:20'),
(3, 'Seksi Inteldakim', 'inteldakim01@gmail.com', '$2y$12$prHDyrqnbJyOC.P4VECJy.SiIEZZLrSKb.ByWYpAw9WTnvrLYWoq2', 'profiles/default-profile.png', 3, 'Staff', 'Aktif', '2026-01-28 10:03:09', NULL, '2026-01-13 19:01:23', '2026-02-04 02:15:23'),
(5, 'Seksi Doklalintalkim', 'doklalintalkim01@gmail.com', '$2y$12$TrIdpj30vNum3uBfYSKGOej.ouDGr7las9PHs8JCFcFwStlj3mNLS', 'profiles/default-profile.png', 2, 'Staff', 'Tidak Aktif', NULL, NULL, '2026-01-18 20:12:12', '2026-02-04 02:15:08'),
(6, 'Subbagian TU', 'tu01@gmail.com', '$2y$12$PXN5pjblbC7OBTvuN4hvueZhw2fi5vVRPISyfje0XbWPH0KRlqDg.', 'profiles/default-profile.png', 4, 'Staff', 'Tidak Aktif', NULL, NULL, '2026-01-18 20:13:19', '2026-02-04 02:15:37'),
(11, 'aaa', 'aaa@gmail.com', '$2y$12$U904DvcjtS0RczK9kHIeR.tZWbHaOZjQXgFOusUNE6cecFlHV4Vm.', 'profiles/8gfvEvsTPbZl7SBbmThX3pBIeRvYgYkkrV2rJ6Xv.jpg', 4, 'Staff', 'Tidak Aktif', NULL, NULL, '2026-01-19 23:11:01', '2026-02-12 02:52:56'),
(12, 'PPNPN', 'ppnpn@gmail.com', '$2y$12$8gVnCvGUJFGyMdCmeMp7D.g1s8ho9BBtWFzvIx1iyLH7ZN/5zX/0i', 'profiles/default-profile.png', 5, 'Staff', 'Aktif', '2026-01-28 10:56:58', NULL, '2026-01-21 20:03:57', '2026-02-04 02:14:54');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `barangs`
--
ALTER TABLE `barangs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `kode_barang` (`kode_barang`),
  ADD KEY `fk_barang_kategori` (`kategori_id`);

--
-- Indeks untuk tabel `barang_masuk`
--
ALTER TABLE `barang_masuk`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_barang_masuk_barang` (`barang_id`);

--
-- Indeks untuk tabel `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`);

--
-- Indeks untuk tabel `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`);

--
-- Indeks untuk tabel `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indeks untuk tabel `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indeks untuk tabel `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `kategori`
--
ALTER TABLE `kategori`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `lokasi`
--
ALTER TABLE `lokasi`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indeks untuk tabel `pengajuans`
--
ALTER TABLE `pengajuans`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pengajuans_barang_id_foreign` (`barang_id`),
  ADD KEY `fk_pengajuans_users` (`user_id`);

--
-- Indeks untuk tabel `seksi`
--
ALTER TABLE `seksi`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indeks untuk tabel `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `transaksi`
--
ALTER TABLE `transaksi`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_transaksi_barang` (`barang_id`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `seksi_id` (`seksi_id`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `barangs`
--
ALTER TABLE `barangs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT untuk tabel `barang_masuk`
--
ALTER TABLE `barang_masuk`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT untuk tabel `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `kategori`
--
ALTER TABLE `kategori`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT untuk tabel `lokasi`
--
ALTER TABLE `lokasi`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT untuk tabel `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT untuk tabel `pengajuans`
--
ALTER TABLE `pengajuans`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT untuk tabel `seksi`
--
ALTER TABLE `seksi`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT untuk tabel `settings`
--
ALTER TABLE `settings`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `transaksi`
--
ALTER TABLE `transaksi`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `barangs`
--
ALTER TABLE `barangs`
  ADD CONSTRAINT `fk_barang_kategori` FOREIGN KEY (`kategori_id`) REFERENCES `kategori` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `barang_masuk`
--
ALTER TABLE `barang_masuk`
  ADD CONSTRAINT `fk_barang_masuk_barang` FOREIGN KEY (`barang_id`) REFERENCES `barangs` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `pengajuans`
--
ALTER TABLE `pengajuans`
  ADD CONSTRAINT `fk_pengajuans_users` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `pengajuans_barang_id_foreign` FOREIGN KEY (`barang_id`) REFERENCES `barangs` (`id`);

--
-- Ketidakleluasaan untuk tabel `transaksi`
--
ALTER TABLE `transaksi`
  ADD CONSTRAINT `fk_transaksi_barang` FOREIGN KEY (`barang_id`) REFERENCES `barangs` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `fk_users_seksi` FOREIGN KEY (`seksi_id`) REFERENCES `seksi` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

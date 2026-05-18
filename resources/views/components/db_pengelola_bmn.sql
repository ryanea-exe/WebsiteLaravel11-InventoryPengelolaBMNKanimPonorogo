-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Waktu pembuatan: 18 Bulan Mei 2026 pada 01.38
-- Versi server: 8.0.44
-- Versi PHP: 8.3.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Basis data: `db_pengelola_bmn`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `anggota_kelompok`
--

CREATE TABLE `anggota_kelompok` (
  `id` bigint UNSIGNED NOT NULL,
  `nama` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `anggota_kelompok`
--

INSERT INTO `anggota_kelompok` (`id`, `nama`) VALUES
(1, 'Renaldi'),
(2, 'Ryan'),
(3, 'Rama'),
(4, 'Martina'),
(5, 'Abdul'),
(6, 'Zuni');

-- --------------------------------------------------------

--
-- Struktur dari tabel `barangs`
--

CREATE TABLE `barangs` (
  `id` bigint UNSIGNED NOT NULL,
  `kode_barang` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `nama_barang` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
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
(1, 'BRG-001', 'Bolpoin Snowman V2', 4, 7, 'Pcs', '2026-01-08 06:48:09', '2026-04-08 06:38:01'),
(2, 'BRG-002', 'Tipe X', 4, 15, 'Pcs', '2026-01-08 06:48:09', '2026-04-06 01:11:59'),
(3, 'BRG-003', 'Binder Clip', 4, 21, 'Lusin', '2026-01-08 06:48:09', '2026-04-06 01:12:10'),
(4, 'BRG-004', 'Map Pelayanan WNI', 4, 34, 'Pcs', '2026-01-08 06:48:09', '2026-04-06 02:59:06'),
(5, 'BRG-005', 'Gunting Gunindo', 5, 6, 'Pcs', '2026-01-08 06:48:09', '2026-04-06 08:02:12'),
(6, 'BRG-006', 'Staples Joyko', 5, 4, 'Pcs', '2026-01-08 06:48:09', '2026-04-06 01:14:17'),
(7, 'BRG-007', 'Kertas HVS A4', 4, 14, 'Rim', '2026-01-08 06:48:09', '2026-04-18 13:19:46'),
(8, 'BRG-008', 'Amplop 310', 5, 13, 'Pack', '2026-01-08 06:48:09', '2026-04-06 01:15:11'),
(10, 'BRG-010', 'Tinta Printer Paspor P4000 Yellow', 5, 1, 'Pcs', '2026-01-08 06:48:09', '2026-04-06 01:16:17'),
(14, 'BRG-011', 'Sticky Note Big', 4, 2, 'Pcs', '2026-02-18 03:02:34', '2026-04-06 01:16:50'),
(15, 'BRG-012', 'Datacard D3000 S11-097 Black', 5, 10, 'Buah', '2026-02-18 03:17:35', '2026-04-06 01:17:35'),
(18, 'BRG-009', 'Bolpoin Baliner Hitam', 4, 0, 'Pcs', '2026-02-21 04:26:50', '2026-04-06 01:15:35'),
(19, 'BRG-013', 'Tikar Swan', 5, 9, 'Pcs', '2026-02-24 01:35:47', '2026-04-06 08:07:02'),
(20, 'BRG-014', 'Pensil 2B', 4, 11, 'Pcs', '2026-03-10 05:16:19', '2026-04-06 01:18:19'),
(21, 'BRG-015', 'Meja Kerja Kayu', 2, 5, 'Unit', '2026-04-06 02:19:25', '2026-04-06 02:19:25'),
(22, 'BRG-016', 'Sapu Lidi', 5, 16, 'Pcs', '2026-04-06 06:14:31', '2026-04-06 07:10:40'),
(23, 'BRG-017', 'Tissue Wajah Indomaret', 5, 10, 'Pack', '2026-04-06 07:11:34', '2026-04-06 07:12:04');

-- --------------------------------------------------------

--
-- Struktur dari tabel `barang_bmn`
--

CREATE TABLE `barang_bmn` (
  `id` bigint UNSIGNED NOT NULL,
  `kode_barang` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `nama_barang` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `kategori_id` bigint UNSIGNED NOT NULL,
  `merk_type` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `jumlah` int NOT NULL DEFAULT '0',
  `satuan` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `barang_bmn`
--

INSERT INTO `barang_bmn` (`id`, `kode_barang`, `nama_barang`, `kategori_id`, `merk_type`, `jumlah`, `satuan`, `created_at`, `updated_at`) VALUES
(1, 'BMN-001', 'Laptop', 1, 'HP Omnibook 7 Aero 13-1667au', 2, 'Unit', '2026-03-27 08:02:23', '2026-04-08 06:47:37'),
(2, 'BMN-002', 'Handphone', 1, 'iPhone 16 Pro Max 512 GB', 2, 'Unit', '2026-03-27 08:07:17', '2026-04-08 06:47:37'),
(3, 'BMN-003', 'Motor', 3, 'CRF 150L 2025', 8, 'Unit', '2026-04-02 01:32:55', '2026-04-05 12:27:50'),
(4, 'BMN-004', 'Mobil', 3, 'Toyota Veloz 1.5L Type V', 1, 'Unit', '2026-04-05 12:28:39', '2026-04-05 12:28:39'),
(5, 'BMN-005', 'Google TV', 1, 'Xiaomi 43 A Pro', 4, 'Unit', '2026-04-06 01:19:29', '2026-04-06 07:22:46'),
(6, 'BMN-006', 'Camera', 1, 'Canon 100D', 3, 'Unit', '2026-04-08 07:07:28', '2026-04-08 07:07:28');

-- --------------------------------------------------------

--
-- Struktur dari tabel `barang_masuk`
--

CREATE TABLE `barang_masuk` (
  `id` bigint UNSIGNED NOT NULL,
  `kode_transaksi` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tanggal` datetime NOT NULL,
  `keterangan` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `barang_masuk`
--

INSERT INTO `barang_masuk` (`id`, `kode_transaksi`, `tanggal`, `keterangan`, `created_at`, `updated_at`) VALUES
(3, NULL, '2026-01-13 00:00:00', NULL, '2026-01-12 23:40:44', '2026-01-12 23:40:44'),
(4, NULL, '2026-01-14 00:00:00', NULL, '2026-01-13 22:53:41', '2026-01-13 22:53:41'),
(5, NULL, '2026-01-23 00:00:00', NULL, '2026-01-23 00:52:59', '2026-01-23 00:52:59'),
(7, NULL, '2026-02-11 00:00:00', 'as', '2026-02-11 07:56:37', '2026-02-11 07:56:37'),
(8, NULL, '2026-02-11 00:00:00', 'jhjhj', '2026-02-11 08:05:41', '2026-02-11 08:05:41'),
(9, NULL, '2026-02-11 00:00:00', 'a', '2026-02-11 08:07:10', '2026-02-11 08:07:10'),
(10, NULL, '2026-02-13 00:00:00', 'p', '2026-02-13 03:10:15', '2026-02-13 03:10:15'),
(13, NULL, '2026-02-13 00:00:00', 'w', '2026-02-13 06:44:08', '2026-02-13 06:44:08'),
(16, NULL, '2026-02-27 00:00:00', NULL, '2026-02-27 01:29:13', '2026-02-27 01:29:13'),
(18, NULL, '2026-03-09 00:00:00', NULL, '2026-03-09 03:36:39', '2026-03-09 03:36:39'),
(19, NULL, '2026-03-11 00:00:00', 'h', '2026-03-11 07:54:10', '2026-03-11 07:54:10'),
(20, NULL, '2026-03-12 08:04:22', NULL, '2026-03-12 01:04:22', '2026-03-12 01:04:22'),
(21, NULL, '2026-03-17 16:48:03', 'sdffsdfsd', '2026-03-17 09:48:03', '2026-03-17 09:48:03'),
(22, NULL, '2026-03-17 09:59:06', 'hhaslfhlskf', '2026-03-17 09:49:09', '2026-04-06 02:59:06'),
(23, 'TRM-20260318-0001', '2026-03-18 09:56:47', 'sfgsofhpiehf', '2026-03-18 02:56:47', '2026-03-18 02:56:47'),
(24, 'TRM-20260406-0001', '2026-04-06 13:12:27', NULL, '2026-04-06 06:12:27', '2026-04-06 06:12:27'),
(25, 'TRM-20260406-0002', '2026-04-06 14:12:04', NULL, '2026-04-06 07:12:04', '2026-04-06 07:12:04'),
(26, 'TRM-20260406-0003', '2026-04-06 15:02:12', NULL, '2026-04-06 08:02:12', '2026-04-06 08:02:12'),
(27, 'TRM-20260418-0001', '2026-04-16 20:19:46', NULL, '2026-04-18 13:05:40', '2026-04-18 13:19:46');

-- --------------------------------------------------------

--
-- Struktur dari tabel `barang_masuk_details`
--

CREATE TABLE `barang_masuk_details` (
  `id` bigint UNSIGNED NOT NULL,
  `barang_masuk_id` bigint UNSIGNED NOT NULL,
  `barang_id` bigint UNSIGNED NOT NULL,
  `jumlah` int NOT NULL,
  `harga_satuan` decimal(15,2) DEFAULT NULL,
  `harga_total` decimal(15,2) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `barang_masuk_details`
--

INSERT INTO `barang_masuk_details` (`id`, `barang_masuk_id`, `barang_id`, `jumlah`, `harga_satuan`, `harga_total`, `created_at`, `updated_at`) VALUES
(1, 21, 10, 1, 0.00, 0.00, '2026-03-17 09:48:03', '2026-03-17 09:48:03'),
(2, 21, 6, 1, 0.00, 0.00, '2026-03-17 09:48:03', '2026-03-17 09:48:03'),
(5, 3, 1, 5, 10000000.00, 50000000.00, '2026-01-12 23:40:44', '2026-01-12 23:40:44'),
(6, 4, 2, 2, 5000000.00, 10000000.00, '2026-01-13 22:53:41', '2026-01-13 22:53:41'),
(7, 5, 5, 5, 4000000.00, 20000000.00, '2026-01-23 00:52:59', '2026-01-23 00:52:59'),
(8, 7, 1, 1, 50000000.00, 50000000.00, '2026-02-11 07:56:37', '2026-02-11 07:56:37'),
(9, 8, 2, 4, 10000000.00, 40000000.00, '2026-02-11 08:05:41', '2026-02-11 08:05:41'),
(10, 9, 2, 2, 10000000.00, 20000000.00, '2026-02-11 08:07:10', '2026-02-11 08:07:10'),
(11, 10, 4, 2, 500000.00, 1000000.00, '2026-02-13 03:10:15', '2026-02-13 03:10:15'),
(12, 13, 2, 2, 250000.00, 500000.00, '2026-02-13 06:44:08', '2026-02-13 06:44:08'),
(13, 16, 1, 5, 5000000.00, 25000000.00, '2026-02-27 01:29:13', '2026-02-27 01:29:13'),
(14, 18, 6, 5, NULL, NULL, '2026-03-09 03:36:39', '2026-03-09 03:36:39'),
(15, 19, 2, 1, 5000000.00, 5000000.00, '2026-03-11 07:54:10', '2026-03-11 07:54:10'),
(16, 20, 7, 5, 10000000.00, 50000000.00, '2026-03-12 01:04:22', '2026-03-12 01:04:22'),
(17, 23, 19, 10, 10000.00, 100000.00, '2026-03-18 02:56:47', '2026-03-18 02:56:47'),
(26, 22, 1, 1, 1000000.00, 1000000.00, '2026-04-06 02:59:06', '2026-04-06 02:59:06'),
(27, 22, 4, 10, 1000.00, 10000.00, '2026-04-06 02:59:06', '2026-04-06 02:59:06'),
(28, 24, 1, 5, 0.00, 0.00, '2026-04-06 06:12:27', '2026-04-06 06:12:27'),
(29, 25, 23, 10, 10000.00, 100000.00, '2026-04-06 07:12:04', '2026-04-06 07:12:04'),
(30, 26, 5, 1, 0.00, 0.00, '2026-04-06 08:02:12', '2026-04-06 08:02:12'),
(32, 27, 7, 10, 0.00, 0.00, '2026-04-18 13:19:46', '2026-04-18 13:19:46');

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
-- Struktur dari tabel `catatan`
--

CREATE TABLE `catatan` (
  `id` bigint UNSIGNED NOT NULL,
  `tanggal` datetime NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `catatan` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `catatan`
--

INSERT INTO `catatan` (`id`, `tanggal`, `user_id`, `catatan`, `created_at`, `updated_at`) VALUES
(1, '2026-03-31 09:08:21', 2, 'minta kamera 5 merk sony', '2026-03-31 02:08:39', '2026-03-31 02:08:39'),
(2, '2026-03-31 14:03:26', 2, 'minta hp iphone 17 promax', '2026-03-31 07:03:40', '2026-03-31 07:03:40'),
(3, '2026-04-06 13:08:42', 2, 'Seksi Tikim membutuhkan kamera baru', '2026-04-06 06:09:05', '2026-04-06 06:09:05'),
(4, '2026-04-06 15:12:26', 2, 'A', '2026-04-06 08:13:01', '2026-04-06 08:13:01');

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
(4, 'Alat Tulis', '2026-01-09 01:37:37', '2026-02-21 05:22:15'),
(5, 'Peralatan', '2026-04-06 01:13:16', '2026-04-06 01:13:16');

-- --------------------------------------------------------

--
-- Struktur dari tabel `kendaraan`
--

CREATE TABLE `kendaraan` (
  `id` bigint UNSIGNED NOT NULL,
  `nomor_polisi` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `jenis_kendaraan` enum('Motor','Mobil') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `nama_kendaraan` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `tahun` int NOT NULL,
  `seksi_id` bigint UNSIGNED DEFAULT NULL,
  `tanggal_pajak_berkala` date DEFAULT NULL,
  `rentang_waktu_servis` int DEFAULT NULL,
  `keterangan` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `kendaraan`
--

INSERT INTO `kendaraan` (`id`, `nomor_polisi`, `jenis_kendaraan`, `nama_kendaraan`, `tahun`, `seksi_id`, `tanggal_pajak_berkala`, `rentang_waktu_servis`, `keterangan`, `created_at`, `updated_at`) VALUES
(1, 'AE 1111 TP', 'Mobil', 'Toyota Veloz 1.5 Hitam', 2025, 1, '2026-04-09', 5, NULL, '2026-04-09 00:43:32', '2026-04-17 09:13:11'),
(2, 'AE 5555 SP', 'Motor', 'Honda Supra 125 Hitam', 2020, 5, '2026-03-19', 6, NULL, '2026-04-09 00:44:29', '2026-04-17 03:02:40'),
(3, 'AE 2018 TP', 'Motor', 'Honda CRF 150L Hitam', 2026, 2, '2026-04-24', 2, NULL, '2026-04-09 01:34:30', '2026-04-17 02:43:26'),
(4, 'B 1922 SQH', 'Mobil', 'Isuzu D-Max 2.4 Hitam', 2024, 1, '2026-04-13', 6, NULL, '2026-04-10 06:25:36', '2026-04-17 09:12:57'),
(5, 'B 1922 SH', 'Motor', 'Yamaha NMax Turbo Hitam', 2026, 2, '2026-04-16', 3, NULL, '2026-04-10 07:23:57', '2026-04-24 03:29:29'),
(6, 'AE 5556 SP', 'Motor', 'Kawasaki KLX 150L Putih', 2024, 3, '2026-04-17', 6, NULL, '2026-04-17 08:18:27', '2026-04-17 08:20:33'),
(7, 'AE 2018 SP', 'Motor', 'Honda Vario 150 Hitam', 2026, 3, '2026-04-22', 6, NULL, '2026-04-22 07:44:32', '2026-04-22 07:44:32'),
(9, 'AE 2019 UP', 'Mobil', 'Isuzu ELF NMR 71 5.8 Putih', 2025, 2, '2025-05-01', NULL, NULL, '2026-04-24 03:16:13', '2026-04-24 03:16:13');

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
  `migration` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2026_01_13_033907_create_pengajuans_table', 1),
(2, '2014_10_12_000000_create_users_table', 1),
(3, '2023_11_01_101010_create_users_table', 1),
(4, '2026_02_02_135550_add_user_id_to_pengajuans_table', 2),
(5, '2026_02_02_135640_add_read_at_to_pengajuans_table', 3),
(6, '2026_02_03_101529_add_status_to_pengajuan_details', 4),
(7, '2026_02_11_123856_add_approved_at_to_pengajuan_details_table', 5);

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
  `kode_pengajuan` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `keperluan` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `tanggal_pengajuan` datetime NOT NULL,
  `status` enum('Diajukan','Disetujui Sebagian','Disetujui','Ditolak') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Diajukan',
  `tanggal_proses` datetime DEFAULT NULL,
  `keterangan_proses` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `read_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `pengajuans`
--

INSERT INTO `pengajuans` (`id`, `kode_pengajuan`, `user_id`, `keperluan`, `tanggal_pengajuan`, `status`, `tanggal_proses`, `keterangan_proses`, `read_at`, `created_at`, `updated_at`) VALUES
(89, 'TRK-20260220-0001', 2, 'bmznxbcmnzx', '2026-02-20 00:00:00', 'Disetujui Sebagian', '2026-02-22 00:00:00', NULL, '2026-02-25 02:55:45', '2026-02-20 04:39:46', '2026-02-25 02:55:45'),
(90, 'TRK-20260222-0001', 2, 'penting', '2026-02-22 00:00:00', 'Disetujui', '2026-02-22 00:00:00', NULL, '2026-02-25 02:55:45', '2026-02-22 05:20:41', '2026-02-25 02:55:45'),
(91, 'TRK-20260222-0002', 2, 'tes', '2026-02-22 00:00:00', 'Ditolak', '2026-02-22 00:00:00', NULL, '2026-02-25 02:55:45', '2026-02-22 05:22:54', '2026-02-25 02:55:45'),
(92, 'TRK-20260222-0003', 2, 'aaa', '2026-02-22 00:00:00', 'Disetujui', '2026-02-22 00:00:00', NULL, '2026-02-25 02:55:45', '2026-02-22 05:24:17', '2026-02-25 02:55:45'),
(93, 'TRK-20260222-0004', 2, 'a', '2026-02-22 00:00:00', 'Ditolak', '2026-02-22 00:00:00', NULL, '2026-02-25 02:55:45', '2026-02-22 05:25:18', '2026-02-25 02:55:45'),
(94, 'TRK-20260222-0005', 2, 'aaaa', '2026-02-22 00:00:00', 'Ditolak', '2026-02-22 00:00:00', NULL, '2026-02-25 02:55:45', '2026-02-22 05:27:16', '2026-02-25 02:55:45'),
(95, 'TRK-20260222-0006', 2, 'asasfas', '2026-02-22 00:00:00', 'Ditolak', '2026-02-22 00:00:00', NULL, '2026-02-25 02:55:45', '2026-02-22 06:13:26', '2026-02-25 02:55:45'),
(96, 'TRK-20260226-0001', 2, 'Untuk pelaksanaan tusi', '2026-02-26 00:00:00', 'Disetujui', '2026-02-26 00:00:00', NULL, '2026-03-07 00:55:34', '2026-02-26 02:58:06', '2026-03-07 00:55:34'),
(100, 'TRK-20260307-0001', 2, 'asdsadsa', '2026-03-07 00:00:00', 'Ditolak', '2026-03-11 00:00:00', 'barang habis bos', NULL, '2026-03-07 02:34:32', '2026-03-11 03:01:12'),
(101, 'TRK-20260307-0002', 3, 'sdfsdfsdfsdfsf', '2026-03-07 00:00:00', 'Disetujui', '2026-03-07 00:00:00', NULL, '2026-03-07 04:08:23', '2026-03-07 02:41:25', '2026-03-07 04:08:23'),
(102, 'TRK-20260307-0003', 3, 'anu', '2026-03-07 00:00:00', 'Disetujui', '2026-03-09 00:00:00', NULL, '2026-03-09 02:38:13', '2026-03-07 04:08:42', '2026-03-09 02:38:13'),
(105, 'TRK-20260309-0002', 6, 'hjhhpiho', '2026-03-09 00:00:00', 'Disetujui', '2026-03-10 00:00:00', 'sdasdasdsadsad', NULL, '2026-03-09 04:05:44', '2026-03-10 03:21:15'),
(106, 'TRK-20260309-0003', 3, 'hougoyu', '2026-03-09 00:00:00', 'Ditolak', '2026-03-11 00:00:00', NULL, NULL, '2026-03-09 04:13:34', '2026-03-11 02:58:11'),
(107, 'TRK-20260310-0001', 5, 'adasdjteyj', '2026-03-10 00:00:00', 'Disetujui', '2026-03-10 00:00:00', 'stok terbatas bos, ojok okeh okeh njalukmu', '2026-03-11 01:44:08', '2026-03-10 04:45:43', '2026-03-11 01:44:08'),
(109, 'TRK-20260310-0002', 5, 'dhashd;as', '2026-03-10 00:00:00', 'Ditolak', '2026-03-11 00:00:00', NULL, NULL, '2026-03-10 04:46:49', '2026-03-11 03:00:19'),
(116, 'TRK-20260310-0003', 5, 'fkoogids', '2026-03-10 00:00:00', 'Disetujui', '2026-03-11 00:00:00', '1 ae bos ra usah akeh akeh', NULL, '2026-03-10 05:02:24', '2026-03-11 02:39:26'),
(118, 'TRK-20260310-0004', 5, 'askjdhaskjd', '2026-03-10 00:00:00', 'Ditolak', '2026-03-11 00:00:00', NULL, '2026-03-11 01:44:08', '2026-03-10 05:03:05', '2026-03-11 01:44:08'),
(120, 'TRK-20260310-0005', 2, 'dfgfsdgfsdg', '2026-03-10 00:00:00', 'Ditolak', '2026-03-11 00:00:00', 'asdasdasd', '2026-04-01 02:38:09', '2026-03-10 05:04:21', '2026-04-01 02:38:09'),
(121, 'TRK-20260310-0006', 2, 'asdasdasdasd', '2026-03-10 00:00:00', 'Disetujui', '2026-03-11 00:00:00', 'fsdfsdfsdfr', '2026-04-06 08:34:07', '2026-03-10 05:04:53', '2026-04-06 08:34:07'),
(122, 'TRK-20260310-0007', 2, 'sidgsa', '2026-03-10 00:00:00', 'Disetujui', '2026-03-11 00:00:00', 'sjdhasjhdgskadgqowiye', '2026-03-11 01:54:08', '2026-03-10 05:07:14', '2026-03-11 01:54:08'),
(123, 'TRK-20260310-0008', 2, '23refsd', '2026-03-10 00:00:00', 'Disetujui Sebagian', '2026-03-11 00:00:00', NULL, '2026-03-11 01:21:22', '2026-03-10 05:10:18', '2026-03-11 01:21:22'),
(124, 'TRK-20260311-0001', 3, 'sdfweh', '2026-03-11 14:42:43', 'Ditolak', '2026-03-31 09:48:51', 'sek', NULL, '2026-03-11 07:42:43', '2026-03-31 02:48:51'),
(125, 'TRK-20260325-0001', 2, 'ydtuyifyfi', '2026-03-25 13:47:25', 'Disetujui Sebagian', '2026-03-31 09:58:51', 'ashidgusaod', NULL, '2026-03-25 06:47:25', '2026-03-31 02:58:51'),
(126, 'TRK-20260325-0002', 2, 'ydtuyifyfi', '2026-03-25 13:47:26', 'Diajukan', NULL, NULL, NULL, '2026-03-25 06:47:26', '2026-03-25 06:47:26'),
(134, 'TRK-20260404-0001', 12, 'jfhlkhf;kshf;s', '2026-04-04 08:34:50', 'Disetujui', '2026-04-06 15:07:02', NULL, NULL, '2026-04-04 01:34:50', '2026-04-06 08:07:02'),
(135, 'TRK-20260406-0001', 2, 'untuk menulis dimap', '2026-04-06 12:54:46', 'Disetujui', '2026-04-06 13:04:12', 'disetujui  2 karena stok menipis', '2026-04-19 13:25:51', '2026-04-06 05:54:46', '2026-04-19 13:25:51'),
(136, 'TRK-20260406-0002', 2, 'untuk tusi', '2026-04-06 15:11:25', 'Diajukan', NULL, NULL, '2026-04-19 13:25:42', '2026-04-06 08:11:25', '2026-04-19 13:25:42'),
(137, 'TRK-20260408-0001', 2, 'untuk mendukung tusi', '2026-04-08 13:33:51', 'Disetujui', '2026-04-08 13:38:01', NULL, '2026-04-19 13:25:08', '2026-04-08 06:33:51', '2026-04-19 13:25:08');

-- --------------------------------------------------------

--
-- Struktur dari tabel `pengajuan_bmn`
--

CREATE TABLE `pengajuan_bmn` (
  `id` bigint UNSIGNED NOT NULL,
  `kode_pengajuan` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `keperluan` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `tanggal_pengajuan` datetime NOT NULL,
  `status` enum('Diajukan','Disetujui Sebagian','Disetujui','Ditolak') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Diajukan',
  `tanggal_proses` datetime DEFAULT NULL,
  `keterangan_proses` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `read_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `pengajuan_bmn`
--

INSERT INTO `pengajuan_bmn` (`id`, `kode_pengajuan`, `user_id`, `keperluan`, `tanggal_pengajuan`, `status`, `tanggal_proses`, `keterangan_proses`, `read_at`, `created_at`, `updated_at`) VALUES
(2, 'TRK-20260331-0001', 2, 'bchfxzchasndkas', '2026-03-31 14:08:55', 'Disetujui', '2026-03-31 14:34:24', 'asbdhasd', NULL, '2026-03-31 07:08:55', '2026-03-31 07:34:24'),
(3, 'TRK-20260401-0001', 2, 'dibuat ngevideo', '2026-04-01 08:53:10', 'Diajukan', NULL, NULL, NULL, '2026-04-01 01:53:10', '2026-04-01 01:53:10'),
(4, 'TRK-20260406-0001', 2, 'untuk mengganti laptop yang rusak', '2026-04-06 12:56:00', 'Disetujui', '2026-04-06 13:07:30', NULL, NULL, '2026-04-06 05:56:00', '2026-04-06 06:07:30'),
(5, 'TRK-20260406-0002', 2, 'untuk tusi atau take video', '2026-04-06 15:37:49', 'Disetujui', '2026-04-06 15:38:36', NULL, '2026-04-19 13:29:45', '2026-04-06 08:37:49', '2026-04-19 13:29:45'),
(6, 'TRK-20260408-0001', 2, 'untuk dukungan tusi', '2026-04-08 13:46:55', 'Disetujui', '2026-04-08 13:47:37', NULL, '2026-04-17 01:24:12', '2026-04-08 06:46:55', '2026-04-17 01:24:12'),
(7, 'TRK-20260429-0001', 15, 'untuk tusi', '2026-04-29 10:44:58', 'Diajukan', NULL, NULL, '2026-04-29 03:45:17', '2026-04-29 03:44:58', '2026-04-29 03:45:17');

-- --------------------------------------------------------

--
-- Struktur dari tabel `pengajuan_bmn_detail`
--

CREATE TABLE `pengajuan_bmn_detail` (
  `id` bigint UNSIGNED NOT NULL,
  `pengajuan_id` bigint UNSIGNED NOT NULL,
  `barang_id` bigint UNSIGNED NOT NULL,
  `jumlah` int NOT NULL,
  `jumlah_disetujui` int DEFAULT NULL,
  `status` enum('Diajukan','Disetujui','Ditolak') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `pengajuan_bmn_detail`
--

INSERT INTO `pengajuan_bmn_detail` (`id`, `pengajuan_id`, `barang_id`, `jumlah`, `jumlah_disetujui`, `status`, `created_at`, `updated_at`) VALUES
(4, 2, 1, 1, 1, 'Disetujui', '2026-03-31 07:08:55', '2026-03-31 07:34:24'),
(5, 2, 2, 2, 1, 'Disetujui', '2026-03-31 07:08:55', '2026-03-31 07:34:24'),
(6, 3, 2, 1, NULL, 'Diajukan', '2026-04-01 01:53:10', '2026-04-01 01:53:10'),
(7, 4, 1, 1, 1, 'Disetujui', '2026-04-06 05:56:00', '2026-04-06 06:07:30'),
(8, 5, 2, 1, 1, 'Disetujui', '2026-04-06 08:37:49', '2026-04-06 08:38:35'),
(9, 6, 2, 1, 1, 'Disetujui', '2026-04-08 06:46:55', '2026-04-08 06:47:37'),
(10, 6, 1, 1, 1, 'Disetujui', '2026-04-08 06:46:55', '2026-04-08 06:47:37'),
(11, 7, 2, 1, NULL, 'Diajukan', '2026-04-29 03:44:58', '2026-04-29 03:44:58'),
(12, 7, 1, 1, NULL, 'Diajukan', '2026-04-29 03:44:58', '2026-04-29 03:44:58');

-- --------------------------------------------------------

--
-- Struktur dari tabel `pengajuan_details`
--

CREATE TABLE `pengajuan_details` (
  `id` bigint UNSIGNED NOT NULL,
  `pengajuan_id` bigint UNSIGNED NOT NULL,
  `barang_id` bigint UNSIGNED NOT NULL,
  `jumlah` int NOT NULL,
  `jumlah_disetujui` int DEFAULT NULL,
  `status` enum('Diajukan','Disetujui','Ditolak') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Diajukan',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `pengajuan_details`
--

INSERT INTO `pengajuan_details` (`id`, `pengajuan_id`, `barang_id`, `jumlah`, `jumlah_disetujui`, `status`, `created_at`, `updated_at`) VALUES
(185, 89, 3, 1, 1, 'Disetujui', '2026-02-20 04:39:46', '2026-02-22 05:53:22'),
(186, 89, 8, 1, NULL, 'Ditolak', '2026-02-20 04:39:46', '2026-02-22 05:53:22'),
(187, 90, 5, 1, 1, 'Disetujui', '2026-02-22 05:20:41', '2026-02-22 06:10:48'),
(188, 90, 8, 1, 1, 'Disetujui', '2026-02-22 05:20:41', '2026-02-22 06:10:48'),
(189, 90, 4, 4, 4, 'Disetujui', '2026-02-22 05:20:41', '2026-02-22 06:10:48'),
(190, 91, 7, 1, NULL, 'Ditolak', '2026-02-22 05:22:54', '2026-02-22 06:21:21'),
(191, 91, 8, 1, NULL, 'Ditolak', '2026-02-22 05:22:54', '2026-02-22 06:21:21'),
(192, 91, 4, 1, NULL, 'Ditolak', '2026-02-22 05:22:54', '2026-02-22 06:21:21'),
(193, 92, 7, 1, 1, 'Disetujui', '2026-02-22 05:24:17', '2026-02-22 05:37:31'),
(194, 93, 7, 1, NULL, 'Ditolak', '2026-02-22 05:25:18', '2026-02-22 06:03:36'),
(195, 94, 3, 1, NULL, 'Ditolak', '2026-02-22 05:27:16', '2026-02-22 06:01:21'),
(196, 95, 4, 1, NULL, 'Ditolak', '2026-02-22 06:13:26', '2026-02-22 06:14:00'),
(197, 96, 7, 1, 1, 'Disetujui', '2026-02-26 02:58:06', '2026-02-26 03:12:09'),
(198, 96, 1, 1, 1, 'Disetujui', '2026-02-26 02:58:06', '2026-02-26 03:12:09'),
(199, 100, 10, 1, 0, 'Ditolak', '2026-03-07 02:34:32', '2026-03-11 03:01:12'),
(200, 101, 10, 1, 1, 'Disetujui', '2026-03-07 02:41:25', '2026-03-07 02:42:42'),
(201, 102, 3, 1, 1, 'Disetujui', '2026-03-07 04:08:42', '2026-03-09 02:14:16'),
(202, 102, 2, 1, 1, 'Disetujui', '2026-03-07 04:08:42', '2026-03-09 02:14:16'),
(205, 105, 6, 2, 2, 'Disetujui', '2026-03-09 04:05:44', '2026-03-10 03:21:15'),
(206, 106, 8, 1, 0, 'Ditolak', '2026-03-09 04:13:34', '2026-03-11 02:58:11'),
(207, 107, 4, 5, 3, 'Disetujui', '2026-03-10 04:45:43', '2026-03-10 05:11:57'),
(208, 107, 3, 5, 1, 'Disetujui', '2026-03-10 04:45:43', '2026-03-10 05:11:57'),
(209, 109, 4, 3, 0, 'Ditolak', '2026-03-10 04:46:49', '2026-03-11 03:00:19'),
(210, 116, 1, 5, 1, 'Disetujui', '2026-03-10 05:02:24', '2026-03-11 02:39:26'),
(211, 118, 1, 5, 0, 'Ditolak', '2026-03-10 05:03:05', '2026-03-11 01:12:57'),
(212, 120, 6, 1, 0, 'Ditolak', '2026-03-10 05:04:21', '2026-03-11 02:34:46'),
(213, 121, 4, 1, 1, 'Disetujui', '2026-03-10 05:04:53', '2026-03-11 02:55:04'),
(214, 122, 4, 1, 1, 'Disetujui', '2026-03-10 05:07:14', '2026-03-11 01:53:13'),
(215, 123, 3, 1, 1, 'Disetujui', '2026-03-10 05:10:18', '2026-03-11 01:10:22'),
(216, 123, 1, 1, 0, 'Ditolak', '2026-03-10 05:10:18', '2026-03-11 01:10:22'),
(217, 124, 2, 1, 0, 'Ditolak', '2026-03-11 07:42:43', '2026-03-31 02:48:51'),
(218, 124, 15, 1, 0, 'Ditolak', '2026-03-11 07:42:43', '2026-03-31 02:48:51'),
(219, 125, 20, 2, 1, 'Disetujui', '2026-03-25 06:47:25', '2026-03-31 02:58:51'),
(220, 125, 19, 1, 0, 'Ditolak', '2026-03-25 06:47:26', '2026-03-31 02:58:51'),
(221, 126, 20, 2, NULL, 'Diajukan', '2026-03-25 06:47:26', '2026-03-25 06:47:26'),
(222, 126, 19, 1, NULL, 'Diajukan', '2026-03-25 06:47:26', '2026-03-25 06:47:26'),
(223, 134, 19, 5, 2, 'Disetujui', '2026-04-04 01:34:50', '2026-04-06 08:07:02'),
(224, 135, 1, 5, 2, 'Disetujui', '2026-04-06 05:54:46', '2026-04-06 06:04:12'),
(225, 136, 2, 1, NULL, 'Diajukan', '2026-04-06 08:11:25', '2026-04-06 08:11:25'),
(226, 136, 4, 1, NULL, 'Diajukan', '2026-04-06 08:11:25', '2026-04-06 08:11:25'),
(227, 137, 1, 1, 1, 'Disetujui', '2026-04-08 06:33:52', '2026-04-08 06:38:01'),
(228, 137, 7, 1, 1, 'Disetujui', '2026-04-08 06:33:52', '2026-04-08 06:38:01');

-- --------------------------------------------------------

--
-- Struktur dari tabel `pengajuan_pemeliharaan`
--

CREATE TABLE `pengajuan_pemeliharaan` (
  `id` bigint UNSIGNED NOT NULL,
  `kode_pengajuan` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tanggal_pengajuan` datetime NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `kendaraan_id` bigint UNSIGNED NOT NULL,
  `status` enum('Diajukan','Disetujui','Ditolak') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Diajukan',
  `tanggal_proses` datetime DEFAULT NULL,
  `read_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `pengajuan_pemeliharaan`
--

INSERT INTO `pengajuan_pemeliharaan` (`id`, `kode_pengajuan`, `tanggal_pengajuan`, `user_id`, `kendaraan_id`, `status`, `tanggal_proses`, `read_at`, `created_at`, `updated_at`) VALUES
(2, 'PM-20260419-01', '2026-04-19 11:58:00', 2, 1, 'Disetujui', '2026-04-20 09:10:06', NULL, '2026-04-19 04:58:00', '2026-04-23 01:47:03'),
(3, 'PM-20260421-01', '2026-04-21 09:53:05', 2, 1, 'Ditolak', '2026-04-23 08:06:55', '2026-04-27 15:13:28', '2026-04-21 02:53:05', '2026-04-27 15:13:28'),
(4, 'PM-20260421-02', '2026-04-21 10:20:39', 2, 4, 'Disetujui', '2026-04-21 10:58:08', '2026-04-22 05:15:05', '2026-04-21 03:20:39', '2026-04-22 05:15:05'),
(5, 'PM-20260421-03', '2026-04-21 10:55:05', 2, 1, 'Ditolak', '2026-04-22 14:29:44', '2026-04-26 11:26:12', '2026-04-21 03:55:05', '2026-04-26 11:26:12'),
(6, 'PM-20260422-01', '2026-04-22 11:55:04', 3, 6, 'Diajukan', NULL, NULL, '2026-04-22 04:55:04', '2026-04-22 04:55:04'),
(7, 'PM-20260422-02', '2026-04-22 14:35:50', 15, 4, 'Disetujui', '2026-04-22 14:49:42', '2026-04-23 02:18:23', '2026-04-22 07:35:50', '2026-04-23 02:18:23');

-- --------------------------------------------------------

--
-- Struktur dari tabel `pengajuan_pemeliharaan_detail`
--

CREATE TABLE `pengajuan_pemeliharaan_detail` (
  `id` bigint UNSIGNED NOT NULL,
  `pengajuan_id` bigint UNSIGNED NOT NULL,
  `keperluan` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `estimasi_biaya` int NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `pengajuan_pemeliharaan_detail`
--

INSERT INTO `pengajuan_pemeliharaan_detail` (`id`, `pengajuan_id`, `keperluan`, `estimasi_biaya`, `created_at`, `updated_at`) VALUES
(1, 3, 'Ganti Oli', 200000, '2026-04-21 02:53:05', '2026-04-21 02:53:05'),
(2, 3, 'Servis Injeksi', 400000, '2026-04-21 02:53:05', '2026-04-21 02:53:05'),
(3, 4, 'Ganti Oli', 350000, '2026-04-21 03:20:39', '2026-04-21 03:20:39'),
(4, 4, 'Ganti 2 Ban', 2000000, '2026-04-21 03:20:40', '2026-04-21 03:20:40'),
(5, 5, 'Servis kaki kaki', 500000, '2026-04-21 03:55:05', '2026-04-21 03:55:05'),
(6, 6, 'ganti oli', 100000, '2026-04-22 04:55:04', '2026-04-22 04:55:04'),
(7, 6, 'ganti 2 kampas rem', 149997, '2026-04-22 04:55:04', '2026-04-22 04:55:04'),
(8, 7, 'Ganti Oli', 200000, '2026-04-22 07:35:50', '2026-04-22 07:35:50'),
(9, 7, 'Ganti 2 Ban', 2000000, '2026-04-22 07:35:50', '2026-04-22 07:35:50');

-- --------------------------------------------------------

--
-- Struktur dari tabel `riwayat_pajak`
--

CREATE TABLE `riwayat_pajak` (
  `id` bigint UNSIGNED NOT NULL,
  `kendaraan_id` bigint UNSIGNED NOT NULL,
  `tanggal_pajak` date NOT NULL,
  `nama_pengurus` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `keterangan` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `riwayat_pajak`
--

INSERT INTO `riwayat_pajak` (`id`, `kendaraan_id`, `tanggal_pajak`, `nama_pengurus`, `keterangan`, `created_at`, `updated_at`) VALUES
(1, 3, '2026-04-16', 'akadhasjd', NULL, '2026-04-09 04:26:20', '2026-04-09 04:26:20'),
(2, 2, '2026-04-08', NULL, NULL, '2026-04-09 07:11:28', '2026-04-09 07:11:28'),
(5, 5, '2026-04-10', 'yrot8t;llll', NULL, '2026-04-10 07:27:10', '2026-04-20 06:55:22'),
(7, 7, '2026-04-22', 'Pak Adi', NULL, '2026-04-22 07:47:24', '2026-04-22 07:47:24');

-- --------------------------------------------------------

--
-- Struktur dari tabel `riwayat_servis`
--

CREATE TABLE `riwayat_servis` (
  `id` bigint UNSIGNED NOT NULL,
  `kendaraan_id` bigint UNSIGNED NOT NULL,
  `tanggal_servis` date NOT NULL,
  `nama_pengurus` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `keterangan` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `riwayat_servis`
--

INSERT INTO `riwayat_servis` (`id`, `kendaraan_id`, `tanggal_servis`, `nama_pengurus`, `keterangan`, `created_at`, `updated_at`) VALUES
(2, 1, '2025-10-03', 'Pak Sinar', NULL, '2026-04-10 03:08:41', '2026-04-18 09:14:02'),
(3, 4, '2026-04-21', 'wxyz', NULL, '2026-04-21 07:31:29', '2026-04-21 07:31:29'),
(4, 4, '2026-04-22', 'sdasd', 'servis kaki kaki', '2026-04-22 04:50:33', '2026-04-22 04:50:33'),
(5, 6, '2026-04-01', 'okta', NULL, '2026-04-22 04:55:44', '2026-04-22 04:55:44');

-- --------------------------------------------------------

--
-- Struktur dari tabel `seksi`
--

CREATE TABLE `seksi` (
  `id` bigint UNSIGNED NOT NULL,
  `seksi` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `seksi_singkat` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nama_kepala` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `nip_kepala` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `seksi`
--

INSERT INTO `seksi` (`id`, `seksi`, `seksi_singkat`, `nama_kepala`, `nip_kepala`, `created_at`, `updated_at`) VALUES
(1, 'Seksi Teknologi Informasi dan Komunikasi Keimigrasian', 'Tikim', 'M. Hari Ariansyah', '19781102 200501 1 008', '2026-02-04 01:27:25', '2026-04-04 03:44:02'),
(2, 'Seksi Dokumen Perjalanan dan Izin Tinggal Keimigrasian', 'Doklanintalkim', 'Aguss', '19850312 201012 1 002', '2026-02-04 01:34:27', '2026-04-04 03:43:31'),
(3, 'Seksi Intelijen dan Penindakan Keimigrasian', 'Inteldakim', 'Wali', '19920725 201503 2 015', '2026-02-04 01:34:59', '2026-04-04 03:43:40'),
(4, 'Subbagian Tata Usaha', 'TU', 'Sri Pamungkas Handayani', '19900115 201902 2 001', '2026-02-04 01:35:20', '2026-04-04 03:44:10'),
(5, 'Pegawai Pemerintah Non Pegawai Negeri', 'PPNPN', 'AAAAA', '-', '2026-02-04 01:35:43', '2026-04-06 04:47:05');

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
('05oFISUMob5hZx8LwEZtnO4kvJhyatgUiPet7KSX', 18, '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiSHNpaVh5QTBOQlJWY2xtZk44S3Vyd051QUNiU1V5SkpPakxxbUNlYyI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6NDE6Imh0dHA6Ly9sYXJhdmVsMTEtcGVuZ2Vsb2xhYm1uLnRlc3QvYmFyYW5nIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6MTg7fQ==', 1778546691),
('Ew1vRxhAOhglReAxwMXUIMwtr56YM0fQHBTn9vGU', 18, '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiTDBHelBLTThqWG5hN2J0M3YzM2NzMm16Rzd2aVhPSFBHYUYxempNTCI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6NTc6Imh0dHA6Ly9sYXJhdmVsMTEtcGVuZ2Vsb2xhYm1uLnRlc3QvcGVtZWxpaGFyYWFuL2tlbmRhcmFhbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjE4O30=', 1778597722),
('F1PNboHwUZuebnpaotd8dMLI9d6PUq8k4eXjivKB', 18, '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'YTo1OntzOjY6Il90b2tlbiI7czo0MDoiV2ZPaVdYZEV5Y3dMUHo2ajR3d3NYSnRKQ1JBOU9rZXhIUUZXdlZtYiI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czo0MToiaHR0cDovL2xhcmF2ZWwxMS1wZW5nZWxvbGFibW4udGVzdC9iYXJhbmciO31zOjk6Il9wcmV2aW91cyI7YToxOntzOjM6InVybCI7czo0NDoiaHR0cDovL2xhcmF2ZWwxMS1wZW5nZWxvbGFibW4udGVzdC9kYXNoYm9hcmQiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX1zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aToxODt9', 1778564068),
('IS5rE3z5urCDLzYCsyaCZNWL094kasDbXTK2nMYS', 18, '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'YTo1OntzOjY6Il90b2tlbiI7czo0MDoiQkJTTjJhUFhLQzd3QVZJTGZQZmpFdnk0VTNVQTRqSld5ZW9Tc0MyNyI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czo1ODoiaHR0cDovL2xhcmF2ZWwxMS1wZW5nZWxvbGFibW4udGVzdC9wZW5nYWp1YW4vcml3YXlhdF9hZG1pbiI7fXM6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjQ3OiJodHRwOi8vbGFyYXZlbDExLXBlbmdlbG9sYWJtbi50ZXN0L3Byb2ZpbGUvZWRpdCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjE4O30=', 1779068260);

-- --------------------------------------------------------

--
-- Struktur dari tabel `settings`
--

CREATE TABLE `settings` (
  `id` bigint UNSIGNED NOT NULL,
  `logo` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `login_bg` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nama_aplikasi` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'Singobarong',
  `nama_aplikasi2` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'Pemeliharaan',
  `subnama_aplikasi` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `login_opening_text` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `sidebar_color` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '#1e3a8a',
  `sidebar_text_color` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '#ffffff',
  `sidebar_hover_color` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '#1d4ed8',
  `format_cetak` enum('pdf','docx') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `settings`
--

INSERT INTO `settings` (`id`, `logo`, `login_bg`, `nama_aplikasi`, `nama_aplikasi2`, `subnama_aplikasi`, `login_opening_text`, `sidebar_color`, `sidebar_text_color`, `sidebar_hover_color`, `format_cetak`, `created_at`, `updated_at`) VALUES
(1, 'logo_1771994474.png', 'login_bg_1775437685.jpg', 'Singobarong', 'Pemeliharaan', 'Sistem Informasi Inventaris Pengelolaan Operasional Barang Persediaan dan Aset BMN Kantor Imigrasi Ponorogo Kelas II Non TPI Ponorogo', 'Aplikasi ini digunakan untuk mengelola Barang Persediaan dan Barang Milik Negara, memfasilitasi proses permintaan barang secara digital, serta memantau distribusi stok secara real-time, akurat, dan transparan.', '#00178a', '#ffffff', '#004cff', 'docx', '2026-02-12 02:36:04', '2026-05-05 01:00:07');

-- --------------------------------------------------------

--
-- Struktur dari tabel `settings2`
--

CREATE TABLE `settings2` (
  `id` bigint NOT NULL,
  `nama_kasubbag_tu` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nip_kasubbag_tu` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nama_kaurumum_tu` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nip_kaurumum_tu` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nama_staffbmn_tu` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nip_staffbmn_tu` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `settings2`
--

INSERT INTO `settings2` (`id`, `nama_kasubbag_tu`, `nip_kasubbag_tu`, `nama_kaurumum_tu`, `nip_kaurumum_tu`, `nama_staffbmn_tu`, `nip_staffbmn_tu`, `created_at`, `updated_at`) VALUES
(1, 'Sri Pamungkas Handayani', '19900115 201902 2 001', 'Gilang Tri Parama Yudha', '19940521 202203 1 005', 'Rizki Karima Vito Chrismastanto', '19921225 201901 1 001', '2026-03-12 02:46:21', '2026-04-06 04:42:20');

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
  `nip` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `photo` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `seksi_id` bigint UNSIGNED DEFAULT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
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

INSERT INTO `users` (`id`, `name`, `email`, `nip`, `photo`, `seksi_id`, `password`, `role`, `status`, `last_login_at`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Admin BMN', 'admin@imigrasi-ponorogo.go.id', NULL, 'profiles/z8ZdB1IP0hA89dhEhkNbgahA39eIfLzYhPuqOzmx.jpg', 4, '$2y$12$minBmJ/.TRH0.WCOS0sBgOvWEem.M6zJGHm8qzQVS./TkNE9hUcG.', 'Administrator', 'Aktif', '2026-05-11 13:13:46', NULL, '2026-01-07 07:07:45', '2026-05-11 06:13:46'),
(2, 'Seksi Tikim', 'tikim01@gmail.com', NULL, 'profiles/yZZa6sGcWN8qvzPP2UCSnGFlBmsbYtiQdY1gQw6l.jpg', 1, '$2y$12$DEafOhFWSkBvS7nNq2STBe1RI4hUNfSU9SbeImONMWtACkPw1HwFG', 'Staff', 'Aktif', '2026-05-11 13:14:51', NULL, '2026-01-07 21:40:35', '2026-05-11 06:14:51'),
(3, 'Seksi Inteldakim', 'inteldakim01@gmail.com', NULL, 'profiles/default-profile.png', 3, '$2y$12$prHDyrqnbJyOC.P4VECJy.SiIEZZLrSKb.ByWYpAw9WTnvrLYWoq2', 'Staff', 'Aktif', '2026-04-22 11:54:20', NULL, '2026-01-13 19:01:23', '2026-04-22 04:54:20'),
(5, 'Seksi Doklanintalkim', 'doklanintalkim01@gmail.com', NULL, 'profiles/default-profile.png', 2, '$2y$12$TrIdpj30vNum3uBfYSKGOej.ouDGr7las9PHs8JCFcFwStlj3mNLS', 'Staff', 'Aktif', '2026-03-11 08:43:55', NULL, '2026-01-18 20:12:12', '2026-03-11 01:43:55'),
(6, 'Subbagian TU', 'tu01@gmail.com', NULL, 'profiles/default-profile.png', 4, '$2y$12$PXN5pjblbC7OBTvuN4hvueZhw2fi5vVRPISyfje0XbWPH0KRlqDg.', 'Staff', 'Aktif', '2026-03-09 11:05:16', NULL, '2026-01-18 20:13:19', '2026-03-09 04:05:16'),
(11, 'Seksi Doklalintalkim 2', 'doklanintalkim02@gmail.com', NULL, 'profiles/8gfvEvsTPbZl7SBbmThX3pBIeRvYgYkkrV2rJ6Xv.jpg', 2, '$2y$12$kyc1WLyWenkfFHxjsR0ChOkCz7FG.KrVgvcKrTamPL4tTUo82R7jK', 'Staff', 'Tidak Aktif', NULL, NULL, '2026-01-19 23:11:01', '2026-05-18 01:35:30'),
(12, 'PPNPN', 'ppnpn@gmail.com', NULL, 'profiles/default-profile.png', 5, '$2y$12$8gVnCvGUJFGyMdCmeMp7D.g1s8ho9BBtWFzvIx1iyLH7ZN/5zX/0i', 'Staff', 'Aktif', '2026-04-04 08:34:02', NULL, '2026-01-21 20:03:57', '2026-04-04 01:34:02'),
(15, 'Seksi Tikim 2', 'tikim02@gmail.com', NULL, 'profiles/Y2gKNRMULJZhD3cbPHBZpbm85Hpn9HjXZbkocuHS.jpg', 1, '$2y$12$NuEYK9.W3QInOirFX7Jj0.j2iIVH1Cdbyg63zP18ILa4cvsVepinm', 'Staff', 'Aktif', '2026-05-11 09:15:45', NULL, '2026-04-22 04:58:09', '2026-05-11 02:15:45'),
(16, 'Admin 2', 'admin02@gmail.com', NULL, 'profiles/a6HDJtcqSla47ezeK0KvVmClUiZJDzZFJxSuLLmB.jpg', 4, '$2y$12$VLZIyzZgaK6sOT4wZRFSb.biOWb3goLRAajv29TCc22uDHHSD7nga', 'Administrator', 'Aktif', '2026-04-28 13:45:37', NULL, '2026-04-28 06:45:21', '2026-04-29 01:57:32'),
(18, 'Super Admin', 'superadmin@system.com', NULL, 'profiles/6aWF8IJk07YyIit5XS5TugQ0ZAV0r3XZm8MxfSAF.jpg', 1, '$2y$12$4W2wPouzvjd8p3Jeu7QfW.SnfG5S0mQp6HfygHJDmVq5wrX7PdAGW', 'Administrator', 'Aktif', '2026-05-18 08:33:30', NULL, '2026-04-29 03:03:19', '2026-05-18 01:33:30');

-- --------------------------------------------------------

--
-- Struktur dari tabel `_barang_masuk`
--

CREATE TABLE `_barang_masuk` (
  `id` bigint UNSIGNED NOT NULL,
  `barang_id` bigint UNSIGNED NOT NULL,
  `jumlah` int NOT NULL,
  `harga_satuan` decimal(15,2) DEFAULT NULL,
  `harga_total` decimal(15,2) DEFAULT NULL,
  `tanggal` datetime NOT NULL,
  `keterangan` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `_barang_masuk`
--

INSERT INTO `_barang_masuk` (`id`, `barang_id`, `jumlah`, `harga_satuan`, `harga_total`, `tanggal`, `keterangan`, `created_at`, `updated_at`) VALUES
(3, 1, 5, 10000000.00, 50000000.00, '2026-01-13 00:00:00', NULL, '2026-01-12 23:40:44', '2026-01-12 23:40:44'),
(4, 2, 2, 5000000.00, 10000000.00, '2026-01-14 00:00:00', NULL, '2026-01-13 22:53:41', '2026-01-13 22:53:41'),
(5, 5, 5, 4000000.00, 20000000.00, '2026-01-23 00:00:00', NULL, '2026-01-23 00:52:59', '2026-01-23 00:52:59'),
(7, 1, 1, 50000000.00, 50000000.00, '2026-02-11 00:00:00', 'as', '2026-02-11 07:56:37', '2026-02-11 07:56:37'),
(8, 2, 4, 66666666666.00, 266666666664.00, '2026-02-11 00:00:00', 'jhjhj', '2026-02-11 08:05:41', '2026-02-11 08:05:41'),
(9, 2, 2, 1000000.00, 2000000.00, '2026-02-11 00:00:00', 'a', '2026-02-11 08:07:10', '2026-02-11 08:07:10'),
(10, 4, 2, 50000.00, 100000.00, '2026-02-13 00:00:00', 'p', '2026-02-13 03:10:15', '2026-02-13 03:10:15'),
(13, 2, 2, 250000.00, 500000.00, '2026-02-13 00:00:00', 'w', '2026-02-13 06:44:08', '2026-02-13 06:44:08'),
(16, 1, 5, 5000000.00, 25000000.00, '2026-02-27 00:00:00', NULL, '2026-02-27 01:29:13', '2026-02-27 01:29:13'),
(18, 6, 5, NULL, NULL, '2026-03-09 00:00:00', NULL, '2026-03-09 03:36:39', '2026-03-09 03:36:39'),
(19, 2, 1, 5000000.00, 5000000.00, '2026-03-11 00:00:00', 'h', '2026-03-11 07:54:10', '2026-03-11 07:54:10'),
(20, 7, 5, 10000000.00, 50000000.00, '2026-03-12 08:04:22', NULL, '2026-03-12 01:04:22', '2026-03-12 01:04:22');

--
-- Indeks untuk tabel yang dibuang
--

--
-- Indeks untuk tabel `anggota_kelompok`
--
ALTER TABLE `anggota_kelompok`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `barangs`
--
ALTER TABLE `barangs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `kode_barang` (`kode_barang`),
  ADD KEY `fk_barang_kategori` (`kategori_id`);

--
-- Indeks untuk tabel `barang_bmn`
--
ALTER TABLE `barang_bmn`
  ADD PRIMARY KEY (`id`),
  ADD KEY `kategori_id` (`kategori_id`);

--
-- Indeks untuk tabel `barang_masuk`
--
ALTER TABLE `barang_masuk`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `barang_masuk_details`
--
ALTER TABLE `barang_masuk_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_barang1` (`barang_id`),
  ADD KEY `barang_masuk_id` (`barang_masuk_id`) USING BTREE;

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
-- Indeks untuk tabel `catatan`
--
ALTER TABLE `catatan`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

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
-- Indeks untuk tabel `kendaraan`
--
ALTER TABLE `kendaraan`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nomor_polisi` (`nomor_polisi`),
  ADD KEY `seksi_id` (`seksi_id`);

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
  ADD UNIQUE KEY `pengajuans_kode_pengajuan_unique` (`kode_pengajuan`),
  ADD KEY `pengajuans_user_id_foreign` (`user_id`);

--
-- Indeks untuk tabel `pengajuan_bmn`
--
ALTER TABLE `pengajuan_bmn`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `kode_pengajuan` (`kode_pengajuan`),
  ADD KEY `user_id` (`user_id`);

--
-- Indeks untuk tabel `pengajuan_bmn_detail`
--
ALTER TABLE `pengajuan_bmn_detail`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pengajuan_id` (`pengajuan_id`,`barang_id`),
  ADD KEY `fk_barang_bmn` (`barang_id`);

--
-- Indeks untuk tabel `pengajuan_details`
--
ALTER TABLE `pengajuan_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_pengajuan` (`pengajuan_id`),
  ADD KEY `fk_barang` (`barang_id`);

--
-- Indeks untuk tabel `pengajuan_pemeliharaan`
--
ALTER TABLE `pengajuan_pemeliharaan`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_pemeliharaan_kendaraan` (`kendaraan_id`),
  ADD KEY `fk_pemeliharaan_user` (`user_id`);

--
-- Indeks untuk tabel `pengajuan_pemeliharaan_detail`
--
ALTER TABLE `pengajuan_pemeliharaan_detail`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pengajuan_id` (`pengajuan_id`);

--
-- Indeks untuk tabel `riwayat_pajak`
--
ALTER TABLE `riwayat_pajak`
  ADD PRIMARY KEY (`id`),
  ADD KEY `kendaraan_id` (`kendaraan_id`);

--
-- Indeks untuk tabel `riwayat_servis`
--
ALTER TABLE `riwayat_servis`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_riwayatservis_kendaraan` (`kendaraan_id`);

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
-- Indeks untuk tabel `settings2`
--
ALTER TABLE `settings2`
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
-- Indeks untuk tabel `_barang_masuk`
--
ALTER TABLE `_barang_masuk`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_barang_masuk_barang` (`barang_id`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `anggota_kelompok`
--
ALTER TABLE `anggota_kelompok`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT untuk tabel `barangs`
--
ALTER TABLE `barangs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT untuk tabel `barang_bmn`
--
ALTER TABLE `barang_bmn`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT untuk tabel `barang_masuk`
--
ALTER TABLE `barang_masuk`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT untuk tabel `barang_masuk_details`
--
ALTER TABLE `barang_masuk_details`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT untuk tabel `catatan`
--
ALTER TABLE `catatan`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

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
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT untuk tabel `kendaraan`
--
ALTER TABLE `kendaraan`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT untuk tabel `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT untuk tabel `pengajuans`
--
ALTER TABLE `pengajuans`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=138;

--
-- AUTO_INCREMENT untuk tabel `pengajuan_bmn`
--
ALTER TABLE `pengajuan_bmn`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT untuk tabel `pengajuan_bmn_detail`
--
ALTER TABLE `pengajuan_bmn_detail`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT untuk tabel `pengajuan_details`
--
ALTER TABLE `pengajuan_details`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=229;

--
-- AUTO_INCREMENT untuk tabel `pengajuan_pemeliharaan`
--
ALTER TABLE `pengajuan_pemeliharaan`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT untuk tabel `pengajuan_pemeliharaan_detail`
--
ALTER TABLE `pengajuan_pemeliharaan_detail`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT untuk tabel `riwayat_pajak`
--
ALTER TABLE `riwayat_pajak`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT untuk tabel `riwayat_servis`
--
ALTER TABLE `riwayat_servis`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

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
-- AUTO_INCREMENT untuk tabel `settings2`
--
ALTER TABLE `settings2`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT untuk tabel `_barang_masuk`
--
ALTER TABLE `_barang_masuk`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `barangs`
--
ALTER TABLE `barangs`
  ADD CONSTRAINT `fk_barang_kategori` FOREIGN KEY (`kategori_id`) REFERENCES `kategori` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Ketidakleluasaan untuk tabel `barang_bmn`
--
ALTER TABLE `barang_bmn`
  ADD CONSTRAINT `fk_barangbmn_kategori` FOREIGN KEY (`kategori_id`) REFERENCES `kategori` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Ketidakleluasaan untuk tabel `barang_masuk_details`
--
ALTER TABLE `barang_masuk_details`
  ADD CONSTRAINT `fk_barang1` FOREIGN KEY (`barang_id`) REFERENCES `barangs` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `fk_barang_masuk1` FOREIGN KEY (`barang_masuk_id`) REFERENCES `barang_masuk` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Ketidakleluasaan untuk tabel `catatan`
--
ALTER TABLE `catatan`
  ADD CONSTRAINT `catatan_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Ketidakleluasaan untuk tabel `kendaraan`
--
ALTER TABLE `kendaraan`
  ADD CONSTRAINT `fk_kendaraan_seksi` FOREIGN KEY (`seksi_id`) REFERENCES `seksi` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Ketidakleluasaan untuk tabel `pengajuans`
--
ALTER TABLE `pengajuans`
  ADD CONSTRAINT `pengajuans_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Ketidakleluasaan untuk tabel `pengajuan_bmn`
--
ALTER TABLE `pengajuan_bmn`
  ADD CONSTRAINT `fk_pengajuanbmn_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Ketidakleluasaan untuk tabel `pengajuan_bmn_detail`
--
ALTER TABLE `pengajuan_bmn_detail`
  ADD CONSTRAINT `fk_barang_bmn` FOREIGN KEY (`barang_id`) REFERENCES `barang_bmn` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `fk_pengajuan_bmn` FOREIGN KEY (`pengajuan_id`) REFERENCES `pengajuan_bmn` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT;

--
-- Ketidakleluasaan untuk tabel `pengajuan_details`
--
ALTER TABLE `pengajuan_details`
  ADD CONSTRAINT `fk_barang` FOREIGN KEY (`barang_id`) REFERENCES `barangs` (`id`),
  ADD CONSTRAINT `fk_pengajuan` FOREIGN KEY (`pengajuan_id`) REFERENCES `pengajuans` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `pengajuan_pemeliharaan`
--
ALTER TABLE `pengajuan_pemeliharaan`
  ADD CONSTRAINT `fk_pemeliharaan_kendaraan` FOREIGN KEY (`kendaraan_id`) REFERENCES `kendaraan` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `fk_pemeliharaan_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON UPDATE RESTRICT;

--
-- Ketidakleluasaan untuk tabel `pengajuan_pemeliharaan_detail`
--
ALTER TABLE `pengajuan_pemeliharaan_detail`
  ADD CONSTRAINT `fk_pengajuanpemeliharaandetail_pengajuanpemeliharaan` FOREIGN KEY (`pengajuan_id`) REFERENCES `pengajuan_pemeliharaan` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Ketidakleluasaan untuk tabel `riwayat_pajak`
--
ALTER TABLE `riwayat_pajak`
  ADD CONSTRAINT `fk_riwayatpajak_kendaraan` FOREIGN KEY (`kendaraan_id`) REFERENCES `kendaraan` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Ketidakleluasaan untuk tabel `riwayat_servis`
--
ALTER TABLE `riwayat_servis`
  ADD CONSTRAINT `fk_riwayatservis_kendaraan` FOREIGN KEY (`kendaraan_id`) REFERENCES `kendaraan` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Ketidakleluasaan untuk tabel `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `fk_users_seksi` FOREIGN KEY (`seksi_id`) REFERENCES `seksi` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

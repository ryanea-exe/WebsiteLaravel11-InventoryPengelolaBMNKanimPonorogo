-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 13, 2026 at 03:26 AM
-- Server version: 10.4.25-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `digicam`
--

-- --------------------------------------------------------

--
-- Table structure for table `cams`
--

CREATE TABLE `cams` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `cam_code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `cover` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Tersedia',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cams`
--

INSERT INTO `cams` (`id`, `cam_code`, `name`, `cover`, `status`, `created_at`, `updated_at`) VALUES
(20, 'CAM01', 'Canon 200D', 'Canon 200D-1720727225.png', 'Tersedia', '2024-07-11 12:47:05', '2024-07-11 12:47:05'),
(21, 'CAM02', 'Canon EOS 5D Mark IV', 'Canon EOS 5D Mark IV-1720727330.png', 'Tersedia', '2024-07-11 12:48:50', '2024-07-11 12:48:50'),
(22, 'CAM03', 'Canon EOS 6D Mark II', 'Canon EOS 6D Mark II-1720727595.png', 'Tersedia', '2024-07-11 12:53:15', '2024-07-11 12:53:15'),
(23, 'CAM04', 'Canon EOS 3000D', 'Canon EOS 3000D-1720727729.png', 'Tersedia', '2024-07-11 12:55:29', '2024-07-11 12:55:29'),
(24, 'CAM05', 'Sony Alpha 7iii', 'Sony Alpha 7iii-1720728424.png', 'Tersedia', '2024-07-11 13:07:04', '2024-07-11 13:07:04'),
(25, 'CAM06', 'Sony A6400L', 'Sony A6400L-1720728722.jpg', 'Tersedia', '2024-07-11 13:12:02', '2024-07-11 13:12:02'),
(26, 'CAM07', 'Sony Alpha A3000', 'Sony Alpha A3000-1720728902.jpg', 'Tersedia', '2024-07-11 13:15:02', '2024-07-11 13:15:02'),
(27, 'CAM08', 'Lensa Kit', 'Lensa Kit-1720729883.jpg', 'Tersedia', '2024-07-11 13:31:23', '2024-07-11 13:31:23'),
(28, 'CAM09', 'Lensa Fix', 'Lensa Fix-1720729900.jpg', 'Tersedia', '2024-07-11 13:31:40', '2024-07-11 13:31:40'),
(29, 'CAM10', 'Lensa Tele', 'Lensa Tele-1720730011.jpg', 'Tersedia', '2024-07-11 13:33:31', '2024-07-11 13:33:31'),
(30, 'CAM11', 'Lighting', 'Lighting-1720730173.jpg', 'Tersedia', '2024-07-11 13:36:13', '2024-07-11 13:36:13'),
(31, 'CAM12', 'Tripod', 'Tripod-1720730264.jpg', 'Tersedia', '2024-07-11 13:37:44', '2024-07-11 13:37:44'),
(33, 'CAM017', 'Lensa', 'Lensa-1721103653.jpg', 'Tersedia', '2024-07-15 21:20:53', '2024-07-15 21:20:53');

-- --------------------------------------------------------

--
-- Table structure for table `cam_category`
--

CREATE TABLE `cam_category` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `cam_id` bigint(20) UNSIGNED NOT NULL,
  `category_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `created_at`, `updated_at`) VALUES
(1, 'Kamera', NULL, NULL),
(2, 'Lensa', NULL, NULL),
(3, 'Drone', NULL, NULL),
(4, 'Tripod', '2024-07-11 04:06:39', '2024-07-11 04:06:39'),
(5, 'Audio', '2024-07-11 04:08:07', '2024-07-11 04:08:07');

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2014_10_12_100000_create_password_resets_table', 1),
(3, '2019_08_19_000000_create_failed_jobs_table', 1),
(4, '2019_12_14_000001_create_personal_access_tokens_table', 1),
(5, '2024_07_10_155619_create_cams_table', 1),
(6, '2024_07_10_160934_create_categories_table', 2),
(7, '2024_07_10_161116_create_cam_category_table', 3),
(8, '2024_07_10_161218_create_rent_logs_table', 4),
(9, '2024_07_11_144935_create_rentals_table', 5);

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `rentals`
--

CREATE TABLE `rentals` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nama` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `alamat` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `no` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `produk` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `harga` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `lama` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_harga` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tanggal` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `rentals`
--

INSERT INTO `rentals` (`id`, `nama`, `alamat`, `no`, `produk`, `harga`, `lama`, `total_harga`, `tanggal`, `created_at`, `updated_at`) VALUES
(11, 'Edo', 'Nganjuk', '081717219071212', 'Lighting', '70000', '2', '140000', '2024-07-15', '2024-07-11 13:56:24', '2024-07-11 13:56:24'),
(12, 'Bintang', 'Bojonegoro', '089819219218219', 'Sony Alpha A3000', '200000', '3', '600000', '2024-07-15', '2024-07-11 13:57:36', '2024-07-11 13:57:36'),
(13, 'Firhan', 'Wonogiri', '0898928932892', 'Canon EOS 6D Mark II', '250000', '2', '500000', '2024-07-13', '2024-07-11 13:58:45', '2024-07-11 13:58:45');

-- --------------------------------------------------------

--
-- Table structure for table `rent_logs`
--

CREATE TABLE `rent_logs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `cam_id` bigint(20) UNSIGNED NOT NULL,
  `rent_date` date NOT NULL,
  `return_date` date NOT NULL,
  `actual_return_date` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `refresh_token` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `refresh_token`, `created_at`, `updated_at`) VALUES
(1, 'Agus', 'Agus@gmail.com', 'agus', NULL, NULL, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cams`
--
ALTER TABLE `cams`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cam_category`
--
ALTER TABLE `cam_category`
  ADD PRIMARY KEY (`id`),
  ADD KEY `cam_category_cam_id_foreign` (`cam_id`),
  ADD KEY `cam_category_category_id_foreign` (`category_id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Indexes for table `rentals`
--
ALTER TABLE `rentals`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `rent_logs`
--
ALTER TABLE `rent_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `rent_logs_user_id_foreign` (`user_id`),
  ADD KEY `rent_logs_cam_id_foreign` (`cam_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `cams`
--
ALTER TABLE `cams`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT for table `cam_category`
--
ALTER TABLE `cam_category`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `rentals`
--
ALTER TABLE `rentals`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `rent_logs`
--
ALTER TABLE `rent_logs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `cam_category`
--
ALTER TABLE `cam_category`
  ADD CONSTRAINT `cam_category_cam_id_foreign` FOREIGN KEY (`cam_id`) REFERENCES `cams` (`id`),
  ADD CONSTRAINT `cam_category_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`);

--
-- Constraints for table `rent_logs`
--
ALTER TABLE `rent_logs`
  ADD CONSTRAINT `rent_logs_cam_id_foreign` FOREIGN KEY (`cam_id`) REFERENCES `cams` (`id`),
  ADD CONSTRAINT `rent_logs_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

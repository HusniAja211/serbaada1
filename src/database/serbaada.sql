-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: May 13, 2025 at 03:02 PM
-- Server version: 8.4.3
-- PHP Version: 8.3.16

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `serbaada`
--

-- --------------------------------------------------------

--
-- Table structure for table `karyawan`
--

CREATE TABLE `karyawan` (
  `id_karyawan` int NOT NULL,
  `email_karyawan` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `username_karyawan` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `level_karyawan` enum('admin','kasir') NOT NULL,
  `password_karyawan` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `gambar_karyawan` varchar(100) NOT NULL,
  `kode_otp` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `expiry_otp` datetime DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `karyawan`
--

INSERT INTO `karyawan` (`id_karyawan`, `email_karyawan`, `username_karyawan`, `level_karyawan`, `password_karyawan`, `gambar_karyawan`, `kode_otp`, `expiry_otp`, `created_at`, `updated_at`) VALUES
(1, 'husnimubarakportofolio@gmail.com', 'Husni Mubarak', 'admin', '$2y$10$y8BneSthTQ5m8aiF2UwlEujm8yTDlFQyw2rX5iX2xVmqkMvQeheqC', 'admin1@gmail.com.png', NULL, NULL, '2025-05-02 17:42:35', '2025-05-13 02:30:09'),
(2, 'kasir1@gmail.com', 'kasir', 'kasir', '$2y$10$EfoxHvnKuXKYDEigfcp2Y.C4y5XXcujub5M//teHoYvLzsIeLlkt.', '1746367109_ezgif-297d4daad8079c.png', '', NULL, '2025-05-04 13:58:29', '2025-05-04 13:58:29'),
(4, 'kasir2@gmail.com', 'kasirr', 'kasir', '$2y$10$aGjsL0Y.dpxL0qnjdlLEV.HfL51kyZ58tpA2OEQPvgepVvtnNcz4q', '1746642742_1.png', NULL, NULL, '2025-05-07 18:32:22', '2025-05-07 18:32:22'),
(5, 'admin1@gmail.com', 'admin', 'admin', '$2y$10$Q.zCcztSI7gnqLpKbJhOGuw/K9ZcKkFMY1Di9kOMzyjzAT.bNbzEG', 'logo es sugus.jpg', NULL, NULL, '2025-05-10 17:05:18', '2025-05-10 17:05:18');

-- --------------------------------------------------------

--
-- Table structure for table `kategori`
--

CREATE TABLE `kategori` (
  `id_kategori` int NOT NULL,
  `nama_kategori` varchar(100) NOT NULL,
  `deskripsi_kategori` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `kategori`
--

INSERT INTO `kategori` (`id_kategori`, `nama_kategori`, `deskripsi_kategori`, `created_at`, `updated_at`) VALUES
(1, 'Makanan', 'Makanan', '2025-04-21 13:13:30', '2025-04-21 13:13:30'),
(2, 'Minuman', 'Pelepas Dahaga', '2025-05-01 09:15:41', '2025-05-01 09:15:41'),
(3, 'Camilan', '                                                   aaaaaaa                             ', '2025-05-13 02:24:59', '2025-05-13 02:24:59');

-- --------------------------------------------------------

--
-- Table structure for table `member`
--

CREATE TABLE `member` (
  `id_member` int NOT NULL,
  `nama_member` varchar(100) NOT NULL,
  `no_telepon_member` varchar(14) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `point_member` int NOT NULL,
  `status_member` enum('aktif','tidak aktif') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `member`
--

INSERT INTO `member` (`id_member`, `nama_member`, `no_telepon_member`, `point_member`, `status_member`, `created_at`, `updated_at`) VALUES
(1, 'Dean Satrio Arung Gesang', '+6288210266308', 0, 'aktif', '2025-05-06 02:44:14', '2025-05-11 08:53:55'),
(2, 'Husni Mubarak', '+6285781197648', 6760, 'aktif', '2025-05-08 22:16:33', '2025-05-11 08:53:49'),
(3, 'Satria Farel Cipta Permata', '+6288299309375', 0, 'aktif', '2025-05-11 08:17:31', '2025-05-13 08:34:05');

-- --------------------------------------------------------

--
-- Table structure for table `presensi_karyawan`
--

CREATE TABLE `presensi_karyawan` (
  `id_presensi_karyawan` int NOT NULL,
  `fid_karyawan` int NOT NULL,
  `status` enum('Hadir','Izin','Sakit','Tidak Hadir') NOT NULL DEFAULT 'Tidak Hadir',
  `tanggal_presensi` date NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `presensi_karyawan`
--

INSERT INTO `presensi_karyawan` (`id_presensi_karyawan`, `fid_karyawan`, `status`, `tanggal_presensi`, `created_at`, `updated_at`) VALUES
(1, 2, 'Hadir', '2025-05-07', '2025-05-06 13:27:35', '2025-05-06 13:27:35'),
(2, 1, 'Izin', '2025-05-06', '2025-05-06 13:32:36', '2025-05-06 13:32:36'),
(3, 1, 'Izin', '2025-05-12', '2025-05-12 18:35:19', '2025-05-12 18:35:19');

-- --------------------------------------------------------

--
-- Table structure for table `produk`
--

CREATE TABLE `produk` (
  `id_produk` int NOT NULL,
  `nama_produk` varchar(100) NOT NULL,
  `tanggal_expired` date DEFAULT NULL,
  `stok_produk` int NOT NULL,
  `uang_modal_produk` decimal(19,2) NOT NULL,
  `harga_jual_produk` decimal(19,2) NOT NULL,
  `keuntungan_produk` decimal(19,2) NOT NULL,
  `fid_kategori` int NOT NULL,
  `gambar_produk` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `deskripsi_produk` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `produk`
--

INSERT INTO `produk` (`id_produk`, `nama_produk`, `tanggal_expired`, `stok_produk`, `uang_modal_produk`, `harga_jual_produk`, `keuntungan_produk`, `fid_kategori`, `gambar_produk`, `deskripsi_produk`, `created_at`, `updated_at`) VALUES
(1, 'Ayam Goreng Sambal Balado', '2025-05-14', 9, 10000.00, 12000.00, 2000.00, 1, '1_20250511.jpg', 'Pedes, leeee', '2025-05-11 08:11:47', '2025-05-11 08:11:47'),
(2, 'Es Sugus', '2025-05-31', 5, 4000.00, 5000.00, 1000.00, 2, '2_20250512.jpg', 'Segerrrr', '2025-05-12 19:48:36', '2025-05-12 19:48:36');

-- --------------------------------------------------------

--
-- Table structure for table `transaksi`
--

CREATE TABLE `transaksi` (
  `id_transaksi` int NOT NULL,
  `tanggal_transaksi` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `total_harga_transaksi` decimal(19,2) NOT NULL,
  `fid_karyawan` int NOT NULL,
  `fid_produk` int NOT NULL,
  `fid_member` int DEFAULT NULL,
  `total_keuntungan` decimal(19,2) NOT NULL,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `transaksi`
--

INSERT INTO `transaksi` (`id_transaksi`, `tanggal_transaksi`, `total_harga_transaksi`, `fid_karyawan`, `fid_produk`, `fid_member`, `total_keuntungan`, `updated_at`) VALUES
(1, '2025-05-12 18:17:34', 12000.00, 1, 1, 2, 2000.00, '2025-05-12 18:17:34'),
(2, '2025-05-12 21:48:22', 5000.00, 1, 2, 2, 1000.00, '2025-05-12 21:48:22'),
(3, '2025-05-12 21:48:22', 12000.00, 1, 1, 2, 2000.00, '2025-05-12 21:48:22'),
(4, '2025-05-12 21:50:45', 5000.00, 1, 2, NULL, 1000.00, '2025-05-12 21:50:45'),
(5, '2025-05-12 21:50:45', 12000.00, 1, 1, NULL, 2000.00, '2025-05-12 21:50:45'),
(6, '2025-05-12 21:52:30', 5000.00, 1, 2, NULL, 1000.00, '2025-05-12 21:52:30'),
(7, '2025-05-12 21:52:30', 12000.00, 1, 1, NULL, 2000.00, '2025-05-12 21:52:30'),
(8, '2025-05-13 02:18:20', 5000.00, 1, 2, 2, 1000.00, '2025-05-13 02:18:20'),
(9, '2025-05-13 02:19:56', 12000.00, 1, 1, 2, 2000.00, '2025-05-13 02:19:56'),
(10, '2025-05-13 02:36:43', 12000.00, 1, 1, 2, 2000.00, '2025-05-13 02:36:43'),
(11, '2025-05-13 02:36:43', 5000.00, 1, 2, 2, 1000.00, '2025-05-13 02:36:43');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `karyawan`
--
ALTER TABLE `karyawan`
  ADD PRIMARY KEY (`id_karyawan`) USING BTREE;

--
-- Indexes for table `kategori`
--
ALTER TABLE `kategori`
  ADD PRIMARY KEY (`id_kategori`);

--
-- Indexes for table `member`
--
ALTER TABLE `member`
  ADD PRIMARY KEY (`id_member`);

--
-- Indexes for table `presensi_karyawan`
--
ALTER TABLE `presensi_karyawan`
  ADD PRIMARY KEY (`id_presensi_karyawan`),
  ADD KEY `fid_karyawan` (`fid_karyawan`);

--
-- Indexes for table `produk`
--
ALTER TABLE `produk`
  ADD PRIMARY KEY (`id_produk`),
  ADD UNIQUE KEY `nama_produk` (`nama_produk`),
  ADD KEY `fid_kategori` (`fid_kategori`);

--
-- Indexes for table `transaksi`
--
ALTER TABLE `transaksi`
  ADD PRIMARY KEY (`id_transaksi`),
  ADD KEY `fid_admin` (`fid_karyawan`),
  ADD KEY `fid_produk` (`fid_produk`),
  ADD KEY `fid_member` (`fid_member`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `karyawan`
--
ALTER TABLE `karyawan`
  MODIFY `id_karyawan` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `kategori`
--
ALTER TABLE `kategori`
  MODIFY `id_kategori` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `member`
--
ALTER TABLE `member`
  MODIFY `id_member` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `presensi_karyawan`
--
ALTER TABLE `presensi_karyawan`
  MODIFY `id_presensi_karyawan` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `produk`
--
ALTER TABLE `produk`
  MODIFY `id_produk` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `transaksi`
--
ALTER TABLE `transaksi`
  MODIFY `id_transaksi` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `presensi_karyawan`
--
ALTER TABLE `presensi_karyawan`
  ADD CONSTRAINT `presensi_karyawan_ibfk_1` FOREIGN KEY (`fid_karyawan`) REFERENCES `karyawan` (`id_karyawan`) ON UPDATE CASCADE;

--
-- Constraints for table `produk`
--
ALTER TABLE `produk`
  ADD CONSTRAINT `produk_ibfk_1` FOREIGN KEY (`fid_kategori`) REFERENCES `kategori` (`id_kategori`) ON DELETE RESTRICT ON UPDATE CASCADE;

--
-- Constraints for table `transaksi`
--
ALTER TABLE `transaksi`
  ADD CONSTRAINT `transaksi_ibfk_1` FOREIGN KEY (`fid_karyawan`) REFERENCES `karyawan` (`id_karyawan`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `transaksi_ibfk_2` FOREIGN KEY (`fid_member`) REFERENCES `member` (`id_member`) ON UPDATE CASCADE,
  ADD CONSTRAINT `transaksi_ibfk_3` FOREIGN KEY (`fid_produk`) REFERENCES `produk` (`id_produk`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

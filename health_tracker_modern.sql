-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Waktu pembuatan: 05 Jan 2026 pada 14.24
-- Versi server: 10.4.28-MariaDB
-- Versi PHP: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `health_tracker_modern`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `activities`
--

CREATE TABLE `activities` (
  `id` int(11) NOT NULL,
  `id_user` int(11) DEFAULT NULL,
  `tanggal` date DEFAULT NULL,
  `jam_tidur` float DEFAULT NULL,
  `durasi_olahraga` float DEFAULT NULL,
  `air_liter` float DEFAULT NULL,
  `kalori` float DEFAULT NULL,
  `berat_kg` float DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `activities`
--

INSERT INTO `activities` (`id`, `id_user`, `tanggal`, `jam_tidur`, `durasi_olahraga`, `air_liter`, `kalori`, `berat_kg`) VALUES
(1, 1, '2025-10-12', 6.5, 45, 2.1, 2200, 65.5),
(2, 1, '2025-10-13', 7, 30, 2.3, 2100, 65.4),
(3, 1, '2025-10-14', 6, 60, 2.5, 2300, 65.2),
(4, 1, '2025-10-15', 8, 50, 2.2, 2500, 65),
(5, 1, '2025-10-16', 6.5, 40, 1.9, 2700, 65.3),
(6, 1, '2025-10-17', 7, 30, 2, 2400, 65.1),
(7, 1, '2025-10-18', 7.5, 45, 2.2, 2600, 65),
(8, 4, '2025-10-17', 10, 12, 1, 123, 45),
(9, 4, '2025-10-17', 1, 1, 1, 123, 55),
(10, 4, '2025-10-18', 4, 3, 1, 280, 55),
(11, 4, '2025-10-19', 5, 10, 1, 250, 54),
(12, 4, '2025-10-19', 8, 30, 2, 550, 57),
(13, 4, '2025-10-16', 7, 45, 3, 1650, 57),
(14, 4, '2025-10-21', 5, 10, 2, 400, 57),
(15, 4, '2025-10-28', 5, 10, 2, 1750, 55),
(16, 4, '2025-10-31', 6, 5, 1.5, 900, 56),
(17, 4, '2026-01-05', 8, 10, 4, 250, 56);

-- --------------------------------------------------------

--
-- Struktur dari tabel `foods`
--

CREATE TABLE `foods` (
  `id` int(11) NOT NULL,
  `nama_makanan` varchar(100) DEFAULT NULL,
  `kalori_per_porsi` float DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `foods`
--

INSERT INTO `foods` (`id`, `nama_makanan`, `kalori_per_porsi`) VALUES
(1, 'Nasi Putih', 200),
(2, 'Ayam Goreng', 250),
(3, 'Tempe Goreng', 150),
(4, 'Mie Goreng', 300),
(5, 'Roti Tawar', 100),
(6, 'Susu UHT', 120),
(7, 'Burger', 450),
(8, 'Sate Ayam', 350),
(9, 'Nasi Padang', 550),
(10, 'Kebab', 400),
(11, 'Martabak', 600),
(12, 'Mie Ayam', 400),
(13, 'Siomay', 250),
(14, 'Batagor', 280),
(15, 'Nasi Goreng Kampung', 500),
(16, 'Telur Gulung', 120),
(17, 'Lontong', 180),
(18, 'Roti Bakar', 300),
(19, 'Piscok (Pisang Cokelat)', 200),
(20, 'Nasi Lemak', 600),
(21, 'Sate Madura', 370),
(22, 'Nasi Goreng', 520),
(23, 'Mie Ayam', 430),
(24, 'Bakso Sapi', 350),
(25, 'Gado-Gado', 450),
(26, 'Soto Ayam', 250),
(27, 'Pecel Lele', 400),
(28, 'Nasi Uduk', 550),
(29, 'Es Campur', 220),
(30, 'Tempe Mendoan', 180),
(31, 'Bubur Ayam', 320),
(32, 'Martabak Telur', 590),
(33, 'Jus Alpukat', 300),
(34, 'Jus Jeruk', 110),
(35, 'Kopi Susu', 180),
(36, 'Teh Manis', 90),
(37, 'Air Putih', 0),
(38, 'Smoothie Pisang', 250),
(39, 'Roti Tawar', 130),
(40, 'Keripik Kentang', 200),
(41, 'Popcorn', 160),
(42, 'Cokelat Panas', 210);

-- --------------------------------------------------------

--
-- Struktur dari tabel `food_logs`
--

CREATE TABLE `food_logs` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `makanan` varchar(100) DEFAULT NULL,
  `porsi` float DEFAULT NULL,
  `kalori` float DEFAULT NULL,
  `tanggal` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `food_logs`
--

INSERT INTO `food_logs` (`id`, `user_id`, `makanan`, `porsi`, `kalori`, `tanggal`) VALUES
(1, 1, 'Nasi Putih', 1, 200, '2025-10-18'),
(2, 1, 'Ayam Goreng', 1, 250, '2025-10-18'),
(3, 1, 'Susu UHT', 1, 120, '2025-10-18'),
(4, 4, 'Tempe Goreng', 2, 300, '2025-10-17'),
(5, 4, 'Martabak', 1, 600, '2025-10-17'),
(6, 4, 'Kebab', 1, 400, '2025-10-17'),
(7, 4, 'Batagor', 1, 280, '2025-10-18'),
(8, 4, 'Siomay', 1, 250, '2025-10-19'),
(9, 4, 'Nasi Padang', 1, 550, '2025-10-19'),
(10, 4, 'Nasi Padang', 3, 1650, '2025-10-16'),
(11, 4, 'Mie Ayam', 1, 400, '2025-10-21'),
(12, 4, 'Batagor', 3, 840, '2025-10-28'),
(13, 4, 'Roti Bakar', 3, 900, '2025-10-31'),
(14, 4, 'Bubur Ayam', 1, 320, '2025-10-31');

-- --------------------------------------------------------

--
-- Struktur dari tabel `reminders`
--

CREATE TABLE `reminders` (
  `id` int(11) NOT NULL,
  `id_user` int(11) DEFAULT NULL,
  `pesan` text DEFAULT NULL,
  `tipe` enum('Harian','Mingguan') DEFAULT NULL,
  `status` enum('Aktif','Selesai') DEFAULT 'Aktif',
  `tanggal` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `reminders`
--

INSERT INTO `reminders` (`id`, `id_user`, `pesan`, `tipe`, `status`, `tanggal`) VALUES
(1, 1, 'Minum air minimal 2 liter hari ini 💧', 'Harian', 'Aktif', '2025-10-17 20:29:54'),
(2, 1, 'Rata-rata tidur minggu ini < 6 jam ⚠️', 'Mingguan', 'Aktif', '2025-10-17 20:29:54');

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id_user` int(11) NOT NULL,
  `nama` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `usia` int(11) DEFAULT NULL,
  `gender` enum('Pria','Wanita') DEFAULT NULL,
  `tinggi_cm` float DEFAULT NULL,
  `aktivitas` enum('Sedentary','Ringan','Sedang','Berat','Sangat Berat') DEFAULT NULL,
  `target_kalori` float DEFAULT 2000,
  `role` enum('user','admin') DEFAULT 'user'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id_user`, `nama`, `email`, `password`, `usia`, `gender`, `tinggi_cm`, `aktivitas`, `target_kalori`, `role`) VALUES
(1, 'Muhammad Fatiha', 'fatih@example.com', '12345', 22, 'Pria', 170, 'Sedang', 2500, 'user'),
(2, 'Rina Andriani', 'rina@example.com', '12345', 24, 'Wanita', 160, 'Ringan', 2000, 'user'),
(3, 'Budi Santoso', 'budi@example.com', '12345', 25, 'Pria', 175, 'Berat', 2800, 'user'),
(4, 'fatih', 'fatih@gmail.com', '12345', 21, 'Pria', 158, 'Sedentary', 2000, 'user'),
(6, 'Admin Health', 'admin@health.com', 'admin123', 30, 'Pria', 170, 'Sedang', 2500, 'admin');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `activities`
--
ALTER TABLE `activities`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_user` (`id_user`);

--
-- Indeks untuk tabel `foods`
--
ALTER TABLE `foods`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `food_logs`
--
ALTER TABLE `food_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indeks untuk tabel `reminders`
--
ALTER TABLE `reminders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_user` (`id_user`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id_user`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `activities`
--
ALTER TABLE `activities`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT untuk tabel `foods`
--
ALTER TABLE `foods`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

--
-- AUTO_INCREMENT untuk tabel `food_logs`
--
ALTER TABLE `food_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT untuk tabel `reminders`
--
ALTER TABLE `reminders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id_user` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `activities`
--
ALTER TABLE `activities`
  ADD CONSTRAINT `activities_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`);

--
-- Ketidakleluasaan untuk tabel `food_logs`
--
ALTER TABLE `food_logs`
  ADD CONSTRAINT `food_logs_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id_user`);

--
-- Ketidakleluasaan untuk tabel `reminders`
--
ALTER TABLE `reminders`
  ADD CONSTRAINT `reminders_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

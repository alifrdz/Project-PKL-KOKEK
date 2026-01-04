-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 01 Des 2025 pada 01.36
-- Versi server: 10.4.32-MariaDB
-- Versi PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_dolankuy`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `destinasi`
--

CREATE TABLE `destinasi` (
  `id` int(11) NOT NULL,
  `tanggal_input` datetime DEFAULT current_timestamp(),
  `kode_wisata` varchar(20) NOT NULL,
  `nama_wisata` varchar(255) NOT NULL,
  `jenis_daya_tarik` enum('Alam','Buatan','Budaya') NOT NULL,
  `no_telp` varchar(20) DEFAULT NULL,
  `deskripsi` text DEFAULT NULL,
  `sumber_link` varchar(255) DEFAULT NULL,
  `alamat` text DEFAULT NULL,
  `kabupaten_kota` varchar(100) DEFAULT NULL,
  `jam_operasional` varchar(100) DEFAULT NULL,
  `fasilitas` text DEFAULT NULL,
  `link_sosmed` varchar(255) DEFAULT NULL,
  `gambar` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `destinasi`
--

INSERT INTO `destinasi` (`id`, `tanggal_input`, `kode_wisata`, `nama_wisata`, `jenis_daya_tarik`, `no_telp`, `deskripsi`, `sumber_link`, `alamat`, `kabupaten_kota`, `jam_operasional`, `fasilitas`, `link_sosmed`, `gambar`) VALUES
(1, '2025-12-01 01:00:48', 'WST001', 'Gunung Bromo', 'Alam', '0812-3456-7890', 'Gunung Bromo adalah gunung berapi aktif yang terletak di Jawa Timur. Tempat ini terkenal dengan pemandangan matahari terbitnya yang spektakuler. Wisatawan dapat menikmati keindahan kawah dan lautan pasir yang luas.', '', 'Taman Nasional Bromo Tengger Semeru', 'Probolinggo', '00:00 - 24:00', 'Area Parkir, Toilet, Warung Makan, Rental Kuda, Viewing Point', 'instagram.com/bromo.official', 'bromo.jpg'),
(2, '2025-12-01 01:00:48', 'WST002', 'Candi Borobudur', 'Budaya', '0274-496401', 'Candi Borobudur adalah candi Buddha terbesar di dunia yang dibangun pada abad ke-8. Merupakan warisan dunia UNESCO dan menjadi destinasi wisata spiritual dan sejarah yang sangat populer.', 'https://borobudurpark.com', 'Jl. Badrawati, Borobudur', 'Magelang', '06:30 - 17:00', 'Museum, Parkir, Toilet, Kafe, Toko Souvenir, Wifi', 'instagram.com/borobudurpark', 'borobudur.jpg'),
(3, '2025-12-01 01:00:48', 'WST003', 'Pantai Kuta', 'Alam', '0361-751011', 'Pantai Kuta adalah salah satu pantai paling terkenal di Bali. Terkenal dengan ombaknya yang cocok untuk surfing, sunset yang indah, dan suasana pantai yang ramai dengan berbagai fasilitas.', 'https://balipedia.id/pantai-kuta', 'Kuta, Badung', 'Badung', '00:00 - 24:00', 'Pantai, Surfing, Restoran, Bar, Toko, Penyewaan Papan Selancar', 'instagram.com/kutabali', 'kuta.jpg');

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id`, `username`, `password`) VALUES
(1, 'admin', '0192023a7bbd73250516f069df18b500');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `destinasi`
--
ALTER TABLE `destinasi`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `kode_wisata` (`kode_wisata`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `destinasi`
--
ALTER TABLE `destinasi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

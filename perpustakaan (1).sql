-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 23 Apr 2026 pada 06.49
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
-- Database: `perpustakaan`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `buku`
--

CREATE TABLE `buku` (
  `id_buku` int(11) NOT NULL,
  `judul` varchar(255) NOT NULL,
  `penulis` varchar(255) NOT NULL,
  `penerbit` varchar(255) NOT NULL,
  `jumlah` int(11) NOT NULL DEFAULT 0,
  `lokasi_rak` varchar(100) NOT NULL,
  `detail_lainnya` text DEFAULT NULL,
  `sampul` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `buku`
--

INSERT INTO `buku` (`id_buku`, `judul`, `penulis`, `penerbit`, `jumlah`, `lokasi_rak`, `detail_lainnya`, `sampul`, `created_at`) VALUES
(1, 'Cantik Itu Luka', 'Eka Kurniawan', 'Gramedia', 4, 'A-1', NULL, 'https://images.unsplash.com/photo-1544947950-fa07a98d237f?auto=format&fit=crop&w=150&h=200&q=80', '2026-04-22 09:53:25'),
(2, 'Atomic Habits', 'James Clear', 'Gramedia', 3, 'B-2', NULL, 'https://images.unsplash.com/photo-1589829085413-56de8ae18c73?auto=format&fit=crop&w=150&h=200&q=80', '2026-04-22 09:53:25'),
(4, 'Khoirur Rooziqin', 'Nasrullah', 'Mizan', 0, 'D-4', NULL, 'https://images.unsplash.com/photo-1614113489855-66422ad300a4?auto=format&fit=crop&w=150&h=200&q=80', '2026-04-22 09:53:25'),
(6, 'MBG', 'alfii', 'friendly', 97, 'b2', '', 'assets/uploads/book_69e89e0fc28840.54646660.png', '2026-04-22 10:08:15'),
(8, 'Zikri Sang Pencipta', 'hanntuud', 'detik.com', 12, 'b5', '', 'assets/uploads/book_69e8d2b00a9641.57685911.png', '2026-04-22 13:52:48');

-- --------------------------------------------------------

--
-- Struktur dari tabel `favorit`
--

CREATE TABLE `favorit` (
  `id_favorit` int(11) NOT NULL,
  `id_user` int(12) NOT NULL,
  `id_buku` int(11) NOT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `favorit`
--

INSERT INTO `favorit` (`id_favorit`, `id_user`, `id_buku`, `created_at`) VALUES
(1, 8, 8, '2026-04-22 21:13:28'),
(2, 7, 6, '2026-04-22 21:35:35');

-- --------------------------------------------------------

--
-- Struktur dari tabel `notifikasi`
--

CREATE TABLE `notifikasi` (
  `id_notif` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `judul` varchar(255) NOT NULL,
  `pesan` text NOT NULL,
  `is_read` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `notifikasi`
--

INSERT INTO `notifikasi` (`id_notif`, `id_user`, `judul`, `pesan`, `is_read`, `created_at`) VALUES
(1, 7, 'Peminjaman Berhasil!', 'Anda baru saja meminjam buku. Jangan lupa kembalikan tepat waktu ya!', 1, '2026-04-22 14:49:20'),
(2, 9, 'Ada Peminjaman Baru', 'Seorang pelanggan baru saja meminjam buku. Silakan cek dasbor pantauan.', 1, '2026-04-22 14:49:20'),
(3, 10, 'Ada Peminjaman Baru', 'Seorang pelanggan baru saja meminjam buku. Silakan cek dasbor pantauan.', 0, '2026-04-22 14:49:20'),
(4, 7, 'Peminjaman Berhasil!', 'Anda baru saja meminjam buku. Jangan lupa kembalikan tepat waktu ya!', 1, '2026-04-22 15:00:37'),
(5, 9, 'Ada Peminjaman Baru', 'Seorang pelanggan baru saja meminjam buku. Silakan cek dasbor pantauan.', 1, '2026-04-22 15:00:37'),
(6, 10, 'Ada Peminjaman Baru', 'Seorang pelanggan baru saja meminjam buku. Silakan cek dasbor pantauan.', 0, '2026-04-22 15:00:37'),
(7, 7, 'Peminjaman Berhasil!', 'Anda baru saja meminjam buku. Jangan lupa kembalikan tepat waktu ya!', 1, '2026-04-22 15:05:41'),
(8, 9, 'Ada Peminjaman Baru', 'Seorang pelanggan baru saja meminjam buku. Silakan cek dasbor pantauan.', 1, '2026-04-22 15:05:41'),
(9, 10, 'Ada Peminjaman Baru', 'Seorang pelanggan baru saja meminjam buku. Silakan cek dasbor pantauan.', 0, '2026-04-22 15:05:41'),
(10, 7, 'Peminjaman Berhasil!', 'Anda baru saja meminjam buku. Jangan lupa kembalikan tepat waktu ya!', 1, '2026-04-22 15:05:44'),
(11, 9, 'Ada Peminjaman Baru', 'Seorang pelanggan baru saja meminjam buku. Silakan cek dasbor pantauan.', 1, '2026-04-22 15:05:44'),
(12, 10, 'Ada Peminjaman Baru', 'Seorang pelanggan baru saja meminjam buku. Silakan cek dasbor pantauan.', 0, '2026-04-22 15:05:44'),
(13, 7, 'Pengembalian Selesai', 'Terima kasih! Buku Anda telah berhasil dikembalikan ke sistem.', 1, '2026-04-22 15:05:51'),
(14, 9, 'Buku Telah Dikembalikan', 'Seorang pelanggan baru saja mengembalikan buku. Stok telah diperbarui otomatis.', 1, '2026-04-22 15:05:51'),
(15, 10, 'Buku Telah Dikembalikan', 'Seorang pelanggan baru saja mengembalikan buku. Stok telah diperbarui otomatis.', 0, '2026-04-22 15:05:51'),
(16, 7, 'Pengembalian Selesai', 'Terima kasih! Buku Anda telah berhasil dikembalikan ke sistem.', 1, '2026-04-22 15:05:54'),
(17, 9, 'Buku Telah Dikembalikan', 'Seorang pelanggan baru saja mengembalikan buku. Stok telah diperbarui otomatis.', 1, '2026-04-22 15:05:54'),
(18, 10, 'Buku Telah Dikembalikan', 'Seorang pelanggan baru saja mengembalikan buku. Stok telah diperbarui otomatis.', 0, '2026-04-22 15:05:54'),
(19, 7, 'Pengembalian Selesai', 'Terima kasih! Buku Anda telah berhasil dikembalikan ke sistem.', 1, '2026-04-22 15:05:57'),
(20, 9, 'Buku Telah Dikembalikan', 'Seorang pelanggan baru saja mengembalikan buku. Stok telah diperbarui otomatis.', 1, '2026-04-22 15:05:57'),
(21, 10, 'Buku Telah Dikembalikan', 'Seorang pelanggan baru saja mengembalikan buku. Stok telah diperbarui otomatis.', 0, '2026-04-22 15:05:57'),
(22, 7, 'Peminjaman Berhasil!', 'Anda baru saja meminjam buku. Jangan lupa kembalikan tepat waktu ya!', 1, '2026-04-22 18:31:25'),
(23, 9, 'Ada Peminjaman Baru', 'Seorang pelanggan baru saja meminjam buku. Silakan cek dasbor pantauan.', 1, '2026-04-22 18:31:25'),
(24, 10, 'Ada Peminjaman Baru', 'Seorang pelanggan baru saja meminjam buku. Silakan cek dasbor pantauan.', 0, '2026-04-22 18:31:25'),
(25, 7, 'Pengembalian Selesai', 'Terima kasih! Buku Anda telah berhasil dikembalikan ke sistem.', 1, '2026-04-22 18:31:38'),
(26, 9, 'Buku Telah Dikembalikan', 'Seorang pelanggan baru saja mengembalikan buku. Stok telah diperbarui otomatis.', 1, '2026-04-22 18:31:38'),
(27, 10, 'Buku Telah Dikembalikan', 'Seorang pelanggan baru saja mengembalikan buku. Stok telah diperbarui otomatis.', 0, '2026-04-22 18:31:38');

-- --------------------------------------------------------

--
-- Struktur dari tabel `peminjaman`
--

CREATE TABLE `peminjaman` (
  `id_pinjam` int(11) NOT NULL,
  `id_user` int(12) NOT NULL,
  `id_buku` int(11) NOT NULL,
  `tanggal_pinjam` datetime DEFAULT current_timestamp(),
  `tanggal_kembali` datetime DEFAULT NULL,
  `status` enum('dipinjam','dikembalikan') DEFAULT 'dipinjam'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `peminjaman`
--

INSERT INTO `peminjaman` (`id_pinjam`, `id_user`, `id_buku`, `tanggal_pinjam`, `tanggal_kembali`, `status`) VALUES
(1, 7, 6, '2026-04-22 17:52:55', '2026-04-22 20:54:02', 'dikembalikan'),
(2, 7, 8, '2026-04-22 21:01:33', '2026-04-22 21:32:26', 'dikembalikan'),
(3, 7, 1, '2026-04-22 21:35:30', '2026-04-22 22:05:54', 'dikembalikan'),
(4, 7, 6, '2026-04-22 21:49:20', '2026-04-22 22:00:47', 'dikembalikan'),
(5, 7, 6, '2026-04-22 22:00:37', '2026-04-22 22:01:20', 'dikembalikan'),
(6, 7, 6, '2026-04-22 22:05:41', '2026-04-22 22:05:57', 'dikembalikan'),
(7, 7, 8, '2026-04-22 22:05:44', '2026-04-22 22:05:51', 'dikembalikan'),
(8, 7, 8, '2026-04-23 01:31:25', '2026-04-23 01:31:38', 'dikembalikan');

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id_user` int(12) NOT NULL,
  `email` varchar(64) NOT NULL,
  `username` varchar(80) NOT NULL,
  `password` varchar(255) NOT NULL,
  `nama` varchar(64) DEFAULT NULL,
  `role` varchar(50) DEFAULT 'pelanggan',
  `foto_profil` varchar(255) DEFAULT NULL,
  `social_id` varchar(255) DEFAULT NULL,
  `social_provider` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id_user`, `email`, `username`, `password`, `nama`, `role`, `foto_profil`, `social_id`, `social_provider`) VALUES
(7, 'syahrulstg@gmail.com', 'alfi', '$2y$10$gdSLS7.HlClF/mKgcaoz2.uXVa/rsX219B.2UjStgyiMmy6MCFPr.', 'Syahrul Alfiansyah ', 'pelanggan', 'assets/uploads/profile/prof_7_1776855655.jpg', NULL, NULL),
(8, 'asek@asek', 'saya', '$2y$10$eBaT.fcBNFG0wdY4vebeaOXp5qDWspwZ27aLexRe3uPFXaQ75D9hm', NULL, 'manajer', NULL, NULL, NULL),
(9, '123@qer', 'han', '$2y$10$tdg0l8E6lQxRPQuSzewCy.Firvibo532a/VfxUO78MbYtIXTXxxBe', NULL, 'karyawan', NULL, NULL, NULL),
(10, 'lekku@gmail', 'lekk', '$2y$10$j0e4TBSj1zvDnz4Bu/6MoO4RGxU7riWB68s18f/4B.SUabNfIFtI.', NULL, 'karyawan', NULL, NULL, NULL);

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `buku`
--
ALTER TABLE `buku`
  ADD PRIMARY KEY (`id_buku`);

--
-- Indeks untuk tabel `favorit`
--
ALTER TABLE `favorit`
  ADD PRIMARY KEY (`id_favorit`),
  ADD KEY `id_user` (`id_user`),
  ADD KEY `id_buku` (`id_buku`);

--
-- Indeks untuk tabel `notifikasi`
--
ALTER TABLE `notifikasi`
  ADD PRIMARY KEY (`id_notif`);

--
-- Indeks untuk tabel `peminjaman`
--
ALTER TABLE `peminjaman`
  ADD PRIMARY KEY (`id_pinjam`),
  ADD KEY `id_user` (`id_user`),
  ADD KEY `id_buku` (`id_buku`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id_user`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `buku`
--
ALTER TABLE `buku`
  MODIFY `id_buku` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT untuk tabel `favorit`
--
ALTER TABLE `favorit`
  MODIFY `id_favorit` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT untuk tabel `notifikasi`
--
ALTER TABLE `notifikasi`
  MODIFY `id_notif` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT untuk tabel `peminjaman`
--
ALTER TABLE `peminjaman`
  MODIFY `id_pinjam` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id_user` int(12) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `favorit`
--
ALTER TABLE `favorit`
  ADD CONSTRAINT `favorit_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`) ON DELETE CASCADE,
  ADD CONSTRAINT `favorit_ibfk_2` FOREIGN KEY (`id_buku`) REFERENCES `buku` (`id_buku`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `peminjaman`
--
ALTER TABLE `peminjaman`
  ADD CONSTRAINT `peminjaman_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`) ON DELETE CASCADE,
  ADD CONSTRAINT `peminjaman_ibfk_2` FOREIGN KEY (`id_buku`) REFERENCES `buku` (`id_buku`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

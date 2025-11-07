-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 07, 2025 at 02:05 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `perpustakaanbaru`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id_admin` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id_admin`, `username`, `password`, `created_at`) VALUES
(1, 'admin', '349de9978efb9b04252f186a7351033e', '2025-05-31 05:41:30');

-- --------------------------------------------------------

--
-- Table structure for table `buku`
--

CREATE TABLE `buku` (
  `id_buku` int(11) NOT NULL,
  `judul_buku` varchar(255) NOT NULL,
  `penulis` varchar(100) NOT NULL,
  `tahun_terbit` year(4) NOT NULL,
  `genre` varchar(50) DEFAULT NULL,
  `deskripsi` varchar(255) NOT NULL,
  `stok` int(11) NOT NULL,
  `tanggal_ditambahkan` date NOT NULL,
  `cover` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `buku`
--

INSERT INTO `buku` (`id_buku`, `judul_buku`, `penulis`, `tahun_terbit`, `genre`, `deskripsi`, `stok`, `tanggal_ditambahkan`, `cover`) VALUES
(16, 'Sejarah', 'Sari Oktafiana', '2021', 'Mata Pelajaran Kelas 10', 'Buku Sejarah untuk Siswa/Siswi SMA Kelas 10', 2, '2025-07-03', 'cover_688061f5b46f59.16126968.jpg'),
(19, 'Pendidikan Pancasila dan Kewarganegaraan', 'Abdul Waidi, dkk.', '2021', 'Mata Pelajaran Kelas 10', 'Buku Pendidikan Pancasila dan Kewarganegaraan untuk Siswa/Siswi SMA Kelas 10', 73, '2025-07-13', 'cover_688062c3275337.44838312.jpg'),
(20, 'Cerdas Cergas : Berbahasa dan Bersastra Indonesia', 'Fadhilah Tri Aulia, dkk', '2021', 'Mata Pelajaran Kelas 10', 'Buku Pelajaran Cerdas Cegar untuk Siswa/Siswi SMA Kelas 10', 2, '2025-07-13', 'cover_68806526342600.10023620.jpg'),
(21, 'Matematika', 'Dicky Sustanto, dkk', '2021', 'Mata Pelajaran Kelas 10', 'Buku Pelajaran Matematika untuk Siswa/Siswi SMA Kelas 10', 2, '2025-07-13', 'cover_6880656ca73d17.71033149.jpg'),
(22, 'Informatika', 'Mushthofa, dkk', '2021', 'Mata Pelajaran Kelas 10', 'Buku Pelajaran Informatika untuk Siswa/Siswi SMA Kelas 10', 300, '2025-07-13', 'cover_68806647a26358.25338957.jpg'),
(23, 'Ilmu Pengetahuan Alam', 'Ayuk Ratna Puspaningsih, dkk', '2021', 'Mata Pelajaran Kelas 10', 'Buku Pelajaran Ilmu Pengetahuan Alam untuk Siswa/Siswi SMA Kelas 10', 233, '2025-07-13', 'cover_688066e37d9fc3.17291831.jpg'),
(24, 'Ilmu Pengetahuan Sosial', 'Sari Oktafiana, dkk', '2021', 'Mata Pelajaran Kelas 10', 'Buku Ilmu Pengetahuan Sosial untuk Siswa/Siswi SMA Kelas 10', 241, '2025-07-23', 'cover_688067bdbfe805.56152218.jpg'),
(25, 'Pendidikan Agama Islam dan Budi Pekerti', 'Ahmad Taufik, dkk', '2021', 'Mata Pelajaran Kelas 10', 'Buku Pendidikan Agama Islam dan Budi Pekerti untuk Siswa/Siswi SMA Kelas 10', 2, '2025-07-23', 'cover_68806861ddd275.75545815.png'),
(26, 'Pendidikan Pancasila dan Kewarganegaraan', 'Tedi Kholiludin, dkk', '2021', 'Mata Pelajaran Kelas 11', 'Buku Pendidikan Pancasila dan Kewarganegaraan untuk Siswa/Siswi SMA Kelas 11', 242, '2025-07-23', 'cover_68806ae23c0a18.68120227.jpg'),
(27, 'Sejarah', 'Martina Safitry, dkk', '2021', 'Mata Pelajaran Kelas 11', 'Buku Pelajaran Sejarah untuk Siswa/Siswi SMA Kelas 11', 251, '2025-07-23', 'cover_68806c8dca1fd2.59133489.jpg'),
(28, 'Informatika', 'Auzi Asfarian, dkk', '2021', 'Mata Pelajaran Kelas 11', 'Buku Pelajaran Informatika untuk Siswa/Siswi SMA Kelas 11', 40, '2025-07-23', 'cover_68806d4d406291.23595320.jpg'),
(29, 'Pendidikan Agama Islam dan Budi Pekerti', 'Abd. Rahman, dkk', '2021', 'Mata Pelajaran Kelas 11', 'Buku Pendidikan Agama Islam dan Budi Pekerti untuk Siswa/Siswi SMA Kelas 11', 180, '2025-07-23', 'cover_68806ddf53ec43.71994635.jpg'),
(30, 'Matematika', 'Dicky Sustanto, dkk', '2021', 'Mata Pelajaran Kelas 11', 'Buku Matematika untuk Siswa/Siswi SMA Kelas 11', 2, '2025-07-23', 'cover_68806e6e29d508.16644414.jpg'),
(31, 'Ekonomi', 'Yeni Fitriani, dkk', '2022', 'Mata Pelajaran Kelas 11', 'Buku Pelajaran Ekonomi untuk Siswa/Siswi SMA Kelas 11', 41, '2025-07-23', 'cover_6880700bb85898.24446927.jpg'),
(32, 'Bahasa Indonesia Tingkat Lanjut : Cakap Berbahasa dan Bersastra Indonesia', 'Rahmah Purwahida, dkk', '2021', 'Mata Pelajaran Kelas 11', 'Buku Pelajaran Bahasa Indonesia untuk Siswa/Siswi SMA Kelas 11', 82, '2025-07-23', 'cover_6880709597e566.78643583.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `rekap_peminjaman_siswa`
--

CREATE TABLE `rekap_peminjaman_siswa` (
  `id_rekap_siswa` int(11) NOT NULL,
  `id_buku` int(11) NOT NULL,
  `nama_peminjam` varchar(100) NOT NULL,
  `no_telpon` varchar(15) NOT NULL,
  `kelas` varchar(10) NOT NULL,
  `judul_buku` varchar(255) NOT NULL,
  `tgl_pinjam` date NOT NULL,
  `status_pengembalian` enum('0','1') DEFAULT '0' COMMENT '0: Belum Dikembalikan, 1: Sudah Dikembalikan'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `rekap_peminjaman_staff_guru`
--

CREATE TABLE `rekap_peminjaman_staff_guru` (
  `id_rekap_staff_guru` int(11) NOT NULL,
  `id_buku` int(11) NOT NULL,
  `nama_peminjam` varchar(100) NOT NULL,
  `nig` varchar(20) NOT NULL,
  `judul_buku` varchar(255) NOT NULL,
  `role_peminjam` enum('staff','guru') NOT NULL,
  `tgl_pinjam` date NOT NULL,
  `status_pengembalian` enum('0','1') DEFAULT '0' COMMENT '0: Belum Dikembalikan, 1: Sudah Dikembalikan'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `rekap_peminjaman_tamu`
--

CREATE TABLE `rekap_peminjaman_tamu` (
  `id_rekap_tamu` int(11) NOT NULL,
  `nama_peminjam` varchar(100) NOT NULL,
  `notelpon` varchar(20) NOT NULL,
  `id_buku` int(50) NOT NULL,
  `judul_buku` varchar(255) NOT NULL,
  `keperluan` text DEFAULT NULL,
  `tgl_pinjam` date NOT NULL,
  `status_pengembalian` enum('0','1') DEFAULT '0' COMMENT '0: Belum Dikembalikan, 1: Sudah Dikembalikan'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `siswa`
--

CREATE TABLE `siswa` (
  `id_siswa` int(11) NOT NULL,
  `password` varchar(50) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `nisn` varchar(15) NOT NULL,
  `no_telp` varchar(20) NOT NULL,
  `kelas` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `siswa`
--

INSERT INTO `siswa` (`id_siswa`, `password`, `nama`, `nisn`, `no_telp`, `kelas`) VALUES
(1, 'bagus123', 'bagus', '10092789289', '088097798798', '10 IPA 1'),
(3, 'akbar123', 'akbar', '221011400864', '088097798798', '12 IPA 2');

-- --------------------------------------------------------

--
-- Table structure for table `staff_guru`
--

CREATE TABLE `staff_guru` (
  `id_guru` int(10) NOT NULL,
  `password` varchar(50) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `nig` varchar(20) NOT NULL,
  `role` enum('staff','guru') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `staff_guru`
--

INSERT INTO `staff_guru` (`id_guru`, `password`, `nama`, `nig`, `role`) VALUES
(1, 'fathan123', 'fathan', '10092789289', 'guru');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id_admin`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `buku`
--
ALTER TABLE `buku`
  ADD PRIMARY KEY (`id_buku`);

--
-- Indexes for table `rekap_peminjaman_siswa`
--
ALTER TABLE `rekap_peminjaman_siswa`
  ADD PRIMARY KEY (`id_rekap_siswa`),
  ADD KEY `rekap_peminjaman_siswa_ibfk_1` (`id_buku`);

--
-- Indexes for table `rekap_peminjaman_staff_guru`
--
ALTER TABLE `rekap_peminjaman_staff_guru`
  ADD PRIMARY KEY (`id_rekap_staff_guru`),
  ADD KEY `id_buku` (`id_buku`);

--
-- Indexes for table `rekap_peminjaman_tamu`
--
ALTER TABLE `rekap_peminjaman_tamu`
  ADD PRIMARY KEY (`id_rekap_tamu`);

--
-- Indexes for table `siswa`
--
ALTER TABLE `siswa`
  ADD PRIMARY KEY (`id_siswa`);

--
-- Indexes for table `staff_guru`
--
ALTER TABLE `staff_guru`
  ADD PRIMARY KEY (`id_guru`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id_admin` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `buku`
--
ALTER TABLE `buku`
  MODIFY `id_buku` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT for table `rekap_peminjaman_siswa`
--
ALTER TABLE `rekap_peminjaman_siswa`
  MODIFY `id_rekap_siswa` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `rekap_peminjaman_staff_guru`
--
ALTER TABLE `rekap_peminjaman_staff_guru`
  MODIFY `id_rekap_staff_guru` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `rekap_peminjaman_tamu`
--
ALTER TABLE `rekap_peminjaman_tamu`
  MODIFY `id_rekap_tamu` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `siswa`
--
ALTER TABLE `siswa`
  MODIFY `id_siswa` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `staff_guru`
--
ALTER TABLE `staff_guru`
  MODIFY `id_guru` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `rekap_peminjaman_siswa`
--
ALTER TABLE `rekap_peminjaman_siswa`
  ADD CONSTRAINT `rekap_peminjaman_siswa_ibfk_1` FOREIGN KEY (`id_buku`) REFERENCES `buku` (`id_buku`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `rekap_peminjaman_staff_guru`
--
ALTER TABLE `rekap_peminjaman_staff_guru`
  ADD CONSTRAINT `rekap_peminjaman_staff_guru_ibfk_2` FOREIGN KEY (`id_buku`) REFERENCES `buku` (`id_buku`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

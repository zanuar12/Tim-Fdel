-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jun 02, 2026 at 12:06 PM
-- Server version: 8.0.30
-- PHP Version: 8.3.31

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_iklim`
--
CREATE DATABASE IF NOT EXISTS `db_iklim` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci;
USE `db_iklim`;

-- --------------------------------------------------------

--
-- Table structure for table `berita`
--

CREATE TABLE `berita` (
  `id_berita` int NOT NULL,
  `judul` varchar(255) NOT NULL,
  `konten` text NOT NULL,
  `gambar` varchar(255) DEFAULT NULL,
  `penulis_id` int NOT NULL,
  `tanggal_publikasi` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `berita`
--

INSERT INTO `berita` (`id_berita`, `judul`, `konten`, `gambar`, `penulis_id`, `tanggal_publikasi`) VALUES
(1, 'Kenaikan Suhu Global Tembus Batas Kritis', 'Laporan iklim terbaru menunjukkan bahwa ambang batas suhu global terus mendekati titik yang mengkhawatirkan akibat emisi karbon yang tidak terkendali.', NULL, 1, '2026-06-01 10:29:27'),
(2, 'Reboisasi Mangrove di Pesisir Jawa', 'Komunitas lokal berhasil menanam puluhan ribu bibit mangrove sebagai benteng alami terhadap abrasi dan kenaikan permukaan air laut.', NULL, 1, '2026-06-01 10:29:27'),
(3, 'Kenaikan Suhu Global Tembus Batas Kritis 1.5 Derajat', 'Laporan iklim terbaru dari WMO menunjukkan bahwa ambang batas suhu global terus mendekati titik yang mengkhawatirkan akibat emisi karbon yang tidak terkendali. Negara-negara industri didesak untuk mempercepat transisi energi mereka.', NULL, 1, '2026-06-01 11:33:13'),
(4, 'Reboisasi Hutan Mangrove di Pesisir Utara Jawa', 'Komunitas lokal bersama LSM lingkungan berhasil menanam puluhan ribu bibit mangrove sebagai benteng alami terhadap abrasi dan kenaikan permukaan air laut. Program ini diharapkan dapat memulihkan ekosistem laut dangkal.', NULL, 1, '2026-06-01 11:33:13'),
(5, 'Penemuan Teknologi Baterai Garam Bebas Lithium', 'Ilmuwan material merilis prototipe baterai skala grid menggunakan natrium (garam) yang jauh lebih ramah lingkungan dan murah dibandingkan tambang lithium, membuka jalan bagi penyimpanan energi surya yang lebih masif.', NULL, 1, '2026-06-01 11:33:13');

-- --------------------------------------------------------

--
-- Table structure for table `userr`
--

CREATE TABLE `userr` (
  `userid` int NOT NULL,
  `username` varchar(100) NOT NULL,
  `useremail` varchar(100) NOT NULL,
  `userpass` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `userr`
--

INSERT INTO `userr` (`userid`, `username`, `useremail`, `userpass`, `created_at`) VALUES
(1, 'DEWA CUACA', 'admin@iklim.id', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '2026-06-01 10:19:50'),
(2, 'reviewer', 'reviewer@iklim.id', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '2026-06-01 11:33:13'),
(3, 'editor', 'editor@iklim.id', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '2026-06-01 11:33:13');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `berita`
--
ALTER TABLE `berita`
  ADD PRIMARY KEY (`id_berita`),
  ADD KEY `penulis_id` (`penulis_id`);

--
-- Indexes for table `userr`
--
ALTER TABLE `userr`
  ADD PRIMARY KEY (`userid`),
  ADD UNIQUE KEY `useremail` (`useremail`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `berita`
--
ALTER TABLE `berita`
  MODIFY `id_berita` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `userr`
--
ALTER TABLE `userr`
  MODIFY `userid` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `berita`
--
ALTER TABLE `berita`
  ADD CONSTRAINT `berita_ibfk_1` FOREIGN KEY (`penulis_id`) REFERENCES `userr` (`userid`) ON DELETE CASCADE;
--
-- Database: `db_indofood`
--
CREATE DATABASE IF NOT EXISTS `db_indofood` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `db_indofood`;

-- --------------------------------------------------------

--
-- Table structure for table `arus_kas`
--

CREATE TABLE `arus_kas` (
  `id` int NOT NULL,
  `laporan_id` int NOT NULL,
  `penerimaan_dari_pelanggan` decimal(10,0) DEFAULT NULL,
  `pembayaran_kepada_pemasok` decimal(10,0) DEFAULT NULL,
  `pembayaran_beban_produksi` decimal(10,0) DEFAULT NULL,
  `pembayaran_kepada_karyawan` decimal(10,0) DEFAULT NULL,
  `kas_dari_operasi` decimal(10,0) GENERATED ALWAYS AS ((((ifnull(`penerimaan_dari_pelanggan`,0) + ifnull(`pembayaran_kepada_pemasok`,0)) + ifnull(`pembayaran_beban_produksi`,0)) + ifnull(`pembayaran_kepada_karyawan`,0))) STORED,
  `penerimaan_bunga` decimal(10,0) DEFAULT NULL,
  `pembayaran_pajak` decimal(10,0) DEFAULT NULL,
  `pembayaran_beban_keuangan` decimal(10,0) DEFAULT NULL,
  `penerimaan_pembayaran_lain` decimal(10,0) DEFAULT NULL,
  `kas_neto_operasi` decimal(10,0) GENERATED ALWAYS AS (((((`kas_dari_operasi` + ifnull(`penerimaan_bunga`,0)) + ifnull(`pembayaran_pajak`,0)) + ifnull(`pembayaran_beban_keuangan`,0)) + ifnull(`penerimaan_pembayaran_lain`,0))) STORED,
  `penjualan_aset_tetap` decimal(10,0) DEFAULT NULL,
  `akuisisi_dari_nonpengendali` decimal(10,0) DEFAULT NULL,
  `penambahan_aset_biologis` decimal(10,0) DEFAULT NULL,
  `penambahan_investasi_jangka_pendek` decimal(10,0) DEFAULT NULL,
  `penambahan_investasi_ventura` decimal(10,0) DEFAULT NULL,
  `penambahan_investasi_jangka_panjang` decimal(10,0) DEFAULT NULL,
  `penambahan_aset_tetap` decimal(10,0) DEFAULT NULL,
  `kas_neto_investasi` decimal(10,0) GENERATED ALWAYS AS (((((((ifnull(`penjualan_aset_tetap`,0) + ifnull(`akuisisi_dari_nonpengendali`,0)) + ifnull(`penambahan_aset_biologis`,0)) + ifnull(`penambahan_investasi_jangka_pendek`,0)) + ifnull(`penambahan_investasi_ventura`,0)) + ifnull(`penambahan_investasi_jangka_panjang`,0)) + ifnull(`penambahan_aset_tetap`,0))) STORED,
  `penerimaan_utang_bank_jangka_pendek` decimal(10,0) DEFAULT NULL,
  `penerimaan_utang_bank_jangka_panjang` decimal(10,0) DEFAULT NULL,
  `penerimaan_setoran_modal_nonpengendali` decimal(10,0) DEFAULT NULL,
  `pembayaran_pinjaman_pihak_berelasi` decimal(10,0) DEFAULT NULL,
  `pembayaran_liabilitas_sewa` decimal(10,0) DEFAULT NULL,
  `pembayaran_dividen_nonpengendali` decimal(10,0) DEFAULT NULL,
  `pembayaran_dividen_kas` decimal(10,0) DEFAULT NULL,
  `pembayaran_utang_bank_jangka_panjang` decimal(10,0) DEFAULT NULL,
  `pembayaran_utang_bank_jangka_pendek` decimal(10,0) DEFAULT NULL,
  `kas_neto_pendanaan` decimal(10,0) GENERATED ALWAYS AS (((((((((ifnull(`penerimaan_utang_bank_jangka_pendek`,0) + ifnull(`penerimaan_utang_bank_jangka_panjang`,0)) + ifnull(`penerimaan_setoran_modal_nonpengendali`,0)) + ifnull(`pembayaran_pinjaman_pihak_berelasi`,0)) + ifnull(`pembayaran_liabilitas_sewa`,0)) + ifnull(`pembayaran_dividen_nonpengendali`,0)) + ifnull(`pembayaran_dividen_kas`,0)) + ifnull(`pembayaran_utang_bank_jangka_panjang`,0)) + ifnull(`pembayaran_utang_bank_jangka_pendek`,0))) STORED,
  `dampak_perubahan_kurs` decimal(10,0) DEFAULT NULL,
  `kenaikan_neto_kas` decimal(10,0) GENERATED ALWAYS AS ((((`kas_neto_operasi` + `kas_neto_investasi`) + `kas_neto_pendanaan`) + ifnull(`dampak_perubahan_kurs`,0))) STORED,
  `kas_awal_tahun` decimal(10,0) DEFAULT NULL,
  `kas_akhir_tahun` decimal(10,0) GENERATED ALWAYS AS ((ifnull(`kas_awal_tahun`,0) + `kenaikan_neto_kas`)) STORED
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `arus_kas`
--

INSERT INTO `arus_kas` (`id`, `laporan_id`, `penerimaan_dari_pelanggan`, `pembayaran_kepada_pemasok`, `pembayaran_beban_produksi`, `pembayaran_kepada_karyawan`, `penerimaan_bunga`, `pembayaran_pajak`, `pembayaran_beban_keuangan`, `penerimaan_pembayaran_lain`, `penjualan_aset_tetap`, `akuisisi_dari_nonpengendali`, `penambahan_aset_biologis`, `penambahan_investasi_jangka_pendek`, `penambahan_investasi_ventura`, `penambahan_investasi_jangka_panjang`, `penambahan_aset_tetap`, `penerimaan_utang_bank_jangka_pendek`, `penerimaan_utang_bank_jangka_panjang`, `penerimaan_setoran_modal_nonpengendali`, `pembayaran_pinjaman_pihak_berelasi`, `pembayaran_liabilitas_sewa`, `pembayaran_dividen_nonpengendali`, `pembayaran_dividen_kas`, `pembayaran_utang_bank_jangka_panjang`, `pembayaran_utang_bank_jangka_pendek`, `dampak_perubahan_kurs`, `kas_awal_tahun`) VALUES
(1, 1, '115014921', '-58059224', '-22001700', '-10404798', '1753911', '-5172128', '-3840151', '217125', '157873', '-38376', '-272326', '-1401539', '-23500', '0', '-5417096', '29578782', '1777184', '377331', '-146470', '-384298', '-2014297', '-2344374', '-2240709', '-25283357', '301304', '28575968'),
(2, 2, '122602872', '-60170820', '-22420901', '-10978904', '1643976', '-5019163', '-4116036', '-2000295', '39018', '-149079', '-306346', '-322537', '0', '-2000000', '-5295206', '30832827', '4823840', '420191', '-298344', '-634994', '-2164538', '-2458520', '-3182234', '-30694751', '610593', '38710056');

-- --------------------------------------------------------

--
-- Table structure for table `laba_rugi`
--

CREATE TABLE `laba_rugi` (
  `id` int NOT NULL,
  `laporan_id` int NOT NULL,
  `penjualan_neto` decimal(10,0) DEFAULT NULL,
  `beban_pokok_penjualan` decimal(10,0) DEFAULT NULL,
  `laba_bruto` decimal(10,0) GENERATED ALWAYS AS ((ifnull(`penjualan_neto`,0) - ifnull(`beban_pokok_penjualan`,0))) STORED,
  `beban_penjualan_distribusi` decimal(10,0) DEFAULT NULL,
  `beban_umum_administrasi` decimal(10,0) DEFAULT NULL,
  `laba_rugi_nilai_wajar_aset_biologis` decimal(10,0) DEFAULT NULL,
  `penghasilan_operasi_lain` decimal(10,0) DEFAULT NULL,
  `beban_operasi_lain` decimal(10,0) DEFAULT NULL,
  `laba_usaha` decimal(10,0) GENERATED ALWAYS AS ((((((`laba_bruto` + ifnull(`beban_penjualan_distribusi`,0)) + ifnull(`beban_umum_administrasi`,0)) + ifnull(`laba_rugi_nilai_wajar_aset_biologis`,0)) + ifnull(`penghasilan_operasi_lain`,0)) + ifnull(`beban_operasi_lain`,0))) STORED,
  `penghasilan_keuangan` decimal(10,0) DEFAULT NULL,
  `beban_keuangan` decimal(10,0) DEFAULT NULL,
  `pajak_final_bunga` decimal(10,0) DEFAULT NULL,
  `bagian_laba_rugi_asosiasi` decimal(10,0) DEFAULT NULL,
  `laba_sebelum_pajak` decimal(10,0) GENERATED ALWAYS AS (((((`laba_usaha` + ifnull(`penghasilan_keuangan`,0)) + ifnull(`beban_keuangan`,0)) + ifnull(`pajak_final_bunga`,0)) + ifnull(`bagian_laba_rugi_asosiasi`,0))) STORED,
  `beban_pajak_penghasilan` decimal(10,0) DEFAULT NULL,
  `laba_tahun_berjalan` decimal(10,0) GENERATED ALWAYS AS ((`laba_sebelum_pajak` + ifnull(`beban_pajak_penghasilan`,0))) STORED,
  `laba_komprehensif_lain` decimal(10,0) DEFAULT NULL,
  `total_laba_komprehensif` decimal(10,0) GENERATED ALWAYS AS ((`laba_tahun_berjalan` + ifnull(`laba_komprehensif_lain`,0))) STORED,
  `laba_attributable_induk` decimal(10,0) DEFAULT NULL,
  `laba_attributable_nonpengendali` decimal(10,0) DEFAULT NULL,
  `eps_dasar` decimal(10,0) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `laba_rugi`
--

INSERT INTO `laba_rugi` (`id`, `laporan_id`, `penjualan_neto`, `beban_pokok_penjualan`, `beban_penjualan_distribusi`, `beban_umum_administrasi`, `laba_rugi_nilai_wajar_aset_biologis`, `penghasilan_operasi_lain`, `beban_operasi_lain`, `penghasilan_keuangan`, `beban_keuangan`, `pajak_final_bunga`, `bagian_laba_rugi_asosiasi`, `beban_pajak_penghasilan`, `laba_komprehensif_lain`, `laba_attributable_induk`, `laba_attributable_nonpengendali`, `eps_dasar`) VALUES
(1, 1, '115786525', '75649996', '-12258278', '-5048503', '317747', '1187010', '-1246321', '1773991', '-6192226', '-268559', '-1361608', '-3962286', '-531389', '8641612', '4435884', '984'),
(2, 2, '123493214', '82300859', '-12591578', '-5244754', '-135498', '1993298', '-644686', '1662967', '-5973876', '-247908', '27375', '-4481314', '39530', '10684653', '4871728', '1217');

-- --------------------------------------------------------

--
-- Table structure for table `laporan_keuangan`
--

CREATE TABLE `laporan_keuangan` (
  `id` int NOT NULL,
  `perusahaan_id` int NOT NULL,
  `tahun` year NOT NULL,
  `tanggal_laporan` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `laporan_keuangan`
--

INSERT INTO `laporan_keuangan` (`id`, `perusahaan_id`, `tahun`, `tanggal_laporan`) VALUES
(1, 1, 2024, '2024-12-31'),
(2, 1, 2025, '2025-12-31');

-- --------------------------------------------------------

--
-- Table structure for table `neraca`
--

CREATE TABLE `neraca` (
  `id` int NOT NULL,
  `laporan_id` int NOT NULL,
  `kas_setara_kas` decimal(10,0) DEFAULT NULL,
  `investasi_jangka_pendek` decimal(10,0) DEFAULT NULL,
  `piutang_usaha_pihak_ketiga` decimal(10,0) DEFAULT NULL,
  `piutang_usaha_pihak_berelasi` decimal(10,0) DEFAULT NULL,
  `piutang_bukan_usaha_pihak_ketiga` decimal(10,0) DEFAULT NULL,
  `piutang_bukan_usaha_pihak_berelasi` decimal(10,0) DEFAULT NULL,
  `persediaan` decimal(10,0) DEFAULT NULL,
  `aset_biologis` decimal(10,0) DEFAULT NULL,
  `uang_muka_jaminan` decimal(10,0) DEFAULT NULL,
  `pajak_dibayar_dimuka` decimal(10,0) DEFAULT NULL,
  `biaya_dibayar_dimuka_lancar` decimal(10,0) DEFAULT NULL,
  `total_aset_lancar` decimal(10,0) GENERATED ALWAYS AS (((((((((((ifnull(`kas_setara_kas`,0) + ifnull(`investasi_jangka_pendek`,0)) + ifnull(`piutang_usaha_pihak_ketiga`,0)) + ifnull(`piutang_usaha_pihak_berelasi`,0)) + ifnull(`piutang_bukan_usaha_pihak_ketiga`,0)) + ifnull(`piutang_bukan_usaha_pihak_berelasi`,0)) + ifnull(`persediaan`,0)) + ifnull(`aset_biologis`,0)) + ifnull(`uang_muka_jaminan`,0)) + ifnull(`pajak_dibayar_dimuka`,0)) + ifnull(`biaya_dibayar_dimuka_lancar`,0))) STORED,
  `tagihan_pajak_penghasilan` decimal(10,0) DEFAULT NULL,
  `piutang_plasma` decimal(10,0) DEFAULT NULL,
  `aset_pajak_tangguhan` decimal(10,0) DEFAULT NULL,
  `investasi_jangka_panjang` decimal(10,0) DEFAULT NULL,
  `aset_hak_guna` decimal(10,0) DEFAULT NULL,
  `aset_tetap` decimal(10,0) DEFAULT NULL,
  `biaya_ditangguhkan` decimal(10,0) DEFAULT NULL,
  `goodwill` decimal(10,0) DEFAULT NULL,
  `aset_tak_berwujud` decimal(10,0) DEFAULT NULL,
  `biaya_dibayar_dimuka_jangka_panjang` decimal(10,0) DEFAULT NULL,
  `aset_tidak_lancar_lainnya` decimal(10,0) DEFAULT NULL,
  `total_aset_tidak_lancar` decimal(10,0) GENERATED ALWAYS AS (((((((((((ifnull(`tagihan_pajak_penghasilan`,0) + ifnull(`piutang_plasma`,0)) + ifnull(`aset_pajak_tangguhan`,0)) + ifnull(`investasi_jangka_panjang`,0)) + ifnull(`aset_hak_guna`,0)) + ifnull(`aset_tetap`,0)) + ifnull(`biaya_ditangguhkan`,0)) + ifnull(`goodwill`,0)) + ifnull(`aset_tak_berwujud`,0)) + ifnull(`biaya_dibayar_dimuka_jangka_panjang`,0)) + ifnull(`aset_tidak_lancar_lainnya`,0))) STORED,
  `total_aset` decimal(10,0) GENERATED ALWAYS AS ((`total_aset_lancar` + `total_aset_tidak_lancar`)) STORED,
  `utang_bank_jangka_pendek` decimal(10,0) DEFAULT NULL,
  `utang_usaha_pihak_ketiga` decimal(10,0) DEFAULT NULL,
  `utang_usaha_pihak_berelasi` decimal(10,0) DEFAULT NULL,
  `utang_lain_pihak_ketiga` decimal(10,0) DEFAULT NULL,
  `beban_akrual` decimal(10,0) DEFAULT NULL,
  `liabilitas_imbalan_kerja_jangka_pendek` decimal(10,0) DEFAULT NULL,
  `utang_pajak` decimal(10,0) DEFAULT NULL,
  `liabilitas_sewa_jangka_pendek` decimal(10,0) DEFAULT NULL,
  `utang_bank_jatuh_tempo` decimal(10,0) DEFAULT NULL,
  `total_liabilitas_jangka_pendek` decimal(10,0) GENERATED ALWAYS AS (((((((((ifnull(`utang_bank_jangka_pendek`,0) + ifnull(`utang_usaha_pihak_ketiga`,0)) + ifnull(`utang_usaha_pihak_berelasi`,0)) + ifnull(`utang_lain_pihak_ketiga`,0)) + ifnull(`beban_akrual`,0)) + ifnull(`liabilitas_imbalan_kerja_jangka_pendek`,0)) + ifnull(`utang_pajak`,0)) + ifnull(`liabilitas_sewa_jangka_pendek`,0)) + ifnull(`utang_bank_jatuh_tempo`,0))) STORED,
  `utang_bank_jangka_panjang` decimal(10,0) DEFAULT NULL,
  `utang_obligasi` decimal(10,0) DEFAULT NULL,
  `utang_jangka_panjang_lainnya` decimal(10,0) DEFAULT NULL,
  `liabilitas_sewa_jangka_panjang` decimal(10,0) DEFAULT NULL,
  `liabilitas_pajak_tangguhan` decimal(10,0) DEFAULT NULL,
  `utang_pihak_berelasi` decimal(10,0) DEFAULT NULL,
  `liabilitas_imbalan_kerja_jangka_panjang` decimal(10,0) DEFAULT NULL,
  `liabilitas_estimasi_pembongkaran` decimal(10,0) DEFAULT NULL,
  `total_liabilitas_jangka_panjang` decimal(10,0) GENERATED ALWAYS AS ((((((((ifnull(`utang_bank_jangka_panjang`,0) + ifnull(`utang_obligasi`,0)) + ifnull(`utang_jangka_panjang_lainnya`,0)) + ifnull(`liabilitas_sewa_jangka_panjang`,0)) + ifnull(`liabilitas_pajak_tangguhan`,0)) + ifnull(`utang_pihak_berelasi`,0)) + ifnull(`liabilitas_imbalan_kerja_jangka_panjang`,0)) + ifnull(`liabilitas_estimasi_pembongkaran`,0))) STORED,
  `total_liabilitas` decimal(10,0) GENERATED ALWAYS AS ((`total_liabilitas_jangka_pendek` + `total_liabilitas_jangka_panjang`)) STORED,
  `modal_ditempatkan` decimal(10,0) DEFAULT NULL,
  `tambahan_modal_disetor` decimal(10,0) DEFAULT NULL,
  `laba_belum_terealisasi` decimal(10,0) DEFAULT NULL,
  `selisih_ekuitas_entitas_anak` decimal(10,0) DEFAULT NULL,
  `selisih_kurs_penjabaran` decimal(10,0) DEFAULT NULL,
  `cadangan_umum` decimal(10,0) DEFAULT NULL,
  `saldo_laba_belum_ditentukan` decimal(10,0) DEFAULT NULL,
  `ekuitas_induk` decimal(10,0) GENERATED ALWAYS AS (((((((ifnull(`modal_ditempatkan`,0) + ifnull(`tambahan_modal_disetor`,0)) + ifnull(`laba_belum_terealisasi`,0)) + ifnull(`selisih_ekuitas_entitas_anak`,0)) + ifnull(`selisih_kurs_penjabaran`,0)) + ifnull(`cadangan_umum`,0)) + ifnull(`saldo_laba_belum_ditentukan`,0))) STORED,
  `kepentingan_nonpengendali` decimal(10,0) DEFAULT NULL,
  `total_ekuitas` decimal(10,0) GENERATED ALWAYS AS ((`ekuitas_induk` + ifnull(`kepentingan_nonpengendali`,0))) STORED
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `neraca`
--

INSERT INTO `neraca` (`id`, `laporan_id`, `kas_setara_kas`, `investasi_jangka_pendek`, `piutang_usaha_pihak_ketiga`, `piutang_usaha_pihak_berelasi`, `piutang_bukan_usaha_pihak_ketiga`, `piutang_bukan_usaha_pihak_berelasi`, `persediaan`, `aset_biologis`, `uang_muka_jaminan`, `pajak_dibayar_dimuka`, `biaya_dibayar_dimuka_lancar`, `tagihan_pajak_penghasilan`, `piutang_plasma`, `aset_pajak_tangguhan`, `investasi_jangka_panjang`, `aset_hak_guna`, `aset_tetap`, `biaya_ditangguhkan`, `goodwill`, `aset_tak_berwujud`, `biaya_dibayar_dimuka_jangka_panjang`, `aset_tidak_lancar_lainnya`, `utang_bank_jangka_pendek`, `utang_usaha_pihak_ketiga`, `utang_usaha_pihak_berelasi`, `utang_lain_pihak_ketiga`, `beban_akrual`, `liabilitas_imbalan_kerja_jangka_pendek`, `utang_pajak`, `liabilitas_sewa_jangka_pendek`, `utang_bank_jatuh_tempo`, `utang_bank_jangka_panjang`, `utang_obligasi`, `utang_jangka_panjang_lainnya`, `liabilitas_sewa_jangka_panjang`, `liabilitas_pajak_tangguhan`, `utang_pihak_berelasi`, `liabilitas_imbalan_kerja_jangka_panjang`, `liabilitas_estimasi_pembongkaran`, `modal_ditempatkan`, `tambahan_modal_disetor`, `laba_belum_terealisasi`, `selisih_ekuitas_entitas_anak`, `selisih_kurs_penjabaran`, `cadangan_umum`, `saldo_laba_belum_ditentukan`, `kepentingan_nonpengendali`) VALUES
(1, 1, '38710056', '9514928', '7989147', '1294396', '307601', '426153', '17953901', '1132115', '1693448', '646969', '96762', '97309', '655524', '726246', '7494144', '953059', '47813979', '864115', '56352086', '1312840', '15399', '5663136', '20951159', '5649272', '152304', '1610555', '4315069', '1820001', '1894232', '79787', '621682', '5015530', '44211216', '8753', '137277', '996881', '434143', '4724321', '99848', '878043', '283732', '2082545', '7290835', '1041894', '140000', '53396455', '43877779'),
(2, 2, '47470705', '9837466', '9066381', '1525052', '421336', '426714', '18691652', '1034743', '1424124', '461370', '31713', '146901', '565793', '701227', '9106803', '1371535', '49793977', '872472', '56352086', '1179602', '15529', '7484584', '21302945', '6213305', '185971', '1625761', '5057112', '1776631', '1968402', '248213', '4021304', '3299113', '45936280', '8753', '141909', '962773', '135799', '4742791', '119456', '878043', '283732', '1653896', '7211470', '1515976', '145000', '61459220', '47087910');

-- --------------------------------------------------------

--
-- Table structure for table `perusahaan`
--

CREATE TABLE `perusahaan` (
  `id` int NOT NULL,
  `nama` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  `kode_saham` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `perusahaan`
--

INSERT INTO `perusahaan` (`id`, `nama`, `kode_saham`, `created_at`) VALUES
(1, 'PT Indofood Sukses Makmur Tbk', 'INDF', '2026-06-02 00:39:21');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int NOT NULL,
  `nama` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `username` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` enum('admin','akuntansi','si') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'si',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `nama`, `username`, `password`, `role`, `created_at`) VALUES
(1, 'Administrator', 'admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin', '2026-06-02 00:39:21'),
(2, 'Prodi Akuntansi', 'akuntansi', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'akuntansi', '2026-06-02 00:39:21'),
(3, 'Prodi SI', 'si', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'si', '2026-06-02 00:39:21');

-- --------------------------------------------------------

--
-- Stand-in structure for view `v_rasio_keuangan`
-- (See below for the actual view)
--
CREATE TABLE `v_rasio_keuangan` (
`tahun` year
,`perusahaan` varchar(200)
,`current_ratio` decimal(13,2)
,`quick_ratio` decimal(14,2)
,`cash_ratio` decimal(13,2)
,`dar` decimal(14,4)
,`der` decimal(14,4)
,`equity_multiplier` decimal(14,4)
,`long_term_der` decimal(14,4)
,`equity_to_asset_ratio` decimal(16,2)
,`aset_lancar_to_total_aset` decimal(16,2)
,`liabilitas_lancar_to_total_liabilitas` decimal(16,2)
,`modal_kerja_bersih` decimal(11,0)
,`gross_profit_margin` decimal(16,2)
,`net_profit_margin` decimal(16,2)
,`roa` decimal(16,2)
,`roe` decimal(16,2)
,`penjualan_neto` decimal(10,0)
,`laba_bruto` decimal(10,0)
,`laba_usaha` decimal(10,0)
,`laba_tahun_berjalan` decimal(10,0)
,`total_aset` decimal(10,0)
,`total_liabilitas` decimal(10,0)
,`total_ekuitas` decimal(10,0)
,`kas_setara_kas` decimal(10,0)
,`total_aset_lancar` decimal(10,0)
,`total_liabilitas_jangka_pendek` decimal(10,0)
,`total_liabilitas_jangka_panjang` decimal(10,0)
,`kas_neto_operasi` decimal(10,0)
,`kas_neto_investasi` decimal(10,0)
,`kas_neto_pendanaan` decimal(10,0)
,`eps_dasar` decimal(10,0)
);

-- --------------------------------------------------------

--
-- Structure for view `v_rasio_keuangan`
--
DROP TABLE IF EXISTS `v_rasio_keuangan`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_rasio_keuangan`  AS SELECT `lk`.`tahun` AS `tahun`, `p`.`nama` AS `perusahaan`, round((`n`.`total_aset_lancar` / `n`.`total_liabilitas_jangka_pendek`),2) AS `current_ratio`, round(((`n`.`total_aset_lancar` - `n`.`persediaan`) / `n`.`total_liabilitas_jangka_pendek`),2) AS `quick_ratio`, round((`n`.`kas_setara_kas` / `n`.`total_liabilitas_jangka_pendek`),2) AS `cash_ratio`, round((`n`.`total_liabilitas` / `n`.`total_aset`),4) AS `dar`, round((`n`.`total_liabilitas` / `n`.`total_ekuitas`),4) AS `der`, round((`n`.`total_aset` / `n`.`total_ekuitas`),4) AS `equity_multiplier`, round((`n`.`total_liabilitas_jangka_panjang` / `n`.`total_ekuitas`),4) AS `long_term_der`, round(((`n`.`total_ekuitas` / `n`.`total_aset`) * 100),2) AS `equity_to_asset_ratio`, round(((`n`.`total_aset_lancar` / `n`.`total_aset`) * 100),2) AS `aset_lancar_to_total_aset`, round(((`n`.`total_liabilitas_jangka_pendek` / `n`.`total_liabilitas`) * 100),2) AS `liabilitas_lancar_to_total_liabilitas`, (`n`.`total_aset_lancar` - `n`.`total_liabilitas_jangka_pendek`) AS `modal_kerja_bersih`, round(((`lr`.`laba_bruto` / `lr`.`penjualan_neto`) * 100),2) AS `gross_profit_margin`, round(((`lr`.`laba_tahun_berjalan` / `lr`.`penjualan_neto`) * 100),2) AS `net_profit_margin`, round(((`lr`.`laba_tahun_berjalan` / `n`.`total_aset`) * 100),2) AS `roa`, round(((`lr`.`laba_tahun_berjalan` / `n`.`total_ekuitas`) * 100),2) AS `roe`, `lr`.`penjualan_neto` AS `penjualan_neto`, `lr`.`laba_bruto` AS `laba_bruto`, `lr`.`laba_usaha` AS `laba_usaha`, `lr`.`laba_tahun_berjalan` AS `laba_tahun_berjalan`, `n`.`total_aset` AS `total_aset`, `n`.`total_liabilitas` AS `total_liabilitas`, `n`.`total_ekuitas` AS `total_ekuitas`, `n`.`kas_setara_kas` AS `kas_setara_kas`, `n`.`total_aset_lancar` AS `total_aset_lancar`, `n`.`total_liabilitas_jangka_pendek` AS `total_liabilitas_jangka_pendek`, `n`.`total_liabilitas_jangka_panjang` AS `total_liabilitas_jangka_panjang`, `ak`.`kas_neto_operasi` AS `kas_neto_operasi`, `ak`.`kas_neto_investasi` AS `kas_neto_investasi`, `ak`.`kas_neto_pendanaan` AS `kas_neto_pendanaan`, `lr`.`eps_dasar` AS `eps_dasar` FROM ((((`laporan_keuangan` `lk` join `perusahaan` `p` on((`p`.`id` = `lk`.`perusahaan_id`))) join `neraca` `n` on((`n`.`laporan_id` = `lk`.`id`))) join `laba_rugi` `lr` on((`lr`.`laporan_id` = `lk`.`id`))) join `arus_kas` `ak` on((`ak`.`laporan_id` = `lk`.`id`))) ORDER BY `lk`.`tahun` ASC  ;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `arus_kas`
--
ALTER TABLE `arus_kas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `laporan_id` (`laporan_id`);

--
-- Indexes for table `laba_rugi`
--
ALTER TABLE `laba_rugi`
  ADD PRIMARY KEY (`id`),
  ADD KEY `laporan_id` (`laporan_id`);

--
-- Indexes for table `laporan_keuangan`
--
ALTER TABLE `laporan_keuangan`
  ADD PRIMARY KEY (`id`),
  ADD KEY `perusahaan_id` (`perusahaan_id`);

--
-- Indexes for table `neraca`
--
ALTER TABLE `neraca`
  ADD PRIMARY KEY (`id`),
  ADD KEY `laporan_id` (`laporan_id`);

--
-- Indexes for table `perusahaan`
--
ALTER TABLE `perusahaan`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `arus_kas`
--
ALTER TABLE `arus_kas`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `laba_rugi`
--
ALTER TABLE `laba_rugi`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `laporan_keuangan`
--
ALTER TABLE `laporan_keuangan`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `neraca`
--
ALTER TABLE `neraca`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `perusahaan`
--
ALTER TABLE `perusahaan`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `arus_kas`
--
ALTER TABLE `arus_kas`
  ADD CONSTRAINT `arus_kas_ibfk_1` FOREIGN KEY (`laporan_id`) REFERENCES `laporan_keuangan` (`id`);

--
-- Constraints for table `laba_rugi`
--
ALTER TABLE `laba_rugi`
  ADD CONSTRAINT `laba_rugi_ibfk_1` FOREIGN KEY (`laporan_id`) REFERENCES `laporan_keuangan` (`id`);

--
-- Constraints for table `laporan_keuangan`
--
ALTER TABLE `laporan_keuangan`
  ADD CONSTRAINT `laporan_keuangan_ibfk_1` FOREIGN KEY (`perusahaan_id`) REFERENCES `perusahaan` (`id`);

--
-- Constraints for table `neraca`
--
ALTER TABLE `neraca`
  ADD CONSTRAINT `neraca_ibfk_1` FOREIGN KEY (`laporan_id`) REFERENCES `laporan_keuangan` (`id`);
--
-- Database: `db_uni`
--
CREATE DATABASE IF NOT EXISTS `db_uni` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci;
USE `db_uni`;

-- --------------------------------------------------------

--
-- Table structure for table `mhs`
--

CREATE TABLE `mhs` (
  `no` int NOT NULL,
  `nama` varchar(150) NOT NULL,
  `npm` int NOT NULL,
  `prodi` varchar(50) NOT NULL,
  `angkatan` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `mhs`
--

INSERT INTO `mhs` (`no`, `nama`, `npm`, `prodi`, `angkatan`) VALUES
(1, 'jojo', 524031101, 'kehutanan', 2021),
(2, 'joni', 524031102, 'perikanan', 2021);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `mhs`
--
ALTER TABLE `mhs`
  ADD PRIMARY KEY (`no`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `mhs`
--
ALTER TABLE `mhs`
  MODIFY `no` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- Database: `db_week7`
--
CREATE DATABASE IF NOT EXISTS `db_week7` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci;
USE `db_week7`;

-- --------------------------------------------------------

--
-- Table structure for table `student`
--

CREATE TABLE `student` (
  `id` int NOT NULL,
  `nama` varchar(255) NOT NULL,
  `npm` varchar(11) NOT NULL,
  `prodi` varchar(50) NOT NULL,
  `angkatan` varchar(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `student`
--

INSERT INTO `student` (`id`, `nama`, `npm`, `prodi`, `angkatan`) VALUES
(1, 'Sandhika Galih', '5240411001', 'Informatika', '2024'),
(2, 'Daniel Baskara Putra', '5240411002', 'Ilmu Komunikasi', '2024'),
(3, 'Brigitta Sriulina Beru', '5240411003', 'Sastra Inggris', '2024'),
(4, 'Akhdiyat Duta Modjo', '5240411004', 'Mekanisasi Pertanian', '2001'),
(5, 'Feby Putri Nilam', '5240411005', 'Ilmu Hubungan Internasional', '2002'),
(6, 'Fiersa Besari', '5230411006', 'Sarjana Sastra', '2002'),
(7, 'Theresia Margaretha Gultom', '5230411007', 'Psikologi', '2023');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id` int NOT NULL,
  `nama` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `nama`, `email`, `password`) VALUES
(1, 'Sosok Admin', 'admin@mail.com', '$2y$10$fMf7R8S1FUoBqxVKqeE6weqK7sceZ0S/dEFi56WhAQS0Pyfst4.ku'),
(2, 'Sosok Staff', 'staff@mail.com', '$2y$10$iDO6jI4HGUCXNvkUEmj5kuoO7COztLt7LiN2M0DS260JtQtCNEO/u'),
(3, 'Sosok Member', 'member@mail.com', '$2y$10$1lqlKCrSGu9AJ3wVJgYSGu6Hs5dcBUrJgKruGy46SpBpVrlpcwLjy');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `student`
--
ALTER TABLE `student`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `student`
--
ALTER TABLE `student`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- Database: `eclim_db`
--
CREATE DATABASE IF NOT EXISTS `eclim_db` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci;
USE `eclim_db`;

-- --------------------------------------------------------

--
-- Table structure for table `materi_edukasi`
--

CREATE TABLE `materi_edukasi` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `judul` varchar(150) NOT NULL,
  `kategori` varchar(50) NOT NULL,
  `konten` text NOT NULL,
  `tanggal_rilis` date NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `materi_edukasi`
--

INSERT INTO `materi_edukasi` (`id`, `user_id`, `judul`, `kategori`, `konten`, `tanggal_rilis`, `created_at`) VALUES
(1, 1, 'Bahaya Mikroplastik di Lautan Kita', 'Polusi', 'Mikroplastik kini ditemukan di dalam perut ikan yang kita konsumsi sehari-hari...', '2026-05-10', '2026-05-15 15:35:45'),
(2, 1, 'Panduan Memulai Kompos Skala Rumah Tangga', 'Daur Ulang', 'Membuat kompos adalah cara termudah untuk mengurangi emisi gas metana dari TPA...', '2026-05-12', '2026-05-15 15:35:45'),
(3, 1, 'Mitos dan Fakta Panel Surya', 'Energi', 'Banyak yang menganggap panel surya terlalu mahal, padahal investasi jangka panjangnya...', '2026-05-15', '2026-05-15 15:35:45');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int NOT NULL,
  `nama_lengkap` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','user') DEFAULT 'user',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `nama_lengkap`, `email`, `password`, `role`, `created_at`) VALUES
(1, 'Admin E-Clim', 'admin@eclim.id', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin', '2026-05-15 15:35:45'),
(2, 'Budi Lingkungan', 'budi@gmail.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'user', '2026-05-15 15:35:45'),
(3, 'Siti Lestari', 'siti@yahoo.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'user', '2026-05-15 15:35:45');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `materi_edukasi`
--
ALTER TABLE `materi_edukasi`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `materi_edukasi`
--
ALTER TABLE `materi_edukasi`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `materi_edukasi`
--
ALTER TABLE `materi_edukasi`
  ADD CONSTRAINT `materi_edukasi_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
--
-- Database: `proyek_timfdel`
--
CREATE DATABASE IF NOT EXISTS `proyek_timfdel` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci;
USE `proyek_timfdel`;

-- --------------------------------------------------------

--
-- Table structure for table `berita`
--

CREATE TABLE `berita` (
  `id_berita` int NOT NULL,
  `judul` varchar(255) NOT NULL,
  `konten` text NOT NULL,
  `penulis_id` int NOT NULL,
  `tanggal_publikasi` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `posts`
--

CREATE TABLE `posts` (
  `id` int NOT NULL,
  `title` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `author_id` int NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `posts`
--

INSERT INTO `posts` (`id`, `title`, `content`, `author_id`, `created_at`) VALUES
(1, 'Selamat Datang', 'Ini adalah posting demo pertama pada situs. Selamat datang!', 1, '2026-05-30 12:18:18'),
(2, 'Pengumuman', 'Website sedang dalam pengembangan. Terima kasih atas kunjungan Anda.', 1, '2026-05-30 12:18:18');

-- --------------------------------------------------------

--
-- Table structure for table `testabel`
--

CREATE TABLE `testabel` (
  `id` int NOT NULL,
  `tes` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `testabel`
--

INSERT INTO `testabel` (`id`, `tes`) VALUES
(1, 'contoh1'),
(2, 'contoh2');

-- --------------------------------------------------------

--
-- Table structure for table `userr`
--

CREATE TABLE `userr` (
  `userid` int NOT NULL,
  `username` varchar(100) NOT NULL,
  `useremail` varchar(100) NOT NULL,
  `userpass` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `userr`
--

INSERT INTO `userr` (`userid`, `username`, `useremail`, `userpass`, `created_at`) VALUES
(1, 'admin', 'admin@iklim.id', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '2026-06-01 10:18:43');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int NOT NULL,
  `email` varchar(255) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `email`, `password_hash`, `created_at`) VALUES
(1, 'demo@example.com', '$2y$10$MpnJDwQaQZsLJU1KX9q.5ebVQ84GcB4kjxj3.15VpRkOL2zDY0QAm', '2026-05-30 12:18:18');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `berita`
--
ALTER TABLE `berita`
  ADD PRIMARY KEY (`id_berita`),
  ADD KEY `penulis_id` (`penulis_id`);

--
-- Indexes for table `posts`
--
ALTER TABLE `posts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_author` (`author_id`);

--
-- Indexes for table `testabel`
--
ALTER TABLE `testabel`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `userr`
--
ALTER TABLE `userr`
  ADD PRIMARY KEY (`userid`),
  ADD UNIQUE KEY `useremail` (`useremail`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `berita`
--
ALTER TABLE `berita`
  MODIFY `id_berita` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `posts`
--
ALTER TABLE `posts`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `testabel`
--
ALTER TABLE `testabel`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `userr`
--
ALTER TABLE `userr`
  MODIFY `userid` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `berita`
--
ALTER TABLE `berita`
  ADD CONSTRAINT `berita_ibfk_1` FOREIGN KEY (`penulis_id`) REFERENCES `userr` (`userid`) ON DELETE CASCADE;

--
-- Constraints for table `posts`
--
ALTER TABLE `posts`
  ADD CONSTRAINT `fk_posts_author` FOREIGN KEY (`author_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
--
-- Database: `testing12`
--
CREATE DATABASE IF NOT EXISTS `testing12` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci;
USE `testing12`;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

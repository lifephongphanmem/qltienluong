-- phpMyAdmin SQL Dump
-- version 4.8.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 18, 2018 at 06:30 AM
-- Server version: 10.1.31-MariaDB
-- PHP Version: 5.6.35

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `qlluong_khanhhoa`
--

-- --------------------------------------------------------

--
-- Table structure for table `bangluong`
--

CREATE TABLE `bangluong` (
  `id` int(10) UNSIGNED NOT NULL,
  `mabl` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `thang` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  `nam` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  `noidung` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `ngaylap` date DEFAULT NULL,
  `nguoilap` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `ghichu` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `manguonkp` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `phanloai` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `linhvuchoatdong` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `phantramhuong` double NOT NULL DEFAULT '100',
  `luongcoban` double NOT NULL DEFAULT '0',
  `luongnb` double NOT NULL DEFAULT '0',
  `pckv` double NOT NULL DEFAULT '0',
  `pccv` double NOT NULL DEFAULT '0',
  `pctn` double NOT NULL DEFAULT '0',
  `pctnvk` double NOT NULL DEFAULT '0',
  `pcudn` double NOT NULL DEFAULT '0',
  `pcth` double NOT NULL DEFAULT '0',
  `pctnn` double NOT NULL DEFAULT '0',
  `pccovu` double NOT NULL DEFAULT '0',
  `pcdang` double NOT NULL DEFAULT '0',
  `pckn` double NOT NULL DEFAULT '0',
  `pck` double NOT NULL DEFAULT '0',
  `ngaygui` date DEFAULT NULL,
  `nguoigui` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `trangthai` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `madv` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `bangluong`
--

INSERT INTO `bangluong` (`id`, `mabl`, `thang`, `nam`, `noidung`, `ngaylap`, `nguoilap`, `ghichu`, `manguonkp`, `phanloai`, `linhvuchoatdong`, `phantramhuong`, `luongcoban`, `luongnb`, `pckv`, `pccv`, `pctn`, `pctnvk`, `pcudn`, `pcth`, `pctnn`, `pccovu`, `pcdang`, `pckn`, `pck`, `ngaygui`, `nguoigui`, `trangthai`, `madv`, `created_at`, `updated_at`) VALUES
(4, '1512393993_1533713718', '01', '2018', '', '2018-08-08', '', NULL, '12', NULL, 'GD', 100, 1300000, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, '1512393993', NULL, NULL),
(5, '1512391746_1533715069', '01', '2018', '', '2018-08-08', '', NULL, '12', NULL, 'GD', 100, 1300000, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, '1512391746', NULL, NULL),
(6, '1534385336_1534385666', '01', '2018', '', '2018-08-16', '', NULL, '12', NULL, 'QLNN', 100, 1300000, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, '1534385336', NULL, NULL),
(7, '1534385336_1534393336', '02', '2018', '', '2018-08-16', '', NULL, '12', NULL, 'GD', 100, 1300000, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, '1534385336', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `bangluong_ct`
--

CREATE TABLE `bangluong_ct` (
  `id` int(10) UNSIGNED NOT NULL,
  `mabl` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `macvcq` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `mapb` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `msngbac` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `mact` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `stt` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  `phanloai` varchar(50) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'CVCHINH',
  `macanbo` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `tencanbo` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `macongchuc` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `heso` double NOT NULL DEFAULT '0',
  `hesopc` double NOT NULL DEFAULT '0',
  `hesott` double NOT NULL DEFAULT '0',
  `thangtl` double NOT NULL DEFAULT '0',
  `vuotkhung` double NOT NULL DEFAULT '0',
  `pcct` double NOT NULL DEFAULT '0',
  `pckct` double NOT NULL DEFAULT '0',
  `pck` double NOT NULL DEFAULT '0',
  `pccv` double NOT NULL DEFAULT '0',
  `pckv` double NOT NULL DEFAULT '0',
  `pcth` double NOT NULL DEFAULT '0',
  `pcdd` double NOT NULL DEFAULT '0',
  `pcdh` double NOT NULL DEFAULT '0',
  `pcld` double NOT NULL DEFAULT '0',
  `pcdbqh` double NOT NULL DEFAULT '0',
  `pcudn` double NOT NULL DEFAULT '0',
  `pctn` double NOT NULL DEFAULT '0',
  `pctnn` double NOT NULL DEFAULT '0',
  `pcdbn` double NOT NULL DEFAULT '0',
  `pcvk` double NOT NULL DEFAULT '0',
  `pckn` double NOT NULL DEFAULT '0',
  `pcdang` double NOT NULL DEFAULT '0',
  `pccovu` double NOT NULL DEFAULT '0',
  `pclt` double NOT NULL DEFAULT '0',
  `pcd` double NOT NULL DEFAULT '0',
  `pctr` double NOT NULL DEFAULT '0',
  `pctnvk` double NOT NULL DEFAULT '0',
  `pcbdhdcu` double NOT NULL DEFAULT '0',
  `pcthni` double NOT NULL DEFAULT '0',
  `tonghs` double NOT NULL DEFAULT '0',
  `ttl` double NOT NULL DEFAULT '0',
  `giaml` double NOT NULL DEFAULT '0',
  `bhct` double NOT NULL DEFAULT '0',
  `tluong` double NOT NULL DEFAULT '0',
  `stbhxh` double NOT NULL DEFAULT '0',
  `stbhyt` double NOT NULL DEFAULT '0',
  `stkpcd` double NOT NULL DEFAULT '0',
  `stbhtn` double NOT NULL DEFAULT '0',
  `ttbh` double NOT NULL DEFAULT '0',
  `gttncn` double NOT NULL DEFAULT '0',
  `luongtn` double NOT NULL DEFAULT '0',
  `stbhxh_dv` double NOT NULL DEFAULT '0',
  `stbhyt_dv` double NOT NULL DEFAULT '0',
  `stkpcd_dv` double NOT NULL DEFAULT '0',
  `stbhtn_dv` double NOT NULL DEFAULT '0',
  `ttbh_dv` double NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `bangluong_ct`
--

INSERT INTO `bangluong_ct` (`id`, `mabl`, `macvcq`, `mapb`, `msngbac`, `mact`, `stt`, `phanloai`, `macanbo`, `tencanbo`, `macongchuc`, `heso`, `hesopc`, `hesott`, `thangtl`, `vuotkhung`, `pcct`, `pckct`, `pck`, `pccv`, `pckv`, `pcth`, `pcdd`, `pcdh`, `pcld`, `pcdbqh`, `pcudn`, `pctn`, `pctnn`, `pcdbn`, `pcvk`, `pckn`, `pcdang`, `pccovu`, `pclt`, `pcd`, `pctr`, `pctnvk`, `pcbdhdcu`, `pcthni`, `tonghs`, `ttl`, `giaml`, `bhct`, `tluong`, `stbhxh`, `stbhyt`, `stkpcd`, `stbhtn`, `ttbh`, `gttncn`, `luongtn`, `stbhxh_dv`, `stbhyt_dv`, `stkpcd_dv`, `stbhtn_dv`, `ttbh_dv`, `created_at`, `updated_at`) VALUES
(1, '1512393993_1533713718', '_1507601256', '', '09.068', '1506672780', '1', 'CVCHINH', '1512393993_1533713682', 'nguyen c', '', 2.34, 0, 0, 0, 0, 0, 0, 0, 0.3, 0, 0, 0, 0, 0, 0.3, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 2.94, 3822000, 0, 0, 0, 274560, 51480, 0, 0, 326040, 0, 3495960, 600600, 102960, 68640, 34320, 806520, NULL, NULL),
(2, '1512391746_1533715069', '_1507601256', '', '09.068', '1506672780', '1', 'CVCHINH', '1512391746_1533713440', 'ab', '', 2.34, 0, 0, 0, 0, 0, 0, 0, 0.3, 0, 0, 0, 0, 0, 0.3, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 2.94, 3822000, 0, 0, 0, 274560, 51480, 0, 0, 326040, 0, 3495960, 600600, 102960, 68640, 34320, 806520, NULL, NULL),
(3, '1534385336_1534385666', '_1507601256', '', '15a.201', '1506672780', '1', 'CVCHINH', '1534385336_1534385500', 'Nguyễn a', '', 0, 0, 0, 0, 0, 0, 0, 0, 0.3, 0.3, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0.6, 780000, 0, 0, 0, 31200, 5850, 0, 0, 37050, 0, 742950, 68250, 11700, 7800, 3900, 91650, NULL, NULL),
(4, '1534385336_1534385666', '_1507601256', '', 'V.07.02.06', '1506672780', '2', 'CVCHINH', '1534385336_1534385597', 'Nguyễn b', '', 1.86, 0, 0, 0, 0, 0, 0, 0, 0.3, 0.3, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 2.46, 3198000, 0, 0, 0, 224640, 42120, 0, 0, 266760, 0, 2931240, 491400, 84240, 56160, 28080, 659880, NULL, NULL),
(5, '1534385336_1534393336', '_1507601256', '', '15a.201', '1506672780', '1', 'CVCHINH', '1534385336_1534385500', 'Nguyễn a', '', 0, 0, 0, 0, 0, 0, 0, 0, 0.3, 0.3, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0.6, 780000, 0, 0, 0, 31200, 5850, 0, 0, 37050, 0, 742950, 68250, 11700, 7800, 3900, 91650, NULL, NULL),
(6, '1534385336_1534393336', '_1507601256', '', 'V.07.02.06', '1506672780', '2', 'CVCHINH', '1534385336_1534385597', 'Nguyễn b', '', 1.86, 0, 0, 0, 0, 0, 0, 0, 0.3, 0.3, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 2.46, 3198000, 0, 0, 0, 224640, 42120, 0, 0, 266760, 0, 2931240, 491400, 84240, 56160, 28080, 659880, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `bangluong_phucap`
--

CREATE TABLE `bangluong_phucap` (
  `id` int(10) UNSIGNED NOT NULL,
  `mabl` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `maso` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `ten` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `macanbo` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `tencanbo` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `phanloai` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `congthuc` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `heso_goc` double NOT NULL DEFAULT '0',
  `heso` double NOT NULL DEFAULT '0',
  `sotien` double NOT NULL DEFAULT '0',
  `stbhxh` double NOT NULL DEFAULT '0',
  `stbhyt` double NOT NULL DEFAULT '0',
  `stkpcd` double NOT NULL DEFAULT '0',
  `stbhtn` double NOT NULL DEFAULT '0',
  `ttbh` double NOT NULL DEFAULT '0',
  `stbhxh_dv` double NOT NULL DEFAULT '0',
  `stbhyt_dv` double NOT NULL DEFAULT '0',
  `stkpcd_dv` double NOT NULL DEFAULT '0',
  `stbhtn_dv` double NOT NULL DEFAULT '0',
  `ttbh_dv` double NOT NULL DEFAULT '0',
  `ghichu` text COLLATE utf8_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `bangluong_phucap`
--

INSERT INTO `bangluong_phucap` (`id`, `mabl`, `maso`, `ten`, `macanbo`, `tencanbo`, `phanloai`, `congthuc`, `heso_goc`, `heso`, `sotien`, `stbhxh`, `stbhyt`, `stkpcd`, `stbhtn`, `ttbh`, `stbhxh_dv`, `stbhyt_dv`, `stkpcd_dv`, `stbhtn_dv`, `ttbh_dv`, `ghichu`, `created_at`, `updated_at`) VALUES
(10, '1512393993_1533713718', 'pccv', 'Phụ cấp chức vụ', '1512393993_1533713682', 'nguyen c', '0', '', 0.3, 0.3, 390000, 31200, 5850, 0, 0, 37050, 68250, 11700, 7800, 3900, 91650, NULL, NULL, NULL),
(11, '1512393993_1533713718', 'pcdbqh', 'Phụ cấp đại biểu HĐND', '1512393993_1533713682', 'nguyen c', '0', '', 0.3, 0.3, 390000, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL),
(12, '1512393993_1533713718', 'heso', 'Lương hệ số', '1512393993_1533713682', 'nguyen c', '0', '', 2.34, 2.34, 3042000, 243360, 45630, 0, 0, 288990, 532350, 91260, 60840, 30420, 714870, NULL, NULL, NULL),
(13, '1512391746_1533715069', 'pccv', 'Phụ cấp chức vụ', '1512391746_1533713440', 'ab', '0', '', 0.3, 0.3, 390000, 31200, 5850, 0, 0, 37050, 68250, 11700, 7800, 3900, 91650, NULL, NULL, NULL),
(14, '1512391746_1533715069', 'pcdbqh', 'Phụ cấp đại biểu HĐND', '1512391746_1533713440', 'ab', '0', '', 0.3, 0.3, 390000, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL),
(15, '1512391746_1533715069', 'heso', 'Lương hệ số', '1512391746_1533713440', 'ab', '0', '', 2.34, 2.34, 3042000, 243360, 45630, 0, 0, 288990, 532350, 91260, 60840, 30420, 714870, NULL, NULL, NULL),
(16, '1534385336_1534385666', 'pckv', 'Phụ cấp khu vực', '1534385336_1534385500', 'Nguyễn a', '0', '', 0.3, 0.3, 390000, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL),
(17, '1534385336_1534385666', 'pccv', 'Phụ cấp chức vụ', '1534385336_1534385500', 'Nguyễn a', '0', '', 0.3, 0.3, 390000, 31200, 5850, 0, 0, 37050, 68250, 11700, 7800, 3900, 91650, NULL, NULL, NULL),
(18, '1534385336_1534385666', 'pckv', 'Phụ cấp khu vực', '1534385336_1534385597', 'Nguyễn b', '0', '', 0.3, 0.3, 390000, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL),
(19, '1534385336_1534385666', 'pccv', 'Phụ cấp chức vụ', '1534385336_1534385597', 'Nguyễn b', '0', '', 0.3, 0.3, 390000, 31200, 5850, 0, 0, 37050, 68250, 11700, 7800, 3900, 91650, NULL, NULL, NULL),
(20, '1534385336_1534385666', 'heso', 'Lương hệ số', '1534385336_1534385597', 'Nguyễn b', '0', '', 1.86, 1.86, 2418000, 193440, 36270, 0, 0, 229710, 423150, 72540, 48360, 24180, 568230, NULL, NULL, NULL),
(21, '1534385336_1534393336', 'pckv', 'Phụ cấp khu vực', '1534385336_1534385500', 'Nguyễn a', '0', '', 0.3, 0.3, 390000, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL),
(22, '1534385336_1534393336', 'pccv', 'Phụ cấp chức vụ', '1534385336_1534385500', 'Nguyễn a', '0', '', 0.3, 0.3, 390000, 31200, 5850, 0, 0, 37050, 68250, 11700, 7800, 3900, 91650, NULL, NULL, NULL),
(23, '1534385336_1534393336', 'pckv', 'Phụ cấp khu vực', '1534385336_1534385597', 'Nguyễn b', '0', '', 0.3, 0.3, 390000, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL),
(24, '1534385336_1534393336', 'pccv', 'Phụ cấp chức vụ', '1534385336_1534385597', 'Nguyễn b', '0', '', 0.3, 0.3, 390000, 31200, 5850, 0, 0, 37050, 68250, 11700, 7800, 3900, 91650, NULL, NULL, NULL),
(25, '1534385336_1534393336', 'heso', 'Lương hệ số', '1534385336_1534385597', 'Nguyễn b', '0', '', 1.86, 1.86, 2418000, 193440, 36270, 0, 0, 229710, 423150, 72540, 48360, 24180, 568230, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `chitieubienche`
--

CREATE TABLE `chitieubienche` (
  `id` int(10) UNSIGNED NOT NULL,
  `madv` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `nam` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `soluongduocgiao` double NOT NULL DEFAULT '0',
  `soluongbienche` double NOT NULL DEFAULT '0',
  `soluongkhongchuyentrach` double NOT NULL DEFAULT '0',
  `soluonguyvien` double NOT NULL DEFAULT '0',
  `soluongdaibieuhdnd` double NOT NULL DEFAULT '0',
  `ghichu` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `dmchucvucq`
--

CREATE TABLE `dmchucvucq` (
  `id` int(10) UNSIGNED NOT NULL,
  `macvcq` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `tencv` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `tenvt` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `ghichu` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `sapxep` int(11) NOT NULL DEFAULT '99',
  `ttdv` int(11) NOT NULL DEFAULT '0',
  `madv` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `maphanloai` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `phannhom` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `dmchucvucq`
--

INSERT INTO `dmchucvucq` (`id`, `macvcq`, `tencv`, `tenvt`, `ghichu`, `sapxep`, `ttdv`, `madv`, `maphanloai`, `phannhom`, `created_at`, `updated_at`) VALUES
(1, '1506416715_1507020248', 'Bí thư đảng uỷ', NULL, '', 1, 0, 'SA', 'KVXP', NULL, '2017-10-03 08:44:08', '2017-10-03 08:48:48'),
(2, '1506416715_1507020370', 'Phó Bí thư đảng uỷ', NULL, '', 2, 0, 'SA', 'KVXP', NULL, '2017-10-03 08:46:10', '2017-10-03 08:49:02'),
(3, '1506416715_1507020566', 'Chủ tịch Hội đồng nhân dân', NULL, '', 3, 0, 'SA', 'KVXP', NULL, '2017-10-03 08:49:26', '2017-10-03 08:49:53'),
(4, '1506416715_1507020588', 'Chủ tịch Ủy ban nhân dân', NULL, '', 3, 0, 'SA', 'KVXP', NULL, '2017-10-03 08:49:48', '2017-10-03 08:49:48'),
(5, '1506416715_1507020611', 'Chủ tịch Ủy ban Mặt trận Tổ quốc', NULL, '', 6, 0, 'SA', 'KVXP', NULL, '2017-10-03 08:50:11', '2017-10-23 04:58:26'),
(6, '1506416715_1507020620', 'Phó Chủ tịch Hội đồng nhân dân', NULL, '', 99, 0, 'SA', 'KVXP', NULL, '2017-10-03 08:50:20', '2017-10-03 08:50:20'),
(7, '1506416715_1507020634', 'Phó Chủ tịch Ủy ban nhân dân', NULL, '', 99, 0, 'SA', 'KVXP', NULL, '2017-10-03 08:50:34', '2017-10-03 08:50:34'),
(8, '1506416715_1507020645', 'Bí thư Đoàn Thanh niên Cộng sản Hồ Chí Minh', NULL, '', 99, 0, 'SA', 'KVXP', NULL, '2017-10-03 08:50:45', '2017-10-03 08:50:45'),
(9, '1506416715_1507020655', 'Chủ tịch Hội Liên hiệp Phụ nữ', NULL, '', 99, 0, 'SA', 'KVXP', NULL, '2017-10-03 08:50:55', '2017-10-03 08:50:55'),
(10, '1506416715_1507020667', 'Chủ tịch Hội Nông dân', NULL, '', 99, 0, 'SA', 'KVXP', NULL, '2017-10-03 08:51:07', '2017-10-03 08:51:07'),
(11, '1506416715_1507020680', 'Chủ tịch Hội Cựu chiến binh', NULL, '', 99, 0, 'SA', 'KVXP', NULL, '2017-10-03 08:51:20', '2017-10-03 08:51:20'),
(12, '1506416715_1507476180', 'Kế toán tài chính', NULL, '', 99, 0, 'SA', 'KVXP', NULL, '2017-10-08 15:23:00', '2017-10-08 15:23:00'),
(13, '1506416715_1507476191', 'Cán bộ', NULL, '', 99, 0, 'SA', 'KVXP', NULL, '2017-10-08 15:23:11', '2017-10-08 15:23:11'),
(14, '1506416715_1507600630', 'Tư pháp - Hộ tịch', NULL, '', 99, 0, 'SA', 'KVXP', NULL, '2017-10-10 01:57:10', '2017-10-10 01:57:10'),
(15, '1506416715_1507600652', 'Văn hóa - Xã hội ', NULL, '', 99, 0, 'SA', 'KVXP', NULL, '2017-10-10 01:57:32', '2017-10-10 01:57:32'),
(16, '1506416715_1507600668', 'Văn phòng - Thống kê', NULL, '', 99, 0, 'SA', 'KVXP', NULL, '2017-10-10 01:57:48', '2017-10-10 01:57:48'),
(17, '1506416715_1507601059', 'Chỉ huy trưởng quân sự', NULL, '', 99, 0, 'SA', 'KVXP', NULL, '2017-10-10 02:04:19', '2017-10-10 02:04:19'),
(18, '1506416715_1507601073', 'Trưởng công an', NULL, '', 99, 0, 'SA', 'KVXP', NULL, '2017-10-10 02:04:33', '2017-10-10 02:04:33'),
(19, '1506416715_1507601097', 'Địa chính - Xây dựng', NULL, '', 99, 0, 'SA', 'KVXP', NULL, '2017-10-10 02:04:57', '2017-10-10 02:04:57'),
(20, '1506416715_1507601117', 'Lao động - Thương binh & xã hội', NULL, '', 99, 0, 'SA', 'KVXP', NULL, '2017-10-10 02:05:17', '2017-10-10 02:05:17'),
(21, '1506416715_1507601156', 'Cán bộ lâm nghiệp', NULL, '', 99, 0, 'SA', 'KVXP', NULL, '2017-10-10 02:05:56', '2017-10-10 02:05:56'),
(22, '1506416715_1507601173', 'Văn thư - Lưu trữ', NULL, '', 99, 0, 'SA', 'KVXP', NULL, '2017-10-10 02:06:13', '2017-10-10 02:06:13'),
(23, '1506416715_1507601182', 'Công an viên', NULL, '', 99, 0, 'SA', 'KVXP', NULL, '2017-10-10 02:06:22', '2017-10-10 02:06:22'),
(24, '_1507601256', 'Hiệu trưởng', NULL, '', 1, 0, 'SA', 'KVHCSN', NULL, '2017-10-10 02:07:36', '2017-10-24 09:04:55'),
(25, '_1507601269', 'Phó Hiệu trưởng', NULL, '', 2, 0, 'SA', 'KVHCSN', NULL, '2017-10-10 02:07:49', '2017-10-24 09:05:07'),
(26, '_1507601295', 'Tổ trưởng - Giáo viên', NULL, '', 3, 0, 'SA', 'KVHCSN', NULL, '2017-10-10 02:08:15', '2017-10-24 09:05:21'),
(27, '_1507601305', 'Tổ phó - Giáo viên', NULL, '', 4, 0, 'SA', 'KVHCSN', NULL, '2017-10-10 02:08:25', '2017-10-24 01:13:53'),
(28, '_1507601316', 'Tổ trưởng - Y tế', NULL, '', 5, 0, 'SA', 'KVHCSN', NULL, '2017-10-10 02:08:36', '2017-10-24 01:14:09'),
(29, '_1507601323', 'Kế toán trưởng', NULL, '', 5, 0, 'SA', 'KVHCSN', NULL, '2017-10-10 02:08:43', '2017-10-24 00:25:14'),
(30, '_1507601349', 'Kế toán', NULL, '', 6, 0, 'SA', 'KVHCSN', NULL, '2017-10-10 02:09:09', '2017-10-24 00:25:26'),
(32, '_1507601406', 'Trưởng trạm ', NULL, '', 7, 0, 'SA', 'KVHCSN', NULL, '2017-10-10 02:10:07', '2017-10-24 00:25:42'),
(33, '_1507601413', 'Phó trưởng trạm ', NULL, '', 8, 0, 'SA', 'KVHCSN', NULL, '2017-10-10 02:10:13', '2017-10-24 00:26:13'),
(34, '_1507601423', 'Cán bộ trạm ', NULL, '', 99, 0, 'SA', 'KVHCSN', NULL, '2017-10-10 02:10:23', '2017-10-23 09:55:56'),
(35, '_1507601429', 'Cán bộ Phòng ', NULL, '', 99, 0, 'SA', 'KVHCSN', NULL, '2017-10-10 02:10:29', '2017-10-23 09:57:20'),
(37, '_1507601444', 'Văn thư', NULL, '', 99, 0, 'SA', 'KVHCSN', NULL, '2017-10-10 02:10:44', '2017-10-10 02:10:44'),
(38, '1508728324_1508734674', 'Giám đốc', NULL, '', 99, 0, 'SA', 'KVXP', NULL, '2017-10-23 04:57:54', '2017-10-23 04:57:54'),
(63, '1508148190_1508745018', 'Phụ trách Phòng', NULL, '', 99, 0, 'SA', 'KVHCSN', NULL, '2017-10-23 07:50:18', '2017-10-24 00:17:22'),
(65, '1508148190_1508745088', 'Tạp vụ', NULL, '', 99, 0, 'SA', 'KVHCSN', NULL, '2017-10-23 07:51:28', '2017-10-23 07:51:28'),
(66, '1508148190_1508745109', 'Lái xe ', NULL, '', 99, 0, 'SA', 'KVHCSN', NULL, '2017-10-23 07:51:49', '2017-10-23 07:51:49'),
(68, '1508148190_1508745280', 'Bảo vệ', NULL, '', 99, 0, 'SA', 'KVHCSN', NULL, '2017-10-23 07:54:40', '2017-10-23 07:54:40'),
(69, '1508148190_1508804602', 'Chi Cục Trưởng', NULL, '', 1, 0, 'SA', 'KVHCSN', NULL, '2017-10-24 00:23:22', '2017-10-24 00:24:19'),
(70, '1508148190_1508804647', 'Phó Chi cục trưởng', NULL, '', 2, 0, 'SA', 'KVHCSN', NULL, '2017-10-24 00:24:07', '2017-10-24 00:24:07'),
(72, '1506586040_1510623373', 'Chuyên viên', NULL, '', 99, 0, 'SA', 'KVHCSN', NULL, NULL, NULL),
(73, '1508147182_1510821993', 'Tổ phó - Kế toán', NULL, '', 6, 0, 'SA', 'KVHCSN', NULL, NULL, NULL),
(74, '1511709453_1511920516', 'Ngô Thị Vy', NULL, 'hiệu trưởng', 99, 0, 'SA', '', NULL, NULL, NULL),
(75, '1511709583_1511920531', 'Hiệu trưởng', NULL, '', 99, 0, 'SA', '', NULL, NULL, NULL),
(76, '1511709583_1511920532', 'Hiệu trưởng', NULL, '', 99, 0, 'SA', '', NULL, NULL, NULL),
(77, '1511710657_1511920537', 'Hiệu trưởng', NULL, 'Công chức', 99, 0, 'SA', '', NULL, NULL, NULL),
(78, '1511710234_1511920543', 'Hiệutrưởng', NULL, '', 99, 0, 'SA', '', NULL, NULL, NULL),
(79, '1511710477_1511920551', 'Hiệu trưởng', NULL, '', 99, 0, 'SA', '', NULL, NULL, NULL),
(80, '1511710477_1511920552', 'Hiệu trưởng', NULL, '', 99, 0, 'SA', '', NULL, NULL, NULL),
(81, '1511710657_1511920566', 'Phó hiệu trưởng', NULL, '', 99, 0, 'SA', '', NULL, NULL, NULL),
(82, '1511710331_1511920566', 'Hiệu Trưởng', NULL, '', 1, 0, 'SA', '', NULL, NULL, NULL),
(83, '1511709513_1511920569', 'Hiệu trưởng', NULL, '', 1, 0, 'SA', '', NULL, NULL, NULL),
(84, '1511710520_1511920570', 'Hiệu trưởng', NULL, '', 99, 0, 'SA', '', NULL, NULL, NULL),
(85, '1511710284_1511920579', 'Hiệu trường', NULL, '', 99, 0, 'SA', '', NULL, NULL, NULL),
(86, '1511710657_1511920579', 'Giáo viên', NULL, '', 99, 0, 'SA', '', NULL, NULL, NULL),
(87, '1511709940_1511920580', 'Hiệu trưởng', NULL, '', 99, 0, 'SA', '', NULL, NULL, NULL),
(88, '1511709453_1511920580', 'ngô thị vy', NULL, '', 99, 0, 'SA', '', NULL, NULL, NULL),
(89, '1511710589_1511920580', 'Hiệu trưởng', NULL, '', 99, 0, 'SA', '', NULL, NULL, NULL),
(90, '1511709906_1511920582', 'Hieu truong', NULL, 'Giao vien', 99, 0, 'SA', '', NULL, NULL, NULL),
(91, '1511709583_1511920582', 'Phó Hiệu trưởng', NULL, '', 2, 0, 'SA', '', NULL, NULL, NULL),
(92, '1511710158_1511920592', 'Hiệu trưởng', NULL, '', 1, 0, 'SA', '', NULL, NULL, NULL),
(93, '1511710331_1511920596', 'Hiệu Trưởng', NULL, 'quản lý', 1, 0, 'SA', '', NULL, NULL, NULL),
(94, '1511710657_1511920598', 'Kế toán', NULL, '', 99, 0, 'SA', '', NULL, NULL, NULL),
(95, '1511709376_1511920599', 'hiệu trưởng', NULL, 'hiệu trưởng', 99, 0, 'SA', '', NULL, NULL, NULL),
(96, '1511709632_1511920604', 'Hiệu trưởng', NULL, '', 1, 0, 'SA', '', NULL, NULL, NULL),
(97, '1511709961_1511920604', 'hieu truong', NULL, '', 99, 0, 'SA', '', NULL, NULL, NULL),
(98, '1511710033_1511920606', 'Hiệu Trưởng', NULL, '', 1, 0, 'SA', '', NULL, NULL, NULL),
(100, '1511709859_1511920611', 'Hiệu trưởng', NULL, '', 1, 0, 'SA', '', NULL, NULL, NULL),
(101, '1511710657_1511920614', 'văn thư', NULL, '', 99, 0, 'SA', '', NULL, NULL, NULL),
(102, '1511709787_1511920622', 'HIỆU TRƯỞNG', NULL, '', 1, 0, 'SA', '', NULL, NULL, NULL),
(104, '1511710657_1511920622', 'Y tế', NULL, '', 99, 0, 'SA', '', NULL, NULL, NULL),
(105, '1511709787_1511920623', 'HIỆU TRƯỞNG', NULL, '', 1, 0, 'SA', '', NULL, NULL, NULL),
(106, '1511709714_1511920627', 'Hiệu trưởng', NULL, '', 1, 0, 'SA', '', NULL, NULL, NULL),
(107, '1511709484_1511920632', 'Hiệu Trưởng', NULL, 'quản lý', 1, 0, 'SA', '', NULL, NULL, NULL),
(108, '1511710657_1511920632', 'Phục vụ', NULL, '', 99, 0, 'SA', '', NULL, NULL, NULL),
(110, '1511710657_1511920646', 'Bảo vệ', NULL, '', 99, 0, 'SA', '', NULL, NULL, NULL),
(111, '1511710033_1511920656', 'Phó Hiệu Trưởng', NULL, '', 2, 0, 'SA', '', NULL, NULL, NULL),
(112, '1511710657_1511920660', 'Cấp dưỡng', NULL, '', 99, 0, 'SA', '', NULL, NULL, NULL),
(113, '1511709714_1511920664', 'Phó hiệu trưởng', NULL, '', 2, 0, 'SA', '', NULL, NULL, NULL),
(114, '1511709714_1511920665', 'Phó hiệu trưởng', NULL, '', 2, 0, 'SA', '', NULL, NULL, NULL),
(115, '1511709737_1511920666', 'HIỆU TRƯỞNG', NULL, 'Hiệu trưởng\nP.Hiệu trưởng\nGiáo viên\nGiáo viên -Tổ trưởng\nKế toán\nThư viện- Tổ trưởng\nBảo vệ\nTạp vụ\nThiết bị', 99, 0, 'SA', '', NULL, NULL, NULL),
(116, '1511710355_1511920675', 'Hiệu Trưởng', NULL, '', 99, 0, 'SA', '', NULL, NULL, NULL),
(117, '1511710589_1511920683', 'Hiệu trưởng', NULL, 'Quản lý', 1, 0, 'SA', '', NULL, NULL, NULL),
(118, '1511709583_1511920687', 'Tổ trưởng chuyên môn', NULL, '', 3, 0, 'SA', '', NULL, NULL, NULL),
(119, '1511709714_1511920703', 'Giáo viên', NULL, '', 3, 0, 'SA', '', NULL, NULL, NULL),
(121, '1511709714_1511920730', 'TPT đội', NULL, '', 4, 0, 'SA', '', NULL, NULL, NULL),
(123, '1511709608_1511920740', 'Hiệu Trưởng', NULL, '', 1, 0, 'SA', '', NULL, NULL, NULL),
(124, '1511709583_1511920740', 'Tổ phó chuyên môn', NULL, '', 4, 0, 'SA', '', NULL, NULL, NULL),
(125, '1511709513_1511920748', 'Phó hiệu trưởng', NULL, '', 99, 0, 'SA', '', NULL, NULL, NULL),
(126, '1511710033_1511920756', 'Giáo Viên', NULL, '', 3, 0, 'SA', '', NULL, NULL, NULL),
(127, '1511710033_1511920757', 'Giáo Viên', NULL, '', 3, 0, 'SA', '', NULL, NULL, NULL),
(129, '1511710033_1511920758', 'Giáo Viên', NULL, '', 3, 0, 'SA', '', NULL, NULL, NULL),
(130, '1511710477_1511920760', 'Hiệu trưởng', NULL, '', 1, 0, 'SA', '', NULL, NULL, NULL),
(131, '1511710477_1511920761', 'Hiệu trưởng', NULL, '', 1, 0, 'SA', '', NULL, NULL, NULL),
(132, '1511709714_1511920771', 'Kế toán', NULL, '', 5, 0, 'SA', '', NULL, NULL, NULL),
(133, '1511710033_1511920784', 'Văn Thư', NULL, '', 4, 0, 'SA', '', NULL, NULL, NULL),
(134, '1511709714_1511920793', 'Văn thư', NULL, '', 5, 0, 'SA', '', NULL, NULL, NULL),
(135, '1511709787_1511920807', 'Hiệu Trưởng', NULL, '', 1, 0, 'SA', '', NULL, NULL, NULL),
(136, '1511709714_1511920811', 'Thư viên', NULL, '', 6, 0, 'SA', '', NULL, NULL, NULL),
(137, '1511710033_1511920814', 'Thư Viện', NULL, '', 5, 0, 'SA', '', NULL, NULL, NULL),
(138, '1511710033_1511920848', 'Thiết Bị', NULL, '', 6, 0, 'SA', '', NULL, NULL, NULL),
(139, '1511709714_1511920849', 'Nhân viên bảo vệ', NULL, '', 7, 0, 'SA', '', NULL, NULL, NULL),
(140, '1511709561_1511920854', 'Hiệu trưởng', NULL, '', 1, 0, 'SA', '', NULL, NULL, NULL),
(141, '1511710033_1511920862', 'Y Tế', NULL, '', 7, 0, 'SA', '', NULL, NULL, NULL),
(142, '1511709714_1511920871', 'Nhân viên phục vụ', NULL, '', 8, 0, 'SA', '', NULL, NULL, NULL),
(143, '1511710009_1511920922', 'Hiệu trưởng', NULL, 'Quản lý trường học', 1, 0, 'SA', '', NULL, NULL, NULL),
(144, '1511709632_1511920945', '2', NULL, 'Phó Hiệu trưởng', 2, 0, 'SA', '', NULL, NULL, NULL),
(145, '1511709583_1511920954', 'Hiệu trưởng', NULL, '', 1, 0, 'SA', '', NULL, NULL, NULL),
(146, '1511710033_1511920974', 'Kế Toán', NULL, '', 8, 0, 'SA', '', NULL, NULL, NULL),
(147, '1511709632_1511920984', 'Tổ trưởng', NULL, '', 3, 0, 'SA', '', NULL, NULL, NULL),
(148, '1511709632_1511921002', 'Tổ phó', NULL, '', 4, 0, 'SA', '', NULL, NULL, NULL),
(149, '1511710009_1511921024', 'Phó Hiệu trưởng', NULL, 'Quản lí chuyên môn', 2, 0, 'SA', '', NULL, NULL, NULL),
(150, '1511710033_1511921050', 'Nhân Viên Bảo Vệ', NULL, '', 9, 0, 'SA', '', NULL, NULL, NULL),
(151, '1511710033_1511921118', 'Nhân Viên Phục Vụ', NULL, '', 10, 0, 'SA', '', NULL, NULL, NULL),
(152, '1511709513_1511921163', 'Giáo viên', NULL, '', 99, 0, 'SA', '', NULL, NULL, NULL),
(153, '1511710009_1511921169', 'Giáo viên', NULL, 'Tổ trưởng', 3, 0, 'SA', '', NULL, NULL, NULL),
(154, '1511709940_1511921225', 'Hiệu trưởng', NULL, '', 1, 0, 'SA', '', NULL, NULL, NULL),
(155, '1511710520_1511921286', 'Giáo viên', NULL, 'biên chế', 99, 0, 'SA', '', NULL, NULL, NULL),
(156, '1511709583_1511921316', 'Hiệu Trưởng', NULL, '', 1, 0, 'SA', '', NULL, NULL, NULL),
(157, '1511710520_1511921353', 'Phó Hiệu trưởng', NULL, '', 99, 0, 'SA', '', NULL, NULL, NULL),
(158, '1511710520_1511921354', 'Phó Hiệu trưởng', NULL, '', 99, 0, 'SA', '', NULL, NULL, NULL),
(160, '1511710355_1511921408', 'Hiệu Trưởng', NULL, '', 1, 0, 'SA', '', NULL, NULL, NULL),
(161, '1511709583_1511921526', 'Hiệu Trưởng', NULL, '', 99, 0, 'SA', '', NULL, NULL, NULL),
(162, '1511709561_1511921579', 'hieu truong', NULL, '', 99, 0, 'SA', '', NULL, NULL, NULL),
(163, '1511710520_1511921732', 'hiệu trưởng', NULL, '', 99, 0, 'SA', '', NULL, NULL, NULL),
(164, '1511710234_1511921807', 'Phó hiệu trưởng', NULL, '', 99, 0, 'SA', '', NULL, NULL, NULL),
(165, '1511709984_1511922567', 'nhân viên phục vụ', NULL, '', 6, 0, 'SA', 'KVHCSN', NULL, NULL, NULL),
(166, '1511709984_1511922617', 'nhân viên bảo vệ', NULL, '', 8, 0, 'SA', 'KVHCSN', NULL, NULL, NULL),
(167, '1511709984_1511922618', 'nhân viên bảo vệ', NULL, '', 8, 0, 'SA', 'KVHCSN', NULL, NULL, NULL),
(168, '1511585223_1511922662', 'Giám Đốc', NULL, '', 99, 0, 'SA', 'KVHCSN', NULL, NULL, NULL),
(169, '1511709537_1511922665', 'Hiệu trưởng ', NULL, 'Trần Văn Kim', 99, 0, 'SA', 'KVHCSN', NULL, NULL, NULL),
(171, '1511585223_1511922772', 'Phó Giasm Đốc', NULL, '', 99, 0, 'SA', 'KVHCSN', NULL, NULL, NULL),
(172, '1511585223_1511922854', 'Phó Giám Đốc', NULL, '', 100, 0, 'SA', 'KVHCSN', NULL, NULL, NULL),
(173, '1511585223_1511927155', 'giảng viên', NULL, '', 99, 0, 'SA', 'KVHCSN', NULL, NULL, NULL),
(174, '1511585034_1511941462', 'Trưởng phòng', NULL, '', 99, 0, 'SA', 'KVHCSN', NULL, NULL, NULL),
(175, '1511585380_1511941578', 'Chủ tịch', NULL, '', 99, 0, 'SA', 'KVHCSN', NULL, NULL, NULL),
(176, '1511584864_1511941822', 'Phó trưởng phòng', NULL, '', 99, 0, 'SA', 'KVHCSN', NULL, NULL, NULL),
(177, '1511585289_1511942170', 'Phó Chủ tịch', NULL, '', 99, 0, 'SA', 'KVHCSN', NULL, NULL, NULL),
(178, '1511585289_1511942223', 'Ủy viên Thường trực', NULL, '', 99, 0, 'SA', 'KVHCSN', NULL, NULL, NULL),
(179, '1511585510_1511942684', 'Phó trưởng đài', NULL, '', 99, 0, 'SA', 'KVHCSN', NULL, NULL, NULL),
(180, '1511585510_1511942764', 'Trưởng đài', NULL, '', 99, 0, 'SA', 'KVHCSN', NULL, NULL, NULL),
(181, '1511584864_1511942883', 'Nhân viên hợp đồng', NULL, '', 99, 0, 'SA', 'KVHCSN', NULL, NULL, NULL),
(182, '1511585771_1511942896', 'Chủ Tịch HĐND', NULL, '', 99, 0, 'SA', 'KVHCSN', NULL, NULL, NULL),
(183, '1511585771_1511942913', 'Phó Chủ Tịch HĐND', NULL, '', 99, 0, 'SA', 'KVHCSN', NULL, NULL, NULL),
(184, '1511585510_1511943375', 'Phóng viên', NULL, '', 99, 0, 'SA', 'KVHCSN', NULL, NULL, NULL),
(185, '1511585510_1511943391', 'Kỷ thuật', NULL, '', 99, 0, 'SA', 'KVHCSN', NULL, NULL, NULL),
(186, '1511585510_1511943404', 'Bảo vệ', NULL, '', 99, 0, 'SA', 'KVHCSN', NULL, NULL, NULL),
(187, '1511585771_1511943516', 'Công chức ĐỊa chính - Xây dựng', NULL, '', 30, 0, 'SA', 'KVHCSN', NULL, NULL, NULL),
(188, '1511585771_1511943568', 'Công chức Văn hóa - Xã hội', NULL, '', 43, 0, 'SA', 'KVHCSN', NULL, NULL, NULL),
(189, '1511585771_1511943598', 'Công chức Tư pháp - Hộ tịch', NULL, '', 44, 0, 'SA', 'KVHCSN', NULL, NULL, NULL),
(190, '1511585771_1511943630', 'Công chức Tài chính - Kế toán', NULL, '', 47, 0, 'SA', 'KVHCSN', NULL, NULL, NULL),
(191, '1511710452_1511947239', 'Giáo viên ', NULL, '', 99, 0, 'SA', 'KVHCSN', NULL, NULL, NULL),
(192, '1511581927_1512006010', 'Bí thư', NULL, '', 99, 0, 'SA', 'KVHCSN', NULL, NULL, NULL),
(193, '1511581883_1512006080', 'Chu tich', NULL, '', 99, 0, 'SA', 'KVHCSN', NULL, NULL, NULL),
(194, '1511582547_1512006106', 'Bí thư Đảng ủy', NULL, 'Nguyễn Văn Hưng', 99, 0, 'SA', 'KVXP', NULL, NULL, NULL),
(195, '1511582951_1512006166', 'Tổ trưởng kế toán', NULL, '', 99, 0, 'SA', 'KVHCSN', NULL, NULL, NULL),
(196, '1511582402_1512006195', 'Chủ tịch xã', NULL, '', 99, 0, 'SA', 'KVHCSN', NULL, NULL, NULL),
(197, '1511582909_1512006211', 'Tổ trưởng-Kế toán', NULL, '', 99, 0, 'SA', 'KVHCSN', NULL, NULL, NULL),
(198, '1511581906_1512006227', 'Chủ tịch', NULL, '', 99, 0, 'SA', 'KVHCSN', NULL, NULL, NULL),
(199, '1511581906_1512006240', 'Phó Chủ tịch', NULL, '', 99, 0, 'SA', 'KVHCSN', NULL, NULL, NULL),
(200, '1511582909_1512006246', 'Tổ phó - Văn thư', NULL, '', 99, 0, 'SA', 'KVHCSN', NULL, NULL, NULL),
(201, '1511581906_1512006251', 'Chuyên viên', NULL, '', 99, 0, 'SA', 'KVHCSN', NULL, NULL, NULL),
(202, '1511582757_1512006271', 'Tổ trưởng - kế toán', NULL, '', 99, 0, 'SA', 'KVHCSN', NULL, NULL, NULL),
(203, '1511581906_1512006274', 'kế toán', NULL, '', 99, 0, 'SA', 'KVHCSN', NULL, NULL, NULL),
(204, '1511582757_1512006293', 'Tổ phó - Y tế', NULL, '', 99, 0, 'SA', 'KVHCSN', NULL, NULL, NULL),
(205, '1511581490_1512006407', 'Trưởng phòng', NULL, '', 99, 0, 'SA', 'KVXP', NULL, NULL, NULL),
(206, '1511581490_1512006421', 'Phó Trưởng Phòng', NULL, '', 99, 0, 'SA', 'KVXP', NULL, NULL, NULL),
(207, '1511581927_1512006642', 'Bí thư', NULL, 'Lê Thanh Văn', 99, 0, 'SA', 'KVHCSN', NULL, NULL, NULL),
(208, '1511581750_1512006987', 'Chánh Thanh tra', NULL, '', 99, 0, 'SA', 'KVHCSN', NULL, NULL, NULL),
(209, '1511583196_1512007990', 'Nhân viên y tế', NULL, '', 99, 0, 'SA', 'KVHCSN', NULL, NULL, NULL),
(210, '1511583196_1512008007', 'Nhân viên cấp dưỡng', NULL, '', 99, 0, 'SA', 'KVHCSN', NULL, NULL, NULL),
(211, '1511583557_1512027985', 'Nguyễn Đông Hà', NULL, 'Hiệu Trưởng', 1, 0, 'SA', 'KVHCSN', NULL, NULL, NULL),
(213, '1511583445_1512028043', 'Võ Thị Thúy', NULL, '', 99, 0, 'SA', 'KVHCSN', NULL, NULL, NULL),
(214, '1511709376_1512029200', 'Hiệu trưởng', NULL, '', 99, 0, 'SA', 'KVHCSN', NULL, NULL, NULL),
(215, '1511709376_1512029233', 'Hiệu trưởng', NULL, 'Hiệu trưởng', 99, 0, 'SA', 'KVHCSN', NULL, NULL, NULL),
(216, '1511709376_1512029270', 'Phó Hiệu trưởng', NULL, 'Phó Hiệu trơngr', 99, 0, 'SA', 'KVHCSN', NULL, NULL, NULL),
(217, '1511709376_1512029295', 'Tổ trưởng', NULL, 'Tổ trưởng', 99, 0, 'SA', 'KVHCSN', NULL, NULL, NULL),
(218, '1511582688_1512029791', 'BTĐU+CTHĐND+TCĐU', NULL, '', 99, 0, 'SA', 'KVXP', NULL, NULL, NULL),
(219, '1511583703_1512030675', 'Thư viện', NULL, '', 99, 0, 'SA', 'KVHCSN', NULL, NULL, NULL),
(220, '1511582688_1512030985', 'CT.UBND', NULL, '', 99, 0, 'SA', 'KVXP', NULL, NULL, NULL),
(221, '1511582688_1512031046', 'PBT.Đảng Ủy+CNUBKT+TKV', NULL, '', 99, 0, 'SA', 'KVXP', NULL, NULL, NULL),
(222, '1511582688_1512031321', 'PCT.HĐND+CT.Công đoàn', NULL, '', 99, 0, 'SA', 'KVXP', NULL, NULL, NULL),
(223, '1511710196_1512181607', 'Hiệu trưởng', NULL, 'Hồ Văn Quốc', 1, 0, 'SA', 'KVHCSN', NULL, NULL, NULL),
(224, '1511710196_1512181640', 'Phó Hiệu trưởng', NULL, 'Mạch Đình Liêm', 2, 0, 'SA', 'KVHCSN', NULL, NULL, NULL),
(225, '1511710196_1512181668', 'Tổ trưởng Văn- Anh', NULL, 'Nguyễn Thị Băng Sương', 3, 0, 'SA', 'KVHCSN', NULL, NULL, NULL),
(226, '1511710196_1512181713', 'Nguyễn Thị Thu Nga', NULL, 'Tổ trưởng Sử- Địa- GGDCD', 4, 0, 'SA', 'KVHCSN', NULL, NULL, NULL),
(227, '1511710196_1512181741', 'Tổ trưởng Hóa- Sinh', NULL, 'Đỗ Thị Mỹ Hạnh', 5, 0, 'SA', 'KVHCSN', NULL, NULL, NULL),
(228, '1511710196_1512181776', 'Tổ Phó -Hóa- sinh', NULL, 'Lê Minh Thương', 99, 0, 'SA', 'KVHCSN', NULL, NULL, NULL),
(229, '1511708509_1512373192', 'Thanh tra viên ', NULL, '', 99, 0, 'SA', 'KVHCSN', NULL, NULL, NULL),
(230, '1511709092_1512374155', 'PBT Đảng ủy + CT HĐND', NULL, '', 99, 0, 'SA', 'KVXP', NULL, NULL, NULL),
(231, '1511709961_1512386387', 'Tổ trưởng - Kế toán ', NULL, '', 99, 0, 'SA', 'KVHCSN', NULL, NULL, NULL),
(232, '1511709961_1512386422', 'Tổ phó - Văn thư', NULL, '', 99, 0, 'SA', 'KVHCSN', NULL, NULL, NULL),
(233, '1511709961_1512386976', 'Nguyễn Minh Nhật ', NULL, 'Phó Hiệu trưởng', 99, 0, 'SA', 'KVHCSN', NULL, NULL, NULL),
(234, '1511709961_1512387030', 'Lương Đình Bình', NULL, 'Hiệu trưởng', 99, 0, 'SA', 'KVHCSN', NULL, NULL, NULL),
(235, '1519985070_1521602439', 'Chánh văn phòng', NULL, '', 99, 0, '1519985070', 'KVHCSN', NULL, NULL, NULL),
(236, '1519985070_1521602521', 'Thủ quỹ', NULL, '', 99, 0, '1519985070', 'KVHCSN', NULL, NULL, NULL),
(237, '1519985070_1521603524', 'Phó Chánh Thanh Tra', NULL, '', 99, 0, '1519985070', 'KVHCSN', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `dmchucvud`
--

CREATE TABLE `dmchucvud` (
  `id` int(10) UNSIGNED NOT NULL,
  `macvd` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `tencv` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `ghichu` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `sapxep` int(11) NOT NULL DEFAULT '99',
  `madv` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `dmdantoc`
--

CREATE TABLE `dmdantoc` (
  `id` int(10) UNSIGNED NOT NULL,
  `dantoc` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `thieuso` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `dmdantoc`
--

INSERT INTO `dmdantoc` (`id`, `dantoc`, `thieuso`, `created_at`, `updated_at`) VALUES
(1, 'Tày', 1, NULL, NULL),
(2, 'Bana', 1, NULL, NULL),
(3, 'Bố Y', 1, NULL, NULL),
(4, 'Brâu', 1, NULL, NULL),
(5, 'Bru', 1, NULL, NULL),
(6, 'Chăm (chàm)', 1, NULL, NULL),
(7, 'Chơ ro', 1, NULL, NULL),
(8, 'Chu Ru', 1, NULL, NULL),
(9, 'Chứt', 1, NULL, NULL),
(10, 'Co', 1, NULL, NULL),
(11, 'Cơ Ho', 1, NULL, NULL),
(12, 'Cơ Lao', 1, NULL, NULL),
(13, 'Cơ Tu', 1, NULL, NULL),
(14, 'Cống', 1, NULL, NULL),
(15, 'Dao', 1, NULL, NULL),
(16, 'Ê Đê', 1, NULL, NULL),
(17, 'Gia Rai', 1, NULL, NULL),
(18, 'Giáy', 1, NULL, NULL),
(19, 'Giẻ Triêng', 1, NULL, NULL),
(20, 'H`Rê', 1, NULL, NULL),
(21, 'Hà Nhì', 1, NULL, NULL),
(22, 'Hoa', 1, NULL, NULL),
(23, 'Kháng', 1, NULL, NULL),
(24, 'Khơ mú', 1, NULL, NULL),
(25, 'Kinh', 0, NULL, NULL),
(26, 'La Chí', 1, NULL, NULL),
(27, 'La Ha', 1, NULL, NULL),
(28, 'La Hủ', 1, NULL, NULL),
(29, 'Lào', 1, NULL, NULL),
(30, 'Lô Lô', 1, NULL, NULL),
(31, 'Lù', 1, NULL, NULL),
(32, 'M`Nông', 1, NULL, NULL),
(33, 'Mạ', 1, NULL, NULL),
(34, 'Máng', 1, NULL, NULL),
(35, 'Mông (mèo)', 1, NULL, NULL),
(36, 'Mường', 1, NULL, NULL),
(37, 'Ngái', 1, NULL, NULL),
(38, 'Nùng', 1, NULL, NULL),
(39, 'Ơ Đu', 1, NULL, NULL),
(40, 'Phù La', 1, NULL, NULL),
(41, 'Pò Thẻn', 1, NULL, NULL),
(42, 'Pu Péo', 1, NULL, NULL),
(43, 'Ra Glai', 1, NULL, NULL),
(44, 'Rơ măm', 1, NULL, NULL),
(45, 'Sán chay', 1, NULL, NULL),
(46, 'Sán Dìu', 1, NULL, NULL),
(47, 'Si La', 1, NULL, NULL),
(48, 'Tà Ôi', 1, NULL, NULL),
(49, 'Thái', 1, NULL, NULL),
(50, 'Thổ', 1, NULL, NULL),
(51, 'Xinh Mun', 1, NULL, NULL),
(52, 'Xtiêng', 1, NULL, NULL),
(53, 'Xu đăng', 1, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `dmdiabandbkk`
--

CREATE TABLE `dmdiabandbkk` (
  `id` int(10) UNSIGNED NOT NULL,
  `madiaban` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `tendiaban` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `ngaytu` date DEFAULT NULL,
  `thangtu` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `namtu` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `ngayden` date DEFAULT NULL,
  `thangden` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `namden` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `phanloai` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `madv` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `ghichu` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `dmdiabandbkk_chitiet`
--

CREATE TABLE `dmdiabandbkk_chitiet` (
  `id` int(10) UNSIGNED NOT NULL,
  `madiaban` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `macanbo` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `ghichu` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `dmdonvi`
--

CREATE TABLE `dmdonvi` (
  `id` int(10) UNSIGNED NOT NULL,
  `madv` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `maqhns` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `tendv` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `diachi` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `sodt` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `lanhdao` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `songuoi` int(11) NOT NULL DEFAULT '0',
  `macqcq` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `diadanh` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `cdlanhdao` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `nguoilapbieu` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `makhoipb` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `madvbc` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `maphanloai` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `capdonvi` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `phanloaixa` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `phanloainguon` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `linhvuchoatdong` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `phanloaitaikhoan` varchar(50) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'SD',
  `phamvitonghop` varchar(50) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'KHOI',
  `pcct` double NOT NULL DEFAULT '0',
  `pckct` double NOT NULL DEFAULT '0',
  `pck` double NOT NULL DEFAULT '0',
  `pccv` double NOT NULL DEFAULT '0',
  `pckv` double NOT NULL DEFAULT '0',
  `pcth` double NOT NULL DEFAULT '0',
  `pcdd` double NOT NULL DEFAULT '0',
  `pcdh` double NOT NULL DEFAULT '0',
  `pcld` double NOT NULL DEFAULT '0',
  `pcdbqh` double NOT NULL DEFAULT '0',
  `pcudn` double NOT NULL DEFAULT '0',
  `pctn` double NOT NULL DEFAULT '0',
  `pctnn` double NOT NULL DEFAULT '0',
  `pcdbn` double NOT NULL DEFAULT '0',
  `pcvk` double NOT NULL DEFAULT '0',
  `pckn` double NOT NULL DEFAULT '0',
  `pcdang` double NOT NULL DEFAULT '0',
  `pccovu` double NOT NULL DEFAULT '0',
  `pclt` double NOT NULL DEFAULT '0',
  `pcd` double NOT NULL DEFAULT '0',
  `pctr` double NOT NULL DEFAULT '0',
  `pctnvk` double NOT NULL DEFAULT '0',
  `pcbdhdcu` double NOT NULL DEFAULT '0',
  `pcthni` double NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `dmdonvi`
--

INSERT INTO `dmdonvi` (`id`, `madv`, `maqhns`, `tendv`, `diachi`, `sodt`, `lanhdao`, `songuoi`, `macqcq`, `diadanh`, `cdlanhdao`, `nguoilapbieu`, `makhoipb`, `madvbc`, `maphanloai`, `capdonvi`, `phanloaixa`, `phanloainguon`, `linhvuchoatdong`, `phanloaitaikhoan`, `phamvitonghop`, `pcct`, `pckct`, `pck`, `pccv`, `pckv`, `pcth`, `pcdd`, `pcdh`, `pcld`, `pcdbqh`, `pcudn`, `pctn`, `pctnn`, `pcdbn`, `pcvk`, `pckn`, `pcdang`, `pccovu`, `pclt`, `pcd`, `pctr`, `pctnvk`, `pcbdhdcu`, `pcthni`, `created_at`, `updated_at`) VALUES
(33, '1511581269', NULL, 'Văn Phòng HĐND - UBND', 'thị trấn Cam Đức, huyện Cam Lâm', '02583.983.226', 'Bùi Quang Nam', 0, '1511581490', 'thị trấn Cam Đức, huyện Cam Lâm', 'Chánh văn phòng HĐND và UBND', 'Hồ Lê Ngọc Hiền', NULL, '1511578024', 'KVHCSN', '1', NULL, 'NGANSACH', NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(34, '1511581308', NULL, 'Phòng Tài Chính - Kế Hoạch', 'Huyện Khánh Sơn - Tỉnh Khánh Hòa', '', '', 0, NULL, 'Khánh Sơn', '', '', NULL, '1511580856', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(35, '1511581382', NULL, 'Phòng Nông Nghiệp và Phát Triển Nông Thôn', 'TDP Tân Hải - TT. Cam Đức - Huyện Cam Lâm - Tỉnh Khánh Hòa', '02583.983616', 'Lê Đình Cường', 0, '1511581490', 'Cam Lâm', 'Trưởng Phòng', 'Nguyễn Thị Lệ Xuân', NULL, '1511578024', 'KVHCSN', '1', NULL, 'NGANSACH', 'QLNN', 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(36, '1511581429', NULL, 'Phòng Kinh tế Hạ tầng', 'Cam Đức - Cam Lâm', '3983638', 'Trương Văn Châu', 0, '1511581490', 'Khánh Hòa', 'Trưởng phòng', 'Võ Thị Thu Hà', NULL, '1511578024', 'KVHCSN', '3', NULL, 'NGANSACH', 'QLNN', 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(37, '1511581455', NULL, 'Phòng Tư Pháp', 'Cam Đức - Cam Lâm', '02583.983299', 'Lê Thanh Hùng', 0, '1511581490', 'Khánh Hòa', 'Trưởng phòng', 'Nguyễn Thị Hoàng Oanh', NULL, '1511578024', 'KVHCSN', '3', NULL, 'NGANSACH', 'QLNN', 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(38, '1511581490', NULL, 'Phòng Tài chính Và Kế Hoạch huyện Cam Lâm', 'Thị trấn Cam Đức - huyện Cam Lâm - tỉnh Khánh Hòa', '02583.983.416 - 02583.983309', 'Trần Thị Lệ Huyền', 0, '1511581490', '', 'Trưởng phòng', 'Phạm Ngọc hoàng', NULL, '1511578024', 'KVHCSN', '4', '', 'NGANSACH', 'QLNN', 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(39, '1511581523', NULL, 'Phòng Giáo Dục Và Đào Tạo huyện Cam Lâm', '34 Nguyễn Du, Thị trấn Cam Đức, huyện Cam Lâm, tỉnh Khánh Hòa', '0258.3983399', 'Lê Anh Bằng', 0, '1511581523', 'Huyện Cam Lâm, tỉnh Khánh Hòa', 'Trưởng phòng', 'Nguyễn Thị Xuân Hiền', NULL, '1511578024', 'KVHCSN', '1', NULL, 'NGANSACH', 'GD', 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(40, '1511581552', NULL, 'Phòng Y Tế', '', '', '', 0, '1511581490', '', '', '', NULL, '1511578024', 'KVHCSN', '1', NULL, 'NGANSACH', NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(41, '1511581591', NULL, 'Văn phòng Hội đồng nhân dân & Ủy ban nhân dân', 'Huyện Khánh Sơn - Tỉnh Khánh Hòa', '', '', 0, '1511581308', 'Khánh Sơn', '', '', NULL, '1511580856', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(42, '1511581602', NULL, 'Phòng Lao Động - Thương Binh và Xã Hội huyện Cam Lâm', 'TDP Tân Hải, thị trấn Cam Đức, huyện Cam Lâm', '02583.983678', 'Phan Ngọc Châu', 0, '1511581490', '', 'Trưởng phòng', 'Nguyễn Đăng Võ', NULL, '1511578024', 'KVHCSN', '4', NULL, 'NGANSACH', 'DBXH', 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(43, '1511581642', NULL, 'Phòng Văn hóa và Thông Tin huyện Cam Lâm', 'Đường Hàm nghi, thị trấn Cam Đức', '02583 983 230', 'Mai Thị Thu Trang', 0, '1511581490', 'huyện Cam Lâm', 'TRƯỞNG PHÒNG', 'Nguyễn Thành Đức', NULL, '1511578024', 'KVHCSN', '3', NULL, 'NGANSACH', 'QLNN', 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(44, '1511581663', NULL, 'Phòng Nông Nghiệp & Phát triển Nông Thôn', 'Huyện Khánh Sơn - Tỉnh Khánh Hòa', '', '', 0, '1511581308', 'Khánh Sơn', '', '', NULL, '1511580856', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(45, '1511581672', NULL, 'Phòng Tài Nguyên Và Môi Trường', 'TDP Tân Hải, thị trấn Cam Đức, huyện Cam Lâm', '02583983577', 'Mai Như Chi', 0, '1511581490', '', 'Trưởng phòng', 'PHan Trọng Vỹ', NULL, '1511578024', 'KVHCSN', '4', NULL, 'NGANSACH', 'QLNN', 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(46, '1511581703', NULL, 'Phòng Dân Tộc', 'Cam Đức, Cam Lâm', '02583983079', 'TRần Vĩnh Hạnh', 0, '1511581490', '', 'Trưởng phòng', 'Nguyeễn Thị Thúy Vy', NULL, '1511578024', 'KVHCSN', '3', NULL, 'NGANSACH', NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(47, '1511581722', NULL, 'Phòng Kinh tế - Hạ tầng', 'Huyện Khánh Sơn - Tỉnh Khánh Hòa', '', '', 0, '1511581308', 'Khánh Sơn', '', '', NULL, '1511580856', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(48, '1511581727', NULL, 'Phòng Nội Vụ', 'Cam Đức - Cam Lâm', '01692482365', 'Võ Minh Đạt', 0, '1511581490', 'Cam Lâm - Khánh Hòa ', 'Trưởng phòng ', 'Nguyễn Thị Lệ Uyên ', NULL, '1511578024', 'KVHCSN', '3', NULL, 'NGANSACH', NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(49, '1511581750', NULL, 'Thanh tra huyện', 'TDP Tân Hải, Cam Đức, Cam Lâm', '0583983227', 'Vũ Đình Hữu', 0, '1511581490', 'Cam Lâm, Khánh Hòa', 'Chánh Thanh tra', 'Trần Lê Trà My', NULL, '1511578024', 'KVHCSN', '1', NULL, 'NGANSACH', 'QLNN', 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(52, '1511581792', NULL, 'Văn Phòng Huyện Ủy', 'TDP Tân Hải - Thị trấn Cam Đức - Huyện Cam Lâm - tỉnh Khánh Hòa', '0258.3983.579', 'Luong Dự', 0, '1511581490', '', 'Bí thư Huyện ủy', 'Trần Thị Mỹ Hạnh', NULL, '1511578024', 'KVHCSN', '1', NULL, 'NGANSACH', NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(54, '1511581831', NULL, 'Ủy Ban Mặt Trận Tổ Quốc Việt Nam', '', '', '', 0, '1511581490', '', '', '', NULL, '1511578024', 'KVHCSN', '1', NULL, 'NGANSACH', NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(56, '1511581860', NULL, 'Hội Cựu Chiến Binh', '', '', '', 0, '1511581490', '', '', '', NULL, '1511578024', 'KVHCSN', '1', NULL, 'NGANSACH', NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(57, '1511581883', NULL, 'Hội Nông Dân', 'thi tran cam duc', '02583983207', 'Nguyen Lai', 0, '1511581490', '', 'Chu tich', 'TruongThi Thu Thao', NULL, '1511578024', 'KVHCSN', '1', NULL, 'NGANSACH', NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(58, '1511581906', NULL, 'Hội Phụ Nữ', 'Cam Đức - Cam Lâm - Khánh Hòa', '0258.3983.767', 'Nguyễn Thị Kim Liên', 0, '1511581490', 'Cam Lâm - Khánh Hòa', 'Chủ tịch', 'Lý Thị Ngọc Mai', NULL, '1511578024', 'KVHCSN', '1', NULL, 'NGANSACH', NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(60, '1511581927', NULL, 'Huyện Đoàn', 'Thị trấn Cam Đức,huyện Cam Lâm', '0258.3983210', 'Lê Thanh Văn', 0, '1511581490', '', 'Bí thư', 'Trần Thị Phương Thùy', NULL, '1511578024', 'KVHCSN', '1', NULL, 'NGANSACH', NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(62, '1511582056', NULL, 'Trạm Khuyến Công - Nông - Lâm - Ngư', '', '', '', 0, '1511581490', '', '', '', NULL, '1511578024', 'KVHCSN', '1', NULL, 'NGANSACH', NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(63, '1511582085', NULL, 'Trung Tâm Văn Hóa Thể Thao', 'Đường Hàm Nghi - Thị trấn Cam Đức - huyện Cam Lâm', '0979 137 317', 'Hoàng Văn Thanh', 0, '1511581490', 'Huyện Cam Lâm', 'Phó giám đốc Phụ trách', 'Đinh Thị Hoàng Anh', NULL, '1511578024', 'KVHCSN', '3', NULL, 'NGANSACH', 'TDTT', 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(64, '1511582110', NULL, 'Nhà  Thiêu Nhi Cam Lâm', 'cam đức', '', 'hạnh', 0, '1511581490', '', 'giám đốc', 'nhật', NULL, '1511578024', 'KVHCSN', '3', NULL, 'CTXMP', 'DBXH', 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(65, '1511582119', NULL, 'Phòng Tư Pháp Khánh Sơn', '', '', '', 0, '1511581308', '', '', '', NULL, '1511580856', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(66, '1511582142', NULL, 'Đài Truyền thanh Truyền hình huyện Cam Lâm', 'Thị trấn Cam Đức, huyện Cam Lâm, tỉnh Khánh Hòa', '02583983229', 'Trần Anh Tuấn', 0, '1511581490', 'huyện Cam Lâm', 'Trưởng Đài', 'Lý Nguyệt Hà', NULL, '1511578024', 'KVHCSN', '4', NULL, 'NGANSACH', 'PTTH', 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(67, '1511582173', NULL, 'Trung Tâm Bồi Dưỡng Chính Trị', 'Cam Đức - Cam Lâm -Khánh Hòa', '02583983302', 'Lê Văn Thuấn', 0, '1511581490', '', 'Giám đốc', 'Phan Thị Thể Minh', NULL, '1511578024', 'KVHCSN', '3', NULL, 'NGANSACH', 'DT', 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(68, '1511582199', NULL, 'Phòng Tài Nguyên môi trường', 'Huyện Khánh Sơn - Tỉnh Khánh Hòa', '', '', 0, '1511581308', 'Khánh Sơn', '', '', NULL, '1511580856', 'KVHCSN', '1', NULL, 'NGANSACH', 'MT', 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(70, '1511582226', NULL, 'UBND Thị Trấn Cam Đức', '', '', '', 0, '1511581490', '', '', '', NULL, '1511578024', 'KVXP', '1', 'XL1', NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(71, '1511582261', NULL, 'Phòng Văn Hóa Thông Tin', 'Huyện Khánh Sơn - Tỉnh Khánh Hòa', '', '', 0, '1511581308', 'Khánh Sơn', '', '', NULL, '1511580856', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(72, '1511582324', NULL, 'UBND Xã Cam Tân', 'Cam Tân - Cam Lâm - Khánh Hòa ', '', 'Võ Ngọc Trung ', 0, '1511581490', '', 'Chủ Tịch UBND xã ', 'Trần Thị Thùy Trang ', NULL, '1511578024', 'KVXP', '4', 'XL2', 'NGANSACH', NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(73, '1511582352', NULL, 'UBND Xã Cam Hòa', '', '', '', 0, '1511581490', '', '', '', NULL, '1511578024', 'KVXP', '3', 'XL1', 'NGANSACH', NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(74, '1511582402', NULL, 'UBND XÃ SƠN TÂN', 'Huyện Cam Lâm - Tỉnh Khánh Hòa', '0985251927', 'Cao Minh Sao', 0, '1511581308', 'Cam Lâm - Khánh Hòa', 'Chủ tịch xã', 'Nguyễn Thị Vĩnh Uyên', NULL, '1511580856', 'KVXP', '4', 'XL1', 'NGANSACH', NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(76, '1511582426', NULL, 'UBND Xã Sơn Tân', '', '', '', 0, '1511581490', '', '', '', NULL, '1511578024', 'KVXP', '1', 'XL1', NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(77, '1511582460', NULL, 'UBND Xã Cam Hải Tây', '', '', '', 0, '1511581490', '', '', '', NULL, '1511578024', 'KVXP', '1', 'XL1', 'NGANSACH', NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(78, '1511582470', NULL, 'Phòng Lao đông Thương binh & Xã hội', 'Huyện Khánh Sơn - Tỉnh Khánh Hòa', '', '', 0, '1511581308', 'Khánh Sơn', '', '', NULL, '1511580856', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(79, '1511582492', NULL, 'UBND Xã Cam Hiệp Bắc', 'Xã Cam Hiệp Bắc, HUyện Cam Lâm, Tỉnh Khánh Hòa', '02583604220', 'Nguyễn Thị Thãi', 0, '1511581490', 'Xã Cam Hiệp Bắc, Huyện Cam Lâm, Tỉnh Khánh Hòa', 'Ghủ tịch ', 'Đặng Thị Lệ Thúy ', NULL, '1511578024', 'KVXP', '4', 'XL1', 'NGANSACH', NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(80, '1511582503', NULL, 'Phòng Dân Tộc', 'Huyện Khánh Sơn - Tỉnh Khánh Hòa', '', '', 0, '1511581308', 'Khánh Sơn', '', '', NULL, '1511580856', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(81, '1511582517', NULL, 'UBND Xã Cam Hiệp Nam', '', '', '', 0, '1511581490', '', '', '', NULL, '1511578024', 'KVXP', '1', 'XL1', 'NGANSACH', NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(82, '1511582534', NULL, 'Phòng Nội Vụ', 'Huyện Khánh Sơn - Tỉnh Khánh Hòa', '', '', 0, '1511581308', 'Khánh Sơn', '', '', NULL, '1511580856', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(83, '1511582547', NULL, 'UBND Xã Cam Thành Bắc', 'thôn Lam Sơn, xã Cam Thành Bắc, huyện Cam Lâm, tỉnh Khánh Hòa', '02583985443', 'Lê Quang Hùng', 0, '1511581490', '', 'Chủ tịch UBND', 'Phạm Thị Trà My', NULL, '1511578024', 'KVXP', '3', 'XL1', 'NGANSACH', NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(84, '1511582573', NULL, 'UBND Xã Cam An Bắc', 'Cam An Bắc, Cam Lâm, Khánh Hòa', '0258 3994440', 'Hồ Văn Trung', 0, '1511581490', '', 'Chủ tịch', 'Phan Đình Đoan Thùy', NULL, '1511578024', 'KVXP', '4', 'XL2', 'NGANSACH', NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(85, '1511582574', NULL, 'Thanh tra Huyện', 'Huyện Khánh Sơn - Tỉnh Khánh Hòa', '', '', 0, '1511581308', 'Khánh Sơn', '', '', NULL, '1511580856', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(86, '1511582595', NULL, 'UBND Xã Cam An Nam', '', '', '', 0, '1511581490', '', '', '', NULL, '1511578024', 'KVXP', '1', 'XL1', NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(87, '1511582610', NULL, 'Phòng Y Tế', 'Huyện Khánh Sơn - Tỉnh Khánh Hòa', '', '', 0, '1511581308', 'Khánh Sơn', '', '', NULL, '1511580856', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(88, '1511582618', NULL, 'UBND Xã Cam Phước Tây', 'Thôn Văn Thủy 1, xã Cam Phước Tây, Cam Phước Tây', '02583999454', 'Lê Tấn Long', 0, '1511581490', '', 'Chủ Tịch', 'Lê Thị Hồng Huệ', NULL, '1511578024', 'KVXP', '1', 'XL1', 'NGANSACH', NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(89, '1511582642', NULL, 'UBND Xã Cam Hải Đông', 'Thôn Thủy Triều, Cam Hải Đông, Cam Lâm, Khánh Hòa', '02583.989.538', 'Nguyễn Trọng Khương', 0, '1511581490', 'Thôn Thủy Triều, Cam Hải Đông, Cam Lâm,Khánh Hòa', 'Chủ tịch', 'Nguyễn Thị Xuân Lâm', NULL, '1511578024', 'KVXP', '1', 'XL1', 'NGANSACH', NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(90, '1511582645', NULL, 'Văn Phòng Huyện Ủy', 'Huyện Khánh Sơn - Tỉnh Khánh Hòa', '', '', 0, '1511581308', 'Khánh Sơn', '', '', NULL, '1511580856', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(91, '1511582664', NULL, 'UBND Xã Suối Tân', 'Đồng Cau, Suối Tân, Cam lâm, Khánh Hòa ', '02583783010', 'Nguyễn Ngọc Khuê', 0, '1511581490', '', 'Chủ Tịch UBND xã', 'Trần Thị Thanh Hiền ', NULL, '1511578024', 'KVXP', '1', 'XL1', 'NGANSACH', NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(92, '1511582688', NULL, 'UBND Xã Suối Cát', 'Thôn Tân Xương 1, xã Suối Cát, huyện Cam Lâm', '02583740807', 'Lương Đức Huệ', 0, '1511581490', '', 'Chủ tịch UBND', 'Nguyễn Mỹ Hoàng Oanh', NULL, '1511578024', 'KVXP', '1', 'XL1', 'NGANSACH', NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(93, '1511582691', NULL, 'Ủy Ban Mặt Trận Tổ Quốc Việt Nam Huyện', 'Huyện Khánh Sơn - Tỉnh Khánh Hòa', '', '', 0, '1511581308', 'Khánh Sơn', '', '', NULL, '1511580856', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(94, '1511582723', NULL, 'Hội Nông dân', 'Huyện Khánh Sơn - Tỉnh Khánh Hòa', '', '', 0, '1511581308', 'Khánh Sơn', '', '', NULL, '1511580856', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(95, '1511582729', NULL, 'Trường Mần Non Hướng Dương', '', '', '', 0, '1511581523', '', '', '', NULL, '1511578024', 'KVHCSN', '1', NULL, NULL, 'DT', 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(96, '1511582757', NULL, 'Trường Mầm Non Mai Vàng', 'Cam Hải Đông, Cam Lâm, Khánh Hòa', '02583.989304', 'Lê Thị Thanh Kiều', 0, '1511581523', 'Cam Hải Đông, Cam Lâm, Khánh Hòa', 'Hiệu Trưởng', 'Nguyễn Thị Minh Thư', NULL, '1511578024', 'KVHCSN', '1', NULL, 'NGANSACH', NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(97, '1511582771', NULL, 'Hội Phụ Nữ', 'Huyện Khánh Sơn - Tỉnh Khánh Hòa', '', '', 0, '1511581308', 'Khánh Sơn', '', '', NULL, '1511580856', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(98, '1511582783', NULL, 'Trường Mẫu Giáo Vàng Anh', 'Đồng Cau, Suối Tân, Cam Lâm, Khánh Hòa', '02583744251', 'Nguyễn Thị Thu', 0, '1511581523', 'Suối Tân', 'Hiệu Trưởng', 'Nguyễn Thị Vân Khuyên', NULL, '1511578024', 'KVHCSN', '1', NULL, 'NGANSACH', 'GD', 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(99, '1511582808', NULL, 'Trường Mẫu Giáo Sơn Ca', 'Huyện Cam Lâm - Tỉnh Khánh Hòa', '02583991766', 'Bùi Thị Túy', 0, '1511581591', 'Cam Tân', 'Hiệu trưởng', 'Hà Võ Hồng Lan', NULL, '1511580856', 'KVHCSN', '1', NULL, 'NGANSACH', 'GD', 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(101, '1511582832', NULL, 'Trường Mẫu Giáo Sơn Ca', '', '', '', 0, '1511581523', '', '', '', NULL, '1511578024', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(102, '1511582849', NULL, 'Huyện Đoàn', 'Huyện Khánh Sơn - Tỉnh Khánh Hòa', '', '', 0, '1511581308', 'Khánh Sơn', '', '', NULL, '1511580856', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(103, '1511582856', NULL, 'Trường Mầm Non Hoa Lan', 'Cam Hải Tây, Cam Lâm, Khánh Hòa', '02583.983267', 'Huỳnh Thị Tưởng', 0, '1511581523', 'Cam Hải Tây, Cam Lâm, Khánh Hòa', 'Hiệu Trưởng', 'Trần Thị Mỹ Linh', NULL, '1511578024', 'KVHCSN', '1', NULL, 'NGANSACH', NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(104, '1511582881', NULL, 'Trường Mẫu Giáo Anh Đào', '66 Nguyễn Du - TT Cam Đức - Cam Lâm - Khánh Hòa', '0258 3983554', 'Phạm Song Châu Hải', 0, '1511581523', '', 'Hiệu trưởng', 'Trần Kim Hạnh Duyên', NULL, '1511578024', 'KVHCSN', '3', NULL, 'CTXMP', 'GD', 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(105, '1511582883', NULL, 'Hội Chữ Thập Đỏ', 'Huyện Khánh Sơn - Tỉnh Khánh Hòa', '', '', 0, '1511581308', 'Khánh Sơn', '', '', NULL, '1511580856', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(106, '1511582909', NULL, 'Trường Mầm non Hoa Hồng', 'Thôn Tân Phú xã Cam Thành Bắc, huyện Cam Lâm, tỉnh Khánh Hòa', '02583985277', 'Nguyễn Thị Kim Chi', 0, '1511581523', '', 'Hiệu trưởng', 'Phạm Thị Thu Hương', NULL, '1511578024', 'KVHCSN', '1', NULL, 'NGANSACH', 'GD', 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(107, '1511582931', NULL, 'Trung tâm Bồi dưỡng - Chính trị', 'Huyện Khánh Sơn - Tỉnh Khánh Hòa', '', '', 0, '1511581308', 'Khánh Sơn', '', '', NULL, '1511580856', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(108, '1511582951', NULL, 'Trường Mầm non Thỏ Hồng', 'Thôn 2, Xã Cam Hiệp Bắc, Cam Lâm, Khánh Hòa', '02583996353', 'Nguyễn Thị Thu Vân', 0, '1511581523', '', 'Hiệu Trưởng', 'Nguyễn Thị Nhung', NULL, '1511578024', 'KVHCSN', '1', NULL, 'NGANSACH', NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(109, '1511582970', NULL, 'Đài truyền thanh - truyền hình', 'Huyện Khánh Sơn - Tỉnh Khánh Hòa', '', '', 0, '1511581308', 'Khánh Sơn', '', '', NULL, '1511580856', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(110, '1511582978', NULL, 'Trường Mầm non Thỏ Ngọc', 'Suối Cát, Cam Hiệp Nam, Cam Lâm, Khánh Hòa', '0583995953', 'Trần Thị Thu Ngân', 0, '1511581523', 'Cam Hiệp Nam, Cam Lâm, Khánh Hòa', 'Hiệu trưởng', 'Nguyễn Thị Bé Ly', NULL, '1511578024', 'KVHCSN', '2', NULL, 'NGANSACH', 'GD', 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(111, '1511583004', NULL, 'Trường Mấu Giáo Thiên Nga', 'Triệu Hải - Cam An Bắc - Cam Lâm - Khánh Hòa', '0258 3994433', 'Mai Đình Lệ Thủy', 0, '1511581523', '', 'Hiệu trưởng', 'Nguyễn Thị Hồng Thắm', NULL, '1511578024', 'KVHCSN', '3', NULL, 'NGANSACH', 'GD', 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(112, '1511583015', NULL, 'Trung tâm dịch vụ thương mại', 'Huyện Khánh Sơn - Tỉnh Khánh Hòa', '', '', 0, '1511581308', 'Khánh Sơn', '', '', NULL, '1511580856', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(113, '1511583053', NULL, 'Trung tâm Văn hóa - thể thao', 'Huyện Khánh Sơn - Tỉnh Khánh Hòa', '', '', 0, '1511581308', 'Khánh Sơn', '', '', NULL, '1511580856', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(114, '1511583060', NULL, 'Trường Mẫu Giáo Sóc Nâu', '', '', '', 0, '1511581523', '', '', '', NULL, '1511578024', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(115, '1511583090', NULL, 'Trung tâm phát triển quỹ đất', 'Huyện Khánh Sơn - Tỉnh Khánh Hòa', '', '', 0, '1511581308', 'Khánh Sơn', '', '', NULL, '1511580856', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(116, '1511583124', NULL, 'Trường Mẫu Giáo Phong Lan', '', '', '', 0, '1511581523', '', '', '', NULL, '1511578024', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(117, '1511583162', NULL, 'Ban quản lý CTCC&MT', 'Huyện Khánh Sơn - Tỉnh Khánh Hòa', '', '', 0, '1511581308', 'Khánh Sơn', '', '', NULL, '1511580856', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(118, '1511583196', NULL, 'Trường Mẫu Giáo Vành Khuyên', 'Suối Cát - Cam Lâm - Khánh Hòa', '02583740835', 'Lê Thị Thùy Yến', 0, '1511581523', '', 'Hiệu trưởng', 'Nguyễn Thị Bích Quyên', NULL, '1511578024', 'KVHCSN', '3', NULL, 'CTXMP', 'GD', 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(119, '1511583206', NULL, 'Trung tâm bảo trợ xã hội', 'Huyện Khánh Sơn - Tỉnh Khánh Hòa', '', '', 0, '1511581308', 'Khánh Sơn', '', '', NULL, '1511580856', 'KVHCSN', '1', '', NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(120, '1511583228', NULL, 'Trường Mẫu Giáo Hoàng Yến', 'Cửu Lợi, Cam Hòa,Cam Lâm', '0583863429', 'Nguyễn Thị Thu Thủy', 0, '1511581523', '', 'Hiệu trưởng', 'Nguyễn Thị Bảo Uyên', NULL, '1511578024', 'KVHCSN', '3', NULL, 'NGANSACH', 'GD', 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(121, '1511583247', NULL, 'Trạm Khuyến Nông', 'Huyện Khánh Sơn - Tỉnh Khánh Hòa', '', '', 0, '1511581308', 'Khánh Sơn', '', '', NULL, '1511580856', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(122, '1511583260', NULL, 'Trường TH Cam An Bắc', 'Cam An Bắc - Cam Lâm -Khánh Hòa', '02583994256', 'Trần Thị Ái Thủy', 0, '1511581523', 'Cam An Bắc - Cam Lâm -Khánh Hòa', 'Hiệu trưởng', 'Võ Thị Ly Linh', NULL, '1511578024', 'KVHCSN', '3', NULL, 'NGANSACH', NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(123, '1511583302', NULL, 'UBND Xã Thành Sơn', 'Huyện Khánh Sơn - Tỉnh Khánh Hòa', '', '', 0, '1511581308', 'Khánh Sơn', '', '', NULL, '1511580856', 'KVXP', '2', '', NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(124, '1511583320', NULL, 'Trường TH Cam An Nam', 'Cam An Nam, Cam Lâm, Khánh Hòa', '02583864012', 'Nguyễn Trường Khương', 0, '1511581523', 'Cam An Nam', 'Hiệu trưởng', 'Nguyễn Thị Hỷ', NULL, '1511578024', 'KVHCSN', '3', NULL, 'NGANSACH', 'GD', 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(125, '1511583341', NULL, 'UBND Xã Sơn Lâm', 'Huyện Khánh Sơn - Tỉnh Khánh Hòa', '', '', 0, '1511581308', 'Khánh Sơn', '', '', NULL, '1511580856', 'KVXP', '2', '', NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(126, '1511583342', NULL, 'Trường TH Cam Phước Tây 2', 'Cam Phước Tây - Cam Lâm', '02583999099', 'Đoàn Văn Chiến', 0, '1511581523', 'Huyện Cam Lâm', 'Hiệu trưởng', 'Nguyễn Thị Thanh Lĩnh', NULL, '1511578024', 'KVHCSN', '3', NULL, 'NGANSACH', 'GD', 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(127, '1511583363', NULL, 'Trường TH Cam Hiệp Bắc', 'xÃ CAM HIỆP BẮC, cAM LÂM, KHÁNH HÒA', '0258 3996028', 'ĐỖ ÁI HẰNG', 0, '1511581523', 'KHÁNH HÒA', '', 'LÊ THỊ HÙNG', NULL, '1511578024', 'KVHCSN', '4', NULL, 'NGANSACH', 'GD', 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(128, '1511583374', NULL, 'UBND Xã Sơn Bình', 'Huyện Khánh Sơn - Tỉnh Khánh Hòa', '', '', 0, '1511581308', 'Khánh Sơn', '', '', NULL, '1511580856', 'KVXP', '2', '', NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(129, '1511583384', NULL, 'Trường TH Cam Phước Tây 1', '', '', '', 0, '1511581523', '', '', '', NULL, '1511578024', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(130, '1511583405', NULL, 'Trường TH Cam Thành Bắc', 'Tân Phú - Cam Thành Bắc - Cam Lâm - Khánh Hòa', '3985406', 'Nguyễn Thị Thanh Tâm', 0, '1511581523', '', 'Hiệu trưởng', 'Lê Thị Bảo Uyên', NULL, '1511578024', 'KVHCSN', '1', NULL, 'NGANSACH', NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(131, '1511583412', NULL, 'UBND Xã Sơn Hiệp', 'Huyện Khánh Sơn - Tỉnh Khánh Hòa', '', '', 0, '1511581308', 'Khánh Sơn', '', '', NULL, '1511580856', 'KVXP', '2', '', NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(132, '1511583425', NULL, 'Trường TH Cam Đức 2', '', '', '', 0, '1511581523', '', '', '', NULL, '1511578024', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(133, '1511583443', NULL, 'UBND Xã Sơn Trung', 'Huyện Khánh Sơn - Tỉnh Khánh Hòa', '', '', 0, '1511581308', 'Khánh Sơn', '', '', NULL, '1511580856', 'KVXP', '2', '', NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(134, '1511583445', NULL, 'Trường TH Cam Đức 1', 'Thị trấn Cam Đức, huyện Cam Lâm, tỉnh Khánh Hòa', '0258.3859307', 'Võ Thị Thúy', 0, '1511581523', '', 'Hiệu trưởng', 'Phạm Thị Minh Sương', NULL, '1511578024', 'KVHCSN', '3', NULL, 'NGANSACH', NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(135, '1511583466', NULL, 'Trường TH Cam Hiệp Nam', 'Suối Cát-  Cam Hiệp Nam ', '058 3995169', 'Lê Anh Tuấn ', 0, '1511581523', 'Suối Cát  -Cam Hiệp Nam ', 'Hiệu trưởng ', 'Nguyễn Thị Thanh Nga ', NULL, '1511578024', 'KVHCSN', '1', NULL, 'NGANSACH', NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(136, '1511583483', NULL, 'UBND Thị Trấn Tô Hạp', 'Huyện Khánh Sơn - Tỉnh Khánh Hòa', '', '', 0, '1511581308', 'Khánh Sơn', '', '', NULL, '1511580856', 'KVXP', '2', '', NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(137, '1511583489', NULL, 'Trường TH Sơn Tân', '', '', '', 0, '1511581523', '', '', '', NULL, '1511578024', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(138, '1511583510', NULL, 'Trường TH Suối Tân', '', '', '', 0, '1511581523', '', '', '', NULL, '1511578024', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(139, '1511583522', NULL, 'UBND Xã Ba Cụm Bắc', 'Huyện Khánh Sơn - Tỉnh Khánh Hòa', '', '', 0, '1511581308', 'Khánh Sơn', '', '', NULL, '1511580856', 'KVXP', '2', '', NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(140, '1511583535', NULL, 'Trường TH Cam Hòa 1', '', '', '', 0, '1511581523', '', '', '', NULL, '1511578024', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(141, '1511583556', NULL, 'UBND Xã Ba Cụm Nam', 'Huyện Khánh Sơn - Tỉnh Khánh Hòa', '', '', 0, '1511581308', 'Khánh Sơn', '', '', NULL, '1511580856', 'KVXP', '2', '', NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(142, '1511583557', NULL, 'Trường TH Cam Hòa 2', 'Lập Định - Cam Hòa - Cam Lâm - Khánh Hòa', '002583863172', 'Nguyễn Đông Hà', 0, '1511581523', '', 'Hiệu Trưởng', 'Đinh Thục Quyên', NULL, '1511578024', 'KVHCSN', '1', NULL, 'NGANSACH', 'GD', 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(143, '1511583584', NULL, 'Trường TH Cam Tân', '', '', '', 0, '1511581523', '', '', '', NULL, '1511578024', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(144, '1511583611', NULL, 'Trường TH Cam Hải Đông', '', '', '', 0, '1511581523', '', '', '', NULL, '1511578024', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(145, '1511583632', NULL, 'Trường TH Suối Cát', 'Thôn Tân Xương 1, xã Suối Cát, huyện Cam Lâm, tỉnh Khánh Hòa', '02583740328', 'Nguyễn Thị Liên Minh', 0, '1511581523', '', 'Hiệu trưởng', 'Nguyễn Thảo Trầm Hương', NULL, '1511578024', 'KVHCSN', '4', NULL, 'NGANSACH', 'GD', 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(146, '1511583652', NULL, 'Trường TH Tân Sinh', 'Tân Sinh Đông, Cam Thành Bắc, Cam Lâm, Khánh Hòa', '02583985833', 'Phạm Thị Lan Khanh', 0, '1511581523', 'Cam Lâm', '', 'Nguyễn Thị Như Thủy', NULL, '1511578024', 'KVHCSN', '3', NULL, 'NGANSACH', NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(147, '1511583680', NULL, 'Trường TH Cam Hải Tây', 'Bắc Vĩnh- Cam Hải Tây- Cam Lâm- Khánh Hòa', '', 'Phạm Xuân Khoa', 0, '1511581523', '', 'Hiệu trưởng', 'Nguyễn Thị Dung', NULL, '1511578024', 'KVHCSN', '1', NULL, 'NGANSACH', 'GD', 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(148, '1511583703', NULL, 'Trường TH Khánh Hòa - Jeju', 'Thôn Suối Lau 2, xã Suối Cát,  huyện Cam Lâm, tỉnh Khánh Hòa', '02583707807', 'Võ Thị Thanh Hương', 0, '1511581523', '', 'Hiệu trưởng', 'Đỗ Thị Phương Anh', NULL, '1511578024', 'KVHCSN', '3', NULL, 'NGANSACH', 'GD', 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(149, '1511583727', NULL, 'Trường THCS Phan Đình Phùng', 'Đồng Cau, Suối Tân, Cam Lâm, Khánh Hòa', '02583740026', 'Nguyễn Hữu Tân', 0, '1511581523', '', 'Hiệu trưởng', 'Phạm Thị Thu Hà', NULL, '1511578024', 'KVHCSN', '1', NULL, 'NGANSACH', 'GD', 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(150, '1511583746', NULL, 'Trường MN Anh Đào', 'Huyện Khánh Sơn - Tỉnh Khánh Hòa', '', '', 0, '1511582402', 'Khánh Sơn', '', '', NULL, '1511580856', 'KVHCSN', '3', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(151, '1511583755', NULL, 'Trường THCS A.YERSIN', '', '', '', 0, '1511581523', '', '', '', NULL, '1511578024', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(152, '1511583783', NULL, 'Trường MN 1/6', 'Huyện Khánh Sơn - Tỉnh Khánh Hòa', '', '', 0, '1511582402', 'Khánh Sơn', '', '', NULL, '1511580856', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(153, '1511583823', NULL, 'Trường MN Vành Khuyên', 'Huyện Khánh Sơn - Tỉnh Khánh Hòa', '', '', 0, '1511582402', 'Khánh Sơn', '', '', NULL, '1511580856', 'KVHCSN', '3', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(154, '1511583836', NULL, 'Trường THCS Trần Quang Khải', 'Cam Phước Tây, Cam Lâm', '02583999234', 'Đồng Văn Năm', 0, '1511581523', '', 'Hiệu trưởng', 'Nguyễn Thị Ngọc huệ', NULL, '1511578024', 'KVHCSN', '3', NULL, 'NGANSACH', 'GD', 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(155, '1511583859', NULL, 'Trường THCS Quang Trung', '', '', '', 0, '1511581523', '', '', '', NULL, '1511578024', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(156, '1511583871', NULL, 'Trường MN Họa Mi', 'Huyện Khánh Sơn - Tỉnh Khánh Hòa', '', '', 0, '1511582402', 'Khánh Sơn', '', '', NULL, '1511580856', 'KVHCSN', '3', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(157, '1511583881', NULL, 'Trường THCS Nguyễn Công Trứ', 'Suối Cát - Cam Hiệp Nam', '0583995174', 'Trịnh Quốc Nha', 0, '1511581523', 'Huyện Cam Lâm', 'Hiệu trương', 'Nguyễn Thị Xanh', NULL, '1511578024', 'KVHCSN', '1', NULL, 'NGANSACH', NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(158, '1511583901', NULL, 'Trường THCS Nguyễn Hiền', 'Suối Cam, Cam Thành Bắc, Cam Lâm, Khánh Hòa', '02583983389', 'Trần Cao Như', 0, '1511581523', 'Suối Cam', 'Hiệu trưởng', 'Nguyễn Thị Diệu Hiền', NULL, '1511578024', 'KVHCSN', '3', NULL, 'NGANSACH', 'GD', 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(159, '1511583908', NULL, 'Trường MN Sơn Ca', 'Huyện Khánh Sơn - Tỉnh Khánh Hòa', '', '', 0, '1511582402', 'Khánh Sơn', '', '', NULL, '1511580856', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(160, '1511583925', NULL, 'Trường THCS Lê Thánh Tôn', 'Thôn Trung Hiệp 2, xã Cam Hiệp Bắc, huyện Cam Lâm, tỉnh Khánh Hòa', '0258.3996081', 'Trần Văn Hiếu', 0, '1511581523', '', 'Hiệu trưởng', 'Nguyễn Thị Minh Thắm', NULL, '1511578024', 'KVHCSN', '4', NULL, 'NGANSACH', 'DT', 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(161, '1511583943', NULL, 'Trường THCS Hùng Vương', 'Bãi Giếng 1, TT.Cam Đức, Cam Lâm, Khánh Hòa', '', 'Nguyễn Tấn', 0, '1511581523', '', '', 'Phạm Thị Hoàng Nữ', NULL, '1511578024', 'KVHCSN', '3', NULL, 'NGANSACH', 'GD', 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(162, '1511583950', NULL, 'Trường MN Hoàng Oanh', 'Huyện Khánh Sơn - Tỉnh Khánh Hòa', '', '', 0, '1511582402', 'Khánh Sơn', '', '', NULL, '1511580856', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(163, '1511583973', NULL, 'Trường THCS Hoàng Hoa Thám', '', '', '', 0, '1511581523', '', '', '', NULL, '1511578024', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(164, '1511583976', NULL, 'Trường MN Sao Mai', 'Huyện Khánh Sơn - Tỉnh Khánh Hòa', '', '', 0, '1511582402', 'Khánh Sơn', '', '', NULL, '1511580856', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(165, '1511583995', NULL, 'Trường THCS Nguyễn Trãi', 'Cam An Bac', '3994312', 'Duong Trong Thu', 0, '1511581523', 'Cam Lam', 'Hieu truong', 'Nguyen Thi Hong Van', NULL, '1511578024', 'KVHCSN', '3', NULL, 'CTXMP', 'GD', 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(166, '1511584008', NULL, 'Trường MN Hoa Phượng', 'Huyện Khánh Sơn - Tỉnh Khánh Hòa', '', '', 0, '1511582402', 'Khánh Sơn', '', '', NULL, '1511580856', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(167, '1511584015', NULL, 'Trường THCS Lương Thế Vinh', 'Cửu Lợi 2 Cam Hòa Cam Lâm Khánh Hòa', '02583680038', 'Nguyễn Thế', 0, '1511581523', '', 'Hiệu trưởng', 'Lê Thị Trâm', NULL, '1511578024', 'KVHCSN', '4', NULL, 'NGANSACH', NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(168, '1511584035', NULL, 'Trường THCS Trần Đại Nghĩa', '', '', '', 0, '1511581523', '', '', '', NULL, '1511578024', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(169, '1511584056', NULL, 'Trường MN Phong Lan', 'Huyện Khánh Sơn - Tỉnh Khánh Hòa', '', '', 0, '1511582402', 'Khánh Sơn', '', '', NULL, '1511580856', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(170, '1511584068', NULL, 'Trường Mẫu Giáo Họa Mi', '', '', '', 0, '1511581523', '', '', '', NULL, '1511578024', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(171, '1511584105', NULL, 'Trường TH Sơn Lâm', 'Huyện Khánh Sơn - Tỉnh Khánh Hòa', '', '', 0, '1511582402', 'Khánh Sơn', '', '', NULL, '1511580856', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(172, '1511584139', NULL, 'Trường TH Sơn Bình', 'Huyện Khánh Sơn - Tỉnh Khánh Hòa', '', '', 0, '1511582402', 'Khánh Sơn', '', '', NULL, '1511580856', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(173, '1511584229', NULL, 'Trường TH Sơn Hiệp', 'Huyện Khánh Sơn - Tỉnh Khánh Hòa', '', '', 0, '1511582402', 'Khánh Sơn', '', '', NULL, '1511580856', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(174, '1511584293', NULL, 'Trường Tiểu học Tô Hạp', 'Huyện Khánh Sơn - Tỉnh Khánh Hòa', '', '', 0, '1511582402', 'Trường Tiểu học Tô Hạp', '', '', NULL, '1511580856', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(175, '1511584334', NULL, 'Trường Tiểu học Sơn Trung', 'Huyện Khánh Sơn - Tỉnh Khánh Hòa', '', '', 0, '1511582402', 'Khánh Sơn', '', '', NULL, '1511580856', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(176, '1511584364', NULL, 'Trường Tiểu học Ba cụm Bắc', 'Huyện Khánh Sơn - Tỉnh Khánh Hòa', '', '', 0, '1511582402', 'Khánh Sơn', '', '', NULL, '1511580856', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(177, '1511584391', NULL, 'Trường TH&THCS Thành Sơn', 'Huyện Khánh Sơn - Tỉnh Khánh Hòa', '', '', 0, '1511582402', 'Khánh Sơn', '', '', NULL, '1511580856', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(178, '1511584421', NULL, 'Trường TH&THCS Ba Cụm Nam', 'Huyện Khánh Sơn - Tỉnh Khánh Hòa', '', '', 0, '1511582402', 'Khánh Sơn', '', '', NULL, '1511580856', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(179, '1511584448', NULL, 'Trường TH&THCS Sơn Lâm', 'Huyện Khánh Sơn - Tỉnh Khánh Hòa', '', '', 0, '1511582402', 'Khánh Sơn', '', '', NULL, '1511580856', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(180, '1511584474', NULL, 'Trường TH&THCS Sơn Bình', 'Huyện Khánh Sơn - Tỉnh Khánh Hòa', '', '', 0, '1511582402', 'Khánh Sơn', '', '', NULL, '1511580856', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(181, '1511584501', NULL, 'Trường TH&THCS Ba Cụm Bắc', 'Huyện Khánh Sơn - Tỉnh Khánh Hòa', '', '', 0, '1511582402', 'Khánh Sơn', '', '', NULL, '1511580856', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(182, '1511584528', NULL, 'Trường THCS Tô Hạp', 'Huyện Khánh Sơn - Tỉnh Khánh Hòa', '', '', 0, '1511582402', 'Khánh Sơn', '', '', NULL, '1511580856', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(183, '1511584559', NULL, 'Trường PT DTNT ', 'Huyện Khánh Sơn - Tỉnh Khánh Hòa', '', '', 0, '1511582402', 'Khánh Sơn', '', '', NULL, '1511580856', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(184, '1511584731', NULL, 'Phòng Tài Chính Kế Hoạch', 'Huyện Khánh Vĩnh - Tỉnh Khánh Hòa', '', '', 0, '1511584731', 'Khánh Hòa', 'Trưởng phòng Tài chính', '', NULL, '1511580879', 'KVHCSN', '1', NULL, 'NGANSACH', NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(185, '1511584768', NULL, 'Văn phòng Huyện ủy', 'Số 04 Lê Hồng Phong- Thị trấn Khánh Vĩnh', '0258 3790425', 'Lê Thị Thùy Châu', 0, '1511584731', '', 'Phó Chánh Văn Phòng', 'Đỗ Mỹ Hoàng', NULL, '1511580879', 'KVHCSN', '3', NULL, 'NGANSACH', 'DDT', 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(186, '1511584831', NULL, 'Văn phòng HĐND&UBND huyện', '', '', '', 0, '1511584731', '', '', '', NULL, '1511580879', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(187, '1511584864', NULL, 'Phòng Nội vụ', 'Số 11, đường 2/8 thị trấn Khánh Vĩnh', '02583790160', 'Lê Thị Kim Hoa', 0, '1511584731', 'huyện Khánh Vĩnh', 'Trưởng phòng', 'Lương Thị Thu Trang', NULL, '1511580879', 'KVHCSN', '3', NULL, 'NGANSACH', 'QLNN', 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(188, '1511584891', NULL, 'Phòng Lao động TB&XH', 'Đường 2/8, thị trấn Khánh Vĩnh', '02583.790243', 'Nguyễn Thu', 0, '1511584731', 'Khánh Vĩnh', 'Trưởng phòng', 'Phan Khánh Ly', NULL, '1511580879', 'KVHCSN', '1', NULL, 'NGANSACH', 'QLNN', 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(189, '1511584914', NULL, 'Phòng Tư pháp', '', '', '', 0, '1511584731', '', '', '', NULL, '1511580879', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(190, '1511584959', NULL, 'Thanh tra huyện', '', '', '', 0, '1511584731', '', '', '', NULL, '1511580879', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(191, '1511584990', NULL, 'Phòng Nông nghiệp và PTNT', '', '', '', 0, '1511584731', '', '', '', NULL, '1511580879', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(192, '1511585012', NULL, 'Phòng Kinh tế và Hạ tầng', 'Khánh Vĩnh', '', 'Trần Văn Thân', 0, '1511584731', 'Khánh Vĩnh', 'Trưởng phòng', 'Trần Thị Tuyết Trinh', NULL, '1511580879', 'KVHCSN', '1', NULL, 'NGANSACH', 'QLNN', 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(193, '1511585034', NULL, 'Phòng Y tế huyện Khánh Vĩnh', 'Thị trấn Khánh Vĩnh, huyện Khánh Vĩnh, tỉnh Khánh Hòa', '02583790852', 'Nguyễn Hùng Hoàng', 0, '1511584731', 'Khánh Vĩnh', 'Trưởng phòng', 'Bùi Thị Hải Tâm', NULL, '1511580879', 'KVHCSN', '3', NULL, 'NGANSACH', NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(194, '1511585064', NULL, 'Phòng Giáo dục và Đào tạo', 'Số 115, đường 2/8 thị trấn Khánh Vĩnh', '0974711983', 'Bùi Hữu Hóa', 0, '1511584731', 'Huyện Khánh Vĩnh', 'Trưởng phòng', 'Lê Thị Thu Hoa', NULL, '1511580879', 'KVHCSN', '1', NULL, 'NGANSACH', 'DT', 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(195, '1511585153', NULL, 'Phòng Tài nguyên và MT', '', '', '', 0, '1511584731', '', '', '', NULL, '1511580879', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(196, '1511585176', NULL, 'Phòng Văn hóa và Thông tin', '', '', '', 0, '1511584731', '', '', '', NULL, '1511580879', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(197, '1511585198', NULL, 'Phòng Dân tộc', 'số 37 đường 2/8 thị trấn Khánh Vĩnh, huyện Khánh Vĩnh, tỉnh Khánh Hòa', '02583790030', 'Trần Minh Thuận', 0, '1511584731', '', 'Trưởng phòng', 'Phạm Thị Bảo Châu', NULL, '1511580879', 'KVHCSN', '3', NULL, 'NGANSACH', 'QLNN', 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(198, '1511585223', NULL, 'Trung tâm Bồi dưỡng chính trị', '', '', '', 0, '1511584731', '06 Lê Hồng Phong-Thị Trấn Khánh Vĩnh', 'Đinh Thị Kính', 'Nguyễn Thị Hiền', NULL, '1511580879', 'KVHCSN', '2', NULL, 'NGANSACH', 'DT', 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(199, '1511585248', NULL, 'Hội Liên hiệp phụ nữ', 'Số 5 đường Hoàng Quốc Việt, Thị trấn Khánh Vĩnh', '02583790543', 'Cao Thị Liên', 0, '1511584731', 'huyện Khánh Vĩnh', 'Chủ Tịch', 'Huỳnh Thị Mỹ Loan', NULL, '1511580879', 'KVHCSN', '1', NULL, 'NGANSACH', NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(200, '1511585269', NULL, 'Hội Nông dân huyện Khánh Vĩnh', 'Số 05 Hoàng Quốc Việt, thị trấn Khánh Vĩnh, huyện Khánh Vĩnh', '02583790363', 'Cao Xuân Thạnh', 0, '1511584731', '', 'Chủ tịch', 'Lê Vương Mai', NULL, '1511580879', 'KVHCSN', '3', NULL, 'NGANSACH', NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(201, '1511585289', NULL, 'Ủy ban MTTQVN huyện', 'Số 5 đường Hoàng Quốc Việt, Thị trấn Khánh Vĩnh', '02583790543', 'Cao Xuân Thiện', 0, '1511585289', 'huyện Khánh Vĩnh', 'Chủ Tịch', 'Huỳnh Thị Mỹ Loan', NULL, '1511580879', 'KVHCSN', '3', NULL, 'NGANSACH', NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(202, '1511585310', NULL, 'Huyện đoàn', '', '', '', 0, '1511584731', '', '', '', NULL, '1511580879', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(203, '1511585335', NULL, 'Hội cựu chiến binh', 'Số 40 Đường Lê Hồng Phong, Thị Trấn Khánh Vĩnh, huyện Khánh Vĩnh', '02583790254', 'Hà Teng', 0, '1511584731', 'huyện Khánh Vĩnh', 'Chủ tịch', 'Lê Anh Bàng', NULL, '1511580879', 'KVHCSN', '1', NULL, 'NGANSACH', NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(204, '1511585359', NULL, 'Hội chữ thập đỏ', '', '', '', 0, '1511584731', '', '', '', NULL, '1511580879', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL);
INSERT INTO `dmdonvi` (`id`, `madv`, `maqhns`, `tendv`, `diachi`, `sodt`, `lanhdao`, `songuoi`, `macqcq`, `diadanh`, `cdlanhdao`, `nguoilapbieu`, `makhoipb`, `madvbc`, `maphanloai`, `capdonvi`, `phanloaixa`, `phanloainguon`, `linhvuchoatdong`, `phanloaitaikhoan`, `phamvitonghop`, `pcct`, `pckct`, `pck`, `pccv`, `pckv`, `pcth`, `pcdd`, `pcdh`, `pcld`, `pcdbqh`, `pcudn`, `pctn`, `pctnn`, `pcdbn`, `pcvk`, `pckn`, `pcdang`, `pccovu`, `pclt`, `pcd`, `pctr`, `pctnvk`, `pcbdhdcu`, `pcthni`, `created_at`, `updated_at`) VALUES
(205, '1511585380', NULL, 'Hội nạn nhân chất độc da cam điôxin', 'Số 40 Lê Hồng Phong, TT Khánh Vĩnh, huyện Khánh Vĩnh', '0948161497', 'Lê Quang Hệ', 0, '1511584731', 'Khánh Vĩnh', 'Chủ tịch', 'Phạm Lê Anh Đức', NULL, '1511580879', 'KVHCSN', '1', NULL, 'NGANSACH', 'DDT', 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(206, '1511585401', NULL, 'Hội người mù', '', '', '', 0, '1511584731', '', '', '', NULL, '1511580879', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(207, '1511585424', NULL, 'Hội khuyến học', '', '', '', 0, '1511584731', '', '', '', NULL, '1511580879', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(208, '1511585444', NULL, 'Hội Người cao tuổi', '', '', '', 0, '1511584731', '', '', '', NULL, '1511580879', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(209, '1511585468', NULL, 'Trung tâm Văn hóa thể thao', '', '', '', 0, '1511584731', '', '', '', NULL, '1511580879', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(210, '1511585490', NULL, 'Nhà Thiếu nhi', 'thị trấn khánh vĩnh, huyện khánh vĩnh, tỉnh khánh hòa', '02583790710', 'trần xuân trà', 0, '1511584731', 'huyện khánh vĩnh', 'giám đốc', 'nguyễn thị lý', NULL, '1511580879', 'KVHCSN', '3', NULL, 'NGANSACH', NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(211, '1511585510', NULL, 'Đài Truyền thanh - Truyền hình huyện Khánh Vĩnh', 'Số 43 Py Năng Xà A', '02583790341', 'Nguyễn Đức Quốc', 0, '1511584731', 'huyện Khánh Vĩnh', 'Trưởng đài', 'Lê Thị Hoài Thanh', NULL, '1511580879', 'KVHCSN', '3', NULL, 'NGANSACH', 'PTTH', 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(212, '1511585532', NULL, 'Trung tâm bảo trợ xã hội', 'Sông Cầu - huyện Khánh Vĩnh - Khánh Hoà', '02583790056', 'Lưu Nguyên', 0, '1511584731', '', 'Phó giám đốc phụ trách', 'Nguyễn Thị Ánh Tuyết', NULL, '1511580879', 'KVHCSN', '3', NULL, 'NGANSACH', 'DBXH', 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(213, '1511585554', NULL, 'Trạm Khuyến nông', 'Thôn Tây, xã Sông Cầu, huyện Khánh Vĩnh', '02583.900777', 'Lê Văn Hùng', 0, '1511584731', 'Sông Cầu', 'Trưởng Trạm', 'Kiều Minh Hải', NULL, '1511580879', 'KVHCSN', '3', NULL, 'NGANSACH', NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(214, '1511585575', NULL, 'Trung tâm phát triển quỹ đất Khánh Vĩnh', 'Đường 2/8, Thị trấn Khánh Vĩnh', '3790086', 'HUỲNH XUÂN LỘC', 0, '1511584731', '', 'Giám đốc', 'LÊ ĐỨC TRÍ', NULL, '1511580879', 'KVHCSN', '3', NULL, 'NGANSACH', 'KT', 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(215, '1511585598', NULL, 'Trung tâm Dịch vụ Thương mại', 'Số 46, đường 2/8, thị trấn Khánh Vĩnh, Huyện Khánh Vĩnh', '02583 790 458 ', 'Nguyễn Văn Chính', 0, '1511584731', 'Huyện Khánh Vĩnh', 'Giám Đốc', 'Hoàng Thị Vân', NULL, '1511580879', 'KVHCSN', '3', NULL, 'NGANSACH', 'KT', 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(216, '1511585619', NULL, 'Ban quản lý công trình công cộng và MT', '', '', '', 0, '1511584731', '', '', '', NULL, '1511580879', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(217, '1511585643', NULL, 'UBND thị trấn', 'Số 72 đường 2/8 thị trấn Khánh Vĩnh, Khánh Vĩnh, Khánh Hòa', '0988412839', 'PHAN VĂN PHƯƠNG', 0, '1511584731', 'Thị trấn Khánh Vĩnh', 'CHỦ TỊCH', 'NGUYỄN THỊ MAI', NULL, '1511580879', 'KVXP', '4', 'XL2', 'NGANSACH', NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(218, '1511585666', NULL, 'UBND xã Sông Cầu', '', '', '', 0, '1511584731', '', '', '', NULL, '1511580879', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(219, '1511585690', NULL, 'Ủy ban nhân dân xã Khánh Phú', 'Thôn Ngã Hai - xã Khánh Phú - huyện Khánh Vĩnh', '0986058414', 'Cao Văn Toàn', 0, '1511584731', 'Khánh Phú', 'Chủ tịch UBND xã', 'Phạm Quốc Tú', NULL, '1511580879', 'KVXP', '4', 'XL3', 'NGANSACH', NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(220, '1511585713', NULL, 'UBND xã Khánh Thành', 'Thôn Tà Mơ xã Khánh Thành huyện Khánh Vĩnh tỉnh Khánh Hòa', '02583706787', 'Cao Thị Thêm', 0, '1511584731', 'Khánh Hòa', 'Chủ tịch', 'Lê Thị Lại', NULL, '1511580879', 'KVXP', '4', 'Xã loại 2', 'NGANSACH', NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(221, '1511585736', NULL, 'UBND xã Cầu Bà', 'Xã Cầu Bà-Huyện KHánh Vĩnh-Tỉnh Khánh Hòa', '3793000', 'Lê Kim Sung', 0, '1511584731', '', 'Chủ tịch UBND xã', 'Huỳnh Lê Nhật Uyên', NULL, '1511580879', 'KVXP', '4', 'Xã loại 3', 'NGANSACH', NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(222, '1511585771', NULL, 'UBND xã Liên Sang ', '', '', '', 0, '1511584731', '', '', '', NULL, '1511580879', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(223, '1511585799', NULL, 'UBND xã Giang Ly', 'Thôn Gia Lố, xã Giang Ly, huyện Khánh Vĩnh, tỉnh Khánh Hoà', '0583793533', 'Cà Lang', 0, '1511584731', '', 'Chủ tịch ', 'Cao Thị Hồng Yến', NULL, '1511580879', 'KVXP', '4', 'Xã loại 3', 'NGANSACH', NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(224, '1511585824', NULL, 'UBND xã Sơn Thái', 'Bố Lang, Sơn Thái, Khánh Vĩnh, Khánh Vĩnh', '0258 3790 056', 'Cà Tam', 0, '1511584731', 'Sơn Thái', 'Chủ tịch', 'Đặng Chí Thiện', NULL, '1511580879', 'KVXP', '4', 'XL3', 'NGANSACH', NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(225, '1511585846', NULL, 'UBND xã Khánh Thượng', 'thôn Trang, xã Khánh Thượng, huyện Khánh Vĩnh, tỉnh Khánh Hòa', '02583793033', 'Pi Năng Thảo', 0, '1511584731', '', 'Chủ tịch UBND xã', 'Hoàng Thanh Hằng', NULL, '1511580879', 'KVXP', '4', 'Xã loại 3', 'NGANSACH', NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(226, '1511585871', NULL, 'UBND xã Khánh Nam', 'Thôn Hòn Dù - xã Khánh Nam - huyện Khánh Vĩnh - Tỉnh Khánh Hòa', '', 'Trần Minh', 0, '1511584731', 'Khánh Nam', 'Chủ tịch', 'Đỗ Hồng Việt', NULL, '1511580879', 'KVXP', '1', 'Xã loại 3', 'NGANSACH', NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(227, '1511585891', NULL, 'UBND xã Khánh Trung  ', 'Thôn Suối Cá, xã Khánh Trung, huyện Khánh Vĩnh, tỉnh Khánh Hòa', '0258 3790966', 'Cao Bơ', 0, '1511584731', '', 'Chủ tịch UBND xã ', 'Đỗ Thị Hoa', NULL, '1511580879', 'KVXP', '4', 'Xã loại 3', 'NGANSACH', NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(228, '1511585914', NULL, 'UBND xã Khánh Đông ', 'Thôn Suối Cau, xã Khánh Đông, Khánh Vĩnh', '', 'Nguyễn Thanh Tuấn', 0, '1511584731', '', 'Chủ tịch', 'Phạm Thị Nguyệt', NULL, '1511580879', 'KVHCSN', '4', NULL, 'NGANSACH', NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(229, '1511585945', NULL, 'UBND xã Khánh Bình', 'Thôn Bến Khế, Xã Khánh Bình, huyện Khánh Vĩnh, tỉnh Khánh Hòa', '02583797254', 'Phan Đình Tuyến', 0, '1511584731', 'Khánh Hòa', 'Chủ tịch', 'Phạm Thị Thanh Thúy', NULL, '1511580879', 'KVXP', '4', 'Xã loại 2', 'NGANSACH', NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(230, '1511585971', NULL, 'UBND xã Khánh Hiệp', 'Thôn Hòn Lay - Xã Khánh Hiệp - Huyện Khánh Vĩnh ', '02583796000', 'Nguyễn Hùng Cường ', 0, '1511584731', '', 'Chủ tịch UBND xã ', 'Trần Thị Mỹ ', NULL, '1511580879', 'KVXP', '4', 'Xã loại 3', 'NGANSACH', NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(231, '1511707075', NULL, 'Trường Mầm non 2 Tháng 8', 'Số 76, đường 2 Tháng 8, Thị trấn Khánh Vĩnh, huyễn Khánh Vĩnh, tỉnh Khánh Hòa', '02583790609', 'Nguyễn Thị Kim Tuyến', 0, '1511585064', '', 'Hiệu trưởng', 'Nguyễn Thị Thơm', NULL, '1511580879', 'KVHCSN', '3', NULL, 'NGANSACH', 'DT', 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(232, '1511707102', NULL, 'Trường Mầm non A Xây', 'Khánh Nam, Khánh Vinh, Khánh Hòa', '', 'Lê Thị Hiền', 0, '1511707102', '', 'Hiệu trưởng', 'Hoàng Thị Loan', NULL, '1511580879', 'KVHCSN', '3', NULL, 'NGANSACH', NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(233, '1511707128', NULL, 'Trường Mẫu giáo Hoa Lan', '', '', '', 0, '1511585064', '', '', '', NULL, '1511580879', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(234, '1511707156', NULL, 'Trường MG Hoa Phượng', 'Thôn Hòn Lay - xã Khánh Hiệp - huyện Khánh Vĩnh - tỉnh Khánh Hòa', '02583796018', 'Nguyễn Thị Hồng Lam', 0, '1511585064', 'Thôn Hòn Lay - xã Khánh Hiệp - huyện Khánh Vĩnh - tỉnh Khánh Hòa', 'Hiệu trưởng', 'Lê Thị Đào', NULL, '1511580879', 'KVHCSN', '3', NULL, 'NGANSACH', 'DT', 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(235, '1511707186', NULL, 'Trường MG Hướng Dương', '', '', '', 0, '1511585064', '', '', '', NULL, '1511580879', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(236, '1511707217', NULL, 'Trường Mẫu giáo Sen Hồng', 'Suối Cau, Khánh Đông , Khánh Vĩnh, Khánh Hòa ', '02583797179', 'Trần Thị Bảo Thoa ', 0, '1511585064', '', 'Hiệu trưởng ', 'Phạm Thị Tình ', NULL, '1511580879', 'KVHCSN', '1', NULL, 'NGANSACH', NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(237, '1511707248', NULL, 'Trường Mẫu giáo Hoa Hồng', 'Khánh Thành, Khánh Vĩnh', '', 'Đỗ Thị Ngọc Thanh', 0, '1511585064', '', 'Hiệu trưởng', 'Văn Thị Hoài Nam', NULL, '1511580879', 'KVHCSN', '3', NULL, 'NGANSACH', 'DT', 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(238, '1511707277', NULL, 'Trường MN Họa My', 'Khánh Trung, Khánh Vĩnh ,Khánh Hòa', '', 'Lê Thị Biên', 0, '1511585064', '', 'Thủ trưởng đơn vị', 'Sầm Tân Hương', NULL, '1511580879', 'KVHCSN', '3', NULL, 'NGANSACH', 'DT', 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(239, '1511707303', NULL, 'Trường MG Hương Sen', 'Giang Ly, Khánh Vĩnh, Khánh Hòa', '02583793532', 'Hoàng Thị Xuyên', 0, '1511585064', '', '', 'Nguyễn Thị Sen', NULL, '1511580879', 'KVHCSN', '1', NULL, 'NGANSACH', 'DT', 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(240, '1511707331', NULL, 'Trường MG Sơn Ca', 'Xã Khánh Phú, huyện Khánh Vĩnh, tỉnh Khánh Hòa', '', 'Phạm Thị Hằng', 0, '1511585064', '', 'Hiệu trưởng', 'Lê Thị Hạnh ', NULL, '1511580879', 'KVHCSN', '1', NULL, 'NGANSACH', NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(241, '1511707356', NULL, 'Trường MG Sao Mai', 'Huyện Khánh Vĩnh, Tỉnh Khánh Hòa', '02586269005', 'Nguyễn Thị Anh Hoa', 0, '1511585064', '', 'Hiệu Trưởng', 'Nguyễn Kiều Như Lý', NULL, '1511580879', 'KVHCSN', '3', NULL, 'NGANSACH', 'DT', 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(242, '1511707381', NULL, 'Trường MN Trầm Hương', 'Bến Khế - Khánh Bình - Khánh Vĩnh - Khánh Hòa', '0258.3797377', 'Hồ Thị Mỹ Dung', 0, '1511585064', '', 'Hiệu trưởng', 'Hoàng Nữ Sao Băng', NULL, '1511580879', 'KVHCSN', '1', NULL, 'NGANSACH', NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(243, '1511707433', NULL, 'Trường mầm non Vành Khuyên', 'thôn Đông, xã Sông Cầu, huyện Khánh Vĩnh, tỉnh Khánh Hòa', '02583790062', 'Lê Nguyễn Tường Vy', 0, '1511585064', '', 'Hiệu trưởng', 'Hồ Hoàng Liên', NULL, '1511580879', 'KVHCSN', '3', '', 'NGANSACH', 'DT', 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(244, '1511707466', NULL, 'Trường MN Anh Đào', 'Thôn Bố Lang, xã Sơn Thái, huyện Khánh Vĩnh, Tỉnh Khánh Hòa', '02583793584', 'Lê Thị Hương', 0, '1511585064', 'Khánh Hòa', 'Hiệu trưởng', 'Nguyễn Thị Thanh', NULL, '1511580879', 'KVHCSN', '3', NULL, 'NGANSACH', NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(245, '1511707493', NULL, 'Trường MG Hoa Mai', 'Thôn Trang, Khánh Thượng, Khánh Vĩnh, Khánh Hòa', '02583793568', 'Dương Thị Hằng', 0, '1511585064', '', 'Hiệu trưởng', 'Tạ Thị Duyên', NULL, '1511580879', 'KVHCSN', '3', NULL, 'NGANSACH', NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(246, '1511707519', NULL, 'Trường Mầm non Hoa Phượng 1', 'Khánh Hiệp, Khánh Vĩnh, Khánh Hòa', '02583796002', 'Ngô Trần Nhật Linh', 0, '1511585064', 'Khánh Vĩnh', 'Hiệu Trưởng', 'Lê Kim Tường', NULL, '1511580879', 'KVHCSN', '3', NULL, 'NGANSACH', NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(247, '1511707544', NULL, 'Trường MN Ngọc Lan', '', '', '', 0, '1511585064', '', '', '', NULL, '1511580879', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(248, '1511707573', NULL, 'Trường TH Cầu Bà', 'Cầu Bà, Khánh Vĩnh, Khánh Hòa', '02583793007', 'Tạ Văn Trinh', 0, '1511585064', 'Khánh Hòa', 'Hiệu trưởng', 'Huỳnh Thị Xuân Huệ', NULL, '1511580879', 'KVHCSN', '3', NULL, 'NGANSACH', 'DT', 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(249, '1511707603', NULL, 'Trường TH Giang Ly', 'Thôn Gia Rích, Giang Ly, Khánh Vĩnh, Khánh Hòa', '02583793514', 'Nguyễn Thanh Bình', 0, '1511585064', '', '', 'Hoàng Thị Thùy Trâm', NULL, '1511580879', 'KVHCSN', '1', NULL, 'NGANSACH', NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(250, '1511707632', NULL, 'Trường TH Khánh Bình', '', '', '', 0, '1511585064', '', '', '', NULL, '1511580879', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(251, '1511707656', NULL, 'Trường TH Khánh Hiệp', 'Hòn Lay - Khánh Hiệp - Khánh Vĩnh', '', 'Nguyễn Văn Phu', 0, '1511585064', '', 'Hiệu trưởng', 'Nguyễn Thị Lượng', NULL, '1511580879', 'KVHCSN', '1', NULL, 'NGANSACH', NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(252, '1511707684', NULL, 'Trường TH Khánh Nam', 'Thôn Hòn Dù - xã Khánh Nam - Khánh Vĩnh', '0258790596', 'Nguyễn Văn Sỹ', 0, '1511585064', 'Khánh Vĩnh ', 'Hiệu trưởng', 'Nguyễn Thuỳ Liên Thư', NULL, '1511580879', 'KVHCSN', '3', NULL, 'NGANSACH', 'DT', 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(253, '1511707710', NULL, 'Trường Th Khánh Đông', 'Khánh Đông, Khánh Vĩnh, Khánh Hòa', '02583797101', 'Bùi Quý Hải', 0, '1511585064', 'Khánh Đông, Khánh Vĩnh, Khánh Hòa', 'Hiệu trưởng', 'Hồ Văn Tý', NULL, '1511580879', 'KVHCSN', '3', NULL, 'NGANSACH', 'DT', 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(254, '1511707735', NULL, 'Trường TH Khánh Phú', 'Khánh Phú - Khánh Vĩnh - Khánh Hòa', '', 'Lê Xuân Tân', 0, '1511585064', '', 'Hiệu Trưởng', 'Đỗ Ngọc Nhứt', NULL, '1511580879', 'KVHCSN', '3', NULL, 'NGANSACH', 'DT', 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(255, '1511707759', NULL, 'Trường TH Khánh Thành', 'Thôn Giòng Cạo, xã Khánh Thành, Huyện Khánh Vĩnh, Tỉnh Khánh Hòa', '0987156602', 'Lê Thị Tiết', 0, '1511585064', 'Huyện Khánh Vĩnh', 'Hiệu trưởng', 'Lê Thị Hồng Huệ', NULL, '1511580879', 'KVHCSN', '1', NULL, 'NGANSACH', 'DT', 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(256, '1511707784', NULL, 'Trường Tiểu Học Khánh Thượng', 'Xã Khánh Thượng, Khánh Vĩnh, Khánh Hòa', '0979358464', 'Nguyễn Công Đại', 0, '1511585064', '', 'Hiệu Trưởng', 'Võ Nữ Phương Thùy', NULL, '1511580879', 'KVHCSN', '3', NULL, 'NGANSACH', NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(257, '1511707808', NULL, 'Trường Tiểu học Khánh Trung', 'Thôn Suối Cá, xã Khánh Trung, huyện Khánh Vĩnh, tỉnh Khánh Hòa', '0987402192', 'Phan Tiến Duẩn', 0, '1511585064', 'Khánh Vinh', 'Hiệu trưởng', 'Nguyễn Thị Lương', NULL, '1511580879', 'KVHCSN', '3', NULL, 'NGANSACH', NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(258, '1511707831', NULL, 'Trường TH Liên Sang', '', '', '', 0, '1511585064', '', '', '', NULL, '1511580879', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(259, '1511707857', NULL, 'Trường TH Sông Cầu', '', '', '', 0, '1511585064', '', '', '', NULL, '1511580879', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(260, '1511707885', NULL, 'Trường Tiểu Học Sơn Thái', 'Sơn Thái, Khánh Vĩnh, Khánh Hòa', '02583793541', 'Đinh Như Thu', 0, '1511585064', 'Khánh Hòa', 'Hiệu trưởng', 'Nguyễn Anh Tuấn', NULL, '1511580879', 'KVHCSN', '3', NULL, 'NGANSACH', 'DT', 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(261, '1511707919', NULL, 'Trường TH Thị Trấn', 'Thị trấn Khánh Vĩnh', '', 'Nguyễn Trọng Sỹ', 0, '1511585064', '', '', 'Nguyễn Thị Phương', NULL, '1511580879', 'KVHCSN', '3', NULL, 'NGANSACH', NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(262, '1511707947', NULL, 'Trường TH Khánh Hiệp 1', 'Thôn Soi Mít - xã Khánh Hiệp - huyện Khánh Vĩnh tỉnh Khánh Hòa', '02583900559', 'Lê Thị Ánh', 0, '1511585064', '', 'Hiệu Trưởng', 'Nguyễn Tiến Cường', NULL, '1511580879', 'KVHCSN', '3', NULL, 'NGANSACH', 'DT', 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(263, '1511707972', NULL, 'Trường Tiểu học Khánh Phú 1', 'Thôn Ngã Hai, xã Khánh Phú, huyện Khánh Vĩnh, tỉnh Khánh Hòa', '', 'Nguyễn Thị Thủy', 0, '1511585064', 'Khánh Vĩnh', 'Hiệu trưởng', 'Nguyễn Thị Thu Hiền', NULL, '1511580879', 'KVHCSN', '3', NULL, 'NGANSACH', 'DT', 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(264, '1511707997', NULL, 'Trường THCS Lê Văn Tám', '', '', 'Vy Viết Quý', 0, '1511585064', '', '', 'Lương Thị Hường', NULL, '1511580879', 'KVHCSN', '1', NULL, 'NGANSACH', NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(265, '1511708022', NULL, 'Trường THCS Nguyễn Thái Bình', 'Bến Khế - Khánh Bình - Khánh Vĩnh - Khánh Hòa', '02583797267', 'Hoàng Thị Hạnh', 0, '1511585064', '', 'Hiệu trưởng', 'Nguyễn Thị Tâm', NULL, '1511580879', 'KVHCSN', '3', NULL, 'NGANSACH', 'DT', 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(266, '1511708046', NULL, 'Trường THCS Thị Trấn', '', '', '', 0, '1511585064', '', '', '', NULL, '1511580879', 'KVHCSN', '1', NULL, 'NGANSACH', NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(267, '1511708068', NULL, 'Trường THCS Chu Văn An', '', '', '', 0, '1511585064', '', '', '', NULL, '1511580879', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(268, '1511708094', NULL, 'Trường THCS Nguyễn Bỉnh Khiêm', '', '', '', 0, '1511585064', '', '', '', NULL, '1511580879', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(269, '1511708117', NULL, 'Trường THCS Cao Văn Bé', 'Khánh Phú, Khánh Vĩnh, Khánh Hòa', '', 'Nguyễn Minh Hùng', 0, '1511585064', 'Khánh Hòa', 'Hiệu trưởng', 'Phạm Thị Thanh', NULL, '1511580879', 'KVHCSN', '3', NULL, 'CTX', NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(270, '1511708141', NULL, 'Trường PTDTNT cấp THCS Huyện Khánh Vĩnh', 'Số 80 đường 2/8, Thị trấn Khánh Vĩnh, Huyện Khánh Vĩnh, Khánh Hòa', '02583790747', 'Nguyễn Văn Thọ', 0, '1511585064', '', 'Hiệu trưởng', 'Trần Thị Minh Trang', NULL, '1511580879', 'KVHCSN', '3', NULL, 'NGANSACH', NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(271, '1511708261', NULL, 'Phòng Tài chính - Kế hoạch', '', '', '', 0, NULL, '', '', '', NULL, '1511580911', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(272, '1511708285', NULL, 'Văn phòng HĐND & UBND', '469 Hùng Vương - Thị Trấn Vạn Giã - Vạn Ninh - Khánh Hoà', '02583910921', 'Lê Văn Khải', 0, '1511708261', '', 'Chánh Văn phòng', 'Lê Thị Thanh Hải', NULL, '1511580911', 'KVHCSN', '3', NULL, 'NGANSACH', 'QLNN', 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(273, '1511708305', NULL, 'Phòng Tư pháp', '469 hùng Vương, TT Vạn Giã, huyện Vạn Ninh', '', 'Hồ Quang Thành', 0, '1511708261', '', 'Trưởng Phòng', 'Nguyễn Thị Thu Vân', NULL, '1511580911', 'KVHCSN', '3', NULL, 'NGANSACH', NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(274, '1511708326', NULL, 'Phòng Quản lý đô thị', '', '', '', 0, '1511708261', '', '', '', NULL, '1511580911', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(275, '1511708347', NULL, 'Phòng Kinh tế', '', '', '', 0, '1511708261', '', '', '', NULL, '1511580911', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(276, '1511708368', NULL, 'Phòng Giáo dục và Đào tạo', '', '', '', 0, '1511708261', '', '', '', NULL, '1511580911', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(277, '1511708393', NULL, 'Phòng Y tế', '469 Hùng Vương, Vạn Giã, Vạn Ninh, Khánh Hòa ', '02583911844', 'Trần Công Hiền', 0, '1511708261', '', 'Phó Trưởng Phòng ', 'Đinh Trần Lệ Dương ', NULL, '1511580911', 'KVHCSN', '3', NULL, 'NGANSACH', 'QLNN', 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(278, '1511708424', NULL, 'Phòng LĐTBXH  ', '469 Hùng Vương, tt Vạn Giã,huyện Vạn Ninh', '02583840643', 'Vũ Thị Kim Trinh', 0, '1511708261', '', 'Trưởng phòng', 'Nguyễn Thị Huyền', NULL, '1511580911', 'KVHCSN', '3', NULL, 'NGANSACH', 'QLNN', 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(279, '1511708445', NULL, 'Phòng Văn hóa thông tin', '', '', '', 0, '1511708261', '', '', '', NULL, '1511580911', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(280, '1511708467', NULL, 'Phòng Tài nguyên và Môi trường', '463 Hùng Vương, thị trấn Vạn Giã, huyện Vạn Ninh, tỉnh Khánh Hòa', '02583840566', 'Võ Thành Sơn', 0, '1511708261', '', 'Trưởng phòng', 'Nguyễn Thị Tú', NULL, '1511580911', 'KVHCSN', '3', NULL, 'NGANSACH', 'QLNN', 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(281, '1511708488', NULL, 'Phòng Nội vụ', '', '', '', 0, '1511708261', '', '', '', NULL, '1511580911', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(282, '1511708509', NULL, 'Thanh tra huyện Vạn Ninh', '469 Hùng Vương, thị trấn Vạn Giã, huyện Vạn Ninh, tỉnh Khánh Hoà', '0258.3910226', 'Phạm Vân', 0, '1511708261', '', 'Chánh Thanh Tra', 'Tô Đào Thảo Nguyên', NULL, '1511580911', 'KVHCSN', '1', NULL, 'NGANSACH', 'QLNN', 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(283, '1511708531', NULL, 'Huyện ủy', '', '', '', 0, '1511708261', '', '', '', NULL, '1511580911', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(284, '1511708566', NULL, 'Ủy ban Mặt trận Tổ quốc Việt Nam huyện Vạn Ninh', '364 Hùng Vương. Thị trấn vạn giã, Vạn ninh, khánh hòa ', '02583 840340', 'Huỳnh Ngọc Thơ ', 0, '1511708261', '', 'Chủ tịch ', 'Ngô Kim Thiên ', NULL, '1511580911', 'KVHCSN', '1', NULL, 'NGANSACH', NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(285, '1511708587', NULL, 'Hội Chữ thập đỏ', '', '', '', 0, '1511708261', '', '', '', NULL, '1511580911', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(286, '1511708610', NULL, 'Hội Đông y', '', '', '', 0, '1511708261', '', '', '', NULL, '1511580911', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(287, '1511708633', NULL, 'Huyện đoàn', '499 Hùng Vương - Vạn Giã - Vạn Ninh', '02583840347', 'Nguyễn Hữu Tuấn', 0, '1511708261', '', 'Bí thư', 'Nguyễn Hữu Thái Khang', NULL, '1511580911', 'KVHCSN', '1', NULL, 'NGANSACH', 'DDT', 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(288, '1511708655', NULL, 'Hội LH Phụ nữ', '364 Hùng Vương, Vạn Giã, Vạn Ninh', '02583913209', 'Phan Thị Tuyết Nhung', 0, '1511708261', '', 'Chủ tịch', 'Nguyễn Thị Khánh Vân', NULL, '1511580911', 'KVHCSN', '1', NULL, 'NGANSACH', NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(289, '1511708679', NULL, 'Hội Nông dân', '', '', '', 0, '1511708261', '', '', '', NULL, '1511580911', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(290, '1511708704', NULL, 'Hội Cựu chiến binh', '', '', '', 0, '1511708261', '', '', '', NULL, '1511580911', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(291, '1511708732', NULL, 'Đài phát thanh truyền hình', '', '', '', 0, '1511708261', '', '', '', NULL, '1511580911', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(292, '1511708757', NULL, 'Trung tâm Phát triển Quỹ đất', '473 Hùng Vương, thị trấn Vạn Giã, Vạn Ninh, Khánh Hòa', '', 'Nguyễn Trường Sinh', 0, '1511708261', '', 'Giám đốc', 'Nguyễn Thị Thu Hằng', NULL, '1511580911', 'KVHCSN', '3', NULL, 'NGANSACH', 'KT', 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(293, '1511708782', NULL, 'Trung tâm Văn hóa thể thao', '', '', '', 0, '1511708261', '', '', '', NULL, '1511580911', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(294, '1511708806', NULL, 'Nhà Thiếu nhi', '499 Hùng Vương, Vạn Giã, Vạn Ninh', '02583840780', 'Nguyễn Hữu Tuấn', 0, '1511708261', '', 'Giám đốc', 'Đặng Thị Va Lê', NULL, '1511580911', 'KVHCSN', '1', NULL, 'NGANSACH', NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(295, '1511708972', NULL, 'Trung tâm Bồi dưỡng chính trị', '', '', '', 0, '1511708261', '', '', '', NULL, '1511580911', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(296, '1511708996', NULL, 'Ủy ban nhân dân thị trấn Vạn Giã', '479 Hùng Vương - Thị trấn Vạn Giã', '0258910483', 'Nguyễn Công Bằng', 0, '1511708261', '', 'Chủ tịch', 'Nguyễn Mai Ngọc', NULL, '1511580911', 'KVXP', '1', 'XL1', 'NGANSACH', NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(297, '1511709018', NULL, 'Ủy ban nhân dân xã Đại Lãnh', 'Đại Lãnh - Vạn Ninh - Khánh Hòa', '3 842 102', 'Trần Đình Thú', 0, '1511708261', '', 'Chủ tịch', 'Trương Thị Xuân Cảnh', NULL, '1511580911', 'KVXP', '4', 'XL1', 'NGANSACH', NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(298, '1511709040', NULL, 'Ủy ban nhân dân xã Vạn Bình', 'Trung Dõng - Vạn Bình - Vạn Ninh - Khánh Hòa', '0258.3840.676', 'Mai Hữu Xuân', 0, '1511708261', '', 'Chủ tịch', 'Trần Ngọc Điệp', NULL, '1511580911', 'KVXP', '4', 'XL2', 'NGANSACH', NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(299, '1511709071', NULL, 'Ủy ban nhân dân xã Vạn Hưng', '', '', '', 0, '1511708261', '', '', '', NULL, '1511580911', 'KVXP', '1', 'XL1', 'NGANSACH', NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(300, '1511709092', NULL, 'Ủy ban nhân dân xã Vạn Khánh', 'Thôn Nhơn Thọ, Vạn Khánh, Vạn Ninh, Khánh Hòa', '02583931293', 'Nguyễn Hữu Danh', 0, '1511708261', '', 'Chủ tịch', 'Lê Đình Thuần', NULL, '1511580911', 'KVXP', '1', 'XL1', 'NGANSACH', NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(301, '1511709119', NULL, 'Ủy ban nhân dân xã Vạn Long', '', '', '', 0, '1511708261', '', '', '', NULL, '1511580911', 'KVXP', '1', '', NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(302, '1511709141', NULL, 'Ủy ban nhân dân xã Vạn Lương', '', '', '', 0, '1511708261', '', '', '', NULL, '1511580911', 'KVXP', '1', '--Chọn phân loại xã--', NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(303, '1511709163', NULL, 'Ủy ban nhân dân xã Vạn Phú', 'Vạn Phú, Vạn Ninh, Khánh Hòa', '01688705356', 'Nguyễn Như Thiết', 0, '1511708261', 'Vạn Phú, Vạn Ninh, Khánh Hòa', '', 'Huỳnh Thị Kim Loan', NULL, '1511580911', 'KVXP', '4', 'XL1', 'CTX', NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(304, '1511709186', NULL, 'Ủy ban nhân dân xã Vạn Phước', '', '', '', 0, '1511708261', '', '', '', NULL, '1511580911', 'KVXP', '1', '', NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(305, '1511709208', NULL, 'Ủy ban nhân dân xã Vạn Thạnh', 'Đầm Môn - Vạn Thạnh - Vạn Ninh - Khánh Hòa', '0583939011', 'Nguyễn Thanh Nam', 0, '1511708261', '', 'Chủ tịch', 'Đoàn Thị Ngọc Duyên', NULL, '1511580911', 'KVXP', '1', NULL, 'NGANSACH', NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(306, '1511709233', NULL, 'Ủy ban nhân dân xã Vạn Thắng', 'Phú Hội 2, Vạn Thắng, Vạn Ninh, Khánh Hòa', '0912273331', 'Nguyễn Sáng', 0, '1511708261', '', 'Chủ tịch UBND xã', 'Lê Thị Minh Nguyễn', NULL, '1511580911', 'KVXP', '4', 'XL1', 'NGANSACH', NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(307, '1511709256', NULL, 'Ủy ban nhân dân xã Vạn Thọ', 'Vạn Thọ - Vạn Ninh - Khánh Hòa', '02583938299', 'Lương Văn Tín', 0, '1511708261', '', 'Chủ Tịch', 'Phan Thị Kim Anh', NULL, '1511580911', 'KVXP', '4', 'XL2', 'NGANSACH', NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(308, '1511709280', NULL, 'Ủy ban nhân dân xã Xuân Sơn', 'Thôn Xuân Trang, xã Xuân Sơn, huyện Vạn Ninh, tỉnh Khánh Hòa', '02583946006', 'Đỗ Văn Thắng', 0, '1511709280', '', 'Chủ tịch UBND xã', 'Lê Tấn Hoàng', NULL, '1511580911', 'KVXP', '4', 'XL2', 'NGANSACH', NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(309, '1511709376', NULL, 'Trường TH Đại lãnh 1', 'Xã Đại Lãnh huyện Vạn Ninh', '02583842456', 'Trần Kim Sơn', 0, '1511708368', 'Đại Lãnh', 'Hiệu trưởng', 'Bùi Duy Phong', NULL, '1511580911', 'KVHCSN', '1', NULL, 'NGANSACH', 'DT', 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(310, '1511709404', NULL, 'Trường TH Đại lãnh 2', '', '', '', 0, '1511708368', '', '', '', NULL, '1511580911', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(311, '1511709427', NULL, 'Trường TH Vạn Thọ 1', '', '', '', 0, '1511708368', '', '', '', NULL, '1511580911', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(312, '1511709453', NULL, 'Trường TH Vạn Thọ 2', 'Vạn Thọ, Vạn Ninh, Khánh Hòa', '02583938077', 'Ngô Thị Vy', 0, '1511708368', '', '', 'Trần Thị Kim Liên', NULL, '1511580911', 'KVHCSN', '1', NULL, 'NGANSACH', NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(313, '1511709484', NULL, 'Trường TH Vạn Phước 1', 'Tân Phước Tây, Vạn phước, vạn Ninh , Khánh Hòa', '02583930976', 'Lê Hùng', 0, '1511708368', 'Vạn Phước, Vạn Ninh, Khánh Hòa', 'Hiệu trưởng', 'Nguyễn Thị Nguyệt', NULL, '1511580911', 'KVHCSN', '1', NULL, 'NGANSACH', 'DT', 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(314, '1511709513', NULL, 'Trường TH Vạn Phước 2', 'Tân Phước Bắc - Vạn Phước - Vạn Ninh - Khánh Hòa', '02583843231', 'Ngô Văn Hải', 0, '1511708368', '', 'Hiệu trưởng', 'Phan Thị Hoàng Hảo', NULL, '1511580911', 'KVHCSN', '3', NULL, 'NGANSACH', 'DT', 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(315, '1511709537', NULL, 'Trường TH Vạn Long', 'Long Hòa -Vạn Long -Vạn Ninh- Khánh Hòa ', '02583843022', 'Trần Văn Kim', 0, '1511708368', 'Vạn Long - Vạn Ninh -Khánh Hòa ', 'Hiệu Trưởng', 'Nguyễn Thị Mộng Huyền ', NULL, '1511580911', 'KVHCSN', '3', NULL, 'NGANSACH', 'DT', 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(316, '1511709561', NULL, 'Trường TH Vạn Khánh 1', '', '', '', 0, '1511708368', '', '', '', NULL, '1511580911', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(317, '1511709583', NULL, 'Trường TH Vạn Khánh 2', 'Thôn Tiên Ninh Xã Vạn Khánh huyện Vạn Ninh tỉnh Khánh Hòa', '02583934193', 'Phan Đình Thuận', 0, '1511708368', 'Vạn Khánh', 'Hiệu trưởng', 'Nguyễn Văn Khánh', NULL, '1511580911', 'KVHCSN', '3', NULL, 'NGANSACH', 'DT', 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(318, '1511709608', NULL, 'Trường TH Vạn Bình', '', '', '', 0, '1511708368', '', '', '', NULL, '1511580911', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(319, '1511709632', NULL, 'Trường TH Vạn Thắng 1', 'Vạn Thắng , Vạn Ninh, Khánh Hòa', '02583840358', 'Nguyễn Thị Tú Oanh', 0, '1511708368', 'Vạn Thắng', 'Hiệu trưởng', 'Bùi Văn Hùng', NULL, '1511580911', 'KVHCSN', '2', NULL, 'NGANSACH', 'DT', 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(320, '1511709665', NULL, 'Trường TH Vạn Thắng 2', '', '', '', 0, '1511708368', '', '', '', NULL, '1511580911', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(321, '1511709687', NULL, 'Trường TH Vạn Thắng 3', 'Tân dân - Van Thắng - van ninh- khánh hòa', '02583934244', 'Ngu', 0, '1511708368', '', '', '', NULL, '1511580911', 'KVHCSN', '1', NULL, 'NGANSACH', NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(322, '1511709714', NULL, 'Trường TH Vạn Phú 1', 'Thôn Phú Cang 1-Vạn Phú-Vạn Ninh-Khánh Hòa', '0258.3840690', 'Mạch Hồng Phong', 0, '1511708368', 'xã Vạn Phú-Vạn Ninh-Khánh Hòa', 'Hiệu trưởng', 'Trần Thị Thu Sương', NULL, '1511580911', 'KVHCSN', '1', NULL, 'NGANSACH', NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(323, '1511709737', NULL, 'Trường TH Vạn phú 2', 'Phú Cang 2, Xã Vạn Phú', '02583841188', 'Huỳnh Ngọc Minh', 0, '1511708368', 'Vạn Phú', 'Hiệu trưởng', 'Lê Thị Đào', NULL, '1511580911', 'KVHCSN', '3', NULL, 'NGANSACH', 'DT', 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(324, '1511709761', NULL, 'Trường TH Vạn phú 3', '', '', '', 0, '1511708368', '', '', '', NULL, '1511580911', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(325, '1511709787', NULL, 'Trường TH Vạn Giã 1', '46 Đinh Tiên Hoàng', '05823840329', 'Lữ Ngọc Minh', 0, '1511708368', 'TT Vạn Giã , Huyện Vạn Ninh, Tỉnh Khánh Hòa', 'Hiệu Trưởng', 'Trần Thị Xương Vương', NULL, '1511580911', 'KVHCSN', '3', NULL, 'NGANSACH', 'DT', 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(326, '1511709808', NULL, 'Trường TH Vạn Giã 2', '', '', '', 0, '1511708368', '', '', '', NULL, '1511580911', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(327, '1511709831', NULL, 'Trường TH Vạn Giã 3', 'Tổ dân phố số 14, Thị trấn Vạn Giã, Vạn Ninh, Khánh Hòa', '02583913710', 'Lưu Thị Phúc', 0, '1511708368', 'Tổ dân phố số 14, Thị trấn Vạn Giã, Vạn Ninh, Khánh Hòa', 'Hiệu trưởng', 'Đàm Thị Minh Thanh', NULL, '1511580911', 'KVHCSN', '3', NULL, 'NGANSACH', 'GD', 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(328, '1511709859', NULL, 'Trường TH Vạn Lương 1', 'Hiền Lương, Vạn Lương, Vạn Ninh, Khánh Hòa', '02583612257', 'Đồng Văn Hài', 0, '1511708368', '', 'Hiệu Trưởng', 'Lê Thị Lam', NULL, '1511580911', 'KVHCSN', '1', NULL, 'NGANSACH', 'DT', 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(329, '1511709882', NULL, 'Trường TH Vạn Lương 2', '', '', '', 0, '1511708368', '', '', '', NULL, '1511580911', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(330, '1511709906', NULL, 'Trường TH Vạn Hưng 1 ', 'Thôn Xuân Tự 1, Xã Vạn Hưng, Vạn Ninh , Khánh Hòa', '02583612019', 'Nguyễn Thị Phương Linh', 0, '1511708368', 'Vạn  Hưng', 'Hiệu trưởng', 'Lê Thị Mỹ Phương ', NULL, '1511580911', 'KVHCSN', '4', NULL, 'NGANSACH', NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(331, '1511709940', NULL, 'Trường TH Vạn Hưng 2', 'Xuân Vinh, Vạn Hưng, Vạn Ninh, Khánh Hòa', '0258 3612048', 'Nguyễn Xuân Duẩn', 0, '1511708368', 'Xuân Vinh, Vạn Hưng, Vạn Ninh, Khánh Hòa', 'Hiệu trưởng', 'Trần Thị Quỳnh Châu', NULL, '1511580911', 'KVHCSN', '3', NULL, 'NGANSACH', 'DT', 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(332, '1511709961', NULL, 'Trường TH Vạn Hưng 3', 'Vạn Hưng, vạn Ninh, Khánh Hoà', '02583945242', 'Lương Đình Bình', 0, '1511708368', '', 'Hiệu trưởng', 'Phan Thi Thanh Lan', NULL, '1511580911', 'KVHCSN', '3', NULL, 'NGANSACH', 'GD', 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(333, '1511709984', NULL, 'Trường TH Xuân Sơn', '', '', '', 0, '1511708368', '', '', '', NULL, '1511580911', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(334, '1511710009', NULL, 'Trường TH Vạn Thạnh 2', '', '', 'hoan', 0, '1511708368', '', '', 'hải', NULL, '1511580911', 'KVHCSN', '3', NULL, 'NGANSACH', 'DT', 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(335, '1511710033', NULL, 'Trường THCS Chi Lăng', 'Tây Bắc 2, Đại Lãnh, Vạn Ninh, Khánh Hòa', '02583842752', 'Ngô Đông Vũ', 0, '1511708368', '', 'Hiệu Trưởng', 'Võ Duy Tân', NULL, '1511580911', 'KVHCSN', '1', NULL, 'NGANSACH', NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(336, '1511710058', NULL, 'Trường THCS Lương Thế Vinh', '', '', '', 0, '1511708368', '', '', '', NULL, '1511580911', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(337, '1511710080', NULL, 'Trường THCS Nguyễn Huệ', '', '', '', 0, '1511708368', '', '', '', NULL, '1511580911', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(338, '1511710105', NULL, 'Trường THCS Trần Quốc Tuấn', '', '', '', 0, '1511708368', '', '', '', NULL, '1511580911', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(339, '1511710134', NULL, 'Trường Nguyễn Trung Trực', '', '', '', 0, '1511708368', '', '', '', NULL, '1511580911', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(340, '1511710158', NULL, 'Trường THCS Nguyễn Bỉnh Khiêm', 'Phú Hội 2- Vạn Thắng-Vạn Ninh-Khánh Hòa', '0984430626', 'Phùng Thanh Phong', 0, '1511708368', 'Vạn Thắng', 'Hiệu trưởng', 'Lưu Thị Sâu', NULL, '1511580911', 'KVHCSN', '3', NULL, 'NGANSACH', 'DT', 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(341, '1511710196', NULL, 'Trường THCS Trần Phú', 'Trung Dõng- Vạn Bình', '02583911218', 'Hồ văn Quốc', 0, '1511708368', 'Vạn Bình', 'Hiệu trưởng', 'Bùi Anh Tuấn', NULL, '1511580911', 'KVXP', '2', 'Xã loại 1', 'NGANSACH', NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(342, '1511710234', NULL, 'Trường THCS Mê Linh', 'Vạn Phú, VạnNinh, Khánh Hòa', '02583842571', 'Huỳnh Văn Giáo', 0, '1511708368', 'Vạn Phú', 'Hiệu trưởng', 'Nguyễn Thơm', NULL, '1511580911', 'KVHCSN', '3', NULL, 'NGANSACH', 'DT', 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(343, '1511710259', NULL, 'Trường THCS Văn Lang', '505 Hùng Vương, Thị trấn, Vạn Giã, Vạn Ninh', '02 58 3840249', 'Nguyễn Chí Kỳ', 0, '1511708368', 'Vạn Giã', 'Hiệu trưởng', 'Trần Thị Lệ Hoa', NULL, '1511580911', 'KVHCSN', '1', NULL, 'NGANSACH', 'DT', 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(344, '1511710284', NULL, 'Trường THCS Đống Đa', 'Quảng Phước - Vạn Lương- Vạn Ninh - Khánh Hòa', '02583943219', 'Nguyễn Thành Liêm', 0, '1511708368', 'Vạn Lương', 'Hiệu Trưởng', 'Huỳnh Thị Thời', NULL, '1511580911', 'KVHCSN', '4', NULL, 'NGANSACH', NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(345, '1511710307', NULL, 'Trường THCS Lý Thường Kiệt', '', '', '', 0, '1511708368', '', '', '', NULL, '1511580911', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(346, '1511710331', NULL, 'Trường THCS Hoa Lư', 'Xuân Sơn, Vạn Ninh, Khánh Hòa', '02583946054', 'Trần Thị Thu Hồng', 0, '1511708368', 'Xuân Sơn', 'Hiệu trưởng', 'Nguyễn Thị Sương', NULL, '1511580911', 'KVHCSN', '3', NULL, 'NGANSACH', 'DT', 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(347, '1511710355', NULL, 'Trường PT cấp 1-2 Vạn Thạnh', 'Vạn Thạnh, Vạn Ninh, Khánh Hòa', '0583939015', 'Nguyễn Văn Mốt', 0, '1511708368', 'Vạn Thạnh', 'Hiệu Trưởng', 'Nguyễn Thị Kim Lợt', NULL, '1511580911', 'KVHCSN', '3', NULL, 'NGANSACH', 'DT', 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(348, '1511710377', NULL, 'Trường MN Bình Minh', '', '', '', 0, '1511708368', '', '', '', NULL, '1511580911', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(349, '1511710399', NULL, 'Trường MG Họa My', '', '', '', 0, '1511708368', '', '', '', NULL, '1511580911', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(350, '1511710429', NULL, 'Trường Mầm non Xuân Sơn', 'Xuân Trang, Xuân Sơn, Vạn Ninh, Khánh Hòa', '0258 3946 119', 'Trần Thị Thu', 0, '1511708368', '', 'Hiệu Trưởng', 'Trần Thị Ngân', NULL, '1511580911', 'KVHCSN', '1', NULL, 'NGANSACH', NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(351, '1511710452', NULL, 'Trường MN Đại Lãnh', 'Thôn tây bắc 1 - Xã Đại Lãnh - Vạn Ninh- Khánh Hòa ', '02583842123', 'Nguyễn Thị Mỹ Lộc ', 0, '1511708368', '', 'Hiệu Trưởng ', 'Nguyễn Thị Vâng ', NULL, '1511580911', 'KVHCSN', '1', NULL, 'NGANSACH', NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(352, '1511710477', NULL, 'Trường MG Vạn Thọ', 'Cổ Mã - Vạn Thọ - Vạn Ninh - Khánh Hòa', '02583938425', 'Lê Thị Liên', 0, '1511708368', '', 'Hiệu trưởng', 'Nguyễn Thị Tuyết Mai', NULL, '1511580911', 'KVHCSN', '3', NULL, 'NGANSACH', 'DT', 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(353, '1511710520', NULL, 'Trường MG Vạn Phước', 'Vạn Phước, Vạn Ninh, Khánh Hòa', '02583930202', 'Ngô Thị Xuân Đài', 0, '1511708368', '', 'Hiệu trưởng', 'Nguyễn Thị Vy Oanh', NULL, '1511580911', 'KVHCSN', '3', NULL, 'NGANSACH', NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(354, '1511710544', NULL, 'Trường MG Vạn Long', '', '', '', 0, '1511708368', '', '', '', NULL, '1511580911', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(355, '1511710566', NULL, 'Trường MG Vạn Khánh', '', '', '', 0, '1511708368', '', '', '', NULL, '1511580911', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(356, '1511710589', NULL, 'Trường MG Vạn Bình', 'Trung Dõng 1 - Vạn Bình - Vạn Ninh - Khánh Hòa', '02583913458', 'Huỳnh Thị Linh Phương', 0, '1511708368', '', '', 'Võ Tố Trinh', NULL, '1511580911', 'KVHCSN', '3', NULL, 'NGANSACH', NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(357, '1511710614', NULL, 'Trường MG Vạn Thắng', 'Quảng Hội, vạn Thắng, Vạn Ninh, Khánh Hòa', '02583911685', 'Hàn Ái Hằng', 0, '1511708368', '', 'Hiệu trưởng', 'Nguyễn Thị Hoàn Hạ', NULL, '1511580911', 'KVHCSN', '1', NULL, 'NGANSACH', NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(358, '1511710637', NULL, 'Trường MG Vạn Phú', '', '', '', 0, '1511708368', '', '', '', NULL, '1511580911', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(359, '1511710657', NULL, 'Trường MG Vạn giã', 'TDP số 4 TT Vạn Giã', '02583841770', 'Lê Thị Ngọc Trân', 0, '1511708368', '', 'Hiệu trưởng', 'Nguyễn Thị Ngọc Mai', NULL, '1511580911', 'KVHCSN', '3', NULL, 'NGANSACH', 'DT', 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(360, '1511710686', NULL, 'Trường MG Vạn Lương', '', '', '', 0, '1511708368', '', '', '', NULL, '1511580911', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(361, '1511710708', NULL, 'Trường MG Vạn Hưng', '', '', '', 0, '1511708368', '', '', '', NULL, '1511580911', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(362, '1511711090', NULL, 'Phòng Tài chính-Kế hoạch', '', '', '', 0, NULL, '', '', '', NULL, '1506416677', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(363, '1511711113', NULL, 'Văn phòng HĐND&UBND TP', '', '', '', 0, '1511711090', '', '', '', NULL, '1506416677', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(364, '1511711141', NULL, 'Phòng Nội vụ', '', '', '', 0, '1511711090', '', '', '', NULL, '1506416677', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL);
INSERT INTO `dmdonvi` (`id`, `madv`, `maqhns`, `tendv`, `diachi`, `sodt`, `lanhdao`, `songuoi`, `macqcq`, `diadanh`, `cdlanhdao`, `nguoilapbieu`, `makhoipb`, `madvbc`, `maphanloai`, `capdonvi`, `phanloaixa`, `phanloainguon`, `linhvuchoatdong`, `phanloaitaikhoan`, `phamvitonghop`, `pcct`, `pckct`, `pck`, `pccv`, `pckv`, `pcth`, `pcdd`, `pcdh`, `pcld`, `pcdbqh`, `pcudn`, `pctn`, `pctnn`, `pcdbn`, `pcvk`, `pckn`, `pcdang`, `pccovu`, `pclt`, `pcd`, `pctr`, `pctnvk`, `pcbdhdcu`, `pcthni`, `created_at`, `updated_at`) VALUES
(365, '1511711162', NULL, 'Thanh tra TP', '', '', '', 0, '1511711090', '', '', '', NULL, '1506416677', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(366, '1511711184', NULL, 'Phòng Tư pháp', '', '', '', 0, '1511711090', '', '', '', NULL, '1506416677', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(367, '1511711204', NULL, 'Phòng Kinh tế', '', '', '', 0, '1511711090', '', '', '', NULL, '1506416677', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(368, '1511711225', NULL, 'Phòng Lao động - TB&XH', '', '', '', 0, '1511711090', '', '', '', NULL, '1506416677', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(369, '1511711246', NULL, 'Phòng Văn hóa và Thông tin', '', '', '', 0, '1511711090', '', '', '', NULL, '1506416677', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(370, '1511711267', NULL, 'Phòng Y tế', '', '', '', 0, '1511711090', '', '', '', NULL, '1506416677', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(371, '1511711288', NULL, 'Phòng Giáo dục và Đào tạo ', '', '', '', 0, '1511711090', '', '', '', NULL, '1506416677', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(372, '1511711309', NULL, 'Phòng Quản lý Đô thị', '', '', '', 0, '1511711090', '', '', '', NULL, '1506416677', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(373, '1511711339', NULL, 'Phòng Tài nguyên và Môi trường ', '', '', '', 0, '1511711090', '', '', '', NULL, '1506416677', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(374, '1511711393', NULL, 'VP Thành ủy', '', '', '', 0, '1511711090', '', '', '', NULL, '1506416677', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(375, '1511711424', NULL, 'Ủy ban mặt trận Tổ quốc TP', '', '', '', 0, '1511711090', '', '', '', NULL, '1506416677', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(376, '1511711445', NULL, 'Hội Cựu chiến binh', '', '', '', 0, '1511711090', '', '', '', NULL, '1506416677', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(377, '1511711470', NULL, 'Hội Nông dân', '', '', '', 0, '1511711090', '', '', '', NULL, '1506416677', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(378, '1511711491', NULL, 'Hội Liên hiệp phụ nữ', '', '', '', 0, '1511711090', '', '', '', NULL, '1506416677', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(379, '1511711516', NULL, 'Thành Đoàn', '', '', '', 0, '1511711090', '', '', '', NULL, '1506416677', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(380, '1511711537', NULL, 'Hội Đông y', '', '', '', 0, '1511711090', '', '', '', NULL, '1506416677', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(381, '1511711559', NULL, 'Hội Chữ thập đỏ', '', '', '', 0, '1511711090', '', '', '', NULL, '1506416677', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(382, '1511711591', NULL, 'BQL Vịnh Nha Trang', '', '', '', 0, '1511711090', '', '', '', NULL, '1506416677', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(383, '1511711614', NULL, 'Đội Thanh niên xung kích', '', '', '', 0, '1511711090', '', '', '', NULL, '1506416677', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(384, '1511711639', NULL, 'Đội công tác chuyên trách giải tỏa', '', '', '', 0, '1511711090', '', '', '', NULL, '1506416677', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(385, '1511711660', NULL, 'Ban Quản lý dịch vụ công ích', '', '', '', 0, '1511711090', '', '', '', NULL, '1506416677', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(386, '1511711684', NULL, 'Trung tâm phát triển quỹ đất', '', '', '', 0, '1511711090', '', '', '', NULL, '1506416677', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(387, '1511711715', NULL, 'BQL chợ Phước Thái', '', '', '', 0, '1511711090', '', '', '', NULL, '1506416677', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(388, '1511711738', NULL, 'Trung tâm Bồi dưỡng chính trị', '', '', '', 0, '1511711090', '', '', '', NULL, '1506416677', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(389, '1511711759', NULL, 'Trung tâm Văn hóa - Thể thao', '', '', '', 0, '1511711090', '', '', '', NULL, '1506416677', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(390, '1511711786', NULL, 'Đài Truyền thanh', '', '', '', 0, '1511711090', '', '', '', NULL, '1506416677', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(391, '1511711817', NULL, 'Chợ Xóm Mới', '', '', '', 0, '1511711090', '', '', '', NULL, '1506416677', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(392, '1511711839', NULL, 'Chợ Phương Sơn', '', '', '', 0, '1511711090', '', '', '', NULL, '1506416677', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(393, '1511711882', NULL, 'UBND Phường Lộc Thọ', '', '', '', 0, '1511711090', '', '', '', NULL, '1506416677', 'KVXP', '1', 'XL1', NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(394, '1511711913', NULL, 'UBND Phường Ngọc Hiệp', '', '', '', 0, '1511711090', '', '', '', NULL, '1506416677', 'KVXP', '1', '', NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(395, '1511711978', NULL, 'UBND Xã Phước Đồng', '', '', '', 0, '1511711090', '', '', '', NULL, '1506416677', 'KVXP', '1', '', NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(396, '1511712001', NULL, 'UBND Phường Phước Hải', '', '', '', 0, '1511711090', '', '', '', NULL, '1506416677', 'KVXP', '1', '', NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(397, '1511712025', NULL, 'UBND Phường Phước Hòa', '', '', '', 0, '1511711090', '', '', '', NULL, '1506416677', 'KVXP', '1', '', NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(398, '1511712049', NULL, 'UBND Phường Phước Long', '', '', '', 0, '1511711090', '', '', '', NULL, '1506416677', 'KVXP', '1', '', NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(399, '1511712079', NULL, 'UBND Phường Phước Tân', '', '', '', 0, '1511711090', '', '', '', NULL, '1506416677', 'KVXP', '1', '', NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(400, '1511712113', NULL, 'UBND Phường Phước Tiến', '', '', '', 0, '1511711090', '', '', '', NULL, '1506416677', 'KVXP', '1', '', NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(401, '1511712138', NULL, 'UBND Phường Phương Sài', '', '', '', 0, '1511711090', '', '', '', NULL, '1506416677', 'KVXP', '1', '', NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(402, '1511712162', NULL, 'UBND Phường Phương Sơn', '', '', '', 0, '1511711090', '', '', '', NULL, '1506416677', 'KVXP', '1', '', NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(403, '1511712185', NULL, 'UBND Phường Tân Lập', '', '', '', 0, '1511711090', '', '', '', NULL, '1506416677', 'KVXP', '1', '', NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(404, '1511712211', NULL, 'UBND Phường Vạn Thắng', '', '', '', 0, '1511711090', '', '', '', NULL, '1506416677', 'KVXP', '1', '', NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(405, '1511712239', NULL, 'UBND Phường Vạn Thạnh', '', '', '', 0, '1511711090', '', '', '', NULL, '1506416677', 'KVXP', '1', '', NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(406, '1511712265', NULL, 'UBND Phường Vĩnh Hải', '', '', '', 0, '1511711090', '', '', '', NULL, '1506416677', 'KVXP', '1', '', NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(407, '1511712288', NULL, 'UBND Xã Vĩnh Hiệp ', '', '', '', 0, '1511711090', '', '', '', NULL, '1506416677', 'KVXP', '1', '', NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(408, '1511712314', NULL, 'UBND Phường Vĩnh Hòa', '', '', '', 0, '1511711090', '', '', '', NULL, '1506416677', 'KVXP', '1', '', NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(409, '1511712340', NULL, 'UBND Xã Vĩnh Lương', '', '', '', 0, '1511711090', '', '', '', NULL, '1506416677', 'KVXP', '1', '', NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(410, '1511712369', NULL, 'UBND Xã Vĩnh Ngọc', '', '', '', 0, '1511711090', '', '', '', NULL, '1506416677', 'KVXP', '1', '--Chọn phân loại xã--', NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(411, '1511712392', NULL, 'UBND Phường Vĩnh Nguyên', '', '', '', 0, '1511711090', '', '', '', NULL, '1506416677', 'KVXP', '1', '', NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(412, '1511712415', NULL, 'UBND Phường Vĩnh Phước', '', '', '', 0, '1511711090', '', '', '', NULL, '1506416677', 'KVXP', '1', '', NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(413, '1511712443', NULL, 'UBND Xã Vĩnh Phương', '', '', '', 0, '1511711090', '', '', '', NULL, '1506416677', 'KVXP', '1', '', NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(414, '1511712468', NULL, 'UBND Xã Vĩnh Thái', '', '', '', 0, '1511711090', '', '', '', NULL, '1506416677', 'KVXP', '1', '', NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(415, '1511712494', NULL, 'UBND Xã Vĩnh Thạnh', '', '', '', 0, '1511711090', '', '', '', NULL, '1506416677', 'KVXP', '1', '', NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(416, '1511712517', NULL, 'UBND Phường Vĩnh Thọ', '', '', '', 0, '1511711090', '', '', '', NULL, '1506416677', 'KVXP', '1', '', NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(417, '1511712541', NULL, 'UBND Xã Vĩnh Trung', '', '', '', 0, '1511711090', '', '', '', NULL, '1506416677', 'KVXP', '1', '', NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(418, '1511712567', NULL, 'UBND Phường Vĩnh Trường', '', '', '', 0, '1511711090', '', '', '', NULL, '1506416677', 'KVXP', '1', '', NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(419, '1511712592', NULL, 'UBND Phường Xương Huân', '', '', '', 0, '1511711090', '', '', '', NULL, '1506416677', 'KVXP', '1', '', NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(420, '1511712693', NULL, 'Trường MN Lý Tự Trọng', '', '', '', 0, '1511711288', '', '', '', NULL, '1506416677', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(421, '1511712748', NULL, 'Trường MN 3/2', '', '', '', 0, '1511711288', '', '', '', NULL, '1506416677', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(422, '1511712771', NULL, 'Trường MN Ngô Thời Nhiệm', '', '', '', 0, '1511711288', '', '', '', NULL, '1506416677', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(423, '1511712799', NULL, 'Trường MN Hồng Bàng', '', '', '', 0, '1511711288', '', '', '', NULL, '1506416677', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(424, '1511712842', NULL, 'Trường MN Hồng Chiêm', '', '', '', 0, '1511711288', '', '', '', NULL, '1506416677', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(425, '1511712877', NULL, 'Trường MN Hướng Dương', '', '', '', 0, '1511711288', '', '', '', NULL, '1506416677', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(426, '1511712946', NULL, 'Trường MN Sao Biển', '', '', '', 0, '1511711288', '', '', '', NULL, '1506416677', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(427, '1511712973', NULL, 'Trường MN 8/3', '', '', '', 0, '1511711288', '', '', '', NULL, '1506416677', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(428, '1511713027', NULL, 'Trường MN Hoa Hồng', '', '', '', 0, '1511711288', '', '', '', NULL, '1506416677', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(429, '1511713053', NULL, 'Trường MN 2/4', '', '', '', 0, '1511711288', '', '', '', NULL, '1506416677', 'KVXP', '1', '', NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(430, '1511713086', NULL, 'Trường MN 20/10', '', '', '', 0, '1511711288', '', '', '', NULL, '1506416677', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(431, '1511713113', NULL, 'Trường MN Bình Khê', '', '', '', 0, '1511711288', '', '', '', NULL, '1506416677', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(432, '1511713138', NULL, 'Trường MN Võ Trứ', '', '', '', 0, '1511711288', '', '', '', NULL, '1506416677', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(433, '1511713164', NULL, 'Trường MN Sơn Ca', '', '', '', 0, '1511711288', '', '', '', NULL, '1506416677', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(434, '1511713189', NULL, 'Trường MN Hương Sen', '', '', '', 0, '1511711288', '', '', '', NULL, '1506416677', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(435, '1511713212', NULL, 'Trường MN Lộc Thọ', '', '', '', 0, '1511711288', '', '', '', NULL, '1506416677', 'KVXP', '1', '', NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(436, '1511713238', NULL, 'Trường MN Ngọc Hiệp', '', '', '', 0, '1511711288', '', '', '', NULL, '1506416677', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(437, '1511713263', NULL, 'Trường MN Phước Đồng', '', '', '', 0, '1511711288', '', '', '', NULL, '1506416677', 'KVXP', '1', '', NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(438, '1511713288', NULL, 'Trường MN Phước Hải', '', '', '', 0, '1511711288', '', '', '', NULL, '1506416677', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(439, '1511713309', NULL, 'Trường MN Phước Hòa', '', '', '', 0, '1511711288', '', '', '', NULL, '1506416677', 'KVXP', '1', '', NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(440, '1511713333', NULL, 'Trường MN Phước Long', '', '', '', 0, '1511711288', '', '', '', NULL, '1506416677', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(441, '1511713358', NULL, 'Trường MN Phước Tân', '', '', '', 0, '1511711288', '', '', '', NULL, '1506416677', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(442, '1511713384', NULL, 'Trường MN Phước Tiến', '', '', '', 0, '1511711288', '', '', '', NULL, '1506416677', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(443, '1511713409', NULL, 'Trường MN Phương Sơn', '', '', '', 0, '1511711288', '', '', '', NULL, '1506416677', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(444, '1511713441', NULL, 'Trường MN Tân Lập', '', '', '', 0, '1511711288', '', '', '', NULL, '1506416677', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(445, '1511713465', NULL, 'Trường MN Vạn Thạnh', '', '', '', 0, '1511711288', '', '', '', NULL, '1506416677', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(446, '1511713515', NULL, 'Trường MN Vạn Thắng', '', '', '', 0, '1511711288', '', '', '', NULL, '1506416677', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(447, '1511713548', NULL, 'Trường MN Vĩnh Hải ', '', '', '', 0, '1511711288', '', '', '', NULL, '1506416677', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(448, '1511713581', NULL, 'Trường MN Vĩnh Hiệp ', '', '', '', 0, '1511711288', '', '', '', NULL, '1506416677', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(449, '1511713604', NULL, 'Trường MN Vĩnh Hòa', '', '', '', 0, '1511711288', '', '', '', NULL, '1506416677', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(450, '1511713628', NULL, 'Trường MN Vĩnh Lương ', '', '', '', 0, '1511711288', '', '', '', NULL, '1506416677', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(451, '1511713653', NULL, 'Trường MN Vĩnh Ngọc', '', '', '', 0, '1511711288', '', '', '', NULL, '1506416677', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(452, '1511713684', NULL, 'Trường MN Vĩnh Nguyên 1', '', '', '', 0, '1511711288', '', '', '', NULL, '1506416677', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(453, '1511713711', NULL, 'Trường MN Vĩnh Nguyên 2', '', '', '', 0, '1511711288', '', '', '', NULL, '1506416677', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(454, '1511713739', NULL, 'Trường MN Vĩnh Phước ', '', '', '', 0, '1511711288', '', '', '', NULL, '1506416677', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(455, '1511713767', NULL, 'Trường MN Vĩnh Phương 1 ', '', '', '', 0, '1511711288', '', '', '', NULL, '1506416677', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(456, '1511713790', NULL, 'Trường MN Vĩnh Phương 2', '', '', '', 0, '1511711288', '', '', '', NULL, '1506416677', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(457, '1511713814', NULL, 'Trường MN Vĩnh Thái ', '', '', '', 0, '1511711288', '', '', '', NULL, '1506416677', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(458, '1511713840', NULL, 'Trường MN Vĩnh Thạnh ', '', '', '', 0, '1511711288', '', '', '', NULL, '1506416677', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(459, '1511713863', NULL, 'Trường MN Vĩnh Thọ', '', '', '', 0, '1511711288', '', '', '', NULL, '1506416677', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(460, '1511713889', NULL, 'Trường MN Vĩnh Trung ', '', '', '', 0, '1511711288', '', '', '', NULL, '1506416677', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(461, '1511713912', NULL, 'Trường MN Vĩnh Trường', '', '', '', 0, '1511711288', '', '', '', NULL, '1506416677', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(462, '1511713934', NULL, 'Trường MN Xương Huân', '', '', '', 0, '1511711288', '', '', '', NULL, '1506416677', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(463, '1511713957', NULL, 'Trường MN Phước Thịnh ', '', '', '', 0, '1511711288', '', '', '', NULL, '1506416677', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(464, '1511713982', NULL, 'Trường TH Vĩnh Lương 1', '', '', '', 0, '1511711288', '', '', '', NULL, '1506416677', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(465, '1511714008', NULL, 'Trường TH Vĩnh Lương 2', '', '', '', 0, '1511711288', '', '', '', NULL, '1506416677', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(466, '1511714031', NULL, 'Trường TH Vĩnh Hòa 1', '', '', '', 0, '1511711288', '', '', '', NULL, '1506416677', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(467, '1511714068', NULL, 'Trường TH Vĩnh Hòa 2', '', '', '', 0, '1511711288', '', '', '', NULL, '1506416677', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(468, '1511714098', NULL, 'Trường TH Vĩnh Hải 1', '', '', '', 0, '1511711288', '', '', '', NULL, '1506416677', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(469, '1511714124', NULL, 'Trường TH Vĩnh Hải 2', '', '', '', 0, '1511711288', '', '', '', NULL, '1506416677', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(470, '1511714157', NULL, 'Trường TH Vĩnh Thọ', '', '', '', 0, '1511711288', '', '', '', NULL, '1506416677', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(471, '1511714264', NULL, 'Trường TH Vĩnh Phước 1', '', '', '', 0, '1511711288', '', '', '', NULL, '1506416677', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(472, '1511714300', NULL, 'Trường TH Vĩnh Phước 2', '', '', '', 0, '1511711288', '', '', '', NULL, '1506416677', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(473, '1511714323', NULL, 'Trường TH Vạn Thắng', '', '', '', 0, '1511711288', '', '', '', NULL, '1506416677', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(474, '1511714346', NULL, 'Trường TH Vạn Thạnh ', '', '', '', 0, '1511711288', '', '', '', NULL, '1506416677', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(475, '1511714376', NULL, 'Trường TH Phương Sài', '', '', '', 0, '1511711288', '', '', '', NULL, '1506416677', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(476, '1511714404', NULL, 'Trường TH Phương Sơn', '', '', '', 0, '1511711288', '', '', '', NULL, '1506416677', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(477, '1511714462', NULL, 'Trường TH Xương Huân 1', '', '', '', 0, '1511711288', '', '', '', NULL, '1506416677', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(478, '1511714497', NULL, 'Trường TH Xương Huân 2', '', '', '', 0, '1511711288', '', '', '', NULL, '1506416677', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(479, '1511714539', NULL, 'Trường TH Lộc Thọ', '', '', '', 0, '1511711288', '', '', '', NULL, '1506416677', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(480, '1511714562', NULL, 'Trường TH Phước Tiến', '', '', '', 0, '1511711288', '', '', '', NULL, '1506416677', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(481, '1511714588', NULL, 'Trường TH Tân Lập 1', '', '', '', 0, '1511711288', '', '', '', NULL, '1506416677', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(482, '1511714668', NULL, 'Trường TH Tân Lập 2', '', '', '', 0, '1511711288', '', '', '', NULL, '1506416677', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(483, '1511714698', NULL, 'Trường TH Phước Tân 1', '', '', '', 0, '1511711288', '', '', '', NULL, '1506416677', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(484, '1511714739', NULL, 'Trường TH Phước Tân 2', '', '', '', 0, '1511711288', '', '', '', NULL, '1506416677', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(485, '1511714764', NULL, 'Trường TH Phước Hòa 1', '', '', '', 0, '1511711288', '', '', '', NULL, '1506416677', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(486, '1511714790', NULL, 'Trường TH Phước Hải 1', '', '', '', 0, '1511711288', '', '', '', NULL, '1506416677', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(487, '1511714813', NULL, 'Trường TH Phước Hải 3', '', '', '', 0, '1511711288', '', '', '', NULL, '1506416677', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(488, '1511714843', NULL, 'Trường TH Phước Long 1', '', '', '', 0, '1511711288', '', '', '', NULL, '1506416677', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(489, '1511714873', NULL, 'Trường TH Phước Long 2', '', '', '', 0, '1511711288', '', '', '', NULL, '1506416677', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(490, '1511714895', NULL, 'Trường TH Vĩnh Trường', '', '', '', 0, '1511711288', '', '', '', NULL, '1506416677', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(491, '1511714917', NULL, 'Trường TH Vĩnh Nguyên 1', '', '', '', 0, NULL, '', '', '', NULL, '1506416677', 'KVXP', '1', '', NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(492, '1511714941', NULL, 'Trường TH Vĩnh Nguyên 2', '', '', '', 0, '1511711288', '', '', '', NULL, '1506416677', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(493, '1511714979', NULL, 'Trường TH Ngọc Hiệp', '', '', '', 0, '1511711288', '', '', '', NULL, '1506416677', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(494, '1511715012', NULL, 'Trường TH Vĩnh Hiệp', '', '', '', 0, '1511711288', '', '', '', NULL, '1506416677', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(495, '1511715036', NULL, 'Trường TH Vĩnh Ngọc', '', '', '', 0, '1511711288', '', '', '', NULL, '1506416677', 'KVHCSN', '1', '', NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(496, '1511715077', NULL, 'Trường TH Vĩnh Thái', '', '', '', 0, '1511711288', '', '', '', NULL, '1506416677', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(497, '1511715101', NULL, 'Trường TH Vĩnh Thạnh', '', '', '', 0, '1511711288', '', '', '', NULL, '1506416677', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(498, '1511715127', NULL, 'Trường TH Vĩnh Trung', '', '', '', 0, '1511711288', '', '', '', NULL, '1506416677', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(499, '1511715155', NULL, 'Trường TH Vĩnh Phương 1', '', '', '', 0, '1511711288', '', '', '', NULL, '1506416677', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(500, '1511715207', NULL, 'Trường TH Vĩnh Phương 2', '', '', '', 0, '1511711288', '', '', '', NULL, '1506416677', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(501, '1511715240', NULL, 'Trường TH Phước Thịnh', '', '', '', 0, '1511711288', '', '', '', NULL, '1506416677', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(502, '1511715267', NULL, 'Trường TH Phước Hòa 2', '', '', '', 0, '1511711288', '', '', '', NULL, '1506416677', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(503, '1511715290', NULL, 'Trường TH Phước Đồng', '', '', '', 0, '1511711288', '', '', '', NULL, '1506416677', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(504, '1511715314', NULL, 'Trường TH Vĩnh Nguyên 3', '', '', '', 0, '1511711288', '', '', '', NULL, '1506416677', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(505, '1511715338', NULL, 'Trường THCS Nguyễn Viết Xuân', '', '', '', 0, '1511711288', '', '', '', NULL, '1506416677', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(506, '1511715367', NULL, 'Trường THCS Mai Xuân Thưởng', '', '', '', 0, '1511711288', '', '', '', NULL, '1506416677', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(507, '1511715389', NULL, 'Trường THCS Lý Thái Tổ', '', '', '', 0, '1511711288', '', '', '', NULL, '1506416677', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(508, '1511715409', NULL, 'Trường THCS Lý Thường Kiệt', '', '', '', 0, '1511711288', '', '', '', NULL, '1506416677', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(509, '1511715431', NULL, 'Trường THCS Nguyễn Khuyến', '', '', '', 0, '1511711288', '', '', '', NULL, '1506416677', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(510, '1511715455', NULL, 'Trường THCS Trưng Vương ', '', '', '', 0, '1511711288', '', '', '', NULL, '1506416677', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(511, '1511715481', NULL, 'Trường THCS Thái Nguyên', '', '', '', 0, '1511711288', '', '', '', NULL, '1506416677', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(512, '1511715504', NULL, 'Trường THCS Võ Văn Ký', '', '', '', 0, '1511711288', '', '', '', NULL, '1506416677', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(513, '1511715526', NULL, 'Trường THCS Phan Sào Nam', '', '', '', 0, '1511711288', '', '', '', NULL, '1506416677', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(514, '1511715550', NULL, 'Trường THCS Âu Cơ', '', '', '', 0, '1511711288', '', '', '', NULL, '1506416677', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(515, '1511715575', NULL, 'Trường THCS Trần Nhật Duật', '', '', '', 0, '1511711288', '', '', '', NULL, '1506416677', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(516, '1511715605', NULL, 'Trường THCS Nguyễn Hiền', '', '', '', 0, '1511711288', '', '', '', NULL, '1506416677', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(517, '1511715626', NULL, 'Trường THCS Bùi Thị Xuân', '', '', '', 0, '1511711288', '', '', '', NULL, '1506416677', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(518, '1511715646', NULL, 'Trường THCS Võ Thị Sáu', '', '', '', 0, '1511711288', '', '', '', NULL, '1506416677', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(519, '1511715667', NULL, 'Trường THCS Cao Thắng', '', '', '', 0, '1511711288', '', '', '', NULL, '1506416677', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(520, '1511715689', NULL, 'Trường THCS Lương Thế Vinh', '', '', '', 0, '1511711288', '', '', '', NULL, '1506416677', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(521, '1511715711', NULL, 'Trường THCS Nguyễn Công Trứ', '', '', '', 0, '1511711288', '', '', '', NULL, '1506416677', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(522, '1511715734', NULL, 'Trường THCS Nguyễn Đình Chiểu', '', '', '', 0, '1511711288', '', '', '', NULL, '1506416677', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(523, '1511715755', NULL, 'Trường THCS Lê Thanh Liêm', '', '', '', 0, '1511711288', '', '', '', NULL, '1506416677', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(524, '1511715775', NULL, 'Trường THCS Trần Quốc Toản', '', '', '', 0, '1511711288', '', '', '', NULL, '1506416677', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(525, '1511715796', NULL, 'Trường THCS Lam Sơn', '', '', '', 0, '1511711288', '', '', '', NULL, '1506416677', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(526, '1511715822', NULL, 'Trường THCS Bạch Đằng', '', '', '', 0, '1511711288', '', '', '', NULL, '1506416677', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(527, '1511715849', NULL, 'Trường THCS Trần Hưng Đạo ', '', '', '', 0, '1511711288', '', '', '', NULL, '1506416677', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(528, '1511715871', NULL, 'Trường THCS Lương Định Của', '', '', '', 0, '1511711288', '', '', '', NULL, '1506416677', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(529, '1511715892', NULL, 'Trường THCS Yersin', '', '', '', 0, '1511711288', '', '', '', NULL, '1506416677', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(530, '1511748129', NULL, 'Văn phòng HĐND và UBND', '', '', '', 0, '1511748256', '', '', '', NULL, '1511580827', 'KVHCSN', '1', NULL, 'NGANSACH', NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(531, '1511748204', NULL, 'Phòng Tư Pháp', '', '', '', 0, NULL, '', '', '', NULL, '1511580827', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(532, '1511748256', NULL, 'Phòng Tài chính - Kế hoạch', '', '', '', 0, NULL, '', '', '', NULL, '1511580827', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(533, '1511748283', NULL, 'Phòng Quản lý Đô thị', '', '', '', 0, NULL, '', '', '', NULL, '1511580827', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(534, '1511748314', NULL, 'Phòng Kinh Tế', '', '', '', 0, NULL, '', '', '', NULL, '1511580827', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(535, '1511748355', NULL, 'Phòng Giáo Dục Và Đào Tạo', '', '', '', 0, NULL, '', '', '', NULL, '1511580827', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(536, '1511748383', NULL, 'Phòng Y Tế', '', '', '', 0, NULL, '', '', '', NULL, '1511580827', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(537, '1511748419', NULL, 'Phòng Lao Động Thương Binh Xã Hội', '', '', '', 0, NULL, '', '', '', NULL, '1511580827', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(538, '1511748452', NULL, 'Phòng Văn Hóa Thông Tin', '', '', '', 0, NULL, '', '', '', NULL, '1511580827', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(539, '1511748480', NULL, 'Phòng Tài Nguyên và Môi Trường', '', '', '', 0, NULL, '', '', '', NULL, '1511580827', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(540, '1511748519', NULL, 'Phòng Nội Vụ', '', '', '', 0, NULL, '', '', '', NULL, '1511580827', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(541, '1511748552', NULL, 'Thanh Tra Thành Phố', '', '', '', 0, NULL, '', '', '', NULL, '1511580827', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(542, '1511748604', NULL, 'Phòng Dân Tộc', '', '', '', 0, NULL, '', '', '', NULL, '1511580827', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(543, '1511748635', NULL, 'Văn Phòng Thành Uỷ', '', '', '', 0, NULL, '', '', '', NULL, '1511580827', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(544, '1511748663', NULL, 'Ủy Ban Mặt Trận Tổ Quốc Việt Nam', '', '', '', 0, NULL, '', '', '', NULL, '1511580827', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(545, '1511748708', NULL, 'Đoàn Thanh Niên Cộng Sản Hồ Chí Minh', '', '', '', 0, NULL, '', '', '', NULL, '1511580827', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(546, '1511748743', NULL, 'Hội Liên Hiệp Phụ Nữ', '', '', '', 0, NULL, '', '', '', NULL, '1511580827', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(547, '1511748769', NULL, 'Hội Nông Dân', '', '', '', 0, NULL, '', '', '', NULL, '1511580827', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(548, '1511748792', NULL, 'Hội Cựu Chiến Binh', '', '', '', 0, NULL, '', '', '', NULL, '1511580827', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(549, '1511748838', NULL, 'Hội Chữ Thập Đỏ', '', '', '', 0, NULL, '', '', '', NULL, '1511580827', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(550, '1511748876', NULL, 'Hội Người Mù', '', '', '', 0, NULL, '', '', '', NULL, '1511580827', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(551, '1511748897', NULL, 'Hội Đông Y', '', '', '', 0, NULL, '', '', '', NULL, '1511580827', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(552, '1511748923', NULL, 'Hội Khuyến Học', '', '', '', 0, NULL, '', '', '', NULL, '1511580827', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(553, '1511748959', NULL, 'Hội Nạn Nhân Chất Độc Da Cam', '', '', '', 0, NULL, '', '', '', NULL, '1511580827', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(554, '1511748989', NULL, 'Hội Người Cao Tuổi', '', '', '', 0, NULL, '', '', '', NULL, '1511580827', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(555, '1511749018', NULL, 'Trung Tâm Văn Hóa Thể Thao', '', '', '', 0, NULL, '', '', '', NULL, '1511580827', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(556, '1511749043', NULL, 'Nhà Văn Hóa Thiêu Nhi', '', '', '', 0, NULL, '', '', '', NULL, '1511580827', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(557, '1511749069', NULL, 'Đài Truyền Thanh Truyền Hình', '', '', '', 0, NULL, '', '', '', NULL, '1511580827', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(558, '1511749120', NULL, 'Trạm Khuyến Nông Khuyến Lâm', '', '', '', 0, NULL, '', '', '', NULL, '1511580827', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(559, '1511749146', NULL, 'Đội Thanh Niên Xung Kích', '', '', '', 0, NULL, '', '', '', NULL, '1511580827', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(560, '1511749165', NULL, 'Trung Tâm Bồi Dưỡng Chính Trị', '', '', '', 0, NULL, '', '', '', NULL, '1511580827', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(561, '1511749199', NULL, 'UBND Xã Cam Thịnh Tây', '', '', '', 0, NULL, '', '', '', NULL, '1511580827', 'KVXP', '1', 'XL1', NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(562, '1511749224', NULL, 'UBND Xã Cam Lập', '', '', '', 0, NULL, '', '', '', NULL, '1511580827', 'KVXP', '1', 'XL1', NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(563, '1511749249', NULL, 'UBND Xã Cam Bình', '', '', '', 0, NULL, '', '', '', NULL, '1511580827', 'KVXP', '1', 'XL1', NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(564, '1511749273', NULL, 'UBND Xã Cam Bình Nam', '', '', '', 0, NULL, '', '', '', NULL, '1511580827', 'KVXP', '1', 'XL1', NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(565, '1511749299', NULL, 'UBND Xã Cam Phước Đông', '', '', '', 0, NULL, '', '', '', NULL, '1511580827', 'KVXP', '1', 'XL1', NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(566, '1511749322', NULL, 'UBND Xã Cam Thịnh Đông', '', '', '', 0, NULL, '', '', '', NULL, '1511580827', 'KVXP', '1', 'XL1', NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(567, '1511749349', NULL, 'UBND Phường Ba Ngòi', '', '', '', 0, NULL, '', '', '', NULL, '1511580827', 'KVXP', '1', 'XL1', NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(568, '1511749379', NULL, 'UBND Phường Cam Lộc', '', '', '', 0, NULL, '', '', '', NULL, '1511580827', 'KVXP', '1', 'XL1', NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(569, '1511749410', NULL, 'UBND Phường Cam Lợi', '', '', '', 0, NULL, '', '', '', NULL, '1511580827', 'KVXP', '1', 'XL1', NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(570, '1511749436', NULL, 'UBND Phường Cam Phúc Bắc', '', '', '', 0, NULL, '', '', '', NULL, '1511580827', 'KVXP', '1', 'XL1', NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL);
INSERT INTO `dmdonvi` (`id`, `madv`, `maqhns`, `tendv`, `diachi`, `sodt`, `lanhdao`, `songuoi`, `macqcq`, `diadanh`, `cdlanhdao`, `nguoilapbieu`, `makhoipb`, `madvbc`, `maphanloai`, `capdonvi`, `phanloaixa`, `phanloainguon`, `linhvuchoatdong`, `phanloaitaikhoan`, `phamvitonghop`, `pcct`, `pckct`, `pck`, `pccv`, `pckv`, `pcth`, `pcdd`, `pcdh`, `pcld`, `pcdbqh`, `pcudn`, `pctn`, `pctnn`, `pcdbn`, `pcvk`, `pckn`, `pcdang`, `pccovu`, `pclt`, `pcd`, `pctr`, `pctnvk`, `pcbdhdcu`, `pcthni`, `created_at`, `updated_at`) VALUES
(571, '1511749463', NULL, 'UBND Phường Cam Phúc Nam', '', '', '', 0, NULL, '', '', '', NULL, '1511580827', 'KVXP', '1', 'XL1', NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(572, '1511749488', NULL, 'UBND Phường Cam Phú', '', '', '', 0, NULL, '', '', '', NULL, '1511580827', 'KVXP', '1', 'XL1', NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(573, '1511749513', NULL, 'UBND Phường Cam Nghĩa', '', '', '', 0, NULL, '', '', '', NULL, '1511580827', 'KVXP', '1', 'XL1', NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(574, '1511749538', NULL, 'UBND Phường Cam Thuận', '', '', '', 0, NULL, '', '', '', NULL, '1511580827', 'KVXP', '1', 'XL1', NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(575, '1511749557', NULL, 'UBND Phường Cam Linh', '', '', '', 0, NULL, '', '', '', NULL, '1511580827', 'KVXP', '1', 'XL1', NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(576, '1511749596', NULL, 'Trường Mầm Non Hoa Mai', '', '', '', 0, NULL, '', '', '', NULL, '1511580827', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(577, '1511749623', NULL, 'Trường Mẫu Giáo 2/4', '', '', '', 0, NULL, '', '', '', NULL, '1511580827', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(578, '1511749658', NULL, 'Trường Mẫu Giáo Cam Thịnh Tây', '', '', '', 0, NULL, '', '', '', NULL, '1511580827', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(579, '1511749690', NULL, 'Trường Mẫu Giáo Cam Lập', '', '', '', 0, NULL, '', '', '', NULL, '1511580827', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(580, '1511749714', NULL, 'Trường Mẫu Giáo Cam Bình', '', '', '', 0, NULL, '', '', '', NULL, '1511580827', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(581, '1511749745', NULL, 'Trường Mầm Non Trường Sa', '', '', '', 0, NULL, '', '', '', NULL, '1511580827', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(582, '1511749771', NULL, 'Trường Mẫu Giáo Ba Ngòi', '', '', '', 0, NULL, '', '', '', NULL, '1511580827', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(583, '1511749794', NULL, 'Trường Maauc Giáo Cam Lộc', '', '', '', 0, NULL, '', '', '', NULL, '1511580827', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(584, '1511749819', NULL, 'Trường Mẫu Giáo Cam Thành Nam', '', '', '', 0, NULL, '', '', '', NULL, '1511580827', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(585, '1511749842', NULL, 'Trường Mẫu Giáo Cam Lợi', '', '', '', 0, NULL, '', '', '', NULL, '1511580827', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(586, '1511749881', NULL, 'Trường Mẫu Giáo Cam Phúc Bắc', '', '', '', 0, NULL, '', '', '', NULL, '1511580827', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(587, '1511750448', NULL, 'Trường Mẫu Giáo Cam Phúc nam', '', '', '', 0, NULL, '', '', '', NULL, '1511580827', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(588, '1511750470', NULL, 'Trường Mẫu Giáo Cam Phú', '', '', '', 0, NULL, '', '', '', NULL, '1511580827', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(589, '1511750500', NULL, 'Trường Mẫu Giáo Cam Nghĩa', '', '', '', 0, NULL, '', '', '', NULL, '1511580827', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(590, '1511750525', NULL, 'Trường Mẫu Giáo Cam Phước Đông', '', '', '', 0, NULL, '', '', '', NULL, '1511580827', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(591, '1511750551', NULL, 'Trường Mẫu Giáo Cam Thịnh Đông', '', '', '', 0, NULL, '', '', '', NULL, '1511580827', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(592, '1511750578', NULL, 'Trường Mẫu Giáo Cam Thuận', '', '', '', 0, NULL, '', '', '', NULL, '1511580827', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(593, '1511750602', NULL, 'Trường Mẫu Giáo Cam Linh', '', '', '', 0, NULL, '', '', '', NULL, '1511580827', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(594, '1511750627', NULL, 'Trường Mầm Non Căn Cứ', '', '', '', 0, NULL, '', '', '', NULL, '1511580827', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(595, '1511750659', NULL, 'Trường TH Cam Thành Nam', '', '', '', 0, NULL, '', '', '', NULL, '1511580827', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(596, '1511750686', NULL, 'Trường Tiểu Học Cam Nghĩa 1', '', '', '', 0, NULL, '', '', '', NULL, '1511580827', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(597, '1511750708', NULL, 'Trường Tiểu Học Cam Nghĩa 2', '', '', '', 0, NULL, '', '', '', NULL, '1511580827', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(598, '1511750734', NULL, 'Trường Tiểu Học Cam Lộc 2', '', '', '', 0, NULL, '', '', '', NULL, '1511580827', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(599, '1511750756', NULL, 'Trường Tiểu Học Cam Bình', '', '', '', 0, NULL, '', '', '', NULL, '1511580827', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(600, '1511750783', NULL, 'Trường TH Cam Thịnh Đông', '', '', '', 0, NULL, '', '', '', NULL, '1511580827', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(601, '1511750814', NULL, 'Trường Tiểu Học Cam Phúc Bắc 2', '', '', '', 0, NULL, '', '', '', NULL, '1511580827', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(602, '1511750835', NULL, 'Trường Tiểu Học Ba Ngòi', '', '', '', 0, NULL, '', '', '', NULL, '1511580827', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(603, '1511750858', NULL, 'Trường Tiểu Học Cam Linh 2', '', '', '', 0, NULL, '', '', '', NULL, '1511580827', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(604, '1511750892', NULL, 'Trường Tiểu Học Cam Phước Đông 1', '', '', '', 0, NULL, '', '', '', NULL, '1511580827', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(605, '1511750914', NULL, 'Trường Tiểu Học Cam Phước Đông 2', '', '', '', 0, NULL, '', '', '', NULL, '1511580827', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(606, '1511750940', NULL, 'Trường Tiểu Học Cam Phúc Nam', '', '', '', 0, NULL, '', '', '', NULL, '1511580827', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(607, '1511750973', NULL, 'Trường Tiểu Học Cam Phúc Bắc 1', '', '', '', 0, NULL, '', '', '', NULL, '1511580827', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(608, '1511751002', NULL, 'Trường Tiểu Học Cam Thịnh 1', '', '', '', 0, NULL, '', '', '', NULL, '1511580827', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(609, '1511751026', NULL, 'Trường Tiểu Học Cam Linh 1', '', '', '', 0, NULL, '', '', '', NULL, '1511580827', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(610, '1511751056', NULL, 'Trường Tiểu Học Cam Lợi', '', '', '', 0, NULL, '', '', '', NULL, '1511580827', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(611, '1511751085', NULL, 'Trường Tiểu Học Cam Lộc 1', '', '', '', 0, NULL, '', '', '', NULL, '1511580827', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(612, '1511751107', NULL, 'Trường Tiểu Học Cam Phú', '', '', '', 0, NULL, '', '', '', NULL, '1511580827', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(613, '1511751130', NULL, 'Trường Tiểu Học Cam Thuận', '', '', '', 0, NULL, '', '', '', NULL, '1511580827', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(614, '1511751165', NULL, 'Trường Tiểu Học Cam Thịnh Tây 1', '', '', '', 0, NULL, '', '', '', NULL, '1511580827', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(616, '1511751227', NULL, 'Trường Tiểu Học Cam Thịnh Tây 2', '', '', '', 0, NULL, '', '', '', NULL, '1511580827', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(617, '1511751250', NULL, 'Trường Tiểu Học Căn Cứ', '', '', '', 0, NULL, '', '', '', NULL, '1511580827', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(618, '1511751277', NULL, 'Trường THCS Nguyễn Thị Minh Khai', '', '', '', 0, NULL, '', '', '', NULL, '1511580827', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(619, '1511751308', NULL, 'Trường THCS Nguyễn Du', '', '', '', 0, NULL, '', '', '', NULL, '1511580827', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(620, '1511751351', NULL, 'Trường THCS Nguyễn Khuyến', '', '', '', 0, NULL, '', '', '', NULL, '1511580827', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(621, '1511751372', NULL, 'Trường THCS Lê Hồng Phong', '', '', '', 0, NULL, '', '', '', NULL, '1511580827', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(625, '1511751528', NULL, 'Trường THCS Trần Phú', '', '', '', 0, NULL, '', '', '', NULL, '1511580827', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(626, '1511751559', NULL, 'Trường THCS Nguyễn Trung Trực', '', '', '', 0, NULL, '', '', '', NULL, '1511580827', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(628, '1511751637', NULL, 'Trường THCS Cam Thịnh Tây', '', '', '', 0, NULL, '', '', '', NULL, '1511580827', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(629, '1511751661', NULL, 'Trường TH-THCS Bình Hưng', '', '', '', 0, NULL, '', '', '', NULL, '1511580827', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(630, '1511751683', NULL, 'Trường TH-THCS Cam Lập', '', '', '', 0, NULL, '', '', '', NULL, '1511580827', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(631, '1511751714', NULL, 'Trường Phổ Thông Dân Tộc Nội Trú', '', '', '', 0, NULL, '', '', '', NULL, '1511580827', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(632, '1511751832', NULL, 'Văn phòng HĐND và UBND', '', '', '', 0, NULL, '', '', '', NULL, '1511580845', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(633, '1511751864', NULL, 'Phòng Tư Pháp', '', '', '', 0, NULL, '', '', '', NULL, '1511580845', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(634, '1511751883', NULL, 'Phòng Tài chính - Kế hoạch', '', '', '', 0, '1511751883', '', '', '', NULL, '1511580845', 'KVHCSN', '1', NULL, 'NGANSACH', NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(635, '1511751915', NULL, 'Phòng Quản lý đô thị', '', '', '', 0, NULL, '', '', '', NULL, '1511580845', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(636, '1511751960', NULL, 'Phòng Kinh Tế', '', '', '', 0, NULL, '', '', '', NULL, '1511580845', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(637, '1511751987', NULL, 'Phòng Giáo Dục & Đào Tạo', '', '', '', 0, NULL, '', '', '', NULL, '1511580845', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(638, '1511752013', NULL, 'Phòng Y Tế', '', '', '', 0, NULL, '', '', '', NULL, '1511580845', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(639, '1511752056', NULL, 'Phòng Lao Động Thương Binh Và Xã Hội', '', '', '', 0, NULL, '', '', '', NULL, '1511580845', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(640, '1511752388', NULL, 'Phòng Văn Hóa Thông Tin', '', '', '', 0, NULL, '', '', '', NULL, '1511580845', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(641, '1511752418', NULL, 'Phòng Tài Nguyên Và Môi Trường', '', '', '', 0, NULL, '', '', '', NULL, '1511580845', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(642, '1511752436', NULL, 'Phòng Nội Vụ', '', '', '', 0, NULL, '', '', '', NULL, '1511580845', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(643, '1511752458', NULL, 'Thanh Tra Huyện', '', '', '', 0, NULL, '', '', '', NULL, '1511580845', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(644, '1511752483', NULL, 'Văn Phòng Huyện Ủy', '', '', '', 0, NULL, '', '', '', NULL, '1511580845', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(645, '1511752524', NULL, 'Ủy Ban Mặt Trận Tổ Quốc Việt Nam', '', '', '', 0, NULL, '', '', '', NULL, '1511580845', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(646, '1511752546', NULL, 'Hội Nông Dân', '', '', '', 0, NULL, '', '', '', NULL, '1511580845', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(647, '1511752595', NULL, 'Hội Phụ Nữ', '', '', '', 0, NULL, '', '', '', NULL, '1511580845', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(648, '1511752620', NULL, 'Hội Cựu Chiến Binh', '', '', '', 0, NULL, '', '', '', NULL, '1511580845', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(649, '1511752639', NULL, 'Đoàn Thanh Niên Cộng Sản Hồ Chí Minh', '', '', '', 0, NULL, '', '', '', NULL, '1511580845', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(650, '1511752673', NULL, 'Hội Người Cao Tuổi', '', '', '', 0, NULL, '', '', '', NULL, '1511580845', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(651, '1511752693', NULL, 'Trung Tâm Văn Hóa Thể Thao', '', '', '', 0, NULL, '', '', '', NULL, '1511580845', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(652, '1511752713', NULL, 'Đài Truyền Thanh Truyền Hình', '', '', '', 0, NULL, '', '', '', NULL, '1511580845', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(653, '1511752778', NULL, 'Ban Quản Lý Công Trình Công Cộng', '', '', '', 0, NULL, '', '', '', NULL, '1511580845', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(654, '1511752822', NULL, 'Ban Quản Lý Dự Án Các Công Trình Xây Dựng', '', '', '', 0, NULL, '', '', '', NULL, '1511580845', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(655, '1511752857', NULL, 'Trung Tâm Phát Triển Quỹ Đất', '', '', '', 0, NULL, '', '', '', NULL, '1511580845', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(656, '1511752883', NULL, 'Hội Nạn nhân CĐDC Dioxin', '', '', '', 0, NULL, '', '', '', NULL, '1511580845', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(657, '1511752906', NULL, 'Hội Người Mù', '', '', '', 0, NULL, '', '', '', NULL, '1511580845', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(658, '1511752930', NULL, 'Hội Chữ Thập Đỏ', '', '', '', 0, NULL, '', '', '', NULL, '1511580845', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(659, '1511752954', NULL, 'Hội Đông Y', '', '', '', 0, NULL, '', '', '', NULL, '1511580845', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(660, '1511752971', NULL, 'Trung Tâm Bồi Dưỡng Chính Trị', '', '', '', 0, NULL, '', '', '', NULL, '1511580845', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(661, '1511752986', NULL, 'Nhà Văn Hóa Thiêu Nhi', '', '', '', 0, NULL, '', '', '', NULL, '1511580845', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(662, '1511753020', NULL, 'UBND Xã Diên An', '', '', '', 0, NULL, '', '', '', NULL, '1511580845', 'KVXP', '1', 'XL1', NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(663, '1511753042', NULL, 'UBND Xã Diên Toàn', '', '', '', 0, NULL, '', '', '', NULL, '1511580845', 'KVXP', '1', 'XL1', NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(664, '1511753061', NULL, 'UBND Xã Diên Thạnh', '', '', '', 0, NULL, '', '', '', NULL, '1511580845', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(665, '1511753086', NULL, 'UBND Xã Diên Lạc', '', '', '', 0, '1511751883', '', '', '', NULL, '1511580845', 'KVXP', '1', 'XL1', NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(666, '1511753108', NULL, 'UBND Xã Diên Hoà', '', '', '', 0, NULL, '', '', '', NULL, '1511580845', 'KVXP', '1', 'XL1', NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(667, '1511753135', NULL, 'UBND Xã Diên Bình', '', '', '', 0, NULL, '', '', '', NULL, '1511580845', 'KVXP', '1', 'XL1', NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(668, '1511753226', NULL, 'UBND Xã Diên Phước', '', '', '', 0, NULL, '', '', '', NULL, '1511580845', 'KVXP', '1', 'XL1', NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(669, '1511753248', NULL, 'UBND Xã Diên Lộc', '', '', '', 0, NULL, '', '', '', NULL, '1511580845', 'KVXP', '1', 'XL1', NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(670, '1511753268', NULL, 'UBND Xã Diên Thọ', '', '', '', 0, NULL, '', '', '', NULL, '1511580845', 'KVXP', '1', 'XL1', NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(671, '1511753288', NULL, 'UBND Xã Diên Phú', '', '', '', 0, NULL, '', '', '', NULL, '1511580845', 'KVXP', '1', 'XL1', NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(672, '1511753315', NULL, 'UBND Xã Diên Điền', '', '', '', 0, NULL, '', '', '', NULL, '1511580845', 'KVXP', '1', 'XL1', NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(673, '1511753338', NULL, 'UBND Xã Diên Sơn', '', '', '', 0, NULL, '', '', '', NULL, '1511580845', 'KVXP', '1', 'XL1', NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(674, '1511753359', NULL, 'UBNd Xã Diên Lâm', '', '', '', 0, NULL, '', '', '', NULL, '1511580845', 'KVXP', '1', 'XL1', NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(675, '1511753383', NULL, 'UBND Xã Diên Tân', '', '', '', 0, NULL, '', '', '', NULL, '1511580845', 'KVXP', '1', 'XL1', NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(676, '1511753409', NULL, 'UBND Xã Diên Đồng', '', '', '', 0, NULL, '', '', '', NULL, '1511580845', 'KVXP', '1', 'XL1', NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(677, '1511753429', NULL, 'UBND Xã Diên Xuân', '', '', '', 0, NULL, '', '', '', NULL, '1511580845', 'KVXP', '1', 'XL1', NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(678, '1511753449', NULL, 'UBND Xã Suối Hiệp', '', '', '', 0, NULL, '', '', '', NULL, '1511580845', 'KVXP', '1', 'XL1', NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(679, '1511753469', NULL, 'UBND Xã Suối Tiên', '', '', '', 0, NULL, '', '', '', NULL, '1511580845', 'KVXP', '1', 'XL1', NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(680, '1511753503', NULL, 'UBND Thị Trấn Diên Khánh', '', '', '', 0, NULL, '', '', '', NULL, '1511580845', 'KVXP', '1', 'XL1', NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(681, '1511754065', NULL, 'Trường THCS Chu Văn An', '', '', '', 0, NULL, '', '', '', NULL, '1511580827', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(682, '1511754160', NULL, 'Trường THCS Nguyễn Văn Trỗi', '', '', '', 0, NULL, '', '', '', NULL, '1511580827', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(683, '1511754209', NULL, 'Trường THCS Phan Chu Chinh', '', '', '', 0, NULL, '', '', '', NULL, '1511580827', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(684, '1511754283', NULL, 'Trường THCS Nguyễn Trọng Kỷ', '', '', '', 0, NULL, '', '', '', NULL, '1511580827', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(685, '1511754405', NULL, 'Trường Mầm Non Hoa Phượng', '', '', '', 0, NULL, '', '', '', NULL, '1511580845', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(686, '1511754430', NULL, 'Trường Mầm Non Diên Đồng ', '', '', '', 0, NULL, '', '', '', NULL, '1511580845', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(687, '1511754460', NULL, 'Trường Mầm Non Diên Tân', '', '', '', 0, NULL, '', '', '', NULL, '1511580845', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(688, '1511754599', NULL, 'Trường Mầm Non Suối Tiên ', '', '', '', 0, NULL, '', '', '', NULL, '1511580845', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(689, '1511754623', NULL, 'Trường Mầm Non Diên Thọ', '', '', '', 0, NULL, '', '', '', NULL, '1511580845', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(690, '1511754652', NULL, 'Trường Mầm Non Diên Phước', '', '', '', 0, NULL, '', '', '', NULL, '1511580845', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(691, '1511754695', NULL, 'Trường Mầm Non Diên An', '', '', '', 0, NULL, '', '', '', NULL, '1511580845', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(692, '1511754730', NULL, 'Trường Mầm Non Diên Điền', '', '', '', 0, NULL, '', '', '', NULL, '1511580845', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(693, '1511754755', NULL, 'Trường Mầm Non Diên Phú', '', '', '', 0, NULL, '', '', '', NULL, '1511580845', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(694, '1511754776', NULL, 'Trường Mầm Non Diên Xuân', '', '', '', 0, NULL, '', '', '', NULL, '1511580845', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(695, '1511754817', NULL, 'Trường Mầm Non Diên Lâm', '', '', '', 0, NULL, '', '', '', NULL, '1511580845', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(696, '1511754839', NULL, 'Trường Mầm Non Diên Lộc', '', '', '', 0, NULL, '', '', '', NULL, '1511580845', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(697, '1511754871', NULL, 'Trường Mầm Non Diên Hòa', '', '', '', 0, NULL, '', '', '', NULL, '1511580845', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(698, '1511754894', NULL, 'Trường Mầm Non Diên Bình', '', '', '', 0, NULL, '', '', '', NULL, '1511580845', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(699, '1511754920', NULL, 'Trường Mầm Non Diên Lạc', '', '', '', 0, NULL, '', '', '', NULL, '1511580845', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(700, '1511754951', NULL, 'Trường Mầm Non Diên Thạnh', '', '', '', 0, NULL, '', '', '', NULL, '1511580845', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(701, '1511754978', NULL, 'Trường Mầm Non Diên Toàn', '', '', '', 0, NULL, '', '', '', NULL, '1511580845', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(702, '1511755047', NULL, 'Trường Mầm Non Thị Trấn Diên Khánh', '', '', '', 0, NULL, '', '', '', NULL, '1511580845', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(703, '1511755074', NULL, 'Trường Mầm Non Diên Sơn', '', '', '', 0, NULL, '', '', '', NULL, '1511580845', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(704, '1511755100', NULL, 'Trường Mầm Non Suối Hiệp', '', '', '', 0, NULL, '', '', '', NULL, '1511580845', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(705, '1511755125', NULL, 'Trường Tiểu học Diên Thạnh', '', '', '', 0, NULL, '', '', '', NULL, '1511580845', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(706, '1511755150', NULL, 'Trường Tiểu học Diên Hòa', '', '', '', 0, NULL, '', '', '', NULL, '1511580845', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(707, '1511755173', NULL, 'Trường Tiểu học Diên Phước ', '', '', '', 0, NULL, '', '', '', NULL, '1511580845', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(708, '1511755207', NULL, 'Trường Tiểu học Diên Lộc', '', '', '', 0, NULL, '', '', '', NULL, '1511580845', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(709, '1511755229', NULL, 'Trường Tiểu học Diên Phú 1', '', '', '', 0, NULL, '', '', '', NULL, '1511580845', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(710, '1511755264', NULL, 'Trường Tiểu học Diên Phú 2', '', '', '', 0, NULL, '', '', '', NULL, '1511580845', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(711, '1511755488', NULL, 'Trường Tiểu học Suối Hiệp 1', '', '', '', 0, NULL, '', '', '', NULL, '1511580845', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(712, '1511755516', NULL, 'Trường Tiểu học Thị Trấn 1', '', '', '', 0, NULL, '', '', '', NULL, '1511580845', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(713, '1511755539', NULL, 'Trường Tiểu học Diên Lạc', '', '', '', 0, NULL, '', '', '', NULL, '1511580845', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(714, '1511755563', NULL, 'Trường Tiểu học Diên Thọ', '', '', '', 0, NULL, '', '', '', NULL, '1511580845', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(715, '1511755583', NULL, 'Trường Tiểu học Diên Điền', '', '', '', 0, NULL, '', '', '', NULL, '1511580845', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(716, '1511755618', NULL, 'Trường Tiểu học TH- THCS Diễn Tân', '', '', '', 0, NULL, '', '', '', NULL, '1511580845', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(717, '1511755655', NULL, 'Trường Tiểu học Thị Trấn Diên Khánh 2', '', '', '', 0, NULL, '', '', '', NULL, '1511580845', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(718, '1511755679', NULL, 'Trường Tiểu học Diên An 1', '', '', '', 0, NULL, '', '', '', NULL, '1511580845', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(719, '1511755700', NULL, 'Trường Tiểu học Diên An 2', '', '', '', 0, NULL, '', '', '', NULL, '1511580845', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(720, '1511755731', NULL, 'Trường Tiểu học Diên Toàn', '', '', '', 0, NULL, '', '', '', NULL, '1511580845', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(721, '1511755757', NULL, 'Trường Tiểu học Diên Bình', '', '', '', 0, NULL, '', '', '', NULL, '1511580845', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(722, '1511755781', NULL, 'Trường Tiểu học Diên Sơn 1', '', '', '', 0, NULL, '', '', '', NULL, '1511580845', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(723, '1511755807', NULL, 'Trương Tiểu học Diên Sơn 2', '', '', '', 0, NULL, '', '', '', NULL, '1511580845', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(724, '1511755829', NULL, 'Trường Tiểu học Diên Lâm ', '', '', '', 0, NULL, '', '', '', NULL, '1511580845', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(725, '1511755855', NULL, 'Trường Tiểu học Diên Đồng', '', '', '', 0, NULL, '', '', '', NULL, '1511580845', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(726, '1511755879', NULL, 'Trường Tiểu học Suối  Hiệp 2', '', '', '', 0, NULL, '', '', '', NULL, '1511580845', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(727, '1511755901', NULL, 'Trường Tiểu học Suối Tiên', '', '', '', 0, NULL, '', '', '', NULL, '1511580845', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(728, '1511755924', NULL, 'Trường Tiểu học Diên Xuân', '', '', '', 0, NULL, '', '', '', NULL, '1511580845', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(729, '1511755948', NULL, 'Trường Tiểu học Diên Xuân 1', '', '', '', 0, NULL, '', '', '', NULL, '1511580845', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(730, '1511755986', NULL, 'Trường Trung học cơ sở Phan Chu  Trinh ', '', '', '', 0, NULL, '', '', '', NULL, '1511580845', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(731, '1511756008', NULL, 'Trường Trung học cơ sở Trịnh Phong ', '', '', '', 0, NULL, '', '', '', NULL, '1511580845', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(732, '1511756033', NULL, 'Trường Trung học cơ sở Nguyễn Du ', '', '', '', 0, NULL, '', '', '', NULL, '1511580845', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(733, '1511756061', NULL, 'Trường Trung học cơ sở Trần  Quang Khải ', '', '', '', 0, NULL, '', '', '', NULL, '1511580845', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(734, '1511756082', NULL, 'Trường Trung học cơ sở Ngô Quyền ', '', '', '', 0, NULL, '', '', '', NULL, '1511580845', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(735, '1511756103', NULL, 'Trường Trung học cơ sở Nguyễn Hụê ', '', '', '', 0, NULL, '', '', '', NULL, '1511580845', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(736, '1511756125', NULL, 'Trường Trung học cơ sở Mạc Đỉnh Chi ', '', '', '', 0, NULL, '', '', '', NULL, '1511580845', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(737, '1511756150', NULL, 'Trường Trung học cơ sở Trần nhân Tông ', '', '', '', 0, NULL, '', '', '', NULL, '1511580845', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(738, '1511756176', NULL, 'Trường Trung học cơ sở Đinh Bộ Lĩnh ', '', '', '', 0, NULL, '', '', '', NULL, '1511580845', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(739, '1511756200', NULL, 'Trường Trung học cơ sở Trần Đại Nghĩa ', '', '', '', 0, NULL, '', '', '', NULL, '1511580845', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(740, '1511756370', NULL, 'Văn phòng HĐND và UBND', '', '', '', 0, NULL, '', '', '', NULL, '1511580899', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(741, '1511756392', NULL, 'Phòng Nội Vụ', '', '', '', 0, NULL, '', '', '', NULL, '1511580899', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(742, '1511756414', NULL, 'Phòng Tài chính - Kế hoạch', '', '', '', 0, NULL, '', '', '', NULL, '1511580899', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(743, '1511756433', NULL, 'Phòng Kinh Tế', '', '', '', 0, NULL, '', '', '', NULL, '1511580899', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(744, '1511756460', NULL, 'Phòng Quản Lý Đô Thị', '', '', '', 0, NULL, '', '', '', NULL, '1511580899', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(745, '1511756486', NULL, 'Phòng Tư Pháp', '', '', '', 0, NULL, '', '', '', NULL, '1511580899', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(746, '1511756517', NULL, 'Thanh Tra Huyện', '', '', '', 0, NULL, '', '', '', NULL, '1511580899', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(747, '1511756545', NULL, 'Phòng Tài Nguyên - Môi Trường', '', '', '', 0, NULL, '', '', '', NULL, '1511580899', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(748, '1511756578', NULL, 'Phòng Lao động Thương Binh Xã Hội', '', '', '', 0, NULL, '', '', '', NULL, '1511580899', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(749, '1511756606', NULL, 'Phòng Giáo Dục Và Đào Tạo', '', '', '', 0, NULL, '', '', '', NULL, '1511580899', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(750, '1511756633', NULL, 'Phòng Văn Hóa Thông Tin', '', '', '', 0, NULL, '', '', '', NULL, '1511580899', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(751, '1511756655', NULL, 'Phòng Y Tế', '', '', '', 0, NULL, '', '', '', NULL, '1511580899', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(752, '1511756680', NULL, 'Phòng Dân Tộc', '', '', '', 0, NULL, '', '', '', NULL, '1511580899', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(753, '1511756766', NULL, 'Văn Phòng Huyện Ủy', '', '', '', 0, NULL, '', '', '', NULL, '1511580899', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(754, '1511756814', NULL, 'Ủy Ban Mặt Trận Tổ Quốc Việt Nam', '', '', '', 0, NULL, '', '', '', NULL, '1511580899', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(755, '1511756839', NULL, 'Đoàn Thanh Niên Cộng Sản Hồ Chí Minh', '', '', '', 0, NULL, '', '', '', NULL, '1511580899', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(756, '1511756863', NULL, 'Hội Phụ Nữ', '', '', '', 0, NULL, '', '', '', NULL, '1511580899', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(757, '1511756884', NULL, 'Hội Nông Dân', '', '', '', 0, NULL, '', '', '', NULL, '1511580899', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(758, '1511756905', NULL, 'Hội Cựu Chiến Binh', '', '', '', 0, NULL, '', '', '', NULL, '1511580899', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(759, '1511756927', NULL, 'Hội Đông Y', '', '', '', 0, NULL, '', '', '', NULL, '1511580899', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(760, '1511756951', NULL, 'Hội chữ Thập Đỏ', '', '', '', 0, NULL, '', '', '', NULL, '1511580899', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(761, '1511756977', NULL, 'Hội Người Cao Tuổi', '', '', '', 0, NULL, '', '', '', NULL, '1511580899', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(762, '1511757001', NULL, 'Hội Nạn nhân chất độc da cam', '', '', '', 0, NULL, '', '', '', NULL, '1511580899', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(763, '1511757021', NULL, 'Hội Người Mù', '', '', '', 0, NULL, '', '', '', NULL, '1511580899', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(764, '1511757043', NULL, 'Hội Khuyến Học', '', '', '', 0, NULL, '', '', '', NULL, '1511580899', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(765, '1511757060', NULL, 'Đài Truyền Thanh Truyền Hình', '', '', '', 0, NULL, '', '', '', NULL, '1511580899', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(766, '1511757077', NULL, 'Trung Tâm Văn Hóa Thể Thao', '', '', '', 0, NULL, '', '', '', NULL, '1511580899', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(767, '1511757097', NULL, 'Nhà Văn Hóa Thiêu Nhi', '', '', '', 0, NULL, '', '', '', NULL, '1511580899', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(768, '1511757113', NULL, 'Trung Tâm Bồi Dưỡng Chính Trị', '', '', '', 0, NULL, '', '', '', NULL, '1511580899', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(769, '1511757139', NULL, 'Tổ quản lý trật tự đô thị', '', '', '', 0, NULL, '', '', '', NULL, '1511580899', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(770, '1511757170', NULL, 'Trạm Khuyến Nông Khuyến Lâm', '', '', '', 0, NULL, '', '', '', NULL, '1511580899', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(771, '1511757216', NULL, 'Trung Tâm Phát Triển Quỹ Đất', '', '', '', 0, NULL, '', '', '', NULL, '1511580899', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(772, '1511757264', NULL, 'UBND phường Ninh Diêm', '', '', '', 0, NULL, '', '', '', NULL, '1511580899', 'KVXP', '1', 'XL1', NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(773, '1511757284', NULL, 'UBND phường Ninh Đa', '', '', '', 0, NULL, '', '', '', NULL, '1511580899', 'KVXP', '1', 'XL1', NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(774, '1511757304', NULL, 'UBND phường Ninh Giang', '', '', '', 0, NULL, '', '', '', NULL, '1511580899', 'KVXP', '1', 'XL1', NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(775, '1511757323', NULL, 'UBND phường Ninh Hà', '', '', '', 0, NULL, '', '', '', NULL, '1511580899', 'KVXP', '1', 'XL1', NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(776, '1511757342', NULL, 'UBND phường Ninh Hải', '', '', '', 0, NULL, '', '', '', NULL, '1511580899', 'KVXP', '1', 'Xã loại 1', NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(777, '1511757362', NULL, 'UBND phường Ninh Hiệp', '', '', '', 0, NULL, '', '', '', NULL, '1511580899', 'KVXP', '1', 'XL1', NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(778, '1511757409', NULL, 'UBND phường Ninh Thuỷ', '', '', '', 0, NULL, '', '', '', NULL, '1511580899', 'KVXP', '1', 'XL1', NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(779, '1511757433', NULL, 'UBND xã Ninh An', '', '', '', 0, NULL, '', '', '', NULL, '1511580899', 'KVXP', '1', 'XL1', NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(780, '1511757472', NULL, 'UBND xã Ninh Bình', '', '', '', 0, NULL, '', '', '', NULL, '1511580899', 'KVXP', '1', 'XL1', NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(781, '1511757492', NULL, 'UBND xã Ninh Đông', '', '', '', 0, NULL, '', '', '', NULL, '1511580899', 'KVXP', '1', 'XL1', NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(782, '1511757512', NULL, 'UBND xã Ninh Hưng', '', '', '', 0, NULL, '', '', '', NULL, '1511580899', 'KVXP', '1', 'XL1', NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(783, '1511757529', NULL, 'UBND xã Ninh Ích', '', '', '', 0, NULL, '', '', '', NULL, '1511580899', 'KVXP', '1', 'XL1', NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(784, '1511766477', NULL, 'UBND xã Ninh Lộc', '', '', '', 0, NULL, '', '', '', NULL, '1511580899', 'KVXP', '1', 'XL1', NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL);
INSERT INTO `dmdonvi` (`id`, `madv`, `maqhns`, `tendv`, `diachi`, `sodt`, `lanhdao`, `songuoi`, `macqcq`, `diadanh`, `cdlanhdao`, `nguoilapbieu`, `makhoipb`, `madvbc`, `maphanloai`, `capdonvi`, `phanloaixa`, `phanloainguon`, `linhvuchoatdong`, `phanloaitaikhoan`, `phamvitonghop`, `pcct`, `pckct`, `pck`, `pccv`, `pckv`, `pcth`, `pcdd`, `pcdh`, `pcld`, `pcdbqh`, `pcudn`, `pctn`, `pctnn`, `pcdbn`, `pcvk`, `pckn`, `pcdang`, `pccovu`, `pclt`, `pcd`, `pctr`, `pctnvk`, `pcbdhdcu`, `pcthni`, `created_at`, `updated_at`) VALUES
(785, '1511766499', NULL, 'UBND xã Ninh Phú', '', '', '', 0, NULL, '', '', '', NULL, '1511580899', 'KVXP', '1', 'XL1', NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(786, '1511766528', NULL, 'UBND xã Ninh Phụng', '', '', '', 0, NULL, '', '', '', NULL, '1511580899', 'KVXP', '1', 'XL1', NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(787, '1511766550', NULL, 'UBND xã Ninh Phước', '', '', '', 0, NULL, '', '', '', NULL, '1511580899', 'KVXP', '1', 'XL1', NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(788, '1511766575', NULL, 'UBND xã Ninh Sim', '', '', '', 0, NULL, '', '', '', NULL, '1511580899', 'KVXP', '1', 'XL1', NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(789, '1511766604', NULL, 'UBND xã Ninh Sơn', '', '', '', 0, NULL, '', '', '', NULL, '1511580899', 'KVXP', '1', 'XL1', NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(790, '1511766625', NULL, 'UBND xã Ninh Quang', '', '', '', 0, NULL, '', '', '', NULL, '1511580899', 'KVXP', '1', 'XL1', NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(791, '1511766646', NULL, 'UBND xã Ninh Tân', '', '', '', 0, NULL, '', '', '', NULL, '1511580899', 'KVXP', '1', 'XL1', NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(792, '1511766664', NULL, 'UBND xã Ninh Tây', '', '', '', 0, NULL, '', '', '', NULL, '1511580899', 'KVXP', '1', 'XL1', NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(793, '1511766686', NULL, 'UBND xã Ninh Thân', '', '', '', 0, NULL, '', '', '', NULL, '1511580899', 'KVXP', '1', 'XL1', NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(794, '1511766705', NULL, 'UBND xã Ninh Thọ', '', '', '', 0, NULL, '', '', '', NULL, '1511580899', 'KVXP', '1', 'XL1', NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(795, '1511766729', NULL, 'UBND xã Ninh Thượng', '', '', '', 0, NULL, '', '', '', NULL, '1511580899', 'KVXP', '1', 'XL1', NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(796, '1511766751', NULL, 'UBND xã Ninh Trung', '', '', '', 0, NULL, '', '', '', NULL, '1511580899', 'KVXP', '1', 'XL1', NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(797, '1511766771', NULL, 'UBND xã Ninh Vân', '', '', '', 0, NULL, '', '', '', NULL, '1511580899', 'KVXP', '1', 'XL1', NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(798, '1511766791', NULL, 'UBND xã Ninh Xuân', '', '', '', 0, NULL, '', '', '', NULL, '1511580899', 'KVXP', '1', 'XL1', NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(799, '1511766838', NULL, 'Trường Tiểu học Ninh  An', '', '', '', 0, NULL, '', '', '', NULL, '1511580899', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(800, '1511766864', NULL, 'Trường Tiểu học Ninh  Bình', '', '', '', 0, NULL, '', '', '', NULL, '1511580899', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(801, '1511766892', NULL, 'Trường Tiểu học Ninh  Diêm', '', '', '', 0, NULL, '', '', '', NULL, '1511580899', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(802, '1511766918', NULL, 'Trường Tiểu học  số 1 Ninh Đa', '', '', '', 0, NULL, '', '', '', NULL, '1511580899', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(803, '1511766948', NULL, 'Trường Tiểu học  số 2 Ninh Đa', '', '', '', 0, NULL, '', '', '', NULL, '1511580899', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(804, '1511766975', NULL, 'Trường Tiểu học Ninh  Đông', '', '', '', 0, NULL, '', '', '', NULL, '1511580899', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(805, '1511766996', NULL, 'Trường Tiểu học Ninh  Giang', '', '', '', 0, NULL, '', '', '', NULL, '1511580899', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(806, '1511767021', NULL, 'Trường Tiểu học Ninh  Hà', '', '', '', 0, NULL, '', '', '', NULL, '1511580899', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(807, '1511767047', NULL, 'Trường Tiểu học Ninh  Hải', '', '', '', 0, NULL, '', '', '', NULL, '1511580899', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(808, '1511767077', NULL, 'Trường Tiểu học số 1 Ninh  Hiệp', '', '', '', 0, NULL, '', '', '', NULL, '1511580899', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(809, '1511767104', NULL, 'Trường Tiểu học số 2 Ninh  Hiệp', '', '', '', 0, NULL, '', '', '', NULL, '1511580899', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(810, '1511767126', NULL, 'Trường Tiểu học số 3 Ninh  Hiệp', '', '', '', 0, NULL, '', '', '', NULL, '1511580899', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(811, '1511767159', NULL, 'Trường Tiểu học Ninh  Hưng', '', '', '', 0, NULL, '', '', '', NULL, '1511580899', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(812, '1511767185', NULL, 'Trường Tiểu học số 1 Ninh  Ích ', '', '', '', 0, NULL, '', '', '', NULL, '1511580899', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(813, '1511767209', NULL, 'Trường Tiểu học số 2 Ninh  Ích ', '', '', '', 0, NULL, '', '', '', NULL, '1511580899', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(814, '1511767233', NULL, 'Trường Tiểu học Ninh  Lộc', '', '', '', 0, NULL, '', '', '', NULL, '1511580899', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(815, '1511767293', NULL, 'Trường Tiểu học Ninh  Phú', '', '', '', 0, NULL, '', '', '', NULL, '1511580899', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(816, '1511767366', NULL, 'Trường Tiểu học số 1 Ninh  Phụng', '', '', '', 0, NULL, '', '', '', NULL, '1511580899', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(817, '1511767421', NULL, 'Trương Tiểu học số 2 Ninh  Phụng', '', '', '', 0, NULL, '', '', '', NULL, '1511580899', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(818, '1511767449', NULL, 'Trường Tiểu học Ninh  Phước', '', '', '', 0, NULL, '', '', '', NULL, '1511580899', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(819, '1511767477', NULL, 'Trường Tiểu học Ninh  Sim', '', '', '', 0, NULL, '', '', '', NULL, '1511580899', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(820, '1511767499', NULL, 'Trường Tiểu học Ninh  Sơn', '', '', '', 0, NULL, '', '', '', NULL, '1511580899', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(821, '1511767521', NULL, 'Trường Tiểu học số 1 Ninh  Quang', '', '', '', 0, NULL, '', '', '', NULL, '1511580899', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(822, '1511767545', NULL, 'Trường Tiểu học số 2 Ninh  Quang', '', '', '', 0, NULL, '', '', '', NULL, '1511580899', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(823, '1511767566', NULL, 'Trường Tiểu học Ninh  Tân', '', '', '', 0, NULL, '', '', '', NULL, '1511580899', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(824, '1511767589', NULL, 'Trường Tiểu học Ninh  Thân ', '', '', '', 0, NULL, '', '', '', NULL, '1511580899', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(825, '1511767637', NULL, 'Trường Tiểu học Ninh  Thọ', '', '', '', 0, NULL, '', '', '', NULL, '1511580899', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(826, '1511767658', NULL, 'Trường Tiểu học Ninh  Thuỷ', '', '', '', 0, NULL, '', '', '', NULL, '1511580899', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(827, '1511767682', NULL, 'Trường Tiểu học Ninh  Thượng', '', '', '', 0, NULL, '', '', '', NULL, '1511580899', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(828, '1511767704', NULL, 'Trường Tiểu học Ninh  Trung', '', '', '', 0, NULL, '', '', '', NULL, '1511580899', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(829, '1511767727', NULL, 'Trường Tiểu học Ninh  Vân', '', '', '', 0, NULL, '', '', '', NULL, '1511580899', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(830, '1511767752', NULL, 'Trường Tiểu học số 1 Ninh  Xuân', '', '', '', 0, NULL, '', '', '', NULL, '1511580899', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(831, '1511767774', NULL, 'Trường Tiểu học số 2 Ninh  Xuân', '', '', '', 0, NULL, '', '', '', NULL, '1511580899', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(832, '1511767799', NULL, 'Trường Mầm non Ninh  An', '', '', '', 0, NULL, '', '', '', NULL, '1511580899', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(833, '1511767823', NULL, 'Trường Mầm non Ninh  Bình', '', '', '', 0, NULL, '', '', '', NULL, '1511580899', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(834, '1511767844', NULL, 'Trường Mầm non Ninh  Diêm', '', '', '', 0, NULL, '', '', '', NULL, '1511580899', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(835, '1511767865', NULL, 'Trường Mầm non Ninh  Đa', '', '', '', 0, NULL, '', '', '', NULL, '1511580899', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(836, '1511767886', NULL, 'Trường Mầm non Ninh  Đông', '', '', '', 0, NULL, '', '', '', NULL, '1511580899', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(837, '1511767907', NULL, 'Trường Mầm non Ninh  Giang', '', '', '', 0, NULL, '', '', '', NULL, '1511580899', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(838, '1511767929', NULL, 'Trường Mầm non Ninh  Hà', '', '', '', 0, NULL, '', '', '', NULL, '1511580899', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(839, '1511767951', NULL, 'Trường Mầm non Ninh  Hải', '', '', '', 0, NULL, '', '', '', NULL, '1511580899', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(840, '1511767972', NULL, 'Trường Mầm non Hoa Sữa ', '', '', '', 0, NULL, '', '', '', NULL, '1511580899', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(841, '1511767997', NULL, 'Trường Mẫu giáo Hướng Dương', '', '', '', 0, NULL, '', '', '', NULL, '1511580899', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(842, '1511768021', NULL, 'Trường Mẫu giáo 2/9 ', '', '', '', 0, NULL, '', '', '', NULL, '1511580899', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(843, '1511768070', NULL, 'Trường Mầm non Ninh  Hưng', '', '', '', 0, NULL, '', '', '', NULL, '1511580899', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(844, '1511768093', NULL, 'Trường Mầm non Ninh  ích', '', '', '', 0, NULL, '', '', '', NULL, '1511580899', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(845, '1511768114', NULL, 'Trường Mầm non Ninh  Lộc', '', '', '', 0, NULL, '', '', '', NULL, '1511580899', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(846, '1511768139', NULL, 'Trường Mầm non Ninh  Phú', '', '', '', 0, NULL, '', '', '', NULL, '1511580899', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(847, '1511768191', NULL, 'Trường THCS Chu Văn An', '', '', '', 0, NULL, '', '', '', NULL, '1511580899', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(848, '1511768282', NULL, 'Trường THCS Nguyễn Văn  Cừ', '', '', '', 0, NULL, '', '', '', NULL, '1511580899', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(849, '1511768308', NULL, 'Trường THCS Nguyễn Thị Định', '', '', '', 0, NULL, '', '', '', NULL, '1511580899', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(850, '1511768331', NULL, 'Trường THCS Trương  Định', '', '', '', 0, NULL, '', '', '', NULL, '1511580899', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(851, '1511768353', NULL, 'Trường THCS Đinh Tiên  Hoàng', '', '', '', 0, NULL, '', '', '', NULL, '1511580899', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(852, '1511768372', NULL, 'Trường THCS Trần Quang  Khải', '', '', '', 0, NULL, '', '', '', NULL, '1511580899', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(853, '1511768422', NULL, 'Trường THCS Lý Thường Kiệt', '', '', '', 0, NULL, '', '', '', NULL, '1511580899', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(854, '1511768449', NULL, 'Trường THCS Phạm Ngũ  Lão', '', '', '', 0, NULL, '', '', '', NULL, '1511580899', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(855, '1511768482', NULL, 'Trường THCS Hàm  Nghi', '', '', '', 0, NULL, '', '', '', NULL, '1511580899', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(856, '1511768504', NULL, 'Trường THCS Ngô Thì Nhậm', '', '', '', 0, NULL, '', '', '', NULL, '1511580899', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(857, '1511768524', NULL, 'Trường THCS Lê Hồng Phong', '', '', '', 0, NULL, '', '', '', NULL, '1511580899', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(858, '1511768547', NULL, 'Trường THCS Trịnh Phong', '', '', '', 0, NULL, '', '', '', NULL, '1511580899', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(859, '1511768569', NULL, 'Trường THCS Trần Phú', '', '', '', 0, NULL, '', '', '', NULL, '1511580899', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(860, '1511768592', NULL, 'Trường THCS Nguyễn Tri  Phương', '', '', '', 0, NULL, '', '', '', NULL, '1511580899', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(861, '1511768616', NULL, 'Trường THCS Võ Thị  Sáu', '', '', '', 0, NULL, '', '', '', NULL, '1511580899', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(862, '1511768639', NULL, 'Trường THCS Tô Hiến  Thành', '', '', '', 0, NULL, '', '', '', NULL, '1511580899', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(863, '1511768661', NULL, 'Trường THCS Phạm Hồng  Thái', '', '', '', 0, NULL, '', '', '', NULL, '1511580899', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(864, '1511768685', NULL, 'Trường THCS Nguyễn Gia Thiều', '', '', '', 0, NULL, '', '', '', NULL, '1511580899', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(865, '1511768705', NULL, 'Trường THCS Quang  Trung', '', '', '', 0, NULL, '', '', '', NULL, '1511580899', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(866, '1511768731', NULL, 'Trường THCS Nguyễn Trung  Trực', '', '', '', 0, NULL, '', '', '', NULL, '1511580899', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(867, '1511768754', NULL, 'Trường THCS Trần Quốc Tuấn', '', '', '', 0, NULL, '', '', '', NULL, '1511580899', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(868, '1511768779', NULL, 'Trường THCS Trần Quốc  Toản', '', '', '', 0, NULL, '', '', '', NULL, '1511580899', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(869, '1511768812', NULL, 'Trường THCS Lê Thánh  Tông', '', '', '', 0, NULL, '', '', '', NULL, '1511580899', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(870, '1511768835', NULL, 'Trường THCS Ngô Gia Tự', '', '', '', 0, NULL, '', '', '', NULL, '1511580899', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(871, '1511768863', NULL, 'Trường THCS Đào Duy  Từ', '', '', '', 0, NULL, '', '', '', NULL, '1511580899', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(872, '1511768912', NULL, 'Trường THCS Nguyễn Phan Vinh', '', '', '', 0, NULL, '', '', '', NULL, '1511580899', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(873, '1511768935', NULL, 'Trường THCS Hùng  Vương', '', '', '', 0, NULL, '', '', '', NULL, '1511580899', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(874, '1511768960', NULL, 'Trường Tiểu học&THCS Ninh Tây', '', '', '', 0, NULL, '', '', '', NULL, '1511580899', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(875, '1511768998', NULL, 'Trường Phổ thông dân tộc nội trú', '', '', '', 0, NULL, '', '', '', NULL, '1511580899', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(876, '1511769027', NULL, 'Trường Mầm non Ninh  Phụng', '', '', '', 0, NULL, '', '', '', NULL, '1511580899', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(877, '1511769046', NULL, 'Trường Mầm non Ninh  Phước', '', '', '', 0, NULL, '', '', '', NULL, '1511580899', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(878, '1511769070', NULL, 'Trường Mầm non Ninh  Sim', '', '', '', 0, NULL, '', '', '', NULL, '1511580899', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(879, '1511769093', NULL, 'Trường Mẫu giáo Ninh Sơn', '', '', '', 0, NULL, '', '', '', NULL, '1511580899', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(880, '1511769117', NULL, 'Trường Mầm non Ninh  Quang', '', '', '', 0, NULL, '', '', '', NULL, '1511580899', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(881, '1511769142', NULL, 'Trường Mẫu giáo Ninh Tân', '', '', '', 0, NULL, '', '', '', NULL, '1511580899', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(882, '1511769167', NULL, 'Trường Mầm non Ninh  Tây', '', '', '', 0, NULL, '', '', '', NULL, '1511580899', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(883, '1511769190', NULL, 'Trường Mầm non Ninh  Thân ', '', '', '', 0, NULL, '', '', '', NULL, '1511580899', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(884, '1511769214', NULL, 'Trường Mầm non Ninh  Thọ', '', '', '', 0, NULL, '', '', '', NULL, '1511580899', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(885, '1511769237', NULL, 'Trường Mầm non Ninh  Thuỷ', '', '', '', 0, NULL, '', '', '', NULL, '1511580899', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(886, '1511769263', NULL, 'Trường Mẫu giáo Ninh  Thượng', '', '', '', 0, NULL, '', '', '', NULL, '1511580899', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(887, '1511769315', NULL, 'Trường Mầm non Ninh  Trung', '', '', '', 0, NULL, '', '', '', NULL, '1511580899', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(888, '1511769336', NULL, 'Trường Mẫu giáo Ninh  Vân', '', '', '', 0, NULL, '', '', '', NULL, '1511580899', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(889, '1511769359', NULL, 'Trường Mầm non Ninh  Xuân', '', '', '', 0, NULL, '', '', '', NULL, '1511580899', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(890, '1511769383', NULL, 'Trường Mầm non 1/ 5 ', '', '', '', 0, NULL, '', '', '', NULL, '1511580899', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(897, '1511881968', NULL, 'Phòng TCKH', '', '', '', 0, '', '', '', '', NULL, '1511879105', 'KVHCSN', '1', NULL, 'NGANSACH', NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(898, '1511881999', NULL, 'phòng GDĐT', '', '', '', 0, '1511881968', '', '', '', NULL, '1511879105', 'KVHCSN', '1', NULL, 'NGANSACH', NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(899, '1511882018', NULL, 'Trường 1', '', '', '', 0, '1511881999', '', '', '', NULL, '1511879105', 'KVHCSN', '1', NULL, 'NGANSACH', NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(900, '1511886443', NULL, 'Trường 2', '', '', '', 0, '1511881999', '', '', '', NULL, '1511879105', 'KVHCSN', '1', NULL, 'NGANSACH', NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(901, '1512224836', NULL, 'Phòng TC huyện', '', '', '', 0, NULL, '', '', '', NULL, '1512224802', 'KVXP', '1', '', NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(902, '1512224853', NULL, 'Xã a', '', '', '', 0, '1512224836', '', '', '', NULL, '1512224802', 'KVXP', '1', '', NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(903, '1512224885', NULL, 'Phòng GDĐT', '', '', '', 0, '1512224836', '', '', '', NULL, '1512224802', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(904, '1512224907', NULL, 'Trường 1', '', '', '', 0, '1512224885', '', '', '', NULL, '1512224802', 'KVHCSN', '1', NULL, 'NGANSACH', NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(905, '1512231041', NULL, 'trường 2', '', '', '', 0, '1512224885', '', '', '', NULL, '1512224802', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(906, '1512391746', NULL, 'Sở Tài chính Khánh Hòa', '', '', '', 0, '14', '', '', '', NULL, '1506415809', 'KVHCSN', '1', NULL, NULL, 'KT', 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(907, '1512393993', NULL, 'Sở Văn hóa và Thể thao', '', '', '', 0, '1512391746', '', '', '', NULL, '1506415809', 'KVHCSN', '2', NULL, NULL, 'TDTT', 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(908, '1512393997', NULL, 'Văn phòng UBND Tỉnh', NULL, NULL, NULL, 0, '1512391746', NULL, NULL, NULL, NULL, '1506415809', 'KVHCSN', NULL, NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(909, '1512393998', NULL, 'VP UBND tỉnh', NULL, NULL, NULL, 0, '1512391746', NULL, NULL, NULL, NULL, '1506415809', 'KVHCSN', NULL, NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(910, '1512393999', NULL, 'Sở Xây Dựng', NULL, NULL, NULL, 0, '1512391746', NULL, NULL, NULL, NULL, '1506415809', 'KVHCSN', NULL, NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(911, '1512394000', NULL, 'VP HĐND', NULL, NULL, NULL, 0, '1512391746', NULL, NULL, NULL, NULL, '1506415809', 'KVHCSN', NULL, NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(912, '1512394001', NULL, 'Sở Nội Vụ', NULL, NULL, NULL, 0, '1512391746', NULL, NULL, NULL, NULL, '1506415809', 'KVHCSN', NULL, NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(913, '1512394002', NULL, 'Sở Lao động TB&XH', NULL, NULL, NULL, 0, '1512391746', NULL, NULL, NULL, NULL, '1506415809', 'KVHCSN', NULL, NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(914, '1512394003', NULL, 'Sở Ngọai Vụ', NULL, NULL, NULL, 0, '1512391746', NULL, NULL, NULL, NULL, '1506415809', 'KVHCSN', NULL, NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(915, '1512394004', NULL, 'Sở G. Dục và Đào Tạo', NULL, NULL, NULL, 0, '1512391746', NULL, NULL, NULL, NULL, '1506415809', 'KVHCSN', NULL, NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(916, '1512394005', NULL, 'Sở Y Tế', NULL, NULL, NULL, 0, '1512391746', NULL, NULL, NULL, NULL, '1506415809', 'KVHCSN', NULL, NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(917, '1512394006', NULL, 'Sở T. Tin và T. Thông', NULL, NULL, NULL, 0, '1512391746', NULL, NULL, NULL, NULL, '1506415809', 'KVHCSN', NULL, NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(919, '1512394008', NULL, 'Sở Giao Thông Vận Tải', NULL, NULL, NULL, 0, '1512391746', NULL, NULL, NULL, NULL, '1506415809', 'KVHCSN', NULL, NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(920, '1512394009', NULL, 'Sở tư pháp', NULL, NULL, NULL, 0, '1512391746', NULL, NULL, NULL, NULL, '1506415809', 'KVHCSN', NULL, NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(921, '1512394010', NULL, 'Sở Công Thương', NULL, NULL, NULL, 0, '1512391746', NULL, NULL, NULL, NULL, '1506415809', 'KVHCSN', NULL, NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(922, '1512394011', NULL, 'Sở KH & Đầu Tư', NULL, NULL, NULL, 0, '1512391746', NULL, NULL, NULL, NULL, '1506415809', 'KVHCSN', NULL, NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(923, '1512394012', NULL, 'Thanh Tra Tỉnh', NULL, NULL, NULL, 0, '1512391746', NULL, NULL, NULL, NULL, '1506415809', 'KVHCSN', NULL, NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(924, '1512394013', NULL, 'Sở TN & môi trường', NULL, NULL, NULL, 0, '1512391746', NULL, NULL, NULL, NULL, '1506415809', 'KVHCSN', NULL, NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(925, '1512394014', NULL, 'Ban Dân Tộc', NULL, NULL, NULL, 0, '1512391746', NULL, NULL, NULL, NULL, '1506415809', 'KVHCSN', NULL, NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(926, '1512394015', NULL, 'BQL Khu Kinh Tế Vân Phong', NULL, NULL, NULL, 0, '1512391746', NULL, NULL, NULL, NULL, '1506415809', 'KVHCSN', NULL, NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(927, '1512394016', NULL, 'Sở Khoa Học&Công Nghệ', NULL, NULL, NULL, 0, '1512391746', NULL, NULL, NULL, NULL, '1506415809', 'KVHCSN', NULL, NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(928, '1512394017', NULL, 'Tỉnh ủy Khánh Hòa', NULL, NULL, NULL, 0, '1512391746', NULL, NULL, NULL, NULL, '1506415809', 'KVHCSN', NULL, NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(929, '1512394018', NULL, 'UB mặt trận TQ tỉnh KH', NULL, NULL, NULL, 0, '1512391746', NULL, NULL, NULL, NULL, '1506415809', 'KVHCSN', NULL, NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(930, '1512394019', NULL, 'Hội Liên Hiệp Phụ nữ ', NULL, NULL, NULL, 0, '1512391746', NULL, NULL, NULL, NULL, '1506415809', 'KVHCSN', NULL, NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(931, '1512394020', NULL, 'Tỉnh Đòan Khánh Hòa', NULL, NULL, NULL, 0, '1512391746', NULL, NULL, NULL, NULL, '1506415809', 'KVHCSN', NULL, NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(932, '1512394021', NULL, 'Hội cựu chiến binh tỉnh', NULL, NULL, NULL, 0, '1512391746', NULL, NULL, NULL, NULL, '1506415809', 'KVHCSN', NULL, NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(933, '1512394022', NULL, 'Hội nông dân', NULL, NULL, NULL, 0, '1512391746', NULL, NULL, NULL, NULL, '1506415809', 'KVHCSN', NULL, NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(934, '1512394023', NULL, 'Sở Du lịch', NULL, NULL, NULL, 0, '1512391746', NULL, NULL, NULL, NULL, '1506415809', 'KVHCSN', NULL, NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(935, '1512394024', NULL, 'Trường Đại học Khánh Hòa', NULL, NULL, NULL, 0, '1512391746', NULL, NULL, NULL, NULL, '1506415809', 'KVHCSN', NULL, NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(936, '1512394025', NULL, 'Trường CĐ Nghề NT', NULL, NULL, NULL, 0, '1512391746', NULL, NULL, NULL, NULL, '1506415809', 'KVHCSN', NULL, NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(937, '1512394026', NULL, 'Trường CĐ Y tế Khánh Hòa ', NULL, NULL, NULL, 0, '1512391746', NULL, NULL, NULL, NULL, '1506415809', 'KVHCSN', NULL, NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(938, '1512394027', NULL, 'Trường Chính trị', NULL, NULL, NULL, 0, '1512391746', NULL, NULL, NULL, NULL, '1506415809', 'KVHCSN', NULL, NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(939, '1512394028', NULL, 'BQL KDL Bán đảo Cam Ranh', NULL, NULL, NULL, 0, '1512391746', NULL, NULL, NULL, NULL, '1506415809', 'KVHCSN', NULL, NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(940, '1512394029', NULL, 'Hội đồng Liên minh các hợp tác xã ', NULL, NULL, NULL, 0, '1512391746', NULL, NULL, NULL, NULL, '1506415809', 'KVHCSN', NULL, NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(941, '1512394030', NULL, 'Liên hiệp các hội khoa học kỹ thuật  ', NULL, NULL, NULL, 0, '1512391746', NULL, NULL, NULL, NULL, '1506415809', 'KVHCSN', NULL, NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(942, '1512394031', NULL, 'Liên hiệp các tổ chức hữu nghị ', NULL, NULL, NULL, 0, '1512391746', NULL, NULL, NULL, NULL, '1506415809', 'KVHCSN', NULL, NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(943, '1512394032', NULL, 'Hội Nhà báo ', NULL, NULL, NULL, 0, '1512391746', NULL, NULL, NULL, NULL, '1506415809', 'KVHCSN', NULL, NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(944, '1512394033', NULL, 'Hội văn học nghệ thuật ', NULL, NULL, NULL, 0, '1512391746', NULL, NULL, NULL, NULL, '1506415809', 'KVHCSN', NULL, NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(945, '1512394034', NULL, 'Hội đông y ', NULL, NULL, NULL, 0, '1512391746', NULL, NULL, NULL, NULL, '1506415809', 'KVHCSN', NULL, NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(946, '1512394035', NULL, 'Hội Chữ thập đỏ ', NULL, NULL, NULL, 0, '1512391746', NULL, NULL, NULL, NULL, '1506415809', 'KVHCSN', NULL, NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(947, '1512394036', NULL, 'Hỗ trợ các hội tổ chức chính trị - xã hội - nghề nghiệp ', NULL, NULL, NULL, 0, '1512391746', NULL, NULL, NULL, NULL, '1506415809', 'KVHCSN', NULL, NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(949, '1519985070', NULL, 'Văn phòng Sở', '04 Phan Chu Trinh, Xương Huân, Nha Trang', '02583.828.526', 'Lê Tấn Bản', 0, '1520042304', '', 'Giám đốc', 'Phạm Thị Như Quỳnh', NULL, '1506415809', 'KVHCSN', '1', '', 'NGANSACH', NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(950, '1519985119', NULL, 'Chi cục Thủy Lợi', '', '', '', 0, '1520042304', '', '', '', NULL, '1506415809', 'KVHCSN', '1', '', NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(951, '1519985144', NULL, 'Trung tâm nước sinh hoạt', '', '', '', 0, '1520042304', '', '', '', NULL, '1506415809', 'KVHCSN', '1', '', NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(952, '1519985212', NULL, 'BQL RPH Cam Lâm', '', '', '', 0, '1520042304', '', '', '', NULL, '1506415809', 'KVHCSN', '1', '', NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(953, '1519985253', NULL, 'Chi cục kiểm lâm', '', '', '', 0, '', '', '', '', NULL, '1506415809', 'KVXP', '1', '', NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(954, '1519985280', NULL, 'Văn phòng Chi cục Kiểm Lâm', '', '', '', 0, '1519985253', '', '', '', NULL, '1506415809', 'KVXP', '1', '', NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(955, '1519985299', NULL, 'Đội Kiểm lâm cơ động và Phòng chống cháy rừng', '', '', '', 0, '1519985253', '', '', '', NULL, '1506415809', 'KVXP', '1', '', NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(956, '1519985319', NULL, 'Hạt Kiểm lâm Cam Lâm', '', '', '', 0, '1519985253', '', '', '', NULL, '1506415809', 'KVXP', '1', '', NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(957, '1520042304', NULL, 'Sở Nông Nghiệp và Phát Triển NT', '', '', '', 0, '1512391746', '', '', '', NULL, '1506415809', 'KVHCSN', '1', '', NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(958, '1533870771', NULL, 'Tổng hợp phòng tài chính kế hoạch Thành Phố Nha Trang', '', '', '', 0, '1533870771', 'Khánh Hòa', '', '', NULL, '1506416677', 'KVHCSN', '1', NULL, NULL, NULL, 'TH', 'HUYEN', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(959, '1533870811', NULL, 'Tổng hợp phòng giáo dục & Đào Tạo thành phố Nha Trang', '', '', '', 0, '1533870771', 'Khánh Hòa', '', '', NULL, '1506416677', 'KVHCSN', '1', NULL, NULL, NULL, 'TH', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(960, '1534123065', NULL, 'Tổng hợp phòng Tài chính kế hoạch huyện Cam Lâm', 'Huyện Cam Lâm', '', '', 0, '1534123065', 'Khánh Hòa', '', '', NULL, '1511578024', 'KVHCSN', '1', NULL, NULL, NULL, 'TH', 'HUYEN', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(961, '1534123114', NULL, 'Tổng hợp phòng giáo dục & đào tạo huyện Cam Lâm', 'Cam Lâm', NULL, NULL, 0, '1534123065', 'Khánh Hòa', NULL, NULL, NULL, '1511578024', NULL, NULL, NULL, NULL, NULL, 'TH', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(962, '1534123186', NULL, 'Tổng hợp phòng Tài chính kế hoạch huyện Cam Ranh', 'Cam Ranh', NULL, NULL, 0, NULL, 'Khánh Hòa', NULL, NULL, NULL, '1511580827', NULL, NULL, NULL, NULL, NULL, 'TH', 'HUYEN', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(963, '1534123212', NULL, 'Tổng hợp phòng giáo dục & đào tạo huyện Cam Ranh', 'Cam Ranh', NULL, NULL, 0, '1534123186', 'Khánh Hòa', NULL, NULL, NULL, '1511580827', NULL, NULL, NULL, NULL, NULL, 'TH', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(964, '1534123257', NULL, 'Tổng hợp phòng Tài chính kế hoạch huyện Diêm Khánh', 'Diêm Khánh', '', '', 0, '1534123257', 'Khánh Hòa', '', '', NULL, '1511580845', 'KVHCSN', '1', NULL, NULL, NULL, 'TH', 'HUYEN', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(965, '1534123294', NULL, 'Tổng hợp phòng giáo dục & đào tạo huyện Diêm Khánh', 'Diêm Khánh', NULL, NULL, 0, '1534123257', 'Khánh Hòa', NULL, NULL, NULL, '1511580845', NULL, NULL, NULL, NULL, NULL, 'TH', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(966, '1534123336', NULL, 'Tổng hợp phòng Tài chính kế hoạch huyện Khánh Sơn', 'Khánh Sơn', '', '', 0, '1534123336', 'Khánh Hòa', '', '', NULL, '1511580856', 'KVHCSN', '1', NULL, NULL, NULL, 'TH', 'HUYEN', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(967, '1534123367', NULL, 'Tổng hợp phòng giáo dục & đào tạo huyện Khánh Sơn', 'Khánh Sơn', NULL, NULL, 0, '1534123336', 'Khánh Hòa', NULL, NULL, NULL, '1511580856', NULL, NULL, NULL, NULL, NULL, 'TH', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(968, '1534123458', NULL, 'Tổng hợp phòng Tài chính kế hoạch huyện Khánh Vĩnh', 'Khánh Vĩnh', NULL, NULL, 0, NULL, 'Khánh Hòa', NULL, NULL, NULL, '1511580879', NULL, NULL, NULL, NULL, NULL, 'TH', 'HUYEN', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(969, '1534123482', NULL, 'Tổng hợp phòng giáo dục & đào tạo huyện Khánh Vĩnh', 'Khánh Vĩnh', NULL, NULL, 0, '1534123458', 'Khánh Hòa', NULL, NULL, NULL, '1511580879', NULL, NULL, NULL, NULL, NULL, 'TH', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(970, '1534123523', NULL, 'Tổng hợp phòng Tài chính kế hoạch Thị Xã Ninh Hòa', 'Thị Xã Ninh Hòa', NULL, NULL, 0, NULL, 'Khánh Hòa', NULL, NULL, NULL, '1511580899', NULL, NULL, NULL, NULL, NULL, 'TH', 'HUYEN', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(971, '1534123552', NULL, 'Tổng hợp phòng giáo dục & đào tạo Thị Xã Ninh Hòa', 'Thị Xã Ninh Hòa', NULL, NULL, 0, '1534123523', 'Khánh Hòa', NULL, NULL, NULL, '1511580899', NULL, NULL, NULL, NULL, NULL, 'TH', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(972, '1534123582', NULL, 'Tổng hợp phòng Tài chính kế hoạch huyện Vạn Ninh', 'Vạn Ninh', NULL, NULL, 0, NULL, 'Khánh Hòa', NULL, NULL, NULL, '1511580911', NULL, NULL, NULL, NULL, NULL, 'TH', 'HUYEN', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(973, '1534123604', NULL, 'Tổng hợp phòng giáo dục & đào tạo huyện Vạn Ninh', 'Vạn Ninh', NULL, NULL, 0, '1534123582', 'Khánh Hòa', NULL, NULL, NULL, '1511580911', NULL, NULL, NULL, NULL, NULL, 'TH', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(974, '1534385271', NULL, 'Phòng Tài Chính Huyện', 'abc', NULL, NULL, 0, NULL, 'abc', NULL, NULL, NULL, '1534385083', NULL, NULL, NULL, NULL, NULL, 'TH', 'HUYEN', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL),
(975, '1534385336', NULL, 'Huyện a', 'abc', NULL, NULL, 0, '1534385271', 'abc', NULL, NULL, NULL, '1534385083', 'KVHCSN', '1', NULL, NULL, NULL, 'SD', 'KHOI', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `dmdonvibaocao`
--

CREATE TABLE `dmdonvibaocao` (
  `id` int(10) UNSIGNED NOT NULL,
  `madvbc` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `tendvbc` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `level` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `ghichu` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `madvcq` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `malinhvu` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `dmdonvibaocao`
--

INSERT INTO `dmdonvibaocao` (`id`, `madvbc`, `tendvbc`, `level`, `ghichu`, `madvcq`, `malinhvu`, `created_at`, `updated_at`) VALUES
(1, '1506415809', 'Tỉnh Khánh Hòa', 'T', '', '1512391746', NULL, '2017-09-26 08:50:09', '2017-09-26 08:50:09'),
(2, '1506416677', 'Thành phố Nha Trang', 'H', '', '1511711090', NULL, '2017-09-26 09:04:37', '2017-09-26 09:05:58'),
(4, '1511578024', 'Huyện Cam Lâm', 'H', '', '1511581490', NULL, NULL, NULL),
(6, '1511580827', 'Huyện Cam Ranh', 'H', '', '1511748256', NULL, NULL, NULL),
(7, '1511580845', 'Huyện Diên Khánh', 'H', '', '1511751883', NULL, NULL, NULL),
(8, '1511580856', 'Huyện Khánh Sơn', 'H', '', '1511581308', NULL, NULL, NULL),
(9, '1511580879', 'Huyện Khánh Vĩnh', 'H', '', '1511584731', NULL, NULL, NULL),
(10, '1511580899', 'Huyện Ninh Hòa', 'H', '', '1511756414', NULL, NULL, NULL),
(11, '1511580911', 'Huyện Vạn Ninh', 'H', '', '1511708261', NULL, NULL, NULL),
(12, '1534385083', 'Huyện', 'H', '', '1534385271', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `dmkhoipb`
--

CREATE TABLE `dmkhoipb` (
  `id` int(10) UNSIGNED NOT NULL,
  `level` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `makhoipb` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `tenkhoipb` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `ghichu` text COLLATE utf8_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `dmkhoipb`
--

INSERT INTO `dmkhoipb` (`id`, `level`, `makhoipb`, `tenkhoipb`, `ghichu`, `created_at`, `updated_at`) VALUES
(1, NULL, 'GD', 'Sự nghiệp giáo dục', '', '2017-09-29 03:05:51', '2017-09-29 03:08:53'),
(2, NULL, 'DT', 'Sự nghiệp đào tạo', '', '2017-09-29 03:06:12', '2017-09-29 03:09:03'),
(3, NULL, 'YTE', 'Sự nghiệp y tế', '', '2017-09-29 03:09:14', '2017-09-29 03:09:14'),
(4, NULL, 'KHCN', 'Sự nghiệp khoa học - công nghệ', '', '2017-09-29 03:09:30', '2017-09-29 03:10:32'),
(5, NULL, 'VHTT', 'Sự nghiệp văn hóa thông tin', '', '2017-09-29 03:09:44', '2017-09-29 03:09:44'),
(6, NULL, 'PTTH', 'Sự nghiệp phát thanh truyền hình', '', '2017-09-29 03:10:03', '2017-09-29 03:10:03'),
(7, NULL, 'TDTT', 'Sự nghiệp thể dục - thể thao', '', '2017-09-29 03:10:23', '2017-09-29 03:10:23'),
(8, NULL, 'DBXH', 'Sự nghiệp đảm bảo xã hội', '', '2017-09-29 03:10:48', '2017-09-29 03:10:48'),
(9, NULL, 'KT', 'Sự nghiệp kinh tế', '', '2017-09-29 03:11:03', '2017-09-29 03:11:03'),
(10, NULL, 'MT', 'Sự nghiệp môi trường', '', '2017-09-29 03:11:23', '2017-09-29 03:11:23'),
(11, NULL, 'QLNN', 'Quản lý NN', '', '2017-09-29 03:11:52', '2017-09-29 03:11:52'),
(12, NULL, 'DDT', 'Đảng, đoàn thể', '', '2017-09-29 03:12:09', '2017-09-29 03:12:09');

-- --------------------------------------------------------

--
-- Table structure for table `dmngachcc`
--

CREATE TABLE `dmngachcc` (
  `id` int(10) UNSIGNED NOT NULL,
  `msngbac` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `phanloai` varchar(150) COLLATE utf8_unicode_ci DEFAULT NULL,
  `tenngbac` varchar(150) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `dmnguonkinhphi`
--

CREATE TABLE `dmnguonkinhphi` (
  `id` int(10) UNSIGNED NOT NULL,
  `manguonkp` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `tennguonkp` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `phanloai` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `ghichu` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `dmnguonkinhphi`
--

INSERT INTO `dmnguonkinhphi` (`id`, `manguonkp`, `tennguonkp`, `phanloai`, `ghichu`, `created_at`, `updated_at`) VALUES
(1, '12', 'Kinh phí không giao tự chủ,không giao khoán', 'NGANSACH', '', '2017-09-28 08:51:02', '2017-09-28 09:19:12'),
(2, '13', 'Kinh phí giao tự chủ, giao khoán', 'NGANSACH', '', '2017-09-28 08:54:14', '2017-09-28 08:54:14'),
(3, '14', 'Kinh phí thực hiện cải cách tiền lương', 'NGANSACH', '', '2017-09-28 08:54:38', '2017-09-28 08:54:38'),
(4, '15', 'Kinh phí hỗ trợ hoạt động sáng tạo tác phẩm, công trình văn hóa nghệ thuật', 'NGANSACH', '', NULL, NULL),
(5, '16', 'Kinh phí chương trình, dự án, đề tài', NULL, NULL, NULL, NULL),
(6, '17', 'Kinh phí thực hiện chính sách', NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `dmphanloaicongtac`
--

CREATE TABLE `dmphanloaicongtac` (
  `id` int(10) UNSIGNED NOT NULL,
  `maphanloai` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `macongtac` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `tencongtac` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `bhxh` double NOT NULL DEFAULT '0',
  `bhyt` double NOT NULL DEFAULT '0',
  `bhtn` double NOT NULL DEFAULT '0',
  `kpcd` double NOT NULL DEFAULT '0',
  `bhxh_dv` double NOT NULL DEFAULT '0',
  `bhyt_dv` double NOT NULL DEFAULT '0',
  `bhtn_dv` double NOT NULL DEFAULT '0',
  `kpcd_dv` double NOT NULL DEFAULT '0',
  `sapxep` double NOT NULL DEFAULT '1',
  `ghichu` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `dmphanloaicongtac`
--

INSERT INTO `dmphanloaicongtac` (`id`, `maphanloai`, `macongtac`, `tencongtac`, `bhxh`, `bhyt`, `bhtn`, `kpcd`, `bhxh_dv`, `bhyt_dv`, `bhtn_dv`, `kpcd_dv`, `sapxep`, `ghichu`, `created_at`, `updated_at`) VALUES
(1, 'Hành chính', 'BIENCHE', 'Cán bộ biên chế', 8, 1.5, 1, 0, 17.5, 3, 1, 2, 1, '', '2017-09-29 04:04:29', '2017-10-18 08:04:52'),
(2, 'Hành chính', 'HOPDONG', 'Cán bộ hợp đồng', 8, 1.5, 1, 0, 17.5, 3, 1, 2, 2, '', '2017-09-29 04:04:56', '2017-10-18 08:32:49'),
(3, '', 'KHONGCT', 'Cán bộ không chuyên trách', 0, 0, 0, 0, 0, 7, 0, 0, 3, '', '2017-09-29 04:05:10', '2017-09-29 04:05:10'),
(4, 'Hành chính', 'NGHIHUU', 'Cán bộ đã nghỉ hưu', 0, 0, 0, 0, 0, 0, 0, 0, 4, '', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `dmphanloaicongtac_baohiem`
--

CREATE TABLE `dmphanloaicongtac_baohiem` (
  `id` int(10) UNSIGNED NOT NULL,
  `madv` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `macongtac` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `bhxh` double NOT NULL DEFAULT '0',
  `bhyt` double NOT NULL DEFAULT '0',
  `bhtn` double NOT NULL DEFAULT '0',
  `kpcd` double NOT NULL DEFAULT '0',
  `bhxh_dv` double NOT NULL DEFAULT '0',
  `bhyt_dv` double NOT NULL DEFAULT '0',
  `bhtn_dv` double NOT NULL DEFAULT '0',
  `kpcd_dv` double NOT NULL DEFAULT '0',
  `ghichu` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `dmphanloaicongtac_baohiem`
--

INSERT INTO `dmphanloaicongtac_baohiem` (`id`, `madv`, `macongtac`, `bhxh`, `bhyt`, `bhtn`, `kpcd`, `bhxh_dv`, `bhyt_dv`, `bhtn_dv`, `kpcd_dv`, `ghichu`, `created_at`, `updated_at`) VALUES
(1, '1512391746', 'BIENCHE', 8, 1.5, 1, 0, 17.5, 3, 1, 2, NULL, NULL, NULL),
(2, '1512391746', 'HOPDONG', 8, 1.5, 1, 0, 17.5, 3, 1, 2, NULL, NULL, NULL),
(3, '1512391746', 'KHONGCT', 0, 0, 0, 0, 0, 7, 0, 0, NULL, NULL, NULL),
(4, '1512391746', 'NGHIHUU', 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL),
(5, '1512224836', 'BIENCHE', 8, 1.5, 1, 0, 17.5, 3, 1, 2, NULL, NULL, NULL),
(6, '1512224836', 'HOPDONG', 8, 1.5, 1, 0, 17.5, 3, 1, 2, NULL, NULL, NULL),
(7, '1512224836', 'KHONGCT', 0, 0, 0, 0, 0, 7, 0, 0, NULL, NULL, NULL),
(8, '1512224836', 'NGHIHUU', 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL),
(9, '1512224853', 'BIENCHE', 8, 1.5, 1, 0, 17.5, 3, 1, 2, NULL, NULL, NULL),
(10, '1512224853', 'HOPDONG', 8, 1.5, 1, 0, 17.5, 3, 1, 2, NULL, NULL, NULL),
(11, '1512224853', 'KHONGCT', 0, 0, 0, 0, 0, 7, 0, 0, NULL, NULL, NULL),
(12, '1512224853', 'NGHIHUU', 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL),
(13, '1511711141', 'BIENCHE', 8, 1.5, 1, 0, 17.5, 3, 1, 2, NULL, NULL, NULL),
(14, '1511711141', 'HOPDONG', 8, 1.5, 1, 0, 17.5, 3, 1, 2, NULL, NULL, NULL),
(15, '1511711141', 'KHONGCT', 0, 0, 0, 0, 0, 7, 0, 0, NULL, NULL, NULL),
(16, '1511711141', 'NGHIHUU', 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL),
(17, '1519985070', 'BIENCHE', 8, 1.5, 1, 0, 17.5, 3, 1, 2, NULL, NULL, NULL),
(18, '1519985070', 'HOPDONG', 8, 1.5, 1, 0, 17.5, 3, 1, 2, NULL, NULL, NULL),
(19, '1519985070', 'KHONGCT', 0, 0, 0, 0, 0, 7, 0, 0, NULL, NULL, NULL),
(20, '1519985070', 'NGHIHUU', 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL),
(21, '1519985280', 'BIENCHE', 8, 1.5, 1, 0, 17.5, 3, 1, 2, NULL, NULL, NULL),
(22, '1519985280', 'HOPDONG', 8, 1.5, 1, 0, 17.5, 3, 1, 2, NULL, NULL, NULL),
(23, '1519985280', 'KHONGCT', 0, 0, 0, 0, 0, 7, 0, 0, NULL, NULL, NULL),
(24, '1519985280', 'NGHIHUU', 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL),
(25, '1512393993', 'BIENCHE', 8, 1.5, 1, 0, 17.5, 3, 1, 2, NULL, NULL, NULL),
(26, '1512393993', 'HOPDONG', 8, 1.5, 1, 0, 17.5, 3, 1, 2, NULL, NULL, NULL),
(27, '1512393993', 'KHONGCT', 0, 0, 0, 0, 0, 7, 0, 0, NULL, NULL, NULL),
(28, '1512393993', 'NGHIHUU', 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL),
(29, '1511711113', 'BIENCHE', 8, 1.5, 1, 0, 17.5, 3, 1, 2, NULL, NULL, NULL),
(30, '1511711113', 'HOPDONG', 8, 1.5, 1, 0, 17.5, 3, 1, 2, NULL, NULL, NULL),
(31, '1511711113', 'KHONGCT', 0, 0, 0, 0, 0, 7, 0, 0, NULL, NULL, NULL),
(32, '1511711113', 'NGHIHUU', 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL),
(33, '1533870811', 'BIENCHE', 8, 1.5, 1, 0, 17.5, 3, 1, 2, NULL, NULL, NULL),
(34, '1533870811', 'HOPDONG', 8, 1.5, 1, 0, 17.5, 3, 1, 2, NULL, NULL, NULL),
(35, '1533870811', 'KHONGCT', 0, 0, 0, 0, 0, 7, 0, 0, NULL, NULL, NULL),
(36, '1533870811', 'NGHIHUU', 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL),
(37, '1511711090', 'BIENCHE', 8, 1.5, 1, 0, 17.5, 3, 1, 2, NULL, NULL, NULL),
(38, '1511711090', 'HOPDONG', 8, 1.5, 1, 0, 17.5, 3, 1, 2, NULL, NULL, NULL),
(39, '1511711090', 'KHONGCT', 0, 0, 0, 0, 0, 7, 0, 0, NULL, NULL, NULL),
(40, '1511711090', 'NGHIHUU', 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL),
(41, '1533870771', 'BIENCHE', 8, 1.5, 1, 0, 17.5, 3, 1, 2, NULL, NULL, NULL),
(42, '1533870771', 'HOPDONG', 8, 1.5, 1, 0, 17.5, 3, 1, 2, NULL, NULL, NULL),
(43, '1533870771', 'KHONGCT', 0, 0, 0, 0, 0, 7, 0, 0, NULL, NULL, NULL),
(44, '1533870771', 'NGHIHUU', 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL),
(45, '1534123114', 'BIENCHE', 8, 1.5, 1, 0, 17.5, 3, 1, 2, NULL, NULL, NULL),
(46, '1534123114', 'HOPDONG', 8, 1.5, 1, 0, 17.5, 3, 1, 2, NULL, NULL, NULL),
(47, '1534123114', 'KHONGCT', 0, 0, 0, 0, 0, 7, 0, 0, NULL, NULL, NULL),
(48, '1534123114', 'NGHIHUU', 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL),
(49, '1511713086', 'BIENCHE', 8, 1.5, 1, 0, 17.5, 3, 1, 2, NULL, NULL, NULL),
(50, '1511713086', 'HOPDONG', 8, 1.5, 1, 0, 17.5, 3, 1, 2, NULL, NULL, NULL),
(51, '1511713086', 'KHONGCT', 0, 0, 0, 0, 0, 7, 0, 0, NULL, NULL, NULL),
(52, '1511713086', 'NGHIHUU', 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL),
(53, '1534385336', 'BIENCHE', 8, 1.5, 1, 0, 17.5, 3, 1, 2, NULL, NULL, NULL),
(54, '1534385336', 'HOPDONG', 8, 1.5, 1, 0, 17.5, 3, 1, 2, NULL, NULL, NULL),
(55, '1534385336', 'KHONGCT', 0, 0, 0, 0, 0, 7, 0, 0, NULL, NULL, NULL),
(56, '1534385336', 'NGHIHUU', 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL),
(57, '1534385271', 'BIENCHE', 8, 1.5, 1, 0, 17.5, 3, 1, 2, NULL, NULL, NULL),
(58, '1534385271', 'HOPDONG', 8, 1.5, 1, 0, 17.5, 3, 1, 2, NULL, NULL, NULL),
(59, '1534385271', 'KHONGCT', 0, 0, 0, 0, 0, 7, 0, 0, NULL, NULL, NULL),
(60, '1534385271', 'NGHIHUU', 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL),
(61, '1511715892', 'BIENCHE', 8, 1.5, 1, 0, 17.5, 3, 1, 2, NULL, NULL, NULL),
(62, '1511715892', 'HOPDONG', 8, 1.5, 1, 0, 17.5, 3, 1, 2, NULL, NULL, NULL),
(63, '1511715892', 'KHONGCT', 0, 0, 0, 0, 0, 7, 0, 0, NULL, NULL, NULL),
(64, '1511715892', 'NGHIHUU', 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL),
(65, '1511713982', 'BIENCHE', 8, 1.5, 1, 0, 17.5, 3, 1, 2, NULL, NULL, NULL),
(66, '1511713982', 'HOPDONG', 8, 1.5, 1, 0, 17.5, 3, 1, 2, NULL, NULL, NULL),
(67, '1511713982', 'KHONGCT', 0, 0, 0, 0, 0, 7, 0, 0, NULL, NULL, NULL),
(68, '1511713982', 'NGHIHUU', 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL),
(69, '1511751864', 'BIENCHE', 8, 1.5, 1, 0, 17.5, 3, 1, 2, NULL, NULL, NULL),
(70, '1511751864', 'HOPDONG', 8, 1.5, 1, 0, 17.5, 3, 1, 2, NULL, NULL, NULL),
(71, '1511751864', 'KHONGCT', 0, 0, 0, 0, 0, 7, 0, 0, NULL, NULL, NULL),
(72, '1511751864', 'NGHIHUU', 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `dmphanloaict`
--

CREATE TABLE `dmphanloaict` (
  `id` int(10) UNSIGNED NOT NULL,
  `macongtac` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `mact` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `tenct` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `ghichu` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `dmphanloaict`
--

INSERT INTO `dmphanloaict` (`id`, `macongtac`, `mact`, `tenct`, `ghichu`, `created_at`, `updated_at`) VALUES
(1, 'BIENCHE', '1506672780', 'Biên chế', '', '2017-09-29 08:13:00', '2017-09-29 08:20:16'),
(2, 'BIENCHE', '1506673238', 'Hợp đồng không thời hạn', '', '2017-09-29 08:20:38', '2017-09-29 08:23:31'),
(3, 'BIENCHE', '1506673422', 'Tập sự, thử việc', '', '2017-09-29 08:23:42', '2017-09-29 08:23:42'),
(4, 'BIENCHE', '1506673544', 'Hợp đồng dài hạn', '', '2017-09-29 08:25:44', '2017-09-29 08:25:44'),
(5, 'BIENCHE', '1506673572', 'Hợp đồng lao động đặc biệt', '', '2017-09-29 08:26:12', '2017-09-29 08:26:12'),
(6, 'BIENCHE', '1506673585', 'Hợp đồng Nghị định 68', '', '2017-09-29 08:26:26', '2017-09-29 08:26:26'),
(7, 'BIENCHE', '1506673604', 'Cán bộ chuyên trách', '', '2017-09-29 08:26:44', '2017-09-29 08:28:42'),
(8, 'HOPDONG', '1506673645', 'Hợp đồng tạm tuyển', '', '2017-09-29 08:27:25', '2017-09-29 08:27:25'),
(9, 'HOPDONG', '1506673663', 'Hợp đồng lần đầu', '', '2017-09-29 08:27:43', '2017-09-29 08:27:43'),
(10, 'HOPDONG', '1506673673', 'Hợp đồng ngắn hạn', '', '2017-09-29 08:27:53', '2017-09-29 08:27:53'),
(11, 'KHONGCT', '1506673695', 'Cán bộ không chuyên trách', '', '2017-09-29 08:28:15', '2017-09-29 08:28:15'),
(12, 'NGHIHUU', '1508229275', 'Cán bộ đã nghỉ hưu', '', '2017-10-17 08:34:35', '2017-10-17 08:34:35');

-- --------------------------------------------------------

--
-- Table structure for table `dmphanloaidonvi`
--

CREATE TABLE `dmphanloaidonvi` (
  `id` int(10) UNSIGNED NOT NULL,
  `maphanloai` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `tenphanloai` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `ghichu` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `dmphanloaidonvi`
--

INSERT INTO `dmphanloaidonvi` (`id`, `maphanloai`, `tenphanloai`, `ghichu`, `created_at`, `updated_at`) VALUES
(1, 'KVHCSN', 'Khu vực HCSN, Đảng, Đoàn thể', '', '2017-09-28 09:30:41', '2017-09-28 09:31:38'),
(2, 'KVXP', 'Khu vực xã, phường, thị trấn', '', '2017-09-28 09:31:25', '2017-09-28 09:31:25');

-- --------------------------------------------------------

--
-- Table structure for table `dmphongban`
--

CREATE TABLE `dmphongban` (
  `id` int(10) UNSIGNED NOT NULL,
  `mapb` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `tenpb` varchar(150) COLLATE utf8_unicode_ci DEFAULT NULL,
  `diengiai` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `sapxep` int(11) NOT NULL DEFAULT '99',
  `madv` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `dmphucap`
--

CREATE TABLE `dmphucap` (
  `id` int(10) UNSIGNED NOT NULL,
  `mapc` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `tenpc` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `baohiem` tinyint(1) DEFAULT NULL,
  `form` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `report` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `phanloai` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `congthuc` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `ghichu` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `dmphucap`
--

INSERT INTO `dmphucap` (`id`, `mapc`, `tenpc`, `baohiem`, `form`, `report`, `phanloai`, `congthuc`, `ghichu`, `created_at`, `updated_at`) VALUES
(1, 'pckv', 'Phụ cấp khu vực', 0, 'Khu vực', 'Phụ cấp</br>khu vực', '0', '', NULL, NULL, NULL),
(2, 'pccv', 'Phụ cấp chức vụ', 1, 'Chức vụ', 'Phụ cấp</br>chức vụ', '0', '', NULL, NULL, NULL),
(3, 'pcudn', 'Phụ cấp ưu đãi ngành', 0, 'Ưu đãi ngành', 'Phụ cấp</br>ưu đãi</br>ngành', '2', 'heso,vuotkhung,pccv', NULL, NULL, NULL),
(4, 'pcth', 'Phụ cấp thu hút', 0, 'Thu hút', 'Phụ cấp</br>thu hút', '2', 'heso,vuotkhung,pccv', NULL, NULL, NULL),
(5, 'pcthni', 'Phụ cấp công tác lâu năm', 0, 'Lâu năm', 'Phụ cấp công tác lâu năm', '2', 'heso,vuotkhung,pccv', NULL, NULL, NULL),
(6, 'pccovu', 'Phụ cấp công vụ', 0, 'Công vụ', 'Phụ cấp</br>công vụ', '2', 'heso,vuotkhung,pccv', NULL, NULL, NULL),
(7, 'pcdang', 'Phụ cấp công tác Đảng', 0, 'Công tác Đảng', 'Phụ cấp</br>công tác</br>Đảng', '0', '', NULL, NULL, NULL),
(8, 'pctnn', 'Phụ cấp  thâm niên nghề', 1, 'Thâm niên nghề', 'Phụ cấp</br>thâm niên</br>nghề', '2', 'heso,vuotkhung,pccv', NULL, NULL, NULL),
(9, 'pcct', 'Phụ cấp ghép lớp', 0, 'Ghép lớp', 'Phụ cấp</br>ghép lớp', '0', '', NULL, NULL, NULL),
(10, 'pctn', 'Phụ cấp trách nhiệm', 0, 'Trách nhiệm', 'Phụ cấp</br>trách nhiệm', '0', '', NULL, NULL, NULL),
(11, 'pckn', 'Phụ cấp kiêm nhiệm', 0, 'Kiêm nhiệm', 'Phụ cấp kiêm nhiệm', '2', 'heso,vuotkhung,pccv', NULL, NULL, NULL),
(12, 'pclt', 'Phụ cấp phân loại xã', 0, 'Phân loại xã', 'Phụ cấp phân loại xã', '2', 'heso,vuotkhung,pccv', NULL, NULL, NULL),
(13, 'pcdd', 'Phụ cấp đắt đỏ', 0, 'Đắt đỏ', 'Phụ cấp</br>đắt đỏ', '0', '', NULL, NULL, NULL),
(14, 'pcdbqh', 'Phụ cấp đại biểu HĐND', 0, 'Đại biểu HĐND', 'Phụ cấp</br>đại biểu</br>HĐND', '0', '', NULL, NULL, NULL),
(15, 'pcvk', 'Phụ cấp cấp ủy viên', 0, 'Cấp ủy viên', 'Phụ cấp</br>cấp ủy</br>viên', '0', '', NULL, NULL, NULL),
(16, 'pcbdhdcu', 'Phụ cấp bồi dưỡng HĐCU', 0, 'Bồi dưỡng HĐCU', 'Phụ cấp</br>bồi dưỡng</br>HĐCU', '0', '', NULL, NULL, NULL),
(17, 'pcdbn', 'Phụ cấp đặc biệt (đặc thù)', 0, 'Đặc biệt (đặc thù)', 'Phụ cấp</br>đặc biệt</br>(đặc thù)', '0', '', NULL, NULL, NULL),
(18, 'pcld', 'Phụ cấp lưu động', 0, 'Lưu động', 'Phụ cấp</br>lưu động', '0', '', NULL, NULL, NULL),
(19, 'pcdh', 'Phụ cấp độc hại', 0, 'Độc hại', 'Phụ cấp</br>độc hại', '0', '', NULL, NULL, NULL),
(20, 'pck', 'Phụ cấp khác', 0, 'Phụ cấp khác', 'Phụ cấp</br>khác', '0', '', NULL, NULL, NULL),
(21, 'heso', 'Lương hệ số', 1, 'Hệ số lương', 'Hệ số', '0', '', NULL, NULL, NULL),
(22, 'vuotkhung', 'Phụ cấp thâm niên vượt khung', 1, 'Thâm niên vượt khung', 'Phụ cấp thâm niên vượt khung', '2', 'heso,pccv', NULL, NULL, NULL),
(23, 'hesott', 'Hệ số lương truy lĩnh', 1, 'Hệ số truy lĩnh', 'Hệ số truy lĩnh', '0', '', NULL, NULL, NULL),
(24, 'hesopc', 'Hệ số phụ cấp', 0, 'Hệ số phụ cấp', 'Hệ số phụ cấp', '0', '', NULL, NULL, NULL),
(25, 'pckct', 'Phụ cấp bằng cấp (không chuyên trách)', NULL, 'Bằng cấp', 'Phụ cấp bằng cấp', '0', '', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `dmphucap_donvi`
--

CREATE TABLE `dmphucap_donvi` (
  `id` int(10) UNSIGNED NOT NULL,
  `madv` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `mapc` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `tenpc` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `baohiem` tinyint(1) DEFAULT NULL,
  `form` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `report` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `phanloai` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `congthuc` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `ghichu` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `dmthongtuquyetdinh`
--

CREATE TABLE `dmthongtuquyetdinh` (
  `id` int(10) UNSIGNED NOT NULL,
  `sohieu` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `tenttqd` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `donvibanhanh` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `ngayapdung` date DEFAULT NULL,
  `muccu` double NOT NULL DEFAULT '0',
  `mucapdung` double NOT NULL DEFAULT '0',
  `ghichu` text COLLATE utf8_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `dmthongtuquyetdinh`
--

INSERT INTO `dmthongtuquyetdinh` (`id`, `sohieu`, `tenttqd`, `donvibanhanh`, `ngayapdung`, `muccu`, `mucapdung`, `ghichu`, `created_at`, `updated_at`) VALUES
(1, 'TT67_2017', 'Thông tư 67/2017/TT-BTC', 'Bộ tài chính', '2017-07-01', 1210000, 1300000, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `dmtieumuc_default`
--

CREATE TABLE `dmtieumuc_default` (
  `id` int(10) UNSIGNED NOT NULL,
  `muc` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  `tieumuc` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `noidung` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `sunghiep` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `macongtac` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `mapc` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `ghichu` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `dmtieumuc_default`
--

INSERT INTO `dmtieumuc_default` (`id`, `muc`, `tieumuc`, `noidung`, `sunghiep`, `macongtac`, `mapc`, `ghichu`, `created_at`, `updated_at`) VALUES
(1, '6000', '6001', 'Lương theo ngạch, bậc', 'ALL', 'ALL', 'heso', NULL, NULL, NULL),
(2, '6000', '6003', 'Lương hợp đồng theo chế độ', 'ALL', 'HOPDONG', 'heso', NULL, NULL, NULL),
(3, '6000', '6049', 'Lương khác', 'null', 'null', 'null', NULL, NULL, NULL),
(4, '6050', '6051', 'Tiền công trả cho vị trí lao động thường xuyên theo hợp đồng', 'null', 'null', 'null', NULL, NULL, NULL),
(5, '6050', '6099', 'Tiền công khác', 'null', 'null', 'null', NULL, NULL, NULL),
(6, '6100', '6101', 'Phụ cấp chức vụ', 'ALL', 'ALL', 'pccv', NULL, NULL, NULL),
(7, '6100', '6102', 'Phụ cấp khu vực', 'ALL', 'ALL', 'pckv', NULL, NULL, NULL),
(8, '6100', '6103', 'Phụ cấp thu hút', 'ALL', 'ALL', 'pcth', NULL, NULL, NULL),
(9, '6100', '6105', 'Phụ cấp làm đêm; làm thêm giờ', 'null', 'null', 'null', NULL, NULL, NULL),
(10, '6100', '6107', 'Phụ cấp nặng nhọc, độc hại, nguy hiểm', 'ALL', 'ALL', 'pcdh', NULL, NULL, NULL),
(11, '6100', '6111', 'Hoạt động phí đại biểu Quốc hội, đại biểu Hội đồng', 'ALL', 'ALL', 'pcdbqh', NULL, NULL, NULL),
(12, '6100', '6112', 'Phụ cấp ưu đãi nghề', 'ALL', 'ALL', 'pcudn', NULL, NULL, NULL),
(13, '6100', '6113', 'Phụ cấp trách nhiệm theo nghề, theo công việc', 'ALL', 'ALL', 'pctn', NULL, NULL, NULL),
(14, '6100', '6114', 'Phụ cấp trực', 'null', 'null', 'null', NULL, NULL, NULL),
(15, '6100', '6115', 'Phụ cấp thâm niên vượt khung; phụ cấp thâm niên nghề', 'ALL', 'ALL', 'pctnn', NULL, NULL, NULL),
(16, '6100', '6116', 'Phụ cấp đặc biệt khác của ngành', 'ALL', 'ALL', 'pcdbn', NULL, NULL, NULL),
(17, '6100', '6121', 'Phụ cấp công tác lâu năm ở vùng có điều kiện kinh tế - xã hội đặc biệt khó khăn', 'ALL', 'ALL', 'pcthni', NULL, NULL, NULL),
(18, '6100', '6122', 'Phụ cấp theo loại xã', 'ALL', 'ALL', 'pclt', NULL, NULL, NULL),
(19, '6100', '6123', 'Phụ cấp công tác Đảng, Đoàn thể chính trị - xã hội', 'ALL', 'ALL', 'pcdang', NULL, NULL, NULL),
(20, '6100', '6124', 'Phụ cấp công vụ', 'ALL', 'ALL', 'pccovu', NULL, NULL, NULL),
(21, '6100', '6149', 'Phụ cấp khác', 'ALL', 'ALL', 'pck', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `dsnangluong`
--

CREATE TABLE `dsnangluong` (
  `id` int(10) UNSIGNED NOT NULL,
  `manl` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `loaids` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `soqd` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `ngayqd` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `nguoiky` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `coquanqd` varchar(150) COLLATE utf8_unicode_ci DEFAULT NULL,
  `noidung` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `ngayxet` date DEFAULT NULL,
  `kemtheo` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `trangthai` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `madv` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `dsnangluong_chitiet`
--

CREATE TABLE `dsnangluong_chitiet` (
  `id` int(10) UNSIGNED NOT NULL,
  `manl` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `macanbo` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `phanloai` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `msngbac` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `ngaytu` date DEFAULT NULL,
  `ngayden` date DEFAULT NULL,
  `bac` int(11) NOT NULL DEFAULT '1',
  `heso` double NOT NULL DEFAULT '0',
  `vuotkhung` double NOT NULL DEFAULT '0',
  `pthuong` double NOT NULL DEFAULT '100',
  `hesott` double NOT NULL DEFAULT '0',
  `pcct` double NOT NULL DEFAULT '0',
  `pckct` double NOT NULL DEFAULT '0',
  `pck` double NOT NULL DEFAULT '0',
  `pccv` double NOT NULL DEFAULT '0',
  `pckv` double NOT NULL DEFAULT '0',
  `pcth` double NOT NULL DEFAULT '0',
  `pcdd` double NOT NULL DEFAULT '0',
  `pcdh` double NOT NULL DEFAULT '0',
  `pcld` double NOT NULL DEFAULT '0',
  `pcdbqh` double NOT NULL DEFAULT '0',
  `pcudn` double NOT NULL DEFAULT '0',
  `pctn` double NOT NULL DEFAULT '0',
  `pctnn` double NOT NULL DEFAULT '0',
  `pcdbn` double NOT NULL DEFAULT '0',
  `pcvk` double NOT NULL DEFAULT '0',
  `pckn` double NOT NULL DEFAULT '0',
  `pcdang` double NOT NULL DEFAULT '0',
  `pccovu` double NOT NULL DEFAULT '0',
  `pclt` double NOT NULL DEFAULT '0',
  `pcd` double NOT NULL DEFAULT '0',
  `pctr` double NOT NULL DEFAULT '0',
  `pctnvk` double NOT NULL DEFAULT '0',
  `pcbdhdcu` double NOT NULL DEFAULT '0',
  `pcthni` double NOT NULL DEFAULT '0',
  `ghichu` text COLLATE utf8_unicode_ci,
  `truylinhtungay` date DEFAULT NULL,
  `truylinhdenngay` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `dsthuyenchuyen`
--

CREATE TABLE `dsthuyenchuyen` (
  `id` int(10) UNSIGNED NOT NULL,
  `mads` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `soqd` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `ngayqd` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `nguoiky` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `coquanqd` varchar(150) COLLATE utf8_unicode_ci DEFAULT NULL,
  `ngaytu` date DEFAULT NULL,
  `ngayden` date DEFAULT NULL,
  `phanloai` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `noidung` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `ghichu` text COLLATE utf8_unicode_ci,
  `madv` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `dinhkem` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `dsthuyenchuyen_chitiet`
--

CREATE TABLE `dsthuyenchuyen_chitiet` (
  `id` int(10) UNSIGNED NOT NULL,
  `mads` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `macanbo` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `noidung` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `ghichu` text COLLATE utf8_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `dutoanluong`
--

CREATE TABLE `dutoanluong` (
  `id` int(10) UNSIGNED NOT NULL,
  `masodv` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `masok` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `masoh` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `masot` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `namns` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `luongnb_dt` double NOT NULL DEFAULT '0',
  `luonghs_dt` double NOT NULL DEFAULT '0',
  `luongbh_dt` double NOT NULL DEFAULT '0',
  `ghichu` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `madv` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `macqcq` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `madvbc` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `trangthai` varchar(50) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'CHUAGUI',
  `ngayguidv` date DEFAULT NULL,
  `nguoiguidv` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `ngayguih` date DEFAULT NULL,
  `nguoiguih` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `lydo` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `dutoanluong`
--

INSERT INTO `dutoanluong` (`id`, `masodv`, `masok`, `masoh`, `masot`, `namns`, `luongnb_dt`, `luonghs_dt`, `luongbh_dt`, `ghichu`, `madv`, `macqcq`, `madvbc`, `trangthai`, `ngayguidv`, `nguoiguidv`, `ngayguih`, `nguoiguih`, `lydo`, `created_at`, `updated_at`) VALUES
(1, '1512393993_1533713939', NULL, NULL, NULL, '2019', 36504000, 9360000, 9678240, NULL, '1512393993', NULL, NULL, 'CHUAGUI', '0000-00-00', NULL, '0000-00-00', NULL, NULL, NULL, NULL),
(2, '1512391746_1533715097', NULL, NULL, NULL, '2019', 36504000, 9360000, 9678240, NULL, '1512391746', NULL, NULL, 'CHUAGUI', '0000-00-00', NULL, '0000-00-00', NULL, NULL, NULL, NULL),
(3, '1534385336_1534403273', NULL, '1534403276', NULL, '2019', 29016000, 18720000, 9018360, NULL, '1534385336', '1534385271', '1534385083', 'DAGUI', '2018-08-16', 'Huyện a', NULL, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `dutoanluong_chitiet`
--

CREATE TABLE `dutoanluong_chitiet` (
  `id` int(10) UNSIGNED NOT NULL,
  `masodv` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `masok` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `masoh` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `masot` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `canbo_congtac` double NOT NULL DEFAULT '0',
  `canbo_dutoan` double NOT NULL DEFAULT '0',
  `macongtac` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `luongnb` double NOT NULL DEFAULT '0',
  `luonghs` double NOT NULL DEFAULT '0',
  `luongbh` double NOT NULL DEFAULT '0',
  `luongnb_dt` double NOT NULL DEFAULT '0',
  `luonghs_dt` double NOT NULL DEFAULT '0',
  `luongbh_dt` double NOT NULL DEFAULT '0',
  `ghichu` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `dutoanluong_chitiet`
--

INSERT INTO `dutoanluong_chitiet` (`id`, `masodv`, `masok`, `masoh`, `masot`, `canbo_congtac`, `canbo_dutoan`, `macongtac`, `luongnb`, `luonghs`, `luongbh`, `luongnb_dt`, `luonghs_dt`, `luongbh_dt`, `ghichu`, `created_at`, `updated_at`) VALUES
(1, '1512393993_1533713939', NULL, NULL, NULL, 1, 1, 'BIENCHE', 36504000, 9360000, 9678240, 36504000, 9360000, 9678240, NULL, NULL, NULL),
(2, '1512391746_1533715097', NULL, NULL, NULL, 1, 1, 'BIENCHE', 36504000, 9360000, 9678240, 36504000, 9360000, 9678240, NULL, NULL, NULL),
(3, '1534385336_1534403273', NULL, NULL, NULL, 2, 2, 'BIENCHE', 14508000, 9360000, 4509180, 29016000, 18720000, 9018360, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `dutoanluong_huyen`
--

CREATE TABLE `dutoanluong_huyen` (
  `id` int(10) UNSIGNED NOT NULL,
  `masodv` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `namns` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `noidung` text COLLATE utf8_unicode_ci,
  `ngaylap` date DEFAULT NULL,
  `nguoilap` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `ghichu` text COLLATE utf8_unicode_ci,
  `ngaygui` date DEFAULT NULL,
  `nguoigui` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `trangthai` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `lydo` text COLLATE utf8_unicode_ci,
  `madv` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `madvbc` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `macqcq` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `dutoanluong_huyen`
--

INSERT INTO `dutoanluong_huyen` (`id`, `masodv`, `namns`, `noidung`, `ngaylap`, `nguoilap`, `ghichu`, `ngaygui`, `nguoigui`, `trangthai`, `lydo`, `madv`, `madvbc`, `macqcq`, `created_at`, `updated_at`) VALUES
(1, '1534403276', '2019', 'Đơn vị HUYỆN A tổng hợp dữ liệu dự toán lương.', '2018-08-16', 'Huyện a', NULL, NULL, NULL, 'DAGUI', NULL, '1534385336', '1534385083', '1534385271', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `dutoanluong_khoi`
--

CREATE TABLE `dutoanluong_khoi` (
  `id` int(10) UNSIGNED NOT NULL,
  `masodv` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `namns` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `noidung` text COLLATE utf8_unicode_ci,
  `ngaylap` date DEFAULT NULL,
  `nguoilap` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `ghichu` text COLLATE utf8_unicode_ci,
  `ngaygui` date DEFAULT NULL,
  `nguoigui` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `trangthai` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `lydo` text COLLATE utf8_unicode_ci,
  `madv` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `madvbc` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `macqcq` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `dutoanluong_tinh`
--

CREATE TABLE `dutoanluong_tinh` (
  `id` int(10) UNSIGNED NOT NULL,
  `masodv` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `namns` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `noidung` text COLLATE utf8_unicode_ci,
  `ngaylap` date DEFAULT NULL,
  `nguoilap` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `ghichu` text COLLATE utf8_unicode_ci,
  `ngaygui` date DEFAULT NULL,
  `nguoigui` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `trangthai` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `lydo` text COLLATE utf8_unicode_ci,
  `madv` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `madvbc` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `macqcq` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `general_configs`
--

CREATE TABLE `general_configs` (
  `id` int(10) UNSIGNED NOT NULL,
  `tuoinu` int(11) NOT NULL DEFAULT '0',
  `tuoinam` int(11) NOT NULL DEFAULT '0',
  `tinh` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL,
  `huyen` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL,
  `luongcb` double NOT NULL DEFAULT '0',
  `bhxh` double NOT NULL DEFAULT '0',
  `bhyt` double NOT NULL DEFAULT '0',
  `bhtn` double NOT NULL DEFAULT '0',
  `kpcd` double NOT NULL DEFAULT '0',
  `stbhxh` double NOT NULL DEFAULT '0',
  `stbhyt` double NOT NULL DEFAULT '0',
  `stbhtn` double NOT NULL DEFAULT '0',
  `stkpcd` double NOT NULL DEFAULT '0',
  `bhxh_dv` double NOT NULL DEFAULT '0',
  `bhyt_dv` double NOT NULL DEFAULT '0',
  `bhtn_dv` double NOT NULL DEFAULT '0',
  `kpcd_dv` double NOT NULL DEFAULT '0',
  `stbhxh_dv` double NOT NULL DEFAULT '0',
  `stbhyt_dv` double NOT NULL DEFAULT '0',
  `stbhtn_dv` double NOT NULL DEFAULT '0',
  `stkpcd_dv` double NOT NULL DEFAULT '0',
  `tg_hetts` double NOT NULL DEFAULT '0',
  `tg_xetnl` double NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `general_configs`
--

INSERT INTO `general_configs` (`id`, `tuoinu`, `tuoinam`, `tinh`, `huyen`, `luongcb`, `bhxh`, `bhyt`, `bhtn`, `kpcd`, `stbhxh`, `stbhyt`, `stbhtn`, `stkpcd`, `bhxh_dv`, `bhyt_dv`, `bhtn_dv`, `kpcd_dv`, `stbhxh_dv`, `stbhyt_dv`, `stbhtn_dv`, `stkpcd_dv`, `tg_hetts`, `tg_xetnl`, `created_at`, `updated_at`) VALUES
(1, 55, 60, NULL, NULL, 1300000, 10.5, 2, 1, 1, 0, 0, 0, 0, 18, 3, 1, 2, 0, 0, 0, 0, 0, 0, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `hosocanbo`
--

CREATE TABLE `hosocanbo` (
  `id` int(10) UNSIGNED NOT NULL,
  `stt` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  `mapb` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `macvcq` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `macvd` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `macanbo` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `anh` varchar(150) COLLATE utf8_unicode_ci DEFAULT NULL,
  `macongchuc` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `sunghiep` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `tencanbo` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `tenkhac` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `ngaysinh` date DEFAULT NULL,
  `gioitinh` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  `dantoc` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `tongiao` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `hktt` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `noio` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `ngaytd` date DEFAULT NULL,
  `lvtd` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `cqtd` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `ngaybn` date DEFAULT NULL,
  `ngayvao` date DEFAULT NULL,
  `cvcn` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `lvhd` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `nguontd` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `httd` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `ngaybc` date DEFAULT NULL,
  `tdgdpt` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `tdcm` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `chuyennganh` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `noidt` varchar(150) COLLATE utf8_unicode_ci DEFAULT NULL,
  `hinhthuc` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `khoadt` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `llct` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `qlnhanuoc` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `ngoaingu` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL,
  `trinhdonn` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL,
  `trinhdoth` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL,
  `ngayvd` date DEFAULT NULL,
  `ngayvdct` date DEFAULT NULL,
  `noikn` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `stct` varchar(150) COLLATE utf8_unicode_ci DEFAULT NULL,
  `ttsk` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `chieucao` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `cannang` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `nhommau` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `socmnd` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `ngaycap` date DEFAULT NULL,
  `noicap` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `soBHXH` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL,
  `ngayBHXH` date DEFAULT NULL,
  `sodt` varchar(15) COLLATE utf8_unicode_ci DEFAULT NULL,
  `email` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `sotk` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `tennganhang` varchar(150) COLLATE utf8_unicode_ci DEFAULT NULL,
  `tthn` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `bhtn` tinyint(1) NOT NULL DEFAULT '1',
  `madv` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `msngbac` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `ngaytu` date DEFAULT NULL,
  `ngayden` date DEFAULT NULL,
  `bac` int(11) NOT NULL DEFAULT '1',
  `heso` double NOT NULL DEFAULT '0',
  `hesopc` double NOT NULL DEFAULT '0',
  `vuotkhung` double NOT NULL DEFAULT '0',
  `pthuong` double NOT NULL DEFAULT '100',
  `pcct` double NOT NULL DEFAULT '0',
  `pckct` double NOT NULL DEFAULT '0',
  `pck` double NOT NULL DEFAULT '0',
  `pccv` double NOT NULL DEFAULT '0',
  `pckv` double NOT NULL DEFAULT '0',
  `pcth` double NOT NULL DEFAULT '0',
  `pcdd` double NOT NULL DEFAULT '0',
  `pcdh` double NOT NULL DEFAULT '0',
  `pcld` double NOT NULL DEFAULT '0',
  `pcdbqh` double NOT NULL DEFAULT '0',
  `pcudn` double NOT NULL DEFAULT '0',
  `pctn` double NOT NULL DEFAULT '0',
  `pctnn` double NOT NULL DEFAULT '0',
  `pcdbn` double NOT NULL DEFAULT '0',
  `pcvk` double NOT NULL DEFAULT '0',
  `pckn` double NOT NULL DEFAULT '0',
  `pcdang` double NOT NULL DEFAULT '0',
  `pccovu` double NOT NULL DEFAULT '0',
  `pclt` double NOT NULL DEFAULT '0',
  `pcd` double NOT NULL DEFAULT '0',
  `pctr` double NOT NULL DEFAULT '0',
  `pctnvk` double NOT NULL DEFAULT '0',
  `pcbdhdcu` double NOT NULL DEFAULT '0',
  `pcthni` double NOT NULL DEFAULT '0',
  `truylinhtungay` date DEFAULT NULL,
  `truylinhdenngay` date DEFAULT NULL,
  `hesott` double NOT NULL DEFAULT '0',
  `mact` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `theodoi` varchar(5) COLLATE utf8_unicode_ci DEFAULT '1',
  `sodinhdanhcanhan` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `macvcqkn` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `ngaybonhiemlandau` date DEFAULT NULL,
  `ngaybonhiemlai` date DEFAULT NULL,
  `nhiemky` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `capchuyenden` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `macq` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `hosocanbo`
--

INSERT INTO `hosocanbo` (`id`, `stt`, `mapb`, `macvcq`, `macvd`, `macanbo`, `anh`, `macongchuc`, `sunghiep`, `tencanbo`, `tenkhac`, `ngaysinh`, `gioitinh`, `dantoc`, `tongiao`, `hktt`, `noio`, `ngaytd`, `lvtd`, `cqtd`, `ngaybn`, `ngayvao`, `cvcn`, `lvhd`, `nguontd`, `httd`, `ngaybc`, `tdgdpt`, `tdcm`, `chuyennganh`, `noidt`, `hinhthuc`, `khoadt`, `llct`, `qlnhanuoc`, `ngoaingu`, `trinhdonn`, `trinhdoth`, `ngayvd`, `ngayvdct`, `noikn`, `stct`, `ttsk`, `chieucao`, `cannang`, `nhommau`, `socmnd`, `ngaycap`, `noicap`, `soBHXH`, `ngayBHXH`, `sodt`, `email`, `sotk`, `tennganhang`, `tthn`, `bhtn`, `madv`, `msngbac`, `ngaytu`, `ngayden`, `bac`, `heso`, `hesopc`, `vuotkhung`, `pthuong`, `pcct`, `pckct`, `pck`, `pccv`, `pckv`, `pcth`, `pcdd`, `pcdh`, `pcld`, `pcdbqh`, `pcudn`, `pctn`, `pctnn`, `pcdbn`, `pcvk`, `pckn`, `pcdang`, `pccovu`, `pclt`, `pcd`, `pctr`, `pctnvk`, `pcbdhdcu`, `pcthni`, `truylinhtungay`, `truylinhdenngay`, `hesott`, `mact`, `theodoi`, `sodinhdanhcanhan`, `macvcqkn`, `ngaybonhiemlandau`, `ngaybonhiemlai`, `nhiemky`, `capchuyenden`, `macq`, `created_at`, `updated_at`) VALUES
(1, '1', '', '_1507601256', NULL, '1512391746_1533713440', '', '', 'Công chức', 'ab', NULL, NULL, 'Nam', 'Kinh', 'Không', NULL, NULL, NULL, 'abc', NULL, NULL, NULL, NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '123456', NULL, NULL, 1, '1512391746', '09.068', NULL, NULL, 1, 2.34, 0, 0, 100, 0, 0, 0, 0.3, 0, 0, 0, 0, 0, 0.3, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, 0, '1506672780', '1', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(2, '1', '', '_1507601256', NULL, '1512393993_1533713682', '', '', 'Công chức', 'nguyen c', NULL, NULL, 'Nam', 'Kinh', 'Không', NULL, NULL, NULL, 'sss', NULL, NULL, NULL, NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '464563', NULL, NULL, 1, '1512393993', '09.068', NULL, NULL, 1, 2.34, 0, 0, 100, 0, 0, 0, 0.3, 0, 0, 0, 0, 0, 0.3, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, 0, '1506672780', '1', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(3, '1', '', '_1507601256', NULL, '1534385336_1534385500', '', '', 'Công chức', 'Nguyễn a', NULL, '1979-01-01', 'Nam', 'Kinh', 'Không', NULL, NULL, NULL, 'sd', NULL, NULL, NULL, NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '123345', NULL, NULL, 1, '1534385336', '15a.201', '2017-01-01', '2019-01-01', 1, 0, 0, 0, 100, 0, 0, 0, 0.3, 0.3, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, 0, '1506672780', '1', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(4, '2', '', '_1507601256', NULL, '1534385336_1534385597', '', '', 'Công chức', 'Nguyễn b', NULL, '1980-01-01', 'Nam', 'Kinh', 'Không', NULL, NULL, NULL, '', NULL, NULL, NULL, NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', NULL, NULL, 1, '1534385336', 'V.07.02.06', '2018-01-01', '2019-01-01', 1, 1.86, 0, 0, 100, 0, 0, 0, 0.3, 0.3, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, 0, '1506672780', '1', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `hosocanbo_kiemnhiem`
--

CREATE TABLE `hosocanbo_kiemnhiem` (
  `id` int(10) UNSIGNED NOT NULL,
  `phanloai` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `mapb` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `macvcq` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `macanbo` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `heso` double NOT NULL DEFAULT '0',
  `hesopc` double NOT NULL DEFAULT '0',
  `pcdbn` double NOT NULL DEFAULT '0',
  `pctn` double NOT NULL DEFAULT '0',
  `pctnn` double NOT NULL DEFAULT '0',
  `pck` double NOT NULL DEFAULT '0',
  `msngbac` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `ngaytu` date DEFAULT NULL,
  `ngayden` date DEFAULT NULL,
  `bac` int(11) NOT NULL DEFAULT '1',
  `hesott` double NOT NULL DEFAULT '0',
  `vuotkhung` double NOT NULL DEFAULT '0',
  `pthuong` double NOT NULL DEFAULT '100',
  `pcct` double NOT NULL DEFAULT '0',
  `pckct` double NOT NULL DEFAULT '0',
  `pccv` double NOT NULL DEFAULT '0',
  `pckv` double NOT NULL DEFAULT '0',
  `pcth` double NOT NULL DEFAULT '0',
  `pcdd` double NOT NULL DEFAULT '0',
  `pcdh` double NOT NULL DEFAULT '0',
  `pcld` double NOT NULL DEFAULT '0',
  `pcdbqh` double NOT NULL DEFAULT '0',
  `pcudn` double NOT NULL DEFAULT '0',
  `pcvk` double NOT NULL DEFAULT '0',
  `pckn` double NOT NULL DEFAULT '0',
  `pcdang` double NOT NULL DEFAULT '0',
  `pccovu` double NOT NULL DEFAULT '0',
  `pclt` double NOT NULL DEFAULT '0',
  `pcd` double NOT NULL DEFAULT '0',
  `pctr` double NOT NULL DEFAULT '0',
  `pctnvk` double NOT NULL DEFAULT '0',
  `pcbdhdcu` double NOT NULL DEFAULT '0',
  `pcthni` double NOT NULL DEFAULT '0',
  `mact` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `madv` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `hosocanbo_kiemnhiem_temp`
--

CREATE TABLE `hosocanbo_kiemnhiem_temp` (
  `id` int(10) UNSIGNED NOT NULL,
  `phanloai` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `mapb` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `macvcq` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `macanbo` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `heso` double NOT NULL DEFAULT '0',
  `hesopc` double NOT NULL DEFAULT '0',
  `pcdbn` double NOT NULL DEFAULT '0',
  `pctn` double NOT NULL DEFAULT '0',
  `pctnn` double NOT NULL DEFAULT '0',
  `pck` double NOT NULL DEFAULT '0',
  `msngbac` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `ngaytu` date DEFAULT NULL,
  `ngayden` date DEFAULT NULL,
  `bac` int(11) NOT NULL DEFAULT '1',
  `hesott` double NOT NULL DEFAULT '0',
  `vuotkhung` double NOT NULL DEFAULT '0',
  `pthuong` double NOT NULL DEFAULT '100',
  `pcct` double NOT NULL DEFAULT '0',
  `pckct` double NOT NULL DEFAULT '0',
  `pccv` double NOT NULL DEFAULT '0',
  `pckv` double NOT NULL DEFAULT '0',
  `pcth` double NOT NULL DEFAULT '0',
  `pcdd` double NOT NULL DEFAULT '0',
  `pcdh` double NOT NULL DEFAULT '0',
  `pcld` double NOT NULL DEFAULT '0',
  `pcdbqh` double NOT NULL DEFAULT '0',
  `pcudn` double NOT NULL DEFAULT '0',
  `pcvk` double NOT NULL DEFAULT '0',
  `pckn` double NOT NULL DEFAULT '0',
  `pcdang` double NOT NULL DEFAULT '0',
  `pccovu` double NOT NULL DEFAULT '0',
  `pclt` double NOT NULL DEFAULT '0',
  `pcd` double NOT NULL DEFAULT '0',
  `pctr` double NOT NULL DEFAULT '0',
  `pctnvk` double NOT NULL DEFAULT '0',
  `pcbdhdcu` double NOT NULL DEFAULT '0',
  `pcthni` double NOT NULL DEFAULT '0',
  `mact` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `madv` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `hosoluanchuyen`
--

CREATE TABLE `hosoluanchuyen` (
  `id` int(10) UNSIGNED NOT NULL,
  `macanbo` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `phanloai` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `ngaylc` date DEFAULT NULL,
  `mapb` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `macvcq` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `soqd` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `ngayqd` date DEFAULT NULL,
  `nguoiky` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `madv` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `hosoluong`
--

CREATE TABLE `hosoluong` (
  `id` int(10) UNSIGNED NOT NULL,
  `manl` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `macanbo` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `phanloai` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `msngbac` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `ngaytu` date DEFAULT NULL,
  `ngayden` date DEFAULT NULL,
  `bac` double NOT NULL DEFAULT '1',
  `heso` double NOT NULL DEFAULT '0',
  `vuotkhung` double NOT NULL DEFAULT '0',
  `pthuong` double NOT NULL DEFAULT '100',
  `ketqua` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `madv` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `hosophucap`
--

CREATE TABLE `hosophucap` (
  `id` int(10) UNSIGNED NOT NULL,
  `mapc` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `macanbo` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `ngaytu` date DEFAULT NULL,
  `ngayden` date DEFAULT NULL,
  `hesopc` double NOT NULL DEFAULT '0',
  `ghichupc` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `madv` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `hosotamngungtheodoi`
--

CREATE TABLE `hosotamngungtheodoi` (
  `id` int(10) UNSIGNED NOT NULL,
  `maso` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `macanbo` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `tencanbo` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `soqd` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `ngayqd` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `nguoiky` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `coquanqd` varchar(150) COLLATE utf8_unicode_ci DEFAULT NULL,
  `maphanloai` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `noidung` text COLLATE utf8_unicode_ci,
  `ngaytu` date DEFAULT NULL,
  `ngayden` date DEFAULT NULL,
  `madv` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `hosotruylinh`
--

CREATE TABLE `hosotruylinh` (
  `id` int(10) UNSIGNED NOT NULL,
  `maso` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `stt` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  `macanbo` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `tencanbo` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `soqd` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `ngayqd` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `nguoiky` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `coquanqd` varchar(150) COLLATE utf8_unicode_ci DEFAULT NULL,
  `mabl` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `ngaytu` date DEFAULT NULL,
  `ngayden` date DEFAULT NULL,
  `madv` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `msngbac` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `hesott` double NOT NULL DEFAULT '0',
  `heso` double NOT NULL DEFAULT '0',
  `vuotkhung` double NOT NULL DEFAULT '0',
  `pcct` double NOT NULL DEFAULT '0',
  `pckct` double NOT NULL DEFAULT '0',
  `pck` double NOT NULL DEFAULT '0',
  `pccv` double NOT NULL DEFAULT '0',
  `pckv` double NOT NULL DEFAULT '0',
  `pcth` double NOT NULL DEFAULT '0',
  `pcdd` double NOT NULL DEFAULT '0',
  `pcdh` double NOT NULL DEFAULT '0',
  `pcld` double NOT NULL DEFAULT '0',
  `pcdbqh` double NOT NULL DEFAULT '0',
  `pcudn` double NOT NULL DEFAULT '0',
  `pctn` double NOT NULL DEFAULT '0',
  `pctnn` double NOT NULL DEFAULT '0',
  `pcdbn` double NOT NULL DEFAULT '0',
  `pcvk` double NOT NULL DEFAULT '0',
  `pckn` double NOT NULL DEFAULT '0',
  `pcdang` double NOT NULL DEFAULT '0',
  `pccovu` double NOT NULL DEFAULT '0',
  `pclt` double NOT NULL DEFAULT '0',
  `pcd` double NOT NULL DEFAULT '0',
  `pctr` double NOT NULL DEFAULT '0',
  `pctnvk` double NOT NULL DEFAULT '0',
  `pcthni` double NOT NULL DEFAULT '0',
  `pcbdhdcu` double NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2014_10_12_100000_create_password_resets_table', 1),
(3, '2016_06_00_104204_create_ngachbac_table', 1),
(4, '2016_06_10_102423_create_dmchucvucq_table', 1),
(5, '2016_06_10_102650_create_dmchucvucd_table', 1),
(6, '2016_06_10_103018_create_dmphongban_table', 1),
(7, '2016_06_10_103042_create_dmphucap_table', 1),
(8, '2016_06_10_103346_create_hosocanbo_table', 1),
(9, '2016_06_19_101922_create_bangluong_table', 1),
(10, '2016_06_19_102235_create_dsnangluong_table', 1),
(11, '2016_06_19_102728_create_dmdantoc_table', 1),
(12, '2016_06_19_102806_create_dmdonvi_table', 1),
(13, '2016_06_20_101936_create_bangluongct_table', 1),
(14, '2016_06_20_102913_create_dmngachcc_table', 1),
(15, '2016_06_20_102959_create_dmphanloaict_table', 1),
(16, '2016_06_20_103758_create_hosoluanchuyen_table', 1),
(17, '2016_06_20_103816_create_hosoluong_table', 1),
(18, '2016_06_20_103913_create_hosophucap_table', 1),
(19, '2016_06_21_163453_create_general_configs_table', 1),
(20, '2017_05_25_160123_create_dmkhoipb_table', 1),
(21, '2017_06_09_093945_create_dsthuyenchuyen_table', 1),
(22, '2017_06_09_095729_create_thuyenchuyen_chitiet_table', 1),
(23, '2017_06_15_090502_create_dsnangluong_chitiet_table', 1),
(24, '2017_06_15_101305_create_nhomngachluong_table', 1),
(25, '2017_06_15_101339_create_ngachluong_table', 1),
(26, '2017_06_26_145019_create_dmdonvibaocao_table', 1),
(27, '2017_08_25_165446_create_chitieubienche_table', 1),
(28, '2017_08_27_181308_create_dutoanluong_table', 1),
(29, '2017_09_26_151350_create_dmnguonkp_table', 1),
(30, '2017_09_28_155748_create_dmphanloaidonvi_table', 1),
(31, '2017_09_28_155801_create_dmphanloaicongtac_table', 1),
(32, '2017_10_04_161146_create_dmdiabandbkk_table', 1),
(33, '2017_10_04_162010_create_dmdiabandbkk_chitiet_table', 1),
(34, '2017_10_06_150719_create_tonghopluong_donvi_table', 1),
(35, '2017_10_06_150739_create_tonghopluong_donvi_chitiet_table', 1),
(36, '2017_10_06_163923_create_tonghopluong_donvi_diaban_table', 1),
(37, '2017_10_21_105034_create_tonghopluong_khoi_table', 1),
(38, '2017_10_21_105106_create_tonghopluong_khoi_chitiet_table', 1),
(39, '2017_10_21_105121_create_tonghopluong_khoi_diaban_table', 1),
(40, '2017_10_23_102525_create_tonghopluong_huyen_tabale', 1),
(41, '2017_10_23_102601_create_tonghopluong_huyen_chitiet_tabale', 1),
(42, '2017_10_23_102612_create_tonghopluong_huyen_diaban_tabale', 1),
(43, '2017_10_23_140923_create_tonghopluong_tinh_table', 1),
(44, '2017_10_23_140934_create_tonghopluong_tinh_chitiet_table', 1),
(45, '2017_10_23_140946_create_tonghopluong_tinh_diaban_table', 1),
(46, '2017_10_25_091621_create_tonghop_huyen_table', 1),
(47, '2017_10_25_091631_create_tonghop_huyen_chitiet_table', 1),
(48, '2017_10_25_091641_create_tonghop_huyen_diaban_table', 1),
(49, '2017_10_25_091652_create_tonghop_tinh_diaban_table', 1),
(50, '2017_10_25_091701_create_tonghop_tinh_table', 1),
(51, '2017_10_25_091711_create_tonghop_tinh_chitiet_table', 1),
(52, '2017_11_10_094152_create_dmthongtuquyetdinh_table', 1),
(53, '2017_11_10_094212_create_nguonkinhphi_table', 1),
(54, '2017_12_02_100231_create_dmphanloaicongtac_baohiem_table', 1),
(55, '2018_04_20_091411_create_hosotamngungtheodoi_table', 1),
(56, '2018_05_10_101006_hosotruylinh_table', 1),
(57, '2018_06_18_104849_create_dmtieumuc_default_table', 1),
(58, '2018_06_19_142114_create_dmphucap_donvi_table', 1),
(59, '2018_06_28_151343_create_bangluong_phucap_table', 1),
(60, '2018_07_17_100941_create_dutoanluong_chitiet_table', 1),
(61, '2018_08_13_101106_create_nguonkinhphi_khoi_table', 2),
(62, '2018_08_13_101116_create_nguonkinhphi_huyen_table', 2),
(63, '2018_08_13_101122_create_nguonkinhphi_tinh_table', 2),
(64, '2018_08_13_101230_create_dutoanluong_khoi_table', 2),
(65, '2018_08_13_101238_create_dutoanluong_huyen_table', 2),
(66, '2018_08_13_101245_create_dutoanluong_tinh_table', 2),
(67, '2018_08_16_102041_create_hosocanbo_kiemnhiem_table', 2),
(68, '2018_08_16_102051_create_hosocanbo_kiemnhiem_temp_table', 2);

-- --------------------------------------------------------

--
-- Table structure for table `ngachbac`
--

CREATE TABLE `ngachbac` (
  `id` int(10) UNSIGNED NOT NULL,
  `msngbac` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `plnb` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `tennb` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `namnb` int(11) NOT NULL DEFAULT '0',
  `bac` int(11) NOT NULL DEFAULT '1',
  `heso` double NOT NULL DEFAULT '0',
  `ptvk` double NOT NULL DEFAULT '0',
  `vk` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ngachluong`
--

CREATE TABLE `ngachluong` (
  `id` int(10) UNSIGNED NOT NULL,
  `manhom` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `msngbac` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `tenngachluong` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `namnb` int(11) NOT NULL DEFAULT '0',
  `bacnhonhat` int(11) NOT NULL DEFAULT '1',
  `baclonnhat` int(11) NOT NULL DEFAULT '1',
  `bacvuotkhung` int(11) NOT NULL DEFAULT '1',
  `heso` double NOT NULL DEFAULT '0',
  `vuotkhung` double NOT NULL DEFAULT '0',
  `hesochenhlech` double NOT NULL DEFAULT '0',
  `vuotkhungchenhlech` double NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `ngachluong`
--

INSERT INTO `ngachluong` (`id`, `manhom`, `msngbac`, `tenngachluong`, `namnb`, `bacnhonhat`, `baclonnhat`, `bacvuotkhung`, `heso`, `vuotkhung`, `hesochenhlech`, `vuotkhungchenhlech`, `created_at`, `updated_at`) VALUES
(1, 'A31', '01.001', 'Chuyên viên cao cấp', 3, 1, 6, 0, 6.2, 0, 0.36, 0, '2017-10-02 03:34:11', '2017-10-02 03:38:33'),
(2, 'A31', '04.023', 'Thanh tra viên cao cấp', 0, 1, 1, 0, 0, 0, 0, 0, '2017-10-02 03:40:28', '2017-10-02 03:40:28'),
(3, 'A31', '06.041', 'Kiểm toán viên cao cấp', 0, 1, 1, 1, 0, 0, 0, 0, NULL, NULL),
(4, 'A31', '07.044', 'Kiểm soát viên cao cấp ngân hàng', 0, 1, 1, 1, 0, 0, 0, 0, NULL, NULL),
(5, 'A31', '08.049', 'Kiểm tra viên cao cấp hải quan', 0, 1, 1, 1, 0, 0, 0, 0, NULL, NULL),
(6, 'A31', '12.084', 'Thẩm kế viên cao cấp', 0, 1, 1, 1, 0, 0, 0, 0, NULL, NULL),
(7, 'A31', '21.187', 'Kiểm soát viên cao cấp thị trường', 0, 1, 1, 1, 0, 0, 0, 0, NULL, NULL),
(8, 'A31', '23.261', 'Thống kê viên cao cấp', 0, 1, 1, 1, 0, 0, 0, 0, NULL, NULL),
(9, 'A31', '13.280', 'Kiểm soát viên cao cấp chất lượng sản phẩm, hàng hóa', 0, 1, 1, 1, 0, 0, 0, 0, NULL, NULL),
(10, 'A31', '03.299', 'Chấp hành viên cao cấp (thi hành án dân sự)', 0, 1, 1, 1, 0, 0, 0, 0, NULL, NULL),
(11, 'A31', '03.232', 'Thẩm tra viên cao cấp (thi hành án dân sự)', 0, 1, 1, 1, 0, 0, 0, 0, NULL, NULL),
(12, 'A31', '06.036', 'Kiểm tra viên cao cấp thuế', 0, 1, 1, 1, 0, 0, 0, 0, NULL, NULL),
(13, 'A32', '06.029', 'Kế toán viên cao cấp', 0, 1, 1, 1, 0, 0, 0, 0, NULL, NULL),
(14, 'A32', '09.066', 'Kiểm dịch viên cao cấp động - thực vật', 0, 1, 1, 1, 0, 0, 0, 0, NULL, NULL),
(15, 'A21', '01.002', 'Chuyên viên chính', 0, 1, 1, 1, 0, 0, 0, 0, NULL, NULL),
(16, 'A21', '04.024', 'Thanh tra viên chính', 0, 1, 1, 1, 0, 0, 0, 0, NULL, NULL),
(17, 'A21', '06.037', 'Kiểm soát viên chính thuế', 0, 1, 1, 1, 0, 0, 0, 0, NULL, NULL),
(18, 'A21', '06.042', 'Kiểm toán viên chính', 0, 1, 1, 1, 0, 0, 0, 0, NULL, NULL),
(19, 'A21', '07.045', 'Kiểm soát viên chính ngân hàng', 0, 1, 1, 1, 0, 0, 0, 0, NULL, NULL),
(20, 'A21', '08.050', 'Kiểm tra viên chính hải quan', 0, 1, 1, 1, 0, 0, 0, 0, NULL, NULL),
(21, 'A21', '12.085', 'Thẩm kế viên chính', 0, 1, 1, 1, 0, 0, 0, 0, NULL, NULL),
(22, 'A21', '21.188', 'Kiểm soát viên chính thị trường', 0, 1, 1, 1, 0, 0, 0, 0, NULL, NULL),
(23, 'A21', '23.262', 'Thống kê viên chính', 0, 1, 1, 1, 0, 0, 0, 0, NULL, NULL),
(24, 'A21', '13.281', 'Kiểm soát viên chính chất lượng sản phẩm, hàng hóa', 0, 1, 1, 1, 0, 0, 0, 0, NULL, NULL),
(25, 'A21', '03.300', 'Chấp hành viên trung cấp (thi hành án dân sự)', 0, 1, 1, 1, 0, 0, 0, 0, NULL, NULL),
(26, 'A21', '03.231', 'Thẩm tra viên chính (thi hành án dân sự)', 0, 1, 1, 1, 0, 0, 0, 0, NULL, NULL),
(27, 'A21', '06.037', 'Kiểm tra viên chính thuế', 0, 1, 1, 1, 0, 0, 0, 0, NULL, NULL),
(28, 'A21', '10.225', 'Kiểm lâm viên chính', 0, 1, 1, 1, 0, 0, 0, 0, NULL, NULL),
(29, 'A22', '06.030', 'Kế toán viên chính', 0, 1, 1, 1, 0, 0, 0, 0, NULL, NULL),
(30, 'A22', '09.067', 'Kiểm dịch viên chính động - thực vật', 0, 1, 1, 1, 0, 0, 0, 0, NULL, NULL),
(31, 'A22', '11.081', 'Kiểm soát viên chính đê điều (*)', 0, 1, 1, 1, 0, 0, 0, 0, NULL, NULL),
(32, 'A1', '01.003', 'Chuyên viên', 0, 1, 1, 1, 0, 0, 0, 0, NULL, NULL),
(33, 'A1', '03.019', 'Công chứng viên', 0, 1, 1, 1, 0, 0, 0, 0, NULL, NULL),
(34, 'A1', '04.025', 'Thanh tra viên', 0, 1, 1, 1, 0, 0, 0, 0, NULL, NULL),
(35, 'A1', '06.031', 'Kế toán viên', 0, 1, 1, 1, 0, 0, 0, 0, NULL, NULL),
(36, 'A1', '06.038', 'Kiểm soát viên thuế', 0, 1, 1, 1, 0, 0, 0, 0, NULL, NULL),
(37, 'A1', '06.043', 'Kiểm toán viên', 0, 1, 1, 1, 0, 0, 0, 0, NULL, NULL),
(38, 'A1', '07.046', 'Kiểm soát viên ngân hàng', 0, 1, 1, 1, 0, 0, 0, 0, NULL, NULL),
(39, 'A1', '08.051', 'Kiểm tra viên hải quan', 0, 1, 1, 1, 0, 0, 0, 0, NULL, NULL),
(40, 'A1', '09.068', 'Kiểm dịch viên động - thực vật', 0, 1, 1, 1, 0, 0, 0, 0, NULL, NULL),
(41, 'A1', '10.078', 'Kiểm lâm viên', 0, 1, 1, 1, 0, 0, 0, 0, NULL, NULL),
(42, 'A1', '11.082', 'Kiểm soát viên đê điều (*)', 0, 1, 1, 1, 0, 0, 0, 0, NULL, NULL),
(43, 'A1', '12.086', 'Thẩm kế viên', 0, 1, 1, 1, 0, 0, 0, 0, NULL, NULL),
(44, 'A1', '21.189', 'Kiểm soát viên thị trường', 0, 1, 1, 1, 0, 0, 0, 0, NULL, NULL),
(45, 'A1', '23.263', 'Thống kê viên', 0, 1, 1, 1, 0, 0, 0, 0, NULL, NULL),
(46, 'A1', '13.282', 'Kiểm soát viên chất lượng sản phẩm, hàng hóa', 0, 1, 1, 1, 0, 0, 0, 0, NULL, NULL),
(47, 'A1', '19.221', 'Kỹ thuật viên bảo quản', 0, 1, 1, 1, 0, 0, 0, 0, NULL, NULL),
(48, 'A1', '03.301', 'Chấp hành viên sơ cấp (thi hành án dân sự)', 0, 1, 1, 1, 0, 0, 0, 0, NULL, NULL),
(49, 'A1', '03.230', 'Thẩm tra viên (thi hành án dân sự)', 0, 1, 1, 1, 0, 0, 0, 0, NULL, NULL),
(50, 'A1', '03.302', 'Thư ký thi hành án (dân sự)', 0, 1, 1, 1, 0, 0, 0, 0, NULL, NULL),
(51, 'A1', '06.038', 'Kiểm tra viên thuế', 0, 1, 1, 1, 0, 0, 0, 0, NULL, NULL),
(52, 'A0', '17a.170', 'Thư viện viên (cao đẳng)', 0, 1, 1, 1, 0, 0, 0, 0, NULL, NULL),
(53, 'A0', '15a.204', 'Giáo viên tiểu học chính', 0, 1, 1, 1, 0, 0, 0, 0, NULL, NULL),
(54, 'A0', '15c.207', 'Giáo viên THPT chưa đạt chuẩn', 0, 1, 1, 1, 0, 0, 0, 0, NULL, NULL),
(55, 'A0', 'Ao', 'Ngạch mới (Cao đẳng)', 0, 1, 1, 1, 0, 0, 0, 0, NULL, NULL),
(56, 'A0', '15a.206', 'Giáo viên mầm non chính', 0, 1, 1, 1, 0, 0, 0, 0, NULL, NULL),
(57, 'A0', '15c.208', 'Giáo viên THCS chưa đạt chuẩn', 0, 1, 1, 1, 0, 0, 0, 0, NULL, NULL),
(58, 'A0', '15a.202', 'Giáo viên trung học cơ sở', 0, 1, 1, 1, 0, 0, 0, 0, NULL, NULL),
(59, 'B', '01.004', 'Cán sự', 0, 1, 1, 1, 0, 0, 0, 0, NULL, NULL),
(60, 'B', '06.032', 'Kế toán viên trung cấp', 0, 1, 1, 1, 0, 0, 0, 0, NULL, NULL),
(61, 'B', '06.039', 'Kiểm thu viên thuế', 0, 1, 1, 1, 0, 0, 0, 0, NULL, NULL),
(62, 'B', '07.048', 'Thủ kho tiền, vàng bạc, đá quý (ngân hàng) (*)', 0, 1, 1, 1, 0, 0, 0, 0, NULL, NULL),
(63, 'B', '08.052', 'Kiểm tra viên trung cấp hải quan', 0, 1, 1, 1, 0, 0, 0, 0, NULL, NULL),
(64, 'B', '09.069', 'Kỹ thuật viên kiểm dịch động - thực vật', 0, 1, 1, 1, 0, 0, 0, 0, NULL, NULL),
(65, 'B', '10.079', 'Kiểm lâm viên trung cấp', 0, 1, 1, 1, 0, 0, 0, 0, NULL, NULL),
(66, 'B', '11.083', 'Kiểm soát viên trung cấp đê điều (*)', 0, 1, 1, 1, 0, 0, 0, 0, NULL, NULL),
(67, 'B', '19.183', 'Kỹ thuật viên kiểm nghiệm bảo quản', 0, 1, 1, 1, 0, 0, 0, 0, NULL, NULL),
(68, 'B', '21.190', 'Kiểm soát viên trung cấp thị trường', 0, 1, 1, 1, 0, 0, 0, 0, NULL, NULL),
(69, 'B', '23.265', 'Thống kê viên trung cấp', 0, 1, 1, 1, 0, 0, 0, 0, NULL, NULL),
(70, 'B', '13.283', 'Kiểm soát viên trung cấp chất lượng sản phẩm, hàng hóa', 0, 1, 1, 1, 0, 0, 0, 0, NULL, NULL),
(71, 'B', '03.303', 'Thư ký trung cấp thi hành án (dân sự)', 0, 1, 1, 1, 0, 0, 0, 0, NULL, NULL),
(72, 'B', '06.039', 'Kiểm tra viên trung cấp thuế', 0, 1, 1, 1, 0, 0, 0, 0, NULL, NULL),
(73, 'B', '19.222', 'Kỹ thuật viên bảo quản trung cấp', 0, 1, 1, 1, 0, 0, 0, 0, NULL, NULL),
(74, 'B', '19.223', 'Thủ kho bảo quản', 0, 1, 1, 1, 0, 0, 0, 0, NULL, NULL),
(75, 'C1', '06.034', 'Thủ quỹ kho bạc, ngân hàng', 0, 1, 1, 1, 0, 0, 0, 0, NULL, NULL),
(76, 'C1', '07.047', 'Kiểm ngân viên', 0, 1, 1, 1, 0, 0, 0, 0, NULL, NULL),
(77, 'C1', '08.053', 'Nhân viên hải quan', 0, 1, 1, 1, 0, 0, 0, 0, NULL, NULL),
(78, 'C1', '10.080', 'Kiểm lâm viên sơ cấp', 0, 1, 1, 1, 0, 0, 0, 0, NULL, NULL),
(79, 'C1', '19.184', 'Thủ kho bảo quản nhóm I', 0, 1, 1, 1, 0, 0, 0, 0, NULL, NULL),
(80, 'C1', '19.185', 'Thủ kho bảo quản nhóm II', 0, 1, 1, 1, 0, 0, 0, 0, NULL, NULL),
(81, 'C1', '19.186', 'Bảo vệ, tuần tra canh gác', 0, 1, 1, 1, 0, 0, 0, 0, NULL, NULL),
(82, 'C1', '19.224', 'Nhân viên bảo vệ kho dự trữ', 0, 1, 1, 1, 0, 0, 0, 0, NULL, NULL),
(83, 'C2', '06.035', 'Thủ quỹ cơ quan, đơn vị', 0, 1, 1, 1, 0, 0, 0, 0, NULL, NULL),
(84, 'C2', '06.040', 'Nhân viên thuế', 0, 1, 1, 1, 0, 0, 0, 0, NULL, NULL),
(85, 'C3', '06.033', 'Ngạch kế toán viên sơ cấp', 0, 1, 1, 1, 0, 0, 0, 0, NULL, NULL),
(86, 'NVTH', '01.006', 'Nhân viên đánh máy', 0, 1, 1, 1, 0, 0, 0, 0, NULL, NULL),
(87, 'NVTH', '01.010', 'Lái xe cơ quan', 0, 1, 1, 1, 0, 0, 0, 0, NULL, NULL),
(88, 'NVTH', '01.007', 'Nhân viên kỹ thuật', 0, 1, 1, 1, 0, 0, 0, 0, NULL, NULL),
(89, 'NVTH', '01.008', 'Nhân viên văn thư', 0, 1, 1, 1, 0, 0, 0, 0, NULL, NULL),
(90, 'NVTH', '01.009', 'Nhân viên phục vụ', 0, 1, 1, 1, 0, 0, 0, 0, NULL, NULL),
(91, 'NVTH', '01.011', 'Nhân viên bảo vệ', 0, 1, 1, 1, 0, 0, 0, 0, NULL, NULL),
(92, 'NVTH', '01.005', 'Kỹ thuật viên đánh máy', 0, 1, 1, 1, 0, 0, 0, 0, NULL, NULL),
(93, 'CBCT', 'BT', 'Bí thư đảng uỷ', 5, 1, 2, 1, 2.35, 0, 0.5, 0, NULL, NULL),
(94, 'CBCT', 'PBT', 'Phó Bí thư đảng uỷ', 5, 1, 2, 1, 2.15, 0, 0.5, 0, NULL, NULL),
(95, 'CBCT', 'CTHD', 'Chủ tịch Hội đồng nhân dân', 5, 1, 2, 1, 2.15, 0, 0.5, 0, NULL, NULL),
(96, 'CBCT', 'CTUB', 'Chủ tịch Ủy ban nhân dân', 5, 1, 2, 1, 2.15, 0, 0.5, 0, NULL, NULL),
(97, 'CBCT', 'MTTQ', 'Chủ tịch Ủy ban Mặt trận Tổ quốc', 5, 1, 2, 1, 1.95, 0, 0.5, 0, NULL, NULL),
(98, 'CBCT', 'PCTHD', 'Phó Chủ tịch Hội đồng nhân dân', 5, 1, 2, 1, 1.95, 0, 0.5, 0, NULL, NULL),
(99, 'CBCT', 'PCTUB', 'Phó Chủ tịch Ủy ban nhân dân', 5, 1, 2, 1, 1.95, 0, 0.5, 0, NULL, NULL),
(100, 'CBCT', 'BTD', 'Bí thư Đoàn Thanh niên Cộng sản Hồ Chí Minh', 5, 1, 2, 1, 1.75, 0, 0.5, 0, NULL, NULL),
(101, 'CBCT', 'CTPN', 'Chủ tịch Hội Liên hiệp Phụ nữ', 5, 1, 2, 1, 1.75, 0, 0.5, 0, NULL, NULL),
(102, 'CBCT', 'CTND', 'Chủ tịch Hội Nông dân', 5, 1, 2, 1, 1.75, 0, 0.5, 0, NULL, NULL),
(103, 'CBCT', 'CTCCB', 'Chủ tịch Hội Cựu chiến binh', 5, 1, 2, 1, 1.75, 0, 0.5, 0, NULL, NULL),
(104, 'A22', 'V.07.04.10', 'Giáo viên trung học cơ sở hạng I ', 0, 1, 1, 1, 0, 0, 0, 0, NULL, NULL),
(105, 'A1', 'V.07.04.11', 'Giáo viên trung học cơ sở hạng II', 0, 1, 1, 1, 0, 0, 0, 0, NULL, NULL),
(106, 'A0', 'V.07.04.12', 'Giáo viên trung học cơ sở hạng III', 0, 1, 1, 1, 0, 0, 0, 0, NULL, NULL),
(107, 'A21', 'V.07.05.13', 'Giáo viên trung học phổ thông hạng I', 0, 1, 1, 1, 0, 0, 0, 0, NULL, NULL),
(108, 'A22', 'V.07.05.14', 'Giáo viên trung học phổ thông hạng II', 0, 1, 1, 1, 0, 0, 0, 0, NULL, NULL),
(109, 'A1', 'V.07.05.15', 'Giáo viên trung học phổ thông hạng III', 0, 1, 1, 1, 0, 0, 0, 0, NULL, NULL),
(110, 'A1', 'V.07.02.04', 'Giáo viên mầm non hạng II', 0, 1, 1, 1, 0, 0, 0, 0, NULL, NULL),
(112, 'B', 'V.07.02.06', 'Giáo viên mầm non hạng IV', 0, 1, 1, 1, 0, 0, 0, 0, NULL, NULL),
(113, 'A0', '06a.031', 'Kế toán viên cao đẳng', 0, 1, 1, 1, 0, 0, 0, 0, NULL, NULL),
(114, 'C1', '15c.210', 'Giáo viên mầm non chưa đạt chuẩn', 0, 1, 1, 1, 0, 0, 0, 0, NULL, NULL),
(115, 'A1', '15a.205', 'Giáo viên mầm non cao cấp', 0, 1, 1, 1, 0, 0, 0, 0, NULL, NULL),
(116, 'A1', '15a.203', 'Giáo viên tiểu học cao cấp', 0, 1, 1, 1, 0, 0, 0, 0, NULL, NULL),
(117, 'A1', '15a.201', 'Giáo viên Trung học cơ sở chính', 0, 1, 1, 1, 0, 0, 0, 0, NULL, NULL),
(118, 'A1', '15.113', 'Giáo viên trung học', 0, 1, 1, 1, 0, 0, 0, 0, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `nguonkinhphi`
--

CREATE TABLE `nguonkinhphi` (
  `id` int(10) UNSIGNED NOT NULL,
  `masodv` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `masoh` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `masok` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `masot` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `sohieu` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `manguonkp` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `noidung` text COLLATE utf8_unicode_ci,
  `namns` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `linhvuchoatdong` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `nhucau` double NOT NULL DEFAULT '0',
  `luongphucap` double NOT NULL DEFAULT '0',
  `daibieuhdnd` double NOT NULL DEFAULT '0',
  `nghihuu` double NOT NULL DEFAULT '0',
  `canbokct` double NOT NULL DEFAULT '0',
  `uyvien` double NOT NULL DEFAULT '0',
  `boiduong` double NOT NULL DEFAULT '0',
  `thunhapthap` double NOT NULL DEFAULT '0',
  `diaban` double NOT NULL DEFAULT '0',
  `tinhgiam` double NOT NULL DEFAULT '0',
  `nghihuusom` double NOT NULL DEFAULT '0',
  `nguonkp` double NOT NULL DEFAULT '0',
  `tietkiem` double NOT NULL DEFAULT '0',
  `hocphi` double NOT NULL DEFAULT '0',
  `vienphi` double NOT NULL DEFAULT '0',
  `nguonthu` double NOT NULL DEFAULT '0',
  `madv` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `madvbc` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `macqcq` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `maphanloai` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `trangthai` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `ngayguidv` date DEFAULT NULL,
  `nguoiguidv` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `ngayguih` date DEFAULT NULL,
  `nguoiguih` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `lydo` text COLLATE utf8_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `nguonkinhphi`
--

INSERT INTO `nguonkinhphi` (`id`, `masodv`, `masoh`, `masok`, `masot`, `sohieu`, `manguonkp`, `noidung`, `namns`, `linhvuchoatdong`, `nhucau`, `luongphucap`, `daibieuhdnd`, `nghihuu`, `canbokct`, `uyvien`, `boiduong`, `thunhapthap`, `diaban`, `tinhgiam`, `nghihuusom`, `nguonkp`, `tietkiem`, `hocphi`, `vienphi`, `nguonthu`, `madv`, `madvbc`, `macqcq`, `maphanloai`, `trangthai`, `ngayguidv`, `nguoiguidv`, `ngayguih`, `nguoiguih`, `lydo`, `created_at`, `updated_at`) VALUES
(1, '1533713951', NULL, NULL, NULL, 'TT67_2017', NULL, '', '2017', 'GD', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '1512393993', '1506415809', '1512391746', 'KVHCSN', 'CHOGUI', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(2, '1533715123', NULL, NULL, NULL, 'TT67_2017', NULL, '', '2017', 'GD', 16000000, 1000000, 0, 0, 0, 0, 0, 10000000, 5000000, 0, 0, 15000000, 15000000, 0, 0, 0, '1512391746', '1506415809', '14', 'KVHCSN', 'CHOGUI', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(3, '1534403282', '1534403301', NULL, NULL, 'TT67_2017', NULL, '', '2017', 'GD', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '1534385336', '1534385083', '1534385271', 'KVHCSN', 'DAGUI', '2018-08-16', 'Huyện a', NULL, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `nguonkinhphi_huyen`
--

CREATE TABLE `nguonkinhphi_huyen` (
  `id` int(10) UNSIGNED NOT NULL,
  `masodv` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `sohieu` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `noidung` text COLLATE utf8_unicode_ci,
  `ngaylap` date DEFAULT NULL,
  `nguoilap` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `ghichu` text COLLATE utf8_unicode_ci,
  `ngaygui` date DEFAULT NULL,
  `nguoigui` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `trangthai` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `lydo` text COLLATE utf8_unicode_ci,
  `madv` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `madvbc` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `macqcq` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `nguonkinhphi_huyen`
--

INSERT INTO `nguonkinhphi_huyen` (`id`, `masodv`, `sohieu`, `noidung`, `ngaylap`, `nguoilap`, `ghichu`, `ngaygui`, `nguoigui`, `trangthai`, `lydo`, `madv`, `madvbc`, `macqcq`, `created_at`, `updated_at`) VALUES
(1, '1534403301', 'TT67_2017', 'Đơn vị HUYỆN A tổng hợp dữ liệu dự toán lương.', '2018-08-16', 'Huyện a', NULL, NULL, NULL, 'DAGUI', NULL, '1534385336', '1534385083', '1534385271', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `nguonkinhphi_khoi`
--

CREATE TABLE `nguonkinhphi_khoi` (
  `id` int(10) UNSIGNED NOT NULL,
  `masodv` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `sohieu` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `noidung` text COLLATE utf8_unicode_ci,
  `ngaylap` date DEFAULT NULL,
  `nguoilap` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `ghichu` text COLLATE utf8_unicode_ci,
  `ngaygui` date DEFAULT NULL,
  `nguoigui` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `trangthai` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `lydo` text COLLATE utf8_unicode_ci,
  `madv` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `madvbc` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `macqcq` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `nguonkinhphi_tinh`
--

CREATE TABLE `nguonkinhphi_tinh` (
  `id` int(10) UNSIGNED NOT NULL,
  `masodv` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `sohieu` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `noidung` text COLLATE utf8_unicode_ci,
  `ngaylap` date DEFAULT NULL,
  `nguoilap` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `ghichu` text COLLATE utf8_unicode_ci,
  `ngaygui` date DEFAULT NULL,
  `nguoigui` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `trangthai` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `lydo` text COLLATE utf8_unicode_ci,
  `madv` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `madvbc` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `macqcq` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `nhomngachluong`
--

CREATE TABLE `nhomngachluong` (
  `id` int(10) UNSIGNED NOT NULL,
  `manhom` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `tennhom` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `phanloai` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `ghichu` text COLLATE utf8_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `heso` double NOT NULL DEFAULT '0',
  `vuotkhung` double NOT NULL DEFAULT '0',
  `baclonnhat` double NOT NULL DEFAULT '1',
  `bacvuotkhung` double NOT NULL DEFAULT '0',
  `hesochenhlech` double NOT NULL DEFAULT '0',
  `namnb` double NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `nhomngachluong`
--

INSERT INTO `nhomngachluong` (`id`, `manhom`, `tennhom`, `phanloai`, `ghichu`, `created_at`, `updated_at`, `heso`, `vuotkhung`, `baclonnhat`, `bacvuotkhung`, `hesochenhlech`, `namnb`) VALUES
(1, 'A31', 'CC-VC loại A3.1', 'CVCC', '', '2017-10-02 02:07:43', '2017-10-02 03:59:51', 6.2, 5, 7, 7, 0.36, 3),
(2, 'A32', 'CC-VC loại A3.2', 'CVC', '', '2017-10-02 02:11:36', '2017-10-10 01:51:40', 5.7, 5, 7, 7, 0.36, 3),
(3, 'A21', 'CC-VC loại A2.1', 'CV', '', '2017-10-02 02:17:51', '2017-10-02 02:37:10', 4.4, 5, 9, 9, 0.34, 3),
(4, 'A22', 'CC-VC loại A2.2', 'CV', '', '2017-10-02 02:18:37', '2017-10-02 02:38:53', 4, 5, 11, 9, 0.34, 3),
(5, 'A1', 'CC-VC loại A1', 'CV', '', '2017-10-02 02:19:12', '2017-10-02 02:38:46', 2.34, 5, 10, 10, 0.33, 3),
(6, 'A0', 'CC-VC loại A0', 'CS', '', '2017-10-02 02:20:09', '2017-10-02 02:37:53', 2.1, 0, 10, 0, 0.31, 3),
(7, 'C1', 'CC-VC loại C1', 'KHAC', '', '2017-10-02 02:22:23', '2017-10-10 01:54:27', 1.65, 5, 16, 13, 0.18, 2),
(8, 'C2', 'CC-VC loại C2', 'KHAC', '', '2017-10-02 02:22:31', '2017-10-10 01:44:18', 1.5, 5, 16, 13, 0.18, 2),
(9, 'C3', 'CC-VC loại C3', 'KHAC', '', '2017-10-02 02:22:38', '2017-10-10 01:46:14', 1.35, 5, 16, 13, 0.18, 2),
(10, 'NVTH', 'NV thừa hành, phục vụ', 'KHAC', '', '2017-10-02 02:24:03', '2017-10-10 01:47:08', 1.5, 5, 16, 13, 0.18, 2),
(11, 'CBCT', 'Cán bộ chuyên trách', 'KHAC', '', '2017-10-02 02:24:27', '2017-10-02 02:24:27', 0, 0, 1, 0, 0, 0),
(12, 'B', 'CC-VC loại B', 'KHAC', '', '2017-10-10 01:41:26', '2017-10-10 01:54:44', 1.86, 5, 13, 13, 0.2, 2);

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tonghopluong_donvi`
--

CREATE TABLE `tonghopluong_donvi` (
  `id` int(10) UNSIGNED NOT NULL,
  `mathdv` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `mathk` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `mathh` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `matht` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `thang` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  `nam` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  `noidung` text COLLATE utf8_unicode_ci,
  `ngaylap` date DEFAULT NULL,
  `nguoilap` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `ghichu` text COLLATE utf8_unicode_ci,
  `ngaygui` date DEFAULT NULL,
  `nguoigui` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `trangthai` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `lydo` text COLLATE utf8_unicode_ci,
  `phanloai` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `madv` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `madvbc` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `macqcq` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `tonghopluong_donvi`
--

INSERT INTO `tonghopluong_donvi` (`id`, `mathdv`, `mathk`, `mathh`, `matht`, `thang`, `nam`, `noidung`, `ngaylap`, `nguoilap`, `ghichu`, `ngaygui`, `nguoigui`, `trangthai`, `lydo`, `phanloai`, `madv`, `madvbc`, `macqcq`, `created_at`, `updated_at`) VALUES
(1, '1533715077', NULL, NULL, '1533715090', '01', '2018', 'Dữ liệu tổng hợp của SỞ TÀI CHÍNH KHÁNH HÒA thời điểm 01/2018', '2018-08-08', 'Sở Tài chính Khánh Hòa', NULL, NULL, NULL, 'DAGUI', NULL, 'DONVI', '1512391746', '1506415809', '14', NULL, NULL),
(2, '1534385816', NULL, NULL, NULL, '01', '2018', 'Dữ liệu tổng hợp của HUYỆN A thời điểm 01/2018', '2018-08-16', 'Huyện a', NULL, '2018-08-16', 'Huyện a', 'DAGUI', NULL, 'DONVI', '1534385336', '1534385083', '1534385271', NULL, NULL),
(3, '1534393374', NULL, '1534393386', NULL, '02', '2018', 'Dữ liệu tổng hợp của HUYỆN A thời điểm 02/2018', '2018-08-16', 'Huyện a', NULL, '2018-08-16', 'Huyện a', 'DAGUI', NULL, 'DONVI', '1534385336', '1534385083', '1534385271', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tonghopluong_donvi_chitiet`
--

CREATE TABLE `tonghopluong_donvi_chitiet` (
  `id` int(10) UNSIGNED NOT NULL,
  `mathdv` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `mathk` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `mathh` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `matht` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `manguonkp` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `linhvuchoatdong` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `macongtac` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `luongcoban` double NOT NULL DEFAULT '0',
  `heso` double NOT NULL DEFAULT '0',
  `hesopc` double NOT NULL DEFAULT '0',
  `hesott` double NOT NULL DEFAULT '0',
  `vuotkhung` double NOT NULL DEFAULT '0',
  `pcct` double NOT NULL DEFAULT '0',
  `pckct` double NOT NULL DEFAULT '0',
  `pck` double NOT NULL DEFAULT '0',
  `pccv` double NOT NULL DEFAULT '0',
  `pckv` double NOT NULL DEFAULT '0',
  `pcth` double NOT NULL DEFAULT '0',
  `pcdd` double NOT NULL DEFAULT '0',
  `pcdh` double NOT NULL DEFAULT '0',
  `pcld` double NOT NULL DEFAULT '0',
  `pcdbqh` double NOT NULL DEFAULT '0',
  `pcudn` double NOT NULL DEFAULT '0',
  `pctn` double NOT NULL DEFAULT '0',
  `pctnn` double NOT NULL DEFAULT '0',
  `pcdbn` double NOT NULL DEFAULT '0',
  `pcvk` double NOT NULL DEFAULT '0',
  `pckn` double NOT NULL DEFAULT '0',
  `pcdang` double NOT NULL DEFAULT '0',
  `pccovu` double NOT NULL DEFAULT '0',
  `pclt` double NOT NULL DEFAULT '0',
  `pcd` double NOT NULL DEFAULT '0',
  `pctr` double NOT NULL DEFAULT '0',
  `pctnvk` double NOT NULL DEFAULT '0',
  `pcbdhdcu` double NOT NULL DEFAULT '0',
  `pcthni` double NOT NULL DEFAULT '0',
  `tonghs` double NOT NULL DEFAULT '0',
  `stbhxh_dv` double NOT NULL DEFAULT '0',
  `stbhyt_dv` double NOT NULL DEFAULT '0',
  `stkpcd_dv` double NOT NULL DEFAULT '0',
  `stbhtn_dv` double NOT NULL DEFAULT '0',
  `ttbh_dv` double NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `tonghopluong_donvi_chitiet`
--

INSERT INTO `tonghopluong_donvi_chitiet` (`id`, `mathdv`, `mathk`, `mathh`, `matht`, `manguonkp`, `linhvuchoatdong`, `macongtac`, `luongcoban`, `heso`, `hesopc`, `hesott`, `vuotkhung`, `pcct`, `pckct`, `pck`, `pccv`, `pckv`, `pcth`, `pcdd`, `pcdh`, `pcld`, `pcdbqh`, `pcudn`, `pctn`, `pctnn`, `pcdbn`, `pcvk`, `pckn`, `pcdang`, `pccovu`, `pclt`, `pcd`, `pctr`, `pctnvk`, `pcbdhdcu`, `pcthni`, `tonghs`, `stbhxh_dv`, `stbhyt_dv`, `stkpcd_dv`, `stbhtn_dv`, `ttbh_dv`, `created_at`, `updated_at`) VALUES
(1, '1533715077', NULL, NULL, '1533715090', '12', 'GD', 'BIENCHE', 1300000, 3042000, 0, 0, 0, 0, 0, 0, 390000, 0, 0, 0, 0, 0, 390000, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 3822000, 600600, 102960, 68640, 34320, 0, NULL, NULL),
(2, '1534385816', NULL, NULL, NULL, '12', 'QLNN', 'BIENCHE', 1300000, 2418000, 0, 0, 0, 0, 0, 0, 780000, 780000, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 3978000, 559650, 95940, 63960, 31980, 0, NULL, NULL),
(3, '1534393374', NULL, NULL, NULL, '12', 'GD', 'BIENCHE', 1300000, 2418000, 0, 0, 0, 0, 0, 0, 780000, 780000, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 3978000, 559650, 95940, 63960, 31980, 0, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tonghopluong_donvi_diaban`
--

CREATE TABLE `tonghopluong_donvi_diaban` (
  `id` int(10) UNSIGNED NOT NULL,
  `mathdv` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `mathk` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `mathh` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `matht` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `madiaban` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `luongcoban` double NOT NULL DEFAULT '0',
  `heso` double NOT NULL DEFAULT '0',
  `hesopc` double NOT NULL DEFAULT '0',
  `hesott` double NOT NULL DEFAULT '0',
  `vuotkhung` double NOT NULL DEFAULT '0',
  `pcct` double NOT NULL DEFAULT '0',
  `pckct` double NOT NULL DEFAULT '0',
  `pck` double NOT NULL DEFAULT '0',
  `pccv` double NOT NULL DEFAULT '0',
  `pckv` double NOT NULL DEFAULT '0',
  `pcth` double NOT NULL DEFAULT '0',
  `pcdd` double NOT NULL DEFAULT '0',
  `pcdh` double NOT NULL DEFAULT '0',
  `pcld` double NOT NULL DEFAULT '0',
  `pcdbqh` double NOT NULL DEFAULT '0',
  `pcudn` double NOT NULL DEFAULT '0',
  `pctn` double NOT NULL DEFAULT '0',
  `pctnn` double NOT NULL DEFAULT '0',
  `pcdbn` double NOT NULL DEFAULT '0',
  `pcvk` double NOT NULL DEFAULT '0',
  `pckn` double NOT NULL DEFAULT '0',
  `pcdang` double NOT NULL DEFAULT '0',
  `pccovu` double NOT NULL DEFAULT '0',
  `pclt` double NOT NULL DEFAULT '0',
  `pcd` double NOT NULL DEFAULT '0',
  `pctr` double NOT NULL DEFAULT '0',
  `pctnvk` double NOT NULL DEFAULT '0',
  `pcthni` double NOT NULL DEFAULT '0',
  `pcbdhdcu` double NOT NULL DEFAULT '0',
  `tonghs` double NOT NULL DEFAULT '0',
  `stbhxh_dv` double NOT NULL DEFAULT '0',
  `stbhyt_dv` double NOT NULL DEFAULT '0',
  `stkpcd_dv` double NOT NULL DEFAULT '0',
  `stbhtn_dv` double NOT NULL DEFAULT '0',
  `ttbh_dv` double NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tonghopluong_huyen`
--

CREATE TABLE `tonghopluong_huyen` (
  `id` int(10) UNSIGNED NOT NULL,
  `mathdv` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `thang` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  `nam` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  `noidung` text COLLATE utf8_unicode_ci,
  `ngaylap` date DEFAULT NULL,
  `nguoilap` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `ghichu` text COLLATE utf8_unicode_ci,
  `ngaygui` date DEFAULT NULL,
  `nguoigui` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `trangthai` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `phanloai` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `lydo` text COLLATE utf8_unicode_ci,
  `madv` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `madvbc` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `macqcq` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `tonghopluong_huyen`
--

INSERT INTO `tonghopluong_huyen` (`id`, `mathdv`, `thang`, `nam`, `noidung`, `ngaylap`, `nguoilap`, `ghichu`, `ngaygui`, `nguoigui`, `trangthai`, `phanloai`, `lydo`, `madv`, `madvbc`, `macqcq`, `created_at`, `updated_at`) VALUES
(1, '1534393386', '02', '2018', 'Đơn vị HUYỆN A tổng hợp dữ liệu chi trả lương.', '2018-08-16', 'Huyện a', NULL, NULL, NULL, 'DAGUI', NULL, NULL, '1534385336', '1534385083', '1534385271', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tonghopluong_huyen_chitiet`
--

CREATE TABLE `tonghopluong_huyen_chitiet` (
  `id` int(10) UNSIGNED NOT NULL,
  `mathdv` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `manguonkp` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `linhvuchoatdong` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `macongtac` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `luongcoban` double NOT NULL DEFAULT '0',
  `heso` double NOT NULL DEFAULT '0',
  `hesopc` double NOT NULL DEFAULT '0',
  `hesott` double NOT NULL DEFAULT '0',
  `vuotkhung` double NOT NULL DEFAULT '0',
  `pcct` double NOT NULL DEFAULT '0',
  `pckct` double NOT NULL DEFAULT '0',
  `pck` double NOT NULL DEFAULT '0',
  `pccv` double NOT NULL DEFAULT '0',
  `pckv` double NOT NULL DEFAULT '0',
  `pcth` double NOT NULL DEFAULT '0',
  `pcdd` double NOT NULL DEFAULT '0',
  `pcdh` double NOT NULL DEFAULT '0',
  `pcld` double NOT NULL DEFAULT '0',
  `pcdbqh` double NOT NULL DEFAULT '0',
  `pcudn` double NOT NULL DEFAULT '0',
  `pctn` double NOT NULL DEFAULT '0',
  `pctnn` double NOT NULL DEFAULT '0',
  `pcdbn` double NOT NULL DEFAULT '0',
  `pcvk` double NOT NULL DEFAULT '0',
  `pckn` double NOT NULL DEFAULT '0',
  `pcdang` double NOT NULL DEFAULT '0',
  `pccovu` double NOT NULL DEFAULT '0',
  `pclt` double NOT NULL DEFAULT '0',
  `pcd` double NOT NULL DEFAULT '0',
  `pctr` double NOT NULL DEFAULT '0',
  `pctnvk` double NOT NULL DEFAULT '0',
  `pcbdhdcu` double NOT NULL DEFAULT '0',
  `pcthni` double NOT NULL DEFAULT '0',
  `tonghs` double NOT NULL DEFAULT '0',
  `stbhxh_dv` double NOT NULL DEFAULT '0',
  `stbhyt_dv` double NOT NULL DEFAULT '0',
  `stkpcd_dv` double NOT NULL DEFAULT '0',
  `stbhtn_dv` double NOT NULL DEFAULT '0',
  `ttbh_dv` double NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tonghopluong_huyen_diaban`
--

CREATE TABLE `tonghopluong_huyen_diaban` (
  `id` int(10) UNSIGNED NOT NULL,
  `mathdv` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `madiaban` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `luongcoban` double NOT NULL DEFAULT '0',
  `heso` double NOT NULL DEFAULT '0',
  `hesopc` double NOT NULL DEFAULT '0',
  `hesott` double NOT NULL DEFAULT '0',
  `vuotkhung` double NOT NULL DEFAULT '0',
  `pcct` double NOT NULL DEFAULT '0',
  `pckct` double NOT NULL DEFAULT '0',
  `pck` double NOT NULL DEFAULT '0',
  `pccv` double NOT NULL DEFAULT '0',
  `pckv` double NOT NULL DEFAULT '0',
  `pcth` double NOT NULL DEFAULT '0',
  `pcdd` double NOT NULL DEFAULT '0',
  `pcdh` double NOT NULL DEFAULT '0',
  `pcld` double NOT NULL DEFAULT '0',
  `pcdbqh` double NOT NULL DEFAULT '0',
  `pcudn` double NOT NULL DEFAULT '0',
  `pctn` double NOT NULL DEFAULT '0',
  `pctnn` double NOT NULL DEFAULT '0',
  `pcdbn` double NOT NULL DEFAULT '0',
  `pcvk` double NOT NULL DEFAULT '0',
  `pckn` double NOT NULL DEFAULT '0',
  `pcdang` double NOT NULL DEFAULT '0',
  `pccovu` double NOT NULL DEFAULT '0',
  `pclt` double NOT NULL DEFAULT '0',
  `pcd` double NOT NULL DEFAULT '0',
  `pctr` double NOT NULL DEFAULT '0',
  `pctnvk` double NOT NULL DEFAULT '0',
  `pcthni` double NOT NULL DEFAULT '0',
  `pcbdhdcu` double NOT NULL DEFAULT '0',
  `tonghs` double NOT NULL DEFAULT '0',
  `stbhxh_dv` double NOT NULL DEFAULT '0',
  `stbhyt_dv` double NOT NULL DEFAULT '0',
  `stkpcd_dv` double NOT NULL DEFAULT '0',
  `stbhtn_dv` double NOT NULL DEFAULT '0',
  `ttbh_dv` double NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tonghopluong_khoi`
--

CREATE TABLE `tonghopluong_khoi` (
  `id` int(10) UNSIGNED NOT NULL,
  `mathdv` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `thang` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  `nam` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  `noidung` text COLLATE utf8_unicode_ci,
  `ngaylap` date DEFAULT NULL,
  `nguoilap` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `ghichu` text COLLATE utf8_unicode_ci,
  `ngaygui` date DEFAULT NULL,
  `nguoigui` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `trangthai` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `phanloai` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `lydo` text COLLATE utf8_unicode_ci,
  `madv` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `madvbc` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `macqcq` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tonghopluong_khoi_chitiet`
--

CREATE TABLE `tonghopluong_khoi_chitiet` (
  `id` int(10) UNSIGNED NOT NULL,
  `mathdv` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `manguonkp` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `linhvuchoatdong` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `macongtac` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `luongcoban` double NOT NULL DEFAULT '0',
  `heso` double NOT NULL DEFAULT '0',
  `hesopc` double NOT NULL DEFAULT '0',
  `hesott` double NOT NULL DEFAULT '0',
  `vuotkhung` double NOT NULL DEFAULT '0',
  `pcct` double NOT NULL DEFAULT '0',
  `pckct` double NOT NULL DEFAULT '0',
  `pck` double NOT NULL DEFAULT '0',
  `pccv` double NOT NULL DEFAULT '0',
  `pckv` double NOT NULL DEFAULT '0',
  `pcth` double NOT NULL DEFAULT '0',
  `pcdd` double NOT NULL DEFAULT '0',
  `pcdh` double NOT NULL DEFAULT '0',
  `pcld` double NOT NULL DEFAULT '0',
  `pcdbqh` double NOT NULL DEFAULT '0',
  `pcudn` double NOT NULL DEFAULT '0',
  `pctn` double NOT NULL DEFAULT '0',
  `pctnn` double NOT NULL DEFAULT '0',
  `pcdbn` double NOT NULL DEFAULT '0',
  `pcvk` double NOT NULL DEFAULT '0',
  `pckn` double NOT NULL DEFAULT '0',
  `pcdang` double NOT NULL DEFAULT '0',
  `pccovu` double NOT NULL DEFAULT '0',
  `pclt` double NOT NULL DEFAULT '0',
  `pcd` double NOT NULL DEFAULT '0',
  `pctr` double NOT NULL DEFAULT '0',
  `pctnvk` double NOT NULL DEFAULT '0',
  `pcbdhdcu` double NOT NULL DEFAULT '0',
  `pcthni` double NOT NULL DEFAULT '0',
  `tonghs` double NOT NULL DEFAULT '0',
  `stbhxh_dv` double NOT NULL DEFAULT '0',
  `stbhyt_dv` double NOT NULL DEFAULT '0',
  `stkpcd_dv` double NOT NULL DEFAULT '0',
  `stbhtn_dv` double NOT NULL DEFAULT '0',
  `ttbh_dv` double NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tonghopluong_khoi_diaban`
--

CREATE TABLE `tonghopluong_khoi_diaban` (
  `id` int(10) UNSIGNED NOT NULL,
  `mathdv` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `madiaban` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `luongcoban` double NOT NULL DEFAULT '0',
  `heso` double NOT NULL DEFAULT '0',
  `hesopc` double NOT NULL DEFAULT '0',
  `hesott` double NOT NULL DEFAULT '0',
  `vuotkhung` double NOT NULL DEFAULT '0',
  `pcct` double NOT NULL DEFAULT '0',
  `pckct` double NOT NULL DEFAULT '0',
  `pck` double NOT NULL DEFAULT '0',
  `pccv` double NOT NULL DEFAULT '0',
  `pckv` double NOT NULL DEFAULT '0',
  `pcth` double NOT NULL DEFAULT '0',
  `pcdd` double NOT NULL DEFAULT '0',
  `pcdh` double NOT NULL DEFAULT '0',
  `pcld` double NOT NULL DEFAULT '0',
  `pcdbqh` double NOT NULL DEFAULT '0',
  `pcudn` double NOT NULL DEFAULT '0',
  `pctn` double NOT NULL DEFAULT '0',
  `pctnn` double NOT NULL DEFAULT '0',
  `pcdbn` double NOT NULL DEFAULT '0',
  `pcvk` double NOT NULL DEFAULT '0',
  `pckn` double NOT NULL DEFAULT '0',
  `pcdang` double NOT NULL DEFAULT '0',
  `pccovu` double NOT NULL DEFAULT '0',
  `pclt` double NOT NULL DEFAULT '0',
  `pcd` double NOT NULL DEFAULT '0',
  `pctr` double NOT NULL DEFAULT '0',
  `pctnvk` double NOT NULL DEFAULT '0',
  `pcthni` double NOT NULL DEFAULT '0',
  `pcbdhdcu` double NOT NULL DEFAULT '0',
  `tonghs` double NOT NULL DEFAULT '0',
  `stbhxh_dv` double NOT NULL DEFAULT '0',
  `stbhyt_dv` double NOT NULL DEFAULT '0',
  `stkpcd_dv` double NOT NULL DEFAULT '0',
  `stbhtn_dv` double NOT NULL DEFAULT '0',
  `ttbh_dv` double NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tonghopluong_tinh`
--

CREATE TABLE `tonghopluong_tinh` (
  `id` int(10) UNSIGNED NOT NULL,
  `mathdv` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `thang` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  `nam` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  `noidung` text COLLATE utf8_unicode_ci,
  `ngaylap` date DEFAULT NULL,
  `nguoilap` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `ghichu` text COLLATE utf8_unicode_ci,
  `ngaygui` date DEFAULT NULL,
  `nguoigui` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `trangthai` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `phanloai` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `lydo` text COLLATE utf8_unicode_ci,
  `madv` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `madvbc` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `macqcq` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `tonghopluong_tinh`
--

INSERT INTO `tonghopluong_tinh` (`id`, `mathdv`, `thang`, `nam`, `noidung`, `ngaylap`, `nguoilap`, `ghichu`, `ngaygui`, `nguoigui`, `trangthai`, `phanloai`, `lydo`, `madv`, `madvbc`, `macqcq`, `created_at`, `updated_at`) VALUES
(1, '1533715090', '01', '2018', 'Dữ liệu tổng hợp của SỞ TÀI CHÍNH KHÁNH HÒA thời điểm 01/2018', '2018-08-08', 'Sở Tài chính Khánh Hòa', NULL, '2018-08-08', 'Sở Tài chính Khánh Hòa', 'CHONHAN', 'DONVI', NULL, '1512391746', '1506415809', '14', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tonghopluong_tinh_chitiet`
--

CREATE TABLE `tonghopluong_tinh_chitiet` (
  `id` int(10) UNSIGNED NOT NULL,
  `mathdv` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `manguonkp` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `linhvuchoatdong` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `macongtac` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `luongcoban` double NOT NULL DEFAULT '0',
  `heso` double NOT NULL DEFAULT '0',
  `hesopc` double NOT NULL DEFAULT '0',
  `hesott` double NOT NULL DEFAULT '0',
  `vuotkhung` double NOT NULL DEFAULT '0',
  `pcct` double NOT NULL DEFAULT '0',
  `pckct` double NOT NULL DEFAULT '0',
  `pck` double NOT NULL DEFAULT '0',
  `pccv` double NOT NULL DEFAULT '0',
  `pckv` double NOT NULL DEFAULT '0',
  `pcth` double NOT NULL DEFAULT '0',
  `pcdd` double NOT NULL DEFAULT '0',
  `pcdh` double NOT NULL DEFAULT '0',
  `pcld` double NOT NULL DEFAULT '0',
  `pcdbqh` double NOT NULL DEFAULT '0',
  `pcudn` double NOT NULL DEFAULT '0',
  `pctn` double NOT NULL DEFAULT '0',
  `pctnn` double NOT NULL DEFAULT '0',
  `pcdbn` double NOT NULL DEFAULT '0',
  `pcvk` double NOT NULL DEFAULT '0',
  `pckn` double NOT NULL DEFAULT '0',
  `pcdang` double NOT NULL DEFAULT '0',
  `pccovu` double NOT NULL DEFAULT '0',
  `pclt` double NOT NULL DEFAULT '0',
  `pcd` double NOT NULL DEFAULT '0',
  `pctr` double NOT NULL DEFAULT '0',
  `pctnvk` double NOT NULL DEFAULT '0',
  `pcbdhdcu` double NOT NULL DEFAULT '0',
  `pcthni` double NOT NULL DEFAULT '0',
  `tonghs` double NOT NULL DEFAULT '0',
  `stbhxh_dv` double NOT NULL DEFAULT '0',
  `stbhyt_dv` double NOT NULL DEFAULT '0',
  `stkpcd_dv` double NOT NULL DEFAULT '0',
  `stbhtn_dv` double NOT NULL DEFAULT '0',
  `ttbh_dv` double NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tonghopluong_tinh_diaban`
--

CREATE TABLE `tonghopluong_tinh_diaban` (
  `id` int(10) UNSIGNED NOT NULL,
  `mathdv` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `madiaban` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `luongcoban` double NOT NULL DEFAULT '0',
  `heso` double NOT NULL DEFAULT '0',
  `hesopc` double NOT NULL DEFAULT '0',
  `hesott` double NOT NULL DEFAULT '0',
  `vuotkhung` double NOT NULL DEFAULT '0',
  `pcct` double NOT NULL DEFAULT '0',
  `pckct` double NOT NULL DEFAULT '0',
  `pck` double NOT NULL DEFAULT '0',
  `pccv` double NOT NULL DEFAULT '0',
  `pckv` double NOT NULL DEFAULT '0',
  `pcth` double NOT NULL DEFAULT '0',
  `pcdd` double NOT NULL DEFAULT '0',
  `pcdh` double NOT NULL DEFAULT '0',
  `pcld` double NOT NULL DEFAULT '0',
  `pcdbqh` double NOT NULL DEFAULT '0',
  `pcudn` double NOT NULL DEFAULT '0',
  `pctn` double NOT NULL DEFAULT '0',
  `pctnn` double NOT NULL DEFAULT '0',
  `pcdbn` double NOT NULL DEFAULT '0',
  `pcvk` double NOT NULL DEFAULT '0',
  `pckn` double NOT NULL DEFAULT '0',
  `pcdang` double NOT NULL DEFAULT '0',
  `pccovu` double NOT NULL DEFAULT '0',
  `pclt` double NOT NULL DEFAULT '0',
  `pcd` double NOT NULL DEFAULT '0',
  `pctr` double NOT NULL DEFAULT '0',
  `pctnvk` double NOT NULL DEFAULT '0',
  `pcthni` double NOT NULL DEFAULT '0',
  `pcbdhdcu` double NOT NULL DEFAULT '0',
  `tonghs` double NOT NULL DEFAULT '0',
  `stbhxh_dv` double NOT NULL DEFAULT '0',
  `stbhyt_dv` double NOT NULL DEFAULT '0',
  `stkpcd_dv` double NOT NULL DEFAULT '0',
  `stbhtn_dv` double NOT NULL DEFAULT '0',
  `ttbh_dv` double NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tonghop_huyen`
--

CREATE TABLE `tonghop_huyen` (
  `id` int(10) UNSIGNED NOT NULL,
  `mathdv` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `thang` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  `nam` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  `noidung` text COLLATE utf8_unicode_ci,
  `ngaylap` date DEFAULT NULL,
  `nguoilap` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `ghichu` text COLLATE utf8_unicode_ci,
  `ngaygui` date DEFAULT NULL,
  `nguoigui` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `trangthai` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `phanloai` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `madv` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `madvbc` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `macqcq` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tonghop_huyen_chitiet`
--

CREATE TABLE `tonghop_huyen_chitiet` (
  `id` int(10) UNSIGNED NOT NULL,
  `mathdv` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `manguonkp` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `linhvuchoatdong` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `macongtac` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `luongcoban` double NOT NULL DEFAULT '0',
  `heso` double NOT NULL DEFAULT '0',
  `hesopc` double NOT NULL DEFAULT '0',
  `hesott` double NOT NULL DEFAULT '0',
  `vuotkhung` double NOT NULL DEFAULT '0',
  `pcct` double NOT NULL DEFAULT '0',
  `pckct` double NOT NULL DEFAULT '0',
  `pck` double NOT NULL DEFAULT '0',
  `pccv` double NOT NULL DEFAULT '0',
  `pckv` double NOT NULL DEFAULT '0',
  `pcth` double NOT NULL DEFAULT '0',
  `pcdd` double NOT NULL DEFAULT '0',
  `pcdh` double NOT NULL DEFAULT '0',
  `pcld` double NOT NULL DEFAULT '0',
  `pcdbqh` double NOT NULL DEFAULT '0',
  `pcudn` double NOT NULL DEFAULT '0',
  `pctn` double NOT NULL DEFAULT '0',
  `pctnn` double NOT NULL DEFAULT '0',
  `pcdbn` double NOT NULL DEFAULT '0',
  `pcvk` double NOT NULL DEFAULT '0',
  `pckn` double NOT NULL DEFAULT '0',
  `pcdang` double NOT NULL DEFAULT '0',
  `pccovu` double NOT NULL DEFAULT '0',
  `pclt` double NOT NULL DEFAULT '0',
  `pcd` double NOT NULL DEFAULT '0',
  `pctr` double NOT NULL DEFAULT '0',
  `pctnvk` double NOT NULL DEFAULT '0',
  `pcbdhdcu` double NOT NULL DEFAULT '0',
  `pcthni` double NOT NULL DEFAULT '0',
  `tonghs` double NOT NULL DEFAULT '0',
  `stbhxh_dv` double NOT NULL DEFAULT '0',
  `stbhyt_dv` double NOT NULL DEFAULT '0',
  `stkpcd_dv` double NOT NULL DEFAULT '0',
  `stbhtn_dv` double NOT NULL DEFAULT '0',
  `ttbh_dv` double NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tonghop_huyen_diaban`
--

CREATE TABLE `tonghop_huyen_diaban` (
  `id` int(10) UNSIGNED NOT NULL,
  `mathdv` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `madiaban` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `luongcoban` double NOT NULL DEFAULT '0',
  `heso` double NOT NULL DEFAULT '0',
  `hesopc` double NOT NULL DEFAULT '0',
  `hesott` double NOT NULL DEFAULT '0',
  `vuotkhung` double NOT NULL DEFAULT '0',
  `pcct` double NOT NULL DEFAULT '0',
  `pckct` double NOT NULL DEFAULT '0',
  `pck` double NOT NULL DEFAULT '0',
  `pccv` double NOT NULL DEFAULT '0',
  `pckv` double NOT NULL DEFAULT '0',
  `pcth` double NOT NULL DEFAULT '0',
  `pcdd` double NOT NULL DEFAULT '0',
  `pcdh` double NOT NULL DEFAULT '0',
  `pcld` double NOT NULL DEFAULT '0',
  `pcdbqh` double NOT NULL DEFAULT '0',
  `pcudn` double NOT NULL DEFAULT '0',
  `pctn` double NOT NULL DEFAULT '0',
  `pctnn` double NOT NULL DEFAULT '0',
  `pcdbn` double NOT NULL DEFAULT '0',
  `pcvk` double NOT NULL DEFAULT '0',
  `pckn` double NOT NULL DEFAULT '0',
  `pcdang` double NOT NULL DEFAULT '0',
  `pccovu` double NOT NULL DEFAULT '0',
  `pclt` double NOT NULL DEFAULT '0',
  `pcd` double NOT NULL DEFAULT '0',
  `pctr` double NOT NULL DEFAULT '0',
  `pctnvk` double NOT NULL DEFAULT '0',
  `pcthni` double NOT NULL DEFAULT '0',
  `pcbdhdcu` double NOT NULL DEFAULT '0',
  `tonghs` double NOT NULL DEFAULT '0',
  `stbhxh_dv` double NOT NULL DEFAULT '0',
  `stbhyt_dv` double NOT NULL DEFAULT '0',
  `stkpcd_dv` double NOT NULL DEFAULT '0',
  `stbhtn_dv` double NOT NULL DEFAULT '0',
  `ttbh_dv` double NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tonghop_tinh`
--

CREATE TABLE `tonghop_tinh` (
  `id` int(10) UNSIGNED NOT NULL,
  `mathdv` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `thang` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  `nam` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  `noidung` text COLLATE utf8_unicode_ci,
  `ngaylap` date DEFAULT NULL,
  `nguoilap` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `ghichu` text COLLATE utf8_unicode_ci,
  `ngaygui` date DEFAULT NULL,
  `nguoigui` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `trangthai` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `phanloai` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `madv` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `madvbc` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `macqcq` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tonghop_tinh_chitiet`
--

CREATE TABLE `tonghop_tinh_chitiet` (
  `id` int(10) UNSIGNED NOT NULL,
  `mathdv` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `manguonkp` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `linhvuchoatdong` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `macongtac` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `luongcoban` double NOT NULL DEFAULT '0',
  `heso` double NOT NULL DEFAULT '0',
  `hesopc` double NOT NULL DEFAULT '0',
  `hesott` double NOT NULL DEFAULT '0',
  `vuotkhung` double NOT NULL DEFAULT '0',
  `pcct` double NOT NULL DEFAULT '0',
  `pckct` double NOT NULL DEFAULT '0',
  `pck` double NOT NULL DEFAULT '0',
  `pccv` double NOT NULL DEFAULT '0',
  `pckv` double NOT NULL DEFAULT '0',
  `pcth` double NOT NULL DEFAULT '0',
  `pcdd` double NOT NULL DEFAULT '0',
  `pcdh` double NOT NULL DEFAULT '0',
  `pcld` double NOT NULL DEFAULT '0',
  `pcdbqh` double NOT NULL DEFAULT '0',
  `pcudn` double NOT NULL DEFAULT '0',
  `pctn` double NOT NULL DEFAULT '0',
  `pctnn` double NOT NULL DEFAULT '0',
  `pcdbn` double NOT NULL DEFAULT '0',
  `pcvk` double NOT NULL DEFAULT '0',
  `pckn` double NOT NULL DEFAULT '0',
  `pcdang` double NOT NULL DEFAULT '0',
  `pccovu` double NOT NULL DEFAULT '0',
  `pclt` double NOT NULL DEFAULT '0',
  `pcd` double NOT NULL DEFAULT '0',
  `pctr` double NOT NULL DEFAULT '0',
  `pctnvk` double NOT NULL DEFAULT '0',
  `pcbdhdcu` double NOT NULL DEFAULT '0',
  `pcthni` double NOT NULL DEFAULT '0',
  `tonghs` double NOT NULL DEFAULT '0',
  `stbhxh_dv` double NOT NULL DEFAULT '0',
  `stbhyt_dv` double NOT NULL DEFAULT '0',
  `stkpcd_dv` double NOT NULL DEFAULT '0',
  `stbhtn_dv` double NOT NULL DEFAULT '0',
  `ttbh_dv` double NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tonghop_tinh_diaban`
--

CREATE TABLE `tonghop_tinh_diaban` (
  `id` int(10) UNSIGNED NOT NULL,
  `mathdv` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `madiaban` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `luongcoban` double NOT NULL DEFAULT '0',
  `heso` double NOT NULL DEFAULT '0',
  `hesopc` double NOT NULL DEFAULT '0',
  `hesott` double NOT NULL DEFAULT '0',
  `vuotkhung` double NOT NULL DEFAULT '0',
  `pcct` double NOT NULL DEFAULT '0',
  `pckct` double NOT NULL DEFAULT '0',
  `pck` double NOT NULL DEFAULT '0',
  `pccv` double NOT NULL DEFAULT '0',
  `pckv` double NOT NULL DEFAULT '0',
  `pcth` double NOT NULL DEFAULT '0',
  `pcdd` double NOT NULL DEFAULT '0',
  `pcdh` double NOT NULL DEFAULT '0',
  `pcld` double NOT NULL DEFAULT '0',
  `pcdbqh` double NOT NULL DEFAULT '0',
  `pcudn` double NOT NULL DEFAULT '0',
  `pctn` double NOT NULL DEFAULT '0',
  `pctnn` double NOT NULL DEFAULT '0',
  `pcdbn` double NOT NULL DEFAULT '0',
  `pcvk` double NOT NULL DEFAULT '0',
  `pckn` double NOT NULL DEFAULT '0',
  `pcdang` double NOT NULL DEFAULT '0',
  `pccovu` double NOT NULL DEFAULT '0',
  `pclt` double NOT NULL DEFAULT '0',
  `pcd` double NOT NULL DEFAULT '0',
  `pctr` double NOT NULL DEFAULT '0',
  `pctnvk` double NOT NULL DEFAULT '0',
  `pcthni` double NOT NULL DEFAULT '0',
  `pcbdhdcu` double NOT NULL DEFAULT '0',
  `tonghs` double NOT NULL DEFAULT '0',
  `stbhxh_dv` double NOT NULL DEFAULT '0',
  `stbhyt_dv` double NOT NULL DEFAULT '0',
  `stkpcd_dv` double NOT NULL DEFAULT '0',
  `stbhtn_dv` double NOT NULL DEFAULT '0',
  `ttbh_dv` double NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `username` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `phone` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `status` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `madv` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `maxa` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `mahuyen` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `matinh` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `level` varchar(45) CHARACTER SET utf8 DEFAULT NULL,
  `sadmin` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `permission` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `username`, `password`, `phone`, `email`, `status`, `madv`, `maxa`, `mahuyen`, `matinh`, `level`, `sadmin`, `permission`, `created_at`, `updated_at`) VALUES
(1, 'Quản trị', 'huongvu', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', NULL, NULL, NULL, NULL, 'SA', 'SA', NULL, NULL, NULL),
(3, 'Phan Hoàng Trung', 'trungphan', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', NULL, NULL, NULL, NULL, 'SA', 'SA', NULL, NULL, NULL),
(4, 'Trần Ngọc Hiếu', 'hieutran', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', NULL, NULL, NULL, NULL, 'SA', 'SA', NULL, NULL, NULL),
(38, 'Quan Tri Bao Tri', 'qtbaotri', 'c4ca4238a0b923820dcc509a6f75849b', NULL, NULL, 'active', NULL, NULL, NULL, NULL, 'SA', 'SA', NULL, NULL, NULL),
(39, 'Hoàng Sang', 'hoangsang', 'e10adc3949ba59abbe56e057f20f883e', '', '', 'active', NULL, NULL, NULL, NULL, 'SA', 'SA', '{\"data\":{\"units\":0,\"create\":1,\"edit\":1,\"delete\":1,\"reports\":0},\"system\":{\"information\":1,\"create\":0,\"edit\":0,\"delete\":0},\"report\":{\"view\":1,\"create\":0,\"edit\":0,\"delete\":0}}', NULL, NULL),
(41, 'Văn Phòng HĐND - UBND', 'CLPUBND', 'e10adc3949ba59abbe56e057f20f883e', '', '', 'active', '1511581269', '1511581269', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(42, 'Phòng Tài Chính - Kế Hoạch', 'KSPTCKH', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511581308', '1511581308', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(43, 'Phòng Nông Nghiệp và Phát Triển Nông Thôn', 'CLPNNPTNT', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511581382', '1511581382', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(44, 'Phòng Kinh tế Hạ tầng', 'CLPKTHT', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511581429', '1511581429', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(45, 'Phòng Tư Pháp', 'CLPTUPHAP', 'e10adc3949ba59abbe56e057f20f883e', '', '', 'active', '1511581455', '1511581455', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(46, 'Phòng Tài Chính Và Kế Hoạch', 'CLPTCKH', 'e10adc3949ba59abbe56e057f20f883e', '', '', 'active', '1511581490', '1511581490', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(47, 'Phòng Giáo Dục Và Đào Tạo', 'CLPGDDT', 'e10adc3949ba59abbe56e057f20f883e', '', '', 'active', '1511581523', '1511581523', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(48, 'Phòng Y Tế', 'CLPYTE', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511581552', '1511581552', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(49, 'Văn phòng Hội đồng nhân dân & Ủy ban nhân dân', 'KSPUBND', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511581591', '1511581591', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(50, 'Phòng Lao Động Thương Binh Xã Hội', 'CLPLĐXH', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511581602', '1511581602', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(51, 'Phòng Văn Hóa Thông Tin', 'CLPVHTT', '1b8bc2218aa260ecb1d01f5c96c1fb7b', '', '', 'active', '1511581642', '1511581642', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(52, 'Phòng Nông Nghiệp & Phát triển Nông Thôn', 'KSPNNPTNT', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511581663', '1511581663', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(53, 'Phòng Tài Nguyên Và Môi Trường', 'CLPTNMT', 'e10adc3949ba59abbe56e057f20f883e', '', '', 'active', '1511581672', '1511581672', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(54, 'Phòng Dân Tộc', 'CLPDANTOC', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511581703', '1511581703', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(55, 'Phòng Kinh tế - Hạ tầng', 'KSPKTHT', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511581722', '1511581722', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(56, 'Phòng Nội Vụ', 'CLPNOIVU', 'e10adc3949ba59abbe56e057f20f883e', '', '', 'active', '1511581727', '1511581727', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(57, 'Phòng Thanh Tra', 'CLTHANHTRA', 'e10adc3949ba59abbe56e057f20f883e', '', '', 'active', '1511581750', '1511581750', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(60, 'Văn Phòng Huyện Ủy', 'CLHUYENUY', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511581792', '1511581792', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(62, 'Ủy Ban Mặt Trận Tổ Quốc Việt Nam', 'CLUBMTTQ', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511581831', '1511581831', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(64, 'Hội Cựu Chiến Binh', 'CLHCCB', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511581860', '1511581860', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(65, 'Hội Nông Dân', 'CLHNONGDAN', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511581883', '1511581883', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(66, 'Hội Phụ Nữ', 'CLHPHUNU', 'e10adc3949ba59abbe56e057f20f883e', '', '', 'active', '1511581906', '1511581906', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(68, 'Huyện Đoàn', 'CLHUYENDOAN', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511581927', '1511581927', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(70, 'Trạm Khuyến Công - Nông - Lâm - Ngư', 'CLTRAM', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511582056', '1511582056', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(71, 'Trung Tâm Văn Hóa Thể Thao', 'CLTTVHTT', 'e10adc3949ba59abbe56e057f20f883e', '', '', 'active', '1511582085', '1511582085', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(72, 'Nhà Văn Hóa Thiêu Nhi', 'CLVHTHIEUNHI', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511582110', '1511582110', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(73, 'Phòng Tư Pháp Khánh Sơn', 'KSPTUPHAP', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511582119', '1511582119', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(74, 'Đài Truyền Thanh Truyền Hình', 'CLTTTH', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511582142', '1511582142', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(75, 'Trung Tâm Bồi Dưỡng Chính Trị', 'CLBOIDUONGCHINHTRI', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511582173', '1511582173', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(76, 'Phòng Tài Nguyên môi trường ', 'KSPTNMT', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511582199', '1511582199', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(78, 'Thị Trấn Cam Đức', 'CLTTCAMDUC', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511582226', '1511582226', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(80, 'UBND Xã Cam Tân', 'CLXCAMTAN', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511582324', '1511582324', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(81, 'UBND Xã Cam Hòa', 'CLXCAMHOA', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511582352', '1511582352', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(82, 'Phòng Giáo Dục -Đào Tạo', 'KSPGDĐT', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511582402', '1511582402', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(84, 'UBND Xã Sơn Tân', 'CLXSONTAN', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511582426', '1511582426', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(85, 'UBND Xã Cam Hải Tây', 'CLXCAMHAITAY', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511582460', '1511582460', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(86, 'Phòng Lao đông Thương binh & Xã hội', 'KSPLĐTBXH', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511582470', '1511582470', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(87, 'UBND Xã Cam Hiệp Bắc', 'CLXCAMHIEPBAC', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511582492', '1511582492', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(88, 'Phòng Dân Tộc', 'KSPDANTOC', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511582503', '1511582503', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(89, 'UBND Xã Cam Hiệp Nam', 'CLXCAMHIEPNAM', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511582517', '1511582517', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(90, 'Phòng Nội Vụ', 'KSPNOIVU', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511582534', '1511582534', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(91, 'UBND Xã Cam Thành Bắc', 'CLXCAMTHANHBAC', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511582547', '1511582547', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(92, 'UBND Xã Cam An Bắc', 'CLXCAMANBAC', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511582573', '1511582573', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(93, 'Thanh tra Huyện', 'KSPTHANHTRA', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511582574', '1511582574', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(94, 'UBND Xã Cam An Nam', 'CLXCAMANNAM', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511582595', '1511582595', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(95, 'Phòng Y Tế', 'KSPYTE', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511582610', '1511582610', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(96, 'UBND Xã Cam Phước Tây', 'CLXCAMPHUOCTAY', 'e10adc3949ba59abbe56e057f20f883e', '', '', 'active', '1511582618', '1511582618', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(97, 'UBND Xã Cam Hải Đông', 'CLXCAMHAIDONG', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511582642', '1511582642', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(98, 'Văn Phòng Huyện Ủy', 'KSPHUYENUY', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511582645', '1511582645', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(99, 'UBND Xã Suối Tân', 'CLXSUOITAN', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511582664', '1511582664', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(100, 'UBND Xã Suối Cát', 'CLXSUOICAT', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511582688', '1511582688', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(101, 'Ủy Ban Mặt Trận Tổ Quốc Việt Nam Huyện', 'KSPMTTQ', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511582691', '1511582691', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(102, 'Hội Nông dân', 'KSPNONGDAN', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511582723', '1511582723', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(103, 'Trường Mần Non Hướng Dương', 'CLMNHUONGDUONG', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511582729', '1511582729', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(104, 'Trường Mẫu Giáo Mai Vàng', 'CLMGMAIVANG', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511582757', '1511582757', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(105, 'Hội Phụ Nữ', 'KSPPHUNU', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511582771', '1511582771', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(106, 'Trường Mẫu Giáo Vàng Anh', 'CLMGVANGANH', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511582783', '1511582783', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(107, 'Hội Cựu Chiến Binh', 'KSPCCB', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511582808', '1511582808', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(109, 'Trường Mẫu Giáo Sơn Ca', 'CLMGSONCA', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511582832', '1511582832', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(110, 'Huyện Đoàn', 'KSPĐOAN', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511582849', '1511582849', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(111, 'Trường Mẫu Giáo Hoa Lan', 'CLMGHOALAN', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511582856', '1511582856', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(112, 'Trường Mẫu Giáo Anh Đào', 'CLMGANHDAO', 'e10adc3949ba59abbe56e057f20f883e', '', '', 'active', '1511582881', '1511582881', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(113, 'Hội Chữ Thập Đỏ', 'KSPCTĐ', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511582883', '1511582883', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(114, 'Trường Mẫu Giáo Hoa Hồng', 'CLMGHOAHONG', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511582909', '1511582909', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(115, 'Trung tâm Bồi dưỡng - Chính trị', 'KSPTTBDCT', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511582931', '1511582931', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(116, 'Trường Mẫu Giáo Thỏ Hồng', 'CLMGTHOHONG', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511582951', '1511582951', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(117, 'Đài truyền thanh - truyền hình', 'KSPĐTTTH', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511582970', '1511582970', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(118, 'Trường Mẫu Giáo Thỏ Ngọc', 'CLMGTHONGOC', 'e10adc3949ba59abbe56e057f20f883e', '', '', 'active', '1511582978', '1511582978', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(119, 'Trường Mấu Giáo Thiên Nga', 'CLMGTHIENNGA', 'e10adc3949ba59abbe56e057f20f883e', '', '', 'active', '1511583004', '1511583004', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(120, 'Trung tâm dịch vụ thương mại', 'KSPDVTM', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511583015', '1511583015', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(121, 'Trung tâm Văn hóa - thể thao', 'KSPVHTT', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511583053', '1511583053', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(122, 'Trường Mẫu Giáo Sóc Nâu', 'CLMGSOCNAU', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511583060', '1511583060', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(123, 'Trung tâm phát triển quỹ đất', 'KSPTTPTQĐ', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511583090', '1511583090', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(124, 'Trường Mẫu Giáo Phong Lan', 'CLMGPHONGLAN', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511583124', '1511583124', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(125, 'Ban quản lý CTCC&MT', 'KSPBQLCTCCMT', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511583162', '1511583162', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(126, 'Trường Mẫu Giáo Vành Khuyên', 'CLMGVANHKHUYEN', 'e10adc3949ba59abbe56e057f20f883e', '', '', 'active', '1511583196', '1511583196', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(127, 'Trung tâm bảo trợ xã hội', 'KSPBTXH', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511583206', '1511583206', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(128, 'Trường Mẫu Giáo Hoàng Yến', 'CLMGHOANGYEN', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511583228', '1511583228', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(129, 'Trạm Khuyến Nông', 'KSPTKN', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511583247', '1511583247', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(130, 'Trường TH Cam An Bắc', 'CLTHCAMANBAC', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511583260', '1511583260', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(131, 'UBND Xã Thành Sơn', 'KSXTHANHSON', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511583302', '1511583302', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(132, 'Trường TH Cam An Nam', 'CLTHCAMANNAM', 'e10adc3949ba59abbe56e057f20f883e', '', '', 'active', '1511583320', '1511583320', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(133, 'UBND Xã Sơn Lâm', 'KSXSONLAM', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511583341', '1511583341', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(134, 'Trường TH Cam Phước Tây 2', 'CLTHCAMPHUOCTAY2', 'e10adc3949ba59abbe56e057f20f883e', '', '', 'active', '1511583342', '1511583342', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(135, 'Trường TH Cam Hiệp Bắc', 'CLTHCAMHIEPBAC', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511583363', '1511583363', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(136, 'UBND Xã Sơn Bình', 'KSXSONBINH', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511583374', '1511583374', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(137, 'Trường TH Cam Phước Tây 1', 'CLTHCAMPHUOCTAY1', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511583384', '1511583384', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(138, 'Trường TH Cam Thành Bắc', 'CLTHCAMTHANHBAC', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511583405', '1511583405', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(139, 'UBND Xã Sơn Hiệp', 'KSXSONHIEP', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511583412', '1511583412', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(140, 'Trường TH Cam Đức 2', 'CLTHCAMDUC2', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511583425', '1511583425', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(141, 'UBND Xã Sơn Trung', 'KSXSONTRUNG', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511583443', '1511583443', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(142, 'Trường TH Cam Đức 1', 'CLTHCAMDUC1', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511583445', '1511583445', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(143, 'Trường TH Cam Hiệp Nam', 'CLTHCAMHIEPNAM', 'e10adc3949ba59abbe56e057f20f883e', '', '', 'active', '1511583466', '1511583466', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(144, 'UBND Thị Trấn Tô Hạp', 'KSXTTTOHAP', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511583483', '1511583483', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(145, 'Trường TH Sơn Tân', 'CLTHSONTAN', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511583489', '1511583489', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(146, 'Trường TH Suối Tân', 'CLTHSUOITAN', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511583510', '1511583510', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(147, 'UBND Xã Ba Cụm Bắc', 'KSXBCUMBAC', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511583522', '1511583522', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(148, 'Trường TH Cam Hòa 1', 'CLTHCAMHOA1', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511583535', '1511583535', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(149, 'UBND Xã Ba Cụm Nam', 'KSXBCUMNAM', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511583556', '1511583556', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(150, 'Trường TH Cam Hòa 2', 'CLTHCAMHOA2', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511583557', '1511583557', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(151, 'Trường TH Cam Tân', 'CLTHCAMTAN', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511583584', '1511583584', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(152, 'Trường TH Cam Hải Đông', 'CLTHCAMHAIDONG', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511583611', '1511583611', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(153, 'Trường TH Suối Cát', 'CLTHSUOICAT', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511583632', '1511583632', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(154, 'Trường TH Cam Tân Sinh', 'CLTHCAMTANSINH', 'e10adc3949ba59abbe56e057f20f883e', '', '', 'active', '1511583652', '1511583652', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(155, 'Trường TH Cam Hải Tây', 'CLTHCAMHAITAY', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511583680', '1511583680', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(156, 'Truường TH Khánh Hòa - Jeju', 'CLTHKHJEJU', 'e10adc3949ba59abbe56e057f20f883e', '', '', 'active', '1511583703', '1511583703', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(157, 'Trường THCS Phan Đình Phùng', 'CLTHCSPHANDINHPHUNG', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511583727', '1511583727', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(158, 'Trường MN Anh Đào', 'KSMNANHDAO', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511583746', '1511583746', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(159, 'Trường THCS A.YERSIN', 'CLTHCSYERSIN', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511583755', '1511583755', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(160, 'Trường MN 1/6', 'KSMN1/6', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511583783', '1511583783', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(161, 'Trường MN Vành Khuyên', 'KSMNVANHKHUYEN', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511583823', '1511583823', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(162, 'Trường THCS Trần Quang Khải', 'CLTHCSTRANQUANGKHAI', 'e10adc3949ba59abbe56e057f20f883e', '', '', 'active', '1511583836', '1511583836', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(163, 'Trường THCS Quang Trung', 'CLTHCSQUANGTRUNG', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511583859', '1511583859', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(164, 'Trường MN Họa Mi', 'KSMNHOAMI', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511583871', '1511583871', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(165, 'Trường THCS Nguyễn Công Trứ', 'CLTHCSNGUYENCONGTRU', 'e10adc3949ba59abbe56e057f20f883e', '', '', 'active', '1511583881', '1511583881', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(166, 'Trường THCS Nguyễn Hiền', 'CLTHCSNGUYENHIEN', 'e10adc3949ba59abbe56e057f20f883e', '', '', 'active', '1511583901', '1511583901', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(167, 'Trường MN Sơn Ca', 'KSMNSONCA', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511583908', '1511583908', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(168, 'Trường THCS Lê Thánh Tôn', 'CLTHCSLETHANHTON', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511583925', '1511583925', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(169, 'Trường THCS Hùng Vương', 'CLTHCSHUNGVUONG', 'e10adc3949ba59abbe56e057f20f883e', '', '', 'active', '1511583943', '1511583943', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(170, 'Trường MN Hoàng Oanh', 'KSMNHOANGOANH', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511583950', '1511583950', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(171, 'Trường THCS Hoàng Hoa Thám', 'CLTHCSHOANGHOATHAM', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511583973', '1511583973', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(172, 'Trường MN Sao Mai', 'KSMNSAOMAI', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511583976', '1511583976', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(173, 'Trường THCS Nguyễn Trãi', 'CLTHCSNGUYENTRAI', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511583995', '1511583995', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(174, 'Trường MN Hoa Phượng', 'KSMNHOAPHUONG', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511584008', '1511584008', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(175, 'Trường THCS Lương Thế Vinh', 'CLTHCSLUONGTHEVINH', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511584015', '1511584015', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(176, 'Trường THCS Trần Đại Nghĩa', 'CLTHCSTRANDAINGHIA', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511584035', '1511584035', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(177, 'Trường MN Phong Lan', 'KSMNPHONGLAN', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511584056', '1511584056', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(178, 'Trường Mẫu Giáo Họa Mi', 'CLMGHOAMI', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511584068', '1511584068', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(179, 'Trường TH Sơn Lâm', 'KSTHSONLAM', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511584105', '1511584105', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(180, 'Trường TH Sơn Bình', 'KSTHSONBINH', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511584139', '1511584139', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(181, 'Trường TH Sơn Hiệp', 'KSTHSONHIEP', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511584229', '1511584229', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(182, 'Trường Tiểu học Tô Hạp', 'KSTHTOHAP', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511584293', '1511584293', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(183, 'Trường Tiểu học Sơn Trung', 'KSTHSONTRUNG', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511584334', '1511584334', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(184, 'Trường Tiểu học Ba cụm Bắc', 'KSTHCUMBAC', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511584364', '1511584364', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(185, 'Trường TH&THCS Thành Sơn', 'KSTHTHCSTHANHSON', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511584391', '1511584391', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(186, 'Trường TH&THCS Ba Cụm Nam', 'KSTHTHCSCUMNAM', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511584421', '1511584421', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(187, 'Trường TH&THCS Sơn Lâm', 'KSTHTHCSSONLAM', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511584448', '1511584448', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(188, 'Trường TH&THCS Sơn Bình', 'KSTHTHCSSONBINH', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511584474', '1511584474', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(189, 'Trường TH&THCS Ba Cụm Bắc', 'KSTHTHCSCUMBAC', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511584501', '1511584501', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(190, 'Trường THCS Tô Hạp', 'KSTHCSTOHAP', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511584528', '1511584528', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(191, 'Trường PT DTNT ', 'KSPTDTNT', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511584559', '1511584559', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(192, 'Phòng Tài Chính Kế Hoạch', 'KVPTCKH', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511584731', '1511584731', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(193, 'Văn phòng Huyện ủy', 'KVPVPHU', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511584768', '1511584768', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(194, 'Văn phòng HĐND&UBND huyện', 'KVPUBND', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511584831', '1511584831', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(195, 'Phòng Nội vụ', 'KVPNOIVU', 'e10adc3949ba59abbe56e057f20f883e', '', '', 'active', '1511584864', '1511584864', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(196, 'Phòng Lao động TB&XH', 'KVPLĐTBXH', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511584891', '1511584891', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(197, 'Phòng Tư pháp', 'KVPTUPHAP', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511584914', '1511584914', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(198, 'Thanh tra huyện', 'KVPTHANHTRA', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511584959', '1511584959', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(199, 'Phòng Nông nghiệp và PTNT', 'KVPNNPTNT', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511584990', '1511584990', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(200, 'Phòng Kinh tế và Hạ tầng', 'KVPKTHT', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511585012', '1511585012', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(201, 'Phòng Y tế', 'KVPYTE', 'e10adc3949ba59abbe56e057f20f883e', '', '', 'active', '1511585034', '1511585034', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(202, 'Phòng Giáo dục và Đào tạo', 'KVPGDĐT', 'e10adc3949ba59abbe56e057f20f883e', '', '', 'active', '1511585064', '1511585064', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(203, 'Phòng Tài nguyên và MT', 'KVPTNMT', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511585153', '1511585153', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(204, 'Phòng Văn hóa và Thông tin', 'KVPVHTT', 'e10adc3949ba59abbe56e057f20f883e', '', '', 'active', '1511585176', '1511585176', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(205, 'Phòng Dân tộc', 'KVPDANTOC', 'e10adc3949ba59abbe56e057f20f883e', '', '', 'active', '1511585198', '1511585198', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(206, 'Trung tâm Bồi dưỡng chính trị', 'KVPTTBDCT', 'e10adc3949ba59abbe56e057f20f883e', '', '', 'active', '1511585223', '1511585223', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(207, 'Hội Liên hiệp phụ nữ', 'KVPPHUNU', 'e10adc3949ba59abbe56e057f20f883e', '', '', 'active', '1511585248', '1511585248', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(208, 'Hội Nông dân', 'KVPNONGDAN', 'e10adc3949ba59abbe56e057f20f883e', '', '', 'active', '1511585269', '1511585269', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(209, 'Ủy ban MTTQVN huyện', 'KVPMTTQ', 'e10adc3949ba59abbe56e057f20f883e', '', '', 'active', '1511585289', '1511585289', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(210, 'Huyện đoàn', 'KVPĐOAN', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511585310', '1511585310', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(211, 'Hội cựu chiến binh', 'KVPHCCB', 'e10adc3949ba59abbe56e057f20f883e', '', '', 'active', '1511585335', '1511585335', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(212, 'Hội chữ thập đỏ', 'KVPTHAPDO', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511585359', '1511585359', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(213, 'Hội nạn nhân chất độc da cam điôxin', 'KVPCĐDC', 'e10adc3949ba59abbe56e057f20f883e', '', '', 'active', '1511585380', '1511585380', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(214, 'Hội người mù', 'KVPNGUOIMU', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511585401', '1511585401', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(215, 'Hội khuyến học', 'KVPKHOC', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511585424', '1511585424', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(216, 'Hội Người cao tuổi', 'KVPNCT', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511585444', '1511585444', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(217, 'Trung tâm Văn hóa thể thao', 'KVTTVHTT', 'e10adc3949ba59abbe56e057f20f883e', '', '', 'active', '1511585468', '1511585468', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(218, 'Nhà Thiếu nhi', 'KVPTHIEUNHI', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511585490', '1511585490', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(219, 'Đài Phát thanh truyền hình', 'KVPPTTH', 'e10adc3949ba59abbe56e057f20f883e', '', '', 'active', '1511585510', '1511585510', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(220, 'Trung tâm bảo trợ xã hội', 'KVPBTXH', 'e10adc3949ba59abbe56e057f20f883e', '', '', 'active', '1511585532', '1511585532', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(221, 'Trạm Khuyến nông', 'KVPTKNKL', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511585554', '1511585554', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(222, 'Trung tâm phát triển quỹ đất', 'KVPQUYĐAT', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511585575', '1511585575', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(223, 'Trung tâm dịch vụ thương mại', 'KVPTTDVTM', 'e10adc3949ba59abbe56e057f20f883e', '', '', 'active', '1511585598', '1511585598', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(224, 'Ban quản lý công trình công cộng và MT', 'KVPBQLCTCC', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511585619', '1511585619', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(225, 'UBND thị trấn', 'KVTHITRAN', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511585643', '1511585643', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(226, 'UBND xã Sông Cầu', 'KVXSONGCAU', 'e10adc3949ba59abbe56e057f20f883e', '', '', 'active', '1511585666', '1511585666', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(227, 'UBND xã Khánh Phú', 'KVXKHANHPHU', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511585690', '1511585690', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(228, 'UBND xã Khánh Thành', 'KVXKHANHTHANH', 'e10adc3949ba59abbe56e057f20f883e', '', '', 'active', '1511585713', '1511585713', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(229, 'UBND xã Cầu Bà', 'KVXCAUBA', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511585736', '1511585736', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(230, 'UBND xã Liên Sang ', 'KVXLIENSANG', 'e10adc3949ba59abbe56e057f20f883e', '', '', 'active', '1511585771', '1511585771', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(231, 'UBND xã Giang Ly', 'KVXGIANGLY', 'e10adc3949ba59abbe56e057f20f883e', '', '', 'active', '1511585799', '1511585799', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(232, 'UBND xã Sơn Thái', 'KVXSONTHAI', 'e10adc3949ba59abbe56e057f20f883e', '', '', 'active', '1511585824', '1511585824', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(233, 'UBND xã Khánh Thượng', 'KVXKHANHTHUONG', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511585846', '1511585846', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(234, 'UBND xã Khánh Nam', 'KVXKHANHNAM', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511585871', '1511585871', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(235, 'UBND xã Khánh Trung  ', 'KVXKHANHTRUNG', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511585891', '1511585891', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(236, 'UBND xã Khánh Đông ', 'KVXKHANHDONG', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511585914', '1511585914', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(237, 'UBND xã Khánh Bình', 'KVXKHANHBINH', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511585945', '1511585945', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(238, 'UBND xã Khánh Hiệp', 'KVXKHANHHIEP', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511585971', '1511585971', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(239, 'Trường Mầm non 2-8', 'KVMN2/8', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511707075', '1511707075', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(240, 'Trường Mầm non A Xây', 'KVMNAXAY', 'e10adc3949ba59abbe56e057f20f883e', '', '', 'active', '1511707102', '1511707102', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(241, 'Trường Mẫu giáo Hoa Lan', 'KVMGHOALAN', 'e10adc3949ba59abbe56e057f20f883e', '', '', 'active', '1511707128', '1511707128', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(242, 'Trường MG Hoa Phượng', 'KVMGHOAPHUONG', 'e10adc3949ba59abbe56e057f20f883e', '', '', 'active', '1511707156', '1511707156', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(243, 'Trường MG Hướng Dương', 'KVMGHUONGDUONG', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511707186', '1511707186', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(244, 'Trường Mẫu giáo Sen Hồng', 'KVMGSENHONG', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511707217', '1511707217', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(245, 'Trường MN Hoa Hồng', 'KVMNHOAHONG', 'e10adc3949ba59abbe56e057f20f883e', '', '', 'active', '1511707248', '1511707248', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(246, 'Trường MN Họa My', 'KVMNHOAMY', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511707277', '1511707277', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(247, 'Trường MN Hương Sen', 'KVMNHUONGSEN', 'e10adc3949ba59abbe56e057f20f883e', '', '', 'active', '1511707303', '1511707303', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(248, 'Trường MN Sơn Ca', 'KVMNSONCA', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511707331', '1511707331', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(249, 'Trường MN Sao Mai', 'KVMNSAOMAI', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511707356', '1511707356', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(250, 'Trường MN Trầm Hương', 'KVMNTRAMHUONG', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511707381', '1511707381', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(253, 'Trường MN Vành Khuyên', 'KVMNVANHKHUYEN', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511707433', '1511707433', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(254, 'Trường MN Anh Đào', 'KVMNANHDAO', 'e10adc3949ba59abbe56e057f20f883e', '', '', 'active', '1511707466', '1511707466', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(255, 'Trường MG Hoa Mai', 'KVMNHOAMAI', 'e10adc3949ba59abbe56e057f20f883e', '', '', 'active', '1511707493', '1511707493', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(256, 'Trường MN Hoa Phượng 1', 'KVMNHOAPHUONG1', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511707519', '1511707519', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(257, 'Trường MN Ngọc Lan', 'KVMNNGOCLAN', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511707544', '1511707544', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(258, 'Trường TH Cầu Bà', 'KVTHCAUBA', 'e10adc3949ba59abbe56e057f20f883e', '', '', 'active', '1511707573', '1511707573', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(259, 'Trường TH Giang Ly', 'KVTHGIANGLY', 'e10adc3949ba59abbe56e057f20f883e', '', '', 'active', '1511707603', '1511707603', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(260, 'Trường TH Khánh Bình', 'KVTHKHANHBINH', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511707632', '1511707632', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(261, 'Trường TH Khánh Hiệp', 'KVTHKHANHHIEP', 'e10adc3949ba59abbe56e057f20f883e', '', '', 'active', '1511707656', '1511707656', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(262, 'Trường TH Khánh Nam', 'KVTHKHANHNAM', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511707684', '1511707684', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(263, 'Trường Th Khánh Đông', 'KVTHKHANHDONG', 'e10adc3949ba59abbe56e057f20f883e', '', '', 'active', '1511707710', '1511707710', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(264, 'Trường TH Khánh Phú', 'KVTHKHANHPHU', 'e10adc3949ba59abbe56e057f20f883e', '', '', 'active', '1511707735', '1511707735', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(265, 'Trường TH Khánh Thành', 'KVTHKHANHTHANH', 'e10adc3949ba59abbe56e057f20f883e', '', '', 'active', '1511707759', '1511707759', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(266, 'Trường Th Khánh Thượng', 'KVTHKHANHTHUONG', 'e10adc3949ba59abbe56e057f20f883e', '', '', 'active', '1511707784', '1511707784', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(267, 'Trường TH Khánh Trung', 'KVTHKHANHTRUNG', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511707808', '1511707808', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(268, 'Trường TH Liên Sang', 'KVTHLIENSANG', 'e10adc3949ba59abbe56e057f20f883e', '', '', 'active', '1511707831', '1511707831', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(269, 'Trường TH Sông Cầu', 'KVTHSONGCAU', 'e10adc3949ba59abbe56e057f20f883e', '', '', 'active', '1511707857', '1511707857', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(270, 'Trường TH Sơn Thái', 'KVTHSONTHAI', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511707885', '1511707885', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(271, 'Trường TH Thị Trấn', 'KVTHTHITRAN', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511707919', '1511707919', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(272, 'Trường TH Khánh Hiệp 1', 'KVTHKHANHHIEP1', 'e10adc3949ba59abbe56e057f20f883e', '', '', 'active', '1511707947', '1511707947', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(273, 'Trường TH Khánh Phú 1', 'KVTHKHANHPHU1', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511707972', '1511707972', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(274, 'Trường THCS Lê Văn Tám', 'KVTHCSLVT', 'e10adc3949ba59abbe56e057f20f883e', '', '', 'active', '1511707997', '1511707997', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(275, 'Trường THCS Nguyễn Thái Bình', 'KVTHCSNTB', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511708022', '1511708022', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(276, 'Trường THCS Thị Trấn', 'KVTHCSTHITRAN', 'e10adc3949ba59abbe56e057f20f883e', '', '', 'active', '1511708046', '1511708046', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(277, 'Trường THCS Chu Văn An', 'KVTHCSCVA', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511708068', '1511708068', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(278, 'Trường THCS Nguyễn Bỉnh Khiêm', 'KVTHCSNBK', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511708094', '1511708094', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(279, 'Trường THCS Cao Văn Bé', 'KVTHCSCVB', 'e10adc3949ba59abbe56e057f20f883e', '', '', 'active', '1511708117', '1511708117', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(280, 'Trường THCS Dân tộc nội trú', 'KVTHCSDTNT', 'e10adc3949ba59abbe56e057f20f883e', '', '', 'active', '1511708141', '1511708141', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(281, 'Phòng Tài chính - Kế hoạch', 'VNPTCKH', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511708261', '1511708261', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(282, 'Văn phòng HĐND & UBND', 'VNUBND', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511708285', '1511708285', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(283, 'Phòng Tư pháp', 'VNPTUPHAP', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511708305', '1511708305', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(284, 'Phòng Quản lý đô thị', 'VNPQLDOTHI', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511708326', '1511708326', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(285, 'Phòng Kinh tế', 'VNPKINHTE', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511708347', '1511708347', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(286, 'Phòng Giáo dục và Đào tạo', 'VNGDĐT', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511708368', '1511708368', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(287, 'Phòng Y tế', 'VNPYTE', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511708393', '1511708393', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(288, 'Phòng LĐTBXH  ', 'VNPLĐTBXH', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511708424', '1511708424', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(289, 'Phòng Văn hóa thông tin', 'VNPVHTT', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511708445', '1511708445', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(290, 'Phòng Tài nguyên môi trường', 'VNPTNMT', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511708467', '1511708467', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(291, 'Phòng Nội vụ', 'VNPNOIVU', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511708488', '1511708488', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(292, 'Thanh tra', 'VNTHANHTRA', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511708509', '1511708509', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(293, 'Huyện ủy', 'VNHUYENUY', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511708531', '1511708531', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(294, 'Ủy ban Mặt trận Tổ quốc ', 'VNUBMTTQ', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511708566', '1511708566', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(295, 'Hội Chữ thập đỏ', 'VNHCHUTHAPDO', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511708587', '1511708587', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(296, 'Hội Đông y', 'VNHDONGY', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511708610', '1511708610', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(297, 'Huyện đoàn', 'VNHUYENDOAN', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511708633', '1511708633', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(298, 'Hội LH Phụ nữ', 'VNHLHPN', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511708655', '1511708655', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(299, 'Hội Nông dân', 'VNHNONGDAN', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511708679', '1511708679', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(300, 'Hội Cựu chiến binh', 'VNHCCB', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511708704', '1511708704', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(301, 'Đài phát thanh truyền hình', 'VNTTTH', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511708732', '1511708732', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(302, 'Trung tâm Phát triển Quỹ đất', 'VNTTPTQUYDAT', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511708757', '1511708757', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(303, 'Trung tâm Văn hóa thể thao', 'VNTTVHTT', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511708782', '1511708782', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(304, 'Nhà Thiếu nhi', 'VNNHATHIEUNHI', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511708806', '1511708806', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(305, 'Trung tâm Bồi dưỡng chính trị', 'VNTTBDCHINHTRI', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511708972', '1511708972', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(306, 'Ủy ban nhân dân thị trấn Vạn Giã', 'VNTTVANGIA', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511708996', '1511708996', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(307, 'Ủy ban nhân dân xã Đại Lãnh', 'VNXDAILANH', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511709018', '1511709018', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(308, 'Ủy ban nhân dân xã Vạn Bình', 'VNXVANBINH', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511709040', '1511709040', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(309, 'Ủy ban nhân dân xã Vạn Hưng', 'VNXCANHUNG', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511709071', '1511709071', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(310, 'Ủy ban nhân dân xã Vạn Khánh', 'VNXVANKHANH', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511709092', '1511709092', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(311, 'Ủy ban nhân dân xã Vạn Long', 'VNXVANLONG', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511709119', '1511709119', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(312, 'Ủy ban nhân dân xã Vạn Lương', 'VNXVANLUONG', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511709141', '1511709141', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(313, 'Ủy ban nhân dân xã Vạn Phú', 'VNXVANPHU', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511709163', '1511709163', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(314, 'Ủy ban nhân dân xã Vạn Phước', 'VNXVANPHUOC', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511709186', '1511709186', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(315, 'Ủy ban nhân dân xã Vạn Thạnh', 'VNXVANTHANH', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511709208', '1511709208', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(316, 'Ủy ban nhân dân xã Vạn Thắng', 'VNXVANTHANG', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511709233', '1511709233', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(317, 'Ủy ban nhân dân xã Vạn Thọ', 'VNXVANTHO', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511709256', '1511709256', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(318, 'Ủy ban nhân dân xã Xuân Sơn', 'VNXXUANSON', 'e10adc3949ba59abbe56e057f20f883e', '', '', 'active', '1511709280', '1511709280', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(319, 'Trường TH Đại lãnh 1', 'VNTHDAILANH1', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511709376', '1511709376', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(320, 'Trường TH Đại lãnh 2', 'VNTHDAILANH2', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511709404', '1511709404', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(321, 'Trường TH Vạn Thọ 1', 'VNTHVANTHO1', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511709427', '1511709427', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(322, 'Trường TH Vạn Thọ 2', 'VNTHVANTHO2', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511709453', '1511709453', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(323, 'Trường TH Vạn Phước 1', 'VNTHVANPHUOC1', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511709484', '1511709484', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(324, 'Trường TH Vạn Phước 2', 'VNTHVANPHUOC2', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511709513', '1511709513', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(325, 'Trường TH Vạn Long', 'VNTHVANLONG', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511709537', '1511709537', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(326, 'Trường TH Vạn Khánh 1', 'VNTHVANKHANH1', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511709561', '1511709561', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(327, 'Trường TH Vạn Khánh 2', 'VNTHVANKHANH2', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511709583', '1511709583', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(328, 'Trường TH Vạn Bình', 'VNTHVANBINH', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511709608', '1511709608', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(329, 'Trường TH Vạn Thắng 1', 'VNTHVANTHANG1', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511709632', '1511709632', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(330, 'Trường TH Vạn Thắng 2', 'VNTHVANTHANG2', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511709665', '1511709665', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(331, 'Trường TH Vạn Thắng 3', 'VNTHVANTHANG3', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511709687', '1511709687', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(332, 'Trường TH Vạn phú 1', 'VNTHVANPHU1', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511709714', '1511709714', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(333, 'Trường TH Vạn phú 2', 'VNTHVANPHU2', '0d9e556b3b9d2808794e7a762b5d0b8d', NULL, NULL, 'active', '1511709737', '1511709737', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(334, 'Trường TH Vạn phú 3', 'VNTHVANPHU3', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511709761', '1511709761', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL);
INSERT INTO `users` (`id`, `name`, `username`, `password`, `phone`, `email`, `status`, `madv`, `maxa`, `mahuyen`, `matinh`, `level`, `sadmin`, `permission`, `created_at`, `updated_at`) VALUES
(335, 'Trường TH Vạn Giã 1', 'VNTHVANGIA1', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511709787', '1511709787', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(336, 'Trường TH Vạn Giã 2', 'VNTHVANGIA2', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511709808', '1511709808', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(337, 'Trường TH Vạn Giã 3', 'VNTHVANGIA3', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511709831', '1511709831', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(338, 'Trường TH Vạn Lương 1', 'VNTHVANLUONG1', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511709859', '1511709859', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(339, 'Trường TH Vạn Lương 2', 'VNTHVANLUONG2', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511709882', '1511709882', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(340, 'Trường TH Vạn Hưng 1 ', 'VNTHVANHUNG1', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511709906', '1511709906', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(341, 'Trường TH Vạn Hưng 2', 'VNTHVANHUNG2', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511709940', '1511709940', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(342, 'Trường TH Vạn Hưng 3', 'VNTHVANHUNG3', '70d2fe6b8feb5bfc98dded8acf58f280', NULL, NULL, 'active', '1511709961', '1511709961', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(343, 'Trường TH Xuân Sơn', 'VNTHXUANSON', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511709984', '1511709984', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(344, 'Trường TH Vạn Thạnh 2', 'VNTHVANTHANH2', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511710009', '1511710009', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(345, 'Trường THCS Chi Lăng', 'VNTHCSCHILANG', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511710033', '1511710033', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(346, 'Trường THCS Lương Thế Vinh', 'VNTHCSLUONGTHEVINH', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511710058', '1511710058', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(347, 'Trường THCS Nguyễn Huệ', 'VNTHCSNGUYENHUE', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511710080', '1511710080', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(348, 'Trường THCS Trần Quốc Tuấn', 'VNTHCSTRANQUOCTUAN', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511710105', '1511710105', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(349, 'Trường Nguyễn Trung Trực', 'VNTHCSNGUYENTRUNGTRUC', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511710134', '1511710134', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(350, 'Trường THCS Nguyễn Bỉnh Khiêm', 'VNTHCSNGUYENBINHKHIEM', '9ba139af4caed79cafd36afcea227335', NULL, NULL, 'active', '1511710158', '1511710158', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(351, 'Trường THCS Trần Phú', 'VNTHCSTRANPHU', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511710196', '1511710196', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(352, 'Trường THCS Mê Linh', 'VNTHCSMELINH', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511710234', '1511710234', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(353, 'Trường THCS Văn Lang', 'VNTHCSVANLANG', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511710259', '1511710259', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(354, 'Trường THCS Đống Đa', 'VNTHCSDONGDA', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511710284', '1511710284', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(355, 'Trường THCS Lý Thường Kiệt', 'VNTHCSLYTHUONGKIET', '15b8e19faa715164196a4926e76f40e8', NULL, NULL, 'active', '1511710307', '1511710307', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(356, 'Trường THCS Hoa Lư', 'VNTHCSHOALU', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511710331', '1511710331', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(357, 'Trường PT cấp 1-2 Vạn Thạnh', 'VNPTCAP1-2VANTHANH', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511710355', '1511710355', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(358, 'Trường MN Bình Minh', 'VNMNBINHMINH', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511710377', '1511710377', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(359, 'Trường MG Họa My', 'VNMGHOAMY', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511710399', '1511710399', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(360, 'Trường MG Vạn Xuân', 'MNMGVANXUAN', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511710429', '1511710429', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(361, 'Trường MG Đại Lãnh', 'VNMGDAILANH', '28bbfe970ab065050a588251d6fa8c73', NULL, NULL, 'active', '1511710452', '1511710452', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(362, 'Trường MG Vạn Thọ', 'VNMGVANTHO', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511710477', '1511710477', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(363, 'Trường MG Vạn Phước', 'VNMGVANPHUOC', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511710520', '1511710520', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(364, 'Trường MG Vạn Long', 'VNMGVANLONG', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511710544', '1511710544', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(365, 'Trường MG Vạn Khánh', 'VNMGVANKHANH', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511710566', '1511710566', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(366, 'Trường MG Vạn Bình', 'VNMGVANBINH', '1c30f687c0a2357f08d572026b3c2d07', NULL, NULL, 'active', '1511710589', '1511710589', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(367, 'Trường MG Vạn Thắng', 'VNMGVANTHANG', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511710614', '1511710614', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(368, 'Trường MG Vạn Phú', 'VNMGVANPHU', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511710637', '1511710637', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(369, 'Trường MG Vạn giã', 'VNMGVANGIA', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511710657', '1511710657', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(370, 'Trường MG Vạn Lương', 'VNMGVANLUONG', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511710686', '1511710686', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(371, 'Trường MG Vạn Hưng', 'VNMGVANHUNG', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511710708', '1511710708', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(372, 'Phòng Tài chính-Kế hoạch', 'TPPTCKH', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511711090', '1511711090', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(373, 'Văn phòng HĐND&UBND TP', 'TPUBND', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511711113', '1511711113', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(374, 'Phòng Nội vụ', 'TPPNOIVU', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511711141', '1511711141', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(375, 'Thanh tra TP', 'TPTHANHTRA', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511711162', '1511711162', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(376, 'Phòng Tư pháp', 'TPPTUPHAP', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511711184', '1511711184', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(377, 'Phòng Kinh tế', 'TPPKINHTE', 'c4ca4238a0b923820dcc509a6f75849b', NULL, NULL, 'active', '1511711204', '1511711204', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(378, 'Phòng Lao động - TB&XH', 'TPPLĐTBXH', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511711225', '1511711225', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(379, 'Phòng Văn hóa và Thông tin', 'TPPVHTT', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511711246', '1511711246', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(380, 'Phòng Y tế', 'TPPYTE', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511711267', '1511711267', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(381, 'Phòng Giáo dục và Đào tạo ', 'TPPGDĐT', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511711288', '1511711288', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(382, 'Phòng Quản lý Đô thị', 'TPPQLDOTHI', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511711309', '1511711309', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(383, 'Phòng Tài nguyên và Môi trường ', 'TPPTNMT', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511711339', '1511711339', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(384, 'VP Thành ủy', 'TPVPTHANHUY', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511711393', '1511711393', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(385, 'Ủy ban mặt trận Tổ quốc TP', 'TPUBMTTQ', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511711424', '1511711424', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(386, 'Hội Cựu chiến binh', 'TPHCCB', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511711445', '1511711445', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(387, 'Hội Nông dân', 'TPHNONGDAN', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511711470', '1511711470', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(388, 'Hội Liên hiệp phụ nữ', 'TPHLHPN', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511711491', '1511711491', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(389, 'Thành Đoàn', 'TPTHANHDOAN', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511711516', '1511711516', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(390, 'Hội Đông y', 'TPHDONGY', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511711537', '1511711537', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(391, 'Hội Chữ thập đỏ', 'TPHCHUTHAPDO', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511711559', '1511711559', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(392, 'BQL Vịnh Nha Trang', 'TPBQLVINHNT', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511711591', '1511711591', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(393, 'Đội Thanh niên xung kích', 'TPDOITN', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511711614', '1511711614', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(394, 'Đội công tác chuyên trách giải tỏa', 'TPDOICTCHUYENTRACH', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511711639', '1511711639', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(395, 'Ban Quản lý dịch vụ công ích', 'TPBQLDVCONGICH', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511711660', '1511711660', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(396, 'Trung tâm phát triển quỹ đất', 'TPTTPTQUYDAT', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511711684', '1511711684', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(397, 'BQL chợ Phước Thái', 'TPBQLCHOPHUOCTHAI', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511711715', '1511711715', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(398, 'Trung tâm Bồi dưỡng chính trị', 'TPTTBDCHINHTRI', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511711738', '1511711738', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(399, 'Trung tâm Văn hóa - Thể thao', 'TPTTVHTT', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511711759', '1511711759', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(400, 'Đài Truyền thanh', 'TPDTT', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511711786', '1511711786', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(401, 'Chợ Xóm Mới', 'TPCHOXOMMOI', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511711817', '1511711817', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(402, 'Chợ Phương Sơn', 'TPCHOPHUONGSON', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511711839', '1511711839', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(403, 'Phường Lộc Thọ', 'TPPLOCTHO', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511711882', '1511711882', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(404, 'UBND Phường Ngọc Hiệp', 'TPPNGOCHIEP', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511711913', '1511711913', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(405, 'UBND Xã Phước Đồng', 'TPXPHUONGDONG', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511711978', '1511711978', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(406, 'UBND Phường Phước Hải', 'TPPPHUOCHAI', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511712001', '1511712001', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(407, 'UBND Phường Phước Hòa', 'TPPPHUOCHOA', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511712025', '1511712025', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(408, 'UBND Phường Phước Long', 'TPPPHUOCLONG', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511712049', '1511712049', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(409, 'UBND Phường Phước Tân', 'TPPPHUOCTAN', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511712079', '1511712079', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(410, 'UBND Phường Phước Tiến', 'TPPPHUOCTIEN', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511712113', '1511712113', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(411, 'UBND Phường Phương Sài', 'TPPPHUONGSAI', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511712138', '1511712138', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(412, 'UBND Phường Phương Sơn', 'TPPPHUONGSON', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511712162', '1511712162', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(413, 'UBND Phường Tân Lập', 'TPPTANLAP', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511712185', '1511712185', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(414, 'UBND Phường Vạn Thắng', 'TPPVANTHANG', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511712211', '1511712211', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(415, 'UBND Phường Vạn Thạnh', 'TPPVANTHANH', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511712239', '1511712239', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(416, 'UBND Phường Vĩnh Hải', 'TPPVINHHAI', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511712265', '1511712265', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(417, 'UBND Xã Vĩnh Hiệp ', 'TPXVINHHIEP', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511712288', '1511712288', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(418, 'UBND Phường Vĩnh Hòa', 'TPPVINHHOA', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511712314', '1511712314', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(419, 'UBND Xã Vĩnh Lương', 'TPXVINHLUONG', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511712340', '1511712340', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(420, 'UBND Xã Vĩnh Ngọc', 'TPXVINHNGOC', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511712369', '1511712369', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(421, 'UBND Phường Vĩnh Nguyên', 'TPPVINHNGUYEN', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511712392', '1511712392', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(422, 'UBND Phường Vĩnh Phước', 'TPPVINHPHUOC', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511712415', '1511712415', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(423, 'UBND Xã Vĩnh Phương', 'TPXVINHPHUONG', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511712443', '1511712443', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(424, 'UBND Xã Vĩnh Thái', 'TPXVINHTHAI', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511712468', '1511712468', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(425, 'UBND Xã Vĩnh Thạnh', 'TPXVINHTHANH', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511712494', '1511712494', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(426, 'UBND Phường Vĩnh Thọ', 'TPPVINHTHO', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511712517', '1511712517', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(427, 'UBND Xã Vĩnh Trung', 'TPXVINHTRUNG', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511712541', '1511712541', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(428, 'UBND Phường Vĩnh Trường', 'TPPVINHTRUONG', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511712567', '1511712567', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(429, 'UBND Phường Xương Huân', 'TPPXUONGHUAN', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511712592', '1511712592', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(430, 'Trường MN Lý Tự Trọng', 'TPMNLYTUTRONG', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511712693', '1511712693', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(431, 'Trường MN 3/2', 'TPMN3/2', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511712748', '1511712748', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(432, 'Trường MN Ngô Thời Nhiệm', 'TPMNNGOTHOINHIEM', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511712771', '1511712771', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(433, 'Trường MN Hồng Bàng', 'TPMNHONGBANG', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511712799', '1511712799', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(434, 'Trường MN Hồng Chiêm', 'TPMNHONGCHIEM', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511712842', '1511712842', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(435, 'Trường MN Hướng Dương', 'TPMNHUONGDUONG', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511712877', '1511712877', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(436, 'Trường MN Sao Biển', 'TPMNSAOBIEN', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511712946', '1511712946', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(437, 'Trường MN 8/3', 'TPMN8/3', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511712973', '1511712973', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(438, 'Trường MN Hoa Hồng', 'TPMNHOAHONG', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511713027', '1511713027', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(439, 'Trường MN 2/4', 'TPMN2/4', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511713053', '1511713053', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(440, 'Trường MN 20/10', 'TPMN20/10', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511713086', '1511713086', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(441, 'Trường MN Bình Khê', 'TPMNBINHKHE', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511713113', '1511713113', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(442, 'Trường MN Võ Trứ', 'TPMNVOTRU', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511713138', '1511713138', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(443, 'Trường MN Sơn Ca', 'TPMNSONCA', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511713164', '1511713164', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(444, 'Trường MN Hương Sen', 'TPMNHUONGSEN', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511713189', '1511713189', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(445, 'Trường MN Lộc Thọ', 'TPMNLOCTHO', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511713212', '1511713212', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(446, 'Trường MN Ngọc Hiệp', 'TPMNNGOCHIEP', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511713238', '1511713238', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(447, 'Trường MN Phước Đồng', 'TPMNPHUOCDONG', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511713263', '1511713263', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(448, 'Trường MN Phước Hải', 'TPMNPHUOCHAI', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511713288', '1511713288', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(449, 'Trường MN Phước Hòa', 'TPMNPHUOCHOA', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511713309', '1511713309', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(450, 'Trường MN Phước Long', 'TPMNPHUOCLONG', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511713333', '1511713333', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(451, 'Trường MN Phước Tân', 'TPMNPHUOCTAN', 'c4ca4238a0b923820dcc509a6f75849b', NULL, NULL, 'active', '1511713358', '1511713358', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(452, 'Trường MN Phước Tiến', 'TPMNPHUOCTIEN', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511713384', '1511713384', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(453, 'Trường MN Phương Sơn', 'TPMNPHUONGSON', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511713409', '1511713409', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(454, 'Trường MN Tân Lập', 'TPMNTANLAP', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511713441', '1511713441', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(455, 'Trường MN Vạn Thạnh', 'TPMNVANTHAN', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511713465', '1511713465', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(456, 'Trường MN Vạn Thắng', 'TPMNVANTHANG', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511713515', '1511713515', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(457, 'Trường MN Vĩnh Hải ', 'TPMNVINHHAI', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511713548', '1511713548', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(458, 'Trường MN Vĩnh Hiệp ', 'TPMNVINHHIEP', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511713581', '1511713581', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(459, 'Trường MN Vĩnh Hòa', 'TPMNVINHHOA', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511713604', '1511713604', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(460, 'Trường MN Vĩnh Lương ', 'TPMNVINHLUONG', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511713628', '1511713628', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(461, 'Trường MN Vĩnh Ngọc', 'TPMNVINHNGOC', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511713653', '1511713653', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(462, 'Trường MN Vĩnh Nguyên 1', 'TPMNVINHNGUYEN1', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511713684', '1511713684', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(463, 'Trường MN Vĩnh Nguyên 2', 'TPMNVINHNGUYEN2', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511713711', '1511713711', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(464, 'Trường MN Vĩnh Phước ', 'TPMNVINHPHUOC', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511713739', '1511713739', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(465, 'Trường MN Vĩnh Phương 1 ', 'TPMNVINHPHUONG1', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511713767', '1511713767', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(466, 'Trường MN Vĩnh Phương 2', 'TPMNVINHPHUONG2', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511713790', '1511713790', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(467, 'Trường MN Vĩnh Thái ', 'TPMNVINHTHAI', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511713814', '1511713814', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(468, 'Trường MN Vĩnh Thạnh ', 'TPMNVINHTHANH', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511713840', '1511713840', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(469, 'Trường MN Vĩnh Thọ', 'TPMNVINHTHO', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511713863', '1511713863', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(470, 'Trường MN Vĩnh Trung ', 'TPMNVINHTRUNG', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511713889', '1511713889', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(471, 'Trường MN Vĩnh Trường', 'TPMNVINHTRUONG', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511713912', '1511713912', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(472, 'Trường MN Xương Huân', 'TPMNXUONGHUAN', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511713934', '1511713934', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(473, 'Trường MN Phước Thịnh ', 'TPMNPHUOCTHINH', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511713957', '1511713957', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(474, 'Trường TH Vĩnh Lương 1', 'TPTHVINHLUONG1', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511713982', '1511713982', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(475, 'Trường TH Vĩnh Lương 2', 'TPTHVINHLUONG2', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511714008', '1511714008', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(476, 'Trường TH Vĩnh Hòa 1', 'TPTHVINHHOA1', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511714031', '1511714031', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(477, 'Trường TH Vĩnh Hòa 2', 'TPTHVINHHOA2', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511714068', '1511714068', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(478, 'Trường TH Vĩnh Hải 1', 'TPTHVINHHAI1', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511714098', '1511714098', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(479, 'Trường TH Vĩnh Hải 2', 'TPTHVINHHAI2', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511714124', '1511714124', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(480, 'Trường TH Vĩnh Thọ', 'TPTHVINHTHO', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511714157', '1511714157', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(481, 'Trường TH Vĩnh Phước 1', 'TPTHVINHPHUOC1', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511714264', '1511714264', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(482, 'Trường TH Vĩnh Phước 2', 'TPTHVINHPHUOC2', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511714300', '1511714300', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(483, 'Trường TH Vạn Thắng', 'TPTHVANTHANG', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511714323', '1511714323', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(484, 'Trường TH Vạn Thạnh ', 'TPTHVANTHANH', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511714346', '1511714346', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(485, 'Trường TH Phương Sài', 'TPTHPHUONGSAI', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511714376', '1511714376', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(486, 'Trường TH Phương Sơn', 'TPTHPHUONGSON', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511714404', '1511714404', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(487, 'Trường TH Xương Huân 1', 'TPTHXUONGHUAN1', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511714462', '1511714462', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(488, 'Trường TH Xương Huân 2', 'TPTHXUONGHUAN2', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511714497', '1511714497', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(489, 'Trường TH Lộc Thọ', 'TPTHLOCTHO', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511714539', '1511714539', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(490, 'Trường TH Phước Tiến', 'TPTHPHUOCTIEN', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511714562', '1511714562', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(491, 'Trường TH Tân Lập 1', 'TPTHTANLAP1', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511714588', '1511714588', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(492, 'Trường TH Tân Lập 2', 'TPTHTANLAP2', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511714668', '1511714668', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(493, 'Trường TH Phước Tân 1', 'TPTHPHUOCTAN1', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511714698', '1511714698', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(494, 'Trường TH Phước Tân 2', 'TPTHPHUOCTAN2', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511714739', '1511714739', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(495, 'Trường TH Phước Hòa 1', 'TPTHPHUOCHOA1', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511714764', '1511714764', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(496, 'Trường TH Phước Hải 1', 'TPTHPHUOCHAI1', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511714790', '1511714790', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(497, 'Trường TH Phước Hải 3', 'TPTHPHUOCHAI3', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511714813', '1511714813', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(498, 'Trường TH Phước Long 1', 'TPTHPHUOCLONG1', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511714843', '1511714843', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(499, 'Trường TH Phước Long 2', 'TPTHPHUOCLONG2', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511714873', '1511714873', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(500, 'Trường TH Vĩnh Trường', 'TPTHVINHTRUONG', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511714895', '1511714895', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(501, 'Trường TH Vĩnh Nguyên 1', 'TPTHVINHNGUYEN1', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511714917', '1511714917', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(502, 'Trường TH Vĩnh Nguyên 2', 'TPTHVINHNGUYEN2', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511714941', '1511714941', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(503, 'Trường TH Ngọc Hiệp', 'TPTHNGOCHIEP', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511714979', '1511714979', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(504, 'Trường TH Vĩnh Hiệp', 'TPTHVINHHIEP', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511715012', '1511715012', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(505, 'Trường TH Vĩnh Ngọc ', 'TPTHVINHNGOC', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511715036', '1511715036', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(506, 'Trường TH Vĩnh Thái', 'TPTHVINHTHAI', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511715077', '1511715077', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(507, 'Trường TH Vĩnh Thạnh', 'TPTHVINHTHANH', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511715101', '1511715101', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(508, 'Trường TH Vĩnh Trung', 'TPTHVINHTRUNG', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511715127', '1511715127', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(509, 'Trường TH Vĩnh Phương 1', 'TPTHVINHPHUONG1', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511715155', '1511715155', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(510, 'Trường TH Vĩnh Phương 2', 'TPTHVINHPHUONG2', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511715207', '1511715207', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(511, 'Trường TH Phước Thịnh', 'TPTHPHUOCTHINH', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511715240', '1511715240', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(512, 'Trường TH Phước Hòa 2', 'TPTHPHUOCHOA2', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511715267', '1511715267', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(513, 'Trường TH Phước Đồng', 'TPTHPHUOCDONG', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511715290', '1511715290', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(514, 'Trường TH Vĩnh Nguyên 3', 'TPTHVINHNGUYEN3', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511715314', '1511715314', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(515, 'Trường THCS Nguyễn Viết Xuân', 'TPTHCSNGUYENVIETXUAN', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511715338', '1511715338', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(516, 'Trường THCS Mai Xuân Thưởng', 'TPTHCSMAIXUANTHUONG', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511715367', '1511715367', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(517, 'Trường THCS Lý Thái Tổ', 'TPTHCSLYTHAITO', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511715389', '1511715389', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(518, 'Trường THCS Lý Thường Kiệt', 'TPTHCSLYTHUONGKIET', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511715409', '1511715409', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(519, 'Trường THCS Nguyễn Khuyến', 'TPTHCSNGUYENKHUYEN', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511715431', '1511715431', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(520, 'Trường THCS Trưng Vương ', 'TPTHCSTRUNGVUONG', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511715455', '1511715455', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(521, 'Trường THCS Thái Nguyên', 'TPTHCSTHAINGUYEN', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511715481', '1511715481', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(522, 'Trường THCS Võ Văn Ký', 'TPTHCSVOVANKY', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511715504', '1511715504', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(523, 'Trường THCS Phan Sào Nam', 'TPTHCSPHANSAONAM', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511715526', '1511715526', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(524, 'Trường THCS Âu Cơ', 'TPTHCSAUCO', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511715550', '1511715550', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(525, 'Trường THCS Trần Nhật Duật', 'TPTHCSTRANNHATDUAT', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511715575', '1511715575', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(526, 'Trường THCS Nguyễn Hiền', 'TPTHCSNGUYENHIEN', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511715605', '1511715605', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(527, 'Trường THCS Bùi Thị Xuân', 'TPTHCSBUITHIXUAN', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511715626', '1511715626', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(528, 'Trường THCS Võ Thị Sáu', 'TPTHCSVOTHISAU', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511715646', '1511715646', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(529, 'Trường THCS Cao Thắng', 'TPTHCSCAOTHANG', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511715667', '1511715667', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(530, 'Trường THCS Lương Thế Vinh', 'TPTHCSLUONGTHEVINH', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511715689', '1511715689', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(531, 'Trường THCS Nguyễn Công Trứ', 'TPTHCSNGUYENCONGTRU', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511715711', '1511715711', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(532, 'Trường THCS Nguyễn Đình Chiểu', 'TPTHCSNGUYENDINHCHIEU', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511715734', '1511715734', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(533, 'Trường THCS Lê Thanh Liêm', 'TPTHCSLETHANHLIEM', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511715755', '1511715755', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(534, 'Trường THCS Trần Quốc Toản', 'TPTHCSTRANQUOCTOAN', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511715775', '1511715775', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(535, 'Trường THCS Lam Sơn', 'TPTHCSLAMSON', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511715796', '1511715796', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(536, 'Trường THCS Bạch Đằng', 'TPTHCSBACHDANG', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511715822', '1511715822', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(537, 'Trường THCS Trần Hưng Đạo ', 'TPTHCSTRANHUNGDAO', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511715849', '1511715849', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(538, 'Trường THCS Lương Định Của', 'TPTHCSLUONGDINHCUA', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511715871', '1511715871', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(539, 'Trường THCS Yersin', 'TPTHCSYERSIN', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511715892', '1511715892', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(540, 'Văn phòng HĐND và UBND', 'CRPUBND', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511748129', '1511748129', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(541, 'Phòng Tư Pháp', 'CRPTUPHAP', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511748204', '1511748204', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(542, 'Phòng Tài chính - Kế hoạch', 'CRPTCKH', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511748256', '1511748256', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(543, 'Phòng Quản lý Đô thị', 'CRPQLDT', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511748283', '1511748283', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(544, 'Phòng Kinh Tế', 'CRPKINHTE', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511748314', '1511748314', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(545, 'Phòng Giáo Dục Và Đào Tạo', 'CRPGDĐT', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511748355', '1511748355', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(546, 'Phòng Y Tế', 'CRPYTE', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511748383', '1511748383', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(547, 'Phòng Lao Động Thương Binh Xã Hội', 'CRPLĐTBXH', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511748419', '1511748419', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(549, 'Phòng Tài Nguyên và Môi Trường', 'CRPTNMT', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511748480', '1511748480', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(550, 'Phòng Nội Vụ', 'CRPNOIVU', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511748519', '1511748519', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(551, 'Thanh Tra Thành Phố', 'CRPTHANHTRA', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511748552', '1511748552', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(552, 'Phòng Dân Tộc', 'CRPDANTOC', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511748604', '1511748604', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(553, 'Văn Phòng Thành Uỷ', 'CRPVPTU', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511748635', '1511748635', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(554, 'Ủy Ban Mặt Trận Tổ Quốc Việt Nam', 'CRPMTTQ', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511748663', '1511748663', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(555, 'Đoàn Thanh Niên Cộng Sản Hồ Chí Minh', 'CRPĐTNCSHCM', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511748708', '1511748708', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(556, 'Hội Liên Hiệp Phụ Nữ', 'CRPPHUNU', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511748743', '1511748743', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(557, 'Hội Nông Dân', 'CRPNONGDAN', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511748769', '1511748769', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(558, 'Hội Cựu Chiến Binh', 'CRPHCCB', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511748792', '1511748792', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(559, 'Hội Chữ Thập Đỏ', 'CRPCTD', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511748838', '1511748838', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(560, 'Hội Người Mù', 'CRPNGUOIMU', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511748876', '1511748876', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(561, 'Hội Đông Y', 'CRPDONGY', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511748897', '1511748897', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(562, 'Hội Khuyến Học', 'CRPKHUYENHOC', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511748923', '1511748923', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(563, 'Hội Nạn Nhân Chất Độc Da Cam', 'CRPCĐDC', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511748959', '1511748959', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(564, 'Hội Người Cao Tuổi', 'CRPNCT', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511748989', '1511748989', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(565, 'Trung Tâm Văn Hóa Thể Thao', 'CRPVHTT', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511749018', '1511749018', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(566, 'Nhà Văn Hóa Thiêu Nhi', 'CRPTHIEUNHI', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511749043', '1511749043', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(567, 'Đài Truyền Thanh Truyền Hình', 'CRPTTTH', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511749069', '1511749069', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(568, 'Trạm Khuyến Nông Khuyến Lâm', 'CRPTKNKL', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511749120', '1511749120', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(569, 'Đội Thanh Niên Xung Kích', 'CRPTHANHNIEN', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511749146', '1511749146', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(570, 'Trung Tâm Bồi Dưỡng Chính Trị', 'CRPTTBDCT', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511749165', '1511749165', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(571, 'UBND Xã Cam Thịnh Tây', 'CRXCAMTHINHTAY', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511749199', '1511749199', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(572, 'UBND Xã Cam Lập', 'CRXCAMLAP', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511749224', '1511749224', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(573, 'UBND Xã Cam Bình', 'CRXCAMBINH', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511749249', '1511749249', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(574, 'UBND Xã Cam Bình Nam', 'CRXCAMTHANHNAM', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511749273', '1511749273', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(575, 'UBND Xã Cam Phước Đông', 'CRXCAMPHUOCDONG', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511749299', '1511749299', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(576, 'UBND Xã Cam Thịnh Đông', 'CRXCAMTHINHDONG', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511749322', '1511749322', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(577, 'UBND Phường Ba Ngòi', 'CRPBANGOI', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511749349', '1511749349', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(578, 'UBND Phường Cam Lộc', 'CRPCAMLOC', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511749379', '1511749379', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(579, 'UBND Phường Cam Lợi', 'CRPCAMLOI', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511749410', '1511749410', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(580, 'UBND Phường Cam Phúc Bắc', 'CRPCAMPHUCBAN', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511749436', '1511749436', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(581, 'UBND Phường Cam Phúc Nam', 'CRPCAMPHUCNAM', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511749463', '1511749463', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(582, 'UBND Phường Cam Phú', 'CRPCAMPHU', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511749488', '1511749488', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(583, 'UBND Phường Cam Nghĩa', 'CRPCAMNGHIA', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511749513', '1511749513', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(584, 'UBND Phường Cam Thuận', 'CRPCAMTHUAN', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511749538', '1511749538', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(585, 'UBND Phường Cam Linh', 'CRPCAMLINH', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511749557', '1511749557', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(586, 'Trường Mầm Non Hoa Mai', 'CRMNHOAMAI', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511749596', '1511749596', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(587, 'Trường Mẫu Giáo 2/4', 'CRMG2/4', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511749623', '1511749623', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(588, 'Trường Mẫu Giáo Cam Thịnh Tây', 'CRMGCAMTHINHTAY', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511749658', '1511749658', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(589, 'Trường Mẫu Giáo Cam Lập', 'CRMGCAMLAP', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511749690', '1511749690', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(590, 'Trường Mẫu Giáo Cam Bình', 'CRMGCAMBINH', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511749714', '1511749714', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(591, 'Trường Mầm Non Trường Sa', 'CRMNTRUONGSA', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511749745', '1511749745', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(592, 'Trường Mẫu Giáo Ba Ngòi', 'CRMGBANGOI', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511749771', '1511749771', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(593, 'Trường Maauc Giáo Cam Lộc', 'CRMGCAMLOC', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511749794', '1511749794', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(594, 'Trường Mẫu Giáo Cam Thành Nam', 'CRCAMTHANHNAM', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511749819', '1511749819', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(595, 'Trường Mẫu Giáo Cam Lợi', 'CRMGCAMLOI', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511749842', '1511749842', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(596, 'Trường Mẫu Giáo Cam Phúc Bắc', 'CRMGCAMPHUCBAC', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511749881', '1511749881', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(597, 'Trường Mẫu Giáo Cam Phúc nam', 'CRCAMPHUCNAM', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511750448', '1511750448', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(598, 'Trường Mẫu Giáo Cam Phú', 'CRMGCAMPHU', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511750470', '1511750470', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(599, 'Trường Mẫu Giáo Cam Nghĩa', 'CRMGCAMNGHIA', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511750500', '1511750500', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(600, 'Trường Mẫu Giáo Cam Phước Đông', 'CRMGCAMPHUOCDONG', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511750525', '1511750525', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(601, 'Trường Mẫu Giáo Cam Thịnh Đông', 'CRMGCAMTHINHDONG', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511750551', '1511750551', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(602, 'Trường Mẫu Giáo Cam Thuận', 'CRMGCAMTHUAN', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511750578', '1511750578', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(603, 'Trường Mẫu Giáo Cam Linh', 'CRMGCAMLINH', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511750602', '1511750602', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(604, 'Trường Mầm Non Căn Cứ', 'CRMNCANCU', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511750627', '1511750627', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(605, 'Trường TH Cam Thành Nam', 'CRTHCAMTHANHNAM', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511750659', '1511750659', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(606, 'Trường Tiểu Học Cam Nghĩa 1', 'CRTHCAMNGHIA1', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511750686', '1511750686', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(607, 'Trường Tiểu Học Cam Nghĩa 2', 'CRTHCAMNGHIA2', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511750708', '1511750708', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(608, 'Trường Tiểu Học Cam Lộc 2', 'CRTHCAMLOC2', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511750734', '1511750734', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(609, 'Trường Tiểu Học Cam Bình', 'CRTHCAMBINH', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511750756', '1511750756', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(610, 'Trường TH Cam Thịnh Đông', 'CRTHCAMTHINHDONG', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511750783', '1511750783', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(611, 'Trường Tiểu Học Cam Phúc Bắc 2', 'CRTHCAMPHUCBAC2', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511750814', '1511750814', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(612, 'Trường Tiểu Học Ba Ngòi', 'CRTHBANGOI', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511750835', '1511750835', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(613, 'Trường Tiểu Học Cam Linh 2', 'CRTHCAMLINH2', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511750858', '1511750858', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(614, 'Trường Tiểu Học Cam Phước Đông 1', 'CRTHCAMPHUOCDONG1', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511750892', '1511750892', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(615, 'Trường Tiểu Học Cam Phước Đông 2', 'CRTHCAMPHUOCDONG2', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511750914', '1511750914', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(616, 'Trường Tiểu Học Cam Phúc Nam', 'CRTHCAMPHUCNAM', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511750940', '1511750940', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(617, 'Trường Tiểu Học Cam Phúc Bắc 1', 'CRTHCAMPHUCBAC1', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511750973', '1511750973', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(618, 'Trường Tiểu Học Cam Thịnh 1', 'CRTHCAMTHINH1', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511751002', '1511751002', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL);
INSERT INTO `users` (`id`, `name`, `username`, `password`, `phone`, `email`, `status`, `madv`, `maxa`, `mahuyen`, `matinh`, `level`, `sadmin`, `permission`, `created_at`, `updated_at`) VALUES
(619, 'Trường Tiểu Học Cam Linh 1', 'CRTHCAMLINH1', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511751026', '1511751026', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(620, 'Trường Tiểu Học Cam Lợi', 'CRTHCAMLOI', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511751056', '1511751056', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(621, 'Trường Tiểu Học Cam Lộc 1', 'CRTHCAMLOI1', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511751085', '1511751085', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(622, 'Trường Tiểu Học Cam Phú', 'CRTHCAMPHU', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511751107', '1511751107', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(623, 'Trường Tiểu Học Cam Thuận', 'CRTHCAMTHUAN', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511751130', '1511751130', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(624, 'Trường Tiểu Học Cam Thịnh Tây 1', 'CRTHCAMTHINHTAY1', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511751165', '1511751165', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(626, 'Trường Tiểu Học Cam Thịnh Tây 2', 'CRTHCAMTHINHTAY2', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511751227', '1511751227', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(627, 'Trường Tiểu Học Căn Cứ', 'CRTHCANCU', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511751250', '1511751250', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(628, 'Trường THCS Nguyễn Thị Minh Khai', 'CRTHCSMINHKHAI', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511751277', '1511751277', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(629, 'Trường THCS Nguyễn Du', 'CRTHCSNGUYENDU', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511751308', '1511751308', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(630, 'Trường THCS Nguyễn Khuyến', 'CRTHCSNGUYENKHUYEN', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511751351', '1511751351', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(631, 'Trường THCS Lê Hồng Phong', 'CRTHCSHONGPHONG', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511751372', '1511751372', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(635, 'Trường THCS Trần Phú', 'CRTHCSTRANPHU', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511751528', '1511751528', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(636, 'Trường THCS Nguyễn Trung Trực', 'CRTHCSTRUNGTRUC', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511751559', '1511751559', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(638, 'Trường THCS Cam Thịnh Tây', 'CRTHCSCAMTHINHTAY', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511751637', '1511751637', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(639, 'Trường TH-THCS Bình Hưng', 'CRTHTHCSBINHHUNG', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511751661', '1511751661', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(640, 'Trường TH-THCS Cam Lập', 'CRTHTHCSCAMLAP', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511751683', '1511751683', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(641, 'Trường Phổ Thông Dân Tộc Nội Trú', 'CRPTDTNTRU', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511751714', '1511751714', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(642, 'Văn phòng HĐND và UBND', 'DKPUBND', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511751832', '1511751832', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(643, 'Phòng Tư Pháp', 'DKPTUPHAP', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511751864', '1511751864', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(644, 'Phòng Tài chính - Kế hoạch', 'DKPTCKH', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511751883', '1511751883', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(645, 'Phòng Quản lý đô thị', 'DKPQLĐT', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511751915', '1511751915', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(646, 'Phòng Kinh Tế', 'DKPKINHTE', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511751960', '1511751960', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(647, 'Phòng Giáo Dục & Đào Tạo', 'DKPGDĐT', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511751987', '1511751987', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(648, 'Phòng Y Tế', 'DKPYTE', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511752013', '1511752013', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(649, 'Phòng Lao Động Thương Binh Và Xã Hội', 'DKPLĐTBXH', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511752056', '1511752056', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(651, 'Phòng Tài Nguyên Và Môi Trường', 'DKPTNMT', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511752418', '1511752418', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(652, 'Phòng Nội Vụ', 'DKPNOIVU', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511752436', '1511752436', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(653, 'Thanh Tra Huyện', 'DKPTHANHTRA', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511752458', '1511752458', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(654, 'Văn Phòng Huyện Ủy', 'DKPVPHU', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511752483', '1511752483', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(655, 'Ủy Ban Mặt Trận Tổ Quốc Việt Nam', 'DKPUBMT', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511752524', '1511752524', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(656, 'Hội Nông Dân', 'DKPNONGDAN', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511752546', '1511752546', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(657, 'Hội Phụ Nữ', 'DKPLHPN', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511752595', '1511752595', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(658, 'Hội Cựu Chiến Binh', 'DKPHCCB', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511752620', '1511752620', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(659, 'Đoàn Thanh Niên Cộng Sản Hồ Chí Minh', 'DKPTHANHNIEN', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511752639', '1511752639', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(660, 'Hội Người Cao Tuổi', 'DKPCAOTUOI', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511752673', '1511752673', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(661, 'Trung Tâm Văn Hóa Thể Thao', 'DKPVHTT', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511752693', '1511752693', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(662, 'Đài Truyền Thanh Truyền Hình', 'DKPPTTH', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511752713', '1511752713', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(663, 'Ban Quản Lý Công Trình Công Cộng', 'DKPCTCC', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511752778', '1511752778', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(664, 'Ban Quản Lý Dự Án Các Công Trình Xây Dựng', 'DKPBQLCTXD', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511752822', '1511752822', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(665, 'Trung Tâm Phát Triển Quỹ Đất', 'DKPQUYDAT', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511752857', '1511752857', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(666, 'Hội Nạn nhân CĐDC Dioxin', 'DKPCĐDC', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511752883', '1511752883', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(667, 'Hội Người Mù', 'DKPNGUOIMU', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511752906', '1511752906', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(668, 'Hội Chữ Thập Đỏ', 'DKPTHAPDO', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511752930', '1511752930', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(669, 'Hội Đông Y', 'DKPDONGY', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511752954', '1511752954', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(670, 'Trung Tâm Bồi Dưỡng Chính Trị', 'DKPTTBDCT', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511752971', '1511752971', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(671, 'Nhà Văn Hóa Thiêu Nhi', 'DKPTHIEUNHI', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511752986', '1511752986', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(672, 'UBND Xã Diên An', 'DKXDIENAN', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511753020', '1511753020', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(673, 'UBND Xã Diên Toàn', 'DKXDIENTOAN', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511753042', '1511753042', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(674, 'UBND Xã Diên Thạnh', 'DKXDIENTHACH', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511753061', '1511753061', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(675, 'UBND Xã Diên Lạc', 'DKXDIENLAC', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511753086', '1511753086', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(676, 'UBND Xã Diên Hoà', 'DKXDIENHOA', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511753108', '1511753108', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(677, 'UBND Xã Diên Bình', 'DKXDIENBINH', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511753135', '1511753135', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(678, 'UBND Xã Diên Phước', 'DKXDIENPHUOC', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511753226', '1511753226', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(679, 'UBND Xã Diên Lộc', 'DKXDIENLOC', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511753248', '1511753248', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(680, 'UBND Xã Diên Thọ', 'DKXDIENTHO', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511753268', '1511753268', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(681, 'UBND Xã Diên Phú', 'DKXDIENPHU', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511753288', '1511753288', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(682, 'UBND Xã Diên Điền', 'DKXDIENDIEN', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511753315', '1511753315', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(683, 'UBND Xã Diên Sơn', 'DKXDIENSON', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511753338', '1511753338', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(684, 'UBNd Xã Diên Lâm', 'DKXDIENLAM', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511753359', '1511753359', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(685, 'UBND Xã Diên Tân', 'DKXDIENTAN', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511753383', '1511753383', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(686, 'UBND Xã Diên Đồng', 'DKXDIENDONG', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511753409', '1511753409', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(687, 'UBND Xã Diên Xuân', 'DKXDIENXUAN', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511753429', '1511753429', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(688, 'UBND Xã Suối Hiệp', 'DKXSUOIHIEP', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511753449', '1511753449', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(689, 'UBND Xã Suối Tiên', 'DKXSUOITIEN', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511753469', '1511753469', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(690, 'UBND Thị Trấn Diên Khánh', 'DKTTDK', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511753503', '1511753503', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(691, 'Trường THCS Chu Văn An', 'CRTHCSVANAN', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511754065', '1511754065', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(692, 'Trường THCS Nguyễn Văn Trỗi', 'CRTHCSVANTROI', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511754160', '1511754160', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(693, 'Trường THCS Phan Chu Chinh', 'CRTHCSCHUTRINH', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511754209', '1511754209', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(694, 'Trường THCS Nguyễn Trọng Kỷ', 'CRTHCSTRONGKY', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511754283', '1511754283', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(695, 'Trường Mầm Non Hoa Phượng', 'DKMNHOAPHUONG', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511754405', '1511754405', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(696, 'Trường Mầm Non Diên Đồng ', 'DKMNDIENDONG', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511754430', '1511754430', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(697, 'Trường Mầm Non Diên Tân', 'DKMNDIENTAN', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511754460', '1511754460', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(698, 'Trường Mầm Non Suối Tiên ', 'DKMNSUOITIEN', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511754599', '1511754599', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(699, 'Trường Mầm Non Diên Thọ', 'DKMNDIENTHO', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511754623', '1511754623', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(700, 'Trường Mầm Non Diên Phước', 'DKMNDIENPHUOC', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511754652', '1511754652', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(701, 'Trường Mầm Non Diên An', 'DKMNDIENAN', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511754695', '1511754695', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(702, 'Trường Mầm Non Diên Điền', 'DKMNDIENDIEN', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511754730', '1511754730', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(703, 'Trường Mầm Non Diên Phú', 'DKMNDIENPHU', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511754755', '1511754755', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(704, 'Trường Mầm Non Diên Xuân', 'DKMNDIENXUAN', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511754776', '1511754776', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(705, 'Trường Mầm Non Diên Lâm', 'DKMNDIENLAM', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511754817', '1511754817', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(706, 'Trường Mầm Non Diên Lộc', 'DKMNDIENLOC', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511754839', '1511754839', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(707, 'Trường Mầm Non Diên Hòa', 'DKMNDIENHOA', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511754871', '1511754871', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(708, 'Trường Mầm Non Diên Bình', 'DKMNDIENBINH', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511754894', '1511754894', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(709, 'Trường Mầm Non Diên Lạc', 'DKMNDIENLAC', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511754920', '1511754920', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(710, 'Trường Mầm Non Diên Thạnh', 'DKMNDIENTHANH', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511754951', '1511754951', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(711, 'Trường Mầm Non Diên Toàn', 'DKMNDIENTOAN', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511754978', '1511754978', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(712, 'Trường Mầm Non Thị Trấn Diên Khánh', 'DKMNTHITRAN', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511755047', '1511755047', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(713, 'Trường Mầm Non Diên Sơn', 'DKMNDIENSON', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511755074', '1511755074', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(714, 'Trường Mầm Non Suối Hiệp', 'DKMNSUOIHIEP', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511755100', '1511755100', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(715, 'Trường Tiểu học Diên Thạnh', 'DKTHDIENTHANH', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511755125', '1511755125', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(716, 'Trường Tiểu học Diên Hòa', 'DKTHDIENHOA', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511755150', '1511755150', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(717, 'Trường Tiểu học Diên Phước ', 'DKTHDIENPHUOC', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511755173', '1511755173', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(718, 'Trường Tiểu học Diên Lộc', 'DKTHDIENLOC', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511755207', '1511755207', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(719, 'Trường Tiểu học Diên Phú 1', 'DKTHDIENPHU1', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511755229', '1511755229', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(720, 'Trường Tiểu học Diên Phú 2', 'DKTHDIENPHU2', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511755264', '1511755264', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(721, 'Trường Tiểu học Suối Hiệp 1', 'DKTHSUOIHIEP1', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511755488', '1511755488', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(722, 'Trường Tiểu học Thị Trấn 1', 'DKTHTHITRAN1', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511755516', '1511755516', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(723, 'Trường Tiểu học Diên Lạc', 'DKTHDIENLAC', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511755539', '1511755539', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(724, 'Trường Tiểu học Diên Thọ', 'DKTHDIENTHO', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511755563', '1511755563', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(725, 'Trường Tiểu học Diên Điền', 'DKTHDIENDIEN', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511755583', '1511755583', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(726, 'Trường Tiểu học TH- THCS Diễn Tân', 'DKTHCSDIENTAN', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511755618', '1511755618', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(727, 'Trường Tiểu học Thị Trấn Diên Khánh 2', 'DKTHTHITRAN2', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511755655', '1511755655', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(728, 'Trường Tiểu học Diên An 1', 'DKTHDIENAN1', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511755679', '1511755679', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(729, 'Trường Tiểu học Diên An 2', 'DKTHDIENAN2', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511755700', '1511755700', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(730, 'Trường Tiểu học Diên Toàn', 'DKTHDIENTOAN', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511755731', '1511755731', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(731, 'Trường Tiểu học Diên Bình', 'DKTHDIENBINH', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511755757', '1511755757', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(732, 'Trường Tiểu học Diên Sơn 1', 'DKTHDIENSON1', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511755781', '1511755781', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(733, 'Trương Tiểu học Diên Sơn 2', 'DKTHDIENSON2', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511755807', '1511755807', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(734, 'Trường Tiểu học Diên Lâm ', 'DKTHDIENLAM', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511755829', '1511755829', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(735, 'Trường Tiểu học Diên Đồng', 'DKTHDIENĐONG', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511755855', '1511755855', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(736, 'Trường Tiểu học Suối  Hiệp 2', 'DKTHSUOIHIEP2', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511755879', '1511755879', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(737, 'Trường Tiểu học Suối Tiên', 'DKTHSUOITIEN', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511755901', '1511755901', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(738, 'Trường Tiểu học Diên Xuân', 'DKTHDIENXUAN', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511755924', '1511755924', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(739, 'Trường Tiểu học Diên Xuân 1', 'DKTHDIENXUAN1', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511755948', '1511755948', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(740, 'Trường Trung học cơ sở Phan Chu  Trinh ', 'DKTHCSPCT', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511755986', '1511755986', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(741, 'Trường Trung học cơ sở Trịnh Phong ', 'DKTHCSTP', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511756008', '1511756008', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(742, 'Trường Trung học cơ sở Nguyễn Du ', 'DKTHCSND', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511756033', '1511756033', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(743, 'Trường Trung học cơ sở Trần  Quang Khải ', 'DKTHCSQK', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511756061', '1511756061', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(744, 'Trường Trung học cơ sở Ngô Quyền ', 'DKTHCSNQ', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511756082', '1511756082', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(745, 'Trường Trung học cơ sở Nguyễn Hụê ', 'DKTHCSNH', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511756103', '1511756103', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(746, 'Trường Trung học cơ sở Mạc Đỉnh Chi ', 'DKTHCSMĐC', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511756125', '1511756125', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(747, 'Trường Trung học cơ sở Trần nhân Tông ', 'DKTHCSTNT', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511756150', '1511756150', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(748, 'Trường Trung học cơ sở Đinh Bộ Lĩnh ', 'DKTHCSĐBL', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511756176', '1511756176', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(749, 'Trường Trung học cơ sở Trần Đại Nghĩa ', 'DKTHCSTĐN', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511756200', '1511756200', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(750, 'Văn phòng HĐND và UBND', 'NHUBND', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511756370', '1511756370', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(751, 'Phòng Nội Vụ', 'NHPNOIVU', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511756392', '1511756392', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(752, 'Phòng Tài chính - Kế hoạch', 'NHPTCKH', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511756414', '1511756414', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(753, 'Phòng Kinh Tế', 'NHPKINHTE', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511756433', '1511756433', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(754, 'Phòng Quản Lý Đô Thị', 'NHPQLDOTHI', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511756460', '1511756460', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(755, 'Phòng Tư Pháp', 'NHPTUPHAP', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511756486', '1511756486', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(756, 'Thanh Tra Huyện', 'NHTHANHTRA', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511756517', '1511756517', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(757, 'Phòng Tài Nguyên - Môi Trường', 'NHPTNMT', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511756545', '1511756545', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(758, 'Phòng Lao động Thương Binh Xã Hội', 'NHPLĐXH', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511756578', '1511756578', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(759, 'Phòng Giáo Dục Và Đào Tạo', 'NHPGDĐT', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511756606', '1511756606', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(760, 'Phòng Văn Hóa Thông Tin', 'NHPVHTT', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511756633', '1511756633', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(761, 'Phòng Y Tế', 'NHPYTE', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511756655', '1511756655', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(762, 'Phòng Dân Tộc', 'NHPDANTOC', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511756680', '1511756680', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(763, 'Văn Phòng Huyện Ủy', 'NHHUYENUY', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511756766', '1511756766', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(764, 'Ủy Ban Mặt Trận Tổ Quốc Việt Nam', 'NHUBMTTQ', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511756814', '1511756814', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(765, 'Đoàn Thanh Niên Cộng Sản Hồ Chí Minh', 'NHTHIDOAN', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511756839', '1511756839', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(766, 'Hội Phụ Nữ', 'NHLHPHUNU', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511756863', '1511756863', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(767, 'Hội Nông Dân', 'NHHNONGDAN', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511756884', '1511756884', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(768, 'Hội Cựu Chiến Binh', 'NHHCCB', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511756905', '1511756905', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(769, 'Hội Đông Y', 'NHHDONGY', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511756927', '1511756927', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(770, 'Hội chữ Thập Đỏ', 'NHHCHUTHAPDO', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511756951', '1511756951', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(771, 'Hội Người Cao Tuổi', 'NHHNCT', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511756977', '1511756977', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(772, 'Hội Nạn nhân chất độc da cam', 'NHHCHATDOCDACAM', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511757001', '1511757001', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(773, 'Hội Người Mù', 'NHHNGUOIMU', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511757021', '1511757021', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(774, 'Hội Khuyến Học', 'NHHKHUYENHOC', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511757043', '1511757043', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(775, 'Đài Truyền Thanh Truyền Hình', 'NHTTTH', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511757060', '1511757060', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(776, 'Trung Tâm Văn Hóa Thể Thao', 'NHTTVHTT', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511757077', '1511757077', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(777, 'Nhà Văn Hóa Thiêu Nhi', 'NHNHATHIEUNHI', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511757097', '1511757097', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(778, 'Trung Tâm Bồi Dưỡng Chính Trị', 'NHTTBDCHINHTRI', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511757113', '1511757113', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(779, 'Tổ quản lý trật tự đô thị', 'NHQLDOTHI', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511757139', '1511757139', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(780, 'Trạm Khuyến Nông Khuyến Lâm', 'NHTRAMKHUYNNONG', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511757170', '1511757170', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(781, 'Trung Tâm Phát Triển Quỹ Đất', 'NHTTPTQUYDAT', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511757216', '1511757216', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(782, 'UBND phường Ninh Diêm', 'NHPNINHDIEM', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511757264', '1511757264', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(783, 'UBND phường Ninh Đa', 'NHPNINHDA', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511757284', '1511757284', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(784, 'UBND phường Ninh Giang', 'NHPNINHGIANG', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511757304', '1511757304', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(785, 'UBND phường Ninh Hà', 'NHPNINHHA', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511757323', '1511757323', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(786, 'UBND phường Ninh Hải', 'NHPNINHHAI', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511757342', '1511757342', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(787, 'UBND phường Ninh Hiệp', 'NHPNINHHIEP', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511757362', '1511757362', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(788, 'UBND phường Ninh Thuỷ', 'NHPNINHTHUY', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511757409', '1511757409', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(789, 'UBND xã Ninh An', 'NHXNINHAN', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511757433', '1511757433', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(790, 'UBND xã Ninh Bình', 'NHXNINHBINH', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511757472', '1511757472', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(791, 'UBND xã Ninh Đông', 'NHXNINHDONG', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511757492', '1511757492', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(792, 'UBND xã Ninh Hưng', 'NHXNINHHUNG', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511757512', '1511757512', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(793, 'UBND xã Ninh Ích', 'NHXNINHICH', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511757529', '1511757529', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(794, 'UBND xã Ninh Lộc', 'NHXNINHLOC', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511766477', '1511766477', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(795, 'UBND xã Ninh Phú', 'NHXNINHPHU', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511766499', '1511766499', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(796, 'UBND xã Ninh Phụng', 'NHXNINHPHUNG', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511766528', '1511766528', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(797, 'UBND xã Ninh Phước', 'NHXNINHPHUOC', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511766550', '1511766550', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(798, 'UBND xã Ninh Sim', 'NHXNINHSIM', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511766575', '1511766575', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(799, 'UBND xã Ninh Sơn', 'NHXNINHSON', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511766604', '1511766604', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(800, 'UBND xã Ninh Quang', 'NHXNINHQUANG', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511766625', '1511766625', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(801, 'UBND xã Ninh Tân', 'NHXNINHTAN', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511766646', '1511766646', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(802, 'UBND xã Ninh Tây', 'NHXNINHTAY', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511766664', '1511766664', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(803, 'UBND xã Ninh Thân', 'NHXNINHTHAN', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511766686', '1511766686', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(804, 'UBND xã Ninh Thọ', 'NHXNINHTHO', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511766705', '1511766705', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(805, 'UBND xã Ninh Thượng', 'NHXNINHTHUONG', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511766729', '1511766729', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(806, 'UBND xã Ninh Trung', 'NHXNINHTRUNG', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511766751', '1511766751', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(807, 'UBND xã Ninh Vân', 'NHXNINHVAN', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511766771', '1511766771', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(808, 'UBND xã Ninh Xuân', 'NHXNINHXUAN', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511766791', '1511766791', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(809, 'Trường Tiểu học Ninh  An', 'NHTHNINHAN', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511766838', '1511766838', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(810, 'Trường Tiểu học Ninh  Bình', 'NHTHNINHBINH', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511766864', '1511766864', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(811, 'Trường Tiểu học Ninh  Diêm', 'NHTHNINHDIEM', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511766892', '1511766892', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(812, 'Trường Tiểu học  số 1 Ninh Đa', 'NHTHSO1NINHDA', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511766918', '1511766918', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(813, 'Trường Tiểu học  số 2 Ninh Đa', 'NHTHSO2NINHDA', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511766948', '1511766948', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(814, 'Trường Tiểu học Ninh  Đông', 'NHTHNINHDONG', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511766975', '1511766975', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(815, 'Trường Tiểu học Ninh  Giang', 'NHTHNINHGIANG', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511766996', '1511766996', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(816, 'Trường Tiểu học Ninh  Hà', 'NHTHNINHHA', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511767021', '1511767021', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(817, 'Trường Tiểu học Ninh  Hải', 'NHTHNINHHAI', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511767047', '1511767047', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(818, 'Trường Tiểu học số 1 Ninh  Hiệp', 'NHTHSO1NINHHIEP', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511767077', '1511767077', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(819, 'Trường Tiểu học số 2 Ninh  Hiệp', 'NHTHSO2NINHHIEP', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511767104', '1511767104', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(820, 'Trường Tiểu học số 3 Ninh  Hiệp', 'NHTHSO3NINHHIEP', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511767126', '1511767126', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(821, 'Trường Tiểu học Ninh  Hưng', 'NHTHNINHHUNG', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511767159', '1511767159', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(822, 'Trường Tiểu học số 1 Ninh  Ích ', 'NHTHSO1NINHICH', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511767185', '1511767185', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(823, 'Trường Tiểu học số 2 Ninh  Ích ', 'NHTHSO2NINHICH', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511767209', '1511767209', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(824, 'Trường Tiểu học Ninh  Lộc', 'NHTHNINHLOC', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511767233', '1511767233', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(825, 'Trường Tiểu học Ninh  Phú', 'NHTHNINHPHU', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511767293', '1511767293', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(826, 'Trường Tiểu học số 1 Ninh  Phụng', 'NHTHSO1NINHPHUNG', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511767366', '1511767366', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(827, 'Trương Tiểu học số 2 Ninh  Phụng', 'NHTHSO2NINHPHUNG', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511767421', '1511767421', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(828, 'Trường Tiểu học Ninh  Phước', 'NHTHNINHPHUOC', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511767449', '1511767449', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(829, 'Trường Tiểu học Ninh  Sim', 'NHTHNINHSIM', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511767477', '1511767477', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(830, 'Trường Tiểu học Ninh  Sơn', 'NHTHNINHSON', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511767499', '1511767499', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(831, 'Trường Tiểu học số 1 Ninh  Quang', 'NHTHSO1NINHQUANG', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511767521', '1511767521', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(832, 'Trường Tiểu học số 2 Ninh  Quang', 'NHTHSO2NINHQUANG', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511767545', '1511767545', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(833, 'Trường Tiểu học Ninh  Tân', 'NHTHNINHTAN', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511767566', '1511767566', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(834, 'Trường Tiểu học Ninh  Thân ', 'NHTHNINHTHAN', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511767589', '1511767589', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(835, 'Trường Tiểu học Ninh  Thọ', 'NHTHNINHTHO', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511767637', '1511767637', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(836, 'Trường Tiểu học Ninh  Thuỷ', 'NHTHNINHTHUY', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511767658', '1511767658', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(837, 'Trường Tiểu học Ninh  Thượng', 'NHTHNINHTHUONG', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511767682', '1511767682', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(838, 'Trường Tiểu học Ninh  Trung', 'NHTHNINHTRUNG', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511767704', '1511767704', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(839, 'Trường Tiểu học Ninh  Vân', 'NHTHNINHVAN', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511767727', '1511767727', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(840, 'Trường Tiểu học số 1 Ninh  Xuân', 'NHTHSO1NINHXUAN', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511767752', '1511767752', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(841, 'Trường Tiểu học số 2 Ninh  Xuân', 'NHTHSO2NINHXUAN', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511767774', '1511767774', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(842, 'Trường Mầm non Ninh  An', 'NHMNNINHAN', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511767799', '1511767799', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(843, 'Trường Mầm non Ninh  Bình', 'NHMNNINHBINH', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511767823', '1511767823', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(844, 'Trường Mầm non Ninh  Diêm', 'NHMNNINHDIEM', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511767844', '1511767844', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(845, 'Trường Mầm non Ninh  Đa', 'NHMNNINHDA', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511767865', '1511767865', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(846, 'Trường Mầm non Ninh  Đông', 'NHMNNINHDONG', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511767886', '1511767886', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(847, 'Trường Mầm non Ninh  Giang', 'NHMNNINHGIANG', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511767907', '1511767907', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(848, 'Trường Mầm non Ninh  Hà', 'NHMNNINHHA', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511767929', '1511767929', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(849, 'Trường Mầm non Ninh  Hải', 'NHMNNINHHAI', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511767951', '1511767951', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(850, 'Trường Mầm non Hoa Sữa ', 'NHMNHOASUA', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511767972', '1511767972', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(851, 'Trường Mẫu giáo Hướng Dương', 'NHMGHUONGDUONG', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511767997', '1511767997', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(852, 'Trường Mẫu giáo 2/9 ', 'NHMG2/9', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511768021', '1511768021', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(853, 'Trường Mầm non Ninh  Hưng', 'NHMNNINHHUNG', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511768070', '1511768070', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(854, 'Trường Mầm non Ninh  ích', 'NHMNNINHICH', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511768093', '1511768093', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(855, 'Trường Mầm non Ninh  Lộc', 'NHMNNINHLOC', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511768114', '1511768114', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(856, 'Trường Mầm non Ninh  Phú', 'NHMNNINHPHU', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511768139', '1511768139', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(857, 'Trường THCS Chu Văn An', 'NHTHCSCHUVANAN', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511768191', '1511768191', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(858, 'Trường THCS Nguyễn Văn  Cừ', 'NHTHCSNGUYENVANCU', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511768282', '1511768282', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(859, 'Trường THCS Nguyễn Thị Định', 'NHTHCSNGUYENTHIDINH', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511768308', '1511768308', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(860, 'Trường THCS Trương  Định', 'NHTHCSTRUONGDINH', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511768331', '1511768331', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(861, 'Trường THCS Đinh Tiên  Hoàng', 'NHTHCSDINHTIENHOANG', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511768353', '1511768353', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(862, 'Trường THCS Trần Quang  Khải', 'NHTHCSTRANQUANGHAI', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511768372', '1511768372', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(863, 'Trường THCS Lý Thường Kiệt', 'NHTHCSLUTHUONGKIET', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511768422', '1511768422', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(864, 'Trường THCS Phạm Ngũ  Lão', 'NHTHCSPHAMNGULAO', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511768449', '1511768449', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(865, 'Trường THCS Hàm  Nghi', 'NHTHCSHAMNGHI', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511768482', '1511768482', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(866, 'Trường THCS Ngô Thì Nhậm', 'NHTHCSNGOTHINHAM', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511768504', '1511768504', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(867, 'Trường THCS Lê Hồng Phong', 'NHTHCSLEHONGPHONG', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511768524', '1511768524', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(868, 'Trường THCS Trịnh Phong', 'NHTHCSTRINHPHONG', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511768547', '1511768547', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(869, 'Trường THCS Trần Phú', 'NHTHCSTRANPHU', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511768569', '1511768569', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(870, 'Trường THCS Nguyễn Tri  Phương', 'NHTHCSNGUYENTRIPHUONG', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511768592', '1511768592', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(871, 'Trường THCS Võ Thị  Sáu', 'NHTHCSVOTHISAU', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511768616', '1511768616', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(872, 'Trường THCS Tô Hiến  Thành', 'NHTHCSTOHIENTHANH', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511768639', '1511768639', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(873, 'Trường THCS Phạm Hồng  Thái', 'NHTHCSPHAMHONGTHAI', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511768661', '1511768661', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(874, 'Trường THCS Nguyễn Gia Thiều', 'NHTHCSNGUYENGIATHIEU', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511768685', '1511768685', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(875, 'Trường THCS Quang  Trung', 'NHTHCSQUANGTRUNG', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511768705', '1511768705', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(876, 'Trường THCS Nguyễn Trung  Trực', 'NHTHCSNGUYENTRUNGTRUC', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511768731', '1511768731', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(877, 'Trường THCS Trần Quốc Tuấn', 'NHTHCSTRANQUOCTUAN', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511768754', '1511768754', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(878, 'Trường THCS Trần Quốc  Toản', 'NHTHCSTRANQUOCTOAN', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511768779', '1511768779', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(879, 'Trường THCS Lê Thánh  Tông', 'NHTHCSLETHANHTONG', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511768812', '1511768812', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(880, 'Trường THCS Ngô Gia Tự', 'NHTHCSNGOGIATU', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511768835', '1511768835', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(881, 'Trường THCS Đào Duy  Từ', 'NHTHCSDAODUYTU', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511768863', '1511768863', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(882, 'Trường THCS Nguyễn Phan Vinh', 'NHTHCSNGUYENPHANVINH', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511768912', '1511768912', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(883, 'Trường THCS Hùng  Vương', 'NHTHCSHUNGVUONG', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511768935', '1511768935', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(884, 'Trường Tiểu học&THCS Ninh Tây', 'NHTH&THCSNINHTAY', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511768960', '1511768960', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(885, 'Trường Phổ thông dân tộc nội trú', 'NHPTDTNOITRU', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511768998', '1511768998', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(886, 'Trường Mầm non Ninh  Phụng', 'NHMNNINHPHUNG', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511769027', '1511769027', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(887, 'Trường Mầm non Ninh  Phước', 'NHMNNINHPHUOC', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511769046', '1511769046', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(888, 'Trường Mầm non Ninh  Sim', 'NHMNNINHSIM', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511769070', '1511769070', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(889, 'Trường Mẫu giáo Ninh Sơn', 'NHMGNINHSON', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511769093', '1511769093', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(890, 'Trường Mầm non Ninh  Quang', 'NHMNNINHQUANG', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511769117', '1511769117', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(891, 'Trường Mẫu giáo Ninh Tân', 'NHMGNINHTAN', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511769142', '1511769142', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(892, 'Trường Mầm non Ninh  Tây', 'NHMNNINHTAY', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511769167', '1511769167', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(893, 'Trường Mầm non Ninh  Thân ', 'NHMNNINHTHAN', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511769190', '1511769190', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(894, 'Trường Mầm non Ninh  Thọ', 'NHMNNINHTHO', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511769214', '1511769214', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(895, 'Trường Mầm non Ninh  Thuỷ', 'NHMNNINHTHUY', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511769237', '1511769237', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(896, 'Trường Mẫu giáo Ninh  Thượng', 'NHMGNINHTHUONG', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511769263', '1511769263', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(897, 'Trường Mầm non Ninh  Trung', 'NHMNNINHTRUNG', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511769315', '1511769315', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(898, 'Trường Mẫu giáo Ninh  Vân', 'NHMGNINHVAN', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511769336', '1511769336', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(899, 'Trường Mầm non Ninh  Xuân', 'NHMNNINHXUAN', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511769359', '1511769359', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(900, 'Trường Mầm non 1/ 5 ', 'NHMN1/5', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1511769383', '1511769383', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(907, 'triển khai phần mềm', 'tkpm', 'c4ca4238a0b923820dcc509a6f75849b', NULL, NULL, 'active', '1511881968', '1511881968', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(908, 'Triển khai', 'tk', 'c4ca4238a0b923820dcc509a6f75849b', NULL, NULL, 'active', '1511881999', '1511881999', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(909, 'triển khai 1', 'tk1', 'c4ca4238a0b923820dcc509a6f75849b', NULL, NULL, 'active', '1511882018', '1511882018', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL);
INSERT INTO `users` (`id`, `name`, `username`, `password`, `phone`, `email`, `status`, `madv`, `maxa`, `mahuyen`, `matinh`, `level`, `sadmin`, `permission`, `created_at`, `updated_at`) VALUES
(910, 'triển khai 2', 'tk2', 'c4ca4238a0b923820dcc509a6f75849b', NULL, NULL, 'active', '1511886443', '1511886443', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(911, 'A Tuan', 'nguyentuan', 'e10adc3949ba59abbe56e057f20f883e', '', '', 'active', '1511584731', '1511584731', NULL, NULL, '0', 'NULL', '{\"data\":{\"units\":0,\"create\":1,\"edit\":1,\"delete\":1,\"reports\":0},\"system\":{\"information\":1,\"create\":0,\"edit\":0,\"delete\":0},\"report\":{\"view\":1,\"create\":0,\"edit\":0,\"delete\":0}}', NULL, NULL),
(912, 'Phòng TC huyện', 'tc', 'c4ca4238a0b923820dcc509a6f75849b', NULL, NULL, 'active', '1512224836', '1512224836', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(913, 'Xã a', 'x1', 'c4ca4238a0b923820dcc509a6f75849b', NULL, NULL, 'active', '1512224853', '1512224853', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(914, 'Phòng GDĐT', 'gd', 'c4ca4238a0b923820dcc509a6f75849b', NULL, NULL, 'active', '1512224885', '1512224885', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(915, 'Trường 1', 't1', 'c20ad4d76fe97759aa27a0c99bff6710', NULL, NULL, 'active', '1512224907', '1512224907', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(916, 'trường 2', 't2', 'c4ca4238a0b923820dcc509a6f75849b', NULL, NULL, 'active', '1512231041', '1512231041', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(917, 'Sở Tài chính Khánh Hòa', 'khstc', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1512391746', '1512391746', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(918, 'Sở Văn hóa và Thể thao', 'khsvhtt', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1512393993', '1512393993', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(919, 'Sở Tài chính Khánh Hòa', 'khthso', 'e10adc3949ba59abbe56e057f20f883e', '', '', 'active', '1512391746', '1512391746', NULL, NULL, '0', 'sa', '{\"data\":{\"units\":0,\"create\":1,\"edit\":1,\"delete\":1,\"reports\":0},\"system\":{\"information\":1,\"create\":0,\"edit\":0,\"delete\":0},\"report\":{\"view\":1,\"create\":0,\"edit\":0,\"delete\":0}}', NULL, NULL),
(920, 'Sở Tài chính Khánh Hòa', 'khthstc', 'e10adc3949ba59abbe56e057f20f883e', '', '', 'active', '1512391746', '1512391746', NULL, NULL, '0', 'sa', '{\"data\":{\"units\":0,\"create\":1,\"edit\":1,\"delete\":1,\"reports\":0},\"system\":{\"information\":1,\"create\":0,\"edit\":0,\"delete\":0},\"report\":{\"view\":1,\"create\":0,\"edit\":0,\"delete\":0}}', NULL, NULL),
(922, 'Sở Tài chính Khánh Hòa', 'khstcct', 'e10adc3949ba59abbe56e057f20f883e', '', '', 'active', '1512391746', '1512391746', NULL, NULL, '0', 'NULL', '{\"data\":{\"units\":0,\"create\":1,\"edit\":1,\"delete\":1,\"reports\":0},\"system\":{\"information\":1,\"create\":0,\"edit\":0,\"delete\":0},\"report\":{\"view\":1,\"create\":0,\"edit\":0,\"delete\":0}}', NULL, NULL),
(924, 'Văn phòng Sở', 'VanphongSNN', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1519985070', '1519985070', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(925, 'Chi cục Thủy Lợi', 'Chicucthuyloi', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1519985119', '1519985119', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(926, 'Trung tâm nước sinh hoạt', 'TrungtamnuocSH', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1519985144', '1519985144', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(927, 'BQL RPH Cam Lâm', 'banQLRPHcamlam', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1519985212', '1519985212', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(928, 'Chi cục kiểm lâm', 'TonghopchicucKL', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1519985253', '1519985253', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(929, 'Văn phòng Chi cục Kiểm Lâm', 'VanphongCCKL', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1519985280', '1519985280', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(930, 'Đội Kiểm lâm cơ động và Phòng chống cháy rừng', 'Doikiemlamcodong', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1519985299', '1519985299', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(931, 'Hạt Kiểm lâm Cam Lâm', 'HatKLcamlam', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1519985319', '1519985319', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(932, 'Sở Nông Nghiệp và Phát Triển NT', 'tonghopSNN', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1520042304', '1520042304', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(933, 'Quản trị', 'quantri', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', NULL, NULL, NULL, NULL, 'SA', 'SA', NULL, NULL, NULL),
(934, 'TC huyện Tổng hợp', 'THTC', 'c4ca4238a0b923820dcc509a6f75849b', '', '', 'active', '1512224836', '1512224836', NULL, NULL, '0', 'NULL', '{\"data\":{\"units\":\"1\",\"reports\":\"1\"}}', NULL, NULL),
(935, 'Tổng hợp phòng tài chính Thành Phố', 'THPTCTP', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1533870771', '1533870771', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(936, 'Tổng hợp phòng giáo dục thành phố', 'THPGDTP', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1533870811', '1533870811', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(937, 'Tổng hợp phòng Tài chính kê hoạch huyện Cam Lâm', 'THCLPTC', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1534123065', '1534123065', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(938, 'Tổng hợp phòng giáo dục & đào tạo huyện Cam Lâm', 'THCLPGD', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1534123114', '1534123114', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(939, 'Tổng hợp phòng Tài chính kế hoạch huyện Cam Ranh', 'THCRPTC', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1534123186', '1534123186', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(940, 'Tổng hợp phòng giáo dục & đào tạo huyện Cam Ranh', 'THCRPGD', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1534123212', '1534123212', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(941, 'Tổng hợp phòng Tài chính kê hoạch huyện Diêm Khánh ', 'THDKPTC', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1534123257', '1534123257', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(942, 'Tổng hợp phòng giáo dục & đào tạo huyện Diêm Khánh', 'THDKPGD', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1534123294', '1534123294', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(943, 'Tổng hợp phòng Tài chính kê hoạch huyện Khánh Sơn', 'THKSPTC', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1534123336', '1534123336', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(944, 'Tổng hợp phòng giáo dục & đào tạo huyện Khánh Sơn', 'THKSPGD', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1534123367', '1534123367', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(945, 'Tổng hợp phòng Tài chính kế hoạch huyện Khánh Vĩnh', 'THKVPTC', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1534123458', '1534123458', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(946, 'Tổng hợp phòng giáo dục & đào tạo huyện Khánh Vĩnh', 'THKVPGD', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1534123482', '1534123482', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(947, 'Tổng hợp phòng Tài chính kế hoạch Thị Xã Ninh Hòa', 'THTXNHPTC', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1534123523', '1534123523', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(948, 'Tổng hợp phòng giáo dục & đào tạo Thị Xã Ninh Hòa', 'THTXNHPGD', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1534123552', '1534123552', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(949, 'Tổng hợp phòng Tài chính kế hoạch huyện Vạn Ninh', 'THVNPTC', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1534123582', '1534123582', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(950, 'Tổng hợp phòng giáo dục & đào tạo huyện Vạn Ninh', 'THVNPGD', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1534123604', '1534123604', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(951, 'Phòng Tài Chính Huyện', 'ptc', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1534385271', '1534385271', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL),
(952, 'Huyện a', 'Ha', 'e10adc3949ba59abbe56e057f20f883e', NULL, NULL, 'active', '1534385336', '1534385336', NULL, NULL, NULL, 'NULL', NULL, NULL, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `bangluong`
--
ALTER TABLE `bangluong`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `bangluong_mabl_unique` (`mabl`);

--
-- Indexes for table `bangluong_ct`
--
ALTER TABLE `bangluong_ct`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `bangluong_phucap`
--
ALTER TABLE `bangluong_phucap`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `chitieubienche`
--
ALTER TABLE `chitieubienche`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `dmchucvucq`
--
ALTER TABLE `dmchucvucq`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `dmchucvucq_macvcq_unique` (`macvcq`);

--
-- Indexes for table `dmchucvud`
--
ALTER TABLE `dmchucvud`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `dmchucvud_macvd_unique` (`macvd`);

--
-- Indexes for table `dmdantoc`
--
ALTER TABLE `dmdantoc`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `dmdantoc_dantoc_unique` (`dantoc`);

--
-- Indexes for table `dmdiabandbkk`
--
ALTER TABLE `dmdiabandbkk`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `dmdiabandbkk_chitiet`
--
ALTER TABLE `dmdiabandbkk_chitiet`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `dmdonvi`
--
ALTER TABLE `dmdonvi`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `dmdonvi_madv_unique` (`madv`);

--
-- Indexes for table `dmdonvibaocao`
--
ALTER TABLE `dmdonvibaocao`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `dmkhoipb`
--
ALTER TABLE `dmkhoipb`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `dmngachcc`
--
ALTER TABLE `dmngachcc`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `dmnguonkinhphi`
--
ALTER TABLE `dmnguonkinhphi`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `dmphanloaicongtac`
--
ALTER TABLE `dmphanloaicongtac`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `dmphanloaicongtac_baohiem`
--
ALTER TABLE `dmphanloaicongtac_baohiem`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `dmphanloaict`
--
ALTER TABLE `dmphanloaict`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `dmphanloaidonvi`
--
ALTER TABLE `dmphanloaidonvi`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `dmphongban`
--
ALTER TABLE `dmphongban`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `dmphongban_mapb_unique` (`mapb`);

--
-- Indexes for table `dmphucap`
--
ALTER TABLE `dmphucap`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `dmphucap_mapc_unique` (`mapc`);

--
-- Indexes for table `dmphucap_donvi`
--
ALTER TABLE `dmphucap_donvi`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `dmthongtuquyetdinh`
--
ALTER TABLE `dmthongtuquyetdinh`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `dmtieumuc_default`
--
ALTER TABLE `dmtieumuc_default`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `dsnangluong`
--
ALTER TABLE `dsnangluong`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `dsnangluong_manl_unique` (`manl`);

--
-- Indexes for table `dsnangluong_chitiet`
--
ALTER TABLE `dsnangluong_chitiet`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `dsnangluong_chitiet_manl_unique` (`manl`);

--
-- Indexes for table `dsthuyenchuyen`
--
ALTER TABLE `dsthuyenchuyen`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `dsthuyenchuyen_mads_unique` (`mads`);

--
-- Indexes for table `dsthuyenchuyen_chitiet`
--
ALTER TABLE `dsthuyenchuyen_chitiet`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `dsthuyenchuyen_chitiet_mads_unique` (`mads`);

--
-- Indexes for table `dutoanluong`
--
ALTER TABLE `dutoanluong`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `dutoanluong_masodv_unique` (`masodv`);

--
-- Indexes for table `dutoanluong_chitiet`
--
ALTER TABLE `dutoanluong_chitiet`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `dutoanluong_huyen`
--
ALTER TABLE `dutoanluong_huyen`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `dutoanluong_khoi`
--
ALTER TABLE `dutoanluong_khoi`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `dutoanluong_tinh`
--
ALTER TABLE `dutoanluong_tinh`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `general_configs`
--
ALTER TABLE `general_configs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `hosocanbo`
--
ALTER TABLE `hosocanbo`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `hosocanbo_macanbo_unique` (`macanbo`);

--
-- Indexes for table `hosocanbo_kiemnhiem`
--
ALTER TABLE `hosocanbo_kiemnhiem`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `hosocanbo_kiemnhiem_temp`
--
ALTER TABLE `hosocanbo_kiemnhiem_temp`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `hosoluanchuyen`
--
ALTER TABLE `hosoluanchuyen`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `hosoluong`
--
ALTER TABLE `hosoluong`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `hosophucap`
--
ALTER TABLE `hosophucap`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `hosotamngungtheodoi`
--
ALTER TABLE `hosotamngungtheodoi`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `hosotamngungtheodoi_maso_unique` (`maso`);

--
-- Indexes for table `hosotruylinh`
--
ALTER TABLE `hosotruylinh`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `hosotruylinh_maso_unique` (`maso`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ngachbac`
--
ALTER TABLE `ngachbac`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ngachluong`
--
ALTER TABLE `ngachluong`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `nguonkinhphi`
--
ALTER TABLE `nguonkinhphi`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `nguonkinhphi_huyen`
--
ALTER TABLE `nguonkinhphi_huyen`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `nguonkinhphi_khoi`
--
ALTER TABLE `nguonkinhphi_khoi`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `nguonkinhphi_tinh`
--
ALTER TABLE `nguonkinhphi_tinh`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `nhomngachluong`
--
ALTER TABLE `nhomngachluong`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD KEY `password_resets_email_index` (`email`),
  ADD KEY `password_resets_token_index` (`token`);

--
-- Indexes for table `tonghopluong_donvi`
--
ALTER TABLE `tonghopluong_donvi`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `tonghopluong_donvi_mathdv_unique` (`mathdv`);

--
-- Indexes for table `tonghopluong_donvi_chitiet`
--
ALTER TABLE `tonghopluong_donvi_chitiet`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tonghopluong_donvi_diaban`
--
ALTER TABLE `tonghopluong_donvi_diaban`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tonghopluong_huyen`
--
ALTER TABLE `tonghopluong_huyen`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `tonghopluong_huyen_mathdv_unique` (`mathdv`);

--
-- Indexes for table `tonghopluong_huyen_chitiet`
--
ALTER TABLE `tonghopluong_huyen_chitiet`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tonghopluong_huyen_diaban`
--
ALTER TABLE `tonghopluong_huyen_diaban`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tonghopluong_khoi`
--
ALTER TABLE `tonghopluong_khoi`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `tonghopluong_khoi_mathdv_unique` (`mathdv`);

--
-- Indexes for table `tonghopluong_khoi_chitiet`
--
ALTER TABLE `tonghopluong_khoi_chitiet`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tonghopluong_khoi_diaban`
--
ALTER TABLE `tonghopluong_khoi_diaban`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tonghopluong_tinh`
--
ALTER TABLE `tonghopluong_tinh`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `tonghopluong_tinh_mathdv_unique` (`mathdv`);

--
-- Indexes for table `tonghopluong_tinh_chitiet`
--
ALTER TABLE `tonghopluong_tinh_chitiet`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tonghopluong_tinh_diaban`
--
ALTER TABLE `tonghopluong_tinh_diaban`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tonghop_huyen`
--
ALTER TABLE `tonghop_huyen`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `tonghop_huyen_mathdv_unique` (`mathdv`);

--
-- Indexes for table `tonghop_huyen_chitiet`
--
ALTER TABLE `tonghop_huyen_chitiet`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tonghop_huyen_diaban`
--
ALTER TABLE `tonghop_huyen_diaban`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tonghop_tinh`
--
ALTER TABLE `tonghop_tinh`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `tonghop_tinh_mathdv_unique` (`mathdv`);

--
-- Indexes for table `tonghop_tinh_chitiet`
--
ALTER TABLE `tonghop_tinh_chitiet`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tonghop_tinh_diaban`
--
ALTER TABLE `tonghop_tinh_diaban`
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
-- AUTO_INCREMENT for table `bangluong`
--
ALTER TABLE `bangluong`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `bangluong_ct`
--
ALTER TABLE `bangluong_ct`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `bangluong_phucap`
--
ALTER TABLE `bangluong_phucap`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `chitieubienche`
--
ALTER TABLE `chitieubienche`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `dmchucvucq`
--
ALTER TABLE `dmchucvucq`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=238;

--
-- AUTO_INCREMENT for table `dmchucvud`
--
ALTER TABLE `dmchucvud`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `dmdantoc`
--
ALTER TABLE `dmdantoc`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=54;

--
-- AUTO_INCREMENT for table `dmdiabandbkk`
--
ALTER TABLE `dmdiabandbkk`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `dmdiabandbkk_chitiet`
--
ALTER TABLE `dmdiabandbkk_chitiet`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `dmdonvi`
--
ALTER TABLE `dmdonvi`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=976;

--
-- AUTO_INCREMENT for table `dmdonvibaocao`
--
ALTER TABLE `dmdonvibaocao`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `dmkhoipb`
--
ALTER TABLE `dmkhoipb`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `dmngachcc`
--
ALTER TABLE `dmngachcc`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `dmnguonkinhphi`
--
ALTER TABLE `dmnguonkinhphi`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `dmphanloaicongtac`
--
ALTER TABLE `dmphanloaicongtac`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `dmphanloaicongtac_baohiem`
--
ALTER TABLE `dmphanloaicongtac_baohiem`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=73;

--
-- AUTO_INCREMENT for table `dmphanloaict`
--
ALTER TABLE `dmphanloaict`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `dmphanloaidonvi`
--
ALTER TABLE `dmphanloaidonvi`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `dmphongban`
--
ALTER TABLE `dmphongban`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `dmphucap`
--
ALTER TABLE `dmphucap`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `dmphucap_donvi`
--
ALTER TABLE `dmphucap_donvi`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `dmthongtuquyetdinh`
--
ALTER TABLE `dmthongtuquyetdinh`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `dmtieumuc_default`
--
ALTER TABLE `dmtieumuc_default`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `dsnangluong`
--
ALTER TABLE `dsnangluong`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `dsnangluong_chitiet`
--
ALTER TABLE `dsnangluong_chitiet`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `dsthuyenchuyen`
--
ALTER TABLE `dsthuyenchuyen`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `dsthuyenchuyen_chitiet`
--
ALTER TABLE `dsthuyenchuyen_chitiet`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `dutoanluong`
--
ALTER TABLE `dutoanluong`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `dutoanluong_chitiet`
--
ALTER TABLE `dutoanluong_chitiet`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `dutoanluong_huyen`
--
ALTER TABLE `dutoanluong_huyen`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `dutoanluong_khoi`
--
ALTER TABLE `dutoanluong_khoi`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `dutoanluong_tinh`
--
ALTER TABLE `dutoanluong_tinh`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `general_configs`
--
ALTER TABLE `general_configs`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `hosocanbo`
--
ALTER TABLE `hosocanbo`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `hosocanbo_kiemnhiem`
--
ALTER TABLE `hosocanbo_kiemnhiem`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `hosocanbo_kiemnhiem_temp`
--
ALTER TABLE `hosocanbo_kiemnhiem_temp`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `hosoluanchuyen`
--
ALTER TABLE `hosoluanchuyen`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `hosoluong`
--
ALTER TABLE `hosoluong`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `hosophucap`
--
ALTER TABLE `hosophucap`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `hosotamngungtheodoi`
--
ALTER TABLE `hosotamngungtheodoi`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `hosotruylinh`
--
ALTER TABLE `hosotruylinh`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=69;

--
-- AUTO_INCREMENT for table `ngachbac`
--
ALTER TABLE `ngachbac`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ngachluong`
--
ALTER TABLE `ngachluong`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=119;

--
-- AUTO_INCREMENT for table `nguonkinhphi`
--
ALTER TABLE `nguonkinhphi`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `nguonkinhphi_huyen`
--
ALTER TABLE `nguonkinhphi_huyen`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `nguonkinhphi_khoi`
--
ALTER TABLE `nguonkinhphi_khoi`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `nguonkinhphi_tinh`
--
ALTER TABLE `nguonkinhphi_tinh`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `nhomngachluong`
--
ALTER TABLE `nhomngachluong`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `tonghopluong_donvi`
--
ALTER TABLE `tonghopluong_donvi`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `tonghopluong_donvi_chitiet`
--
ALTER TABLE `tonghopluong_donvi_chitiet`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `tonghopluong_donvi_diaban`
--
ALTER TABLE `tonghopluong_donvi_diaban`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tonghopluong_huyen`
--
ALTER TABLE `tonghopluong_huyen`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tonghopluong_huyen_chitiet`
--
ALTER TABLE `tonghopluong_huyen_chitiet`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tonghopluong_huyen_diaban`
--
ALTER TABLE `tonghopluong_huyen_diaban`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tonghopluong_khoi`
--
ALTER TABLE `tonghopluong_khoi`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tonghopluong_khoi_chitiet`
--
ALTER TABLE `tonghopluong_khoi_chitiet`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tonghopluong_khoi_diaban`
--
ALTER TABLE `tonghopluong_khoi_diaban`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tonghopluong_tinh`
--
ALTER TABLE `tonghopluong_tinh`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tonghopluong_tinh_chitiet`
--
ALTER TABLE `tonghopluong_tinh_chitiet`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tonghopluong_tinh_diaban`
--
ALTER TABLE `tonghopluong_tinh_diaban`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tonghop_huyen`
--
ALTER TABLE `tonghop_huyen`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tonghop_huyen_chitiet`
--
ALTER TABLE `tonghop_huyen_chitiet`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tonghop_huyen_diaban`
--
ALTER TABLE `tonghop_huyen_diaban`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tonghop_tinh`
--
ALTER TABLE `tonghop_tinh`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tonghop_tinh_chitiet`
--
ALTER TABLE `tonghop_tinh_chitiet`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tonghop_tinh_diaban`
--
ALTER TABLE `tonghop_tinh_diaban`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=953;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

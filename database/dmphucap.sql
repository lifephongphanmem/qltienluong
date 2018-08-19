-- phpMyAdmin SQL Dump
-- version 4.8.2
-- https://www.phpmyadmin.net/
--
-- Máy chủ: 127.0.0.1
-- Thời gian đã tạo: Th8 19, 2018 lúc 12:59 PM
-- Phiên bản máy phục vụ: 10.1.34-MariaDB
-- Phiên bản PHP: 5.6.37

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Cơ sở dữ liệu: `qlluong_local`
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `dmphucap`
--

CREATE TABLE `dmphucap` (
  `id` int(10) UNSIGNED NOT NULL,
  `stt` int(11) NOT NULL DEFAULT '99',
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
-- Đang đổ dữ liệu cho bảng `dmphucap`
--

INSERT INTO `dmphucap` (`id`, `stt`, `mapc`, `tenpc`, `baohiem`, `form`, `report`, `phanloai`, `congthuc`, `ghichu`, `created_at`, `updated_at`) VALUES
(1, 1, 'heso', 'Lương hệ số', 1, 'Hệ số lương', 'Hệ số', '0', '', NULL, NULL, NULL),
(2, 2, 'vuotkhung', 'Phụ cấp thâm niên vượt khung', 1, 'Thâm niên vượt khung', 'Phụ cấp thâm niên vượt khung', '2', 'heso,pccv', NULL, NULL, NULL),
(3, 3, 'hesott', 'Hệ số lương truy lĩnh', 1, 'Hệ số truy lĩnh', 'Hệ số truy lĩnh', '0', '', NULL, NULL, NULL),
(4, 4, 'hesopc', 'Hệ số phụ cấp', 0, 'Hệ số phụ cấp', 'Hệ số phụ cấp', '0', '', NULL, NULL, NULL),
(5, 5, 'pckv', 'Phụ cấp khu vực', 0, 'Khu vực', 'Phụ cấp khu vực', '0', '', NULL, NULL, NULL),
(6, 6, 'pccv', 'Phụ cấp chức vụ', 1, 'Chức vụ', 'Phụ cấp chức vụ', '0', '', NULL, NULL, NULL),
(7, 7, 'pcudn', 'Phụ cấp ưu đãi ngành', 0, 'Ưu đãi ngành', 'Phụ cấp ưu đãi ngành', '2', 'heso,vuotkhung,pccv', NULL, NULL, NULL),
(8, 8, 'pcth', 'Phụ cấp thu hút', 0, 'Thu hút', 'Phụ cấpthu hút', '2', 'heso,vuotkhung,pccv', NULL, NULL, NULL),
(9, 9, 'pcthni', 'Phụ cấp công tác lâu năm', 0, 'Lâu năm', 'Phụ cấp công tác lâu năm', '2', 'heso,vuotkhung,pccv', NULL, NULL, NULL),
(10, 10, 'pccovu', 'Phụ cấp công vụ', 0, 'Công vụ', 'Phụ cấp công vụ', '2', 'heso,vuotkhung,pccv', NULL, NULL, NULL),
(11, 11, 'pcdang', 'Phụ cấp công tác Đảng', 0, 'Công tác Đảng', 'Phụ cấp công tác Đảng', '0', '', NULL, NULL, NULL),
(12, 12, 'pctnn', 'Phụ cấp  thâm niên nghề', 1, 'Thâm niên nghề', 'Phụ cấp thâm niên nghề', '2', 'heso,vuotkhung,pccv', NULL, NULL, NULL),
(13, 13, 'pcct', 'Phụ cấp ghép lớp', 0, 'Ghép lớp', 'Phụ cấp ghép lớp', '0', '', NULL, NULL, NULL),
(14, 14, 'pctn', 'Phụ cấp trách nhiệm', 0, 'Trách nhiệm', 'Phụ cấp trách nhiệm', '0', '', NULL, NULL, NULL),
(15, 15, 'pckn', 'Phụ cấp kiêm nhiệm', 0, 'Kiêm nhiệm', 'Phụ cấp kiêm nhiệm', '2', 'heso,vuotkhung,pccv', NULL, NULL, NULL),
(16, 16, 'pclt', 'Phụ cấp phân loại xã', 0, 'Phân loại xã', 'Phụ cấp phân loại xã', '2', 'heso,vuotkhung,pccv', NULL, NULL, NULL),
(17, 17, 'pcdd', 'Phụ cấp đắt đỏ', 0, 'Đắt đỏ', 'Phụ cấp đắt đỏ', '0', '', NULL, NULL, NULL),
(18, 18, 'pcdbqh', 'Phụ cấp đại biểu HĐND', 0, 'Đại biểu HĐND', 'Phụ cấp đại biểu HĐND', '0', '', NULL, NULL, NULL),
(19, 19, 'pcvk', 'Phụ cấp cấp ủy viên', 0, 'Cấp ủy viên', 'Phụ cấp cấp ủy viên', '0', '', NULL, NULL, NULL),
(20, 20, 'pcbdhdcu', 'Phụ cấp bồi dưỡng HĐCU', 0, 'Bồi dưỡng HĐCU', 'Phụ cấp bồi dưỡng HĐCU', '0', '', NULL, NULL, NULL),
(21, 21, 'pcdbn', 'Phụ cấp đặc biệt (đặc thù)', 0, 'Đặc biệt (đặc thù)', 'Phụ cấp đặc biệt (đặc thù)', '0', '', NULL, NULL, NULL),
(22, 22, 'pcld', 'Phụ cấp lưu động', 0, 'Lưu động', 'Phụ cấp lưu động', '0', '', NULL, NULL, NULL),
(23, 23, 'pcdh', 'Phụ cấp độc hại', 0, 'Độc hại', 'Phụ cấp độc hại', '0', '', NULL, NULL, NULL),
(24, 24, 'pckct', 'Phụ cấp bằng cấp (không chuyên trách)', 0, 'Bằng cấp', 'Phụ cấp bằng cấp', '0', '', NULL, NULL, NULL),
(25, 25, 'pck', 'Phụ cấp khác', 0, 'Phụ cấp khác', 'Phụ cấp khác', '0', '', NULL, NULL, NULL);

--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng `dmphucap`
--
ALTER TABLE `dmphucap`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `dmphucap_mapc_unique` (`mapc`);

--
-- AUTO_INCREMENT cho các bảng đã đổ
--

--
-- AUTO_INCREMENT cho bảng `dmphucap`
--
ALTER TABLE `dmphucap`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

-- phpMyAdmin SQL Dump
-- version 5.0.3
-- https://www.phpmyadmin.net/
--
-- Máy chủ: 127.0.0.1
-- Thời gian đã tạo: Th3 29, 2021 lúc 03:25 PM
-- Phiên bản máy phục vụ: 10.4.14-MariaDB
-- Phiên bản PHP: 7.4.11

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Cơ sở dữ liệu: `databaseesp`
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `dbdieukhien`
--

CREATE TABLE `dbdieukhien` (
  `id` int(11) NOT NULL,
  `den` varchar(100) NOT NULL,
  `quat` varchar(100) NOT NULL,
  `caidat1` varchar(100) NOT NULL,
  `caidat2` varchar(100) NOT NULL,
  `date` date NOT NULL,
  `time` time NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Đang đổ dữ liệu cho bảng `dbdieukhien`
--

INSERT INTO `dbdieukhien` (`id`, `den`, `quat`, `caidat1`, `caidat2`, `date`, `time`) VALUES
(1, '1', '0', '0', '0', '2021-03-29', '19:59:23');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `dbsensor`
--

CREATE TABLE `dbsensor` (
  `id` int(11) NOT NULL,
  `nhietdo` varchar(100) NOT NULL,
  `doam` varchar(100) NOT NULL,
  `den` varchar(100) NOT NULL,
  `quat` varchar(100) NOT NULL,
  `caidat1` varchar(100) NOT NULL,
  `caidat2` varchar(100) NOT NULL,
  `date` date NOT NULL,
  `time` time NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Đang đổ dữ liệu cho bảng `dbsensor`
--

INSERT INTO `dbsensor` (`id`, `nhietdo`, `doam`, `den`, `quat`, `caidat1`, `caidat2`, `date`, `time`) VALUES
(1, '11', '22', '33', '44', '55', '66', '2021-03-29', '20:16:26'),
(2, '111', '222', '333', '444', '555', '666', '2021-03-29', '20:17:04');

--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng `dbdieukhien`
--
ALTER TABLE `dbdieukhien`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `dbsensor`
--
ALTER TABLE `dbsensor`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT cho các bảng đã đổ
--

--
-- AUTO_INCREMENT cho bảng `dbdieukhien`
--
ALTER TABLE `dbdieukhien`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT cho bảng `dbsensor`
--
ALTER TABLE `dbsensor`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

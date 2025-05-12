-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 19, 2024 at 06:38 AM
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
-- Database: `qlhotel`
--

-- --------------------------------------------------------

--
-- Table structure for table `bookings`
--

CREATE TABLE `bookings` (
  `MaDDP` int(11) NOT NULL,
  `id` char(10) NOT NULL,
  `MaKH` int(11) NOT NULL,
  `NgayDen` date NOT NULL,
  `NgayDi` date NOT NULL,
  `TongTien` decimal(10,0) NOT NULL,
  `MaTTDDP` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `bookings`
--

INSERT INTO `bookings` (`MaDDP`, `id`, `MaKH`, `NgayDen`, `NgayDi`, `TongTien`, `MaTTDDP`) VALUES
(8, '3', 11, '2024-11-25', '2024-11-26', 400000, 4),
(10, '1', 13, '2024-11-25', '2024-11-26', 250000, 4),
(11, '3', 14, '2024-11-25', '2024-11-29', 1600000, 4),
(13, '2', 16, '2024-11-24', '2024-11-26', 600000, 2),
(15, '2', 21, '2024-12-02', '2024-12-03', 300000, 3),
(16, '3', 22, '2024-11-25', '2024-11-27', 800000, 3),
(19, '3', 24, '2024-12-01', '2024-12-03', 456789, 3),
(20, '4', 24, '2024-12-11', '2024-12-20', 6300000, 3),
(21, '1', 23, '0000-00-00', '2024-12-25', 250000, 3),
(22, '1', 23, '2024-12-18', '2024-12-26', 2000000, 3),
(23, '1', 23, '0000-00-00', '2024-12-26', 1750000, 3),
(24, '2', 23, '0000-00-00', '2024-12-25', 3900000, 3),
(25, '23426', 24, '0000-00-00', '2024-12-26', 3200000, 3),
(26, '1', 23, '2024-12-12', '2024-12-21', 2250000, 1),
(27, '23426', 24, '0000-00-00', '2025-01-11', 400000, 1),
(28, '3', 24, '0000-00-00', '2024-12-31', 2800000, 1),
(29, '23426', 24, '0000-00-00', '2024-12-25', 2400000, 1),
(30, '23426', 32, '2024-12-17', '2024-12-27', 4000000, 1);

-- --------------------------------------------------------

--
-- Table structure for table `customers`
--

CREATE TABLE `customers` (
  `MaKH` int(11) NOT NULL,
  `TenKH` varchar(100) NOT NULL,
  `SĐT` text NOT NULL,
  `DiaChi` varchar(100) NOT NULL,
  `CCCD` text NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` text NOT NULL,
  `image` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `customers`
--

INSERT INTO `customers` (`MaKH`, `TenKH`, `SĐT`, `DiaChi`, `CCCD`, `username`, `password`, `image`) VALUES
(1, 'Lan', '0387239782', 'Nam Cao', '', '', '', ''),
(2, 'Linh', '290765936', 'Gia Lâm', '', '', '', 'https://thuthuatnhanh.com/wp-content/uploads/2022/06/Anh-meme-meo.jpg'),
(3, 'Minh', '0927257965', 'Hải Nam', '', '', '', ''),
(4, 'Minh', '0927257965', 'Hải Nam', '', '', '', ''),
(5, 'Lan', '0387239782', 'Nam Cao', '', '', '', ''),
(6, 'Lan', '0387239782', 'Nam Cao', '', '', '', ''),
(7, 'Linh', '0290765936', 'Gia Lâm', '', '', '', ''),
(10, 'Lan', '0290765936', 'Hà Nội', '', '', '', ''),
(11, 'Lụa', '0174047457', 'Thái Bình', '', '', '', ''),
(12, 'Lụa', '174047457', 'Thái Bình', '', '', '', ''),
(13, 'Linh', '0290765936', 'Nam Cao', '', '', '', ''),
(14, 'Ly', '0290765936', 'Kiến Xương', '', '', '', ''),
(15, 'Minh', '0927257965', 'Hải Nam', '', '', '', ''),
(16, 'Huyền', '0174857958', 'Hải Dương', '', '', '', ''),
(20, 'TA THI THU HUE', '0387811691', 'HÀ NAM- VIỆT NAM', '', '', '', ''),
(21, 'Tạ huệ', '0387811691', 'Đồng Văn, Hà Nam', '', '', '', ''),
(22, 'TA THI THU HUE', '0387811691', 'HÀ NAM- VIỆT NAM', '', '', '', ''),
(23, 'tạ huệ', '0348885653', 'hà nam', '456789056', 'jane_smith', '$2y$10$1ZDdchvQdXxlXNgxOchckepHDHrATg53mZomFK6WmbsnpoFea7p5e', 'https://yt3.ggpht.com/a/AATXAJzpWfdZwQLd-RUBDn8O2PJOdUsNQVq4fm_sYA=s900-c-k-c0xffffffff-no-rj-mo'),
(32, 'liên', '0348885653', 'hà nôi', '56789373', '1412', '$2y$10$T/uEur6CmVWxQGkB2LYZn.nUTegr2Vpa3tQFgTaPDF757i2U/dl/a', '');

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

CREATE TABLE `reviews` (
  `review_id` int(10) NOT NULL,
  `id` int(10) NOT NULL,
  `MaKH` int(10) NOT NULL,
  `rating` int(11) NOT NULL,
  `review_text` varchar(255) NOT NULL,
  `date_review` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `reviews`
--

INSERT INTO `reviews` (`review_id`, `id`, `MaKH`, `rating`, `review_text`, `date_review`) VALUES
(1, 1, 2, 4, 'phòng ổn', '2024-12-17 00:00:00'),
(2, 2, 23, 5, 'phòng thoải mái tiện nghi', '2024-12-12 09:58:00'),
(3, 2, 23, 5, 'phòng thoải mái tiện nghi', '2024-12-12 09:58:36'),
(4, 4, 24, 5, 'phòng thoải mái tiện nghi', '2024-12-12 09:58:36'),
(5, 5, 23, 5, 'phòng thoải mái tiện nghi', '2024-12-12 10:00:36'),
(6, 23426, 23, 5, 'phòng thoải mái tiện nghi', '2024-12-12 10:06:09'),
(10, 23426, 24, 1, 'ổn', '2024-12-12 10:55:58'),
(20, 1, 23, 4, 'ỔN', '2024-12-12 11:23:41'),
(21, 1, 23, 4, 'ỔN', '2024-12-12 11:24:18'),
(22, 23426, 24, 2, 'TEEEEEEEEEE', '2024-12-12 11:33:27');

-- --------------------------------------------------------

--
-- Table structure for table `rooms`
--

CREATE TABLE `rooms` (
  `id` char(10) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `price` decimal(10,0) DEFAULT NULL,
  `area` varchar(50) DEFAULT NULL,
  `capacity` int(11) DEFAULT NULL,
  `bed_type` varchar(50) DEFAULT NULL,
  `services` varchar(100) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `type` enum('single','double','suite') NOT NULL,
  `MaTTP` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `rooms`
--

INSERT INTO `rooms` (`id`, `name`, `price`, `area`, `capacity`, `bed_type`, `services`, `description`, `image`, `type`, `MaTTP`) VALUES
('1', 'Phòng tiêu chuẩn ', 250000, '12-16', 2, '1 Giường đôi hoặc 2 Giường đơn', 'TV; Tủ quần áo, Bàn làm việc ;Phòng tắm riêng.', 'Phòng thường có diện tích nhỏ nhất, khoảng 12-16m², được trang bị nội thất cơ bản như một giường đôi hoặc hai giường đơn, TV, tủ quần áo, bàn làm việc và phòng tắm riêng. View thường hướng ra khu vực bên trong khách sạn hoặc đường phố.', 'https://s.net.vn/UrdW', 'single', 4),
('2', 'phòng Superior', 300000, '16-20', 4, '1 Giường đôi hoặc 2 Giường đơn', 'TV; Tủ quần áo; Bàn làm việc ;Phòng tắm riêng; Minibar; Ghế sofa; Bàn trang điểm', 'Cao cấp hơn phòng Standard, phòng Superior thường có diện tích rộng hơn (16-20m²) và được trang bị thêm một số tiện nghi như minibar, ghế sofa, bàn trang điểm. View có thể hướng ra cảnh quan đẹp hơn như hồ bơi, vườn hoặc thành phố.', 'https://s.net.vn/R8R9', 'single', 2),
('23426', 'Phòng tiêu chuẩn 3', 400000, '30-40', 5, 'Giường đôi', 'phục vụ bữa ăn tại phòng ;ăn sáng free', 'phòng xanh sạch đệp', 'https://decordi.vn/wp-content/uploads/2021/03/6-phong-ngu-2-giuong-hco-nguoi-lon2.jpg', 'single', 4),
('3', ' Phòng Deluxe', 400000, '20-30', 5, '1 Giường đôi hoặc 2 Giường đơn', 'TV; Tủ quần áo, Bàn làm việc ;Phòng tắm riêng; Bồn tắm riêng; Áo choàng tắm; Dép đi trong nhà ;Đồ dù', 'Phòng Deluxe mang đến sự sang trọng và thoải mái với diện tích rộng rãi (20-30m²) và nội thất cao cấp hơn. Khách có thể tận hưởng các tiện nghi như bồn tắm riêng, áo choàng tắm, dép đi trong nhà và đồ dùng cá nhân chất lượng cao. Phòng Deluxe thường có view đẹp hướng ra biển, núi hoặc thành phố.', 'https://s.net.vn/f3B8', 'single', 4),
('4', 'Phòng Executive', 700000, '20-30', 5, '1 Giường đôi hoặc 2 Giường đơn', 'TV; Tủ quần áo, Bàn làm việc rộng rãi, Ghế văn phòng thoải mái, ổ cắm điện và cổng kết nối tiện lợi ', 'Phòng Executive được thiết kế đặc biệt dành cho khách doanh nhân. Ngoài các tiện nghi của phòng Deluxe, phòng Executive còn có thêm bàn làm việc rộng rãi, ghế văn phòng thoải mái, ổ cắm điện và cổng kết nối tiện lợi. Khách sạn còn cung cấp dịch vụ phòng họp nhỏ, bữa sáng riêng và quyền sử dụng phòng chờ Executive Lounge.', 'https://s.net.vn/2tPp', 'single', 1),
('5', 'Phòng Suite', 1000000, '40m² trở lên', 10, '1 Giường king size', 'TV; Tủ quần áo, Bàn làm việc rộng rãi, Ghế văn phòng thoải mái, ổ cắm điện và cổng kết nối tiện lợi ', 'Phòng Suite là lựa chọn cao cấp nhất, mang đến không gian rộng rãi và riêng tư tuyệt đối. Suite thường có diện tích từ 40m² trở lên, bao gồm phòng khách riêng biệt, phòng ngủ, phòng tắm rộng rãi và có thể có thêm phòng ăn, bếp nhỏ hoặc phòng làm việc. Các Suite thường có ban công hoặc cửa sổ lớn với view đẹp, nội thất sang trọng và dịch vụ đặc biệt như quản gia riêng.', 'https://s.net.vn/5aZL', 'single', 3);

-- --------------------------------------------------------

--
-- Table structure for table `statusddp`
--

CREATE TABLE `statusddp` (
  `MaTTDDP` int(11) NOT NULL,
  `TentrangthaiDDP` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `statusddp`
--

INSERT INTO `statusddp` (`MaTTDDP`, `TentrangthaiDDP`) VALUES
(1, 'Chờ xác nhận'),
(2, 'Đã xác nhận'),
(3, 'Đã thanh toán'),
(4, 'Đã hủy');

-- --------------------------------------------------------

--
-- Table structure for table `statusp`
--

CREATE TABLE `statusp` (
  `MaTTP` int(11) NOT NULL,
  `TentrangthaiP` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `statusp`
--

INSERT INTO `statusp` (`MaTTP`, `TentrangthaiP`) VALUES
(1, 'Trống chưa dọn dẹp'),
(2, 'Đã đặt'),
(3, 'Đang sử dụng'),
(4, 'Trống đã dọn dẹp');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `bookings`
--
ALTER TABLE `bookings`
  ADD PRIMARY KEY (`MaDDP`),
  ADD KEY `MaTTDDP` (`MaTTDDP`),
  ADD KEY `id` (`id`);

--
-- Indexes for table `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`MaKH`);

--
-- Indexes for table `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`review_id`);

--
-- Indexes for table `rooms`
--
ALTER TABLE `rooms`
  ADD PRIMARY KEY (`id`),
  ADD KEY `MaTTP` (`MaTTP`);

--
-- Indexes for table `statusddp`
--
ALTER TABLE `statusddp`
  ADD PRIMARY KEY (`MaTTDDP`);

--
-- Indexes for table `statusp`
--
ALTER TABLE `statusp`
  ADD PRIMARY KEY (`MaTTP`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `bookings`
--
ALTER TABLE `bookings`
  MODIFY `MaDDP` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `customers`
--
ALTER TABLE `customers`
  MODIFY `MaKH` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT for table `reviews`
--
ALTER TABLE `reviews`
  MODIFY `review_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `statusddp`
--
ALTER TABLE `statusddp`
  MODIFY `MaTTDDP` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `bookings`
--
ALTER TABLE `bookings`
  ADD CONSTRAINT `bookings_ibfk_1` FOREIGN KEY (`MaTTDDP`) REFERENCES `statusddp` (`MaTTDDP`),
  ADD CONSTRAINT `bookings_ibfk_2` FOREIGN KEY (`id`) REFERENCES `rooms` (`id`);

--
-- Constraints for table `rooms`
--
ALTER TABLE `rooms`
  ADD CONSTRAINT `rooms_ibfk_1` FOREIGN KEY (`MaTTP`) REFERENCES `statusp` (`MaTTP`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

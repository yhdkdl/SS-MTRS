-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 28, 2025 at 06:50 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `th_ctms`
--

-- --------------------------------------------------------

--
-- Table structure for table `booked_seats`
--

CREATE TABLE `booked_seats` (
  `id` int(11) NOT NULL,
  `booking_id` varchar(20) NOT NULL,
  `seat_id` int(11) NOT NULL,
  `seat_price` decimal(8,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `bookings`
--

CREATE TABLE `bookings` (
  `id` varchar(20) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `showtime_id` int(11) NOT NULL,
  `status` enum('pending','confirmed','cancelled') DEFAULT 'pending',
  `payment_status` enum('pending','paid','failed') DEFAULT 'pending',
  `total_price` decimal(8,2) NOT NULL,
  `receipt_img` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cinemarooms`
--

CREATE TABLE `cinemarooms` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `capacity` int(11) NOT NULL,
  `room_type` enum('VIP','Regular','Premium') NOT NULL,
  `base_price` decimal(8,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `customers`
--

CREATE TABLE `customers` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `customers`
--

INSERT INTO `customers` (`id`, `name`, `email`, `password`) VALUES
(2, 'yohan', 'yyy@gmail.com', '$2y$10$TNeDSgw651flWFPa4xak5eaYZXhxYqCqp4Pa60KnSr9SKChJUiX.y'),
(3, 'usma', 'uuu@gmail.com', '$2y$10$6NLLK.SXchW7D3bLdvpRV.dajLbKwCJKUi0/9g.tEpB.wbrVml7CC'),
(4, 'gg', 'gg@gmail.com', '$2y$10$ZETwMeEn/ihQYHjkCmncL..jp9R8qITeyWrwO2106uZ3ASIUIX492'),
(5, 'aa', 'aa@gmail.com', '$2y$10$KGd1gvxX07K4xe.Z8yZXQ.tY1pHSva9S2qYSoqxXRnYprM1BNBytW'),
(6, 'bb', 'bb@gmail.com', '$2y$10$Z2pEhmmgdFo82SPKEKBOu.9JNFQQs89dH5pDk3BsXWdCKoS01Fl7i'),
(7, 'aa', 'a@gmail.com', '$2y$10$wPT3mDZcpW2iux8ZJ4sWhu.Or6w/f7.L6P26jYq9ZOrr2KEW4LmBy'),
(8, 'ff', 'ff@gmail.com', '$2y$10$I5H95/W8fibWqbWELl4w0euzxVBY45EWh9MRj9aPG0cHOUQsm42sC');

-- --------------------------------------------------------

--
-- Table structure for table `employee`
--

CREATE TABLE `employee` (
  `id` int(11) NOT NULL,
  `emp_id` varchar(50) DEFAULT NULL,
  `full_Name` varchar(255) NOT NULL,
  `phone` varchar(15) NOT NULL,
  `email` varchar(255) NOT NULL,
  `role` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `employee`
--

INSERT INTO `employee` (`id`, `emp_id`, `full_Name`, `phone`, `email`, `role`, `password`, `created_at`) VALUES
(6, 'TH1111', 'ffffgg', '0928186282', 'hhh@gmail.com', 'Manager', '$2y$10$Q5T4in3V/XSrKQLiCy1uTOA5ZO.ziOI4MQJzMk54uUO65C6rAiFYO', '2024-12-15 03:59:25'),
(8, 'TH1405', 'hhhhh', '0928186282', 'hh@gmail.com', 'Frontdeskofficer', '$2y$10$Q5T4in3V/XSrKQLiCy1uTOA5ZO.ziOI4MQJzMk54uUO65C6rAiFYO', '2024-12-20 02:17:56');

-- --------------------------------------------------------

--
-- Table structure for table `feedbacks`
--

CREATE TABLE `feedbacks` (
  `id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `feedback` text NOT NULL,
  `submission_date` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `feedbacks`
--

INSERT INTO `feedbacks` (`id`, `email`, `feedback`, `submission_date`) VALUES
(1, 'yyy@gmail.com', 'good site', '2025-01-21 16:10:00');

-- --------------------------------------------------------

--
-- Table structure for table `movies`
--

CREATE TABLE `movies` (
  `id` int(30) NOT NULL,
  `title` text NOT NULL,
  `cover_img` text NOT NULL,
  `description` text NOT NULL,
  `duration` float NOT NULL,
  `release_date` date DEFAULT NULL,
  `youtube_link` varchar(255) DEFAULT NULL,
  `status` varchar(10) DEFAULT NULL CHECK (`status` in ('active','inactive'))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `movies`
--

INSERT INTO `movies` (`id`, `title`, `cover_img`, `description`, `duration`, `release_date`, `youtube_link`, `status`) VALUES
(1, 'The Matrix', '1600221180_matrix.jpg', 'Sample Movie', 2.5, '2024-12-06', '', 'active'),
(2, 'redge', '1734242222_download.jpeg', 'edfgb', 2.5, '2024-12-18', 'https://www.youtube.com/watch?v=qi2XeXQWRx4', 'active'),
(4, 'The Wolf of Wall Street', '1600221240_img 2.jpg', 'Sample 2', 3.15, '2020-09-17', '', 'inactive'),
(5, 'Greatest Showman', '1600221900_images.jpg', 'Greatest Showman', 3.18, '2020-09-01', '', 'inactive'),
(6, 'Jaws', '1600221900_download.jpg', 'Jaws', 2.36, '2020-07-22', '', 'inactive'),
(7, 'Extractions', '1600222080_extraction-20200423134825-19294.jpg', 'Extractions', 2.5, '2024-10-01', '', 'inactive'),
(8, 'Avengers End Game 2', '1600222200_avengersendgame-20190417122917-18221.jpg', 'Avengers End Game', 3, '2024-10-17', '', 'inactive'),
(9, 'White House Down', '1600237980_download (1).jpg', 'White House Down', 4, '2024-10-03', '', 'active'),
(10, 'Uglies', '1727915880_download.jpeg', 'Uglies', 3.13, '2024-10-03', '', 'inactive');

-- --------------------------------------------------------

--
-- Table structure for table `seats`
--

CREATE TABLE `seats` (
  `id` int(11) NOT NULL,
  `room_id` int(11) NOT NULL,
  `seat_number` varchar(10) NOT NULL,
  `status` tinyint(1) DEFAULT 0,
  `is_booked` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `showtime`
--

CREATE TABLE `showtime` (
  `id` int(11) NOT NULL,
  `movie_id` int(11) NOT NULL,
  `room_id` int(11) NOT NULL,
  `start_time` datetime NOT NULL,
  `price` decimal(8,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `th_admin`
--

CREATE TABLE `th_admin` (
  `id` int(11) NOT NULL,
  `emp_id` varchar(50) DEFAULT NULL,
  `full_Name` varchar(255) NOT NULL,
  `phone` varchar(15) NOT NULL,
  `email` varchar(255) NOT NULL,
  `role` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `th_admin`
--

INSERT INTO `th_admin` (`id`, `emp_id`, `full_Name`, `phone`, `email`, `role`, `password`, `created_at`) VALUES
(1, 'TH15015', 'admin admin', '0928186282', 'admin@email.com', 'admin', '$2y$10$Q5T4in3V/XSrKQLiCy1uTOA5ZO.ziOI4MQJzMk54uUO65C6rAiFYO', '2024-10-05 21:26:08');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `booked_seats`
--
ALTER TABLE `booked_seats`
  ADD PRIMARY KEY (`id`),
  ADD KEY `booking_id` (`booking_id`),
  ADD KEY `seat_id` (`seat_id`);

--
-- Indexes for table `bookings`
--
ALTER TABLE `bookings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `customer_id` (`customer_id`),
  ADD KEY `showtime_id` (`showtime_id`);

--
-- Indexes for table `cinemarooms`
--
ALTER TABLE `cinemarooms`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `employee`
--
ALTER TABLE `employee`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `employee_id` (`emp_id`),
  ADD UNIQUE KEY `emp_id` (`emp_id`);

--
-- Indexes for table `feedbacks`
--
ALTER TABLE `feedbacks`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `movies`
--
ALTER TABLE `movies`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `seats`
--
ALTER TABLE `seats`
  ADD PRIMARY KEY (`id`),
  ADD KEY `room_id` (`room_id`);

--
-- Indexes for table `showtime`
--
ALTER TABLE `showtime`
  ADD PRIMARY KEY (`id`),
  ADD KEY `movie_id` (`movie_id`),
  ADD KEY `room_id` (`room_id`);

--
-- Indexes for table `th_admin`
--
ALTER TABLE `th_admin`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `employee_id` (`emp_id`),
  ADD UNIQUE KEY `emp_id` (`emp_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `booked_seats`
--
ALTER TABLE `booked_seats`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=106;

--
-- AUTO_INCREMENT for table `cinemarooms`
--
ALTER TABLE `cinemarooms`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `customers`
--
ALTER TABLE `customers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `employee`
--
ALTER TABLE `employee`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `feedbacks`
--
ALTER TABLE `feedbacks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `movies`
--
ALTER TABLE `movies`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `seats`
--
ALTER TABLE `seats`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=216;

--
-- AUTO_INCREMENT for table `showtime`
--
ALTER TABLE `showtime`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `booked_seats`
--
ALTER TABLE `booked_seats`
  ADD CONSTRAINT `booked_seats_ibfk_1` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `booked_seats_ibfk_2` FOREIGN KEY (`seat_id`) REFERENCES `seats` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `bookings`
--
ALTER TABLE `bookings`
  ADD CONSTRAINT `bookings_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `bookings_ibfk_2` FOREIGN KEY (`showtime_id`) REFERENCES `showtime` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `seats`
--
ALTER TABLE `seats`
  ADD CONSTRAINT `seats_ibfk_1` FOREIGN KEY (`room_id`) REFERENCES `cinemarooms` (`id`);

--
-- Constraints for table `showtime`
--
ALTER TABLE `showtime`
  ADD CONSTRAINT `showtime_ibfk_1` FOREIGN KEY (`movie_id`) REFERENCES `movies` (`id`),
  ADD CONSTRAINT `showtime_ibfk_2` FOREIGN KEY (`room_id`) REFERENCES `cinemarooms` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 17, 2025 at 08:41 PM
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
-- Database: `purerentaldb`
--

-- --------------------------------------------------------

--
-- Table structure for table `bookings`
--

CREATE TABLE `bookings` (
  `id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `car_id` int(11) NOT NULL,
  `booking_date` date NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `total_cost` decimal(10,2) NOT NULL,
  `status` enum('pending','confirmed','cancelled','completed') DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `drivers_license_image` varchar(255) DEFAULT NULL,
  `insurance_card_image` varchar(255) DEFAULT NULL,
  `pickup_time` time NOT NULL,
  `return_time` time NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `bookings`
--

INSERT INTO `bookings` (`id`, `customer_id`, `car_id`, `booking_date`, `start_date`, `end_date`, `total_cost`, `status`, `created_at`, `updated_at`, `drivers_license_image`, `insurance_card_image`, `pickup_time`, `return_time`) VALUES
(3, 1, 6, '2025-04-11', '2025-04-11', '2025-04-13', 1200.00, 'confirmed', '2025-04-11 10:32:19', '2025-04-11 10:32:19', NULL, NULL, '00:00:00', '00:00:00'),
(6, 1, 6, '2025-04-17', '2025-04-23', '2025-04-24', 800.00, 'confirmed', '2025-04-17 15:58:33', '2025-04-17 15:58:33', NULL, NULL, '00:00:00', '00:00:00'),
(7, 1, 9, '2025-04-17', '2025-04-17', '2025-04-18', 800.00, 'confirmed', '2025-04-17 17:43:50', '2025-04-17 17:43:50', NULL, NULL, '00:00:00', '00:00:00'),
(8, 1, 9, '2025-04-17', '2025-04-09', '2025-04-11', 1200.00, 'confirmed', '2025-04-17 17:44:36', '2025-04-17 17:44:36', NULL, NULL, '00:00:00', '00:00:00'),
(9, 1, 6, '2025-04-17', '2025-04-28', '2025-04-29', 800.00, 'confirmed', '2025-04-17 18:22:42', '2025-04-17 18:22:42', '', '', '00:00:00', '00:00:00'),
(10, 1, 6, '2025-04-17', '2025-04-25', '2025-04-26', 800.00, 'confirmed', '2025-04-17 18:25:04', '2025-04-17 18:25:04', 'uploads/Screenshot 2024-12-31 at 12.22.26 PM.png', 'uploads/ronaldbizick.png', '00:00:00', '00:00:00'),
(11, 1, 8, '2025-04-17', '2025-04-14', '2025-04-16', 1200.00, 'confirmed', '2025-04-17 18:29:53', '2025-04-17 18:29:53', 'uploads/ronaldbizick.png', 'uploads/ronaldbizick.png', '00:00:00', '00:00:00'),
(12, 1, 9, '2025-04-17', '2025-04-02', '2025-04-05', 1600.00, 'confirmed', '2025-04-17 18:40:02', '2025-04-17 18:40:02', 'uploads/logo-stonino.png', 'uploads/OD070I.png', '00:00:00', '00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `cars`
--

CREATE TABLE `cars` (
  `id` int(11) NOT NULL,
  `make` varchar(255) NOT NULL,
  `model` varchar(255) NOT NULL,
  `year` int(11) DEFAULT NULL,
  `category` varchar(100) DEFAULT NULL,
  `status` varchar(50) DEFAULT 'Available',
  `display_image` varchar(255) DEFAULT NULL,
  `seaters` int(11) NOT NULL DEFAULT 0,
  `num_doors` int(11) NOT NULL DEFAULT 0,
  `runs_on_gas` varchar(50) NOT NULL DEFAULT '',
  `mpg` decimal(5,2) DEFAULT NULL,
  `daily_rate` decimal(10,2) DEFAULT NULL,
  `weekly_rate` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cars`
--

INSERT INTO `cars` (`id`, `make`, `model`, `year`, `category`, `status`, `display_image`, `seaters`, `num_doors`, `runs_on_gas`, `mpg`, `daily_rate`, `weekly_rate`) VALUES
(6, 'Tesla', 'Cybertruck', 2024, 'SUV', 'Available', 'images/cars/67e56f818cd5b_tesla.png', 5, 4, 'battery', 0.00, 400.00, 0.00),
(8, 'BMW', 'i8', 2017, 'Sedan', 'Available', 'images/cars/67fd557dafb3b_image.png', 4, 2, 'battery', 28.00, 400.00, 0.00),
(9, 'Lotus', 'Emira', 2024, 'Sedan', 'Available', 'images/cars/67fd566fc98c3_lotus_emira.png', 2, 2, 'battery', 20.00, 400.00, 0.00);

-- --------------------------------------------------------

--
-- Table structure for table `car_images`
--

CREATE TABLE `car_images` (
  `id` int(11) NOT NULL,
  `car_id` int(11) NOT NULL,
  `image_path` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `car_images`
--

INSERT INTO `car_images` (`id`, `car_id`, `image_path`) VALUES
(78, 6, 'images/cars/67e56f818d6d0_1.png'),
(79, 6, 'images/cars/67e56f818ddab_2.png'),
(80, 6, 'images/cars/67e56f818e644_3.png'),
(84, 8, 'images/cars/67fd557db0444_67e5575a3f42b_67e323da4a2b0_2.png'),
(85, 8, 'images/cars/67fd557db0cf3_67e5575a3fe9a_67e3225607499_1.png'),
(86, 8, 'images/cars/67fd557db1510_67e3225607499_1.png'),
(87, 9, 'images/cars/67fd566fca101_1.png'),
(88, 9, 'images/cars/67fd566fcaa9d_2.png'),
(89, 9, 'images/cars/67fd566fcb26b_3.png');

-- --------------------------------------------------------

--
-- Table structure for table `car_rental_rates`
--

CREATE TABLE `car_rental_rates` (
  `id` int(11) NOT NULL,
  `car_id` int(11) NOT NULL,
  `daily_rate` decimal(10,2) NOT NULL,
  `weekly_rate` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `car_rental_rates`
--

INSERT INTO `car_rental_rates` (`id`, `car_id`, `daily_rate`, `weekly_rate`) VALUES
(65, 6, 0.00, 0.00),
(66, 6, 0.00, 0.00),
(67, 6, 0.00, 0.00),
(68, 6, 0.00, 0.00),
(69, 6, 0.00, 0.00),
(70, 6, 0.00, 0.00),
(71, 6, 0.00, 0.00);

-- --------------------------------------------------------

--
-- Table structure for table `customers`
--

CREATE TABLE `customers` (
  `id` int(11) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `drivers_license_image` longblob DEFAULT NULL,
  `insurance_card_image` longblob DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `customers`
--

INSERT INTO `customers` (`id`, `first_name`, `last_name`, `email`, `phone`, `drivers_license_image`, `insurance_card_image`, `created_at`, `updated_at`) VALUES
(1, 'John', 'Doe', 'john.doe@example.com', '555-1234', NULL, NULL, '2025-03-18 17:40:49', '2025-03-18 17:40:49'),
(2, 'Jane', 'Smith', 'jane.smith@example.com', '555-5678', NULL, NULL, '2025-03-18 17:40:49', '2025-03-18 17:40:49'),
(3, 'deok-sun', 'sung', 'sungdeoksun@gmail.com', '34929485723', NULL, NULL, '2025-04-17 18:40:02', '2025-04-17 18:40:02');

-- --------------------------------------------------------

--
-- Table structure for table `gigcars`
--

CREATE TABLE `gigcars` (
  `id` int(11) NOT NULL,
  `make` varchar(50) NOT NULL,
  `model` varchar(50) NOT NULL,
  `year` int(5) DEFAULT NULL,
  `category` varchar(20) DEFAULT NULL,
  `status` varchar(20) DEFAULT 'Available',
  `gigcar_dispimage` varchar(255) DEFAULT NULL,
  `seaters` int(11) NOT NULL DEFAULT 4,
  `num_doors` int(11) NOT NULL DEFAULT 4,
  `runs_on_gas` varchar(20) NOT NULL DEFAULT '',
  `mpg` decimal(5,2) DEFAULT NULL,
  `daily_rate` decimal(10,2) DEFAULT NULL,
  `weekly_rate` decimal(10,2) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `gigcars`
--

INSERT INTO `gigcars` (`id`, `make`, `model`, `year`, `category`, `status`, `gigcar_dispimage`, `seaters`, `num_doors`, `runs_on_gas`, `mpg`, `daily_rate`, `weekly_rate`, `created_at`, `updated_at`) VALUES
(1, 'Mitsubishi', 'Mirage G4', 2023, 'Sedan', 'Available', 'images/cars/67fd589832415_1.png', 5, 4, 'battery', 38.00, 50.00, 318.00, '2025-04-14 18:06:00', '2025-04-14 20:58:19'),
(2, 'Ford', 'Explorer', 2016, 'SUV', 'Available', 'images/cars/67fd6caa71fd0_1.png', 5, 4, 'battery', 19.00, 50.00, 318.00, '2025-04-14 20:14:16', '2025-04-14 20:57:53'),
(3, 'Kia', 'Soul', 2023, 'SUV', 'Available', 'images/cars/67fd6f7649086_1.png', 4, 4, 'battery', 30.00, 60.00, 0.00, '2025-04-14 20:25:01', '2025-04-14 20:58:01'),
(4, 'Hyundai', 'Venue', 2023, 'SUV', 'Available', 'images/cars/67fd72045501a_1.png', 5, 4, 'battery', 30.00, 60.00, 0.00, '2025-04-14 20:35:53', '2025-04-14 20:58:07'),
(5, 'Kia', 'Forte', 2023, 'Sedan', 'Available', 'images/cars/67fd75fbb31c1_kia-forte2023.png', 5, 4, 'battery', 39.00, 60.00, 0.00, '2025-04-14 20:53:25', '2025-04-14 20:58:14'),
(6, 'Nissan', 'Versa', 2025, 'Sedan', 'Available', 'images/cars/67fd78eb87095_versa 2025.png', 5, 4, 'battery', 36.00, 55.00, 0.00, '2025-04-14 21:05:57', '2025-04-14 21:06:51');

-- --------------------------------------------------------

--
-- Table structure for table `locations`
--

CREATE TABLE `locations` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `address` varchar(200) NOT NULL,
  `city` varchar(50) NOT NULL,
  `state` varchar(50) DEFAULT NULL,
  `zip` varchar(20) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `locations`
--

INSERT INTO `locations` (`id`, `name`, `address`, `city`, `state`, `zip`, `phone`, `created_at`, `updated_at`) VALUES
(1, 'Raleigh Office', '123 Main St', 'Raleigh', 'NC', '27601', '(984) 327-7870', '2025-03-18 17:40:49', '2025-03-18 17:40:49'),
(2, 'Surf City Office', '456 Beach Ave', 'Surf City', 'NC', '28455', '(984) 327-7871', '2025-03-18 17:40:49', '2025-03-18 17:40:49');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `bookings`
--
ALTER TABLE `bookings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `customer_id` (`customer_id`),
  ADD KEY `car_id` (`car_id`);

--
-- Indexes for table `cars`
--
ALTER TABLE `cars`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `car_images`
--
ALTER TABLE `car_images`
  ADD PRIMARY KEY (`id`),
  ADD KEY `car_id` (`car_id`);

--
-- Indexes for table `car_rental_rates`
--
ALTER TABLE `car_rental_rates`
  ADD PRIMARY KEY (`id`),
  ADD KEY `car_id` (`car_id`);

--
-- Indexes for table `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `gigcars`
--
ALTER TABLE `gigcars`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `locations`
--
ALTER TABLE `locations`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `bookings`
--
ALTER TABLE `bookings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `cars`
--
ALTER TABLE `cars`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `car_images`
--
ALTER TABLE `car_images`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=94;

--
-- AUTO_INCREMENT for table `car_rental_rates`
--
ALTER TABLE `car_rental_rates`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=79;

--
-- AUTO_INCREMENT for table `customers`
--
ALTER TABLE `customers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `gigcars`
--
ALTER TABLE `gigcars`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `locations`
--
ALTER TABLE `locations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `bookings`
--
ALTER TABLE `bookings`
  ADD CONSTRAINT `bookings_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`),
  ADD CONSTRAINT `bookings_ibfk_2` FOREIGN KEY (`car_id`) REFERENCES `cars` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `car_images`
--
ALTER TABLE `car_images`
  ADD CONSTRAINT `car_images_ibfk_1` FOREIGN KEY (`car_id`) REFERENCES `cars` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `car_rental_rates`
--
ALTER TABLE `car_rental_rates`
  ADD CONSTRAINT `car_rental_rates_ibfk_1` FOREIGN KEY (`car_id`) REFERENCES `cars` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

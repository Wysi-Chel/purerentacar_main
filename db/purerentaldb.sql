-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 14, 2025 at 07:13 PM
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

DELIMITER $$
--
-- Procedures
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `CreateBooking` (IN `p_customer_id` INT, IN `p_car_id` INT, IN `p_start_date` DATE, IN `p_end_date` DATE)   BEGIN
    DECLARE v_daily_rate DECIMAL(10,2);
    DECLARE v_num_days INT;
    DECLARE v_total_cost DECIMAL(10,2);
    DECLARE v_conflict INT;

    -- Check for booking conflicts for the selected car and period
    SELECT COUNT(*) INTO v_conflict
    FROM bookings
    WHERE car_id = p_car_id
      AND (p_start_date < end_date AND p_end_date > start_date)
      AND status IN ('pending', 'confirmed', 'completed');

    IF v_conflict > 0 THEN
        SIGNAL SQLSTATE '45000' 
            SET MESSAGE_TEXT = 'Car is already booked for the selected period';
    END IF;

    -- Get the car's daily rental rate
    SELECT rental_rate INTO v_daily_rate 
    FROM cars 
    WHERE id = p_car_id;

    -- Calculate the number of rental days (inclusive)
    SET v_num_days = DATEDIFF(p_end_date, p_start_date) + 1;
    SET v_total_cost = v_daily_rate * v_num_days;

    -- Insert the booking record and set its status to confirmed
    INSERT INTO bookings (customer_id, car_id, booking_date, start_date, end_date, total_cost, status)
    VALUES (p_customer_id, p_car_id, CURDATE(), p_start_date, p_end_date, v_total_cost, 'confirmed');
END$$

DELIMITER ;

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
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `bookings`
--

INSERT INTO `bookings` (`id`, `customer_id`, `car_id`, `booking_date`, `start_date`, `end_date`, `total_cost`, `status`, `created_at`, `updated_at`) VALUES
(1, 1, 7, '2025-04-11', '2025-04-03', '2025-04-04', 140.00, 'confirmed', '2025-04-11 10:21:24', '2025-04-11 10:21:24'),
(2, 1, 5, '2025-04-11', '2025-04-12', '2025-04-14', 1200.00, 'confirmed', '2025-04-11 10:23:54', '2025-04-11 10:23:54'),
(3, 1, 6, '2025-04-11', '2025-04-11', '2025-04-13', 1200.00, 'confirmed', '2025-04-11 10:32:19', '2025-04-11 10:32:19'),
(4, 1, 1, '2025-04-11', '2025-04-25', '2025-04-28', 1600.00, 'confirmed', '2025-04-11 11:14:40', '2025-04-11 11:14:40'),
(5, 1, 1, '2025-04-11', '2025-05-01', '2025-05-03', 1200.00, 'confirmed', '2025-04-11 17:49:04', '2025-04-11 17:49:04');

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
(1, 'BMW', 'i8', 2017, 'Sport', 'Available', 'images/cars/67e45474527a8_67e4545ca5589_image.png', 2, 2, 'battery', 28.00, 400.00, 0.00),
(5, 'Lotus', 'Emira', 2024, 'Sport', 'Available', 'images/cars/67e56f0da46a9_lotus_emira.png', 2, 2, 'battery', 20.00, 400.00, 0.00),
(6, 'Tesla', 'Cybertruck', 2024, 'Pickup', 'Available', 'images/cars/67e56f818cd5b_tesla.png', 5, 4, 'battery', 0.00, 400.00, 0.00),
(7, 'Tesla', 'X', 2023, 'suv', 'Available', 'images/cars/67e6b929cdaf6_1.png', 5, 4, 'battery', 0.00, 70.00, 0.00);

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
(55, 1, 'images/cars/67e5575a3f42b_67e323da4a2b0_2.png'),
(56, 1, 'images/cars/67e5575a3fe9a_67e3225607499_1.png'),
(75, 5, 'images/cars/67e56f0da5359_1.png'),
(76, 5, 'images/cars/67e56f0da5d78_2.png'),
(77, 5, 'images/cars/67e56f0da64f6_3.png'),
(78, 6, 'images/cars/67e56f818d6d0_1.png'),
(79, 6, 'images/cars/67e56f818ddab_2.png'),
(80, 6, 'images/cars/67e56f818e644_3.png'),
(81, 7, 'images/cars/67e6b929ce5dc_4.png'),
(82, 7, 'images/cars/67e6b929cf739_3.png'),
(83, 7, 'images/cars/67e6b929d0019_2.png');

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
(58, 5, 0.00, 0.00),
(59, 5, 0.00, 0.00),
(60, 5, 0.00, 0.00),
(61, 5, 0.00, 0.00),
(62, 5, 0.00, 0.00),
(63, 5, 0.00, 0.00),
(64, 5, 0.00, 0.00),
(65, 6, 0.00, 0.00),
(66, 6, 0.00, 0.00),
(67, 6, 0.00, 0.00),
(68, 6, 0.00, 0.00),
(69, 6, 0.00, 0.00),
(70, 6, 0.00, 0.00),
(71, 6, 0.00, 0.00),
(72, 7, 0.00, 0.00),
(73, 7, 0.00, 0.00),
(74, 7, 0.00, 0.00),
(75, 7, 0.00, 0.00),
(76, 7, 0.00, 0.00),
(77, 7, 0.00, 0.00),
(78, 7, 0.00, 0.00);

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
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `customers`
--

INSERT INTO `customers` (`id`, `first_name`, `last_name`, `email`, `phone`, `created_at`, `updated_at`) VALUES
(1, 'John', 'Doe', 'john.doe@example.com', '555-1234', '2025-03-18 17:40:49', '2025-03-18 17:40:49'),
(2, 'Jane', 'Smith', 'jane.smith@example.com', '555-5678', '2025-03-18 17:40:49', '2025-03-18 17:40:49');

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `cars`
--
ALTER TABLE `cars`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `car_images`
--
ALTER TABLE `car_images`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=84;

--
-- AUTO_INCREMENT for table `car_rental_rates`
--
ALTER TABLE `car_rental_rates`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=79;

--
-- AUTO_INCREMENT for table `customers`
--
ALTER TABLE `customers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

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
  ADD CONSTRAINT `bookings_ibfk_2` FOREIGN KEY (`car_id`) REFERENCES `cars` (`id`);

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

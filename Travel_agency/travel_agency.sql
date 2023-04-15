-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 15, 2023 at 06:07 PM
-- Server version: 10.4.27-MariaDB
-- PHP Version: 8.2.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `travel_agency`
--

DELIMITER $$
--
-- Procedures
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `check_availability` (IN `package_id` INT, IN `num_people` INT, OUT `available_capacity` INT)   BEGIN
    DECLARE maximum_capacity INT;
    DECLARE used_capacity INT;

    SELECT maximum_capacity INTO maximum_capacity FROM travel_packages WHERE package_id = package_id;

    SELECT SUM(no_of_guests) INTO used_capacity FROM reservations WHERE package_id = package_id AND status = 'confirmed';

    SET available_capacity = maximum_capacity - used_capacity;

    IF available_capacity >= num_people THEN
        SELECT 'Available' AS message;
    ELSE
        SELECT CONCAT('Not available. Only ', available_capacity, ' seats are available.') AS message;
    END IF;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `make_reservation` (IN `p_customer_id` INT, IN `p_package_id` INT, IN `p_num_people` INT, OUT `p_reservation_id` INT, OUT `p_message` VARCHAR(100))   BEGIN
    DECLARE v_available_capacity INT;

    CALL check_availability(p_package_id, p_num_people, v_available_capacity);

    IF v_available_capacity < p_num_people THEN
        SET p_message = CONCAT('Not available. Only ', v_available_capacity, ' seats are available.');
    ELSE

        INSERT INTO reservations (customer_id, package_id, reservation_date, status, no_of_guests)
        VALUES (p_customer_id, p_package_id, NOW(), 'pending', p_num_people);
        SET p_reservation_id = LAST_INSERT_ID();
        SET p_message = 'Reservation successful.';

        CALL update_available_capacity;
    END IF;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `update_available_capacity` (IN `p_package_id` INT)   BEGIN
    UPDATE travel_packages SET available_capacity = maximum_capacity - (
        SELECT SUM(no_of_guests) FROM reservations WHERE package_id = p_package_id AND status = 'confirmed'
    ) WHERE package_id = p_package_id;
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `bills`
--

CREATE TABLE `bills` (
  `invoice_id` int(11) NOT NULL,
  `customer_id` int(5) UNSIGNED ZEROFILL NOT NULL,
  `payable_amount` varchar(250) NOT NULL,
  `customer_name` varchar(30) NOT NULL,
  `expiry_month` int(11) NOT NULL,
  `expiry_year` int(11) NOT NULL,
  `cvv` int(3) NOT NULL,
  `card_type` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `customers`
--

CREATE TABLE `customers` (
  `customer_id` int(5) UNSIGNED ZEROFILL NOT NULL,
  `name` varchar(30) NOT NULL,
  `email` varchar(50) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `password` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `customers`
--

INSERT INTO `customers` (`customer_id`, `name`, `email`, `phone`, `password`) VALUES
(00002, 'John Doe', 'johndoe@gmail.com', '01234567', 'lalala'),
(00003, 'Harry Potter', 'harry@hogwarts.com', '044718590', 'alohomora'),
(00004, 'Jane Doe', 'janedoe@gmail.com', '05671234', 'nanana'),
(00005, 'john smith', 'johnsmith@gmail.com', '123456789', 'lalala'),
(00006, 'ballistic', 'ballistic@gmail.com', '1234567', '12345'),
(00007, 'bill', 'bill@gmail.com', '85637864982', '12345');

-- --------------------------------------------------------

--
-- Table structure for table `destinations`
--

CREATE TABLE `destinations` (
  `name` varchar(50) NOT NULL,
  `accommodation` varchar(50) NOT NULL,
  `location` varchar(150) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `destinations`
--

INSERT INTO `destinations` (`name`, `accommodation`, `location`) VALUES
('Bandarban', 'Hillside Resort Bandarban', 'Sangu River, Bandarban 4600, Bangladesh'),
('Cox\'s Bazar', 'Hotel Sea World', 'Laboni Beach Rd, Cox\'s Bazar 4700, Bangladesh'),
('Kuakata', 'Hotel South Haven', 'Kuakata Beach Road, Kuakata 8600, Bangladesh'),
('Rangamati', 'Hotel Green Hill', 'Main Road, Rangamati 4500, Bangladesh'),
('Srimangal', 'Grand Sultan Tea Resort & Golf', 'Srimongol, Sylhet 3210, Bangladesh'),
('Sundarbans', 'Pakhiralay', 'James Pargana, Khulna Division, Khulna 9201, Bangladesh'),
('Sylhet', 'Sylhet', 'Hazrat Shahjalal Rd, Sylhet 3100, Bangladesh');

-- --------------------------------------------------------

--
-- Table structure for table `reservations`
--

CREATE TABLE `reservations` (
  `reservation_id` int(7) UNSIGNED ZEROFILL NOT NULL,
  `customer_id` int(5) UNSIGNED ZEROFILL NOT NULL,
  `package_id` int(4) UNSIGNED ZEROFILL NOT NULL,
  `reservation_date` datetime NOT NULL,
  `status` varchar(10) NOT NULL,
  `no_of_guests` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `travel_packages`
--

CREATE TABLE `travel_packages` (
  `package_id` int(4) UNSIGNED ZEROFILL NOT NULL,
  `destination_name` varchar(50) NOT NULL,
  `package_name` varchar(50) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `maximum_capacity` int(11) NOT NULL,
  `available_capacity` int(11) NOT NULL,
  `departure_time` datetime DEFAULT NULL,
  `arrival_time` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `travel_packages`
--

INSERT INTO `travel_packages` (`package_id`, `destination_name`, `package_name`, `start_date`, `end_date`, `price`, `maximum_capacity`, `available_capacity`, `departure_time`, `arrival_time`) VALUES
(0001, 'Cox\'s Bazar', 'Beach Getaway', '2023-06-15', '2023-06-20', '50000.00', 60, 60, '2023-06-15 07:00:00', '2023-06-15 12:00:00'),
(0003, 'Sundarbans', 'Wildlife Safari', '2023-07-10', '2023-07-15', '40000.00', 60, 60, '2023-06-15 10:00:00', '2023-06-15 11:00:00'),
(0004, 'Bandarban', 'Hiking Adventure', '2023-08-05', '2023-08-10', '45000.00', 52, 52, '2023-07-10 01:00:00', '2023-07-10 07:00:00'),
(0005, 'Sylhet', 'Tea Plantation Tour', '2023-09-01', '2023-09-05', '35000.00', 40, 40, '2023-08-05 10:00:00', '2023-08-05 16:00:00'),
(0007, 'Rangamati', 'Lake Retreat', '2023-10-15', '2023-10-20', '40000.00', 38, 38, '2023-09-01 20:00:00', '2023-09-01 21:00:00'),
(0008, 'Srimangal', 'Tea Capital', '2023-11-10', '2023-11-15', '30000.00', 40, 40, '2023-11-10 07:00:00', '2023-11-10 13:00:00'),
(0009, 'Kuakata', 'Beachside Bliss', '2023-12-20', '2023-12-25', '50000.00', 52, 52, '2023-12-20 10:00:00', '2023-12-20 14:00:00');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `bills`
--
ALTER TABLE `bills`
  ADD PRIMARY KEY (`invoice_id`),
  ADD KEY `customer_id` (`customer_id`);

--
-- Indexes for table `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`customer_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `destinations`
--
ALTER TABLE `destinations`
  ADD PRIMARY KEY (`name`);

--
-- Indexes for table `reservations`
--
ALTER TABLE `reservations`
  ADD PRIMARY KEY (`reservation_id`),
  ADD KEY `customer_id` (`customer_id`),
  ADD KEY `package_id` (`package_id`);

--
-- Indexes for table `travel_packages`
--
ALTER TABLE `travel_packages`
  ADD PRIMARY KEY (`package_id`),
  ADD KEY `destination_name` (`destination_name`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `bills`
--
ALTER TABLE `bills`
  MODIFY `invoice_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `customers`
--
ALTER TABLE `customers`
  MODIFY `customer_id` int(5) UNSIGNED ZEROFILL NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `reservations`
--
ALTER TABLE `reservations`
  MODIFY `reservation_id` int(7) UNSIGNED ZEROFILL NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `travel_packages`
--
ALTER TABLE `travel_packages`
  MODIFY `package_id` int(4) UNSIGNED ZEROFILL NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `bills`
--
ALTER TABLE `bills`
  ADD CONSTRAINT `bills_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`customer_id`);

--
-- Constraints for table `reservations`
--
ALTER TABLE `reservations`
  ADD CONSTRAINT `reservations_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`customer_id`),
  ADD CONSTRAINT `reservations_ibfk_2` FOREIGN KEY (`package_id`) REFERENCES `travel_packages` (`package_id`);

--
-- Constraints for table `travel_packages`
--
ALTER TABLE `travel_packages`
  ADD CONSTRAINT `travel_packages_ibfk_1` FOREIGN KEY (`destination_name`) REFERENCES `destinations` (`name`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

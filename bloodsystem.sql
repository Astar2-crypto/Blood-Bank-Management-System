-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 03, 2026 at 10:57 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.1.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `bloodsystem`
--

-- --------------------------------------------------------

--
-- Table structure for table `hospital_requests`
--

CREATE TABLE `hospital_requests` (
  `id` int(11) NOT NULL,
  `hospital_name` varchar(150) NOT NULL,
  `blood_group` varchar(5) NOT NULL,
  `units_requested` int(11) NOT NULL,
  `status` enum('pending','approved','rejected') DEFAULT 'pending',
  `request_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `hospital_requests`
--

INSERT INTO `hospital_requests` (`id`, `hospital_name`, `blood_group`, `units_requested`, `status`, `request_date`) VALUES
(1, 'Mbagathi', 'A+', 1, 'approved', '2026-03-11 14:23:51'),
(2, 'Mbagathi hospital', 'A+', 1, 'approved', '2026-03-18 13:48:16'),
(3, 'KNH', 'A+', 2, 'approved', '2026-03-24 11:08:10'),
(4, 'Nairobi hospital', 'B+', 1, 'rejected', '2026-04-02 05:55:10'),
(5, 'Mbagathi hospital', 'O+', 2, 'approved', '2026-04-02 09:00:54'),
(6, ' Nairobi hospital', 'AB-', 3, 'pending', '2026-04-02 09:03:38');

-- --------------------------------------------------------

--
-- Table structure for table `inventory`
--

CREATE TABLE `inventory` (
  `id` int(11) NOT NULL,
  `blood_group` varchar(5) NOT NULL,
  `quantity` int(11) DEFAULT 0,
  `expiry_date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `inventory`
--

INSERT INTO `inventory` (`id`, `blood_group`, `quantity`, `expiry_date`) VALUES
(1, 'A+', 0, '2026-04-29'),
(2, 'A+', 0, '2026-03-24'),
(3, 'B+', 2, '2026-05-14'),
(4, 'B+', 3, '2026-01-30'),
(5, 'A+', 3, '2026-05-05'),
(6, 'B+', 10, '2026-04-03'),
(7, 'B+', 2, '2026-04-03'),
(8, 'O+', 0, '2026-04-03'),
(9, 'AB-', 1, '2026-05-09');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `role` enum('admin','donor','hospital') DEFAULT 'donor',
  `full_name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `blood_group` varchar(5) DEFAULT NULL,
  `age` int(11) DEFAULT NULL,
  `weight` decimal(5,2) DEFAULT NULL,
  `is_eligible` tinyint(1) DEFAULT 0,
  `security_question` varchar(150) DEFAULT NULL,
  `security_answer` varchar(255) DEFAULT NULL,
  `long_term_meds` enum('Yes','No') DEFAULT 'No',
  `blood_infection` enum('Yes','No') DEFAULT 'No',
  `tattoos` enum('Yes','No') DEFAULT 'No',
  `major_surgery` enum('Yes','No') DEFAULT 'No'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `role`, `full_name`, `email`, `password`, `blood_group`, `age`, `weight`, `is_eligible`, `security_question`, `security_answer`, `long_term_meds`, `blood_infection`, `tattoos`, `major_surgery`) VALUES
(1, 'donor', 'Sarah Sunda', 'sarahsunda@gmail.com', 'sarah', 'A+', 24, 60.00, 1, 'What is the name of your pet?', 'sarah', 'No', 'No', 'No', 'No'),
(2, 'admin', 'Esther Kemunto', 'wessyastar@gmail.com', 'wessy', 'A+', 23, 58.00, 1, 'What is your Nickname?', 'wessy', 'No', 'No', 'No', 'No'),
(3, 'donor', 'Jane Bwari', 'janebwari@gmail.com', 'jane', 'O+', 44, 54.80, 1, 'What is your Nickname?', 'jane', 'No', 'No', 'No', 'No'),
(8, 'donor', 'Susan Aiden', 'susanaiden@gmail.com', 'susan', 'O-', 12, 54.00, 0, 'What is your favorite food?', 'ugali', 'No', 'No', 'No', 'No'),
(9, 'donor', 'Sasha Fierce', 'sashafierce@gmail.com', 'sasha', 'O-', 56, 88.00, 1, 'What is your favorite food?', 'chapati', 'No', 'No', 'No', 'No'),
(11, 'donor', 'Felix Weru', 'felixweru@gmail.com', 'felix', 'O+', 50, 64.90, 1, 'What is your favorite food?', 'ugali', 'No', 'No', 'No', 'No'),
(12, 'donor', 'George Mburu', 'georgemburu@gmail.com', 'mburu', 'AB-', 56, 77.00, 1, 'Who is your Favorite musician?', 'okello', 'No', 'No', 'No', 'No'),
(14, 'donor', 'Abel Getanda', 'abelgetanda@gmail.com', 'abel', 'A+', 55, 66.80, 1, 'What is the name of your best friend?', 'Charles', 'No', 'No', 'No', 'No'),
(16, 'donor', 'Carol  Mwongeli', 'carolmwongeli@gmail.com', 'carol', 'O-', 45, 59.00, 1, 'What is your Nickname?', 'carol', 'No', 'No', 'No', 'No'),
(17, 'donor', 'Jon doe', 'jondoe@gmail.com', '1234', 'AB-', 78, 67.00, 0, 'What is your Nickname?', 'jon', 'No', 'No', 'No', 'No');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `hospital_requests`
--
ALTER TABLE `hospital_requests`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `inventory`
--
ALTER TABLE `inventory`
  ADD PRIMARY KEY (`id`);

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
-- AUTO_INCREMENT for table `hospital_requests`
--
ALTER TABLE `hospital_requests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `inventory`
--
ALTER TABLE `inventory`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

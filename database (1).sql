-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 28, 2024 at 06:56 PM
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
-- Database: `database`
--

-- --------------------------------------------------------

--
-- Table structure for table `agencies`
--

CREATE TABLE `agencies` (
  `id` int(11) NOT NULL,
  `login_id` int(11) NOT NULL,
  `email` text NOT NULL,
  `name` text DEFAULT NULL,
  `service_provider` text DEFAULT NULL,
  `area` text DEFAULT NULL,
  `contact_information` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `agencies`
--

INSERT INTO `agencies` (`id`, `login_id`, `email`, `name`, `service_provider`, `area`, `contact_information`) VALUES
(1, 23, 'harish@gmail.com', 'fvgbh', 'vgbhjn', 'gvbhjn', 'gvbhfvgbfghbj');

-- --------------------------------------------------------

--
-- Table structure for table `lawyer`
--

CREATE TABLE `lawyer` (
  `id` int(11) NOT NULL,
  `login_id` int(11) NOT NULL,
  `name` text DEFAULT NULL,
  `specialization` text DEFAULT NULL,
  `experience` int(11) DEFAULT NULL,
  `license_details` text DEFAULT NULL,
  `location_of_practice` text DEFAULT NULL,
  `email` text DEFAULT NULL,
  `contact_number` text DEFAULT NULL,
  `document_file` text DEFAULT NULL,
  `qr_code_file` text DEFAULT NULL,
  `upi_id` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `lawyer`
--

INSERT INTO `lawyer` (`id`, `login_id`, `name`, `specialization`, `experience`, `license_details`, `location_of_practice`, `email`, `contact_number`, `document_file`, `qr_code_file`, `upi_id`) VALUES
(1, 15, 'name', 'specialization', 0, 'license_details', 'location_of_practice', 'harish@gmail.com', NULL, NULL, NULL, NULL),
(2, 22, 'hello', 'specialization', 1, 'license_details', 'ngbhjn', 'kiran@gmail.com', 'fgvbh', 'images/66f8327499e0b_S2024026.jpg', 'images/66f831267789e_VigneshSignature.jpg', 'fvgbhjn');

-- --------------------------------------------------------

--
-- Table structure for table `location`
--

CREATE TABLE `location` (
  `id` int(11) NOT NULL,
  `login_id` int(11) NOT NULL,
  `longitude` text DEFAULT NULL,
  `latitude` text DEFAULT NULL,
  `is_online` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `location`
--

INSERT INTO `location` (`id`, `login_id`, `longitude`, `latitude`, `is_online`) VALUES
(1, 21, '78.3891539744141', '17.405716468736397', 1),
(2, 22, '78.30554375931463', '17.518209481821682', 1),
(3, 23, '78.30554375931463', '17.518209481821682', 1);

-- --------------------------------------------------------

--
-- Table structure for table `login`
--

CREATE TABLE `login` (
  `id` int(11) NOT NULL,
  `username` text NOT NULL,
  `password` text NOT NULL,
  `type` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `login`
--

INSERT INTO `login` (`id`, `username`, `password`, `type`) VALUES
(17, 'Vignesh', '$2y$10$Ibao1gU0A0kiJ2KvuQReyOcBo/e5sCLCSwwgtOwPO65VJp9WKt4bO', 'customer'),
(21, 'Uday', '$2y$10$f87tVao92/05SiuB3IQK8uYted8Ysx1SU1JyBS7vtO6mqQblFe1X.', 'customer'),
(22, 'Kiran', '$2y$10$5yJBGIBYtWTQYqW7kMPfZeJ1nRM/dPI5J21xqDlu/YliWvnNKOdpe', 'advocate'),
(23, 'Harish', '$2y$10$HD2gnWWrPzz7YdJyYf9Awup8zJ/RLqJICicIWj7BxVuq59vcMFreK', 'agencies');

-- --------------------------------------------------------

--
-- Table structure for table `problem_cases`
--

CREATE TABLE `problem_cases` (
  `id` int(11) NOT NULL,
  `login_id` int(11) NOT NULL,
  `name` text DEFAULT NULL,
  `contact_number` text DEFAULT NULL,
  `description` text DEFAULT NULL,
  `file_name` text DEFAULT NULL,
  `assigned_to` text DEFAULT NULL,
  `status` text NOT NULL DEFAULT 'NOT_ASSIGNED'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `problem_cases`
--

INSERT INTO `problem_cases` (`id`, `login_id`, `name`, `contact_number`, `description`, `file_name`, `assigned_to`, `status`) VALUES
(1, 21, 'fcgvbhj', 'vgbhjn', 'cfvgbh', 'images/66f80f2351990_image.png', '22', 'solved');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `login_id` int(11) NOT NULL,
  `email` text NOT NULL,
  `name` text DEFAULT NULL,
  `age` int(11) DEFAULT NULL,
  `gender` text DEFAULT NULL,
  `role` text DEFAULT NULL,
  `contact_number` text DEFAULT NULL,
  `file_name` text DEFAULT NULL,
  `upiId` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `login_id`, `email`, `name`, `age`, `gender`, `role`, `contact_number`, `file_name`, `upiId`) VALUES
(4, 17, 'vignesh@gmail.com', NULL, NULL, NULL, 'user', '789', NULL, NULL),
(6, 21, 'uday@gmail.com', 'Uday', 4, 'Male', 'user', '789', 'images/66f8282451ac5_DiplomaMemo.jpg', 'fcvgbhj');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `agencies`
--
ALTER TABLE `agencies`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `lawyer`
--
ALTER TABLE `lawyer`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `location`
--
ALTER TABLE `location`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `login`
--
ALTER TABLE `login`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `problem_cases`
--
ALTER TABLE `problem_cases`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `agencies`
--
ALTER TABLE `agencies`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `lawyer`
--
ALTER TABLE `lawyer`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `location`
--
ALTER TABLE `location`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `login`
--
ALTER TABLE `login`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `problem_cases`
--
ALTER TABLE `problem_cases`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

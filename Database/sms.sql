-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3308
-- Generation Time: Dec 13, 2025 at 03:45 PM
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
-- Database: `sms`
--

-- --------------------------------------------------------

--
-- Table structure for table `otp_tbl`
--

CREATE TABLE `otp_tbl` (
  `otp_id` int(20) NOT NULL,
  `u_id` int(20) NOT NULL,
  `otp_sts` tinyint(1) NOT NULL DEFAULT 0,
  `verification_token` int(6) NOT NULL,
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_tbl`
--

CREATE TABLE `user_tbl` (
  `u_id` int(200) NOT NULL,
  `u_name` varchar(200) NOT NULL,
  `u_email` varchar(200) NOT NULL,
  `username` varchar(450) NOT NULL,
  `password` varchar(450) NOT NULL,
  `role` varchar(450) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `update_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_tbl`
--

INSERT INTO `user_tbl` (`u_id`, `u_name`, `u_email`, `username`, `password`, `role`, `created_at`, `update_at`) VALUES
(1, '', '', 'Admin01', 'test01', 'admin', '2025-05-13 13:44:45', '2025-05-13 13:44:45');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `otp_tbl`
--
ALTER TABLE `otp_tbl`
  ADD PRIMARY KEY (`otp_id`),
  ADD KEY `u_id` (`u_id`);

--
-- Indexes for table `user_tbl`
--
ALTER TABLE `user_tbl`
  ADD PRIMARY KEY (`u_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `otp_tbl`
--
ALTER TABLE `otp_tbl`
  MODIFY `otp_id` int(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user_tbl`
--
ALTER TABLE `user_tbl`
  MODIFY `u_id` int(200) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `otp_tbl`
--
ALTER TABLE `otp_tbl`
  ADD CONSTRAINT `u_id` FOREIGN KEY (`u_id`) REFERENCES `user_tbl` (`u_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
